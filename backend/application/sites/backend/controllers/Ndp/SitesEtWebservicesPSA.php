<?php

require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';

class Ndp_SitesEtWebservicesPSA_Controller extends Ndp_Controller
{
    protected $multiLangue = false;
    protected $administration = true;
    protected $form_name = "sites_et_webservices_psa";
    protected $field_id = "SITE_ID";

    const SITE_MASTER            = 2;
    const CONT_VP                = "cont_vp";
    const CONT_WEBSTORE          = "cont_webstore";
    const CONT_VP_POPIN          = "cont_vp_popin";
    const CONT_WEBSTORE_POPIN    = "cont_webstore_popin";
    const CONT_WEBSTORE_PARCOURS = "cont_webstore_parcours";
    const CONT_SHOWROOM          = "cont_showroom";
    const CONT_MY_PEUGEOT        = "cont_my_peugeot";
    const ENABLE                 = 1;
    const DISABLE                = -2;
    const STORE                  = 2;
    const SHOWROOM               = 1;
    const WS_AOA                 = 6;
    const PARCOURS_PDV           = 1;
    const PARCOURS_REGIONAL      = 2;
    const PARCOURS_PRODUIT       = 3;
    const DEFAULT_DOMAIN         = "www.peugeot.tld";
    const WS_MOTEUR_CONFIG_SELECT = 9;
    const WS_MOTEUR_CONFIG_CONFIG = 10;
    const WS_MOTEUR_CONFIG_LOOK_COMBINATIONS = 11;
    const WS_MOTEUR_CONFIG_COMPARE_GRADE   = 12;
    const WS_MOTEUR_CONFIG_ENGINE_CRITERIA = 13;

    protected function setEditModel()
    {
        $connection = Pelican_Db::getInstance();
        $this->aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $result = $connection->queryTab('SELECT * from #pref#_'.$this->form_name.' WHERE SITE_ID = :SITE_ID', $this->aBind);
        if (empty($result)) {
            $this->aBind[':SITE_ID'] = self::SITE_MASTER;
        }
        $sql = <<<SQL
                SELECT
                    *
                FROM
                    #pref#_{$this->form_name}
                WHERE
                     SITE_ID = :SITE_ID
SQL;

        $this->editModel = $sql;
    }


    public function indexAction()
    {
        parent::indexAction();
        $this->id = $_SESSION[APP]['SITE_ID'];
        $this->_forward('edit');
        $this->aButton['add'] = '';
        Backoffice_Button_Helper::init($this->aButton);
    }

    public function editAction()
    {
        parent::editAction();
        $this->oForm = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
        $form  = $this->oForm->open(Pelican::$config['DB_PATH']);
        $form .= $this->beginForm($this->oForm);
        $form .= $this->oForm->beginFormTable();

        $form .= $this->createDomain();
        $form .= $this->createVp();
        $form .= $this->createRangeManager();
        $form .= $this->createShowroom();
        $form .= $this->createMyPeugeot();
        $form .= $this->createWebStore();
        $form .= $this->createEndOfForm();

        $form .= $this->oForm->endFormTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();

        $this->setResponse($form);
    }

    /**
     *
     * @return string
     */
    public function createDomain()
    {
        $this->setDefaultValueTo('SITE_DOMAIN_NAME', self::DEFAULT_DOMAIN);
        $form  = $this->oForm->createInput('SITE_DOMAIN_NAME', t('NDP_SITE_DOMAIN_NAME'), 255, '', true, $this->values['SITE_DOMAIN_NAME'], $this->readO, 44);
        $form .= $this->oForm->createHr();

        return $form;
    }

