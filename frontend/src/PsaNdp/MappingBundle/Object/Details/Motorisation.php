<?php

namespace PsaNdp\MappingBundle\Object\Details;

use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;
use PsaNdp\MappingBundle\Object\Media;
use PsaNdp\MappingBundle\Object\Price;
use PsaNdp\MappingBundle\Object\PriceByMonth;


/**
 * Class Motorisation
 */
class Motorisation extends Content
{
    protected $mapping = array(
        'libelle' => 'label',
        'link' => 'ctaList',
        'text' => 'subtitle',
        'pricebyMonth' => 'priceByMonth',
        'finition' => 'engineFinition',
    );

    /**
     * @var string $id
     */
    protected $id;

    /**
     * @var Media $img
     */
    protected $img;

    /**
     * @var bool $info
     */
    protected $info = false;

    /**
     * @var array $label
     */
    protected $label;

    /**
     * @var Price $price
     */
    protected $price;

    /**
     * @var PriceByMonth $priceByMonth
     */
    protected $priceByMonth;

    /**
     * @var MotorisationFinition $motorisationFinition
     */
    protected $motorisationFinition;

    /**
     * Constructor
     *
     * @param MediaFactory $mediaFactory
     */
    public function __construct(MediaFactory $mediaFactory)
    {
        parent::__construct();
        $this->img = $mediaFactory->createMedia();
        $this->price = new Price();
        $this->setMediaFactory($mediaFactory);
    }

    /**
     * @return Media
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * @param Media $img
     */
    public function setImg($img)
    {
        $this->img = $img;
    }

    /**
     * @return boolean
     */
    public function isInfo()
    {
        return $this->info;
    }

    /**
     * @param boolean $info
     */
    public function setInfo($info)
    {
        $this->info = $info;
    }

    /**
     * @return array
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param array $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return Price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param Price $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return MotorisationFinition
     */
    public function getMotorisationFinition()
    {
        return $this->motorisationFinition;
    }

    /**
     * @param MotorisationFinition $motorisationFinition
     *
     * @return Motorisation
     */
    public function setMotorisationFinition(MotorisationFinition $motorisationFinition)
    {
        $this->motorisationFinition = $motorisationFinition;

        return $this;
    }


}
