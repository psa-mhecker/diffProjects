<?php

namespace PsaNdp\MappingBundle\Object\Filters;

use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\RadioButton;

/**
 * Class Price
 */
class Price extends Content
{
    protected $mapping = array();

    /**
     * @var RadioButton $radio1
     */
    protected $radio1;

    /**
     * @var RadioButton $radio2
     */
    protected $radio2;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->radio1 = new RadioButton();
        $this->radio2 = new RadioButton();
    }

    /**
     * @return RadioButton
     */
    public function getRadio1()
    {
        return $this->radio1;
    }

    /**
     * @param RadioButton $radio1
     *
     * @return $this
     */
    public function setRadio1($radio1)
    {
        $this->radio1 = $radio1;

        return $this;
    }

    /**
     * @return RadioButton
     */
    public function getRadio2()
    {
        return $this->radio2;
    }

    /**
     * @param RadioButton $radio2
     *
     * @return $this
     */
    public function setRadio2($radio2)
    {
        $this->radio2 = $radio2;

        return $this;
    }
}
