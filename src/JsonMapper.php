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
        if (is_null($class)) {
            $class = '\Commercetools\Commons\Json\Node';
        }
        return forward_static_call([$class, 'ofData'], json_decode($data));
    }
}
