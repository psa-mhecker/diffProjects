<?php
use Citroen\SelectionVehicule;
use Citroen\Perso\Flag\Detail;

class Layout_Citroen_Global_Header_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        //debug($_SESSION[APP]);


        if (!$_GET['popin']) {

            $aParams = $this->getParams();
			
			
			
            $this->assign("aParams", $aParams);

            $user = \Citroen\UserProvider::getUser();
            $this->assign("user", $user);

            $this->assign("session", $_SESSION[APP]);
            // Masquage du fil d'ariane pour les pages utilisants les gabarits suivant
            $this->assign("bTpl404", (in_array($aParams['TEMPLATE_PAGE_ID'], array(Pelican::$config['TEMPLATE_PAGE']['404']))) ? 1 : 0);
            $this->assign("bTplHome", (in_array($aParams['TEMPLATE_PAGE_ID'], array(Pelican::$config['TEMPLATE_PAGE']['HOME']))) ? 1 : 0);

            $pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    Pelican::getPreviewVersion(),
                    Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
            ));

			// Zone Configuration de la page globale
			$aConfigurationPageGlobal = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
					$pageGlobal['PAGE_ID'],
					Pelican::$config['ZONE_TEMPLATE_ID']['CONFIGURATION'],
					$pageGlobal['PAGE_VERSION'],
					$_SESSION[APP]['LANGUE_ID']
			));
			
			if(intval($aConfigurationPageGlobal['MEDIA_ID9'])>0){
				$aConfigurationPageGlobal['MEDIA_ID9'] = Pelican::$config['MEDIA_HTTP'].Pelican_Media::getMediaPath($aConfigurationPageGlobal['MEDIA_ID9']);
			}
			
			
			$this->assign("aConfig", $aConfigurationPageGlobal);
            /* Gestion des langues */

            $siteLangues = Pelican_Cache::fetch("Frontend/Citroen/SiteLangues", array(
                    $_SESSION[APP]['SITE_ID']
            ));

            // Placement de la langue courante en première position dans la liste
            $currentLangKey = null;
            if (is_array($siteLangues)) {
                foreach ($siteLangues as $key => $val) {
                    if ($val['LANGUE_ID'] == $_SESSION[APP]['LANGUE_ID']) {
                        $currentLangKey = $key;
                        break;
                    }
                }
                unset($key, $val);
            }
            if (isset($currentLangKey)) {
                $currentLang = $siteLangues[$currentLangKey];
                unset($siteLangues[$currentLangKey]);
                $siteLangues = array_merge(array($currentLang), $siteLangues);
                unset($currentLang, $currentLangKey);
            }

            $this->assign("siteLangues", $siteLangues);

            if (is_array($siteLangues) && !empty($siteLangues)) {
                foreach ($siteLangues as $langues) {
                    $pageLangue[$langues['LANGUE_ID']] = Pelican_Cache::fetch("Frontend/Page", array($_GET['pid'], $_SESSION[APP]['SITE_ID'], $langues['LANGUE_ID'], Pelican::getPreviewVersion()));
                }
            }
            $this->assign("pageLangue", $pageLangue);

            /*
             *  Utilisation des cookies
             */
            $aCookies = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
                    $pageGlobal['PAGE_ID'],
                    Pelican::$config['ZONE_TEMPLATE_ID']['COOKIE'],
                    $pageGlobal['PAGE_VERSION'],
                    $_SESSION[APP]['LANGUE_ID']
            ));

            if($aCookies['ZONE_TITRE4'] === NULL){
                $aCookies['ZONE_TITRE4'] = 1;
            }
            /*
             *  Utilisation de configuration
             */
            $aConfigGlobal = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
                    $pageGlobal['PAGE_ID'],
                    Pelican::$config['ZONE_TEMPLATE_ID']['CONFIGURATION'],
                    $pageGlobal['PAGE_VERSION'],
                    $_SESSION[APP]['LANGUE_ID']
            ));
            $altLogo = empty($aConfigGlobal['MEDIA_PATH2']) ? 'Citroën' : $aConfigGlobal['MEDIA_PATH2'];
            $this->assign("altLogo", $altLogo);

            $bDisplayCookiesLayer = false;
            $_SESSION[APP]['USE_COOKIES'] = Backoffice_Cookie_Helper::getCookie('USING_COOKIES');
            $_SESSION[APP]['SHOW_COOKIES_LAYER'] = ($_SESSION[APP]['USE_COOKIES'] == true) ? false : true;
            /* Gestion des différents cas d'utilisation des cookies */
            if (is_array($aCookies) && isset($aCookies['ZONE_PARAMETERS'])) {
                switch ($aCookies['ZONE_PARAMETERS']) {
                    /* Aucune info sur les cookies n’est donnée sur le site.
                     * Dans ce cas, les cookies sont acceptés par défaut et la
                     * page + tranche cookies doivent pouvoir être désactivés. */
                    case 'FORCE_COOKIES' :
                        $_SESSION[APP]['USE_COOKIES'] = true;
                        $_SESSION[APP]['SHOW_COOKIES_LAYER'] = false;
                        $bDisplayCookiesLayer = $_SESSION[APP]['SHOW_COOKIES_LAYER'];
                        break;
                    /* Par défaut, les cookies sont acceptés. La tranche
                     * « cookies » s’affiche avec un texte du type « En naviguant
                     * sur ce site vous acceptez les cookies… », un bouton
                     * « Continuez », une page « plus d’infos sur les cookies ».
                     * Si l’internaute continue à naviguer sur le site, la
                     * tranche « cookies » ne s’affiche plus.
                     */
                    case 'INFO_COOKIES' :
                        if (is_array($_SESSION[APP]) && !isset($_SESSION[APP]['SHOW_COOKIES_LAYER'])) {
                            $bDisplayCookiesLayer = true;
                        } else {
                            $bDisplayCookiesLayer = $_SESSION[APP]['SHOW_COOKIES_LAYER'];
                        }
                        /* Cookies acceptés */
                        $_SESSION[APP]['USE_COOKIES'] = true;
                        /* Masquage du bandeau d'informations */
                        $_SESSION[APP]['SHOW_COOKIES_LAYER'] = false;
                        Backoffice_Cookie_Helper::setCookie('USING_COOKIES', true, time() + (10 * 365 * 24 * 60 * 60));
                        break;
                    /* Par défaut, aucun cookie (sauf exception) ne doit être
                     * déposé. La tranche « cookies » s’affiche avec un texte,
                     * le bouton « Acceptez » et une page « plus d’infos sur
                     * les cookies ».Si l’internaute continue à naviguer sur le
                     * site, la tranche « cookies » reste affichée.A l’acceptation,
                     * cette tranche disparait
                     */
                    case 'ACCEPT_COOKIES' :
                        if (is_array($_SESSION[APP]) && !isset($_SESSION[APP]['SHOW_COOKIES_LAYER'])) {
                            $bDisplayCookiesLayer = true;
                        } else {
                            $bDisplayCookiesLayer = $_SESSION[APP]['SHOW_COOKIES_LAYER'];
                        }
                        break;
                }
            }
            $this->assign('aCookies', $aCookies);
            $this->assign('bDisplayCookiesLayer', $bDisplayCookiesLayer);

            /*
             * Navigation Push
             */
            $zoneNavigationPush = Pelican_Cache::fetch("Frontend/Page/ZoneTemplateId", array(
                    $pageGlobal['PAGE_ID'],
                    Pelican::$config['ZONE_TEMPLATE_ID']['NAVIGATION_PUSH'],
                    Pelican::getPreviewVersion(),
                    $_SESSION[APP]['LANGUE_ID']
            ));
            $navigationPush = Citroen_Cache::fetchProfiling($zoneNavigationPush['ZONE_PERSO'], "Frontend/Citroen/ZoneMulti", array(
                    $pageGlobal['PAGE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    Pelican::getPreviewVersion(),
                    Pelican::$config['ZONE_TEMPLATE_ID']['NAVIGATION_PUSH'],
                    'PUSH',
                    $aParams['AREA_ID'],
                    $aParams['ZONE_ORDER']
            ));

            if ($navigationPush) {
                foreach ($navigationPush as $i => $push) {
                    if ($push['MEDIA_ID']) {
                        $navigationPush[$i]['MEDIA_PATH'] = Pelican_Media::getMediaPath($push['MEDIA_ID']);
                    }
                }
            }
            $this->assign("navigationPush", $navigationPush);

            /*
             *  Mon projet
             */
            $monProjet = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
                    $pageGlobal['PAGE_ID'],
                    Pelican::$config['ZONE_TEMPLATE_ID']['MON_PROJET'],
                    $pageGlobal['PAGE_VERSION'],
                    $_SESSION[APP]['LANGUE_ID']
            ));
            $this->assign("monProjet", $monProjet);

            /*
             * Recherche
             */
            $activationRecherche = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
                    $pageGlobal['PAGE_ID'],
                    Pelican::$config['ZONE_TEMPLATE_ID']['RECHERCHE'],
                    $pageGlobal['PAGE_VERSION'],
                    $_SESSION[APP]['LANGUE_ID']
            ));
            $aSiteWS = Pelican_Cache::fetch('Frontend/Citroen/SiteWs', array($_SESSION[APP]['SITE_ID']));
            $aWs = Pelican_Cache::fetch('Frontend/Citroen/WsConfig');
            if (!$aSiteWS[$aWs['CITROEN_SERVICE_GSA']['id']])
                $activationRecherche['ZONE_TITRE2'] = 0;
            $this->assign("activationRecherche", $activationRecherche);

            $recherche = Pelican_Cache::fetch("Frontend/Page/Template", array(
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    Pelican::getPreviewVersion(),
                    Pelican::$config['TEMPLATE_PAGE']['RESULTATS_RECHERCHE']
            ));
            $this->assign("recherche", $recherche);

            /*
             * Shopping Tools
             */
            $shoppingTools = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
                    $pageGlobal['PAGE_ID'],
                    Pelican::$config['ZONE_TEMPLATE_ID']['SHOPPING_TOOLS'],
                    $pageGlobal['PAGE_VERSION'],
                    $_SESSION[APP]['LANGUE_ID']
            ));
            $this->assign("shoppingTools", $shoppingTools);

            $navigationShoppingTools = Pelican_Cache::fetch("Frontend/Page/Navigation", array(
                    $pageGlobal['PAGE_ID'],
                    Pelican::$config['ZONE_TEMPLATE_ID']['SHOPPING_TOOLS'],
                    $pageGlobal['PAGE_VERSION'],
                    $_SESSION[APP]['LANGUE_ID'],
                    false,
                    Pelican::$config["MEDIA_HTTP"]
            ));
            $this->assign("navigationShoppingTools", $navigationShoppingTools);

            $navigationShoppingToolsSize = 0;
            if ($navigationShoppingTools) {
                foreach ($navigationShoppingTools as $temp) {
                    if (sizeof($temp['ssmenu']) >= 2) {
                        $navigationShoppingToolsSize++;
                    }
                }
            }
            $this->assign("navigationShoppingToolsSize", $navigationShoppingToolsSize);

            /*
             * Configuration Expand ligne C et DS
             */
            $expandGamme = Pelican_Cache::fetch("Frontend/Citroen/ExpandGamme", array(
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    )
            );

            $this->assign("expandGamme", $expandGamme);
			
			/*Moteur de Config*/
			$aComboLcdvMtcfg= \Citroen\GammeFinition\VehiculeGamme::getVehiculesGammeMtcfg($this->getCodePays());

            /*
             * Navigation principale
             */
            $navigationSite = Pelican_Cache::fetch(
                    "Frontend/Citroen/NavigationSession", array($_SESSION[APP]['FLAGS_USER']['preferred_product'],
                    $this->isMobile())
            );

            // Injection paramètre origin sur les push perso contenant une URL interne
            if (is_array($navigationSite)) {

                foreach ($navigationSite as $navitemKey => $navitem) {

                    if(!empty($navitem['n1']['media_expand'])) {
                        $navigationSite[$navitemKey]['n1']['MEDIA_PATH_EXPAND'] = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getMediaPath($navitem['n1']['media_expand']);
                    }
                    // Expand véhicule
                    $expandVehiculePush = array('PUSH_OUTILS_MAJEUR', 'PUSH_OUTILS_MINEUR', 'PUSH_CONTENU_ANNEXE');
                    if (is_array($navitem['n2'])) {
                        foreach ($navitem['n2'] as $tabKey => $tab) {

                            if ($tab['n3Actif'] == 1 && is_array($tab['n3']) && count($tab['n3'])) {
                                foreach ($tab['n3'] as $indexVehicule => $vehicule) {

                                    //test des CTA vehicules pour generer
                                    //   --> ajoute le CTA Découvrir si la 
                                    //   --> si une CTA CFG est demandé : generé l'url vers le configurateur
									if(!is_array($vehicule['EXPAND_CTA'])){
										$vehicule['EXPAND_CTA']=array();
										array_unshift($vehicule['EXPAND_CTA'], array(
																'VEHICULE_ID' => $vehicule['VEHICULE_ID'],
																'SITE_ID' => $_SESSION[APP]['SITE_ID'],
																'LANGUE_ID' => $_SESSION[APP]['LANGUE_ID'],
																'VEHICULE_CTA_EXPAND_LABEL' => t('DECOUVRIR'),
																'VEHICULE_CTA_EXPAND_VALUE' => $vehicule['MODE_OUVERTURE_SHOWROOM'],
																'VEHICULE_CTA_EXPAND_URL' => $vehicule['PAGE_CLEAR_URL'],
																'EXPAND_GTM_ACTION'=> 'showroom',
																'VEHICULE_CTA_EXPAND' =>  1

														));
									}else{

                                            if(is_array($vehicule['EXPAND_CTA']) ){
														array_unshift($vehicule['EXPAND_CTA'], array(
																'VEHICULE_ID' => $vehicule['VEHICULE_ID'],
																'SITE_ID' => $_SESSION[APP]['SITE_ID'],
																'LANGUE_ID' => $_SESSION[APP]['LANGUE_ID'],
																'VEHICULE_CTA_EXPAND_LABEL' => t('DECOUVRIR'),
																'VEHICULE_CTA_EXPAND_VALUE' => $vehicule['MODE_OUVERTURE_SHOWROOM'],
																'VEHICULE_CTA_EXPAND_URL' => $vehicule['PAGE_CLEAR_URL'],
																'EXPAND_GTM_ACTION'=> 'showroom',
																'VEHICULE_CTA_EXPAND' =>  1
														));


                                            }

									}
                                    //genere les CTA
                                    if($tab['vehiculeGamme']== Pelican::$config['VEHICULE_GAMME']['GAMME_LIGNE_C']){
                                        $post_expand_gtm_Action ='::Citroen::'.$vehicule['VEHICULE_LABEL'];
                                    }
                                    if($tab['vehiculeGamme']== Pelican::$config['VEHICULE_GAMME']['GAMME_LIGNE_DS']){
                                        $post_expand_gtm_Action ='::DS::'.$vehicule['VEHICULE_LABEL'];
                                    }
                                     $iCompteurCta = 0;
                                    if (isset($vehicule['EXPAND_CTA']) && is_array($vehicule['EXPAND_CTA'])) {
                                        foreach ($vehicule['EXPAND_CTA'] as $keyCta => $expandCta) {

											  if($expandCta['VEHICULE_CTA_EXPAND'] == 1 && $iCompteurCta < 3){

														$aParams['CTA'] = $expandCta;
														$aParams['CTA']['TYPE'] = \Citroen_CTA_Expand::TYPE;
														$aParams['CTA']['EXPAND_GTM_CATEGORY'] = 'ExpandBar::NewCar';

														$aParams['CTA']['POST_EXPAND_GTM_ACTION'] = $post_expand_gtm_Action;
														$aParams['PAGE_VEHICULE'] = $vehicule['VEHICULE_ID'];
														$aParams['VEHICULE_URL'] = $vehicule['PAGE_CLEAR_URL'];
														$aParams['CTA']['BARRE_OUTILS_MODE_OUVERTURE'] = $vehicule['MODE_OUVERTURE_SHOWROOM'];


														if (!isset($expandCta['VEHICULE_CTA_EXPAND_OUTIL'])){
															$aParams['CTA']['ADD_CSS'] = 'buttonTransversal';
														} else {
															$aParams['CTA']['ADD_CSS'] = 'buttonLead';
															}


														$navigationSite[$navitemKey]['n2'][$tabKey]['n3'][$indexVehicule]['EXPAND_CTA'][$keyCta] = Pelican_Request::call('_/Layout_Citroen_CTA/', $aParams);
														$iCompteurCta++;
												}


                                        }
                                    }
									// if(!empty($navigationSite[$navitemKey]['n2'][$tabKey]['n3'][$indexVehicule]['VEHICULE_LCDV6_MTCFG'])){
										// $sLcdv6Mtcfg = substr($navigationSite[$navitemKey]['n2'][$tabKey]['n3'][$indexVehicule]['VEHICULE_LCDV6_MTCFG'], 0, 6);
										// if(is_array($aComboLcdvMtcfg['INFOS'])){
										// if (array_key_exists($navigationSite[$navitemKey]['n2'][$tabKey]['n3'][$indexVehicule]['VEHICULE_LCDV6_CONFIG'], $aComboLcdvMtcfg['INFOS'])) {
											// $navigationSite[$navitemKey]['n2'][$tabKey]['n3'][$indexVehicule]['PRICE_MTCFG'] = $aComboLcdvMtcfg['INFOS'][$navigationSite[$navitemKey]['n2'][$tabKey]['n3'][$indexVehicule]['VEHICULE_LCDV6_CONFIG']]['BASE_PRICE'];
										// }
										// }
									// }
                                }
                            }
                            foreach ($expandVehiculePush as $pushName) {
                                if (is_array($navitem['n2'][$tabKey][$pushName])) {
                                    foreach ($navitem['n2'][$tabKey][$pushName] as $pushKey => $push) {
                                        //$usingPersoData = isset($push['_sync']) && Frontoffice_Zone_Helper::usingPersoData($push, $push['_sync']) ? true : false;

                                        if ($push['_perso'] && !empty($push['PAGE_MULTI_URL']) && $push['PAGE_MULTI_OPTION']==3) {
                                            $navigationSite[$navitemKey]['n2'][$tabKey][$pushName][$pushKey]['PAGE_MULTI_URL'] = Frontoffice_Zone_Helper::setUrlQueryString($push['PAGE_MULTI_URL'], array('origin' => 'ctaperso'));
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // Expand standard
                    if (is_array($navitem['n1']['PUSH'])) {
                        foreach ($navitem['n1']['PUSH'] as $pushKey => $push) {
                            // $usingPersoData = isset($push['_sync']) && Frontoffice_Zone_Helper::usingPersoData($push, $push['_sync']) ? true : false;
                            if ($push['_perso'] && !empty($push['PAGE_MULTI_URL']) && $push['PAGE_MULTI_OPTION']==3) {
                                $navigationSite[$navitemKey]['n1']['PUSH'][$pushKey]['PAGE_MULTI_URL'] = Frontoffice_Zone_Helper::setUrlQueryString($push['PAGE_MULTI_URL'], array('origin' => 'ctaperso'));
                            }
                        }
                    }
                }
            }

            $this->assign("navigationSite", $navigationSite);

            /*
             * CTA Majeur
             */
            $zoneCtaMajeur = Pelican_Cache::fetch("Frontend/Page/ZoneTemplateId", array(
                    $pageGlobal['PAGE_ID'],
                    Pelican::$config['ZONE_TEMPLATE_ID']['CTA_MAJEUR'],
                    Pelican::getPreviewVersion(),
                    $_SESSION[APP]['LANGUE_ID']
            ));
            $ctaMajeur = Citroen_Cache::fetchProfiling($zoneCtaMajeur['ZONE_PERSO'], "Frontend/Citroen/ZoneMulti", array(
                    $pageGlobal['PAGE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    Pelican::getPreviewVersion(),
                    Pelican::$config['ZONE_TEMPLATE_ID']['CTA_MAJEUR'],
                    'CTA',
                    $aParams['AREA_ID'],
                    $aParams['ZONE_ORDER']
            ));

            $this->assign("ctaMajeur", $ctaMajeur);

            /*
             * CTA Mineur
             */
            $zoneCtaMineur = Pelican_Cache::fetch("Frontend/Page/ZoneTemplateId", array(
                    $pageGlobal['PAGE_ID'],
                    Pelican::$config['ZONE_TEMPLATE_ID']['CTA_MAJEUR'],
                    Pelican::getPreviewVersion(),
                    $_SESSION[APP]['LANGUE_ID']
            ));
            $ctaMineur = Citroen_Cache::fetchProfiling($zoneCtaMineur['ZONE_PERSO'], "Frontend/Citroen/ZoneMulti", array(
                    $pageGlobal['PAGE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    Pelican::getPreviewVersion(),
                    Pelican::$config['ZONE_TEMPLATE_ID']['CTA_MINEUR'],
                    'CTA',
                    $aParams['AREA_ID'],
                    $aParams['ZONE_ORDER']
            ));
            $this->assign("ctaMineur", $ctaMineur);
            /*
             * Interstitiel
             */
            $zoneInterstitiel = Pelican_Cache::fetch("Frontend/Page/ZoneTemplateId", array(
                    $pageGlobal['PAGE_ID'],
                    Pelican::$config['ZONE_TEMPLATE_ID']['INTERSTITIEL'],
                    Pelican::getPreviewVersion(),
                    $_SESSION[APP]['LANGUE_ID']
            ));
            if ($_SESSION[APP]['USE_COOKIES'] == true && Backoffice_Cookie_Helper::getCookie('HIDE_INTERSTITIEL') != 1) {
                if ($_SESSION[APP]['USE_COOKIES_ACCEPTED']) {
                    $_SESSION[APP]['HIDE_INTERSTITIEL'] = false;
                } else {
                    $_SESSION[APP]['USE_COOKIES_ACCEPTED'] = true;
                    Backoffice_Cookie_Helper::setCookie('HIDE_INTERSTITIEL', true, time() + $zoneInterstitiel['ZONE_TITRE3']);
                }
            }
            if (!$_SESSION[APP]['HIDE_INTERSTITIEL']) {
                $zoneInterstitiel['MEDIA_PATH3'] = Pelican::$config['MEDIA_HTTP'].Pelican_Media::getMediaPath($zoneInterstitiel['MEDIA_ID3']);
                $zoneInterstitiel['MEDIA_PATH4'] = Pelican::$config['MEDIA_HTTP'].Pelican_Media::getMediaPath($zoneInterstitiel['MEDIA_ID4']);
                $zoneInterstitiel['MEDIA_PATH5'] = Pelican_Media::getMediaPath($zoneInterstitiel['MEDIA_ID5']);
                //video
                $sMediaVideoYoutube = Frontoffice_Video_Helper::getPlayer($zoneInterstitiel['MEDIA_ID6'],'',true, '640', '480');

                $this->assign("sMediaVideoYoutube", $sMediaVideoYoutube,false);
                $this->assign("zoneInterstitiel", $zoneInterstitiel);
				
                $_SESSION[APP]['HIDE_INTERSTITIEL'] = true;
                if ($_SESSION[APP]['USE_COOKIES'] == true) {   //création d'un cookie d'une heure
                    Backoffice_Cookie_Helper::setCookie('HIDE_INTERSTITIEL', true, time() + $zoneInterstitiel['ZONE_TITRE3']);
                }
            }
            /*
             * Fil d'Ariane
             */
            $filAriane = Pelican_Cache::fetch("Frontend/Citroen/FilAriane", array(
                    $aParams['pid'],
                    $_SESSION[APP]['LANGUE_ID']
            ));
            $this->assign("filAriane", $filAriane);
            if (sizeof($filAriane) > 1) {
                $this->assign("iNav1", $filAriane[1]['PAGE_ID']);
            }

            $aConfiguration = Pelican_Cache::fetch("Frontend/Citroen/Configuration", array(
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    "CURRENT"
            ));
            $this->assign("aConfiguration", $aConfiguration);

            //fetch ma selecttion
            (!is_null($user)) ? $iUserId = $user->getId() : null;
            $aVehiculeSelection = SelectionVehicule::getUserSelection($iUserId);
            $this->assign("aVehiculeSelection", $aVehiculeSelection);

            //Indicateur
            //Récupération des informations du site
            $aSite = Pelican_Cache::fetch("Frontend/Site", array(
                    $_SESSION[APP]['SITE_ID']
            ));
            $flagUser = $_SESSION[APP]['FLAGS_USER'];
            $indicateurPro = $flagUser['pro'];
            $indicateurClient = $flagUser['client'];
            $displayLanguettePro = ($aParams['PAGE_LANGUETTE_PRO'] == 1 && $indicateurPro == null && $aSite['SITE_PERSO_ACTIVATION'] == 1) ? true : false;
            $displayLanguetteClient = ($aParams['PAGE_LANGUETTE_CLIENT'] == 1 && $indicateurClient == null  && $aSite['SITE_PERSO_ACTIVATION'] == 1) ? true : false;
			
			
            $this->assign("displayLanguettePro", $displayLanguettePro);
            $this->assign("displayLanguetteClient", $displayLanguetteClient);
            $aPageConnexion = Pelican_Cache::fetch("Frontend/Page/Template", array(
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    Pelican::getPreviewVersion(),
                    Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION']));
            $sURLPageConnexion = Pelican::$config['DOCUMENT_HTTP'].$aPageConnexion['PAGE_CLEAR_URL'];
            $this->assign("sURLPageConnexion", $sURLPageConnexion);

            // Gestion du libelle sous le menu (MObile)            
            if ($this->isMobile()) {
                $LibHomeMobile = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
                        $pageGlobal['PAGE_ID'],
                        Pelican::$config['ZONE_TEMPLATE_ID']['LIBELLE_HOME'],
                        $pageGlobal['PAGE_VERSION'],
                        $_SESSION[APP]['LANGUE_ID']
                ));
                $this->assign("LibHomeMobile", $LibHomeMobile);
            }

            $this->assign("url_home", $_SESSION[APP]["HOME_PAGE_URL"]);
            $this->assign('sCulture', strtolower($_SESSION[APP]['LANGUE_CODE']).'_'.$_SESSION[APP]['CODE_PAYS']);
            if ($this->isMobile()) {
                $this->assign('blangueCode', ($_SESSION[APP]["LANGUE_CODE"] == "FR") ? false : true);
            }
			
			
				if(is_array(Pelican::$config['JAVASCRIPT_FOOTER']['WEB'])){
					array_push(Pelican::$config['JAVASCRIPT_FOOTER']['WEB'],
							Pelican::$config['DESIGN_HTTP']."/assets/js/common/Tranches/stripper.js"
					);
				}	
			
			
            $this->fetch();
        }
    }

    /**
     * Méthode permettant d'accepter les cookies sur le site, l'Ajax modifie des
     * données de session indiquant que l'utilisateur à accepter les cookies et qu'il
     * n'est plus nécessaires d'afficher le bandeau d'information
     */
    public function acceptcookiesAction()
    {
        /* Cookies acceptés */
        $_SESSION[APP]['USE_COOKIES'] = true;
        /* Masquage du bandeau d'informations */
        $_SESSION[APP]['SHOW_COOKIES_LAYER'] = false;
        $_SESSION[APP]['USE_COOKIES_ACCEPTED'] = false;

        Backoffice_Cookie_Helper::setCookie('USING_COOKIES', true, time() + (10 * 365 * 24 * 60 * 60));
    }
	
	 public function getCodePays(){
        $oConnection = Pelican_Db::getInstance();
        $sqlCodePays = $oConnection->queryItem("SELECT SITE_CODE_PAYS FROM #pref#_site_code WHERE SITE_ID = :SITE_ID", array(
            ":SITE_ID" => $_SESSION[APP]['SITE_ID']
        ));
        if(empty($sqlCodePays)){
            
            return false;
        }
       
        return $sqlCodePays; 
     }	
     	
}
