<?php

namespace Citroen;

use Citroen\Service;

/**
 * Class GDG
 *
 * @author David Moaté
 *
 */

class GDG
{
    /**
     * Appel WS GDG : getCarPicker
     *
     * @param string $languages
     * @param string $countries
     * @return array $brands
     * @return array $ranges
     * @return array $_format
     * @return array $contexts
     */
    public static function getCarPicker($languages, $countries, $brands, $ranges, $_format, $contexts){
       $aParams = array(
                        'languages' => $languages,
                        'countries' => $countries,
                        'brands'    => $brands,
                        'ranges'    => $ranges,
                        '_format'   => $_format,
                        'contexts'  => $contexts
		);
        try {
                $service = \Itkg\Service\Factory::getService('CITROEN_SERVICE_GDG', array());
                $oResponse = $service->call('getCarPicker', $aParams);

                return $oResponse;
        } catch(\Exception $e) {
                echo $e->getMessage();
        }

    }
	
    /**
     * Appel WS GDG : getBrochure
     *
     * @param string $languages
     * @param string $countries
     * @return array $brands
     * @return array $ranges
     * @return array $_format
     * @return array $contexts
     */
    public static function getBrochure($languages, $countries, $brands, $ranges, $_format){
       $aParams = array(
                        'languages' => $languages,
                        'countries' => $countries,
                        'brands'    => $brands,
                        'ranges'    => $ranges,
                        '_format'   => $_format
		);
        try {
                $service = \Itkg\Service\Factory::getService('CITROEN_SERVICE_GDG', array());
                $oResponse = $service->call('getBrochure', $aParams);
                
                return $oResponse;
        } catch(\Exception $e) {
                echo $e->getMessage();
        }

    }

}
