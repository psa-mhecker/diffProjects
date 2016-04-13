<?php
namespace Citroen\Batch;

use Itkg\Batch;

/**
 * Classe Planification
 *
 * Batch Planification : Permet de mettre à jour les pages en mode planification
 *
 * @author David Moaté <david.moate@businessdecision.com>
 */
class Planification extends Batch
{

    public function execute()
    {      
        $oConnection = \Pelican_Db::getInstance();
        $bind[":DATE_OF_DAY"] = $oConnection->strToBind (date("d/m/Y"));

        $pagesPlanifiees = $oConnection->queryTab(" SELECT PAGE_ID,PAGE_SCHEDULE_VERSION, PAGE_CURRENT_VERSION, PAGE_DRAFT_VERSION, LANGUE_ID 
                                                    FROM #pref#_page p
                                                    WHERE " . $oConnection->dateSqlToString("PAGE_START_DATE_SCHEDULE ", false) . " <= :DATE_OF_DAY
                                                    AND (SCHEDULE_STATUS is null OR SCHEDULE_STATUS != 1)", $bind);


        if(is_array($pagesPlanifiees)){
            Foreach($pagesPlanifiees as $pagePlanifiee){
                $bind[":PAGE_ID"] = $pagePlanifiee['PAGE_ID'];
                $bind[":PAGE_SCHEDULE_VERSION"] = $pagePlanifiee['PAGE_CURRENT_VERSION'];           
                $bind[":PAGE_CURRENT_VERSION"] = $pagePlanifiee['PAGE_SCHEDULE_VERSION'];
                $bind[":PAGE_DRAFT_VERSION"] = $pagePlanifiee['PAGE_SCHEDULE_VERSION'];
                if($pagePlanifiee['PAGE_DRAFT_VERSION'] != $pagePlanifiee['PAGE_CURRENT_VERSION']){
                    $bind[":PAGE_DRAFT_VERSION"] = $pagePlanifiee['PAGE_DRAFT_VERSION'];
                }
                $bind[":LANGUE_ID"] = $pagePlanifiee['LANGUE_ID'];

                if(!empty($pagePlanifiee['PAGE_SCHEDULE_VERSION']) && !empty($pagePlanifiee['PAGE_CURRENT_VERSION'])){
                    $oConnection->query("UPDATE #pref#_page_version 
                                        SET  STATE_ID = 4                            
                                        WHERE PAGE_ID  = :PAGE_ID
                                        AND PAGE_VERSION  = :PAGE_CURRENT_VERSION
                                        AND LANGUE_ID = :LANGUE_ID"
                                    , $bind);
                    $oConnection->query("UPDATE #pref#_page_version 
                                        SET  STATE_ID = 1                            
                                        WHERE PAGE_ID  = :PAGE_ID
                                        AND PAGE_VERSION  = :PAGE_SCHEDULE_VERSION
                                        AND LANGUE_ID = :LANGUE_ID"
                                    , $bind);            
                    $oConnection->query("UPDATE #pref#_page SET                         
                                PAGE_CURRENT_VERSION  = :PAGE_CURRENT_VERSION,
                                PAGE_DRAFT_VERSION  = :PAGE_DRAFT_VERSION,
                                PAGE_SCHEDULE_VERSION = :PAGE_SCHEDULE_VERSION,
                                SCHEDULE_STATUS = 1
                                WHERE PAGE_ID = :PAGE_ID", $bind);
                }
            }   
        }

        $pagesDePlanifiees = $oConnection->queryTab(" SELECT PAGE_ID,PAGE_SCHEDULE_VERSION, PAGE_CURRENT_VERSION, PAGE_DRAFT_VERSION, LANGUE_ID 
                                                    FROM #pref#_page p
                                                    WHERE " . $oConnection->dateSqlToString("PAGE_END_DATE_SCHEDULE ", false) . " <= :DATE_OF_DAY
                                                    AND SCHEDULE_STATUS = 1", $bind);

        if(is_array($pagesDePlanifiees)){
            Foreach($pagesDePlanifiees as $pageDePlanifiee){
                $bind[":PAGE_ID"] = $pageDePlanifiee['PAGE_ID'];
                $bind[":PAGE_SCHEDULE_VERSION"] = '';
                $bind[":PAGE_CURRENT_VERSION"] = $pageDePlanifiee['PAGE_SCHEDULE_VERSION'];
                $bind[":PAGE_DRAFT_VERSION"] = $pageDePlanifiee['PAGE_SCHEDULE_VERSION'];
                if($pageDePlanifiee['PAGE_DRAFT_VERSION'] != $pageDePlanifiee['PAGE_CURRENT_VERSION']){
                    $bind[":PAGE_DRAFT_VERSION"] = $pageDePlanifiee['PAGE_DRAFT_VERSION'];
                }
                $bind[":LANGUE_ID"] = $pageDePlanifiee['LANGUE_ID'];

                if(!empty($pageDePlanifiee['PAGE_SCHEDULE_VERSION']) && !empty($pageDePlanifiee['PAGE_CURRENT_VERSION'])){
                    $oConnection->query("UPDATE #pref#_page_version 
                                        SET  STATE_ID = 4                            
                                        WHERE PAGE_ID  = :PAGE_ID
                                        AND PAGE_VERSION  = :PAGE_CURRENT_VERSION
                                        AND LANGUE_ID = :LANGUE_ID"
                                    , $bind);
                    $oConnection->query("UPDATE #pref#_page SET                         
                                PAGE_CURRENT_VERSION  = :PAGE_CURRENT_VERSION,
                                PAGE_DRAFT_VERSION  = :PAGE_DRAFT_VERSION,
                                PAGE_SCHEDULE_VERSION = :PAGE_SCHEDULE_VERSION,
                                SCHEDULE_STATUS = 0
                                WHERE PAGE_ID = :PAGE_ID", $bind);
                }
            }   
        }
        echo 'Fin de la planification';
    }
}