<?php

namespace PsaNdp\MappingBundle\Object\Filters;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PsaNdp\MappingBundle\Object\Block\Pf58Object\Pf58Label;
use PsaNdp\MappingBundle\Object\Content;

/**
 * Class Energy
 */
class Energy extends Content
{
    protected $mapping = array();

    /**
     * @var Collection $label
     */
    protected $label;

    /**
     * @param Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->label = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param array $energies
     *
     * @return $this
     */
    public function setLabel(array $energies)
    {
        // libélle remonter de moteur de conf
        // Ordre AO remonté par le webservice moteur de conf
        foreach ($energies as $energy) {
            $pf58Label = new Pf58Label();
            $pf58Label->setDataFromArray($energy);
            $this->addLabel($pf58Label);
        }

        return $this;
    }

    /**
     * @param Pf58Label $label
     */
    public function addLabel(Pf58Label $label)
    {
        $this->label->add($label);
    }
}
