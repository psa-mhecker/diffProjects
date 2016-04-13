<?php

/**
 * Fichier de Pelican_Cache : Mes Selections Mon projet
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_MonProjet_InfosVehiculeByLcdv6 extends Pelican_Cache
{

	var $duration = DAY;

	function getValue(){
		$oConnection = Pelican_Db::getInstance();
		$aBind[':LCDV6'] =  $oConnection->strToBind($this->params[0]);
		$aBind[':GAMME'] =  $oConnection->strToBind($this->params[1]);
		$aBind[':SITE_ID'] = $this->params[2];
		$aBind[':LANGUE_ID'] = $this->params[3];
		$sSQL = 'SELECT v.VEHICULE_LCDV6_CONFIG,
						v.VEHICULE_LABEL,
						m.MEDIA_PATH

					FROM  #pref#_vehicule as v 
					LEFT JOIN #pref#_media as m ON v.VEHICULE_MEDIA_ID_THUMBNAIL = m.MEDIA_ID
					WHERE v.VEHICULE_LCDV6_CONFIG =:LCDV6
					AND v.VEHICULE_GAMME_MANUAL =:GAMME
					AND v.SITE_ID =:SITE_ID
					AND v.LANGUE_ID =:LANGUE_ID'
					;
		
		
		$aData = $oConnection->queryRow($sSQL, $aBind);
		//debug($aFavs);
		$this->value = $aData;
	}
}