<?php

/**
 * Menu de gauche affichant les états de workflow du tableau de bord
 *
 * @package Pelican_BackOffice
 * @subpackage Navigation
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 05/05/2004
 */

class Workflow_Navigation_Controller extends Pelican_Controller_Back
{

    public function indexAction ()
    {
    	$aOnglet = $this->getParam('aOnglet');
        $navigation = $aOnglet[$_GET["view"]]['navigation'];
        
        if ($_SESSION[APP]["content_type"]["id"]) {
            $idNiveau1 = $aOnglet[$_GET["view"]]["id"];
            $menu = $navigation;
            $templateWorkflow = Pelican::$config["TPL_CONTENT"];
            
            $tmp = $_SESSION[APP]["state"]["id"];
            foreach ($tmp as $key => $value) {
                $state[] = $key;
            }
            $directory = Pelican_Cache::fetch("Backend/State", $state);
            
            $directory[] = array(
                "id" => "A" , 
                "lib" => '<b>&nbsp;Workflow<\/b>' , 
                "order" => "0" , 
                "icon" => $this->getView()->getHead()->skinPath . "/images/tree_workflow.gif"
            );
            
            // Affichage de la hiérarchie
            $oTree = Pelican_Factory::getInstance('Hierarchy.Tree',"dtree" . $_GET["view"] . $_SESSION[APP]['SITE_ID'], "id", "pid");
            $oTree->addTabNode($directory);
            $oTree->setOrder("order", "ASC");
            $oTree->setTreeType("dtree");
            $return = $oTree->getTree();
            
            /** javascript a lancer */
            $init = "top.initTree";
            if (valueExists($_GET, "item")) {
                $init = "true,dtree" . $_GET["view"] . $_SESSION[APP]['SITE_ID'] . ".aIncrement[" . $_GET["item"] . "]";
            }
            $this->getView()->default = "<script type=\"text/javascript\">
		dtree" . $_GET["view"] . $_SESSION[APP]['SITE_ID'] . ".doDefault(" . $init . ");
		top.initTree=false;
		</script>";
            $this->getRequest()->setResponse($return);
        }
    }
}
?>