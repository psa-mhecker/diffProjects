<?php


/**
 * Gestion du cache.
 *
 * @author David Moate <david.moate@businessdecision.com>
 *
 * @since 02/04/2015
 */
class Ndp_Cache
{
    private $pageId;
    private $languageId;
    private $locale;
    private $siteId;
    private $areaId;
    private $orderId;
    private $ctaId;
    private $contentId;
    private $general;
    private $blockType;
    private $device;
    private $lcdv6;
    private $decacheTags = [];
    private $cacheTypeTag;
    protected $connection;
    /** @var \PsaNdp\MappingBundle\Manager\PsaTagManager */
    private $tagManager;

    const STRATEGY = 'strategy';
    const PAGE = 'page';
    const NODE = 'node';

    public function __construct($cacheTypeTag, $decacheTags = [])
    {
        $this->init($cacheTypeTag, $decacheTags);
    }

    public function init($cacheTypeTag, $decacheTags)
    {
        $connection = Pelican_Db::getInstance();

        $this->setConnection($connection);
        $this->setCacheTypeTag($cacheTypeTag);
        $this->setDecacheTags($decacheTags);

        $this->tagManager = Pelican_Application::getContainer()->get('open_orchestra_base.manager.tag');
    }

    public function getPageId()
    {
        return $this->pageId;
    }

    public function setPageId($pageId)
    {
        $this->pageId = $pageId;

        return $this;
    }

    public function getLanguageId()
    {
        return $this->languageId;
    }

