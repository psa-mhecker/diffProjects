<?php
/**
 * Formulaire de gestion des états de workflow
 *
 * @package Pelican_BackOffice
 * @subpackage Administration
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 02/07/2004
 */

class Administration_Skins_Controller extends Pelican_Controller_Back
{

    protected $administration = true;

    protected $form_name = "skins";

    protected $field_id = "SKIN_ID";

    protected $defaultOrder = "SKIN_REPORT_ORDER";

    protected function setListModel()
    {
        $this->listModel = "SELECT * FROM #pref#_state order by " . $this->listOrder;
    }

    protected function setEditModel()
    {
        $this->editModel = "SELECT * from #pref#_state WHERE " . "state" . "_id='" . $this->id . "'";
    }

    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
			$table->setFilterField("SKIN_ID", "<b>".t('LOGIN')."&nbsp;:</b>", array("SKIN_ID", "SKIN_ID"), "", "1", true, true);
			$table->setFilterField("SKIN_LABEL", "<b>".t('FORM_LABEL')."&nbsp;:</b>", array("SKIN_LABEL", "SKIN_LABEL"), 2);
        $table->getFilter(3);
        $table->setCSS(array("tblalt1" , "tblalt2"));
        $table->setTableOrder("#pref#_state", "SKIN_ID", "SKIN_REPORT_ORDER");
        $table->setValues($this->getListModel(), "SKIN_ID");
			$table->addColumn(t('ID'), "SKIN_ID", "10", "left", "", "tblheader");
        $table->addColumn(t('FORM_LABEL'), "SKIN_LABEL", "90", "left", "", "tblheader");
        
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "SKIN_ID"), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => "SKIN_ID" , "" => "readO=true"), "center");
        $this->setResponse($table->getTable() . "<br /><br />");
    }

    public function editAction()
    {
        parent::editAction();
        ob_start();
        $oForm = Pelican_Factory::getInstance('Form', true);
			$oForm->open(Pelican::$config["DB_PATH"]);
        beginFormTable();
        $this->beginForm($oForm);
        
        $oForm->createHidden($this->field_id, $this->id);
        $oForm->createInput("SKIN_LABEL", t('Label (state)'), 100, "", true, $this->values["SKIN_LABEL"], $this->readO, 100);
        $oForm->createInput("SKIN_LABEL2", t('Label (saction)'), 100, "", true, $this->values["SKIN_LABEL2"], $this->readO, 100);
        if (! $this->values["SKIN_REPORT_ORDER"]) {
            $this->values["SKIN_REPORT_ORDER"] = "999";
        }
        $oForm->createHidden("SKIN_REPORT_ORDER", $this->values["SKIN_REPORT_ORDER"]);
        $oForm->createCheckBoxFromList("SKIN_PUBLICATION", t('Publication state'), array(1 => ""), $this->values["SKIN_PUBLICATION"], false, $this->readO);
        $this->endForm($oForm);
        endFormTable();
        $oForm->close();
        $form = ob_get_contents();
        ob_clean();
		
	// Zend_Form start
        $form = formToString($this->oForm, $form);
        // Zend_Form stop
		
        $this->setResponse($form);
    }
}