    /**
     *
     * @return string
     */
    public function createVp()
    {
        $type = self::CONT_VP;
        $targetsAffichage = array(self::DISABLE => t('NDP_DESACTIVE'), self::ENABLE => t('NDP_ACTIVE'));
        $jsContainerAffichage = self::addJsContainerRadio($type);
        self::setDefaultValueTo('ZONE_VP', self::ENABLE);
        $readO = false;
        $wsEngineState1 = self::getWsState(self::WS_MOTEUR_CONFIG_SELECT);
        $wsEngineState2 = self::getWsState(self::WS_MOTEUR_CONFIG_CONFIG);
        $wsEngineState3 = self::getWsState(self::WS_MOTEUR_CONFIG_LOOK_COMBINATIONS);
        $wsEngineState4 = self::getWsState(self::WS_MOTEUR_CONFIG_COMPARE_GRADE);
        $wsEngineState5 = self::getWsState(self::WS_MOTEUR_CONFIG_ENGINE_CRITERIA);
        if (!$wsEngineState1 && !$wsEngineState2 && !$wsEngineState3 && !$wsEngineState4 && !$wsEngineState5) {
            $this->values['ZONE_VP'] = self::DISABLE;
            $readO = true;
        }
        $form = $this->oForm->createRadioFromList(
            "ZONE_VP",
            t('NDP_CONF_VP'),
            $targetsAffichage,
            $this->values['ZONE_VP'],
            true,
            $readO,
            'h',
            false,
            $jsContainerAffichage
        );
        $form .= self::addHeadContainer(self::ENABLE, $this->values['ZONE_VP'], $type);
        self::setDefaultValueTo('ZONE_URL_WEB_VP', Pelican::$config['SITES_WEBSERVICES_PSA_URL']['VP']);
        $form .= $this->oForm->createInput(
            "ZONE_URL_WEB_VP",
            t('URL_WEB'),
            255,
            "internallink",
            true,
            $this->values["ZONE_URL_WEB_VP"],
            $this->read0,
            100
        );
        $type = self::CONT_VP_POPIN;
        $jsContainerAffichage = self::addJsContainerRadio($type);
        self::setDefaultValueTo('ZONE_VP_POPIN', self::ENABLE);
        $form .= $this->oForm->createRadioFromList(
            "ZONE_VP_POPIN",
            t('NDP_POPIN_TRANSITION'),
            $targetsAffichage,
            $this->values['ZONE_VP_POPIN'],
            true,
            $this->readO,
            'h',
            false,
            $jsContainerAffichage
        );
        $form .= self::addHeadContainer(self::ENABLE, $this->values['ZONE_VP_POPIN'], $type);
        $jsContainerAffichage = self::addJsContainerRadio($type);
        self::setDefaultValueTo('ZONE_VP_POPIN_CONFIRM', self::DISABLE);
        $form .= $this->oForm->createRadioFromList(
            "ZONE_VP_POPIN_CONFIRM",
            t('NDP_CONFIRM_POPIN_TRANSITION'),
            $targetsAffichage,
            $this->values['ZONE_VP_POPIN_CONFIRM'],
            true,
            $this->readO,
            'h',
            false
        );        
        $form .= self::addFootContainer();
        $form .= self::addFootContainer();
        $form .= $this->oForm->createHr();
        
        return $form;
    }


