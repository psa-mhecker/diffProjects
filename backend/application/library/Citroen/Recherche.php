<?php

namespace Citroen;

use Citroen\Service\GSA;

/**
 * Class Vehicules gérant les appels vers WS GSA.
 *
 * @author Khadidja Messaoudi <khadidja.messaoudi@businessdecision.com>
 */
class Recherche
{
    /**
     * Appel WS GSA : suggest.
     *
     * @param string $sPays   : Pays (ex : FR)
     * @param string $sLocale : Code pays (ex : fr_FR)
     *
     * @return array $aVehicules tableau de véhicules
     */
    public static function suggest($sQuery, $site = "FR-CT_W")
    {
        $serviceParams = array(
            'q' => $sQuery,
            'max' => '10',
            'site' => $site,
            'client' => 'CPPv2',
            'access' => 'p',
            'format' => 'rich',
        );
        try {
            $service = \Itkg\Service\Factory::getService('CITROEN_SERVICE_GSA', array());
            $response = $service->call('suggest', $serviceParams, false);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        $aResponse = \Citroen_View_Helper_Global::objectsIntoArray(json_decode($response));
        $aSuggest = array();
        if (is_array($aResponse['results']) && count($aResponse['results'])>0) {
            foreach ($aResponse['results'] as $result) {
                $aSuggest[] = $result['name'];
            }
        }

        return $aSuggest;
    }

    /**
     * Appel WS GSA : search.
     *
     * @param string $sPays   : Pays (ex : FR)
     * @param string $sLocale : Code pays (ex : fr_FR)
     *
     * @return array $aVehicules tableau de véhicules
     */
    public static function search($sQuery, $start, $site = "FR-CT_W")
    {
        $serviceParams = array(
            'q' => $sQuery,
            'output' => 'xml_no_dtd',
            'client' => 'CPPv2',
            'sort' => 'date:D:S:d1',
            'site' => $site,
            'start' => $start,
            'num' => \Pelican::$config['GSA']['NOMBRE_RESULTAT'],
            'tlen' => 1000,
        );
        try {
            $service = \Itkg\Service\Factory::getService('CITROEN_SERVICE_GSA', array());
            $response = $service->call('search', $serviceParams, false);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        $sXML = simplexml_load_string($response);
        $aResults = array();
        if ($sXML->RES->R) {
            $aResults['NB_RESULTS'] = (int) $sXML->RES->M;
            foreach ($sXML->RES->R as $result) {
                /* suppression des br */
                $sDescription = strip_tags((string) $result->S);

                /* suppression des hosts pour n'avoir que des urls relative */
                $aUrl = parse_url((string) $result->U);
                $sUrl = $aUrl['path'];
                if ($aUrl['query']) {
                    $sUrl .= '?'.$aUrl['query'];
                }

                $aResults['RESULTS'][] =
                    array(
                        "title"    => (string) $result->T,
                        "url"    => $sUrl,
                        "desc"    => $sDescription,
                    );
            }
        }

        return $aResults;
    }
}
