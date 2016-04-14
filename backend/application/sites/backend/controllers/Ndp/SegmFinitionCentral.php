<?php
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';

class Ndp_SegmFinitionCentral_Controller extends Ndp_Controller
{
    protected $form_name    = "segmentation_finition";
    protected $field_id     = "ID";
    protected $defaultOrder = "ORDER_TYPE";
    protected $defaultSegment = 1;

    protected function setListModel()
    {
        $oConnection     = Pelican_Db::getInstance();
        $bind            = [];
        $sqlTypeCouleur  = "
                SELECT
                    ID,
                    CODE,
                    LABEL
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
        $table->setTableOrder("#pref#_".$this->form_name, "ID", "ORDER_TYPE");
        $table->addColumn(t('ID'), "ID", "20", "center", "", "tblheader");
        $table->addColumn(t('CODE'), "CODE", "20", "center", "", "tblheader");
        $table->addColumn(t('NDP_LABEL_SEGMENT'), "LABEL", "80", "center", "", "tblheader");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "ID"), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => "ID", "" => "readO=true"), "center");
        $this->getView()->getHead()->setScript($this->getScriptWhichHideDeleteButtonForDefaultSegment(), 'foot');

        $this->setResponse($table->getTable());
    }

    /**
     *
     * @return string
     */
    public function getScriptWhichHideDeleteButtonForDefaultSegment()
    {
        $script = "
            $( document ).ready(function() {
                var running = true;
                var i = 1;
                while (running) {
                    var formTd = $('#td__'+i+'_1');
                    if (typeof(formTd.html()) != 'undefined') {
                        if (formTd.html() == ".$this->defaultSegment.") {
                            $('#_'+i+'_5').hide();
                            running = false;
                            }
                        i++;
                    } else {
                        running = false;
                    }
                }
            });
        ";
        
        return $script;
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
        $readO = true;
        if ($this->id != $this->defaultSegment) {
            $readO = $this->readO;
        }
        $form .= $this->oForm->createHidden("ORDER_TYPE", $this->values['ORDER_TYPE']);
        $form .= $this->oForm->createInput('CODE', t('CODE'), 255, '', true, $this->values['CODE'], $readO, 5);
        $form .= $this->oForm->createInput('LABEL', t('NDP_LABEL'), 255, '', true, $this->values['LABEL'], $this->readO, 44);
        $form .= $this->oForm->endFormTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();
        $this->setResponse($form);
    }
}
