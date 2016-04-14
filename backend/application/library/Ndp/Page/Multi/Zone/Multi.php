<?php
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page/Multi/Interface.php';

/**
 * Gestion des page multi zone multi.
 *
 * @author David Moate <david.moate@businessdecision.com>
 *
 * @since 18/03/2015
 */
class Ndp_Page_Multi_Zone_Multi extends Ndp_Multi implements Ndp_Page_Multi_Interface
{

    const TABLE_NAME = 'page_multi_zone_multi';

    /**
     * Récupère les multis
     * @return array
     */
    public function getValues()
    {
        $values = [];
        if ($this->getPageId() != self::PAGE_ID_NEW) {
            $bind[":PAGE_ID"] = $this->getPageId();
            $bind[":LANGUE_ID"] = $this->getLangueId();
            $bind[":PAGE_VERSION"] = $this->getPageVersion();
            $bind[":AREA_ID"] = $this->getAreaId();
            $bind[":ZONE_ORDER"] = $this->getZoneOrder();
            $bind[":PAGE_ZONE_MULTI_TYPE"] = $this->getMultiType();
            $sql = 'SELECT *
                FROM #pref#_' . self::TABLE_NAME.'
                WHERE PAGE_ID = :PAGE_ID
                AND LANGUE_ID = :LANGUE_ID
                AND PAGE_VERSION = :PAGE_VERSION
                AND AREA_ID = :AREA_ID
                AND ZONE_ORDER = :ZONE_ORDER
                AND PAGE_ZONE_MULTI_TYPE = ":PAGE_ZONE_MULTI_TYPE"
                ORDER BY PAGE_ZONE_MULTI_ORDER';
            $values = $this->connection->queryRow($sql, $bind);
        }

        return $values;
    }

    /**
     *  Enregistre les multis
     * @return \Page_Multi_Zone_Multi
     */
    public function save()
    {
        $saved = Pelican_Db::$values;
        $valuesColonne = [];
        foreach (Pelican_Db::$values as $key => $value)
        {
            if (strpos($key, $this->getMultiType()) !== false) {
                $valuesColonne[trim(str_replace($this->getMultiType().'_',
                            ' ', $key))] = $value;
            }
        }
        Pelican_Db::$values['PAGE_ZONE_MULTI_ID'] = $this->getMultiId();
        Pelican_Db::$values['PAGE_ZONE_MULTI_TYPE'] = $this->getMultiType();
        Pelican_Db::$values = array_merge(Pelican_Db::$values,
            $valuesColonne);
        $this->connection->insertQuery(sprintf('#pref#_%s', self::TABLE_NAME));
        Pelican_Db::$values = $saved;

        return $this;
    }

    /**
     * Supprime les multis
     * @return \Page_Multi_Zone_Multi
     */
    public function delete()
    {
        $saved = Pelican_Db::$values;
        Pelican_Db::$values['PAGE_ZONE_MULTI_TYPE'] = $this->getMultiType();
        Pelican_Db::$values['PAGE_ZONE_MULTI_ID'] = $this->getMultiId();
        if (Pelican_Db::$values['PAGE_ID'] != self::PAGE_ID_NEW) {
            $this->connection->deleteQuery(sprintf('#pref#_%s',
                    self::TABLE_NAME));
        }
        Pelican_Db::$values = $saved;
        
        return $this;
    }

    /**
     *
     * @param type $pageId
     * @param type $langueId
     * @param type $pageVersion
     * @param type $areaId
     * @param type $multiType
     * @param type $multiId
     *
     * @return $this
     */
    public function setValues($pageId, $langueId, $pageVersion, $areaId, $multiType, $multiId, $zoneOrder)
    {
        $this->setPageId($pageId);
        $this->setLangueId($langueId);
        $this->setPageVersion($pageVersion);
        $this->setAreaId($areaId);
        $this->setMultiType($multiType);
        $this->setMultiId($multiId);
        $this->setZoneOrder($zoneOrder);

        return $this;
    }
}
