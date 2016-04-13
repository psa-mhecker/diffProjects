<?php

/**
 * Fichier de Pelican_Cache
 *
 * @package Pelican_Cache
 * @subpackage Page
 * @since 30/01/2015
 */
 
class Frontend_Page_ZoneMultiByPageId extends Pelican_Cache
{
        var $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue ()
    {

        $oConnection = Pelican_Db::getInstance();
		$aBind[":PAGE_ID"] = $this->params[0];
        $aBind[":SITE_ID"] = $this->params[1];
        $aBind[":LANGUE_ID"] = $this->params[2];
        if ($this->params[3]) {
            $type_version = $this->params[3];
        } else {
            $type_version = "CURRENT";
        }
		
		
		$sSql=' SELECT PP.* ,PPMZ.*,PV.*
			    FROM #pref#_page PP 
				INNER JOIN #pref#_page_version PV ON (PP.PAGE_ID=PV.PAGE_ID AND PP.LANGUE_ID = PV.LANGUE_ID AND PP.PAGE_'.$type_version.'_VERSION=PV.PAGE_VERSION)
				INNER JOIN #pref#_page_multi_zone PPMZ ON (PPMZ.PAGE_ID = PP.PAGE_ID AND  PPMZ.PAGE_VERSION = PP.PAGE_'.$type_version.'_VERSION AND PPMZ.LANGUE_ID = PP.LANGUE_ID )  
				WHERE 
				PP.PAGE_ID = :PAGE_ID 
				AND PP.LANGUE_ID = :LANGUE_ID 
				AND PP.SITE_ID = :SITE_ID 
				AND PP.PAGE_STATUS = 1';
				
				
		 $this->value = $oConnection->queryRow($sSql, $aBind);
 
	}
}