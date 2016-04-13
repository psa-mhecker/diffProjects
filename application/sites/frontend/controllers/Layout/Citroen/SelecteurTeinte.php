<?php

/**
 * Classe d'affichage Front de la tranche Sélecteur de teintes Showroom Accueil
 *
 * @package Layout
 * @subpackage Citroen
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 * @since 02/08/2013
 */
use Citroen\GammeFinition\VehiculeGamme;
use Citroen\Financement;
use Citroen\SelectionVehicule;
use Citroen\GTM;
require_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Showroom.php');

class Layout_Citroen_SelecteurTeinte_Controller extends Pelican_Controller_Front {

    public function indexAction() {
        /* Initialisation des variables */
        $aShowRoomInfo = array();
        $aShowRoomColors = array();
        $iVehiculeId = null;
        $bShowColors = false;


        /* Récupération des informations de page et page_zone à afficher */
        $aParams = Frontoffice_Vehicule_Helper::getShowroomAccueilValues($this->getParams(), $_SESSION[APP]['LANGUE_ID']);
        $aDataParams = $this->getParams();

       
        if (!isset($aDataParams['PAGE_PARENT_ID']) || empty($aDataParams['PAGE_PARENT_ID'])) {
            $this->assign('display', '0');
            $this->fetch();
        } else {
            $aDatasPageParent = Pelican_Cache::fetch("Frontend/Page", array($aDataParams['PAGE_PARENT_ID'], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], 'CURRENT'));
            if ($aDataParams['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_INTERNE'] && $aDatasPageParent['TEMPLATE_PAGE_ID'] != Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']) {
                $this->assign('display', '0');
                $this->fetch();
            } else {
                $this->assign('display', '1');
            }
        }
        if (isset($aDataParams['ZONE_TITRE5']) && !empty($aDataParams['ZONE_TITRE5'])) {
            if ($aDataParams['ZONE_TITRE5'] == 'ROLL') {
                $this->assign('isRollOver', 1);
            }
            if ($aDataParams['ZONE_TITRE5'] == 'POP_IN') {
                $this->assign('isPopin', 1);
            }
            // Par défaut on ouvre les Mentions Légales en Pop In
        } else {
            $this->assign('isPopin', 1);
        }

        if (is_array($aParams) && !empty($aParams) && isset($aParams['ZONE_ATTRIBUT']) && !empty($aParams['ZONE_ATTRIBUT'])) {
            $iVehiculeId = (int) $aParams['ZONE_ATTRIBUT'];
            /* Recherche des informations sur l'ensemble des modèles disponibles
             * pour un site et une langue donné
             */
            
            $a2Result = VehiculeGamme::getShowRoomVehicule(
                            $_SESSION[APP]['SITE_ID'], $aParams['LANGUE_ID'], $iVehiculeId, null, $aDataParams['PAGE_ID']
            );            
            // Gestion de l'ordre des couleurs
            $aListeCouleurs         =   $a2Result[0]['COLORS'];
            $aListeCouleursTemp     =   array();
            $aListeCouleursTemp2    =   array();

            if(is_array($aListeCouleurs)){
                foreach ($aListeCouleurs as $key => $value) {
                    if(isset( $value['PAGE_ZONE_MULTI_ORDER'] ) && !empty( $value['PAGE_ZONE_MULTI_ORDER'] )){
                        $aListeCouleursTemp[$value['PAGE_ZONE_MULTI_ORDER']]  = $value;
                    }else{ // Si pas d'ordre la couleur ce place à la fin
                        $aListeCouleursTemp2[]    =   $value;
                    }            
                }
                ksort($aListeCouleursTemp);        
                $aListeCouleurs   =   array_merge($aListeCouleursTemp, $aListeCouleursTemp2);
            }
            $a2Result[0]['COLORS']  =   $aListeCouleurs;
            $aResult = $a2Result[0];


            if(!isset(Pelican::$config['WS_ACTIVE_LIST_INDEXED']['CITROEN_SERVICE_SIMULFIN']) ||
                    !Pelican::$config['WS_ACTIVE_LIST_INDEXED']['CITROEN_SERVICE_SIMULFIN'] )
            {
                $aResult['VEHICULE']['VEHICULE_USE_FINANCIAL_SIMULATOR'] = false;
            }
            $aFinancement = $a2Result[1];

            // Marquage GTM
            GTM::$dataLayer['vehicleModelBodystyle']      = $aResult['VEHICULE']['LCDV6'];
            GTM::$dataLayer['vehicleModelBodystyleLabel'] = $aResult['VEHICULE']['VEHICULE_LABEL'];

            if (is_array($aResult) && is_array($aResult['VEHICULE'])) {
                $aShowRoomInfo = $aResult['VEHICULE'];
                //Mise en session pour la tranche véhicules neufs
                $_SESSION[APP]['SHOWROOM_VEHICULE_ACTIF'] = $aResult['VEHICULE']['VEHICULE_LCDV6_CONFIG'];
            }
            if (is_array($aResult) && is_array($aResult['COLORS'])) {
                $aShowRoomColors = $aResult['COLORS'];
            }
        }

