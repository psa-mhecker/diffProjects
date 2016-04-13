<?php

/**
 * Formulaire de gestion des types de contenu
 *
 * @package Pelican_BackOffice
 * @subpackage Administration
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 07/03/2004
 */

class Administration_Content_Type_Controller extends Pelican_Controller_Back
{

    protected $administration = true;

    protected $form_name = "content_type";

    protected $field_id = "CONTENT_TYPE_ID";

    protected $defaultOrder = "CONTENT_TYPE_LABEL";

    protected $processus = array(
        "#pref#_content_type" , 
        array(
            "#pref#_content_type_site" , 
            'SITE_ID'
        )
    );

    protected $decacheBack = array(
        "Backend/ContentType" , 
        "Backend/ContentType"
    );

    protected function beforeDelete ()
    {
    	
    	
        $aBind[":CONTENT_TYPE_ID"] = Pelican_Db::$values["CONTENT_TYPE_ID"];
        $oConnection = Pelican_Db::getInstance();
        $oConnection->query('delete from #pref#_user_role where content_type_id=:CONTENT_TYPE_ID', $aBind);
    }

    protected function setListModel ()
    {
        $this->listModel = "SELECT
			#pref#_content_type.CONTENT_TYPE_ID,
			CONTENT_TYPE_LABEL,
			TEMPLATE_LABEL
			FROM #pref#_content_type, #pref#_template
			where #pref#_content_type.TEMPLATE_ID=#pref#_template.TEMPLATE_ID
			order by " . $this->listOrder;
    }

    protected function setEditModel ()
    {
        $this->editModel = "SELECT * from #pref#_content_type WHERE CONTENT_TYPE_ID='" . $this->id . "'";
    }

    public function listAction ()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        
        $table->setFilterField("id", "<b>" . t('Identifier') . "</b> :", array(
            "CONTENT_TYPE_ID" , 
            "CONTENT_TYPE_ID"
        ));
        $table->setFilterField("libelle", "<b>" . t('FORM_LABEL') . "</b> :", array(
            "CONTENT_TYPE_LABEL" , 
            "CONTENT_TYPE_LABEL"
        ));
        $table->setFilterField("template", "<b>" . t('TEMPLATE') . "</b> :", array(
            "TEMPLATE_LABEL" , 
            "TEMPLATE_LABEL"
        ));
        $aSite = Pelican_Cache::fetch("Frontend/Site");
        $table->getFilter(2);
        
        $table->setCSS(array(
            "tblalt1" , 
            "tblalt2"
        ));
        $table->setValues($this->getListModel(), "#pref#_content_type.CONTENT_TYPE_ID");
        $table->addColumn(t('ID'), "CONTENT_TYPE_ID", "10", "left", "", "tblheader", "CONTENT_TYPE_ID");
        $table->addColumn(t('FORM_LABEL'), "CONTENT_TYPE_LABEL", "50", "left", "", "tblheader", "CONTENT_TYPE_LABEL");
        $table->addColumn(t('TEMPLATE'), "TEMPLATE_LABEL", "50", "left", "", "tblheader", "TEMPLATE_LABEL");
        
        $sqlSite = "select distinct #pref#_site.SITE_ID as \"id\", REPLACE(SITE_LABEL,' ','&nbsp;') as \"lib\" from #pref#_content_type_site, #pref#_site where #pref#_content_type_site.SITE_ID=#pref#_site.SITE_ID ";
        $table->addMulti(t('Site(s)'), "CONTENT_TYPE_ID", "50", "left", "<br>", "tblheader", "", $sqlSite);
        
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array(
            "id" => "CONTENT_TYPE_ID"
        ), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array(
            "id" => "CONTENT_TYPE_ID" , 
            "" => "readO=true"
        ), "center");
        $this->setResponse($table->getTable());
    }

    public function editAction ()
    {
        parent::editAction();
        $form = $this->startStandardForm();
        
        $form .= $this->oForm->createInput("CONTENT_TYPE_LABEL", t('FORM_LABEL'), 100, "", true, $this->values["CONTENT_TYPE_LABEL"], $this->readO, 100);
        $aTemplates = getComboValuesFromCache("Template");
        $form .= $this->oForm->createComboFromList("TEMPLATE_ID", t('TEMPLATE'), $aTemplates, $this->values["TEMPLATE_ID"], true, $this->readO);
        $form .= $this->oForm->createCheckBoxFromList("CONTENT_TYPE_ADMINISTRATION", t('Hide when add'), array(
            "1" => ""
        ), $this->values["CONTENT_TYPE_ADMINISTRATION"], false, $this->readO, "h");
        $form .= $this->oForm->createCheckBoxFromList("CONTENT_TYPE_DEFAULT", t('By default'), array(
            "1" => ""
        ), $this->values["CONTENT_TYPE_DEFAULT"], false, $this->readO, "h");
        
        // Les profils disponibles
        $form .= $this->oForm->showSeparator();
        $sqlData = "select #pref#_site.SITE_ID as id, SITE_LABEL as lib from #pref#_site order by lib";
        $sqlSelected = "select #pref#_site.SITE_ID as id, SITE_LABEL as lib from #pref#_site, #pref#_content_type_site where #pref#_site.SITE_ID=#pref#_content_type_site.SITE_ID and #pref#_content_type_site.CONTENT_TYPE_ID='" . $this->id . "' order by lib";
        $form .= $this->oForm->createAssocFromSql($oConnection, 'SITE_ID', "Sites autorisés", $sqlData, $sqlSelected, false, true, $this->readO, 8, 200, false);
        
        $form .= $this->stopStandardForm();
		
	// Zend_Form start
	$form = formToString($this->oForm, $form);
        // Zend_Form stop
		
        $this->setResponse($form);
    }
}