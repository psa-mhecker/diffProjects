<?php
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page/Cta/Interface.php';

/**
 * Gestion des page zone multi.
 *
 * @author Pierre Pottié <pierre.pottie@businessdecision.com>
 *
 * @since 21/04/2015
 */
class Ndp_Page_Multi_Zone_Cta_Cta extends Ndp_Cta implements Ndp_Page_Cta_Interface
{
    const TABLE_NAME = 'page_multi_zone_cta_cta';

    /**
     * Retourne les données de la table page_zone_cta_cta
     * @return array
     */
    public function getValues()
    {
        $values = [];
        if ($this->getPageId() != self::PAGE_ID_NEW) {
            $bind[":PAGE_ID"]            = $this->getPageId();
            $bind[":LANGUE_ID"]          = $this->getLangueId();
            $bind[":PAGE_VERSION"]       = $this->getPageVersion();
            $bind[":AREA_ID"]            = $this->getAreaId();
            $bind[':ZONE_ORDER']         = $this->getZoneOrder();
            $bind[":PAGE_ZONE_CTA_TYPE"] = $this->getType();
            $bind[":PAGE_ZONE_CTA_ID"]   = $this->getParentId();
            $sql                         = 'SELECT c.*,pzcc.*
                FROM #pref#_'.self::TABLE_NAME.' pzcc
                 LEFT JOIN psa_cta c ON pzcc.cta_id = c.id and c.IS_REF = 0
                WHERE pzcc.PAGE_ID = :PAGE_ID
                AND pzcc.LANGUE_ID = :LANGUE_ID
                AND pzcc.PAGE_VERSION = :PAGE_VERSION
                AND pzcc.AREA_ID = :AREA_ID
                AND pzcc.ZONE_ORDER = :ZONE_ORDER
                AND pzcc.PAGE_ZONE_CTA_TYPE = ":PAGE_ZONE_CTA_TYPE"
                AND pzcc.PAGE_ZONE_CTA_ID = :PAGE_ZONE_CTA_ID
                ORDER BY pzcc.PAGE_ZONE_CTA_ORDER';
            $values = $this->connection->queryTab($sql, $bind);
        }

        return $values;
    }

   /**
    * 
    * @return \Ndp_Page_Multi_Zone_Cta_Cta
    */
    public function save()
    {
        $values = Pelican_Db::$values;

        if ($values['multi_display'] == self::MULTI_IS_DISPLAYED) {
            $values['LANGUE_ID']            = $this->getLangueId();
            $values['PAGE_VERSION']         = $this->getPageVersion();
            $values['PAGE_ID']              = $this->getPageId();
            $values['AREA_ID']              = $this->getAreaId();
            $values['ZONE_ORDER']           = $this->getZoneOrder();
            $values['PAGE_ZONE_CTA_TYPE']   = $this->getType();
            $values['PAGE_ZONE_CTA_ORDER']  = $this->getId();
            $values['PAGE_ZONE_CTA_CTA_ID'] = $this->getId();
            $values['PAGE_ZONE_CTA_ID']     = $this->getParentId();
            $values                         = $this->addFieldCta($values);

            Ndp_Cta::saveCta($values, self::TABLE_NAME);
        }

        return $this;
    }

    /**
     * Supprime des données dans la table page_zone_cta_cta
     * @return $this
     */
    public function delete()
    {
        $bind[':LANGUE_ID']            = $this->getLangueId();
        $bind[':PAGE_VERSION']         = $this->getPageVersion();
        $bind[':PAGE_ID']              = $this->getPageId();
        $bind[':AREA_ID']              = $this->getAreaId();
        $bind[':PAGE_ZONE_CTA_TYPE']   = $this->getType();
        $bind[':PAGE_ZONE_CTA_ID']     = $this->getParentId();
        $bind[':ZONE_ORDER']           = $this->getZoneOrder();
        $bind[':PAGE_ZONE_CTA_CTA_ID'] = $this->getId();


        if (Pelican_Db::$values['PAGE_ID'] != null && Pelican_Db::$values['PAGE_ID']
            != self::PAGE_ID_NEW) {
            $sql  = 'DELETE pzcc,c FROM #pref#_'.self::TABLE_NAME.' pzcc, psa_cta c ';
            $sql2 = 'DELETE pzcc FROM #pref#_'.self::TABLE_NAME.' pzcc';

            $where2 = ' AND pzcc.cta_id = c.id and c.IS_REF = 0';
            $where  = ' WHERE pzcc.LANGUE_ID = :LANGUE_ID
                    AND pzcc.PAGE_VERSION = :PAGE_VERSION
                    AND pzcc.PAGE_ID = :PAGE_ID
                    AND pzcc.ZONE_ORDER = :ZONE_ORDER
                    AND pzcc.AREA_ID = :AREA_ID
                    AND pzcc.PAGE_ZONE_CTA_TYPE = :PAGE_ZONE_CTA_TYPE
                    AND pzcc.PAGE_ZONE_CTA_ID = :PAGE_ZONE_CTA_ID
                    AND pzcc.PAGE_ZONE_CTA_CTA_ID = :PAGE_ZONE_CTA_CTA_ID';
            //  $this->connection->query($sql.$where.$where2, $bind);

            $this->connection->query($sql2.$where, $bind);
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
     * 
     * @return \Page_Zone_Cta_Cta
     */
    public function setValues($pageId, $langueId, $pageVersion, $areaId, $ctaType, $ctaId, $zoneOrder)
    {
        $this->setPageId($pageId);
        $this->setLangueId($langueId);
        $this->setPageVersion($pageVersion);
        $this->setAreaId($areaId);
        $this->setCtaType($ctaType);
        $this->setCtaId($ctaId);
        $this->setZoneOrder($zoneOrder);

        return $this;
    }
}
