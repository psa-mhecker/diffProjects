<?php

/**
 * Formulaire de gestion des zones de page du Back Office
 *
 * @package Pelican_BackOffice
 * @subpackage Administration
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 02/07/2004
 */
class Administration_Area_Controller extends Pelican_Controller_Back
{

    protected $administration = true;

    protected $form_name = "area";

    protected $field_id = "AREA_ID";

    protected $defaultOrder = "AREA_LABEL";

    protected function setListModel ()
    {
        $this->listModel = "SELECT #pref#_area.AREA_ID, AREA_LABEL, count(distinct ZONE_ID) as COMPTE,
			count(tpa.AREA_ID)+count(tpa.AREA_ID) as NB, AREA_HORIZONTAL from #pref#_area
			left join #pref#_zone_template zt on (#pref#_area.area_id=zt.area_id)
			left join #pref#_template_page_area tpa on (#pref#_area.area_id=tpa.area_id)
			group by #pref#_area.AREA_ID, AREA_LABEL, AREA_HORIZONTAL
			order by " . $this->listOrder;
    
    }

    protected function setEditModel ()
    {
        $this->editModel = "SELECT * from #pref#_area WHERE AREA_ID='" . $this->id . "'";
    }

    public function listAction ()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setFilterField("nom", "<b>" . t('Name or content') . "</b> :", array(
            "AREA_LABEL" , 
            "AREA_HEAD" , 
            "AREA_FOOT"
        ), 2);
        $table->setFilterField();
        $table->getFilter(2);
        $table->setCSS(array(
            "tblalt1" , 
            "tblalt2"
        ));
        $table->setValues($this->getListModel(), "#pref#_area.AREA_ID");
        $table->addColumn(t('ID'), "AREA_ID", "10", "left", "", "tblheader", "AREA_ID");
        $table->addColumn(t('POPUP_LABEL_NAME'), "AREA_LABEL", "90", "left", "", "tblheader", "AREA_LABEL");
        $table->addColumn(t('BLOCS'), "COMPTE", "10", "left", "", "tblheader", "COMPTE");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array(
            "id" => "AREA_ID"
        ), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array(
            "id" => "AREA_ID" , 
            "" => "readO=true"
        ), "center", array(
            "NB=0"
        ));
        $this->setResponse($table->getTable());
    }

    public function editAction ()
    {
        parent::editAction();
        $this->oForm = Pelican_Factory::getInstance('Form', true);
        
        $this->oForm->setTab ( "1", t ( 'Desktop' ) );
		$this->oForm->setTab ( "2", t ( 'Mobile' ) );
        
        $form .= $this->oForm->open(Pelican::$config["DB_PATH"]);
        $form .= $this->beginForm($this->oForm);
        $this->oForm->bDirectOutput = false;
        $form .= $this->oForm->beginFormTable();
        //$form = $this->startStandardForm();
		
        $form .= $this->oForm->createHidden("AREA_TYPE_ID", $_GET["tc"]);
        $form .= $this->oForm->createInput("AREA_LABEL", t('POPUP_LABEL_NAME'), 100, "", true, $this->values["AREA_LABEL"], $this->readO, 100);
        $form .= $this->oForm->createCheckBoxFromList("AREA_HORIZONTAL", t('Backend horizontal display'), array(
            "1" => ""
        ), $this->values["AREA_HORIZONTAL"], false, $this->readO, "h");
        $form .= $this->oForm->createCheckBoxFromList("AREA_DROPPABLE", t('Droppable display'), array(
            "1" => ""
        ), $this->values["AREA_DROPPABLE"], false, $this->readO, "h");
        $form .= $this->oForm->endFormTable();
        
        
        $form .= $this->oForm->beginTab ( "1" );
        
        $form .= $this->oForm->createTextArea("AREA_HEAD", t('Entete'), false, $this->values["AREA_HEAD"], 1000, $this->readO, 10, 75);
        $form .= $this->oForm->createTextArea("AREA_FOOT", t('Pied'), false, $this->values["AREA_FOOT"], 1000, $this->readO, 10, 75);
        
        $form .= $this->oForm->beginTab ( "2" );
        $form .= $this->oForm->createCheckBoxFromList("AREA_MOBILE", t('Entête spécifique pour le mobile'), array(
            "1" => ""
        ), $this->values["AREA_MOBILE"], false, $this->readO, "h");
        $form .= $this->oForm->createTextArea("AREA_HEAD_MOBILE", t('Entete'), false, $this->values["AREA_HEAD_MOBILE"], 1000, $this->readO, 10, 75);
        $form .= $this->oForm->createTextArea("AREA_FOOT_MOBILE", t('Pied'), false, $this->values["AREA_FOOT_MOBILE"], 1000, $this->readO, 10, 75);
        
        
        $form .= $this->oForm->endTab ();
		$form .= $this->beginForm ( $this->oForm );
		$form .= $this->oForm->beginFormTable ();
		$form .= $this->oForm->endFormTable ();
		$form .= $this->endForm ( $this->oForm );
		$form .= $this->oForm->close ();

        // Zend_Form start
	$form = formToString($this->oForm, $form);
        // Zend_Form stop
		$this->setResponse($form);
    }
}