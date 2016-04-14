<?php

namespace PsaNdp\MappingBundle\Object;

/**
 * Class AbstractObject
 */
class AbstractObject implements \ArrayAccess
{
    const NDP_NEXT = 'NDP_NEXT';
    const NDP_PREVIOUS = 'NDP_PREVIOUS';
    const NDP_OK = 'NDP_OK';
    const NDP_READ_MORE = 'NDP_READ_MORE';
    const NDP_CLOSE = 'NDP_CLOSE';
    const NDP_FROM = 'NDP_FROM';
    const NDP_CHANGE_VEHICLE = 'NDP_CHANGE_VEHICLE';

    /**
     * @var array $mapping
     */
    protected $mapping = array();

    /**
     * @var array
     */
    protected $overrideMapping = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->mapping = array_merge($this->mapping, $this->overrideMapping);
    }

    /**
     * Set data from array
     *
     * @param mixed $data
     *
     * @return $this
     */
    public function setDataFromArray($data)
    {
        foreach ($data as $key => $value) {
            $property = $this->getMappedProperty($key);
            $setterName = 'set'.ucfirst($property);

            if (method_exists(get_called_class(), $setterName)) {
                $this->$setterName($value);
            } elseif (property_exists(get_called_class(), $property)) {
                $this->$property = $value;
            }
        }

        return $this;
    }

    /**
     * Offset get
     *
     * @param mixed $offset
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function &offsetGet($offset)
    {
        $property = $this->getMappedProperty($offset);
        $getterName = 'get'.ucfirst($property);

        if (method_exists(get_called_class(), $getterName)) {
            $output = $this->$getterName();

            return $output;
        }

        if (method_exists(get_called_class(), $offset)) {
            $output = $this->$offset();

            return $output;
        }

        throw new \InvalidArgumentException(sprintf('%s:%s property is not defined', get_called_class(), $offset));
    }

    /**
     * Offset to set
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function offsetSet($offset, $value)
    {
        $property = $this->getMappedProperty($offset);

        if (property_exists(get_called_class(), $property)) {
            $setterName = 'set'.ucfirst($property);

            return $this->$setterName($value);
        }

        throw new \InvalidArgumentException(sprintf('%s:%s property is not defined', get_called_class(), $offset));
    }

    /**
     * Offset to unset
     *
     * @param mixed $offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        if (property_exists(get_called_class(), $offset)) {
            $this->$offset = null;
        } elseif (isset($this->mapping[$offset])) {
            $attribute = $this->mapping[$offset];

            $this->$attribute = null;
        }
    }

    /**
     * Whether a offset exists
     *
     * @param mixed $offset
     *
     * @return boolean|null true on success or false on failure.
     *        For Smarty isset() behavior, when property value is null, Smarty isset() should be false also
     */
    public function offsetExists($offset)
    {

        if (isset($this->mapping[$offset])) {
            return $this->offsetExistIssetSmarty($this->mapping[$offset]);
            // Otherwise, try direct access
        } else {
            return $this->offsetExistIssetSmarty($offset);
        }


    }

    /**
     * For Smarty isset() behavior, when property value is null, Smarty isset() should be false also
     *
     * @param $offset
     *
     * @return null
     */
    private function offsetExistIssetSmarty($offset)
    {
        $exists = false;

        if (
            (property_exists(get_called_class(), $offset) && $this->$offset !== null)||
            ($this->getterExists($offset) && ($this->getGetterName($offset) != null))
        ) {
            $exists = true;
        }

        return $exists;
    }

    /**
     * Get mapped property if exists
     *
     * @param  mixed $property
     *
     * @return mixed
     */
    protected function getMappedProperty($property)
    {
        if (array_key_exists($property, $this->mapping)) {
            $property = $this->mapping[$property];
        }

        return $property;
    }

    /**
     * @param $offset
     *
     * @return string
     */
    private function getGetterName($offset)
    {
        $property = $this->getMappedProperty($offset);

        return sprintf('get%s', ucfirst($property));
    }

    /**
     * @param string $offset
     *
     * @return bool
     */
    private function getterExists($offset)
    {
        return method_exists(get_called_class(), $this->getGetterName($offset));
    }
}
