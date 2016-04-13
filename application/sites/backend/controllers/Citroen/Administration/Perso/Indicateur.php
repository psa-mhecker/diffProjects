<?php
require_once (Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php');
class Citroen_Administration_Perso_Indicateur_Controller extends Citroen_Controller
{
    protected $administration = true;
    protected $form_name = "perso_indicateur";
    protected $field_id = "INDICATEUR_ID";
    protected $defaultOrder = "INDICATEUR_ID";
    protected $decacheBack = array(
        array('Frontend/Citroen/Perso/Indicateurs')
    );
    
    protected function setListModel()
    {	
        $oConnection = Pelican_Db::getInstance();
        $sqlList = "SELECT 
                            * 
                    FROM 
                            #pref#_perso_indicateur
                    ORDER BY " . $this->listOrder;
        
        $this->listModel = $oConnection->queryTab($sqlList);
    }

    protected function setEditModel()
    {
        $this->aBind[':'.$this->field_id] = (int)$this->id;
        $this->editModel = "SELECT 
                                    * 
                            FROM 
                                    #pref#_perso_indicateur 
                            WHERE   ".$this->field_id." = :" . $this->field_id;
    }

    public function listAction()
    {
        parent::listAction();
        $this->aButton["add"] = "";               
        Backoffice_Button_Helper::init($this->aButton);
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setValues($this->getListModel(), "INDICATEUR_ID");      
        $table->addColumn(t('INDICATEUR'), "INDICATEUR_LABEL", "90", "left", "", "tblheader", "INDICATEUR_LABEL");
        $table->addColumn(t('PRIORITE'), "PRIORITE", "90", "center", "", "tblheader", "PRIORITE");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "INDICATEUR_ID"), "center");
        $this->setResponse($table->getTable());
    }

    public function editAction()
    {	
        $oConnection = Pelican_Db::getInstance();
        parent::editAction();
        $form = $this->startStandardForm();        
        $form .= $this->oForm->createLabel(t('INDICATEUR'), $this->values['INDICATEUR_LABEL']);
        $form .= $this->oForm->createInput("PRIORITE", t('PRIORITE'), 3, "", false, $this->values['PRIORITE'], $this->readO, 3);
        $form .= $this->oForm->createHidden("INDICATEUR_LABEL", $this->values['INDICATEUR_LABEL']);
        $form .= $this->stopStandardForm();
        $this->setResponse($form);
    }
}