    /**
     *
     * @return string
     */
    public function createRangeManager()
    {

        $form  = $this->oForm->createInput('SITE_RANGE_MANAGER', t('NDP_BO_RANGE_MANAGER'), 255, '', true, $this->values['SITE_RANGE_MANAGER'], $this->readO, 44);
        $form .= $this->oForm->createHr();

        return $form;
    }
    /**
     *
     * return string
     */
    public function createShowroom()
    {
        $type = self::CONT_SHOWROOM;
        $targetsAffichage = array(self::DISABLE => t('NDP_DESACTIVE'), self::SHOWROOM => t('SHOWROOM'), self::STORE => t('NDP_STORE'));
        $jsContainerAffichage = self::addJsContainerRadio($type);
        self::setDefaultValueTo('ZONE_SHOWROOM', self::DISABLE);
        $readO = false;
        $wsEngineState = self::getWsState(self::WS_AOA);
        if (!$wsEngineState) {
            $this->values['ZONE_SHOWROOM'] = self::DISABLE;
            $readO = true;
        }
        $form = $this->oForm->createRadioFromList(
            "ZONE_SHOWROOM",
            t('NDP_STORE_SHOWROOM'),
            $targetsAffichage,
            $this->values['ZONE_SHOWROOM'],
            true,
            $readO,
            'h',
            false,
            $jsContainerAffichage
        );

        $form .= self::addHeadContainer(array(self::SHOWROOM, self::STORE), $this->values['ZONE_SHOWROOM'], $type);
        self::setDefaultValueTo('ZONE_URL_WEB_FICHE_ACCESSOIRES', Pelican::$config['SITES_WEBSERVICES_PSA_URL']['FICHE_ACCESSOIRES']);
        $form .= $this->oForm->createInput(
            "ZONE_URL_WEB_FICHE_ACCESSOIRES",
            t('NDP_URL_WEB_FICHE_ACCESSOIRES'),
            255,
            "internallink",
            true,
            $this->values["ZONE_URL_WEB_FICHE_ACCESSOIRES"],
            $this->read0,
            100
        );
        $form .= $this->oForm->createInput(
            "ZONE_URL_MOB_FICHE_ACCESSOIRES",
            t('NDP_URL_MOB_FICHE_ACCESSOIRES'),
            255,
            "internallink",
            false,
            $this->values["ZONE_URL_MOB_FICHE_ACCESSOIRES"],
            $this->read0,
            100
        );
        $form .= self::addFootContainer();
        $form .= $this->oForm->createHr();

        return $form;
    }

    /**
     *
     * return string
     */
    public function createWebStore()
    {
        $type = self::CONT_WEBSTORE;
        $targetsAffichage = array(self::DISABLE => t('NDP_DESACTIVE'), self::ENABLE => t('NDP_ACTIVE'));
        $jsContainerAffichage = self::addJsContainerRadio($type);
        self::setDefaultValueTo('ZONE_WEBSTORE', self::DISABLE);
        $form = $this->oForm->createRadioFromList(
            "ZONE_WEBSTORE",
            t('NDP_PEUGEOT_WEBSTORE'),
            $targetsAffichage,
            $this->values['ZONE_WEBSTORE'],
            true,
            $this->readO,
            'h',
            false,
            $jsContainerAffichage
        );
        $form .= self::addHeadContainer(self::ENABLE, $this->values['ZONE_WEBSTORE'], $type);
        $form .= $this->oForm->createInput(
            "ZONE_URL_WEB_MOB_WEBSTORE",
            t('NDP_URL_WEB_MOB'),
            255,
            "internallink",
            true,
            $this->values["ZONE_URL_WEB_MOB_WEBSTORE"],
            $this->read0,
            100
        );
        $type = self::CONT_WEBSTORE_POPIN;
        $jsContainerAffichage = self::addJsContainerRadio($type);
        self::setDefaultValueTo('ZONE_WEBSTORE_POPIN', self::ENABLE);
        $form .= $this->oForm->createRadioFromList(
            "ZONE_WEBSTORE_POPIN",
            t('NDP_POPIN_TRANSITION'),
            $targetsAffichage,
            $this->values['ZONE_WEBSTORE_POPIN'],
            true,
            $this->readO,
            'h',
            false,
            $jsContainerAffichage
        );
        $form .= self::addHeadContainer(self::ENABLE, $this->values['ZONE_WEBSTORE_POPIN'], $type);
        $jsContainerAffichage = self::addJsContainerRadio($type);
        self::setDefaultValueTo('ZONE_WEBSTORE_POPIN_CONFIRM', self::DISABLE);
        $form .= $this->oForm->createRadioFromList(
            "ZONE_WEBSTORE_POPIN_CONFIRM",
            t('NDP_CONFIRM_POPIN_TRANSITION'),
            $targetsAffichage,
            $this->values['ZONE_WEBSTORE_POPIN_CONFIRM'],
            true,
            $this->readO,
            'h',
            false
        );
        $form .= self::addFootContainer();
        $type = self::CONT_WEBSTORE_PARCOURS;
        $targetsAffichage = array(self::PARCOURS_PDV => t('NDP_PARCOURS_PDV'), self::PARCOURS_REGIONAL => t('NDP_PARCOURS_REGIONAL'), self::PARCOURS_PRODUIT => t('NDP_PARCOURS_PRODUIT'));
        $jsContainerAffichage = self::addJsContainerRadio($type);
        $form .= $this->oForm->createRadioFromList(
            "ZONE_PARCOURS_WEBSTORE",
            t('NDP_PARCOURS_WEBSTORE'),
            $targetsAffichage,
            $this->values['ZONE_PARCOURS_WEBSTORE'],
            true,
            $this->readO,
            'h',
            false,
            $jsContainerAffichage
        );
        $form .= self::addHeadContainer(self::PARCOURS_PRODUIT, $this->values['ZONE_PARCOURS_WEBSTORE'], $type);
        self::setDefaultValueTo('ZONE_URL_WEB_MOB_WEBSTORE_PRODUITS', Pelican::$config['SITES_WEBSERVICES_PSA_URL']['WEBSTORE']);
        $form .= $this->oForm->createInput(
            "ZONE_URL_WEB_MOB_WEBSTORE_PRODUITS",
            t('NDP_URL_WEB_MOB_WEBSTORE_PRODUITS'),
            255,
            "internallink",
            true,
            $this->values["ZONE_URL_WEB_MOB_WEBSTORE_PRODUITS"],
            $this->read0,
            100
        );
        $form .= self::addFootContainer();
        $form .= self::addFootContainer();
        $form .= $this->oForm->createHr();

        return $form;
    }

