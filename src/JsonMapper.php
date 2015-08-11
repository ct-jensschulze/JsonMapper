<?php

namespace Commercetools\Commons;

use Commercetools\Commons\Json\Node;

class JsonMapper
{
    /**
     * @param string $data
     * @return Node
     */
    public function map($data, $class = null)
    {
        $node = Node::ofData(json_decode($data));
        if (is_null($class)) {
            $class = '\Commercetools\Commons\JsonObject';
        }
        return forward_static_call([$class, 'ofNode'], $node);
    }
}
