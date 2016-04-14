<?php
namespace Citroen\Service\Webstore;

use Itkg\Service\Configuration as BaseConfiguration;

/**
 * Classe Configuration pour le service Webstore.
 */
class Configuration extends BaseConfiguration
{
    /**
     * Initialisation des models et des logs.
     */
    public function __construct()
    {
        $this->identifier = '[CITROEN] - [WEBSTORE]';
        $this->methodIdentifiers = array(
            'getStockWebstore' => 'GetStockWebstore',
            'getVehicles' => 'GetVehicles',
        );
        $this->models = array(
            'getStockWebstore' => array(
                'request' => array(
                    'model' => 'Citroen\Service\Webstore\Model\GetStockWebstoreRequest',
                ),
                'response' => array(
                    'model' => 'Citroen\Service\Webstore\Model\GetStockWebstoreResponse',
                    'mapping' => array(
                        'GetStockWebstoreResponse' => 'Citroen\Service\Webstore\Model\GetStockWebstoreResponse',
                    ),
                ),
            ),
            'getVehicles' => array(
                'request' => array(
                    'model' => 'Citroen\Service\Webstore\Model\GetVehiclesRequest',
                ),
                'response' => array(
                    'model' => 'Citroen\Service\Webstore\Model\GetVehiclesResponse',
                    'mapping' => array(
                        'GetVehiclesResponse' => 'Citroen\Service\Webstore\Model\GetVehiclesResponse',
                    ),
                ),
            ),
        );
        $this->loggers['default'] = array(
            'writer' => 'file',
            'formater' => 'simple',
            'parameters' => array(
                'file' => \Pelican::$config['VAR_ROOT'].'/logs/WEBSTORE.log',
            ),
        );
    }
}
