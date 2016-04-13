<?php
/**
 * Formulaire de gestion des controllers de page de la plate-forme
 *
 * @package Pelican_BackOffice
 * @subpackage Administration
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 26/12/2004
 */

pelican_import('Hierarchy.List');

class Administration_Template_Page_Controller extends Pelican_Controller_Back
{

    protected $administration = true;

    protected $form_name = "template_page";

    protected $field_id = "TEMPLATE_PAGE_ID";

    protected $defaultOrder = " #pref#_template_page.PAGE_TYPE_ID, TEMPLATE_PAGE_LABEL";

    protected $readOArea;

    protected $isPortal = false;

    protected $decacheBack = array ("Template/Page" );

    public function before()
    {
        
        if (!empty($_REQUEST['form_name'])) {
            $this->form_name = $_REQUEST['form_name'];
        } else {
            $this->defaultOrder = " SITE_ID, " . $this->listOrder;
            
            // a transformer en action duplicateAction
            if ($_GET["dup"] && !$_GET["readO"]) {
                $this->_forward('duplicate');
            }
            
            // action ajout areaAction
            if ($_GET["area"] && $_GET["ordre"] && $_GET["champ"]) {
                $this->_forward("area");
            }
            
            $_GET["area"] = "";
            $_GET["ordre"] = "";
            $_GET["champ"] = "";
            
            if ($this->id == "temp") {
                $this->id = "-2";
            }
            
            if ($_GET["tpid"]) {
                if ($_GET["aid"]) {
                    $this->form_name = "zone_template";
                    $this->_forward('zonetemplate');
                } else {
                    $this->form_name = "template_page_area";
                    $this->_forward('templatepagearea');
                }
            }
        }
        parent::before();
    }

    public function duplicateAction()
    {
        
        
        $oConnection = Pelican_Db::getInstance();
        
        /** TEMPLATE_PAGE */
        $this->aBind[":TEMPLATE_PAGE_ID"] = $this->id;
        Pelican_Db::$values = $oConnection->queryRow("SELECT tp.* from #pref#_template_page tp WHERE TEMPLATE_PAGE_ID=:TEMPLATE_PAGE_ID", $this->aBind);
        Pelican_Db::$values["TEMPLATE_PAGE_ID"] = $oConnection->getNextId("#pref#_template_page", $this->aBind);
        Pelican_Db::$values["TEMPLATE_PAGE_LABEL"] = "- Nouveau Gabarit -";
        $oConnection->insertQuery("#pref#_template_page");
        $id = Pelican_Db::$values["TEMPLATE_PAGE_ID"];
        /** ZONE */
        $this->aBind[":TEMPLATE_PAGE_ID"] = $this->id;
        $area = $oConnection->queryTab("SELECT * from #pref#_template_page_area WHERE TEMPLATE_PAGE_ID=:TEMPLATE_PAGE_ID", $this->aBind);
        if ($area) {
            foreach ($area as Pelican_Db::$values) {
                Pelican_Db::$values["TEMPLATE_PAGE_ID"] = $id;
                $oConnection->insertQuery("#pref#_template_page_area");
            }
        }
        /** blocs */
        $this->aBind[":TEMPLATE_PAGE_ID"] = $this->id;
        $zone = $oConnection->queryTab("SELECT zt.* from #pref#_zone_template zt WHERE TEMPLATE_PAGE_ID=:TEMPLATE_PAGE_ID order by ZONE_TEMPLATE_ORDER", $this->aBind);
        if ($zone) {
            foreach ($zone as Pelican_Db::$values) {
                Pelican_Db::$values["ZONE_TEMPLATE_ID"] = -2;
                Pelican_Db::$values["TEMPLATE_PAGE_ID"] = $id;
                $oConnection->insertQuery("#pref#_zone_template");
            }
            ;
        }
        /** Redirection */
        echo Pelican_Html::script("document.location.href='" . str_replace("&id=" . $this->id, "&id=" . Pelican_Db::$values["TEMPLATE_PAGE_ID"], str_replace("&dup=true", "", $_SERVER["REQUEST_URI"])) . "'");
    }

    public function areaAction()
    {
        $_SERVER["REQUEST_URI"] = str_replace("&area=" . $_GET["area"] . "&ordre=" . $_GET["ordre"] . "&champ=" . $_GET["champ"], "", $_SERVER["REQUEST_URI"]);
        
        $_GET["operateur"] = ">=";
        if ($_GET["ordre"] == 1) {
            $_GET["ordre"] = "+1";
        }
        $sql = "update #pref#_template_page_area set " . $_GET["champ"] . "=" . $_GET["champ"] . $_GET["ordre"] . " where " . $_GET["champ"] . $_GET["operateur"] . $_GET["area"] . " and TEMPLATE_PAGE_ID=" . $this->id;
        $oConnection->query($sql);
        echo "<script>document.location.href='" . $_SERVER["REQUEST_URI"] . "'</script>";
    }

    protected function beforeDelete()
    {
        
        
        $oConnection = Pelican_Db::getInstance();
        
        $typePage = getPageTypeCode(Pelican_Db::$values['PAGE_TYPE_ID']);
        
        if (!empty(Pelican_Db::$values["TEMPLATE_PAGE_ID"])) {
            if ($typePage == 'PORTAL') {
                $oConnection->query("delete from #pref#_" . strtolower(Pelican::$config['PORTAL_AUTH_TABLE']) . "_page_zone where ZONE_TEMPLATE_ID IN (SELECT ZONE_TEMPLATE_ID FROM #pref#_zone_template WHERE TEMPLATE_PAGE_ID=" . Pelican_Db::$values["TEMPLATE_PAGE_ID"] . ")");
                $oConnection->query("delete from #pref#_" . strtolower(Pelican::$config['PORTAL_AUTH_TABLE']) . "_zone_template where ZONE_TEMPLATE_ID IN (SELECT ZONE_TEMPLATE_ID FROM #pref#_zone_template WHERE TEMPLATE_PAGE_ID=" . Pelican_Db::$values["TEMPLATE_PAGE_ID"] . ")");
            }
            $oConnection->query("delete from #pref#_page_zone_content where ZONE_TEMPLATE_ID IN (SELECT ZONE_TEMPLATE_ID FROM #pref#_zone_template WHERE TEMPLATE_PAGE_ID=" . Pelican_Db::$values["TEMPLATE_PAGE_ID"] . ")");
            $oConnection->query("delete from #pref#_page_zone_media where ZONE_TEMPLATE_ID IN (SELECT ZONE_TEMPLATE_ID FROM #pref#_zone_template WHERE TEMPLATE_PAGE_ID=" . Pelican_Db::$values["TEMPLATE_PAGE_ID"] . ")");
            $oConnection->query("delete from #pref#_page_zone_multi where ZONE_TEMPLATE_ID IN (SELECT ZONE_TEMPLATE_ID FROM #pref#_zone_template WHERE TEMPLATE_PAGE_ID=" . Pelican_Db::$values["TEMPLATE_PAGE_ID"] . ")");
            $oConnection->query("delete from #pref#_page_zone where ZONE_TEMPLATE_ID IN (SELECT ZONE_TEMPLATE_ID FROM #pref#_zone_template WHERE TEMPLATE_PAGE_ID=" . Pelican_Db::$values["TEMPLATE_PAGE_ID"] . ")");
            $oConnection->query("delete from #pref#_zone_template where TEMPLATE_PAGE_ID=" . Pelican_Db::$values["TEMPLATE_PAGE_ID"]);
            $oConnection->query("delete from #pref#_template_page_area where TEMPLATE_PAGE_ID=" . Pelican_Db::$values["TEMPLATE_PAGE_ID"]);
        }
    }

