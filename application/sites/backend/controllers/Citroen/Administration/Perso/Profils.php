<?php
require_once (Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php');
class Citroen_Administration_Perso_Profils_Controller extends Citroen_Controller
{
    protected $administration = true;
    protected $form_name = "perso_profile";
    protected $field_id = "PROFILE_ID";
    protected $defaultOrder = "PROFILE_ID";
    protected $decacheBack = array(
        array('Frontend/Citroen/Perso/Profils')
    );

    protected function setListModel()
    {	
        $oConnection = Pelican_Db::getInstance();
        $sqlList = "SELECT 
                            * 
                    FROM 
                            #pref#_perso_profile
                    ORDER BY " . $this->listOrder;
        
        $this->listModel = $oConnection->queryTab($sqlList);
    }

    protected function setEditModel()
    {
        $this->aBind[':'.$this->field_id] = (int)$this->id;
        $this->editModel = "SELECT 
                                    * 
                            FROM 
                                    #pref#_perso_profile
                            WHERE   ".$this->field_id." = :" . $this->field_id;
    }

    public function listAction()
    {
        parent::listAction();
        $this->aButton["add"] = "";
        Backoffice_Button_Helper::init($this->aButton);
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setValues($this->getListModel(), "PROFILE_ID");      
        $table->addColumn(t('PROFIL'), "PROFILE_LABEL", "90", "left", "", "tblheader", "PROFILE_LABEL");
        $table->addColumn(t('PRIORITE'), "PRIORITE", "90", "center", "", "tblheader", "PRIORITE");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "PROFILE_ID"), "center");
        $this->setResponse($table->getTable());
    }

    public function editAction()
    {	
        $oConnection = Pelican_Db::getInstance();
        parent::editAction();
        $form = $this->startStandardForm();        
        $form .= $this->oForm->createLabel(t('PROFIL'), $this->values['PROFILE_LABEL']);
        $form .= $this->oForm->createInput("PRIORITE", t('PRIORITE'), 3, "", false, $this->values['PRIORITE'], $this->readO, 3);
        $form .= $this->oForm->createHidden("PROFILE_LABEL", $this->values['PROFILE_LABEL']);
        $form .= $this->stopStandardForm();
        $this->setResponse($form);
    }
}