<?php

namespace PsaNdp\MappingBundle\Object\Popin;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PsaNdp\MappingBundle\Object\Content;

/**
 * Class PopinFinancements
 */
class PopinFinancements extends Content
{
    protected $mapping = array();

    /**
     * @var Collection $financements
     */
    protected $financements;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->financements = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getFinancements()
    {
        return $this->financements;
    }

    /**
     * @param Collection $financements
     */
    public function setFinancements(Collection $financements)
    {
        $this->financements = $financements;
    }

    /**
     * @param PopinFinancement $financement
     */
    public function addFinancement(PopinFinancement $financement)
    {
        $this->financements->add($financement);
    }
}
