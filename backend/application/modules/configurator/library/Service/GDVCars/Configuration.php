<?php
namespace Service\GDVCars;

use Itkg\Service\Configuration as BaseConfiguration;

/**
 * Classe Configuration pour le service BoutiqAcc.
 */
class Configuration extends BaseConfiguration
{
    /**
     * Initialisation des models et des logs.
     */
    public function __construct()
    {
        $this->identifier = 'GDV Cars';
        $this->methodIdentifiers = array(
            'cars' => 'cars',
        );
        $this->models = array(
            'cars' => array(
                'request' => array(
                    'model' => 'Service\GDVCars\Model\CarsRequest',
                ),
                'response' => array(
                    'model' => 'Service\GDVCars\Model\CarsResponse',
                    'mapping' => array(
                        'SearchResponse' => 'Service\GDVCars\Model\CarsResponse',
                    ),
                ),
            ),
        );
        $this->loggers['default'] = array(
            'writer' => 'file',
            'formater' => 'simple',
            'parameters' => array(
                'file' => \Pelican::$config['VAR_ROOT'].'/logs/GDVCars.log',
            ),
        );
    }
}
