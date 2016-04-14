<?php

namespace Citroen\Batch\Wurfl;

use Itkg\Batch\Configuration as BaseConfiguration;

/**
 * Classe Configuration.
 *
 * Classe de configuration du batch IPass
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Configuration extends BaseConfiguration
{
    public function __construct()
    {
        $this->identifier = 'WURFL';
    }
}
