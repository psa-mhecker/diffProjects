<?php
//use Citroen\Event;
//use Symfony\Component\EventDispatcher\EventDispatcher;
use Citroen\Perso\Score\ScoreManager;
use Citroen\Perso\Score\IndicateurManager;
use Citroen\GTM;
use Citroen\Html\Util;
use Citroen\Configurateur;
use Citroen\CarStore;

pelican_import('Profiler');
require_once (pelican_path('Layout'));
require_once (pelican_path('Translate'));
require_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Showroom.php');


// temp
Pelican::$config ['ARTISTEER_VERSION'] = 'artisteer/2.4';

class Index_Controller extends Pelican_Controller_Front {

    protected $_layout;
    protected $_dispatcher;

    public function __construct(Pelican_Request $request) {
        parent::__construct($request);
    }

    /**
     * @return the $_layout
     */
    public function getLayout() {
        if (empty($this->_layout)) {
            $this->_layout = Pelican_Factory::getInstance('Layout');
            //$this->_layout = new Pelican_Layout ();
        }
        return $this->_layout;
    }

    /**
     * @param field_type $_layout
     */
    public function setLayout($_layout) {
        $this->_layout = $_layout;
    }
    
    private function contextualiseExternalUrl($url,$context,$replacement,$redundant=false){
        $aRedirectUrlParts = parse_url($url);
        if($redundant){
            $secondMbdParamPosition = strpos($aRedirectUrlParts['fragment'],$redundant);
            $aRedirectUrlParts['fragment']= substr_replace(
                    $aRedirectUrlParts['fragment'],
                    "",
                    $secondMbdParamPosition,
                    strlen($aRedirectUrlParts['fragment'])
                    );
        }
        $url=http_build_url("",$aRedirectUrlParts);
        $url = str_replace($context, $replacement, $url);
        return $url;    
    }

    public function previewAction() {
        if (!isset($_SESSION["APP"]["PREVIEW"]["LOGGED"])) {
            //head
            $head = $this->getView()
                    ->getHead();
            $head->setCss("/css/preview-connect.css");

            $head->setJs(Pelican::$config["DESIGN_HTTP"] . "/js/jquery-1.8.0.min.js");
            $head->setJs(Pelican::$config["DESIGN_HTTP"] . "/js/jquery.tools.min.meta.js");
            $head->setJs(Pelican::$config["DESIGN_HTTP"] . "/js/jquery.jcarousel.min.js");
            $head->setJs(Pelican::$config["DESIGN_HTTP"] . "/js/jquery.functions.js");
            $head->setTitle(t("Authentification"));
            $hiddenSchedule = '';
            if($this->getParam('schedule')){
                $hiddenSchedule = Pelican_Html::hidden('schedule', $this->getParam('schedule'));            
            }
            //login
            $inputLogin = Pelican_Html::input(array(
                        'name' => "preview_login", 'id' => "preview_login", 'value' => $this->getParam('login')
            ));
            $labelLogin = Pelican_Html::div(array(
                        'name' => "label_mdp", 'id' => "label_mdp", 'class' => "label_preview"
                            ), t("PREVIEW_LOGIN"));
            $inputLoginDiv = Pelican_Html::div(array(
                        'class' => "loginMdpDiv",
                            ), $labelLogin . $inputLogin);


            //mot de passe
            $inputMdp = Pelican_Html::input(array(
                        'name' => "preview_mdp", 'id' => "preview_mdp", 'type' => "password"
            ));
            $labelMdp = Pelican_Html::div(array(
                        'name' => "label_mdp", 'id' => "label_mdp", 'class' => "label_preview"
                            ), t("PREVIEW_MDP"));
            $inputMdpDiv = Pelican_Html::div(array(
                        'class' => "loginMdpDiv",
                            ), $labelMdp . $inputMdp);

            $title = Pelican_Html::div(array(
                        'id' => "title_preview"
                            //), t("PREVIEW_TITLE"));
                            ), t("AUTHENTIFICATION"));

            $inputButtonPreview = Pelican_Html::input(array(
                        'type' => "submit", 'id' => "btnPreviewValid"
            ));

            $inputDivPreview = Pelican_Html::div(array(
                        'id' => "previewLoginBox",
                            ), $title . $inputLoginDiv . $inputMdpDiv . $inputButtonPreview . $hiddenSchedule);


            $formPreview = Pelican_Html::form(array(
                        'id' => "previewForm",
                        'method' => "previewForm",
                        'action' => "/_/Index/connectPreview",
                            ), $inputDivPreview);

            $this->setResponse($head->getHeader(false) . $formPreview);

            $pid = $this->getParam('pid');
            if (!empty($pid)) {
                $_SESSION["APP"]["PREVIEW"]["PID"] = $this->getParam('pid');
            }
        } else { 
            $_GET["preview"] = 1;
            $cachetimeout = - 1;
            $this->_forward('index');
        }
    }

    public function indexAction() {

        $aParams = $this->getParams();

        // CPW-4042
        Citroen\Url::registeSupplementsToSession($aParams);
        
        //RÃ©cupÃ©ration des informations du site
        $aSite = Pelican_Cache::fetch("Frontend/Site", array(
                    $_SESSION[APP]['SITE_ID']
        ));

        if ($aSite['SITE_MAINTENANCE'] == 1 && $_SERVER['REQUEST_URI'] != $aSite['SITE_MAINTENANCE_URL']) {
            $this->redirect($aSite['SITE_MAINTENANCE_URL']);
        }

      if($_GET['Forfait'] && strpos($_SERVER['QUERY_STRING'], 'Forfait')!==false){
        	$aInfosContent = Pelican_Cache::fetch("Frontend/Content/ContentInfo", array(
	                $_GET['Forfait'],
	                $_SESSION[APP]['SITE_ID'],
	                $_SESSION[APP]['LANGUE_ID'],
	                Pelican::getPreviewVersion()
	                ));
	           
	       	 header("Status: 301 Moved Permanently", false, 301);	
	         header('location: http://'.$_SERVER['HTTP_HOST'].$aInfosContent['CONTENT_CLEAR_URL']);
       
        }
		
		
		

        // profiling
        Pelican_Profiler::start('header', 'page');

        if (!isset($_SESSION['HTTP_USER_AGENT_FORCE'])) {
            unset($_SESSION['HTTP_USER_AGENT']);
        }

        $this->getView()->plugins_dir[] = Pelican::$config['DOCUMENT_ROOT'] . '/views/plugins';
        //head
        $head = $this->getView()
                ->getHead();
        $head->setLink("icon", "/favicon.ico", "", "image/x-icon");
        $head->setDocType('HTML 5');
        // CPW-3893 : Intégration de balise hreflang
        $result_href = Pelican_Cache::fetch("Request/Hreflang",array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            $aParams['pid']
        ));
        $head->setAddon($result_href['HREFLANG_TEXT']);    
        //chargement jquery pour le choix du device
        if (isset($_SESSION["APP"]["PREVIEW"]["LOGGED"]) && strripos($_SERVER["REQUEST_URI"], "preview")) {
            $head->setCss("/css/preview-connect.css");
            $classSelect = '';
            if (is_array(Pelican:: $config['USER_AGENT_LIST'])) {
                $option = "<option value=''>---" . t("PREVIEW_CHOICE") . "---</option>";
                foreach (Pelican:: $config['USER_AGENT_LIST'] as $lib => $userAgent) {
                    $selected = false;
                    //if($userAgent == $this->getParam("useragent")){
                    if ($userAgent == $_SESSION['HTTP_USER_AGENT']) {
                        $selected = "selected=selected";
                        if ($lib != 'Desktop')
                            $classSelect = "mob";
                    }else {
                        $classSelect = "mob";
                    }
                    $option .= "<option value='" . $userAgent . "' " . $selected . ">" . $lib . "</option>";
                }
            }
            $pid = $this->getParam('pid');
            $pidHidden = Pelican_Html::input(array(
                        'id' => "pid_preview_hidden",
                        'type' => "hidden",
                        'value' => $pid
            ));
            $isSchedule = Pelican_Html::input(array(
                        'id' => "is_schedule",
                        'type' => "hidden",
                        'value' => $this->getParam('schedule')
            ));
            
            $listeDevice = Pelican_Html::select(array(
                        'id' => "select_device",
                        'class' => $classSelect
                            ), $option);
            $listeDeviceDiv = Pelican_Html::div(array(
                            ), $listeDevice);
            $previewDeviceChoice = Pelican_Html::div(array(
                        'id' => "previewUserAgent"
                            ), $isSchedule . $pidHidden . $listeDeviceDiv);
        }
        if (!isset($_SESSION["APP"]["PREVIEW"]["LOGGED"]) && strripos($_SERVER["REQUEST_URI"], "preview")) {
            $this->redirect('/_/Index/preview');
        }

        //layout
        $layout = $this->getLayout();

        //pack

        //if(strtoupper(Pelican::$config['TYPE_ENVIRONNEMENT']) != 'DEV') {
           //$head->activatePack();
        //}
        // Site initialisation
        $layout->getInfos();
        //$return = Pelican_Cache::fetch("Frontend/Template_Page", array($layout->aPage["TEMPLATE_PAGE_ID"], $this->_type));

