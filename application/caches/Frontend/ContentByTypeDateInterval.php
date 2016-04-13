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

require_once(Pelican::$config['APPLICATION_LIBRARY']."/Teletoon/Pagination.php");

class Frontend_ContentByTypeDateInterval extends Pelican_Cache {

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

        $aBind[":START_DATE"] = $this->params[4];
        $aBind[":END_DATE"] = $this->params[5];
        
        $aBind[":DATE"] = $oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP')));
        
        if($this->params[6] == 'list')
        {
        
        	$nb_items_per_page = $this->params[7][0];
			$page_min = $this->params[7][1];
			$item_count = $this->params[7][2];
			$current_page = $this->params[7][3];
			
	        /** donnees globales */
	        $sSQL = "
					SELECT c.*,
					cv.*,
					cc.*,
					m.MEDIA_PATH,
					m.MEDIA_ALT,
					cn.NB_POINT,
					cn.NB_NOTE,
					" . $oConnection->dateSqlToString("c.CONTENT_CREATION_DATE ", false) . " as CONTENT_CREATION_DATE,
					" . $oConnection->dateSqlToString("cv.CONTENT_PUBLICATION_DATE ", false) . " as CONTENT_PUBLICATION_DATE,
					" . $oConnection->dateSqlToString("cv.CONTENT_DATE ", false) . " as CONTENT_DATE,
					" . $oConnection->dateSqlToString("cv.CONTENT_START_DATE ", false) . " as CONTENT_START_DATE,
					" . $oConnection->dateSqlToString("cv.CONTENT_END_DATE ", false) . " as CONTENT_END_DATE
					FROM #pref#_content c
					inner join #pref#_content_version cv on (c.CONTENT_ID=cv.CONTENT_ID AND c.CONTENT_" . $type_version . "_VERSION=cv.CONTENT_VERSION AND c.LANGUE_ID=cv.LANGUE_ID)
					left join #pref#_content_note cn on (c.CONTENT_ID=cn.CONTENT_ID)
					inner join #pref#_content_type ct on (c.CONTENT_TYPE_ID=ct.CONTENT_TYPE_ID)
					left join #pref#_content_category cc on (cv.CONTENT_CATEGORY_ID=cc.CONTENT_CATEGORY_ID)
					left join #pref#_media m on (cv.MEDIA_ID = m.MEDIA_ID)
					WHERE
					c.CONTENT_TYPE_ID = :CONTENT_TYPE_ID
					AND c.SITE_ID = :SITE_ID
					AND c.LANGUE_ID = :LANGUE_ID
					AND CONTENT_CODE = 1
					AND ".$oConnection->getNVLClause("cv.CONTENT_START_DATE",$oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP'),mktime(0, 0, 0, 1, 1, 1970))))." < :DATE
					AND ".$oConnection->getNVLClause("cv.CONTENT_END_DATE",$oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP'),mktime(0, 0, 0, 1, 1, 2038))))." >= :DATE
					AND ".$oConnection->getNVLClause("c.CONTENT_CREATION_DATE",$oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP'),mktime(0, 0, 0, 1, 1, 2038))))." < ':END_DATE'
	                AND ".$oConnection->getNVLClause("c.CONTENT_CREATION_DATE",$oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP'),mktime(0, 0, 0, 1, 1, 1970))))." >= ':START_DATE'
					ORDER BY c.CONTENT_CREATION_DATE DESC, c.CONTENT_ID 
					";
	        //$result = $oConnection->queryTab($sSQL, $aBind);
	        
	    		
			    
			$sSQL = $oConnection->getLimitedSQL($sSQL, $nb_items_per_page * $current_page+1, $nb_items_per_page);
				
			$result = $oConnection->queryTab($sSQL, $aBind);
			
			if (is_array($result))
			{
				foreach($result as $key => $content)
				{
					$dateBits = explode("/", $content["CONTENT_CREATION_DATE"]);
					if (is_array($dateBits) && count($dateBits)>2){
						$result[$key]["DATE_FR"] = utf8_encode(strftime("%e %B", mktime(0, 0, 0, $dateBits[1], $dateBits[0], $dateBits[2])));
	
					}
				}
			}

			
	        $this->value["sql"] = $sSQL;
			$this->value["liste"] = $result;
			$this->value["pagination"] = Teletoon_Pagination::getPagination($item_count, $nb_items_per_page, $current_page, 10);
				
        } elseif($this->params[6] == 'count') {
        	
        	$strSql = "
					SELECT c.CONTENT_ID
					FROM #pref#_content c
					inner join #pref#_content_version cv on (c.CONTENT_ID=cv.CONTENT_ID AND c.CONTENT_" . $type_version . "_VERSION=cv.CONTENT_VERSION AND c.LANGUE_ID=cv.LANGUE_ID)
					left join #pref#_content_note cn on (c.CONTENT_ID=cn.CONTENT_ID)
					inner join #pref#_content_type ct on (c.CONTENT_TYPE_ID=ct.CONTENT_TYPE_ID)
					left join #pref#_content_category cc on (cv.CONTENT_CATEGORY_ID=cc.CONTENT_CATEGORY_ID)
					left join #pref#_media m on (cv.MEDIA_ID = m.MEDIA_ID)
					WHERE
					c.CONTENT_TYPE_ID = :CONTENT_TYPE_ID
					AND c.SITE_ID = :SITE_ID
					AND c.LANGUE_ID = :LANGUE_ID
					AND CONTENT_CODE = 1 
					AND ".$oConnection->getNVLClause("cv.CONTENT_START_DATE",$oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP'),mktime(0, 0, 0, 1, 1, 1970))))." < :DATE
					AND ".$oConnection->getNVLClause("cv.CONTENT_END_DATE",$oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP'),mktime(0, 0, 0, 1, 1, 2038))))." >= :DATE
					AND ".$oConnection->getNVLClause("c.CONTENT_CREATION_DATE",$oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP'),mktime(0, 0, 0, 1, 1, 2038))))." < ':END_DATE'
	                AND ".$oConnection->getNVLClause("c.CONTENT_CREATION_DATE",$oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP'),mktime(0, 0, 0, 1, 1, 1970))))." >= ':START_DATE'
					ORDER BY c.CONTENT_CREATION_DATE DESC, c.CONTENT_ID 
					";
	        $result = $oConnection->queryTab($strSql, $aBind);
	        
	    		 
			if (is_array($result))
			{
				foreach($result as $key => $content)
				{
					$dateBits = explode("/", $content["CONTENT_START_DATE"]);
					if (is_array($dateBits) && count($dateBits)>2){
						$result[$key]["DATE_FR"] = utf8_encode(strftime("%e %B", mktime(0, 0, 0, $dateBits[1], $dateBits[0], $dateBits[2])));
	
					}
				}
			}
        	
        	
        	$result =  count($result);
			$this->value = $result;
			
        } elseif($this->params[6] == 'all') {
        	$sSQL = "
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
					AND ".$oConnection->getNVLClause("cv.CONTENT_START_DATE",$oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP'),mktime(0, 0, 0, 1, 1, 1970))))." < :DATE
					AND ".$oConnection->getNVLClause("cv.CONTENT_END_DATE",$oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP'),mktime(0, 0, 0, 1, 1, 2038))))." >= :DATE
					AND ".$oConnection->getNVLClause("c.CONTENT_CREATION_DATE",$oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP'),mktime(0, 0, 0, 1, 1, 2038))))." < ':END_DATE'
	                AND ".$oConnection->getNVLClause("c.CONTENT_CREATION_DATE",$oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP'),mktime(0, 0, 0, 1, 1, 1970))))." >= ':START_DATE'
	                    
					ORDER BY c.CONTENT_CREATION_DATE DESC, c.CONTENT_ID
					";
	        	
			$result = $oConnection->queryTab($sSQL, $aBind);
			
			if (is_array($result))
			{
				foreach($result as $key => $content)
				{
					$dateBits = explode("/", $content["CONTENT_START_DATE"]);
					if (is_array($dateBits) && count($dateBits)>2){
						$result[$key]["DATE_FR"] = utf8_encode(strftime("%e %B", mktime(0, 0, 0, $dateBits[1], $dateBits[0], $dateBits[2])));
	
					}
				}
			}
			
			$this->value = $result;
			
        }
    }
}
	
?>