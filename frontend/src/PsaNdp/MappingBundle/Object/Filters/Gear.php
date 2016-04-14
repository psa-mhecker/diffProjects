<?php

namespace PsaNdp\MappingBundle\Object\Filters;

use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\RadioButton;

/**
 * Class Gear
 */
class Gear extends Content
{
    protected $mapping = array(
        'label2' => 'subtitle',
    );

    /**
     * @var RadioButton $checkbox1
     */
    protected $checkbox1;

    /**
     * @var RadioButton $checkbox2
     */
    protected $checkbox2;

    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->checkbox1 = new RadioButton();
        $this->checkbox2 = new RadioButton();
    }

    /**
     * @return RadioButton
     */
    public function getCheckbox2()
    {
        return $this->checkbox2;
    }

    /**
     * @param RadioButton $checkbox2
     *
     * @return $this
     */
    public function setCheckbox2($checkbox2)
    {
        $this->checkbox2 = $checkbox2;

        return $this;
    }

    /**
     * @return RadioButton
     */
    public function getCheckbox1()
    {
        return $this->checkbox1;
    }

    /**
     * @param RadioButton $checkbox1
     *
     * @return $this
     */
    public function setCheckbox1($checkbox1)
    {
        $this->checkbox1 = $checkbox1;

        return $this;
    }
}
