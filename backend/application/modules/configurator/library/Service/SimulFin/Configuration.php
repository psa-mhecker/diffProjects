<?php

use Itkg\Service\Configuration as BaseConfiguration;

/**
 * Classe Configuration pour le service SimulFin.
 */
class Configuration extends BaseConfiguration
{
    /**
     * Initialisation des models et des logs.
     */
    public function __construct()
    {
        $this->identifier = '[CITROEN] - [SIMULFIN]';
        $this->methodIdentifiers = array(
            'openSession' => 'openSession',
            'saveCalculationDisplay' => 'saveCalculationDisplay',
        );
        $this->models = array(
            'openSession' => array(
                'request' => array(
                    'model' => 'OpenSessionRequest',
                ),
                'response' => array(
                    'model' => 'OpenSessionResponse',
                    'mapping' => array(
                        'OpenSessionResponse' => 'OpenSessionResponse',
                    ),
                ),
            ),
            'saveCalculationDisplay' => array(
                'request' => array(
                    'model' => 'SaveCalculationDisplayRequest',
                ),
                'response' => array(
                    'model' => 'SaveCalculationDisplayResponse',
                    'mapping' => array(
                        'SaveCalculationDisplayResponse' => 'SaveCalculationDisplayResponse',
                    ),
                ),
            ),
        );
        $this->loggers['default'] = array(
            'writer' => 'file',
            'formater' => 'simple',
            'parameters' => array(
                'file' => \Pelican::$config['VAR_ROOT'].'/logs/SIMULFIN.log',
            ),
        );
    }
}
