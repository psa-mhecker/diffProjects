<?php

namespace PsaNdp\MappingBundle\Object\Block\Pf53Object;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PsaNdp\MappingBundle\Manager\PriceManager;
use PsaNdp\MappingBundle\Object\Block\Pf53Finitions;
use PsaNdp\MappingBundle\Object\Details\Detail;
use PsaNdp\MappingBundle\Object\Media;
use PsaNdp\MappingBundle\Object\Price;

/**
 * Class Pf53FinitionsMobile
 */
class Pf53FinitionsMobile extends Pf53Finitions
{
    /**
     * @var Media
     */
    protected $image;

    /**
     * @var array $switch
     */
    protected $switch;

    /**
     * @var Price
     */
    protected $price;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var array
     */
    protected $mentions;

    /**
     * @var Collection
     */
    protected $toggle;

    /**
     * Constructor
     *
     * @param PriceManager $priceManager
     */
    public function __construct(PriceManager $priceManager)
    {
        $this->priceManager = $priceManager;
        $this->price = new Price();
        $this->toggle = new ArrayCollection();
    }

    /**
     * @return Media
     */
    public function getImage()
    {
        return array('src' => self::VEHICLE_V3D_BASE_URL.$this->lcdv16.'&width=210&height=97&ratio=1&format=jpg&quality=100&view='.$this->angleView, 'alt' => $this->lcdv16);
    }

    /**
     * @param array $image
     *
     * @return $this
     */
    public function setImage(array $image)
    {
        $this->image = $this->mediaFactory->createFromArray($image);

        return $this;
    }

    /**
     * @return array
     */
    public function getSwitch()
    {
        return $this->switch;
    }

    /**
     * @param array $switch
     *
     * @return $this
     */
    public function setSwitch(array $switch)
    {
        $this->switch = $switch;

        return $this;
    }

    /**
     * @return array
     */
    public function getLibelle()
    {
        return array(
            'text' => $this->translate['from'],
            'position' => false,
        );
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
     */
    public function setPrice(array $price)
    {
        $this->price->setDataFromArray($price);
    }

    /**
     * @return string
     */
    public function getText()
    {
        return 'avec le moteur '.$this->version->GrEngine->label;
//        return $this->text;
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
        return $this->translate['furtherInfos'];
    }

    /**
     * @return array
     */
    public function getMentions()
    {
        return array(
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas porttitor accumsan diam ut convallis. Etiam at erat felis. Maecenas vitae velit hendrerit, laoreet risus sed, porta justo. "
        );
//        return $this->mentions;
    }

    /**
     * @param array $mentions
     *
     * @return $this
     */
    public function setMentions($mentions)
    {
        $this->mentions = $mentions;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getToggle()
    {
        return $this->toggle;
    }

    /**
     * @param array $toggles
     *
     * @return $this
     */
    public function setToggle(array $toggles)
    {
        foreach ($toggles as $toggle) {
            $detail = new Detail();
            $detail->setDataFromArray($toggle);
            $this->addToggle($detail);
        }

        return $this;
    }

    /**
     * @param Detail $detail
     */
    public function addToggle(Detail $detail)
    {
        $this->toggle->add($detail);
    }

    /**
     * @return array|void
     */
    public function initSeries()
    {
        $return = array('close' => $this->translate['close']);

        foreach ($this->series as $segments) {
            foreach ($segments['serie'] as $serie) {
                $pf53Serie = new Pf53SeriesMobile();
                $pf53Serie->setTranslate($this->translate);
                $pf53Serie->setTranslator($this->translator, $this->domain, $this->locale);
                $pf53Serie->setSiteSettings($this->siteSettings);
                $pf53Serie->setPriceManager($this->priceManager);
                $pf53Serie->setDataFromArray($serie);
                $pf53Serie->initSerie();
                $return['finition'][] = $pf53Serie;
            }
        }

        $this->series = $return;
    }

    /**
     * @return int
     */
    public function countSeries()
    {
        return count($this->series['finition']);
    }
}
