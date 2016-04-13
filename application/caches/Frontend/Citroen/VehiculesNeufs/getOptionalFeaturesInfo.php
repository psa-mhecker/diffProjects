<?php

/**
 * Fichier de Pelican_Cache : Retour WS CarStore
 * @package Cache
 * @subpackage Pelican
 */
use Citroen\Vehicules;

class Frontend_Citroen_VehiculesNeufs_getOptionalFeaturesInfo extends Pelican_Cache
{

	var $duration = DAY;

	/**
	 * Valeur ou objet à mettre en Pelican_Cache
	 */
	function getValue()
	{
		
		
		$sPays = $this->params[0];
		$sLanguageCode = $this->params[1];
		$sCarNum = $this->params[2];
		
		$aResult = Vehicules::getOptionalFeaturesInfo($sPays, $sLanguageCode, $sCarNum);
		
		$aReturn = array();
		if (is_array($aResult['OptionalFeaturesList']['GetOptionalFeaturesInfoType']) && sizeof($aResult['OptionalFeaturesList']['GetOptionalFeaturesInfoType']) > 0 ) {
			foreach ($aResult['OptionalFeaturesList']['GetOptionalFeaturesInfoType'] as $iKey => $aFeature) {
				$aReturn[$iKey] = $aFeature['Label'];
			}
		}
		
		$this->value = $aReturn;
	}

}
