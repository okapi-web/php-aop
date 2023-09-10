<!--suppress HtmlDeprecatedAttribute -->
<h1 align="center">PHP AOP</h1>

<!-- Main Badges -->
<p align="center">
  <!-- License: MIT -->
  <a href="https://opensource.org/licenses/MIT" target="_blank">
    <img 
      alt="License: MIT" 
      src="https://img.shields.io/badge/License-MIT-9C0000.svg?labelColor=ebdbb2&style=flat&logo=data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNCIgaGVpZ2h0PSIxNCI+PHBhdGggdmVjdG9yLWVmZmVjdD0ibm9uLXNjYWxpbmctc3Ryb2tlIiBkPSJNMCAyLjk5NWgxLjI4djguMDFIMHpNMi41NCAzaDEuMjh2NS4zNEgyLjU0em0yLjU1LS4wMDVoMS4yOHY4LjAxSDUuMDl6bTIuNTQuMDA3aDEuMjh2MS4zMzZINy42M3oiIGZpbGw9IiM5YzAwMDAiLz48cGF0aCB2ZWN0b3ItZWZmZWN0PSJub24tc2NhbGluZy1zdHJva2UiIGQ9Ik03LjYzIDUuNjZoMS4yOFYxMUg3LjYzeiIgZmlsbD0iIzdjN2Q3ZSIvPjxwYXRoIHZlY3Rvci1lZmZlY3Q9Im5vbi1zY2FsaW5nLXN0cm9rZSIgZD0iTTEwLjE3NyAzLjAwMmgzLjgyNnYxLjMzNmgtMy44MjZ6bS4wMDMgMi42NThoMS4yOFYxMWgtMS4yOHoiIGZpbGw9IiM5YzAwMDAiLz48L3N2Zz4="
    >
  </a>

  <!-- Twitter: @WalterWoshid -->
  <a href="https://twitter.com/WalterWoshid" target="_blank">
    <img 
      alt="Twitter: @WalterWoshid" 
      src="https://img.shields.io/badge/@WalterWoshid-Twitter?labelColor=ebdbb2&style=flat&logo=twitter&logoColor=458588&color=458588&label=Twitter"
    >
  </a>

  <!-- PHP: >=8.1 -->
  <a href="https://www.php.net" target="_blank">
    <img 
      alt="PHP: >=8.1" 
      src="https://img.shields.io/badge/PHP->=8.1-4C5789.svg?labelColor=ebdbb2&style=flat&logo=php&logoColor=4C5789"
    > 
  </a>

  <!-- Packagist -->
  <a href="https://packagist.org/packages/okapi/aop" target="_blank">
    <img 
      alt="Packagist" 
      src="https://img.shields.io/packagist/v/okapi/aop?label=Packagist&labelColor=ebdbb2&style=flat&color=fe8019&logo=packagist"
    >
  </a>

  <!-- Build -->
  <!--suppress HtmlUnknownTarget -->
  <a href="../../actions/workflows/tests.yml" target="_blank">
    <img 
      alt="Build" src="https://img.shields.io/github/actions/workflow/status/okapi-web/php-aop/tests.yml?label=Build&labelColor=ebdbb2&style=flat&logo=data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgdmlld0JveD0iMCAwIDUxMiA1MTIiIGhlaWdodD0iMTYiPjxwYXRoIGZpbGw9IiM2YWFiMjAiIGQ9Ik0zMy45MTQgNDEzLjYxMmgxNDkuNTV2MjcuNTk1SDI3LjQ5NGMtMjYuMzQ4IDAtMzQuMTM2LTEzLjE5NC0yMS43MjktMzQuMzFMMTM3LjkxIDE4Ny43NTNWNjEuOTc1aC0yNi4wNzVjLTE5LjUwNCAwLTE5LjUwNC0yNy41OTUgMC0yNy41OTVoMTg5LjkzYzE5LjUwNSAwIDE5LjUwNSAyNy41OTUgMCAyNy41OTVIMjc1LjY5djEzMi44MjhoLTI3Ljk2M1Y2MS45NzVoLTgxLjg1NHYxMzIuODI4TDMzLjkxNCA0MTMuNjEyem0xMzUuNi0xNjkuMTg3TDg0LjY5MiAzODYuNTc0aDcwLjYwMWwxMDQuMzc1LTExMi45MDctMTUuNTgyLTI5LjI0MmgtNzQuNTd6bTE0NS45OTYgOS43ODNMMjA5LjUgMzY3LjUwNmwxMDYuMDEgMTEwLjI4NiAzMy41MzgtMzMuNTM4LTgwLjY1LTc2Ljc0OCA4MC42NS03OS43Ni0zMy41MzgtMzMuNTM4em01Ni45NDMgMzMuNTM3IDgwLjY1IDc5Ljc2LTgwLjY1IDc2Ljc1IDMzLjUzOCAzMy41MzdMNTEyIDM2Ny41MDYgNDA1Ljk5IDI1NC4yMDhsLTMzLjUzNyAzMy41Mzd6Ii8+PC9zdmc+"
    >
  </a>
