<?php
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';

class Ndp_ModelGroupingSilhouetteSite_Controller extends Ndp_Controller
{
    const FIELD_COLOR   = "COLOR_ID";

    protected $multiLangue = true;
    protected $form_name = "ws_gdg_model_silhouette_site";
    protected $defaultOrder = 'SITE_ID, LCDV6';
    protected $field_id = "ID";
    private $wsConfigurator;
    private $wsWebstore;

    const STRIP_NEW = 1;
    const STRIP_SPECIAL_OFFER = 2;
    const STRIP_SPECIAL_SERIE = 3;
    const KEY_ORDER_AO = 1;
    const KEY_ORDER_PRICE_ASC = 2;
    const KEY_ORDER_PRICE_DESC = 3;
    const ENABLED = 1;
    const DISABLED = 0;
    const GROUPING = 1;
    const SILHOUETTE = 2;
    const WEBSTORE = "ZONE_WEBSTORE";
    const CONFIGURATOR = "ZONE_VP";
    const OFF = -2;

    /**
     * 
     * @param \Pelican_Request $request
     */
    public function __construct(\Pelican_Request $request)
    {
        parent::__construct($request);

        $webservice = Pelican_Factory::getInstance('Webservice');
        $webservice->setSiteId($this->id)->setName(Ndp_Webservice::CONFIGURATOR)->getValues();
        $this->wsConfigurator = $webservice;

        $webservice->setName(Ndp_Webservice::WEBSTORE)->getValues();
        $this->wsWebstore = $webservice;
    }

    protected function setListModel()
    {
//        parent::listAction();
        $connection = Pelican_Db::getInstance();
        $bind[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
        $bind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];

        $query = 'SELECT *
                    FROM
                    #pref#_'.$this->form_name.'
                    WHERE
                    SITE_ID = :SITE_ID
                    AND LANGUE_ID = :LANGUE_ID
                    ORDER BY '.$this->listOrder;

        $this->listModel = $connection->queryTab($query, $bind);
    }

    protected function updateModelTable()
    {
        $container = $this->getContainer();
        $rangeManager = $container->get('range_manager');
        $parameters = [];
        $codePaysById = Pelican_Cache::fetch('Ndp/CodePaysById');
        $parameters['languages'] = strtolower($_SESSION[APP]['LANGUE_CODE']);
        $parameters['countries'] = $codePaysById[$_SESSION[APP]['SITE_ID']];
        $models = $rangeManager->getModelsGroupingSilhouetteFromSearch($parameters);
        $lcdv6 = [];
        if (!empty($models)) {
            foreach ($models as $model) {
                $lcdv6[] = $this->addOrUpdateModel($model);
            }
        }
        
        $this->deleteOldModel($lcdv6);
    }

    protected function addOrUpdateModel($model)
    {
        $connection = Pelican_Db::getInstance();

        $bind[":LCDV6"]                           = $model['LCDV6'];
        $bind[":GENDER"]                          = $model['GENDER'];
        $bind[":GROUPING_CODE"]                   = $model['GROUPING_CODE'];
        $bind[":COMMERCIAL_LABEL"]                = $model['COMMERCIAL_LABEL'];
        $bind[':NEW_COMMERCIAL_STRIP']            = $model['NEW_COMMERCIAL_STRIP'];
        $bind[':SPECIAL_OFFER_COMMERCIAL_STRIP']  = $model['SPECIAL_OFFER_COMMERCIAL_STRIP'];
        $bind[':SPECIAL_SERIES_COMMERCIAL_STRIP'] = $model['SPECIAL_SERIES_COMMERCIAL_STRIP'];
        $bind[':SHOW_IN_CONFIG']                  = $model['SHOW_IN_CONFIG'];
        $bind[':STOCK_WEBSTORE']                  = $model['STOCK_WEBSTORE'];
        $bind[':LANGUE_ID']                       = $model['LANGUE_ID'];
        $bind[':SITE_ID']                         = $model['SITE_ID'];

        $sql = "INSERT INTO #pref#_{$this->form_name} (
                        LCDV6,
                        GENDER,
                        GROUPING_CODE,
                        COMMERCIAL_LABEL,
                        NEW_COMMERCIAL_STRIP,
                        SPECIAL_OFFER_COMMERCIAL_STRIP,
                        SPECIAL_SERIES_COMMERCIAL_STRIP,
                        SHOW_IN_CONFIG,
                        STOCK_WEBSTORE,
                        LANGUE_ID,
                        SITE_ID
                        )
                        VALUES(
                        ':LCDV6',
                        ':GENDER',
                        ':GROUPING_CODE',
                        ':COMMERCIAL_LABEL',
                        ':NEW_COMMERCIAL_STRIP',
                        ':SPECIAL_OFFER_COMMERCIAL_STRIP',
                        ':SPECIAL_SERIES_COMMERCIAL_STRIP',
                        ':SHOW_IN_CONFIG',
                        ':STOCK_WEBSTORE',
                        ':LANGUE_ID',
                        ':SITE_ID'
                        )
                ON DUPLICATE KEY UPDATE GENDER = ':GENDER' , GROUPING_CODE = ':GROUPING_CODE', COMMERCIAL_LABEL=':COMMERCIAL_LABEL'";

