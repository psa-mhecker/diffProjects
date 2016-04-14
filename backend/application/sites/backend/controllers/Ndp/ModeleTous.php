<?php
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';

use PsaNdp\MappingBundle\Entity\Vehicle\PsaModelSilhouetteSite;

class Ndp_ModeleTous_Controller extends Ndp_Controller
{

    protected $multiLangue = true;
    protected $form_name = "model_config";
    protected $field_id = "SITE_ID";
    private $wsConfigurator;
    private $wsWebstore;

    const ENABLED = 1;
    const DISABLED = 0;
    const KEY_ORDER_AO = 1;
    const KEY_ORDER_PRICE_ASC = 2;
    const KEY_ORDER_PRICE_DESC = 3;
    const CTA_TYPE = "CTA_FOR_REF";
    const CTA = "CTA_ERREUR";

    /**
     * 
     * @param \Pelican_Request $request
     */
    public function __construct(\Pelican_Request $request)
    {
        parent::__construct($request);
        $this->id = (int) $_SESSION [APP] ['SITE_ID'];

        $webservice = Pelican_Factory::getInstance('Webservice');
        $webservice->setSiteId($this->id)->setName(Ndp_Webservice::CONFIGURATOR)->getValues();
        $this->wsConfigurator = $webservice;

        $webservice->setName(Ndp_Webservice::WEBSTORE)->getValues();
        $this->wsWebstore = $webservice;
    }

    /**
     *
     */
    public function listAction()
    {
        $this->editAction();
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
//        $form .= $this->getFormFinishingEngine();
//        $form .= $this->getFormUpselling();
//        $form .= $this->getFormEngineSpecifications();
//        $form .= $this->getFormComparisonVersion();
//        $form .= $this->getFormCtaMessageErreur();
//        $form .= $this->getFormCtaRangeBar();
        $form .= $this->getFormStrip();
        $form .= $this->oForm->endFormTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();
        $this->setResponse($form);
    }

    /**
     *
     * @return string
     */
    public function getFormFinishingEngine()
    {
        $form = $this->oForm->createTitle(t('NDP_FINISHING_ENGINE'));
        $form .= $this->oForm->showSeparator("formsep");
        $form .= $this->oForm->createDescription(t('NDP_MSG_FINISHING_ENGINE'));

        $targetsFinishingOrder = array(
            self::KEY_ORDER_AO => t('NDP_ORDER_AO'),
            self::KEY_ORDER_PRICE_ASC => t('NDP_PRICE_ASC'),
            self::KEY_ORDER_PRICE_DESC => t('NDP_PRICE_DESC'),
        );
        $this->setDefaultValueTo('FINISHING_ORDER', self::KEY_ORDER_AO);

        $form .= $this->oForm->createRadioFromList(
            "FINISHING_ORDER", t('NDP_ORDERING'), $targetsFinishingOrder, $this->values['FINISHING_ORDER'], false, $this->readO, 'h'
        );

        return $form;
    }

    /**
     * @return string
     */
    public function getFormUpselling()
    {
        $form = $this->oForm->createTitle(t('NDP_UPSELLING'));
        $form .= $this->oForm->showSeparator("formsep");
        $form .= $this->oForm->createDescription(t('NDP_MSG_UPSELLING'));

        $targetsUpselling = array(
            self::ENABLED => t('NDP_YES'),
            self::DISABLED => t('NDP_NO')
        );
        $this->setDefaultValueTo('UPSELLING', self::ENABLED);

        $form .= $this->oForm->createRadioFromList(
            "UPSELLING", t('NDP_ENABLE_UPSELLING'), $targetsUpselling, $this->values['UPSELLING'], false, $this->readO, 'h'
        );
        $form .= $this->oForm->createHidden('OLD_UPSELLING', $this->values['UPSELLING']);

        return $form;
    }

