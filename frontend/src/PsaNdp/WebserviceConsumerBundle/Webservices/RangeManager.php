<?php

namespace PsaNdp\WebserviceConsumerBundle\Webservices;

use Itkg\Consumer\Service\Service;
use PsaNdp\MappingBundle\Repository\PsaSiteWebserviceRepository;
use PsaNdp\MappingBundle\Repository\PsaWebserviceRepository;

/**
 * Class RangeManager.
 */
class RangeManager extends RestConsumer
{
    const ANGLE_VUE_DEFAUT = '001';

    /**
     * @var array
     */
    private $defaultParamaters;

    /**
     * @var array
     */
    private $cheapestByLcdv4;
    /**
     * @var array
     */
    private $cheapestByLcdv6;

    /**
     * @var array
     */
    private $cheapestByLcdv6AndGrBodyStyle;

    /**
     * @var array
     */
    private $allowedMethods = array(
        'select',
        'criteria',
        'search',
        'cars',
        'booklets',
    );

    /**
     * @param Service                     $service
     * @param PsaSiteWebserviceRepository $siteWebserviceRepository
     * @param PsaWebserviceRepository     $psaWebserviceRepository
     */
    public function __construct(Service $service, PsaSiteWebserviceRepository $siteWebserviceRepository, PsaWebserviceRepository $psaWebserviceRepository)
    {
        $this->defaultParamaters = array(
            'languages' => 'fr',
            'countries' => 'FR',
            'brands' => 'P',
            'ranges' => 'VP',
            '_format' => 'json',
            'cheapest' => 'false',
            'devices' => 'WEB',
            'prices' => 'TTC',
        );

        parent::__construct($service, $siteWebserviceRepository, $psaWebserviceRepository);
    }

    /**
     * @param $method
     * @param $args
     *
     * @method select
     * @method search
     * @method cars
     * @method criteria
     * @method booklets
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (!in_array($method, $this->allowedMethods)) {
            throw new \BadMethodCallException(sprintf('call to method %s is not allowed', $method));
        }
        if(empty($args)) {
            $args[0] = $this->defaultParamaters;
        }

        return $this->call($method.'/', $args[0]);
    }

    /**
     * @return array
     */
    protected function getSearchDefaultsParameters()
    {
        return $this->defaultParamaters;
    }

