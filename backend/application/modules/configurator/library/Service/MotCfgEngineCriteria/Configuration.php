<?php

use Itkg\Service\Configuration as BaseConfiguration;

/**
 * Classe Configuration pour le service MotCfgEngineCriteria.
 */
class Plugin_MotCfgEngineCriteria_Configuration extends BaseConfiguration
{
    /**
     * Initialisation des models et des logs.
     */
    public function __construct()
    {
        $this->identifier = '[WS MOTCFG ENGINECRITERIA]';
        $this->methodIdentifiers = array(
            'engineCriteria' => 'engineCriteria',
        );
        $this->models = array(
            'engineCriteria' => array(
                'request' => array(
                    'model' => 'Plugin_MotCfgEngineCriteria_Model_Request',
                ),
                'response' => array(
                    'model' => 'Plugin_MotCfgEngineCriteria_Model_Response',
                    'mapping' => array(
                        'configResponse' => 'Plugin_MotCfgEngineCriteria_Model_Response',
                    ),
                ),
            ),
        );

        $this->loggers['default'] = array(
            'writer' => 'file',
            'formater' => 'simple',
            'parameters' => array(
                'file' => \Pelican::$config['VAR_ROOT'].'/logs/MotCfgEngineCriteria.log',
            ),
        );
    }
}
