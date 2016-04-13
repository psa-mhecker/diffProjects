<?php

/**
 * Controleur g?rant les traductions FO (import export)
 *
 * @package NMB
 * @subpackage administration
 */
class Administration_Traduction_Controller extends Pelican_Controller_Back
{

    protected $form_name = "label";

    protected $field_id = "LABEL_ID";

    protected $defaultOrder = "l.LABEL_ID";

    protected $decacheBack = array(
        "Translation",
        "TranslationByLabelId",
        "Citroen/PersoProfile"
    );

    protected function setListModel()
    {
        $oConnection = Pelican_Db::getInstance();
        $sSQL = "SELECT LANGUE_ID from #pref#_site_language where SITE_ID = " . $_SESSION[APP]['SITE_ID'];
        $aLanguage = $oConnection->queryTab($sSQL);
        $sLanguage = "";
        if (is_array($aLanguage) && count($aLanguage) > 0) {
            foreach ($aLanguage as $key => $language) {
                if ($key != 0) {
                    $sLanguage .= ",";
                }
                $sLanguage .= $language['LANGUE_ID'];
            }
        }
        
        $sqlList = "
			SELECT
				distinct l.*,
				l.LABEL_ID LABEL_ID2
				";
        if ($this->getParam('tc') == "bo" || $_SESSION[APP]['SITE_ID'] == Pelican::$config['SITE_BO']) {
            $sqlList .= " , left(llfr.LABEL_TRANSLATE,50) TRANSLATE_FR,
									left(llen.LABEL_TRANSLATE,50) TRANSLATE_EN ";
        }
        $sqlList .= " FROM
						#pref#_label l";
        if ($this->getParam('tc') == "bo" || $_SESSION[APP]['SITE_ID'] == Pelican::$config['SITE_BO']) {
            $sqlList .= " LEFT JOIN #pref#_label_langue llfr
							ON (l.LABEL_ID = llfr.LABEL_ID and llfr.LANGUE_ID = 1)
						  LEFT JOIN #pref#_label_langue llen
			 				ON (l.LABEL_ID = llen.LABEL_ID and llen.LANGUE_ID = 2)";
        } else {
            $sqlList .= " LEFT JOIN #pref#_label_langue ll
							ON (l.LABEL_ID = ll.LABEL_ID ";
            if ($sLanguage != "") {
                $sqlList .= " AND ll.LANGUE_ID IN (" . $sLanguage . ")";
            }
            $sqlList .= "	)
						  LEFT JOIN #pref#_label_langue_site lls
							ON (l.LABEL_ID = lls.LABEL_ID AND lls.SITE_ID = " . $_SESSION[APP]['SITE_ID'] . "";
            if ($sLanguage != "") {
                $sqlList .= " AND lls.LANGUE_ID IN (" . $sLanguage . ")";
            }
            $sqlList .= "		)";
        }
        if ($this->getParam('tc') == "bo") {
            $where[] = "l.LABEL_BO = 1";
        } else {
            $where[] = "l.LABEL_FO = 1";
        }
        if (! empty($_GET['filter_LABEL_ID'])) {
            $this->aBind[':LABEL_ID'] = $_GET['filter_LABEL_ID'];
            $where[] = "l.LABEL_ID like '%" . str_replace("'", "''", $_GET['filter_LABEL_ID']) . "%' ";
        }
        if (! empty($_GET['filter_LABEL_TRANSLATE'])) {
            $this->aBind[':LABEL_TRANSLATE'] = $_GET['filter_LABEL_TRANSLATE'];
            if ($this->getParam('tc') == "bo" || $_SESSION[APP]['SITE_ID'] == Pelican::$config['SITE_BO']) {
                $where[] = "((llfr.LABEL_TRANSLATE like '%" . str_replace("'", "''", $_GET['filter_LABEL_TRANSLATE']) . "%') OR (llen.LABEL_TRANSLATE like '%" . str_replace("'", "''", $_GET['filter_LABEL_TRANSLATE']) . "%'))";
            } else {
                $where[] = "lls.LABEL_TRANSLATE like '%" . str_replace("'", "''", $_GET['filter_LABEL_TRANSLATE']) . "%'";
            }
        }
        if ($where) {
            $sqlList .= " where " . implode(" and ", $where);
        }
        
        $sqlList .= " order by " . $this->listOrder;
        
        if (! empty($_GET['id'])) {
            $this->aBind[":LABEL_ID"] = $oConnection->strToBind($this->id);
        }
        $aTranslate = $oConnection->queryTab($sqlList);
        $this->listModel = $aTranslate;
    }

    protected function setEditModel()
    {
        $sTableName = ($this->getParam('tc') == "bo") ? "#pref#_label_langue" : "#pref#_label_langue_site";
        if (! empty($_GET['id'])) {
            $oConnection = Pelican_Db::getInstance();
            $this->aBind[":LABEL_ID"] = $oConnection->strToBind($this->id);
        }
        $sSQL = "SELECT
					" . $sTableName . ".*,
					#pref#_label.*,
					#pref#_label.LABEL_ID  LABEL_ID
				FROM
					#pref#_label
				LEFT JOIN #pref#_label_langue ON (#pref#_label_langue.LABEL_ID=#pref#_label.LABEL_ID) ";
        
        if ($this->getParam('tc') == "fo") {
            $sSQL .= " LEFT JOIN #pref#_label_langue_site ON (#pref#_label_langue.LABEL_ID = #pref#_label_langue_site.LABEL_ID AND #pref#_label_langue_site.SITE_ID = " . $_SESSION[APP]['SITE_ID'] . ")";
        }
        $sSQL .= " WHERE #pref#_label.LABEL_ID=" . $this->aBind[":LABEL_ID"] . " ";
        $this->editModel = $sSQL;
    }

    public function listAction()
    {
        $oConnection = Pelican_Db::getInstance();
        $sTableName = ($this->getParam('tc') == "bo") ? "#pref#_label_langue" : "#pref#_label_langue_site";
        // R?cup?ration des langues disponibles pour le site affich?
        $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $sSQL = "SELECT
					l.LANGUE_CODE,
					l.LANGUE_LABEL,
					l.LANGUE_ID,
					sl.SITE_ID
				FROM
					#pref#_site_language sl INNER JOIN #pref#_language l
						ON (sl.LANGUE_ID = l.LANGUE_ID)
				WHERE
					sl.SITE_ID = :SITE_ID
		";
        $aLangue = $oConnection->queryTab($sSQL, $aBind);
        $sSqlCodePays = $oConnection->queryItem("SELECT SITE_CODE_PAYS FROM #pref#_site_code WHERE SITE_ID = :SITE_ID", array(
            ":SITE_ID" => $_SESSION[APP]['SITE_ID']
        ));
        
        $filename = $this->getFilename($aLangue, $sSqlCodePays);
        
        if (is_array($aLangue) && count($aLangue) > 0) {
            foreach ($aLangue as $key => $langue) {
                // Tableau des langues disponibles
                $aTabLang[$langue['LANGUE_ID']] = t(strtoupper(dropaccent($langue['LANGUE_LABEL'])));
            }
        }
        
        if ($this->getParam('tc') == "bo") {
            $sLibExportTrad = strtoupper(t('EXPORT_TRAD_BO'));
            $sLibImportTrad = strtoupper(t('IMPORT_TRAD_BO'));
            $sLibLabelFileTrad = t('LABEL_FILE_TRAD_BO');
            $sLibLabelLangTrad = t('LABEL_TRAD_LANG_BO');
            $sLibUploadTrad = t("UPLOAD_FILE_TRAD_BO");
        } else {
            $sLibExportTrad = strtoupper(t('EXPORT_TRAD_FO'));
            $sLibImportTrad = strtoupper(t('IMPORT_TRAD_FO'));
            $sLibLabelFileTrad = t('LABEL_FILE_TRAD_FO');
            $sLibLabelLangTrad = t('LABEL_TRAD_LANG_FO');
            $sLibUploadTrad = t("UPLOAD_FILE_TRAD_FO");
        }
        $form = '</table><div id="globalImport" style="width:100%"><div id="exportFo" style="float:left;width:50%;">';
        $form .= '<span style="font-weight:bold;">' . Pelican_Html::b($sLibExportTrad) . '</span>' . Pelican_Html::br() . Pelican_Html::br();
        if (is_array($aLangue) && count($aLangue) > 0) {
            // $form .= Pelican_Html::i(t('SAVE_CSV_TRAD_FO')).Pelican_Html::br();
            foreach ($aLangue as $key => $langue) {
                if (file_exists($filename[$langue['LANGUE_ID']])) {
                    if ($this->getParam('tc') == "bo") {
                        $sFile = $langue['LANGUE_CODE'];
                    } else {
                        $sFile = $sSqlCodePays . "-" . $langue['LANGUE_CODE'];
                    }
                    $form .= '<form name="fFormDl' . $key . '" id="fFormDl' . $key . '" action="/download.php" method="post">';
                    $form .= '<input type="hidden" value="' . $sFile . '.csv" id="file" name="file"/>';
                    $form .= '<a href="javascript://" onClick="downloadAction(\'fFormDl' . $key . '\');">' . $langue['LANGUE_LABEL'] . '</a>' . Pelican_Html::br();
                    $form .= '</form>';
                }
            }
        }
        $form .= '</div>
		<div id="importFo" style="float:left;width:50%;">';
        
        $form .= '<span style="font-weight:bold;">' . Pelican_Html::b($sLibImportTrad) . '</span>' . Pelican_Html::br() . Pelican_Html::br();
        $form .= '<form name="fFormImport" id="fFormImport" action="/_/Administration_Traduction/launchImport" method="post" onSubmit="return checkImport();" enctype="multipart/form-data">';
        $form .= Pelican_Html::b($sLibLabelFileTrad . " *") . Pelican_Html::br();
        $form .= '<input type="hidden" name="MAX_FILE_SIZE" value="2097152"> ';
        $form .= '<input type="hidden" name="tc" value="' . $this->getParam('tc') . '"> ';
        $form .= '<input type="file" name="FILE_IMPORT_TRAD" id="FILE_IMPORT_TRAD" size="40" />';
        $form .= Pelican_Html::br();
        $form .= Pelican_Html::br() . Pelican_Html::b($sLibLabelLangTrad . " *") . Pelican_Html::br() . Pelican_Html::br();
        $compteur = 0;
        foreach ($aTabLang as $k => $lang) {
            $checked = ($compteur == 0) ? "checked" : "";
            $form .= '<input type="radio" name="LANGUE_IMPORT_TRAD" value="' . $k . '" ' . $checked . '/>' . $lang . '' . Pelican_Html::br();
            $compteur ++;
        }
        $form .= Pelican_Html::br() . '<input name="submitUpload" type="submit" class="button" value="' . $sLibUploadTrad . '"/>';
        $form .= '</form>';
        $form .= '<script type="text/javascript">';
        if ($_SESSION[APP]['IMPORT_TRAD'] || $_SESSION[APP]['DECACHE_TRAD']) {
            $sLib = '';
            if ($_SESSION[APP]['IMPORT_TRAD']) {
				if($_SESSION[APP]['FORMAT_KO'] != ''){
					$sLib = 'PROBLEME_ENCODAGE';
				}elseif($_SESSION[APP]['IMPORT_TRAD_DETAIL'] != ''){
					$sLib = ($this->getParam('bImport') == 'OK') ? 'ALERT_IMPORT_SUCCES_BUT_ALERT' : 'ALERT_IMPORT_ECHEC';
				}else{
					$sLib = ($this->getParam('bImport') == 'OK') ? 'ALERT_IMPORT_SUCCES' : 'ALERT_IMPORT_ECHEC';
				}
                
            } elseif ($_SESSION[APP]['DECACHE_TRAD']) {
                $sLib = ($this->getParam('bDecache') == 'OK') ? 'ALERT_DECACHE_SUCCES' : 'ALERT_DECACHE_ECHEC';
            }
            unset($_SESSION[APP]['IMPORT_TRAD']);
            unset($_SESSION[APP]['DECACHE_TRAD']);
            $form .= 'alert(\'' . t($sLib, 'js2') . '\'); ';
        }
        $form .= '	function checkImport(){
						var sFichier = $("input[name=FILE_IMPORT_TRAD]").val();
						if(sFichier == ""){
							alert(\'' . t('WARNING_CHOOSE_FILE_TRAD', 'js2') . '\');
							return false;
						}
					}
					function downloadAction(fFormId){
						$("#" + fFormId).submit();
					}
					</script>';
        $form .= Pelican_Html::br();
        $form .= '</div></div>';
        $form .= '<form name="fFormCache" id="fFormCache" action="/_/Administration_Traduction/generateCache" method="post">
		<input name="submitDecache" type="submit" class="button" value="' . t("DECACHE_TRAD") . '"/>
		<input name="tc" type="hidden" value="' . $this->getParam('tc') . '"/>
		</form>
		<table width="100%">';
        
        parent::listAction();
        
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        
        $table->setFilterField('LABEL_ID', t('ID') . " : ", array(
            "#pref#_label.LABEL_ID"
        ));
        $table->setFilterField('LABEL_TRANSLATE', t('LABEL_TRANSLATE') . " : ", array(
            "LABEL_TRANSLATE"
        ), "", array(), "1", true);
        $table->getFilter(2);
        $table->setCSS(array(
            "tblalt1",
            "tblalt2"
        ));
        $table->setValues($this->getListModel(), "#pref#_label.LABEL_ID");
        $table->addColumn(t('ID'), "LABEL_ID2", "30", "left", "", "tblheader", "LABEL_ID2");
        if ($_SESSION[APP]['SITE_ID'] == Pelican::$config['SITE_BO']) {
            $table->addColumn(t('FRANCAIS'), "TRANSLATE_FR", "30", "left", "", "tblheader", "TRANSLATE_FR");
            $table->addColumn(t('ANGLAIS'), "TRANSLATE_EN", "30", "left", "", "tblheader", "TRANSLATE_EN");
        }
        $sqlLng = "SELECT
						distinct LABEL_ID as \"id\",
						LANGUE_CODE as \"lib\"
					FROM
						#pref#_label_langue ll,
						#pref#_language l,
						#pref#_site_language sl
					WHERE ll.LANGUE_ID = l.LANGUE_ID
					AND sl.LANGUE_ID = ll.LANGUE_ID
					AND sl.SITE_ID = " . $_SESSION[APP]['SITE_ID'];
        $table->addMulti(t('LANG_LIST_MASTER'), "LABEL_ID", "20", "left", ",", "tblheader", "", $sqlLng);
        if ($this->getParam('tc') == "fo") {
            $sqlLngPays = "SELECT
							distinct LABEL_ID as \"id\",
							LANGUE_CODE as \"lib\"
						FROM
							#pref#_label_langue_site ll,
							#pref#_language l,
							#pref#_site_language sl
						WHERE ll.LANGUE_ID = l.LANGUE_ID
						AND sl.LANGUE_ID = ll.LANGUE_ID
						AND sl.SITE_ID = " . $_SESSION[APP]['SITE_ID'];
            $table->addMulti(t('LANG_LIST_PAYS'), "LABEL_ID", "20", "left", ",", "tblheader", "", $sqlLngPays);
        }
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array(
            "id" => "LABEL_ID"
        ), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array(
            "id" => "LABEL_ID",
            "" => "readO=true"
        ), "center");
        
        $this->setResponse($form . $table->getTable());
    }

    public function editAction()
    {
        $oConnection = Pelican_Db::getInstance();
        $sTableName = ($this->getParam('tc') == "bo") ? "#pref#_label_langue" : "#pref#_label_langue_site";
        parent::editAction();
        $form = $this->startStandardForm();
        $form .= $this->oForm->createLabel(t('LABEL_TRANSLATE'), "");
        $form .= $this->oForm->createHidden("LABEL_ID_SAUVE", $this->values["LABEL_ID"]);
        $form .= $this->oForm->createHidden("LABEL_INFO", $this->values["LABEL_INFO"]);
        if (empty($this->values)) {
            if ($this->getParam('tc') == "bo") {
                $this->values["LABEL_BO"] = 1;
            } else {
                $this->values["LABEL_FO"] = 1;
            }
        }
        if ($this->getParam('tc') == "bo") {
            $form .= $this->oForm->createHidden("LABEL_BO", $this->values["LABEL_BO"]);
        } else {
            $form .= $this->oForm->createHidden("LABEL_FO", $this->values["LABEL_FO"]);
        }
        $form .= $this->oForm->createInput("LABEL_ID", t("ID"), 255, "", true, $this->values["LABEL_ID"], $_GET["readO"], 75);
        $aCase = Pelican::$config['LABEL_CASE'];
        if ($_SESSION[APP]['SITE_ID'] == Pelican::$config['SITE_BO']) {
            $form .= $this->oForm->createInput("LABEL_LENGTH", t("LABEL_LENGTH_MAX"), 3, "", false, $this->values["LABEL_LENGTH"], $_GET["readO"], 3);
            $form .= $this->oForm->createComboFromList("LABEL_CASE", t("LABEL_CASE"), $aCase, $aCase[$this->values["LABEL_CASE"]], false, $_GET["readO"]);
        } else {
            $form .= $this->oForm->createLabel(t("LABEL_LENGTH_MAX"), $this->values["LABEL_LENGTH"]);
            $form .= $this->oForm->createLabel(t("LABEL_CASE"), $aCase[$this->values["LABEL_CASE"]]);
        }
        $this->aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $sSQL = "SELECT
					#pref#_language.*, ";
        if ($this->getParam('tc') == "bo") {
            $sSQL .= "#pref#_label_langue.LABEL_TRANSLATE ";
        } else {
            $sSQL .= "ifnull(#pref#_label_langue_site.LABEL_TRANSLATE, #pref#_label_langue.LABEL_TRANSLATE) LABEL_TRANSLATE ";
        }
        $sSQL .= "FROM
					#pref#_language
				INNER JOIN #pref#_site_language
					ON (#pref#_site_language.LANGUE_ID = #pref#_language.LANGUE_ID and #pref#_site_language.SITE_ID = :SITE_ID)
				LEFT JOIN #pref#_label_langue
					ON (#pref#_language.LANGUE_ID=#pref#_label_langue.LANGUE_ID AND #pref#_label_langue.LABEL_ID=:LABEL_ID)";
        if ($this->getParam('tc') == "fo") {
            $sSQL .= " LEFT JOIN #pref#_label_langue_site
					ON (#pref#_language.LANGUE_ID=#pref#_label_langue_site.LANGUE_ID AND #pref#_label_langue_site.LABEL_ID=:LABEL_ID AND #pref#_label_langue_site.SITE_ID = :SITE_ID )";
        }
        $sSQL .= " ORDER BY #pref#_label_langue.LANGUE_ID ";
        $result = $oConnection->queryTab($sSQL, $this->aBind);
        foreach ($result as $langue) {
            $form .= $this->oForm->createInput("LABEL_TRANSLATE_" . $langue['LANGUE_ID'], Pelican_Html::img(array(
                src => '/library/Pelican/Translate/public/images/flags/' . $langue["LANGUE_CODE"] . '.png'
            )) . '&nbsp;&nbsp;' . $langue["LANGUE_LABEL"], 255, "", false, $langue["LABEL_TRANSLATE"], $_GET["readO"], 75);
        }
        $form .= $this->oForm->createHidden("B_PUBLICATION_LABEL", true);
        $form .= $this->oForm->createHidden("SITE_ID", $_SESSION[APP]['SITE_ID']);
        $form .= $this->oForm->createHidden("tc", $this->getParam('tc'));
        if (! $this->readO) {
            $form .= '
					<script type="text/javascript">
					function checkingKey(){
						var labelId = "' . $_GET['id'] . '";
						var labelIdNew = $("#LABEL_ID").val();
						$.ajax({
							url: "check",
                            data: {
                                className: "Administration_Traduction_Controller",
                                method: "checkExistenceCleAction",
                                labelId: labelId,labelIdNew:labelIdNew},
							async: false,
							success: function(data) {
								$("#B_PUBLICATION_LABEL").val("true");
								if(data > 0){
									$("#B_PUBLICATION_LABEL").val("false");
								}
							}
						});
					}
			</script>';
            
            $form .= $this->oForm->createJs('
					checkingKey();
					if($("#B_PUBLICATION_LABEL").val() == "false"){
						if($("#B_PUBLICATION_LABEL_BO").val() == "false"){
							alert(\'' . t('Key exists', 'js2') . '\');
						}
						fwFocus($("#LABEL_ID"));
						return false;
					}');
        }
        
        $form .= $this->stopStandardForm();
        
        // Zend_Form start
        $form = formToString($this->oForm, $form);
        // Zend_Form stop
        
        $this->setResponse($form);
    }

    public function before()
    {
        parent::before();
    }

    public function saveAction()
    {
        // var_dump(Pelican_Db::$values);
        $oConnection = Pelican_Db::getInstance();
        Pelican_Db::$values["LABEL_ID"] = rtrim(Pelican_Db::$values["LABEL_ID"]);
        $sTableName = (Pelican_Db::$values['tc'] == "bo" || $_SESSION[APP]['SITE_ID'] == Pelican::$config['SITE_BO']) ? "#pref#_label_langue" : "#pref#_label_langue_site";
        $translation_folder = Pelican::$config["VAR_ROOT"] . "/i18n/common/";
        $this->aBind[":LANGUE_ID"] = Pelican_Db::$values['LANGUE_ID'];
        $this->aBind[":SITE_ID"] = Pelican_Db::$values['SITE_ID'];
        $this->aBind[":LABEL_ID"] = $oConnection->strtoBind(Pelican_Db::$values["LABEL_ID"]);
        $this->aBind[":LABEL_ID_SAUVE"] = $oConnection->strtoBind(Pelican_Db::$values["LABEL_ID_SAUVE"]);
        
        // cas dun libelle non affiche mais existant
        if ($this->form_action == Pelican_Db::DATABASE_INSERT) {
            $exists = $oConnection->queryRow('select * from #pref#_label where LABEL_ID=:LABEL_ID', $this->aBind);
             if (count($exists) > 0) {
                $this->form_action = Pelican_Db::DATABASE_UPDATE;
                $fields = array(
                    'LABEL_INFO',
                    'LABEL_BACK',
                    'LABEL_LENGTH',
                    'LABEL_CASE',
                    'LABEL_BO',
                    'LABEL_FO'
                );
                foreach ($fields as $field) {
                    if (! empty($exists[$field])) {
                        Pelican_Db::$values[$field] = $exists[$field];
                    }
                }
            }
        }
        
        if (! empty(Pelican_Db::$values["LABEL_ID_SAUVE"]) && Pelican_Db::$values["LABEL_ID_SAUVE"] != Pelican_Db::$values["LABEL_ID"] && $this->form_action == Pelican_Db::DATABASE_UPDATE) {
            $oConnection->query("UPDATE #pref#_label SET LABEL_ID=:LABEL_ID WHERE LABEL_ID=:LABEL_ID_SAUVE", $this->aBind);
            $sSQL = "UPDATE
						" . $sTableName . "
					SET
						LABEL_ID = :LABEL_ID
					WHERE
						LABEL_ID = :LABEL_ID_SAUVE";
            if ($this->getParam('tc') == "fo") {
                $sSQL .= " AND SITE_ID = :SITE_ID";
            }
            $oConnection->query($sSQL, $this->aBind);
        }
        
        if ($this->form_action != Pelican_Db::DATABASE_DELETE) {
            Pelican_Db::$values["LABEL_TRANSLATE"] = Pelican_Db::$values["LABEL_TRANSLATE_FR"];
            $oConnection->updateTable($this->form_action, "#pref#_label");
        }
        
        $this->aBind[":LABEL_ID"] = $oConnection->strtoBind(Pelican_Db::$values["LABEL_ID"]);
        $sSQL = "DELETE FROM
					" . $sTableName . "
				WHERE
					LABEL_ID = :LABEL_ID";
        if ($this->getParam('tc') == "fo" && $_SESSION[APP]['SITE_ID'] != Pelican::$config['SITE_BO']) {
            $sSQL .= " AND SITE_ID = :SITE_ID";
        }
        $oConnection->query($sSQL, $this->aBind);
        
        if ($this->form_action == Pelican_Db::DATABASE_DELETE) {
            Pelican_Db::$values["LABEL_TRANSLATE"] = Pelican_Db::$values["LABEL_TRANSLATE_FR"];
            $sSQL = "SELECT count(*) from #pref#_label_langue where LABEL_ID=:LABEL_ID";
            $countLangue = $oConnection->query($sSQL, $this->aBind);
            $sSQL = "SELECT count(*) from #pref#_label_langue_site where LABEL_ID=:LABEL_ID";
            $countLangueSite = $oConnection->query($sSQL, $this->aBind);
            if ($countLangueSite == 0 && $countLangue == 0) {
                $oConnection->query("delete from #pref#_label where LABEL_ID=:LABEL_ID", $this->aBind);
            }
        }
        
        if ($this->form_action != Pelican_Db::DATABASE_DELETE) {
            $result = $oConnection->queryTab("select * from #pref#_language");
            foreach ($result as $langue) {
                Pelican_Db::$values["LABEL_TRANSLATE"] = '';
                Pelican_Db::$values["LABEL_TRANSLATE"] = Pelican_Db::$values["LABEL_TRANSLATE_" . $langue['LANGUE_ID']];
                Pelican_Db::$values['LANGUE_ID'] = $langue['LANGUE_ID'];
                if (Pelican_Db::$values['LABEL_TRANSLATE'] != '') {
                    $oConnection->updateTable(Pelican_Db::DATABASE_INSERT, $sTableName);
                }
            }
        }
        $lang_code = $oConnection->queryItem('select LANGUE_CODE from #pref#_language where LANGUE_ID=:LANGUE_ID', $this->aBind);
        
        $this->generateCsv();
    }

    /**
     * M?thode permettant de lancer l'import de nouvelles traductions
     * ? partir d'un fichier et d'une langue s?lectionn?e r?cup?r?s en POST
     * Si l'import s'est d?roul? correctement on redirige avec un bool ? true
     */
    public function launchImportAction()
    {
        $oConnection = Pelican_Db::getInstance();
        if ($_SESSION[APP]['SITE_ID'] != Pelican::$config['SITE_BO']) {
            $sTableName = ($this->getParam('tc') == "bo") ? "#pref#_label_langue" : "#pref#_label_langue_site";
        } else {
            $sTableName = "#pref#_label_langue";
        }
        
        $bCSV = Backoffice_File_Helper::isCSV($_FILES['FILE_IMPORT_TRAD']['type']);
        $bImport = 'KO';
        
        if ($bCSV == true && $_FILES['FILE_IMPORT_TRAD']['error'] == UPLOAD_ERR_OK) {
            // R?cup?ration des infos de la langue s?lectionn?e
            $this->aBind[':LANGUE_ID'] = $this->getParam('LANGUE_IMPORT_TRAD');
            $this->aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
            $sSQL = "SELECT
						l.LANGUE_CODE,
						l.LANGUE_LABEL,
						l.LANGUE_ID
					FROM
						#pref#_language l
					WHERE
						l.LANGUE_ID = :LANGUE_ID
			";
            $aLanguageSelected = $oConnection->queryRow($sSQL, $this->aBind);
            
            // R?cup?ration de toutes les entit?s traduites dans la langue s?lectionn?e
            $sSQL = "SELECT
						l.LABEL_ID,";
            
            if ($_SESSION[APP]['SITE_ID'] != Pelican::$config['SITE_BO']) {
                if ($this->getParam('tc') == "fo") {
                    $sSQL .= " lls.LABEL_TRANSLATE ";
                } else {
                    $sSQL .= " ll.LABEL_TRANSLATE ";
                }
            } else {
                $sSQL .= " ll.LABEL_TRANSLATE ";
            }
            
            //ici
            $sSQL .= " FROM #pref#_label l, #pref#_label_langue ll";
            
            if ($_SESSION[APP]['SITE_ID'] != Pelican::$config['SITE_BO']) {
                if ($this->getParam('tc') == "fo") {
                    $sSQL .= ", #pref#_label_langue_site lls  ";
                }
            }
            //et ici
            $sSQL .= " WHERE
            l.LABEL_ID = ll.LABEL_ID AND ll.LANGUE_ID = :LANGUE_ID ";
            
            if ($_SESSION[APP]['SITE_ID'] != Pelican::$config['SITE_BO']) {
                if ($this->getParam('tc') == "fo") {
                    $sSQL .= "AND l.LABEL_ID = lls.LABEL_ID  AND lls.SITE_ID = :SITE_ID AND lls.LANGUE_ID = :LANGUE_ID ";
                }
            }
            
            if ($this->getParam('tc') == "bo") {
                $sSQL .= " AND l.LABEL_BO = 1";
            } else {
                $sSQL .= " AND l.LABEL_FO = 1";
            }
            $sSQL .= " order by l.LABEL_ID";
            
            $aResult = $oConnection->queryTab($sSQL, $this->aBind);
            
            // Construction d'un tableau avec la cl? de traduction en cl? du tableau
            $aTraduction = array();
            if (is_array($aResult) && count($aResult) > 0) {
                foreach ($aResult as $i => $result) {
                    $aTraduction[strtoupper(dropaccent($result['LABEL_ID']))] = $result['LABEL_TRANSLATE'];
                }
            }
            
            // Construction du chemin o? le fichier va ?tre d?poser
            $sCheminDestination = Pelican::$config["DOCUMENT_INIT"] . '/var/i18n/backend/';
            // D?p?t du fichier dans le r?pertoire
            if (isset($_FILES['FILE_IMPORT_TRAD']['tmp_name']) && $_FILES['FILE_IMPORT_TRAD']['error'] == UPLOAD_ERR_OK) {
                move_uploaded_file($_FILES['FILE_IMPORT_TRAD']['tmp_name'], $sCheminDestination . $_FILES['FILE_IMPORT_TRAD']['name']);
            }
            
            $aClesTraitees = array();
            // Lecture du fichier CSV d?pos?
            $filename = $sCheminDestination . $_FILES['FILE_IMPORT_TRAD']['name'];
            if (file_exists($filename)) {
                $fp = fopen($filename, 'r');				
				
				$content = file_get_contents($filename);				
				$_SESSION[APP]['IMPORT_TRAD_DETAIL'] = '';
				$_SESSION[APP]['FORMAT_KO'] = '';
				
				if(mb_detect_encoding($content, "UTF-8", TRUE)){
					while (! feof($fp)) {
						$ligne = fgetcsv($fp, 4096, ";", '"');
						
						//count($ligne) == 2 controle du nombre de colonne et aussi si le séparateur est correct
						if ($ligne[0] != "CLE" && $ligne[1] != "TRADUCTION" && $ligne[0] != "" && count($ligne) == 2) {
							$this->aBind[':LABEL_ID'] = $oConnection->strtoBind($ligne[0]);

							if( preg_match( '/[\p{Cyrillic}]/u', $ligne[1]) || $this->getParam('LANGUE_IMPORT_TRAD') == '33' ){
								$this->aBind[':LABEL_TRANSLATE'] = $oConnection->strtoBind($ligne[1]);
							}else{
								$this->aBind[':LABEL_TRANSLATE'] = $oConnection->strtoBind(($ligne[1]));                            
							}
							
							// Values
							Pelican_Db::$values["LABEL_ID"] = str_replace(array(
								"'",
								"''"
							), array(
								"_",
								"_"
							), $ligne[0]);
							Pelican_Db::$values['LANGUE_ID'] = $this->aBind[':LANGUE_ID'];
							Pelican_Db::$values['SITE_ID'] = $this->aBind[':SITE_ID'];
							if( preg_match( '/[\p{Cyrillic}]/u', $ligne[1]) || $this->getParam('LANGUE_IMPORT_TRAD') == '33' ){
								Pelican_Db::$values["LABEL_TRANSLATE"] = $ligne[1];
							}else{
								Pelican_Db::$values["LABEL_TRANSLATE"] = utf8_encode($ligne[1]);               
							}                                              
							
							if ($this->getParam('tc') == "bo") {
								Pelican_Db::$values["LABEL_BO"] = 1;
							} else {
								Pelican_Db::$values["LABEL_FO"] = 1;
							}
							// On v?rifie si la cl? de traduction existe en base
							
							if (array_key_exists(strtoupper(dropaccent($ligne[0])), $aTraduction)) {
								// Si elle existe, on v?rifie que la traduction n'a pas ?t? modifi?, on ne fait rien si la traduction est vide
								if (strtoupper($aTraduction[strtoupper(dropaccent($ligne[0]))]) != strtoupper($ligne[1]) && $ligne[1] != "" /* comment by lbo && strtoupper($aTraduction[strtoupper(dropaccent($ligne[0]))]) != ""*/){
									// Si elle a ?t? modifi?, on la met ? jour en base de donn?es
									$sSQL = 'UPDATE
												' . $sTableName . '
											SET LABEL_TRANSLATE = :LABEL_TRANSLATE
											WHERE
												LABEL_ID = :LABEL_ID
											AND LANGUE_ID = :LANGUE_ID';
									if ($_SESSION[APP]['SITE_ID'] != Pelican::$config['SITE_BO']) {
										if ($this->getParam('tc') == "fo") {
											$sSQL .= " AND SITE_ID = :SITE_ID ";
										}
									}
									$oConnection->query($sSQL, $this->aBind);
								} elseif (strtoupper($aTraduction[strtoupper(dropaccent($ligne[0]))]) == "" && $ligne[1] != "" && ! in_array(strtoupper(dropaccent($ligne[0])), $aClesTraitees)) {
									$oConnection->updateTable(Pelican_Db::DATABASE_INSERT, $sTableName);
								}
							} else {
								if (! in_array(strtoupper(dropaccent($ligne[0])), $aClesTraitees)) {
									$sSQLCount = "select count(*) from #pref#_label where LABEL_ID = :LABEL_ID";
									$iCount = $oConnection->queryItem($sSQLCount, array(
										":LABEL_ID" => $oConnection->strToBind(Pelican_Db::$values["LABEL_ID"])
									));

									if ($iCount == "0") {
										// Si elle n'existe pas, on la cr?e
										$oConnection->updateTable(Pelican_Db::DATABASE_INSERT, "#pref#_label");
										$oConnection->updateTable(Pelican_Db::DATABASE_INSERT, $sTableName);
									} else {
										$sSQLCountSite = "select count(*) from " . $sTableName . " where LABEL_ID = :LABEL_ID AND LANGUE_ID = :LANGUE_ID";
										if ($_SESSION[APP]['SITE_ID'] != Pelican::$config['SITE_BO']) {
											$sSQLCountSite .= "  AND SITE_ID = :SITE_ID";
											$iCountSite = $oConnection->queryItem($sSQLCountSite, array(
												":LABEL_ID" => $oConnection->strToBind(Pelican_Db::$values["LABEL_ID"]),
												":LANGUE_ID" => $oConnection->strToBind(Pelican_Db::$values["LANGUE_ID"]),
												":SITE_ID" => $oConnection->strToBind(Pelican_Db::$values["SITE_ID"])
											));
										} else {
											$iCountSite = $oConnection->queryItem($sSQLCountSite, array(
												":LABEL_ID" => $oConnection->strToBind(Pelican_Db::$values["LABEL_ID"]),
												":LANGUE_ID" => $oConnection->strToBind(Pelican_Db::$values["LANGUE_ID"])
											));
										}                                    
										if ($iCountSite == "0") {
											// Si elle n'existe pas, on la cr?e
											$oConnection->updateTable(Pelican_Db::DATABASE_INSERT, $sTableName);
										}else{
											$sSQL = 'UPDATE
												' . $sTableName . '
											SET LABEL_TRANSLATE = :LABEL_TRANSLATE
											WHERE
												LABEL_ID = :LABEL_ID
											AND LANGUE_ID = :LANGUE_ID';
											if ($_SESSION[APP]['SITE_ID'] != Pelican::$config['SITE_BO']) {
												if ($this->getParam('tc') == "fo") {
													$sSQL .= " AND SITE_ID = :SITE_ID ";
												}
											}                                        
											$oConnection->query($sSQL, $this->aBind);
										}
									}
								}
							}
							$aClesTraitees[] = strtoupper(dropaccent($ligne[0]));
						}else{
							if(count($ligne) != 2){
								$_SESSION[APP]['IMPORT_TRAD_DETAIL'] .= t('CHECK_NB_COLONNE_OR_SYNTAXE');
							}
							
						}
					}//die;
				}else{
					$bImport = 'KO';
					$_SESSION[APP]['FORMAT_KO'] = t('PROBLEME_ENCODAGE');					
				}
                $bImport = 'OK';
                // On ferme le fichier et la balise de tableau
                fclose($fp);
            }
        }
        if ($bImport) {
            $_SESSION[APP]['IMPORT_TRAD'] = true;
        }
        $this->generateCsv();
        echo '<script type="text/javascript">location.href = "/_/Index/child?tid=' . Pelican::$config['TEMPLATE_ADMIN_TRADUCTION'] . '&bImport=' . $bImport . '&tc=' . $this->getParam('tc') . '"</script>';
    }

    public function getTraduction($lang, $tc)
    {
        $oConnection = Pelican_Db::getInstance();
        
        $aBind[":LANGUE_CODE"] = $oConnection->strToBind($lang);
        $aBind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
        
        $sSql = "
			SELECT
				distinct(lab.LABEL_ID),
				ll.LABEL_TRANSLATE
			FROM
				#pref#_label lab
			 ";
        if (($tc == "fo")) {
            $sSql .= " INNER JOIN #pref#_label_langue_site ll
							ON (ll.LABEL_ID = lab.LABEL_ID ) ";
        } else {
            $sSql .= " INNER JOIN #pref#_label_langue ll
							ON (ll.LABEL_ID = lab.LABEL_ID) ";
        }
        $sSql .= "
			inner join #pref#_language l on (l.LANGUE_ID = ll.LANGUE_ID)
			where l.LANGUE_CODE = :LANGUE_CODE
			
			";
        
        if (($tc == "fo")) {
            $sSql .= " AND ll.SITE_ID=:SITE_ID";
        }
        
        $sSql .= " order by ll.LABEL_ID";
        
        $aResult = $oConnection->queryTab($sSql, $aBind);
		
		
        if (is_array($aResult)) {
            foreach ($aResult as $result) {
				if ($lang == 'sk') 
					$aLabels[$result["LABEL_ID"]] = $result["LABEL_TRANSLATE"];
				else
					$aLabels[$result["LABEL_ID"]] = $result["LABEL_TRANSLATE"];
            }
        }
		return $aLabels;
    }

    /**
     * M?thode permettant de d?cacher les traductions r?cemment import?es
     * Cette m?thode construit un fichier php disponible dans le r?pertoire Pelican::$config["VAR_ROOT"]/i18n/frontend
     */
    public function generateCacheAction()
    {
        $oConnection = Pelican_Db::getInstance();
        $bDecache = 'KO';
        // R?pertoire o? sont stock?s les fichiers de langue
        if ($this->getParam('tc') == "bo") {
            $sTranslationFolder = Pelican::$config["VAR_ROOT"] . "/i18n/common/";
        } else {
            $sTranslationFolder = Pelican::$config["VAR_ROOT"] . "/i18n/frontend/";
        }
        
        // R?cup?ration des langues disponibles pour le site affich?
        $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $sSQL = "SELECT
					distinct(l.LANGUE_CODE),
					l.LANGUE_LABEL,
					l.LANGUE_ID
				FROM
					#pref#_site_language sl INNER JOIN #pref#_language l
						ON (sl.LANGUE_ID = l.LANGUE_ID)
		";
        $aLangue = $oConnection->queryTab($sSQL, $aBind);
        $sSqlCodePays = $oConnection->queryItem("SELECT SITE_CODE_PAYS FROM #pref#_site_code WHERE SITE_ID = :SITE_ID", array(
            ":SITE_ID" => $_SESSION[APP]['SITE_ID']
        ));
        
        if (is_array($aLangue) && count($aLangue) > 0) {
            foreach ($aLangue as $langue) {
                $aTranslation = $this->getTraduction($langue['LANGUE_CODE'], $this->getParam('tc'));

                if (is_array($aTranslation) && count($aTranslation) > 0) {
                    unset($content);
                    foreach ($aTranslation as $key => $value) {
                        $key = strtr(strtoupper(dropaccent($key)), " ", "_");
                        $content[] = "Pelican::\$lang[\"" . $key . "\"] = \"" . str_replace('"', '\"', $value) . "\";";
                    }
                    
					
                    if ($this->getParam('tc') == "bo") {
                        $langue['LANGUE_CODE'] = $langue['LANGUE_CODE'];
                    } else {
                        $langue['LANGUE_CODE'] = $sSqlCodePays . '-' . $langue['LANGUE_CODE'];
                    }
                    if ($langue['LANGUE_CODE'] != "") {
                        @unlink($sTranslationFolder . $langue['LANGUE_CODE'] . ".php");
                        if (file_put_contents($sTranslationFolder . $langue['LANGUE_CODE'] . '.php', "<?php\n" . implode("\n", $content) . "\n?>")) {
                            $bDecache = 'OK';
                        }
                    }
                }
            }
			
        }
        if ($bDecache) {
            $_SESSION[APP]['DECACHE_TRAD'] = true;
        }
        echo '<script type="text/javascript">location.href = "/_/Index/child?tid=' . Pelican::$config['TEMPLATE_ADMIN_TRADUCTION'] . '&bDecache=' . $bDecache . '&tc=' . $this->getParam('tc') . '"</script>';
    }

    /**
     * Méthode permettant de vérifier l'existence d'une clé, si cette clé existe, une alerte js s'affichera
     */
    public function checkExistenceCleAction($params)
    {
        $oConnection = Pelican_Db::getInstance();
        $iLabelId = $params['labelId'];
        $iLabelIdNew = $params['labelIdNew'];
        $aBind[':LABEL_ID'] = $oConnection->strtoBind($iLabelId);
        $aBind[':LABEL_ID_NEW'] = $oConnection->strtoBind($iLabelIdNew);
        $sSQL = '
			SELECT
				count(*)
			FROM
				#pref#_label l
			WHERE
				UPPER(l.LABEL_ID) = UPPER(:LABEL_ID_NEW)
		';
        if ($iLabelId != - 2 && $iLabelId != "") {
            $sSQL .= " AND UPPER(l.LABEL_ID) <> UPPER(:LABEL_ID)";
        }
        $iCount = $oConnection->queryItem($sSQL, $aBind);
        
        return $iCount;
    }

    /**
     * Génération des csv
     */
    public function generateCsv()
    {
        $oConnection = Pelican_Db::getInstance();
        $aTabLang = array();
        
        // R?cup?ration des langues disponibles pour le site affich?
        $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $sSQL = "SELECT
					l.LANGUE_CODE,
					l.LANGUE_LABEL,
					l.LANGUE_ID,
					sl.SITE_ID
				FROM
					#pref#_site_language sl INNER JOIN #pref#_language l
						ON (sl.LANGUE_ID = l.LANGUE_ID)
				WHERE
					sl.SITE_ID = :SITE_ID
		";
        $aLangue = $oConnection->queryTab($sSQL, $aBind);
        $sSqlCodePays = $oConnection->queryItem("SELECT SITE_CODE_PAYS FROM #pref#_site_code WHERE SITE_ID = :SITE_ID", array(
            ":SITE_ID" => $_SESSION[APP]['SITE_ID']
        ));
        
        $filename = $this->getFilename($aLangue, $sSqlCodePays);
        
        if (is_array($aLangue) && count($aLangue) > 0) {
            foreach ($aLangue as $key => $langue) {
                // Tableau des langues disponibles
                $aTabLang[$langue['LANGUE_ID']] = t(strtoupper(dropaccent($langue['LANGUE_LABEL'])));
                
                // Pour chaque langue disponible sur le site, on r?cup?re le nombre d'entit?s traduites
                $aBindLang[':LANGUE_ID'] = $langue['LANGUE_ID'];
                $aBindLang[':SITE_ID'] = $langue['SITE_ID'];
                $sSql = "	SELECT count(distinct(l.LABEL_ID))
						FROM
							#pref#_label l
						LEFT JOIN #pref#_label_langue ll ON (l.LABEL_ID = ll.LABEL_ID) ";
                if ($this->getParam('tc') == "fo") {
                    $sSql .= " LEFT JOIN #pref#_label_langue_site lls ON (lls.LABEL_ID = ll.LABEL_ID AND lls.SITE_ID = :SITE_ID) ";
                }
                $sSql .= "	WHERE
							";
                if ($this->getParam('tc') == "bo") {
                    $sSql .= "  l.LABEL_BO = 1";
                } else {
                    $sSql .= "  l.LABEL_FO = 1";
                }
                $count = (int) $oConnection->queryItem($sSql, $aBindLang);
                $nbRow = - 1;
                $row = 0;
                $rownum = 50;
                // On va boucler pour ?viter les erreurs de m?moire allou?e, de 50 en 50
                while ($nbRow < $count) {
                    // On r?cup?re les entit?s
                    $sSQL = "SELECT
								l.LABEL_ID,";
                    if ($this->getParam('tc') == "fo") {
                        $sSQL .= " IFNULL(lls.LABEL_TRANSLATE,ll.LABEL_TRANSLATE) LABEL_TRANSLATE";
                    } else {
                        $sSQL .= " ll.LABEL_TRANSLATE, l.LABEL_BO";
                    }
                    $sSQL .= " FROM
								#pref#_label l
					LEFT JOIN #pref#_label_langue ll ON (l.LABEL_ID = ll.LABEL_ID and ll.LANGUE_ID = :LANGUE_ID)";
                    if ($this->getParam('tc') == "fo") {
                        $sSQL .= " LEFT JOIN #pref#_label_langue_site lls ON (lls.LABEL_ID = l.LABEL_ID AND lls.SITE_ID = :SITE_ID and lls.LANGUE_ID = :LANGUE_ID) ";
                        $sSQL .= " WHERE l.LABEL_FO = 1 ";
                    } else {
                        $sSQL .= " WHERE l.LABEL_BO = 1 ";
                    }
                    $sSQL .= "
							order by l.LABEL_ID
							LIMIT " . ($nbRow + 1) . ", " . $rownum;
                    $aTraduction = $oConnection->queryTab($sSQL, $aBindLang);
                    // s'il existe des entit?s pour cette langue on va construire un tableau compos? de la cl? et de la traduction
                    if (! empty($aTraduction)) {
                        $aNewData = array();
                        for ($i = 0; $i < sizeof($aTraduction); $i ++) {
                            $aNewNewData = array();
                            foreach ($aTraduction[$i] as $key => $value) {
                                if (is_string($key)) {
                                    if ("LABEL_ID" == $key) {
                                        $aNewNewData["CLE"] = $value;
                                    } elseif ("LABEL_TRANSLATE" == $key) {
                                        $aNewNewData["TRADUCTION"] = $value;
                                    } elseif ("LABEL_BO" == $key && $value == 1) {
                                        $aNewNewData["BO"] = 1;
                                    }
                                }
                            }
                            $aNewData[$i] = $aNewNewData;
                        }
                        
                        // Si on est ? la premi?re ligne, on inscrit les titres
                        if ($row == 0) {
                            $fp = fopen($filename[$langue['LANGUE_ID']], 'w');
                            fputs($fp, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
                            fputcsv($fp, array_keys($aNewData[0]), ';');
                            $row = 1;
                        } else {
                            $fp = fopen($filename[$langue['LANGUE_ID']], 'a');
                        }
                        
                        // On ?crit les entit?s r?cup?r?es de BDD
                        foreach ($aNewData as $key => $line) {
                            fputcsv($fp, $aNewData[$key], ';');
                        }
                        fclose($fp);
                    }
                    // On incr?mente le compteur de lignes
                    $nbRow = (int) ($nbRow + $rownum);
                }
            }
        }
    }

    /**
     * Génération du tableau du chemin root des fichiers csv
     *
     * @param array $aLangue
     *            Tableau de langue
     * @param string $sCodePays
     *            Code pays courant
     *            
     * @return array tableau des chemins des fichiers csv
     */
    public function getFilename($aLangue, $sCodePays)
    {
        $filename = array();
        
        if (is_array($aLangue) && count($aLangue) > 0) {
            foreach ($aLangue as $langue) {
                // On crée un chemin physique pour chaque langue
                if ($this->getParam('tc') == "bo") {
                    $filename[$langue['LANGUE_ID']] = Pelican::$config["DOCUMENT_INIT"] . '/var/i18n/backend/' . $langue['LANGUE_CODE'] . '.csv';
                } else {
                    $filename[$langue['LANGUE_ID']] = Pelican::$config["DOCUMENT_INIT"] . '/var/i18n/backend/' . $sCodePays . "-" . $langue['LANGUE_CODE'] . '.csv';
                }
            }
        }
        
        return $filename;
    }
}