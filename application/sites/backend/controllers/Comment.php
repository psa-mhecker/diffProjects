<?php

/**
 * Formulaire des commentaires
 * @author  patrick.deroubaix@businessdecision.fr
 * @package Pelican_BackOffice
 * @subpackage Service
 */

class Comment_Controller extends Pelican_Controller_Back
{

    protected $form_name = "comment";

    protected $field_id = "COMMENT_ID";

    protected $defaultOrder = "OBJECT_TYPE_LABEL, COMMENT_CREATION_DATE desc";
    
    protected $decacheBack = array ( array ("Comment/Object", array('OBJECT_ID','OBJECT_TYPE_ID') ) );
	
    protected function setListModel()
    {
        $oConnection = Pelican_Db::getInstance();
        
        $this->listModel = "SELECT COMMENT_ID,
COMMENT_PSEUDO,
COMMENT_TITLE, 
COMMENT_CREATION_DATE,
" . $oConnection->dateSqlToString(COMMENT_CREATION_DATE, true) . " as COMMENT_CREATION_DATE_FR,
" . $oConnection->getCaseClause("COMMENT_STATUS", array(
            1 => "'" . Pelican_Html::span(array(
                style => "color:green"
            ), 'En ligne') . "'"
        ), "'" . Pelican_Html::span(array(
            style => "color:red"
        ), t('OFFLINE')) . "'") . " as COMMENT_STATUS,
