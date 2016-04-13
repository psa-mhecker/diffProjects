<?php
/**
 * Fichier de Pelican_Cache : Finitions
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_Finitions extends Pelican_Cache {

    var $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aLcdv6Gamme = $this->params[0];
        $aBind[':LCDV6'] = $oConnection->strToBind($aLcdv6Gamme['LCDV6']);
       $aBind[':GAMME'] = $oConnection->strToBind($aLcdv6Gamme['GAMME']);
        $aBind[':SITE_ID'] = $this->params[1];
        $aBind[':LANGUE_ID'] = $this->params[2];
		$sSQL = "SELECT
					*
					FROM
					#pref#_ws_finitions pws,
					(
						select GR_COMMERCIAL_NAME_CODE, PRICE_NUMERIC, PRICE_DISPLAY
						from #pref#_ws_prix_finition_version pwpfv
						where SITE_ID = :SITE_ID AND LANGUE_ID = :LANGUE_ID
						AND LCDV6 = :LCDV6
						AND PRICE_NUMERIC in (
												select min(PRICE_NUMERIC) as cpt
												from 
													#pref#_ws_prix_finition_version 												 
												where 
													SITE_ID = pwpfv.SITE_ID AND LANGUE_ID = pwpfv.LANGUE_ID
													AND LCDV6 = pwpfv.LCDV6 
													and GR_COMMERCIAL_NAME_CODE = pwpfv.GR_COMMERCIAL_NAME_CODE 
												group by GR_COMMERCIAL_NAME_CODE
											)
					) as pfv
					WHERE
					SITE_ID = :SITE_ID
					AND LANGUE_ID = :LANGUE_ID
					AND LCDV6 = :LCDV6";
					
			if( !empty($aLcdv6Gamme['GAMME']) ){
				$sSQL .= " AND GAMME = :GAMME ";
			}
			$sSQL .= " and pfv.GR_COMMERCIAL_NAME_CODE = pws.FINITION_CODE
			ORDER BY pfv.PRICE_NUMERIC ASC
		";
        $aFinitions = $oConnection->queryTab($sSQL, $aBind);
		if(is_array($aFinitions) && count($aFinitions)>0){
			foreach($aFinitions as $key=>$finition){
				//problème de valeur mini pour le prix affiché donc PRIMARY_DISPLAY_PRICE = PRICE_DISPLAY
				$aFinitions[$key]['PRIMARY_DISPLAY_PRICE'] = $aFinitions[$key]['PRICE_DISPLAY'];
				
				if($finition['PREVIOUS_FINITION_CODE'] != ''){
					$aBind[':PREVIOUS_FINITION_CODE'] = $oConnection->strToBind($finition['PREVIOUS_FINITION_CODE']);
						$sSQL = "
						SELECT
							FINITION_LABEL
						FROM
							#pref#_ws_finitions
						WHERE
							FINITION_CODE = :PREVIOUS_FINITION_CODE
						AND SITE_ID = :SITE_ID
						AND LANGUE_ID = :LANGUE_ID
						AND LCDV6 = :LCDV6
					";
					if( !empty($aLcdv6Gamme['GAMME']) ){
						$sSQL .= " AND GAMME = :GAMME ";
					}
					$aFinitions[$key]['FINITION_MERE'] = $oConnection->queryItem($sSQL, $aBind);
				}
				$aBind[':FINITION_CODE'] = $oConnection->strToBind($finition['FINITION_CODE']);
				if($finition['PREVIOUS_FINITION_CODE'] != ''){
					$sSQL = "
						SELECT
							*
						FROM
							#pref#_ws_equipement_option
						WHERE
							FINITION_CODE = :FINITION_CODE
						AND SITE_ID = :SITE_ID
						AND LANGUE_ID = :LANGUE_ID
						AND LCDV6 = :LCDV6";
						if( !empty($aLcdv6Gamme['GAMME']) ){
							$sSQL .= " AND GAMME = :GAMME ";
						}
				}else{
					$sSQL = "
						SELECT
							*
						FROM
							#pref#_ws_equipement_standard
						WHERE
							FINITION_CODE = :FINITION_CODE
						AND SITE_ID = :SITE_ID
						AND LANGUE_ID = :LANGUE_ID
						AND LCDV6 = :LCDV6";
						if( !empty($aLcdv6Gamme['GAMME']) ){
							$sSQL .= " AND GAMME = :GAMME ";
						}
				}
				$aFinitions[$key]['EQUIPEMENTS'] = $oConnection->queryTab($sSQL, $aBind);
				if($finition['V3D_LCDV'] != ''){
					$aFinitions[$key]['IMAGE'] = Pelican::$config["VISUEL_3D_PATH"]."?ratio=".Pelican::$config['VISUEL_3D_PARAM']['RATIO']."&version=".$finition['V3D_LCDV']."&quality=".Pelican::$config['VISUEL_3D_PARAM']['QUALITY']."&width=373&format=png&height=209&view=".Pelican::$config['VISUEL_3D_PARAM']['VIEW']."&client=".Pelican::$config['VISUEL_3D_PARAM']['CLIENT']."&trim=".$finition['V3D_INTERIOR']."&color=".$finition['V3D_EXTERIOR'];
					$aFinitions[$key]['IMAGE_MOBILE'] = Pelican::$config["VISUEL_3D_PATH"]."?ratio=".Pelican::$config['VISUEL_3D_PARAM']['RATIO']."&version=".$finition['V3D_LCDV']."&quality=".Pelican::$config['VISUEL_3D_PARAM']['QUALITY']."&width=200&format=png&height=200&view=".Pelican::$config['VISUEL_3D_PARAM']['VIEW']."&client=".Pelican::$config['VISUEL_3D_PARAM']['CLIENT']."&trim=".$finition['V3D_INTERIOR']."&color=".$finition['V3D_EXTERIOR'];
				}
			}
		}
		
        $this->value = $aFinitions;
    }
}