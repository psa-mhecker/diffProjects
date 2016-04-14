<?php
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';

class Ndp_FinitionCouleurCentral_Controller extends Ndp_Controller
{
    protected $form_name    = "finishing_color";
    protected $field_id     = "ID";
    protected $defaultOrder = "ID";
    const DEFAULT_COLOR = 1;
    

    protected function setListModel()
    {
        $connection = Pelican_Db::getInstance();
        $bind = [];
        $sql = '
                SELECT
                    ID,
                    LABEL,
                    CONCAT("<span style=\"display:block;width:100px;height:20px;background-color:",COLOR_CODE,";\" >&nbsp;</span>") as  COLOR_CODE
                FROM
                    #pref#_'.$this->form_name.' 
                    ORDER BY '.$this->listOrder;

        $this->listModel = $connection->queryTab($sql, $bind);
    }

    protected function setEditModel()
    {
        $this->aBind[':ID'] = (int) $this->id;
        $sqlTypeCouleur     = <<<SQL
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
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setValues($this->getListModel(), "ID");
        $table->addColumn(t('ID'), 'ID', '20', 'center', '', 'tblheader', 'ID');
        $table->addColumn(t('NDP_LABEL'), 'LABEL', '50', 'center', '', 'tblheader', 'LABEL');
        $table->addColumn(t('COLOR'), "COLOR_CODE", "80", 'center');
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "ID"), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => "ID", "" => "readO=true"), "center");
        $script = "$('#_".self::DEFAULT_COLOR."_5').hide();";
        $this->getView()->getHead()->setScript($script, 'foot');

        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        parent::editAction();
        $this->oForm                = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
        $form  = $this->oForm->open(Pelican::$config['DB_PATH']);
        $form .= $this->beginForm($this->oForm);
        $form .= $this->oForm->beginFormTable();
        $form .= $this->oForm->createHidden('id', $this->id);
        $readO = $this->readO || ($this->id == self::DEFAULT_COLOR);
        $form .= $this->oForm->createInput('LABEL', t('NDP_LABEL'), 50, '', true, $this->values['LABEL'], $readO, 50);
        $form .= $this->oForm->createInput('COLOR_CODE', t('COLOR'), 8, 'color', true, $this->values['COLOR_CODE'], $this->readO, 10);
        $form .= $this->oForm->endFormTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();
        $this->setResponse($form);
    }
    
    public function deleteAction() {
        $con = Pelican_Db::getInstance();
        $sql = "UPDATE #pref#_finishing_site SET `COLOR_ID`= :DEFAULT_COLOR WHERE `COLOR_ID`=:COLOR_ID";
        $bind = [':COLOR_ID' => $this->id, ':DEFAULT_COLOR' => self::DEFAULT_COLOR];
        $con->query($sql, $bind);
        
        parent::deleteAction();
    }
}
