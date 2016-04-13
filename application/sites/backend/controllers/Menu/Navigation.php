<?php

/**
 * Menu de gauche affichant l'arborescence des fonctionnalités disponibles pour l'onglet courant
 *
 * @package Pelican_BackOffice
 * @subpackage Navigation
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 05/05/2004
 */
class Menu_Navigation_Controller extends Pelican_Controller
{

    public function indexAction ()
    {
    	$aOnglet = $this->getParam('aOnglet');
        $navigation = $aOnglet[$_GET["view"]]['navigation'];
        
        if ($navigation) {
            foreach ($navigation as $menu => $menu_value) {
                if ($_GET["view"] == "O_" . $menu_value["pid"]) {
                    $menu_value["pid"] = "0";
                }
                $directory[] = $menu_value;
            }
        }
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
        $this->setResponse($return);
    }
}
?>