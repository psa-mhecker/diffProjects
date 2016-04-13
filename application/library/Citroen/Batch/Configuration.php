<?php

namespace Citroen\Batch;

use Itkg\Component\Console\Configuration as BaseConfiguration;

/**
 * Classe Configuration
 *
 * Environnement de configuration globale à l'ensemble de tous les batch Citroen
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class configuration extends BaseConfiguration
{
    /**
     * Constructeur
     * 
     * Initialise la configuration de base partagée par l'ensemble des batch Citroen
     */
    public function __construct()
    {
        $this->identifier = 'CITROEN';
        
        $includes = array(
            'config-cli.php'
        );
        $this->addIni('display_errors', 1);
        
        $this->setIncludes($includes);
        $this->setIncludePath(get_include_path());
        $this->loggers[] = array(
            'echo'
        );

        if(isset(\Pelican::$config['PHP_BINARY']) ) {
            $this->parameters['PHP_BINARY'] = \Pelican::$config['PHP_BINARY'];
        }   
    }
}