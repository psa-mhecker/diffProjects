<?php

namespace PsaNdp\MappingBundle\Object;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Object\Factory\CtaFactory;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;

/**
 * Class Content
 */
class Content extends AbstractObject
{
    const ONLY_MOBILE  = 'show-for-small-only';
    const ONLY_DESKTOP = 'hide-for-small-only';

    protected $mapping = array(
        'text' => 'subtitle',
        'txt' => 'subtitle',
        'datalayer' => 'dataLayer',
        'description' => 'subtitle',
    );

    /** @var CtaFactory */
    protected $ctaFactory;

    /**
     * @var MediaFactory
     */
    protected $mediaFactory;

    /**
     * @var string $title
     */
    protected $title;

    /**
     * @var string $subtitle
     */
    protected $subtitle;

    /**
     * @var array $translate
     */
    protected $translate = array();

    /**
     * @var string $url
     */
    protected $url;

    /**
     * @var string $target
     */
    protected $target;

    /**
     * @var string|null should be automatically filled by AbstractPsaStrategy addTransverseTransformerData()
     */
    protected $anchorId = null;

    /**
     * @var string|null should be automatically filled by AbstractPsaStrategy addTransverseTransformerData()
     */
    protected $popintrancheid = null;

    /**
     * @var string should be automatically filled by AbstractPsaStrategy addTransverseTransformerData()
     */
    protected $dataLayer = '';

    /**
     * @var array $ctaList
     */
    protected $ctaList = array();

    /**
     * @var PsaPageZoneConfigurableInterface
     */
    protected $block;

    /**
     * @var int
     */
    protected $siteId;

    /**
     * @var string
     */
    protected $sectionClass;

    /**
     * @return null|string
     */
    public function getAnchorId()
    {
        return $this->anchorId;
    }

    /**
     * @param null|string $anchorId
     *
     * @return Content
     */
    public function setAnchorId($anchorId)
    {
        $this->anchorId = $anchorId;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPopintrancheid()
    {
        return $this->popintrancheid;
    }

    /**
     * @param null|string $popintrancheid
     *
     * @return Content
     */
    public function setPopintrancheid($popintrancheid)
    {
        $this->popintrancheid = $popintrancheid;

        return $this;
    }

    /**
     * CtaFactory injected only on object which needs it.
     * @return CtaFactory|null
     */
    public function getCtaFactory()
    {
        return $this->ctaFactory;
    }

    /**
     * CtaFactory injected only on object which needs it.
     * @param CtaFactory $ctaFactory
     *
     * @return Content
     */
    public function setCtaFactory(CtaFactory $ctaFactory)
    {
        $this->ctaFactory = $ctaFactory;

        return $this;
    }

    /**
     * @return MediaFactory
     */
    public function getMediaFactory()
    {
        return $this->mediaFactory;
    }

    /**
     * @param MediaFactory $mediaFactory
     *
     * @return Content
     */
    public function setMediaFactory($mediaFactory)
    {
        $this->mediaFactory = $mediaFactory;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * @param string $subtitle
     *
     * @return Content
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Content
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return array
     */
    public function getTranslate()
    {
        return $this->translate;
    }

    /**
     * @param array $translate
     *
     * @return Content
     */
    public function setTranslate(array $translate)
    {
        $this->translate = $translate;

        return $this;
    }

    /**
     * @param integer|string $key
     * @param mixed $value
     * @return Content
     */
    public function addTranslate($key, $value)
    {
        if (!is_array($this->translate)) {
            $this->translate = [];
        }

        $this->translate[$key] = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param string $target
     *
     * @return Content
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return Content
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getDataLayer()
    {
        return $this->dataLayer;
    }

    /**
     * @param string $dataLayer
     *
     * @return Content
     */
    public function setDataLayer($dataLayer)
    {
        $this->dataLayer = $dataLayer;

        return $this;
    }

    /**
     * @return array
     */
    public function getCtaList()
    {
        return $this->ctaList;
    }

    /**
     * @param array $ctaList
     *
     * @return Content
     */
    public function setCtaList($ctaList)
    {
        $this->ctaList = $ctaList;

        return $this;
    }

    /**
     * @return PsaPageZoneConfigurableInterface
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @param PsaPageZoneConfigurableInterface $block
     *
     * @return $this
     */
    public function setBlock(PsaPageZoneConfigurableInterface $block)
    {
        $this->block = $block;

        return $this;
    }

    /**
     * @param $siteId
     *
     * @return $this
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;

        return $this;
    }

    /**
     * @return int
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     *
     * @return string
     */
    public function getSectionClass()
    {
        $this->sectionClass = '';

        if (isset($this->block)) {
            // affichage mobile uniquement
            if ($this->block->getZoneMobile() &&  !$this->block->getZoneWeb()) {
                $this->sectionClass = self::ONLY_MOBILE;
            }
            // affichage mobile uniquement
            if (!$this->block->getZoneMobile() &&  $this->block->getZoneWeb()) {
                $this->sectionClass = self::ONLY_DESKTOP;
            }
        }
        return $this->sectionClass;
    }

    /**
     * @param string $sectionClass
     */
    public function setSectionClass($sectionClass)
    {
        $this->sectionClass = $sectionClass;
    }

    /**
     * @return int|null
     */
    public function getTimerSpeed()
    {
        return $this->block->getTimerSpeed();
    }


    /**
     * @param PsaPageZoneConfigurableInterface $block
     * @param array $options
     */
    protected function initCtaListFromBlock(PsaPageZoneConfigurableInterface $block, $options = [])
    {
        $ctaReferences = $block->getCtaReferences();

        if ($ctaReferences && count($ctaReferences) > 0) {
            $ctaFactory = $this->getCtaFactory();
            $ctaList = $ctaFactory->create($ctaReferences, $options);
            $this->setCtaList($ctaList);
        }
    }

    /**
     * @param PsaPageZoneConfigurableInterface $block
     * @param string $type
     * @param array $options
     * @return array $ctaList|null
     */
    protected function initCtaListByTypeFromBlock(PsaPageZoneConfigurableInterface $block, $type, $options = [])
    {
        $ctaReferences = $block->getCtaReferencesByType($type);
        $ctaList = null;

        if ($ctaReferences && count($ctaReferences) > 0) {
            $ctaFactory = $this->getCtaFactory();
            $ctaList = $ctaFactory->create($ctaReferences, $options);
        }

        return $ctaList;
    }

    /**
     * @return int
     */
    public function getBlockOrder()
    {
        return $this->getBlock()->getOrder();
    }

    /**
     * @return string
     */
    public function getBlockName()
    {
        return $this->getBlock()->getName();
    }
}
