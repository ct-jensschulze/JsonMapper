<?php

namespace Commercetools\Commons\Json;


class Node implements \JsonSerializable, \Iterator, \Countable, \ArrayAccess
{
    protected $name;
    protected $root;
    protected $parent;
    /**
     * @var \ArrayIterator
     */
    protected $data;
    protected $initialized = [];

    final private function __construct($name, $data = [], Node $root = null, Node $parent = null)
    {
        $this->name = $name;
        $this->root = is_null($root) ? $this : $root;
        $this->parent = $parent;
        $this->data = new \ArrayIterator($data);
    }

    public function __call($method, $arguments)
    {
        $action = substr($method, 0, 3);
        $field = lcfirst(substr($method, 3));

        switch ($action) {
            case 'get':
                return $this->get($field);
            case 'set':
                return $this->set($field, isset($arguments[0]) ? $arguments[0] : null);
        }
        throw new \BadMethodCallException();
    }

    public function set($field, $value)
    {
        $this->initialized[$field] = null;
        $this->data->offsetSet($field, $value);
        return $this;
    }

    public function get($field)
    {
        if (isset($this->initialized[$field])) {
            return $this->data[$field];
        }

        $children = null;
        if (isset($this->data[$field])) {
            $children = $this->data[$field];
        }
        $this->initialized[$field] = true;
        $this->data[$field] = static::createNodeObject($field, $children, $this->root, $this);

        return $this->data[$field];
    }

    /**
     * @param $field
     * @param $node
     * @param $context
     * @param Node $root
     * @param Node $parent
     * @return mixed
     */
    protected static function createNodeObject($field, $node, Node $root = null, Node $parent = null)
    {
        if ($node instanceof \stdClass) {
            return new Node($field, $node, $root, $parent);
        }
        if (is_array($node)) {
            return new NodeCollection($field, $node, $root, $parent);
        }
        return $node;
    }

    /**
     * @param $context
     * @return mixed
     */
    public static function of()
    {
        return static::createNodeObject('', new \stdClass());
    }

    final public static function ofData($data)
    {
        return static::createNodeObject('', $data);
    }

    public function toArray()
    {
        return (object)$this->data->getArrayCopy();
    }

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return $this->data->count();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return $this->data->offsetExists($offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return $this->data->current();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->data->next();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->data->key();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return $this->data->valid();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->data->rewind();
    }
}