    /**
     *
     * return string
     */
    public function createMyPeugeot()
    {
        $type = self::CONT_MY_PEUGEOT;
        $targetsAffichage = array(self::DISABLE => t('NDP_DESACTIVE'), self::ENABLE => t('NDP_ACTIVE'));
        $jsContainerAffichage = self::addJsContainerRadio($type);
        self::setDefaultValueTo('ZONE_MY_PEUGEOT', self::DISABLE);
        $form = $this->oForm->createRadioFromList(
            "ZONE_MY_PEUGEOT",
            t('NDP_MY_PEUGEOT'),
            $targetsAffichage,
            $this->values['ZONE_MY_PEUGEOT'],
            true,
            $this->readO,
            'h',
            false,
            $jsContainerAffichage
        );
        $form .= self::addHeadContainer(self::ENABLE, $this->values['ZONE_MY_PEUGEOT'], $type);

        $form .= $this->oForm->createInput(
            "ZONE_URL_WEB_ACCUEIL",
            t('NDP_URL_WEB_ACCUEIL'),
            255,
            "internallink",
            true,
            $this->values["ZONE_URL_WEB_ACCUEIL"],
            $this->read0,
            100
        );
        $form .= self::addFootContainer();
        $form .= $this->oForm->createHr();

        return $form;
    }

