<?php

/**
 * @package Cache
 * @subpackage General
 */

/**
 * Fichier de Pelican_Cache : Mise en cache du resultat d'une Requ�te
 *
 * @package Cache
 * @subpackage General
 * @author Laurent Boulay <laurent.boulay@businessdecision.com>
 * @since 26/06/2013
 */
class Frontend_Url extends Pelican_Cache {
	
	/**
	 * Valeur ou objet à mettre en Pelican_Cache
	 */
	function getValue() {
		
		if ($this->params[0] && $this->params[0] != "/") {
		    $oConnection = Pelican_Db::getInstance();
		    
		    $aBind[":SITE_ID"] = $this->params[0];
		    $aBind[":URL"] = $oConnection->strToBind($this->params[1]);
		    $aBind[":URL2"] = $oConnection->strToBind($this->params[1].'/');
		    
		    $version = 'current';
		    if ($this->params[2]) {
		        $version = $this->params[2];
		    }
		    
		    $sSql = "
		        SELECT 
		            p.PAGE_ID as pid, 
		            0 as cid,
		            pv.PAGE_PROTOCOLE_HTTPS as HTTPS,
		            p.LANGUE_ID
		        FROM #pref#_page p
		            INNER JOIN #pref#_page_version pv 
		                ON (
		                    p.page_id = pv.page_id 
		                    and p.page_".$version."_version=pv.page_version 
		                    and p.langue_id = pv.langue_id
		                )
                WHERE p.SITE_ID = :SITE_ID
                AND
                (pv.page_clear_url = :URL 
                OR pv.page_clear_url = :URL2)
            UNION
                SELECT 
                    0 as pid, 
                    c.CONTENT_ID as cid, 
                    0 as HTTPS,
                    c.LANGUE_ID
		        FROM #pref#_content c
		            INNER JOIN #pref#_content_version cv 
		                ON (
		                    c.content_id = cv.content_id 
		                    and c.content_".$version."_version=cv.content_version 
		                    and c.langue_id = cv.langue_id
		                )
                WHERE c.SITE_ID = :SITE_ID 
                AND
                (cv.content_clear_url = :URL 
                OR cv.content_clear_url = :URL2)
            ";
            
            $result = $oConnection->queryRow($sSql, $aBind);
		}
		
		$this->value = $result;
	}
}
?>
