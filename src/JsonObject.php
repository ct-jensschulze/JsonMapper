<?php
/**
 * @author @ct-jensschulze <jens.schulze@commercetools.de>
 */

namespace Commercetools\Commons;


use Commercetools\Commons\Json\Node;

class JsonObject implements JsonObjectInterface, ContextAwareInterface, \ArrayAccess
{
    const JSON_OBJECT_INTERFACE = 'Commercetools\Commons\JsonObjectInterface';
    const TYPE = 'type';
    const OPTIONAL = 'optional';
    const INITIALIZED = 'initialized';
    const DECORATOR = 'decorator';
    const ELEMENT_TYPE = 'elementType';

    use ContextTrait;

    /**
     * @var Node
     */
    protected $data;

    /**
     * @var bool[]
     */
    protected static $interfaces = [];

    public function __construct(Node $node = null, ContextInterface $context = null)
    {
        if (is_null($node)) {
            $node = Node::of();
        }
        $this->context = $context;
        $this->data = $node;
    }

    /**
     * @param $data
     * @param ContextInterface $context
     * @return mixed
     */
    public static function fromArray($data, $context = null)
    {
        return static::ofNode(Node::ofData($data), $context);
    }

    final public static function of(ContextInterface $context = null)
    {
        return new static(null, $context);
    }

    final public static function ofNode(Node $node = null, ContextInterface $context = null)
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

    public function fieldDefinitions()
    {
        return [];
    }

    protected function fieldDefinition($field)
    {
        $definitions = $this->fieldDefinitions();
        if (isset($definitions[$field])) {
            return $definitions[$field];
        }

        return null;
    }

    protected function fieldDefinitionValue($field, $key)
    {
        $definition = $this->fieldDefinition($field);
        if (isset($definition[$key])) {
            return $definition[$key];
        }

        return null;
    }

    protected function fieldType($field)
    {
        return $this->fieldDefinitionValue($field, static::TYPE);
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

    /**
     * @param $type
     * @param $interfaceName
     * @return bool
     * @internal
     */
    protected function hasInterface($type, $interfaceName)
    {
        $interfaceName = trim($interfaceName, '\\');
        $cacheKey = $interfaceName . '-' . $type;
        if (!isset(static::$interfaces[$cacheKey])) {
            $interface = false;
            if (
                $this->isPrimitiveType($type) === false
                && isset(class_implements($type)[$interfaceName])
            ) {
                $interface = true;
            }
            static::$interfaces[$cacheKey] = $interface;
        }
        return static::$interfaces[$cacheKey];
    }

    public function get($field)
    {
        $type = $this->fieldType($field);
        if (!$this->isPrimitiveType($type)) {
            if ($this->hasInterface($type, static::JSON_OBJECT_INTERFACE)) {
                /**
                 * @var JsonObjectInterface $type
                 */
                return $type::ofNode($this->data->get($field), $this->context);
            } else {
                return new $type($this->data->get($field));
            }
        }
        return $this->data->get($field);
    }

    public function set($field, $value)
    {
        if ($value instanceof JsonObjectInterface) {
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
        $this->data->offsetUnset($offset);
    }
}