    public function saveAction()
    {
        
        
        //var_dump($_REQUEST);
        if ($this->form_name == "zone_template") {
            $this->_zoneTemplateSave();
        }
        
        if ($this->form_name == "template_page_area") {
            $this->_templatePageAreaSave();
        }
        if ($this->form_name == "template_page") {
            $this->_templatePageSave();
        
        }
        
        if ($this->form_action == "INS") {
            Pelican_Db::$values["form_retour"] = str_replace("id=-2", "id=" . Pelican_Db::$values["TEMPLATE_PAGE_ID"], $_SERVER["HTTP_REFERER"]);
            Pelican_Db::$values["form_retour"] = str_replace(array(
                'tpid=', 
                'aid='
            ), array(
                'void='
            ), Pelican_Db::$values["form_retour"]);
        }
    }

    protected function afterSave()
    {
        /** décache de fichiers */
        Pelican_Cache::clean("Frontend/Page/Zone");
    }

    protected function _zoneTemplateSave()
    {


        $oConnection = Pelican_Db::getInstance();
        
        if (!Pelican_Db::$values["ZONE_TEMPLATE_ORDER"]) {
            Pelican_Db::$values["ZONE_TEMPLATE_ORDER"] = $oConnection->queryItem("select max(ZONE_TEMPLATE_ORDER) from #pref#_zone_template where TEMPLATE_PAGE_ID=:TEMPLATE_PAGE_ID", array(
                ":TEMPLATE_PAGE_ID" => Pelican_Db::$values["TEMPLATE_PAGE_ID"]
            )) + 1;
        }
        
        /** cas de la suppression */
        if (Pelican_Db::$values["id"] && Pelican_Db::$values["form_action"] == Pelican_Db::DATABASE_DELETE) {
            Pelican_Db::$values["ZONE_TEMPLATE_ID"] = Pelican_Db::$values["id"];
            Pelican_Db::$values["form_retour"] = $_SESSION[APP]["session_start_page"];
            
            $oConnection->query("delete from #pref#_navigation where ZONE_TEMPLATE_ID=:ID", array(
                ":ID" => Pelican_Db::$values["ZONE_TEMPLATE_ID"]
            ));
            $oConnection->query("delete from #pref#_page_zone_content where ZONE_TEMPLATE_ID=:ID", array(
                ":ID" => Pelican_Db::$values["ZONE_TEMPLATE_ID"]
            ));
            $oConnection->query("delete from #pref#_page_zone_media where ZONE_TEMPLATE_ID=:ID", array(
                ":ID" => Pelican_Db::$values["ZONE_TEMPLATE_ID"]
            ));
            $oConnection->query("delete from #pref#_page_zone where ZONE_TEMPLATE_ID=:ID", array(
                ":ID" => Pelican_Db::$values["ZONE_TEMPLATE_ID"]
            ));
        }
        $oConnection->updateTable(Pelican_Db::$values["form_action"], "#pref#_zone_template");
    }

    protected function _templatePageAreaSave()
    {
        
        
        $oConnection = Pelican_Db::getInstance();
        
        /** cas de la suppression */
        if (Pelican_Db::$values["id"] && Pelican_Db::$values["form_action"] == Pelican_Db::DATABASE_DELETE) {
            Pelican_Db::$values["AREA_ID"] = Pelican_Db::$values["id"];
            Pelican_Db::$values["TEMPLATE_PAGE_ID"] = Pelican_Db::$values["id2"];
            Pelican_Db::$values["form_retour"] = $_SESSION[APP]["session_start_page"];
            
            $oConnection->query("select ZONE_TEMPLATE_ID from #pref#_zone_template where AREA_ID=:ID AND TEMPLATE_PAGE_ID=:ID2", array(
                ":ID" => Pelican_Db::$values["AREA_ID"], 
                ":ID2" => Pelican_Db::$values["TEMPLATE_PAGE_ID"]
            ));
            $zone_template_id = $oConnection->data["ZONE_TEMPLATE_ID"];
            if ($zone_template_id) {
                $oConnection->query("delete from #pref#_navigation where ZONE_TEMPLATE_ID in (" . implode(",", $zone_template_id) . ")");
                $oConnection->query("delete from #pref#_page_zone_content where ZONE_TEMPLATE_ID in (" . implode(",", $zone_template_id) . ")");
                $oConnection->query("delete from #pref#_page_zone_media where ZONE_TEMPLATE_ID in (" . implode(",", $zone_template_id) . ")");
                $oConnection->query("delete from #pref#_page_zone where ZONE_TEMPLATE_ID in (" . implode(",", $zone_template_id) . ")");
                $oConnection->query("delete from #pref#_zone_template where ZONE_TEMPLATE_ID in (" . implode(",", $zone_template_id) . ")");
            }
        }
        
        $oConnection->deleteQuery("#pref#_template_page_area");
        if (Pelican_Db::$values["form_action"] != Pelican_Db::DATABASE_DELETE) {
            if (!Pelican_Db::$values["TEMPLATE_PAGE_AREA_ORDER"]) {
                Pelican_Db::$values["TEMPLATE_PAGE_AREA_ORDER"] = $oConnection->queryItem("select max(TEMPLATE_PAGE_AREA_ORDER) from #pref#_template_page_area where template_page_id=" . Pelican_Db::$values["TEMPLATE_PAGE_ID"]);
                Pelican_Db::$values["TEMPLATE_PAGE_AREA_ORDER"] ++;
            }
            $oConnection->insertQuery("#pref#_template_page_area");
        }
    
    /**
	$aBind[":TEMPLATE_PAGE_ID"] = Pelican_Db::$values["TEMPLATE_PAGE_ID"];
	$sql = "select AREA_ID from #pref#_template_page_area where TEMPLATE_PAGE_ID=:TEMPLATE_PAGE_ID ORDER BY ".$oConnection->getCaseClause("COLONNE", array(1 => 0), 1)." , LIGNE + COLONNE, HAUTEUR + LARGEUR";
	$areas = $oConnection->queryTab($sql, $aBind);
	if ($areas) {
	foreach($areas as $area_id) {
	$aBind[":AREA_ID"] = $area_id["AREA_ID"];
	$aBind[":PAGE_ORDER"]++;
	$sql = "update #pref#_template_page_area set TEMPLATE_PAGE_AREA_ORDER = :PAGE_ORDER
	WHERE TEMPLATE_PAGE_ID=:TEMPLATE_PAGE_ID
	AND AREA_ID=:AREA_ID";
	$areas = $oConnection->query($sql, $aBind);
	}
	}
     */
    }

