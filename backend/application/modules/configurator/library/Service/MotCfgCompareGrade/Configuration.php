<?php

use Itkg\Service\Configuration as BaseConfiguration;

/**
 * Classe Configuration pour le service MotCfgCompareGrade.
 */
class Plugin_MotCfgCompareGrade_Configuration extends BaseConfiguration
{
    /**
     * Initialisation des models et des logs.
     */
    public function __construct()
    {
        $this->identifier = '[WS MOTCFG COMPAREGRADE]';
        $this->methodIdentifiers = array(
            'compareGrades' => 'compareGrades',
        );
        $this->models = array(
            'compareGrades' => array(
                'request' => array(
                    'model' => 'Plugin_MotCfgCompareGrade_Model_Request',
                ),
                'response' => array(
                    'model' => 'Plugin_MotCfgCompareGrade_Model_Response',
                    'mapping' => array(
                        'configResponse' => 'Plugin_MotCfgCompareGrade_Model_Response',
                    ),
                ),
            ),
        );

        $this->loggers['default'] = array(
            'writer' => 'file',
            'formater' => 'simple',
            'parameters' => array(
                'file' => \Pelican::$config['VAR_ROOT'].'/logs/MotCfgCompareGrade.log',
            ),
        );
    }
}
