<?php
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page/Cta/Interface.php';

/**
 * Gestion des page zone cta.
 *
 * @author David Moate <david.moate@businessdecision.com>
 *
 * @since 21/03/2015
 */
class Ndp_Page_Multi_Zone_Multi_Cta extends Ndp_Cta implements Ndp_Page_Cta_Interface
{

    const TABLE_NAME = 'page_multi_zone_multi_cta';

    /**
     * Retourne les données de la table page_zone_cta
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
            $bind[":PAGE_ZONE_MULTI_ID"] = $this->getParentId();
            $bind[":PAGE_ZONE_MULTI_TYPE"] = $this->getParentType();
            $sql = 'SELECT c.*, pzmc.*,
                CASE
                    WHEN pzmc.TARGET = ""
                    THEN c.TARGET
                    ELSE pzmc.TARGET
                END as TARGET
                FROM #pref#_' . self::TABLE_NAME.' pzmc
                LEFT JOIN psa_cta c ON pzmc.cta_id = c.id and c.IS_REF = 0
                WHERE PAGE_ID = :PAGE_ID
                AND pzmc.LANGUE_ID = :LANGUE_ID
                AND pzmc.PAGE_VERSION = :PAGE_VERSION
                AND ZONE_ORDER = :ZONE_ORDER
                   
                AND pzmc.AREA_ID = :AREA_ID
                AND pzmc.PAGE_ZONE_MULTI_ID = :PAGE_ZONE_MULTI_ID
                AND pzmc.PAGE_ZONE_MULTI_TYPE = ":PAGE_ZONE_MULTI_TYPE"
                ORDER BY PAGE_ZONE_CTA_ORDER';
            $values = $this->getConnection()->queryTab($sql, $bind);
        }

        return parent::getValues($values);
    }

    /**
     * Méthode statique de sauvegarde des données d'un multi cta à enregistrer dans
     * la table page_zone_cta.
     * @return \Page_Zone_Cta
     */
    public function save()
    {
        $values = array_merge(Pelican_Db::$values, Pelican_Db::$values[$this->getType()]);
        $values['PAGE_ZONE_CTA_TYPE'] = $this->getType();
        $values['PAGE_ZONE_CTA_ID'] = $this->getId();
        $this->saveCta($values, self::TABLE_NAME);
        return $this;
    }


    /**
     * Supprime des données dans la table page_zone_cta
     * @return \Page_Zone_Cta
     */
    public function delete()
    {
        $bind[':LANGUE_ID'] = Pelican_Db::$values['LANGUE_ID'];
        $bind[':PAGE_VERSION'] = Pelican_Db::$values['PAGE_VERSION'];
        $bind[':PAGE_ID'] = Pelican_Db::$values['PAGE_ID'];
        $bind[':AREA_ID'] = Pelican_Db::$values['AREA_ID'];
        $bind[':ZONE_ORDER'] = Pelican_Db::$values['ZONE_ORDER'];
        $bind[":PAGE_ZONE_MULTI_TYPE"] = $this->getParentType();
         
        $bind[':PAGE_ZONE_CTA_TYPE'] = $this->connection->strToBind($this->getType());
        if (Pelican_Db::$values['PAGE_ID'] != self::PAGE_ID_NEW) {
            $sql = 'DELETE FROM #pref#_'.self::TABLE_NAME.'
                    WHERE LANGUE_ID = :LANGUE_ID
                    AND PAGE_VERSION = :PAGE_VERSION
                    AND PAGE_ID = :PAGE_ID
                    AND AREA_ID = :AREA_ID
                    AND ZONE_ORDER = :ZONE_ORDER
                    AND PAGE_ZONE_CTA_TYPE = :PAGE_ZONE_CTA_TYPE';
            $this->connection->query($sql, $bind);
        }

        return $this;
    }

    /**
     * set tous les membres de la classe parent Cta
     * @param type $pageId
     * @param type $langueId
     * @param type $pageVersion
     * @param type $areaId
     * @param type $ctaType
     * @param type $ctaId
     * @return \Page_Zone_Cta
     */
    public function setValues($pageId, $langueId, $pageVersion, $areaId, $ctaType, $ctaId, $zoneOrder)
    {
        $this->setPageId($pageId);
        $this->setLangueId($langueId);
        $this->setPageVersion($pageVersion);
        $this->setAreaId($areaId);
        $this->setType($ctaType);
        $this->setId($ctaId);
        $this->setZoneOrder($zoneOrder);

        return $this;
    }
}
