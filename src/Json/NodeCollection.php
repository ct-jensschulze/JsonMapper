<?php

namespace Commercetools\Commons\Json;

class NodeCollection extends Node
{
    /**
     * @return mixed
     */
    public static function of()
    {
        return static::createNodeObject('', []);
    }

    public function toArray()
    {
        return (array)$this->data->getArrayCopy();
    }

    public function getFirst()
    {
        $this->rewind();
        $firstKey = $this->key();
        return $this->get($firstKey);
    }
}
