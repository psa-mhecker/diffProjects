<?php

namespace PsaNdp\MappingBundle\Object\BlockTrait;

/**
 * Class ConfigurationTrait
 */
trait ConfigurationTrait
{
    /**
     * @var bool $active
     */
    protected $active;

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     *
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }
}
