<?php

/**
 * Fichier de Ndp_AssistantSite :.
 *
 * Classe Back-Office d'assitant de créatino de si
 *
 * @author Laurent Boulay <laurent.boulay@businessdecision.com>
 *
 * @since 08/11/2013
 */
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';

class Ndp_Administration_AssistantSite_Controller extends Ndp_Controller
{
    protected $multiLangue = false;
    protected $administration = true;
    protected $form_name = "ASSISTANT_SITE";
    protected $field_id = "SITE_ID";
    protected $defaultOrder = "SITE_ID";
    protected $processus = array("#pref#_site", "#pref#_site_code", array("#pref#_directory_site", "DIRECTORY_ID"), array("#pref#_site_dns", "SITE_DNS"), array("#pref#_site_language", 'LANGUE_ID'));
    protected $decacheBack = array('Ndp/CodePaysById', "Frontend/Site", "Backend/ContentType", "frontend_Frontend/Site/Url", array("Backend/Generic", "site"));

	public function init() {
		
		$oConnection = Pelican_Db::getInstance();
		
		if (!empty (Pelican::$config ["SITE_MASTER"])) {
			
			$aBind [':SITE_ID'] = Pelican::$config ["SITE_MASTER"];
			$aBind[':ADMIN_LOGIN'] = $oConnection->strToBind(Pelican::$config['ADMIN_LOGIN']);
			// assistant
	//		$oConnection->query ( 'select distinct CONTENT_TYPE_ID from #pref#_content_type_site', $aBind );
                        //se base sur le site master et le profil admin (manque le content_type_id 1 avec l'ancienne version sinon)
                    $sql = "select 
                        distinct(CONTENT_TYPE_ID)
                        from
                        #pref#_user_role
                        where
                        USER_LOGIN=:ADMIN_LOGIN
                        AND #pref#_user_role.SITE_ID=:SITE_ID";
                    $result = $oConnection->query($sql, $aBind);

			Pelican::$config ['ASSISTANT'] ['CONTENT'] = $oConnection->data ['CONTENT_TYPE_ID'];

			$directories = $oConnection->queryTab('select PROFILE_LABEL, DIRECTORY_ID from #pref#_profile p
inner join #pref#_profile_directory pd on (p.PROFILE_ID = pd.PROFILE_ID)
where SITE_ID = :SITE_ID
order by PROFILE_LABEL,PROFILE_DIRECTORY_ORDER', $aBind);
			
			foreach ($directories as $right) {
				Pelican::$config ['ASSISTANT'] ['DIRECTORY_ID'] [$right ['PROFILE_LABEL']] [] = $right ['DIRECTORY_ID'];
			}
		
		}
	
	}

    public function listAction()
    {
        if ($_GET['add'] == 1) {
            $message = <<<MS
                <div id="div_popup">
                    <div class="form_title">Création de minisite réussie</div>
                    <div>
                        <p>Le site pays a bien été créé.</p>
                        <p>Vous devez fermer votre session pour pouvoir vous connecter sur le BackOffice de ce site</p>
                        <p><a href="/_/Index/login" >Cliquez ici pour vous déconnecter</a></p>
                    </div>
                </div>
MS;

            $this->setResponse($message);
        } else {
            $this->id = $this->config['database']['insert_id'];
            $this->_initBack();
            $this->_forward("edit");
        }
    }

    public function editAction()
    {
        parent::editAction();

        /* Initialisation du formulaire */
        $form = self::getCss();

        $this->getView()
                ->getHead()
                ->setJs("/library/External/jquery/jquery.steps-1.0.3/jquery.steps.js");

        $script = '
$("#wizard").steps({
    transitionEffect:3,
    enableFinishButton:true,
    labels: {
        previous:\''.t('PREVIOUS', 'js').'\',
        next:\''.t('NEXT', 'js').'\',
        finish:\''.t('ADD_SITE').'\'
    },
    onStepChanging: function (event, currentIndex, newIndex)
    {
        return verifStep(currentIndex);
    },
    onFinishing: function (event, currentIndex)
    {
        return verifStep(currentIndex);
    },

    onFinished: function (event, currentIndex)
    {
        $(\'#fForm\').submit();
    }

});';
        $this->getView()->getHead()->setScript($script, 'foot');

        $this->oForm = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
        $form .= $this->oForm->open(Pelican::$config['DB_PATH']);
        $form .= $this->beginForm($this->oForm);
        $form .= $this->getSteps();
        $form .= $this->endForm($this->oForm, array(), $_SERVER['REQUEST_URI']);
        $form .= $this->oForm->close();

        $this->aButton["back"] = "";
        $this->aButton["save"] = "";
        Backoffice_Button_Helper::init($this->aButton);

        $sFinalForm = formToString($this->oForm, $form);
        $this->setResponse($sFinalForm);
    }

    private static function getCss()
    {
        $css = '
        <style type="text/css">
            .wizard ul, .tabcontrol ul {list-style: none!important;padding: 0;margin: 0;}
            .wizard>.steps>ul>li, .wizard>.actions>ul>li {float: left;}
            .wizard ul>li, .tabcontrol ul>li {display: block;padding: 0;}
            .wizard>.steps>ul>li {width: 25%;}
            .wizard>.steps .done a, .wizard>.steps .done a:hover, .wizard>.steps .done a:active {background: url("/library/Pelican/Index/Backoffice/public/skins/outlook/images/onglet_off_centre.gif") repeat-x;color: #000;}
            .wizard>.steps .number {font-size: 1.429em;}
            .wizard>.steps a, .wizard>.steps a:hover, .wizard>.steps a:active {display: block;width: auto;margin: 0 .5em .5em;padding: 1em 1em;text-decoration: none;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;}
            .wizard>.steps .current a, .wizard>.steps .current a:hover, .wizard>.steps .current a:active {background: url("/library/Pelican/Index/Backoffice/public/skins/outlook/images/onglet_on_centre.gif") repeat-x;color: #000;cursor: default;}
            .wizard>.steps .disabled a, .wizard>.steps .disabled a:hover, .wizard>.steps .disabled a:active {background: #eee;color: #aaa;cursor: default;}
            .wizard>.content {clear:both;display: block;margin: .5em;min-height: 35em;width: auto;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;}
            .wizard>.steps .current-info {display:none;}

            .wizard>.content h1.title {display:none}

            .wizard>.actions {position: relative;display: block;text-align: right;width: 100%;}
            .wizard>.actions>ul {display: inline-block;text-align: right;}
            .wizard>.actions>ul>li {margin: 0 .5em;}
            .wizard>.steps>ul>li, .wizard>.actions>ul>li {float: left;}
            .wizard>.actions a, .wizard>.actions a:hover, .wizard>.actions a:active {background: url("/library/Pelican/Index/Backoffice/public/skins/outlook/images/button_on.gif");color: #000;display: block;padding: .5em 1em;text-decoration: none;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;}
            .wizard a, .tabcontrol a {outline: 0;}
            .wizard>.actions>ul>li.disabled {display:none;}
        </style>
        ';

        return $css;
    }

    /*
     * Affichage du bandeau des étapes
     *
     * @return string
     */

    private function getSteps()
    {
        $aStep1 = $this->getStep1($this->oForm);
        $aStep2 = $this->getStep2($this->oForm);
        $aStep3 = $this->getStep3($this->oForm);
        $aStep4 = $this->getStep4($this->oForm);

        $script = '
            function verifStep(index) {
                obj = document.getElementById(\'fForm\');
                if (index == 0) {
                    '.$aStep1[1].'
                } else if (index == 1) {
                    '.$aStep2[1].'
                } else if (index == 2) {
                    '.$aStep3[1].'
                } else if (index == 3) {
                    '.$aStep4[1].'
                }
                return true;
            }


function check_url(url_label,url_div, id, width){
	var url = document.getElementById(url_label).value;
	callAjax("/Administration_Site/checkUrl", url_label, url, url_div, id, width);
}

function check_url_area(obj){
	callAjax({
			type: "POST",
			data: {urls : obj.value},
			url: "/_/Ndp_Administration_AssistantSite/ajaxVerifUrlArea",
            success : function(data) {
                obj.value = data[0].value;
                if (data[1].value != "") {
                    $("#msg_url_area").html("<span style=\"color: red;\">'.t('NEXT_URL_USED').' :<br/>- "+data[1].value.replace("\n", "<br/>- ")+"</span>");
                } else {
                    $("#msg_url_area").html("");
                }
            }
		});
}

function check_code(obj){
	callAjax({
			type: "POST",
			data: {code : obj.value, field : obj.id, div : "msg_code"},
			url: "/_/Ndp_Administration_AssistantSite/checkCode"
		});
}

';
        $this->getView()->getHead()->setScript($script, 'foot');

        $return = '
        <div id="wizard">
            <h1>'.t('Global parameters').'</h1>
            <div>
                <table>'.$aStep1[0].'</table>
            </div>

            <h1>'.t('DNS').'</h1>
            <div><table>'.$aStep2[0].'</table></div>

            <h1>'.t('Languages').'</h1>
            <div><table>'.$aStep3[0].'</table></div>

            <h1>'.t('SERVICES').'</h1>
            <div><table>'.$aStep4[0].'</table></div>
        </div>
        ';

        return $return;
    }

    /*
     * Affichage de la première étape
     *
     * @return string
     */

    private function getStep1(Pelican_Form $oForm)
    {
        $saveJS = $oForm->_sJS;

        $form = $oForm->createInput("SITE_LABEL", t('Nom'), 255, "", true, '', $this->readO, 100, false);
        $form .= $oForm->createInput("SITE_TITLE", t('Titre des pages'), 255, "", true, '', $this->readO, 100);
        $form .= $oForm->createInput("SITE_URL", t('URL principale'), 255, "", true, '', $this->readO, 100, false, "onBlur=check_url('SITE_URL','msg_url',".$this->id.",100)");
        $form .= "	<tr><td></td><td id='msg_url'></td></tr>";

        $aFuseau = array();
        for ($i = 0; $i < 13; $i++) {
            if ($i == 0) {
                $aFuseau[$i] = $i;
            } else {
                $aFuseau["+".$i] = "+".$i;
                $aFuseau["-".$i] = "-".$i;
            }
        }
        arsort($aFuseau);
        $form .= $oForm->createComboFromList("SITE_FUSEAU", t('Fuseau'), $aFuseau, $this->values["SITE_FUSEAU"], true, $this->readO);
        $form .= $oForm->createTextArea("SITE_MAIL_WEBMASTER", t('Mail webmaster').' (?)', true, '', 1024, $this->readO, 1, 100, false, "", true, "", t("FONCTIONNEMENT_MAIL_WEBMASTER"));
        $form .= $oForm->createInput("SITE_MAIL_EXPEDITEUR", t('Mail expediteur'), 100, "", true, 'no_return_address@mpsa.com', $this->readO, 100, false, "", "text", array(), false, "");
        $form .= $oForm->createInput("SITE_LOGIN_PREVISU", t('Login previsu'), 255, "", true, '', $this->readO, 100, false, "");
        $form .= $oForm->createPassword("SITE_PWD_PREVISU", t('Password previsu'), 100, true, '', $this->readO, 100, false);

        $aData = array(0 => t('DESACTIVER'), 1 => t('ACTIVER_WEB_TABLETTE'), 2 => t('ACTIVER_MOBILE'), 3 => t('ACTIVER_WEB_TABLETTE_MOBILE'));
        $form .= $oForm->createComboFromList("SITE_ACTIVATION_RECHERCHE", t('ACTIVATION_CHAMP_RECHERCHE '), $aData, false, true, $this->readO, 1, false, '', false);
        $form .= $oForm->createComboFromList("SITE_ACTIVATION_AUTOCOMPLETION", t('ACTIVATION_AUTOCOMPLETION'), $aData, false, true, $this->readO, 1, false, '', false);

        $endJS = $oForm->_sJS;

        return array($form, str_replace($saveJS, '', $endJS));
    }

    /*
     * Affichage de la deuxième étape
     *
     * @return string
     */

    private function getStep2($oForm)
    {
        $saveJS = $oForm->_sJS;
        $form = $oForm->createTextArea("SITE_DNS", t('Available alias').' (?)', true, '', "", $this->readO, 10, 50, false, "", true, "onBlur=check_url_area(this)", t("FONCTIONNEMENT_DNS"));
        $form .= "	<tr><td></td><td id='msg_url_area'></td></tr>";
        $endJS = $oForm->_sJS;

        return array($form, str_replace($saveJS, '', $endJS));
    }

    /*
     * Affichage de la troisième étape
     *
     * @return string
     */

    private function getStep3($oForm)
    {
        $oConnection = Pelican_Db::getInstance();

        $saveJS = $oForm->_sJS;
        $strSQLList = "SELECT langue_id as id, ".$oConnection->getConcatClause(array("langue_label", "' ('", "langue_translate", "')'"))." as lib
				FROM #pref#_language
				ORDER BY lib";
        $form = $oForm->createAssocFromSql(null, "assoc_langue_id", t('Site languages'), $strSQLList, array(), true, true, $this->readO, 5, 250, false, "");
        $form .= $oForm->createInput("SITE_CODE_PAYS", t('CODE_PAYS'), 4, "", true, '', $this->readO, 10, false, "onBlur=check_code(this)");
        $form .= $oForm->createInput("SITE_CODE_LDAP", t('CODE_LDAP'), 6, "", true, '', $this->readO, 10, false, "onBlur=check_code(this)");
        $form .= "	<tr><td></td><td id='msg_code'></td></tr>";
        $endJS = $oForm->_sJS;

        return array($form, str_replace($saveJS, '', $endJS));
    }

    /*
     * Affichage de la quatrième étape
     *
     * @return string
     */

    private function getStep4($oForm)
    {
        $oConnection = Pelican_Db::getInstance();

        $saveJS = $oForm->_sJS;
        $form = $oForm->createComboFromSql($oConnection, "MAP_PROVIDER_ID", t('Map provider'), "select MAP_PROVIDER_ID as id, MAP_PROVIDER_LABEL as lib from #pref#_map_provider where MAP_PROVIDER_HIDE IS NULL order by MAP_PROVIDER_LABEL", $this->values ["MAP_PROVIDER_ID"], false, $this->readO);
        $mapProviders = $oConnection->queryTab("select * from #pref#_map_provider order by MAP_PROVIDER_ID");
        $siteDNS = $oConnection->queryTab("select sd.SITE_DNS, spd.SITE_PARAMETER_ID, spd.SITE_PARAMETER_VALUE, spd.SITE_PARAMETER_PARAM from #pref#_site_dns sd
        		left join #pref#_site_parameter_dns as spd on (sd.SITE_ID=spd.SITE_ID and sd.SITE_DNS=spd.SITE_DNS and SITE_PARAMETER_ID like 'map_%')
        		where sd.SITE_ID=".$this->id." order by sd.SITE_DNS");
        $crossTab= [];
        foreach ($siteDNS as $val) {
            $crossTab[$val ['SITE_DNS']] [$val ['SITE_PARAMETER_ID']] = $val ['SITE_PARAMETER_VALUE'];
            if ($val ['SITE_PARAMETER_ID'] == "map_google") {
                $crossTab[$val ['SITE_DNS']] [$val ['SITE_PARAMETER_ID'].'_KEY'] = $val ['SITE_PARAMETER_PARAM'];
            }
        }

        if (!empty($crossTab)) {
            $form .= '<tr><td colspan="2"><table>';
            foreach ($crossTab as $key => $val) {
                if (empty($mapTable)) {
                    $mapTable = '<tr><th>&nbsp;</th><th>';
                    foreach ($mapProviders as $mp) {
                        $mapTable .= '<th>'.$mp ['MAP_PROVIDER_LABEL'].'</th>';
                    }
                    $mapTable .= '</th></tr>';
                }
                $mapTable .= '<tr><td class="formlib">'.$key.'</td><td>';
                foreach ($mapProviders as $mp) {
                    $label = $key.'...'.$mp ['MAP_PROVIDER_CODE'];
                    if ($mp ['MAP_PROVIDER_CODE'] == "google") {
                        $sKey = $key.'...'.$mp ['MAP_PROVIDER_CODE'].'...KEY_MAP';
                        $mapTable .= '<td style="padding:5px;"><label>'.t('CLIENT_MAP').'</label><br>'.$oForm->createInput($label, '', 100, "", false, $val ['map_'.$mp ['MAP_PROVIDER_CODE']], false, 20, true);
                        $mapTable .= '<label>'.t('KEY_MAP').'</label><br>'.$oForm->createInput($sKey, '', 100, "", false, $val ['map_'.$mp ['MAP_PROVIDER_CODE'].'_KEY'], false, 20, true);
                    } else {
                        $mapTable .= '<td style="padding:5px;">'.$oForm->createInput($label, '', 100, "", false, $val ['map_'.$mp ['MAP_PROVIDER_CODE']], false, 20, true);
                    }
                    $mapFields [] = $label;
                }
                $mapTable .= '</td></tr>';
            }

            $form .= $mapTable.'</table></td></tr>';

            $form .= $oForm->createHidden("map_fields", (is_array($mapFields) ? implode('#', $mapFields) : ""));
        }
        $endJS = $oForm->_sJS;

        return array($form, str_replace($saveJS, '', $endJS));
    }

    public function saveAction()
    {
        $oConnection = Pelican_Db::getInstance();

        if (!Pelican_Db::$values['SITE_CITROEN_FONT2']) {
            Pelican_Db::$values['SITE_CITROEN_FONT2'] = '0';
        }
        $aBind [':SITE_ID'] = Pelican_Db::$values ['SITE_ID'];
        Pelican_Db::$values ["SITE_DNS"] = Pelican_Form::splitTextarea(Pelican_Db::$values ["SITE_DNS"]);
        sort(Pelican_Db::$values ["assoc_langue_id"]);
        Pelican_Db::$values ['LANGUE_ID'] = Pelican_Db::$values ["assoc_langue_id"];
        $tmp = Pelican_Db::$values ['LANGUE_ID'][0];
        Pelican_Db::$values ['DIRECTORY_ID'] = Pelican::$config['ASSISTANT']['DIRECTORY_ID']['ADMINISTRATEUR'];
        $oConnection->updateForm($this->form_action, $this->processus);
        $save = Pelican_Db::$values;
        if ($this->form_action != Pelican_Db::DATABASE_DELETE) {
            $mapFields = explode('#', Pelican_Db::$values ['map_fields']);
            foreach ($mapFields as $map) {
                $temp = explode('...', $map);
                Pelican_Db::$values ['SITE_DNS'] = $temp [0];
                Pelican_Db::$values ['SITE_PARAMETER_ID'] = 'map_'.$temp [1];
                Pelican_Db::$values ['SITE_PARAMETER_VALUE'] = Pelican_Db::$values [str_replace('.', '_', $map)];
                if ($temp [1] == "google") {
                    Pelican_Db::$values ['SITE_PARAMETER_PARAM'] = Pelican_Db::$values [str_replace('.', '_', $map).'___KEY_MAP'];
                }
                if (Pelican_Db::$values ['SITE_PARAMETER_VALUE']) {
                    $oConnection->insertQuery('#pref#_site_parameter_dns');
                }
            }
        }
        Pelican_Db::$values = $save;
        Pelican_Db::$values ['LANGUE_ID'] = $tmp;

        //Gestion Robots.txt
        /* $sFolderRobot = Pelican::$config["DOCUMENT_INIT"] . "/var/robots/".Pelican_Db::$values ['SITE_CODE_PAYS'];
          $FileWeb = $sFolderRobot."/robots_web.txt";
          $FileMob = $sFolderRobot."/robots_mob.txt";
          $this->verifyDir($sFolderRobot);
          if(Pelican_Db::$values ['SITE_CODE_PAYS'] != "" && Pelican_Db::$values ['SITE_ROBOT_DESK'] != ""){
          @unlink( $FileWeb );
          file_put_contents($FileWeb, Pelican_Db::$values ['SITE_ROBOT_DESK']);
          }
          if(Pelican_Db::$values ['SITE_CODE_PAYS'] != "" && Pelican_Db::$values ['SITE_ROBOT_MOBILE'] != ""){
          @unlink( $FileMob );
          file_put_contents($FileMob, Pelican_Db::$values ['SITE_ROBOT_MOBILE']);
          } */
        Pelican_Db::$values['form_retour'] .= '&add=1';
    }

    public function verifyDir($dir_name, $permission = 755)
    {
        if (!is_dir($dir_name)) {
            if (isset($_SERVER['WINDIR'])) {
                mkdir($dir_name, $permission, true);
            } else {
                $cmd = "mkdir -p -m ".$permission." ".$dir_name;
                Pelican::runCommand($cmd);
            }
        }
    }

    public function afterInsert()
    {
        $i = 0;
		$oConnection = Pelican_Db::getInstance();

		if (is_array(Pelican_Db::$values ['LANGUE_ID'])) {
			Pelican_Db::$values ['LANGUE_ID'] = reset(Pelican_Db::$values ['LANGUE_ID']);
		}
		$lang = Pelican_Db::$values ['LANGUE_ID'];

		/**
         * Template de navigation
         */
        Pelican_Db::$values ["TEMPLATE_PAGE_ID"] = Pelican::$config['TEMPLATE_PAGE']['GLOBAL']; // $navigation;
        Pelican_Db::$values ["PAGE_ID"] = -2;
        Pelican_Db::$values ["PAGE_DRAFT_VERSION"] = 1;
        Pelican_Db::$values ["PAGE_ORDER"] = -1;
	Pelican_Db::$values ["PAGE_TITLE"] = t("NDP_PAGE_GENERALE");
	Pelican_Db::$values ["PAGE_TITLE_BO"] = t("NDP_PAGE_GENERALE");
        Pelican_Db::$values ["PAGE_VERSION"] = 1;
        Pelican_Db::$values ["STATE_ID"] = 1;
        Pelican_Db::$values ["PAGE_GENERAL"] = 1;
		Pelican_Db::$values ['LANGUE_ID'] = $lang;

        $oConnection->insertQuery("#pref#_page");
        $oConnection->insertQuery("#pref#_page_version");

		Pelican_Db::$values ["PAGE_PATH"] = Pelican_Db::$values ["PAGE_ID"];
		Pelican_Db::$values ["PAGE_LIBPATH"] = Pelican_Db::$values ["PAGE_ID"].'|'.Pelican_Db::$values ["PAGE_TITLE_BO"];
		$oConnection->updateQuery("#pref#_page");
		
		/**
         * Template de la Home
         */
        Pelican_Db::$values ["TEMPLATE_PAGE_ID"] = Pelican::$config['TEMPLATE_PAGE']['HOME']; // $home;
        Pelican_Db::$values ["PAGE_ID"] = -2;
        Pelican_Db::$values ["PAGE_DRAFT_VERSION"] = 1;
		Pelican_Db::$values ["PAGE_ORDER"] = 0;
        Pelican_Db::$values ["PAGE_TITLE"] = "Accueil";
        Pelican_Db::$values ["PAGE_TITLE_BO"] = "Accueil";
        Pelican_Db::$values ["PAGE_VERSION"] = 1;
        Pelican_Db::$values ["STATE_ID"] = 1;
        Pelican_Db::$values ["PAGE_GENERAL"] = 0;
		Pelican_Db::$values ['LANGUE_ID'] = $lang;

        $oConnection->insertQuery("#pref#_page");
        $oConnection->insertQuery("#pref#_page_version");

		Pelican_Db::$values ["PAGE_PATH"] = Pelican_Db::$values ["PAGE_ID"];
		Pelican_Db::$values ["PAGE_LIBPATH"] = Pelican_Db::$values ["PAGE_ID"].'|'.Pelican_Db::$values ["PAGE_TITLE_BO"];
		$oConnection->updateQuery("#pref#_page");
		
		/**
         * Profile
         */
        if (!empty(Pelican::$config['ASSISTANT']['DIRECTORY_ID'])) {
            foreach (Pelican::$config['ASSISTANT']['DIRECTORY_ID'] as $profile => $aDir) {
                Pelican_Db::$values ["PROFILE_ID"] = - 2; // $profile;
                Pelican_Db::$values ["PROFILE_LABEL"] = $profile;
                Pelican_Db::$values ["PROFILE_ADMIN"] = "0";
                $oConnection->insertQuery("#pref#_profile");

                foreach ($aDir as $dir) {
                    $i++;
                    $oConnection->Query("INSERT INTO #pref#_profile_directory ( PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER ) VALUES ( ".Pelican_Db::$values ["PROFILE_ID"].", ".$dir.", ".Pelican_Db::$values ["PROFILE_ID"].str_pad($i, 3, "0", STR_PAD_LEFT).")");
                }
				
				// for non LDAP admin
				Pelican_Db::$values ["USER_LOGIN"] = 'admin';
				$oConnection->insertQuery("#pref#_user_profile");
            }
        }

        /*
         * Type de contenu
         */
        if (!empty(Pelican::$config['ASSISTANT']['CONTENT'])) {
            foreach (Pelican::$config['ASSISTANT']['CONTENT'] as $content) {
                Pelican_Db::$values ["CONTENT_TYPE_ID"] = $content;
                $oConnection->insertQuery("#pref#_content_type_site");
				
				// for non LDAP admin
				Pelican_Db::$values ["ROLE_ID"] = 7;
				Pelican_Db::$values ["USER_LOGIN"] = 'admin';
				$oConnection->insertQuery("#pref#_user_role");
            }
        }

        /*
         * Mediatheque
         */
        $aMediaDirectory = $oConnection->queryRow("SELECT MEDIA_DIRECTORY_ID, MEDIA_DIRECTORY_LABEL FROM #pref#_media_directory WHERE SITE_ID = :SITE_ID AND MEDIA_DIRECTORY_PARENT_ID IS NULL", array(':SITE_ID' => Pelican::$config["SITE_MASTER"]));
        Pelican_Db::$values ["MEDIA_DIRECTORY_ID"] = - 2; // $media;
        Pelican_Db::$values ["MEDIA_DIRECTORY_PARENT_ID"] = $aMediaDirectory['MEDIA_DIRECTORY_ID'];
        Pelican_Db::$values ["MEDIA_DIRECTORY_LABEL"] = strtoupper(Pelican_Db::$values['SITE_CODE_PAYS']);
        Pelican_Db::$values ["MEDIA_DIRECTORY_PATH"] = $aMediaDirectory['MEDIA_DIRECTORY_LABEL'].' > '.strtoupper(Pelican_Db::$values['SITE_CODE_PAYS']);
        $oConnection->insertQuery("#pref#_media_directory");
	
    }

    public function ajaxVerifUrlAreaAction()
    {
        $oConnection = Pelican_Db::getInstance();
        $DNS = array();
        $DNSDelete = array();

        if ($this->getParam('urls')) {
            $urls = explode("\n", $this->getParam('urls'));

            if (!empty($urls)) {
                foreach ($urls as $url) {
                    $aBind [':URL'] = $oConnection->strtoBind(trim($url));

                    $sSQL = "
                    (
                        SELECT site_id
                        FROM #pref#_site
                        WHERE
                            site_url=:URL
                            OR site_media_url=:URL
                    )
                    UNION
                    (
                        SELECT site_id
                        FROM #pref#_site_dns
                        WHERE
                            site_dns=:URL
                    )
                    ";
                    $aResult = $oConnection->queryRow($sSQL, $aBind);

                    if (count($aResult) == 0) {
                        $DNS[] = trim($url);
                    } else {
                        $DNSDelete[] = trim($url);
                    }
                }

                $DNS = implode("\n", $DNS);
                $DNSDelete = implode("\n", $DNSDelete);

                $this->addResponseCommand('debug', array('value' => $DNS));
                $this->addResponseCommand('debug', array('value' => $DNSDelete));
            }
        }
    }

    public function checkCodeAction()
    {
        set_time_limit(0);
        $aData = $this->getParams();
        $code = $aData['code'];
        $field = $aData['field'];
        $div = $aData['div'];

        if (!$_SESSION [APP] ["user"] ["id"]) {
            echo("Veuillez vous identifier en Back Office");
            exit();
        }

        $oConnection = Pelican_Db::getInstance();
        $oConnection->setExitOnError(false);

        $aBind [':CODE'] = $oConnection->strtoBind($code);

        $sSQL = "
				SELECT site_id
				FROM #pref#_site_code
				WHERE
					site_code_pays = :CODE
			";

        $aResult = $oConnection->queryRow($sSQL, $aBind);
        if ($code == "") {   // l'url est deja utilisée
            $error = "<span style=\"color: red;\">".$code.t('CODE_OBLIGATOIRE')."</span>";
            $code = "";
        } else {
            if (count($aResult) > 0) {   // l'url est deja utilisée
                $error = "<span style=\"color: red;\">".t('CODE_DEJA_UTILISEE')." : ".$code."</span>";
                $code = "";
            } else { // la nouvelle url est valide
                $error = "";
            }
        }

        $this->getRequest()->addResponseCommand('assign', array('id' => $field, 'attr' => 'value', 'value' => $code));
        $this->getRequest()->addResponseCommand('assign', array('id' => $div, 'attr' => 'innerHTML', 'value' => $error));
    }
}
