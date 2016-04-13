<?php

/**
 * Classe d'affichage Front de la tranche Récapitulatif modèle Showroom Accueil
 *
 * @package Layout
 * @subpackage Citroen
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 * @since 12/08/2013
 */
use Citroen\GammeFinition\VehiculeGamme;
use Citroen\Gamme;
use Citroen\SelectionVehicule;
use Citroen\Configurateur;
require_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Showroom.php');

class Layout_Citroen_RecapitulatifModele_Controller extends Pelican_Controller_Front {

    public function indexAction() {

        /* Initialisation des variables */
        $aShowRoomInfo = array();
        $iVehiculeId = null;
        
        $aDataParams = $this->getParams();
        $this->assign('mentionType', $aDataParams['ZONE_TITRE5']);
        
        if (isset($aDataParams['ZONE_TITRE5']) && !empty($aDataParams['ZONE_TITRE5'])) {
               
            if ($aDataParams['ZONE_TITRE5'] == "ROLL") {
                $this->assign('isRollOverRecap', 1);
            }
            if ($aDataParams['ZONE_TITRE5'] == "POP_IN") {
                $this->assign('isPopinRecap', 1);
            }
        } else {
            $this->assign('isPopinRecap', 1);
        }
        if (!isset(Pelican::$config['WS_ACTIVE_LIST_INDEXED']['CITROEN_SERVICE_SIMULFIN']) ||
                !Pelican::$config['WS_ACTIVE_LIST_INDEXED']['CITROEN_SERVICE_SIMULFIN']) {
            $aResult['VEHICULE']['VEHICULE_USE_FINANCIAL_SIMULATOR'] = false;
        }
        
        /* Récupération des informations de page et page_zone à afficher */
        $aParams = Frontoffice_Vehicule_Helper::getShowroomAccueilValues($this->getParams(), $_SESSION[APP]['LANGUE_ID']);
        

        $aConfiguration = Pelican_Cache::fetch("Frontend/Citroen/Configuration", array(
                $aDataParams['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                Pelican::getPreviewVersion()
        ));
        
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

        if (is_array($aParams) && isset($aParams['ZONE_ATTRIBUT']) && !empty($aParams['ZONE_ATTRIBUT'])) {
            $iVehiculeId = (int) $aParams['ZONE_ATTRIBUT'];
            /* Recherche des informations sur l'ensemble des modèles disponibles
             * pour un site et une langue donné
             */
            $aTemp = VehiculeGamme::getShowRoomVehicule(
                            $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $iVehiculeId
            );
            if ($aTemp[0]) {
                $aResult = $aTemp[0];
                $aFinancement = $aTemp[1];
            }
            if (is_array($aResult) && is_array($aResult['VEHICULE'])) {
                $aShowRoomInfo = $aResult['VEHICULE'];
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



        /* Récupération du détail des outils */
        $aTools = Pelican_Cache::fetch('Frontend/Citroen/VehiculeOutil', array(
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    $aParams['ZONE_TOOL'],
                    'WEB'
        ));
        if (is_array($aTools) && !empty($aTools)) {
            foreach ($aTools as $key => $OneOutil) {
                
                $aDataParams['CTA'] = $OneOutil;
                $aDataParams['CTA']['NO_SPAN'] = 'true';
                $aTools[$key] = Pelican_Request::call('_/Layout_Citroen_CTA/',$aDataParams);
                
               
            }
        }


        /*
         * Récupération de l'image utilisée pour les points forts à réutiliser
         * dans le cadre de ce bloc
         */
        $iZoneTemplateId = Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_ACCUEIL_POINTS_FORTS'];
        $aKeyPointsZone = \Pelican_Cache::fetch('Frontend/Page/ZoneTemplate', array(
                    $aParams['PAGE_ID'],
                    $iZoneTemplateId,
                    $aParams['PAGE_VERSION'],
                    $_SESSION[APP]['LANGUE_ID']
        ));

        /* Récupération du visuel du point Fort */
        $aKeyPointsMedia = Pelican_Cache::fetch('Media/Detail', array(
                    $aKeyPointsZone['MEDIA_ID']
        ));

        /* Récupération des informations de la tranche configuration dans global */
  
        if (isset($sLCVD6) && !empty($sLCVD6)) {

            $urlConfigurateur = Configurateur::getConfigurateurUrl($aShowRoomInfo,$aConfiguration);

            $this->assign("urlConfigurateur", $urlConfigurateur);
        }

        $oUser = \Citroen\UserProvider::getUser();
        if (!is_null($oUser)) {
            $iUserId = $oUser->getId();
        } else {
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
                

		if($aDataParams["PAGE_ID"] == ""){
			$aDataParams["PAGE_ID"] = $aParams['PAGE_ID'];
		}
		if($aDataParams['LANGUE_ID'] == ""){
			$aDataParams['LANGUE_ID'] = $aParams['LANGUE_ID'];
		}

		
		

        $this->assign('bActiveAddToSelection', $bActiveAddToSelection);
        $this->assign('iOrder', $iOrder);

        $this->assign('cashPriceType', $aResult['VEHICULE']['CASH_PRICE_TYPE']);
        $this->assign('useFinancialSimulator', $useFinancialSimulator);
        $this->assign('hasCreditPrice', $hasCreditPrice);
        $this->assign('hasCashPrice', $hasCashPrice);
        $this->assign('cashPrice', $cashPrice);
        $this->assign('creditPriceNextRent', $creditPriceNextRent);
        $this->assign('creditPriceNextRentLM', $creditPriceNextRentLM);
        $creditPriceFirstRentEscape = str_replace(array("&laquo;", "&raquo;"), "\"", $creditPriceFirstRent);
        $this->assign('creditPriceFirstRent', $creditPriceFirstRentEscape);
        $this->assign('creditPriceFirstRentLM', $creditPriceFirstRentLM);
        $this->assign('cashPriceLegalMention', $cashPriceLegalMention, false);
        $this->assign('wsCreditPriceNextRentValue', $aFinancement['VEHICULE_CREDIT_PRICE_ML_ALL']['VARIABLES']['PMT']['VALUE']);
        $this->assign('wsCreditPriceNextRentUnit', $aFinancement['VEHICULE_CREDIT_PRICE_ML_ALL']['VARIABLES']['PMT']['UNIT']);
        $aFinancement['VEHICULE_CREDIT_PRICE_FIRST_RENT'] = str_replace(array("&laquo;", "&raquo;"), "\"", $aFinancement['VEHICULE_CREDIT_PRICE_FIRST_RENT']);
        $this->assign('wsCreditPriceFirstRent', $aFinancement['VEHICULE_CREDIT_PRICE_FIRST_RENT']);
        $this->assign('wsCreditPriceFirstRentLM', str_replace(".", ".<br/>", $aFinancement['VEHICULE_CREDIT_PRICE_FIRST_RENT_LEGAL_MENTION']));
        $this->assign('aDataSimulateurFinancement', $aFinancement['VEHICULE_CREDIT_PRICE_ML_ALL']);
        $this->assign('aTools', $aTools);
        $this->assign('aShowRoomInfo', $aShowRoomInfo);
        $this->assign('aKeyPointsMedia', $aKeyPointsMedia);
        $this->assign('aParams', $aParams);
        $this->assign('aData', $aDataParams);
       
        $this->fetch();
    }

}
