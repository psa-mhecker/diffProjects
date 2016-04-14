<?php
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';

class Ndp_TypeCouleurCentral_Controller extends Ndp_Controller
{
    protected $form_name    = "type_couleur";
    protected $field_id     = "ID";
    protected $defaultOrder = "ID";
    protected $defaultColor = 1;

    protected function setListModel()
    {
        $oConnection     = Pelican_Db::getInstance();
        $bind            = [];
        $sqlTypeCouleur  = "
                SELECT
                    ID,
                    CODE,
                    LABEL_CENTRAL
                FROM
                    #pref#_{$this->form_name}";
        $sqlTypeCouleur .= " ORDER BY {$this->listOrder}";
        $this->listModel = $oConnection->queryTab($sqlTypeCouleur, $bind);
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
        $table->addColumn(t('ID'), "ID", "20", "center", "", "tblheader", "ID");
        $table->addColumn(t('CODE'), "CODE", "20", "center", "", "tblheader",
            "CODE");
        $table->addColumn(t('NDP_LABEL_CENTRAL'), "LABEL_CENTRAL", "80",
            "center", "", "tblheader", "LABEL_CENTRAL");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "ID"),
            "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button",
            array("id" => "ID", "" => "readO=true"), "center");
        $script = "$('#_".$this->defaultColor."_5').hide();";
        $this->getView()->getHead()->setScript($script, 'foot');

        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        parent::editAction();
        $this->oForm                = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
        $form                       = $this->oForm->open(Pelican::$config['DB_PATH']);
        $form .= $this->beginForm($this->oForm);
        $form .= $this->oForm->beginFormTable();
        $form .= $this->oForm->createHidden('id', $this->id);
        if ($this->id != $this->defaultColor) {
            $form .= $this->oForm->createInput('CODE', t('CODE'), 2, '', true,
                $this->values['CODE'], $this->readO, 2);
        }
        $form .= $this->oForm->createInput('LABEL_CENTRAL',
            t('NDP_LABEL_CENTRAL'), 255, '', true,
            $this->values['LABEL_CENTRAL'], $this->readO, 44);
        $form .= $this->oForm->endFormTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();
        $this->setResponse($form);
    }
}