        /* Initialisation des variables */
        //$aFinancement = array();
        $sPrixHT = '';
        $sPrixTTC = '';
        $bTTCPrice = true;
        $useFinancialSimulator = false;
        $hasCreditPrice = false;
        $hasCashPrice = false;
        $cashPrice = $aResult['VEHICULE']['CASH_PRICE'];
        $sLCVD6 = $aResult['VEHICULE']['LCDV6'];
		$_GET['select_vehicule_lcdv6'] = $sLCVD6;
        $cashPriceLegalMention = $aResult['VEHICULE']['VEHICULE_CASH_PRICE_LEGAL_MENTION'];
        $creditPriceNextRent = $aResult['VEHICULE']['VEHICULE_CREDIT_PRICE_NEXT_RENT'];
        $creditPriceNextRentLM = $aResult['VEHICULE']['VEHICULE_CREDIT_PRICE_NEXT_RENT_LEGAL_MENTION'];
        $creditPriceFirstRent = $aResult['VEHICULE']['VEHICULE_CREDIT_PRICE_FIRST_RENT'];
        $creditPriceFirstRentLM = $aResult['VEHICULE']['VEHICULE_CREDIT_PRICE_FIRST_RENT_LEGAL_MENTION'];

        //Affichage prix à crédit
        $iAffichPrixCredit = Frontoffice_Zone_Helper::getAffichePrixCredit();

        if (isset($aResult['VEHICULE']['VEHICULE_DISPLAY_CASH_PRICE']) && $aResult['VEHICULE']['VEHICULE_DISPLAY_CASH_PRICE'] == 1) {
            $hasCashPrice = true;
        }
        if (isset($aResult['VEHICULE']['VEHICULE_DISPLAY_CREDIT_PRICE']) && $aResult['VEHICULE']['VEHICULE_DISPLAY_CREDIT_PRICE'] == 1 && ($iAffichPrixCredit == 2 || $iAffichPrixCredit == 1 )) {
            $hasCreditPrice = true;
        }

        if (isset($aResult['VEHICULE']['VEHICULE_USE_FINANCIAL_SIMULATOR']) && $aResult['VEHICULE']['VEHICULE_USE_FINANCIAL_SIMULATOR'] == 1) {
            $useFinancialSimulator = true;
        }

        /* Si il n'y a qu'une seule teinte disponible, on affihche pas les vignettes */
        if (is_array($aShowRoomColors) && count($aShowRoomColors) > 1) {
            $bShowColors = true;
        }

        /* Recherche des informations du véhicule */
        $aWSVehiculeInfo = \Citroen\GammeFinition\VehiculeGamme::getVehiculesGamme($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $sLCVD6, 'row');

        /* Vérification si le prix est HT ou TTC */
        if (is_array($aResult['VEHICULE']) && isset($aResult['VEHICULE']['VEHICULE_CASH_PRICE_TYPE']) && $aResult['VEHICULE']['VEHICULE_CASH_PRICE_TYPE'] != 'CASH_PRICE_TTC') {
            $bTTCPrice = false;
        }
        /* Si le prix au comptant a été renseigné dans le BO on l'utilise en priorité
         * sinon on utilise le prix de la version la moins chère
         */
        if (!empty($aResult['VEHICULE']['VEHICULE_CASH_PRICE_TYPE'])) {
            $sPrice = $aResult['VEHICULE']['VEHICULE_CASH_PRICE'];
        } else {
            $sPrice = \Citroen\GammeFinition\VehiculeGamme::getWSVehiculeFirstCashPrice($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $sLCVD6);
        }
        if ($bTTCPrice === true) {
            $sPrixTTC = $sPrice;
        } else {
            $sPrixHT = $sPrice;
        }
        /* Récupération des informations sur le prix à crédit */
        //$aFinancement = \Citroen\GammeFinition\VehiculeGamme::getWSVehiculeCreditPrice($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $sLCVD6, $aWSVehiculeInfo['MODEL_LABEL'], $aWSVehiculeInfo['GAMME'], $sPrixHT, $sPrixTTC);


        /* Nettoyage des balises span des données provenant du WS simulateur financier */
        $clean = array('<span style="font-size:10pt">', '</span>', '<FONT size="9">', '</FONT> ');

