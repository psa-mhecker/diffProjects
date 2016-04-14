<?php
use PsaNdp\WebserviceConsumerBundle\Webservices\ConfigurationEngineSelect;

require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';

class Ndp_SegmFinition_Controller extends Ndp_Controller
{
    protected $multiLangue    = true;
    protected $form_name      = "segmentation_finition_site";
    protected $field_id       = "ID";
    protected $defaultOrder   = "ORDER_TYPE";
    protected $tdUpselling    = 4;
    protected $tdParam        = 5;
    protected $wsGammeState   = false;
    protected $enableVersionStepUpselling = false;

    const ENABLE                  = 1;
    const DISABLE                 = 0;
    const WS_GAMME                = 14;
    const CODE_VP                 = 'VP';
    const DEFAULT_SEGMENT         = 1;

    protected function setListModel()
    {
        $oConnection = Pelican_Db::getInstance();
        $this->wsGammeState = $this->getStateOfWsGamme();
        $bind[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
        $bind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
        $sql = $this->getSql();
        $results = $oConnection->queryTab($sql, $bind);
       
        if ($this->wsGammeState) {
            foreach ($results as $keyRes => $valueRes) {
                $results[$keyRes]['CRIT'] = $this->enableVersionStepUpselling;
            }
        }

        $this->listModel = $results;
    }

    /**
     *
     * @return string
     */
    protected function getSqlNoGamme()
    {
        $sql = "
                SELECT
                    sf.ID,
                    sf.ID as id,
                    sf.CODE,
                    sf.CODE as code,
                    sf.LABEL,
                    sf.LABEL as label,
                    sfs.LABEL_LOCAL,
                    sfs.ENABLE_UPSELLING,
                    CONCAT(IFNULL(sfs.MARKETING_CRITERION,0), IFNULL(sfs.CLIENTELE_DESIGN,0)) as CRIT
                FROM
                    #pref#_segmentation_finition sf
                LEFT JOIN #pref#_{$this->form_name} sfs
                    ON  sfs.ID = sf.ID
                    AND sfs.SITE_ID = :SITE_ID
                    AND sfs.LANGUE_ID = :LANGUE_ID
                ORDER BY sfs.{$this->defaultOrder}";

        return $sql;
    }

    /**
     *
     * @return string
     */
    protected function getSql()
    {
        $sql = "
                SELECT
                    sfs.ID,
                    sfs.CODE,
                    sfs.LABEL_LOCAL,
                    sfs.ENABLE_UPSELLING,
                    TRIM(CONCAT(sfs.MARKETING_CRITERION, sfs.CLIENTELE_DESIGN)) as CRIT";
        if (!$this->wsGammeState) {
            $sql .= ",
                    sfs.ID as id,
                    sfs.CODE as code,
                    sf.LABEL,
                    sf.LABEL as label";
        }
        $sql .= "
                FROM
                    #pref#_{$this->form_name} sfs ";
        if (!$this->wsGammeState) {
            $sql .= ", #pref#_segmentation_finition sf ";
        }
        $sql .= "
                WHERE sfs.SITE_ID = :SITE_ID AND sfs.LANGUE_ID = :LANGUE_ID ";
        if (!$this->wsGammeState) {
            $sql .= "AND sfs.CODE = sf.CODE ";
        }
        $sql .= "
                ORDER BY sfs.{$this->defaultOrder}";

        return $sql;
    }

    protected function setEditModel()
    {
        $this->aBind[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
        $this->aBind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
        $this->aBind[':ID'] = (int) $this->id;

        switch ($this->wsGammeState) {
            case self::ENABLE:
                $sql = " SELECT *, sfs.CODE as PARENT_CODE FROM #pref#_{$this->form_name} sfs WHERE sfs.ID = :ID";
                break;
            case self::DISABLE:
                $sql = "SELECT *, sf.CODE as PARENT_CODE FROM #pref#_segmentation_finition sf LEFT JOIN #pref#_{$this->form_name} sfs ON sf.CODE = sfs.CODE WHERE sfs.ID = :ID";
                break;
        }

        $this->editModel = $sql;
    }

    public function listAction()
    {
        $this->wsGammeState = $this->getStateOfWsGamme();
        $this->updateModelTable();
        $form = '<tr><td colspan="2" class="formlib"><b>'.t('NDP_MSG_SEG_WITHOUT_FIN').'</b></td></tr><br><br>';
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setTableOrder("#pref#_".$this->form_name, "ID", "ORDER_TYPE");
        $table->setValues($this->getListModel(), "ID");
        $table->addColumn(t('ID'), "ID", "20", "center", "", "tblheader");
        $table->addColumn(t('CODE'), "CODE", "20", "center", "", "tblheader");
        if (!$this->wsGammeState) {
            $table->addColumn(t('NDP_LABEL_CENTRAL'), "LABEL", "80", "center", "", "tblheader");
            $this->tdUpselling = $this->tdUpselling + 1;
            $this->tdParam = $this->tdParam + 1;
        }
        $table->addColumn(t('NDP_LABEL_LOCAL'), "LABEL_LOCAL", "80", "center", "", "tblheader");
        $table->addColumn(t('NDP_UPSELLING'), "ENABLE_UPSELLING", "80", "center", "", "tblheader");
        $table->addColumn(t('NDP_SETTED'), "CRIT", "80", "center", "", "tblheader");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "ID"), "center");
        $this->button['back'] = '';
        $this->button['add'] = '';
        Backoffice_Button_Helper::init($this->button);
        $this->getView()->getHead()->setScript($this->getScriptForSettingLabelOfUpsellingOrParameters($this->tdUpselling), 'foot');
        $this->getView()->getHead()->setScript($this->getScriptForSettingLabelOfUpsellingOrParameters($this->tdParam, true), 'foot');


        $this->setResponse($form.$table->getTable());
    }



