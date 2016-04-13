<?php
 
namespace Citroen\Batch\Planification;
 
use Itkg\Batch\Configuration as BaseConfiguration;
 
/**
* Classe Configuration
*
* Classe de configuration du batch Planification
*
* @author David Moaté <david.moate@businessdecision.com>
*/
class Configuration extends BaseConfiguration
{
    public function __construct()
    {
        $this->identifier = 'PLANIFICATION';
    }
} 
