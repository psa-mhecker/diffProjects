<?php

namespace Citroen\Batch;

use Itkg\Batch;

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
        
        $oConnection = \Pelican_Db::getInstance();
        
        // récupération des pages qui vont changer de status
        $sSql = '
            SELECT p.PAGE_ID, p.LANGUE_ID, p.SITE_ID, p.PAGE_STATUS
            FROM #pref#_page as p
            INNER JOIN #pref#_page_version as pv 
                ON (p.page_id = pv.page_id and p.page_current_version = pv.page_version and p.langue_id = pv.langue_id)
            WHERE (pv.page_start_date < now()
            and pv.page_end_date > now()
            and p.page_status = 0)
            OR (pv.page_end_date < now()
            and p.page_status = 1)
        ';
        $aResult = $oConnection->queryTab($sSql);
        
        // Requête de mise en ligne
        $sSqlOn = '
            UPDATE #pref#_page as p
            INNER JOIN #pref#_page_version as pv 
                ON (p.page_id = pv.page_id and p.page_current_version = pv.page_version and p.langue_id = pv.langue_id)
            SET p.page_status = 1
            WHERE pv.page_start_date < now()
            and pv.page_end_date > now()
            and p.page_status = 0
            ';
        $oConnection->query($sSqlOn);
        
        // Requête de depublication
        $sSqlOff = '
            UPDATE #pref#_page as p
            INNER JOIN #pref#_page_version as pv 
                ON (p.page_id = pv.page_id and p.page_current_version = pv.page_version and p.langue_id = pv.langue_id)
            SET p.page_status = 0
            WHERE pv.page_end_date < now()
            and p.page_status = 1
            ';
        $oConnection->query($sSqlOff);
        
        // Script
        echo 'passage dans script';
    }
}