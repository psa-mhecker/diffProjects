<?php

namespace PsaNdp\MappingBundle\Object\Block;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Cta;
use PsaNdp\MappingBundle\Object\Factory\CtaFactory;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;
use PsaNdp\MappingBundle\Utils\SocialLinksManager;

/**
 * Class Pc79MurMediaManuel.
 */
class Pc79MurMediaManuel extends Content
{
    const RATIO_VISUEL = 'NDP_MEDIA_MANUAL_WALL_SMALL_VISUAL';
    const BIG_RATIO_VISUEL = 'NDP_MEDIA_MANUAL_WALL_BIG_VISUAL';
    const RATIO_VISUEL_MOBILE = 'NDP_MURMEDIA_SMALL_16_9';

    protected $mapping = array(
        'datalayer' => 'dataLayer',
    );

    /**
     * @var string
     */
    protected $close;

    /**
     * @var array
     */
    protected $gallery;

    /**
     * @var array
     */
    protected $errorMsg;

    /**
     * @var SocialLinksManager
     */
    protected $linkManager;

    /**
     * @param CtaFactory         $ctaFactory
     * @param SocialLinksManager $linkManager
     * @param MediaFactory       $mediaFactory
     */
    public function __construct(CtaFactory $ctaFactory, SocialLinksManager $linkManager, MediaFactory $mediaFactory)
    {
        $this->ctaFactory = $ctaFactory;
        $this->linkManager = $linkManager;
        $this->mediaFactory = $mediaFactory;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        if (empty($this->title) && $this->block instanceof PsaPageZoneConfigurableInterface) {
            $this->title = $this->block->getZoneTitre();
        }

        return $this->title;
    }

    /**
     * @return array
     */
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    /**
     * @param array $errorMsg
     *
     * @return $this
     */
    public function setErrorMsg($errorMsg)
    {
        $this->errorMsg = $errorMsg;

        return $this;
    }

    /**
     * @return array
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * @param array $gallery
     */
    public function setGallery($gallery)
    {
        $this->gallery = $gallery;
    }

    /**
     * @param string $close
     *
     * @return $this
     */
    public function setClose($close)
    {
        $this->close = $close;

        return $this;
    }

    /**
     * @return string
     */
    public function getClose()
    {
        return $this->close;
    }

    /**
     * Initialize gallery.
     */
    public function initializeGallery()
    {
        $this->ctaList = array();
        if ($this->block instanceof PsaPageZoneConfigurableInterface) {
            $gallery = array();

            foreach ($this->block->getMultis() as $idx => $multi) {
                if ($multi->getMedia()) {
                    $size = ['desktop' => self::RATIO_VISUEL,'mobile' => self::RATIO_VISUEL_MOBILE];
                    if (0 == $idx) {
                        $size = ['desktop' => self::BIG_RATIO_VISUEL,'mobile' => self::RATIO_VISUEL_MOBILE];
                    }
                    $gallery[] = $this->mediaFactory->createFromMedia($multi->getMedia(), ['size' => $size, 'autoCrop'=>true]);
                }
            }

            $this->setGallery($gallery);

            if ($this->block->getZoneParameters() === '1') {
                $this->ctaList = array($this->ctaFactory->createCtaFromArray(array(
                    'title' => $this->block->getZoneTitre2(),
                    'url' => $this->block->getZoneUrl(),
                    'type' => Cta::NDP_CTA_TYPE_SIMPLELINK,
                )));
            }
        }
    }
}
