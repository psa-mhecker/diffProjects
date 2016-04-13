<?php

namespace Citroen;

use Citroen\Service\GammeVU;

/**
 * Class Gamme gérant les appels vers WSGamme
 *
 * @author Pierre Pottié <pierre.pottie@businessdecision.com>
 *
 */
class Gamme
{

   static protected $serviceName = 'CITROEN_SERVICE_GAMMEVU';

    /**
     * Appel WS Gamme : GetConfiguratorUrlList
     *
    * @param string $Pays : Pays (ex : FR)
     * @param string $Locale : Code pays (ex : fr_FR)
     * @return array $urls tableau de urls vers le configurateur VU
     */
    public static function getConfiguratorUrlList( $Pays, $Locale)
    {
        $serviceParams = array(
            'country' => $Pays,
            'culture' => $Locale,
        );
        $urls =array();
        try {
             //Test si le webservice wsGammeVU a été activé pour le pays
            $aSiteWS = \Pelican_Cache::fetch('Frontend/Citroen/SiteWs',array($_SESSION[APP]['SITE_ID']));
            $aWs = \Pelican_Cache::fetch('Frontend/Citroen/WsConfig');
            if( $aSiteWS[ $aWs[self::$serviceName]['id'] ]  ){
                //Appel du web Service via itkg 
                $service = \Itkg\Service\Factory::getService(self::$serviceName, array());
                $urls = $service->call('getConfiguratorUrlList', $serviceParams);
            }       
        } catch (\Exception $e) {
            return array('error' => $e->getMessage());
        }
        return $urls;
    }
}