    protected function _templatePageSave()
    {
        
        
        $oConnection = Pelican_Db::getInstance();
        
        $typePage = getPageTypeCode(Pelican_Db::$values['PAGE_TYPE_ID']);
        
        if ($this->form_action != Pelican_Db::DATABASE_DELETE) {
            /** récupération de l'ordre des zones */
            if (Pelican_Db::$values["AREA"]) {
                $area = explode(",", Pelican_Db::$values["AREA"]);
                if ($area) {
                    foreach ($area as $area_id) {
                        if (Pelican_Db::$values["AREA_" . $area_id]) {
                            $temp = explode("&", str_replace(array(
                                "item[]=", 
                                "UL" . $area_id . "[]="
                            ), "", Pelican_Db::$values["AREA_" . $area_id]));
                            for ($i = 0; $i < count($temp); $i ++) {
                                $j ++;
                                $list[$temp[$i]] = array(
                                    "order" => $j, 
                                    "AREA_ID" => $area_id
                                );
                            }
                        }
                    }
                }
            }
            
            if (Pelican_Db::$values["MOBILE"]) {
                $mobile = explode(",", Pelican_Db::$values["MOBILE"]);
                $mobileorder = 0;
                if ($mobile) {
                    foreach ($mobile as $mobile_id) {
                        if ($mobile_id == "M" && Pelican_Db::$values["MOBILE_" . $mobile_id]) {
                            $temp = explode("&", str_replace(array(
                                "item[]=", 
                                "UL" . $mobile_id . "[]="
                            ), "", Pelican_Db::$values["MOBILE_" . $mobile_id]));
                            for ($i = 0; $i < count($temp); $i ++) {
                                $list[$temp[$i]]['mobile'] = ++$mobileorder;
                            }
                        }
                    }
                }
            }
            /** mise à jour */
            
            if ($list) {
                /** sauvegarde des données de zone */
                $this->aBind[":TEMPLATE_PAGE_ID"] = Pelican_Db::$values["TEMPLATE_PAGE_ID"];
                $sql = "select * from
				#pref#_zone_template
				where template_page_id = :TEMPLATE_PAGE_ID";
                $result = $oConnection->queryTab($sql, $this->aBind);
                
                /** sauvegarde des données de Pelican_Index_Frontoffice_Zone dans navigation */
                /*$this->aBind[":TEMPLATE_PAGE_ID"] = Pelican_Db::$values["TEMPLATE_PAGE_ID"];
                $sql = "select nav.* from
				#pref#_zone_template zt,
				#pref#_navigation nav
				where template_page_id = :TEMPLATE_PAGE_ID
				AND zt.zone_template_id = nav.zone_template_id";
                $resultNav = $oConnection->queryTab($sql, $this->aBind);
                
                /** suppression des données pour ANNULE/REMPLACE dans navigation  */
                /*$sql = "delete from
				#pref#_navigation
				where zone_template_id in (
				select zt.zone_template_id from
				#pref#_zone_template zt
				where template_page_id = :TEMPLATE_PAGE_ID)";
                $oConnection->query($sql, $this->aBind);
                
                //Cas des templates portal, on désactive la vérification de contraintes
                //le temps de faire annuler/remplacer
                //TODO :: fonction générique permettant de désactiver une contrainte (DbFw.php)
                if ($typePage == 'PORTAL') {
                    $oConnection->query("SET foreign_key_checks = 0;");
                }
                
                /** suppression des données pour ANNULE/REMPLACE */
                /*$sql = "delete from
				#pref#_zone_template
				where template_page_id = :TEMPLATE_PAGE_ID";
                //$oConnection->query($sql, $this->aBind);
                
                /** insertion des nouvelles données */
                $DBVALUES_INIT = Pelican_Db::$values;
                if ($result) {
                    foreach ($result as Pelican_Db::$values) {
                        $id = Pelican_Db::$values["ZONE_TEMPLATE_ID"];
                        Pelican_Db::$values["AREA_ID"] = $list[$id]["AREA_ID"];
                        Pelican_Db::$values["ZONE_TEMPLATE_ORDER"] = $list[$id]["order"];
                        Pelican_Db::$values["ZONE_TEMPLATE_MOBILE_ORDER"] = $list[$id]["mobile"];
                        //debug(Pelican_Db::$values);
                        //$oConnection->insertQuery("#pref#_zone_template");
                        $oConnection->updateQuery("#pref#_zone_template");
                    }
                }
                Pelican_Db::$values = $DBVALUES_INIT;
                
                //Cas des templates portal, on réactive la vérification de contraintes
                //après annuler/remplacer
                //TODO :: fonction générique permettant de désactiver une contrainte
                /*if ($typePage == 'PORTAL') {
                    $oConnection->query("SET foreign_key_checks = 1;");
                }
                
                /** réinsertion des données dans  navigation*/
                /*$DBVALUES_INIT = Pelican_Db::$values;
                if ($resultNav) {
                    foreach ($resultNav as Pelican_Db::$values) {
                        $oConnection->insertQuery("#pref#_navigation");
                    }
                }
                Pelican_Db::$values = $DBVALUES_INIT;*/
            
            }
        } else {
            if ($typePage == 'PORTAL') {
                $oConnection->query("delete from #pref#_" . strtolower(Pelican::$config['PORTAL_AUTH_TABLE']) . "_page_zone where ZONE_TEMPLATE_ID IN (SELECT ZONE_TEMPLATE_ID FROM #pref#_zone_template WHERE TEMPLATE_PAGE_ID=" . Pelican_Db::$values["TEMPLATE_PAGE_ID"] . ")");
                $oConnection->query("delete from #pref#_" . strtolower(Pelican::$config['PORTAL_AUTH_TABLE']) . "_zone_template where ZONE_TEMPLATE_ID IN (SELECT ZONE_TEMPLATE_ID FROM #pref#_zone_template WHERE TEMPLATE_PAGE_ID=" . Pelican_Db::$values["TEMPLATE_PAGE_ID"] . ")");
            }
            $oConnection->query("delete from #pref#_page_zone where ZONE_TEMPLATE_ID IN (SELECT ZONE_TEMPLATE_ID FROM #pref#_zone_template WHERE TEMPLATE_PAGE_ID=" . Pelican_Db::$values["TEMPLATE_PAGE_ID"] . ")");
            $oConnection->query("delete from #pref#_page_zone_content where ZONE_TEMPLATE_ID IN (SELECT ZONE_TEMPLATE_ID FROM #pref#_zone_template WHERE TEMPLATE_PAGE_ID=" . Pelican_Db::$values["TEMPLATE_PAGE_ID"] . ")");
            $oConnection->query("delete from #pref#_page_zone_media where ZONE_TEMPLATE_ID IN (SELECT ZONE_TEMPLATE_ID FROM #pref#_zone_template WHERE TEMPLATE_PAGE_ID=" . Pelican_Db::$values["TEMPLATE_PAGE_ID"] . ")");
            $oConnection->query("delete from #pref#_page_zone where ZONE_TEMPLATE_ID IN (SELECT ZONE_TEMPLATE_ID FROM #pref#_zone_template WHERE TEMPLATE_PAGE_ID=" . Pelican_Db::$values["TEMPLATE_PAGE_ID"] . ")");
            $oConnection->query("delete from #pref#_zone_template where TEMPLATE_PAGE_ID=" . Pelican_Db::$values["TEMPLATE_PAGE_ID"]);
            $oConnection->query("delete from #pref#_template_page_area where TEMPLATE_PAGE_ID=" . Pelican_Db::$values["TEMPLATE_PAGE_ID"]);
        }
        $oConnection->updateTable(Pelican_Db::$values["form_action"], "#pref#_template_page");
    }

