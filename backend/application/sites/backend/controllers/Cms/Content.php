<?php
include_once dirname(__FILE__).'/../Cms.php';
include_once dirname(__FILE__).'/Page.php';
include_once dirname(__FILE__).'/Content/Module.php';

class Cms_Content_Controller extends Cms_Controller
{
    public $object_id;

    public $object_type_id;

    protected $form_name = "content";

    protected $field_id = "CONTENT_ID";

    protected $clearurlId = 'cid';

    protected $cybertag = array(
        "cid" ,
        "contenu",
    );

    protected $versionType = "DRAFT";

    protected $workflowField = "CONTENT";

    protected $defaultOrder = "CONTENT_VERSION_UPD_DATE_ORDER DESC";

    protected $contentTypeId;

    protected $values_content;

    protected $values_media;

    protected $processus = array(
        "#pref#_content" ,
        "#pref#_content_version" ,
        array(
            "#pref#_content_version_media" ,
            "MEDIA_ID",
        ) ,
        array(
            "#pref#_content_version_content" ,
            "CONTENT_CONTENT_ID",
        ),
    );

    protected $decacheBack = array();

    protected $decachePublication = array(
    );

    protected $decacheBackOrchestra = array (
    );

    protected $decachePublicationOrchestra = array (
    );

    protected function setListModel()
    {
        $oConnection = Pelican_Db::getInstance();

        $this->aBind[':LANGUE'] = ($_GET["langue"] ? $_GET["langue"] : $_SESSION[APP]['LANGUE_ID']);

        $this->listModel = "SELECT
			v.CONTENT_ID AS ID,
			v.CONTENT_TYPE_ID,
			v.CONTENT_TITLE_BO AS TITLE_BO,
			v.CONTENT_TITLE AS TITLE,
			tc.CONTENT_TYPE_LABEL,
			".$oConnection->dateSqlToString("v.CONTENT_CREATION_DATE")." as CONTENT_CREATION_DATE,
			CONTENT_CREATION_DATE as CONTENT_CREATION_DATE_ORDER,
			".$oConnection->dateSqlToString("CONTENT_VERSION_UPDATE_DATE")." as CONTENT_VERSION_UPD_DATE,
			CONTENT_VERSION_UPDATE_DATE as CONTENT_VERSION_UPD_DATE_ORDER,
			CONTENT_CREATION_USER,
			TID,
			STATE_LABEL,
			v.CONTENT_VERSION
			FROM
			(".$this->_getSqlView().") v,
			#pref#_content_type tc,
			#pref#_content_type_site cts,
			#pref#_site st
			WHERE
			v.CONTENT_TYPE_ID = tc.CONTENT_TYPE_ID
			AND tc.CONTENT_TYPE_ID = cts.CONTENT_TYPE_ID
			AND v.SITE_ID = cts.SITE_ID
			AND v.SITE_ID = st.SITE_ID
			AND v.LANGUE_ID=:LANGUE";

        /* Un mot est recherché */
        if (! empty($_SESSION[APP]["content_search"]["rechercheTexte"])) {
            $this->listModel .= " AND ";
            $this->aBind[':CONTENT_TITLE'] = $oConnection->strToBind($_SESSION[APP]["content_search"]["rechercheTexte"]);
            $this->listModel .= "(".$oConnection->getSearchClause("v.CONTENT_TITLE_BO", $_SESSION[APP]["content_search"]["rechercheTexte"], 0, ":CONTENT_TITLE", $this->aBind);
            if (is_numeric($_SESSION[APP]["content_search"]["rechercheTexte"])) {
                $this->aBind[':CONTENT_ID'] = $_SESSION[APP]["content_search"]["rechercheTexte"];
                $this->listModel .= " OR v.CONTENT_ID=:CONTENT_ID";
            }
            $this->listModel .= ")";
        }

        if (! empty($_SESSION[APP]["content_search"]["rechercheSite"])) {
            $this->aBind[':SITE_ID'] = $_SESSION[APP]["content_search"]["rechercheSite"];
            $this->listModel .= " AND v.SITE_ID = :SITE_ID";
        }

        /*if( $_SESSION[APP]['PROFIL_LABEL'] != Pelican::$config['PROFILE']['ADMINISTRATEUR'] ){
            if (! empty($_SESSION[APP]["user"]["main"])) {
                if (! empty($_SESSION[APP]["content_search"]["rechercheAuteur"])) {
                    $this->aBind[':CONTENT_CREATION_USER'] = $_SESSION[APP]["content_search"]["rechercheAuteur"];
                    $this->listModel .= " AND v.CONTENT_VERSION_CREATION_USER LIKE '%:CONTENT_CREATION_USER%'";
                }
            } else {
                $this->aBind[':CONTENT_CREATION_USER'] = $oConnection->strToBind($_SESSION[APP]["user"]["id"]);
                $this->listModel .= " AND v.CONTENT_VERSION_CREATION_USER = :CONTENT_CREATION_USER";
            }
        }*/
        if (! empty($_SESSION[APP]["content_search"]["rechercheTypeContenu"])) {
            $this->aBind[':CONTENT_TYPE_ID'] = $oConnection->strToBind($_SESSION[APP]["content_search"]["rechercheTypeContenu"]);
            $this->listModel .= " AND v.CONTENT_TYPE_ID = :CONTENT_TYPE_ID";
        }
        if (! empty($_SESSION[APP]["content_search"]["recherchePage"])) {
            $this->aBind[':PAGE_ID'] = $oConnection->strToBind($_SESSION[APP]["content_search"]["recherchePage"]);
            $this->listModel .= " AND v.PAGE_ID = :PAGE_ID";
        }
        if (! empty($_SESSION[APP]["content_search"]["rechercheDateDebut"])) {
            $this->aBind[':CONTENT_DATE_START'] = $oConnection->dateStringToSql($_SESSION[APP]["content_search"]["rechercheDateDebut"]);
            $this->listModel .= " AND v.CONTENT_CREATION_DATE >= :CONTENT_DATE_START";
        }
        if (! empty($_SESSION[APP]["content_search"]["rechercheDateFin"])) {
            $this->aBind[':CONTENT_DATE_END'] = $oConnection->dateStringToSql($_SESSION[APP]["content_search"]["rechercheDateFin"]);
            $this->listModel .= " AND v.CONTENT_CREATION_DATE <= :CONTENT_DATE_END";
        }
        if (! empty($_SESSION[APP]["content_search"]["rechercheContentCode2"])) {
            $this->aBind[':CONTENT_CODE2'] = $oConnection->strToBInd($_SESSION[APP]["content_search"]["rechercheContentCode2"]);
            $this->listModel .= " AND v.CONTENT_CODE2 = :CONTENT_CODE2";
        }
        if (! empty($_SESSION[APP]["content_search"]["rechercheState"]) && empty($_GET["popup_content"])) {
            $this->aBind[':STATE_ID'] = $_SESSION[APP]["content_search"]["rechercheState"];
            $this->listModel .= " AND v.STATE_ID = :STATE_ID ";
        } elseif (! empty($_GET["popup_content"])) {
            $this->listModel .= " AND CONTENT_CURRENT_VERSION IS NOT NULL";
        }
        $this->listModel .= " and v.STATE_ID != ".Pelican::$config["CORBEILLE_STATE"]." ";

        $this->listModel .= $this->_ContentTypeFilter();
        $this->listModel .= ' ORDER BY '.$this->listOrder;
    }

