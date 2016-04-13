<?php
/**
	* @package Pelican_Cache
	* @subpackage Pelican
	*/


/**
	* Fichier de Pelican_Cache : dernier contenu du jour, ou dernier contenu jusqu'au jour si $mostRecent
	*
	* @package Pelican_Cache
	* @subpackage Pelican
	* @author Renaud Delcoigne <renaud.delcoigne@businessdecision.com>
	* @since 27/07/2011
	*/

class Frontend_ContentTypeByDate extends Pelican_Cache {

    var $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue()
    {

        $oConnection = Pelican_Db::getInstance();
        
		$aBind[":SITE_ID"] = $this->params[0];
		$aBind[":LANGUE_ID"] = $this->params[1];
		$aBind[":CONTENT_TYPE_ID"] = $this->params[2];
        if ($this->params[3]) {
            $type_version = $this->params[3];
        } else {
            $type_version = "CURRENT";
        }

        $aBind[":DATE"] = $this->params[4];
        
        $aBind[":NOW"] = $oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP')));
        
        $mostRecent = $this->params[5];
        
        $equality = "<=";
        if (!$mostRecent)  $equality = "=";
        
        /** donnees globales */
        $strSql = "
				SELECT c.*,
				cv.*,
				cc.*,
				m.MEDIA_PATH,
				m.MEDIA_ALT,
				" . $oConnection->dateSqlToString("c.CONTENT_CREATION_DATE ", false) . " as CONTENT_CREATION_DATE,
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
				c.CONTENT_TYPE_ID = :CONTENT_TYPE_ID 
				AND c.SITE_ID = :SITE_ID
				AND c.LANGUE_ID = :LANGUE_ID 
				AND CONTENT_CODE = 1 
				AND ".$oConnection->getNVLClause("cv.CONTENT_START_DATE",$oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP'),mktime(0, 0, 0, 1, 1, 1970))))." < :NOW
				AND ".$oConnection->getNVLClause("cv.CONTENT_END_DATE",$oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP'),mktime(0, 0, 0, 1, 1, 2038))))." >= :NOW
				AND ".$oConnection->getNVLClause("c.CONTENT_CREATION_DATE",$oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP'),mktime(0, 0, 0, 1, 1, 1970))))." ".$equality." ':DATE'
				ORDER BY c.CONTENT_CREATION_DATE DESC, c.CONTENT_ID DESC
				LIMIT 1
				";
        
        //debug(strtr($strSql, $aBind));

        $result = $oConnection->queryRow($strSql, $aBind);
        
        
        
        
        $this->value = $result;
    }
}
	
?>