</p>

<!-- Coverage -->
<p align="center">
  <!-- Coverage - PHP 8.1 -->
  <a href="https://app.codecov.io/gh/okapi-web/php-aop/flags" target="_blank">
    <img 
      alt="Coverage - PHP 8.1" 
      src="https://img.shields.io/codecov/c/github/okapi-web/php-aop?flag=os-ubuntu-latest_php-8.1&label=Coverage - PHP 8.1&labelColor=ebdbb2&style=flat&logo=codecov&logoColor=FFC107&color=FFC107"
    />
  </a>

  <!-- Coverage - PHP 8.2 -->
  <a href="https://app.codecov.io/gh/okapi-web/php-aop/flags" target="_blank">
    <img 
      alt="Coverage - PHP 8.2" 
      src="https://img.shields.io/codecov/c/github/okapi-web/php-aop?flag=os-ubuntu-latest_php-8.2&label=Coverage - PHP 8.2&labelColor=ebdbb2&style=flat&logo=codecov&logoColor=FFC107&color=FFC107"
    />
  </a>
</p>

<h2 align="center">
  PHP AOP is a PHP library that provides a powerful Aspect Oriented Programming 
  (AOP) implementation for PHP.
</h2>



## Installation

```shell
composer require okapi/aop
```



# Usage

## 📖 List of contents

