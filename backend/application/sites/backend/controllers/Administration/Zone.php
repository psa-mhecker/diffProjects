<?php

/**
 * Formulaire de gestion des zones de saisie du Back Office.
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 04/12/2004
 */
include_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Administration/Template.php';

class Administration_Zone_Controller extends Pelican_Controller_Back
{
    protected $administration = true;

    protected $form_name = "zone";

    protected $field_id = "ZONE_ID";

    protected $defaultOrder = "ZONE_LABEL";

    protected $processus = array(
        "#pref#_zone" ,
        "#pref#_zone_description", );

    protected function beforeSave()
    {
        Pelican_Db::$values["ZONE_AJAX"] = (Pelican_Db::$values["ZONE_RADIO"] == 1 ? "1" : "");
        Pelican_Db::$values["ZONE_IFRAME"] = (Pelican_Db::$values["ZONE_RADIO"] == 2 ? "1" : "");
    }

    protected function setListModel()
    {
        $site = "";
        if ($_GET['filter_site']) {
            $aBindTmp[":SITE_ID"] = $_GET['filter_site'];
            $oConnection = Pelican_Db::getInstance();
            $rs = $oConnection->queryTab("select template_page_id from #pref#_template_page where site_id=:SITE_ID", $aBindTmp);
            if (count($rs) > 0) {
                $aTid = array();
                foreach ($rs as $i) {
                    $aTid[] = $i["template_page_id"];
                }
                $lsTid = implode(',', $aTid);
            }
            if ($lsTid != "") {
                $site = " and template_page_id in (".$lsTid.") ";
            } else {
                // si pas de template pour un site, forcément pas de bloc utilisés. Ce test evite d'avoir a modifier le reste du traitement.
                $site = " and 1 = 2 ";
            }
        }

        $this->listModel = "SELECT #pref#_zone.ZONE_ID, ZONE_LABEL, ZONE_TYPE_LABEL, ZONE_CATEGORY_LABEL,
			ZONE_PROGRAM, ZONE_IFRAME, COUNT(DISTINCT ZONE_TEMPLATE_ID) as NB, ZONE_BO_PATH, ZONE_FO_PATH
			FROM #pref#_zone
			left join #pref#_zone_type on (#pref#_zone.ZONE_TYPE_ID=#pref#_zone_type.ZONE_TYPE_ID)
			left join #pref#_zone_category on (#pref#_zone.ZONE_CATEGORY_ID=#pref#_zone_category.ZONE_CATEGORY_ID)
			left join #pref#_zone_template on (#pref#_zone.ZONE_ID=#pref#_zone_template.ZONE_ID)
			WHERE (PLUGIN_ID is null or PLUGIN_ID='') ".$site."
			GROUP BY #pref#_zone.ZONE_ID,
			ZONE_LABEL,
			ZONE_TYPE_LABEL,
			ZONE_CATEGORY_LABEL,
			ZONE_PROGRAM,
			ZONE_IFRAME,
			ZONE_BO_PATH,
			ZONE_FO_PATH
			ORDER BY ZONE_TYPE_LABEL, ".$this->listOrder;
    }

    protected function setEditModel()
    {
        $this->editModel = "SELECT * from #pref#_zone WHERE
			#pref#_zone.ZONE_ID='".$this->id."'";
    }

    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");

        $azonetypes = Pelican_Cache::fetch("Backend/Generic", "zone_type");
        $aZoneCategories = Pelican_Cache::fetch("Backend/Generic", "zone_category");
        $aSite = Pelican_Cache::fetch("Frontend/Site");
        $table->setFilterField("site", "<b>".t('SITE')."</b> :", 'SITE_ID', $aSite, array(), "1", false);
        $table->setFilterField("zone_type", "<b>".t('TYPE')."</b> :", "#pref#_zone.ZONE_TYPE_ID", $azonetypes);
        $table->setFilterField("zone_category", "<b>".t('Categorie')."</b> :", "#pref#_zone.ZONE_CATEGORY_ID", $aZoneCategories);
        $table->setFilterField("nom", "<b>".t('Name or path')."</b> :", array(
            "ZONE_LABEL",
            "ZONE_FO_PATH",
            "ZONE_BO_PATH", ), 2);
        $table->getFilter(2);

        $table->setCSS(array(
            "tblalt1",
            "tblalt2", ));
        $table->setValues($this->getListModel(), "#pref#_zone.ZONE_ID", "ZONE_TYPE_LABEL");

        /* filtrage sur les templates à pb */
        if ($table->aTableValues) {
            foreach ($table->aTableValues as $key => $val) {
                if ($val['ZONE_BO_PATH']) {
                    if (! file_exists(Pelican::$config["CONTROLLERS_ROOT"].'/layout'.$val['ZONE_BO_PATH'])) {
                        $table->aTableValues[$key]['NOT_EXISTS'] = 'BO invalide';

     //$table->aTableValues[$key]['NB'] = 0;
                    }
                }
            }
        }
        $table->addColumn("ID", "ZONE_ID", "10", "left", "", "tblheader", "ZONE_ID");
        $table->addColumn(t('FIRST_NAME'), "ZONE_LABEL", "90", "left", "", "tblheader", "ZONE_LABEL");
        $table->addColumn(t('Categorie'), "ZONE_CATEGORY_LABEL", "60", "left", "", "tblheader", "ZONE_CATEGORY_LABEL");

