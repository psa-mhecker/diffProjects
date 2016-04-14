<?php
namespace Citroen\Service\BoutiqAcc;

use Itkg\Service\Configuration as BaseConfiguration;

/**
 * Classe Configuration pour le service BoutiqAcc.
 */
class Configuration extends BaseConfiguration
{
    /**
     * Initialisation des models et des logs.
     */
    public function __construct()
    {
        $this->identifier = '[CITROEN] - [BOUTIQACC]';
        $this->methodIdentifiers = array(
            'getAccessories' => 'getAccessories',
            'getCriteriaValues' => 'getCriteriaValues',
        );
        $this->models = array(
            'getAccessories' => array(
                'request' => array(
                    'model' => 'Citroen\Service\BoutiqAcc\Model\GetAccessoriesRequest',
                ),
                'response' => array(
                    'model' => 'Citroen\Service\BoutiqAcc\Model\GetAccessoriesResponse',
                    'mapping' => array(
                        'getAccessoriesResponse' => 'Citroen\Service\BoutiqAcc\Model\GetAccessoriesResponse',
                    ),
                ),
            ),
            'getCriteriaValues' => array(
                'request' => array(
                    'model' => 'Citroen\Service\BoutiqAcc\Model\GetCriteriaValuesRequest',
                ),
                'response' => array(
                    'model' => 'Citroen\Service\BoutiqAcc\Model\GetCriteriaValuesResponse',
                    'mapping' => array(
                        'getCriteriaValuesResponse' => 'Citroen\Service\BoutiqAcc\Model\GetCriteriaValuesResponse',
                    ),
                ),
            ),
        );
        $this->loggers['default'] = array(
            'writer' => 'file',
            'formater' => 'simple',
            'parameters' => array(
                'file' => \Pelican::$config['VAR_ROOT'].'/logs/BOUTIQACC.log',
            ),
        );
    }
}
