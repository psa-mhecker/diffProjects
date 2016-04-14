<?php

require_once Pelican::$config['APPLICATION_CONTROLLERS'] . '/Ndp.php';

class Ndp_ServConnAppliMobile_Controller extends Ndp_Controller
{
    protected $multiLangue = true;
    protected $administration = true;
    protected $form_name = "benefice";
    protected $field_id = "ID";
    protected $defaultOrder = "ID";

    protected function setListModel()
    {
        $connection = Pelican_Db::getInstance();
        $bind = [
            ':SITE_ID' => (int) $_SESSION[APP]['SITE_ID'],
            ':LANGUE_ID' => (int) $_SESSION[APP]['LANGUE_ID']
        ];
        $sql = "
                SELECT
                    ID,
                    LABEL
                FROM
                    #pref#_{$this->form_name}";
        $sql .= " WHERE
            SITE_ID = :SITE_ID
            AND LANGUE_ID = :LANGUE_ID
            ORDER BY {$this->listOrder}";
        $data = $connection->queryTab($sql, $bind);
        
        $this->listModel = $data;
    }

    protected function setEditModel()
    {
        $this->aBind[':ID'] = (int) $this->id;
        $sql = <<<SQL
                SELECT
                    *
                FROM
                    #pref#_{$this->form_name}
                WHERE
                     ID = :ID
SQL;
                    
        $this->editModel = $sql;
    }

    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setValues($this->getListModel(), "ID");
        $table->addColumn(t('ID'), "ID", "20", "center", "", "tblheader", "ID");
        $table->addColumn(t('NDP_BENEFICE'), "LABEL", "45", "center", "", "tblheader", "LABEL");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "ID"), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => "ID", "" => "readO=true"), "center");
        
        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        $this->multiLangue = false;
        parent::editAction();
        $this->oForm = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
        $form  = $this->oForm->open(Pelican::$config['DB_PATH']);
        $form .= $this->beginForm($this->oForm);
        $form .= $this->oForm->beginFormTable();
        $form .= $this->oForm->createHidden('ID', $this->id);
        $form .= $this->oForm->createInput('LABEL', t('NDP_BENEFICE'), 45, '', true, $this->values['LABEL'], $this->readO, 44);
        $form .= $this->oForm->endFormTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();
        
        $this->setResponse($form);
    }
}