    protected function setEditModel()
    {
        $oConnection = Pelican_Db::getInstance();

        $this->aBind[":CONTENT_ID"] = $this->id;

        $this->editModel = " SELECT
		c.* ,
		cv.* ,";
        if (Pelican::$config["TABLE_SPECIFIQUE"][$_GET["uid"]]) {
            $this->editModel .= " contentTable.*,";
        }

        $this->editModel .= $oConnection->dateSqlToString("c.CONTENT_CREATION_DATE")." as CONTENT_CREATION_DATE,
		".$oConnection->dateSqlToString("cv.CONTENT_START_DATE ", true)." as CONTENT_START_DATE,
		".$oConnection->dateSqlToString("cv.CONTENT_END_DATE ", true)." as CONTENT_END_DATE,
		".$oConnection->dateSqlToString("cv.CONTENT_VERSION_CREATION_DATE ", true)." as CONTENT_VERSION_CREATION_DATE,
		".$oConnection->dateSqlToString("cv.CONTENT_PUBLICATION_DATE ", true)." as CONTENT_PUBLICATION_DATE,
		".$oConnection->dateSqlToString("cv.CONTENT_DATE ", true)." as CONTENT_DATE,
        ".$oConnection->dateSqlToString("cv.CONTENT_DATE2 ", true)." as CONTENT_DATE2,
		c.CONTENT_CREATION_DATE as CONTENT_CREATION_DATE_ORDER
		FROM
		#pref#_content c,
		#pref#_content_version cv";
        if (Pelican::$config["TABLE_SPECIFIQUE"][$_GET["uid"]]) {
            $this->editModel .= ", ".Pelican::$config["TABLE_SPECIFIQUE"][$_GET["uid"]]." contentTable";
        }
        $this->editModel .= " WHERE
		c.CONTENT_ID = cv.CONTENT_ID
		AND c.CONTENT_DRAFT_VERSION = cv.CONTENT_VERSION
		AND c.LANGUE_ID = cv.LANGUE_ID"; //LANGUE//
        if (Pelican::$config["TABLE_SPECIFIQUE"][$_GET["uid"]]) {
            $this->editModel .= " AND cv.CONTENT_ID = contentTable.CONTENT_ID
			AND cv.CONTENT_VERSION = contentTable.CONTENT_VERSION
			AND cv.LANGUE_ID = contentTable.LANGUE_ID";
        }
        $this->editModel .= " AND cv.LANGUE_ID = ".($_GET["langue"] ? $_GET["langue"] : $_SESSION[APP]['LANGUE_ID'])."
		AND c.CONTENT_ID = :CONTENT_ID";
    }

