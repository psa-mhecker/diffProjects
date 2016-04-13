<?php
 
namespace Citroen\Batch\Extractfolabels;
 
use Itkg\Batch\Configuration as BaseConfiguration;
 
/**
* Classe Configuration
*
* Classe de configuration du batch Extractfolabels
*
* @author Christophe VRIGNAUD <christophe.vrignaud@businessdecision.com>
*/
class Configuration extends BaseConfiguration
{
    public function __construct()
    {
        $this->identifier = 'EXTRACTFOLABELS';
    }
} 
