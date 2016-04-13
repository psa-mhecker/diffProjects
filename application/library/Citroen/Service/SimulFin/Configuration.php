<?php
namespace Citroen\Service\SimulFin;

use Itkg\Service\Configuration as BaseConfiguration;

/**
 * Classe Configuration pour le service SimulFin
 */
class Configuration extends BaseConfiguration
{

    /**
     * Initialisation des models et des logs
     */
    public function __construct()
    {
        $this->identifier = '[CITROEN] - [SIMULFIN]';
        $this->methodIdentifiers = array(
            'openSession' => 'openSession',
            'saveCalculationDisplay' => 'saveCalculationDisplay'
        );
        $this->models = array(
            'openSession' => array(
                'request' => array(
                    'model' => 'Citroen\Service\SimulFin\Model\OpenSessionRequest'
                ),
                'response' => array(
                    'model' => 'Citroen\Service\SimulFin\Model\OpenSessionResponse',
                    'mapping' => array(
                        'OpenSessionResponse' => 'Citroen\Service\SimulFin\Model\OpenSessionResponse'
                    )
                )
            ),
            'saveCalculationDisplay' => array(
                'request' => array(
                    'model' => 'Citroen\Service\SimulFin\Model\SaveCalculationDisplayRequest'
                ),
                'response' => array(
                    'model' => 'Citroen\Service\SimulFin\Model\SaveCalculationDisplayResponse',
                    'mapping' => array(
                        'SaveCalculationDisplayResponse' => 'Citroen\Service\SimulFin\Model\SaveCalculationDisplayResponse'
                    )
                )
            )
        );
        $this->loggers['default'] = array(
            'writer' => 'file',
            'formater' => 'simple',
            'parameters' => array(
                'file' => \Pelican::$config['VAR_ROOT'].'/logs/SIMULFIN.log'
            )
        );
    }

}