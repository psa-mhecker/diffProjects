<?php

include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/conf/services.ini.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgConfig.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgConfig/Configuration.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgConfig/Model/Request.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgConfig/Model/Response.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/GDVCars.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/GDVCars/Configuration.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/GDVCars/Model/Request.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/GDVCars/Model/Response.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgSelect.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgSelect/Configuration.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgSelect/Model/Request.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgSelect/Model/Response.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/SimulFin.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/SimulFin/Configuration.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/SimulFin/Model/OpenSessionRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/SimulFin/Model/OpenSessionResponse.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/SimulFin/Model/SaveCalculationDisplayRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/SimulFin/Model/SaveCalculationDisplayResponse.php');

ini_set('default_socket_timeout', 10);

class Configurator {

    public static function getMLData($versionData, $params) {
        $serviceParams = array(
          'country' => 'FR',//$params['country'],
          'language' => 'fr',//$params['language'],
          'financingMake' => $params['brandId'],
          'currency' => $params['currency'],
          'flowDate' => gmdate("Y-m-d\TH:i:s.uP")
        );

        $service = \Itkg\Service\Factory::getService('SERVICE_SIMULFIN', array());
        $idSession = $service->call('openSession', $serviceParams);

        if ($idSession) {
            $serviceParams = array(
                'idSession' => $idSession,
                'country' => $params['country'],
                'language' => $params['language'],
                'financingMake' => $params['brandId'],
                'currency' => $params['currency'],
                'flowDate' => $params['flowDate'],
                'vehicleBrandCode' => $params['vehicleBrandCode'],
                'vehicleBrandLabel' => $params['vehicleBrandLabel'],
                'vehicleType' => $params['vehicleType'],
                'vehicleIdentification' => $params['vehicleIdentification'],
                'vehicleModel' => $params['vehicleModel'],
                'vehicleDescription' => $params['vehicleDescription'], //On ne sait pas où il faut la récupérer
                'vehicleCategory' => $params['vehicleCategory'],
                'vehicleEngine' => $params['vehicleEngine'], //On ne sait pas où il faut la récupérer
                'vehiclePriceHT' => $params['vehiclePriceHT'],
                'vehiclePriceTTC' => $params['vehiclePriceTTC'],
                'clientType' => $params['clientType'],
                'financingSpecialFlag' => $params['financingSpecialFlag'],
            );

            $service = \Itkg\Service\Factory::getService('SERVICE_SIMULFIN', array());
            $aResponse = $service->call('saveCalculationDisplay', $serviceParams);

            return $aResponse;
        }
    }
    public static function getVersionsFromGRBodystyle($grbodystyle, $params) {
        $serviceParamsSelect = array(
            'client' => $params['client'],
            'brandId' => $params['brandId'],
            'date' => $params['date'],
            'country' => $params['country'],
            'tariffCode' => $params['tariffCode'],
            'taxIncluded' => $params['taxIncluded'],
            'grbodystyle' => $grbodystyle
        );
        $serviceSelect = \Itkg\Service\Factory::getService('SERVICE_MOTCFGSELECT', array());
        $responseXmlSelect = $serviceSelect->call('select', $serviceParamsSelect);
        $responseArraySelect = self::objectsIntoArray($responseXmlSelect);

        $return = array();
        if (is_array($responseArraySelect['Version']) && count($responseArraySelect['Version'])) {
            foreach ($responseArraySelect['Version'] as $version) {
                $return[] = $version['IdVersion']['id'];
            }
        }
        return $return;
    }
    public static function getVersionData($idVersion, $params) {
        $serviceParams = array(
            'client' => $params['client'],
            'brandId' => $params['brandId'],
            'date' => $params['date'],
            'country' => $params['country'],
            'tariffCode' => $params['tariffCode'],
            'taxIncluded' => $params['taxIncluded'],
            'professionalUse' => $params['professionalUse'],
            'version' => $idVersion
        );

        $service = \Itkg\Service\Factory::getService('SERVICE_MOTCFGCONFIG', array());
        $responseXml = $service->call('config', $serviceParams);
        $responseArray = self::objectsIntoArray($responseXml);
        return $responseArray;
    }
    // types de teintes (metalized, ...)
    private static function getTeintesCategories() {
        return array(0 => '0M', 1 => '0P');
    }