    protected function _templatePagePortalSave()
    {
        
        
        $oConnection = Pelican_Db::getInstance();
        
        if (!Pelican_Db::$values["ZONE_TEMPLATE_ORDER"]) {
            Pelican_Db::$values["ZONE_TEMPLATE_ORDER"] = $oConnection->queryItem("select max(ZONE_TEMPLATE_ORDER) from #pref#_zone_template where TEMPLATE_PAGE_ID=:TEMPLATE_PAGE_ID", array(
                ":TEMPLATE_PAGE_ID" => Pelican_Db::$values["TEMPLATE_PAGE_ID"]
            )) + 1;
        }
        
        /** cas de la suppression */
        if (Pelican_Db::$values["id"] && Pelican_Db::$values["form_action"] == Pelican_Db::DATABASE_DELETE) {
            Pelican_Db::$values["ZONE_TEMPLATE_ID"] = Pelican_Db::$values["id"];
            Pelican_Db::$values["form_retour"] = $_SESSION[APP]["session_start_page"];
            
            $oConnection->query("delete from #pref#_navigation where ZONE_TEMPLATE_ID=:ID", array(
                ":ID" => Pelican_Db::$values["ZONE_TEMPLATE_ID"]
            ));
            $oConnection->query("delete from #pref#_page_zone_content where ZONE_TEMPLATE_ID=:ID", array(
                ":ID" => Pelican_Db::$values["ZONE_TEMPLATE_ID"]
            ));
            $oConnection->query("delete from #pref#_page_zone_media where ZONE_TEMPLATE_ID=:ID", array(
                ":ID" => Pelican_Db::$values["ZONE_TEMPLATE_ID"]
            ));
            $oConnection->query("delete from #pref#_" . strtolower(Pelican::$config['PORTAL_AUTH_TABLE']) . "_page_zone where ZONE_TEMPLATE_ID=:ID", array(
                ":ID" => Pelican_Db::$values["ZONE_TEMPLATE_ID"]
            ));
            $oConnection->query("delete from #pref#_page_zone where ZONE_TEMPLATE_ID=:ID", array(
                ":ID" => Pelican_Db::$values["ZONE_TEMPLATE_ID"]
            ));
            $oConnection->query("delete from #pref#_" . strtolower(Pelican::$config['PORTAL_AUTH_TABLE']) . "_zone_template where ZONE_TEMPLATE_ID=:ID", array(
                ":ID" => Pelican_Db::$values["ZONE_TEMPLATE_ID"]
            ));
        }
        
        $oConnection->updateTable(Pelican_Db::$values["form_action"], "#pref#_zone_template");
    }

    protected function setListModel()
    {
        $this->listModel = "SELECT #pref#_template_page.TEMPLATE_PAGE_ID, TEMPLATE_PAGE_LABEL, #pref#_template_page.SITE_ID, SITE_LABEL, PAGE_TYPE_LABEL, PAGE_TYPE_HIDE,
				COUNT(distinct PAGE_ID) as NB
				FROM #pref#_template_page
				INNER JOIN #pref#_site ON ( #pref#_template_page.SITE_ID = #pref#_site.SITE_ID )
				INNER JOIN #pref#_page_type ON ( #pref#_template_page.PAGE_TYPE_ID = #pref#_page_type.PAGE_TYPE_ID )
				LEFT JOIN #pref#_page_version ON ( #pref#_template_page.TEMPLATE_PAGE_ID = #pref#_page_version.TEMPLATE_PAGE_ID )
				group by #pref#_template_page.PAGE_TYPE_ID, TEMPLATE_PAGE_LABEL, #pref#_template_page.SITE_ID, #pref#_template_page.TEMPLATE_PAGE_ID, #pref#_template_page.TEMPLATE_PAGE_LABEL, #pref#_site.SITE_LABEL, #pref#_page_type.PAGE_TYPE_LABEL, #pref#_page_type.page_type_hide  
				order by " . $this->listOrder;
	
    }

    protected function setEditModel()
    {
        $this->editModel = "SELECT * from #pref#_template_page WHERE TEMPLATE_PAGE_ID='" . $this->id . "'";
        if ($this->id == "-2") {
            if (!$_GET["site"]) {
                $this->editModel = "";
            } else {
                $this->id = "temp";
            }
        }
    }

    public function listAction()
    {
        parent::listAction();
        
        $_SESSION[APP]["session_start_page_2"] = $_SESSION[APP]["session_start_page"];
        
        $aTemplateTypes = Pelican_Cache::fetch("Backend/Generic", "site");
        $aPageTypes = Pelican_Cache::fetch("Backend/Generic", array(
            "page_type"
        ));
        if ($aPageTypes) {
            foreach ($aPageTypes as $key => $value) {
                if ($value['PAGE_TYPE_UNIQUE']) {
                    $aPageTypes[$key]['PAGE_TYPE_LABEL'] = $value['PAGE_TYPE_LABEL'] . ' (1 par site)';
                }
            }
        }
        
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setFilterField("SITE", "<b>" . t('SITE') . "</b> :", "#pref#_template_page.SITE_ID", $aTemplateTypes);
        $table->setFilterField("PAGE_TYPE", "<b>" . t('TYPE') . "</b> :", "#pref#_page_type.PAGE_TYPE_ID", $aPageTypes);
        $table->setFilterField("search_keyword", "<b>" . t('RECHERCHER') . " :</b>", "TEMPLATE_PAGE_LABEL");
        $table->getFilter(3);
        $table->setCSS(array(
            "tblalt1", 
            "tblalt2"
        ));
        $table->setValues($this->getListModel(), "#pref#_template_page.TEMPLATE_PAGE_ID", "SITE_LABEL");
        $table->addInput(t('FORM_BUTTON_COPY'), "button", array(
            "id" => "TEMPLATE_PAGE_ID", 
            "site" => 'SITE_ID', 
            "" => "dup=true"
        ), "center");
        $table->addColumn("ID", "TEMPLATE_PAGE_ID", "10", "left", "", "tblheader", "#pref#_template_page.TEMPLATE_PAGE_ID");
        $table->addColumn(t('TYPE'), "PAGE_TYPE_LABEL", "25", "left", "", "tblheader", "#pref#_page_type.PAGE_TYPE_LABEL");
        $table->addColumn(t('NAME'), "TEMPLATE_PAGE_LABEL", "50", "left", "", "tblheader", "#pref#_template_page.TEMPLATE_PAGE_LABEL");
        $table->addColumn(t('HIDE'), "PAGE_TYPE_HIDE", "10", "center", "boolean", "tblheader", "#pref#_page_type.PAGE_TYPE_HIDE");
        $table->addColumn(t('NB_USE'), "NB", "10", "center", "", "tblheader", "NB");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array(
            "id" => "TEMPLATE_PAGE_ID", 
            "site" => 'SITE_ID'
        ), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array(
            "id" => "TEMPLATE_PAGE_ID", 
            "site" => 'SITE_ID', 
            "" => "readO=true"
        ), "center", array(
            "NB=0"
        ));
        $this->setResponse($table->getTable());
        
