<?php
use PsaNdp\WebserviceConsumerBundle\Webservices\ConfigurationEngineSelect;

require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';


class Ndp_CatGamme_Controller extends Ndp_Controller
{


    const OUI       = "Oui";
    const NON       = "Non";
    const LAST_POS = 10000;

    protected $multiLangue = true;
    protected $form_name = "vehicle_category_site";
    protected $field_id = "ID";
    protected $defaultOrder = "ID";
    
    /**
     * @var array
     */
    protected $categories;

    protected function setListModel()
    {
        $connection = Pelican_Db::getInstance();
        $bind[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
        $bind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
        $sqlCategoryGammeVehicule = "
                SELECT
                    vc.ID,
                    LABEL_CENTRAL,
                    LABEL,
                    SITE_ID,
                    CRITERES_MARKETING
                FROM
                    #pref#_vehicle_category vc
                LEFT JOIN #pref#_{$this->form_name} vcs
                    ON vcs.ID = vc.ID
                    AND SITE_ID = :SITE_ID
                    AND LANGUE_ID = :LANGUE_ID
";
        $sqlCategoryGammeVehicule .= "ORDER BY CATEGORY_ORDER";

        $categoryGammeVehicule = $connection->queryTab($sqlCategoryGammeVehicule, $bind);
        $categoryGammeVehiculeWithSeo = $this->setDataPageSeoGamme($categoryGammeVehicule);
        $categoryGammeVehiculeWithSeoAndCarSelector = $this->setDataListModel($categoryGammeVehiculeWithSeo);

        $this->listModel = $categoryGammeVehiculeWithSeoAndCarSelector;
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
                    #pref#_vehicle_category vc
                LEFT JOIN #pref#_{$this->form_name} vcs
                    ON vcs.ID = vc.ID
                    AND SITE_ID = :SITE_ID
                    AND LANGUE_ID = :LANGUE_ID
                WHERE vc.ID = :ID
SQL;
        $this->editModel = $sqlTypeCouleur;
    }

    protected function updateModelTable()
    {
        $con = Pelican_Db::getInstance();
        $bind = [];
        $catGammes = $con->queryTab($this->getSqlCatGamme(), $bind);
        if (!empty($catGammes)) {
            foreach ($catGammes as $catGamme) {
                $this->addOrUpdateModel($catGamme);
            }
        }
    }
    
    /**
     * 
     * @return string
     */
    protected function getSqlCatGamme() {
        $sqlCategoryGammeVehicule = "
                SELECT
                    vc.ID,
                    vc.LABEL_CENTRAL
                FROM
                    #pref#_vehicle_category vc
        ";

        return $sqlCategoryGammeVehicule;
    }

    /**
     *
     * @param array $catGamme
     *
     */
    protected function addOrUpdateModel($catGamme)
    {
        $connection = Pelican_Db::getInstance();
        $bind[":ID"]                              = $catGamme['ID'];
        $bind[":LANGUE_ID"]                       = $_SESSION[APP]['LANGUE_ID'];
        $bind[":SITE_ID"]                         = $_SESSION[APP]['SITE_ID'];
        $bind[":CATEGORY_ORDER"]                  = self::LAST_POS;

        $sql = "INSERT IGNORE INTO #pref#_{$this->form_name} (
                        ID,
                        LANGUE_ID,
                        SITE_ID,
                        CATEGORY_ORDER
                        )
                        VALUES(
                         :ID,
                        ':LANGUE_ID',
                        ':SITE_ID',
                        :CATEGORY_ORDER
                        )";

        $connection->query($sql, $bind);
    }
    
    public function listAction()
    {
        $this->updateModelTable();
        parent::listAction();
        $this->setCategories();
        $table = $this->getTable();
        $this->button['back'] = '';
        $this->button['add'] = '';
        Backoffice_Button_Helper::init($this->button);

        $this->setResponse($table);
    }

    private function setCategories()
    {
        $sql = ' SELECT
                    pz.ZONE_PARAMETERS
               FROM
                    psa_page_zone pz
                    INNER JOIN  psa_page p ON (pz.page_ID=p.PAGE_ID AND pz.PAGE_VERSION=p.PAGE_CURRENT_VERSION )
                    INNER JOIN psa_page_version pv ON (p.PAGE_ID=pv.PAGE_ID AND p.PAGE_CURRENT_VERSION=pv.PAGE_VERSION)
               WHERE
                    pz.ZONE_TEMPLATE_ID='.Pelican::$config['ZONE_TEMPLATE_ID']['NDP_PF25_CAR_SELECTOR'].'
                    AND p.PAGE_STATUS=1';
        $connection = Pelican_Db::getInstance();
        $this->categories = explode('#', $connection->queryItem($sql));
    }


    /**
     * @return Pelican_List
     */
    private function getTable()
    {
        $table = Pelican_Factory::getInstance('List', '', '', 0, 0, 0, 'liste');
        /* @var $table Pelican_List */
        $table->setValues($this->getListModel(), 'ID');
        $table->setTableOrder("#pref#_".$this->form_name, "ID", "CATEGORY_ORDER");
        $table->addColumn(t('ID'), 'ID', 5);
        $table->addColumn(t('NDP_LABEL_CENTRAL_LIST'), 'LABEL_CENTRAL', 40);
        $table->addColumn(t('NDP_LABEL_LOCAL_LIST'), 'LABEL', 40);
        //$table->addColumn(t('NDP_UTILISEES_SUR_LA_PAGE_SEO_GAMME'), 'SEO', 30, 'center');
        $table->addColumn(t('NDP_UTILISEES_SUR_LE_CAR_SELECTOR'), 'CAR_SELECTOR', 30, 'center');
        $table->addInput(t('FORM_BUTTON_EDIT'), 'button', array('id' => 'ID'), 'center');

        return $table->getTable();
    }