        if ($_SERVER['REQUEST_URI'] == '/robots.txt') {
            $this->_forward('robots');
            die;
        }
        
        // Remplacement des variables contextuelles (CPW-2688 + CPW-2693)
        $aLcdv = Pelican_Cache::fetch("Frontend/Citroen/Perso/Lcdv6ByProduct", array($_SESSION[APP]['SITE_ID']));
        // Récupération des infos sur le véhicule préféré
        $sLcdvPref = '';
        $sLcdvPrefName = '';
        if ($_SESSION[APP]['FLAGS_USER']['preferred_product'] != '') {
            $sLcdvPref = $aLcdv[$_SESSION[APP]['FLAGS_USER']['preferred_product']];
            $aLcdvPref = Pelican_Cache::fetch("Frontend/Citroen/VehiculeByLCDVGamme", array(
                $sLcdvPref,
                null,
                $_SESSION[APP]['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID']
            ));
            
            $sLcdvPrefName = $aLcdvPref['VEHICULE_LABEL'];
            if(isset($aLcdvPref['CODE_REGROUPEMENT_SILHOUETTE']) && !empty($aLcdvPref['CODE_REGROUPEMENT_SILHOUETTE'])){
	            $aCRS = explode(':',$aLcdvPref['CODE_REGROUPEMENT_SILHOUETTE']);
	            $sMbdPref = sprintf('%s%s',$aLcdvPref['LCDV4'],$aCRS[0]);
            }
            
        }
        

        
        // Récupération des infos sur le véhicule courant
        $sLcdvCurrent = '';
        $sLcdvCurrentName = '';
        if ($_SESSION[APP]['FLAGS_USER']['current_product'] != '') {
            $sLcdvCurrent = $aLcdv[$_SESSION[APP]['FLAGS_USER']['current_product']];
            $aCurrentVehicule = Pelican_Cache::fetch("Frontend/Citroen/VehiculeByLCDVGamme", array(
                $sLcdvCurrent,
                null,
                $_SESSION[APP]['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID']
            ));
            $sLcdvCurrentName = $aLcdvPref['VEHICULE_LABEL'];
            if(isset($aCurrentVehicule['CODE_REGROUPEMENT_SILHOUETTE']) && !empty($aCurrentVehicule['CODE_REGROUPEMENT_SILHOUETTE'])){
	            $aCRS = explode(':',$aCurrentVehicule['CODE_REGROUPEMENT_SILHOUETTE']);
	            $sMbdCurrent = sprintf('%s%s',$aCurrentVehicule['LCDV4'],$aCRS[0]);
            }
        }
        else
        {
            if(isset($_GET['lcdv']))
            {
                $sLcdvCurrent = $_GET['lcdv'];
                
                $_SESSION[APP]['FLAGS_USER']['current_product'] = $sLcdvCurrent;
                
                $aCurrentVehicule = Pelican_Cache::fetch("Frontend/Citroen/VehiculeByLCDVGamme", array(
                    $sLcdvCurrent,
                    null,
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID']
                ));
                
                if(is_array($aCurrentVehicule) && !empty($aCurrentVehicule)){
                   ($aCurrentVehicule['VEHICULE_LCDV6_MANUAL']!=null)?$sLcdvCurrent = $aCurrentVehicule['VEHICULE_LCDV6_MANUAL']:$aCurrentVehicule['VEHICULE_LCDV6_CONFIG']; 
                }
                
            $sLcdvCurrentName = $aCurrentVehicule['VEHICULE_LABEL'];
                if(isset($aLcdvPref['CODE_REGROUPEMENT_SILHOUETTE']) && !empty($aCurrentVehicule['CODE_REGROUPEMENT_SILHOUETTE'])){
                    $aCRS = explode(':',$aCurrentVehicule['CODE_REGROUPEMENT_SILHOUETTE']);
                    $sMbdCurrent = sprintf('%s%s',$aCurrentVehicule['LCDV4'],$aCRS[0]);
                }
            }
        }
        $aConfiguration = Pelican_Cache::fetch("Frontend/Citroen/Configuration", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            ));
        if ($layout->aPage ['PAGE_URL_EXTERNE'] != '') {
            $redirection = false;
            
            if(isset($_GET['CarNum'])){
                if( isset($_SESSION['CarStoreUrl']) && isset($_SESSION['CarStoreUrl'][$_GET['CarNum']])){
                    $layout->aPage ['PAGE_URL_EXTERNE'] = $_SESSION['CarStoreUrl'][$_GET['CarNum']];

                }else{
                    $layout->aPage ['PAGE_URL_EXTERNE'] = $configuration['URL_CARSTORE'];
                }
                 
                $redirection = true;
             
            }
              

            $url_cfg_tag = array(
                '##URL_CONFIGURATEUR##' ,
                '##URL_CONFIGURATEUR_PRO##' ,
               );
                
            //la page externe est la page rebond pour le configurateur PRO?
            if( in_array($layout->aPage ['PAGE_URL_EXTERNE'],$url_cfg_tag)){
                $tags= array( 
                        'GAMME' => ($layout->aPage ['PAGE_URL_EXTERNE'] == '##URL_CONFIGURATEUR_PRO##') ?Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VU']:Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VP'],
                        'LCDV6' => $sLcdvCurrent,
                        'GRADES' => $_GET['Grades'],
                        'VERSION' =>$_GET['Version'],
                        );

                $layout->aPage ['PAGE_URL_EXTERNE'] = Configurateur::getConfigurateurUrl($tags,$aConfiguration);
                
                $redirection = true;
            }else{
                $url_carstore_tag = array(
                    '##URL_CARSTORE##' ,
                    '##URL_CARSTORE_PRO##' ,
                );
                 //la page externe est la page rebond pour le configurateur PRO?
                if( in_array($layout->aPage ['PAGE_URL_EXTERNE'],$url_carstore_tag)){
                    $tags= array( 
                        'LCDV6' => (!empty($_GET['lcdv6']))?$_GET['lcdv6']:$_GET['lcdv'],
                       'CULTURE'  => $_GET['culture'],
                       'LATITUDE' => $_GET['latitude'],
                       'LONGITUDE'=> $_GET['longitude'],
                       'RADIUS' => $_GET['radius']
                        );
                   
                    $layout->aPage ['PAGE_URL_EXTERNE'] = CarStore::getCarStoreUrl($tags,$aConfiguration);

                    $redirection = true;
                }
                
            }
            
            if($redirection === true){
                header('location: '.Citroen\Url::parse($layout->aPage["PAGE_URL_EXTERNE"]));
                exit;
            }
                
                
            
            $bKeepUrlQuery=false;
            if ($_SERVER['QUERY_STRING'] != '') {
                if (strpos($layout->aPage["PAGE_URL_EXTERNE"], '?') !== false) {
                    $layout->aPage["PAGE_URL_EXTERNE"] .= '&' . $_SERVER['QUERY_STRING'];
                } else {
                    $layout->aPage["PAGE_URL_EXTERNE"] .= '?' . $_SERVER['QUERY_STRING'];
                }
            }
			
			if(empty($sLcdvCurrent)){
				$sLcdvCurrent=$_GET['lcdv'];
			}

            $tags = array(
                    '##LCDV_CURRENT##' => $sLcdvCurrent,
                    '##LCDV_CURRENT_NAME##'=> $sLcdvCurrentName,
                    '##LCDV_PREF_NAME##'=> $sLcdvPrefName,
                    '##LCDV_PREF##'=> $sLcdvPref,
                    );



           $layout->aPage["PAGE_URL_EXTERNE"] = \Citroen\Html\Util::replaceTagsInUrl($layout->aPage["PAGE_URL_EXTERNE"],$tags, false);

         
            if (strpos($layout->aPage["PAGE_URL_EXTERNE"], '##MBD_PREF##') !== false && isset($sMbdPref)) {
                $bKeepUrlQuery=true;
                $layout->aPage["PAGE_URL_EXTERNE"] = $this->contextualiseExternalUrl($layout->aPage["PAGE_URL_EXTERNE"],'##MBD_PREF##',$sMbdPref,"&mbd=");
            }
            
            if (strpos($layout->aPage["PAGE_URL_EXTERNE"], '##MBD_CURRENT##') !== false && isset($sMbdCurrent)) {
               $bKeepUrlQuery=true;
                $layout->aPage["PAGE_URL_EXTERNE"] = $this->contextualiseExternalUrl($layout->aPage["PAGE_URL_EXTERNE"],'##MBD_CURRENT##',$sMbdCurrent,"&mbd=");
                $layout->aPage["PAGE_URL_EXTERNE"] = str_replace('##MBD_CURRENT##', $sMbdCurrent, $layout->aPage["PAGE_URL_EXTERNE"]);
            }
            //supprime le parametre lcdv pour des besoins specifiques sur cppv2
            // avant de faire la redirection            
            $aRedirectUrlParts = parse_url($layout->aPage["PAGE_URL_EXTERNE"]);
            if($bKeepUrlQuery){
                if(isset($aRedirectUrlParts['query'])){
                    parse_str($aRedirectUrlParts['query'], $aQuery);
                    if(is_array($aQuery) && isset($aQuery['lcdv'])){
                        unset($aQuery['lcdv']);
                    }
                    $aRedirectUrlParts['query'] =   http_build_query($aQuery);
                    if(isset($aRedirectUrlParts['query']) && empty($aRedirectUrlParts['query'])){
                        unset($aRedirectUrlParts['query']);                        
                    }
                }
            }
            $newRedirectUrlWithoutParameters = http_build_url("",$aRedirectUrlParts);
			header("location: " .Citroen\Url::parse($newRedirectUrlWithoutParameters), false, 301);
			exit;
        }
		

        
        // Récupération de la configuration (global)
      //  $aConfiguration = Pelican_Cache::fetch("Frontend/Citroen/Configuration", array($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], "CURRENT"));
        
        // Gestion de l'affichage de la bulle de scroll
        // Si l'affichage de la bulle de scoll est activé et qu'elle n'a jamais été affichée à l'internaute,
        // on l'affiche et on créé un cookie pour ne plus l'afficher ensuite.
        //exclusion du gabarit GABARIT_BLANC_NO_FOOTER/HEADER
        if(Pelican::$config['TEMPLATE_PAGE']['GABARIT_BLANC_NO_FOOTER_HEADER'] != $layout->aPage['TEMPLATE_PAGE_ID']
                && Pelican::$config['TEMPLATE_PAGE']['POINT_DE_VENTE_IFRAME'] != $layout->aPage['TEMPLATE_PAGE_ID']){
            if($aConfiguration['SHOW_SCROLL_TOP'] == 2){
                $this->assign('showScrollIncite', true);
            }elseif ($aConfiguration['SHOW_SCROLL_TOP'] && !isset($_COOKIE['scrollincite_shown'])) {
                Backoffice_Cookie_Helper::setCookie('scrollincite_shown' , true , time() + (3600*24*365));
                $this->assign('showScrollIncite', true);
            }
        }

        // Google Tag Manager - initialisation variables générales
        GTM::$dataLayer['brand']          = Pelican::$config['GTM']['brand'];
        GTM::$dataLayer['virtualPageURL'] = $_SERVER['REQUEST_URI'];
        GTM::$dataLayer['pageName']       = $layout->getPageTitle();
        GTM::$dataLayer['language']       = strtolower($_SESSION[APP]['LANGUE_CODE']);
        GTM::$dataLayer['country']        = strtolower($_SESSION[APP]['CODE_PAYS']);
        GTM::$dataLayer['siteTypeLevel1'] = Pelican::$config['GTM']['siteTypeLevel1'];
        GTM::$dataLayer['siteTypeLevel2'] = '';
        GTM::$dataLayer['scoringVisit']   = GTM::serializeScore();
        GTM::$dataLayer['profiles']       = GTM::serializeProfile();
        GTM::$dataLayer['vehicleModelBodystyle']      = '';
        GTM::$dataLayer['vehicleModelBodystyleLabel'] = '';
        GTM::$dataLayer['vehicleFinition']            = '';
        GTM::$dataLayer['vehicleFinitionLabel']       = '';
        GTM::$dataLayer['vehicleMotor']               = '';
        GTM::$dataLayer['vehicleMotorLabel']          = '';
        GTM::$dataLayer['edealerName']                = '';
        GTM::$dataLayer['edealerSiteGeo']             = '';
        GTM::$dataLayer['edealerID']                  = '';
        GTM::$dataLayer['edealerCity']                = '';
        GTM::$dataLayer['edealerAddress']             = '';
        GTM::$dataLayer['edealerPostalCode']          = '';
        GTM::$dataLayer['edealerRegion']              = '';
        GTM::$dataLayer['edealerCountry']             = '';
        GTM::$dataLayer['internalSearchKeyword']      = '';
        GTM::$dataLayer['internalSearchType']         = '';
        GTM::$dataLayer['customDimension1']           = "No Perso";
		
		

		$bShowOutils = false;
		$biFrameDs = false;
		if(intval($_GET['pid'])>0){
			  $aPageMasterN1 = Pelican_Cache::fetch("Frontend/Page", array(
						$_GET['pid'],
						$_SESSION[APP]['SITE_ID'],
						$_SESSION[APP]['LANGUE_ID']
				));
				
				$aConfigurationOutils = Pelican_Cache::fetch("Frontend/Page/ZoneTemplateId", array(
					$_GET['pid'],
					Pelican::$config['ZONE_TEMPLATE_ID']['ACCEUIL_OUTILS'],
					'CURRENT',
					$_SESSION[APP]['LANGUE_ID']
				));	
				
				
				$aConfigurationShowOutils = Pelican_Cache::fetch("Frontend/Page/ZoneTemplateId", array(
					$_GET['pid'],
					Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_OUTILS'],
					'CURRENT',
					$_SESSION[APP]['LANGUE_ID']
				));
				
				
				if($aConfigurationOutils['ZONE_WEB']==1 || $aConfigurationShowOutils['ZONE_WEB']==1){
					$bShowOutils = true;
				}
				
				
		
			if($aPageMasterN1['TEMPLATE_PAGE_ID']==Pelican::$config['TEMPLATE_PAGE']['MASTER_PAGE_VEHICULES_N1']){
			
				 $aPageChild = Pelican_Cache::fetch("Frontend/Page/Childall", array(
						$aPageMasterN1['PAGE_ID'],
						$_SESSION[APP]['SITE_ID'],
						$_SESSION[APP]['LANGUE_ID'],
						'CURRENT',
						1
				));
				if(is_array($aPageChild) && sizeof($aPageChild) ==1){
					header("Status: 301 Moved Permanently", false, 301);	
					header('location: http://'.$_SERVER['HTTP_HOST'].$aPageChild[0][0]['URL']);
					exit;
				}
			}
		}
		
		
		$pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    Pelican::getPreviewVersion(),
                    Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
            ));

		// Zone Configuration de la page globale
		$aConfigurationPageGlobal = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
				$pageGlobal['PAGE_ID'],
				Pelican::$config['ZONE_TEMPLATE_ID']['OUTILS'],
				$pageGlobal['PAGE_VERSION'],
				$_SESSION[APP]['LANGUE_ID']
		));
		
			// cache qui remonte les outils mobile ou web
			$aOutilGeneral=array();
			
			if($bShowOutils == false){
			$aOutils = Pelican_Cache::fetch("Frontend/Citroen/VehiculeOutil", array(
						$_SESSION[APP]['SITE_ID'],
						$_SESSION[APP]['LANGUE_ID'],
						($this->isMobile()) ? $aConfigurationPageGlobal['ZONE_TOOL2'] : $aConfigurationPageGlobal['ZONE_TOOL'],
						($this->isMobile()) ? "MOBILE" : "WEB"
			));
			
				 if (is_array($aOutils) && !empty($aOutils)) {
					
						
						foreach ($aOutils as $key => $OneOutil){	
							$OneOutil['CTA_GENERAL'] = 1;	
							$aOutilCta['CTA'] = $OneOutil;
							$aOutilCta['CTA']['COLOR'] = 'blue';
							$aOutilCta['CTA']['ADD_CSS'] = 'activeRoll';
							$aOutilGeneral[$key] = Pelican_Request::call('_/Layout_Citroen_CTA/',$aOutilCta);
						}
									
				 }
			 }
			 if($layout->aPage['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['POINT_DE_VENTE_IFRAME']){
				 $biFrameDs = true;
			}
			
			 if(!$this->isMobile()){
				$this->assign('outilszoneweb',$bShowOutils);
				$this->assign('biFrameDs',$biFrameDs);
				$this->assign('sTemplateOutils',Pelican::$config["TEMPLATE_OUTILS_WEB"]);
			 }else{
				$this->assign('outilszonemobile',$aConfigurationOutils['ZONE_MOBILE']);
				$this->assign('sTemplateOutilsMobile',Pelican::$config["TEMPLATE_OUTILS_MOBILE"]);
			}
			
			$this->assign('aOutilGeneral', $aOutilGeneral);
			$this->assign('NbOutilGeneral', count($aOutilGeneral));
	
		$bShowJs = false;
        if(in_array($layout->aPage['TEMPLATE_PAGE_ID'], array(Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL'], Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_INTERNE']))){
            GTM::$dataLayer['siteTypeLevel2'] = 'Showroom';
			if(!empty($layout->aPage['PAGE_PRIMARY_COLOR']) && !empty($layout->aPage['PAGE_PRIMARY_COLOR'])){
				$aColor['PRIMARY_COLOR'] = $layout->aPage['PAGE_PRIMARY_COLOR'];
                $aColor['SECOND_COLOR']  = $layout->aPage['PAGE_SECOND_COLOR'];
			}else{
					$aPageShowroomColor = Frontoffice_Showroom_Helper::getShowroomColor($layout->aPage['PAGE_ID'],$_SESSION[APP]['LANGUE_ID'],Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']);
					if(!empty($aPageShowroomColor['PAGE_PRIMARY_COLOR']) &&  !empty($aPageShowroomColor['PAGE_SECOND_COLOR'])){
                        $aColor['PRIMARY_COLOR'] = $aPageShowroomColor['PAGE_PRIMARY_COLOR'];
                        $aColor['SECOND_COLOR']  = $aPageShowroomColor['PAGE_SECOND_COLOR'];
                    }
			}
			
			$bShowJs = true;
			$this->assign('aShowroom', $aColor);
			
        }
		
        
        // Variables de profile de perso
        $profilesLabel = GTM::getProfilesLabel();
        GTM::$dataLayer['customDimension20'] = isset($profilesLabel[0]) ? $profilesLabel[0] : '';
        GTM::$dataLayer['customDimension21'] = isset($profilesLabel[1]) ? $profilesLabel[1] : '';
        GTM::$dataLayer['customDimension22'] = isset($profilesLabel[2]) ? $profilesLabel[2] : '';
        GTM::$dataLayer['customDimension23'] = isset($profilesLabel[3]) ? $profilesLabel[3] : '';
        GTM::$dataLayer['customDimension24'] = isset($profilesLabel[4]) ? $profilesLabel[4] : '';
        GTM::$dataLayer['customDimension25'] = isset($profilesLabel[5]) ? $profilesLabel[5] : '';
        GTM::$dataLayer['customDimension26'] = isset($profilesLabel[6]) ? $profilesLabel[6] : '';
        unset($profilesLabel);
        
        // Variable sur les indicateurs de perso
        $vehiculeLabel = GTM::getPersoIndicVehiculeLabel();
        $cd31 = GTM::serializeScore('label');
        GTM::$dataLayer['customDimension27'] = isset($vehiculeLabel['preferred_product']) ? $vehiculeLabel['preferred_product'] : '';
        GTM::$dataLayer['customDimension28'] = isset($vehiculeLabel['product_best_score']) ? $vehiculeLabel['product_best_score'] : '';
        GTM::$dataLayer['customDimension29'] = isset($vehiculeLabel['recent_product']) ? $vehiculeLabel['recent_product'] : '';
        GTM::$dataLayer['customDimension30'] = isset($vehiculeLabel['product_owned']) ? $vehiculeLabel['product_owned'] : '';
        GTM::$dataLayer['customDimension31'] = !empty($cd31) ? $cd31 : '';
        unset($vehiculeLabel);
        unset($cd31);
        
        if (!$layout->isValid() || strcmp($layout->aPage ['PAGE_TITLE'], "") == 0) {
            // Génération & transmission du tag HTML Google Tag Manager au template
            $this->assign('gtmTag', Frontoffice_Analytics_Helper::getGtmTag(), false);
            
            $this->sendError(404, '');
        }

        //---------> Build Page
        // metaTags
        $layout->getMetaTag();

        // skins
        //$head->setSkin ( 'artisteer', (! empty ( $_GET ['skin'] ) ? $_GET ['skin'] : ''), Pelican::$config ['ARTISTEER_VERSION'] );
        //$this->assign ( 'skin', $head->skins );

        $head->setTitle($layout->getPageTitle());
        //$head->getJqueryIni ('jquery_old');
        Pelican_Profiler::stop('header', 'page');

        // Balise <meta name="google-site-verification" ... />
        if( !empty($aSite['GOOGLE_SITE_VERIFICATION']) ){
            $head->setMeta('name', 'google-site-verification', $aSite['GOOGLE_SITE_VERIFICATION']);
        }
        
        if( $_SESSION[APP]['CODE_PAYS'] == 'AR'){
            $head->setMeta('name', 'p:domain_verify', 'b8079d2466c58fc2b3c75ff32a446f2c');
        }

		// Réinitialisation du mode édition véhicule lorsque l'utilisateur quitte la page Mon projet
		if ($layout->aPage['TEMPLATE_PAGE_ID'] != Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION']) {
			$_SESSION[APP]['VEHICULE_SELECTION_EDITION'] = false;
		}
        Pelican_Profiler::start('zones', 'page');
        $body = $layout->getZones();
        
        // Affichage de la 404 si la page ne contient aucun bloc à afficher (pour le mode courant : web/mobile)
        $show404empty = function($index) use ($layout){
            // Si on est déjà sur la 404, on ne l'appelle pas à nouveau
            if (isset($GLOBALS['__mark_code404Action'])) {
                return false;
            }
            
            // Si le compteur de bloc n'existe pas (ou que la page ne comporte aucun bloc), on n'affiche pas la 404
            if (!isset($layout->oZone->blocCount) || $layout->oZone->blocCount == 0) {
                return false;
            }
            
            $isMobile = $index->isMobile();
            
            // Si on est en mode web et que la page ne comporte aucun bloc web, on affiche la 404
            if (!$isMobile && $layout->oZone->blocCountWeb == 0) {
                return true;
            }
            
            // Si on est en mode mobile et que la page ne comporte aucun bloc mobile, on affiche la 404
            if ($isMobile && $layout->oZone->blocCountMobile == 0) {
                return true;
            }
            
            return false;
        };
        if ($show404empty($this)){
            $this->sendError(404, '');
        }
        // Remplacement de #MEDIA_HTTP# par l'URL du host media
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
        $body = str_replace('#MEDIA_HTTP#', Pelican::$config["SERVER_PROTOCOL"] . '://' . Pelican::$config["HTTP_MEDIA"], $body);

        Pelican_Profiler::stop('zones', 'page');

        Pelican_Profiler::start('fetch', 'page');
        $body .= $layout->getCybertag();

        //Récupération des informations du site
        $aSite = Pelican_Cache::fetch("Frontend/Site", array(
                    $_SESSION[APP]['SITE_ID']
        ));
        $aPageInfo = $this->getParams();
        if (isset($aPageInfo['popinMobile'])) {
            $this->assign('isMentionLegale', 1);
        }
		$head->setJs('/js/lang.js.php');

        if ($this->isMobile()) {
            /* Mobile */
            //$head->setJs("/jquery-1.8.0.min.js", '', 'jspackMob');
            //$head->setJs("/jquery.tools.min.meta.js", '', 'jspackMob');
            //$head->setJs("/jquery.functions.js", '', 'jspackMob');
            $noFont = 0;

           foreach (Pelican::$config["MOBILE_DEVICE_NOFONT"] as $keyMobileDevice => $valueMobileDevice) {
                  if(strstr($_SERVER['HTTP_USER_AGENT'], $valueMobileDevice))
                { 
                    $noFont = 1;
                    break;
                }
           }

           
            if ($aSite['SITE_CITROEN_FONT2'] == 1 && $noFont == 0) {
                $head->setCss(Pelican::$config["DOCUMENT_HTTP"] . "/css/mobile/main.css");
            }
			$head->setCss("/mobile/reset.css", "screen", "", "", 'cssPack2');
            $head->setCss("/mobile/main.css", "screen", "", "", 'mobile/cssPackMob');
            $head->setCss("/mobile/jquery-ui-1.10.3.custom.min.css", "screen", "", "", 'mobile/cssPackMob');
            // CPW-3964 main.css $head->setCss("/mobile/jquery-ui.css", "screen", "", "", 'mobile/cssPackMob');
            //$head->setCss(Pelican::$config["DESIGN_HTTP"]  . "/css/mobile/swipebox-1.1.2.css");
            // CPW-3964 main.css $head->setCss("/mobile/swipebox.css", "screen", "", "", 'mobile/cssPackMob');
            // CPW-3964 suppression de l'appel $head->setCss("/mobile/citroen.css", "screen", "", "", 'mobile/cssPackMob');

            $head->endJs("/mobile/lib/jquery-2.1.4.min.js", '', 'jspackMob');
             // CPW-3964 n'existe pas en mobile $head->endJs("/jquery.functions.js", '', 'jspackMob');
            //$head->endJs("/mobile/lib/jquery-ui-1.10.3.custom.min.js", '', 'jspackMob1');
            // CPW-3964 jquery.tools.min.js $head->endJs("/mobile/jquery.nouislider.min.js", '', 'jspackMob1');
            // CPW-3964 jquery.tools.min.js $head->endJs("/mobile/lib/jquery.ui.touch-punch.min.js", '', 'jspackMob2');
            $head->endJs("/mobile/lib/jquery.scrollTo-min.js", '', 'jspackMob2');
            // CPW-3964 jquery.tools.min.js $head->endJs("/mobile/lib/jquery.touchswipe.min.js", '', 'jspackMob2');
            //$head->endJs("/mobile/jquery-ui.js", '', 'jspackMob');
            // CPW-3964 js.tools.min.js $head->endJs("/mobile/jquery.bxslider.min.js", '', 'jspackMob2');
            // CPW-3964 js.tools.min.js $head->endJs("/mobile/jquery.lazyload.min.js", '', 'jspackMob2');
            //$head->endJs("/mobile/lib/swipebox/jquery.swipebox2.min.js", '', 'jspackMob3');
            //$head->endJs("/mobile/lib/swipebox/jquery.swipebox.1.2.1.js", '', 'jspackMob3');
            // CPW-3964 jquery.tools.min.js $head->endJs("/mobile/lib/underscore-min.js"); // ne peut pas être minifier
            //$head->endJs("/mobile/lib/select2.min.js", '', 'jspackMob4');
            // CPW-3964 js.tools.min.js $head->endJs("/mobile/iscroll-lite.js", '', 'jspackMob4');
            // CPW-3964 js.tools.min.js $head->endJs("/mobile/jgestures.min.js", '', 'jspackMob4');
            // CPW-3964 js.tools.min.js $head->endJs("/mobile/typeahead.min.js", '', 'jspackMob4');
            // CPW-3964 jquery.tools.min.js  $head->endJs("/jquery.masonry.min.js", '', 'jspackMob4');
            
            //$head->endJs("https://maps.googleapis.com/maps/api/js?client=" . $aSite['DNS'][$_SERVER['SERVER_NAME']]['map_google'] . "&amp;sensor=true&amp;libraries=places");

            $head->endJs("/mobile/google.maps.markerclusterer.v3.min.js", '', 'jspackMob2');
            // CPW-3964 citroen.js $head->endJs("/mobile/locator.js", '', 'jspackMob2');
            // CPW-3964 citroen.js $head->endJs("/mobile/main.js", '', 'jspackMob4');
            // CPW-3964 citroen.js $head->endJs("/mobile/demo.js", '', 'jspackMob4');
           // CPW-3964 citroen.js  $head->endJs("/mobile/applications.js", '', 'jspackMob4');
            // CPW-3964 suppression de l'appel $head->endJs("/mobile/users.js", '', 'jspackMob4');
             $head->endJs("/mobile/jquery.tools.min.js", '', 'jspackMob4');
             $head->endJs("/mobile/js.tools.min.js", '', 'jspackMob4');
             $head->endJs("/mobile/citroen.js", '', 'jspackMob4');
			 //$head->endJs("/webforms_loader.js", '', 'jspack'); 


            //$head->endJs(Pelican::$config['DESIGN_HTTP'].'/assets/js/vendor/jquery/dist/jquery.min.js', '', 'jspackMob4');
            $head->endJs(Pelican::$config['DESIGN_HTTP'].'/assets/js/vendor/jquery-migrate/jquery-migrate.js', '', 'jspackMob4');
            $head->endJs(Pelican::$config['DESIGN_HTTP'].'/assets/js/vendor/jquery.placeholder/jquery.placeholder.min.js', '', 'jspackMob4');
            $head->endJs(Pelican::$config['DESIGN_HTTP'].'/assets/js/vendor/jquery.cookie/jquery.cookie.js', '', 'jspackMob4');
            $head->endJs(Pelican::$config['DESIGN_HTTP'].'/assets/js/common/cookiesbanner.js', '', 'jspackMob4');
            $head->endJs(Pelican::$config['DESIGN_HTTP'].'/assets/js/common/modernizr-custom.js', '', 'jspackMob4');
            $head->endJs(Pelican::$config['DESIGN_HTTP'].'/assets/js/vendor/jquery.cookie/jquery.cookie.js', '', 'jspackMob4');
            //$head->endJs(Pelican::$config['DESIGN_HTTP'].'/assets/js/vendor/gsap/src/minified/TweenMax.min.js', '', 'jspackMob4');
            $head->endJs(Pelican::$config['DESIGN_HTTP'].'/assets/js/common/class/mqdetector.js', '', 'jspackMob4');
            $head->endJs(Pelican::$config['DESIGN_HTTP'].'/assets/js/common/class/touchdetect.js', '', 'jspackMob4');
            $head->endJs(Pelican::$config['DESIGN_HTTP'].'/assets/js/common/main.js', '', 'jspackMob4');
            $head->endJs(Pelican::$config['DESIGN_HTTP'].'/assets/js/common/nav-mobile.js', '', 'jspackMob4');
            $head->endJs(Pelican::$config['DESIGN_HTTP'].'/assets/js/common/toggle-content-mobile.js', '', 'jspackMob4');

            if(isset(Pelican::$config['JAVASCRIPT_FOOTER']['MOBILE']) && count(Pelican::$config['JAVASCRIPT_FOOTER']['MOBILE']) > 0){
                foreach(Pelican::$config['JAVASCRIPT_FOOTER']['MOBILE'] as $key => $script){
                    $head->endJs($script, '', 'jspackMob4'); // CPW-3964
                }
            }

            $html5 = '';
        } else {
            $frontFont = isset($aSite['SITE_CITROEN_FONT2']) ? intval($aSite['SITE_CITROEN_FONT2']) : 0;
            switch ($frontFont) {
                // Citroen
                case 1:
                    if($head->getPackStatus()){
                        $head->setCss("/../../../../frontend/css/main.css", '','','','cssPack1');
                    } else {
                        $head->setCss(Pelican::$config["DOCUMENT_HTTP"] . "/css/main.css");
                    }
                    $head->setCss("/main.css", "screen", "", "", 'cssPack2');
                    break;
                
                // Ubuntu
                case 2:

                    $head->setCss("/main.css", "screen", "", "", 'cssPack2');
                    if($_SESSION[APP]['CODE_PAYS']=='RU' || $_SESSION[APP]['CODE_PAYS']=='UA'){
						$head->setCss("/arial-mini.css", "screen", "", "", 'cssPack6');
					}
					$head->setCss("/font-cyrillic.css", "screen", "", "", 'cssPack6');
                    break;
                
                // Arial
                default:
                    if($head->getPackStatus()){
                        $head->setCss("/../../../../frontend/css/main.css", '','','','cssPack1');
                    } else {
                        $head->setCss(Pelican::$config["DOCUMENT_HTTP"] . "/css/main.css");
                    }
					$head->setCss("/main.css", "screen", "", "", 'cssPack2');
					$head->setCss("/font.css", "screen", "", "", 'cssPack2');
                    break;
            }
            
            //$head->setCss("/font.css", "screen", "", "", 'cssPack1');
            // CPW-3964 main.css $head->setCss("/responsive.css", "screen", "", "", 'cssPack3');
            // CPW-3964 main.css $head->setCss("/sprites.css", "screen", "", "", 'cssPack4');
            // CPW-3964 main.css $head->setCss("/videojs.css", "screen", "", "", 'cssPack3');
            // CPW-3964 suppresion de l'appel $head->setCss("/citroen.css", "screen", "", "", 'cssPack3');
            // CPW-3964 main.css $head->setCss("/jquery-ui.css", "screen", "", "", 'cssPack3');
            $head->endJs("/jquery-2.1.4.min.js", '', 'jspack');
			if($bShowJs){
				
				$sIsWebMobile = $this->isMobile()?'mobile':'desktop';
				$bC4Cactus = false;
				switch ($_GET['select_vehicule_lcdv6']) {
					case Pelican::$config['LCDV_C1']:
						$sFolderImg = Pelican::$config['FOLDER_C1'];
						break;
					case Pelican::$config['LCDV_GRAND_C4_PICASSO']:
						$sFolderImg = Pelican::$config['FOLDER_GRAND_C4_PICASSO'];
						break;
					case Pelican::$config['LCDV_C4_PICASSO']:
						$sFolderImg = Pelican::$config['FOLDER_C4_PICASSO'];
						break;
					case Pelican::$config['LCDV_C4_CACTUS']:
						$sFolderImg = Pelican::$config['FOLDER_C4_CACTUS'];
                        $bC4Cactus = true;
						break;
					case Pelican::$config['LCDV_C4']:
						$sFolderImg = Pelican::$config['FOLDER_C4'];
						break;
                    case Pelican::$config['LCDV_C3']:
                        $sFolderImg =Pelican::$config['FOLDER_C3'];
                        break;
                    case Pelican::$config['LCDV_C3_PICASSO']:
                        $sFolderImg =Pelican::$config['FOLDER_C3_PICASSO'];
                        break;
                    case Pelican::$config['LCDV_C5_TOURER']:
                        $sFolderImg =Pelican::$config['FOLDER_C5_TOURER'];
                        break;
				}
				
			
				if(!$this->isMobile() && !empty($sFolderImg)){
					if($_SESSION[APP]['CODE_PAYS']=='GB'){
						//$head->endJs(Pelican::$config["DESIGN_HTTP"] . "/animation/".$sFolderImg."/".$sIsWebMobile."/GB/script/mmd_script_uk.js");
                        $head->endJs(Pelican::$config["DESIGN_HTTP"] . "/animation/".$sFolderImg."/".$sIsWebMobile."/script/jquery.transit.min.js");
                        $head->endJs(Pelican::$config["DESIGN_HTTP"] . "/animation/".$sFolderImg."/".$sIsWebMobile."/script/script_mmd1404.js");
					}elseif($_SESSION[APP]['CODE_PAYS']=='PT' &&  $bC4Cactus == true){
						$head->endJs(Pelican::$config["DESIGN_HTTP"] . "/animation/".$sFolderImg."/".$sIsWebMobile."/PT/script/mmd_c4cactus_pt.js");	
					}else{
						$head->endJs(Pelican::$config["DESIGN_HTTP"] . "/animation/".$sFolderImg."/".$sIsWebMobile."/script/jquery.transit.min.js");
						if($sFolderImg == Pelican::$config['FOLDER_C4_CACTUS']){
						$head->endJs(Pelican::$config["DESIGN_HTTP"] . "/animation/script/jquery.min.js");
						}
						$head->endJs(Pelican::$config["DESIGN_HTTP"] . "/animation/".$sFolderImg."/".$sIsWebMobile."/script/script_mmd1404.js");
					}
				}
			}
            // CPW-3964 jquery.tools.min.js $head->endJs("/jquery.functions.js", '', 'jspack');
            //Déplacé dans la version WEB car conflit avec jquery 2.0 mobile - vu avec David - Non utilisé
            //$head->setJQuery ( 'validationEngine' );
            // CPW-3964 jquery.tools.min.js $head->endJs("/jquery-ui.js", '', 'jspack');
            // CPW-3964 jquery.tools.min.js $head->endJs("/underscore-min.js"); // ne peut pas être minifier 
            // CPW-3964 jquery.tools.min.js $head->endJs("/jquery.fancybox.pack.js", '', 'jspack');
            // CPW-3964 jquery.tools.min.js $head->endJs("/jquery.fancybox-media.js", '', 'jspack');

           // $head->endJs("https://maps.googleapis.com/maps/api/js?client=" . $aSite['DNS'][$_SERVER['SERVER_NAME']]['map_google'] . "&amp;sensor=true&amp;libraries=places");
                
            //die($aSite['DNS'][$_SERVER['SERVER_NAME']]['map_google']);
                
            $head->endJs("/google.maps.markerclusterer.v3.min.js", '', 'jspack');
            $head->endJs("/locator.js", '', 'jspack');
            /* $head->setScript('
              <div class="sneezies" id="sneezy_<%= id %>"><div class="inner">
              <%
              _.each(items,function(item){
              var isImg = (/\.jpeg|jpg|gif|png$/).test(item),
              ytRegExp = new RegExp(\'(//www\\.youtube\\.com/watch\\?v=)|(//youtu\\.be/)(\\w*)\'),
              isYoutube = (ytRegExp.test(item))? item.replace(ytRegExp,\'//www.youtube.com/embed/$3\') : false;
              if(isYoutube) isYoutube = isYoutube.split(\'&\')[0];
              %>

              <div class="item">
              <div class="closer"></div>
              <% if(isImg){ %>
              <img src="<%= item %>" alt="" />
              <% } else if(isYoutube){ %>
              <iframe src="<%= isYoutube %>?autoplay=0&autohide=1&fs=1&rel=0&hd=1&wmode=opaque&enablejsapi=1" frameborder="0"></iframe>
              <% }; %>
              <span class="popClose"><span>Fermer</span></span>
              </div>
              <%
              });
              %>
              </div></div>
              ', 'foot'); */
            // CPW-3964 jquery.tools.min.js $head->endJs("/jquery.bxslider.min.js", '', 'jspack');
            // CPW-3964 jquery.tools.min.js $head->endJs("/jquery.lazyload.min.js", '', 'jspack');
            // CPW-3964 jquery.tools.min.js $head->endJs("/jquery.mousewheel.min.js", '', 'jspack');
            // CPW-3964 jquery.tools.min.js $head->endJs("/jquery.jscrollpane.min.js", '', 'jspack');
            // CPW-3964 jquery.tools.min.js $head->endJs("/jquery.masonry.min.js", '', 'jspack');

            $head->endJs("/jquery.tools.min.js", '', 'jspack'); // CPW-3964 
            $head->endJs("/typeahead.min.js", '', 'jspack');
            $head->endJs("/video.js", '', 'jspack');
            // CPW-3964 citroen.js $head->endJs("/main.js", '', 'jspack');
            //$head->endJs("/f5.js", '', 'jspack');
            // CPW-3964 citroen.js $head->endJs("/applications.js", '', 'jspack');
            $head->endJs("/citroen.js", '', 'jspack'); // CPW-3964
			//$head->endJs("/webforms_loader.js", '', 'jspack'); 
			

	

			
			
			    $head->endJs(Pelican::$config['DESIGN_HTTP']."/assets/js/vendor/jquery-migrate/jquery-migrate.js", '', 'jspackMob4');
				$head->endJs(Pelican::$config['DESIGN_HTTP']."/assets/js/vendor/jquery.placeholder/jquery.placeholder.min.js", '', 'jspackMob4');
				$head->endJs(Pelican::$config['DESIGN_HTTP']."/assets/js/vendor/gsap/src/minified/TweenMax.min.js", '', 'jspackMob4');
				$head->endJs(Pelican::$config['DESIGN_HTTP']."/assets/js/vendor/jquery.cookie/jquery.cookie.js", '', 'jspackMob4');
				// $head->endJs(Pelican::$config['DESIGN_HTTP']."/assets/js/common/cookiesbanner.js", '', 'jspackMob4');
				// $head->endJs(Pelican::$config['DESIGN_HTTP']."/assets/js/common/modernizr-custom.js", '', 'jspackMob4');
				// $head->endJs(Pelican::$config['DESIGN_HTTP']."/assets/js/common/class/mqdetector.js", '', 'jspackMob4');
				// $head->endJs(Pelican::$config['DESIGN_HTTP']."/assets/js/common/class/touchdetect.js", '', 'jspackMob4');
				// $head->endJs(Pelican::$config['DESIGN_HTTP']."/assets/js/common/easyTab.js", '', 'jspackMob4');
				// $head->endJs(Pelican::$config['DESIGN_HTTP']."/assets/js/common/main.js", '', 'jspackMob4');
				// $head->endJs(Pelican::$config['DESIGN_HTTP']."/assets/js/common/nav-desktop.js", '', 'jspackMob4');
				// $head->endJs(Pelican::$config['DESIGN_HTTP']."/assets/js/common/toolbar.js", '', 'jspackMob4');
				// $head->endJs(Pelican::$config['DESIGN_HTTP']."/assets/js/common/scrolldown.js", '', 'jspackMob4');
				$head->endJs("/function.psa.js", '', 'jspack'); // CPW-3964

				if(isset(Pelican::$config['JAVASCRIPT_FOOTER']['WEB']) && count(Pelican::$config['JAVASCRIPT_FOOTER']['WEB']) > 0){
					foreach(Pelican::$config['JAVASCRIPT_FOOTER']['WEB'] as $key => $script){
						$head->endJs($script, '', 'jspackMob4'); // CPW-3964
					}
				}

			
			
            // CPW-3964 suppression de l'appel $head->endJs("/users.js", '', 'jspack');

            $html5 = '
<!--[if lt IE 9]>
    <script>
        var html5 = ["header","footer","aside","article","section","nav","summary","time"];
        for(var i=0; i<html5.length; i++){ document.createElement(html5[i]); }
    </script>
<![endif]-->
        ';
    }
    $sGoogleMapsJs = sprintf("https://maps.googleapis.com/maps/api/js?client=%s&sensor=true&libraries=places",$aSite['DNS'][$_SERVER['SERVER_NAME']]['map_google']);
    $head->setScript("var googlemapAPI = '".$sGoogleMapsJs."';");
//    $head->setMeta('property', 'og:site_name', Pelican::$config['SITE']['INFOS']['SITE_TITLE']);
    
    $head->setScript('var url_supplements = new String("'.Citroen\Url::supplementsToUrlQuery().'");','foot');
    $js_supp = <<<JS_SUP
    $(function(){
        $('body a').on('click',function(e){
            if(url_supplements.length>0 
                && $(this).attr('href').indexOf(url_supplements)==-1
                && $(this).attr('href').substring(0,1)!='/' 
                && $(this).attr('href').substring(0,1)!='#'
            ){
                var url_supp = url_supplements;
                var url = $(this).attr('href');
                if(url.indexOf(location.href)==-1){
                    var pos_query = url.indexOf('?');
                    if(pos_query==-1){
                        var pos_achor = url.indexOf('#');
                        if(pos_achor==-1){
                            pos_query = url.length-1;
                        }else{
                            pos_query = pos_achor-1;
                        }
                        url_supp = '?'+url_supp;
                    }else{
                        url_supp = url_supp+'&';
                    }
                    url = url.substr(0,pos_query).concat(url_supp,url.substr(pos_query+1));
                    $(this).attr('href',url);
                }
            }
        });
    });                   
JS_SUP;
    $head->setScript($js_supp,'foot');
    

//	$head->setMeta('property', 'twitter:card', "summary_large_image");    
//	$head->setMeta('property', 'twitter:site', "@citroen");    
	
//    if(isset($_GET['content_title'])){
//        $head->setMeta('property', 'og:title', $_GET['content_title']);
//        $head->setMeta('property', 'twitter:title', $_GET['content_title']);
//
//    }else{
//        $head->setMeta('property', 'og:title', str_replace('"', "&quot;", $layout->getPageTitle()));
//        $head->setMeta('property', 'twitter:title', str_replace('"', "&quot;", $layout->getPageTitle()));
//    }
	
//    if(isset($_GET['content_media'])){		
//                $head->setMeta('property', 'og:image', $_GET['content_media']);            
//				$head->setMeta('property', 'twitter:image', $_GET['content_media']);		
//				
//                $image_info = Pelican_Media::getMediaInfo($_GET['media_id']);
//				if(is_array($image_info)&& 
//							($image_info["MEDIA_WIDTH"] > 200 
//							|| $image_info["MEDIA_HEIGHT"] > 200)){
//					$head->setMeta('property', 'og:image:width', $image_info["MEDIA_WIDTH"]);
//					$head->setMeta('property', 'og:image:height', $image_info["MEDIA_HEIGHT"]);
//				}else{
//					$head->setMeta('property', 'og:image:width', 300);
//					$head->setMeta('property', 'og:image:height', 200);
//
//
//
//
//				}
//    }else{
//        $head->setMeta('property', 'og:image', $layout->aPage['MEDIA_PATH'] ? Pelican::$config["MEDIA_HTTP"] . $layout->aPage['MEDIA_PATH'] : '');
//		$head->setMeta('property', 'twitter:image', $layout->aPage['MEDIA_PATH'] ? Pelican::$config["MEDIA_HTTP"] . $layout->aPage['MEDIA_PATH'] : '');		
//        if($layout->aPage['MEDIA_PATH'] != ''){
//            $image_info = Pelican_Media::getMediaInfo($layout->aPage['MEDIA_ID']);
//            if(is_array($image_info) && 
//                    ($image_info["MEDIA_WIDTH"] > 200 
//                    || $image_info["MEDIA_HEIGHT"] > 200)){
//                    $head->setMeta('property', 'og:image:width', $image_info["MEDIA_WIDTH"]);
//                    $head->setMeta('property', 'og:image:height', $image_info["MEDIA_HEIGHT"]);
//            }else{
//                    $head->setMeta('property', 'og:image:width', 300);
//                    $head->setMeta('property', 'og:image:height', 200);
//            }
//        }
//    }        

    //CPW-3600
    $head->setMeta('property', 'og:type', 'website');
    
    $image_info = array();
    
    if($layout->aPage['PAGE_VEHICULE'] && ($layout->aPage['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_INTERNE'] || $layout->aPage['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL'])){
        $vehicule = Pelican_Cache::fetch("Frontend/Citroen/VehiculeShowroomById", 
                array($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $layout->aPage['PAGE_VEHICULE'], 'CURRENT', null, null, $layout->aPage['PAGE_ID']));
        $aListeCouleurs = $vehicule['COLORS'];
        $aListeCouleursTemp = array();
        $aListeCouleursTemp2 = array();
        if(is_array($aListeCouleurs)){
            foreach ($aListeCouleurs as $key => $value) {
                if(isset( $value['PAGE_ZONE_MULTI_ORDER'] ) && !empty( $value['PAGE_ZONE_MULTI_ORDER'] )){
                    $aListeCouleursTemp[$value['PAGE_ZONE_MULTI_ORDER']] = $value;
                }else{
                    $aListeCouleursTemp2[] = $value;
                }            
            }
            ksort($aListeCouleursTemp);        
            $aListeCouleurs = array_merge($aListeCouleursTemp, $aListeCouleursTemp2);
            $image_info['MEDIA_PATH'] = $aListeCouleurs[0]['CARWEB1_PATH'];
        }
    }elseif($layout->aPage['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['ACTU_DETAIL'] || $layout->aPage['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['ACTU_GALERIE']){
        $og_image_id = '';
        if($layout->aPage['MEDIA_ID']){
            $og_image_id = $layout->aPage['MEDIA_ID'];
        }elseif($_SESSION[APP]['ACTU_MEDIA_ID']){
            $og_image_id = $_SESSION[APP]['ACTU_MEDIA_ID'];
        }
        $image_info = Pelican_Media::getMediaInfo($layout->aPage['PAGE_OG_IMAGE']? $layout->aPage['PAGE_OG_IMAGE'] : $og_image_id);
    }
    
    $head->setMeta('property', 'og:image', $image_info['MEDIA_PATH'] ? Pelican::$config["MEDIA_HTTP"] . $image_info['MEDIA_PATH'] : Pelican::$config['IMAGE_FRONT_HTTP'].'/logo.png');
    if(isset($image_info["MEDIA_WIDTH"])&&$image_info["MEDIA_WIDTH"] > 280){
        $head->setMeta('property', 'og:image:width', $image_info["MEDIA_WIDTH"]);
    }else{
        $head->setMeta('property', 'og:image:width', 280);
    }
    if(isset($image_info["MEDIA_HEIGHT"])&&$image_info["MEDIA_HEIGHT"] > 150) {
        $head->setMeta('property', 'og:image:height', $image_info["MEDIA_HEIGHT"]);
    } else {
        $head->setMeta('property', 'og:image:height', 150);
    }
    
    //External links must use http:// or https://
    if($layout->aPage['PAGE_OG_URL']){
        if(substr($layout->aPage['PAGE_OG_URL'], 0, 7) == "http://" || substr($layout->aPage['PAGE_OG_URL'], 0, 8) == "https://"){
            $OG_URL = $layout->aPage['PAGE_OG_URL'];
        }else{
            if(substr($layout->aPage['PAGE_OG_URL'], 0, 1) == "/"){
                $OG_URL = Pelican::$config["DOCUMENT_HTTP"].$layout->aPage['PAGE_OG_URL'];
            }else{
                $OG_URL = Pelican::$config["DOCUMENT_HTTP"]."/".$layout->aPage['PAGE_OG_URL'];
 
            }
        }
    }
    $head->setMeta('property', 'og:url', $OG_URL ? $OG_URL : Pelican::$config["DOCUMENT_HTTP"].$layout->aPage ['PAGE_CLEAR_URL']);
    $head->setMeta('property', 'og:title', $layout->aPage['PAGE_OG_TITLE'] ? $layout->aPage['PAGE_OG_TITLE'] : $layout->aPage ['PAGE_META_TITLE']);
    $head->setMeta('property', 'og:site_name', $aSite ['SITE_TITLE']);
    $head->setMeta('property', 'og:description', $layout->aPage['PAGE_OG_DESC'] ? $layout->aPage['PAGE_OG_DESC'] : $layout->aPage['PAGE_META_DESC']);
    //CPW-3600
    //
//    $head->setMeta('property', 'og:type', '');
//    $desc = (valueExists($layout->aContent, "CONTENT_META_DESC") ? $layout->aContent["CONTENT_META_DESC"] : $layout->aPage["PAGE_META_DESC"]);
//    $desc = (!empty($layout->aPage["PAGE_TEXT"])) ? $layout->aPage["PAGE_TEXT"] : $desc;
    
//     if(isset($_GET['content_description'])){
//		$head->setMeta('property', 'og:description', $_GET['content_description']);
//        $head->setMeta('property', 'twitter:description', $_GET['content_description']);
//    }
//	else
//	{
//		$head->setMeta('property', 'og:description', str_replace('"', "&quot;", $desc));
//		$head->setMeta('property', 'twitter:description', str_replace('"', "&quot;", $desc));
//	}
    if ($this->isMobile()) {
        $head->setMeta('name', 'viewport', 'width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no');
    } else {
        $head->setMeta('name', 'viewport', 'width=device-width,user-scalable=no');
            //$head->setMeta('http-equiv', 'X-UA-Compatible', 'IE=edge');
    }
        $head->setMeta('name', 'apple-mobile-web-app-capable', 'yes');
        $head->setMeta('name', 'apple-mobile-web-app-status-bar-style', 'black');
        $head->setMeta('name', 'format-detection', 'telephone=no');
        $head->setMetaRobots(Pelican::$config['ROBOTS_SEO_FO'][$layout->aPage['PAGE_META_ROBOTS']]);
        $this->assign('doctype', $head->getDocType());
        $aSession = current($_SESSION);
        $this->assign('lang', $aSession['LANGUE_CODE']);
        if ($_SERVER['HTTP_X_REQUESTED_WITH'] == "") {
            if($layout->aPage['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['POINT_DE_VENTE_IFRAME']){
                $this->assign('page_skin', 'ds');

            }
        }
        /* cas pour la popin
         *  http://cppv2.dev.media/library/External/swfobject/swfobject.js.
         * Origin http://cppv2.dev.frontend is not allowed by Access-Control-Allow-Origin
         */
        $edge = '<meta http-equiv="X-UA-Compatible" content="IE=edge" />';
        if (!isset($_GET['popin'])) {
            $this->assign('header', $edge . "\n" . $head->getHeader(false) . $html5, false);
            $this->assign('footer', $head->getFooter(), false);
            $this->assign('footerJS', $aSite['FOOTER_JS']);
        }
        if ($_GET['debug']) {
            debug(1);
        }

        if ($_GET['perso']) {
            do {
                $aBind = array(
                    ':SITE_ID' => $_SESSION[APP]['SITE_ID'],
                    ':LANGUE_ID' => $_SESSION[APP]['LANGUE_ID'],
                );

                // Liste des indicateurs contenant un PRODUCT_ID
                $aProductKeys = array(
                    'current_product',
                    'preferred_product',
                    'product_best_score',
                    'recent_product'
                );

                // Liste des indicateurs contenant un code LCDV6
                $aLcdv6Keys = array(
                    'product_owned'
                );

                if (isset($_SESSION[APP]['USER'])) {
                    $user_id = $_SESSION[APP]['USER']->getId();
                } else {
                    $user_id = null;
                }
                
                try {
                    $oScoreManager = new ScoreManager();
                    $oIndicateurManager = new IndicateurManager();
                } catch (\MongoConnectionException $ex) {
                    break;
                }

                $oIndicateurCursor = $oIndicateurManager->getAllByUser($_SESSION[APP]['perso_sess'], $user_id);
                $aIndicateurs = array();
                $oConnection = Pelican_Db::getInstance();

                $sSQL = "SELECT * FROM  psa_perso_product";
                $aProducts = $oConnection->queryTab($sSQL);
                $aProductsIndexed = array();
                if (count($aProducts)) {
                    foreach ($aProducts as $aOneProduct) {
                        $aProductsIndexed[$aOneProduct['PRODUCT_ID']] = $aOneProduct['PRODUCT_LABEL'];
                    }
                }
                
                // Récupération mapping des profils : id/nom
                $profiles = \Pelican_Cache::fetch("Citroen/PersoProfile", array($_SESSION[APP]['LANGUE_CODE']));
                if (is_array($profiles) ){
                    $aProfilNamesIndexed = array();
                    foreach ($profiles as $val) {
                        $aProfilNamesIndexed[$val['PROFILE_ID']] = !empty($val['locallabel']) ? $val['locallabel'] : $val['PROFILE_LABEL'];
                    }
                }

                if (
                        isset($_SESSION[APP]['PROFILES_USER']) && count($_SESSION[APP]['PROFILES_USER'])
                ) {
                    $aProfiles = array();
                    foreach ($_SESSION[APP]['PROFILES_USER'] as $v) {
                        $aProfiles[] = $aProfilNamesIndexed[$v];
                    }
                }

                // Table de mapping code LCDV6 => PRODUCT_ID
                $map_lcdv6_productid = Pelican_Cache::fetch("Frontend/Citroen/Perso/Lcdv6ByProduct", array($_SESSION[APP]['SITE_ID']));
                $lcdv6_indexed_products = array_flip(array_filter($map_lcdv6_productid));
                $aVehicules =  Pelican_Cache::fetch("Frontend/Citroen/Perso/VehiculesNamesInDebug",array($_SESSION[APP]['SITE_ID'],$_SESSION[APP]['LANGUE_ID']));
                foreach ($oIndicateurCursor as $oIndicateur) {
                    foreach ($oIndicateur as $k => $v) {
                        
                        // On ignore les indicateurs dont le nom commence par __ (indicateurs internes)
                        if (preg_match('/^__/i', $k)) {
                            continue;
                        }
                        
                        // Ajout du nom du véhicule lorsque l'indicateur contient un PRODUCT_ID
                        if (in_array($k, $aProductKeys) && !empty($v)) {
                            $OneIndicateur[$k] = sprintf('%s(id:%s)', $aProductsIndexed[$v], $v);
                        }

                        // Ajout du nom du véhicule lorsque l'indicateur contient un code LCDV6
                        elseif (in_array($k, $aLcdv6Keys) && !empty($v) ) {
                            $OneIndicateur[$k] = sprintf('%s(lcdv6:%s)', $aVehicules[$v], $v);

                        }
                        // Aucun post-traitement pour l'indicateur
                        // Sauf evol 2739 - retraitement indice 
                        else {
                            if(is_array($v) && !empty($v) && ($k == 'tranche_true_score' || $k == 'tranche_score'))
                            {
                               foreach ($v as $keyV => $valueV) {
                                   $v[sprintf('%s(id:%s)', $aProductsIndexed[$keyV], $keyV)] = $valueV;
                                   unset($v[$keyV]);
                                }
                            }
                            $OneIndicateur[$k] = $v;
                        }
                    }
                    $aIndicateurs[] = $OneIndicateur;
                }

                $oProductsCursor = $oScoreManager->getAllProductsByUser($_SESSION[APP]['perso_sess'], $user_id);

                $oProductsCursor = iterator_to_array($oProductsCursor);
                $aProducts = array();
                foreach ($oProductsCursor as $oProduct) {
                    $oProduct['product'] = sprintf('%s(id:%s)', $aProductsIndexed[$oProduct['product']], $oProduct['product']);
                    $aProducts[] = $oProduct;
                }

                $oRecentProduct = $oScoreManager->getMostRecentProductByUser($_SESSION[APP]['perso_sess'], $user_id);


                if (null != $oRecentProduct) {
                    $oRecentProduct['product'] = sprintf('%s(id:%s)', $aProductsIndexed[$oRecentProduct['product']], $oRecentProduct['product']);
                }

                $oMaxScoreProduct = $oScoreManager->getProductWithMaxScoreByUser($_SESSION[APP]['perso_sess'], $user_id);
                if (null != $oMaxScoreProduct) {
                    $oMaxScoreProduct['product'] = sprintf('%s(id:%s)', $aProductsIndexed[$oMaxScoreProduct['product']], $oMaxScoreProduct['product']);
                }



                $debug = array(
                    'user_id' => $user_id,
                    'session_id' => $_SESSION[APP]['perso_sess'],
                    'scores' => array(
                        'max_score' => $oMaxScoreProduct,
                        'recent_product' => $oRecentProduct,
                        'all_products' => $aProducts
                    ),
                    'indicateurs' => $aIndicateurs[0],
                    'profile' => $aProfiles,
                    'consultation_pge' => isset($_SESSION[APP]['perso_consultation_page']) ? $_SESSION[APP]['perso_consultation_page'] : null,
                    'referrer_score' => isset($_SESSION[APP]['perso_referrer']) ? $_SESSION[APP]['perso_referrer'] : null,
                );
                
                if(isset($sMbdPref)){
                    $debug['__MBD_PREF__']= $sMbdPref;
                }
                if(isset($sMbdCurrent)){
                    $debug['__MBD_CURRENT__']= $sMbdCurrent;
                    }
                debug($debug);
            } while(0);
        }


        
        $body = str_replace('##LCDV_PREF_NAME##', $sLcdvPrefName, $body);
        $body = str_replace('##LCDV_PREF##', $sLcdvPref, $body);
        $body = str_replace('##LCDV_CURRENT_NAME##', $sLcdvCurrentName, $body);
        $body = str_replace('##LCDV_CURRENT##', $sLcdvCurrent, $body);
        
        
        if(isset($sMbdPref)){
	        $body = str_replace('##MBD_PREF##', $sMbdPref, $body);
        }
        if(isset($sMbdCurrent)){
	        $body = str_replace('##MBD_CURRENT##', $sMbdCurrent, $body);
        }
        
        //Type de cookie
        $aCookies = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
                    $pageGlobal['PAGE_ID'],
                    Pelican::$config['ZONE_TEMPLATE_ID']['COOKIE'],
                    $pageGlobal['PAGE_VERSION'],
                    $_SESSION[APP]['LANGUE_ID']
        ));
        if ($aCookies['ZONE_TITRE4'] === NULL) {
            $aCookies['ZONE_TITRE4'] = 1;
        } 
        $this->assign("cookieType", $aCookies['ZONE_TITRE4']);
        //pid & cid
        $this->assign("pid", $aCookies['ZONE_TITRE5']);
        $this->assign("cid", $aCookies['ZONE_TITRE6']);

        $this->assign('body', $previewDeviceChoice . $body, false);
	$this->assign('CODE_PAYS', $_SESSION[APP]['CODE_PAYS'], false);
        // Génération & transmission du tag HTML Google Tag Manager au template
        $this->assign('gtmTag', Frontoffice_Analytics_Helper::getGtmTag(), false);

        // Récupération du sharer configuré en backoffice > Administration > Groupes de réseaux sociaux
        $aMainSharer = Pelican_Cache::fetch("Frontend/Citroen/FindGroupeReseauxSociaux", array($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']));
        $sMainSharer = Backoffice_Share_Helper::getSharer($aMainSharer['GROUPE_RESEAUX_SOCIAUX_ID'], $aMainSharer['SITE_ID'], $aMainSharer['LANGUE_ID'], Pelican::$config['MODE_SHARER'][0], array('index_sharebox' => true));
        $this->assign('shareTpl', $sMainSharer, false);

        /** affichage de la vue */
        $this->fetch();
        Pelican_Profiler::stop('fetch', 'page');
    }

    public function connectPreviewAction() {
        $error = false;
        $errorBack = "";

        //controle login
        if ($this->getParam('preview_login') != Pelican::$config["SITE"]["INFOS"]["SITE_LOGIN_PREVISU"]) {
            $errorBack = "?error=login";
            $error = true;
        }

        //controle mdp
        if ($this->getParam('preview_mdp') != Pelican::$config["SITE"]["INFOS"]["SITE_PWD_PREVISU"]) {
            if ($errorBack != "") {
                $errorBack .= "&error=mdp";
            } else {
                $errorBack .= "?error=mdp&login=" . $this->getParam('preview_login');
            }
            $error = true;
        }

        if ($error == true) {
            $this->redirect('/_/Index/preview' . $errorBack);
        } else {
            $_SESSION["APP"]["PREVIEW"]["LOGGED"] = 1;
            if($this->getParam('schedule')){
                $this->redirect('/_/Index/preview?schedule=1&pid=' . $_SESSION["APP"]["PREVIEW"]["PID"]);
            }
            $this->redirect('/_/Index/preview?pid=' . $_SESSION["APP"]["PREVIEW"]["PID"]);
        }
    }

    public function robotsAction() {

        $device = $this->isMobile() ? 'mob' : 'web';
        $file = '/robots_' . $device . '.txt';
        $robotsTxt = @file_get_contents(Pelican::$config["DOCUMENT_INIT"] . "/var/robots/" . $_SESSION[APP]['CODE_PAYS'] . $file);
        $robotsTxt = str_replace('##SITEMAP##', '/sitemap.xml', $robotsTxt);

        header("Content-Type:text/plain");
        echo $robotsTxt;
    }
	
	function ranger($url){
		$headers = array(
		"Range: bytes=0-32768"
		);

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($curl);
		curl_close($curl);
		return $data;
	}
    

}
