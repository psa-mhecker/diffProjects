<?php

/**
 * Formulaire de gestion des rôles utilisateur du workflow
 *
 * @package Pelican_BackOffice
 * @subpackage Administration
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 07/03/2004
 */

class Administration_Page_Type_Controller extends Pelican_Controller_Back
{

    protected $administration = true;

    protected $form_name = "page_type";

    protected $field_id = "PAGE_TYPE_ID";

    protected $defaultOrder = "PAGE_TYPE_LABEL";

    protected $important = array(
        "GENERAL" , 
        "HOME" , 
        "CONTENT" , 
        "PORTAL" , 
        "SEARCH"
    );

    protected function beforeSave ()
    {
        $this->decacheBack = array(
            array(
                "Backend/Generic" , 
                "page_type"
            ) , 
            array(
                "Template/Page" , 
                $_POST['SITE_ID']
            ) , 
            array(
                "Backend/PageType" , 
                $_POST['SITE_ID']
            ) , 
            array(
                "PageType/Template"
            )
        );
    }

    protected function setListModel ()
    {
        $this->listModel = "SELECT
				pt.PAGE_TYPE_ID,
				PAGE_TYPE_CODE,
				PAGE_TYPE_LABEL,
				PAGE_TYPE_PARAM,
				PAGE_TYPE_SHORTCUT,
				PAGE_TYPE_UNIQUE,
				PAGE_TYPE_ONE_USE,
				PAGE_TYPE_HIDE,
				COUNT(distinct TEMPLATE_PAGE_ID) as NB
			FROM #pref#_page_type pt
			LEFT JOIN #pref#_template_page tp on (pt.PAGE_TYPE_ID=tp.PAGE_TYPE_ID)
			group by 
				pt.PAGE_TYPE_ID,
				PAGE_TYPE_CODE,
				PAGE_TYPE_LABEL,
				PAGE_TYPE_PARAM,
				PAGE_TYPE_SHORTCUT,
				PAGE_TYPE_UNIQUE,
				PAGE_TYPE_ONE_USE,
				PAGE_TYPE_HIDE
			order by " . $this->listOrder;
    }

    protected function setEditModel ()
    {
        $this->editModel = "SELECT * from #pref#_page_type WHERE PAGE_TYPE_ID='" . $this->id . "'";
    }

    public function listAction ()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setFilterField("search_keywordCode", "<b>" . t('Code') . " :</b>", "PAGE_TYPE_CODE");
        $table->setFilterField("search_keywordLabel", "<b>" . t('LAYOUT') . " :</b>", "PAGE_TYPE_LABEL");
        $table->getFilter(2);
        $table->setCSS(array(
            "tblalt1" , 
            "tblalt2"
        ));
        $table->setValues($this->getListModel(), "pt.PAGE_TYPE_ID");
        $table->addColumn(t('ID'), "PAGE_TYPE_ID", "5", "left", "", "tblheader", "PAGE_TYPE_ID");
        $table->addColumn(t('CODE'), "PAGE_TYPE_CODE", "10", "left", "", "tblheader", "PAGE_TYPE_CODE");
        $table->addColumn(t('LAYOUT'), "PAGE_TYPE_LABEL", "50", "left", "", "tblheader", "PAGE_TYPE_LABEL");
        $table->addColumn(t('PARAMETER'), "PAGE_TYPE_PARAM", "10", "left", "", "tblheader", "PAGE_TYPE_PARAM");
        $table->addColumn(t('SHORTCUT'), "PAGE_TYPE_SHORTCUT", "10", "left", "", "tblheader", "PAGE_TYPE_SHORTCUT");
        $table->addColumn(t('ALONE_LAYOUT'), "PAGE_TYPE_UNIQUE", "2", "center", "boolean", "tblheader", "PAGE_TYPE_UNIQUE");
        $table->addColumn(t('ONE_USE'), "PAGE_TYPE_ONE_USE", "2", "center", "boolean", "tblheader", "PAGE_TYPE_ONE_USE");
        $table->addColumn(t('HIDE'), "PAGE_TYPE_HIDE", "2", "center", "boolean", "tblheader", "PAGE_TYPE_HIDE");
        
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array(
            "id" => "PAGE_TYPE_ID"
        ), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array(
            "id" => "PAGE_TYPE_ID" , 
            "" => "readO=true"
        ), "center", array(
            "NB=0"
        ));
        $this->setResponse($table->getTable());
    }

    public function editAction ()
    {
        parent::editAction();
        $form = $this->startStandardForm();
        
        $readOCode = $this->readO;
        /** important pour garder les codes de fonctionnement */
        if (in_array($this->values['PAGE_TYPE_CODE'], $this->important)) {
            $readOCode = true;
        }
        
        $form .= $this->oForm->createInput("PAGE_TYPE_LABEL", t('FORM_LABEL'), 75, "", true, $this->values["PAGE_TYPE_LABEL"], $this->readO, 75);
        $form .= $this->oForm->createInput("PAGE_TYPE_CODE", t('CODE'), 75, "", true, $this->values["PAGE_TYPE_CODE"], $readOCode, 75);
        $form .= $this->oForm->createCheckBoxFromList("PAGE_TYPE_UNIQUE", t('ONE_SITE_LAYOUT'), array(
            "1" => ""
        ), $this->values['PAGE_TYPE_UNIQUE'], false, $this->readO);
        $form .= $this->oForm->createCheckBoxFromList("PAGE_TYPE_ONE_USE", t('ONE_SITE_USE'), array(
            "1" => ""
        ), $this->values['PAGE_TYPE_ONE_USE'], false, $this->readO);
        $form .= $this->oForm->createCheckBoxFromList("PAGE_TYPE_HIDE", t('HIDE_LAYOUT'), array(
            "1" => ""
        ), $this->values['PAGE_TYPE_HIDE'], false, $this->readO);
        $form .= $this->oForm->createInput("PAGE_TYPE_PARAM", t('LINK_PARAMETER'), 75, "", false, $this->values["PAGE_TYPE_PARAM"], $this->readO, 75);
        $form .= $this->oForm->createInput("PAGE_TYPE_SHORTCUT", t('LAYOUT_SHORTCUT'), 75, "", false, $this->values["PAGE_TYPE_SHORTCUT"], $this->readO, 75);
        
        $form .= $this->stopStandardForm();
		
	// Zend_Form start
	$form = formToString($this->oForm, $form);
        // Zend_Form stop
		
        $this->setResponse($form);
    }
}