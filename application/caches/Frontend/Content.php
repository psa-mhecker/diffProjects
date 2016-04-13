<?php
/**
 * @package Pelican_Cache
 * @subpackage Pelican
 */

require_once(pelican_path('Media'));

/**
 * Fichier de Pelican_Cache : Données associées à un contenu
 *
 * @package Pelican_Cache
 * @subpackage Pelican
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 16/12/2004
 */
class Frontend_Content extends Pelican_Cache
{

    
    var $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue()
    {
        
        
        $oConnection = Pelican_Db::getInstance();
        
        $aBind[":CONTENT_ID"] = $this->params[0];
        $aBind[":SITE_ID"] = $this->params[1];
        $aBind[":LANGUE_ID"] = $this->params[2];
        if ($this->params[3]) {
            $type_version = $this->params[3];
        } else {
            $type_version = "CURRENT";
        }
        $aBind[":DATE"] = $oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP')));
        
        /** donnees globales */
        $strSql = "
				SELECT c.*,
				cv.*,
				cc.*,
				m.MEDIA_PATH,
				m.MEDIA_ALT,
				ct.CONTENT_TYPE_ID as CONTENT_TYPE_ID,
				" . $oConnection->dateSqlToString("cv.CONTENT_PUBLICATION_DATE ", false) . " as CONTENT_PUBLICATION_DATE,
				" . $oConnection->dateSqlToString("cv.CONTENT_DATE ", false) . " as CONTENT_DATE,
				" . $oConnection->dateSqlToString("cv.CONTENT_START_DATE ", false) . " as CONTENT_START_DATE,
				" . $oConnection->dateSqlToString("cv.CONTENT_END_DATE ", false) . " as CONTENT_END_DATE
				FROM #pref#_content c
				inner join #pref#_content_version cv on (c.CONTENT_ID=cv.CONTENT_ID AND c.CONTENT_" . $type_version . "_VERSION=cv.CONTENT_VERSION AND c.LANGUE_ID=cv.LANGUE_ID)
				inner join #pref#_content_type ct on (c.CONTENT_TYPE_ID=ct.CONTENT_TYPE_ID)
				left join #pref#_content_category cc on (cv.CONTENT_CATEGORY_ID=cc.CONTENT_CATEGORY_ID)
				left join #pref#_media m on (cv.MEDIA_ID = m.MEDIA_ID)
				WHERE
				cv.CONTENT_ID = :CONTENT_ID
				AND c.SITE_ID = :SITE_ID
				AND c.LANGUE_ID = :LANGUE_ID
				AND ".$oConnection->getNVLClause("cv.CONTENT_START_DATE",$oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP'),mktime(0, 0, 0, 1, 1, 1970))))." <= :DATE
				AND ".$oConnection->getNVLClause("cv.CONTENT_END_DATE",$oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP'),mktime(0, 0, 0, 1, 1, 2038))))." >= :DATE
				";
        $result = $oConnection->queryRow($strSql, $aBind);
        echo $strSql;
        echo $aBind;
        $result["MEDIA_PATH_OPTIMISED"] = Pelican_Media::getFileNameMediaFormat($result["MEDIA_PATH"], '0');

        $aBind[":CONTENT_VERSION"] = $result["CONTENT_VERSION"];
        
        /** taxonomy */
        $aBind[":OBJECT_ID"] = $this->params[0];
        $query = "select t.TERMS_ID,
        		t.TERMS_NAME 
        		from #pref#_terms t
        		inner join #pref#_terms_relationships tr on ( tr.TERMS_ID=t.TERMS_ID)
        		inner join #pref#_object_type ot on (ot.OBJECT_TYPE_ID = tr.OBJECT_TYPE_ID)
        		where
        		 tr.OBJECT_ID=:OBJECT_ID 
        		 and ot.OBJECT_TYPE_FIELD = 'CONTENT_ID'
        		 order by TERMS_ORDER";
        
        $result["TAGS"] = $oConnection->queryTab($query, $aBind);

        /** zones */
        $strSql = "SELECT
				cz.*,
				m.*,
				cz.ZONE_LAYOUT_ID,ZONE_LAYOUT_LABEL,ZONE_LAYOUT_HEAD,ZONE_LAYOUT_FOOT
				 FROM #pref#_content_zone cz
				 	left join #pref#_zone_layout czt on (cz.ZONE_LAYOUT_ID=czt.ZONE_LAYOUT_ID)
				 	left join #pref#_media m on (cz.MEDIA_ID=m.MEDIA_ID)
				 WHERE
				 cz.CONTENT_ID = :CONTENT_ID
				 AND cz.CONTENT_VERSION = :CONTENT_VERSION
				 AND cz.LANGUE_ID = :LANGUE_ID
				 ORDER BY CONTENT_ZONE_ID";
        $aContentZone = $oConnection->queryTab($strSql, $aBind);
        
        if (! empty($aContentZone)) {
            foreach ($aContentZone as $key => $data) {
                $data["MEDIA_PATH_OPTIMISED"] = Pelican_Media::getFileNameMediaFormat($aContentZone[$key]["MEDIA_PATH"], '0');
                
                $aBind[":CONTENT_ZONE_ID"] = $data["CONTENT_ZONE_ID"];
                
                $strSql = "select c.CONTENT_ID ID, cv.CONTENT_TITLE, cv.CONTENT_CLEAR_URL, cv.CONTENT_TITLE_URL
			                FROM #pref#_content c, #pref#_content_version cv, #pref#_content_content_version ccv
			                WHERE ccv.CONTENT_CHILD_ID    = c.CONTENT_ID
			                AND  ccv.CONTENT_ID       = :CONTENT_ID
			                AND  ccv.CONTENT_VERSION    = :CONTENT_VERSION
			                AND  ccv.LANGUE_ID       = :LANGUE_ID
			                AND  c.CONTENT_ID        = cv.CONTENT_ID
			                AND  c.CONTENT_CURRENT_VERSION = cv.CONTENT_VERSION
			                AND  cv.LANGUE_ID        = :LANGUE_ID
			                AND  ccv.CONTENT_CHILD_TYPE = :CONTENT_ZONE_ID";
                $aArticlesAssocies = $oConnection->queryTab($strSql, $aBind);
                
                if ($aArticlesAssocies) {
                    $nb = count($aArticlesAssocies);
                    for ($i = 0; $i < $nb; $i ++) {
                        $aArticlesAssocies[$i]["CONTENT_CLEAR_URL"] = ($aArticlesAssocies[$i]["CONTENT_CLEAR_URL"] ? $aArticlesAssocies[$i]["CONTENT_CLEAR_URL"] : makeClearUrl($aArticlesAssocies[$i]["CONTENT_ID"], "cid", $aArticlesAssocies[$i]["CONTENT_TITLE"]));
                        $aArticlesAssocies[$i]["TITLE_URL"] = strip_tags($aArticlesAssocies[$i]["CONTENT_TITLE_URL"]);
                    }
                    if ($nb > 0) {
                        if ($nb % 2 == 0) {
                            $aArticlesAssocies = array_chunk($aArticlesAssocies, sizeof($aArticlesAssocies) / 2, false);
                        } else {
                            $aArticlesAssocies = array_chunk($aArticlesAssocies, 1 + sizeof($aArticlesAssocies) / 2, false);
                        }
                    }
                    foreach ($aArticlesAssocies as $article) {
                        $data["articlesAssocies"][] = $article;
                    }
                }
                if ($data["MEDIA_TYPE_ID"] == "video") {
                    $data["flash"] = Pelican_Media::getFlashPlayer($data["CONTENT_ZONE_ID"], $data["MEDIA_ID"], $data["CONTENT_ZONE_TITLE2"]);
                }
                $result[$data["CONTENT_ZONE_TYPE"]][] = $data;
            }
        }
        
        $this->value = $result;
    }
}
?>