    protected function updateModelTable()
    {
        switch ($this->wsGammeState) {
            case true: 
                $container = $this->getContainer();
                $wsGamme = $container->get('ws_gamme');
                $params = [
                    'input' => [
                        'Site' => Pelican::$config['CODE_LANGUE_CLIENT'][$_SESSION[APP]['LANGUE_CODE']],
                        'Country' => $_SESSION[APP]['LANGUE_CODE'],
                        'Culture' => strtolower($_SESSION[APP]['LANGUE_CODE'])
                        ]
                    ];
                $gammes = $wsGamme->getSegmentation($params);
                $this->enableVersionStepUpselling = $wsGamme->getStateOfEnableStepUpselling($params);
                break;
            case false:
                $con = Pelican_Db::getInstance();
                $bind = [];
                $bind[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
                $bind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
                $gammes = $con->queryTab($this->getSqlNoGamme(), $bind);
                break;
        }
        $nouvellesGammes = [];
        if (!empty($gammes)) {
            foreach ($gammes as $gamme) {
                $nouvellesGammes[] = $this->addOrUpdateModel($gamme);
            }
        }

        $this->deleteOldModel($nouvellesGammes);
    }

    /**
     *
     * @param array $gamme
     *
     * @return string
     */
    protected function addOrUpdateModel($gamme)
    {
        $connection = Pelican_Db::getInstance();
        $bind[":ID"]                              = $gamme['id'];
        $bind[":CODE"]                            = $gamme['code'];
        $bind[":LABEL_LOCAL"]                     = $gamme['label'];
        $bind[":ENABLE_UPSELLING"]                = $gamme['hasUpselling'];
        $bind[":LANGUE_ID"]                       = $_SESSION[APP]['LANGUE_ID'];
        $bind[":SITE_ID"]                         = $_SESSION[APP]['SITE_ID'];

        $sql = "INSERT IGNORE INTO #pref#_{$this->form_name} (
                        ID_CENTRAL,
                        CODE,
                        LABEL_LOCAL,
                        ENABLE_UPSELLING,
                        LANGUE_ID,
                        SITE_ID
                        )
                        VALUES(
                         :ID,
                        ':CODE',
                        ':LABEL_LOCAL',
                        ':ENABLE_UPSELLING',
                        ':LANGUE_ID',
                        ':SITE_ID'
                        )";

        $connection->query($sql, $bind);

        return $gamme['code'];
    }

