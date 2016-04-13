<?php
 
namespace Citroen\Batch\Backup;
 
use Itkg\Batch\Configuration as BaseConfiguration;
 
/**
* Classe Configuration
*
* Classe de configuration du batch IPass
*
* @author Raphaël Carles <raphael.Carles@businessdecision.com>
*/
class Configuration extends BaseConfiguration
{
    public function __construct()
    {
        $this->identifier = 'BACKUP';
    }
} 