        $aFinancement['VEHICULE_CREDIT_PRICE_FIRST_RENT_LEGAL_MENTION'] = str_replace($clean, "", $aFinancement['VEHICULE_CREDIT_PRICE_FIRST_RENT_LEGAL_MENTION']);
        $aFinancement['VEHICULE_CREDIT_PRICE_FIRST_RENT'] = str_replace($clean, "", $aFinancement['VEHICULE_CREDIT_PRICE_FIRST_RENT']);
        $aFinancement['VEHICULE_CREDIT_PRICE_ML_ALL']['VARIABLES']['LegalText']['LABEL'] = str_replace($clean, "", $aFinancement['VEHICULE_CREDIT_PRICE_ML_ALL']['VARIABLES']['LegalText']['LABEL']);

        $oUser = \Citroen\UserProvider::getUser();
        if (!is_null($oUser)) {
            $iUserId = $oUser->getId();
		}
		else {
			$iUserId = null;
		}
		$aSelectionVehicules = SelectionVehicule::getUserSelection($iUserId);
		$bActiveAddToSelection = true;
		if (is_array($aSelectionVehicules) && !empty($aSelectionVehicules)) {
			$aSortedByLcdv6 = array();
			foreach ($aSelectionVehicules as $aSelectionVehicule) {
				if ($aSelectionVehicule['lcdv6_code'] == $aShowRoomInfo['LCDV6']) {
					//vehicule dans la selection
					$bActiveAddToSelection = false;
				}
			}
			if (count($aSelectionVehicules) <= 3) {
				$iOrder = count($aSelectionVehicules);
			}
		} else {
			$iOrder = 0;
		}
		
		$aData =array();

        if($aDataParams["PAGE_ID"] == ""){
            $aDataParams["PAGE_ID"] = $aParams['PAGE_ID'];
        }
        if($aDataParams['LANGUE_ID'] == ""){
            $aDataParams['LANGUE_ID'] = $aParams['LANGUE_ID'];
        }


		/*Récupération des visuels interieur 360°*/
		$visuelsInterieur360 = Pelican_Cache::fetch('Frontend/Citroen/VehiculesVisuelInterieur', 
			array(
					$_SESSION[APP]['SITE_ID'], 
					$_SESSION[APP]['LANGUE_ID'],					
					$iVehiculeId,
					'VISUEL_INTERIEUR'
				));

