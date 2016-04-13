<?php

use Citroen\ConcessionFavoris;
use Citroen\GammeFinition\VehiculeGamme;

class Layout_Citroen_PointsDeVente_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $aData = $this->getParams();		      
		
        $this->assign('imgFront', Pelican::$config['MEDIA_HTTP']);

        if ($this->getParam('id')) {
            $this->_forward('getDealerMobile');
        } 
        
        $pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
        ));
        
        $aConfiguration = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
            $pageGlobal['PAGE_ID'],
            Pelican::$config['ZONE_TEMPLATE_ID']['CONFIGURATION'],
            $pageGlobal['PAGE_VERSION'],
            $_SESSION[APP]['LANGUE_ID']
        ));
        
        $this->assign("Advisor", $aConfiguration['ZONE_TITRE18']);
        
        $aCta = Citroen_Cache::fetchProfiling($aData['ZONE_PERSO'], "Frontend/Citroen/ZoneMulti", array(
            $aData['PAGE_ID'],
            $aData['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            $aData['ZONE_TEMPLATE_ID'],
            'CTAFORM',
            $aData['AREA_ID'],
            $aData['ZONE_ORDER']
        ));
            if(is_array($aCta)&& !empty($aCta)){
                foreach($aCta as $key=> $multi){
                    if(isset($multi['OUTIL']) && !empty($multi['OUTIL'])){
                         $aData['CTA'] = $multi['OUTIL'];

                         $aCta[$key]['OUTIL'] = Pelican_Request::call('_/Layout_Citroen_CTA/',$aData);
                    }
                }
            }
        $aOutil = Pelican_Cache::fetch("Frontend/Citroen/VehiculeOutil", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            ($this->isMobile()) ? $aData['ZONE_TOOL2'] : $aData['ZONE_TOOL'],
            ($this->isMobile()) ? "MOBILE" : "WEB"
        ));     
        $outilsPliables =   array();
        
        // Définition du code couleur à utiliser
        $codeCouleur = Frontoffice_Zone_Helper::getCodeCouleurOutil($aData['ZONE_TITRE19'], $aData['ZONE_TITRE3'], $aData['ZONE_TITRE4']);
        $this->assign('codeCouleur', $codeCouleur);
        
        // Définition picto pour chaque outil
        Frontoffice_Zone_Helper::addPictoOutil($aData['ZONE_TITRE19'], $aOutil,'');
        
        if (is_array($aOutil) && !empty($aOutil)) {
            // Récupération liste services par typologie
            $checkedServices = !empty($aData['ZONE_TEXTE']) ? json_decode($aData['ZONE_TEXTE']) : array();
            
            // Récupération association outil/typologie
            $referentielOutils = Pelican_Cache::fetch("Frontend/Citroen/ReferentielOutils", array($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $this->isMobile()));
            if (!is_array($referentielOutils)) {
                $referentielOutils = array();
            }        
            // Construction table association outil/service
            $referentielOutilService = array();
            foreach ($referentielOutils as $typologie => $outils) {
                // Récupération des services correspondant à la typologie courante
                $typologieServices = isset($checkedServices->$typologie) ? $checkedServices->$typologie : array();
                // Ajout des services de la typologie à chaque outil appartenant à la typologie
                foreach ($outils as $outil) {
                    if (isset($referentielOutilService[$outil])) {
                        $referentielOutilService[$outil] = array_unique(array_merge($referentielOutilService[$outil], $typologieServices));
                    } else {
                        $referentielOutilService[$outil] = $typologieServices;
                    }
                }
            }
			
			if(sizeof($referentielOutilService)){
				foreach($referentielOutilService as $iKeyOutils=>$aValueOutils){
					$aOutilsId[]=$iKeyOutils;
				}
			}
			
            unset($typologie, $outils, $outil, $typologieServices);
            
			$aServices =  Pelican_Cache::fetch('Frontend/Citroen/Annuaire/ServicesOrder', array(
						$_SESSION[APP]['SITE_ID'],
						$_SESSION[APP]['LANGUE_ID'],
						"1"
					));	
			if(is_array($aServices) && sizeof($aServices)>0){
				foreach ($aServices as $iKey => $aValue) {
					$aServicesCta[$iKey]= $aValue['code'];
				}
			}
			
            foreach ($aOutil as $key => $OneOutil) {
                // Intégration de la liste des service pour chaque outil
                $OneOutil['services'] = isset($referentielOutilService[$OneOutil['BARRE_OUTILS_ID']]) ? $referentielOutilService[$OneOutil['BARRE_OUTILS_ID']] : null;
               
				if(empty($OneOutil['services'])){
					if(is_array($aOutilsId)){
						if(!in_array($OneOutil['BARRE_OUTILS_ID'], $aOutilsId)){
							 $OneOutil['services']= $aServicesCta;
						}
					}
				} 
				
                $aData['CTA'] = $OneOutil;
				$aData['CTA']['COLOR'] = 'blue';
                if($this->isMobile()){
				    $aData['CTA']['ADD_CSS'] = 'general';
                }

                $aOutil[$key] = Pelican_Request::call('_/Layout_Citroen_CTA/',$aData);

            }
        }
        $aFiltres = explode('|', $aData['ZONE_PARAMETERS']);
       
        $this->assign("aFiltres", $aFiltres);
        # Récupération des points de vente favoris
        $user = \Citroen\UserProvider::getUser();
        $userId = empty($user) ? null : $user->getId();
        if ($userId) {
            # Utilisateur connecté => on se base sur son compte
            $favoris = ConcessionFavoris::getFavorisConcessionsFromDB($userId);
        } else {
            # Utilisateur non connecté => on se base sur le cookie
            $favoris = ConcessionFavoris::getFavorisConcessionsFromSession();
        }
        $favoris = array_filter($favoris);
		
          $aConfigurationPdv = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
            $pageGlobal['PAGE_ID'],
            Pelican::$config['ZONE_TEMPLATE_ID']['RECHERCHE_PDV'],
            $pageGlobal['PAGE_VERSION'],
            $_SESSION[APP]['LANGUE_ID']
        ));
        
        $aData['MEA']=$aConfigurationPdv['ZONE_TITRE6'];	
        $aData['filter_bar'] = !empty($aData['ZONE_TITRE7']) ? $aData['ZONE_TITRE7'] : 'close';
        $aData['brand_activity'] = !empty($aData['ZONE_TITRE8']) && preg_match('#^(|DS|AC)$#', $aData['ZONE_TITRE8']) ? $aData['ZONE_TITRE8'] : '';
        // garder 'brand_activity' pour le reutiliser dans 'getDealerMobile.mobi'
        $_SESSION[APP]['PDV_DATA']['MEA'] = $aData['MEA'];
        $_SESSION[APP]['PDV_DATA']['brand_activity'] = $aData['brand_activity'];
                
        $iPosition = Frontoffice_Zone_Helper::getPositionZone($aData['ZONE_ID'], $aData['ZONE_ORDER'], $aData['AREA_ID']);
        
        // Matrice des picto
        $matriceAffichage = include Pelican::$config['CONFIG_ROOT'].'/dealer-locator-icon.php';
        
        $this->assign("iPosition", $iPosition);
        $this->assign("matriceAffichage", $matriceAffichage);
        $this->assign("aData", $aData);
        $this->assign("aCta", $aCta);
        $this->assign("aOutil", $aOutil);
        $this->assign("hasBookmark", !empty($favoris) ? true : false);



        $this->fetch();
    }

    public function getMapConfigurationAction()
    {
        $aParams = $this->getParams();
        // Zone
        $aZone = Pelican_Cache::fetch("Frontend/Page/ZoneTemplateByPageId", array(
            $aParams['page'],
            $aParams['version'],
            $aParams['ztid'],
            $_SESSION[APP]['LANGUE_ID'],
            $aParams['area'],
            $aParams['order']
        ));

        /**
         * Page globale
         */
        $pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
        ));

        $aFiltres = explode('|', $aZone['ZONE_PARAMETERS']);
        

        /**
         * Configuration
         */
        $aConfiguration = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
            $pageGlobal['PAGE_ID'],
            Pelican::$config['ZONE_TEMPLATE_ID']['CONFIGURATION'],
            $pageGlobal['PAGE_VERSION'],
            $_SESSION[APP]['LANGUE_ID']
        ));



        $bRegroupement = ($aZone['ZONE_CRITERIA_ID2'] == 1) ? true : false;
        $bAutocompletion = ($aZone['ZONE_CRITERIA_ID3'] == 1) ? true : false;

        $sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
        $sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));
        $aParamsCache = array(
            $aConfiguration['ZONE_ATTRIBUT'],
            10,
            $aZone['ZONE_CRITERIA_ID'],
            $aZone['ZONE_ATTRIBUT2'],
            $aZone['ZONE_ATTRIBUT3'],
            $aConfiguration['ZONE_MAP_LATITUDE'],
            $aConfiguration['ZONE_MAP_LONGITUDE'],
            $sPays,
            $sLangue,
            $aZone['ZONE_ATTRIBUT'],
            $bRegroupement,
            $bAutocompletion,
            '',
            $aZone['ZONE_TITRE13']
        );
        $aConfig = Pelican_Cache::fetch("Frontend/Citroen/Annuaire/MapConf", array(
            implode('##', $aParamsCache)
        ));

        
        $aTempServ = array();
        if($aConfig['services'])
        {
            /* commenté par Wei le 20/07/2015
             * pour rendre les données dans un tableau non associatif
            foreach ($aConfig['services'] as $key => $value) {
                if(in_array($value['code'],$aFiltres))
                {
                    $aTempServ[$key] = $aConfig['services'][$key];
                    //cas des filtres qui doivent être blanc
                                    $path = str_replace ("services/", "services/white/", $value['img']);
                    $aTempServ[$key]['service_icon_url'] = Pelican::$config['MEDIA_HTTP'].$path;
                }
            }
            $aConfig['services'] = $aTempServ;
            */
            foreach ($aConfig['services'] as $key => $value) {
                if(in_array($value['code'],$aFiltres))
                {
                    $aTempServ[] = $value;
                }
            }
            $aConfig['services'] = $aTempServ;
        }


        echo json_encode($aConfig);
    }

    public function getMapConfigurationUniqueAction()
    {

        $aParams = $this->getParams();

        // Zone
        $aZone = Pelican_Cache::fetch("Frontend/Page/ZoneTemplateByPageId", array(
            $aParams['page'],
            $aParams['version'],
            $aParams['ztid'],
            $_SESSION[APP]['LANGUE_ID'],
            $aParams['area'],
            $aParams['order']
        ));
    $aFiltres = explode('|', $aZone['ZONE_PARAMETERS']);
        /**
         *  Page globale
         */
        $pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
        ));

        /**
         *  Configuration
         */
        $aConfiguration = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
            $pageGlobal['PAGE_ID'],
            Pelican::$config['ZONE_TEMPLATE_ID']['CONFIGURATION'],
            $pageGlobal['PAGE_VERSION'],
            $_SESSION[APP]['LANGUE_ID']
        ));

        $bRegroupement = ($aZone['ZONE_CRITERIA_ID2'] == 1) ? true : false;
        $bAutocompletion = ($aZone['ZONE_CRITERIA_ID3'] == 1) ? true : false;

        $sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
        $sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));

        $lat = ($aParams['lat'] != '') ? $aParams['lat'] : $aConfiguration['ZONE_MAP_LATITUDE'];
        $lng = ($aParams['long'] != '') ? $aParams['long'] : $aConfiguration['ZONE_MAP_LONGITUDE'];

        $aParamsCache = array(
            $aConfiguration['ZONE_ATTRIBUT'],
            10,
            $aZone['ZONE_CRITERIA_ID'],
            $aZone['ZONE_ATTRIBUT2'],
            $aZone['ZONE_ATTRIBUT3'],
            $lat,
            $lng,
            $sPays,
            $sLangue,
            $aZone['ZONE_ATTRIBUT'],
            $bRegroupement,
            $bAutocompletion,
            $aParams['id'],
            $aZone['ZONE_TITRE13']
        );

        $aConfig = Pelican_Cache::fetch("Frontend/Citroen/Annuaire/MapConf", array(
            implode('##', $aParamsCache)
        ));

        $aTempServ = array();
         if($aConfig['services'])
        {
        foreach ($aConfig['services'] as $key => $value) {
            if(in_array($value['code'],$aFiltres))
            {
                $aTempServ[$key] = $aConfig['services'][$key];
            }
        }
        $aConfig['services'] = $aTempServ;
        }

        echo json_encode($aConfig);
    }

    public function getStoreListAction()
    {
        $aParams = $this->getParams();
        $sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
        $sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));

        /**
         *  Page globale
         */
        $pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
        ));

        /**
         *  Configuration
         */
        $aConfiguration = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
            $pageGlobal['PAGE_ID'],
            Pelican::$config['ZONE_TEMPLATE_ID']['CONFIGURATION'],
            $pageGlobal['PAGE_VERSION'],
            $_SESSION[APP]['LANGUE_ID']
        ));
        $lat = ($aParams['lat'] != '') ? $aParams['lat'] : $aConfiguration['ZONE_MAP_LATITUDE'];
        $lng = ($aParams['long'] != '') ? $aParams['long'] : $aConfiguration['ZONE_MAP_LONGITUDE'];
        // Zone
        $aZone = Pelican_Cache::fetch("Frontend/Page/ZoneTemplateByPageId", array(
            $aParams['page'],
            $aParams['version'],
            $aParams['ztid'],
            $_SESSION[APP]['LANGUE_ID'],
            $aParams['area'],
            $aParams['order']
        ));

        $minpdv = ($aZone['ZONE_CRITERIA_ID'] == 2) ?$aZone['ZONE_ATTRIBUT2'] : "";
        $mindvn = ($aZone['ZONE_CRITERIA_ID'] == 2) ?$aZone['ZONE_ATTRIBUT3'] : "";
        
        $brandActivity = isset($aParams['brandactivity']) ? $aParams['brandactivity'] : '';
        
  $aDealers = Pelican_Cache::fetch("Frontend/Citroen/Annuaire/DealerList", array(
            $lat,
            $lng,
            $sPays,
            $sLangue,
            $aZone['ZONE_ATTRIBUT'],
            $aParams['request'],
            $aZone['ZONE_TITRE13'],
            $minpdv,
            $mindvn,
            Pelican_Cache::getTimeStep(360), // JFO rajoute un purge du cache
            $brandActivity
        ));
        $aDealers = (!empty($aDealers)) ? $aDealers : 'vide';
        header('Content-type: application/json; charset=utf-8');
      $seconds_to_cache = 14400;
      $ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
      header("Expires: $ts");
      header("Pragma: cache");
      header("Cache-Control: max-age=$seconds_to_cache, public");
        echo json_encode($aDealers);
    }

    public function getDealerAction()
    {
        $aData = $this->getParams();

        $sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
        $sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));


    /**
         *  Page globale
         */
        $pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
        ));

        /**
         *  Configuration
         */
        $aConfiguration = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
            $pageGlobal['PAGE_ID'],
            Pelican::$config['ZONE_TEMPLATE_ID']['RECHERCHE_PDV'],
            $pageGlobal['PAGE_VERSION'],
            $_SESSION[APP]['LANGUE_ID']
        ));


     $aDealer = Pelican_Cache::fetch("Frontend/Citroen/Annuaire/Dealer", array(
            $aData['id'],
            $sPays,
            $sLangue,
            Pelican_Cache::getTimeStep(360) // JFO rajoute un purge du cache
        ));

        
        
		Frontoffice_Zone_Helper::setPositionZone($aData['ZONE_ID'], $aData['ZONE_ORDER'], $aData['AREA_ID']);
        
        
        # On détermine si le point de vente est dans les favoris de l'utilisateur
        $user = \Citroen\UserProvider::getUser();
        $userId = empty($user) ? null : $user->getId();
        if ($userId) {
            # Utilisateur connecté => on se base sur son compte
            $favoris = ConcessionFavoris::getFavorisConcessionsFromDB($userId);
        } else {
            # Utilisateur non connecté => on se base sur le cookie
            $favoris = ConcessionFavoris::getFavorisConcessionsFromSession();
        }
        $aDealer['bookmarked'] = isset($favoris) && is_array($favoris) && in_array($aData['id'], $favoris) ? true : false;

        // On détermine si l'administarteur a donné les droits d'affichage de l'email
        // 18/06/2015 : ajouter la condition filtre pour les données dans le array 'contacts'
        if ($aConfiguration['ZONE_TITRE5'] == 2){
            unset($aDealer['email']);
            $this->filtreInContacts($aDealer['contacts'], 'email');
        }
        
        // Masquage du numéro de téléphone
        if ($aConfiguration['ZONE_TITRE9'] == 2){
            unset($aDealer['phone']);
            $this->filtreInContacts($aDealer['contacts'], 'phone');
        }
        
        // Masquage du numéro de fax
        if ($aConfiguration['ZONE_TITRE10'] == 2){
            unset($aDealer['fax']);
            $this->filtreInContacts($aDealer['contacts'], 'fax');
        }
        
        # Ajout du label du bouton
        $aDealer['bookmark_btn_label'] = t('ADD_FAVORITE_BOOKMARKED');
        
        
		$urlAdvisor=$aConfiguration['ZONE_TITRE14'];
		$activerAdvisorService=$aConfiguration['ZONE_TITRE15'];
		
        if($urlAdvisor && $activerAdvisorService==1){
        	
        	$nameAdvisor=$aDealer['nameAdvisor'];
        		
            $aNotes = Pelican_Cache::fetch("Frontend/Citroen/Annuaire/citroenAdvisor", array($urlAdvisor, $nameAdvisor));
  		 	
        	$aDealer['notes_advisor'] = $aNotes;
		}
    	
        
       header('Content-type: application/json; charset=utf-8');
        echo json_encode($aDealer);
    }
    
    private function filtreInContacts(&$contacts,$key_variable){
        if(!is_array($contacts)){
            return;
        }
        
        foreach($contacts as &$contact){
            if(isset($contact['list'])){
                foreach($contact['list'] as &$person){
                    unset($person[$key_variable]);
                }
            }
        }
    }

    public function getDealerMobileAction()
    {
		
		
        $aData = $this->getParams();
        $aOutilMob = Pelican_Cache::fetch("Frontend/Citroen/VehiculeOutil", array(
            $aData['SITE_ID'],
            $aData['LANGUE_ID'],
            ($this->isMobile()) ? $aData['ZONE_TOOL2'] : $aData['ZONE_TOOL'],
            ($this->isMobile()) ? "MOBILE" : "WEB"
        ));
		
			
		$checkedServices = !empty($aData['ZONE_TEXTE']) ? json_decode($aData['ZONE_TEXTE']) : array();
		$referentielOutils = Pelican_Cache::fetch("Frontend/Citroen/ReferentielOutils", array($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $this->isMobile()));
		 
		 if (!is_array($referentielOutils)) {
			$referentielOutils = array();
		}        
		// Construction table association outil/service  
		$referentielOutilService = array();
		foreach ($referentielOutils as $typologie => $outils) {
			// Récupération des services correspondant à la typologie courante
			$typologieServices = isset($checkedServices->$typologie) ? $checkedServices->$typologie : array();
			// Ajout des services de la typologie à chaque outil appartenant à la typologie
			foreach ($outils as $outil) {
				if (isset($referentielOutilService[$outil])) {
					$referentielOutilService[$outil] = array_unique(array_merge($referentielOutilService[$outil], $typologieServices));
				} else {
					$referentielOutilService[$outil] = $typologieServices;
				}
			}
		}
		
		if(sizeof($referentielOutilService)){
			foreach($referentielOutilService as $iKeyOutils=>$aValueOutils){
				$aOutilsId[]=$iKeyOutils;
			}
		}	
		

		$sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
        $sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));

		$aDealer = Pelican_Cache::fetch("Frontend/Citroen/Annuaire/Dealer", array(
            $aData['id'],
            $sPays,
            $sLangue,
            Pelican_Cache::getTimeStep(360) // JFO rajoute un purge du cache
        ));
        
        
        /**
         *  Page globale
         */
        $pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
        ));

        /**
         *  Configuration
         */
        $aConfiguration = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
            $pageGlobal['PAGE_ID'],
            Pelican::$config['ZONE_TEMPLATE_ID']['RECHERCHE_PDV'],
            $pageGlobal['PAGE_VERSION'],
            $_SESSION[APP]['LANGUE_ID']
        ));

        $urlAdvisor=$aConfiguration['ZONE_TITRE14'];
		
        $activerAdvisorService=$aConfiguration['ZONE_TITRE15'];
		
		if($urlAdvisor && $activerAdvisorService==1){
        	
        	$nameAdvisor=$aDealer['nameAdvisor'];
        		
            $aNotes = Pelican_Cache::fetch("Frontend/Citroen/Annuaire/citroenAdvisor", array($urlAdvisor, $nameAdvisor));
  		 	
        	$aDealer['notes_advisor'] = $aNotes;
		}
    	
		$aServices =  Pelican_Cache::fetch('Frontend/Citroen/Annuaire/ServicesOrder', array(
						$_SESSION[APP]['SITE_ID'],
						$_SESSION[APP]['LANGUE_ID'],
						"1"
					 ));
					 
		$aServicesCta =array();			
		if(is_array($aServices) && sizeof($aServices)>0){
				foreach ($aServices as $iKey => $aValue) {
					$aServicesCta[$iKey]= $aValue['code'];
				}
		}	
		
		$aOutil =array();	
        if (is_array($aOutilMob) && !empty($aOutilMob)) {
            if ($this->isMobile()) {
                foreach ($aOutilMob as $key=>$OneOutil) {
					
					$OneOutil['services'] = isset($referentielOutilService[$OneOutil['BARRE_OUTILS_ID']]) ? $referentielOutilService[$OneOutil['BARRE_OUTILS_ID']] : null;
			
					if(empty($OneOutil['services'])){
						if(is_array($aOutilsId)){
								if(!in_array($OneOutil['BARRE_OUTILS_ID'], $aOutilsId)){
									 $OneOutil['services_all']= $aServicesCta;
								}
							}
					} 
					
					if(is_array($aDealer['serviceListCode']) && sizeof($aDealer['serviceListCode'])>0){
						foreach($aDealer['serviceListCode'] as $iServiceCode){
							if(is_array($OneOutil['services'])){
									if(in_array($iServiceCode, $OneOutil['services'])){
										 $OneOutil['services_all'] = $OneOutil['services'];
									}
								
							}
						}
					}
				
					if(!empty($OneOutil['services_all'])){
							$aData['CTA'] = $OneOutil;
							$aOutil[$key] = Pelican_Request::call('_/Layout_Citroen_CTA/',$aData);
					}
		
                }
		$aData['brandactivity'] = $_SESSION[APP]['PDV_DATA']['brand_activity'];	
                $aData['MEA'] = $_SESSION[APP]['PDV_DATA']['MEA'];	
                
                                        $this->assign('aData', $aData);
					$this->assign('aOutilMob', $aOutil);
					$this->assign('aDealer', $aDealer);
					$this->fetch();
						
				
            }
        }
	
    }

    /**
     * Cette méthode retourne les points de vente favoris enregistré dans le
     * compte de l'utilisateur (pour les utilisateurs connectés uniquement)
     */
    public function ajaxPdvBookmarkGetAction()
    {
        # Iinit retour
        $retour = array(
            'loggedin' => false,
            'favoris_db' => array('favoris_vn' => null, 'favoris_av' => null),
            'favoris_cookie' => array('favoris_vn' => null, 'favoris_av' => null),
        );

        # Check si l'utilisateur est connecté
        $user = \Citroen\UserProvider::getUser();
        $userId = empty($user) ? null : $user->getId();

        $sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
        $sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));

        # Récupération des points de vente favoris associé au compte utilisateur
        if (!empty($userId)) {
            $retour['loggedin'] = true;
            $retour['favoris_db'] = ConcessionFavoris::getFavorisConcessionsFromDB($userId);
            $dealerVn = Pelican_Cache::fetch("Frontend/Citroen/Annuaire/Dealer", array(
                $retour['favoris_db']['favoris_vn'],
                $sPays,
                $sLangue,
                Pelican_Cache::getTimeStep(360) // JFO rajoute un purge du cache
            ));
            $retour['favoris_vn_name'] = $dealerVn['name'];
            $dealerAv = Pelican_Cache::fetch("Frontend/Citroen/Annuaire/Dealer", array(
                $retour['favoris_db']['favoris_av'],
                $sPays,
                $sLangue,
                Pelican_Cache::getTimeStep(360) // JFO rajoute un purge du cache
            ));
            $retour['favoris_av_name'] = $dealerAv['name'];
        }

        # Ajout des pdv mémorisés dans les cookies
        $retour['favoris_cookie'] = ConcessionFavoris::getFavorisConcessionsFromSession();

   $dealerVn = Pelican_Cache::fetch("Frontend/Citroen/Annuaire/Dealer", array(
            $retour['favoris_cookie']['favoris_vn'],
            $sPays,
            $sLangue,
            Pelican_Cache::getTimeStep(360) // JFO rajoute un purge du cache
        ));
        $retour['favoris_vn_name'] = $dealerVn['name'];
        $dealerAv = Pelican_Cache::fetch("Frontend/Citroen/Annuaire/Dealer", array(
            $retour['favoris_cookie']['favoris_av'],
            $sPays,
            $sLangue,
            Pelican_Cache::getTimeStep(360) // JFO rajoute un purge du cache
        ));
        $retour['favoris_av_name'] = $dealerAv['name'];


        header('Content-type: application/json; charset=utf-8');
        echo json_encode($retour);
    }

    /**
     * Défini le point de vente passé en paramètre en tant que favori.
     * Si l'utilisateur est connecté, le favori est enregistré dans la base (table user),
     * sinon il est mémorisé dans un cookie
     */
    public function ajaxPdvBookmarkSetAction()
    {
        # Iinit retour
        $retour = array();

        # Récupération paramètre (ID du point de vente)
        $aParams = $this->getParams();
        $pdvId   = isset($aParams['pdvId'])   ? $aParams['pdvId']   : null;
        $pdvType = isset($aParams['pdvType']) ? $aParams['pdvType'] : 'favoris_vn';

        # Check si l'utilisateur est connecté
        $user = \Citroen\UserProvider::getUser();
        $userId = empty($user) ? null : $user->getId();

        # Sauvegarde du favori
        ConcessionFavoris::addToFavs($userId, $pdvId, $pdvType);

        # Changement de label du bouton
        $retour['bookmark_btn_label'] = t('ADD_FAVORITE_BOOKMARKED');



        header('Content-type: application/json; charset=utf-8');
        echo json_encode($retour);
    }
  
}