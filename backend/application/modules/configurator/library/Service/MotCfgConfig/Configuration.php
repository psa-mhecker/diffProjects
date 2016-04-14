<?php

use Itkg\Service\Configuration as BaseConfiguration;

/**
 * Classe Configuration pour le service MotCfgConfig.
 */
class Plugin_MotCfgConfig_Configuration extends BaseConfiguration
{
    /**
     * Initialisation des models et des logs.
     */
    public function __construct()
    {
        $this->identifier = '[WS MOTCFG CONFIG]';
        $this->methodIdentifiers = array(
            'select' => 'select',
        );
        $this->models = array(
            'config' => array(
                'request' => array(
                    'model' => 'Plugin_MotCfgConfig_Model_Request',
                ),
                'response' => array(
                    'model' => 'Plugin_MotCfgConfig_Model_Response',
                    'mapping' => array(
                        'configResponse' => 'Plugin_MotCfgConfig_Model_Response',
                    ),
                ),
            ),
        );

        $this->loggers['default'] = array(
            'writer' => 'file',
            'formater' => 'simple',
            'parameters' => array(
                'file' => \Pelican::$config['VAR_ROOT'].'/logs/MotCfgConfig.log',
            ),
        );
    }
}