        $connection->query($sql, $bind);

        return $model['LCDV6'];
    }

    protected function deleteOldModel($lcvd6s)
    {
        $connection = Pelican_Db::getInstance();
        $bind[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
        $bind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
        $sql = "DELETE FROM #pref#_{$this->form_name}";
        if (!empty($lcvd6s)) {
            $sql .= ' WHERE LCDV6 NOT IN ( "'.implode('","', $lcvd6s).'" ) AND SITE_ID = :SITE_ID AND LANGUE_ID = :LANGUE_ID';
        }

        $connection->query($sql, $bind);
    }

    /** affichage des resultats
     *
     */
    public function listAction()
    {
        $this->updateModelTable();
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', '', '', 0, 0, 0, 'liste');
        $table->setValues($this->getListModel(), 'MODEL_SILHOUETTE_ID');
        $table->addColumn(t('NDP_GENDER'), 'GENDER', '20', 'left', '', 'tblheader', 'GENDER');
        $table->addColumn(t('NDP_LCDV6'), 'LCDV6', '8', 'left', '', 'tblheader', 'LCDV6');
        $table->addColumn(t('NDP_GROUPING_CODE'), 'GROUPING_CODE', '8', 'left', '', 'tblheader', 'GROUPING_CODE');
        $table->addColumn(t('NDP_COMMERCIAL_LABEL'), 'COMMERCIAL_LABEL', '45', 'left', '', 'tblheader', 'COMMERCIAL_LABEL');
//        $table->addColumn(t('NDP_SHOW_FINISHING'), 'SHOW_FINISHING', '45', 'left', '', 'tblheader', 'SHOW_FINISHING');
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "ID"), "center");

        $this->setResponse($table->getTable());

        // hide add button
        $this->aButton['add'] = '';
        Backoffice_Button_Helper::init($this->aButton);
    }

    /**
     *
     */
    public function editAction()
    {

        parent::editAction();
        $this->oForm = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
        $form = $this->oForm->open(Pelican::$config['DB_PATH']);
        $form .= $this->beginForm($this->oForm);
        $form .= $this->oForm->beginFormTable();
        $form .= $this->oForm->createHidden('ID', $this->id);
        $form .= $this->oForm->createHidden('LCDV6', $this->values['LCDV6']);
        $form .= $this->getTitleModelSilhouette();
        $form .= $this->getFormStripsSetup();
        $form .= $this->getFormColor();
        $form .= $this->getFormPriceSetup();
//        $form .= $this->getFormConfiguratorSetup();
//        $form .= $this->getFormStockWebstoreSetup();
//        $form .= $this->getFormFinishingDisplaySetup();
//        $form .= $this->getFormUpsellingSetup();
        $form .= $this->addJsForColorPicker();
        $form .= $this->oForm->endFormTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();
        $this->setResponse($form);
    }

    /**
     *
     * @return string
     */
    public function getTitleModelSilhouette()
    {
        $title = $this->values['COMMERCIAL_LABEL']
            .' ('
            .$this->values['GENDER'].' - '
            .$this->values['LCDV6'].' '
            .$this->values['GROUPING_CODE']
            .')';

        return $this->oForm->createTitle($title);
    }

    /**
     *
     * @return string
     */
    public function getFormStripsSetup()
    {
        $form = $this->oForm->createTitle(t('NDP_STRIP'));
        $form .= $this->oForm->showSeparator("formsep");
        $form .= $this->oForm->createDescription(t('NDP_MSG_STRIP'));

        $stripsAvailabled = array(
            'NEW_COMMERCIAL_STRIP' => t('NDP_NEW'),
            'SPECIAL_OFFER_COMMERCIAL_STRIP' => t('NDP_SPECIAL_OFFER'),
            'SPECIAL_SERIES_COMMERCIAL_STRIP' => t('NDP_SPECIAL_SERIE'),
            'LIMITED_SERIES_COMMERCIAL_STRIP' => t('NDP_LIMITED_SERIE')
        );
        $targets = array(
            self::DISABLED => t('NDP_DESACTIVE'),
            self::ENABLED => t('NDP_ACTIVE')
        );
        foreach ($stripsAvailabled as $key => $stripLib) {

            $this->setDefaultValueTo($key, self::DISABLED);
            $form .= $this->oForm->createRadioFromList(
                $key, $stripLib, $targets, $this->values[$key], true, $this->readO, 'h'
            );
        }

        return $form;
    }

    /**
     * @return string
     */
    public function getFormConfiguratorSetup()
    {

        return $this->getFormActivateWsSetup('CONFIG', 'SHOW_IN_CONFIG', self::CONFIGURATOR);
    }

    /**
     * @return string
     */
    public function getFormStockWebstoreSetup()
    {

        return $this->getFormActivateWsSetup('STOCK_WEBSTORE', 'STOCK_WEBSTORE', self::WEBSTORE);
    }

    /**
     * @return string
     */
    public function getFormPriceSetup()
    {
        $form = $this->oForm->createTitle(t('NDP_CAR_PRICE'));
        $form .= $this->oForm->showSeparator("formsep");
        $form .= $this->oForm->createDescription(t('NDP_MESSAGE_DISPLAY_PRICE'));

        $targets = array(
            self::ENABLED => t('NDP_YES'),
            self::DISABLED => t('NDP_NO')
        );
        $defaultValue = self::ENABLED;
        $readO = $this->readO;
        $options = array();

        if ('1' !== $this->getPriceParameterFromSite()) {
            $defaultValue = self::DISABLED;
            $options['disabled'] = true;
        }

        $this->setDefaultValueTo('DISPLAY_PRICE', $defaultValue);
        $form .= $this->oForm->createRadioFromList(
            'DISPLAY_PRICE', t('NDP_VEHICULE_PRICE_DISPLAY'), $targets, $this->values['DISPLAY_PRICE'], false, $readO, 'h', false, '', null, $options
        );

        return $form;
    }

    /**
     * @return string
     */
    protected function getFormColor()
    {
        $form = $this->oForm->createTitle(t('COLOR'));
        $form .= $this->oForm->showSeparator("formsep");
        $form .= $this->oForm->createDescription(t('NDP_MESSAGE_DISPLAY_COLOR'));
        $form .= $this->oForm->createComboFromList(self::FIELD_COLOR, t('COLOR'), $this->getListColorForCombo(), $this->values[self::FIELD_COLOR], false, $this->readO, 1, false, '', false);

        return $form;
    }

    /**
     * @return string
     */
    protected function addJsForColorPicker()
    {
        $colors = $this->getListColorForCombo(true);
        $colorJs = [];
        foreach ($colors as $keyColor => $valueColor) {
            $colorJs[$valueColor['id']] = $valueColor['color'];
        }
        $js = "<script type='text/javascript'>
            var colors = ".json_encode($colorJs).";
            $( document ).ready(function() {

                   $('select[name*=\"COLOR_ID\"]').each(function(idx, elm){
                               var selectColor = $(elm);
                               var valueColor =selectColor.val();
                               if (typeof colors[valueColor] != 'undefined') {
                                selectColor.css('background-color', colors[valueColor]);
                               }
                               selectColor.find('option').each(function() {
                                if (this.value != '') {
                                    this.style.backgroundColor = colors[this.value];
                                }
                                }); // fin each
                    });// fin each

            ";

        $js .= "  $('select[name*=\"COLOR_ID\"]').change(function() {

                        var selectColor =  $(this);
                        var colorChange = colors[selectColor.val()];
                        if (typeof colorChange != 'undefined') {
                           selectColor.css('background-color', colorChange);
                        } else {
                            selectColor.css('background-color', '#FFF');
                        }
                    }); // fin select
                ";

        $js .= "    }); // fin readey
            </script>
            ";

        return $js;
    }

    public function getFormFinishingDisplaySetup()
    {
        $form = $this->oForm->createTitle(t('NDP_PRESENTATION_SHOW_FINISHING'));

        $targetsFinishingDisplay = array(
            self::GROUPING => t('NDP_PER_GROUPING_SILOUHETTE'),
            self::SILHOUETTE => t('NDP_PER_SILOUHETTE')
        );

        $this->setDefaultValueTo('SHOW_FINISHING', self::GROUPING);
        $form .= $this->oForm->createRadioFromList(
            "SHOW_FINISHING", '', $targetsFinishingDisplay, $this->values['SHOW_FINISHING'], false, $this->readO, 'h', false, $disabled
        );

        return $form;
    }

    /**
     * 
     * @param string $ndpTradKey
     * @param string $keyValue
     * @param string $field
     * @param int    $defaultValue
     *
     * @return string
     */
    public function getFormActivateWsSetup($ndpTradKey, $keyValue, $field, $defaultValue = self::DISABLED)
    {
        $disabled = '';
        $forceSetValue = false;

        $form = $this->oForm->createTitle(t('NDP_'.$ndpTradKey));
        $form .= $this->oForm->showSeparator("formsep");

        if ($this->getOneFieldOfPsaSitesWS($field) == self::OFF) {
            $form .= $this->oForm->createDescription(t('NDP_GRP_SILH_MSG_'.$ndpTradKey));
            $disabled = 'disabled="disabled" ';
            $forceSetValue = true;
        }

        $targets = array(
            self::ENABLED => t('NDP_YES'),
            self::DISABLED => t('NDP_NO')
        );
        $this->setDefaultValueTo($keyValue, $defaultValue, $forceSetValue);
        $form .= $this->oForm->createRadioFromList(
            $keyValue, t('NDP_'.$ndpTradKey), $targets, $this->values[$keyValue], false, $this->readO, 'h', false, $disabled
        );

        return $form;
    }

    /**
     *
     * @param string $field
     *
     * @return boolean $response
     */
    public function getOneFieldOfPsaSitesWS($field)
    {
        $response = false;
        $connection = Pelican_Db::getInstance();
        $bind = [":SITE_ID" => $_SESSION[APP]["SITE_ID"]];
        $query = "SELECT $field FROM #pref#_sites_et_webservices_psa WHERE SITE_ID = :SITE_ID";
        $response = $connection->queryRow($query, $bind);

        return $response[$field];
    }

    /**
     * @return mixed
     */
    protected function getPriceParameterFromSite()
    {
        $connection = Pelican_Db::getInstance();
        $bind = [":SITE_ID" => $_SESSION[APP]["SITE_ID"]];

        $query = "SELECT * "
            ." FROM #pref#_site_national_param s"
            ." WHERE s.SITE_ID=:SITE_ID";

        $response = $connection->queryItem($query, $bind);

        $result = json_decode($response);

        return $result->VEHICULE_PRICE_DISPLAY;
    }

    /**
     *
     * @return string
     */
    public function getFormUpsellingSetup()
    {
        $params = $this->getParams();

        $connection = Pelican_Db::getInstance();

        $form = $this->oForm->createTitle(t('NDP_UPSELLING'));
        $form .= $this->oForm->showSeparator("formsep");
        $form .= $this->oForm->createDescription(t('NDP_MSG_GRP_SILH_UPSELLING'));
        $params['MODELE_ID'] = $this->id;
        $params['LCDV6'] = $this->values['LCDV6'];
        $form .= Pelican_Request::call('_/Ndp_ModelGroupingSilhouetteUpselling/', $params);
        $form .= $this->oForm->createComment(t('NDP_MSG_DEFAULT_FINISHING'));

        return $form;
    }
    /*
     * Enregistrement en BDD
     *
     */

    public function saveAction()
    {
        $connection = Pelican_Db::getInstance();
        $this->id = Pelican_Db::$values['ID'];
        $saved = Pelican_Db::$values;
        $bind = [];
        $bind[":ID"] = $this->id;
        $bind[":SHOW_FINISHING"] = Pelican_Db::$values['SHOW_FINISHING'];
        $bind[":NEW_COMMERCIAL_STRIP"] = Pelican_Db::$values['NEW_COMMERCIAL_STRIP'];
        $bind[":SPECIAL_OFFER_COMMERCIAL_STRIP"] = Pelican_Db::$values['SPECIAL_OFFER_COMMERCIAL_STRIP'];
        $bind[":SPECIAL_SERIES_COMMERCIAL_STRIP"] = Pelican_Db::$values['SPECIAL_SERIES_COMMERCIAL_STRIP'];
        $bind[":LIMITED_SERIES_COMMERCIAL_STRIP"] = Pelican_Db::$values['LIMITED_SERIES_COMMERCIAL_STRIP'];
        $bind[":SHOW_IN_CONFIG"] = Pelican_Db::$values['SHOW_IN_CONFIG'];
        $bind[":STOCK_WEBSTORE"] = Pelican_Db::$values['STOCK_WEBSTORE'];
        $bind[":DISPLAY_PRICE"] = Pelican_Db::$values['DISPLAY_PRICE'];
        $bind[":COLOR_ID"] = Pelican_Db::$values[self::FIELD_COLOR];
        $connection->query("UPDATE #pref#_{$this->form_name} SET"
            . " SHOW_FINISHING = :SHOW_FINISHING,"
            . " NEW_COMMERCIAL_STRIP = :NEW_COMMERCIAL_STRIP,"
            . " SPECIAL_OFFER_COMMERCIAL_STRIP = :SPECIAL_OFFER_COMMERCIAL_STRIP,"
            . " SPECIAL_OFFER_COMMERCIAL_STRIP = :SPECIAL_OFFER_COMMERCIAL_STRIP,"
            . " SPECIAL_SERIES_COMMERCIAL_STRIP = :SPECIAL_SERIES_COMMERCIAL_STRIP,"
            . " LIMITED_SERIES_COMMERCIAL_STRIP = :LIMITED_SERIES_COMMERCIAL_STRIP,"
            . " SHOW_IN_CONFIG = :SHOW_IN_CONFIG,"
            . " STOCK_WEBSTORE = :STOCK_WEBSTORE,"
            . " DISPLAY_PRICE = :DISPLAY_PRICE,"
            . " COLOR_ID = :COLOR_ID"
            . " WHERE ID = :ID", $bind);
        
        $params = ['MODEL_ID' => $this->id];
        Pelican_Request::call('_/Ndp_ModelGroupingSilhouetteUpselling/save', $params);
        Pelican_Db::$values = $saved;
    }

    protected function setEditModel()
    {
        $this->aBind[':ID'] = (int) $this->id;
        $groupingSilhouette = <<<SQL
                SELECT
                    *
                FROM
                    #pref#_{$this->form_name}
                WHERE
                     ID = :ID
SQL;
        $this->editModel = $groupingSilhouette;
    }

    protected function getListModelGroupingSilhouetteUpselling()
    {
        parent::listAction();
        $connection = Pelican_Db::getInstance();
        $bind[':MODELE_ID'] = $this->id;
        $query = 'SELECT *
                    FROM
                    #pref#_ws_gdg_model_silhouette_upselling
                    WHERE
                    MODEL_SILHOUETTE_ID = :MODELE_ID
                    ';

        $this->listModel = $connection->queryTab($query, $bind);
    }

    /**
     *
     * @param boolean $hexColor
     *
     * @return array
     */
    protected function getListColorForCombo($hexColor = false)
    {
        $sql = 'SELECT ID as id, LABEL as lib FROM #pref#_finishing_color ORDER BY LABEL';
        if ($hexColor) {
            $sql = 'SELECT COLOR_CODE as color, ID as id FROM #pref#_finishing_color ORDER BY LABEL';
        }

        $con = Pelican_Db::getInstance();
        $results = $con->queryTab($sql, []);

        $colorList = array();
        if (!$hexColor) {
            foreach ($results as $key => $result) {
                $colorList[$result['id']] = $result['lib'];
            }
        } else {
            $colorList = $results;
        }

        return $colorList;
    }
}
