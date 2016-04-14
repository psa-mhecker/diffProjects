<?php

require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';

class Ndp_TypeCouleur_Controller extends Ndp_Controller
{
    protected $multiLangue = true;
    protected $form_name = "type_couleur_site";
    protected $field_id = "ID";
    protected $defaultOrder = "ID";
    
    protected function setListModel()
    {
        $oConnection = Pelican_Db::getInstance();     
        $bind[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
        $bind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
        $sqlTypeCouleur = "
                SELECT
                    tc.ID,
                    CODE,
                    LABEL_CENTRAL,
                    LABEL_LOCAL
                FROM
                    #pref#_type_couleur tc
                LEFT JOIN #pref#_{$this->form_name} tcs
                    ON tcs.ID = tc.ID 
                    AND SITE_ID = :SITE_ID
                    AND LANGUE_ID = :LANGUE_ID
";
        $sqlTypeCouleur .= "ORDER BY ORDER_TYPE";
        $this->listModel = $oConnection->queryTab($sqlTypeCouleur, $bind);
    }

    protected function setEditModel()
    {
        $this->aBind[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
        $this->aBind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
        $this->aBind[':ID'] = (int) $this->id;
        $sqlTypeCouleur = <<<SQL
                SELECT
                        *
                FROM
                    #pref#_type_couleur tc
                LEFT JOIN #pref#_{$this->form_name} tcs
                    ON tcs.ID = tc.ID 
                    AND SITE_ID = :SITE_ID
                    AND LANGUE_ID = :LANGUE_ID
                WHERE tc.ID = :ID
SQL;
        $this->editModel = $sqlTypeCouleur;
    }

    protected function updateModelTable()
    {
        $con = Pelican_Db::getInstance();

        $typeCouleurs = $con->queryTab($this->getSqlTypeCouleur(), []);
        if (!empty($typeCouleurs)) {
            foreach ($typeCouleurs as $couleur) {
                $this->addOrUpdateModel($couleur);
            }
        }
    }

    /**
     *
     * @return string
     */
    protected function getSqlTypeCouleur()
    {
        $sql = "
                SELECT
                    tc.ID,
                    tc.LABEL_CENTRAL
                FROM
                    #pref#_type_couleur tc
        ";

        return $sql;

    }
    /**
     *
     * @param array $couleur
     *
     */
    protected function addOrUpdateModel($couleur)
    {
        $connection = Pelican_Db::getInstance();
        $bind[":ID"]                              = $couleur['ID'];
        $bind[":LANGUE_ID"]                       = $_SESSION[APP]['LANGUE_ID'];
        $bind[":SITE_ID"]                         = $_SESSION[APP]['SITE_ID'];
        $bind[":ORDER_TYPE"]                  = $this->getMaxOrder();

        $sql = "INSERT IGNORE INTO #pref#_{$this->form_name} (
                        ID,
                        LANGUE_ID,
                        SITE_ID,
                        ORDER_TYPE
                        )
                        VALUES(
                         :ID,
                        ':LANGUE_ID',
                        ':SITE_ID',
                        :ORDER_TYPE
                        )";

        $connection->query($sql, $bind);
    }

    public function listAction()
    {
        $this->updateModelTable();
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setTableOrder("#pref#_" . $this->form_name, "ID", "ORDER_TYPE");
        $table->setValues($this->getListModel(), "ID");
        $table->addColumn(t('ID'), "ID", "20", "center", "", "tblheader");
        $table->addColumn(t('CODE'), "CODE", "20", "center", "", "tblheader");
        $table->addColumn(t('NDP_LABEL_CENTRAL'), "LABEL_CENTRAL", "80", "center", "", "tblheader");
        $table->addColumn(t('NDP_LABEL_LOCAL'), "LABEL_LOCAL", "80", "center", "", "tblheader");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "ID"), "center");
        $this->button['back'] = '';
        $this->button['add'] = '';
        Backoffice_Button_Helper::init($this->button);
        
        $this->setResponse($table->getTable());
    }

    public function editAction()
    {        
        parent::editAction();
        $this->oForm = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
        $form = $this->oForm->open(Pelican::$config['DB_PATH']);
        $form .= $this->beginForm($this->oForm);
        $form .= $this->oForm->beginFormTable();
        $form .=  $this->oForm->createHidden('id', $this->id);
        $form .=  $this->oForm->createHidden('CODE', $this->values['CODE']);
        $form .=  $this->oForm->createHidden('LABEL_CENTRAL', $this->values['LABEL_CENTRAL']);
        if(!isset( $this->values['ORDER_TYPE'])) {
            $this->values['ORDER_TYPE'] = $this->getMaxOrder();
        }
        $form .=  $this->oForm->createHidden('ORDER_TYPE', $this->values['ORDER_TYPE']);
        $form .=  $this->oForm->createLabel(t('CODE'), $this->values['CODE']);
        $form .=  $this->oForm->createLabel(t('NDP_LABEL_CENTRAL'), $this->values['LABEL_CENTRAL']);
        $form .=  $this->oForm->createInput('LABEL_LOCAL', t('NDP_LABEL_LOCAL'), 255, '', true, $this->values['LABEL_LOCAL'], $this->readO, 44);
        $form .= $this->oForm->endFormTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();
        $this->setResponse($form);
    }

    private function getMaxOrder()
    {
        $con = Pelican_Db::getInstance();
        $bind[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
        $bind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
        $sql = 'SELECT
                      MAX(ORDER_TYPE) +1
                    FROM
                        #pref#_'.$this->form_name.'
                   WHERE
                    SITE_ID = :SITE_ID
                    AND LANGUE_ID = :LANGUE_ID
                    ';
        return $con->queryItem($sql, $bind);

    }
}

