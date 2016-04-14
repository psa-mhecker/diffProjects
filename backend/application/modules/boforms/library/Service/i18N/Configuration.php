<?php
//namespace Plugin\BOForms;

use Itkg\Service\Configuration as BaseConfiguration;

/**
 * Classe Configuration pour le service Instance
 */
class Plugin_I18N_Configuration extends BaseConfiguration
{

    /**
     * Initialisation des models et des logs
     */
    public function __construct()
    {
        $this->identifier = '[CITROEN] - [I18N]';
        $this->methodIdentifiers = array(
            'getXMLComponent' => 'getXMLComponent',
            'updateXMLComponent' => 'updateXMLComponent'
        );
        $this->models = array(
            'getXMLComponent' => array(
                'request' => array(
                    'model' => 'Plugin_I18N_Model_GetXMLComponentRequest'
                ),
                'response' => array(
                    'model' => 'Plugin_I18N_Model_GetXMLComponentResponse',
                    'mapping' => array(
                        'getXMLComponentResponse' => 'Plugin_I18N_Model_GetXMLComponentResponse'
                    )
                )
            ),
            'updateXMLComponent' => array(
                'request' => array(
                    'model' => 'Plugin_I18N_Model_UpdateXMLComponentRequest'
                ),
                'response' => array(
                    'model' => 'Plugin_I18N_Model_UpdateXMLComponentResponse',
                    'mapping' => array(
                        'updateXMLComponentResponse' => 'Plugin_I18N_Model_UpdateXMLComponentResponse'
                    )
                )
            )

            
        );
        $this->loggers['default'] = array(
            'writer' => 'file',
            'formater' => 'simple',
            'parameters' => array(
                'file' => \Pelican::$config['VAR_ROOT'].'/logs/BOForms.log'
            )
        );
    }

}