    /**
     * @param $idVersion
     * @param $params
     * @param bool|false $withDefault (indique si on regroupe les categories dans default)
     * @return array
     * @throws \Itkg\Exception\NotFoundException
     * @throws \Itkg\Exception\UnauthorizedException
     * @throws \Itkg\Exception\ValidationException
     */
    public static function getTeintesData($idVersion, $params, $withDefault = false) {
        $tblCategoryTeinte = self::getTeintesCategories();

         $serviceParams = array(
              'client' => $params['client'],
              'brandId' => $params['brandId'],
              'date' => $params['date'],
              'country' => $params['country'],
              'tariffCode' => $params['tariffCode'],
              'taxIncluded' => $params['taxIncluded'],
              'professionalUse' => $params['professionalUse'],
              'version' => $idVersion
          );

          $service = \Itkg\Service\Factory::getService('SERVICE_MOTCFGCONFIG', array());
          $responseXml = $service->call('config', $serviceParams);
          $responseArray = self::objectsIntoArray($responseXml);

          $teintesCompatibles = array();
          $teintesCompatiblesIds = array();
          if (is_array($responseArray['LookFeatures']['ExteriorFeatures']) && count($responseArray['LookFeatures']['ExteriorFeatures']) > 0) {
              foreach ($responseArray['LookFeatures']['ExteriorFeatures'] as $teinte) {
                  $category_teinte = substr($teinte['id'], 0, 2);

                  $teintesCompatiblesIds[] = $teinte['id'];

                  // si withDefault vaut true alors on regroupe par teinte connues et le reste dans DEFAULT
                  if ($withDefault == true && (!in_array($category_teinte, $tblCategoryTeinte))) {

                     $teintesCompatibles['DEFAULT'][] = array(
                              'label' =>$teinte['label'],
                              'id' =>$teinte['id'],
                              'Price' =>$teinte['Price'],
                              'isNew' => (rand(0, 1) == 1) ? '1' : '0'
                     );
                  } else {
                      $teintesCompatibles[$category_teinte][] = array(
                          'label' =>$teinte['label'],
                          'id' =>$teinte['id'],
                          'Price' =>$teinte['Price'],
                          'isNew' => (rand(0, 1) == 1) ? '1' : '0'
                      );
                  }
              }
          }

          //if($_COOKIE['etapeParcours'] != 1 && $_COOKIE['etapeParcours'] != "") {
              $serviceParamsSelect = array(
                  'client' => $params['client'],
                  'brandId' => $params['brandId'],
                  'date' => $params['date'],
                  'country' => $params['country'],
                  'tariffCode' => $params['tariffCode'],
                  'taxIncluded' => $params['taxIncluded'],
                  'criteria' => array('GrBodystyle' => $responseArray['GrbodyStyle']['id'])
              );

              $serviceSelect = \Itkg\Service\Factory::getService('SERVICE_MOTCFGSELECT', array());
              $responseXmlSelect = $serviceSelect->call('select', $serviceParamsSelect);
              $responseArraySelect = self::objectsIntoArray($responseXmlSelect);


              $teintesIncompatibles = array();
              if (is_array($responseArraySelect['Version']) && count($responseArraySelect['Version']) > 0) {
                  foreach ($responseArraySelect['Version'] as $select) {
                      if ($select['IdVersion']['id'] != $responseArray['IdVersion']['id']) {

                        $serviceParamsVersion = array(
                            'client' => $params['client'],
                            'brandId' => $params['brandId'],
                            'date' => $params['date'],
                            'country' => $params['country'],
                            'tariffCode' => $params['tariffCode'],
                            'taxIncluded' => $params['taxIncluded'],
                            'professionalUse' => $params['professionalUse'],
                            'version' => $select['IdVersion']['id']
                        );

                        $serviceVersion = \Itkg\Service\Factory::getService('SERVICE_MOTCFGCONFIG', array());
                        $responseXmlVersion = $serviceVersion->call('config', $serviceParamsVersion);
                        $responseArrayVersion = self::objectsIntoArray($responseXmlVersion);


                        if (is_array($responseArrayVersion['LookFeatures']['ExteriorFeatures']) && count($responseArrayVersion['LookFeatures']['ExteriorFeatures']) > 0) {
                            foreach ($responseArrayVersion['LookFeatures']['ExteriorFeatures'] as $teinte) {
                                if(! in_array($teinte['id'], $teintesCompatiblesIds)) {
                                    $category_teinte = substr($teinte['id'], 0, 2);
                                    $teintesIncompatibles[$category_teinte][] = array(
                                        'label' =>$teinte['label'],
                                        'id' =>$teinte['id'],
                                        'Price' =>$teinte['Price'],
                                        'isNew' => (rand(0, 1) == 1) ? '1' : '0'
                                      );

                                }
                            }
                        }

                      }
                  }
              //}
          }

          $nbTeintesCompatibles = count($teintesCompatiblesIds);
          unset($teintesCompatiblesIds);

          return array(
              'nbTeintesCompatibles' => $nbTeintesCompatibles,
              'teintesCompatibles'   => $teintesCompatibles,
              'teintesIncompatibles' => $teintesIncompatibles,
              'idModelPreSelect'     => $responseArray['Model']['id'],
              'idBodystylePreSelect' => $responseArray['BodyStyle']['id'],
              'biton' => (rand(0, 1) == 1) ? '1' : '0');
    }

    public static function objectsIntoArray($arrObjData, $lan = '')
    {
        $arrData = array();

        if (is_object($arrObjData)) {
            $arrObjData = get_object_vars($arrObjData);
        }

        if (is_array($arrObjData)) {
            foreach ($arrObjData as $index => $value) {
                if (is_object($value) || is_array($value)) {
                    $value = self::objectsIntoArray($value, $lan);
                }
                if (is_string($value)) {
                    if (preg_match('/[\p{Cyrillic}]/u', $value) || mb_detect_encoding($data, 'UTF-8', true) == 'UTF-8') {
                        $value = $value;
                    } else {
                        $value = utf8_decode($value);
                    }
                }
                $arrData[$index] = $value;
            }
        }

        return $arrData;
    }
}
