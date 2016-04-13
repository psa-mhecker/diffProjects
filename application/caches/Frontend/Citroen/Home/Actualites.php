<?php
/**
 * Fichier de Pelican_Cache : Actualités Home
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_Home_Actualites extends Pelican_Cache {

    var $duration = HOUR;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance ();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        $sVersion = ($this->params[2]) ? $this->params[2] : "CURRENT";    
        $aBind[':CONTENT_TYPE_ID'] = Pelican::$config['CONTENT_TYPE_ID']['ACTUALITE'];
        $aBind[':CONTENT_STATUS'] = 1;
        $aBind[':PAGE_STATUS'] = 1;
        $aBind[':TEMPLATE_PAGE_ID'] = Pelican::$config['TEMPLATE_PAGE']['ACTU_DETAIL'];
        $aBind[':ZONE_ID'] = Pelican::$config['ZONE']['ACTU_DETAIL'];
        $sSQL = "
            select
                pv.PAGE_CLEAR_URL,
                cv.CONTENT_TITLE,
                cv.CONTENT_TEXT2,
                cv.CONTENT_CODE2,
                cv.CONTENT_DIRECT_HOME,
                cv.CONTENT_DIRECT_PAGE,
                m.MEDIA_ID,
                m.MEDIA_PATH,
                m.MEDIA_ALT,
                DATE_FORMAT(cv.CONTENT_DATE2, '%d/%m/%Y') as DATE_FR,
                DATE_FORMAT(cv.CONTENT_DATE2, '%m/%d/%Y') as DATE_UK
            from #pref#_page p
            inner join #pref#_page_version pv
                on (pv.PAGE_ID = p.PAGE_ID
                    and pv.LANGUE_ID = p.LANGUE_ID
                    and pv.PAGE_VERSION = p.PAGE_" . $sVersion . "_VERSION)
            inner join #pref#_zone_template zt
                on (zt.TEMPLATE_PAGE_ID = pv.TEMPLATE_PAGE_ID)
            inner join #pref#_page_zone pz
                on (pz.PAGE_ID = pv.PAGE_ID
                    and pz.LANGUE_ID = pv.LANGUE_ID
                    and pz.PAGE_VERSION = pv.PAGE_VERSION
                    and pz.ZONE_TEMPLATE_ID = zt.ZONE_TEMPLATE_ID)
            inner join #pref#_content c
                on (c.CONTENT_ID = pz.CONTENT_ID)
            inner join #pref#_content_version cv
                on (cv.CONTENT_ID = c.CONTENT_ID
                    and cv.CONTENT_VERSION = c.CONTENT_" . $sVersion . "_VERSION
                    and cv.LANGUE_ID = c.LANGUE_ID)
            left join #pref#_media m
                on (m.MEDIA_ID = cv.MEDIA_ID)
            where pv.TEMPLATE_PAGE_ID = :TEMPLATE_PAGE_ID
            and zt.ZONE_ID = :ZONE_ID
            and p.SITE_ID = :SITE_ID
            and p.PAGE_STATUS = 1
            and pv.STATE_ID = 4
            and p.LANGUE_ID = :LANGUE_ID
            and c.CONTENT_TYPE_ID = :CONTENT_TYPE_ID
            and c.SITE_ID = :SITE_ID
            and c.LANGUE_ID = :LANGUE_ID
            and c.CONTENT_STATUS = :CONTENT_STATUS 
            and p.PAGE_STATUS = :PAGE_STATUS
            
        ";

        if($this->params[3]){
         $aBind[':CONTENT_DIRECT_HOME'] = $this->params[3];  
         $sSQL .= " and cv.CONTENT_DIRECT_HOME = :CONTENT_DIRECT_HOME ";
        }
        
        $sSQL .= "and IF(cv.CONTENT_START_DATE IS NULL, FALSE, cv.CONTENT_START_DATE < now()) "
            ."and IF(cv.CONTENT_END_DATE IS NULL, TRUE, cv.CONTENT_END_DATE > now()) "
            ."AND IF(pv.PAGE_START_DATE IS NULL, TRUE, pv.PAGE_START_DATE < now()) "
            ."AND IF(pv.PAGE_END_DATE IS NULL, TRUE, pv.PAGE_END_DATE > now()) ";

        $sSQL .= "
        and cv.CONTENT_DATE2 < now()
        order by cv.CONTENT_START_DATE desc";
        
        $sSQL = $oConnection->getLimitedSQL($sSQL, 1, 4, true, $aBind);
        $aResults = $oConnection->queryTab($sSQL, $aBind);
        $this->value = $aResults;
    }
}