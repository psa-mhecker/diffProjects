<?php

use Itkg\Service\Configuration as BaseConfiguration;

/**
 * Classe Configuration pour le service MotCfgLookCombinations.
 */
class Plugin_MotCfgLookCombinations_Configuration extends BaseConfiguration
{
    /**
     * Initialisation des models et des logs.
     */
    public function __construct()
    {
        $this->identifier = '[WS MOTCFG LOOK COMBINATIONS]';
        $this->methodIdentifiers = array(
            'lookCombinations' => 'lookCombinations',
        );
        $this->models = array(
            'lookCombinations' => array(
                'request' => array(
                    'model' => 'Plugin_MotCfgLookCombinations_Model_Request',
                ),
                'response' => array(
                    'model' => 'Plugin_MotCfgLookCombinations_Model_Response',
                    'mapping' => array(
                        'configResponse' => 'Plugin_MotCfgLookCombinations_Model_Response',
                    ),
                ),
            ),
        );

        $this->loggers['default'] = array(
            'writer' => 'file',
            'formater' => 'simple',
            'parameters' => array(
                'file' => \Pelican::$config['VAR_ROOT'].'/logs/MotCfgLookCombinations.log',
            ),
        );
    }
}