    /**
     *
     * @param array $gammes
     */
    protected function deleteOldModel($gammes)
    {
        $connection = Pelican_Db::getInstance();
        $sql = "DELETE FROM #pref#_{$this->form_name}";
        if (!empty($gammes)) {
            $sql .= ' WHERE LANGUE_ID = '.$_SESSION[APP]['LANGUE_ID'].' AND SITE_ID = '.$_SESSION[APP]['SITE_ID'].' AND CODE NOT IN ( "'.implode('","', $gammes).'" ) ';
        }

        $connection->query($sql, array());
    }

    /**
     *
     * @return boolean
     */
    public function getStateOfUpsellingInAllModels()
    {
        $connection = Pelican_Db::getInstance();
        $bind = [
          ':SITE_ID' =>  $_SESSION[APP]['SITE_ID'],
          ':LANGUE_ID' =>  $_SESSION[APP]['LANGUE_ID']
        ];
        $sql = "SELECT UPSELLING FROM #pref#_model_config WHERE SITE_ID = :SITE_ID AND LANGUE_ID = :LANGUE_ID";
        $state = $connection->queryRow($sql, $bind)['UPSELLING'];

        return $state;
    }

    /**
     *
     * @return boolean
     */
    public function getStateOfWsGamme()
    {
        $this->wsGammeState = false;
        $webservice = Pelican_Factory::getInstance('Webservice');
        $sitesWsConf = $webservice->getSitesWsConf();

        foreach ($sitesWsConf as $key => $value) {
            if ($value['site_id'] == $_SESSION[APP]['SITE_ID'] && $value['ws_id'] == self::WS_GAMME) {
                $this->wsGammeState = true;
                break;
            }
        }

        return $this->wsGammeState;
    }

