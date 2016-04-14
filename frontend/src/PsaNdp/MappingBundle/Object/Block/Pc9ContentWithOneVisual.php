<?php

namespace PsaNdp\MappingBundle\Object\Block;


use Doctrine\Common\Collections\ArrayCollection;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Factory\CtaFactory;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;

class Pc9ContentWithOneVisual extends Content
{

    const RATIO_VISUEL = "NDP_GENERIC_4_3_640";


    protected $mapping = array();

    /**
     * @var ArrayCollection
     */
    protected $slideShow;

    /**
     * @var string
     */
    protected $timerSpeed;

    /**
     * @var string
     */
    protected $text;

    /**
     * @param CtaFactory   $ctaFactory
     * @param MediaFactory $mediaFactory
     */
    public function __construct(CtaFactory $ctaFactory, MediaFactory $mediaFactory)
    {
        $this->ctaFactory = $ctaFactory;
        $this->mediaFactory = $mediaFactory;
        $this->slideShow = new ArrayCollection();
    }

    /**
     * Initialize SlideShow
     */
    public function initSlideShow()
    {
        $this->slideShow = new ArrayCollection();
        $this->ctaList = array();

        $multis = $this->getBlock()->getMultis();

        if(!$multis->isEmpty()){
            foreach($multis as $multi){
                $options = ['size' =>  ['desktop' => self::RATIO_VISUEL, 'mobile' => self::RATIO_VISUEL], 'autoCrop' => true];
                $this->slideShow->add($this->mediaFactory->createFromMedia($multi->getMedia(),$options));
            }
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getSlideShow()
    {
        if($this->slideShow->isEmpty()){
            $this->initSlideShow();
        }

        return $this->slideShow;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->block->getZoneTexte();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->block->getZoneTitre();
    }

    /**
     * @return string
     */
    public function getSubtitle()
    {
        return $this->block->getZoneTitre2();
    }

    /**
     * @return array
     */
    public function getCtaList()
    {
        if (empty($this->ctaList)) {
            if ($this->block instanceof ReadBlockInterface) {
                $this->initCtaListFromBlock($this->block);
            }
        }

        return $this->ctaList;
    }

    /**
     * @return int|null
     */
    public function getTimerSpeed()
    {
        return $this->block->getTimerSpeed();
    }

    /**
     * @return string
     */
    public function getImagePosition()
    {
        return $this->block->getZoneParameters();
    }
}
