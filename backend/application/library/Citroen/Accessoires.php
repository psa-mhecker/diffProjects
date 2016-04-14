<?php

namespace Citroen;

/**
 * Class Accessoires gérant les appels vers WS AOA.
 *
 * @author Khadidja Messaoudi <khadidja.messaoudi@businessdecision.com>
 */
class Accessoires
{
    /**
     * Appel WS AOA : getCriteriaValues.
     *
     * @param string $sCriterion : Critères de remontée
     * @param string $sLocale    : Code pays (ex : fr_FR)
     *
     * @return array $aUniverses tableau d'accessoires
     */
    public static function getCriteriaValues($sCriterion, $sLocale, $clientId = 'CSA01')
    {
        $aParams = array(
            'criterion' => $sCriterion,
            'locale' => $sLocale,
            'clientId' => $clientId,
        );
        try {
            $service = \Itkg\Service\Factory::getService('CITROEN_SERVICE_BOUTIQACC', array());
            $oResponse = $service->call('getCriteriaValues', $aParams);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        $aUniverses = \Citroen_View_Helper_Global::objectsIntoArray($oResponse->criteriaValueOut->criteriaValue);

        return $aUniverses[0];
    }

    /**
     * Appel WS AOA : getAccessories.
     *
     * @param string $sLocale          : Code pays (ex : fr_FR)
     * @param int    $iSubUniverseCode : code du sous-univers
     * @param string $sModelCode       : code du model
     * @param string $sBodyStyleCode   : suite du code du model
     *
     * @return $aAccessoires tableau d'accessoires
     */
    public static function getAccessories($sLocale, $iSubUniverseCode, $sModelCode, $sBodyStyleCode, $clientId = 'CSA01')
    {
        $aParams = array(
            'locale' => $sLocale,
            //'subUniverseCode' => $iSubUniverseCode,
            'modelCode' => $sModelCode,
            'bodyStyleCode' => $sBodyStyleCode,
            'clientId' => $clientId,
        );
        try {
            $service = \Itkg\Service\Factory::getService('CITROEN_SERVICE_BOUTIQACC', array());
            $oResponse = $service->call('getAccessories', $aParams);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        $aAccessoires = \Citroen_View_Helper_Global::objectsIntoArray($oResponse->accessoryList->accessoryLocal);

        return $aAccessoires[0]['accessory'];
    }
}
