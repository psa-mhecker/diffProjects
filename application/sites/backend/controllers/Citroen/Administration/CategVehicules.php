<?php
require_once (Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php');
class Citroen_Administration_CategVehicules_Controller extends Citroen_Controller  
{  
    //protected $administration = false; //true  
    protected $multiLangue = true;
    protected $form_name = "categ_vehicule";   
    protected $field_id = "CATEG_VEHICULE_ID";  
    protected $defaultOrder = "CATEG_VEHICULE_ID";  
  
    protected function setListModel ()  
    {  
        $oConnection = Pelican_Db::getInstance();
        /* Valeurs Bindées pour la requête */
        $aBindToolBarList[':SITE_ID'] = (int)$_SESSION[APP]['SITE_ID'];
        $aBindToolBarList[':LANGUE_ID'] = (int)$_SESSION[APP]['LANGUE_ID'];
        $sqlToolBarList = "
                SELECT 
                    CATEG_VEHICULE_ID,
                    CATEG_VEHICULE_LABEL
                FROM 
                    #pref#_{$this->form_name}
                WHERE 
                    SITE_ID = :SITE_ID
                    AND LANGUE_ID = :LANGUE_ID ";
        if ($_GET['filter_search_keyword'] != '') {
            $sqlToolBarList.= " AND (
            CATEG_VEHICULE_LABEL like '%" . $_GET['filter_search_keyword'] . "%' 
            )
            ";
            }
                $sqlToolBarList.="ORDER BY {$this->listOrder}";
        $this->listModel = $oConnection->queryTab($sqlToolBarList,$aBindToolBarList);
    }  
  
    protected function setEditModel ()  
    {  
        $this->aBind[':SITE_ID'] = (int)$_SESSION[APP]['SITE_ID'];
        $this->aBind[':LANGUE_ID'] = (int)$_SESSION[APP]['LANGUE_ID'];
        $this->aBind[':' . $this->field_id] = (int)$this->id;
        
        $sSqlToolBarForm = <<<SQL
                SELECT 
                    *
                FROM 
                    #pref#_{$this->form_name}
                WHERE 
                    SITE_ID = :SITE_ID
                    AND LANGUE_ID = :LANGUE_ID
                    AND {$this->field_id} = :{$this->field_id}
                ORDER BY {$this->listOrder}
SQL;
          
        $this->editModel = $sSqlToolBarForm; 
    }  
  
    public function listAction ()  
    {  
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setFilterField("search_keyword", "<b>" . t('RECHERCHER') . " :</b>", "");
        $table->getFilter(1);
        $table->setValues($this->getListModel(), "CATEG_VEHICULE_ID");
        $table->addColumn(t('ID'), "CATEG_VEHICULE_ID", "20", "left", "", "tblheader", "CATEG_VEHICULE_ID");
        $table->addColumn(t('CATEG_VEHICULE_LABEL'), "CATEG_VEHICULE_LABEL", "80", "left", "", "tblheader", "CATEG_VEHICULE_LABEL");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "CATEG_VEHICULE_ID"), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => "CATEG_VEHICULE_ID", "" => "readO=true"), "center");
        $this->setResponse($table->getTable()); 
    }  
  
    public function editAction ()  
    {  
        parent::editAction();  
//------------ Begin startStandardForm ----------  
        $this->oForm = Pelican_Factory::getInstance('Form', true);  
        $this->oForm->bDirectOutput = false;  
        $form = $this->oForm->open(Pelican::$config['DB_PATH']);  
        $form .= $this->beginForm($this->oForm);  
        $form .= $this->oForm->beginFormTable();  
//------------ End startStandardForm ----------  
  
        $form .=  $this->oForm->createInput('CATEG_VEHICULE_LABEL', t('CATEG_VEHICULE_LABEL'), 255, '', true, $this->values['CATEG_VEHICULE_LABEL'], $this->readO, 44); 
  
//------------ Begin stopStandardForm ----------  
        $form .= $this->oForm->endFormTable();  
        $form .= $this->endForm($this->oForm);  
        $form .= $this->oForm->close();  
//------------ End stopStandardForm ----------  
        $this->setResponse($form);          
    }  
}  
?>
