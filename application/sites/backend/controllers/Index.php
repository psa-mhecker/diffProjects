<?php
pelican_import('Profiler');
pelican_import('Hierarchy');
pelican_import('Hierarchy.Tree');
require_once 'External/Cpw/User.php';
include_once (dirname(__FILE__) . '/Cms/Page/Perso.php');

class Index_Controller extends Pelican_Controller_Back
{

    /**
     */
    public $adaptiveDriver = array(
        'txt' => 'Text',
        'iphone' => 'Iphone'
    );

    /**
     */
    public function indexAction ()
    {
        
        /**
         * tronc commun
         */
        self::_indexCommon();
        
        $head = $this->getView()->getHead();
        
        /**
         * Gestion des roles pour un site
         */
        $this->_setRolesBySiteId();
        
        /**
         * Permet de setter en session le label du profil selectionné
         */
        $this->_setProfilLabelSession();
      
        /**
         * head
         */
        $head->setScript("var vIndexIframePath = '" . Pelican::$config["PAGE_INDEX_IFRAME_PATH"] . "';");
        $head->setScript("var vIndexPath = '" . Pelican::$config["PAGE_INDEX_PATH"] . "';");
        $head->setScript("var vTransactionPath = '" . Pelican::$config["DB_PATH"] . "';");
        $head->setScript("var vView = '" . (isset($_GET["view"]) ? $_GET["view"] : "") . "';");
        $head->setScript("var vOnline = '" . (isset(Pelican::$config["ACTION_ONLINE"]) ? Pelican::$config["ACTION_ONLINE"] : "") . "';");
        
        /* Rajout du script gérant l'id de Back Office */
        $head->setJs("/js/boIdScript.js");
        
        $head->endJQuery('blockui');
        $head->setScript(Backoffice_Button_Helper::clickActionJavascript());




        $this->getHierarchieMenu();
        if ((empty($_GET["view"]) && ! empty($this->aHierarchie["onglet"])) || (! empty($_GET["view"]) && empty($this->aHierarchie["onglet"][$_GET["view"]]))) {
            $temp = array_keys($this->aHierarchie["onglet"]);
            $_GET["view"] = $temp[0];
        }


        /**
        *
        * Permet de rediriger depuis la mediatheque
        *
        */
        if(isset($_GET['pid_mediatheque']) && isset($_GET["fromMediatheque"]) && $_GET["fromMediatheque"] == 1)
        {
            $_SESSION[APP]['PAGE_ID'] = $_GET['pid_mediatheque'] ;

            // Si c'est du contenu : 
            if(isset($_GET['isContentM']) && $_GET['isContentM'] == 1 && isset($_GET['pid_mediatheque_type']))
            {   
                    $_SESSION[APP]['session_start_page'] = "/_/Index/child?rechercheTexte=&rechercheContentType=0&recherchePage=&rechercheState=&rechercheAuteur=&rechercheDateDebut=&rechercheDateFin=&submitRecherche=Rechercher&lang=&view=O_".Pelican::$config['TEMPLATE_ADMIN_CONTENT']."&popup_content=0&mutualisation=&rechercheSite=".$_SESSION[APP]["SITE_MEDIA"]."&media=image&order=&navRows=157&navPage=1&navLimitRows=20&navMaxLinks=9&navFirstPage=1&navMinRow=1&navMaxRow=20&id=".$_GET['pid_mediatheque']."&tid=24&uid=".$_GET['pid_mediatheque_type'];
                     $_SESSION[APP]['session_start_page1'] = "/_/Index/child?rechercheTexte=&rechercheContentType=0&recherchePage=&rechercheState=&rechercheAuteur=&rechercheDateDebut=&rechercheDateFin=&submitRecherche=Rechercher&lang=&view=O_".Pelican::$config['TEMPLATE_ADMIN_CONTENT']."&popup_content=0&mutualisation=&rechercheSite=".$_SESSION[APP]["SITE_MEDIA"]."&media=image&order=&navRows=157&navPage=1&navLimitRows=20&navMaxLinks=9&navFirstPage=1&navMinRow=1&navMaxRow=20&id=".$_GET['pid_mediatheque']."&tid=24&uid=".$_GET['pid_mediatheque_type'];
                    
                    $_SESSION[APP]["view"] = "O_" . Pelican::$config["ONGLET_CONTENT"];
                    $head->endScript("
                    var iframejr = document.getElementById('iframeRight');
                    iframejr.src ='/_/Index/child?rechercheTexte=&rechercheContentType=0&recherchePage=&rechercheState=&rechercheAuteur=&rechercheDateDebut=&rechercheDateFin=&submitRecherche=Rechercher&lang=&view=O_".Pelican::$config['TEMPLATE_ADMIN_CONTENT']."&popup_content=0&mutualisation=&rechercheSite=".$_SESSION[APP]["SITE_MEDIA"]."&media=image&order=&navRows=157&navPage=1&navLimitRows=20&navMaxLinks=9&navFirstPage=1&navMinRow=1&navMaxRow=20&id=".$_GET['pid_mediatheque']."&tid=24&uid=".$_GET['pid_mediatheque_type']."';
                    top.activeOngletRubrique(document, '1');
                    iframejr.src ='/_/Index/child?rechercheTexte=&rechercheContentType=0&recherchePage=&rechercheState=&rechercheAuteur=&rechercheDateDebut=&rechercheDateFin=&submitRecherche=Rechercher&lang=&view=O_".Pelican::$config['TEMPLATE_ADMIN_CONTENT']."&popup_content=0&mutualisation=&rechercheSite=".$_SESSION[APP]["SITE_MEDIA"]."&media=image&order=&navRows=157&navPage=1&navLimitRows=20&navMaxLinks=9&navFirstPage=1&navMinRow=1&navMaxRow=20&id=".$_GET['pid_mediatheque']."&tid=24&uid=".$_GET['pid_mediatheque_type']."';

                    ");
            }
        }
        /**
         * body
         */
        $this->assign('title', isset($head->titleLeft) ? $head->titleLeft : t('RUBRIQUES'));
        $this->assign('top', Backoffice_Div_Helper::top($this->aHierarchie["name"]), false);
        $this->assign('tab', Backoffice_Div_Helper::tab($this->aHierarchie["onglet"], $this->bPopup), false);
        $this->assign('left_middle', Backoffice_Div_Helper::leftMiddle($this->aHierarchie["onglet"], $_GET["view"]), false);
        $this->assign('left_bottom', Backoffice_Div_Helper::leftBottom(), false);

        $this->assign('right_middle', Backoffice_Div_Helper::rightMiddle(), false);
        $this->assign('right_bottom', Backoffice_Div_Helper::rightBottom($this->bPopup), false);
        $this->assign('footer', Backoffice_Div_Helper::footer(), false);
        $this->assign('default', (! empty($this->getView()->default) ? $this->getView()->default : ''), false);
        
        /**
         * a faire a la fin pour etre sur d'avoir tous les js et css
         */
        $this->assign('doctype', $head->getDocType());
        $this->assign('header', $head->getHeader(false), false);
        $this->assign('footer', $head->getFooter(), false);


        $this->fetch();
    }

    public function childAction ()
    {
        /**
         * tronc commun
         */
        self::_indexCommon();
        
        /**
         * head
         */
        $head = $this->getView()->getHead();
        $head->endJs(Pelican::$config["LIB_PATH"] . Pelican::$config['LIB_FORM'] . "/js/xt_toggle.js");
        $head->endScript("function menu(rid) {top.menu(rid);}");
        $head->endJs("/perso.js");
        
        /**
         * controller principal
         */
        if (! empty($_GET["error"])) {
            // Affichage des erreurs
            $this->assign('body', Pelican_Request::call('Error'), false);
        } else {
            if (! empty($_GET["tid"]) || ! empty($_GET["comment"])) {
                if (! empty($_GET["comment"])) {
                    $_template_path = '/Comment';
                } else {
                    $aTemplate = Pelican_Cache::fetch("Template", $_GET["tid"]);
                    $root = ($aTemplate[0]["PLUGIN_ID"] ? '_/module/' . $aTemplate[0]["PLUGIN_ID"] . '/' : '_/');
                    $_template_path = $root . $aTemplate[0]["TEMPLATE_PATH"];
                    $_template_complement = $aTemplate[0]["TEMPLATE_COMPLEMENT"];
                    $this->getView()->getHead()->sTitle = $aTemplate[0]['lib'];
                }
                if (! empty($_GET["tc"])) {
                    $_template_complement = $_GET["tc"];
                }
                if (! empty($_template_path)) {
                    $return = Pelican_Request::call($_template_path);
                } else {
                    $_GET['error'] = t('PAGE_NOT_FOUND');
                    $this->assign('body', Pelican_Request::call('Error'), false);
                }
            }
        }
        
        // Message d'information à afficher dans la page
        if (!empty($GLOBALS['flash_message'])) {
            $this->assign('flash_message', $GLOBALS['flash_message']);
        }
        
        /**
         * body
         */
        $this->assign('begin', '');
        if (empty($_GET['iframe'])) {
            $this->assign('button', Backoffice_Button_Helper::updateButtons(), false);
        }
        $this->assign('body', $return, false);
        /**
         * a faire a la fin pour etre sur d'avoir tous les js et css
         */
        $this->assign('doctype', $head->getDocType());
        $this->assign('header', $head->getHeader(false), false);
        $this->assign('footer', $head->getFooter(), false);
        $this->fetch();
    }

    public function popupAction ()
    {
        $_GET["popup_content"] = "1";
        $_GET["view"] = "O_" . Pelican::$config["ONGLET_CONTENT"];
        
        /**
         * tronc commun
         */
        self::_indexCommon();
        
        /**
         * head
         */
        $head = $this->getView()->getHead();
        $head->setScript("var id = '';");
        $head->setScript("var sTitle = '';");
        $head->setScript("var sHistory = '';");
        $head->setScript("var leftWidth = '';");
        $head->setScript("var rightWidth = '';");
        $head->setScript("var iframeWidth = '';");
        $head->setScript("var zone = '" . ($_GET["zone"] ? $_GET["zone"] : "editor") . "';");
        $head->setScript("var mutualisation = " . ($_GET["mutualisation"] ? "true" : "false") . ";");
        $head->setScript("var sForm = '" . $_GET["form"] . "';");
        $head->setScript("var sField = '" . $_GET["field"] . "';");
        $head->setScript("var libDir = '" . Pelican::$config["LIB_PATH"] . "';");
        $head->setJs("/js/script.js");
        $head->setIncludeHeader(Pelican::$config["INDEX_ROOT"] . "/js/popup.js.php");
        $head->setTitle(t('EDITOR_INTERNAL'));
        
        $this->getHierarchieMenu();
        
        /**
         * body
         */
        $this->assign('title', isset($head->titleLeft) ? $head->titleLeft : t('RUBRIQUES'));
        $this->assign('left_middle', Backoffice_Div_Helper::leftMiddle($this->aHierarchie["onglet"], $_GET["view"]), false);
        $this->assign('left_bottom', Backoffice_Div_Helper::leftBottom(), false);
        $this->assign('right_middle', Backoffice_Div_Helper::rightMiddle(), false);
        $this->assign('right_bottom', Backoffice_Div_Helper::rightBottom($this->bPopup), false);
        $this->assign('default', (! empty($this->getView()->default) ? $this->getView()->default : ''), false);
        
        /**
         * a faire a la fin pour etre sur d'avoir tous les js et css
         */
        $this->assign('doctype', $head->getDocType());
        $this->assign('header', $head->getHeader(false), false);
        $this->assign('footer', $head->getFooter(), false);
        
        $this->fetch();
    }

    public function loginAction ()
    {
        $head = $this->getView()->getHead();
        
        // libellé de cookie volontairement erronné !
        if (! empty($_COOKIE["screen_depth"])) {
            $_SESSION[APP]["login_attempt"] = $_COOKIE["screen_depth"];
        }
        
        if ($this->getRequest()->isPost()) {
            
            /**
             * * Vérification des caractères utilisés dans le login/password et identification
             */
            if (isset($_POST["login"]) && isset($_POST["password"])) {
                /**
                 * * Suppression des anciennes variables de session
                 */
                if (!empty($_SESSION[APP]['LANGUE_CODE'])) {
                    $saveLang = $_SESSION[APP]['LANG'];
                    $saveLangCode = $_SESSION[APP]['LANGUE_CODE'];
                    $saveLangId = $_SESSION[APP]['LANGUE_ID'];
                }
                $_SESSION[APP] = array();
                if (!empty($saveLangCode)) {
                    $_SESSION[APP]['LANG'] = $saveLang;
                    $_SESSION[APP]['LANGUE_CODE'] = $saveLangCode;
                    $_SESSION[APP]['LANGUE_ID'] = $saveLangId;
                }
                
                $sLogin = $_POST["login"];
                $sPwd = $_POST["password"];
				
                $isLoginAllowed = Pelican_Auth::controlAttempt($sLogin);
                if ($isLoginAllowed) {
                /**
                 * Création de l'objet oUser
                 */
                $oUser = Pelican_Factory::getUser('Backoffice');
               
                $oUserCppv = new Citroen_User_Backoffice($sLogin, $sPwd);
               
                /**
                 * Connexion Ldap
                 */
                $userLdap = new Cpw_User($sLogin, $sPwd);
                 
                $bIsLdap = false;
                
                    $bLdapLogged = $userLdap->login();
					
                   
                    if ($bLdapLogged) {
						
					   // JIRA PFDPAR-34
                        $password = Pelican_Security_Password::generate(6,50);
                        $sPwd = $password;
                        $bIsLdap = true;
                        $sLastName = (string)$userLdap->getLastname();
                        $sFirstName = (string)$userLdap->getFirstname();
                        $sEmail = (string)$userLdap->getEmail();
                        $bAdmin = $userLdap->isAdmin();
                        $sLogin = $userLdap->getLogin();
                        $aStrongestRights = $userLdap->getRights();
						
                        $oUserCppv->deleteUser($sLogin);
						
                        $aRights = $userLdap->getBusiness();
                        $aParams['login'] = $sLogin;
                        $aParams['password'] = $sPwd;
                        $aParams['email'] = $sEmail;
                        $aParams['admin'] = $bAdmin;
                        $aParams['name'] = $sFirstName . ' ' . $sLastName;
                        $aParams['rights'] = array(
                            'strongest' => $aStrongestRights,
                            "all" => $aRights
                        );
                        $aParams['is_ldap'] = $bIsLdap;
                        $oUserCppv->createUser($aParams);
                    }
                //}
          
                
				$oUserCppv->login($sLogin,$sPwd, $bIsLdap);
				
                $log = false;
                $choose = false;
              
                if ($oUser->isLoggedIn()) {
					
                    $result = $oUser->getFullInfos();
					
                    /**
                     * * Si le user existe, on crée ses variables de session
                     */
                    if ($result) {
                        
                        /**
                         * * USER
                         */
                        $_SESSION[APP]["user"]["id"] = $result[0]["USER_LOGIN"];
                        $_SESSION[APP]["user"]["name"] = $result[0]["USER_NAME"];
                        $_SESSION[APP]["user"]["email"] = $result[0]["USER_EMAIL"];
                        $_SESSION[APP]["user"]["main"] = $result[0]["USER_FULL"];
                        $_SESSION[APP]["admin"] = $result[0]["PROFILE_ADMIN"];
                        
                        /**
                         * * NAVIGATION
                         */
                        $_SESSION[APP]["navigation"] = self::_getNavigation($result);
                        
                        /**
                         * * COMBO NAVIGATION
                         */
                        if (count($_SESSION[APP]["navigation"]["site"]) > 1) {
                            $aCombo[][] = Pelican_Html::option(array(
                                'value' => "",
                                "class" => 'SITE_ID'
                            ), "&nbsp;");
                            foreach ($_SESSION[APP]["navigation"]["site"] as $siteValue) {
                                $initCombo[$siteValue["name"] . "_" . $siteValue["profile_name"]] = $siteValue;
                            }
                            ksort($initCombo);
                            foreach ($initCombo as $site => $siteValue) {
                                if (is_array($siteValue) && $siteValue["id"]) {
                                    $aCombo[$siteValue["name"]][] = Pelican_Html::option(array(
                                        'value' => $siteValue["profile_id"] . "_" . $siteValue["id"],
                                        "class" => 'SITE_ID'
                                    ), Pelican_Text::htmlentities($siteValue["profile_name"]));
                                }
                            }
                        }
                        if ($aCombo) {
                            foreach ($aCombo as $optgroup => $aOption) {
                                if ($optgroup) {
                                    $combo[] = Pelican_Html::optgroup(array(
                                        'label' => Pelican_Text::htmlentities($optgroup),
                                        "class" => 'SITE_ID'
                                    ), implode("", $aOption));
                                } else {
                                    $combo[] = implode("", $aOption);
                                }
                            }
                            $htmlComboSite = Pelican_Html::select(array(
                                'id' => 'SITE_ID',
                                'name' => 'SITE_ID',
                                'class' => "text",
                                'onchange' => "change_site(this);"
                            ), implode("", $combo));
                        }
                        $_SESSION[APP]["htmlComboSite"] = $htmlComboSite;
                        
                        /**
                         * * PROFILS
                         */
                        $sql = "select
                    " . Pelican::$config['FW_PREFIXE_TABLE'] . "profile.PROFILE_ID as \"PROFILE_ID\"
                    from
                    " . Pelican::$config['FW_PREFIXE_TABLE'] . "profile,
                    " . Pelican::$config['FW_PREFIXE_TABLE'] . "user_profile
                    where
                    " . Pelican::$config['FW_PREFIXE_TABLE'] . "user_profile.PROFILE_ID=" . Pelican::$config['FW_PREFIXE_TABLE'] . "profile.PROFILE_ID
                    and USER_LOGIN='" . $_SESSION[APP]["user"]["id"] . "'";
                        
                        $oConnection = Pelican_Db::getInstance();
                        
                        $oConnection->Query($sql);
                        
                        $_SESSION[APP]["profile"] = $oConnection->data["PROFILE_ID"];
                        
                        /*
                         * $sql = "select " . Pelican::$config['FW_PREFIXE_TABLE'] . "user_role.ROLE_ID, CONTENT_TYPE_ID, STATE_PARENT_ID, STATE_ID from " . Pelican::$config['FW_PREFIXE_TABLE'] . "user_role, " . Pelican::$config['FW_PREFIXE_TABLE'] . "state_dependencies where USER_LOGIN='" . $_SESSION[APP]["user"]["id"] . "' AND " . Pelican::$config['FW_PREFIXE_TABLE'] . "user_role.ROLE_ID = " . Pelican::$config['FW_PREFIXE_TABLE'] . "state_dependencies.ROLE_ID"; //AND " . Pelican::$config['FW_PREFIXE_TABLE'] . "user_role.SITE_ID=" . $_POST['SITE_ID']; $result = $oConnection->getTab($sql); if ($result) { foreach ($result as $state) { $_SESSION[APP]["state"]["id"][$state["STATE_PARENT_ID"]] = $state["STATE_ID"]; $_SESSION[APP]["state"][$state["STATE_PARENT_ID"]]["content_type"][$state["CONTENT_TYPE_ID"]] = $state["CONTENT_TYPE_ID"]; $_SESSION[APP]["content_type"][$state["CONTENT_TYPE_ID"]]["state"][$state["STATE_PARENT_ID"]][] = $state["STATE_ID"]; $_SESSION[APP]["content_type"]["id"][$state["CONTENT_TYPE_ID"]] = $state["CONTENT_TYPE_ID"]; } }
                         */
                        /**
                         * * SITES
                         */
                        $sql = "select distinct " . Pelican::$config['FW_PREFIXE_TABLE'] . "site.SITE_ID, SITE_LABEL, " . Pelican::$config['FW_PREFIXE_TABLE'] . "profile.PROFILE_ID
                    from " . Pelican::$config['FW_PREFIXE_TABLE'] . "site,
                    " . Pelican::$config['FW_PREFIXE_TABLE'] . "profile,
                    " . Pelican::$config['FW_PREFIXE_TABLE'] . "user_profile
                    where
                    USER_LOGIN='" . $_SESSION[APP]["user"]["id"] . "'
                    and " . Pelican::$config['FW_PREFIXE_TABLE'] . "site.SITE_ID=" . Pelican::$config['FW_PREFIXE_TABLE'] . "profile.SITE_ID
                    and " . Pelican::$config['FW_PREFIXE_TABLE'] . "user_profile.PROFILE_ID=" . Pelican::$config['FW_PREFIXE_TABLE'] . "profile.PROFILE_ID
                    order by " . Pelican::$config['FW_PREFIXE_TABLE'] . "site.SITE_ID";
                        $site = $oConnection->getTab($sql);
                        
                        $_SESSION[APP]["sites"] = $site;
                        if (count($site) == 1) {
                            $_SESSION[APP]["SITE_ID_SELECTED"] = $site[0]['SITE_ID'];
                            $_SESSION[APP]['SITE_ID'] = $site[0]['SITE_ID'];
                            $_SESSION[APP]["SITE_LABEL"] = $site[0]["SITE_LABEL"];
                            $_SESSION[APP]["PROFILE_ID"] = $site[0]["PROFILE_ID"];
                            // reinitilisation des infos du site en cours de modification
                            Pelican_Application::getSiteInfos();
                            Pelican_Application::setLang();
                        }
                        
                        /**
                         * Si l'authentification a été faite
                         * Redirection vers l'application
                         */
                        if ($_SESSION[APP]["sites"]) {
                            if (valueExists($_SESSION[APP], 'SITE_ID')) {
                                $redirect = Pelican::$config["PAGE_INDEX_PATH"];
                            } else {
                                $log = true;
                                $choose = true;
                            }
                        } else {
                            $site = false;
                        }
                    }
                } else {
                    /**
                     * * Sinon on retourne à l'identification
                     */
                    $error = t('WRONG_LOGIN');
                }
			 } else {
                    $error = t('BLOCKED_LOGIN');
             }
            } elseif (isset($_POST['SITE_ID'])) {
                $aSite = explode("_", $_POST['SITE_ID']);
                $_SESSION[APP]["PROFILE_ID"] = $aSite[0];
                $_SESSION[APP]['SITE_ID'] = $aSite[1];
                // reinitilisation des infos du site en cours de modification
                Pelican_Application::getSiteInfos();
                Pelican_Application::setLang();
                $redirect = Pelican::$config["PAGE_INDEX_PATH"];
            }
        }
        
        if (isset($_POST['SITE_ID'])) {
            $aSiteId = explode('_', $_POST['SITE_ID']);
            $_SESSION[APP]["SITE_ID_SELECTED"] = $aSiteId[1];
        }
        if (! empty($redirect)) {
            $this->redirect($redirect, 200);
            exit();
        }
        
        /**
         * * Cas d'un retour à la suite de trop nombreuses tentatives
         */
        if (isset($_GET["a"])) {
            if ($_GET["a"] == "false") {
                $error = t('MAX_TRIES');
            }
        }
        $this->assign('attempt', (isset($_SESSION[APP]["login_attempt"]) ? $_SESSION[APP]["login_attempt"] : ''));
        
        /**
         * * Si le champ login n'est pas vide, focus sur le champ password, sinon login
         */
        if (! empty($choose) && ! empty($_SESSION[APP]["htmlComboSite"])) {
            $focusedInput = 'SITE_ID';
        } else {
            if (! empty($_SESSION[APP]['LANGUE_CODE'])) {
                $saveLang = $_SESSION[APP]['LANG'];
                $saveLangCode = $_SESSION[APP]['LANGUE_CODE'];
                $saveLangId = $_SESSION[APP]['LANGUE_ID'];
            }
            $_SESSION[APP] = array();
            if (! empty($saveLangCode)) {
                $_SESSION[APP]['LANG'] = $saveLang;
                $_SESSION[APP]['LANGUE_CODE'] = $saveLangCode;
                $_SESSION[APP]['LANGUE_ID'] = $saveLangId;
            }
            
            $focusedInput = "login";
        }
        self::_indexCommon();
        
        $head->endJs(Pelican::$config['LIB_PATH'] . "/public/js/global.js");
        /**
         * a faire a la fin pour etre sur d'avoir tous les js et css
         */
        $this->assign('logo', Pelican_Html::img(array(
            'src' => "/css/images/cppv2.jpg",
            'alt' => "",
            'border' => "0"
        )));
		
        $this->assign('load', "onload=\"if (document.location.href!=parent.location.href) top.location.href='" . Pelican::$config["INDEX_PATH"] . "/_/Index/login';document.fLogin." . $focusedInput . ".focus();\"");
        
        /**
         * * Message d'accueil ou d'erreur
         */
        $msg = '';
        if (empty($choose)) {
            if (empty($error)) {
                $msg = (APP_MSG);
            } else {
                $msg = (Pelican_Html::span(array(
                    'class' => "erreur"
                ), $error));
            }
        }
        $this->assign('msg', $msg);
        
        if (! empty($choose) && valueExists($_SESSION[APP], "sites")) {
            // Liste des sites
            $this->assign('aSites', $_SESSION[APP]["sites"]);
            $this->assign('combo', $_SESSION[APP]["htmlComboSite"], false);
        } else {
            $this->assign('combo', '');
        }
        
        $this->assign('login', (isset($_POST["login"]) ? htmlentities($_POST["login"]) : ''));
        $this->assign('doctype', $head->getDocType());
        $this->assign('header', $head->getHeader(false), false);
        $this->assign('footer', $head->getFooter(), false);
        $this->fetch();
    }

    /**
     * __DESC__
     */
    protected function _indexCommon ()
    {
        $head = $this->getView()->getHead();
        
        /**
         * init de la session
         */
        if (empty($_GET["view"]) && ! empty($_REQUEST["view"])) {
            $_GET["view"] = $_REQUEST["view"];
        }
        if (empty($_GET["media"])) {
            $_GET["media"] = "image";
        }
        if (! empty($_COOKIE["screen_width"])) {
            $_SESSION["screen_width"] = $_COOKIE["screen_width"];
        }
        if (! empty($_COOKIE["screen_height"])) {
            $_SESSION["screen_height"] = $_COOKIE["screen_height"];
        }
        
        // Lors d'un changement de site, on vérifie que le user y a bien droit (doit être présent dans la variable de session créée à l'authentification
        if (! empty($_POST['SITE_ID'])) {
            if (! is_array($_POST['SITE_ID'])) {
                if (isset($_SESSION[APP]["navigation"]["site"])) {
                    if (isset($_SESSION[APP]["navigation"]["site"][$_POST['SITE_ID']])) {
                        $aSite = explode("_", $_POST['SITE_ID']);
                        $_SESSION[APP]["PROFILE_ID"] = $aSite[0];
                        $_SESSION[APP]['SITE_ID'] = $aSite[1];
                        Pelican_Application::getSiteInfos();
                        Pelican_Application::setLang();
                    }
                }
            }
        }
        
        if (isset($_SESSION[APP]["PROFILE_ID"]) && isset($_SESSION[APP]['SITE_ID'])) {
            $_SESSION[APP]["admin"] = $_SESSION[APP]["navigation"]["site"][$_SESSION[APP]["PROFILE_ID"] . "_" . $_SESSION[APP]['SITE_ID']]["admin"];
        }
        if (! isset($_SESSION[APP]["admin"])) {
            if (valueExists($_SESSION[APP], "PROFILE_ID")) {
                $_SESSION[APP]["admin"] = $_SESSION[APP]["navigation"]["site"][$_SESSION[APP]["PROFILE_ID"] . "_" . $_SESSION[APP]['SITE_ID']]["admin"];
            } else {
                $_SESSION[APP]["admin"] = 0;
            }
        }
        
        if (isset(Pelican::$config['SITE']['INFOS']['SITE_TITLE'])) {
            $head->setTitle(Pelican_Text::htmlentities(Pelican::$config['SITE']['INFOS']['SITE_TITLE']));
        } else {
            $head->setTitle(Pelican_Text::htmlentities(APP_TITLE));
        }
        $this->_setSkin();
        $head->setCss(Pelican::$config['DOCUMENT_HTTP'] . "/css/style.css");
        $head->setJs("/js/Citroen/global.js");
        $head->endJs(Pelican::$config["MEDIA_LIB_PATH"] . "/js/media_translate.js.php");
        $head->setJs(Pelican::$config["LIB_PATH"] . Pelican::$config['LIB_FORM'] . "/js/xt_mozilla_fonctions.js");
        $head->setJs(Pelican::$config["LIB_PATH"] . Pelican::$config['LIB_FORM'] . "/js/xt_popup_fonctions.js");
        $head->setJs(Pelican::$config['LIB_PATH'] . "/public/js/global.js");
        $head->endJs(Pelican::$config['LIB_PATH'] . "/public/js/hidecombo.js");
        $head->setScript("var libDir='" . Pelican::$config["LIB_PATH"] . "';");
        $head->setScript("var mediaDir='" . (valueExists($_GET, "popup_content") ? "." : Pelican::$config["MEDIA_LIB_PATH"]) . "';");
        $head->setScript("var httpMediaDir='" . Pelican::$config["MEDIA_HTTP"] . "';");
        $head->endJQuery('autocomplete');
        $head->endJQuery('ui.datepicker');
        if ($_SESSION[APP]['LANGUE_CODE'] == "fr") {
            $head->endJQuery('ui.datepicker.fr');
        } else {
            $head->endJQuery('ui.datepicker.en');
        }
        $head->setJs("/js/script.js");
        $head->endJs(Pelican::$config["MEDIA_HTTP"]."/library/External/CryptoJS/rollups/sha1.js");
        $head->endJs(Pelican::$config['MEDIA_HTTP'] . "/design/backend/js/jquery.dialogextend.1_0_1.js");
        //$head->setCss("http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/redmond/jquery-ui.css");
        $head->setCss(Pelican::$config['MEDIA_HTTP'] . "/design/backend/css/evol.colorpicker.min.css");
        $head->endJQuery('evolcolorpicker');

    }

    /**
     * Gestion des onglets
     *
     * On parcours tous les niveaux et on vérifie s'ils doivent s'afficher sous forme d'onglet
     * si ce n'est pas le cas, ils se placent dans l'onglet "OUTILS" ou "ADMIN" :
     * - qui ne s'affiche que si un module doit s'y trouver
     * - qui se met à la fin des onglets sauf pour les admins ou il se met juste avant
     * ATTENTION : seul un admin peut avoir l'onglet ADMIN
     *
     * @return un tableau avec :
     *         sites : "site"=>"S_(id)"=>"id, name"
     *         onglets : "onglet"=>"O_(id)"=>"id, name, order, TEMPLATE_ID"
     *         modules : "onglet"=>"O_(id)"=>"menu"=>"id"=>"id, parent_id, name, order, TEMPLATE_ID"
     */
    protected function _getNavigation ($values)
    {
        // tableau de description des modules par onglet
        $aNavigation = array();
        $SITE_ID = "";
        /* Création du Pelican_Html de la combo des sites par profiles */
        $asites = getComboValuesFromCache("Frontend/Site");
        $oTree = Pelican_Factory::getInstance('Hierarchy', "navigation", "id", "pid");
        $oTree->addTabNode($values);
        $oTree->setOrder("order", "ASC");
        foreach ($oTree->aNodes as $node) {
            if ($node->SITE_ID && $SITE_ID != $node->PROFILE_ID . "_" . $node->SITE_ID) {
                /**
                 * * Entrée site
                 */
                $SITE_ID = $node->PROFILE_ID . "_" . $node->SITE_ID;
                $aNavigation["site"][$SITE_ID] = array(
                    "id" => $node->SITE_ID,
                    "name" => $asites[$node->SITE_ID],
                    "profile_id" => $node->PROFILE_ID,
                    "profile_name" => $node->PROFILE_LABEL,
                    "admin" => $node->PROFILE_ADMIN
                );
            }
            // contrôle admin
            $menuok = true;
            if ($node->DIRECTORY_ADMIN && ! $node->PROFILE_ADMIN) {
                $menuok = false;
            }
            if ($menuok && $node->id) {
                /**
                 * * Définition des menus
                 */
                /**
                 * * c'est un niveau 1 => il se retrouve sous l'onglet Outils ou Administration
                 */
                if (($node->level - 1) == 1) {
                    /**
                     * * Niveau 1 : on crée l'onglet
                     */
                    $labelTrad = strtr(strtoupper(dropaccent($node->DIRECTORY_LEFT_LABEL)), " ", "_");
                    $onglet = "O_" . $node->DIRECTORY_ID;
                    $aNavigation["site"][$SITE_ID]["onglet"][$onglet] = array(
                        "id" => $node->DIRECTORY_ID,
                        "lib" => $node->lib,
                        "volet_gauche" => t($labelTrad),
                        "icon" => $node->image,
                        "order" => $node->order,
                        "TEMPLATE_ID" => $node->TEMPLATE_ID
                    );
                } else {
                    /**
                     * * Niveau > 1
                     */
                    $labelTrad = strtr(strtoupper(dropaccent($node->lib)), " ", "_");
                    
                    $aNavigation["site"][$SITE_ID]["onglet"][$onglet]["navigation"][$node->DIRECTORY_ID] = array(
                        "id" => $node->DIRECTORY_ID,
                        "pid" => $node->DIRECTORY_PARENT_ID,
                        "lib" => t($labelTrad),
                        "icon" => $node->image,
                        "order" => $node->order,
                        "TEMPLATE_ID" => $node->TEMPLATE_ID,
                        "url" => ($node->TEMPLATE_ID ? "javascript:menu('" . $node->TEMPLATE_ID . "','" . $node->TEMPLATE_COMPLEMENT . "')" : "")
                    );
                }
            }
        }
        return $aNavigation;
    }

    /**
     * Gestion des roles pour un site donné
     *
     * @access private
     */
    protected function _setRolesBySiteId ()
    {
        // Récupération du site id
        if (isset($_POST['SITE_ID']) && ! empty($_POST['SITE_ID'])) {
            $aSite_id = explode('_', $_POST['SITE_ID']);
            $site_id = $aSite_id[1];
        }         // Récupération du site id lors de la 1ere connexion mise en session suite à la redirection.
        elseif (isset($_SESSION[APP]["SITE_ID_SELECTED"])) {
            $site_id = $_SESSION[APP]["SITE_ID_SELECTED"];
        }
        // On set en sessions les roles correspondants au site sélectionné.
        if (isset($site_id) && ! empty($site_id)) {
            $oConnection = Pelican_Db::getInstance();
            $aSite_id = explode('_', $_POST['SITE_ID']);
            $sql = "select
            " . Pelican::$config['FW_PREFIXE_TABLE'] . "user_role.ROLE_ID,
            CONTENT_TYPE_ID,
            STATE_PARENT_ID,
            STATE_ID
            from
            " . Pelican::$config['FW_PREFIXE_TABLE'] . "user_role,
            " . Pelican::$config['FW_PREFIXE_TABLE'] . "state_dependencies
            where
            USER_LOGIN='" . $_SESSION[APP]["user"]["id"] . "'
            AND " . Pelican::$config['FW_PREFIXE_TABLE'] . "user_role.ROLE_ID = " . Pelican::$config['FW_PREFIXE_TABLE'] . "state_dependencies.ROLE_ID
            AND " . Pelican::$config['FW_PREFIXE_TABLE'] . "user_role.SITE_ID=" . $site_id;
            $result = $oConnection->getTab($sql);
            if ($result) {
                foreach ($result as $state) {
                    $_SESSION[APP]["state"]["id"][$state["STATE_PARENT_ID"]] = $state["STATE_ID"];
                    $_SESSION[APP]["state"][$state["STATE_PARENT_ID"]]["content_type"][$state["CONTENT_TYPE_ID"]] = $state["CONTENT_TYPE_ID"];
                    $_SESSION[APP]["content_type"][$state["CONTENT_TYPE_ID"]]["state"][$state["STATE_PARENT_ID"]][] = $state["STATE_ID"];
                    $_SESSION[APP]["content_type"]["id"][$state["CONTENT_TYPE_ID"]] = $state["CONTENT_TYPE_ID"];
                }
            }
        }
    }

    /**
     * Permet de setter en session le label du profil via l'id du profil selectionné
     *
     * @access private
     */
    private function _setProfilLabelSession ()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':PROFILE_ID'] = $_SESSION[APP]['PROFILE_ID'];
        $sql = "Select PROFILE_LABEL from #pref#_profile where PROFILE_ID= :PROFILE_ID";
        $result = $oConnection->getRow($sql, $aBind);
        $_SESSION[APP]["PROFIL_LABEL"] = $result['PROFILE_LABEL'];
    }

    /**
     * pour les check "ajax" (peuvent être synchrones) dans le backend
     */
    public function checkAction ()
    {
        if (! empty($_REQUEST["className"]) && ! empty($_REQUEST["method"])) {
            $className = $_REQUEST["className"];
            $method = $_REQUEST["method"];
            $sCheckName = $className . '/' . $method;
            if (in_array($sCheckName, Pelican::$config['BACKEND_AJAX'])) {
                $path = str_replace("_Controller", "", $_REQUEST["className"]);
                $path = str_replace("_", "/", $path);
                // Inclusion de la classe concernée par l'appel "ajax"
                require_once (Pelican::$config["CONTROLLERS_ROOT"] . "/" . $path . ".php");
                $result = call_user_func(array(
                    $className,
                    $method
                ), $_REQUEST);
                echo ($result);
            } else {
                echo 'fonction non déclarée dans Pelican::$config[\'BACKEND_AJAX\'], dans le fichier config/[APP].ini.php';
            }
        } else {
            echo 'les parametres className et method sont obligatoires';
        }
    }

    public function downloadExportAction ()
    {
        $filename = Pelican::$config['VAR_ROOT'] . "/export/" . $_GET['file'] . ".xml";
        if (file_exists($filename)) {
            $handle = fopen($filename, "rb");
            $contents = '';
            while (! feof($handle)) {
                $contents .= fread($handle, 8192);
            }
            fclose($handle);
            if ($contents) {
                header("Content-type: application/force-download");
                header("Content-Disposition: attachment; filename=\"export.xml\"");
                echo $contents;
            }
        }
    }
}