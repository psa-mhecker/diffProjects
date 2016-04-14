<?php
require_once Pelican::$config["APPLICATION_CONTROLLERS"].'/Administration/Directory.php';
require_once Pelican::$config["APPLICATION_CONTROLLERS"].'/Ndp.php';
require_once Pelican::$config["APPLICATION_CONTROLLERS"].'/Cms.php';

/**
 * Formulaire de gestion des sites.
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 02/07/2004
 */
class Administration_Site_Controller extends Ndp_Controller
{


    const GLOBAL_PARAMETERS_TAB = '1';
    const DNS_TAB = '2';
    const NATIONAL_PARAMETERS_TAB = '3';
    const LANGUAGE_TAB = '4';
    const CONTENT_TYPE_TAB = '5';
    const SECTION_TAB = '6';
    const SERVICES_TIERS_TAB = '7';
    const ENABLED = 1;
    const DISABLED = 0;

    protected $administration = true;
    protected $form_name = "site_simple";
    protected $field_id = 'SITE_ID';
    protected $defaultOrder = "SITE_LABEL";
    protected $processus = array(
        "#pref#_site",
        "#pref#_site_code",
        array("#pref#_directory_site", "DIRECTORY_ID"),
        array("#pref#_site_dns", "SITE_DNS"),
        array("#pref#_site_language", 'LANGUE_ID'),
        array("method", "Administration_Site_Controller::siteContentType")
    );
    protected $decacheBack = array("Backend/ContentType", array("Backend/Generic", "site"), "Tag/Type", "CodePaysById");

    protected function init()
    {

        if (isset($_POST['complement_tc']) && $_POST['complement_tc'] === 'admin') {
            $this->setParam('tc', 'admin');
        }

        $_SESSION [APP] ["tree_profile"] = "";

        // Si site ADMIN
        if ($this->getParam('tc') === 'admin') {
            if ($this->show) {
                $this->form_name = "site";
            }

// @TODO uniformiser avec comportement par defaut
            $this->processus = array(
                "#pref#_site",
                "#pref#_site_code",
                array("#pref#_directory_site", "DIRECTORY_ID"),
                array("#pref#_site_dns", "SITE_DNS"),
                array("#pref#_site_language", 'LANGUE_ID'),
                array("method", "Administration_Site_Controller::siteContentType"),
            );
        } else {
            $this->form_name = "interfacesite";
            $this->processus = array(
                "#pref#_site",
                array("#pref#_site_dns", "SITE_DNS"),
                array("#pref#_site_language", 'LANGUE_ID'),
                array("method", "Administration_Site_Controller::siteContentType"),
            );
        }
    }

    protected function setListModel()
    {
        $this->listModel = "SELECT #pref#_site.* FROM #pref#_site order by ".$this->listOrder;
    }

    protected function setEditModel()
    {
        $this->editModel = "SELECT *
            from #pref#_site s
                left join #pref#_site_code sc on (s.SITE_ID = sc.SITE_ID)
            WHERE s.SITE_ID=".(int) $this->id;
    }