        $_SESSION[APP]["gabarit"] = "";
    }

    public function editAction()
    {
        $oConnection = Pelican_Db::getInstance();
        
        parent::editAction();
        
        $_SESSION[APP]["session_start_page"] = $_SESSION[APP]["session_start_page_2"];
        
        ob_start();
        $oForm = Pelican_Factory::getInstance('Form', true);
        $oForm->setTab("1", t('Gabarit Web'));
        $oForm->setTab("2", t('EDITOR_HTML'));
        $oForm->setTab("3", t('Gabarit Mobile'));
        $oForm->open(Pelican::$config["DB_PATH"]);
		$oForm->beginFormTable();
        $this->beginForm($oForm);
        $aComboPageTypes = $this->getComboPageType($this->values['SITE_ID'], $_GET['id']);
        $oForm->createHidden($this->field_id, $this->id);
        $oForm->createInput("TEMPLATE_PAGE_LABEL", t('FIRST_NAME'), 100, "", true, $this->values["TEMPLATE_PAGE_LABEL"], $this->readO, 50);
        $sqlSite = "SELECT site_id, site_label FROM #pref#_site";
        $ajaxJsCall = Pelican_Factory::staticCall(Pelican::getAjaxEngine(), 'getJsCall');
        $oForm->createComboFromSql($oConnection, 'SITE_ID', t('SITE'), $sqlSite, $this->values['SITE_ID'], true, $this->readO, "1", false, "", true, false, "onchange=\"" . $ajaxJsCall . "('Administration_Template_Page/layouttype',this.value,'" . $this->id . "','" . $this->values["PAGE_TYPE_ID"] . "','PAGE_TYPE_ID');\"");
        $oForm->createComboFromList("PAGE_TYPE_ID", t('Layout type'), $aComboPageTypes, $this->values["PAGE_TYPE_ID"], true, $this->readO);
        $this->endForm($oForm);
        $oForm->endFormTable();
        
        $oForm->drawTab();
        if ($this->form_action != "INS") {
            $detail = true;
            if ($detail) {
                $oForm->beginTab("1");
                $oForm->createFreeHtml('<tr><td>');
                /** gabarit */
               $sql2 = "SELECT distinct tpa.*, AREA_LABEL
						FROM #pref#_template_page_area tpa
						INNER JOIN #pref#_area a on (tpa.AREA_ID=a.AREA_ID)
						WHERE tpa.TEMPLATE_PAGE_ID=" . $this->id . "
						order by tpa.LIGNE, tpa.COLONNE";
                $draw = $oConnection->queryTab($sql2);
                $gabarit = $this->getGabarit($draw);
                $oForm->createFreeHtml(Pelican_Html::br() . Pelican_Html::center($gabarit));
                
                /** affichage de la structure */
                $sql3 = "SELECT distinct tpa.*, a.*, zt.*, z.*
						FROM #pref#_template_page_area tpa
						INNER JOIN #pref#_area a on (tpa.AREA_ID=a.AREA_ID)
						LEFT JOIN #pref#_zone_template zt on (zt.template_page_id=tpa.template_page_id AND zt.AREA_ID=a.AREA_ID)
						LEFT JOIN #pref#_zone z on (zt.ZONE_ID=z.ZONE_ID)
						WHERE tpa.TEMPLATE_PAGE_ID=" . $this->id . "
						order by tpa.TEMPLATE_PAGE_AREA_ORDER, zt.ZONE_TEMPLATE_ORDER";
                $struct = $oConnection->queryTab($sql3);
                $oForm->createFreeHtml('</td></tr>');
                $oForm->endTab();
                $oForm->beginTab("2");
                $oForm->createFreeHtml('<tr><td>');
                
                $i = 0;
                if ($struct) {
                    foreach ($struct as $area) {
                        $i ++;
                        $this->structureInit[$area["TEMPLATE_PAGE_AREA_ORDER"]][0] = Pelican_Html::pre(array(
                            name => "codeB" . $i, 
                            "class" => "html:nogutter:nocontrols"
                        ), Pelican_Text::htmlentities($area["AREA_HEAD"]));
                        $this->structureInit[$area["TEMPLATE_PAGE_AREA_ORDER"]][1] .= ($area["ZONE_TEMPLATE_LABEL"] ? Pelican_Html::div(array(
                            "class" => "schema_bloc"
                        ), Pelican_Html::center($area["ZONE_TEMPLATE_LABEL"]) . "<br />FO : " . $area["ZONE_FO_PATH"] . "<br />BO : " . $area["ZONE_BO_PATH"]) : "");
                        $this->structureInit[$area["TEMPLATE_PAGE_AREA_ORDER"]][2] = Pelican_Html::pre(array(
                            name => "codeE" . $i, 
                            "class" => "html:nogutter:nocontrols"
                        ), Pelican_Text::htmlentities($area["AREA_FOOT"]));
                        $highlight .= "dp.SyntaxHighlighter.HighlightAll('codeB" . $i . "');";
                        $highlight .= "dp.SyntaxHighlighter.HighlightAll('codeE" . $i . "');";
                    }
                    foreach ($this->structureInit as $schema) {
                        $this->structure[] = Pelican_Html::div(array(
                            "class" => "schema_area"
                        ), implode("", $schema));
                    }
                    $code = implode("", $this->structure);
                    $oForm->createFreeHtml((Pelican_Html::br() . Pelican_Html::br() . Pelican_Html::center(Pelican_Html::div(array(
                        "class" => "schema"
                    ), $code))));
                    
                    $this->assign('highlight', $highlight);
                
                }
                
                $_SESSION[APP]["gabarit"] = $gabarit;
                $_SESSION[APP]["session_start_page"] = $_SERVER["REQUEST_URI"];
            }
            
            $oForm->createFreeHtml('</td></tr>');
            $oForm->endTab();
            $oForm->beginTab("3");
            $oForm->createFreeHtml('<tr><td>');
            $detail = true;
            if ($detail) {
                /** gabarit */
                $sql2 = "SELECT distinct tpa.*, AREA_LABEL
						FROM #pref#_template_page_area tpa
						INNER JOIN #pref#_area a on (tpa.AREA_ID=a.AREA_ID)
						WHERE tpa.TEMPLATE_PAGE_ID=" . $this->id . "
						order by tpa.LIGNE, tpa.COLONNE";
                $draw = $oConnection->queryTab($sql2);
                $gabarit = $this->getGabaritMobile($draw);
                $oForm->createFreeHtml(Pelican_Html::br() . Pelican_Html::center($gabarit));
                
                /** affichage de la structure */
                $sql3 = "SELECT distinct tpa.*, a.*, zt.*, z.*
						FROM #pref#_template_page_area tpa
						INNER JOIN #pref#_area a on (tpa.AREA_ID=a.AREA_ID)
						LEFT JOIN #pref#_zone_template zt on (zt.template_page_id=tpa.template_page_id AND zt.AREA_ID=a.AREA_ID)
						LEFT JOIN #pref#_zone z on (zt.ZONE_ID=z.ZONE_ID)
						WHERE tpa.TEMPLATE_PAGE_ID=" . $this->id . "
						order by tpa.TEMPLATE_PAGE_AREA_ORDER, zt.ZONE_TEMPLATE_ORDER";
                $struct = $oConnection->queryTab($sql3);
                
                $_SESSION[APP]["gabarit"] = $gabarit;
                $_SESSION[APP]["session_start_page"] = $_SERVER["REQUEST_URI"];
            }
        } else {
            $oForm->createFreeHtml(Pelican_Html::center("<hr><br>La gestion des zones sera disponible après l'ajout du Template de page"));
        }
        $oForm->createFreeHtml('</td></tr>');
        $oForm->endTab();
        $oForm->close();
		$form = ob_get_contents();
        ob_clean();
		
		// Zend_Form start
		$form = formToString($oForm, $form);
        // Zend_Form stop
        
        $this->assign('content', $form, false);
        $this->assign('id', $this->id);
        $this->assign('request_uri', rawurlencode($_SERVER["REQUEST_URI"]));
        
        $this->getView()->getHead()->setJs("/library/External/scriptaculous-js/prototype.js");
        $this->getView()->getHead()->setJs("/library/External/scriptaculous-js/scriptaculous.js");
        $this->getView()->getHead()->setCss("/library/External/SyntaxHighlighter/css/SyntaxHighlighter.css");
        $this->getView()->getHead()->setJs("/library/External/SyntaxHighlighter/js/shCore.js");
        $this->getView()->getHead()->setJs("/library/External/SyntaxHighlighter/js/shBrushCSharp.js");
        $this->getView()->getHead()->setJs("/library/External/SyntaxHighlighter/js/shBrushXml.js");
        
        $this->replaceTemplate('index', 'edit');
        $this->fetch();
    }

    public function zonetemplateAction()
    {
        $this->aBind[":TEMPLATE_PAGE_ID"] = $_GET["tpid"];
        $this->aBind[":AREA_ID"] = $_GET["aid"];
        $this->aBind[":ZONE_TEMPLATE_ID"] = $this->id;
        $this->editModel = "SELECT * from #pref#_zone_template WHERE ZONE_TEMPLATE_ID=:ZONE_TEMPLATE_ID";
        
        $oConnection = Pelican_Db::getInstance();
        
        parent::editAction($this->aBind);
        ob_start();
        $oForm = Pelican_Factory::getInstance('Form', true);
        $oForm->open(Pelican::$config["DB_PATH"], "post", "fForm", false, true, "CheckForm", "opener");
        beginFormTable();
        $this->beginForm($oForm);
        $oForm->createHidden("ZONE_TEMPLATE_ID", $this->id);
        $oForm->createHidden("TEMPLATE_PAGE_ID", $_GET["tpid"]);
        $oForm->createHidden("ZONE_TEMPLATE_ORDER", $this->values["ZONE_TEMPLATE_ORDER"]);
        $sqlArea = "SELECT area_id, area_label FROM #pref#_area ORDER by area_label";
        $oForm->createComboFromSql($oConnection, "AREA_ID", t('Zone'), $sqlArea, $_GET["aid"], true, true, "1", false, "", true);
        $sqlZone = "SELECT zone_id, zone_label FROM #pref#_zone ORDER BY zone_label";
        $oForm->createComboFromSql($oConnection, "ZONE_ID", t('BLOC'), $sqlZone, $this->values["ZONE_ID"], true, $this->readO, "1", false, "", true);
        $oForm->createInput("ZONE_TEMPLATE_LABEL", t('FORM_LABEL'), 50, "", true, $this->values["ZONE_TEMPLATE_LABEL"], $this->readO, 50);
        if ($this->form_action == "INS") {
            $this->values["ZONE_CACHE_TIME"] = 30;
        }
        $oForm->createInput("ZONE_CACHE_TIME", t('Cache'), 5, "", true, $this->values["ZONE_CACHE_TIME"], $this->readO, 5);
        $this->endForm($oForm);
        endFormTable();
        echo "<p align=\"center\">";
        $oForm->createButton("valider", t('POPUP_BUTTON_SAVE'), "document.fForm.submit();");
        $oForm->createButton("close", t('POPUP_BUTTON_CLOSE'), "self.close();");
        echo "</p>";
        $oForm->close();
        $form = ob_get_contents();
        ob_clean();
        
        $this->assign('content', $form, false);
        $this->replaceTemplate('zonetemplate', 'edit');
        $this->fetch();
    }

    public function templatepageareaAction()
    {
        $this->aBind[":AREA_ID"] = $this->id;
        $this->aBind[":TEMPLATE_PAGE_ID"] = $_GET["tpid"];
        
        $this->editModel = "SELECT * from #pref#_template_page_area WHERE TEMPLATE_PAGE_ID = :TEMPLATE_PAGE_ID AND AREA_ID=:AREA_ID";
        
        $oConnection = Pelican_Db::getInstance();
        
        parent::editAction($this->aBind);
        ob_start();
        $oForm = Pelican_Factory::getInstance('Form', true);
        $oForm->open(Pelican::$config["DB_PATH"], "post", "fForm", false, true, "CheckForm", "opener", true, false);
        beginFormTable();
        $this->beginForm($oForm);
        
        $oForm->createHidden("TEMPLATE_PAGE_ID", $_GET["tpid"]);
        if ($this->form_action != "INS") {
            $this->readOArea = true;
        }
        //$oForm->createCombo($oConnection, "AREA_ID", t('Zone'), "area", "", "", $this->values["AREA_ID"], true, $this->readOArea);
        $sqlArea = "SELECT area_id, area_label FROM #pref#_area where area_id not in (select area_id from #pref#_template_page_area where template_page_id=" . $_GET["tpid"] . ") ORDER by area_label";
        $oForm->createComboFromSql($oConnection, "AREA_ID", t('Zone'), $sqlArea, $this->values["AREA_ID"], true, $this->readOArea, "1", false, "", true);
        $oForm->createInput("LIGNE", t('POPUP_TABLE_LINE'), 3, "number", true, ($this->values["LIGNE"] ? $this->values["LIGNE"] : $_GET["r"]), $this->readO, 3);
        $oForm->createInput("COLONNE", t('COLUMN'), 3, "number", true, ($this->values["COLONNE"] ? $this->values["COLONNE"] : $_GET["c"]), $this->readO, 3);
        $oForm->createInput("LARGEUR", t('POPUP_LABEL_WIDTH'), 3, "number", true, ($this->values["LARGEUR"] ? $this->values["LARGEUR"] : $_GET["w"]), $this->readO, 3);
        $oForm->createInput("HAUTEUR", t('POPUP_LABEL_HEIGHT'), 3, "number", true, ($this->values["HAUTEUR"] ? $this->values["HAUTEUR"] : $_GET["h"]), $this->readO, 3);
        $oForm->createInput("TEMPLATE_PAGE_AREA_ORDER", t('ORDRE'), 3, "number", false, $this->values["TEMPLATE_PAGE_AREA_ORDER"], $this->readO, 3);
        if ($_GET["d"] == 1) {
            $oForm->createCheckBoxFromList("IS_DROPPABLE", t('Drag&Drop'), array(
                "1" => t('FORM_MSG_YES')
            ), $this->values["IS_DROPPABLE"], false, $this->readO, "h");
        }
        
        $this->endForm($oForm);
        endFormTable();
        echo "<p align=\"center\">";
        $oForm->createButton("valider", t('POPUP_BUTTON_SAVE'), "document.fForm.submit();document.location.href=document.location.href");
        $oForm->createButton("close", t('POPUP_BUTTON_CLOSE'), "self.close();");
        echo "</p>";
        $oForm->close();
        $form = ob_get_contents();
        ob_clean();
        
        $this->assign('content', $form, false);
        $this->replaceTemplate('templatepagearea', 'edit');
        $this->fetch();
    }

    /** methodes supplementaires */
    
    /**
     * Affichage de la représentation graphique de gabarit sou forme d'élements à déplacer par Drag&Drop
     *
     * @param mixed $tabAreas Liste des zones
     * @param string $this->form_action Action en cours dans le formulaire
     * @return string
     */
    protected function getGabarit($tabAreas)
    {
        
        $blocs = $this->getZoneDef($tabAreas);
        $UL = $blocs["ul"];
        $INPUT = $blocs["input"];
        $JS = $blocs["js"];
        
        if ($this->values['PAGE_TYPE_ID']) {
            $this->isPortal = (getPageTypeCode($this->values['PAGE_TYPE_ID']) == 'PORTAL' ? 1 : 0);
        }
        
        if ($this->form_action == "UPD") {
            $add = Pelican_Html::input(array(
                type => "button", 
                "class" => "button", 
                value => t('POPUP_LABEL_ADD'), 
                onclick => "openArea(-2, '" . $this->isPortal . "')"
            ));
        }
        
        if ($tabAreas) {
            $oldLigne = 1;
            $nbrCol = 4;
            $thWidth = "150px;";
            $nbrbloc = sizeOf($tabAreas);
            $i = 1;
            $lar = array();
            $haut = array();
            $th[] = Pelican_Html::td(array(), "&nbsp;");
            for ($i = 1; $i <= $nbrCol; $i ++) {
                $th[] = Pelican_Html::td(array(
                    width => $thWidth, 
                    "class" => "gabarit_header"
                ), $i);
            }
            
            $tr[] = Pelican_Html::tr(@implode("\n", $th));
            
            if ($tabAreas) {
                foreach ($tabAreas as $area) {
                    /** Préparation de la ligne */
                    if (!$this->readO) {
                        $buttons = Pelican_Html::img(array(
                            align => "right", 
                            src => "/images/add_menu.gif", 
                            alt => "Ajouter un bloc", 
                            onclick => "openZone(-2," . $area["AREA_ID"] . ")"
                        )) . Pelican_Html::img(array(
                            align => "right", 
                            src => "/images/del_menu.gif", 
                            alt => "Supprimer cette zone", 
                            onclick => "delArea(" . $area["AREA_ID"] . ")"
                        )) . Pelican_Html::img(array(
                            align => "right", 
                            src => "/images/edit_menu.gif", 
                            alt => "Editer cette zone", 
                            onclick => "openArea(" . $area["AREA_ID"] . ",'" . $this->isPortal . "')"
                        ));
                    }
                    $edit = Pelican_Html::span(array(), $buttons . $area["AREA_LABEL"] . " <b>[" . $area["TEMPLATE_PAGE_AREA_ORDER"] . "]</b>");
                    /* jquery overlay, en cours
				$edit .=Pelican_Html::a(array(href=>str_replace("&id=".$this->id,"&aid=".$_GET["AREA_ID"]."&tpid=".$this->id, $_SERVER['REQUEST_URI']),rel=>"#overlay"),test);
				*/
                    $label = $edit . $UL[$area["AREA_ID"]];
                    
                    for ($i = 0; $i < $area["HAUTEUR"]; $i ++) {
                        $control[$area["LIGNE"] + $i][$area["COLONNE"]] ++;
                        $lar[$area["LIGNE"] + $i] += $area["LARGEUR"];
                    }
                    for ($i = 0; $i < $area["LARGEUR"]; $i ++) {
                        $control[$area["LIGNE"]][$area["COLONNE"] + $i] ++;
                        $haut[$area["COLONNE"] + $i] += $area["HAUTEUR"];
                    }
                    if ($nbrRow < $area["LIGNE"]) {
                        $nbrRow = $area["LIGNE"];
                    }
                    $control[$area["LIGNE"]][$area["COLONNE"]] --;
                    
                    $aStandard = array(
                        align => center, 
                        valign => "top", 
                        id => "td" . $i, 
                        "class" => "area bloc", 
                        colspan => $area["LARGEUR"], 
                        rowspan => $area["HAUTEUR"]
                    );
                    
                    $td[$area["LIGNE"]][$area["COLONNE"]] = Pelican_Html::td($aStandard, Pelican_Html::a(array(
                        title => "Editer le bloc"
                    ), $label));
                }
            }
            
            for ($i = 1; $i <= $nbrRow; $i ++) {
                $tdtemp = array();
                $tdtemp[] = Pelican_Html::td(array(
                    style => "width:20px;", 
                    "class" => "gabarit_header"
                ), $i);
                for ($j = 1; $j <= $nbrCol; $j ++) {
                    if (!$control[$i][$j]) {
                        $aStandard = array(
                            align => center, 
                            valign => "top", 
                            "class" => "gabarit_fond", 
                            ondblclick => "openZone(-2,0," . $i . "," . $j . ",1,1,'" . $this->isPortal . "')"
                        );
                        $td[$i][$j] = Pelican_Html::td($aStandard, Pelican_Html::a(array(
                            title => $i . "-" . $j . "-1-1"
                        ), "&nbsp;"));
                    } else {
                        if ($control[$i][$j] > 1) {
                            debug("conflit ligne " . $i . " - colonne " . $j);
                        }
                    }
                    $tdtemp[] = $td[$i][$j];
                }
                $tr[] = Pelican_Html::tr(implode('', $tdtemp));
            }
            $table = Pelican_Html::table(array(
                id => "gabarit", 
                border => "0", 
                cellspacing => "5"
            ), implode('', $tr));
            
            $return = $table;
            
            /*$return = Pelican_Html::div(array(style=>"background-image:url(/images/browser/display1.png);width:805px;height:74px;"),"&nbsp;")
		.Pelican_Html::div(array(style=>"background-image:url(/images/browser/display2.png);width:805px;"),$return)
		.Pelican_Html::div(array(style=>"background-image:url(/images/browser/display3.png);width:805px;height:176px;"),"&nbsp;").Pelican_Html::br();
*/
            $return .= @implode("\n", $INPUT);
            $return .= Pelican_Html::script(@implode("\n", $JS));
        }
        $return = $add . "<br /><br />" . $return;
        return $return;
    }

    /**
     * Affichage de la représentation graphique de gabarit mobile sous forme d'élements à déplacer par Drag&Drop
     *
     * @param mixed $tabAreas Liste des zones
     * @param string $this->form_action Action en cours dans le formulaire
     * @return string
     */
    protected function getGabaritMobile($tabAreas)
    {
        
        $blocs = $this->getZoneDefMobile($tabAreas);
        $UL = $blocs["ul"];
        $INPUT = $blocs["input"];
        $JS = $blocs["js"];
        
        $return = Pelican_Html::div(array(
            "class" => "area bloc", 
            style => "width:130px;min-height:51px;left:45px;;position:absolute;"
        ), t('Available blocs') . $UL["0"]);
        $return .= Pelican_Html::div(array(
            style => "background-image:url(/images/browser/mobile1.png);width:292px;height:51px;"
        ), "&nbsp;") . Pelican_Html::div(array(
            style => "background-image:url(/images/browser/mobile2.png);width:292px;"
        ), Pelican_Html::div(array(
            "class" => "area bloc", 
            style => "position:relative;width:242px;min-height:140px;"
        ), t('Selected blocs') . $UL["M"])) . Pelican_Html::div(array(
            style => "background-image:url(/images/browser/mobile3.png);width:292px;height:247px;"
        ), "&nbsp;");
        $return .= @implode("\n", $INPUT);
        $return .= Pelican_Html::script(@implode("\n", $JS));
        return $return;
    }

    /**
     * Récupération des blocs et des zones du gabarit
     *
     * @return string
     */
    protected function getZoneDef($areas = array())
    {
        
        $oConnection = Pelican_Db::getInstance();
        
        if ($this->values['PAGE_TYPE_ID']) {
            $this->isPortal = (getPageTypeCode($this->values['PAGE_TYPE_ID']) == 'PORTAL' ? 1 : 0);
        }
        
        /** Drag & Drop */
        $this->aBind[":TEMPLATE_PAGE_ID"] = $this->id;
        $sql = "select #pref#_template_page_area.AREA_ID, ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL  from
			#pref#_template_page_area
			inner join #pref#_zone_template on (#pref#_template_page_area.template_page_id=#pref#_zone_template.template_page_id AND #pref#_template_page_area.area_id=#pref#_zone_template.area_id)
			inner join #pref#_area on (#pref#_zone_template.area_id=#pref#_area.area_id)
			left join #pref#_zone on (#pref#_zone_template.zone_id=#pref#_zone.zone_id)
			where #pref#_template_page_area.template_page_id = :TEMPLATE_PAGE_ID
			order by template_page_area_order, zone_template_order";
        $result = $oConnection->queryTab($sql, $this->aBind);
        
        if ($result) {
            foreach ($result as $li) {
                $ul[$li["AREA_ID"]][] = $li;
                $id[] = "UL" . $li["AREA_ID"];
            }
            $id = array_unique($id);
        }
        
        if ($areas) {
            foreach ($areas as $area) {
                $key = $area["AREA_ID"];
                
                $LI = array();
                $param = "UL" . $key . "[]=";
                $order = array();
                if ($ul[$key]) {
                    foreach ($ul[$key] as $li) {
                        if (!$this->readO) {
                            $buttons = Pelican_Html::img(array(
                                src => "/images/edit_menu.gif", 
                                alt => "Editer le bloc", 
                                onclick => "openZone(" . $li["ZONE_TEMPLATE_ID"] . "," . $li["AREA_ID"] . ")", 
                                hspace => 3
                            )) . Pelican_Html::img(array(
                                src => "/images/del_menu.gif", 
                                alt => "Supprimer le bloc", 
                                onclick => "delZone(" . $li["ZONE_TEMPLATE_ID"] . ",'" . $this->isPortal . "')", 
                                hspace => 3
                            ));
                        }
                        $LI[] = Pelican_Html::li(array(
                            id => "item_" . $li["ZONE_TEMPLATE_ID"]
                        ), Pelican_Html::span(array(
                            style => "cursor:pointer;"
                        ), $li["ZONE_TEMPLATE_LABEL"] . $buttons));
                        $order[] = $param . $li["ZONE_TEMPLATE_ID"];
                    }
                }
                $areaClass = "area";
                $UL[$key] = Pelican_Html::ul(array(
                    "class" => $areaClass, 
                    id => "UL" . $area["AREA_ID"]
                ), @implode("\n", $LI));
                $INPUT[$key] = Pelican_Html::input(array(
                    type => hidden, 
                    name => "AREA_" . $key, 
                    id => "AREA_" . $key, 
                    value => @implode("&", $order)
                ));
                $UL_ID[] = "'UL" . $key . "'";
                $REPORT[] = $key;
            }
            
            if (!$this->readO) {
                foreach ($areas as $area) {
                    $key = $area["AREA_ID"];
                    $JS[$key] = "Sortable.create('UL" . $key . "',
			{dropOnEmpty:true,containment:[" . @implode(",", $UL_ID) . "],constraint:false,
			onChange:function(){\$('AREA_" . $key . "').value = Sortable.serialize('UL" . $key . "') }});";
                    $aClass[] = "#UL" . $area["AREA_ID"];
                
                }
            }
            
            /* jqueryui, en cours */
            /*		
Pelican_Index::directJquery('ui.sortable');

$this->getView()->getHead()->setJqueryFunction("\$('ul.area').sortable({
				connectWith: '.area',
			opacity: 0.5,
			forcePlaceholderSize: true,
			placeholder: 'area-highlight',
			items: 'li',
			revert:		true,
			floats:		true,
			update: function(event, ui) {
				var id = '#AREA_' + $(this).attr('id').replace('UL','');
				document.getElementById(id).value = $(this).sortable('serialize');
				alert(serial);
			},
		}).disableSelection();");*/
            
            $INPUT[] = Pelican_Html::input(array(
                type => hidden, 
                name => "AREA", 
                value => @implode(",", $REPORT)
            ));
            
            return array(
                "ul" => $UL, 
                "input" => $INPUT, 
                "js" => $JS
            );
        }
    }

    /**
     * Récupération des blocs et des zones du gabarit
     *
     * @return string
     */
    protected function getZoneDefMobile($areas = array())
    {
        
        $oConnection = Pelican_Db::getInstance();
        
        /** Drag & Drop */
        $this->aBind[":TEMPLATE_PAGE_ID"] = $this->id;
        $sql = "select #pref#_template_page_area.AREA_ID, ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL, ZONE_TEMPLATE_MOBILE_ORDER from
			#pref#_template_page_area
			inner join #pref#_zone_template on (#pref#_template_page_area.template_page_id=#pref#_zone_template.template_page_id AND #pref#_template_page_area.area_id=#pref#_zone_template.area_id)
			inner join #pref#_area on (#pref#_zone_template.area_id=#pref#_area.area_id)
			left join #pref#_zone on (#pref#_zone_template.zone_id=#pref#_zone.zone_id)
			where #pref#_template_page_area.template_page_id = :TEMPLATE_PAGE_ID
			order by zone_template_mobile_order, template_page_area_order, zone_template_order";
        $result = $oConnection->queryTab($sql, $this->aBind);
        
        if ($result) {
            foreach ($result as $li) {
                $key = ((int) $li["ZONE_TEMPLATE_MOBILE_ORDER"] ? "M" : "0");
                $ul[$key][] = $li;
            }
            $id[] = "UL" . $key;
            if (!$ul['M']) {
                $ul['M'] = $ul['0'];
                $ul['0'] = array();
            }
        }
        $keys = array(
            'M', 
            '0'
        );
        if ($ul) {
            foreach ($keys as $key) {
                $area = $ul[$key];
                $LI = array();
                $order = array();
                $param = "UL" . $key . "[]=";
                if ($area) {
                    foreach ($area as $li) {
                        $LI[] = Pelican_Html::li(array(
                            id => "item_" . $li["ZONE_TEMPLATE_ID"]
                        ), Pelican_Html::span(array(), $li["ZONE_TEMPLATE_LABEL"]));
                        $order[] = $param . $li["ZONE_TEMPLATE_ID"];
                    }
                }
                $UL[$key] = Pelican_Html::ul(array(
                    "class" => "area", 
                    id => "UL" . $key
                ), @implode("\n", $LI) . "&nbsp;");
                $UL_ID[$key] = "'UL" . $key . "'";
                $INPUT[$key] = Pelican_Html::input(array(
                    type => hidden, 
                    name => "MOBILE_" . $key, 
                    id => "MOBILE_" . $key, 
                    value => @implode("&", $order)
                ));
                $REPORT[] = $key;
            }
            
            foreach ($keys as $key) {
                $JS[$key] = "Sortable.create('UL" . $key . "',
			{dropOnEmpty:true,containment:['ULM','UL0'],constraint:false,
			onChange:function(){\$('MOBILE_" . $key . "').value = Sortable.serialize('UL" . $key . "') }});";
                $aClass[] = "#UL" . $key;
            }
            
            /* jqueryui, en cours
		$this->getView()->getHead()->setJqueryFunction("$(\"".implode(",",$aClass)."\").sortable({
		connectWith: '.area'
		}).disableSelection();");*/
            $INPUT[] = Pelican_Html::input(array(
                type => hidden, 
                name => "MOBILE", 
                value => @implode(",", $REPORT)
            ));
            return array(
                "ul" => $UL, 
                "input" => $INPUT, 
                "js" => $JS
            );
        }
    }

    protected function getComboPageType($site_id, $template_id)
    {
        $aPageTypes = Pelican_Cache::fetch("Backend/PageType", array(
            $site_id, 
            $template_id
        ));
        if ($aPageTypes) {
            foreach ($aPageTypes as $key => $value) {
                if ($value['PAGE_TYPE_UNIQUE']) {
                    $aPageTypes[$key]['PAGE_TYPE_LABEL'] = $value['PAGE_TYPE_LABEL'] . ' (1 par site)';
                }
                $aComboPageTypes[$aPageTypes[$key]['PAGE_TYPE_ID']] = $aPageTypes[$key]['PAGE_TYPE_LABEL'];
            }
        }
        return $aComboPageTypes;
    }

    public function pagetypeAction()
    {
        
        $site_id = $this->getParam(0);
        $template_id = $this->getParam(1);
        $type_page_id = $this->getParam(2);
        $dest = $this->getParam(3);
        
        $aComboPageTypes = $this->getComboPageType($site_id, $template_id);
        
        if ($aComboPageTypes) {
            $aOpt[] = Pelican_Html::option(array(
                value => ""
            ), t('FORM_SELECT_CHOOSE'));
            foreach ($aComboPageTypes as $id => $value) {
                $aOpt[] = Pelican_Html::option(array(
                    value => $id, 
                    selected => ($id == $type_page_id)
                ), $value);
            }
            $html = implode('', $aOpt);
        }
        
        $this->getRequest()->addResponseCommand('script', array(
            'value' => "innerHTML('" . $dest . "', '" . rawurlencode(utf8_decode($html)) . "')"
        ));
    
    }

}