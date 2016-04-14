<?php
/**
 * Fichier de Pelican_Cache : FilAriane.
 */
class Frontend_Citroen_HeritageGrandVisuel extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet � mettre en Pelican_Cache
     */
    public function getValue()
    {
        $pageId = $this->params[0];
        $langueId = $this->params[1];
        $version = ($this->params[2]) ? $this->params[2] : "CURRENT";
        while (!$aResults) {
            $aTemp = $this->getPageZone($pageId, $langueId, $version);
            if (!$aTemp['ZONE_TITRE11']) {
                $aResults = $aTemp;
            }
            if (!$aTemp['PAGE_PARENT_ID']) {
                break;
            }
        }
        $this->value = $aResults;
    }

    public function getPageZone($pageId, $langueId, $version)
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':PAGE_ID'] = $pageId;
        $aBind[':LANGUE_ID'] = $langueId;
        $aBind[':ZONE_ID'] = Pelican::$config['ZONE']['CONTENT_GRAND_VISUEL'];
        $sSQL = "
            select pz.*
            from psa_page p
            inner join psa_page_version pv
                on (pv.PAGE_ID = p.PAGE_ID
                    and pv.LANGUE_ID = p.LANGUE_ID
                    and pv.PAGE_VERSION = p.PAGE_".$version."_VERSION)
            inner join psa_zone_template zt
                on (zt.TEMPLATE_PAGE_ID = pv.TEMPLATE_PAGE_ID)
            inner join psa_page_zone pz
                on (pz.PAGE_ID = pv.PAGE_ID
                    and pz.LANGUE_ID = pv.LANGUE_ID
                    and pz.PAGE_VERSION = pv.PAGE_VERSION
                    and pz.ZONE_TEMPLATE_ID = zt.ZONE_TEMPLATE_ID)
            where zt.ZONE_ID = :ZONE_ID
            and p.LANGUE_ID = :LANGUE_ID
            and p.PAGE_ID = :PAGE_ID
            and p.PAGE_STATUS = 1
            and pv.STATE_ID = 4";

        return $oConnection->queryRow($sSQL, $aBind);
    }
}