    public function setLanguageId($languageId)
    {
        $this->languageId = $languageId;

        return $this;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale()
    {
        $bind[':LANGUE_ID'] = $this->getLanguageId();
        $sql = 'SELECT LANGUE_CODE FROM #pref#_language WHERE LANGUE_ID = :LANGUE_ID';
        $langueCode = $this->getConnection()->queryRow($sql, $bind);
        $this->locale = $langueCode['LANGUE_CODE'];

        return $this;
    }

    public function getSiteId()
    {
        return $this->siteId;
    }

    public function setSiteId($siteId)
    {
        if (empty($siteId)) {
            $siteId = $_SESSION[APP]['SITE_ID'];
        }
        $this->siteId = $siteId;

        return $this;
    }

    public function getAreaId()
    {
        return $this->areaId;
    }

    public function setAreaId($areaId)
    {
        $this->areaId = $areaId;

        return $this;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function getCtaId()
    {
        return $this->ctaId;
    }

    public function setCtaId($ctaId)
    {
        $this->ctaId = $ctaId;

        return $this;
    }

    public function getContentId()
    {
        return $this->contentId;
    }

    public function setContentId($contentId)
    {
        $this->contentId = $contentId;

        return $this;
    }

    public function getGeneral()
    {
        return $this->general;
    }

    public function setGeneral($general)
    {
        $this->general = $general;

        return $this;
    }

    public function getBlockType()
    {
        return $this->blockType;
    }

    public function setBlockType($blockType)
    {
        $this->blockType = $blockType;

        return $this;
    }

    public function getDevice()
    {
        return $this->device;
    }

    public function setDevice($device)
    {
        $this->device = $device;

        return $this;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function setConnection($connection)
    {
        $this->connection = $connection;

        return $this;
    }

    public function getRedis()
    {
        return $this->redis;
    }

    public function setRedis($redis)
    {
        $this->redis = $redis;

        return $this;
    }

    public function getDecacheTags()
    {
        return $this->decacheTags;
    }

    public function setDecacheTags(array $decacheTags)
    {
        $this->decacheTags = $decacheTags;

        return $this;
    }

    public function getCacheTypeTag()
    {
        return $this->cacheTypeTag;
    }

    public function setCacheTypeTag($cacheTypeTag)
    {
        $this->cacheTypeTag = $cacheTypeTag;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNavigation()
    {
        return $this->getSiteId().'-'.$this->getLocale();
    }

    /**
     * @param $lcdv6
     *
     * @return $this
     */
    public function setLcdv6($lcdv6)
    {
        $this->lcdv6 = $lcdv6;

        return $this;
    }

    public function getLcdv6()
    {
        return $this->lcdv6;
    }

    public function hydrate(array $values, $parent = false)
    {
        if (empty($values['SITE_ID']) && !empty($_SESSION[APP]['SITE_ID'])) {
            $values['SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        }
        if (!empty($values['SITE_ID'])) {
            $this->setSiteId($values['SITE_ID']);
            $this->setGeneral('general');
        }
        if (!empty($values['PAGE_ID'])) {
            $this->setPageId($values['PAGE_ID']);
        }
        if (true === $parent && !empty($values['PAGE_PARENT_ID'])) {
            $this->setPageId($values['PAGE_PARENT_ID']);
        }
        if (!empty($values['CONTENT_ID'])) {
            $this->setContentId($values['CONTENT_ID']);
        }
        if (!empty($values['LANGUE_ID']) && !is_array($values['LANGUE_ID'])) {
            $this->setLanguageId($values['LANGUE_ID']);
            $this->setLocale();
        }
        if (!empty($values['DEVICE_ID'])) {
            $this->setDevice($values['DEVICE_ID']);
        }
        if (!empty($values['BLOCK_TYPE'])) {
            $this->setBlockType($values['BLOCK_TYPE']);
        }
        if (!empty($values['AREA_ID']) && !is_array($values['AREA_ID'])) {
            $this->setAreaId($values['AREA_ID']);
        }
        if (isset($values['TARGET']) && !empty($values['ID'])) {
            $this->setCtaId($values['ID']);
        }
        if (isset($values['LCDV6'])) {
            $this->setLcdv6($values['LCDV6']);
        }
        // Pour gérer le cas d'une zone statique.
        if (!is_array($values['ZONE_TEMPLATE_ID']) && empty($values['AREA_ID']) && !empty($values['ZONE_TEMPLATE_ID'])
        ) {
            $this->setAreaId($this->getAreaIdByZoneTemplateId($values['ZONE_TEMPLATE_ID']));
        }
        if (!empty($values['ZONE_ORDER']) && !is_array($values['ZONE_ORDER'])) {
            $this->setOrderId($values['ZONE_ORDER']);
        }
        // Pour gérer le cas d'une zone statique.
        if (!is_array(
                $values['ZONE_TEMPLATE_ID']
            ) && empty($values['ZONE_ORDER']) && !empty($values['ZONE_TEMPLATE_ID'])
        ) {
            $this->setOrderId($this->getZoneOrderByZoneTemplateId($values['ZONE_TEMPLATE_ID']));
        }

        return $this;
    }

    public function getAreaIdByZoneTemplateId($zoneTemplateId)
    {
        $bind[':ZONE_TEMPLATE_ID'] = $zoneTemplateId;
        $sql = 'SELECT AREA_ID FROM #pref#_zone_template WHERE ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID';
        $zoneTemplate = $this->getConnection()->queryRow($sql, $bind);

        return $zoneTemplate['AREA_ID'];
    }

    public function getZoneOrderByZoneTemplateId($zoneTemplateId)
    {
        $bind[':ZONE_TEMPLATE_ID'] = $zoneTemplateId;
        $sql = 'SELECT ZONE_TEMPLATE_ORDER FROM #pref#_zone_template WHERE ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID';
        $zoneTemplate = $this->getConnection()->queryRow($sql, $bind);

        return $zoneTemplate['ZONE_TEMPLATE_ORDER'];
    }

    public function decacheOrchestra()
    {
        /** @var \Itkg\CombinedHttpCache\Client\RedisClient $redisCache */
        $redisCache = Pelican_Application::getContainer()->get('psa_ndp.cache.redis');

        //TODO translation

        $cacheTypeTag = $this->getCacheTypeTag();
        $decacheTags = $this->getDecacheTags();

        $tagsValue = [];

        foreach ($decacheTags as $tag) {
            $tagsValue[] = $this->formatTagValue($tag);
        }

        switch ($cacheTypeTag) {
            case 'strategy':
                $redisCache->removeKeysFromTags($tagsValue);
                break;
        }

        return $this;
    }

    /**
     * Format tag with its associated value using tagManager.
     *
     * @param $tag
     *
     * @return string
     */
    private function formatTagValue($tag)
    {
        switch ($tag) {
            case 'pageId':
                $result = $this->tagManager->formatNodeIdTag($this->getPageId());
                break;
            case 'locale':
                $result = $this->tagManager->formatLanguageTag($this->getLocale());
                break;
            case 'siteId':
                $result = $this->tagManager->formatSiteIdTag($this->getSiteId());
                break;
            case 'cta':
                $result = $this->tagManager->formatKeyIdTag('cta', $this->getCtaId());
                break;
            case 'content':
                $result = $this->tagManager->formatKeyIdTag('contentId', $this->getContentId());
                break;
            default:
                $result = $this->tagManager->formatKeyIdTag($tag, $this->getValue($tag));
        }

        return $result;
    }

    public function getValue($tag)
    {
        $value = $tag;
        $methode = 'get'.ucfirst($tag);
        if (method_exists($this, $methode)) {
            $value = $this->$methode();
        }

        return $value;
    }
}
