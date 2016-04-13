<?php
/**
 * Created by PhpStorm.
 * User: kmessaoudi
 * Date: 22/04/14
 * Time: 14:22
 */

include("config.php");
$oConnection = Pelican_Db::getInstance();
if(is_numeric($_GET['siteID'])){
    $aBind[':SITE_ID'] = $_GET['siteID'];
    $sql = "
        SELECT
            sl.LANGUE_ID,
            l.LANGUE_CODE
        FROM
            #pref#_language l
                INNER JOIN #pref#_site_language sl ON (l.LANGUE_ID = sl.LANGUE_ID)
        WHERE
            sl.SITE_ID = :SITE_ID
    ";
    $languages = $oConnection->queryTab($sql, $aBind);
    if(is_array($languages) && count($languages)>0){
        foreach($languages as $language){
            $aBind[':LANGUE_ID'] = $language['LANGUE_ID'];
            $aBind[':STATE_ID'] = 5;
            $sql = "
                    SELECT
                        pv.PAGE_CLEAR_URL,
                        pv.LANGUE_ID,
                        pv.PAGE_ID
                    FROM
                        #pref#_page  p
                    INNER JOIN #pref#_page_version pv ON (p.PAGE_ID = pv.PAGE_ID and p.LANGUE_ID = pv.LANGUE_ID)
                    WHERE
                        p.SITE_ID = :SITE_ID
                    and pv.LANGUE_ID = :LANGUE_ID
                    and pv.STATE_ID <> :STATE_ID
            ";
            $urls = $oConnection->queryTab($sql, $aBind);
            if(is_array($urls) && count($urls)>0){
                foreach($urls as $url){
                    if(substr($url['PAGE_CLEAR_URL'],0,3) != '/'.$language['LANGUE_CODE'] && $url['PAGE_CLEAR_URL'] != '' && substr($url['PAGE_CLEAR_URL'],0,4) != 'http'){
                        $aBindUrl = array();
                        $aBindUrl[':PAGE_CLEAR_URL'] = $oConnection->strToBind('/'.$language['LANGUE_CODE'].$url['PAGE_CLEAR_URL']);
                        $aBindUrl[':LANGUE_ID'] = $url['LANGUE_ID'];
                        $aBindUrl[':PAGE_ID'] = $url['PAGE_ID'];
                        debug($aBindUrl);
                        $sql = "UPDATE #pref#_page_version SET PAGE_CLEAR_URL = :PAGE_CLEAR_URL where PAGE_ID = :PAGE_ID and LANGUE_ID = :LANGUE_ID";
                        $oConnection->query($sql,$aBindUrl);
                    }
                }
            }
        }
    }

}else{
     echo "merci de passer en siteID dans l'url";
}