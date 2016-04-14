<?php

require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';

class Ndp_ServConn_Controller extends Ndp_Controller
{
    protected $multiLangue = true;
    protected $administration = true;
    protected $form_name = "services_connect";
    protected $form_name_benefice = "benefice";
    protected $field_id = "id";
    protected $defaultOrder = "id";
    
    const GABARIT_FICHE_SERVICE = "NDP_G29_SERVICE_CONNECTE";
    const TYPE_PRIX = "PRICE_SERVICE_CONNECTE";
    const A_PARTIR = 1;
    const AUTRE = 2;

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
        $table->addColumn(t('NDP_SERVICE_CONNECTE'), "LABEL", "45", "center", "", "tblheader", "LABEL");
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
        $form .= $this->createFirstFieldsOfForm();
        $form .= $this->createPriceForm();
        $form .= $this->oForm->endFormTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();
        $this->setResponse($form);
    }
    
    /**
     * 
     * @return string
     */
    public function createFirstFieldsOfForm()
    {
        $form = $this->oForm->createHidden('ID', $this->id);
        $form .= $this->oForm->createInput('LABEL', t('NDP_SERVICE_CONNECTE'), 45, '', true, $this->values['LABEL'], $this->readO, 44);
        $form .= $this->oForm->createInput('DESCRIPTION', t('NDP_DESCRIPTION'), 100, '', true, $this->values['DESCRIPTION'], $this->readO, 44);
        $listeUrlFicheService = $this->getListUrlGabaritFicheService();
        $form .= $this->oForm->createComboFromList('URL', t('NDP_URL_FICHE_SERVICE'), $listeUrlFicheService, $this->values['URL'], false, $this->readO, 1, false);
        $form .= $this->createBeneficesForm();
        $form .= $this->oForm->createInput('MENTIONS_LEGALES', t('NDP_TERMS_CONDITIONS'), 50, '', false, $this->values['MENTIONS_LEGALES'], $this->readO, 44);
        $form .= $this->oForm->createMedia("VISUEL_APPLICATION", t('NDP_VISUEL_APPLICATION'), false, "image", "", $this->values["VISUEL_APPLICATION"], $this->readO, true, false, "VISUEL_APPLICATION");
        $form .= $this->oForm->createMedia("VISUEL_SELECTEUR", t('NDP_VISUEL_SELECTEUR'), false, "image", "", $this->values["VISUEL_SELECTEUR"], $this->readO, true, false, "VISUEL_SELECTEUR");
    
        return $form;
    }
    
    /**
     * 
     * @return string
     */
    public function createBeneficesForm()
    {
        $sqlData = $this->getBenefices();
        $selected = [];
        if ($this->values['BENEFICES'] != '') {
            $selected = explode('#', $this->values['BENEFICES']);
        }
        $form = $this->oForm->createAssocFromSql('', 'BENEFICES', t('NDP_BENEFICE'), $sqlData, $selected, true, true, $this->readO, 5, 200, false, '', '', '', true, false, false);
        
        return $form;
    }
    
    /**
     * 
     * @return string
     */
    public function createPriceForm()
    {
        $type = self::TYPE_PRIX;
        $js = Cms_Page_Ndp::addJsContainerRadio($type);
        $typAffichage = [
            self::A_PARTIR  => t('A_PARTIR_DE'),
            self::AUTRE     => t('NDP_OTHER')
        ];
        if (empty($this->values['PRIX'])) {
             $this->values['PRIX'] = self::A_PARTIR;
        }
        $form  = $this->oForm->createRadioFromList('PRIX', t('NDP_FILTER_PRICE'), $typAffichage, $this->values['PRIX'], true, $this->readO, 'h', false, $js);
        $form .= Cms_Page_Ndp::addHeadContainer(self::A_PARTIR, $this->values['PRIX'], $type);
        $form .= $this->oForm->createInput('A_PARTIR_DE', t('A_PARTIR_DE'), 30, 'reel', true, $this->values['A_PARTIR_DE'], $this->readO, 44);
        $form .= Cms_Page_Ndp::addFootContainer();
        $form .= Cms_Page_Ndp::addHeadContainer(self::AUTRE, $this->values['PRIX'], $type);
        $form .= $this->oForm->createInput('AUTRE', t('NDP_OTHER'), 30, '', true, $this->values['AUTRE'], $this->readO, 44);
        $form .= Cms_Page_Ndp::addFootContainer();
        $form .= $this->oForm->createJS("
                if ($('input[name=PRIX]:checked', '#fForm').val() == '".self::A_PARTIR."') {
                    var rx = /^\d+(?:\.\d{1,2})?$/ 
                    var decimal = $('#A_PARTIR_DE').val();
                    if(rx.test(decimal) == false) { 
                        alert('".t('NDP_ONLY_TWO_DECIMAL_FROM')."'); 
                        return false;
                    }
                }");
        
        return $form;
    }
    
    public function getBenefices()
    {
        $sqlData = '
            SELECT
                    ID,
                    LABEL
            FROM
                    #pref#_'.$this->form_name_benefice.' 
            WHERE
                    SITE_ID = '.(int) $_SESSION[APP]['SITE_ID'].'
                    AND LANGUE_ID = '.(int) $_SESSION[APP]['LANGUE_ID'].'
            ORDER BY ID;';      
        
        return $sqlData;   
    }
    
    /**
     * 
     * @return string
     */
    public function getListUrlGabaritFicheService()
    {
        $connection = Pelican_Db::getInstance();
        $bind = [
             ':SITE_ID' => (int) $_SESSION[APP]['SITE_ID'],
             ':LANGUE_ID' => (int) $_SESSION[APP]['LANGUE_ID']
        ];
        $sql = "
                SELECT DISTINCT
                    pv.PAGE_CLEAR_URL,
                    pv.PAGE_TITLE_BO
                FROM
                    #pref#_page_version pv, #pref#_page p";
        $sql .= " WHERE
            p.SITE_ID = :SITE_ID
            AND pv.PAGE_VERSION = p.PAGE_CURRENT_VERSION 
            AND pv.LANGUE_ID = :LANGUE_ID
            AND pv.STATE_ID = 4
            AND pv.TEMPLATE_PAGE_ID = ".Pelican::$config['TEMPLATE_PAGE'][self::GABARIT_FICHE_SERVICE]."
            ORDER BY pv.PAGE_ID";
        $result = $connection->queryTab($sql, $bind);
        $listUrl = [];
        foreach ($result as $key => $values) {
            $listUrl[$values['PAGE_CLEAR_URL']] = $values['PAGE_TITLE_BO'];
        }
        
        return $listUrl;        
    }
    
    public function saveAction()
    {
        if (!empty(Pelican_Db::$values['BENEFICES'])) {
            Pelican_Db::$values['BENEFICES'] = implode('#', Pelican_Db::$values['BENEFICES']);
        }
        parent::saveAction();
    }
}
