<?php

use Itkg\Service\Configuration as BaseConfiguration;

/**
 * Classe Configuration pour le service MotCfgSelect.
 */
class Plugin_MotCfgSelect_Configuration extends BaseConfiguration
{
    /**
     * Initialisation des models et des logs.
     */
    public function __construct()
    {
        $this->identifier = '[WS MOTCFG SELECT]';
        $this->methodIdentifiers = array(
            'select' => 'select',
        );
        $this->models = array(
            'select' => array(
                'request' => array(
                    'model' => 'Plugin_MotCfgSelect_Model_Request',
                ),
                'response' => array(
                    'model' => 'Plugin_MotCfgSelect_Model_Response',
                    'mapping' => array(
                        'configResponse' => 'Plugin_MotCfgSelect_Model_Response',
                    ),
                ),
            ),
        );

        $this->loggers['default'] = array(
            'writer' => 'file',
            'formater' => 'simple',
            'parameters' => array(
                'file' => \Pelican::$config['VAR_ROOT'].'/logs/MotCfgSelect.log',
            ),
        );
    }
}
