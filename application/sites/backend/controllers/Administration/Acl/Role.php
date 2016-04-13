<?php

/**
 * Formulaire de gestion des rôles utilisateur du workflow
 *
 * @package Pelican_BackOffice
 * @subpackage Administration
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 07/03/2004
 */

class Administration_Acl_Role_Controller extends Pelican_Controller_Back
{

    protected $administration = true;

    protected $form_name = "acl_role";

    protected $field_id = "ACL_ROLE_ID";

    protected $defaultOrder = "ACL_ROLE_LABEL";
    
    protected $readOID;
    
    protected function setListModel ()
    {
        $this->listModel = "SELECT * FROM #pref#_acl_role
		inner join #pref#_site on (#pref#_acl_role.SITE_ID=#pref#_site.SITE_ID)
		order by " . $this->listOrder;
    }

    protected function setEditModel ()
    {
        $this->editModel = "SELECT * from #pref#_acl_role WHERE ACL_ROLE_ID='" . $this->id . "'";
    }

    public function listAction ()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setCSS(array(
            "tblalt1" , 
            "tblalt2"
        ));
        $table->setFilterField("site", "<b>" . t('SITE') . "&nbsp;:</b><br />", "#pref#_acl_role.SITE_ID", "select #pref#_site.SITE_ID as \"id\", SITE_LABEL as lib FROM #pref#_site ORDER BY SITE_LABEL");
        $table->setFilterField("ACL_ROLE_ID", "<b>" . t('LOGIN') . " </b> :", array(
            "ACL_ROLE_ID"
        ), "", "1", true, true);
        $groupe = "SITE_LABEL";
        $table->setFilterField("ACL_ROLE_LABEL", "<b>" . t('NAME') . " </b> :", array(
            "ACL_ROLE_LABEL"
        ), "", "1", true, true);
        $table->getFilter(3);
        $table->setValues($this->getListModel(), "#pref#_acl_role.ACL_ROLE_ID", $groupe);
        $table->addColumn(t('ID'), "ACL_ROLE_ID", "50", "left", "", "tblheader", "ACL_ROLE_ID");
        $table->addColumn(t('ROLE'), "ACL_ROLE_LABEL", "50", "left", "", "tblheader", "ACL_ROLE_LABEL");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array(
            "id" => "ACL_ROLE_ID"
        ), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array(
            "id" => "ACL_ROLE_ID" , 
            "" => "readO=true"
        ), "center");
        $this->setResponse($table->getTable());
    }

    public function editAction ()
    {
        parent::editAction();
        $form = $this->startStandardForm();
        
        if ($this->form_action != Pelican_Db::DATABASE_INSERT) {
            $this->readOID = true;
        }
        $form .= $this->oForm->createInput("ACL_ROLE_ID", t('ID'), 75, "", true, $this->values["ACL_ROLE_ID"], $this->readOID, 75);
        $form .= $this->oForm->createInput("ACL_ROLE_LABEL", t('FORM_LABEL'), 75, "", true, $this->values["ACL_ROLE_LABEL"], $this->readO, 75);
        $form .= $this->oForm->createComboFromSql($oConnection, 'SITE_ID', t('SITE'), "SELECT #pref#_site.SITE_ID as ID, SITE_LABEL as LIB FROM #pref#_site ORDER BY SITE_LABEL", $this->values['SITE_ID'], true, $this->readO, "1", false, "", true, false);
        
        $form .= $this->stopStandardForm();
		
		// Zend_Form start
		$form = formToString($this->oForm, $form);
        // Zend_Form stop
		
        $this->setResponse($form);
    }
}