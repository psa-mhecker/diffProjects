<?php
include (Pelican::$config["PLUGIN_ROOT"] . '/formbuilder/library/FormBuilder.php');

/**
 * Formulaire de gestion des Formulaires
 *
 * @package Pelican_BackOffice
 * @subpackage Administration
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 15/01/2014
 */
class FormBuilder_Administration_Module_Controller extends Pelican_Controller_Back
{

    protected $administration = true;

    protected $form_name = "formbuilder";

    protected $field_id = "FORMBUILDER_ID";

    protected $defaultOrder = "FORMBUILDER_LABEL";

    protected $processus = array(
        "#pref#_formbuilder",
        array(
            "method",
            "FormBuilder_Administration_Module_Controller::saveMail"
        )
    );

    protected $decacheBack = array(
        array(
            "FormBuilder",
            "FORMBUILDER_ID"
        ),
        array(
            "FormBuilder/Mail",
            "FORMBUILDER_ID"
        )
    );

    protected function setListModel ()
    {
        $this->listModel = "SELECT * FROM #pref#_formbuilder where SITE_ID=" . $_SESSION[APP]['SITE_ID'] . " order by " . $this->listOrder;
    }

    protected function setEditModel ()
    {
        if (empty($_GET['idvalues'])) {
            $this->editModel = "SELECT * from #pref#_formbuilder WHERE FORMBUILDER_ID='" . $this->id . "'";
        } else {
            $this->editModel = "SELECT * from #pref#_formbuilder_value WHERE FORMBUILDER_VALUE_ID='" . $_GET['idvalues'] . "'";
        }
    }

