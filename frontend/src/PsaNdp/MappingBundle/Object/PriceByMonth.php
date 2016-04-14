<?php

namespace PsaNdp\MappingBundle\Object;

/**
 * Class PriceByMonth
 */
class PriceByMonth extends AbstractObject
{
    protected $mapping = array(
        'libelle' => 'label'
    );

    /**
     * @var array $label
     */
    protected $label = array();

    /**
     * @var Price $price
     */
    protected $price;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->price = new Price();
    }

    /**
     * @return array
     */
    public function getLabel()
    {
        if (empty($this->label)) {
            return null;
        }

        return $this->label;
    }

    /**
     * @param array $label
     *
     * @return $this
     */
    public function setLabel(array $label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param array $price
     *
     * @return $this
     */
    public function setPrice(array $price)
    {
        $this->price->setDataFromArray($price);

        return $this;
    }
}