OBJECT_TYPE_LABEL
FROM #pref#_comment c
inner join #pref#_object_type ot on (c.OBJECT_TYPE_ID = ot.OBJECT_TYPE_ID)
WHERE SITE_ID = " . $_SESSION[APP]['SITE_ID'];
        if ($_GET['object']) {
            $this->listModel .= " AND OBJECT_ID=" . $_GET['object'];
        }
        if ($_GET['object_type']) {
            $this->listModel .= " AND c.OBJECT_TYPE_ID=" . $_GET['object_type'];
        }
        $this->listModel .= " ORDER BY " . $this->listOrder;
    
    }

    protected function setEditModel()
    {
        $oConnection = Pelican_Db::getInstance();
        
        $this->editModel = "SELECT " . $oConnection->datesqltostring('COMMENT_CREATION_DATE', true) . " as COMMENT_CREATION_DATE,
            COMMENT_EMAIL,
            COMMENT_ID,
            COMMENT_PSEUDO,
            COMMENT_RATING,
            COMMENT_STATUS,
            COMMENT_TEXT,
            COMMENT_TITLE,
            COMMENT_URL,
            OBJECT_ID,
            OBJECT_TYPE_ID,
            SITE_ID
            FROM #pref#_comment
            WHERE COMMENT_ID=" . $_GET['id'];
    
    }

    public function listAction()
    {
        $_SESSION["retour"] = $_SERVER["REQUEST_URI"];
        
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        
        $table->setFilterField("text", t('POPUP_SEARCH_TITLE'), array(
            "COMMENT_PSEUDO", 
            "COMMENT_TITLE", 
            "COMMENT_TEXT"
        ));
        $table->setFilterField("status", t('ETAT'), "COMMENT_STATUS", array(
            array(
                "id" => "1", 
                "lib" => "En ligne"
            ), 
            array(
                "id" => "0", 
                "lib" => t('OFFLINE')
            )
        ));
        if (!$_GET['iframe']) {
            $sqlObject = "select OBJECT_TYPE_ID id,
			OBJECT_TYPE_LABEL lib
			from #pref#_object_type";
            $table->setFilterField("object_type", t('TYPE'), "ot.OBJECT_TYPE_ID", $sqlObject);
        }
        $table->getFilter(2);
        
        $table->setCSS(array(
            "tblalt1", 
            "tblalt2"
        ));
        $table->setValues($this->getListModel(), "COMMENT_ID", (!$_GET['iframe'] ? "OBJECT_TYPE_LABEL" : ""));
        
        $table->addColumn(t('ID'), "COMMENT_ID", "8", "left", "", "tblheader", "COMMENT_ID");
        $table->addColumn(t('AUTEUR'), "COMMENT_PSEUDO", "20", "left", "", "tblheader");
        $table->addColumn(t('TITRE'), "COMMENT_TITLE", "20", "left", "", "tblheader");
        $table->addColumn(t('DATE'), "COMMENT_CREATION_DATE_FR", "20", "left", "", "tblheader", "COMMENT_CREATION_DATE");
        $table->addColumn(t('ETAT'), "COMMENT_STATUS", "20", "left", "", "tblheader");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array(
            "id" => "COMMENT_ID"
        ), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array(
            "id" => "COMMENT_ID", 
            "" => "readO=true"
        ), "center");
        
        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        parent::editAction();
        $oForm = Pelican_Factory::getInstance('Form', true);
        $oForm->bDirectOutput = false;
        $form .= $oForm->open(Pelican::$config["DB_PATH"]);
        $form .= $this->beginForm($oForm);
        $form .= $oForm->beginFormTable();
        
        if (!$this->values["OBJECT_ID"] && $_GET['object']) {
            $this->values["OBJECT_ID"] = $_GET['object'];
        }
        $form .= $oForm->createHidden("OBJECT_ID", $this->values["OBJECT_ID"]);
        if (!$this->values["OBJECT_TYPE_ID"] && $_GET['object_type']) {
            $this->values["OBJECT_TYPE_ID"] = $_GET['object_type'];
        }
        $form .= $oForm->createHidden("OBJECT_TYPE_ID", $this->values["OBJECT_TYPE_ID"]);
        $form .= $oForm->createHidden('SITE_ID', $_SESSION[APP]['SITE_ID']);
        
        $form .= $oForm->createInput("COMMENT_PSEUDO", t('AUTEUR'), 255, "", true, $this->values["COMMENT_PSEUDO"], true, 116);
        $form .= $oForm->createInput("COMMENT_CREATION_DATE", t('DATE'), 255, "", true, $this->values["COMMENT_CREATION_DATE"], true, 116);
        $form .= $oForm->createInput("COMMENT_TITLE", t('TITRE'), 255, "", true, $this->values["COMMENT_TITLE"], $this->readO, 100);
        $form .= $oForm->createInput("COMMENT_RATING", t('RATING'), 1, "", false, $this->values["COMMENT_RATING"], $this->readO, 1);
        $form .= $oForm->createTextArea("COMMENT_TEXT", t('Commentary'), false, $this->values["COMMENT_TEXT"], 4000, $this->readO, 5, 100, false, "", false);
        $aDataValues = array(
            '1' => t('FORM_MSG_YES')
        );
        $form .= $oForm->createCheckBoxFromList("COMMENT_STATUS", t('PUBLICATION'), $aDataValues, $this->values["COMMENT_STATUS"], false, $this->readO, "h");
        
        if (!$_GET['iframe']) {
            $form .= $oForm->endFormTable();
            $form .= $this->endForm($oForm);
        } else {
            $form .= $oForm->endFormTable();
            $this->form_retour = str_replace("&id=" . $this->id, "", $_SERVER["REQUEST_URI"]);
            $this->form_retour = str_replace("&readO=true", "", $this->form_retour);
            // Initialisation : page de retour après SUBMIT
            // $form .= $oForm->createHidden("form_retour", $this->form_retour);
            // Initialisation : Identifiant et type de contenu
            $form .= $oForm->createHidden($this->field_id, $this->id);
            // $form .= $oForm->createHidden("form_name", $this->form_name);
            // Initialisation : variable d'identification de la fonction à appeler
            // $form .= $oForm->createHidden("form_action", $this->form_action);
            $form .= $oForm->beginFormTable(0, 0, "formbottom");
            $form .= ("<tr><td align=\"center\" class=\"formfooter\">");
            if ($this->readO) {
                $form .= $oForm->createSubmit(t('Send'), t('POPUP_LABEL_DEL'), "", 65, 30);
            } else {
                $form .= $oForm->createSubmit(t('Send'), t('FORM_BUTTON_SAVE'), "", 65, 30);
            }
            $form .= $oForm->createButton("retour", t('POPUP_BUTTON_BACK'), "javascript:window.location='" . $this->form_retour . "';");
            $form .= ("</td></tr>");
            $form .= $oForm->endFormTable();
            $form .= $this->endForm($oForm);
        }
        $form .= $oForm->close();
        $this->setResponse($form);
    }
}