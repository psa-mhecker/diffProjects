<?php

namespace Citroen;

use Citroen\Service\Webstore;

/**
 * Class Vehicules gérant les appels vers WS CarStock
 *
 * @author Khadidja Messaoudi <khadidja.messaoudi@businessdecision.com>
 *
 */
class Vehicules
{

	/**
	 * Appel WS CarStock : getStockWebstore
	 *
	 * @param string $sPays : Pays (ex : FR)
	 * @param string $sLocale : Code pays (ex : fr-FR)
	 * @param int $iStoreId : Identifiant du store (idsiteGeol)
	 * @return array $aVehicules tableau de véhicules
	 */
	public static function getStockWebstore($sPays, $sLocale, $iStoreId, $sStoreRRDI, $modelCode, $bodyStyleCode)
	{
		$serviceParams = array(
				'brand' => 'AC',
				'country' => $sPays,
				'client' => 'CPPv2',
				'languageCode' => $sLocale,
				'modelCode' => $modelCode,
				'bodyStyleCode' => $bodyStyleCode,
				'IdSiteGeo1' => $iStoreId,
				'dealerCode1' => $sStoreRRDI
		);
		try {
			$service = \Itkg\Service\Factory::getService('CITROEN_SERVICE_WEBSTORE', array());
			$oResponse = $service->call('getStockWebstore', $serviceParams);
		} catch (\Exception $e) {
			echo $e->getMessage();
		}

		$aVehicules['CARS'] = \Citroen_View_Helper_Global::objectsIntoArray($oResponse->GetStockWebstoreResult->vehiclesList->VehicleProperties);
		$aVehicules['STORE_URL'] = $oResponse->GetStockWebstoreResult->Dealer1StockURL;
		return $aVehicules;
	}

	/**
	 * Appel WS CarStock : getStockWebstore
	 *
	 * @param string $sPays : Pays (ex : FR)
	 * @param string $sLocale : Code pays (ex : fr-FR)
	 * @param int $iLat : Latitude
	 * @param int $iLng : Longitude
	 * @param string $modelCode : model code
	 * @param string $bodyStyleCode : boody style code
	 * @param int $currentPage : page courante
	 * @param int $nbElements : nb elements remontés
	 * @return array $aVehicules tableau de véhicules
	 */
	public static function getVehicles($sPays, $sLocale, $iLat, $iLng, $modelCode, $bodyStyleCode, $currentPage, $nbElements,$typeAff, $maxDistance, $typeSite = "ALL")
	{
		if($typeAff == 'CP_CITY'){
			$serviceParams = array(
					'brand' => 'AC',
					'country' => $sPays,
					'client' => 'CPPv2',
					'languageCode' => $sLocale,
					'lat' => $iLat,
					'lng' => $iLng,
					'modelCode' => $modelCode,
					'bodyStyleCode' => $bodyStyleCode,
					'currentPage' => $currentPage,
					'nbElements' => $nbElements,
					'maxDistance' => $maxDistance,
					'typeSite'=>$typeSite
			);
		}else{
			$serviceParams = array(
					'brand' => 'AC',
					'country' => $sPays,
					'client' => 'CPPv2',
					'languageCode' => $sLocale,
					'lat' => $iLat,
					'lng' => $iLng,
					'modelCode' => $modelCode,
					'bodyStyleCode' => $bodyStyleCode,
					'currentPage' => $currentPage,
					'nbElements' => $nbElements,
					'typeSite'=>$typeSite
			);
		}

		try {
			$service = \Itkg\Service\Factory::getService('CITROEN_SERVICE_WEBSTORE', array());
			$oResponse = $service->call('getVehicles', $serviceParams);
			
		} catch (\Exception $e) {
			echo $e->getMessage();
		}

		$aVehicules = \Citroen_View_Helper_Global::objectsIntoArray($oResponse->GetVehiclesResult);
		return $aVehicules;
	}

	/**
	 * Appel WS CarStock : getOptionalFeaturesInfo
	 *
	 * @param string $sPays : Pays (ex : FR)
	 * @param string $sLocale : Code pays (ex : fr-FR)
	 * @param string $sCarNum : carnum
	 * @return array $aFeatures tableau de véhicules
	 */
	public static function getOptionalFeaturesInfo($sPays, $sLocale, $sCarNum)
	{
		$aFeatures =array();
		$serviceParams = array(
				'brand' => 'AC',
				'country' => $sPays,
				'client' => 'CPPv2',
				'languageCode' => $sLocale,
				'carnum' => $sCarNum
		);


		try {
			$service = \Itkg\Service\Factory::getService('CITROEN_SERVICE_WEBSTORE', array());
			$oResponse = $service->call('getOptionalFeaturesInfo', $serviceParams);
		} catch (\Exception $e) {
			echo $e->getMessage();
		}

		$aFeatures = \Citroen_View_Helper_Global::objectsIntoArray($oResponse->GetOptionalFeaturesInfoResult);
		return $aFeatures;
	}


}
