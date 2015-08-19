<?php
/**
 * @author @ct-jensschulze <jens.schulze@commercetools.de>
 */

namespace Commercetools\Commons;


use Commercetools\Commons\Json\NodeCollection;

class Collection extends JsonObject
{
    protected $type;

    public function __construct(NodeCollection $node = null, ContextInterface $context = null)
    {
        if (is_null($node)) {
            $node = NodeCollection::of();
        }
        parent::__construct($node, $context);
    }

    protected function fieldType($field)
    {
        return $this->type;
    }
}
