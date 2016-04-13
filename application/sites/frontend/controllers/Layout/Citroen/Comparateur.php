<?php

/**
 * Classe d'affichage Front du comparateur
 *
 * @package Layout
 * @subpackage Citroen
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 * @since 24/07/2013
 */
use Citroen\GammeFinition\VehiculeGamme;
use Citroen\Financement;
use Citroen\Configurateur;
require_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Showroom.php');



class Layout_Citroen_Comparateur_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $aCompSelect = array();
        /* Récupération des données du bloc */
        $aParams = $this->getParams();
        $_SESSION[APP]['PID'] = $aParams['pid'];
        $isComparateur = true;
        $finitionsSelect = array();
        $_SESSION[APP]['PAGE_GAMME_VEHICULE'] = $aParams['PAGE_GAMME_VEHICULE'];
        
        // Récupération du LCDV6 passé en paramètre dans l'URL, permettant de préselectionner un véhicule dans le comparateur
        $lcdv6Preset = null;
        if (!empty($_GET['Car'])) {
            $lcdv6Preset = $_GET['Car'];
        } elseif (!empty($_GET['lcdv'])) {
            $lcdv6Preset = $_GET['lcdv'];
        }

        $config_comparateur = json_decode($aParams['ZONE_PARAMETERS']);
 

        if(empty($config_comparateur))   $config_comparateur = array();
            $aLcdv6Gamme = $this->getLCDV6Gamme();
            $this->assign('showTypeFilter','0');            
        if($aLcdv6Gamme['GAMME']){
            $aParams['filterComparator'] = $aLcdv6Gamme['GAMME'];
                        
        }elseif(count($config_comparateur)  == 1 ){
            $tmp = $config_comparateur;
            $aParams['filterComparator'] = array_pop($tmp);
            $tmp=null;

        }else{

            $this->assign('showTypeFilter','1');
        
       }
        
        if( in_array($aParams['filterComparator'],$config_comparateur)   || ($aParams['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']
             || $aParams['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_INTERNE']
             || $aParams['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['GABARIT_BLANC'])){

            if ($aParams['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']
             || $aParams['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_INTERNE']
             || $aParams['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['GABARIT_BLANC'])
            {
                $this->assign('showComparator','1');

                //si page show room accueil ou gabarit blanc le pageId prend l'id de la page en cours
                // sinon prendre l'id de la page parente
                
                $aVehicule = VehiculeGamme::getVehiculeByLCDVGamme($aLcdv6Gamme['LCDV6'], $aLcdv6Gamme['GAMME'], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']);
                $aFinitions = Pelican_Cache::fetch("Frontend/Citroen/Finitions", array(
                    $aLcdv6Gamme,
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID']
                ));

                $this->assign('showTypeFilter','0');
                $aParams['filterComparator'] = $aLcdv6Gamme['GAMME'];

                if (is_array($aFinitions)) {
                    foreach ($aFinitions as $aFinition) {
                        if ( $aFinition['GAMME'] == $aParams['filterComparator'] ) {
                            $finitionsSelect[$aFinition['FINITION_CODE']]['FINITION_LABEL'] = $aFinition['FINITION_LABEL'];
                            $finitionsSelect[$aFinition['FINITION_CODE']]['LCDV6'] = $aLcdv6Gamme['LCDV6'];
                        }
                    }
                }

                $this->assign('aLcdv6Gamme', $aLcdv6Gamme);
                $this->assign('aVehicule', $aVehicule);
                
                $isComparateur = false;
            } else {
                //check les variables passées en URL et mettre à jour la session si c'est applicable
                //si on passe des vehicules en parametres d'url ces derniers priment sur le variables en session
                if (isset($aParams['invoker']) && !empty($aParams['invoker'])) {
                    switch ($aParams['invoker']) {
                        case 'CARSELECTOR':

                            Frontoffice_Vehicule_Helper::cleanVehiculeCompInSession(
                                $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], 'CARSELECTOR');


                            Frontoffice_Vehicule_Helper::putVehiculeCompInSession($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $aParams['vehicule_id'][0], $aParams['lcdv6'][0], null, null, 'CARSELECTOR');
                            break;
                        // case 'ANOTHERINVOKER';
                        //     Frontoffice_Vehicule_Helper::cleanVehiculeCompInSession($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], 'ANOTHERINVOKER');
                        //     Frontoffice_Vehicule_Helper::putVehiculeCompInSession($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $aParams['vehicule_id'][0], $aParams['lcdv6'][0]);
                        //     break;
                    }
                }

                $aVehiculeInSession = Frontoffice_Vehicule_Helper::getVehiculeCompInSession(
                    $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], null, null, $aParams['invoker']
                );

                if (is_array($aVehiculeInSession) && count($aVehiculeInSession) > 0) {
                    foreach ($aVehiculeInSession as $key => $vehicule) {
                        print_r($vehicule);
                        if (isset($vehicule['LCDV6']) && !empty($vehicule['LCDV6'])) {
                            $aLcdv6Gamme['LCDV6'] = $vehicule['LCDV6'];
                            $aLcdv6Gamme['GAMME'] = $vehicule['GAMME'];
                            $aFinitions = Pelican_Cache::fetch("Frontend/Citroen/Finitions", array(
                                $aLcdv6Gamme,
                                $_SESSION[APP]['SITE_ID'],
                                $_SESSION[APP]['LANGUE_ID']
                            ));
                            if (is_array($aFinitions)) {
                                foreach ($aFinitions as $aFinition) {
                                    //if ($aFinition['GAMME'] == Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VP']) {
                                        $finitionsSelect[$key][$aFinition['FINITION_CODE']]['FINITION_LABEL'] = $aFinition['FINITION_LABEL'];
                                        $finitionsSelect[$key][$aFinition['FINITION_CODE']]['LCDV6'] = $aLcdv6Gamme['LCDV6'];
                                    //}
                                }
                            }
                        }
                    }
                }
            }

           
            // On récupère les véhicules via les pages Showroom Accueil tout en gardant l'ordre des pages.
            $aVehiculesFromNavigation = self::getVehiculeModeleFromNavigation($aParams['filterComparator'] );


            // On récupère tous les véhicules VP du configurateur
            $aVehiculesFromConfigurateur = VehiculeGamme::getVehiculesGamme($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'],'','lcdv6',$aParams['filterComparator'] );
            
            foreach ($aVehiculesFromConfigurateur as $lcdv6=>$aVehiculeFromConfigurateur) {
                if(array_key_exists($lcdv6, $aVehiculesFromNavigation)){
                    
                        $aVehiculesVpFromConfigurateur[$lcdv6] = $aVehiculeFromConfigurateur['MODEL_LABEL'];
                }
            }
            
            // Si un véhicule du configurateur n'existe pas dans les pages showroom alors on les positionnent à la fin
            if (is_array($aVehiculesVpFromConfigurateur)) {
                foreach ($aVehiculesVpFromConfigurateur as $idVehicule => $labelVehicule) {
                    if (!array_key_exists($idVehicule, $aVehiculesFromNavigation)) {
                        $aVehiculesFromNavigation[$idVehicule] = $labelVehicule;
                    }
                }
            }

            $aCompSelect['LISTE1']['MODELS'] = $aVehiculesFromNavigation;
            $aCompSelect['LISTE2']['MODELS'] = $aVehiculesFromNavigation;
            $aCompSelect['LISTE3']['MODELS'] = $aVehiculesFromNavigation;
            if ($aParams["ZONE_SKIN"] != "ds") {
                $imageCarDefaut = Pelican::$config["IMAGE_FRONT_HTTP"] . '/car/compare.png';
            } else {
                $imageCarDefaut = Pelican::$config["IMAGE_FRONT_HTTP"] . '/car/compare-ds.png';
            }

            //Mentions légales
            if ($aParams['ZONE_TITRE7'] != '') {
                $aMentionsLegales = Pelican_Cache::fetch("Frontend/Page", array(
                    $aParams['ZONE_TITRE7'],
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    Pelican::getPreviewVersion()
                ));
            }

            if ($aParams['MEDIA_ID4'] != '') {
                $sVisuelML = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aParams['MEDIA_ID4']), Pelican::$config['MEDIA_FORMAT_ID']['ACTUALITES_PETIT']);
            }

            $sSharer = Backoffice_Share_Helper::getSharer($aParams['ZONE_CRITERIA_ID'],$aParams['SITE_ID'], $aParams['LANGUE_ID'], Pelican::$config['MODE_SHARER'][0], array('getParams' => $aParams));
            $this->assign("sSharer", $sSharer);
            $this->assign('aMentionsLegales', $aMentionsLegales);
            $this->assign('sVisuelML', $sVisuelML);
           
            $this->assign('aCompSelect', $aCompSelect);
            $this->assign('imageCarDefaut', $imageCarDefaut);
            $this->assign('aVehiculeInSession', $aVehiculeInSession);
            $this->assign('finitionsSelect', $finitionsSelect);
            $this->assign( 'aLcdv6Gamme',$this->getLCDV6Gamme());
            $this->assign('showComparator','1');
        }elseif ( $aParams['TEMPLATE_PAGE_ID'] !=  Pelican::$config['TEMPLATE_PAGE']['COMPARATEUR']){
            $this->assign( 'aLcdv6Gamme',$this->getLCDV6Gamme());
        }

        
        $this->assign('isComparateur', $isComparateur);
         $this->assign('aParams', $aParams);
		  $this->assign('aData', $aData);
        $this->assign('lcdv6Preset', $lcdv6Preset);
        $this->fetch();
    }

    private function getLCDV6Gamme(){
        $aParams = $this->getParams();
        $iPageId = ($aParams['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']||$aParams['TEMPLATE_PAGE_ID'] ==Pelican::$config['TEMPLATE_PAGE']['GABARIT_BLANC']) ? $aParams['PAGE_ID'] : $aParams['PAGE_PARENT_ID'];  
        $aPageInfo = Pelican_Cache::fetch("Frontend/Page", array(
            $iPageId,
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID']
        ));

        //si gabarit blanc récupérer la tranche finition si elle existe sur le gabarit
        if ($aParams['TEMPLATE_PAGE_ID'] ==Pelican::$config['TEMPLATE_PAGE']['GABARIT_BLANC']) {
            $iTemplatePageId = $aPageInfo['TEMPLATE_PAGE_ID'];
            $aTrancheFinition = Pelican_Cache::fetch('Frontend/Page/ZoneInGabaritBlanc', array(
                $aPageInfo['PAGE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                Pelican::$config['AREA']['DYNAMIQUE'],
                Pelican::$config['ZONE']['FINITIONS'],
                $aPageInfo['PAGE_VERSION']
            ));
            if (is_array($aTrancheFinition)&&!empty($aTrancheFinition)) {
                if (!empty($aTrancheFinition[0]['ZONE_ATTRIBUT'])) {
                    $iVehiculeId = $aTrancheFinition[0]['ZONE_ATTRIBUT'];
                }
            }
        }

        //Zone
        $aSelecteurTeinte = Pelican_Cache::fetch("Frontend/Page/ZoneTemplateByPageId", array(
            $aPageInfo['PAGE_ID'],
            $aPageInfo['PAGE_VERSION'],
            Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_ACCUEIL_SELECT_TEINTE'],
            $_SESSION[APP]['LANGUE_ID']
        ));

        if(is_array($aSelecteurTeinte)&&!empty($aSelecteurTeinte)){
            $iVehiculeId = $aSelecteurTeinte['ZONE_ATTRIBUT'];
        }
                   
        $aLcdv6Gamme = VehiculeGamme::getLCDV6Gamme($iVehiculeId, $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']);
        return $aLcdv6Gamme;

    }


    public static function getVehiculeModeleFromNavigation($config_comparateur)
    {
        $aVehiculeModele = array();
      
        $aDatasVehiculesShowroomAccueil = array();
        $aPagesIdVehiculeShowroomAccueil = Pelican_Cache::fetch('Frontend/Citroen/Showroom/PagesShowroomAccueil', array(
                $_SESSION[APP]['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID']
        ));

        if (is_array($aPagesIdVehiculeShowroomAccueil) && !empty($aPagesIdVehiculeShowroomAccueil)) {
            foreach ($aPagesIdVehiculeShowroomAccueil as $key => $aPageIdVehiculeShowroomAccueil) {
                $aPagesIdVehiculeShowroomAccueil[$key]['PAGE_ORDER'] = $key;
            }

            foreach ($aPagesIdVehiculeShowroomAccueil as $key => $aPageIdVehiculeShowroomAccueil) {
                $aDatasVehiculesShowroomAccueil[$key] = VehiculeGamme::getShowRoomVehiculeByShowRoomPage($aPageIdVehiculeShowroomAccueil['PAGE_ID'], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']);
            }

            if (is_array($aDatasVehiculesShowroomAccueil) && !empty($aDatasVehiculesShowroomAccueil)) {
                foreach ($aDatasVehiculesShowroomAccueil as $aDataVehicule) {
                    // On récupere uniquement les véhicules de la gamme VP suite au ticket jira 2744
                    if ($aDataVehicule[0]['VEHICULE']['LCDV6'] && $aDataVehicule[0]['VEHICULE']['VEHICULE_GAMME_CONFIG'] == $config_comparateur) {
                        $aVehiculeModele[$aDataVehicule[0]['VEHICULE']['LCDV6']] = $aDataVehicule[0]['VEHICULE']['VEHICULE_LABEL'];
                    }
                }
            }
        }
        return $aVehiculeModele;
    }

    public function getFinitionsByModelAjaxAction()
    {
        $aData = $this->getParams();
       
        //Récupération des finitions
        $aVehiculeInSession = Frontoffice_Vehicule_Helper::getVehiculeCompInSession(
                $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']);
        $finitionsSelect = array();
        if (isset($_GET['v']) && !empty($_GET['v'])) {
            if (strpos($_GET['v'], '_') !== false) {
                $aCar = explode('_', $_GET['v']);
                $_GET['v'] = $aCar[0];
                $finitionSelected = $aCar[1];
            }

            $aLcdv6Gamme['LCDV6'] = $_GET['v'];
            $aLcdv6Gamme['GAMME'] = $_GET['GAMME'];

            $aFinitions = Pelican_Cache::fetch("Frontend/Citroen/Finitions", array(
                    $aLcdv6Gamme,
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID']
            ));
            if (is_array($aFinitions)) {
                foreach ($aFinitions as $aFinition) {
                  
                        $finitionsSelect[$aFinition['FINITION_CODE']]['FINITION_LABEL'] = $aFinition['FINITION_LABEL'];
                        $finitionsSelect[$aFinition['FINITION_CODE']]['LCDV6'] = $aLcdv6Gamme['LCDV6'];
                  
                }
            }
        }
        $this->assign('finitionsSelect', $finitionsSelect);
        $this->assign('finitionSelected', $finitionSelected);
        $this->assign('aData', $aData);
        $this->fetch();
    }

    public function getEngineByFinitionAjaxAction()
    {
        $aData = $this->getParams();
         

        $engineSelect = array();
        if (isset($_GET['v']) && !empty($_GET['v'])) {
            $aEngineList = VehiculeGamme::getEngineList($_GET['v'], $_GET['lcdv6'], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']);
            if (is_array($aEngineList)) {
                foreach ($aEngineList as $aEngine) {
                    $engineSelect[$aEngine['ENGINE_CODE']]['ENGINE_LABEL'] = str_replace("{##}", " ", $aEngine['ENGINE_LABEL']);
                    $engineSelect[$aEngine['ENGINE_CODE']]['PRICE_DISPLAY'] = $aEngine['PRICE_DISPLAY'];
                }
            }
        }

        // Récupération des informations su le véhicule
        $aVehicule = VehiculeGamme::getVehiculeByLCDVGamme($_GET['lcdv6'], null, $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']);

        $this->assign('engineSelect', $engineSelect);
        $this->assign('finition', $_GET['v']);
        $this->assign('lcdv6', $_GET['lcdv6']);
        $this->assign('aData', $aData);
        $this->assign('aVehicule', $aVehicule);
        $this->fetch();
    }

    public function addToCompareAction()
    {
        include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Vehicule.php');
        $aData = $this->getParams();

        $bReturn = Frontoffice_Vehicule_Helper::putVehiculeCompInSession($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], null, $aData['lcdv6']);
        $aComparateur = Pelican_Cache::fetch("Frontend/Page/Template", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            Pelican::$config['TEMPLATE_PAGE']['COMPARATEUR']
        ));
        $this->getRequest()->addResponseCommand('script', array(
            'value' => 'document.location.href="' . Citroen\URL::parse($aComparateur["PAGE_CLEAR_URL"]) . '";'
        ));
    }

    public function updateComparateurSessionAjaxAction()
    {
        include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Vehicule.php');
        $iSiteId = $_SESSION[APP]['SITE_ID'];
        $iLangueId = $_SESSION[APP]['LANGUE_ID'];
        $aData = $this->getParams();
        Frontoffice_Vehicule_Helper::cleanVehiculeCompInSession($iSiteId, $iLangueId);
        for ($i = 0; $i < 3; $i++) {
            $key = 'select' . $i . 'b';
            if (isset($aData[$key]) && !empty($aData[$key])) {
                $param_parts = explode('#', $aData['select' . $i . 'b']);
                $finition_code = $param_parts[0];
                Frontoffice_Vehicule_Helper::putVehiculeCompInSession($iSiteId, $iLangueId, null, $aData['select' . $i . 'a'], $finition_code, $aData['select' . $i . 'c']);
            }
        }
        print json_encode($_SESSION[APP][$iSiteId][$iLangueId]['COMPARATEUR']);
    }

    public function getOutilsAjaxAction()
    {
        $aParams = $this->getParams();
        //On récupère le code lcdv6 du véhicule
        $sLCVD6 = $aParams['values']['LCDV6'];
   
        //On récupère le code finition
        $finition = $aParams['values']['FINITION'];
        //On récupère le code finition
        $version = $aParams['values']['ENGINE'];
        //On récupère l'identifiant du bloc de selection
        $idBloc = $aParams['values']['ID'];
        
      
        $sGamme = $aParams['values']['GAMME'];

        //On récupère l'identifiant du bloc de selection
        
        $aVehicule = VehiculeGamme::getVehiculeByLCDVGamme($sLCVD6, $sGamme, $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']);
        $urlDecouvrir = Pelican_Cache::fetch('Frontend/Citroen/UrlVehiculeById', array(
            $aVehicule['VEHICULE_ID'],
            Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_ACCUEIL_SELECT_TEINTE'],
            Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL'],
            $_SESSION[APP]['LANGUE_ID'],
            $_SESSION[APP]['SITE_ID']
        ));

        switch ($idBloc) {
            case 'select0b':
            case 'select0c':
                $outils = 'outils0';
                $element = 0;
                break;
            case 'select1b':
            case 'select1c':
                $outils = 'outils1';
                $element = 1;
                break;
            case 'select2b':
            case 'select2c':
                $outils = 'outils2';
                $element = 2;
                break;
        }

        /* Récupération du détail des outils */
        $aTools = Pelican_Cache::fetch('Frontend/Citroen/VehiculeOutil', array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            '7|8|9',
            'WEB'
        ));
         

        $btnAjout = $_GET['values']['btnAjout'];
        $this->assign("btnAjout", $btnAjout);

        $this->assign("element", $element);
  
        $aInfosVehicule = self::getInfosVersionVehicule($version, $finition, $sLCVD6);
      
      	$aVehicule['VERSION'] = $aInfosVehicule['LCDV_CODE'];
		$aVehicule['GRADES'] = $finition;

		$aVehicule['LCDV6'] = $sLCVD6;
		$aVehicule['GAMME'] = $sGamme;
		

	 
        $aData= $aParams;
        $aData['CTA'] = array(
            'BARRE_OUTILS_URL_WEB' => '##URL_CONFIGURATEUR##',
            'BARRE_OUTILS_MODE_OUVERTURE' => 2,
            'BARRE_OUTILS_TITRE' => t('CONFIGURER'),
            'COLOR'=> 'orange',
			'NO_SPAN'=>true
        );
        $aData['vehicule'] = $aVehicule;
        $configurateur = Pelican_Request::call('_/Layout_Citroen_CTA/',$aData);

        $this->assign("configurateur", $configurateur);

        
        $this->assign("urlDecouvrir", $urlDecouvrir);
        $this->assign("aTools", $aTools);
        $this->assign("aParams",$aParams);
        $this->fetch();
        $this->getRequest()->addResponseCommand('assign', array(
            'id' => $outils,
            'attr' => 'innerHTML',
            'value' => $this->getResponse()
        ));
        if ($btnAjout) {
            $js = "
                $('.confirmAdd').unbind('click');
                $('.confirmAdd').bind('click', function(e) {
                    e.preventDefault();
                    indiceConfigurateur = $(this).data('value');
                    // selection a partir de la motorisation
                    selectionConfigurateur = $('#select'+indiceConfigurateur+'c').parent('td').find('ul li a.on').data('value');
                    if (!selectionConfigurateur) {
                        // selection a partir de la finition
                        selectionConfigurateur = $('#select'+indiceConfigurateur+'b').parent('td').find('ul li a.on').data('value');
                    }
                    selectionConfigurateur = selectionConfigurateur.replace(/\#/g,'|');
                    popin = $('#layerconfirmadd');
                    html = popin.html();
                    promptPop(html);
                });
                $('.confirmMAJ').unbind('click');
                $('.confirmMAJ').bind('click', function(e) {
                    e.preventDefault();
                    indiceConfigurateur = $(this).data('value');
                    // selection a partir de la motorisation
                    selectionConfigurateur = $('#select'+indiceConfigurateur+'c').parent('td').find('ul li a.on').data('value');
                    if (!selectionConfigurateur) {
                        // selection a partir de la finition
                        selectionConfigurateur = $('#select'+indiceConfigurateur+'b').parent('td').find('ul li a.on').data('value');
                    }
                    selectionConfigurateur = selectionConfigurateur.replace(/\#/g,'|');
                    popin = $('#layerconfirmmaj');
                    html = popin.html();
                    promptPop(html);
                });
            ";
            $this->getRequest()->addResponseCommand('script', array('value' => $js));
        }
    }

    // obsolete 
    public function getImageEtPrixVehiculeByFinitionAjaxAction()
    {
        include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Zone.php');

     
        print_r($aParams);
        //On récupère le code lcdv6 du véhicule
        $sLCVD6 = $_GET['values']['LCDV6'];
        //On récupère le code finition
        $finition = $_GET['values']['FINITION'];
       // $sGamme = Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VP'];
//On récupère le code finition
        $version = $_GET['values']['ENGINE'];
        //On récupère l'identifiant du bloc de selection
        $idBloc = $_GET['values']['ID'];
        $gamme = $_GET['values']['GAMME'];
        $aVehicule = VehiculeGamme::getVehiculeByLCDVGamme($sLCVD6,$gamme, $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']);
        //$aVehicule = VehiculeGamme::getWSVehiculeFirstFinitionVersion( $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'],$sLCVD6 );
        switch ($idBloc) {
            case 'select0b':
                $car = 'car0';
                break;
            case 'select1b':
                $car = 'car1';
                break;
            case 'select2b':
                $car = 'car2';
                break;
        }
     	
        $iAffichPrixCredit = Frontoffice_Zone_Helper::getAffichePrixCredit();
		if (isset($aVehicule['VEHICULE_DISPLAY_CREDIT_PRICE']) && $aVehicule['VEHICULE_DISPLAY_CREDIT_PRICE'] == 1 && ($iAffichPrixCredit == 2 || $iAffichPrixCredit == 1 )) {
			$hasCreditPrice = true;
		}
        $aData = self::getInfosVersionVehicule($version,$finition, $sLCVD6);//, $aVehicule['VEHICULE_CASH_PRICE_TYPE']);
 
    
        $urlImage = $aData['IMAGE'];
        $prixComptant = $aData['PRIMARY_DISPLAY_PRICE'];
        $mLComptant = $aVehicule['VEHICULE_CASH_PRICE_LEGAL_MENTION'];
        $priceType = $aVehicule['VEHICULE_CASH_PRICE_TYPE'];

        if ($iAffichPrixCredit == 2 || ($iAffichPrixCredit == 1 && ($_GET['values']['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL'] || $_GET['values']['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_INTERNE']))) {
            $prixCredit = $aData['CREDIT']['PRIX'];
            $mLCredit = $aData['MENTIONS_LEGALES']['HTML'];
            if (!$prixCredit && !$mLCredit) {
                if ($aVehicule['VEHICULE_CREDIT_PRICE_NEXT_RENT']) {
                    $prixCredit = $aVehicule['VEHICULE_CREDIT_PRICE_NEXT_RENT'];
                    $mLCredit = $aVehicule['VEHICULE_CREDIT_PRICE_NEXT_RENT_LEGAL_MENTION'];
                }
            }
        }
		
		
        
        //LCDV6
		$this->assign('hasCreditPrice', $hasCreditPrice);
        $imageCarDefaut = Pelican::$config["IMAGE_FRONT_HTTP"] . '/car/compare.png';
        $this->assign('imageCarDefaut', $imageCarDefaut);
        $this->assign("urlImage", $urlImage);
        $this->assign("prixComptant", $prixComptant);
        $this->assign("prixCredit", $prixCredit);
        $this->assign("mLCredit", $mLCredit);
        $this->assign("mLComptant", $mLComptant);
        $this->assign("priceType", $priceType);
        $this->assign("idBloc", $idBloc);
        $this->fetch();
        $this->getRequest()->addResponseCommand('assign', array(
            'id' => $car,
            'attr' => 'innerHTML',
            'value' => $this->getResponse()
        ));
        /* $this->getRequest()->addResponseCommand('script', array(
          'value' => "$('.tooltip,.texttip').each(tooltip.build);"
          )); */
    }

    public function getImageEtPrixVehiculeByVersionAjaxAction()
    {
        include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Zone.php');

        //On récupère le code lcdv6 du véhicule
        $sLCVD6 = $_GET['values']['LCDV6'];
        //On récupère le code finition
        $finition = $_GET['values']['FINITION'];
        //On récupère le code finition
        $version = $_GET['values']['ENGINE'];
        //On récupère l'identifiant du bloc de selection
        $idBloc = $_GET['values']['ID'];
          $gamme = $_GET['values']['GAMME'];
        $aVehicule = VehiculeGamme::getVehiculeByLCDVGamme($sLCVD6, $gamme, $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']);
        switch ($idBloc) {
            case 'select0c': case 'select0b':
                $car = 'car0';
                break;
            case 'select1c':case 'select1b':
                $car = 'car1';
                break;
            case 'select2c':case 'select2b':
                $car = 'car2';
                break;
        }
        $iAffichPrixCredit = Frontoffice_Zone_Helper::getAffichePrixCredit();
		if (isset($aVehicule['VEHICULE_DISPLAY_CREDIT_PRICE']) && $aVehicule['VEHICULE_DISPLAY_CREDIT_PRICE'] == 1 && ($iAffichPrixCredit == 2 || $iAffichPrixCredit == 1 )) {
			$hasCreditPrice = true;
		}
        $aData = self::getInfosVersionVehicule($version, $finition, $sLCVD6);
        $urlImage = $aData['IMAGE'];
        $prixComptant = $aData['PRICE_DISPLAY'];
        $mLComptant = $aVehicule['VEHICULE_CASH_PRICE_LEGAL_MENTION'];
        $priceType = $aVehicule['VEHICULE_CASH_PRICE_TYPE'];
        if ($iAffichPrixCredit == 2 || ($iAffichPrixCredit == 1 && ($_GET['values']['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL'] || $_GET['values']['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_INTERNE']))) {
            $prixCredit = $aData['CREDIT']['PRIX'];
            $mLCredit = $aData['MENTIONS_LEGALES']['HTML'];
            if (!$prixCredit && !$mLCredit) {
                if( $aVehicule['VEHICULE_CREDIT_PRICE_NEXT_RENT']) {
                    $prixCredit = $aVehicule['VEHICULE_CREDIT_PRICE_NEXT_RENT'];
                    $mLCredit = $aVehicule['VEHICULE_CREDIT_PRICE_NEXT_RENT_LEGAL_MENTION'];
                }
            }
        }
		$this->assign('hasCreditPrice', $hasCreditPrice);
        $imageCarDefaut = Pelican::$config["IMAGE_FRONT_HTTP"] . '/car/compare.png';
        $this->assign('imageCarDefaut', $imageCarDefaut);
        $this->assign("urlImage", $urlImage);
        $this->assign("prixComptant", $prixComptant);
        $this->assign("prixCredit", $prixCredit);
        $this->assign("mLCredit", $mLCredit);
        $this->assign("mLComptant", $mLComptant);
        $this->assign("priceType", $priceType);
        $this->assign("idBloc", $idBloc);
        $this->fetch();
        if (is_array($aData) && !empty($aData)) {
            $this->getRequest()->addResponseCommand('assign', array(
                'id' => $car,
                'attr' => 'innerHTML',
                'value' => $this->getResponse()
            ));
            /* $this->getRequest()->addResponseCommand('script', array(
              'value' => "$('.tooltip,.texttip').each(tooltip.build);"
              )); */
        }
    }

    public function getEquipementsCaracteristiquesAction()
    {
        $aData = $this->getParams();
        
        $aPageShowroomColor = Frontoffice_Showroom_Helper::getShowroomColor($_SESSION[APP]['PID'],$_SESSION[APP]['LANGUE_ID'],Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']);

        if(!empty($aPageShowroomColor['PAGE_PRIMARY_COLOR']) &&  !empty($aPageShowroomColor['PAGE_SECOND_COLOR'])){
            $aData['PRIMARY_COLOR'] = $aPageShowroomColor['PAGE_PRIMARY_COLOR'];
            $aData['SECOND_COLOR']  = $aPageShowroomColor['PAGE_SECOND_COLOR'];
        }
        
        $this->assign("aData", $aData);

        // Mise en session de la comparaison du comparateur de la page Mon projet
        if ($aData['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION']) {
            unset($_SESSION[APP]['COMPARATEUR_PERSO']);
            for ($i = 1; $i<=3; $i++) {
                if ($aData['model_'.$i] != '0') {
                    $_SESSION[APP]['COMPARATEUR_PERSO'][$i-1]['LCDV6'] = $aData['model_'.$i];
                    if ($aData['finition_'.$i] != '0') {
                        $_SESSION[APP]['COMPARATEUR_PERSO'][$i-1]['FINITION_CODE'] = $aData['finition_'.$i];
                        if ($aData['engine_'.$i] != '0') {
                            $_SESSION[APP]['COMPARATEUR_PERSO'][$i-1]['ENGINE_CODE'] = $aData['engine_'.$i];
                        }
                    }
                }
            }
        }
        
        for ($i = 1; $i < 4; $i++) {
            if ($_GET['values']['model_' . $i] != '0' && $_GET['values']['model_' . $i] != '' && $_GET['values']['model_' . $i] != 'undefined' && $_GET['values']['finition_' . $i] != '0' && $_GET['values']['finition_' . $i] != 'undefined' && $_GET['values']['finition_' . $i] != '') {
                $aResult = array();
                $aLcdv6Gamme['LCDV6'] = $_GET['values']['model_' . $i];
                //$aLcdv6Gamme['GAMME'] = VehiculeGamme::getVehiculesGamme( $_SESSION[APP]['SITE_ID'],$_SESSION[APP]['LANGUE_ID'],$_GET['values']['model_' . $i]);
                $aLcdv6Gamme['GAMME'] = 'VP';
                //On récupère le code lcdv6 du véhicule
                $aResult = VehiculeGamme::getEquipementDispo($aLcdv6Gamme, $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'],$_SESSION[APP]['PAGE_GAMME_VEHICULE']);
                
                $aTempEquipements['EQUIPEMENTS_' . $i] = $aResult[$_GET['values']['finition_' . $i]];
                if ($_GET['values']['engine_' . $i] != '' && $_GET['values']['engine_' . $i] != 'undefined' && $_GET['values']['engine_' . $i] != '0') {
                    $aTempCaracteristiques['CARACTERISTIQUES_' . $i] = VehiculeGamme::getCaracteristiques($_GET['values']['engine_' . $i], $_GET['values']['model_' . $i], 'VP', $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'],$_GET['values']['finition_' . $i]);
                }
            }
        }
        $aEquipements = array();
        for ($i = 1; $i < 4; $i++) {
            if (is_array($aTempEquipements['EQUIPEMENTS_' . $i]) && count($aTempEquipements['EQUIPEMENTS_' . $i]) > 0) {
                foreach ($aTempEquipements['EQUIPEMENTS_' . $i] as $category) {
                    if (is_array($category['EQUIPEMENTS']) && count($category['EQUIPEMENTS']) > 0) {
                        foreach ($category['EQUIPEMENTS'] as $equipement) {
                            if ($equipement['DISPONIBILITY'] != 'None' && $equipement['DISPONIBILITY'] != '' && $equipement['DISPONIBILITY'] != '-') {
                                for ($j = 1; $j < 4; $j++) {
                                    if (!isset($aEquipements[$category['LABEL']][$equipement['LABEL']]['vehicule_' . $j])) {

                                        $aEquipements[$category['LABEL']][$equipement['LABEL']]['vehicule_' . $j] = \Pelican::$config["IMAGE_FRONT_HTTP"]
                                                                                . "/" .
                                            Pelican::$config['EQUIPEMENT_PICTO_DISPO']['-'];
                                    }
                                }
                                $aEquipements[$category['LABEL']][$equipement['LABEL']]['vehicule_' . $i] = $equipement['DISPONIBILITY'];
                            }
                        }
                    }
                }
            }
        }
        $aCaracteristiques = array();
        for ($i = 1; $i < 4; $i++) {
            if (is_array($aTempCaracteristiques['CARACTERISTIQUES_' . $i]) && count($aTempCaracteristiques['CARACTERISTIQUES_' . $i]) > 0) {
                foreach ($aTempCaracteristiques['CARACTERISTIQUES_' . $i] as $category) {
                    if (is_array($category['CARACTERISTIQUES']) && count($category['CARACTERISTIQUES']) > 0) {
                        foreach ($category['CARACTERISTIQUES'] as $caracteristiques) {
                            if ($caracteristiques['VALUE'] != 'None' && $caracteristiques['VALUE'] != '' && $caracteristiques['VALUE'] != '-') {
                                for ($j = 1; $j < 4; $j++) {
                                    if (!isset($aCaracteristiques[$category['LABEL']][$caracteristiques['NAME']]['vehicule_' . $j])) {
                                        $aCaracteristiques[$category['LABEL']][$caracteristiques['NAME']]['vehicule_' . $j] = '-';
                                    }
                                }
                                $aCaracteristiques[$category['LABEL']][$caracteristiques['NAME']]['vehicule_' . $i] = $caracteristiques['VALUE'];
                            }
                        }
                    }
                }
            }
        }
        if (empty($aCaracteristiques) && $_GET['values']['engine_1'] == '0' && $_GET['values']['engine_2'] == '0' && $_GET['values']['engine_2'] == '0') {
            $aCategory = VehiculeGamme::getCategoryCaracteristiques(null, $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']);
            $this->assign("aCategory", $aCategory);
        }
        if ($_GET['values']['engine_1'] == '0' && $_GET['values']['engine_2'] == '0' && $_GET['values']['engine_2'] == '0') {
            $this->assign("classCaracteristiques", 'prevent');
        }
        $this->assign("aEquipements", $aEquipements);
        $this->assign("aCaracteristiques", $aCaracteristiques);
        $this->fetch();
        $this->getRequest()->addResponseCommand('assign', array(
            'id' => "caracteristiques-equipements",
            'attr' => 'innerHTML',
            'value' => $this->getResponse()
        ));
        $this->getRequest()->addResponseCommand('script', array(
            'value' => "$('.ECFolder').each(folder.build);$('.overall').each(overall.build);"
        ));
        if ($_GET['values']['engine_1'] != '0' || $_GET['values']['engine_2'] != '0' || $_GET['values']['engine_2'] != '0') {
            $this->getRequest()->addResponseCommand('script', array(
                'value' => "$('.disclaimer').css('display','none');"
            ));
        } else {
            if (!empty($aCategory)) {
                $this->getRequest()->addResponseCommand('script', array(
                    'value' => "$('.disclaimer').css('display','block');"
                ));
            }
        }
    }

    public static function getInfosFinitionVehicule($idFinition, $lcdv6, $VEHICULE_CASH_PRICE_TYPE="")
    {
    	   $sGamme = Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VP'];

        $aLcdv6Gamme['LCDV6'] = $lcdv6;
        $aLcdv6Gamme['GAMME'] =$sGamme;
        $aFinitions = Pelican_Cache::fetch("Frontend/Citroen/Finitions", array(
            $aLcdv6Gamme,
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID']
        ));
        
        if (is_array($aFinitions)) {
            $sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
            $sCodePays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));
            $sCodeLangue = $sLangue . "-" . strtolower($sCodePays);
            foreach ($aFinitions as $aFinition) {
                if ($aFinition['GAMME'] == Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VP'] && $aFinition['FINITION_CODE'] == $idFinition) {
                    $sPrixTTC = 0;
                    $sPrixHT = 0;

                    if(!$aFinition['VEHICULE_CASH_PRICE_TYPE'])
                    {
                        $aFinition['VEHICULE_CASH_PRICE_TYPE'] = $VEHICULE_CASH_PRICE_TYPE ;
                    }

                    if ($aFinition['VEHICULE_CASH_PRICE_TYPE'] != 'CASH_PRICE_TTC') {
                        $sPrixHT = $aFinitions['PRIMARY_DISPLAY_PRICE'];
                    } else {
                        $sPrixTTC = $aFinition['PRIMARY_DISPLAY_PRICE'];
                    }
                    $aFinition['CREDIT'] = Financement::getCreditPrice($sCodePays, $sCodeLangue, Pelican::$config['DEVISE'][trim($sDevise)], $aFinition['V3D_LCDV'], $aFinition['FINITION_LABEL'], '', $aFinition['GAMME'], $sPrixHT, $sPrixTTC);
                    $aFinition['MENTIONS_LEGALES'] = Financement::getCreditPriceML($sCodePays, $sCodeLangue, Pelican::$config['DEVISE'][trim($sDevise)], $aFinition['V3D_LCDV'], $aFinition['MODEL_LABEL'], '', $aFinition['GAMME'], $sPrixHT, $sPrixTTC);

                    return $aFinition;
                }
            }
        }
        return false;
    }

    public static function getInfosVersionVehicule($version, $idFinition, $lcdv6)
    {
        $aVersion = Pelican_Cache::fetch("Frontend/Citroen/VersionsComparateur", array(
            $lcdv6,
            $idFinition,
            null,
            $version,
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID']
        ));
        if (is_array($aVersion)) {
            $sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
            $sCodePays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));
            $sCodeLangue = $sLangue . "-" . strtolower($sCodePays);
            $sPrixTTC = 0;
            $sPrixHT = 0;
            if ($aVersion['VEHICULE_CASH_PRICE_TYPE'] != 'CASH_PRICE_TTC') {
                $sPrixHT = $aVersion['PRICE_NUMERIC'];
            } else {
                $sPrixTTC = $aVersion['PRICE_NUMERIC'];
            }
            $aVersion['CREDIT'] = Financement::getCreditPrice($sCodePays, $sCodeLangue, Pelican::$config['DEVISE'][trim($sDevise)], $aVersion['LCDV_CODE'], $aVersion['MODEL_LABEL'], '', $aVersion['GAMME'], $sPrixHT, $sPrixTTC);
            $aVersion['MENTIONS_LEGALES'] = Financement::getCreditPriceML($sCodePays, $sCodeLangue, Pelican::$config['DEVISE'][trim($sDevise)], $aVersion['LCDV_CODE'], $aVersion['MODEL_LABEL'], '', $aVersion['GAMME'], $sPrixHT, $sPrixTTC);
            return $aVersion;
        }

        return false;
    }
}