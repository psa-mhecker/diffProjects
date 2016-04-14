<?php

require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';

class Ndp_CatGammeCentral_Controller extends Ndp_Controller
{
    protected $form_name = "vehicle_category";
    protected $field_id = "ID";
    protected $defaultOrder = "ID";

    protected function setListModel()
    {
        $connection = Pelican_Db::getInstance();
        $bind       = [];
        $sqlTypeCouleur = "
                SELECT
                    ID,
                    LABEL_CENTRAL,
                    MEDIA_ID
                FROM
                    #pref#_{$this->form_name}";
        $sqlTypeCouleur .= " ORDER BY {$this->listOrder}";
        $dataVehiculeCategory = $connection->queryTab($sqlTypeCouleur, $bind);
        $dataVehiculeCategoryWithPicto = $this->setPictoVehiculeCategory($dataVehiculeCategory);
        
        $this->listModel = $dataVehiculeCategoryWithPicto;
    }

    protected function setEditModel()
    {
        $this->aBind[':ID'] = (int) $this->id;
        $sqlTypeCouleur = <<<SQL
                SELECT
                    *
                FROM
                    #pref#_{$this->form_name}
                WHERE
                     ID = :ID
SQL;
                    
        $this->editModel = $sqlTypeCouleur;
    }

    public function listAction()
    {
        parent::listAction();
        $this->oForm = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
        $form = $this->oForm->createHeader(t('NDP_CAT_USES_IN_RBCS'), 4); 
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setValues($this->getListModel(), "ID");
        $table->addColumn(t('ID'), "ID", "20", "center", "", "tblheader", "ID");
        $table->addColumn(t('NDP_CAT_VEHICULE'), "LABEL_CENTRAL", "80", "center", "", "tblheader", "LABEL_CENTRAL");
        $table->addColumn(t('NDP_PICTO_CAT'), "PICTO", "10", "center");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "ID"), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => "ID", "" => "readO=true"), "center");
        
        $this->setResponse($form.$table->getTable());
    }

    public function editAction()
    {
        parent::editAction();
        $this->oForm = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
        $form  = $this->oForm->open(Pelican::$config['DB_PATH']);
        $form .= $this->beginForm($this->oForm);
        $form .= $this->oForm->beginFormTable();
        $form .= $this->oForm->createHidden('id', $this->id);
        $form .= $this->oForm->createInput('LABEL_CENTRAL', t('NDP_LABEL'), 255, '', true, $this->values['LABEL_CENTRAL'], $this->readO, 44);
        $form .= $this->oForm->createMedia("MEDIA_ID", t('PICTO'), true, "image", "", $this->values["MEDIA_ID"], $this->readO, true, false);
        $form .= $this->oForm->endFormTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();
        
        $this->setResponse($form);
    }
    
    /**
     * 
     * @param array $dataVehiculeCategory
     * 
     * @return array
     */
    public function setPictoVehiculeCategory(array $dataVehiculeCategory)
    {
        $dataTemp = [];
        foreach ($dataVehiculeCategory as $key => $data) {          
            if (empty($data['MEDIA_ID'])) {
                continue;
            }
            $data['PICTO'] = '<img src="'.Pelican::$config["MEDIA_HTTP"].Pelican_Media::getMediaPath($data['MEDIA_ID']).'" style="max-width: 50px;" />';
            $dataTemp[$key] = $data;
        }
        
        return $dataTemp;
    }
}
