<?php
namespace Citroen\Service\GammeVU;

use Itkg\Service\Configuration as BaseConfiguration;

/**
 * Classe Configuration pour le service BoutiqAcc
 */
class Configuration extends BaseConfiguration
{

    /**
     * Initialisation des models et des logs
     */
    public function __construct()
    {
        $this->identifier = '[CITROEN] - [GammeVU]';
        $this->methodIdentifiers = array(
            'getConfiguratorUrlList' => 'getConfiguratorUrlList'
        );
        $this->models = array(
            'getConfiguratorUrlList' => array(
                'request' => array(
                    'model' => 'Citroen\Service\GammeVU\Model\GetConfiguratorUrlListRequest'
                ),
                'response' => array(
                    'model' => 'Citroen\Service\GammeVU\Model\GetConfiguratorUrlListResponse',
                    'mapping' => array(
                        'GetConfiguratorUrlListResponse' => 'Citroen\Service\GammeVU\Model\GetConfiguratorUrlListResponse'
                    )
                )
            )
            
        );
        $this->loggers['default'] = array(
            'writer' => 'file',
            'formater' => 'simple',
            'parameters' => array(
                'file' => \Pelican::$config['VAR_ROOT'].'/logs/GammeVU.log'
            )
        );
    }

}