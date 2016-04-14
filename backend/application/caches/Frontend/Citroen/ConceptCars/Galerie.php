<?php
/**
 * Fichier de Pelican_Cache : Galerie Concept Cars.
 */
class Frontend_Citroen_ConceptCars_Galerie extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet � mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        $sVersion = ($this->params[2]) ? $this->params[2] : 'CURRENT';
        $aBind[':PAGE_PARENT_ID'] = $this->params[3];
        $aBind[':TEMPLATE_PAGE_ID'] = Pelican::$config['TEMPLATE_PAGE']['CONCEPT_CAR_DETAIL'];
        $aBind[':ZONE_ID'] = Pelican::$config['ZONE']['VISUEL_CINEMASCOPE_CONCEPT_CAR'];
        $sSQL = "
            select
                pv.PAGE_TITLE,
                pv.PAGE_CLEAR_URL,
                m.MEDIA_PATH,
                m.MEDIA_ALT
            from #pref#_page p
            inner join #pref#_page_version pv
                on (p.PAGE_ID = pv.PAGE_ID
                    and p.PAGE_".$sVersion."_VERSION = pv.PAGE_VERSION
                    and p.LANGUE_ID = pv.LANGUE_ID)
            inner join #pref#_page_zone pz
                on (pz.PAGE_ID = pv.PAGE_ID
                    and pz.PAGE_VERSION = pv.PAGE_VERSION
                    and pz.LANGUE_ID = pv.LANGUE_ID)
            inner join #pref#_zone_template zt
                on (zt.ZONE_TEMPLATE_ID = pz.ZONE_TEMPLATE_ID)
            inner join #pref#_media m
                on (m.MEDIA_ID = pz.MEDIA_ID)
            where pv.TEMPLATE_PAGE_ID = :TEMPLATE_PAGE_ID
            and p.PAGE_PARENT_ID = :PAGE_PARENT_ID
            and zt.ZONE_ID = :ZONE_ID
            and p.SITE_ID = :SITE_ID
            and p.LANGUE_ID = :LANGUE_ID
            and p.PAGE_STATUS = 1
            and pv.STATE_ID = 4
            order by p.PAGE_ORDER asc";

        $aResults = $oConnection->queryTab($sSQL, $aBind);
        $this->value = $aResults;
    }
}
