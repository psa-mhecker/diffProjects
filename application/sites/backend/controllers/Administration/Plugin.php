<?php

/**
    * Formulaire de gestion des plugins
    *
    * @package Pelican_BackOffice
    * @subpackage Administration
    * @author Raphaël Carles <rcarles@businessdecision.com>
    * @since 13/07/2007
    */
class Administration_Plugin_Controller extends Pelican_Controller_Back
{

    protected $administration = true;

    protected $form_name = "plugin";

    protected $field_id = "PLUGIN_ID";

    protected $defaultOrder = "AREA_LABEL";

    public function getListModel()
    {
        $list = Pelican_Plugin::getList();

         if ($_GET['filter_search_keyword'] != '') {
            $aNewList = array();
            foreach ($list as $keyList => $valueList) {
                if(stristr($valueList['title'], $_GET['filter_search_keyword']) || stristr($valueList['id'], $_GET['filter_search_keyword']))
                {
                    $aNewList[] = $valueList;
                }
            }
            $list = $aNewList;
            }

        return $list;
    }

    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste", false, false);
        $table->setFilterField("search_keyword", "<b>" . t('RECHERCHER') . " :</b>", "");
        $table->getFilter(1);

        // $table->setFilterField("site", "<b>Site&nbsp;:</b><br />", "#pref#_profile.SITE_ID", "select #pref#_site.SITE_ID id, SITE_LABEL lib FROM #pref#_site ORDER BY SITE_LABEL");
        // $table->getFilter(2);

