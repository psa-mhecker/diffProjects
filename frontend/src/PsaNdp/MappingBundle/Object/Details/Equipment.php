<?php

namespace PsaNdp\MappingBundle\Object\Details;

use PsaNdp\MappingBundle\Object\Content;

/**
 * Class Equipment
 */
class Equipment extends Content
{
    protected $mapping = array(
        'text' => 'subtitle',
    );

    /**
     * @var bool $info
     */
    protected $info = false;

    /**
     * @return boolean
     */
    public function isInfo()
    {
        return $this->info;
    }

    /**
     * @param boolean $info
     *
     * @return $this
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }
}
