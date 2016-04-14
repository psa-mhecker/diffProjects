<?php

namespace PsaNdp\MappingBundle\Object\Details;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;
use PsaNdp\MappingBundle\Object\Price;
use PsaNdp\MappingBundle\Object\Media;
use PsaNdp\MappingBundle\Object\PriceByMonth;

/**
 * Class Finition
 */
class Finition extends Content
{
    protected $mapping = array(
        'libelle' => 'label'
    );

    /**
     * @var Media $img
     */
    protected $img;

    /**
     * @var PriceByMonth $priceByMonth
     */
    protected $priceByMonth;

    /**
     * @var string $text
     */
    protected $text;

    /**
     * @var Collection $details
     */
    protected $details;

    /**
     * @var string $mention
     */
    protected $mention;

    /**
     * @param MediaFactory $mediaFactory
     * @param PriceByMonth $priceByMonth
     */
    public function __construct(MediaFactory $mediaFactory, PriceByMonth $priceByMonth)
    {
        $this->img = $mediaFactory->createMedia();
        $this->priceByMonth = $priceByMonth;
        $this->details = new ArrayCollection();
        $this->setMediaFactory($mediaFactory);
    }

    /**
     * @return Collection
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param Collection $details
     *
     * @return $this
     */
    public function setDetails(Collection $details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * @param Detail $detail
     */
    public function addDetail(Detail $detail)
    {
        $this->details->add($detail);
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
     *
     * @return $this
     */
    public function setImg(Media $img)
    {
        $this->img = $img;

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

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getMention()
    {
        return $this->mention;
    }

    /**
     * @param string $mention
     *
     * @return $this
     */
    public function setMention($mention)
    {
        $this->mention = $mention;

        return $this;
    }
}