    /**
     *
     * return string
     */
    public function createEndOfForm()
    {
        $form = $this->oForm->createInput(
            "ZONE_URL_PEUGEOT_SERVICE",
            t('NDP_PEUGEOT_SERVICE'),
            255,
            "internallink",
            false,
            $this->values["ZONE_URL_PEUGEOT_SERVICE"],
            $this->read0,
            100
        );
        $form .= $this->oForm->createInput(
            "ZONE_URL_PEUGEOT_ENVIRONNEMENT",
            t('NDP_PEUGEOT_ENVIRONNEMENT'),
            255,
            "internallink",
            false,
            $this->values["ZONE_URL_PEUGEOT_ENVIRONNEMENT"],
            $this->read0,
            100
        );
        $form .= $this->oForm->createInput(
            "ZONE_URL_PEUGEOT_PRO",
            t('NDP_PEUGEOT_PRO'),
            255,
            "internallink",
            false,
            $this->values["ZONE_URL_PEUGEOT_PRO"],
            $this->read0,
            100
        );
        $form .= $this->oForm->createInput(
            "ZONE_URL_PEUGEOT_WEBSTORE_PRO",
            t('NDP_PEUGEOT_WEBSTORE_PRO'),
            255,
            "internallink",
            false,
            $this->values["ZONE_URL_PEUGEOT_WEBSTORE_PRO"],
            $this->read0,
            100
        );
        $form .= $this->oForm->createInput(
            "ZONE_URL_PRODUIT_DERIVES",
            t('NDP_STORE_PRODUIT_DERIVES'),
            255,
            "internallink",
            false,
            $this->values["ZONE_URL_PRODUIT_DERIVES"],
            $this->read0,
            100
        );
        $form .= $this->oForm->createInput(
            "ZONE_URL_PEUGEOT_SCOOTER",
            t('NDP_PEUGEOT_SCOOTER'),
            255,
            "internallink",
            false,
            $this->values["ZONE_URL_PEUGEOT_SCOOTER"],
            $this->read0,
            100
        );
        $form .= $this->oForm->createInput(
            "ZONE_URL_PEUGEOT_CYCLES",
            t('NDP_PEUGEOT_CYCLES'),
            255,
            "internallink",
            false,
            $this->values["ZONE_URL_PEUGEOT_CYCLES"],
            $this->read0,
            100
        );
        $form .= $this->oForm->createInput(
            "ZONE_URL_MU_BY_PEUGEOT",
            t('NDP_MU_BY_PEUGEOT'),
            255,
            "internallink",
            false,
            $this->values["ZONE_URL_MU_BY_PEUGEOT"],
            $this->read0,
            100
        );

        return $form;
    }

    public function saveAction()
    {
        $connection = Pelican_Db::getInstance();
        if (Pelican_Db::$values['ZONE_VP_POPIN'] == self::DISABLE) {
            unset(Pelican_Db::$values['ZONE_VP_POPIN_CONFIRM']);
        }
        if (Pelican_Db::$values['ZONE_VP'] == self::DISABLE) {
            unset(Pelican_Db::$values['ZONE_URL_WEB_VP']);
            unset(Pelican_Db::$values['ZONE_VP_POPIN']);
            unset(Pelican_Db::$values['ZONE_VP_POPIN_CONFIRM']);
        }
        if (Pelican_Db::$values['ZONE_SHOWROOM'] == self::DISABLE) {
            unset(Pelican_Db::$values['ZONE_URL_WEB_FICHE_ACCESSOIRES']);
            unset(Pelican_Db::$values['ZONE_URL_MOB_FICHE_ACCESSOIRES']);
        }
        if (Pelican_Db::$values['ZONE_MY_PEUGEOT'] == self::DISABLE) {
            unset(Pelican_Db::$values['ZONE_URL_WEB_ACCUEIL']);
        }
        if (Pelican_Db::$values['ZONE_WEBSTORE_POPIN'] == self::DISABLE) {
            unset(Pelican_Db::$values['ZONE_WEBSTORE_POPIN_CONFIRM']);
        }
        if (Pelican_Db::$values['ZONE_PARCOURS_WEBSTORE'] != self::PARCOURS_PRODUIT) {
            unset(Pelican_Db::$values['ZONE_URL_WEB_MOB_WEBSTORE_PRODUITS']);
        }
        if (Pelican_Db::$values['ZONE_WEBSTORE'] == self::DISABLE) {
            unset(Pelican_Db::$values['ZONE_URL_WEB_MOB_WEBSTORE']);
            unset(Pelican_Db::$values['ZONE_WEBSTORE_POPIN']);
            unset(Pelican_Db::$values['ZONE_WEBSTORE_POPIN_CONFIRM']);
            unset(Pelican_Db::$values['ZONE_PARCOURS_WEBSTORE']);
            unset(Pelican_Db::$values['ZONE_URL_WEB_MOB_WEBSTORE_PRODUITS']);
        }
            $bind = [
                ":SITE_ID" => $_SESSION[APP]['SITE_ID']
            ];
            $connection->query('DELETE FROM #pref#_'.$this->form_name.' WHERE SITE_ID = :SITE_ID', $bind);
            $connection->updateTable(Pelican_Db::DATABASE_INSERT, '#pref#_'.$this->form_name);
    }
}