    /**
     * @return string
     */
    public function getFormEngineSpecifications()
    {
        $form = $this->oForm->createTitle(t('NDP_ENGINE_SPECIFICATIONS'));
        $form .= $this->oForm->showSeparator("formsep");

        $targetsShowCarac = array(
            self::ENABLED => t('NDP_YES'),
            self::DISABLED => t('NDP_NO')
        );
        $this->setDefaultValueTo('SHOW_SPECIFICATIONS', self::DISABLED);

        $form .= $this->oForm->createRadioFromList(
            "SHOW_SPECIFICATIONS", t('NDP_DISPLAY'), $targetsShowCarac, $this->values['SHOW_SPECIFICATIONS'], false, $this->readO, 'h'
        );

        return $form;
    }

    /**
     * @return string
     */
    public function getFormComparisonVersion()
    {
        $form = $this->oForm->createTitle(t('NDP_COMPARISON_VERSION'));
        $form .= $this->oForm->showSeparator("formsep");
        $form .= $this->oForm->createDescription(t('NDP_MSG_COMPARISON_VERSION'));

        $targetsShowCarac = array(
            self::DISABLED => t('NDP_DESACTIVE'),
            self::ENABLED => t('NDP_ACTIVE'),
        );

        $this->setDefaultValueTo('SHOW_COMPARISONCHART', self::DISABLED);

        $type = 'COMPARATIF_VERSION';

        $jsActivation = $this->oForm->addJsContainerRadio($type);

        $form .= $this->oForm->createRadioFromList(
            "SHOW_COMPARISONCHART", t('NDP_SHOW_COMPARISONCHART'), $targetsShowCarac, $this->values['SHOW_COMPARISONCHART'], false, $this->readO, 'h', false, $jsActivation
        );
        $form .= $this->oForm->addHeadContainer('1', $this->values['SHOW_COMPARISONCHART'], $type);

        $targetsShowComparisonChart = array(
            self::ENABLED => t('NDP_YES'),
            self::DISABLED => t('NDP_NO')
        );
        $this->setDefaultValueTo('SHOW_COMPARISONCHART_BUTTON_OPEN', self::ENABLED);
        $form .= $this->oForm->createRadioFromList(
            "SHOW_COMPARISONCHART_BUTTON_OPEN", t('NDP_SHOW_BUTTON_OPEN'), $targetsShowComparisonChart, $this->values['SHOW_COMPARISONCHART_BUTTON_OPEN'], false, $this->readO, 'h'
        );

        $this->setDefaultValueTo('SHOW_COMPARISONCHART_BUTTON_CLOSE', self::ENABLED);
        $form .= $this->oForm->createRadioFromList(
            "SHOW_COMPARISONCHART_BUTTON_CLOSE", t('NDP_SHOW_BUTTON_CLOSE'), $targetsShowComparisonChart, $this->values['SHOW_COMPARISONCHART_BUTTON_CLOSE'], false, $this->readO, 'h'
        );

        $this->setDefaultValueTo('SHOW_COMPARISONCHART_BUTTON_DIFF', self::ENABLED);
        $form .= $this->oForm->createRadioFromList(
            "SHOW_COMPARISONCHART_BUTTON_DIFF", t('NDP_SHOW_BUTTON_DIFF'), $targetsShowComparisonChart, $this->values['SHOW_COMPARISONCHART_BUTTON_DIFF'], false, $this->readO, 'h'
        );

        $this->setDefaultValueTo('SHOW_COMPARISONCHART_BUTTON_PRINT', self::ENABLED);
        $form .= $this->oForm->createRadioFromList(
            "SHOW_COMPARISONCHART_BUTTON_PRINT", t('NDP_SHOW_BUTTON_PRINT'), $targetsShowComparisonChart, $this->values['SHOW_COMPARISONCHART_BUTTON_PRINT'], false, $this->readO, 'h'
        );
        $form .= $this->oForm->addFootContainer();

        return $form;
    }

