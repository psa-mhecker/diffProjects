<?php
/**
 * User: dmoate
 * Date: 15/05/14
 */
include("config.php");
$oConnection = Pelican_Db::getInstance();

if(is_numeric($_GET['siteID']) && is_numeric($_GET['langueID'])){

    $aBind[':SITE_ID']      = $_GET['siteID'];
    $aBind[':LANGUE_ID']    = $_GET['langueID'];
    $aBind[':STATE_ID']     = 5;
    
    // Récupération de toutes les pages visibles dans l'arbo
    $sql = "
        SELECT
        p.PAGE_ID as \"id\",
        p.PAGE_PARENT_ID as \"pid\",
        p.PAGE_ORDER as \"order\",
        p.PAGE_PATH as \"path\",
        p.PAGE_STATUS as \"status\",
        p.PAGE_CURRENT_VERSION as \"current_version\",
        COALESCE(pv1.PAGE_TITLE_BO) as \"lib\"
        FROM psa_page p LEFT JOIN psa_page p1 on (p.PAGE_ID = p1.PAGE_ID AND p1.langue_id = :LANGUE_ID)
        LEFT JOIN psa_page_version pv1 on (p1.PAGE_ID = pv1.PAGE_ID AND p1.PAGE_DRAFT_VERSION = pv1.PAGE_VERSION AND pv1.LANGUE_ID = p1.langue_id) 
        WHERE p.SITE_ID=:SITE_ID AND (pv1.STATE_ID <> :STATE_ID) GROUP BY p.PAGE_ID         
    ";
    
    $aPage = $oConnection->queryTab($sql, $aBind);
    
    if( empty ($aPage)){
        echo "Erreur avec l'association langueId et siteId";
        die;
    }
    
    $oTree = Pelican_Factory::getInstance('Hierarchy.Tree', 'dtree' . 0 . ($site_id ? $site_id : $_SESSION [APP] ['SITE_ID']), "id", "pid");
    $oTree->addTabNode($aPage);
    $oTree->setOrder("order", "ASC");
    $oTree->setTreeType($type);
    $arrayIds = array();
    $aListPage = $oTree->buildJsonTree($oTree->aNodes);
    
    // pour récupérer l'id de la page global
    $aGlobal   =   buildListIdPage($aListPage[0],$arrayIds);
    
    // pour récupérer les ids de la page d'accueil et ses enfants
    $aPageIds   =   buildListIdPage($aListPage[1],$arrayIds);
    
    $aListeId   = array_merge($aGlobal, $aPageIds);

    $aListeId = implode(',', $aListeId);
    
    $aBind[":PAGE_ID"]      = $pageId;
    $aBind[':SITE_ID']      = $_GET['siteID'];
    $aBind[':LANGUE_ID']    = $_GET['langueID'];
    
    if(!empty($aListeId)){
        //Récupération de toutes les pages à supprimer qui ne sont pas visible dans l'arbo
        $sql ="
            SELECT distinct(p.PAGE_ID)
            from psa_page p inner join 
            psa_page_version pv on p.PAGE_ID = pv.PAGE_ID 
            WHERE p.SITE_ID = :SITE_ID 
            and pv.LANGUE_ID = :LANGUE_ID 
            AND p.PAGE_ID NOT IN ( $aListeId )";


        $aListePageIdCleanBdd   =   $oConnection->queryTab($sql, $aBind);
        if(empty($aListePageIdCleanBdd)){
            echo "pas de page à mettre à la corbeille";die;
        }
        if(is_array($aListePageIdCleanBdd)){
            foreach($aListePageIdCleanBdd as $key => $pageId){
                $aListePageIdClean[$key]    =   $pageId['PAGE_ID'];
            }
            $aListeIdClean = implode(',', $aListePageIdClean);
        }        

        $aBind[":STATE_ID"]     = 5;
        $aBind[':LANGUE_ID']    = $_GET['langueID'];
        
        // Mise à jour de l'etat de toutes les pages qui ne sont pas dans le tableau en arbo en brouillon (etat 5)
        if(!empty($aListeIdClean)){
            foreach($aListePageIdCleanBdd as $key => $pageId2){
                echo 'Mise en corbeille ou suppression en base de la page: ' .  $pageId2['PAGE_ID'] . '</br>';
            }
            echo 'Total : ' . count($aListePageIdCleanBdd) . ' pages <br/>';
            $sqlUpdate = "
                        UPDATE #pref#_page_version
                        SET STATE_ID = :STATE_ID
                        WHERE LANGUE_ID = :LANGUE_ID AND PAGE_ID IN (  $aListeIdClean  )";

            $oConnection->query($sqlUpdate, $aBind);
            echo "Succès du nettoyage";
        }
    }

}else{
     echo "merci de passer un siteId et un langueId dans l'url";
}


function buildListIdPage($aListPage, $array) {
    $array[$aListPage->id] = $aListPage->id;
    if(isset($aListPage->children) && !empty($aListPage->children)){
        foreach($aListPage->children as $children){
             $array = buildListIdPage($children,$array);
        }
    }
    return $array;
}