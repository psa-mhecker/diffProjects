<?php
namespace Citroen\Service\AnnuPDV;

use Itkg\Service\Configuration as BaseConfiguration;

/**
 * Classe Configuration pour le service BoutiqAcc
 */
class Configuration extends BaseConfiguration
{

    /**
     * Initialisation des models et des logs
     */
    public function __construct()
    {
        $this->identifier = '[CITROEN] - [ANNUPDV]';
        $this->methodIdentifiers = array(
            'geoLocalize' => 'geoLocalize',
            'getDealer' => 'getDealer',
            'getDealersList' => 'getDealersList',
            'getBusinessList' => 'getBusinessList'
        );
        $this->models = array(
            'geoLocalize' => array(
                'request' => array(
                    'model' => 'Citroen\Service\AnnuPDV\Model\GeoLocalizeRequest'
                ),
                'response' => array(
                    'model' => 'Citroen\Service\AnnuPDV\Model\GeoLocalizeResponse',
                    'mapping' => array(
                        'GeoLocalizeResponse' => 'Citroen\Service\AnnuPDV\Model\GeoLocalizeResponse'
                    )
                )
            ),
            'getDealer' => array(
                'request' => array(
                    'model' => 'Citroen\Service\AnnuPDV\Model\GetDealerRequest'
                ),
                'response' => array(
                    'model' => 'Citroen\Service\AnnuPDV\Model\GetDealerResponse',
                    'mapping' => array(
                        'GetDealerResponse' => 'Citroen\Service\AnnuPDV\Model\GetDealerResponse'
                    )
                )
            ),
            'getDealersList' => array(
                'request' => array(
                    'model' => 'Citroen\Service\AnnuPDV\Model\GetDealersListRequest'
                ),
                'response' => array(
                    'model' => 'Citroen\Service\AnnuPDV\Model\GetDealersListResponse',
                    'mapping' => array(
                        'GetDealersListResponse' => 'Citroen\Service\AnnuPDV\Model\GetDealersListResponse'
                    )
                )
            )
            ,
            'getBusinessList' => array(
                'request' => array(
                    'model' => 'Citroen\Service\AnnuPDV\Model\GetBusinessListRequest'
                ),
                'response' => array(
                    'model' => 'Citroen\Service\AnnuPDV\Model\GetBusinessListResponse',
                    'mapping' => array(
                        'GetBusinessListResponse' => 'Citroen\Service\AnnuPDV\Model\GetBusinessListResponse'
                    )
                )
            )
            
        );
        $this->loggers['default'] = array(
            'writer' => 'file',
            'formater' => 'simple',
            'parameters' => array(
                'file' => \Pelican::$config['VAR_ROOT'].'/logs/ANNUPDV.log'
            )
        );
    }

}