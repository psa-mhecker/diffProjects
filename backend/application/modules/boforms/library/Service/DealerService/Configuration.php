<?php
//namespace Plugin\BOForms;

use Itkg\Service\Configuration as BaseConfiguration;

/**
 * Classe Configuration pour le service Instance
 */
class Plugin_DealerService_Configuration extends BaseConfiguration
{

    /**
     * Initialisation des models et des logs
     */
    public function __construct()
    {
        $this->identifier = '[CITROEN] - [DEALER SERVICE]';
        $this->methodIdentifiers = array(
            'geoLocalize' => 'geoLocalize',
            'getDealerList' => 'getDealerList'
        );
        $this->models = array(
            'geoLocalize' => array(
                'request' => array(
                    'model' => 'Plugin_BOForms_Model_GeoLocalizeRequest'
                ),
                'response' => array(
                    'model' => 'Plugin_BOForms_Model_GeoLocalizeResponse',
                    'mapping' => array(
                        'geoLocalizeResponse' => 'Plugin_BOForms_Model_GeoLocalizeResponse'
                    )
                )
            ),
            'getDealerList' => array(
                'request' => array(
                    'model' => 'Plugin_BOForms_Model_GetDealerListRequest'
                ),
                'response' => array(
                    'model' => 'Plugin_BOForms_Model_GetDealerListResponse',
                    'mapping' => array(
                        'getDealerListResponse' => 'Plugin_BOForms_Model_GetDealerListResponse'
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
