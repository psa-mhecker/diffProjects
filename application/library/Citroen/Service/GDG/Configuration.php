<?php
namespace Citroen\Service\GDG;

use Itkg\Service\Configuration as BaseConfiguration;

/**
 * Classe Configuration pour le service GDG
 */
class Configuration extends BaseConfiguration
{

    /**
     * Initialisation des models et des logs
     */
    public function __construct()
    {
        $this->identifier = '[CITROEN] - [GDG]';
        $this->methodIdentifiers = array(
            'getCarPicker'  => 'getCarPicker',
            'getBrochure'   => 'getBrochure'
        );
        $this->models = array(
            'getCarPicker' => array(
                'request' => array(
                    'model' => 'Citroen\Service\GDG\Model\GetCarPickerRequest'
                ),
                'response' => array(
                    'model' => 'Citroen\Service\GDG\Model\GetCarPickerResponse',
                    'mapping' => array(
                        'GetCarPickerResponse' => 'Citroen\Service\GDG\Model\GetCarPickerResponse'
                    )
                )
            ),
            'getBrochure' => array(
                'request' => array(
                    'model' => 'Citroen\Service\GDG\Model\GetBrochureRequest'
                ),
                'response' => array(
                    'model' => 'Citroen\Service\GDG\Model\GetBrochureResponse',
                    'mapping' => array(
                        'GetBrochureResponse' => 'Citroen\Service\GDG\Model\GetBrochureResponse'
                    )
                )
            )            
        );
        $this->loggers['default'] = array(
            'writer' => 'file',
            'formater' => 'simple',
            'parameters' => array(
                'file' => \Pelican::$config['VAR_ROOT'].'/logs/GDG.log'
            )
        );
    }

}