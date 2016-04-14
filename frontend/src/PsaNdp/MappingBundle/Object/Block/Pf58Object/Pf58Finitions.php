<?php

namespace PsaNdp\MappingBundle\Object\Block\Pf58Object;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PsaNdp\MappingBundle\Object\Content;

/**
 * Class Pf58Finitions
 */
class Pf58Finitions extends Content
{
    protected $mapping = array();

    /**
     * @var Collection $listing
     */
    protected $listing;

    public function __construct()
    {
        parent::__construct();
        $this->listing = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getListing()
    {
        return $this->listing;
    }

    /**
     * @param Collection $listing
     */
    public function setListing($listing)
    {
        $this->listing = $listing;
    }

    /**
     * @param $listing
     */
    public function addListing($listing)
    {
        $this->listing->add($listing);
    }
}