        $table->addColumn(t('NB_USE'), "NB", "10", "center", "", "tblheader", "NB");
        $sqlSite = "select distinct #pref#_site.SITE_ID as \"id\", SITE_LABEL as \"lib\" from #pref#_page, #pref#_page_version, #pref#_zone_template, #pref#_site where #pref#_page.SITE_ID=#pref#_site.SITE_ID and #pref#_page.PAGE_ID=#pref#_page_version.PAGE_ID and #pref#_page_version.TEMPLATE_PAGE_ID=#pref#_zone_template.TEMPLATE_PAGE_ID ";
        $table->addMulti(t('Site(s)'), "ZONE_ID", "40", "left", "<br>", "tblheader", "", $sqlSite);

        $table->addColumn(t('TEMPLATE'), "NOT_EXISTS", "10", "left", "error", "tblheader", "NOT_EXISTS");

        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array(
            "id" => "ZONE_ID", ), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array(
            "id" => "ZONE_ID",
            "" => "readO=true", ), "center", array(
            "NB=0", ));
        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        $oConnection = Pelican_Db::getInstance();

        parent::editAction();
        $form = $this->startStandardForm();

        if ($this->form_action != Pelican_Db::DATABASE_INSERT) {
            $form .= $this->oForm
                ->createLabel(t('ID'), $this->id);
        }
        //$this->oForm->createCombo($oConnection, "ZONE_TYPE_ID", t('Zone type'), "zone_type", "", "", $this->values["ZONE_TYPE_ID"], true, $this->readO, "1", false, "", true, true);
        $sqlZoneType = "SELECT zone_type_id, zone_type_label FROM #pref#_zone_type";
        $form .= $this->oForm
            ->createComboFromSql($oConnection, "ZONE_TYPE_ID", t('Zone type'), $sqlZoneType, $this->values["ZONE_TYPE_ID"], true, $this->readO, "1", false, "", true);
        $form .= $this->oForm
            ->createCombo($oConnection, "ZONE_CATEGORY_ID", t('Zone category'), "zone_category", "", "", $this->values["ZONE_CATEGORY_ID"], true, $this->readO, "1", false, "", true, true);
        //$sqlZoneCategory="SELECT zone_category_id, zone_category_label FROM #pref#_zone_category";
        //$form .= $this->oForm->createComboFromSql($oConnection, "ZONE_CATEGORY_ID", t('Zone category'),$sqlZoneCategory,$this->values["ZONE_CATEGORY_ID"], true, $this->readO, "1", false, "", true);
        $form .= $this->oForm
            ->createInput("ZONE_LABEL", t('FIRST_NAME'), 100, "", true, $this->values["ZONE_LABEL"], $this->readO, 50);

        $data = ($this->values["ZONE_AJAX"] ? "1" : "").($this->values["ZONE_IFRAME"] ? "2" : "");
        $form .= $this->oForm
            ->createRadioFromList("ZONE_RADIO", "Type de bloc", array(
            "" => t('Direct zone')." ",
            "1" => t('Ajax zone')." ",
            "2" => t('Iframe zone')." ", ), $data, true, $this->readO, "h");

        //$form .= $this->oForm->createInput("ZONE_COMMENT", t('PARAMETER'), 255, "", false, $this->values["ZONE_COMMENT"], $this->readO, 100);
        $form .= $this->oForm
            ->createInput("ZONE_FO_PATH", t('FRONT_PATH'), 255, "", false, $this->values["ZONE_FO_PATH"], $this->readO, 100);
        if ($this->values["ZONE_FO_PATH"]) {
            $skeleton = Administration_Template_Controller::getSkeleton(20, $this->values["ZONE_FO_PATH"]);
            $form .= $this->oForm
                ->createLabel('CodeFo', Pelican_Html::pre(array(
                name => 'masterFo',
                "class" => "php", ), htmlentities($skeleton)));
            $dp[] = 'masterFo';
        }
        $form .= $this->oForm
            ->createInput("ZONE_BO_PATH", t('BACK_PATH'), 255, "", false, $this->values["ZONE_BO_PATH"], $this->readO, 100);
        //$form .= $this->oForm->createInput("ZONE_DB_MULTI", "Transaction multiple", 255, "", false, $this->values["ZONE_DB_MULTI"], $this->readO, 100);
        if ($this->values["ZONE_BO_PATH"]) {
            $skeleton = Administration_Template_Controller::getSkeleton(30, $this->values["ZONE_BO_PATH"]);
            $form .= $this->oForm
                ->createLabel('CodeBo', Pelican_Html::pre(array(
                name => 'masterBo',
                "class" => "php", ), htmlentities($skeleton)));
            $dp[] = 'masterBo';
        }
        $form .= $this->oForm
            ->createHidden("ZONE_CONTENT", $this->values["ZONE_CONTENT"]);

        $form .= $this->stopStandardForm();

        // Zend_Form start
        $form = formToString($this->oForm, $form);
        // Zend_Form stop

        if ($dp) {
            $highlight = '';
            $this->getView()
                ->getHead()
                ->setCss("/library/External/SyntaxHighlighter/css/SyntaxHighlighter.css");
            $this->getView()
                ->getHead()
                ->setJs("/library/External/SyntaxHighlighter/js/shCore.js");
            $this->getView()
                ->getHead()
                ->setJs("/library/External/SyntaxHighlighter/js/shBrushPhp.js");
            $this->getView()
                ->getHead()
                ->setJs("/library/External/SyntaxHighlighter/js/shBrushXml.js");
            $highlight .= "dp.SyntaxHighlighter.ClipboardSwf = '/library/External/SyntaxHighlighter/js/clipboard.swf';";
            foreach ($dp as $code) {
                $highlight .= "dp.SyntaxHighlighter.HighlightAll('".$code."');";
            }
            if ($highlight) {
                $form .= Pelican_Html::script($highlight);
            }
        }

        $this->setResponse($form);
    }
}
