<?php

namespace Okapi\Aop\Core\Invocation;

trait AdviceChainAwareTrait
{
    /**
     * The advice chain.
     *
     * @var AdviceChain
     */
    private AdviceChain $adviceChain;

    /**
     * Set advice chain.
     *
     * @param AdviceChain $adviceChain
     *
     * @return void
     *
     * @internal
     */
    public function setAdviceChain(AdviceChain $adviceChain): void
    {
        $this->adviceChain = $adviceChain;
    }

    /**
     * Call next advice or target method.
     *
     * @param bool $allowRepeatedCalls
     *   <br>If {@see true}, the original method will be called again.<br>
     *   If {@see false}, the original method will be called only once and every
     *   subsequent call will return the same result.<br>
     *   Default: {@see false}<br>
     *   <b>WARNING: May cause unexpected behavior and side effects.</b>
     *
     * @return mixed
     */
    public function proceed(bool $allowRepeatedCalls = false): mixed
    {
        return $this->adviceChain->proceed($allowRepeatedCalls);
    }

    /**
     * Set result.
     *
     * @param mixed $result
     *
     * @return void
     */
    public function setResult(mixed $result): void
    {
        $this->adviceChain->setResult($result);
    }
}
