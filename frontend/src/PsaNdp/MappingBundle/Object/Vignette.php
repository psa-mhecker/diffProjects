<?php

namespace PsaNdp\MappingBundle\Object;

/**
 * Class Vignette.
 *
 * @codeCoverageIgnore
 */
class Vignette extends Content
{
    /**
     * @var string
     */
    protected $vignetteCommercial;

    /**
     * @var string
     */
    protected $vignetteCommercialClass;

    /**
     * @var string
     */
    protected $price;

    /**
     * @var string
     */
    protected $priceLabel;

    /**
     * @var string
     */
    protected $thumbnail;

    /**
     * @var MediaInterface
     */
    protected $media;

    /**
     * @var array
     */
    protected $filters;

    /**
     * @return string
     */
    public function getVignetteCommercial()
    {
        return $this->vignetteCommercial;
    }

    /**
     * @param string $vignetteCommercial
     */
    public function setVignetteCommercial($vignetteCommercial)
    {
        $this->vignetteCommercial = $vignetteCommercial;
    }

    /**
     * @return string
     */
    public function getVignetteCommercialClass()
    {
        return $this->vignetteCommercialClass;
    }

    /**
     * @param string $vignetteCommercialClass
     */
    public function setVignetteCommercialClass($vignetteCommercialClass)
    {
        $this->vignetteCommercialClass = $vignetteCommercialClass;
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
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getPriceLabel()
    {
        return $this->priceLabel;
    }

    /**
     * @param string $priceLabel
     */
    public function setPriceLabel($priceLabel)
    {
        $this->priceLabel = $priceLabel;
    }

    /**
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @param string $thumbnail
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }

    /**
     * @return MediaInterface
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param array $filters
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
    }
}