    /**
     * @return string
     */
    public function getFormCtaMessageErreur()
    {
        $form = $this->oForm->createTitle(t('NDP_MSG_CTA_ERROR'));
        $form .= $this->oForm->showSeparator("formsep");
        $form .= $this->getCtaClassicalDisplay();

        return $form;
    }

    /**
     *
     * @return string
     */
    public function getCtaClassicalDisplay()
    {
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $this->buildValuesForCta();
        $ctaComposite->setCta($this->oForm, $this->values, false, self::CTA_TYPE);
        $ctaDisable = Pelican_Factory::getInstance('CtaDisable');
        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->addTargetAvailable('_popin', t('NDP_POPIN'));
        $ctaNew = Pelican_Factory::getInstance('CtaNew');
        $ctaNew->addTargetAvailable('_popin', t('NDP_POPIN'));
        $ctaNew->hideStyle(true);
        $ctaNew->hideTitle(true);
        $ctaComposite->addInputCta($ctaDisable);
        $ctaComposite->addInputCta($ctaRef);
        $ctaComposite->addInputCta($ctaNew);

        return $ctaComposite->generate();
    }

    public function buildValuesForCta()
    {
         $this->values['PAGE_ZONE_CTA_STATUS'] = $this->values[self::CTA];
         switch ($this->values[self::CTA]) {
            case Ndp_Cta::SELECT_CTA:
                $this->values['CTA_ID'] = $this->values['CTA_ERREUR_ID'];
                $this->values['TARGET'] = $this->values['CTA_ERREUR_TARGET'];
                $this->values['STYLE'] = $this->values['CTA_ERREUR_STYLE'];
                break;
            case Ndp_Cta::NEW_CTA:
                $this->values['TARGET'] = $this->values['CTA_ERREUR_TARGET'];
                $this->values['ACTION'] = $this->values['CTA_ERREUR_ACTION'];
                break;
        }
    }
    /**
     * @return string
     */
    public function getFormCtaRangeBar()
    {
        $form = $this->oForm->createTitle(t('NDP_GESTION_CTA_RANGE_BAR_CAR_SELECTOR'));
        $form .= $this->oForm->showSeparator("formsep");
        $form .= $this->oForm->createDescription(t('NDP_MSG_CTA_RANGE_BAR'));
        $form .= $this->getFormCtaDiscover();
        $form .= $this->getFormCtaConfig();
        $form .= $this->getFormCtaStock();

        return $form;
    }

    /**
     * @return string
     */
    public function getFormCtaDiscover()
    {
        $form = $this->oForm->createTitle(t('NDP_CTA_DISCOVER'));
        $form .= $this->oForm->createLabel(t('NDP_DISPLAY_CTA_REQUIRED'),'');
        $form .= $this->oForm->createInput(
            'CTA_DISCOVER_ORDER', t('NDP_ORDER'), 3, "number", false, $this->values['CTA_DISCOVER_ORDER'], $this->readO, 3
        );

        return $form;
    }

    /**
     * @return string
     */
    public function getFormCtaConfig()
    {
        $form = $this->oForm->createTitle(t('NDP_CTA_CONFIG'));

        if ($this->wsConfigurator->getStatus() == Ndp_Webservice::IS_OFF) {
            $form .= $this->oForm->createDescription(t('NDP_MSG_CTA_WEBSERVICE_CONFIG'));
        }

        if ($this->wsConfigurator->getStatus() == Ndp_Webservice::IS_ON) {

            $type = 'CTA_CONFIGURER';

            $targetsCtaConfigurer = array(
                self::DISABLED => t('NDP_DESACTIVE'),
                self::ENABLED => t('NDP_ACTIVE'),
            );

            $this->setDefaultValueTo('CTA_CONFIGURE_DISPLAY', self::DISABLED);

            $jsActivation = $this->oForm->addJsContainerRadio($type);
            $form .= $this->oForm->createRadioFromList(
                "CTA_CONFIGURE_DISPLAY", t('NDP_SHOW_CTA'), $targetsCtaConfigurer, $this->values['CTA_CONFIGURE_DISPLAY'], false, $this->readO, 'h', false, $jsActivation
            );

            $form .= $this->oForm->addHeadContainer('1', $this->values['CTA_CONFIGURE_DISPLAY'], $type);
            $form .= $this->oForm->createInput(
                'CTA_CONFIGURE_ORDER', t('NDP_ORDER'), 3, "number", false, $this->values['CTA_CONFIGURE_ORDER'], $this->readO, 3
            );
            $form .= $this->oForm->addFootContainer();
        }
        
        return $form;
    }