    /**
     * @return array
     */
    public function getModelsFromCars()
    {
        $models = [];

        $cars = $this->cars();
        foreach ($cars['CarPicker']['Model'] as $m) {
            $model['gender'] = $m['Type'];
            $model['model'] = $m['Label'];
            $body = current($m['Bodies']['Body']);
            $model['LCDV4'] = substr($body['LCDV'], 0, 4);
            $models[] = $model;
        }

        return $models;
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    public function getModelsFromSearch($parameters = [])
    {
        $models = [];

        $cars = $this->search(array_merge($this->getSearchDefaultsParameters(), $parameters));
        if (!empty($cars['Search']['Vehicle'])) {
            foreach ($cars['Search']['Vehicle'] as $m) {
                $model['gender'] = $m['Range'];
                $model['model'] = $m['ModelName'];
                $model['LCDV4'] = $m['LCDV4'];
                $models[] = $model;
            }
        }

        return $models;
    }

    /**
     * @param array $lcdv16s
     *
     * @return type
     */
    public function getFinitionInformationFromModel($lcdv16s = [])
    {
        $finitions = [];
        $cars = $this->search($this->getSearchDefaultsParameters());

        if (!empty($cars['Search']['Vehicle'])) {
            foreach ($cars['Search']['Vehicle'] as $vehicule) {
                if (in_array($vehicule['LCDV16'], $lcdv16s)) {
                    $finitions[] = $vehicule;
                }
            }
        }
        $finition = [];
        foreach ($finitions as $key => $row) {
            $finition[$key] = $row['Price']['Value'];
        }
        array_multisort($finition, SORT_ASC, $finitions);

        return $finitions;
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    public function getModelsBodyStyleFromSearch($parameters = [])
    {
        $models = [];
        $cars = $this->search(array_merge($this->getSearchDefaultsParameters(), $parameters));
        if (!empty($cars['Search']['Vehicle'])) {
            foreach ($cars['Search']['Vehicle'] as $m) {
                $model['gender'] = $m['Range'];
                $model['model'] = $m['ModelName'];
                $model['LCDV6'] = $m['LCDV6'];
                $model['silhouette'] = $m['GrBodyStyle']['Label'];
                $models[] = $model;
            }
        }

        return $models;
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    public function getModelsGroupingSilhouetteFromSearch($parameters = [])
    {
        $models = [];
        $cars = $this->search(array_merge($this->getSearchDefaultsParameters(), $parameters));
        if (!empty($cars['Search']['Vehicle'])) {
            foreach ($cars['Search']['Vehicle'] as $m) {
                if (!isset($models[$m['ModelName'].' - '.$m['GrBodyStyle']['Code']])) {
                    $model['GENDER'] = $m['Range'];
                    $model['COMMERCIAL_LABEL'] = $m['Label'];
                    $model['LCDV6'] = $m['LCDV6'];
                    $model['GROUPING_CODE'] = $m['GrBodyStyle']['Code'];
                    $model['NEW_COMMERCIAL_STRIP'] = 0;
                    $model['SPECIAL_OFFER_COMMERCIAL_STRIP'] = 0;
                    $model['SPECIAL_SERIES_COMMERCIAL_STRIP'] = 0;
                    $model['SHOW_IN_CONFIG'] = 0;
                    $model['STOCK_WEBSTORE'] = 0;
                    $model['LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
                    $model['SITE_ID'] = $_SESSION[APP]['SITE_ID'];
                    $models[$m['ModelName'].' - '.$m['GrBodyStyle']['Code']] = $model;
                }
            }
        }

        return $models;
    }

    /**
     * @param $parameters
     */
    private function initCheapest($parameters)
    {
        $this->cheapestByLcdv4 = [];
        $this->cheapestByLcdv6 = [];
        $this->cheapestByLcdv6AndGrBodyStyle = [];
        $cars = $this->search(array_merge($this->getSearchDefaultsParameters(), $parameters));
        if (!empty($cars['Search']['Vehicle'])) {
            foreach ($cars['Search']['Vehicle'] as $m) {
                // on ne récupère que les infos du modèle le moins cher
                $lcdv4 = $m['LCDV4'];
                $lcdv6 = $m['LCDV6'];
                $grBodyKey = $m['LCDV6'].'-'.$m['GrBodyStyle']['Code'];
                // Cheapest By Model
                if (!isset($this->cheapestByLcdv4[$lcdv4])) {
                    $this->cheapestByLcdv4[$lcdv4] = $m;
                }
                if ($m['Price']['Value'] <  $this->cheapestByLcdv4[$lcdv4]['Price']['Value']) {
                    $this->cheapestByLcdv4[$lcdv4] = $m;
                }
                //Cheapest By Model Body
                if (!isset($this->cheapestByLcdv6[$lcdv6])) {
                    $this->cheapestByLcdv6[$lcdv6] = $m;
                }
                if ($m['Price']['Value'] <  $this->cheapestByLcdv6[$lcdv6]['Price']['Value']) {
                    $this->cheapestByLcdv6[$lcdv6] = $m;
                }
                //Cheapest By Model body / Group Body
                if (!isset($this->cheapestByLcdv6AndGrBodyStyle[$grBodyKey])) {
                    $this->cheapestByLcdv6AndGrBodyStyle[$grBodyKey] = $m;
                }
                if ($m['Price']['Value'] <  $this->cheapestByLcdv6AndGrBodyStyle[$grBodyKey]['Price']['Value']) {
                    $this->cheapestByLcdv6AndGrBodyStyle[$grBodyKey] = $m;
                }
            }
        }
    }

    /**
     * @param string $lcdv4
     * @param string $country
     * @param string $language
     *
     * @return array
     */
    public function getCheapestByLcdv4($lcdv4, $country = 'FR', $language = 'fr')
    {
        $infos = [];

        if (empty($this->cheapestByLcdv4)) {
            $parameters = [];
            $parameters['countries'] = $country;
            $parameters['languages'] = $language;
            $this->initCheapest($parameters);
        }
        if (isset($this->cheapestByLcdv4[$lcdv4])) {
            $infos = $this->cheapestByLcdv4[$lcdv4];
        }

        return $infos;
    }

    /**
     * @param string $lcdv6
     * @param string $country
     * @param string $language
     *
     * @return array
     */
    public function getCheapestByLcdv6($lcdv6, $country = 'FR', $language = 'fr')
    {
        $infos = [];

        if (empty($this->cheapestByLcdv6)) {
            $parameters = [];
            $parameters['countries'] = $country;
            $parameters['languages'] = $language;
            $this->initCheapest($parameters);
        }
        if (isset($this->cheapestByLcdv6[$lcdv6])) {
            $infos = $this->cheapestByLcdv6[$lcdv6];
        }

        return $infos;
    }

    /**
     * @param string $lcdv6
     * @param string $grBodyStyle
     * @param string $country
     * @param string $language
     *
     * @return array
     */
    public function getCheapestByLcdv6AndGrBodyStyle($lcdv6, $grBodyStyle, $country = 'FR', $language = 'fr')
    {
        $infos = [];
        $grBodyKey = $lcdv6.'-'.$grBodyStyle;
        if (empty($this->cheapestByLcdv6AndGrBodyStyle)) {
            $parameters = [];
            $parameters['countries'] = $country;
            $parameters['languages'] = $language;
            $this->initCheapest($parameters);
        }

        if (isset($this->cheapestByLcdv6AndGrBodyStyle[$grBodyKey])) {
            $infos = $this->cheapestByLcdv6AndGrBodyStyle[$grBodyKey];
        }

        return $infos;
    }

    /**
     * @param array $parameters
     * 
     * @return array
     */
    public function getGammesVehicules($parameters = [], $options = [])
    {
        return $this->getGenericGammesVehicules(array_merge($this->getSearchDefaultsParameters(), $parameters), $options);
    }

    /**
     * @param array $parameters
     * 
     * @return type
     */
    public function getGammesVehiculesByModelSilhouette($parameters = [])
    {
        $options = ['distinct' => true, 'withSilhouette' => true];

        return $this->getGenericGammesVehicules(array_merge($this->getSearchDefaultsParameters(), $parameters), $options);
    }

    /**
     * @param array $parameters
     * @param array $options
     *                          bool distinct : grouping code distinct ou non par model
     *                          bool withSilhouette : affiche ou non le détail du model
     * 
     * @return array
     */
    public function getGenericGammesVehicules($parameters = [], $options = [])
    {
        $result = [];
        if (!isset($options['distinct'])) {
            $options['distinct'] = false;
        }
        if (!isset($options['withSilhouette'])) {
            $options['withSilhouette'] = false;
        }
        try {
            $cars = $this->search($parameters);
            if (!empty($cars['Search']['Vehicle'])) {
                $modelStored = [];
                foreach ($cars['Search']['Vehicle'] as $car) {
                    if (!$options['distinct'] || !in_array($car['ModelName'].'-'.$car['GrBodyStyle']['Code'], $modelStored)) {
                        $carId = $car['LCDV6'].'-'.$car['GrBodyStyle']['Code'];
                        $modelName = $car['ModelName'].' ('.$carId.')';
                        if ($options['withSilhouette']) {
                            $modelName = $car['ModelName'].' '.$car['GrBodyStyle']['Label'].' ('.$carId.')';
                        }
                        $result[$carId] = $modelName;
                        $modelStored[] = $car['ModelName'].'-'.$car['GrBodyStyle']['Code'];
                    }
                }
            }
        } catch (\Exception $e) {
            debug($e->getMessage());
        }
        asort($result);

        return $result;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'WS_GEST_RANGE_MANAGER';
    }
}
