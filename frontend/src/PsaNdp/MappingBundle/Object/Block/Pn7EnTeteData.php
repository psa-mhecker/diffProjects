<?php

namespace PsaNdp\MappingBundle\Object\Block;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Entity\PsaPageTypesCode;
use PsaNdp\MappingBundle\Object\BlockTrait\Pt22MyPeugeotTrait;
use PsaNdp\MappingBundle\Object\Breadcrumb;
use PsaNdp\MappingBundle\Object\Image;

/**
 * Class Pn7EnTeteData.
 */
class Pn7EnTeteData extends Breadcrumb
{
    use Pt22MyPeugeotTrait;

    const CLASSIQUE = 1;
    const VISUEL_TEXTE = 2;
    const ZONE_CTA_ACTIVE = '1';
    const ZONE_CTA_DISABLED = '2';
    const BGCOLOR_ENTETE_1 = 'white';
    const BGCOLOR_ENTETE_2 = 'grey';
    const RATIO_VISUEL = 'NDP_PF2_DESKTOP';
    const RATIO_VISUEL_MOBILE = 'NDP_GENERIC_4_3_640';

    /** @var string $sticky */
    protected $sticky;

    /** @var string $text */
    protected $text;

    /** @var string $color */
    protected $color;

    /** @var Image $media */
    protected $media = [];

    /** @var string $bgColor */
    protected $bgColor;

    /** @var string $zoneDescText */
    protected $zoneDescText;

    /** @var  bool $isMobile */
    protected $isMobile;


    /**
     * @return string
     */
    public function getTitle()
    {

        if (empty($this->title) && $this->block instanceof PsaPageZoneConfigurableInterface) {

            $this->title = $this->block->getZoneTitre();
            // Exception : test if gabarit techno, title should not be show
            if($this->isMobile && $this->block->getPage()->getVersion()->getTemplatePage()->getPageType()->getPageTypeCode() == PsaPageTypesCode::PAGE_TYPE_CODE_G36)
            {
              $this->title = null;
            }
        }


        return $this->title;
    }

    /**
     * @param array $translate
     *
     * @return $this
     */
    public function setTranslate(array $translate)
    {
        $this->translate = array(
            'close' => $translate['NDP_CLOSE'],
        );

        return $this;
    }

    /**
     * @return string
     */
    public function getSticky()
    {
        return $this->sticky;
    }

    /**
     * @param string $sticky
     */
    public function setSticky($sticky)
    {
        $this->sticky = $sticky;
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
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return array
     */
    public function getBgColor()
    {
        return $this->bgColor;
    }

    /**
     * @param array $bgColor
     */
    public function setBgColor($bgColor)
    {
        $this->bgColor = $bgColor;
    }

    /**
     * @return string
     */
    public function getZoneDescText()
    {
        return $this->zoneDescText;
    }

    /**
     * @param string $zoneDescText
     */
    public function setZoneDescText($zoneDescText)
    {
        $this->zoneDescText = $zoneDescText;
    }

    /**
     * @param boolean $isMobile
     *
     * @return Pn7EnTeteData
     */
    public function setIsMobile($isMobile)
    {
        $this->isMobile = $isMobile;

        return $this;
    }

    public function init()
    {
        $this->breadcrumb = $this->initBreadcrumb();

        if ($this->block->getZoneTitre2()) {
            $this->sticky = true;
        }

        switch ($this->block->getZoneTitre3()) {
            case self::CLASSIQUE:
                $ctaReference = $this->ctaFactory->createFromReference($this->block->getCtaReferences()->first());
                if ($ctaReference) {
                    $this->ctaList = $this->ctaFactory->create($this->block->getCtaReferences(), array('url' => 'simple-link'));
                }
                break;
            case self::VISUEL_TEXTE:
                $this->getMedia();
                break;
        }
    }

    /**
     * @return array
     */
    public function getMedia()
    {
        $ctaDisabled = ($this->block->getZoneTitre6() === self::ZONE_CTA_DISABLED);

        if ($this->block->getMedia()) {
            $size = ['desktop' => self::RATIO_VISUEL];
            $this->media = $this->mediaFactory->createFromMedia($this->block->getMedia(), ['size' => $size, 'autoCrop'=>true]);

            if (!$ctaDisabled) {
                $this->bgColor = constant('self::BGCOLOR_ENTETE_'.$this->block->getZoneTitre4());
                $this->zoneDescText = $this->block->getZoneTexte();
                $this->ctaList = $this->ctaFactory->create($this->block->getCtaReferences());
            }
        }

        return $this->media;
    }

    /**
     * @return array
     */
    protected function getCtaMedia()
    {
        $result = null;
        foreach ($this->block->getCtaReferences() as $ctaReference) {
            $result = $this->ctaFactory->createFromReference($ctaReference);
        }

        return $result;
    }
}
