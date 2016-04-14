<?php
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

/**
 * Controleur gerant les traductions FO et BO (import export)
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
    );

    protected function setListModel()
    {
        $oConnection = Pelican_Db::getInstance();
        $aLangue = $this->getLangues($_SESSION[APP]['SITE_ID']);

        $sLanguage = "";
        if (is_array($aLangue) && count($aLangue) > 0) {
            foreach ($aLangue as $key => $language) {
                if ($key != 0) {
                    $sLanguage .= ",";
                }
                $sLanguage .= $language['LANGUE_ID'];
            }
        }

        $sqlList = "SELECT distinct l.*, l.LABEL_ID LABEL_ID2 FROM #pref#_label l";

        //@TODO optimisation : remplacer le flag BO / FO par and (ll.LABEL_TRANSLATE is NULL or ll.LABEL_TRANSLATE = '')

        if ($this->getParam('tc') == "bo" || $_SESSION[APP]['SITE_ID'] != Pelican::$config['SITE_BO']) {
            // cas traduction BO (site admin)
            // et
            // cas traduction FO via pays (surcharge)
            $sqlList .= " LEFT JOIN #pref#_label_langue_site ll ON (
                                l.LABEL_ID = ll.LABEL_ID
                                AND ll.LANGUE_ID IN (".$sLanguage.")
                                AND ll.SITE_ID = ".$_SESSION[APP]['SITE_ID']."
                                )
                            	";
        } elseif ($_SESSION[APP]['SITE_ID'] == Pelican::$config['SITE_BO']) {
            // cas traduction FO via site admin
            $sqlList .= " LEFT JOIN #pref#_label_langue ll
                            ON (l.LABEL_ID = ll.LABEL_ID and ll.LANGUE_ID IN (".$sLanguage."))
                                ";
        }
        if ($this->getParam('tc') == "bo") {
            $where[] = "l.LABEL_BO = 1";
        } else {
            $where[] = "l.LABEL_FO = 1";
        }
        if (!empty($_GET['filter_LABEL_ID'])) {
            $this->aBind[':LABEL_ID'] = $_GET['filter_LABEL_ID'];
            $where[] = "l.LABEL_ID like '%".str_replace("'", "''", $_GET['filter_LABEL_ID'])."%' ";
        }
        if (!empty($_GET['filter_LABEL_TRANSLATE'])) {
            $this->aBind[':LABEL_TRANSLATE'] = $_GET['filter_LABEL_TRANSLATE'];
            $where[] = "ll.LABEL_TRANSLATE like '%".str_replace("'", "''", $_GET['filter_LABEL_TRANSLATE'])."%'";
        }
        if ($where) {
            $sqlList .= " where ".implode(" and ", $where);
        }

        $sqlList .= " order by ".$this->listOrder;

        if (!empty($_GET['id'])) {
            $this->aBind[":LABEL_ID"] = $oConnection->strToBind($this->id);
        }

        $aTranslate = $oConnection->queryTab($sqlList);
        $this->listModel = $aTranslate;
    }

    protected function setEditModel()
    {
        $oConnection = Pelican_Db::getInstance();
        $this->aBind[":LABEL_ID"] = $oConnection->strToBind($this->id);
        $sSQL = "SELECT * FROM #pref#_label l WHERE l.LABEL_ID=".$this->aBind[":LABEL_ID"]." ";
        $this->editModel = $sSQL;
    }

    public function listAction()
    {
        $form = "";
        $form .= '</table><div id="globalImport" style="width:100%">';
        $form .= $this->getExportHTML($_SESSION[APP]['SITE_ID'], $this->getParam('tc'));
        $form .= $this->getImportHTML($_SESSION[APP]['SITE_ID'], $this->getParam('tc'));

        $form .= '</div>';

        /* generation des constantes de langues en fichier php (begin) */

        if($_SESSION[APP]['PROFIL_LABEL'] !='TRADUCTEUR') {
            $form .= '<form name="fFormCache" id="fFormCache" action="/_/Administration_Traduction/generateCache" method="post">
                    <input name="submitDecache" type="submit" class="button" value="'.t("TRAD_DECACHE").'"/>
                    <input name="tc" type="hidden" value="'.$this->getParam('tc').'"/>
                    </form>';
        }
            /* generation des constantes de langues en fichier php (end) */

        $form .= '<table width="100%">';

        parent::listAction();

        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");

        $table->setFilterField('LABEL_ID', t('ID')." : ");
        $table->setFilterField('LABEL_TRANSLATE', t('LABEL_TRANSLATE')." : ");
        $table->getFilter(2);
        $table->setCSS(array("tblalt1", "tblalt2"));
        $table->setValues($this->getListModel(), "#pref#_label.LABEL_ID");
        $table->addColumn(t('ID'), "LABEL_ID2", "30", "left", "", "tblheader", "LABEL_ID2");

        if ($this->getParam('tc') == "fo") {
            // cas traduction FO via pays (surcharge) : on precise s'il existe traduction generique
            // cas traduction FO depuis site admin : on est concerne par la traduction generique
            $sqlLng = "SELECT
                                    distinct LABEL_ID as \"id\",
                                    LANGUE_CODE as \"lib\"
                            	FROM
                                    #pref#_label_langue ll,
                                    #pref#_language l,
                                    #pref#_site_language sl
                            	WHERE ll.LANGUE_ID = l.LANGUE_ID
                            	AND sl.LANGUE_ID = ll.LANGUE_ID
                            	AND sl.SITE_ID = ".$_SESSION[APP]['SITE_ID'];

            $table->addMulti(t('LANG_LIST_MASTER'), "LABEL_ID", "20", "left", ",", "tblheader", "", $sqlLng);
        }

        if ($this->getParam('tc') == "bo" || $_SESSION[APP]['SITE_ID'] != Pelican::$config['SITE_BO']) {
            // cas traduction BO (site admin)
            // cas traduction FO depuis site pays
            $sqlLngPays = "SELECT
                            distinct LABEL_ID as \"id\",
                            LANGUE_CODE as \"lib\"
                                    FROM
                            #pref#_label_langue_site ll,
                            #pref#_language l,
                            #pref#_site_language sl
                                    WHERE ll.LANGUE_ID = l.LANGUE_ID
                                    AND sl.LANGUE_ID = ll.LANGUE_ID
                                    AND sl.SITE_ID = ".$_SESSION[APP]['SITE_ID'];
            if ($_SESSION[APP]['SITE_ID'] == Pelican::$config['SITE_BO']) {
                $label = t('LANG_LIST_MASTER');
            } else {
                $label = t('LANG_LIST_SITE');
            }
            $table->addMulti($label, "LABEL_ID", "20", "left", ",", "tblheader", "", $sqlLngPays);
        }

        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "LABEL_ID"), "center");
        if($_SESSION[APP]['PROFIL_LABEL'] !='TRADUCTEUR') {
            $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => "LABEL_ID", "" => "readO=true"), "center");
        }
        if ($_SESSION[APP]['SITE_ID'] != Pelican::$config['SITE_BO']) {
            $this->aButton["add"] = "";
            Backoffice_Button_Helper::init($this->aButton);
        }

        // message d'alerte
        if ($_SESSION[APP]['TRAD_MESSAGE_KEY'] != "") {
            $sLib = $_SESSION[APP]['TRAD_MESSAGE_KEY'];
            $_SESSION[APP]['TRAD_MESSAGE_KEY'] = "";
            $form .= '<script type="text/javascript">
                    alert(\''.t($sLib, 'js2').'\');
                </script>';
        }

        $this->setResponse($form.$table->getTable());
    }

    public function editAction()
    {
        $oConnection = Pelican_Db::getInstance();
        parent::editAction();

        $form = $this->startStandardForm();
        $form .= $this->oForm->createLabel(t('LABEL_TRANSLATE'), "");
        $form .= $this->oForm->createHidden("LABEL_ID_SAUVE", $this->values["LABEL_ID"]); // en cas de renommage de clé
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

        if ($_SESSION[APP]['SITE_ID'] != Pelican::$config['SITE_BO']) {
            // nom de cle en lecture seule depuis site pays (surcharge)
            $form .= $this->oForm->createInput("LABEL_ID", t("ID"), 255, "", true, $this->values["LABEL_ID"], true, 75);
            $form .= $this->oForm->createTextarea(
                "LABEL_INFO", t("TRAD_DESC"), false, $this->values["LABEL_INFO"], 500, true, 5, 100
            );
        } else {
            $form .= $this->oForm->createInput(
                "LABEL_ID", t("ID"), 255, "", true, $this->values["LABEL_ID"], $_GET["readO"], 75
            );
            // possibilite de modifier le descriptif de la cle
            $form .= $this->oForm->createTextarea(
                "LABEL_INFO", t("TRAD_DESC"), false, $this->values["LABEL_INFO"], 500, $_GET["readO"], 5, 100
            );
        }

        $this->aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];

        $sSQL = "SELECT lng.*,
            l.LABEL_TRANSLATE LABEL_TRANSLATE_GLOBAL,
            ls.LABEL_TRANSLATE
            FROM #pref#_language lng
            INNER JOIN #pref#_site_language lngs ON (lngs.LANGUE_ID = lng.LANGUE_ID and lngs.SITE_ID = :SITE_ID)
            LEFT JOIN #pref#_label_langue l ON (lng.LANGUE_ID=l.LANGUE_ID AND l.LABEL_ID=:LABEL_ID)
            LEFT JOIN #pref#_label_langue_site ls ON (lng.LANGUE_ID=ls.LANGUE_ID AND ls.LABEL_ID=:LABEL_ID AND ls.SITE_ID = :SITE_ID )
            ORDER BY l.LANGUE_ID ";
        $result = $oConnection->queryTab($sSQL, $this->aBind);
        foreach ($result as $langue) {
            if ($_SESSION[APP]['SITE_ID'] != Pelican::$config['SITE_BO']) {
                // cas pays (surcharge) : on affiche la valeur globale
                $form .= $this->oForm->createLabel(t("TRAD_GLOBAL"), $langue["LABEL_TRANSLATE_GLOBAL"]);
            }
            if ($this->getParam('tc') == "fo" && $_SESSION[APP]['SITE_ID'] == Pelican::$config['SITE_BO']) {
                // cas traduction FO globale (depuis site admin)
                $valueTmp = $langue["LABEL_TRANSLATE_GLOBAL"];
            } else {
                // cas traduction FO surcharge depuis pays
                // cas traduction BO
                $valueTmp = $langue["LABEL_TRANSLATE"];
            }
            $form .= $this->oForm->createInput(
                "LABEL_TRANSLATE_".$langue['LANGUE_ID'], Pelican_Html::img(
                    array(
                        src => '/library/Pelican/Translate/public/images/flags/'.$langue["LANGUE_CODE"].'.png'
                    )
                ).'&nbsp;&nbsp;'.$langue["LANGUE_LABEL"], 500, "text", false, $valueTmp, $_GET["readO"], 75
            );
        }
        $form .= $this->oForm->createHidden("B_PUBLICATION_LABEL", true);
        $form .= $this->oForm->createHidden("SITE_ID", $_SESSION[APP]['SITE_ID']);
        $form .= $this->oForm->createHidden("tc", $this->getParam('tc'));
        if (!$this->readO) {
            $form .= '
                    <script type="text/javascript">
                    function checkingKey()
                    {
                        var labelId = "'.$_GET['id'].'";
                        var labelIdNew = $("#LABEL_ID").val();
                        if (labelId != labelIdNew) {
                            $.ajax({
                                url: "check",
                                data: {
                                    className: "Administration_Traduction_Controller",
                                    method: "checkExistenceCleAction",
                                    labelId: labelId,labelIdNew:labelIdNew},
                                async: false,
                                success: function (data) {
                                    $("#B_PUBLICATION_LABEL").val("true");
                                    if (data > 0) {
                                        $("#B_PUBLICATION_LABEL").val("false");
                                    }
                                }
                            });
                        }
            }
    </script>';

            $form .= $this->oForm->createJs(
                '
                    checkingKey();
                    if ($("#B_PUBLICATION_LABEL").val() == "false") {
                        alert(\''.t('TRAD_KEY_EXISTS', 'js2').'\');
                        fwFocus($("#LABEL_ID"));

                        return false;
                    }'
            );
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
        $oConnection = Pelican_Db::getInstance();
        Pelican_Db::$values["LABEL_ID"] = rtrim(Pelican_Db::$values["LABEL_ID"]);

        $this->aBind[":LABEL_ID"] = $oConnection->strtoBind(Pelican_Db::$values["LABEL_ID"]);
        $this->aBind[":LABEL_ID_SAUVE"] = $oConnection->strtoBind(Pelican_Db::$values["LABEL_ID_SAUVE"]);

        // cas d un renommage de cle depuis le site admin
        if (!empty(Pelican_Db::$values["LABEL_ID_SAUVE"]) && Pelican_Db::$values["LABEL_ID_SAUVE"] != Pelican_Db::$values["LABEL_ID"] && $this->form_action == Pelican_Db::DATABASE_UPDATE) {
            $oConnection->query(
                "UPDATE #pref#_label SET LABEL_ID=:LABEL_ID WHERE LABEL_ID=:LABEL_ID_SAUVE", $this->aBind
            );
            $oConnection->query(
                "UPDATE #pref#_label_langue SET LABEL_ID=:LABEL_ID WHERE LABEL_ID=:LABEL_ID_SAUVE", $this->aBind
            );
            $oConnection->query(
                "UPDATE #pref#_label_langue_site SET LABEL_ID=:LABEL_ID WHERE LABEL_ID=:LABEL_ID_SAUVE", $this->aBind
            );
        }

        if ($this->form_action == Pelican_Db::DATABASE_DELETE) {
            // suppression
            $oConnection->query("DELETE from #pref#_label_langue_site WHERE LABEL_ID=:LABEL_ID", $this->aBind);
            if ($_SESSION[APP]['SITE_ID'] == Pelican::$config['SITE_BO']) {
                $oConnection->query("DELETE from #pref#_label_langue WHERE LABEL_ID=:LABEL_ID", $this->aBind);
                $oConnection->query("DELETE from #pref#_label WHERE LABEL_ID=:LABEL_ID", $this->aBind);
            }
        } else {
            // maj de #pref#_label pour le cas insert et le cas update (car possibilite modification champ de description de la cle)
            $oConnection->updateTable($this->form_action, "#pref#_label");

            // identification de la table selon le cas
            $sTableName = (Pelican_Db::$values['tc'] == "bo" || $_SESSION[APP]['SITE_ID'] != Pelican::$config['SITE_BO']) ? "#pref#_label_langue_site" : "#pref#_label_langue";

            $this->aBind[":SITE_ID"] = Pelican_Db::$values['SITE_ID'];
            $sSQL = "DELETE FROM ".$sTableName." WHERE LABEL_ID = :LABEL_ID";
            if ($this->getParam('tc') == 'fo' && $_SESSION[APP]['SITE_ID'] != Pelican::$config['SITE_BO']) {
                $sSQL .= " AND SITE_ID = :SITE_ID";
            }
            $oConnection->query($sSQL, $this->aBind);

            $aLangue = $this->getLangues($_SESSION[APP]['SITE_ID']);
            $langueSauve = Pelican_Db::$values['LANGUE_ID'];
            foreach ($aLangue as $langue) {
                Pelican_Db::$values["LABEL_TRANSLATE"] = Pelican_Db::$values["LABEL_TRANSLATE_".$langue['LANGUE_ID']];
                Pelican_Db::$values['LANGUE_ID'] = $langue['LANGUE_ID'];
                if (Pelican_Db::$values['LABEL_TRANSLATE'] != '') {
                    $oConnection->updateTable(Pelican_Db::DATABASE_INSERT, $sTableName);
                }
            }
            Pelican_Db::$values['LANGUE_ID'] = $langueSauve;
        }
    }

    /**
     * Methode permettant de lancer l'import de nouvelles traductions
     * a partir d'un fichier et d'une langue selectionnee recuperes en POST
     * Si l'import s'est deroule correctement on redirige avec un bool a true
     */
    public function generateImportFileAction()
    {
        $oConnection = Pelican_Db::getInstance();

        $bCSV = Backoffice_File_Helper::isCSV($_FILES['FILE_TRAD_IMPORT']['type']);

        if (isset($_FILES['FILE_TRAD_IMPORT']['tmp_name']) && $bCSV == true && $_FILES['FILE_TRAD_IMPORT']['error'] == UPLOAD_ERR_OK) {
            // Construction du chemin ou le fichier va etre depose
            $sCheminDestination = Pelican::$config["DOCUMENT_INIT"].'/var/i18n/backend/';

            $LABEL_FO = null;
            $LABEL_BO = null;
            if ($_POST["tc"] == 'fo') {
                $LABEL_FO = 1;
            } else {
                $LABEL_BO = 1;
            }
            $site_id = $_POST["site_id"];
            $langue_id = $_POST["langue_id"];

            // Depot (et renommage) du fichier dans le repertoire
            $dateTimeCurrent = new DateTime();
            $sDateTimeCurrent = $dateTimeCurrent->format('YmdHi');
            $siteCode = "";
            if ($_POST["tc"] == 'fo' && $_POST["site_id"] == Pelican::$config['SITE_BO']) {
                $siteCode .= 'global_fo';
            } else {
                $rs = $oConnection->queryItem(
                    "SELECT SITE_CODE_PAYS FROM #pref#_site_code WHERE SITE_ID = :SITE_ID", array(":SITE_ID" => $site_id)
                );
                $siteCode .= $rs;
            }

            $langueCode = $oConnection->queryItem(
                "SELECT LANGUE_CODE FROM #pref#_language l WHERE l.LANGUE_ID = :LANGUE_ID", array(":LANGUE_ID" => $langue_id)
            );
            $filename = $siteCode.'-'.$langueCode.'_'.$sDateTimeCurrent.'.csv';
            if (!file_exists(Pelican::$config['TRANSLATION_IMPORT'])) {
                mkdir(Pelican::$config['TRANSLATION_IMPORT']);
            }

            $pathfilename = Pelican::$config['TRANSLATION_IMPORT'].$filename;
            move_uploaded_file($_FILES['FILE_TRAD_IMPORT']['tmp_name'], $pathfilename);

            // construction du tableau des datas a importer
            if (file_exists($pathfilename)) {
                $aClesaTraiter = array();
                $fields = array();
                $i = 0;
                $handle = @fopen($pathfilename, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 4096, ";", '"')) !== false) {
                        if (empty($fields)) {
                            $fields = $row;
                            continue;
                        }
                        foreach ($row as $k => $value) {
                            $aClesaTraiter[$i][$fields[$k]] = $value;
                        }
                        $i++;
                    }
                    if (!feof($handle)) {
                        echo "Error: unexpected fgets() fail\n";
                    }
                    fclose($handle);
                }
            }

            // on met à jour la bdd
            if (count($aClesaTraiter) > 0) {
                $aBind[":LANGUE_ID"] = $langue_id;
                $aBind[":SITE_ID"] = $site_id;

                // Recuperation de toutes les entites traduites
                $sSQL = "SELECT LABEL_ID FROM #pref#_label";
                $rs = $oConnection->query($sSQL, $aBind);
                $aLabel = $oConnection->data["LABEL_ID"];

                if ($_POST["tc"] == "bo" || $site_id != Pelican::$config['SITE_BO']) {
                    // Recuperation de toutes les entites traduites surchargee pour une langue et un site
                    $sSQL = "SELECT LABEL_ID FROM #pref#_label_langue_site WHERE LANGUE_ID = :LANGUE_ID AND SITE_ID = :SITE_ID";
                    $rs = $oConnection->query($sSQL, $aBind);
                    $aLabelLangueSite = $oConnection->data["LABEL_ID"];
                } elseif ($site_id == Pelican::$config['SITE_BO']) {
                    // Recuperation de toutes les entites traduites globale pour une langue
                    $sSQL = "SELECT LABEL_ID FROM #pref#_label_langue WHERE LANGUE_ID = :LANGUE_ID";
                    $rs = $oConnection->query($sSQL, $aBind);
                    $aLabelLangue = $oConnection->data["LABEL_ID"];
                }

                foreach ($aClesaTraiter as $cle) {
                    Pelican_Db::$values = $cle;
                    // Pelican_Db::$values["LABEL_TRANSLATE"] = utf8_encode(Pelican_Db::$values["LABEL_TRANSLATE"]);
                    Pelican_Db::$values["LABEL_BO"] = $LABEL_BO;
                    Pelican_Db::$values["LABEL_FO"] = $LABEL_FO;
                    Pelican_Db::$values["SITE_ID"] = $site_id;
                    Pelican_Db::$values["LANGUE_ID"] = $langue_id;

                    // modif dans #pref#_label
                    $action = "";
                    if (in_array($cle["LABEL_ID"], $aLabel)) {
                        // si la cle existe deja, on ne fait la maj que si LABEL_INFO non vide
                        if ($cle["LABEL_INFO"] != "") {
                            $action = Pelican_Db::DATABASE_UPDATE;
                        }
                    } else {
                        $action = Pelican_Db::DATABASE_INSERT;
                    }
                    if ($action != "") {
                        $oConnection->setExitOnError(false);
                        $oConnection->updateTable($action, "#pref#_label");
                        $oConnection->commit();
                        $oConnection->setExitOnError(true);
                    }

                    $action = "";
                    $table = "";
                    if ($_POST["tc"] == "bo" || $site_id != Pelican::$config['SITE_BO']) {
                        // cas traduction BO (site admin)
                        // et
                        // cas traduction FO via pays (surcharge)
                        $table = "#pref#_label_langue_site";
                        if (in_array($cle["LABEL_ID"], $aLabelLangueSite)) {
                            $action = Pelican_Db::DATABASE_UPDATE;
                        } else {
                            $action = Pelican_Db::DATABASE_INSERT;
                        }
                    } elseif ($site_id == Pelican::$config['SITE_BO']) {
                        // cas traduction FO via site admin
                        $table = "#pref#_label_langue";
                        if (in_array($cle["LABEL_ID"], $aLabelLangue)) {
                            $action = Pelican_Db::DATABASE_UPDATE;
                        } else {
                            $action = Pelican_Db::DATABASE_INSERT;
                        }
                    }

                    if ($action != "" && $table != "") {
                        $oConnection->setExitOnError(false);
                        $oConnection->updateTable($action, $table);
                        $oConnection->commit();
                        $oConnection->setExitOnError(true);
                    }
                }
            }
            $_SESSION[APP]['TRAD_MESSAGE_KEY'] = "ALERT_IMPORT_SUCCES";
        } else {
            $_SESSION[APP]['TRAD_MESSAGE_KEY'] = "ALERT_IMPORT_ECHEC";
        }
        echo '<script type="text/javascript">location.href = "/_/Index/child?tid='.Pelican::$config['TEMPLATE_ADMIN_TRADUCTION'].'&tc='.$this->getParam(
            'tc'
        ).'"</script>';
    }

    public function getTraduction($langue_id, $tc, $site_id = '', $forCsv = false)
    {
        $oConnection = Pelican_Db::getInstance();
        if ($site_id == '') {
            $site_id = $_SESSION[APP]['SITE_ID'];
        }
        $aBind[":LANGUE_ID"] = $langue_id;
        $aBind[":SITE_ID"] = $site_id;

        $sSql = "
              SELECT
              distinct(l.LABEL_ID),
                l.LABEL_INFO,
                IFNULL(lls.LABEL_TRANSLATE,ll.LABEL_TRANSLATE) LABEL_TRANSLATE
                FROM
                #pref#_label l
                LEFT JOIN #pref#_label_langue ll ON (l.LABEL_ID = ll.LABEL_ID and ll.LANGUE_ID = :LANGUE_ID)
                LEFT JOIN #pref#_label_langue_site lls ON (l.LABEL_ID = lls.LABEL_ID and lls.LANGUE_ID = :LANGUE_ID AND lls.SITE_ID = :SITE_ID)
              ";
        if ($tc == "bo") {
            $sSql .= " WHERE l.LABEL_BO = 1 ";
        } else {
            $sSql .= " WHERE l.LABEL_FO = 1 ";
        }

        $sSql .= " order by l.LABEL_ID";

        $aResult = $oConnection->queryTab($sSql, $aBind);

        if (is_array($aResult)) {
            if ($forCsv) {
                $aLabels = $aResult;
            } else {
                foreach ($aResult as $result) {
                    $aLabels[$result["LABEL_ID"]] = $result["LABEL_TRANSLATE"];
                }
            }
        }

        return $aLabels;
    }

    /**
     * Methode permettant de decacher les traductions recemment importees
     * Cette methode construit un fichier php disponible dans le repertoire Pelican::$config['TRANSLATION_ROOT'] pour
     * le BO Cette methode construit un fichier php disponible dans le repertoire
     * Pelican::$config["TRANSLATION_ROOT_FO"] pour les FO
     */
    public function generateCacheAction()
    {
        $oConnection = Pelican_Db::getInstance();

        $_SESSION[APP]['TRAD_MESSAGE_KEY'] = "ALERT_DECACHE_ECHEC";

        // Recuperation des langues disponibles pour le site (prerequis : le site admin doit contenir toutes les langues des autres sites)
        $aLangue = $this->getLangues($_SESSION[APP]['SITE_ID']);


        if ($this->getParam('tc') == 'fo' && $_SESSION[APP]['SITE_ID'] == Pelican::$config['SITE_BO']) {
            // cas traduction de tous les FO (en tenant compte des surcharges)
            $aPays = $oConnection->queryTab(
                "SELECT SITE_ID, SITE_CODE_PAYS FROM #pref#_site_code WHERE SITE_ID!=".Pelican::$config['SITE_BO']
            );
        } else {
            // traduction du BO
            // traduction du FO courant (en tenant compte des surcharges)
            $aPays = $oConnection->queryTab(
                "SELECT SITE_ID, SITE_CODE_PAYS FROM #pref#_site_code WHERE SITE_ID=:SITE_ID", array(
                ":SITE_ID" => $_SESSION[APP]['SITE_ID']
                )
            );
        }
        if (is_array($aPays) && count($aPays) > 0) {
            foreach ($aPays as $pays) {
                if (is_array($aLangue) && count($aLangue) > 0) {
                    foreach ($aLangue as $langue) {
                        $aTranslation = $this->getTraduction(
                            $langue['LANGUE_ID'], $this->getParam('tc'), $pays["SITE_ID"]
                        );
                        if (is_array($aTranslation) && count($aTranslation) > 0) {
                            unset($content);
                            foreach ($aTranslation as $key => $value) {
                                $key = strtr(strtoupper(dropaccent($key)), " ", "_");
                                $content[] = $this->addTradFileLine($key, $value, $this->getParam('tc'));
                            }

                            if ($langue['LANGUE_CODE'] != "") {
                                if ($this->getParam('tc') == 'fo') {

                                    $_SESSION[APP]['TRAD_MESSAGE_KEY'] = "ALERT_DECACHE_SUCCES";
                                    // cas projet NDP, fichier nomme selon standard symfony
                                    $dbFileTranslationName = Pelican::$config['TRANSLATION_ROOT_FO'].$pays['SITE_ID'].'.'.$langue['LANGUE_CODE'].'.db';
                                    $fs = new Filesystem($dbFileTranslationName);
                                    if (!$fs->exists(Pelican::$config['TRANSLATION_ROOT_FO'])) {
                                        $fs->mkdir(Pelican::$config['TRANSLATION_ROOT_FO']);
                                    }

                                    try {
                                        $fs->touch($dbFileTranslationName);
                                        $this->getContainer()->get('sonata.cache.symfony')->flush(array('translations'));
                                    } catch (IOExceptionInterface $e) {

                                        $_SESSION[APP]['TRAD_MESSAGE_KEY'] = 'NDP_ERROR_CREATE_TRAD_CACHE';
                                    }
                                    //supression de tous les cache de strategy pour prendre en compte les langues
                                    /** @todo for better cache remove only strateg with translation :) */
                                    /** @var \Itkg\CombinedHttpCache\Client\RedisClient $redisCache */
                                    $redisCache = Pelican_Application::getContainer()->get('psa_ndp.cache.redis');
                                    /** @var \PsaNdp\MappingBundle\Manager\PsaTagManager $tagManager */
                                    $tagManager = Pelican_Application::getContainer()->get('open_orchestra_base.manager.tag');

                                    $redisCache->removeKeysFromTags([
                                        $tagManager->formatKeyIdTag('type', 'block'),
                                        $tagManager->formatSiteIdTag($pays['SITE_ID']),
                                        $tagManager->formatLanguageTag($langue['LANGUE_CODE']),
                                    ]);
                                } else {
                                    $filePath = Pelican::$config['TRANSLATION_ROOT'].$pays["SITE_CODE_PAYS"].'-'.$langue['LANGUE_CODE'].".php";
                                    $fileContent = "<?php\n".implode("\n", $content)."\n?>";

                                    @unlink($filePath);

                                    if (file_put_contents($filePath, $fileContent)) {
                                        $_SESSION[APP]['TRAD_MESSAGE_KEY'] = "ALERT_DECACHE_SUCCES";
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        echo '<script type="text/javascript">location.href = "/_/Index/child?tid='.Pelican::$config['TEMPLATE_ADMIN_TRADUCTION'].'&tc='.$this->getParam(
            'tc'
        ).'"</script>';
    }

    /**
     * Méthode ajoutant une ligne dans le fichier de traduction
     */
    public function addTradFileLine($key, $value, $type)
    {
        if ($type == 'fo') {
            $rs = "    '".$key."' => '".str_replace("'", "\\'", $value)."'";
        } else {
            $rs = "Pelican::\$lang[\"".$key."\"] = \"".str_replace('"', '\"', $value)."\";";
        }

        return $rs;
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
        $sSQL = 'SELECT count(*) FROM #pref#_label l WHERE UPPER(l.LABEL_ID) = UPPER(:LABEL_ID_NEW)';
        $iCount = $oConnection->queryItem($sSQL, $aBind);

        return $iCount;
    }

    /**
     * Recuperation des langues d'un site
     */
    public function getLangues($site_id)
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $site_id;
        $sSQL = "SELECT
                    l.LANGUE_CODE,
                    l.LANGUE_LABEL,
                    l.LANGUE_ID,
                    sl.SITE_ID
                  FROM
                    #pref#_site_language sl INNER JOIN #pref#_language l ON (sl.LANGUE_ID = l.LANGUE_ID)
                  WHERE
                    sl.SITE_ID = :SITE_ID
                ";
        $aLangue = $oConnection->queryTab($sSQL, $aBind);

        return $aLangue;
    }

    /**
     * Génération du code d'import
     *
     * @param int $site_id
     *
     * @return string html
     */
    public function getImportHTML($site_id, $tc)
    {
        $oConnection = Pelican_Db::getInstance();

        // Recuperation des langues disponibles pour le site courant
        $aLangue = $this->getLangues($site_id);
        $html = "";
        $isChecked = false;
        if (is_array($aLangue) && count($aLangue) > 0) {
            $html .= '<div id="importFo" style="float:left;width:50%;">';
            $html .= '<span style="font-weight:bold;">'.Pelican_Html::b(
                    strtoupper(t('TRAD_IMPORT'))
                ).'</span>'.Pelican_Html::br().Pelican_Html::br();
            $html .= '<form name="fFormImport" id="fFormImport" action="/_/Administration_Traduction/generateImportFile" method="post" onSubmit="return checkImport();" enctype="multipart/form-data">';
            $html .= '<input type="hidden" name="MAX_FILE_SIZE" value="2097152">';
            $html .= '<input type="hidden" value="'.$site_id.'" id="site_id" name="site_id"/>';
            $html .= '<input type="hidden" value="'.$tc.'" id="tc" name="tc" />';
            $html .= '<input type="file" name="FILE_TRAD_IMPORT" id="FILE_TRAD_IMPORT" size="40" /><br/>';

            foreach ($aLangue as $key => $langue) {
                $checked = ($isChecked == false) ? "checked" : "";
                $isChecked = true;
                $html .= '<input type="radio" name="langue_id" value="'.$langue["LANGUE_ID"].'" '.$checked.'/>'.$langue["LANGUE_LABEL"].''.Pelican_Html::br(
                );
            }
            $html .= Pelican_Html::br().'<input name="submitUpload" type="submit" class="button" value="'.t(
                    'TRAD_IMPORT'
                ).'"/>';
            $html .= '</form>';
            $html .= '<script type="text/javascript">
                    function checkImport()
                    {
                           var sFichier = $("input[name=FILE_TRAD_IMPORT]").val();
                           if (sFichier == "") {
                               alert(\''.t('TRAD_CHOOSE_FILE_WARNING', 'js2').'\');
                               return false;
                           }
                       }
                       </script>';
            $html .= '</div>';
        }

        return $html;
    }

    /**
     * Génération du code d'export
     *
     * @param int $site_id
     *
     * @return string html
     */
    public function getExportHTML($site_id, $tc)
    {
        $oConnection = Pelican_Db::getInstance();
        $html = "";
        $aLangue = $this->getLangues($site_id);

        if (is_array($aLangue) && count($aLangue) > 0) {
            $html .= '<div id="exportFo" style="float:left;width:50%;">';
            $html .= '<span style="font-weight:bold;">'.Pelican_Html::b(
                    strtoupper(t('TRAD_EXPORT'))
                ).'</span>'.Pelican_Html::br().Pelican_Html::br();
            $html .= '<form name="fFormExport" id="fFormExport" action="/_/Administration_Traduction/generateExportFile" method="post">';
            $html .= '<input type="hidden" value="" id="langue_id" name="langue_id"/>';
            $html .= '<input type="hidden" value="'.$site_id.'" id="site_id" name="site_id"/>';
            $html .= '<input type="hidden" value="'.$tc.'" id="tc" name="tc" />';

            foreach ($aLangue as $key => $langue) {
                $langue_id = $langue['LANGUE_ID'];
                $html .= '<a href="javascript://" onClick="document.getElementById(\'fFormExport\').elements[\'langue_id\'].value='.$langue_id.';$(\'#fFormExport\').submit();">'.$langue['LANGUE_LABEL'].'</a>'.Pelican_Html::br(
                );
            }
            $html .= '</form>';
            $html .= '</div>';
        }

        return $html;
    }

    /**
     * Génération du fichier d'export
     *
     * @return file fichier csv
     */
    public function generateExportFileAction()
    {
        $oConnection = Pelican_Db::getInstance();

        $siteCode = "";
        if ($_POST["tc"] == 'fo' && $_POST["site_id"] == Pelican::$config['SITE_BO']) {
            $siteCode .= 'global_fo';
        } else {
            $rs = $oConnection->queryItem(
                "SELECT SITE_CODE_PAYS FROM #pref#_site_code WHERE SITE_ID = :SITE_ID", array(":SITE_ID" => $_POST["site_id"])
            );
            $siteCode .= $rs;
        }

        $langueCode = $oConnection->queryItem(
            "SELECT LANGUE_CODE FROM #pref#_language l WHERE l.LANGUE_ID = :LANGUE_ID", array(":LANGUE_ID" => $_POST['langue_id'])
        );
        $dateTimeCurrent = new DateTime();
        $sDateTimeCurrent = $dateTimeCurrent->format('YmdHi');

        $filename = $siteCode.'-'.$langueCode.'_'.$sDateTimeCurrent.'.csv';
        $pathfilename = Pelican::$config['TRANSLATION_EXPORT'].$filename;

        $aTranslation = $this->getTraduction($_POST['langue_id'], $_POST["tc"], $_POST["site_id"], true);
        if (is_array($aTranslation) && count($aTranslation) > 0) {
            try {
                if (!file_exists(Pelican::$config['TRANSLATION_EXPORT'])) {
                    mkdir(Pelican::$config['TRANSLATION_EXPORT']);
                }
                $fp = fopen($pathfilename, 'w');
                if (!$fp) {
                    throw new Exception(t("ERROR_DOWNLOAD"));
                }
            } catch (Exception $e) {
                echo t("ERROR_DOWNLOAD");
                exit();
            }
            fputcsv($fp, array_keys($aTranslation[0]), ';');
            foreach ($aTranslation as $item) {
                fputcsv($fp, $item, ';');
            }
            fclose($fp);
        }

        if (file_exists($pathfilename)) {
            header('Content-Type: application/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename='.$filename.'');
            header('Pragma: no-cache');
            readfile($pathfilename);
            exit();
        } else {
            echo t("ERROR_DOWNLOAD");
            exit();
        }
    }
}
