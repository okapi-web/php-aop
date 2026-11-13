# How PHP-AOP works

PHP-AOP extends CodeTransformer's class-loading and source-transformation pipeline.
CodeTransformer supplies the kernel lifecycle, dependency injection, transformer
management, stream filters, and cache infrastructure. PHP-AOP adds aspect matching,
class weaving, and advice execution.

These diagrams describe the current implementation. They use Mermaid, which GitHub
renders directly inside Markdown. This page separates initialization, class loading,
and method execution because they happen at different times.

## 1. Initialize the kernel

Call your kernel's `init()` before loading the classes you want to intercept.

```mermaid
flowchart TD
    Start["Application calls MyKernel::init()"] --> Exists{"Kernel instance exists?"}
    Exists -->|Yes| Kernel["Use the kernel instance"]
    Exists -->|No| DI["CodeTransformer: register dependency injection"]
    DI --> Decorate["AOP: provide specialized loader, processor, managers and cache services"]
    Decorate --> Create["Create the kernel through dependency injection"]
    Create --> Kernel
    Kernel --> Initialized{"Already initialized?"}
    Initialized -->|Yes| Repeat["Return, or throw if double initialization is forbidden"]
    Initialized -->|No| Configure["Collect aspects, transformers and options; run configureOptions()"]
    Configure --> Aspects["AOP: instantiate aspects and register their advice"]
    Aspects --> Services["CodeTransformer: register options, transformers and cache state"]
    Services --> Filters["CodeTransformer: register transformation and cached-source filters"]
    Filters --> Loader["Install the AOP-aware Composer class loader"]
    Loader --> Ready["Mark the kernel initialized"]
```

The specialization uses service decoration: AOP's class loader extends
CodeTransformer's loader, and `AspectProcessor` extends `TransformerProcessor`.
The aspect and transformer managers can use the application's custom dependency
injection callback to construct components.

Implementation: [AopKernel](../src/AopKernel.php), plus the inherited
`CodeTransformerKernel` lifecycle from the `okapi/code-transformer` dependency.

## 2. Load a class

The AOP loader first asks Composer for the source file. Internal framework classes
and excluded paths bypass matching and transformation.

```mermaid
flowchart TD
    Request["PHP requests a class"] --> Locate["Composer locates its source file"]
    Locate --> Found{"File found?"}
    Found -->|No| Missing["Return false to the autoload chain"]
    Found -->|Yes| Bypass{"Internal class or excluded path?"}
    Bypass -->|Yes| Original["Load original source"]
    Bypass -->|No| Cache{"Cache state reusable?"}
    Cache -->|Yes| CachedFile{"Cached source path available?"}
    CachedFile -->|No| Original
    CachedFile -->|Yes| Cached["Load cached source through the cached-source filter"]
    Cache -->|No| Match["Match AOP advice and CodeTransformer transformers"]
    Match --> Any{"Anything matched?"}
    Any -->|No| Original
    Any -->|Yes| Filter["Load through CodeTransformer's transformation stream filter"]
    Filter --> Transform["Run matched transformers first"]
    Transform --> Advice{"Advice matched and class eligible for weaving?"}
    Advice -->|Yes| Weave["AOP: rename implementation and generate woven class"]
    Advice -->|No| Save
    Weave --> Save["Save changed source and any woven file; update cache state"]
    Save --> Load["PHP loads the resulting source"]
```

Cache reuse depends on the configured mode:

| Mode | Behavior |
| --- | --- |
| Debug enabled | Bypass cache reuse and match again. |
| Development | Reuse existing cache state only when `isFresh()` succeeds. |
| Production | Trust existing cache state without checking source freshness. |

For a reusable state, the loader either returns the original source path or loads
the recorded cache path through a stream filter. When rebuilding, processing can
record woven, transformed-only, or no-transformation cache state. A class with no
matching advice or transformers returns its original path immediately.

When a class is woven, the generated relationship is:

```php
// Renamed implementation, retaining its original parent if it had one:
class MyClass__AopProxied extends OriginalParent { /* Original implementation */ }

// Generated class under the name application code already uses:
class MyClass extends MyClass__AopProxied { /* Intercepted method wrappers */ }
```

For a class without an original parent, the first declaration has no `extends`
clause. The woven class extends the renamed implementation. Application code keeps
using `MyClass`; it does not switch to a separate wrapper object. Source rewriting
and the stream filters preserve source-file locations for debugging.

Implementation: [ClassLoader](../src/Core/AutoloadInterceptor/ClassLoader.php),
[AspectProcessor](../src/Core/Processor/AspectProcessor.php),
[ProxiedClassModifier](../src/Core/Transform/ProxiedClassModifier.php), and
[WovenClassBuilder](../src/Core/Transform/WovenClassBuilder.php).

## 3. Invoke an intercepted method

Class loading prepares the wrappers. Each subsequent call to an intercepted method
uses the registered advice; it does not repeat source matching and weaving.

```mermaid
flowchart TD
    Call["Application calls an intercepted method"] --> Wrapper["Generated method delegates to Interceptor"]
    Wrapper --> Before["Run Before advice; carry forward argument changes"]
    Before --> HasAround{"Around advice registered?"}
    HasAround -->|No| Direct["Invoke the renamed implementation's method"]
    HasAround -->|Yes| Around
    subgraph Chain["Inside the Around advice chain"]
        Around["Enter Around advice"] --> Needed{"Chain reaches original without an established result?"}
        Needed -->|Yes| Original["Invoke original inside the chain, possibly through proceed()"]
        Original --> Resume["Resume Around advice and resolve the final chain result"]
        Needed -->|No| Resume
    end
    Direct --> After["Run After advice with the current result"]
    Resume --> After
    After --> Result["Return the final result to the caller"]
```

This is the normal successful path. An exception interrupts it; `After` is not a
`finally` handler. Missing advice groups are skipped. The configured advice order
is applied within the Before, Around, After, and AfterThrowing groups.

If an original target call throws a `Throwable`, the interceptor runs the matching
`AfterThrowing` advice in order and rethrows the same object. This happens inside
the original-method callback, before control returns to any surrounding Around
advice. Successful calls and failures originating in other advice skip this group.
AfterThrowing invocations expose `getThrowable()` and invocation context, without
`proceed()` or result replacement. If this advice itself throws, its throwable
propagates immediately and the remaining failure advice does not run.

Each AfterThrowing attribute can restrict execution with an `exception` type
filter. Matching includes subclasses; the default `Throwable`
filter accepts both exceptions and errors. The filter is validated when the advice
attribute is instantiated, and is applied again on each failing target call.

Join points and cached metadata identify each advice declaration by its aspect,
method, and attribute index. Repeated attributes retain their own method filters
and execution order. The cache hash includes the declaration format version so
older method-name-only caches are rebuilt, including caches of explicit aspects.

An Around invocation's `proceed()` advances the chain. The original method can run
inside that call; the Around advice then resumes and can modify the result. The
chain also continues
after an advice returns; merely omitting `proceed()` is not sufficient to suppress
the original call. Supplying a result can avoid the original call. The chain
normally reuses an established result; `proceed(true)` permits repeated original
calls, including from After advice. An explicit `setResult(null)` distinguishes a
replacement null result from an advice that returns no replacement value.

Implementation: [Interceptor](../src/Core/Intercept/Interceptor.php),
[AdviceChain](../src/Core/Invocation/AdviceChain.php), and
[MethodInvocation](../src/Invocation/MethodInvocation.php).
