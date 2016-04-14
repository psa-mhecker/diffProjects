<?php

namespace PsaNdp\MappingBundle\Object;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class PriceList
 */
class PriceList extends Content
{
    protected $mapping = array();

    /**
     * @var Collection $price
     */
    protected $price;

    public function __construct()
    {
        parent::__construct();
        $this->price = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param Collection $price
     *
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @param Price $price
     */
    public function addPrice(Price $price)
    {
        $this->price->add($price);
    }
}