		if(!empty($visuelsInterieur360['MEDIA_ID'])){
			$urlVisuelsInterieur360['MEDIA_ID'] = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($visuelsInterieur360['MEDIA_ID']), Pelican::$config['MEDIA_FORMAT_ID']['VISUEL_INTERIEUR']);				
		}
		if(!empty($visuelsInterieur360['MEDIA_ID2'])){
			$urlVisuelsInterieur360['MEDIA_ID2'] = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($visuelsInterieur360['MEDIA_ID2']), Pelican::$config['MEDIA_FORMAT_ID']['VISUEL_INTERIEUR']);				
		}
		if(!empty($visuelsInterieur360['MEDIA_ID3'])){
			$urlVisuelsInterieur360['MEDIA_ID3'] = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($visuelsInterieur360['MEDIA_ID3']), Pelican::$config['MEDIA_FORMAT_ID']['VISUEL_INTERIEUR']);				
		}
		if(!empty($visuelsInterieur360['MEDIA_ID4'])){
			$urlVisuelsInterieur360['MEDIA_ID4'] = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($visuelsInterieur360['MEDIA_ID4']), Pelican::$config['MEDIA_FORMAT_ID']['VISUEL_INTERIEUR']);				
		}
		if(!empty($visuelsInterieur360['MEDIA_ID5'])){
			$urlVisuelsInterieur360['MEDIA_ID5'] = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($visuelsInterieur360['MEDIA_ID5']), Pelican::$config['MEDIA_FORMAT_ID']['VISUEL_INTERIEUR']);				
		}
		if(!empty($visuelsInterieur360['MEDIA_ID6'])){
			$urlVisuelsInterieur360['MEDIA_ID6'] = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($visuelsInterieur360['MEDIA_ID6']), Pelican::$config['MEDIA_FORMAT_ID']['VISUEL_INTERIEUR']);				
		}	
		$bIsEsNl=false;
		if(($_SESSION[APP]['CODE_PAYS'] == 'ES' || $_SESSION[APP]['CODE_PAYS'] == 'NL')){
						$bIsEsNl=true;
		}		
        
        // Masquage du prix
        $hidePrice = false;
        if (class_exists('Layout_Citroen_SlideShow_Controller') && Layout_Citroen_SlideShow_Controller::$persoMatch) {
            $hidePrice = $aDataParams['ZONE_TITRE6'] ? true : false;
        }

        /* Assignation des variables SMARTY */
		 $this->assign('bIsEsNl', $bIsEsNl);
			$this->assign('codepays', $_SESSION[APP]['CODE_PAYS']);
        $this->assign('CTAShowroom', $aResult['CTA']);
        $this->assign('cashPriceType',t( $aResult['VEHICULE']['VEHICULE_CASH_PRICE_TYPE']));
        $this->assign('stAPartirDe',t('A_PARTIR_DE'));
        $this->assign('useFinancialSimulator', $useFinancialSimulator);
        $this->assign('hasCashPrice', $hasCashPrice);
        $this->assign('cashPrice', $cashPrice);
        $this->assign('hidePrice', $hidePrice);
        $this->assign('creditPriceNextRent', $creditPriceNextRent);
        $this->assign('creditPriceNextRentLMSelecteurTeinte', $creditPriceNextRentLM);
        $creditPriceFirstRentEscape = str_replace(array("&laquo;", "&raquo;"), "\"", $creditPriceFirstRent);
        $this->assign('creditPriceFirstRent', $creditPriceFirstRentEscape);
        $this->assign('creditPriceFirstRentLM', $creditPriceFirstRentLM);
        $this->assign('cashPriceLegalMentionSelecteurTeinte', $cashPriceLegalMention, false);
        $this->assign('calculatriceFinancement', $aResult['VEHICULE']['VEHICULE_CREDIT_CALC']);
        $this->assign('calcEnabled', Pelican::$config["SITE"]["INFOS"]['CALCULATRICE_FINANCEMENT']);
		$this->assign('urlVisuelsInterieur360', $urlVisuelsInterieur360);
		$this->assign('affichageVisuelsInterieur360', $visuelsInterieur360['AFFICHAGE']);
		
		$wsCreditPriceNextRentValue = str_replace(",", ".", $aFinancement['VEHICULE_CREDIT_PRICE_ML_ALL']['VARIABLES']['PMT']['VALUE']);
		$wsCreditPriceNextRentValue = round($aFinancement['VEHICULE_CREDIT_PRICE_ML_ALL']['VARIABLES']['PMT']['VALUE'], 2, PHP_ROUND_HALF_UP);
		$wsCreditPriceNextRentValue	= str_replace(".", ",", $wsCreditPriceNextRentValue);
		
		if(!empty($wsCreditPriceNextRentValue)){
			 $hasCreditPrice = true;
		}
		
		
		
		$this->assign('hasCreditPrice', $hasCreditPrice);
        $this->assign('wsCreditPriceNextRentValue', $wsCreditPriceNextRentValue);
		
        $this->assign('wsCreditPriceNextRentUnit', $aFinancement['VEHICULE_CREDIT_PRICE_ML_ALL']['VARIABLES']['PMT']['UNIT']);
        $aFinancement['VEHICULE_CREDIT_PRICE_FIRST_RENT'] = str_replace(array("&laquo;", "&raquo;"), "\"", $aFinancement['VEHICULE_CREDIT_PRICE_FIRST_RENT']);
        $this->assign('wsCreditPriceFirstRent', $aFinancement['VEHICULE_CREDIT_PRICE_FIRST_RENT']);
        $this->assign('wsCreditPriceFirstRentLM', str_replace(".", ".<br/>", $aFinancement['VEHICULE_CREDIT_PRICE_FIRST_RENT_LEGAL_MENTION']));
        $this->assign('aDataSimulateurFinancementSelecteurTeinte', $aFinancement['VEHICULE_CREDIT_PRICE_ML_ALL']);

        $ios7 = false;
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone OS 7') !== false)$ios7 = true;

        $u = $_SERVER['HTTP_USER_AGENT'];
        $isIE9  = (bool)preg_match('/msie 9./i', $u );

        if(($this->isMobile() && $ios7) || $isIE9){
            $this->assign('show360', false);
        }else{
            $this->assign('show360', true);
        }
		
		//temporiare 
		$aColors = Frontoffice_Showroom_Helper::getShowroomColor($aDataParams['pid'],$_SESSION[APP]['LANGUE_ID'],Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']);
		if(is_array($aColors) && sizeof($aColors)>0){
			$aDataParams['PRIMARY_COLOR'] = $aColors['PAGE_PRIMARY_COLOR'];
			$aDataParams['SECOND_COLOR']  = $aColors['PAGE_SECOND_COLOR'];
			
		}

        $this->assign('aShowRoomInfo', $aShowRoomInfo);
		
        $this->assign('aShowRoomColors', $aShowRoomColors);
        $this->assign('iOrder', $iOrder);
        $this->assign('bActiveAddToSelection', $bActiveAddToSelection);

        $this->assign('bShowColors', $bShowColors);
        $this->assign('aParams', $aParams);
		$this->assign('aData', $aData);
        $this->assign('aDataParams', $aDataParams);
        $this->fetch();
    }

}