- [Terminology](#terminology)
- [Implicit Aspects](#implicit-aspects)
  - [Create a Kernel](#create-a-kernel)
  - [Create an Aspect](#create-an-aspect)
  - [Target Classes](#target-classes)
  - [Initialize the Kernel](#initialize-the-kernel)
  - [Result](#result)
- [Class-Level Explicit Aspects](#class-level-explicit-aspects)
  - [Create an Aspect](#create-an-aspect-1)
  - [Target Classes](#target-classes-1)
  - [Initialize the Kernel](#initialize-the-kernel-1)
  - [Result](#result-1)
- [Method-Level Explicit Aspects](#method-level-explicit-aspects)
  - [Create an Aspect](#create-an-aspect-2)
  - [Target Classes](#target-classes-2)
  - [Initialize the Kernel](#initialize-the-kernel-2)
  - [Result](#result-2)
- [Features](#features)
- [Limitations](#limitations)
- [How it works](#how-it-works)
- [Testing](#testing)
- [Contributing](#contributing)
- [Roadmap](#roadmap)



## Terminology

- **AOP**: Aspect Oriented Programming - A programming paradigm that aims to
  increase modularity by allowing the separation of cross-cutting concerns.

- **Aspect**: A class that implements the logic that you want to apply to your
  target classes. Aspects must be annotated with the `#[Aspect]` attribute.

- **Advice**: The logic that you want to apply to your target classes. Advice
  methods must be annotated with the `#[Before]`, `#[Around]` or `#[After]`
  attributes.

- **Join Point**: A point in the execution of your target classes where you can
  apply your advice. Join points are defined by the `#[Before]`, `#[Around]` or
  `#[After]` attributes.

- **Pointcut**: A set of join points where you can apply your advice. Pointcuts
  are defined by the `#[Pointcut]` attribute.

- **Weaving**: The process of applying your advice to your target classes.

- **Implicit Aspects**: The aspects are applied without any modification to the
  target classes. The aspect itself specifies the classes or methods it applies
  to.

- **Class-Level Explicit Aspects**: The aspects are applied by modifying the
  target classes, typically by adding the aspect as an attribute to the target
  class.

- **Method-Level Explicit Aspects**: The aspects are applied by modifying the
  target classes, typically by adding the aspect as an attribute to the target
  method.



## Implicit Aspects

<details open>
<summary>Click to expand</summary>

### Create a Kernel

```php
<?php

use Okapi\Aop\AopKernel;

// Extend from the "AopKernel" class
class MyKernel extends AopKernel
{
    // Define a list of aspects
    protected array $aspects = [
        DiscountAspect::class,
        PaymentProcessorAspect::class,
    ];   
}
```


### Create an Aspect

```php
// Discount Aspect

<?php

use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Attributes\After;
use Okapi\Aop\Invocation\AfterMethodInvocation;

// Aspects must be annotated with the "Aspect" attribute
#[Aspect]
class DiscountAspect
{
    // Annotate the methods that you want to intercept with
    // "Before", "Around" or "After" attributes
    #[After(
        // Use named arguments
        // You can also use Wildcards (see Okapi/Wildcards package)
        class: Product::class . '|' . Order::class,
        method: 'get(Price|Total)',
        // When using an eager wildcard you can need some of those combinable options:
        // bool onlyPublic: will weave only public methods
        // bool bypassParent: will weave only the matching Class and ignore parent classes hierarchy.
        // bool bypassTraits: will weave only methods defining in Class, ignore those defined in Trait.
    )]
    public function applyDiscount(AfterMethodInvocation $invocation): void
    {
        // Get the subject of the invocation
        // The subject is the object class that contains the method
        // that is being intercepted
        $subject = $invocation->getSubject();
        
        $productDiscount = 0.1;
        $orderDiscount   = 0.2;
        
        if ($subject instanceof Product) {
            // Get the result of the original method
            $oldPrice = $invocation->proceed();
            $newPrice = $oldPrice - ($oldPrice * $productDiscount);
            
            // Set the new result
            $invocation->setResult($newPrice);
        }
        
        if ($subject instanceof Order) {
            $oldTotal = $invocation->proceed();
            $newTotal = $oldTotal - ($oldTotal * $orderDiscount);
            
            $invocation->setResult($newTotal);
        }
    }
}
```

```php
// PaymentProcessor Aspect

<?php

use InvalidArgumentException;
use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Around;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Attributes\Before;
use Okapi\Aop\Invocation\AroundMethodInvocation;
use Okapi\Aop\Invocation\AfterMethodInvocation;
use Okapi\Aop\Invocation\BeforeMethodInvocation;

#[Aspect]
class PaymentProcessorAspect
{
    #[Before(
        class: PaymentProcessor::class,
        method: 'processPayment',
    )]
    public function checkPaymentAmount(BeforeMethodInvocation $invocation): void
    {
        $payment = $invocation->getArgument('amount');
        
        if ($payment < 0) {
            throw new InvalidArgumentException('Invalid payment amount');
        }
    }
    
    #[Around(
        class: PaymentProcessor::class,
        method: 'processPayment',
    )]
    public function logPayment(AroundMethodInvocation $invocation): void
    {
        $startTime = microtime(true);
        
        // Proceed with the original method
        $invocation->proceed();
        
        $endTime     = microtime(true);
        $elapsedTime = $endTime - $startTime;
        
        $amount = $invocation->getArgument('amount');
        
        $logMessage = sprintf(
            'Payment processed for amount $%.2f in %.2f seconds',
            $amount,
            $elapsedTime,
        );
        
        // Singleton instance of a logger
        $logger = Logger::getInstance();
        $logger->log($logMessage);
    }
    
    #[After(
        class: PaymentProcessor::class,
        method: 'processPayment',
    )]
    public function sendEmailNotification(AfterMethodInvocation $invocation): void
    {
        // Proceed with the original method
        $result = $invocation->proceed();
        $amount = $invocation->getArgument('amount');
        
        $message = sprintf(
            'Payment processed for amount $%.2f',
            $amount,
        );
        if ($result === true) {
            $message .= ' - Payment successful';
        } else {
            $message .= ' - Payment failed';
        }
        
        // Singleton instance of an email queue
        $mailQueue = MailQueue::getInstance();
        $mailQueue->addMail($message);
    }
}
```


### Target Classes

```php
// Product

<?php

class Product
{
    private float $price;
    
    public function getPrice(): float
    {
        return $this->price;
    }
}
```

```php
// Order

<?php

class Order
{
    private float $total = 500.00;
    
    public function getTotal(): float
    {
        return $this->total;
    }
}
```

```php
// PaymentProcessor

<?php

class PaymentProcessor
{
    public function processPayment(float $amount): bool
    {
        // Process payment
        
        return true;
    }
}
```


### Initialize the Kernel

```php
// Initialize the kernel early in the application lifecycle
// Preferably after the autoloader is registered

<?php

use MyKernel;

require_once __DIR__ . '/vendor/autoload.php';

// Initialize the AOP Kernel
$kernel = MyKernel::init();
```


### Result

```php
<?php

// Just use your classes as usual

$product = new Product();

// Before AOP: 100.00
// After AOP: 90.00
$productPrice = $product->getPrice();


$order = new Order();

// Before AOP: 500.00
// After AOP: 400.00
$orderTotal = $order->getTotal();



$paymentProcessor = new PaymentProcessor();

// Invalid payment amount
$amount = -50.00;

// Before AOP: true
// After AOP: InvalidArgumentException
$paymentProcessor->processPayment($amount);


// Valid payment amount
$amount = 100.00;

// Value: true
$paymentProcessor->processPayment($amount);


$logger   = Logger::getInstance();
$logs     = $logger->getLogs();

// Value: Payment processed for amount $100.00 in 0.00 seconds
$firstLog = $logs[0]; 


$mailQueue = MailQueue::getInstance();
$mails     = $mailQueue->getMails();

// Value: Payment processed for amount $100.00 - Payment successful
$firstMail = $mails[0];
```

</details>


## Class-Level Explicit Aspects

<details>
<summary>Click to expand</summary>

Adding the custom Aspect to the Kernel is not required for class-level explicit
aspects as they are registered automatically at runtime.

### Create an Aspect

```php
// Logging Aspect

<?php

use Attribute;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Attributes\Before;
use Okapi\Aop\Invocation\BeforeMethodInvocation;

// Class-Level Explicit Aspects must be annotated with the "Aspect" attribute
// and the "Attribute" attribute
#[Attribute]
#[Aspect]
class LoggingAspect
{
    // The "class" argument is not required
    // The "method" argument is optional
    //   Without the argument, the aspect will be applied to all methods
    //   With the argument, the aspect will be applied to the specified method
    #[Before]
    public function logAllMethods(BeforeMethodInvocation $invocation): void
    {
        $methodName = $invocation->getMethodName();
        
        $logMessage = sprintf(
            "Method '%s' executed.",
            $methodName,
        );
        
        $logger = Logger::getInstance();
        $logger->log($logMessage);
    }
    
    #[Before(
        method: 'updateInventory',
    )]
    public function logUpdateInventory(BeforeMethodInvocation $invocation): void
    {
        $methodName = $invocation->getMethodName();

        $logMessage = sprintf(
            "Method '%s' executed.",
            $methodName,
        );

        $logger = Logger::getInstance();
        $logger->log($logMessage);
    }
}
```


### Target Classes

```php
// Inventory Tracker

<?php

// Custom Class-Level Explicit Aspect added to the class
#[LoggingAspect]
class InventoryTracker
{
    private array $inventory = [];
    
    public function updateInventory(int $productId, int $quantity): void
    {
         $this->inventory[$productId] = $quantity;
    }
    
    public function checkInventory(int $productId): int
    {
        return $this->inventory[$productId] ?? 0;
    }
}
```


### Initialize the Kernel

```php
// Initialize the kernel early in the application lifecycle
// Preferably after the autoloader is registered

// The kernel must still be initialized, even if it has no Aspects

<?php

use MyKernel;

require_once __DIR__ . '/vendor/autoload.php';

// Initialize the AOP Kernel
$kernel = MyKernel::init();
```


### Result

```php
<?php

// Just use your classes as usual

$inventoryTracker = new InventoryTracker();
$inventoryTracker->updateInventory(1, 100);
$inventoryTracker->updateInventory(2, 200);

$countProduct1 = $inventoryTracker->checkInventory(1);
$countProduct2 = $inventoryTracker->checkInventory(2);



$logger = Logger::getInstance();

// Value:
//   Method 'updateInventory' executed. (4 times)
//   Method 'checkInventory' executed. (2 times)
$logs = $logger->getLogs();
```

</details>


## Method-Level Explicit Aspects

<details>
<summary>Click to expand</summary>

Adding the custom Aspect to the Kernel is not required for method-level explicit
aspects as they are registered automatically at runtime.

### Create an Aspect

```php
// Performance Aspect

<?php

use Attribute;
use Okapi\Aop\Attributes\Around;
use Okapi\Aop\Invocation\AroundMethodInvocation;
use Okapi\Aop\Attributes\Aspect;

// Method-Level Explicit Aspects must be annotated with the "Aspect" attribute
// and the "Attribute" attribute
#[Attribute]
#[Aspect]
class PerformanceAspect
{
    // The "class" argument is not required
    // The "method" argument is optional
    //   Without the argument, the aspect will be applied to all methods
    //   With the argument, the aspect will be applied to the specified method
    #[Around]
    public function measure(AroundMethodInvocation $invocation): void
    {
        $start = microtime(true);
        $invocation->proceed();
        $end = microtime(true);

        $executionTime = $end - $start;

        $class  = $invocation->getClassName();
        $method = $invocation->getMethodName();

        $logMessage = sprintf(
            "Method %s::%s executed in %.2f seconds.",
            $class,
            $method,
            $executionTime,
        );

        $logger = Logger::getInstance();
        $logger->log($logMessage);
    }
}
```


### Target Classes

```php
// Customer Service

<?php

class CustomerService
{
    #[PerformanceAspect]
    public function createCustomer(): void
    {
        // Logic to create a customer
    }
}
```


### Initialize the Kernel

```php
// Initialize the kernel early in the application lifecycle
// Preferably after the autoloader is registered

// The kernel must still be initialized, even if it has no Aspects

<?php

use MyKernel;

require_once __DIR__ . '/vendor/autoload.php';

// Initialize the AOP Kernel
$kernel = MyKernel::init();
```


### Result

```php
<?php

// Just use your classes as usual

$customerService = new CustomerService();
$customerService->createCustomer();



$logger = Logger::getInstance();
$logs   = $logger->getLogs();

// Value: Method CustomerService::createCustomer executed in 0.01 seconds.
$firstLog = $logs[0];
```

</details>



# Features

- **Advice types:** "Before", "Around" and "After"

- Intercept "private" and "protected" methods
  (Will show errors in IDEs)

- Access "private" and "protected" properties and methods of the subject
  (Will show errors in IDEs)

- Intercept "final" methods and classes

- Use Transformers from the "Okapi/Code-Transformer" package in your Kernel
  to modify and transform the source code of a loaded PHP class
  (See "Okapi/Code-Transformer" package for more information)


# Limitations

- **Internal** "private" and "protected" methods cannot be intercepted



# How it works

- This package extends the "Okapi/Code-Transformer" package with Dependency
  Injection and AOP features

- The `AopKernel` registers multiple services

  - The `TransformerManager` service stores the list of aspects and their 
    configuration
  
  - The `CacheStateManager` service manages the cache state
  
  - The `StreamFilter` service registers a
    [PHP Stream Filter](https://www.php.net/manual/wrappers.php.php#wrappers.php.filter)
    which allows to modify the source code before it is loaded by PHP
  
  - The `AutoloadInterceptor` service overloads the Composer autoloader,
    which handles the loading of classes


## General workflow when a class is loaded

- The `AutoloadInterceptor` service intercepts the loading of a class

- The `AspectMatcher` matches the class and method names with the list of
  aspects and their configuration

- If the class and method names match an aspect, query the cache state to see
  if the source code is already cached

  - Check if the cache is valid:
    - Modification time of the caching process is less than the modification
      time of the source file or the aspect file
    - Check if the cache file, the source file and the aspect file exist

  - If the cache is valid, load the proxied class from the cache
  - If not, return a stream filter path to the `AutoloadInterceptor` service

- The `StreamFilter` modifies the source code by applying the aspects
  - Convert the original source code to a proxied class 
    (MyClass -> MyClass__AopProxied)
  - The proxied class should have the same amount of lines as the original
    class (because the debugger will point to the original class)
  - The proxied class extends a woven class which contains the logic of applying
    the aspects
  - The woven class will be included at the bottom of the proxied class
  - The woven class will also be cached



## Testing
- Run `composer run-script test`<br>
  or
- Run `composer run-script test-coverage`


## Contributing

- To contribute to this project, fire up an aspect in any application
  that works or has 100% working tests, and match every class and method with 
  '*' with any advice type.
- If the application throws an error, then it's a bug.
- Example:
```php
<?php

use Okapi\Aop\Attributes\After;
use Okapi\Aop\Attributes\Aspect;
use Okapi\Aop\Invocation\AfterMethodInvocation;

#[Aspect]
class EverythingAspect
{
    #[After(
        class: '*',
        method: '*',
    )]
    public function everything(AfterMethodInvocation $invocation): void
    {
        echo $invocation->getClassName() . "\n";
        echo $invocation->getMethodName() . "\n";
    }
}
```



## Roadmap

See [Roadmap](https://github.com/okapi-web/php-aop/issues/9) for more details.



## Show your support

Give a ⭐ if this project helped you!



## 🙏 Thanks

- Big thanks to [lisachenko](https://github.com/lisachenko) for their pioneering
  work on the [Go! Aspect-Oriented Framework for PHP](https://github.com/goaop/framework).
  This project drew inspiration from their innovative approach and served as a
  foundation for this project.



## 📝 License

Copyright © 2023 [Valentin Wotschel](https://github.com/WalterWoshid).<br>
This project is [MIT](https://opensource.org/licenses/MIT) licensed.
