<?php

/**
 * Fichier de Pelican_Cache : Retour WS CarStore
 * @package Cache
 * @subpackage Pelican
 */
use Citroen\Vehicules;

class Frontend_Citroen_VehiculesNeufs_getStockWebstore extends Pelican_Cache
{

	var $duration = DAY;

	/*
	 * Valeur ou objet à mettre en Pelican_Cache
	 */

	function getValue()
	{
		$iStoreId = $this->params[0];
		$sLangue = $this->params[1];
		$sLanguageCode = $this->params[2];
		$iStart = $this->params[3];
		$iLimit = $this->params[4];
		$sStoreRRDI = $this->params[5];
        $sModelCode = $this->params[6];
        $sBodyCode = $this->params[7];
		$sDisponibilite = t('DISPO_SOUS');
		$sEconomie = t('SOIT_ECO');
		$aResult = Vehicules::getStockWebstore($sLangue, $sLanguageCode, $iStoreId, $sStoreRRDI, $sModelCode, $sBodyCode);
		$aReturn = array();
		if ($aResult['CARS'] && is_array($aResult['CARS']) && count($aResult['CARS']) > 0) {
			$aReturn['STORE_URL'] = $aResult['STORE_URL'];
            $aReturn['COUNT'] = count($aResult['CARS']);
			$aReturn['CARS'] = array_slice($aResult['CARS'], $iStart, $iLimit);
			foreach ($aReturn['CARS'] as $key => $car) {
				$sPriceSale = 0;
				$iPourcentage = 0;
				$aReturn['CARS'][$key]['DISPONIBLE_SOUS'] = str_replace('#STOCK#', $car['VehicleAvailability'], $sDisponibilite);
				if ($car['VehicleWebstorePrice'] != 0 && $car['VehiclePriceCatalogue'] != 0) {
					$sPriceSale = $car['VehiclePriceCatalogue'] - $car['VehicleWebstorePrice'];
					$iPourcentage = round(($sPriceSale * 100) / $car['VehiclePriceCatalogue'], 2);
					$aReturn['CARS'][$key]['SOIT_ECO'] = str_replace('#PRICE#', $sPriceSale, $sEconomie);
					$aReturn['CARS'][$key]['POURCENTAGE'] = $iPourcentage;
				}
			}
		}

		$this->value = $aReturn;
	}

}
