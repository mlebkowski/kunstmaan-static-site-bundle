<?php

namespace Nassau\KunstmaanStaticSiteBundle\Service\Generator;

class GeneratorRepository implements \ArrayAccess
{
    private $storage = [];

    /**
     * @param array|\Traversable $items
     */
    public function __construct($items = [])
    {
        if (false === $items instanceof \Traversable && false === is_array($items)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected array or \Traversable instance, %s given',
                is_object($items) ? get_class($items) : gettype($items)
            ));
        }

        foreach ($items as $key => $item) {
            $this->offsetSet($key, $item);
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->storage[$offset]);
    }

    /**
     * @param mixed $offset
     * @return RouteParametersGenerator
     */
    public function offsetGet($offset)
    {
        if (false === isset($this->storage[$offset])) {
            throw new \InvalidArgumentException('There is no generator named: ' . $offset);
        }

        return $this->storage[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (false === $value instanceof RouteParametersGenerator) {
            throw new \InvalidArgumentException(sprintf(
                'Expected %s instance, %s given',
                RouteParametersGenerator::class,
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }

        $this->storage[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->storage[$offset]);
    }
}