    /**
     *
     * @return string
     */
    public function getFormCtaStock()
    {
        $disabled = '';
        $forceSetValue = false;

        $form = $this->oForm->createTitle(t('NDP_CTA_STOCK'));

        // webservice désactivé
        if ($this->wsWebstore->getStatus() == Ndp_Webservice::IS_OFF) {
            $form .= $this->oForm->createDescription(t('NDP_MSG_CTA_WEBSERVICE_WEBSTORE'));
            $disabled = 'disabled="disabled" ';
            $forceSetValue = true;
        }

        // webservice activé
        if ($this->wsWebstore->getStatus() == Ndp_Webservice::IS_ON) {
            $form .= $this->oForm->createDescription(t('NDP_MSG_CTA_STOCK'));
        }
        // si webStore  est désactivé dans les Sites et webservices PSA
        //          --> Griser les champs suivants
        //          --> le Cta est concidéré comme désactivé

        $targetsCtaConfigurer = array(
            self::DISABLED => t('NDP_DESACTIVE'),
            self::ENABLED => t('NDP_ACTIVE'),
        );

        $this->setDefaultValueTo('CTA_STOCK_DISPLAY', self::DISABLED, $forceSetValue);

        $type = 'CTA_STOCK';
        $jsActivation = $this->oForm->addJsContainerRadio($type);

        $form .= $this->oForm->createRadioFromList(
            "CTA_STOCK_DISPLAY", t('NDP_SHOW_CTA'), $targetsCtaConfigurer, $this->values['CTA_STOCK_DISPLAY'], true, $this->readO, 'h', false, $disabled.$jsActivation
        );
        $form .= $this->oForm->addHeadContainer('1', $this->values['CTA_STOCK_DISPLAY'], $type);
        $form .= $this->oForm->createInput(
            'CTA_STOCK_ORDER', t('NDP_ORDER'), 3, "number", false, $this->values['CTA_STOCK_ORDER'], $this->readO, 3
        );
        $form .= $this->oForm->addFootContainer();

        return $form;
    }

    /**
     *
     * @return string
     */
    public function getFormStrip()
    {
        $connection = Pelican_Db::getInstance();

        $form = $this->oForm->createTitle(t('NDP_STRIP_PRIORITY_DISPLAY'));
        $form .= $this->oForm->showSeparator("formsep");
        $form .= $this->oForm->createDescription(t('NDP_MSG_STRIP_PRIORITY_DISPLAY'));

        $params['source'] = array(
            PsaModelSilhouetteSite::STRIP_NEW => t(PsaModelSilhouetteSite::STRIP_NEW),
            PsaModelSilhouetteSite::STRIP_SPECIAL_OFFER => t(PsaModelSilhouetteSite::STRIP_SPECIAL_OFFER),
            PsaModelSilhouetteSite::STRIP_SPECIAL_SERIES => t(PsaModelSilhouetteSite::STRIP_SPECIAL_SERIES),
            PsaModelSilhouetteSite::STRIP_LIMITED_SERIES => t(PsaModelSilhouetteSite::STRIP_LIMITED_SERIES),
        );

        $selected = array_keys($params['source']);

        if ($this->values['STRIP_ORDER'] != '') {
            $oldValues =explode('#', $this->values['STRIP_ORDER']);
            $selected = array_merge($oldValues, array_diff($oldValues, $selected));
        }
        $params['order'] = true;
        $params['readO'] = $this->readO;
        $params['form'] = $this->oForm;

        $form .= $this->oForm->createAssocFromList(
            $connection, 'STRIP_ORDER', t('NDP_PRIORITY_DISPLAY'), $params['source'], $selected, false, false, $params['readO'], 5, '100%', false, '', $params['order'], $params['max'], false, array(
            'delOnDlbClick' => false,
            'showSource' => false
            )
        );

        return $form;
    }
    
