<?php
/**
 * @author @ct-jensschulze <jens.schulze@commercetools.de>
 */

namespace Commercetools\Commons;

/**
 * @package Commercetools\Commons
 */
trait ContextTrait
{
    /**
     * @var ContextInterface
     */
    protected $context;

    /**
     * @return ContextInterface
     */
    public function getContext()
    {
        if (is_null($this->context)) {
            $this->context = new Context();
        }
        return $this->context;
    }

    /**
     * @param ContextInterface $context
     * @return $this
     */
    public function setContext(ContextInterface $context = null)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @param ContextInterface $context
     * @return $this
     */
    public function setContextIfNull(ContextInterface $context = null)
    {
        if (is_null($this->context)) {
            $this->setContext($context);
        }

        return $this;
    }
}