    public function listAction()
    {
        $this->aBind[":CONTENT_ID"] = $this->id;

        /* On masque le bouton ajout, on affiche à la place la combo des types de contenu */
        $this->sAddUrl = false;

        /* Include du formulaire de la liste des type de contenu (pas dans le cas du tableau de bord) */
        $combo = '';
        if (! $_GET["sid"] && ! $this->bPopup) {
            $combo = $this->_getContentCombo().Pelican_Html::br().Pelican_Html::br();
        }

        parent::listAction();

        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste", "", true, true);
        if ($_GET["sid"]) {
            $aTypeContenus = Pelican_Cache::fetch("Backend/ContentType", array(
                $_SESSION[APP]['SITE_ID'],
                "",
                implode(",", $_SESSION[APP]["content_type"]["id"]),
            ));
            $table->setFilterField("rechercheTypeContenu", "<b>".t('Content type')."&nbsp;:</b><br />", "", $aTypeContenus, "", 1, false);
            $aPages = Pelican_Cache::fetch("Backend/Page", array(
                $_SESSION[APP]['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
            ));
            if ($aPages) {
                foreach ($aPages as $key => $value) {
                    $aPages[$key][0] = $value["id"];
                    $aPages[$key][1] = $value["lib"];
                }
            }
            $table->setFilterField("recherchePage", "<b>".t('RUBRIQUE')."&nbsp;:</b><br />", "", $aPages, "", 1, false);
            $table->setFilterField("rechercheAuteur", "<b>".t('Author')."&nbsp;:</b><br />", "", "", "", 1, false);
            $table->getFilter(3);
        }
        /* dans le cas de la popup on limite à 6 pages à la fois */
        $table->setCSS(array(
            "tblalt1",
            "tblalt2",
        ));
        // $table->setValues($this->getListModel(), "v.CONTENT_ID", "CONTENT_TYPE_LABEL", $this->aBind);
        $table->setValues($this->getListModel(), "v.CONTENT_ID", "", $this->aBind);
        if (! $_GET["popup_content"]) {
            $table->addColumn(t('Upd.'), "CONTENT_VERSION_UPD_DATE", "10", "center", "", "tblheader", "CONTENT_VERSION_UPD_DATE_ORDER");
        }
        //$table->addColumn(t('ID'), "ID", "20", "center", "", "tblheader", "v.CONTENT_ID", false, '', 0, 1, 1, 'javascript:void(popupSimpleNoScroll("'.Pelican::$config["PAGE_INDEX_IFRAME_PATH"].'?tid=75&cid=[fieldValue]", "popupContentUrl", 400, 160))');
        $table->addColumn(t('ID'), "ID", "20", "center", "", "tblheader", "v.CONTENT_ID", false, '', 0, 1, 1);
        $table->addColumn(t('TITRE'), "TITLE_BO", "70", "left", "", "tblheader", "CONTENT_TITLE_BO");
        //$table->addColumn("Type contenu", "CONTENT_TYPE_LABEL", "20", "left", "", "tblheader", "v.CONTENT_TYPE_ID");
        if ($_GET["popup_content"]) {
            $table->navMaxLinks = 6;
            $table->addInput(t('CHOISIR'), "button", array(
                "id" => "ID",
                "title" => "TITLE",
                "_javascript_" => "top.select",
            ), "center");
            $table->addInput(t('FORM_BUTTON_IMG_TITLE'), "button", array(
                "id" => "ID",
                "uid" => "CONTENT_TYPE_ID",
                "" => "readO=true",
            ), "center");

     //   $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "ID", "uid" => "CONTENT_TYPE_ID"), "center");
        } else {
            $table->addColumn(t('TYPE'), "CONTENT_TYPE_LABEL", "10", "center", "", "tblheader", "CONTENT_TYPE_LABEL");
            $table->addColumn(t('ETAT'), "STATE_LABEL", "10", "center", "", "tblheader", "STATE_LABEL");
            // $table->addColumn(t('DATE'), "CONTENT_CREATION_DATE", "10", "center", "", "tblheader", "CONTENT_CREATION_DATE_ORDER");
            //$table->addColumn(t('AUTEUR'), "CONTENT_CREATION_USER2", "10", "center", "", "tblheader", "CONTENT_CREATION_USER");
            $table->addColumn(t('vers'), "CONTENT_VERSION", "5", "center", "", "tblheader");
            $table->addInput(t('FORM_BUTTON_EDIT'), "button", array(
                "id" => "ID",
                "tid" => "TID",
                "uid" => "CONTENT_TYPE_ID",
            ), "center");
            /*if (! $_GET["sid"]) {
                $table->addInput(t('POPUP_LABEL_DEL'), "button", array(
                    "id" => "ID" ,
                    "tid" => "TID" ,
                    "uid" => "CONTENT_TYPE_ID" ,
                    "" => "readO=true"
                ), "center");
            }*/
        }
        $this->assign('list', $combo.$table->getTable(), false);
        $this->fetch();
    }

    public function editAction()
    {
        $oConnection = Pelican_Db::getInstance();

        /* Edition */
        $this->contentTypeId = $_GET["uid"];
        $sqlTemplate = " SELECT
        TEMPLATE_PATH,
        CONTENT_TYPE_LABEL,
        PLUGIN_ID
        FROM  #pref#_template, #pref#_content_type
        WHERE  #pref#_template.TEMPLATE_ID  = #pref#_content_type.TEMPLATE_ID
        AND  #pref#_content_type.CONTENT_TYPE_ID = ".$this->contentTypeId;

        $template = $oConnection->queryRow($sqlTemplate);
        $module_template = $template["TEMPLATE_PATH"];

        parent::editAction();

        /* Valeurs du formulaire
         * $this->values a été initalisé à partir de $strSqlForm (table CONTENT et CONTENT_VERSION)
         * => création des valeurs associées aux contenus (CONTENT_CONTENT_VERSION) et Pelican_Media (MEDIA_CONTENT)
         */
        if (!empty($this->values) && !empty($this->values["CONTENT_ID"])) {
            $aBind[":CONTENT_ID"] = $this->values["CONTENT_ID"];
            $aBind[":CONTENT_VERSION"] = $this->values["CONTENT_VERSION"];
            $aBind[":LANGUE_ID"] = $this->values['LANGUE_ID'];
            /* contenus associés */
            $sSQl = "select *
			FROM
			#pref#_content_version_content pccv
			INNER JOIN #pref#_content pc ON (pc.CONTENT_ID=pccv.CONTENT_CONTENT_ID AND pc.LANGUE_ID=pccv.LANGUE_ID)
			INNER JOIN #pref#_content_version pcv ON (pc.CONTENT_ID=pcv.CONTENT_ID AND pc.CONTENT_DRAFT_VERSION=pcv.CONTENT_VERSION AND pc.LANGUE_ID=pcv.LANGUE_ID)
			WHERE
			pccv.CONTENT_ID=:CONTENT_ID
			AND pccv.CONTENT_VERSION=:CONTENT_VERSION
			AND pccv.LANGUE_ID=:LANGUE_ID
			ORDER BY CONTENT_CONTENT_TYPE";
            $this->values_content = $oConnection->queryTab($sSQl, $aBind);

            $sSQl = " select *
			FROM #pref#_content_version_media
			WHERE CONTENT_ID = :CONTENT_ID
			AND CONTENT_VERSION =:CONTENT_VERSION
			AND LANGUE_ID =:LANGUE_ID";
            $this->values_media = $oConnection->queryTab($sSQl, $aBind);
        }

        $this->oForm = Pelican_Factory::getInstance('Form', true);

        $form = $this->oForm->setTab("1", t('Content'));

        // Si ce n'est pas un contenu de type FORM
        if ($this->contentTypeId !== "7") {
            $form .= $this->oForm->setTab("3", t('PUBLICATION'));
            $form .= $this->oForm->setTab("4", t('SEO'));
        }

        $form .= $this->beginForm($this->oForm);
        
        $this->oForm->bDirectOutput = false;   
        $form .= $this->oForm->open(Pelican::$config["DB_PATH"]);
        
        $form .= $this->oForm->createHidden($this->field_id, $this->id);

        $form .= $this->oForm->beginFormTable();
        include_once dirname(__FILE__).'/Content/Common/Common.php';

        $form .= Cms_Content_Common_Common::render($this);
        $form .= $this->oForm->endFormTable();
        $form .= ("<br />");

        $form .= $this->oForm->beginTab("1");
        //include_once($root . $module_template);
        $root =  Pelican::$config['APPLICATION_CONTROLLERS'];
        $module = $root.'/'.str_replace("_", "/", $module_template).".php";
        $moduleClass = $module_template;
        if (file_exists($module)) {
            include_once $module;
            $form .= call_user_func_array(array(
                $moduleClass,
                'render',
            ), array(
                $this,
            ));
            $form .= $this->oForm->createHidden('module_name', $moduleClass);
        }

        $form .= $this->oForm->beginTab("3");
        include_once dirname(__FILE__).'/Content/Common/Publication.php';
        $form .= Cms_Content_Common_Publication::render($this);
        $form .= $this->oForm->beginTab("4");
        include_once dirname(__FILE__).'/Content/Common/Param.php';
        $form .= Cms_Content_Common_Param::render($this);
        $form .= $this->endForm($this->oForm);


        $form .= $this->oForm->endTab();

        $form .= $this->oForm->close();

        //check if used content hide "A supprimer" button
        if (!empty($this->values["CONTENT_ID"])) {
            $usedContent = self::checkUsedContent($this->values["CONTENT_ID"]);
        }

        if ($usedContent)
        {
            $this->aButton["state_5"] = "";
            Backoffice_Button_Helper::init($this->aButton);
        }

        if ($this->bPopup) {
            /*
             * * dans le cas de la popup
             */
            $form .= Pelican_Html::script(array(
                type => "text/javascript",
            ), "
				top.sHistory = escape(document.fForm.form_retour.value);
				top.id = ".($this->id ? $this->id : "''").";
				top.refresh();
				top.sTitle = '".rawurlencode(htmlentities($this->aValues["CONTENT_TITLE_BO"]))."';");
        }

        $this->assign('versionForm', $this->getVersioningForm(), false);
        $this->assign('form', $form, false);

        if ($_GET["action"] == 'duplicate') {
            $this->values["CONTENT_ID"] = Pelican::$config["DATABASE_INSERT_ID"];
            $this->values["CONTENT_TITLE_BO"] .= ' - Copie';
            Pelican_Db::$values = $this->values;
            Pelican_Db::$values['form_retour'] = $this->form_retour;
            Pelican_Db::$values['form_action'] = Pelican_Db::DATABASE_INSERT;
            Pelican_Db::$values["CONTENT_CREATION_DATE"] = date('d/m/Y');
            Pelican_Db::$values["CONTENT_VERSION_UPDATE_DATE"] = date('d/m/Y');
            Pelican_Db::$values["CONTENT_VERSION_CREATION_DATE"] = date('d/m/Y');
            Pelican_Db::$values["CONTENT_VERSION"] = 1;
            Pelican_Db::$values["CONTENT_CURRENT_VERSION"] = 1;
            Pelican_Db::$values["CONTENT_DRAFT_VERSION"] = 1;
            $this->_forward('save');
        } else {
            $this->fetch();
        }
    }

    /**
     * Fichier de traitement de l'édition de contenu.
     *
     * @author Raphaël Carles <rcarles@businessdecision.com>
     *
     * @since 31/05/2004
     */

    /**
     * Règles.
     *
     * 1 Si un module mod_db_nomduformulaire existe il est exécuté
     * sinon le fichier de transaction standard est exécuté : db_mod_standard.php
     *
     * 2 si un processus est défini (dans db_sequences associés au nom du formulaire), il est utilisé
     * sinon c'est celui associé à "content" qui est utilisé
     *
     * 3 les versions en excès sont supprimées en jouant la séquence à l'envers
     */
    public function saveAction()
    {
        if ($_GET['action'] == 'duplicate') {
            $this->form_action = Pelican_Db::DATABASE_INSERT;
            $this->form_retour = Pelican_Db::$values['form_retour'];
        }

        $oConnection = Pelican_Db::getInstance();
        // Cas particulier : preview sur un contenu jamais enregistré auparavant
        $bMustHandlePreview = false;
        if (Pelican_Db::$values['form_preview'] == '1' && Pelican_Db::$values['CONTENT_ID'] == Pelican::$config["DATABASE_INSERT_ID"]) {
            $bMustHandlePreview = true;
        }

        if (Pelican_Db::$values["CONTENT_PUBLICATION_DATE"]) {
            Pelican_Db::$values["CONTENT_PUBLICATION_DATE"] = Pelican_Db::$values["CONTENT_PUBLICATION_DATE"]." ".Pelican_Db::$values["CONTENT_PUBLICATION_DATE_HEURE"].":00";
        }

        if (Pelican_Db::$values["CONTENT_START_DATE"]) {
            Pelican_Db::$values["CONTENT_START_DATE"] = Pelican_Db::$values["CONTENT_START_DATE"]." ".Pelican_Db::$values["CONTENT_START_DATE_HEURE"].":00";
        }

        if (Pelican_Db::$values["CONTENT_END_DATE"]) {
            Pelican_Db::$values["CONTENT_END_DATE"] = Pelican_Db::$values["CONTENT_END_DATE"]." ".Pelican_Db::$values["CONTENT_END_DATE_HEURE"].":00";
        }

        if (! Pelican_Db::$values["CONTENT_PUBLICATION_DATE"] && Pelican_Db::$values["CONTENT_START_DATE"]) {
            Pelican_Db::$values["CONTENT_PUBLICATION_DATE"] = Pelican_Db::$values["CONTENT_START_DATE"]." ".Pelican_Db::$values["CONTENT_START_DATE_HEURE"].":00";
        }

        if (! Pelican_Db::$values["CONTENT_PUBLICATION_DATE"]) {
            Pelican_Db::$values["CONTENT_PUBLICATION_DATE"] = date(t('DATE_FORMAT_PHP'));
        }

        if (! Pelican_Db::$values["CONTENT_DATE"] && Pelican_Db::$values["CONTENT_PUBLICATION_DATE"]) {
            Pelican_Db::$values["CONTENT_DATE"] = Pelican_Db::$values["CONTENT_PUBLICATION_DATE"]." ".Pelican_Db::$values["CONTENT_PUBLICATION_DATE_HEURE"].":00";
        }

        /* dates d'affichage par défaut */
        if (! Pelican_Db::$values["CONTENT_START_DATE"]) {
            Pelican_Db::$values["CONTENT_START_DATE"] = Pelican::$config["START_DATE_EMPTY"];
        }
        if (! Pelican_Db::$values["CONTENT_END_DATE"]) {
            Pelican_Db::$values["CONTENT_END_DATE"] = Pelican::$config["END_DATE_EMPTY"];
        }

        if (in_array(Pelican_Db::$values["CONTENT_TYPE_ID"], array(Pelican::$config['CONTENT_TYPE_ID']['ACTUALITE']))) {
            Pelican_Db::$values["CONTENT_TITLE_BO"] = (Pelican_Db::$values["CONTENT_SHORTTEXT"] ? Pelican_Db::$values["CONTENT_SHORTTEXT"] : Pelican_Db::$values["CONTENT_TITLE"]);
        } else {
            Pelican_Db::$values["CONTENT_TITLE_BO"] = (Pelican_Db::$values["CONTENT_TITLE_BO"] ? Pelican_Db::$values["CONTENT_TITLE_BO"] : Pelican_Db::$values["CONTENT_TITLE"]);
        }

        if (Pelican_Db::$values["PAGE_ID"] && ! Pelican_Db::$values["PAGE_PARENT_ID"]) {
            $sSQL = "select PAGE_PARENT_ID from #pref#_page where PAGE_ID=:PAGE_ID AND LANGUE_ID=:LANGUE_ID";
            $aBind[":PAGE_ID"] = Pelican_Db::$values["PAGE_ID"];
            $aBind[":LANGUE_ID"] = Pelican_Db::$values['LANGUE_ID'];
            Pelican_Db::$values["PAGE_PARENT_ID"] = ($oConnection->queryItem($sSQL, $aBind) == 1 ? 0 : $oConnection->queryItem($sSQL, $aBind));
        }

        if (Pelican_Db::$values["OLD_PAGE_ID"] && ! Pelican_Db::$values["OLD_PAGE_PARENT_ID"]) {
            $sSQL = "select PAGE_PARENT_ID from #pref#_page where PAGE_ID=:PAGE_ID AND LANGUE_ID=:LANGUE_ID";
            $aBind[":PAGE_ID"] = Pelican_Db::$values["OLD_PAGE_ID"];
            $aBind[":LANGUE_ID"] = Pelican_Db::$values['LANGUE_ID'];
            Pelican_Db::$values["OLD_PAGE_PARENT_ID"] = ($oConnection->queryItem($sSQL, $aBind) == 1 ? 0 : $oConnection->queryItem($sSQL, $aBind));
        }

        if (Pelican_Db::$values["CONTENT_SHORTTEXT"] && (! Pelican_Db::$values["CONTENT_META_DESC"] || Pelican_Db::$values["CONTENT_META_DESC"] == "")) {
            $cleanStr = Pelican_Db::$values["CONTENT_SHORTTEXT"];
            $cleanStr = nl2br($cleanStr);
            $cleanStr = str_replace("<br />", " ", $cleanStr);
            $cleanStr = str_replace("</", " </", $cleanStr);
            $cleanStr = strip_tags($cleanStr);
            $cleanStr = str_replace("  ", " ", $cleanStr);
            Pelican_Db::$values["CONTENT_META_DESC"] = $cleanStr;
        }

        if (Pelican_Db::$values["CONTENT_TITLE"] && (! Pelican_Db::$values["CONTENT_META_TITLE"] || Pelican_Db::$values["CONTENT_META_TITLE"] == "")) {
            Pelican_Db::$values["CONTENT_META_TITLE"] = Pelican_Db::$values["CONTENT_TITLE"];
        }

        Pelican_Db::$values["CONTENT_DIRECT_HOME"] = 0;
        Pelican_Db::$values["CONTENT_DIRECT_PAGE"] = 0;
        if (Pelican_Db::$values["CONTENT_DIRECT"]) {
            if (! is_array(Pelican_Db::$values["CONTENT_DIRECT"])) {
                Pelican_Db::$values["CONTENT_DIRECT"] = array(
                    Pelican_Db::$values["CONTENT_DIRECT"],
                );
            }
            foreach (Pelican_Db::$values["CONTENT_DIRECT"] as $value) {
                switch ($value) {
                    case 1:
                        {
                            Pelican_Db::$values["CONTENT_DIRECT_PAGE"] = 1;
                            break;
                        }
                    case 10:
                        {
                            Pelican_Db::$values["CONTENT_DIRECT_HOME"] = 1;
                            break;
                        }
                }
            }
        }

        /*
         * * exécution des db associés au module
         */
        $root = ($zone["PLUGIN_ID"] ? Pelican::$config["PLUGIN_ROOT"] : Pelican::$config['APPLICATION_CONTROLLERS']);
        $module = $root.'/'.str_replace("_", "/", Pelican_Db::$values["module_name"]).".php";
        $moduleClass = Pelican_Db::$values["module_name"];
        /* cumul du decache */
        if (file_exists($module)) {
            include_once $module;

            /* gestion des decaches */
            $property = new ReflectionProperty($moduleClass, 'decacheBack');
            $moduleDecacheBack = $property->getValue();
            $property = new ReflectionProperty($moduleClass, 'decachePublication');
            $moduleDecachePublication = $property->getValue();
            $property = new ReflectionProperty($moduleClass, 'decacheBackOrchestra');
            $moduleDecacheBackOrchestra = $property->getValue();
            $property = new ReflectionProperty($moduleClass, 'decachePublicationOrchestra');
            $moduleDecachePublicationOrchestra = $property->getValue();


            if (is_array($moduleDecacheBack)) {
                $this->listDecache = array_merge($this->listDecache, $moduleDecacheBack);
            }
            if (is_array($moduleDecacheBackOrchestra)) {
                $this->listDecacheOrchestra = array_merge($this->listDecacheOrchestra, $moduleDecacheBackOrchestra);
            }
            if (Pelican_Db::$values["PUBLICATION"]) {
                if (is_array($moduleDecachePublication)) {
                    $this->listDecache = array_merge($this->listDecache, $moduleDecachePublication);
                }
            }
            if (Pelican_Db::$values["PUBLICATION"]) {
                if (is_array($moduleDecachePublicationOrchestra)) {
                    $this->listDecacheOrchestra = array_merge($this->listDecacheOrchestra, $moduleDecachePublicationOrchestra);
                }
            }

            /* save */
            if ($this->form_action == Pelican_Db::DATABASE_DELETE) {
                call_user_func_array(array(
                    $moduleClass,
                    'save',
                ), array(
                    $this,
                ));
            }
            call_user_func_array(array(
                $moduleClass,
                'addCache',
            ), array(
                $this,
            ));
        }

        if (file_exists($module)) {
            if ($this->form_action != Pelican_Db::DATABASE_DELETE) {
                call_user_func_array(array(
                    $moduleClass,
                    'beforeSave',
                ), array(
                    $this,
                ));
            }
        }
        $this->_save();

        if (file_exists($module)) {
            if ($this->form_action != Pelican_Db::DATABASE_DELETE) {
                call_user_func_array(array(
                    $moduleClass,
                    'save',
                ), array(
                    $this,
                ));
            }
        }
        //        $DBVALUES_INIT["CONTENT_ID"] = Pelican_Db::$values["CONTENT_ID"];
        /*
         * * cas d'une suppression de version
         */
        $DBVALUES_INIT = Pelican_Db::$values;
        if ($this->workflowFieldDeleteVersion) {
            foreach ($this->workflowFieldDeleteVersion as $delete_version) {
                // => initialisation de la version à supprimer (on en a fait la sauvegarde juste avant)
                Pelican_Db::$values = $DBVALUES_INIT;
                Pelican_Db::$values[$WORKFLOW_FIELD."_VERSION"] = $delete_version;
                $this->form_action = Pelican_Db::DATABASE_DELETE;
                $this->_save();
                Pelican_Db::$values = $DBVALUES_INIT;
                $this->form_action = Pelican_Db::$values['form_action'];
            }
        }
        Pelican_Db::$values = $DBVALUES_INIT;

        if (Pelican_Db::$values['LANGUE_ID']) {
            Pelican_Db::$values['form_retour'] .= "&langue=".Pelican_Db::$values['LANGUE_ID'];
        }

        // Cas de la preview sur un contenu nouvellement créé - maintenant qu'on connait le CONTENT_ID, on le remplace dans form_retour
        if ($bMustHandlePreview) {
            Pelican_Db::$values['form_retour'] = str_replace('&cid=' + Pelican::$config["DATABASE_INSERT_ID"], '&cid=' + Pelican_Db::$values['CONTENT_ID'], Pelican_Db::$values['form_retour']);
        }
        if ($_GET['action'] == 'duplicate') {
            echo Pelican_Html::script("location.href='".Pelican::$config["INDEX_PATH"].Pelican_Db::$values['form_retour']."'");
        }
    }

    protected function init()
    {
        if ($this->getRequest()->isGet()) {
            $oConnection = Pelican_Db::getInstance();

            $_SESSION[APP]["uid"] = array();
            //le uid est stocké dans la variable de session pour pouvroir le voir dans le Pelican_Form multi !!!
            if (isset($_GET["uid"])) {
                $_SESSION[APP]["uid"] = $_GET["uid"];
            }
            if ($this->id && ! $_GET["uid"]) {
                $_GET["uid"] = $oConnection->queryItem("select content_type_id from #pref#_content where content_id=".((int) $this->id));
            }

            /* Initialisation de la recherche */
            if (! isset($_GET["navPage"]) || $_GET["sid"]) {
                if (isset($_GET["rechercheTexte"])) {
                    $_SESSION[APP]["content_search"]["rechercheTexte"] = $_GET["rechercheTexte"];
                } else {
                    unset($_SESSION[APP]["content_search"]["rechercheTexte"]);
                }
                if (isset($_GET["rechercheAuteur"])) {
                    $_SESSION[APP]["content_search"]["rechercheAuteur"] = $_GET["rechercheAuteur"];
                }
                if (isset($_GET["filter_rechercheTypeContenu"])) { // PLA20130124 : pour ne pas que les contenus soient filtrés avec le type du précédent filtre
                    $_GET["rechercheContentType"] = $_GET["filter_rechercheTypeContenu"];
                }
                if (isset($_GET["rechercheContentType"])) {
                    $_SESSION[APP]["content_search"]["rechercheTypeContenu"] = $_GET["rechercheContentType"];
                }
                if (isset($_GET["recherchePage"])) {
                    $_SESSION[APP]["content_search"]["recherchePage"] = $_GET["recherchePage"];
                }
                if (isset($_GET["rechercheDateDebut"])) {
                    $_SESSION[APP]["content_search"]["rechercheDateDebut"] = $_GET["rechercheDateDebut"];
                }
                if (isset($_GET["rechercheDateFin"])) {
                    $_SESSION[APP]["content_search"]["rechercheDateFin"] = $_GET["rechercheDateFin"];
                }
                if (isset($_GET["rechercheState"])) {
                    $_SESSION[APP]["content_search"]["rechercheState"] = $_GET["rechercheState"];
                }
                if (isset($_GET['rechercheContentCode2'])) {
                    $_SESSION[APP]["content_search"]["rechercheContentCode2"] = $_GET["rechercheContentCode2"];
                } else {
                    unset($_SESSION[APP]["content_search"]["rechercheContentCode2"]);
                }
            }
            if (isset($_GET["rechercheSite"])) {
                $_SESSION[APP]["content_search"]["rechercheSite"] = $_GET["rechercheSite"];
            }

            /* Initialisation des compléments de requête */
            if (empty($_SESSION[APP]["content_search"]["rechercheSite"])) {
                $_SESSION[APP]["content_search"]["rechercheSite"] = $_SESSION[APP]['SITE_ID'];
            }
        }
    }

    protected function _getSqlCore()
    {
        $sql = "SELECT DISTINCT cv.content_id,
		cv.content_version,
		c.content_type_id,
		cv.content_title,
		cv.content_title_bo,
		c.content_creation_date,
		CONTENT_VERSION_UPDATE_DATE,
		CONTENT_VERSION_CREATION_USER,
		content_creation_user,
		REPLACE(REPLACE(c.content_creation_user, '##', ','), '#', '') as content_creation_user2,
		content_current_version,
		state_label,
		s.state_id,
		c.site_id,
		c.langue_id,
		cv.page_id,
                cv.CONTENT_CODE2,
		24 as TID
		FROM #pref#_content c,
		#pref#_content_version cv,
		#pref#_state s
		WHERE c.content_id = cv.content_id
		AND c.content_draft_version = cv.content_version
		AND c.langue_id = cv.langue_id
		AND cv.state_id = s.state_id";

        return $sql;
    }

    /** WF */
    protected function _getSqlWorkflow()
    {
        $sql = "(".$this->_getSqlCore().")
		UNION
		(SELECT pv.page_id as content_id,
		pv.page_version as content_version,
		1 as content_type_id,
		REPLACE(REPLACE(p.page_libpath,'#','<br />'),'|',' : ') as content_title,
		REPLACE(REPLACE(p.page_libpath,'#','<br />'),'|',' : ') as content_title_bo,
		p.page_creation_date as content_creation_date,
		page_version_update_date as CONTENT_VERSION_UPDATE_DATE,
		page_creation_user as content_creation_user,
        PAGE_VERSION_CREATION_USER as CONTENT_VERSION_CREATION_USER,
		REPLACE(REPLACE(p.page_creation_user, '##', ','), '#', '') as content_creation_user2,
		page_current_version as content_current_version,
		state_label,
		s.state_id,
		p.site_id,
		p.langue_id,
		p.page_id,
                NULL as CONTENT_CODE2,
		28 as TID
		FROM #pref#_page p,
		#pref#_page_version pv,
		#pref#_state s
		WHERE p.page_id = pv.page_id
		AND p.page_draft_version = pv.page_version
		AND p.langue_id = pv.langue_id
		AND pv.state_id = s.state_id)";

        return $sql;
    }

    protected function _ContentTypeFilter()
    {

        /* WF */
        if ($_GET["sid"]) {
            /* Cas de la recherche */
            unset($_SESSION[APP]["content_search"]);
            $_GET["rechercheContentType"] = "";
            $_GET["recherchePage"] = "";
            $_GET["rechercheAuteur"] = "";
            if ($_GET["filter_recherchePage"]) {
                $_GET["recherchePage"] = $_GET["filter_recherchePage"];
            }
            if ($_GET["filter_rechercheTypeContenu"]) {
                $_GET["rechercheContentType"] = $_GET["filter_rechercheTypeContenu"];
            }
            if ($_GET["filter_rechercheAuteur"]) {
                $_GET["rechercheAuteur"] = $_GET["filter_rechercheAuteur"];
            }
            if ($_GET["sid"] == - 1) {
                $sid = implode(",", Pelican_Cache::fetch("State/Publication"));
                $sql = " AND v.STATE_ID in (".$sid.")
				AND v.CONTENT_TYPE_ID IN (".implode(",", $_SESSION[APP]["content_type"]["id"]).")
				AND CONTENT_STATUS = 0";
                /* pour les contenus archivés */
                $this->versionType = "CURRENT";
            } else {
                /**Récupération de tout les contenus existant*/
                $allContentType = $_SESSION[APP]["state"][$_GET["sid"]]["content_type"];
                /**Suppression des contenus appartenant aux contenu catégorisé*/
                $nbLoop = 0;
                if (! empty(Pelican::$config["CNT_TYPE_NOT_IN_WORKFLOW"])) {
                    $nbLoop = sizeOf(Pelican::$config["CNT_TYPE_NOT_IN_WORKFLOW"]);
                }
                for ($i = 0; $i < $nbLoop; $i ++) {
                    unset($allContentType[Pelican::$config["CNT_TYPE_NOT_IN_WORKFLOW"][$i]]);
                }
                $sql = " AND v.STATE_ID in (".$_GET["sid"].")
				and v.CONTENT_TYPE_ID in (".implode(",", $allContentType).")";
            }
            $this->listOrder .= ', v.CONTENT_TYPE_ID';
        } elseif (is_array($_SESSION[APP]["content_type"]["id"])) {
            $sql = " AND v.CONTENT_TYPE_ID IN (".implode(",", $_SESSION[APP]["content_type"]["id"]).") ";
            $this->listOrder .= ', v.CONTENT_TYPE_ID';
        }

        return $sql;
    }

    protected function _getSqlView()
    {
        /* WF */
        if ($_GET["sid"]) {
            $sql = $this->_getSqlWorkflow();
        } else {
            $sql = $this->_getSqlCore();
        }

        return $sql;
    }

    /**
     * Liste déroulante d'ajout des types de contenus suivant les droits associés à l'utilisateur.
     */
    protected function _getContentCombo()
    {
        $return = '<script type="text/javascript">
			var iContentTypeID = "'.$_GET["rechercheContentType"].'";
			var sPageIndex = "'.Pelican::$config["PAGE_INDEX_IFRAME_PATH"].'";
			var sTid = "'.$_GET["tid"].'";
			var sView ="'.$_REQUEST["view"].'";
			var iDbInsert = "'.Pelican::$config["DATABASE_INSERT_ID"].'";
			var bPopup = "'.$_GET["popup_content"].'";
			var langue = "'.$_SESSION[APP]['LANGUE_ID'].'";
			function changeLocation(id){
				var bMutualisation = false;
				var sHref = "";

				if (!id) {
					iContentTypeID = document.getElementById("CONTENT_TYPE_ID").value;
				} else {
					/** Cas de la mutualisation */
					bMutualisation = id;
				}
				sHref = sPageIndex + "?&tid=" + sTid + "&view=" + sView + "&popup_content=" + bPopup + "&uid=" + iContentTypeID+ "&langue=" + langue ;

				sHref+= "&id=" + iDbInsert;
				if (bMutualisation) {
					sHref += "&mutualisation=" + bMutualisation;
				}

				if (iContentTypeID) {
					top.getIFrameDocument("iframeRight").location.href=sHref;
				}
			}
			document.changeLocation = changeLocation;
</script>';

        if (! $this->iSharingId) {
            $aTypeContenus = getComboValuesFromCache("Backend/ContentType", array(
                $_SESSION[APP]['SITE_ID'],
                "",
                implode(",", $_SESSION[APP]["content_type"]["id"]),
                true,
            ));
            if ($_GET["rechercheContentType"] && $aTypeContenus[$_GET["rechercheContentType"]]) {
                $return .= Pelican_Html::div(Pelican_Html::button(array(
                    onclick => "changeLocation(-2)",
                ), "<b>".t("ADD")." : ".$aTypeContenus[$_GET["rechercheContentType"]]."</b>")); //"onchange=changeLocation()");
            } else {
                $oForm = Pelican_Factory::getInstance('Form', false);
                $return .= $oForm->open("", "post", "fForm", false, true, "CheckForm", "", true, false);
                $return .= beginFormTable();
                $return .= $oForm->createComboFromList("CONTENT_TYPE_ID", t("Créer un contenu de type : "), $aTypeContenus, "", false, false, "1", false, "165", true, false, "onchange=changeLocation()");
                $return .= endFormTable();
                $return .= $oForm->close();
            }
        }

        return $return;
    }

    protected function _save()
    {
        $oConnection = Pelican_Db::getInstance();

        include_once 'Pelican/Taxonomy.php';

        $aBind[":CONTENT_ID"] = Pelican_Db::$values["CONTENT_ID"];
        $aBind[":CONTENT_VERSION"] = Pelican_Db::$values["CONTENT_VERSION"];
        $aBind[":LANGUE_ID"] = (Pelican_Db::$values['LANGUE_ID'] ? Pelican_Db::$values['LANGUE_ID'] : $_SESSION[APP]['LANGUE_ID']);
        switch ($this->form_action) {
            case Pelican_Db::DATABASE_INSERT:
                {
                    $order = (Pelican_Db::$values["CONTENT_ID"] == Pelican::$config["DATABASE_INSERT_ID"]);
                }
            case Pelican_Db::DATABASE_UPDATE:
                {
                    if (! Pelican_Db::$values["MEDIA_ID"] && Pelican_Db::$values["CONTENT_ID"]) {
                        $SQL = "
						DELETE FROM #pref#_content_version_media
						WHERE CONTENT_ID   = :CONTENT_ID
						AND LANGUE_ID    = :LANGUE_ID
						AND CONTENT_VERSION = :CONTENT_VERSION";
                        $oConnection->query($SQL, $aBind);
                    }
                    $oConnection->updateForm($this->form_action, $this->processus);
                    // sauvegarde de la taxonomie


                    $oTaxonomy = Pelican_Factory::getInstance('Taxonomy');
                    $oTaxonomy->saveTermsRelationships(array(
                        'TAXONOMY',
                        'TAXONOMY2',
                    ), Pelican_Db::$values["CONTENT_ID"], 1);
                    if ($order) {
                        Cms_Page_Controller::setPageOrder(Pelican_Db::$values["PAGE_ID"], Pelican_Db::$values["CONTENT_ID"], Pelican_Db::$values["CONTENT_TYPE_ID"], (Pelican_Db::$values["CONTENT_DIRECT_PAGE"] ? 1 : 0));
                        Cms_Page_Controller::setPageOrder(1, Pelican_Db::$values["CONTENT_ID"], Pelican_Db::$values["CONTENT_TYPE_ID"], (Pelican_Db::$values["CONTENT_DIRECT_HOME"] ? 1 : 0));
                    } else {
                        Cms_Page_Controller::cleanPageOrder(Pelican_Db::$values["PAGE_ID"], Pelican_Db::$values["CONTENT_TYPE_ID"]);
                        Cms_Page_Controller::cleanPageOrder(1, Pelican_Db::$values["CONTENT_TYPE_ID"]);
                    }
                    break;
                }
            case Pelican_Db::DATABASE_DELETE:
                {

                    // supression de l'entré #pref#_content dans processus
                    array_shift($this->processus);

                    // Récup de toute les versions d'un contenu
                    if ($this->workflowFieldDeleteVersion) {
                        foreach ($this->workflowFieldDeleteVersion as $delete_version) {
                            //supression d'une version
                            $version[] = array(
                                "CONTENT_VERSION" => $delete_version,
                            );
                        }
                    } else {
                        //supression définitive
                        // suppression dans la navigation
                        $version = $oConnection->queryTab("select CONTENT_VERSION, LANGUE_ID from #pref#_content_version where CONTENT_ID=".Pelican_Db::$values["CONTENT_ID"]." AND LANGUE_ID=".Pelican_Db::$values['LANGUE_ID']);
                        Cms_Page_Controller::cleanPageOrder(Pelican_Db::$values["CONTENT_ID"], Pelican_Db::$values["CONTENT_TYPE_ID"]);
                    }

                    if ($version) {
                        foreach ($version as $content_version) {
                            Pelican_Db::$values["CONTENT_VERSION"] = $content_version["CONTENT_VERSION"];
                            if ($content_version['LANGUE_ID']) {
                                Pelican_Db::$values['LANGUE_ID'] = $content_version['LANGUE_ID'];
                            }
                            $oConnection->updateForm($this->form_action, $this->processus);
                        }
                    }

                    // Supression dans la table #pref#_content à la fin du traitement
                    $oConnection->updateTable($this->form_action, "#pref#_content");

                    break;
                }
        }
    }

    protected function checkUsedContent($contentId)
    {
        $oConnection = Pelican_Db::getInstance();

        $usedContent = false;

        $aTables = array(
            '_page_multi_zone',
            '_page_multi_zone_content',
            '_page_multi_zone_multi',
            '_page_zone',
            '_page_zone_content',
            '_page_zone_multi'
        );

        foreach ($aTables as $table)
        {
            $query = "select CONTENT_ID
                  from #pref#".$table."
                  where CONTENT_ID=".$contentId."
                  AND LANGUE_ID=".$_SESSION[APP]["LANGUE_ID"]."
                  AND PAGE_VERSION = (
                    select PAGE_VERSION
                    FROM #pref#_page_version
                    WHERE PAGE_ID = #pref#".$table.".PAGE_ID
                    ORDER BY PAGE_VERSION DESC
                    LIMIT 1
                  )
            ";

            $result = $oConnection->queryRow($query);

            if ($result['CONTENT_ID']){
                $usedContent = true;
            }
        }

        return $usedContent;
    }
}
