<?php

/**
 * Formulaire de gestion des rôles utilisateur du workflow.
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 07/03/2004
 */
class Administration_Acl_Functionality_Controller extends Pelican_Controller_Back
{
    protected $administration = true;

    protected $form_name = "acl_functionality";

    protected $field_id = "ACL_FUNCTIONALITY_ID";

    protected $defaultOrder = "ACL_FUNCTIONALITY_LABEL";

    protected $readOID;

    protected function setListModel()
    {
        $this->listModel = "SELECT * FROM #pref#_acl_functionality f
		inner join #pref#_acl_functionality_type ft on (f.ACL_FUNCTIONALITY_TYPE_ID=ft.ACL_FUNCTIONALITY_TYPE_ID)
		order by ".$this->listOrder;
    }

    protected function setEditModel()
    {
        $this->editModel = "SELECT * from #pref#_acl_functionality WHERE ACL_FUNCTIONALITY_ID='".$this->id."'";
    }

    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");

        //------------ Begin Filter ----------
        $table->setFilterField("functionality_label", "<b>".t('NAME')." :</b>", "ACL_FUNCTIONALITY_LABEL");
        $table->setFilterField("functionality_type", "<b>".t('Fonctionality type')." :</b>", "ft.ACL_FUNCTIONALITY_TYPE_ID", "SELECT * FROM #pref#_acl_functionality_type
			order by ACL_FUNCTIONALITY_TYPE_LABEL");
        $table->getFilter(2);
        //------------ End Filter ----------


        //------------ Begin Table ----------
        $table->setCSS(array(
            "tblalt1",
            "tblalt2", ));
        $table->setValues($this->getListModel(), "ACL_FUNCTIONALITY_ID", "ACL_FUNCTIONALITY_TYPE_LABEL");
        $table->addColumn(t('ID'), "ACL_FUNCTIONALITY_ID", "50", "left", "", "tblheader", "ACL_FUNCTIONALITY_ID");
        $table->addColumn(t('Fonctionnalite'), "ACL_FUNCTIONALITY_LABEL", "50", "left", "", "tblheader", "ACL_FUNCTIONALITY_LABEL");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array(
            "id" => "ACL_FUNCTIONALITY_ID", ), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array(
            "id" => "ACL_FUNCTIONALITY_ID",
            "" => "readO=true", ), "center");
        //------------ End Table ----------


        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        parent::editAction();
        $form = $this->startStandardForm();

        $oConnection = Pelican_Db::getInstance();

        $this->readOID = $this->readO;
        if ($this->form_action != Pelican_Db::DATABASE_INSERT) {
            $this->readOID = true;
        }
        $form .= $this->oForm
            ->createInput("ACL_FUNCTIONALITY_ID", t('ID'), 75, "", true, $this->values["ACL_FUNCTIONALITY_ID"], $this->readOID, 75);
        $form .= $this->oForm
            ->createInput("ACL_FUNCTIONALITY_LABEL", t('FORM_LABEL'), 75, "", true, $this->values["ACL_FUNCTIONALITY_LABEL"], $this->readO, 75);
        $form .= $this->oForm
            ->createCombo($oConnection, "ACL_FUNCTIONALITY_TYPE_ID", t('Fonctionality type'), "acl_functionality_type", "", "", $this->values["ACL_FUNCTIONALITY_TYPE_ID"], true, $this->readO, "1", false, "", true, true);

        $form .= $this->stopStandardForm();

        // Zend_Form start
        $form = formToString($this->oForm, $form);
        // Zend_Form stop

        $this->setResponse($form);
    }
}
