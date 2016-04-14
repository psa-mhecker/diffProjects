<?php

include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page/Zone/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page/Zone/Multi/Hmvc.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page/Multi/Zone/Multi/Hmvc.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page/Multi/Zone/Multi.php';

/**
 * Gestion des page zone multi.
 *
 * @author David Moate <david.moate@businessdecision.com>
 *
 * @since 18/03/2015
 */
class Ndp_Multi
{

    const MULTI_IS_DISPLAYED = 1;
    const PAGE_ID_NEW = '-2';
    const SIMPLE = 1;
    const HMVC = 2;
    const CONTENT = 3;
    const CTA = 4;

    /**
     * @var Pelican_Db
     */
    protected $connection;
    protected $childs = [];
    private $pageId;
    private $langueId;
    private $pageVersion;
    private $multiType;
    private $multiName;
    private $multiId;
    private $areaId;
    private $zoneTemplateId;
    private $isMulti = false;
    private $zoneOrder;
    private $multi;    
    
    public function __construct()
    {
        $this->connection = Pelican_Db::getInstance();
    }

    public function getPageId()
    {
        
        return $this->pageId;
    }

    public function setPageId($pageId = '')
    {
        $this->pageId = $pageId;

        return $this;
    }

    public function getLangueId()
    {
        
        return $this->langueId;
    }

    public function setLangueId($langueId = '')
    {
        $this->langueId = $langueId;

        return $this;
    }
    
    public function getZoneOrder()
    {
        
        return $this->zoneOrder;
    }

    public function setZoneOrder($zoneOrder = '')
    {
        $this->zoneOrder = $zoneOrder;

        return $this;
    }

    public function getPageVersion()
    {
        
        return $this->pageVersion;
    }

    public function setPageVersion($pageVersion = '')
    {
        $this->pageVersion = $pageVersion;

        return $this;
    }

    public function getMultiType()
    {
        
        return $this->multiType;
    }

    public function setMultiType($multiType)
    {
        $this->multiType = $multiType;

        return $this;
    }
    
    public function getType()
    {
        
        return $this->getMultiType();
    }

    public function setType($type)
    {
        $this->multiType = $type;

        return $this;
    }
    
    public function getMultiName()
    {
        
        return $this->multiName;
    }

    public function setMultiName($multiName)
    {
        $this->multiName = $multiName;

        return $this;
    }

    public function getMultiId()
    {
        
        return $this->multiId;
    }

    public function setMultiId($multiId)
    {
        $this->multiId = $multiId;

        return $this;
    }
    
    public function getId()
    {
        
        return $this->getMultiId();
    }

    public function setId($id)
    {
        $this->multiId = $id;

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

    public function getZoneTemplateId()
    {
        
        return $this->zoneTemplateId;
    }

    public function setZoneTemplateId($zoneTemplateId)
    {
        $this->zoneTemplateId = $zoneTemplateId;

        return $this;
    }
    
    public function getIsMulti()
    {
        
        return $this->isMulti;
    }

    public function setIsMulti($isMulti)
    {
        $this->isMulti = $isMulti;
        
        return $this;
    }
    
    public function getMulti()
    {
        
        return $this->multi;
    }

    public function setMulti($multi)
    {
        $this->multi = $multi;
        
        return $this;
    }
    
    public function addChild($child)
    {
        $this->childs[] = $child;
        
        return $this;
    }
    
    public function getChilds()
    {
        
        return $this->childs;
    }

    public static function isZoneDynamique($templateId)
    {

        return (empty($templateId)) ? true : false;        
    }

    /**
     * 
     * @param array $zoneValues
     * @return array
     */
    public function hydrate(array $zoneValues)
    {
        if (!empty($zoneValues['PAGE_ID'])) {
            $this->setPageId($zoneValues['PAGE_ID']);
        }
        if (!empty($zoneValues['LANGUE_ID'])) {
            $this->setLangueId($zoneValues['LANGUE_ID']);
        }
        if (!empty($zoneValues['PAGE_VERSION'])) {
            $this->setPageVersion($zoneValues['PAGE_VERSION']);
        }
        if (!empty($zoneValues['ZONE_TEMPLATE_ID'])) {
            $this->setZoneTemplateId($zoneValues['ZONE_TEMPLATE_ID']);
        }
        if (!empty($zoneValues['AREA_ID'])) {
            $this->setAreaId($zoneValues['AREA_ID']);
        }
        if (!empty($zoneValues['ZONE_ORDER'])) {
            $this->setZoneOrder($zoneValues['ZONE_ORDER']);
        }

        return $this;
    }

    /**
     * Permet de récupérer tous les champs d'un multi 
     * Et de pouvoir convertir un champs de type tableau en string (ex utilisation d'une liste associative)
     * @param array $multi
     * @return array
     */
    public function addFieldMulti(array $multi)
    {
        foreach ($multi as $key => $value)
        {
            if (is_string($key) && !empty($key)) {
                if (is_array($value) && !empty($value)) {
                    $value = implode(',', $value);
                }
                $multi[$key] = $value;
            }
        }

        return $multi;
    }

    /**
     * Permet de réorganiser les Ids des multis
     * @param array $multiValues
     * @return array
     */
    public function setAllMultiId($multiValues)
    {
        $pageZoneMultiIds = [];
        foreach ($multiValues as $key => $values)
        {
            if (isset($values['PAGE_ZONE_MULTI_ID']) && is_numeric($values['PAGE_ZONE_MULTI_ID'])) {
                $pageZoneMultiIds[] = intval($values['PAGE_ZONE_MULTI_ID']);
            }
        }
        $nbIdMulti = !empty($pageZoneMultiIds) ? max($pageZoneMultiIds) : 0;
        foreach ($multiValues as $key => $values)
        {
            if (!isset($values['PAGE_ZONE_MULTI_ID'])) {
                $multiValues[$key]['PAGE_ZONE_MULTI_ID'] = ++$nbIdMulti;
            }
        }

        return $multiValues;
    }
}
