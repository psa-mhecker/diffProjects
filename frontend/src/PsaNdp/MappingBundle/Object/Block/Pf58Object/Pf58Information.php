<?php

namespace PsaNdp\MappingBundle\Object\Block\Pf58Object;

use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Price;
use PsaNdp\MappingBundle\Object\PriceByMonth;

/**
 * Class Pf58Information
 */
class Pf58Information extends Content
{
    protected $mapping = array(
        'text' => 'title',
        'libelle' => 'label',
        'val' => 'title',
        'label' => 'subtitle',
    );

    /**
     * @var PriceByMonth $priceByMonth
     */
    protected $priceByMonth;

    /**
     * @var array $mentions
     */
    protected $mentions = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->priceByMonth = new PriceByMonth();
    }

    /**
     * @return array
     */
    public function getMentions()
    {
        return $this->mentions;
    }

    /**
     * @param array $mentions
     *
     * @return $this
     */
    public function setMentions($mentions)
    {
        $this->mentions = $mentions;
    }

    /**
     * @return PriceByMonth
     */
    public function getPriceByMonth()
    {
        return $this->priceByMonth;
    }

    /**
     * @param PriceByMonth $priceByMonth
     *
     * @return $this
     */
    public function setPriceByMonth($priceByMonth)
    {
        $this->priceByMonth = $priceByMonth;

        return $this;
    }

    /**
     * @return array
     */
    public function getLabel()
    {
        return $this->priceByMonth->getLabel();
    }

    /**
     * @param array $label
     *
     * @return $this
     */
    public function setLabel(array $label)
    {
        if (isset($label['text']) && isset($label['position'])) {
            $this->priceByMonth->setLabel($label['text'], $label['position']);
        } else {
            throw new \RuntimeException(sprintf('The array need to be set with a text and a position'));
        }

        return $this;
    }

    /**
     * @return Price
     */
    public function getPrice()
    {
        return $this->priceByMonth->getPrice();
    }

    /**
     * @param Price $price
     *
     * @return $this
     */
    public function setPrice(Price $price)
    {
        $this->priceByMonth->setPrice($price);

        return $this;
    }
}
