<?php

namespace PsaNdp\MappingBundle\Object;


/**
 * Class Transition
 */
class Thumbnail extends Content
{
    protected $mapping = array(
        'thumbnail'=>'img',
        'price'=>'price',
        'beforePrice'=>'beforePrice',
        'showPrice'=>'showPrice',
    );

    /**
     * @var string $img
     */
    protected $img;

    /**
     * @var string $img
     */
    protected $imgUrl;

    /**
     * @var string $alt
     */
    protected $alt;

    /**
     * @var string $beforPrice
     */
    protected $beforePrice;

    /**
     * @var bool $showPrice
     */
    protected $showPrice;

    /**
     * @var string $price
     */
    protected $price;

    /**
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * @param string $img
     *
     * @return Thumbnail
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * @return string
     */
    public function getImgUrl()
    {
        return $this->img;
    }


    /**
     * @return string
     */
    public function getAlt()
    {
        return 'Peugeot '.$this->title;
    }

    /**
     * @param string $alt
     *
     * @return Thumbnail
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * @return string
     */
    public function getBeforePrice()
    {
        $return = null;

        if ($this->getShowPrice()) {
            $return = $this->beforePrice.' ';
        }

        return $return;
    }

    /**
     * @param string $beforePrice
     *
     * @return Thumbnail
     */
    public function setBeforePrice($beforePrice)
    {
        $this->beforePrice = $beforePrice;

        return $this;
    }

    /**
     * @return string
     */
    public function getShowPrice()
    {
        return $this->showPrice;
    }

    /**
     * @param bool $showPrice
     *
    * @return Thumbnail
     */
    public function setShowPrice($showPrice)
    {
        $this->showPrice = $showPrice;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param string $price
     *
     * @return Thumbnail
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }




}
