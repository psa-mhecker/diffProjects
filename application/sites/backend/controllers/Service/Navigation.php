<?php

/**
 * Menu de gauche affichant les états de workflow du tableau de bord
 *
 * @package Pelican_BackOffice
 * @subpackage Navigation
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 05/05/2004
 */

class Service_Navigation_Controller extends Pelican_Controller
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
        

        $directory[] = array("id" => "A" , "lib" => '<b>&nbsp;Services du site<\/b>' , "order" => "_0" , "icon" => "/library/Pelican/Hierarchy/Tree/public/images/base.gif");
        $oConnection = Pelican_Db::getInstance();
        $aServices = $oConnection->queryTab("SELECT s.SERVICE_ID,s.SERVICE_LABEL, st.TEMPLATE_ID, s.SERVICE_ORDER
										 FROM " . Pelican::$config['FW_PREFIXE_TABLE'] . "service s, " . Pelican::$config['FW_PREFIXE_TABLE'] . "service_type st
										 WHERE s.service_parent_id is null
										 AND st.service_type_id = s.service_type_id
										 ORDER BY service_order ASC");
        
        if ($aServices) {
            foreach ($aServices as $serv) {
                $url = "/_/public/child?tid=" . $serv["TEMPLATE_ID"] . "&tc=&view=" . $_GET["view"] . "&media=image&order=SERVICE_ID&navRows=1&navPage=1&navLimitRows=20&navMaxLinks=9&navFirstPage=1&navMinRow=1&navMaxRow=1&id=" . $serv["SERVICE_ID"];
                $directory[] = array("id" => $serv["SERVICE_ID"] , "pid" => "A" , "lib" => '<b>&nbsp;<a href="javascript:menu(\'' . $serv["TEMPLATE_ID"] . '\',\'\',\'' . $serv["SERVICE_ID"] . '\')">' . $serv["SERVICE_LABEL"] . '</a><\/b>' , "order" => $serv["SERVICE_ORDER"] , "icon" => "/library/Pelican/Hierarchy/Tree/public/images/page.gif");
            }
            $menu = "menu('" . $aServices[0]["TEMPLATE_ID"] . "','','" . $aServices[0]["SERVICE_ID"] . "');";
        
        }
        
        $directory[] = array("id" => "B" , "lib" => '<b>'.t('Statistics').'<\/b>' , "order" => "_1" , "icon" => "/library/Pelican/Hierarchy/Tree/public/images/base.gif");
        
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
?>