    /**
     *
     * @param int $td
     *
     * @return string
     */
    public function getScriptForSettingLabelOfUpsellingOrParameters($td, $setYesToDefaultSegment = false)
    {
        $script = "
            $( document ).ready(function() {
                var running = true;
                var i = 1;
                while (running) {
                    var formTd = $('#td__'+i+'_".$td."');
                    if (typeof(formTd.html()) != 'undefined') {
                        if (formTd.html() != 0 && formTd.html() != '&nbsp;') {
                                formTd.html('".t('NDP_YES')."');
                            } else {
                                formTd.html('".t('NDP_NO')."');
                            }
                    ";
        if ($setYesToDefaultSegment) {
            $script .= "
                var formTdId = $('#td__'+i+'_1');
                if (formTdId.html() == ".self::DEFAULT_SEGMENT.") {
                        formTd.html('".t('NDP_YES')."');
                }
                ";
        }
        $script .= "
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
        $this->wsGammeState = $this->getStateOfWsGamme();
        $this->oForm = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
        $form  = $this->oForm->open(Pelican::$config['DB_PATH']);
        $form .= $this->beginForm($this->oForm);
        $form .= $this->oForm->beginFormTable();
        $form .= $this->oForm->createHidden('id', $this->id);
        if (!$this->wsGammeState) {
            $form .= $this->oForm->createHidden('ID_CENTRAL', $this->values['ID_CENTRAL']);
        }
        $form .= $this->oForm->createHidden('CODE', $this->values['PARENT_CODE']);
        $form .= $this->oForm->createLabel(t('CODE'), $this->values['PARENT_CODE']);
        if (!$this->wsGammeState) {
            $form .= $this->oForm->createLabel(t('NDP_LABEL_CENTRAL'), $this->values['LABEL']);
        }
        $form .= $this->oForm->createInput('LABEL_LOCAL', t('NDP_LABEL_LOCAL'), 255, '', true, $this->values['LABEL_LOCAL'], $this->readO, 44);
        $form .= $this->oForm->createComment(t('NDP_MSG_ENABLING_UPSELLING'));
        $form .= $this->oForm->createComment(t('NDM_MSG_PREVIOUS_CONF_REPLACE'));
        $stateOfUpsellingInAllModels = $this->getStateOfUpsellingInAllModels();
        if (!isset($this->values['ENABLE_UPSELLING']) && $this->wsGammeState) {
            $this->values['ENABLE_UPSELLING'] = self::ENABLE;
        }
        $readO = false;
        if (!$stateOfUpsellingInAllModels) {
            $readO = true;
            $this->values['ENABLE_UPSELLING'] = self::DISABLE;
        }
        $form .= $this->oForm->createRadioFromList(
            'ENABLE_UPSELLING',
            t('NDP_ENABLING_UPSELLING'),
            array(
                self::ENABLE => t("NDP_YES"),
                self::DISABLE => t("NDP_NO")
            ),
            $this->values['ENABLE_UPSELLING'], false, $readO
        );
        $form .= $this->oForm->createHidden('OLD_UPSELLING', $this->values['ENABLE_UPSELLING']);
        $form .= $this->oForm->createHr();
        $container = $this->getContainer();
        $marketingCriterion = [];
        $codePaysById = Pelican_Cache::fetch('CodePaysById');
        $langueById = Pelican_Cache::fetch('Language');
        /** @var ConfigurationEngineSelect  $engineConfiguration */
        $engineConfiguration = $container->get('configuration_engine_select');
        $engineConfiguration->addContext('LanguageID', strtolower($langueById[$_SESSION[APP]['LANGUE_ID']]['LANGUE_CODE']));
        $engineConfiguration->addContext('Country', $codePaysById[$_SESSION[APP]['SITE_ID']]);
        try {
            $marketingCriterion = $engineConfiguration->getVersionsCriterion();
        } catch (\Exception $e) {
            $params['%country%'] = $codePaysById[$_SESSION[APP]['SITE_ID']];
            $params['%language%'] = strtolower($langueById[$_SESSION[APP]['LANGUE_ID']]['LANGUE_CODE']);
            $this->addFlashMessage(strtr(t("NDP_MSG_WS_NO_ANSWER_FOR_COUNTRY_LANGUAGE"), $params), 'warning');
        }
        $selected = array();
        if ($this->values['MARKETING_CRITERION'] != '') {
            $selected = explode('#', $this->values['MARKETING_CRITERION']);
        }
        $form .= $this->oForm->createAssocFromList(
            null,
            'MARKETING_CRITERION',
            t('NDP_MARKETING_CRITERION'),
            $marketingCriterion,
            $selected,
            false,
            true,
            $this->readO,
            5,
            200,
            false,
            '',
            false
        );
        $form .= $this->oForm->createHr();
        $selected = array();
        if ($this->values['CLIENTELE_DESIGN'] != '') {
            $selected = explode('#', $this->values['CLIENTELE_DESIGN']);
        }
        $customerType = [];
        try {
            $customerType = $engineConfiguration->getCustomerType();
        } catch (\Exception $e) {
            // is catched by first try/catch
        }

        $form .= $this->oForm->createAssocFromList(
            null,
            'CLIENTELE_DESIGN',
            t('NDP_CLIENTELE_DESIGN'),
            $customerType,
            $selected,
            false,
            true,
            $this->readO,
            5,
            200,
            false,
            '',
            false
        );
        $form .= $this->oForm->endFormTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();

        $this->setResponse($form);
    }

    /**
     * Save.
     *
     * @return none
     */
    public function saveAction()
    {
        $values = Pelican_Db::$values;
        $connection = Pelican_Db::getInstance();
        if (!empty(Pelican_Db::$values['MARKETING_CRITERION'])) {
            Pelican_Db::$values['MARKETING_CRITERION'] = implode('#', Pelican_Db::$values['MARKETING_CRITERION']);
        }
        if (!empty(Pelican_Db::$values['CLIENTELE_DESIGN'])) {
            Pelican_Db::$values['CLIENTELE_DESIGN'] = implode('#', Pelican_Db::$values['CLIENTELE_DESIGN']);
        }
        if (Pelican_Db::$values['ENABLE_UPSELLING'] != Pelican_Db::$values['OLD_UPSELLING']) {
            $bind = [":ENABLE_UPSELLING" => Pelican_Db::$values['ENABLE_UPSELLING'], ':VEHICULE_USE' => Pelican_Db::$values['CODE']];
            $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
            $bind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
            $query = "UPDATE #pref#_ws_gdg_model_silhouette_upselling SET"
            . " UPSELLING = :ENABLE_UPSELLING WHERE VEHICULE_USE = ':VEHICULE_USE' AND SITE_ID = :SITE_ID AND LANGUE_ID = :LANGUE_ID";

             $connection->query($query, $bind);
        }
        parent::saveAction();
        Pelican_Db::$values = $values;
    }
}