    public function listAction()
    {
        // Si site ADMIN
        if ($this->getParam('tc') === 'admin') {
            parent::listAction();
            $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
            $table->setFilterField("search_keywordSite", "<b>".t('Site')." :</b>", "SITE_LABEL");
            $table->setFilterField("search_keywordTitre", "<b>".t('Titre')." :</b>", "SITE_TITLE");
            $table->getFilter(2);
            $table->setCSS(array("tblalt1", "tblalt2"));
            $table->setValues($this->getListModel(), 'SITE_ID');
            $table->addColumn(t('ID'), 'SITE_ID', "10", "left", "", "tblheader", 'SITE_ID');
            $table->addColumn(t('SITE'), "SITE_LABEL", "50", "left", "", "tblheader", "SITE_LABEL");
            $table->addColumn(t('TITRE'), "SITE_TITLE", "25", "left", "", "tblheader", "SITE_TITLE");
            $sqlDns = "select distinct SITE_ID as \"id\", SITE_DNS as \"lib\" from #pref#_site_dns ";
            $table->addMulti(t('URLS'), 'SITE_ID', "25", "left", "<br>", "tblheader", "", $sqlDns);
            $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => 'SITE_ID'), "center");
            $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => 'SITE_ID', "" => "readO=true"), "center");
            $this->aButton["add"] = "";
            Backoffice_Button_Helper::init($this->aButton);
            $this->setResponse($table->getTable());
        } else {
            $this->id = $_SESSION [APP] ['SITE_ID'];
            parent::_initBack();
            $this->_forward("edit");
        }
    }

    public function editAction()
    {
        parent::editAction();
        // Si site PAS ADMIN
        if ($this->getParam('tc') !== 'admin') {
            $this->form_retour = '/_/Index/child?tid='.$this->iTemplateId.'&tc='.$this->getParam('tc').'&view=';
        }
        $this->loadMagicParameters();
        $connection = Pelican_Db::getInstance();

        $oForm = Pelican_Factory::getInstance('Form', true);
        $oForm->bDirectOutput = false;
        $oForm->setTab(self::GLOBAL_PARAMETERS_TAB, t('GLOBAL_PARAMETERS'));

        if ($this->getParam('tc') !== 'admin') {
            $oForm->setTab(self::NATIONAL_PARAMETERS_TAB, t('NDP_NATIONAL_PARAMETERS'));
        }
        $oForm->setTab(self::DNS_TAB, t('DNS'));
        $oForm->setTab(self::LANGUAGE_TAB, t('LANGUAGES'));
        // Si site ADMIN
        if ($this->getParam('tc') === 'admin') {
            $oForm->setTab(self::CONTENT_TYPE_TAB, t('FONCTIONNALITES'));
        }
        $oForm->setTab("5", t('CONTENT_TYPES'));
        $oForm->setTab(self::SERVICES_TIERS_TAB, t('SERVICES'));

        $form = $oForm->open(Pelican::$config ["DB_PATH"]);
        $form .= $this->beginForm($oForm);

        /*         * **************
         * Paramètres généraux
         * *************** */
        $form .= $this->getFormGolbalParameters($oForm);

        /*         * ***************
         * DNS
         * *************** */
        $form .= $oForm->beginTab(self::DNS_TAB);
        $connection->query("select SITE_DNS from #pref#_site_dns where SITE_ID=".$this->id." order by SITE_DNS");
        $form .= $oForm->createTextArea(
            "SITE_DNS", t('Available alias').' (?)', true, $connection->data ["SITE_DNS"], "", $this->readO, 10, 50, false, "", true, "", t("FONCTIONNEMENT_DNS")
        );
        // Correspondance des DNS BO FO
        $sSQLDnsBack = 'SELECT SITE_DNS FROM #pref#_site_dns WHERE SITE_ID = :SITE_ID';
        $aDns = $connection->queryTab($sSQLDnsBack, array(':SITE_ID' => Pelican::$config['SITE_BO']));
        $aDataDnsCurrent = $connection->queryTab($sSQLDnsBack, array(':SITE_ID' => $this->id));
        $aDataBoFo = $connection->queryTab(
            'SELECT * FROM #pref#_site_parameter_dns WHERE SITE_ID = :SITE_ID AND SITE_PARAMETER_ID = :PARAM', array(':SITE_ID' => $this->id, ':PARAM' => $connection->strToBind('SITE_DNS_BO'))
        );
        $aDataBoHttp = $connection->queryTab(
            'SELECT * FROM #pref#_site_parameter_dns WHERE SITE_ID = :SITE_ID AND SITE_PARAMETER_ID = :PARAM', array(':SITE_ID' => $this->id, ':PARAM' => $connection->strToBind('SITE_DNS_HTTP'))
        );

        $aDnsCurrent = array();
        if (is_array($aDataDnsCurrent) && !empty($aDataDnsCurrent)) {
            foreach ($aDataDnsCurrent as $DnsCurrent) {
                $aDnsCurrent[$DnsCurrent['SITE_DNS']] = $DnsCurrent['SITE_DNS'];
            }
        }
        $aDnsBoFo = $aHttpBoFo = array();
        if (is_array($aDataBoFo) && !empty($aDataBoFo)) {
            foreach ($aDataBoFo as $DataBoFo) {
                $aDnsBoFo[$DataBoFo['SITE_DNS']] = $DataBoFo['SITE_PARAMETER_VALUE'];
            }
        }
        if (is_array($aDataBoHttp) && !empty($aDataBoHttp)) {
            foreach ($aDataBoHttp as $DataBoHttp) {
                $aHttpBoFo[$DataBoHttp['SITE_DNS']] = $DataBoHttp['SITE_PARAMETER_VALUE'];
            }
        }
        if (is_array($aDns) && !empty($aDns) && is_array($aDnsCurrent) && !empty($aDnsCurrent)) {

            $form .= $oForm->createLabel(t('CORRESPONDANCE_URL_BO_FO'), '');
            foreach ($aDns as $dns) {
                $form .= '<tr><td class="formlib">'.$dns['SITE_DNS'].'</td><td class="formval">';
                $form .= $oForm->createComboFromList(
                    "HTTP[".$dns['SITE_DNS']."]", '', array('http' => 'http://', 'https' => 'https://'), $aHttpBoFo[$dns['SITE_DNS']], false, $this->readO, 1, false, '', false, true
                );
                $form .= $oForm->createComboFromList(
                    "SITE_DNS_BOFO[".$dns['SITE_DNS']."]", $dns['SITE_DNS'], $aDnsCurrent, $aDnsBoFo[$dns['SITE_DNS']], false, $this->readO, 1, false, '', true, true
                );
                $form .= '</td></tr>';
            }
        }

        /*         * ***************
         * Parametres Nationaux
         * *************** */
        if ($this->getParam('tc') !== 'admin') {
            $form .= $this->getFormNationalParameters($oForm);
        }

        /*         * ***************
         * Langues
         * *************** */
        $form .= $oForm->beginTab(self::LANGUAGE_TAB);
        // langues associées (en plus du francais)
        $strSQLList = "SELECT langue_id as id, ".$connection->getConcatClause(
                array("langue_label", "' ('", "langue_translate", "')'")
            )." as lib
				FROM #pref#_language
				ORDER BY lib";

        $strSQLSelectedList = "SELECT sl.langue_id as id, l.langue_label as lib
				FROM #pref#_language l, #pref#_site_language sl
				WHERE sl.langue_id= l.langue_id
				AND sl.site_id = ".$this->id."
				ORDER BY lib";
        $form .= $oForm->createAssocFromSql(
            $connection, "assoc_langue_id", t('Site languages'), $strSQLList, $strSQLSelectedList, true, true, $this->readO, 5, 250, false, ""
        );
        $form .= "<script type='text/javascript'>
					$('#srcassoc_langue_id').dblclick(function() {
					  alert('".t('ATTENTION_VOUS_ALLEZ_AJOUTER_UNE_LANGUE', 'js')."');
					});
					$('#assoc_langue_id').dblclick(function() {
					  alert('".t('ATTENTION_VOUS_ALLEZ_SUPPRIMER_UNE_LANGUE', 'js')."');
					});
					$( '#tableClassForm3 tr td a :first' ).click(function() {
						alert('".t('ATTENTION_VOUS_ALLEZ_AJOUTER_UNE_LANGUE', 'js')."');
					});
					$( '#tableClassForm3 tr td a :last' ).click(function() {
						alert('".t('ATTENTION_VOUS_ALLEZ_SUPPRIMER_UNE_LANGUE', 'js')."');
					});
					$('#assoc_langue_id').on('objectAddFromAssoc', function() {
					    updateSiteDefaultLangueList();
					});
					$('#srcassoc_langue_id').on('objectDelFromAssoc', function() {
					    updateSiteDefaultLangueList();
					});
				</script>";

        $form .= $oForm->createComboFromSql($connection, 'SITE_DEFAULT_LANGUAGE', t('NDP_SITE_DEFAULT_LANGUAGE'), $strSQLSelectedList, $this->values['SITE_DEFAULT_LANGUAGE'], true, $this->readO, 1, false, false, false);

        // Si site ADMIN
        if ($this->getParam('tc') === 'admin') {
            $form .= $oForm->createInput(
                "SITE_CODE_PAYS", t('CODE_PAYS'), 4, "", true, $this->values ["SITE_CODE_PAYS"], $this->readO, 10, false, ""
            );
            $form .= $oForm->createInput(
                "SITE_CODE_LDAP", t('CODE_LDAP'), 6, "", true, $this->values ["SITE_CODE_LDAP"], $this->readO, 10, false, ""
            );
        } else {
            $form .= $oForm->createHidden("SITE_CODE_PAYS", $this->values ["SITE_CODE_PAYS"]);
            $form .= $oForm->createHidden("SITE_CODE_LDAP", $this->values ["SITE_CODE_LDAP"]);
        }

        /*         * ***************
         * Fonctionnalites BO (directory)
         * *************** */
        // Si site ADMIN
        if ($this->getParam('tc') === 'admin') {
            $form .= $oForm->beginTab(self::SECTION_TAB);
            $form .= $oForm->createLabel(t('RUBRIQUES'), "");
            $_GET ["table"] = "DIRECTORY";
            $form .= $oForm->createSubFormHmvc(
                "directory", t('Backend'), array('class' => 'Administration_Directory_Controller', 'method' => 'site'), $this->values, $this->readO
            );
        }

        /*         * ***************
         * Types de contenus
         * *************** */
        $form .= $oForm->beginTab(self::CONTENT_TYPE_TAB);
        if (($this->id != Pelican::$config ["FORM_ID_AJOUT"]) && (strlen($this->id) != 0)) {
            $strQueryData = "SELECT #pref#_content_type_site.*, #pref#_content_type.CONTENT_TYPE_ID as ID, CONTENT_TYPE_LABEL as LIB from
                    #pref#_content_type
                    left join #pref#_content_type_site on (#pref#_content_type_site.CONTENT_TYPE_ID = #pref#_content_type.CONTENT_TYPE_ID and SITE_ID='".$this->id."')
                    ORDER BY CONTENT_TYPE_LABEL";
            $aData = $connection->queryTab($strQueryData);
        }
        if ($aData) {
            $form .= "<tr>";
            $form .= "<td class=\"".$oForm->sStyleLib."\">".t('Content types');
            $form .= "</td>";
            $form .= $oForm->getDisposition();
            $form .= "<td class=\"".$oForm->sStyleVal."\">";

            $form .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"1\" width=\"100%\" class=\"tableaucroise\">";
            $form .= "<tr>";
            $form .= "<td align=\"center\" class=\"croiselib\" >&nbsp;&nbsp;</td>";
            $form .= "<td align=\"center\"  class=\"croiselib\">".t('GESTION')."</td>";
            $form .= "</tr>";
            foreach ($aData as $ligne) {
                $form .= "<tr>";
                $form .= "<td align=\"center\" class=\"croiselib\" >".t($ligne ["LIB"])."</td>";
                $form .= "<td align=\"center\" class=\"croiseval\" >";
                $ligne ["CONTENT_TYPE_ID"] = ($this->id == -2) ? $ligne ["ID"] : $ligne ["CONTENT_TYPE_ID"];
                $form .= $oForm->createCheckBoxFromList(
                    "CONTENT_TYPE_ID_GESTION[]", "", array($ligne ["ID"] => ""), $ligne ["CONTENT_TYPE_ID"], false, $this->readO, "h", true
                );
                $form .= "</td>";
                $form .= "</tr>";
            }
            $form .= "</table>";
            $form .= "</td></tr>\n";
        }

        $form .= $this->getFormServices($oForm, $connection);

        $form .= $this->endForm($oForm);
        $form .= $oForm->close();

        // Zend_Form start
        $form = formToString($this->oForm, $form);
        // Zend_Form stop

        $this->assign('content', $form, false);
        $this->replaceTemplate('index', 'edit');
        $this->fetch();
    }

    /**
     * 
     * @param Ndp_Form $oForm
     * 
     * @return string
     */
    public function getFormGolbalParameters(Ndp_Form $oForm)
    {
        $params = $this->getParams();
        $form = $oForm->beginTab(self::GLOBAL_PARAMETERS_TAB);
        $params['SITE_ID'] = $this->id;
        $params['HMVC'] = true;
        $params['oForm'] = $oForm;

        $form .= Pelican_Request::call('_/Administration_Site_Global_Parameters', $params);

        return $form;
    }

    /**
     * 
     * @param Ndp_Form $oForm
     * 
     * @return string
     */
    public function getFormNationalParameters(Ndp_Form $oForm)
    {
        $params = $this->getParams();
        $form = $oForm->beginTab(self::NATIONAL_PARAMETERS_TAB);
        $params['SITE_ID'] = $this->id;
        $params['HMVC'] = true;
        $params['oForm'] = $oForm;

        $form .= Pelican_Request::call('_/Administration_Site_National_Parameters', $params);

        return $form;
    }

    /**
     * Services tiers
     * 
     * @param Ndp_Form $oForm
     * @return string
     */
    public function getFormServices(Ndp_Form $oForm)
    {
        $params = $this->getParams();

        $form = $oForm->beginTab(self::SERVICES_TIERS_TAB);
        $params['SITE_ID'] = $this->id;
        $params['HMVC'] = true;
        $params['oForm'] = $oForm;

        $form .= Pelican_Request::call('_/Administration_Site_Services_Map', $params);

        $paramsTagSetup = $params;
        $paramsTagSetup['TAG_TYPE_ID'] = $this->values["TAG_TYPE_ID"];
        
        $form .= $oForm->createTextArea(
            "TAG_TYPE_JS_LINK", t('Javascript a inclure').' ('.t('HEADER').')', false, $this->values["TAG_TYPE_JS_LINK"], '', $this->readO, 20, 75, false, "", true, ""
        );

        $form .= $oForm->createTextArea(
            "FOOTER_JS", t('NDP_INCLUDE_JS').' ('.t('FOOTER').')', false, $this->values["FOOTER_JS"], '', $this->readO, 20, 75, false, "", true, ""
        );

        $form .= $this->getFormStreamlikeSetup($oForm);
        $form .= $this->getFormRevooSetup($oForm);
        $form .= $this->getFormTwitterSetup($oForm);

        return $form;
    }

    function getFormStreamlikeSetup($oForm)
    {
        // config Streamlike
        $form = $oForm->createTitle(t('NDP_STREAMLIKE'));

        $targetsActivatation = array(
            self::DISABLED => t('NDP_DESACTIVE'),
            self::ENABLED => t('NDP_ACTIVE'),
        );


        $this->values['ENABLE_STREAMLIKE'] = self::DISABLED;
        //force à enabled si des données existent
        if (!empty($this->values["STREAMLIKE_COMPANY_ID"])) {
            $this->values['ENABLE_STREAMLIKE'] = self::ENABLED;
        }
        $type = 'STREAMLIKE';

        $jsActivation = $oForm->addJsContainerRadio($type);

        $form .= $oForm->createRadioFromList(
            "ENABLE_STREAMLIKE", t('NDP_ENABLE_STREAMLIKE'), $targetsActivatation, $this->values['ENABLE_STREAMLIKE'], false, $this->readO, 'h', false, $jsActivation
        );
        $form .= $oForm->addHeadContainer('1', $this->values['ENABLE_STREAMLIKE'], $type);

        $form .= $oForm->createInput(
            "STREAMLIKE_COMPANY_ID", t('STREAMLIKE_COMPANY_ID'), 255, "", false, $this->values["STREAMLIKE_COMPANY_ID"], $this->readO, 50
        );
        $form .= $oForm->createInput(
            "STREAMLIKE_CACHETIME", t('STREAMLIKE_CACHETIME'), 10, "", false, $this->values["STREAMLIKE_CACHETIME"], $this->readO, 10
        );
        $form .= $oForm->createMedia(
            'STREAMLIKE_DEFAULT_COVER',
            t('STREAMLIKE_DEFAULT_COVER'),
            false,
            'image',
            '',
            $this->values["STREAMLIKE_DEFAULT_COVER"],
            $this->readO,
            true,
            false
        );
        $form .= $oForm->addFootContainer();

        return $form;
    }


    function getFormRevooSetup($oForm)
    {
        // config Reevoo
        $form = $oForm->createTitle(t('NDP_REEVOO'));

        $targetsActivatation = array(
            self::DISABLED => t('NDP_DESACTIVE'),
            self::ENABLED => t('NDP_ACTIVE'),
        );


        $this->values['ENABLE_REEVOO'] = self::DISABLED;
        //force à enabled si des données existent
        if (!empty($this->values["REEVOO_API_URL"])) {
            $this->values['ENABLE_REEVOO'] = self::ENABLED;
        }
        $type = 'REEVOO';

        $jsActivation = $oForm->addJsContainerRadio($type);

        $form .= $oForm->createRadioFromList(
            "ENABLE_REEVOO", t('NDP_ENABLE_REEVOO'), $targetsActivatation, $this->values['ENABLE_REEVOO'], false, $this->readO, 'h', false, $jsActivation
        );
        $form .= $oForm->addHeadContainer('1', $this->values['ENABLE_REEVOO'], $type);

        $form .= $oForm->createInput(
            "REEVOO_API_URL", t('NDP_REEVOO_API_URL'), 255, "", false, $this->values["REEVOO_API_URL"], $this->readO, 50
        );
        $form .= $oForm->createInput(
            "REEVOO_ID", t('NDP_REEVOO_ID'), 10, "", false, $this->values["REEVOO_ID"], $this->readO, 10
        );
        $form .= $oForm->addFootContainer();

        return $form;
    }


    function getFormTwitterSetup($oForm)
    {
        // config Twitter
        $form = $oForm->createTitle(t('NDP_TWITTER'));

        $form .= $oForm->createInput("TWITTER_ID", t('NDP_TWITTER_ID'), 30, "text", false, $this->values["TWITTER_ID"], $this->readO, 30);

        return $form;
    }

    public function saveAction()
    {

        $connection = Pelican_Db::getInstance();
        // Repositionnement du complement tc dans les paramètres
        $this->setParam('tc', Pelican_Db::$values['complement_tc']);
        $params = $this->getParams();
        $params['SITE_ID'] = $this->id;
        $params['HMVC'] = true;

        unset(Pelican_Db::$values['complement_tc']);

        $aBind [':SITE_ID'] = Pelican_Db::$values ['SITE_ID'];
        $connection->query('delete from #pref#_site_parameter_dns where SITE_ID=:SITE_ID', $aBind);

        $save = Pelican_Db::$values;
        if ($this->form_action != Pelican_Db::DATABASE_DELETE && is_array(
                Pelican_Db::$values['SITE_DNS_BOFO']
            ) && !empty(Pelican_Db::$values['SITE_DNS_BOFO'])
        ) {
            foreach (Pelican_Db::$values['SITE_DNS_BOFO'] as $sDnsBo => $sDnsFo) {
                Pelican_Db::$values ['SITE_PARAMETER_ID'] = 'SITE_DNS_BO';
                Pelican_Db::$values ['SITE_DNS'] = $sDnsBo;
                Pelican_Db::$values ['SITE_PARAMETER_VALUE'] = $sDnsFo;
                Pelican_Db::$values ['SITE_PARAMETER_PARAM'] = '';
                if (Pelican_Db::$values ['SITE_PARAMETER_VALUE']) {
                    $connection->insertQuery('#pref#_site_parameter_dns');
                }
                Pelican_Db::$values ['SITE_PARAMETER_ID'] = 'SITE_DNS_HTTP';
                Pelican_Db::$values ['SITE_PARAMETER_VALUE'] = Pelican_Db::$values['HTTP'][Pelican_Db::$values ['SITE_DNS']];
                Pelican_Db::$values ['SITE_PARAMETER_PARAM'] = '';
                if (Pelican_Db::$values ['SITE_PARAMETER_VALUE']) {
                    $connection->insertQuery('#pref#_site_parameter_dns');
                }
            }
        }
        Pelican_Db::$values = $save;

        Pelican_Db::$values ["SITE_DNS"] = Pelican_Form::splitTextarea(Pelican_Db::$values ["SITE_DNS"]);
        $tmp = Pelican_Db::$values ['LANGUE_ID'];
        Pelican_Db::$values ['LANGUE_ID'] = Pelican_Db::$values ["assoc_langue_id"];

        if (is_array(Pelican_Db::$values ['SITE_TITLE_LEVEL'])) {
            Pelican_Db::$values ['SITE_TITLE_LEVEL'] = implode(",", Pelican_Db::$values ['SITE_TITLE_LEVEL']);
        }

        Pelican_Db::$values ['SITE_STYLE'] = Pelican_Form::splitTextarea(Pelican_Db::$values ['SITE_STYLE']);
        if (is_array(Pelican_Db::$values ['SITE_STYLE'])) {
            Pelican_Db::$values ['SITE_STYLE'] = implode(";", Pelican_Db::$values ['SITE_STYLE']);
        }

        if (Pelican_Db::$values['ENABLE_STREAMLIKE'] == self::DISABLED) {
            Pelican_Db::$values['STREAMLIKE_COMPANY_ID'] = '';
            Pelican_Db::$values['STREAMLIKE_CACHETIME'] = '';
        }

        $this->saveMagicParameters();

        $save = Pelican_Db::$values ;

        if ($this->form_action != Pelican_Db::DATABASE_DELETE) {
            Pelican_Request::call('_/Administration_Site_Services_Map/save', $params);
        }

        Pelican_Db::$values ['LANGUE_ID'] = $tmp;

        Pelican_Request::call('_/Administration_Site_Global_Parameters/save', $params);

        Pelican_Request::call('_/Administration_Site_Services_Tag/save', $params);

        // Si site PAS ADMIN
        if ($params['tc'] !== 'admin') {
            Pelican_Request::call('_/Administration_Site_National_Parameters/save', $params);

            // Decache Alias DNS
            Pelican_Cache::clean("Service/ConfigGoogleApi", array($_SESSION[APP]['SITE_ID']));
        }
       Pelican_Db::$values = $save;
        
        $connection->updateForm($this->form_action, $this->processus);

        $params['HMVC'] = false;

        // decache robots.txt
        /** @var \Itkg\CombinedHttpCache\Client\RedisClient $redisCache */
        $redisCache = Pelican_Application::getContainer()->get('psa_ndp.cache.redis');
        /** @var \PsaNdp\MappingBundle\Manager\PsaTagManager $tagManager */
        $tagManager = Pelican_Application::getContainer()->get('open_orchestra_base.manager.tag');

        // decache robots.txt
        $redisCache->removeKeysFromTags([$tagManager->formatKeyIdTag('type', 'robots'), $tagManager->formatSiteIdTag($save['SITE_ID'])]);
        // decache block
        $redisCache->removeKeysFromTags([$tagManager->formatKeyIdTag('type', 'block'), $tagManager->formatSiteIdTag($save['SITE_ID'])]);
    }

    public function getMagicParameters() {

        return  ['REEVOO_API_URL', 'REEVOO_ID', 'SITE_DEFAULT_LANGUAGE', 'TWITTER_ID'];
    }

    public function loadMagicParameters() {

        $parameters = $this->getMagicParameters();
        $con  = Pelican_Db::getInstance();
        foreach ( $parameters as $parameterName ) {
            $this->values[$parameterName] ='';
            $query = "SELECT SITE_PARAMETER_VALUE FROM #pref#_site_parameter
                WHERE SITE_ID = ".$_SESSION[APP]['SITE_ID']." AND SITE_PARAMETER_ID = '".$parameterName."'";
            $value = $con->queryItem($query,[]);
            if (!empty($value)) {
                $this->values[$parameterName] = $value;
            }

        }

    }

    public function saveMagicParameters()
    {
        $save = Pelican_Db::$values ;
        $parameters = $this->getMagicParameters();
        $params = $this->getParams();
        $params['HMVC'] = true;

        if ($params['ENABLE_REEVOO'] == self::DISABLED) {
            $params['REEVOO_ID'] = '';
            $params['REEVOO_API_URL'] = '';

        }
        foreach ($parameters as $parameterName) {
            $params['SITE_ID'] = $this->id;
            $params['SITE_PARAMETER_ID'] = $parameterName;
            $params['SITE_PARAMETER_VALUE'] = isset(Pelican_Db::$values[$parameterName]) ? Pelican_Db::$values[$parameterName] : '' ;
            Pelican_Request::call('_/Administration_Site_Parameter/save', $params);

        }
        Pelican_Db::$values = $save;

    }

    public function checkUrlAction()
    {
        set_time_limit(0);
        $aData = $this->getParams();
        $label = $aData[0];
        $url = $aData[1];
        $div = $aData[2];
        $site_id = $aData[3];

        if (!$_SESSION [APP] ["user"] ["id"]) {
            echo("Veuillez vous identifier en Back Office");
            exit();
        }

        $connection = Pelican_Db::getInstance();
        $connection->setExitOnError(false);

        $aBind [':URL'] = $connection->strtoBind(trim($url));
        $aBind [':ID'] = $connection->strtoBind($site_id);

        $sSQL = "
			(
				SELECT site_id
				FROM #pref#_site
				WHERE
					site_id<>:ID
					and site_url=:URL
					OR site_media_url=:URL
			)
			UNION
			(
				SELECT site_id
				FROM #pref#_site_dns
				WHERE
					site_id<>:ID
					and site_dns=:URL
			)
			";
        $aResult = $connection->queryRow($sSQL, $aBind);
        if ($url == "") {
            // l'url est deja utilisée
            $error = "<span style=\"color: red;\">".t('NDP_URL_REQUIRED')."</span>";
            $url = "";
        } else {
            if (count($aResult) > 0) {
                // l'url est deja utilisée
                $error = "<span style=\"color: red;\">".t('NDP_URL_ALREADY_USED')." : ".$url."</span>";
                $url = "";
            } else { // la nouvelle url est valide
                $error = "";
            }
        }

        $this->getRequest()->addResponseCommand('assign', array('id' => $label, 'attr' => 'value', 'value' => $url));
        $this->getRequest()->addResponseCommand(
            'assign', array('id' => $div, 'attr' => 'innerHTML', 'value' => $error)
        );
    }

    public function afterInsert()
    {
        $connection = Pelican_Db::getInstance();
        /**
         * Template de navigation
         */
        Pelican_Db::$values ["TEMPLATE_PAGE_ID"] = -2; // $navigation;
        Pelican_Db::$values ["PAGE_TYPE_ID"] = getPageTypeId('GENERAL');
        Pelican_Db::$values ["PAGE_GENERAL"] = (Pelican_Db::$values ["PAGE_TYPE_ID"] == getPageTypeId('GENERAL'));
        Pelican_Db::$values ["TEMPLATE_PAGE_LABEL"] = "- Général -";
        Pelican_Db::$values ["PAGE_ID"] = -2;
        Pelican_Db::$values ["PAGE_DRAFT_VERSION"] = 1;
        Pelican_Db::$values ["PAGE_ORDER"] = 1;
        Pelican_Db::$values ["PAGE_TITLE"] = "- Général -";
        Pelican_Db::$values ["PAGE_TITLE_BO"] = "- Général -";
        Pelican_Db::$values ["PAGE_VERSION"] = 1;
        Pelican_Db::$values ["STATE_ID"] = 1;

        $connection->insertQuery("#pref#_template_page");
        $connection->insertQuery("#pref#_page");
        $connection->insertQuery("#pref#_page_version");

        /**
         * Template de la Home
         */
        Pelican_Db::$values ["TEMPLATE_PAGE_ID"] = -2; // $home;
        Pelican_Db::$values ["PAGE_TYPE_ID"] = getPageTypeId('HOME');
        Pelican_Db::$values ["PAGE_GENERAL"] = (Pelican_Db::$values ["PAGE_TYPE_ID"] == getPageTypeId('GENERAL'));
        Pelican_Db::$values ["TEMPLATE_PAGE_LABEL"] = "- Accueil -";
        Pelican_Db::$values ["PAGE_ID"] = -2;
        Pelican_Db::$values ["PAGE_DRAFT_VERSION"] = 1;
        Pelican_Db::$values ["PAGE_ORDER"] = 2;
        Pelican_Db::$values ["PAGE_TITLE"] = "Accueil";
        Pelican_Db::$values ["PAGE_TITLE_BO"] = "Accueil";
        Pelican_Db::$values ["PAGE_VERSION"] = 1;
        Pelican_Db::$values ["STATE_ID"] = 1;

        $connection->insertQuery("#pref#_template_page");
        $connection->insertQuery("#pref#_page");
        $connection->insertQuery("#pref#_page_version");

        /**
         * Template de contenu
         */
        Pelican_Db::$values ["TEMPLATE_PAGE_ID"] = -2; // $content;
        Pelican_Db::$values ["PAGE_TYPE_ID"] = getPageTypeId('CONTENT');
        Pelican_Db::$values ["PAGE_GENERAL"] = (Pelican_Db::$values ["PAGE_TYPE_ID"] == getPageTypeId('GENERAL'));
        Pelican_Db::$values ["TEMPLATE_PAGE_LABEL"] = "- Contenu -";

        $connection->insertQuery("#pref#_template_page");

        /**
         * Mediatheque
         */
        Pelican_Db::$values ["MEDIA_DIRECTORY_ID"] = -2; // $media;
        Pelican_Db::$values ["MEDIA_DIRECTORY_LABEL"] = "Mediathèque";
        Pelican_Db::$values ["MEDIA_DIRECTORY_PATH"] = "Mediathèque";
        $connection->insertQuery("#pref#_media_directory");

        /**
         * Profile
         */
        Pelican_Db::$values ["PROFILE_ID"] = -2; // $profile;
        Pelican_Db::$values ["PROFILE_LABEL"] = "Administration";
        Pelican_Db::$values ["PROFILE_ADMIN"] = "0";
        $connection->insertQuery("#pref#_profile");

        /**
         * Affectation à l'admin
         */
        Pelican_Db::$values ["USER_LOGIN"] = $_SESSION [APP] ["user"] ["id"];
        $connection->insertQuery("#pref#_user_profile");
            $i=0;
        /**
         * Accès
         */
        foreach (Pelican_Db::$values ["DIRECTORY_ID"] as $dir) {
            $i++;
            $connection->Query(
                "INSERT INTO #pref#_profile_directory ( PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER ) VALUES ( ".Pelican_Db::$values ["PROFILE_ID"].", ".$dir.", ".Pelican_Db::$values ["PROFILE_ID"].str_pad(
                    $i, 3, "0", STR_PAD_LEFT
                ).")"
            );
        }

        /**
         * Type de contenu
         */
        $tc = "select CONTENT_TYPE_ID from #pref#_content_type where CONTENT_TYPE_DEFAULT = 1";
        $connection->query($tc);
        Pelican_Db::$values ["CONTENT_TYPE_ID"] = $connection->data ["CONTENT_TYPE_ID"];
        // Si site PAS ADMIN
        if ($this->getParam('tc') !== 'admin') {
            $connection->updateTable(Pelican_Db::DATABASE_INSERT, "#pref#_content_type_site", "CONTENT_TYPE_ID");
        }
    }

    public function afterUpdate()
    {
        // Si site ADMIN
        if ($this->getParam('tc') === 'admin') {
            $connection = Pelican_Db::getInstance();
            $aBind [':SITE_ID'] = Pelican_Db::$values ['SITE_ID'];
            $connection->query(
                'delete from #pref#_profile_directory
                          where profile_id in (select profile_id from #pref#_profile where SITE_ID=:SITE_ID) and directory_id not in (select directory_id from #pref#_directory_site where site_id=:SITE_ID)', $aBind
            );
        }
    }

    public function beforeDelete()
    {
        $connection = Pelican_Db::getInstance();
        if ($this->getParam('tc') === 'admin') {
            $aBind [':SITE_ID'] = Pelican_Db::$values ['SITE_ID'];
            $connection->query(
                'delete from psa_media_format_intercept where media_id in (select media_id from psa_media where media_directory_id in (select media_directory_id from #pref#_media_directory where site_id = :SITE_ID))', $aBind
            );
        $connection->query('update psa_media_directory set site_id=1 where site_id = :SITE_ID', $aBind );
        $connection->query('delete from psa_content_category_category where PARENT_ID in (select content_category_id from psa_content_category where site_id = :SITE_ID)', $aBind );
        $connection->query('delete from #pref#_page_zone_multi where zone_template_id in (
            select zone_template_id from #pref#_zone_template where template_page_id in (
                select template_page_id from #pref#_template_page where SITE_ID = :SITE_ID))', $aBind );
        }
        
        $cascadeDelete = array(
            array('#pref#_page_zone_multi', 'page'),
            array('#pref#_page_multi_zone_multi', 'page'),
            '#pref#_form',
            '#pref#_content_type_site',
            '#pref#_directory_site',
            array('#pref#_profile_directory', 'profile'),
            array('#pref#_user_profile', 'profile'),
            '#pref#_profile',
            '#pref#_site_parameter_dns',
            '#pref#_site_parameter',
            array('#pref#_content_version', 'content'),
            array('#pref#_content_version_media', 'content'),
            array('#pref#_content_zone_multi', 'content'),
            '#pref#_content',
            '#pref#_content_category',
            array('#pref#_navigation', 'page'),
            array('#pref#_page_zone', 'page'),
            array('#pref#_page_zone_content', 'page'),
            array('#pref#_page_zone_media', 'page'),
            array('#pref#_page_version', 'page'),
            array('#pref#_page_version', 'template_page'),
            '#pref#_page',
            '#pref#_template_site',
            array('#pref#_template_page_area', 'template_page'),
            array('#pref#_zone_template', 'template_page'),
            '#pref#_template_page',
            array('#pref#_user_profile', 'profile'),
            array('#pref#_profile_directory', 'profile'),
            '#pref#_profile',
            '#pref#_service',
            '#pref#_comment',
            array('#pref#_terms_group_rel', 'terms_group'),
            array('#pref#_terms', 'terms_group'),
            '#pref#_terms_group',
            '#pref#_rewrite',
            '#pref#_pub',
            '#pref#_tag',
            '#pref#_research_log',
            '#pref#_research',
            '#pref#_research_param',
            '#pref#_research_param_field',
            '#pref#_cta',
            '#pref#_site_service',
            '#pref#_type_couleur_site',
            '#pref#_pdv_service',
            '#pref#_vehicle_category_site',
            '#pref#_model_config',
            '#pref#_ws_gdg_model_silhouette_site',
            '#pref#_acl_role',
            '#pref#_finishing_site',
            '#pref#_model_site',
            '#pref#_template_page',
            '#pref#_accessoires',
            '#pref#_accessoires_site',
            '#pref#_appli_mobile'
        );

        // Si site PAS ADMIN
        if ($this->getParam('tc') !== 'admin') {
            $aTmp = array(
                '#pref#_site_code',
                '#pref#_site_language',
                '#pref#_site_dns'
            );
            $cascadeDelete = array_merge($cascadeDelete, $aTmp);
        }
        $connection->cascadeDelete('site', $cascadeDelete);
    }

    /**
     * 
     * @param array $array
     * @param string $key
     */
    protected static function initArrayIfEmpty(&$array, $key)
    {
        if (!array_key_exists($key, $array)) {
            $array[$key] = [];
        }
    }

    public static function siteContentType()
    {
        $connection = Pelican_Db::getInstance();

        $DBVALUES_MONO = Pelican_Db::$values;

        $connection->query(
            "delete from #pref#_content_type_site where SITE_ID='".Pelican_Db::$values ['SITE_ID']."'"
        );
        if (Pelican_Db::$values ["CONTENT_TYPE_ID_GESTION"] && Pelican_Db::$values ["form_action"] != Pelican_Db::DATABASE_DELETE) {

            self::initArrayIfEmpty($DBVALUES_MONO, "CONTENT_TYPE_ID_EMISSION");
            self::initArrayIfEmpty($DBVALUES_MONO, "CONTENT_TYPE_ID_RECEPTION");
            self::initArrayIfEmpty($DBVALUES_MONO, "CONTENT_ALERTE");
            self::initArrayIfEmpty($DBVALUES_MONO, "CONTENT_ALERTE_URL");

            foreach ($DBVALUES_MONO ["CONTENT_TYPE_ID_GESTION"] as $content_id) {
                Pelican_Db::$values = array();
                Pelican_Db::$values ['SITE_ID'] = $DBVALUES_MONO ['SITE_ID'];
                Pelican_Db::$values ["CONTENT_TYPE_ID"] = $content_id;
                Pelican_Db::$values ["CONTENT_TYPE_SITE_EMISSION"] = (in_array(
                        $content_id, $DBVALUES_MONO ["CONTENT_TYPE_ID_EMISSION"]
                    ) ? "1" : "");
                Pelican_Db::$values ["CONTENT_TYPE_SITE_RECEPTION"] = (in_array(
                        $content_id, $DBVALUES_MONO ["CONTENT_TYPE_ID_RECEPTION"]
                    ) ? "1" : "");
                Pelican_Db::$values ["CONTENT_ALERTE"] = '';
                Pelican_Db::$values ["CONTENT_ALERTE_URL"] = '';
                if ($DBVALUES_MONO [Pelican_Db::$values ["CONTENT_TYPE_ID"]."_ALERTE_URL"]) {
                    Pelican_Db::$values ["CONTENT_ALERTE"] = '1';
                    Pelican_Db::$values ["CONTENT_ALERTE_URL"] = $DBVALUES_MONO [Pelican_Db::$values ["CONTENT_TYPE_ID"]."_ALERTE_URL"];
                }
                $connection->insertQuery("#pref#_content_type_site");
            }
        }
        Pelican_Db::$values = $DBVALUES_MONO;
    }
}