    public function editAction()
    {
        parent::editAction();
        $title = '<h2>'.t('NDP_CAT_VEHICULE').'</h2><hr />';
        if (!empty($this->values["CRITERES_MARKETING"])) {
            $this->values["CRITERES_MARKETING"] = explode('#', $this->values["CRITERES_MARKETING"]);
        }
        $connection = Pelican_Db::getInstance();
        $this->oForm = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
        $form  = $this->oForm->open(Pelican::$config['DB_PATH']);
        $form .= $this->beginForm($this->oForm);
        $form .= $this->oForm->beginFormTable();
        $form .= $this->oForm->createHidden('id', $this->id);
        $form .= $this->oForm->createHidden('LABEL_CENTRAL', $this->values['LABEL_CENTRAL']);
        $form .= $this->oForm->createHidden('CATEGORY_ORDER', $this->values['CATEGORY_ORDER']);
        if (empty($this->values['LABEL'])) {
            $this->values['LABEL'] = $this->values['LABEL_CENTRAL'];
        }
        $form .= $this->oForm->createLabel(t('NDP_LABEL_CENTRAL'), $this->values['LABEL_CENTRAL']);
        $form .= $this->oForm->createInput('LABEL', t('NDP_LABEL_LOCAL'), 255, '', true, $this->values['LABEL'], $this->readO, 44);
        $form .= $this->oForm->createMedia("MEDIA_ID", t('PICTO'), false, "image", "", $this->values["MEDIA_ID"], true, true, false);

        $form .= $this->oForm->createAssocFromList(
            $connection,
            'CRITERES_MARKETING',
            t('NDP_CRITERES_MARKETING'),
            $this->getCriteresMarketing(),
            $this->values["CRITERES_MARKETING"],
            false,
            true,
            $this->readO,
            5,
            300,
            false,
            '',
            '',
            0,
            true
        );



        /* @TODO Mettre en place Critères marketing qui vient d'un web service*/

        $form .= $this->oForm->endFormTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();
        $this->setResponse($title.$form);
    }

    public function saveAction()
    {
        if (!empty(Pelican_Db::$values['CRITERES_MARKETING'])) {
            Pelican_Db::$values['CRITERES_MARKETING'] = implode('#', Pelican_Db::$values['CRITERES_MARKETING']);
        }
        parent::saveAction();
    }
    /**
     *
     * @param array $datasListeModel
     *
     * @return array
     */
    private function setDataPageSeoGamme(array $datasListeModel)
    {
        $dataTemp = [];
        foreach ($datasListeModel as $key => $dataListeModel) {
            $dataListeModel['SEO'] = '-';
            if ($this->isSeoPageGame($dataListeModel)) {
                $dataListeModel['SEO'] = self::OUI;
            }
            $dataTemp[$key] = $dataListeModel;
        }

        return $dataTemp;
    }

    /**
     *
     * @param array $datasListeModel
     *
     * @return array
     */
    private function setDataListModel(array $datasListeModel)
    {
        $dataTemp = [];
        foreach ($datasListeModel as $key => $dataListeModel) {
            $dataListeModel['CAR_SELECTOR'] = self::OUI;
            if (!$this->isInCarSelector($dataListeModel)) {
                $dataListeModel['CAR_SELECTOR'] = self::NON;
            }
            if (!$this->hasCritereMarketing($dataListeModel)) {
                $dataListeModel['CAR_SELECTOR'] = '-';
            }
            $dataTemp[$key] = $dataListeModel;
        }

        return $dataTemp;
    }

    /**
     * @return array
     */
    private function getCriteresMarketing()
    {
        $marketingCriterion = [];
        $container = $this->getContainer();
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

        return $marketingCriterion;
    }

    /**
     * @param array $dataListeModel
     *
     * @return bool
     */
    public function hasCritereMarketing(array $dataListeModel)
    {
        return  !empty($dataListeModel['CRITERES_MARKETING']);
    }


    /**
     * @param array $dataListeModel
     *
     * @return bool
     */
    private function isInCarSelector(array $dataListeModel)
    {
        /**
         * Indique si la catégorie remonte sur le Car Selector
         * Le statut qu’il soit « Oui » ou « Non » ne s’affiche que si au moins un critère marketing a été associé à la catégorie.
         * Si aucun statut ne s’affiche alors un tiret est représenté.
         *  « Non » est affiché si au moins un critère marketing a été associé à la catégorie
         * et que la catégorie n’a pas été sélectionnée sur la tranche Car Selector.
         */

        return in_array($dataListeModel['ID'], $this->categories);
    }

    /**
     * @param array $dataListeModel
     *
     * @return bool
     */
    private function isSeoPageGame(array $dataListeModel)
    {
        /**
         * Indique si la catégorie remonte sur la page SEO gamme.
         * Le statut « Oui » ne s’affiche que si au moins un critère marketing a été associé à la catégorie en local.
         * Si aucun critère marketing n’a été associé à la catégorie alors un tiret est représenté.
         */

        return !empty($dataListeModel['CRITERES_MARKETING']);
    }
}
