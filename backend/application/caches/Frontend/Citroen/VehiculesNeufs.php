<?php
/**
 * Fichier de Pelican_Cache : Retour WS CarStore.
 */
use Citroen\Vehicules;

class Frontend_Citroen_VehiculesNeufs extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $sLangue = $this->params[0];
        $sLanguageCode = $this->params[1];
        $iStart = $this->params[2];
        $iLimit = $this->params[3];
        $sDisponibilite = t('DISPO_SOUS');
        $sEconomie = t('SOIT_ECO');
        $aResult = Vehicules::getStockWebstore($sLangue, $sLanguageCode);
        $aReturn = array();
        if (is_array($aResult) && count($aResult)>0) {
            $aReturn['COUNT'] = count($aResult);
            $aReturn['CARS'] = array_slice($aResult, $iStart, $iLimit);
            foreach ($aReturn['CARS'] as $key => $car) {
                $sPriceSale = 0;
                $iPourcentage = 0;
                $aReturn['CARS'][$key]['DISPONIBLE_SOUS'] = str_replace('#STOCK#', $car['VehicleStockLevel'], $sDisponibilite);
                if ($car['VehicleWebstorePrice'] != 0 && $car['VehiclePriceCatalogue'] != 0) {
                    $sPriceSale = $car['VehiclePriceCatalogue'] - $car['VehicleWebstorePrice'];
                    $iPourcentage = round(($sPriceSale*100)/$car['VehiclePriceCatalogue'], 2);
                    $aReturn['CARS'][$key]['SOIT_ECO'] = str_replace('#PRICE#', $sPriceSale, $sEconomie);
                    $aReturn['CARS'][$key]['POURCENTAGE'] = $iPourcentage;
                }
            }
        }

        $this->value = $aReturn;
    }
}