    /*
     * Enregistrement en BDD
     *
     */
    public function saveAction()
    {
        /** @var Pelican_Db $connection */
        $connection = Pelican_Db::getInstance();

        Pelican_Db::$values['STRIP_ORDER'] = implode('#', Pelican_Db::$values['STRIP_ORDER']);
        Pelican_Db::$values['SITE_ID'] = $this->id;
        Pelican_Db::$values['LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $this->saveCta();
        //Ajout de la désactive/activation de l'UPSELLING
        if (Pelican_Db::$values['UPSELLING'] != Pelican_Db::$values['OLD_UPSELLING']) {
            $bind = [":ENABLE_UPSELLING" => Pelican_Db::$values['UPSELLING']];
            $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
            $bind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
            $query = "UPDATE #pref#_segmentation_finition_site SET"
            . " ENABLE_UPSELLING = :ENABLE_UPSELLING WHERE SITE_ID = :SITE_ID AND LANGUE_ID = :LANGUE_ID";

            $connection->query($query, $bind);
            $query = "UPDATE #pref#_ws_gdg_model_silhouette_upselling SET"
            . " UPSELLING = :ENABLE_UPSELLING WHERE SITE_ID = :SITE_ID AND LANGUE_ID = :LANGUE_ID";

             $connection->query($query, $bind);

        }
        //Fix du form retour pour ne pas être redigiré ailleurs.
        Pelican_Db::$values['form_retour'] = '/_/Index/child?tid='.$this->getParam('tid').';tc=&amp;view=O_1&amp;toprefresh=1&amp;toprefresh=1';
        parent::saveAction();       
    }
    
    /**
     * Méthode pour generer la sauvegarde des CTA du référentiel
     */
    public function saveCta()
    {
        switch (Pelican_Db::$values[self::CTA_TYPE]['PAGE_ZONE_CTA_STATUS']) {
            case Ndp_Cta::DISABLE_CTA:
                Pelican_Db::$values[self::CTA] = Ndp_Cta::DISABLE_CTA;
                break;
            case Ndp_Cta::SELECT_CTA:
                Pelican_Db::$values['CTA_ERREUR_ID'] = Pelican_Db::$values[self::CTA_TYPE]['SELECT_CTA']['CTA_ID'];
                Pelican_Db::$values['CTA_ERREUR_STYLE'] = Pelican_Db::$values[self::CTA_TYPE]['SELECT_CTA']['STYLE'];
                Pelican_Db::$values['CTA_ERREUR_TARGET'] = Pelican_Db::$values[self::CTA_TYPE]['SELECT_CTA']['TARGET'];
                Pelican_Db::$values[self::CTA] = Ndp_Cta::SELECT_CTA;
                break;
            case Ndp_Cta::NEW_CTA:
                Pelican_Db::$values['CTA_ERREUR_ACTION'] = Pelican_Db::$values[self::CTA_TYPE]['NEW_CTA']['ACTION'];
                Pelican_Db::$values['CTA_ERREUR_TARGET'] = Pelican_Db::$values[self::CTA_TYPE]['NEW_CTA']['TARGET'];
                Pelican_Db::$values[self::CTA] = Ndp_Cta::NEW_CTA;
                break;
        }        
    }

    /**
     * 
     */
    protected function setEditModel()
    {
        $this->editModel = "SELECT *
            from #pref#_".$this->form_name."
            WHERE SITE_ID=".$this->id;
    }
}