        $table->setCSS(array(
            "tblalt1",
            "tblalt2"
        ));
        $table->setValues($this->getListModel(), "title", "category");
        $table->addColumn(t('PLUGIN'), "id", "15", "left", "", "tblheader");
        $table->addColumn(t('TITRE'), "title", "15", "left", "", "tblheader");
        $table->addImage(t('MODULE'), Pelican::$config["IMAGE_PATH"] . "/", "plugin_module", "1", "center", "number", "tblheader");
        $table->addImage(t('BLOC'), Pelican::$config["IMAGE_PATH"] . "/", "plugin_bloc", "1", "center", "number", "tblheader");
        $table->addImage(t('PLUGIN_CONTENT'), Pelican::$config["IMAGE_PATH"] . "/", "plugin_content", "1", "center", "number", "tblheader");
        $table->addImage(t('PLUGIN_NAV'), Pelican::$config["IMAGE_PATH"] . "/", "plugin_navigation", "1", "center", "number", "tblheader");
        $table->addColumn(t('VERSION'), "version", "10", "right", "", "tblheader");
        $table->addColumn(t('AUTEUR'), "author", "20", "right", "", "tblheader");
        $table->addColumn(t('DATE'), "date", "10", "right", "", "tblheader");
        $table->addInput(t('ACTIVER'), "button", array(
            "id" => "id",
            "" => "action=1"
        ), "center", "activated=0");
        $table->addInput(t('DESACTIVER'), "button", array(
            "id" => "id",
            "" => "readO=true"
        ), "center", "activated=1");
        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        if ($_GET["action"] === "0" || ! empty($_POST)) {
            $this->_forward('save');
        } else {
            $oConnection = Pelican_Db::getInstance();
            parent::editAction();
            $oPlugin = Pelican_Plugin::newInstance($this->id);

            if ($oPlugin) {
                $plugin = $oPlugin->getInfo($this->id);
                $form = $this->startStandardForm();

                $form .= $this->oForm->createHidden($this->field_id, $this->id);
                $form .= $this->oForm->createHidden("action", $_GET["action"]);
                $form .= $this->oForm->createLabel(t('ID'), $plugin["id"]);
                $form .= $this->oForm->createLabel(t('TITRE'), $plugin["title"]);
                $form .= $this->oForm->createLabel(t('VERSION'), $plugin["version"]);
                $form .= $this->oForm->createLabel(t('DATE'), $plugin["date"]);

                if ($plugin["plugin_navigation"]) {
                    // niveau 1
                    $form .= $this->oForm->showSeparator();
                    $form .= $this->oForm->createLabel("Bloc de Navigation", "");
                    $form .= $this->oForm->createInput("DIRECTORY_LABEL_NAVIGATION", "Onglet à créer", 50, "", false, $plugin["title"], $this->readO, 50);
                }
                if (($plugin["plugin_module"])) {
                    $form .= $this->oForm->showSeparator();
                    $form .= $this->oForm->createLabel("Module de Gestion", "");
                    $strSQL = "select DIRECTORY_ID, DIRECTORY_LABEL from #pref#_directory where template_id = 1";
                    $oConnection->query($strSQL);
                    $aGroup = $oConnection->data;

                    $strSQL = "select d.DIRECTORY_ID, " . $oConnection->getConcatClause(array(
                        "d2.DIRECTORY_LABEL",
                        "'>'",
                        "d.DIRECTORY_LABEL"
                    )) . " LABEL from #pref#_directory d
                        inner join #pref#_directory d2 on (d.DIRECTORY_PARENT_ID=d2.DIRECTORY_ID) where d.directory_parent_id in (" . implode(",", $aGroup["DIRECTORY_ID"]) . ") order by LABEL";

                    $aSelected = "";
                    $form .= $this->oForm->_getValuesFromSql($oConnection, $strSQL, $aData);
                    if ($plugin["plugin_navigation"]) {
                        $aData["courant"] = "- Onglet créé ci-dessus -";
                        $aSelected = "courant";
                    }

                    $form .= $this->oForm->createComboFromList("DIRECTORY_ID_PARENT", "Position dans le backoffice", $aData, $aSelected, true, $this->readO);

                    $form .= $this->oForm->createLabel("Libellé dans l'arborescence", "");
                    // S'il y a des fichiers module_XXXX.php, le libellé de ces modules est créé automatiquement à partir de la chaîne XXX
                    if (is_array($plugin["routes"]['plugin_module'])) {
                        foreach ($plugin["routes"]['plugin_module'] as $title => $plugin_module) {
                            $title = ucfirst(str_replace(strtolower($this->id . '_' . PLUGIN_ROUTE_MODULE) . '_', '', strtolower($title)));
                            $form .= $this->oForm->createInput("DIRECTORY_LABEL_MODULE_" . strtoupper($title), $title, 50, "", true, $plugin["title"] . ' ' . $title, $this->readO, 50);
                        }
                    }
                }
                if ($plugin["plugin_content"]) {

                    $form .= $this->oForm->showSeparator();
                    $form .= $this->oForm->createLabel(t('Content type'), "");
                    // S'il y a des fichiers module_XXXX.php, le libellé de ces modules est créé automatiquement à partir de la chaîne XXX
                    if (is_array($plugin["routes"]['plugin_content'])) {
                        foreach ($plugin["routes"]['plugin_content'] as $title => $plugin_content) {
                            $title = ucfirst(str_replace(strtolower($this->id . '_' . PLUGIN_ROUTE_CONTENT) . '_', '', strtolower($title)));
                            $form .= $this->oForm->createInput("CONTENT_TYPE_LABEL_" . strtoupper($title), $title, 50, "", true, $plugin["title"] . ' ' . $title, $this->readO, 50);
                        }
                    }
                }

                if ($plugin["plugin_navigation"] || $plugin["plugin_module"] || $plugin["plugin_content"]) {

                    $form .= $this->oForm->showSeparator();
                    /*
                     * $sqlData = "select #pref#_site.SITE_ID id, SITE_LABEL lib from #pref#_site order by lib"; $form .= $this->oForm->createAssocFromSql($oConnection, 'SITE_ID', "Sites associés", $sqlData, $sqlSelected, false, true, $this->readO, 8, 200, false);
                     */
                    $comboQuery = "select SITE_ID id, SITE_LABEL lib from #pref#_site order by lib";
                    $searchQuery = "select PROFILE_ID as \"id\", PROFILE_LABEL as \"lib\" from #pref#_profile where SITE_ID=:RECHERCHE: order by PROFILE_LABEL";
                    $form .= $this->oForm->createAssocFromSql($oConnection, "PROFILE_ID", "Profil(s) associés", "", "", false, true, $this->readO, 12, 250, false, array(
                        "site",
                        $comboQuery,
                        $searchQuery
                    ));
                }
                $form .= $this->stopStandardForm();

                $this->setResponse($form);
            }
        }
    }

    public function saveAction()
    {
        $oConnection = Pelican_Db::getInstance();
        $oPlugin = Pelican_Plugin::newInstance(($this->id ? $this->id : $_POST['PLUGIN_ID']));
        if ($oPlugin) {
            $return = $oPlugin->activation(($_POST["action"] ? true : false));
            Pelican_Db::$values = $_POST;
            if ($_POST["action"]) {

                // navigation => création du niveau 1 (onglet) associé
                if (Pelican_Db::$values["DIRECTORY_LABEL_NAVIGATION"]) {
                    Pelican_Db::$values["DIRECTORY_LABEL"] = Pelican_Db::$values["DIRECTORY_LABEL_NAVIGATION"];
                    Pelican_Db::$values["DIRECTORY_ID"] = - 2;
                    Pelican_Db::$values["TEMPLATE_ID"] = $return["navigation"]['']['id'];
                    Pelican_Db::$values["DIRECTORY_PARENT_ID"] = null;
                    $oConnection->updateTable("INS", "#pref#_directory");
                    $directories[] = Pelican_Db::$values["DIRECTORY_ID"];
                }

                // Récupération des clés du tableau
                $strKeys = implode(',', array_keys(Pelican_Db::$values));

                // Module
                if ($return["module"]) {
                    preg_match_all('/DIRECTORY_LABEL_MODULE_([A-Z0-9]*)/', $strKeys, $array_directory_labels);
                    // Boucle sur les directory_label et les enregistre en base
                    foreach ($array_directory_labels[1] as $directory_label) {
                        if (empty(Pelican_Db::$values["DIRECTORY_PARENT_ID"])) {
                            if (Pelican_Db::$values["DIRECTORY_ID_PARENT"] != "courant") {
                                Pelican_Db::$values["DIRECTORY_PARENT_ID"] = Pelican_Db::$values["DIRECTORY_ID_PARENT"];
                            } else {
                                Pelican_Db::$values["DIRECTORY_PARENT_ID"] = Pelican_Db::$values["DIRECTORY_ID"];
                            }
                        }
                        Pelican_Db::$values["DIRECTORY_ID"] = - 2;
                        Pelican_Db::$values["DIRECTORY_LABEL"] = Pelican_Db::$values['DIRECTORY_LABEL_MODULE_' . $directory_label];

                        // Récupération du template_id inséré pour les fonctionnalités
                        Pelican_Db::$values["TEMPLATE_ID"] = $return["module"][$directory_label]['id'];
                        $oConnection->updateTable("INS", "#pref#_directory");
                        $directories[] = Pelican_Db::$values["DIRECTORY_ID"];
                    }
                }

                if ($return['content']) {
                    preg_match_all('/CONTENT_TYPE_LABEL_([A-Z0-9]*)/', $strKeys, $array_content_labels);
                    // Boucle sur les CONTENT_TYPE_label et les enregistre en base
                    foreach ($array_content_labels[1] as $array_content_labels) {
                        Pelican_Db::$values['CONTENT_TYPE_ID'] = - 2;
                        Pelican_Db::$values['CONTENT_TYPE_LABEL'] = Pelican_Db::$values['CONTENT_TYPE_LABEL_' . $array_content_labels];
                        Pelican_Db::$values['TEMPLATE_ID'] = $return['content'][$array_content_labels]['id'];
                        $oConnection->updateTable('INS', '#pref#_content_type');
                        $content_types[] = Pelican_Db::$values["CONTENT_TYPE_ID"];
                    }
                }

                // affectation aux site
                if (Pelican_Db::$values["PROFILE_ID"]) {
                    $oConnection->updateTable("UPD", "#pref#_profile_directory", "PROFILE_ID");
                    $oConnection->query("select SITE_ID from #pref#_profile where profile_id in (" . implode(",", Pelican_Db::$values["PROFILE_ID"]) . ")");
                    Pelican_Db::$values['SITE_ID'] = $oConnection->data['SITE_ID'];
                    if (Pelican_Db::$values['SITE_ID']) {
                        if (is_array($directories)) {
                            foreach ($directories as $dir) {
                                Pelican_Db::$values["DIRECTORY_ID"] = $dir;
                                $oConnection->updateTable("UPD", "#pref#_directory_site", 'SITE_ID');
                            }
                        }
                        if (is_array($content_types)) {
                            foreach ($content_types as $ct) {
                                Pelican_Db::$values["CONTENT_TYPE_ID"] = $ct;
                                $oConnection->updateTable("UPD", "#pref#_content_type_site", 'SITE_ID');
                            }
                        }
                    }
                }
            }
        }
    }
}
