<?php

namespace Citroen\Batch;

use Itkg\Batch;
use Citroen\GammeFinition\Gamme as GammeFinition;

/**
 * Classe IPass
 *
 * Batch IPass : Permet de mettre à jour les règles de macros et 
 * micros-éligibilités depuis le WS getApplications
 * 
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Gamme extends Batch
{
    public function execute()
    {
        
        $oGamme = new GammeFinition;
        $oGamme->importAllCSVData();
        // Script
        echo 'passage dans script';
    }
}