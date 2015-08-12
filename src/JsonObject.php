<?php
/**
 * @author @ct-jensschulze <jens.schulze@commercetools.de>
 */

namespace Commercetools\Commons;


use Commercetools\Commons\Json\Node;

class JsonObject implements \JsonSerializable
{
    use ContextTrait;

    /**
     * @var Node
     */
    protected $data;

    protected function __construct(Node $node = null, ContextInterface $context = null)
    {
        if (is_null($node)) {
            $node = Node::of();
        }
        $this->context = $context;
        $this->data = $node;
    }

    final public static function of(ContextInterface $context = null)
    {
        return new static(null, $context);
    }

    public static function ofNode(Node $node = null, ContextInterface $context = null)
    {
        return new static($node, $context);
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

    public function fieldTypeDefinition()
    {
        return [];
    }

    protected function fieldType($field)
    {
        $definitions = $this->fieldTypeDefinition();
        if (isset($definitions[$field])) {
            return $definitions[$field];
        }

        return null;
    }

    protected function isPrimitiveType($type)
    {
        $primitives = [
            'bool' => 'is_bool',
            'int' => 'is_int',
            'string' => 'is_string',
            'float' => 'is_float',
            'array' => 'is_array'
        ];
        return isset($primitives[$type]);
    }

    public function get($field)
    {
        $type = $this->fieldType($field);
        if (!$this->isPrimitiveType($type)) {
            return new $type($this->data->get($field), $this->context);
        }
        return $this->data->get($field);
    }

    public function set($field, $value)
    {
        if ($value instanceof JsonObject) {
            $value = $value->toArray();
        }
        $this->data->set($field, $value);

        return $this;
    }

    public function toArray()
    {
        return $this->data->toArray();
    }

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
