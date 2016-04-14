<?php

namespace PsaNdp\MappingBundle\Object\Block\Pf58Object;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PsaNdp\MappingBundle\Object\Content;

/**
 * Class Pf58Toggle
 */
class Pf58Toggle extends Content
{
    protected $mapping = array();

    /**
     * @var string $class
     */
    protected $class;

    /**
     * @var Collection $toggleCont
     */
    protected $toggleCont;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->toggleCont = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getToggleCont()
    {
        return $this->toggleCont;
    }

    /**
     * @param array $toggleCont
     *
     * @return $this
     */
    public function setToggleCont(array $toggleCont)
    {
        foreach ($toggleCont as $toggle) {
            $cont = new Pf58Consommation();
            $cont->setDataFromArray($toggle);
            $this->addToggleCont($cont);
        }
        $this->toggleCont = $toggleCont;

        return $this;
    }

    /**
     * @param Pf58Consommation $consommation
     */
    public function addToggleCont(Pf58Consommation $consommation)
    {
        $this->toggleCont->add($consommation);
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }
}
