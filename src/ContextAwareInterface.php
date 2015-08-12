<?php
/**
 * @author @ct-jensschulze <jens.schulze@commercetools.de>
 */

namespace Commercetools\Commons;


interface ContextAwareInterface
{
    /**
     * @return ContextInterface
     */
    public function getContext();

    /**
     * @param ContextInterface
     * @return mixed
     */
    public function setContext(ContextInterface $context = null);

    /**
     * @param ContextInterface
     * @return mixed
     */
    public function setContextIfNull(ContextInterface $context = null);
}
