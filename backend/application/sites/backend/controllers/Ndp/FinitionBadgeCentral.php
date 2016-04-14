<?php
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';

class Ndp_FinitionBadgeCentral_Controller extends Ndp_Controller
{
    protected $form_name    = "finishing_badge";
    protected $field_id     = "ID";
    protected $defaultOrder = "ID";

    protected function setListModel()
    {
        $connection = Pelican_Db::getInstance();
        $bind = [];
        $sql = "
                SELECT
                    ID,
                    LABEL,
                    CONCAT('<img src=\"',BADGE_URL,'\" style=\"height:60px;\" />') AS BADGE_IMG
                FROM
                    #pref#_{$this->form_name} 
                    ORDER BY ".$this->listOrder;
        $this->listModel = $connection->queryTab($sql, $bind);
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
        $table = Pelican_Factory::getInstance('List', '', '', 0, 0, 0, 'liste');
        $table->setValues($this->getListModel(), 'ID');
        $table->addColumn(t('ID'), 'ID', '20', 'center', '', 'tblheader', 'ID');
        $table->addColumn(t('NDP_LABEL'), 'LABEL', '50', 'center', '', 'tblheader', 'LABEL');
        $table->addColumn(t('BADGE'), 'BADGE_IMG', 80, 'center');
        $table->addInput(t('FORM_BUTTON_EDIT'), 'button', array('id' => 'ID'), 'center');
        $table->addInput(t('POPUP_LABEL_DEL'), 'button', array('id' => 'ID', '' => 'readO=true'), 'center');
        $script = '$("#_'.$this->defaultColor.'_5").hide();';
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
        $form .= $this->oForm->createInput('LABEL', t('LABEL'), 50, '', true, $this->values['LABEL'], $this->readO, 60);
        $form .= $this->oForm->createInput('BADGE_URL', t('BADGE'), 255, '', true, $this->values['BADGE_URL'], $this->readO, 60);
        if ($this->values['BADGE_URL']) {
            $form .= '<tr><td>&nbsp;</td><td><img src="'.$this->values['BADGE_URL'].'" style="height:60px ;"  /></td>';
        }
        $form .= $this->oForm->endFormTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();
        $this->setResponse($form);
    }
}
