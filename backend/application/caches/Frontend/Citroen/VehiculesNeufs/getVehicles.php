<?php

/**
 * Fichier de Pelican_Cache : Retour WS CarStore.
 */
use Citroen\Vehicules;

class Frontend_Citroen_VehiculesNeufs_getVehicles extends Pelican_Cache
{
    public $duration = DAY;

    /**
     * Valeur ou objet à mettre en Pelican_Cache.
     */
    public function getValue()
    {
        $sPays = $this->params[0];
        $sLanguageCode = $this->params[1];
        $iPage = $this->params[2];
        $nbElements = $this->params[3];
        $iLat = $this->params[4];
        $iLng = $this->params[5];
        $sModelCode = $this->params[6];
        $sBodyCode = $this->params[7];
        $typeAff = $this->params[8];
        $imaxDistance = $this->params[9];
        $sDisponibilite = t('DISPO_SOUS');
        $sEconomie = t('SOIT_ECO');
        $aResult = Vehicules::getVehicles($sPays, $sLanguageCode, $iLat, $iLng, $sModelCode, $sBodyCode, $iPage, $nbElements, $typeAff, $imaxDistance);
        $aReturn = array();
        if (is_array($aResult['VehicleType']['GetVehicleType']) && sizeof($aResult['VehicleType']['GetVehicleType']) > 0 && $aResult['CountVehicle'] > 0) {
            $aReturn['COUNT'] = $aResult['CountVehicle'];
            $aReturn['CARS'] = $aResult['VehicleType']['GetVehicleType'];
            foreach ($aReturn['CARS'] as $key => $car) {
                $sPriceSale = 0;
                $iPourcentage = 0;
                $aReturn['CARS'][$key]['IMAGE'] = preg_replace('/^\s*\/\/<!\[CDATA\[([\s\S]*)\/\/\]\]>\s*\z/', '$1', $car['VisuExt']);
                $aReturn['CARS'][$key]['DISPONIBLE_SOUS'] = str_replace('#STOCK#', $car['AvailabilityDelay'], $sDisponibilite);
                if ($car['InternetPrice'] != 0 && $car['CatalogPrice'] != 0 && $car['InternetPrice'] != $car['CatalogPrice']) {
                    $sPriceSale = $car['CatalogPrice'] - $car['InternetPrice'];
                    $iPourcentage = round(($sPriceSale * 100) / $car['CatalogPrice'], 2);
                    if ($sPriceSale > 0) {
                        $aReturn['CARS'][$key]['SOIT_ECO'] = str_replace('#PRICE#', $sPriceSale.' #CURRENCY#', $sEconomie);
                    }
                    $aReturn['CARS'][$key]['POURCENTAGE'] = $iPourcentage;
                }
            }
        }

        $this->value = $aReturn;
    }
}
