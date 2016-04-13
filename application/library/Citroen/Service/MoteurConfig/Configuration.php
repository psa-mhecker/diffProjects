<?php
namespace Citroen\Service\MoteurConfig;

use Itkg\Service\Configuration as BaseConfiguration;

/**
 * Classe Configuration pour le service Moteur de Config
 */
class Configuration extends BaseConfiguration
{

    /**
     * Initialisation des models et des logs
     */
    public function __construct()
    {
        $this->identifier = '[CITROEN] - [MOTEURCONFIG]';
        $this->methodIdentifiers = array(
            'Select' => 'Select'
        );
        $this->models = array(
            'Select' => array(
                'request' => array(
                    'model' => 'Citroen\Service\MoteurConfig\Model\SelectRequest'
                ),
                'response' => array(
                    'model' => 'Citroen\Service\MoteurConfig\Model\SelectResponse',
                    'mapping' => array(
                        'SelectResponse' => 'Citroen\Service\MoteurConfig\Model\SelectResponse'
                    )
                )
            )
        );
		
        $this->loggers['default'] = array(
            'writer' => 'file',
            'formater' => 'simple',
            'parameters' => array(
                'file' => \Pelican::$config['VAR_ROOT'].'/logs/MOTEURCONFIG.log'
            )
        );
    }

}