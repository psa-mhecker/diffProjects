<?php

namespace PsaNdp\MappingBundle\Object\Block\Pf58Object;

use PsaNdp\MappingBundle\Object\Content;

/**
 * Class Pf58Label
 */
class Pf58Label extends Content
{
    protected $mapping = array(
        'label' => 'title'
    );

    /**
     * @var bool $disabled
     */
    protected $disabled;

    /**
     * @return boolean
     */
    public function isDisabled()
    {
        return $this->disabled;
    }

    /**
     * @param boolean $disabled
     *
     * @return $this
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }
}