    public function listAction ()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setFilterField("search_keyword", "<b>" . t('RECHERCHER') . " :</b>", "FORMBUILDER_LABEL");
        $table->getFilter(1);
        $table->setCSS(array(
            "tblalt1",
            "tblalt2"
        ));
        $table->setValues($this->getListModel(), "FORMBUILDER_ID");
        $table->addColumn(t('FORMBUILDER_ID'), "FORMBUILDER_ID", "10", "left", "", "tblheader");
        $table->addColumn(t('FORMBUILDER_LABEL'), "FORMBUILDER_LABEL", "50", "left", "", "tblheader");
        /*
         * @TODO $table->addColumn(t('FORMBUILDER_MODE'), "FORMBUILDER_MODE", "30", "left", "", "tblheader"); $table->addInput(t('FORMBUILDER_VALUES'), "button", array( "id" => "FORMBUILDER_ID", "" => "values=true" ), "center");
         */
        $table->addInput(t('FORMBUILDER_EDIT'), "button", array(
            "id" => "FORMBUILDER_ID"
        ), "center");
        $table->addInput(t('FORMBUILDER_DEL'), "button", array(
            "id" => "FORMBUILDER_ID",
            "" => "readO=true"
        ), "center");
        $this->setResponse($table->getTable());
    }

    public function editAction ()
    {
        if (! empty($_GET['values'])) {
            if (! empty($_GET['idvalues'])) {
                $this->_forward('editvalues');
            } else {
                $this->_forward('listvalues');
            }
        } else {
            parent::editAction();
            
            $form = $this->startStandardForm();
            
            foreach (Pelican::$config['FORMBUILDER']['MAIL']['TYPE']['ID'] as $type) {
                $this->oForm->setTab("1_" . $type, t('FORMBUILDER_TAB_EMAIL') . ' : ' . t($type));
            }
            $this->oForm->setTab("2", t('FORMBUILDER_TAB_EXTENDED_EMAIL'));
            $this->oForm->setTab("3", t('FORMBUILDER_TAB_CSS'));
            
            $oConnection = Pelican_Db::getInstance();
            $languages = array(
                'vi',
                'tr',
                'sv',
                'ru',
                'ro',
                'pt',
                'pl',
                'no',
                'nl',
                'lt',
                'ja',
                'it',
                'id',
                'hu',
                'hr',
                'he',
                'fr',
                'fi',
                'fa',
                'et',
                'es',
                'en',
                'el',
                'de',
                'da',
                'cz',
                'ca'
            );
            $strSQLList = "SELECT langue_id as id, " . $oConnection->getConcatClause(array(
                "langue_label",
                "' ('",
                "langue_translate",
                "')'"
            )) . " as lib
                FROM #pref#_language
                WHERE LOWER(langue_code) in ('" . implode("','", $languages) . "')
                ORDER BY lib";
            
            $form .= $this->oForm->createHidden($this->field_id, $this->id);
            $form .= $this->oForm->createHidden('SITE_ID', (! empty($this->values["SITE_ID"]) ? $this->values["SITE_ID"] : $_SESSION[APP]['SITE_ID']));
            $form .= $this->oForm->createHidden('FORMBUILDER_STRUCTURE', rawurldecode($this->values["FORMBUILDER_STRUCTURE"]));
            
            $form .= $this->oForm->beginFormTable();
            $form .= $this->oForm->createInput("FORMBUILDER_LABEL", t('FORMBUILDER_NAME'), 100, "", true, $this->values["FORMBUILDER_LABEL"], $this->readO, 100);
            $form .= $this->oForm->createComboFromSql($oConnection, 'LANGUE_ID', t('FORMBUILDER_LANGUAGE'), $strSQLList, (isset($this->values['LANGUE_ID']) ? $this->values['LANGUE_ID'] : ''), true, $this->readO);
            $form .= Pelican_Html::tr(array(), Pelican_Html::td(array(), "&nbsp;") . Pelican_Html::td(array(), $this->oForm->createButton('FORMBUILDER_EDITOR', t('FORMBUILDER_EDITOR'), "javascript:window.open('/_/module/formbuilder/FormBuilder_Administration_Module/editor');")));
            $form .= $this->oForm->endFormTable();
            $form .= ("<br />");
            /*
             * $form .= $this->oForm->createComboFromList("FORMBUILDER_MODE", t('FORMBUILDER_MODE'), array( 'mail' => 'Mail', 'database' => 'Database' ), $this->values["FORMBUILDER_MODE"], true, $this->readO);
             */
            
            $form .= $this->oForm->createHidden('FORMBUILDER_MODE', 'mail');
            
            $paramMail = array();
            if ($this->values["FORMBUILDER_ID"]) {
                $mail = $oConnection->queryTab('select * from #pref#_formbuilder_mail where FORMBUILDER_ID=' . $this->values["FORMBUILDER_ID"] . ' ORDER BY FORMBUILDER_MAIL_TYPE');
                // prepare de resulset to be read in the order of the declaration of the property $mailType
                if (is_array($mail)) {
                    foreach ($mail as $detailMail) {
                        $paramMail[$detailMail['FORMBUILDER_MAIL_TYPE']] = $detailMail;
                    }
                }
                $iMail = 0;
            }
            
            // read resulset in the order of the property $mailType
            foreach (Pelican::$config['FORMBUILDER']['MAIL']['TYPE']['ID'] as $type) {
                
                // resultset of email params for this type
                $valueMail = $paramMail[$type];
                
                // does fields exp, det, subject have to be mandatory (defined in the property $mailTypeMandatory) ?
                if (! empty(Pelican::$config['FORMBUILDER']['MAIL']['TYPE']['MANDATORY'])) {
                    $mandatory = (in_array($type, Pelican::$config['FORMBUILDER']['MAIL']['TYPE']['MANDATORY']));
                }
                
                // tab definition
                $form .= $this->oForm->beginTab("1_" . $type);
                
                // multi object prefixe
                $this->multi = "multi" . $iMail . "_";
                $iMail ++;
                
                // multi object dedicated fields
                $form .= $this->oForm->createhidden($this->multi . "multi_display", 1);
                $form .= $this->oForm->createhidden($this->multi . "FORMBUILDER_MAIL_TYPE", $type);
                
                // if default value defined in the property $mailTypeDefaultExp : hidden field
                if (! empty(Pelican::$config['FORMBUILDER']['MAIL']['TYPE']['DEFAULT'][$type]['FORMBUILDER_MAIL_EXP'])) {
                    $form .= $this->oForm->createhidden($this->multi . "FORMBUILDER_MAIL_EXP", Pelican::$config['FORMBUILDER']['MAIL']['TYPE']['DEFAULT'][$type]['FORMBUILDER_MAIL_EXP']);
                } else {
                    $form .= $this->oForm->createInput($this->multi . "FORMBUILDER_MAIL_EXP", t('FORMBUILDER_MAIL_EXP'), 320, "mail", $mandatory, $valueMail["FORMBUILDER_MAIL_EXP"], $this->readO, 100);
                }
                
                // if default value defined in the property $mailTypeDefaultExp : hidden field
                if (! empty(Pelican::$config['FORMBUILDER']['MAIL']['TYPE']['DEFAULT'][$type]['FORMBUILDER_MAIL_DEST'])) {
                    $form .= $this->oForm->createhidden($this->multi . "FORMBUILDER_MAIL_DEST", Pelican::$config['FORMBUILDER']['MAIL']['TYPE']['DEFAULT'][$type]['FORMBUILDER_MAIL_DEST']);
                } else {
                    $form .= $this->oForm->createInput($this->multi . "FORMBUILDER_MAIL_DEST", t('FORMBUILDER_MAIL_DEST'), 320, "mail", $mandatory, $valueMail["FORMBUILDER_MAIL_DEST"], $this->readO, 100);
                }
                
                $form .= $this->oForm->createTextArea($this->multi . "FORMBUILDER_MAIL_CC", t('FORMBUILDER_MAIL_CC'), false, $valueMail["FORMBUILDER_MAIL_CC"], "", $this->readO, 2, 100);
                $form .= $this->oForm->createTextArea($this->multi . "FORMBUILDER_MAIL_CCI", t('FORMBUILDER_MAIL_CCI'), false, $valueMail["FORMBUILDER_MAIL_CCI"], "", $this->readO, 2, 100);
                $form .= $this->oForm->createInput($this->multi . "FORMBUILDER_MAIL_SUBJECT", t('FORMBUILDER_MAIL_SUBJECT'), 255, "", $mandatory, $valueMail["FORMBUILDER_MAIL_SUBJECT"], $this->readO, 100);
                
                // attachment
                $attachment = array();
                if ($this->values["FORMBUILDER_ID"]) {
                    $attachment = $oConnection->queryTab('select * from #pref#_formbuilder_mail_media where FORMBUILDER_ID=' . $this->values["FORMBUILDER_ID"] . " AND FORMBUILDER_MAIL_TYPE='" . $type . "' ORDER BY FORMBUILDER_MAIL_MEDIA_ORDER");
                }
                for ($i = 0; $i < Pelican::$config['FORMBUILDER']['MAIL']['ATTACHMENT_NUMBER']; $i ++) {
                    $form .= $this->oForm->createFile($this->multi . "MEDIA_ID" . "_" . $i, t('FORMBUILDER_MAIL_FILE') . ' (' . ($i + 1) . ')', false, "", $attachment[$i]["MEDIA_ID"], $this->readO);
                }
                
                // body
                $form .= $this->oForm->createRadioFromList($this->multi . "FORMBUILDER_MAIL_BODY_TYPE", t("FORMBUILDER_MAIL_BODY_TYPE"), array(
                    "html" => t('FORMBUILDER_MAIL_BODY_TYPE_HTML'),
                    "text" => t('FORMBUILDER_MAIL_BODY_TYPE_TEXT')
                ), (! empty($valueMail["FORMBUILDER_MAIL_BODY_TYPE"]) ? $valueMail["FORMBUILDER_MAIL_BODY_TYPE"] : 'text'), "", $this->readO);
                
                $form .= $this->oForm->createEditor($this->multi . "FORMBUILDER_MAIL_BODY_HTML", t('FORMBUILDER_MAIL_BODY_HTML'), false, $valueMail["FORMBUILDER_MAIL_BODY_HTML"], $this->readO, true, "", 500, 150);
                $form .= $this->oForm->createTextArea($this->multi . "FORMBUILDER_MAIL_BODY_TEXT", t('FORMBUILDER_MAIL_BODY_TEXT'), false, $valueMail["FORMBUILDER_MAIL_BODY_TEXT"], "", $this->readO, 10, 78);
            }
            $form .= $this->oForm->beginTab("2");
            $form .= $this->oForm->createInput("FORMBUILDER_SMTP_HOST", t('FORMBUILDER_SMTP_HOST'), 100, "", false, $this->values["FORMBUILDER_SMTP_HOST"], $this->readO, 100);
            $form .= $this->oForm->createInput("FORMBUILDER_SMTP_USER", t('FORMBUILDER_SMTP_USER'), 100, "", false, $this->values["FORMBUILDER_SMTP_USER"], $this->readO, 100);
            $form .= $this->oForm->createInput("FORMBUILDER_SMTP_PWD", t('FORMBUILDER_SMTP_PWD'), 100, "", false, $this->values["FORMBUILDER_SMTP_PWD"], $this->readO, 100);
            
            $form .= $this->oForm->beginTab("3");
            
            // css classes declarations
            if (empty($this->values["FORMBUILDER_CSS"])) {
                $typeCss = array(
                    "form",
                    "title",
                    "required",
                    "text",
                    "number",
                    "textarea",
                    "checkbox",
                    "radio",
                    "select",
                    "section",
                    "page",
                    "date",
                    "email",
                    "phone",
                    "url",
                    "civility",
                    "iban",
                    "captcha",
                    "p",
                    "submit"
                );
                $aCss[] = ".formbuilder {
}";
                foreach ($typeCss as $css) {
                    $aCss[] = ".formbuilder-" . $css . " {
}";
                }
                $this->values["FORMBUILDER_CSS"] = implode("\n\n", $aCss);
            }
            $form .= $this->oForm->createTextArea("FORMBUILDER_CSS", t('FORMBUILDER_CSS'), false, $this->values["FORMBUILDER_CSS"], "", $this->readO, 80, 150);
            
            $form .= $this->oForm->endTab();
            $form .= $this->oForm->createhidden("increment_formbuilder_mail", "formbuilder_mail");
            $form .= $this->oForm->createhidden("count_formbuilder_mail", sizeOf(Pelican::$config['FORMBUILDER']['MAIL']['TYPE']['ID']) - 1);
            $form .= $this->stopStandardForm();
            
            $this->setResponse($form);
        }
    }

    public function listvaluesAction ()
    {
        $sql = "SELECT * FROM #pref#_formbuilder_value where FORMBUILDER_ID=" . $this->id . " order by FORMBUILDER_VALUE_ID";
        $oConnection = Pelican_Db::getInstance();
        $values = $oConnection->queryTab($sql);
        
        if (is_array($values)) {
            foreach ($values as $key => $json) {
                $data[$key]["FORMBUILDER_VALUE_ID"] = $json['FORMBUILDER_VALUE_ID'];
                $form = json_decode($json['FORMBUILDER_VALUE_STRUCTURE'], true);
                $val = json_decode($json['FORMBUILDER_VALUE_DATA'], true);
                foreach ($form['fields'] as $field) {
                    switch ($field['type']) {
                        case 'submit':
                        case 'section':
                            break;
                        case 'radio':
                        case 'select':
                            {
                                if (is_array($val[$field['name']])) {
                                    $data[$key][$field['title'] . '-' . $field['name']] = $val[$field['name']][0];
                                }
                                $coldef[$field['title']] = $field['title'] . '-' . $field['name'];
                                break;
                            }
                        case 'checkbox':
                            {
                                if (is_array($val[$field['name']])) {
                                    $data[$key][$field['title'] . '-' . $field['name']] = implode(',', $val[$field['name']]);
                                }
                                $coldef[$field['title']] = $field['title'] . '-' . $field['name'];
                                break;
                            }
                        default:
                            {
                                $data[$key][$field['title'] . '-' . $field['name']] = $val[$field['name']];
                                $coldef[$field['title']] = $field['title'] . '-' . $field['name'];
                                break;
                            }
                    }
                }
            }
        }
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setCSS(array(
            "tblalt1",
            "tblalt2"
        ));
        
        $table->setValues($data, "FORMBUILDER_VALUE_ID");
        $table->addColumn(t('FORMBUILDER_ID'), "FORMBUILDER_VALUE_ID", "10", "left", "", "tblheader");
        $table->addMultiColumn($coldef, "50");
        /*
         * $table->addInput(t('FORMBUILDER_EDIT'), "button", array( "idvalues" => "FORMBUILDER_VALUE_ID" ), "center");
         */
        $table->addInput(t('FORMBUILDER_DEL'), "button", array(
            "idvalues" => "FORMBUILDER_VALUE_ID",
            "" => "readO=true"
        ), "center");
        $this->setResponse($table->getTable());
    }

    public function editvaluesAction ()
    {
        $this->id = $_GET['idvalues'];
        parent::editAction();
        $form = $this->startStandardForm();
        $form .= $this->oForm->createInput("FORMBUILDER_VALUE_ID", t('FORMBUILDER_ID'), 100, "", false, $_GET['idvalues'], $this->readO, 100);
        $form .= $this->oForm->createInput("FORMBUILDER_VALUE_DATA", t('FORMBUILDER_VALUE'), 100, "", false, '<pre>' . $this->json_format($this->values["FORMBUILDER_VALUE_DATA"]) . '</pre>', $this->readO, 100);
        $form .= $this->stopStandardForm();
        $this->setResponse($form);
    }

    public function editorAction ()
    {
        $head = $this->getView()->getHead();
        
        $head->setCss(Pelican_Plugin::getMediaPath('formbuilder') . 'css/bootstrap.min.css');
        $head->setCss(Pelican_Plugin::getMediaPath('formbuilder') . 'editor.css');
        $head->setJs(Pelican_Plugin::getMediaPath('formbuilder') . 'js/jquery.min.js');
        $head->setJs(Pelican_Plugin::getMediaPath('formbuilder') . 'js/jquery-ui.min.js');
        $head->setJs(Pelican_Plugin::getMediaPath('formbuilder') . 'js/knockout-latest.debug.js');
        $head->setJs(Pelican_Plugin::getMediaPath('formbuilder') . 'js/knockout.mapping-latest.js');
        $head->setJs(Pelican_Plugin::getMediaPath('formbuilder') . 'js/bootstrap-tabs.js');
        $head->setJs(Pelican_Plugin::getMediaPath('formbuilder') . 'js/jquery.hotkeys.js');
        $head->setScript("//Translations
var lang=new Object();
lang['FORMBUILDER_TITLE_IBAN'] = '" . t('FORMBUILDER_TITLE_IBAN') . "';
lang['FORMBUILDER_LABEL_MME'] = '" . t('FORMBUILDER_LABEL_MME') . "';
lang['FORMBUILDER_LABEL_MLLE'] = '" . t('FORMBUILDER_LABEL_MLLE') . "';
lang['FORMBUILDER_LABEL_MR'] = '" . t('FORMBUILDER_LABEL_MR') . "';
lang['FORMBUILDER_TITLE_FIRSTFIELD'] = '" . t('FORMBUILDER_TITLE_FIRSTFIELD') . "';
lang['FORMBUILDER_TITLE_TEXT'] = '" . t('FORMBUILDER_TITLE_TEXT') . "';
lang['FORMBUILDER_TITLE_TEXTAREA'] = '" . t('FORMBUILDER_TITLE_TEXTAREA') . "';
lang['FORMBUILDER_TITLE_SUBMIT'] = '" . t('FORMBUILDER_TITLE_SUBMIT') . "';
lang['FORMBUILDER_TITLE_NUMBER'] = '" . t('FORMBUILDER_TITLE_NUMBER') . "';
lang['FORMBUILDER_TITLE_CHECK'] = '" . t('FORMBUILDER_TITLE_CHECK') . "';
lang['FORMBUILDER_TITLE_FIRSTCHOICE'] = '" . t('FORMBUILDER_TITLE_FIRSTCHOICE') . "';
lang['FORMBUILDER_TITLE_SECONDCHOICE'] = '" . t('FORMBUILDER_TITLE_SECONDCHOICE') . "';
lang['FORMBUILDER_TITLE_THIRDCHOICE'] = '" . t('FORMBUILDER_TITLE_THIRDCHOICE') . "';
lang['FORMBUILDER_TITLE_SELECTACHOICE'] = '" . t('FORMBUILDER_TITLE_SELECTACHOICE') . "';
lang['FORMBUILDER_TITLE_SECTIONBREAK'] = '" . t('FORMBUILDER_TITLE_SECTIONBREAK') . "';
lang['FORMBUILDER_TITLE_SECTIONDESC'] = '" . t('FORMBUILDER_TITLE_SECTIONDESC') . "';
lang['FORMBUILDER_TITLE_NAME'] = '" . t('FORMBUILDER_TITLE_NAME') . "';
lang['FORMBUILDER_TITLE_PHONE'] = '" . t('FORMBUILDER_TITLE_PHONE') . "';
lang['FORMBUILDER_TITLE_RIB'] = '" . t('FORMBUILDER_TITLE_RIB') . "';
lang['FORMBUILDER_TITLE_IBAN'] = '" . t('FORMBUILDER_TITLE_IBAN') . "';
lang['FORMBUILDER_TITLE_CIVILITY'] = '" . t('FORMBUILDER_TITLE_CIVILITY') . "'");
        $head->setJs(Pelican_Plugin::getMediaPath('formbuilder') . 'editor.js');
        $this->assign('imgpath', Pelican_Plugin::getMediaPath('formbuilder') . 'images/');
        $this->assign('header', $head->getHeader(false), false);
        
        $this->fetch();
    }

    public function saveAction ()
    {
        if (! empty($_POST['FORMBUILDER_VALUE_ID'])) {
            $this->form_name = "formbuilder_value";
            $this->field_id = "FORMBUILDER_VALUE_ID";
        } else {
            // objet en texte
            Pelican_Db::$values['FORMBUILDER_MAIL_SUBJECT'] = strip_tags(Pelican_Db::$values['FORMBUILDER_MAIL_SUBJECT']);
            // objet en texte
            Pelican_Db::$values['FORMBUILDER_MAIL_BODY_TEXT'] = strip_tags(Pelican_Db::$values['FORMBUILDER_MAIL_BODY_TEXT']);
            // 1 seul expediteur
            Pelican_Db::$values['FORMBUILDER_MAIL_EXP'] = str_replace(array(
                "\r\n",
                "\n",
                ","
            ), array(
                ";",
                ";",
                ";"
            ), Pelican_Db::$values['FORMBUILDER_MAIL_EXP']);
            $temp = explode(';', Pelican_Db::$values['FORMBUILDER_MAIL_EXP']);
            Pelican_Db::$values['FORMBUILDER_MAIL_EXP'] = $temp[0];
            
            // 1 seul dest
            Pelican_Db::$values['FORMBUILDER_MAIL_DEST'] = str_replace(array(
                "\r\n",
                "\n",
                ","
            ), array(
                ";",
                ";",
                ";"
            ), Pelican_Db::$values['FORMBUILDER_MAIL_DEST']);
            $temp = explode(';', Pelican_Db::$values['FORMBUILDER_MAIL_DEST']);
            Pelican_Db::$values['FORMBUILDER_MAIL_DEST'] = $temp[0];
            
            // copies
            Pelican_Db::$values['FORMBUILDER_MAIL_CC'] = trim(str_replace(array(
                "\r\n",
                "\n",
                ","
            ), array(
                ";",
                ";",
                ";"
            ), Pelican_Db::$values['FORMBUILDER_MAIL_CC']), ";");
            
            // copies cachees
            Pelican_Db::$values['FORMBUILDER_MAIL_CCI'] = trim(str_replace(array(
                "\r\n",
                "\n",
                ","
            ), array(
                ";",
                ";",
                ";"
            ), Pelican_Db::$values['FORMBUILDER_MAIL_CCI']), ";");
            
            // tracking fields
            if (! empty(Pelican_Db::$values['FORMBUILDER_STRUCTURE'])) {
                $formDef = json_decode(Pelican_Db::$values['FORMBUILDER_STRUCTURE'], true);
                foreach ($formDef['fields'] as $field) {
                    $var[$field['name']] = $field['title'];
                }
                Pelican_Db::$values['FORMBUILDER_FIELDS'] = json_encode($var);
            }
            
            Pelican_Db::$values['FORMBUILDER_STRUCTURE'] = rawurlencode(Pelican_Db::$values['FORMBUILDER_STRUCTURE']);
        }
        parent::saveAction();
    }

    public static function saveMail ()
    {
        $oConnection = Pelican_Db::getInstance();
        
        Pelican_Form::readMulti('formbuilder_mail');
        
        $DBVALUES_MONO = Pelican_Db::$values;
        if ($DBVALUES_MONO["formbuilder_mail"]) {
            // suppression pour annule/remplace
            $oConnection->query("DELETE from #pref#_formbuilder_mail_media where FORMBUILDER_ID=" . Pelican_Db::$values["FORMBUILDER_ID"]);
            $oConnection->query("DELETE from #pref#_formbuilder_mail where FORMBUILDER_ID=" . Pelican_Db::$values["FORMBUILDER_ID"]);
            foreach ($DBVALUES_MONO["formbuilder_mail"] as Pelican_Db::$values) {
                // récupération de la clé
                Pelican_Db::$values["FORMBUILDER_ID"] = $DBVALUES_MONO["FORMBUILDER_ID"];
                if ($DBVALUES_MONO['form_action'] != Pelican_Db::DATABASE_DELETE) {
                    $oConnection->updateTable(Pelican_Db::DATABASE_INSERT, "#pref#_formbuilder_mail");
                    
                    // media
                    for ($i = 0; $i < Pelican::$config['FORMBUILDER']['MAIL']['ATTACHMENT_NUMBER']; $i ++) {
                        if (! empty(Pelican_Db::$values['MEDIA_ID_' . $i])) {
                            Pelican_Db::$values['MEDIA_ID'] = Pelican_Db::$values['MEDIA_ID_' . $i];
                            Pelican_Db::$values['FORMBUILDER_MAIL_MEDIA_ORDER'] = $i;
                            $oConnection->updateTable(Pelican_Db::DATABASE_INSERT, "#pref#_formbuilder_mail_media");
                        }
                    }
                }
            }
        }
        Pelican_Db::$values = $DBVALUES_MONO;
    }

    public function beforeDelete ()
    {
        $oConnection = Pelican_Db::getInstance();
        $oConnection->query('delete from #pref#_formbuilder_value where FORMBUILDER_ID=' . Pelican_Db::$values['FORMBUILDER_ID']);
    }

    public function json_format ($json)
    {
        if (! is_string($json)) {
            if (phpversion() && phpversion() >= 5.4) {
                return json_encode($json, JSON_PRETTY_PRINT);
            }
            $json = json_encode($json);
        }
        $result = '';
        $pos = 0; // indentation level
        $strLen = strlen($json);
        $indentStr = "\t";
        $newLine = "\n";
        $prevChar = '';
        $outOfQuotes = true;
        
        for ($i = 0; $i < $strLen; $i ++) {
            // Grab the next character in the string
            $char = substr($json, $i, 1);
            
            // Are we inside a quoted string?
            if ($char == '"' && $prevChar != '\\') {
                $outOfQuotes = ! $outOfQuotes;
            }             // If this character is the end of an element,
              // output a new line and indent the next line
            else 
                if (($char == '}' || $char == ']') && $outOfQuotes) {
                    $result .= $newLine;
                    $pos --;
                    for ($j = 0; $j < $pos; $j ++) {
                        $result .= $indentStr;
                    }
                }                 // eat all non-essential whitespace in the input as we do our own here and it would only mess up our process
                else 
                    if ($outOfQuotes && false !== strpos(" \t\r\n", $char)) {
                        continue;
                    }
            
            // Add the character to the result string
            $result .= $char;
            // always add a space after a field colon:
            if ($char == ':' && $outOfQuotes) {
                $result .= ' ';
            }
            
            // If the last character was the beginning of an element,
            // output a new line and indent the next line
            if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
                $result .= $newLine;
                if ($char == '{' || $char == '[') {
                    $pos ++;
                }
                for ($j = 0; $j < $pos; $j ++) {
                    $result .= $indentStr;
                }
            }
            $prevChar = $char;
        }
        
        return $result;
    }
}
