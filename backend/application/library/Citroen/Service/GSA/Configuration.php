<?php
namespace Citroen\Service\GSA;

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
        $this->identifier = '[CITROEN] - [GSA]';
        $this->methodIdentifiers = array(
            'search' => 'search',
        );
        $this->models = array(
            'search' => array(
                'request' => array(
                    'model' => 'Citroen\Service\GSA\Model\SearchRequest',
                ),
                'response' => array(
                    'model' => 'Citroen\Service\GSA\Model\SearchResponse',
                    'mapping' => array(
                        'SearchResponse' => 'Citroen\Service\GSA\Model\SearchResponse',
                    ),
                ),
            ),
            'suggest' => array(
                'request' => array(
                    'model' => 'Citroen\Service\GSA\Model\SuggestRequest',
                ),
                'response' => array(
                    'model' => 'Citroen\Service\GSA\Model\SuggestResponse',
                    'mapping' => array(
                        'SuggestResponse' => 'Citroen\Service\GSA\Model\SuggestResponse',
                    ),
                ),
            ),
        );
        $this->loggers['default'] = array(
            'writer' => 'file',
            'formater' => 'simple',
            'parameters' => array(
                'file' => \Pelican::$config['VAR_ROOT'].'/logs/GSA.log',
            ),
        );
    }
}
