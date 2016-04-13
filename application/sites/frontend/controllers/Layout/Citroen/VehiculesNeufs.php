<?php

use Citroen\GammeFinition\VehiculeGamme;
use Citroen\CarStore;
require_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Showroom.php');

class Layout_Citroen_VehiculesNeufs_Controller extends Pelican_Controller_Front
{

	const MAX_DISTANCE_CARSTORE = 100;
	const VEHICULE_LOW_KM = 60;

	public function indexAction()
	{
		$aData = $this->getParams();



		$this->assign("aData", $aData);

		// Page Mon projet avec l'onglet Comparer actif ou Page Showroom
		if (($aData['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION'] && isset($_GET['TROUVER'])) || ($aData['TEMPLATE_PAGE_ID'] != Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION'])) {
			Frontoffice_Zone_Helper::setPositionZone($aData['ZONE_ID'], $aData['ZONE_ORDER'], $aData['AREA_ID']);

			$bFR = false;
			$bBE = false;
			$bIT = false;

			// La tranche dans le gabarit Mon projet utilise le vehicule selectionné dans la tranche sélection de véhicules
			if ($aData['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION']) {
				$aData['ZONE_TITRE3'] = 'PRODUCT';
			}
			if ($aData['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION']) {
				if ($_GET['select_vehicule_lcdv6']) {
					$_SESSION[APP]['SHOWROOM_VEHICULE_ACTIF'] = $_GET['select_vehicule_lcdv6'];
				}
			}else{
				if($aData['PAGE_VEHICULE'] && ($aData['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL'] || $aData['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_INTERNE'])){
					$_SESSION[APP]['SHOWROOM_VEHICULE_ACTIF'] = VehiculeGamme::getLCDV6($aData['PAGE_VEHICULE'], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'] );
				} elseif ($aData['PAGE_VEHICULE']) {
					$aVehicule = Pelican_Cache::fetch("Frontend/Citroen/VehiculeById", array($aData['PAGE_VEHICULE'], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']));
					if ($aVehicule)	{
						$_SESSION[APP]['SHOWROOM_VEHICULE_ACTIF'] = ($aVehicule['VEHICULE_LCDV6_CONFIG'])?$aVehicule['VEHICULE_LCDV6_CONFIG']:$aVehicule['VEHICULE_LCDV6_MANUAL'];
					}
				}
			}

			switch ($aData['ZONE_TITRE3']) {
				case 'PDV':
					$bFR = true;
					$this->assign('bFR', $bFR);
					break;
				case 'CP_CITY':
					$bBE = true;
					$this->assign('bBE', $bBE);
					break;
				case 'PRODUCT':
					$bIT = true;
					$this->assign('bIT', $bIT);
					$this->_forward('italie');
					break;
			}
			$iPosition = Frontoffice_Zone_Helper::getPositionZone($aData['ZONE_ID'], $aData['ZONE_ORDER'], $aData['AREA_ID']);
			$this->assign("aData", $aData);
			$this->assign("iPosition", $iPosition);
			$this->assign("imgFront", Pelican::$config['MEDIA_HTTP']);
			$this->assign("countryCode", $_SESSION[APP]["CODE_PAYS"]);
			$this->assign("bTrancheVisible", true);
			$this->assign("lcdv", $_SESSION[APP]['SHOWROOM_VEHICULE_ACTIF']);
		}
		else {
			$this->assign("bTrancheVisible", false);
		}


		$this->fetch();
	}

	/**
	 * Gestion par produit
	 */
	public function italieAction()
	{
		$aData = $this->getParams();


		$iPageId = $aData['pid'];

		if(intval($iPageId)>0 ){
			$aPageShowroomColor = Frontoffice_Showroom_Helper::getShowroomColor($iPageId,$_SESSION[APP]['LANGUE_ID'],Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']);
				if(!empty($aPageShowroomColor['PAGE_PRIMARY_COLOR']) &&  !empty($aPageShowroomColor['PAGE_SECOND_COLOR'])){
					$aData['PRIMARY_COLOR'] = $aPageShowroomColor['PAGE_PRIMARY_COLOR'];
					$aData['SECOND_COLOR']  = $aPageShowroomColor['PAGE_SECOND_COLOR'];
				}
		}


		// Page globale
		$pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
		));
		// Zone Configuration de la page globale
		$aConfiguration = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
				$pageGlobal['PAGE_ID'],
				Pelican::$config['ZONE_TEMPLATE_ID']['CONFIGURATION'],
				$pageGlobal['PAGE_VERSION'],
				$_SESSION[APP]['LANGUE_ID']
		));

		$sEmission = $aConfiguration['ZONE_TITRE'];
		$sDevise = $aConfiguration['ZONE_TITRE2'];
		$sConso = $aConfiguration['ZONE_TITRE3'];
		$sTaille = $aConfiguration['ZONE_TITRE4'];
		$iLat = $aConfiguration['ZONE_MAP_LATITUDE'];
		$iLng = $aConfiguration['ZONE_MAP_LONGITUDE'];
		$sLCDV6 = $_SESSION[APP]['SHOWROOM_VEHICULE_ACTIF'];
		if (!$sLCDV6 && $aData['PAGE_VEHICULE']) {
			$aVehicule = Pelican_Cache::fetch("Frontend/Citroen/VehiculeById", array($aData['PAGE_VEHICULE'], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']));
			if ($aVehicule)	{
				$sLCDV6 = ($aVehicule['VEHICULE_LCDV6_CONFIG'])?$aVehicule['VEHICULE_LCDV6_CONFIG']:$aVehicule['VEHICULE_LCDV6_MANUAL'];
			}
		}
		$sLCDV4 = substr($sLCDV6, 0, 4);
		$sLCDV2 = substr($sLCDV6, 4);
		$sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));
		$lang = strtolower($_SESSION[APP]['LANGUE_CODE']);

		$vehiculeGamme = \Pelican_Cache::fetch("Citroen/GammeVehiculeGamme", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				$sLCDV6,
				'row'
		));

		if($vehiculeGamme["GAMME"] == "VU"){
			$urlClean = $aConfiguration['ZONE_TITRE20'];
		}else{
			$urlClean = $aConfiguration['ZONE_TITRE14'];
		}
		$sUrlCarStore = str_replace(
				array(
						'##LCDV2##',
						'##LCDV4##',
						'##LCDV6##',
						'##LATITUDE##',
						'##LONGITUDE##',
						'##RADIUS##',
						'##CULTURE##'
				),
				array(
						$sLCDV2,
						$sLCDV4,
						$sLCDV6,
						$iLat,
						$iLng,
						'',
						$lang.'-'.$sPays
				),
				$urlClean);
		$bTrancheVisible = true;
		if ($aData['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION']) {
			if (!$_GET['select_vehicule_lcdv6']) {
				$bTrancheVisible = false;
			}
		}

		// Véhicules
		if (!$sLCDV6 && $aData['PAGE_VEHICULE']) {
			$aVehicule = Pelican_Cache::fetch("Frontend/Citroen/VehiculeById", array($aData['PAGE_VEHICULE'], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']));
			if ($aVehicule)	{
				$sLCDV6 = ($aVehicule['VEHICULE_LCDV6_CONFIG'])?$aVehicule['VEHICULE_LCDV6_CONFIG']:$aVehicule['VEHICULE_LCDV6_MANUAL'];
			}
		}
		if ($bTrancheVisible && $sLCDV6) {
			$sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
			$sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));
			$sModelCode = '';
			$sBodyStyleCode = '';
			if ($sLCDV6 != '') {
				$sModelCode = substr($sLCDV6, 0, 4);
				$sBodyStyleCode = substr($sLCDV6, 4);
			}
			$iMin = 1;
			$iLimit = 6;

			$typeSite = "ALL";
			if($vehiculeGamme["GAMME"] == "VU"){
				$typeSite = "PROFESIONNEL";
			}

			$aVehicules = self::_getCars($sPays, $sLangue, 'PRODUCT', $iMin, $iLimit, 0, $sDevise, $iLat, $iLng, $sModelCode, $sBodyStyleCode, "", "",  $typeSite);
			if(is_array($aVehicules['CARS'])){
				foreach( $aVehicules['CARS'] as $iKey=>$aCar){
					$aMentions[$aCar['PricebackLink']]	=	$aCar['PriceMention'];

					$aFeatures = Pelican_Cache::fetch("Frontend/Citroen/VehiculesNeufs/getOptionalFeaturesInfo", array($sPays,$sLangue.'-'.$sPays,$aCar['CarNum']));
					if(is_array($aFeatures) && sizeof($aFeatures)>0){
						$aVehicules['CARS'][$iKey]['FEATURES'] = $aFeatures;
					}
					if($aData['ZONE_ATTRIBUT2'] == 1) {
						if ($aCar['StockLevel'] == self::VEHICULE_LOW_KM) {
							$aVehicules['LOW_KM'][$iKey] = $aCar;
						} else {
							$aVehicules['VN'][$iKey] = $aCar;
						}
					}

				}

				sort($aMentions);
				$sMentionsLegales	=	'';

				if(is_array($aMentions)){
					foreach( $aMentions as $mentions){
						if(count($aMentions) > 1){
							$sMentionsLegales	=	$sMentionsLegales  . $mentions . '<br/>';
						}else{
							$sMentionsLegales	=	$sMentionsLegales  . $mentions;
						}
					}
					if( isset($sMentionsLegales)  && !empty($sMentionsLegales)){
						$this->assign("sMentionsLegales",  utf8_encode($sMentionsLegales), false);
					}
				}
			}

		}

		$active = 1;
		if(strtoupper($_SESSION[APP]['CODE_PAYS']) == "NL"){
			$active = 0;
		}

                $aTraduction = array();
                $OptionTraduction = '';
                $aTraduction = Pelican_Cache::fetch("TranslationByLabelId", array('OPTIONS-CARSTORE',$_SESSION[APP]['SITE_ID'],'FRONT'));
                $OptionTraduction = $aTraduction[$_SESSION[APP]['LANGUE_ID']];
		$this->assign("OptionTraduction", $OptionTraduction);

		$this->assign("sUrlCarStore", $sUrlCarStore);
		$this->assign("aVehicules", $aVehicules);
		$this->assign("sEmission", $sEmission);
		$this->assign("sDevise", $sDevise);
		$this->assign("sConso", $sConso);
		$this->assign("sTaille", $sTaille);
		$this->assign("iLat", $iLat);
		$this->assign("iLng", $iLng);
		$this->assign("imgFront", Pelican::$config['MEDIA_HTTP']);
		$this->assign("iCount", 4);
		$this->assign("aData", $aData);
		$this->assign("iPosition", $iPosition);
		$this->assign("imgFront", Pelican::$config['MEDIA_HTTP']);
		$this->assign("countryCode", $_SESSION[APP]["CODE_PAYS"]);
		$this->assign("bTrancheVisible", $bTrancheVisible);
		$this->assign("active", $active);

		$this->fetch();
	}

	/**
	 * Gestion par point de vente
	 * Ajax
	 */
	public function franceAction()
	{

		$aData = $this->getParams();


		$iPageId = $aData['form_page_id'];

		if(intval($iPageId)>0 ){
			$aPageShowroomColor = Frontoffice_Showroom_Helper::getShowroomColor($iPageId,$_SESSION[APP]['LANGUE_ID'],Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']);
				if(!empty($aPageShowroomColor['PAGE_PRIMARY_COLOR']) &&  !empty($aPageShowroomColor['PAGE_SECOND_COLOR'])){
					$aData['PRIMARY_COLOR'] = $aPageShowroomColor['PAGE_PRIMARY_COLOR'];
					$aData['SECOND_COLOR']  = $aPageShowroomColor['PAGE_SECOND_COLOR'];
				}
		}

		// Page globale
		$pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
		));

		$aConfiguration = Pelican_Cache::fetch("Frontend/Citroen/Configuration", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				Pelican::getPreviewVersion()
		));

		// Zone Configuration de la page globale
		$aConfigurationPageGlobal = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
				$pageGlobal['PAGE_ID'],
				Pelican::$config['ZONE_TEMPLATE_ID']['CONFIGURATION'],
				$pageGlobal['PAGE_VERSION'],
				$_SESSION[APP]['LANGUE_ID']
		));

		$sEmission = $aConfiguration['ZONE_TITRE'];
		$sDevise = $aConfigurationPageGlobal['ZONE_TITRE2'];
		$sConso = $aConfiguration['ZONE_TITRE3'];
		$sTaille = $aConfiguration['ZONE_TITRE4'];
		$sLCDV6 = $_SESSION[APP]['SHOWROOM_VEHICULE_ACTIF'];
		$sLCDV4 = substr($sLCDV6, 0, 4);
		$sLCDV2 = substr($sLCDV6, 4);



		// Véhicules
		$sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
		$sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));

		$iMin = 1;
		$iLimit = 6;
		$iLat = $aData['lat'];
		$iLng = $aData['long'];

		//$sLCDV6 = $_SESSION[APP]['SHOWROOM_VEHICULE_ACTIF'];
		if ($sLCDV6 != '') {
			$sModelCode = substr($sLCDV6, 0, 4);
			$sBodyStyleCode = substr($sLCDV6, 4);
		}

		$iStoreId = $aData['storeId'];
		$sStoreRRDI = $aData['storeRRDI'];

		$vehiculeGamme = \Pelican_Cache::fetch("Citroen/GammeVehiculeGamme", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				$sLCDV6,
				'row'
		));

		$typeSite = "ALL";
		if($vehiculeGamme["GAMME"] == "VU"){
			$typeSite = "PROFESIONNEL";
		}

		$aVehicules = self::_getCars($sPays, $sLangue, 'PDV', $iMin, $iLimit,$iStoreId,$sDevise, $iLat, $iLng, $sModelCode, $sBodyStyleCode,$sStoreRRDI, "", $typeSite);

		//$aVehicules = self::_getCars($sPays, $sLangue, 'PDV', $iMin, $iLimit, 0, $sDevise, $iLat, $iLng, $sModelCode, $sBodyStyleCode, "", $rayon);
		if(is_array($aVehicules['CARS'])){
			foreach( $aVehicules['CARS'] as $iKey=>$aCar){
				$aMentions[$aCar['PricebackLink']]	=	$aCar['PriceMention'];
				if(!empty($aCar['VehicleCarNum'])){
					$aFeatures = Pelican_Cache::fetch("Frontend/Citroen/VehiculesNeufs/getOptionalFeaturesInfo", array($sPays,$sLangue.'-'.$sPays,$aCar['VehicleCarNum']));
					if(is_array($aFeatures) && sizeof($aFeatures)>0){
						$aVehicules['CARS'][$iKey]['FEATURES'] = $aFeatures;
					}
				}
				$aDataLocalisation = array('lat'=>$iLat,
						'lng'=>$iLng);
				$aVehicules['CARS'][$iKey]['VehicleWebstoreLink'] = $aCar['VehicleWebstoreLink'].'&'.http_build_query($aDataLocalisation) ;

			}



			sort($aMentions);
			$sMentionsLegales	=	'';

			if(is_array($aMentions)){
				foreach( $aMentions as $mentions){
					if(count($aMentions) > 1){
						$sMentionsLegales	=	$sMentionsLegales  . $mentions . '<br/>';
					}else{
						$sMentionsLegales	=	$sMentionsLegales  . $mentions;
					}
				}
				if( isset($sMentionsLegales)  && !empty($sMentionsLegales)){
					$this->assign("sMentionsLegales",  utf8_encode($sMentionsLegales), false);
				}
			}
		}

		/*if ($aVehicules['STORE_URL']) {
            $sUrlCarStore = $aVehicules['STORE_URL'];
        } else {*/
		// $pays = $_SESSION[APP]['CODE_PAYS'] == 'CT' ? 'FR' : $_SESSION[APP]['CODE_PAYS'];
		// $lang = strtolower($_SESSION[APP]['LANGUE_CODE']);

		$dataFrance = array(
				'LCDV6'	=>$sLCDV6,
				'LATITUDE'	=>$aData['lat'],
				'LONGITUDE'	=>$aData['long'],
				'RADIUS'	=>'',
				'CULTURE'	=> $lang.'-'.$pays
		);

		$sUrlCarStore = CarStore::getCarStoreUrl($dataFrance, $aConfiguration);


		//}


		$aDealer = Pelican_Cache::fetch("Frontend/Citroen/Annuaire/Dealer", array(
				$aData['storeId'],
				$sPays,
				$sLangue
		));

		$active = 1;
		if(strtoupper($_SESSION[APP]['CODE_PAYS']) == "NL"){
			$active = 0;
		}

                $aTraduction = array();
                $OptionTraduction = '';
                $aTraduction = Pelican_Cache::fetch("TranslationByLabelId", array('OPTIONS-CARSTORE',$_SESSION[APP]['SITE_ID'],'FRONT'));
                $OptionTraduction = $aTraduction[$_SESSION[APP]['LANGUE_ID']];
		$this->assign("OptionTraduction", $OptionTraduction);
                
		$this->assign("aData", $aData);
		$this->assign("sUrlCarStore", $sUrlCarStore);
		$this->assign("sEmission", $sEmission);
		$this->assign("aDealer", $aDealer);
		$this->assign("sDevise", $sDevise);
		$this->assign("sConso", $sConso);
		$this->assign("sTaille", $sTaille);
		$this->assign("aVehicules", $aVehicules);
		$this->assign("iPosition", $iPosition);
		$this->assign("iCount", 4);
		$this->assign("iZid", $aData['iZid']);
		$this->assign("iZorder", $aData['iZorder']);
		$this->assign("iAreaId", $aData['iAreaId']);
		$this->assign("bTrancheVisible", $bTrancheVisible);
		$this->assign("active", $active);

		$this->fetch();

		$this->getRequest()->addResponseCommand('assign', array(
				'id' => 'resultVN',
				'attr' => 'innerHTML',
				'value' => $this->getResponse()
		));
		$this->getRequest()->addResponseCommand('script', array(
				'value' => "
				lazy.set($('img.lazy'));
				 $('#seeMoreCars a').unbind('click');
				 $('#seeMoreCars a').bind('click',function(e){
					e.preventDefault();
					displayMoreCars('more');
				});"
		));
	}

	/**
	 * Gestion par Code postal / Ville
	 * Ajax
	 */
	public function belgiqueAction()
	{
		$aData = $this->getParams();


		$iPageId = $aData['form_page_id'];

		if(intval($iPageId)>0 ){
			$aPageShowroomColor = Frontoffice_Showroom_Helper::getShowroomColor($iPageId,$_SESSION[APP]['LANGUE_ID'],Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']);
				if(!empty($aPageShowroomColor['PAGE_PRIMARY_COLOR']) &&  !empty($aPageShowroomColor['PAGE_SECOND_COLOR'])){
					$aData['PRIMARY_COLOR'] = $aPageShowroomColor['PAGE_PRIMARY_COLOR'];
					$aData['SECOND_COLOR']  = $aPageShowroomColor['PAGE_SECOND_COLOR'];
				}

		}

		// Page globale
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

		$aConfiguration = Pelican_Cache::fetch("Frontend/Citroen/Configuration", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				Pelican::getPreviewVersion()
		));

		$sEmission = $aConfiguration['ZONE_TITRE'];
		$sDevise = $aConfigurationPageGlobal['ZONE_TITRE2'];
		$sConso = $aConfiguration['ZONE_TITRE3'];
		$sTaille = $aConfiguration['ZONE_TITRE4'];
		//max distance = 100 dans carestore
		$rayon = MAX_DISTANCE_CARSTORE;
		if($aData['iMaxDistance'] < MAX_DISTANCE_CARSTORE)
		{
			$rayon = $aData['iMaxDistance'];
		}

		$sLCDV6 = $_SESSION[APP]['SHOWROOM_VEHICULE_ACTIF'];
		$sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
		$sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));


		$data = array(
				'LCDV6'	=>$sLCDV6,
				'LATITUDE'	=>$aData['lat'],
				'LONGITUDE'	=>$aData['long'],
				'RADIUS'	=>'',
				'CULTURE'	=> $lang.'-'.$pays
		);


		$sUrlCarStore = CarStore::getCarStoreUrl($data, $aConfiguration);

		// Position
		$iZid = (int) $aData['iZid'];
		$iZorder = (int) $aData['iZorder'];
		$iAreaId = (int) $aData['iAreaId'];
		$iPosition = $_SESSION[APP]['ZONE_POSITION'][$iZid][$iZorder][$iAreaId]['POSITION'];

		// Véhicules
		// $sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
		// $sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));
		$iMin = 1;
		$iLimit = 6;
		$iLat = $aData['lat'];
		$iLng = $aData['long'];

		//$sLCDV6 = $_SESSION[APP]['SHOWROOM_VEHICULE_ACTIF'];
		if ($sLCDV6 != '') {
			$sModelCode = substr($sLCDV6, 0, 4);
			$sBodyStyleCode = substr($sLCDV6, 4);
		}

		$vehiculeGamme = \Pelican_Cache::fetch("Citroen/GammeVehiculeGamme", array(
			$_SESSION[APP]['SITE_ID'],
			$_SESSION[APP]['LANGUE_ID'],
			$sLCDV6,
			'row'
	));

		$typeSite = "ALL";
		if($vehiculeGamme["GAMME"] == "VU"){
			$typeSite = "PROFESIONNEL";
		}

		$aVehicules = self::_getCars($sPays, $sLangue, 'CP_CITY', $iMin, $iLimit, 0, $sDevise, $iLat, $iLng, $sModelCode, $sBodyStyleCode, "", $rayon, $typeSite);

		if(is_array($aVehicules['CARS'])){
			foreach( $aVehicules['CARS'] as $iKey=>$aCar){
				$aMentions[$aCar['PricebackLink']]	=	$aCar['PriceMention'];
				$aFeatures = Pelican_Cache::fetch("Frontend/Citroen/VehiculesNeufs/getOptionalFeaturesInfo", array($sPays,$sLangue.'-'.$sPays,$aCar['CarNum']));
				if(is_array($aFeatures) && sizeof($aFeatures)>0){
					$aVehicules['CARS'][$iKey]['FEATURES'] = $aFeatures;
				}
				if($aData['groupvnlowkm'] == 1){
					if($aCar['StockLevel'] == self::VEHICULE_LOW_KM){
						$aVehicules['LOW_KM'][$iKey]= $aCar;
					}else{
						$aVehicules['VN'][$iKey] = $aCar;
					}
				}

			}



			sort($aMentions);
			$sMentionsLegales	=	'';

			if(is_array($aMentions)){
				foreach( $aMentions as $mentions){
					if(count($aMentions) > 1){
						$sMentionsLegales	=	$sMentionsLegales  . $mentions . '<br/>';
					}else{
						$sMentionsLegales	=	$sMentionsLegales  . $mentions;
					}
				}
				if( isset($sMentionsLegales)  && !empty($sMentionsLegales)){
					$this->assign("sMentionsLegales",  utf8_encode($sMentionsLegales), false);
				}
			}
		}

		$active = 1;
		if(strtoupper($_SESSION[APP]['CODE_PAYS']) == "NL"){
			$active = 0;
		}

                $aTraduction = array();
                $OptionTraduction = '';
                $aTraduction = Pelican_Cache::fetch("TranslationByLabelId", array('OPTIONS-CARSTORE',$_SESSION[APP]['SITE_ID'],'FRONT'));
                $OptionTraduction = $aTraduction[$_SESSION[APP]['LANGUE_ID']];
		$this->assign("OptionTraduction", $OptionTraduction);
                
		$this->assign("aData", $aData);
		$this->assign("sUrlCarStore", $sUrlCarStore);
		$this->assign("sEmission", $sEmission);
		$this->assign("sDevise", $sDevise);
		$this->assign("sConso", $sConso);
		$this->assign("sTaille", $sTaille);
		$this->assign("aVehicules", $aVehicules);
		$this->assign("iPosition", $iPosition);
		$this->assign("iCount", 4);
		$this->assign("bTrancheVisible", $bTrancheVisible);
		$this->assign("active", $active);




		$this->fetch();

		$this->getRequest()->addResponseCommand('assign', array(
				'id' => 'resultVN',
				'attr' => 'innerHTML',
				'value' => $this->getResponse()
		));
		$this->getRequest()->addResponseCommand('script', array(
				'value' => "
				lazy.set($('img.lazy'));
				$('form[name=newCarBelgium]').trigger('notbusy');
				$('#seeMoreCars a').unbind('click');
				$('#seeMoreCars a').bind('click',function(e){
					e.preventDefault();
					displayMoreCars('more');
				});"
		));
	}

	/**
	 * Défilement infini des véhicules
	 * Ajax
	 */
	public function moreCarsAction()
	{
		$aData = $this->getParams();

		$iZid = (int) $aData['iZid'];
		$iZorder = (int) $aData['iZorder'];
		$iAreaId = (int) $aData['iAreaId'];
		$zType = (string) $aData['zType'];

		// Page globale
		$pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
		));
		// Zone Configuration de la page globale
		$aConfiguration = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
				$pageGlobal['PAGE_ID'],
				Pelican::$config['ZONE_TEMPLATE_ID']['CONFIGURATION'],
				$pageGlobal['PAGE_VERSION'],
				$_SESSION[APP]['LANGUE_ID']
		));

		$sEmission = $aConfiguration['ZONE_TITRE'];
		$sDevise = $aConfiguration['ZONE_TITRE2'];
		$sConso = $aConfiguration['ZONE_TITRE3'];
		$sTaille = $aConfiguration['ZONE_TITRE4'];

		// Véhicules
		$sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
		$sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));
		$iMin = ($sTypeAff == 'less') ? 1 : $aData['iMin'];
		$iLimit = 6;

		$sModelCode = '';
		$sBodyStyleCode = '';
		$iStoreId = 0;
		$sStoreRRDI = "";
		$iLat = null;
		$iLng = null;
		// Mode 'Italie', filtrage par produit
		if ($zType == 'PRODUCT') {
			$sLCDV6 = $_SESSION[APP]['SHOWROOM_VEHICULE_ACTIF'];
			if ($sLCDV6 != '') {
				$sModelCode = substr($sLCDV6, 0, 4);
				$sBodyStyleCode = substr($sLCDV6, 4);
			}
		}
		// Mode 'Belgique', filtrage par coordonnés
		if ($zType == 'CP_CITY') {
			$iLat = $aData['lat'];
			$iLng = $aData['long'];
		}
		// Mode 'France', filtrage par id du PDV
		if ($zType == 'PDV') {
			$iStoreId = $aData['storeId'];
			$sStoreRRDI = $aData['storeRRDI'];
		}

		$vehiculeGamme = \Pelican_Cache::fetch("Citroen/GammeVehiculeGamme", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				$sLCDV6,
				'row'
		));

		$typeSite = "ALL";
		if($vehiculeGamme["GAMME"] == "VU"){
			$typeSite = "PROFESIONNEL";
		}

		$aVehicules = self::_getCars($sPays, $sLangue, $zType, $iMin, $iLimit, $iStoreId, $sDevise, $iLat, $iLng, $sModelCode, $sBodyStyleCode, $sStoreRRDI, $typeSite);

		// Position
		$iPosition = $_SESSION[APP]['ZONE_POSITION'][$iZid][$iZorder][$iAreaId]['POSITION'];
		$this->assign("sEmission", $sEmission);
		$this->assign("iPosition", $iPosition);
		$this->assign("sDevise", $sDevise);
		$this->assign("sConso", $sConso);
		$this->assign("sTaille", $sTaille);
		$this->assign("iLat", $iLat);
		$this->assign("iLng", $iLng);
		$this->assign("aVehicules", $aVehicules);
		$this->assign("zType", $zType);



		$this->fetch();
		$sTypeAdd = ($sTypeAff == 'less') ? 'assign' : 'append';

		$this->getRequest()->addResponseCommand($sTypeAdd, array(
				'id' => 'allNewCar',
				'attr' => 'innerHTML',
				'value' => $this->getResponse()
		));

		$iCount = $iMin + 1;
		if ($sTypeAff == 'less') {
			$this->getRequest()->addResponseCommand('script', array(
					'value' => "
							 $('#iCount').val(" . $iCount . ");
							 $('#seeMoreCars a').html('" . t('VOIR_STOCK') . "');
							 $('#seeMoreCars a').unbind('click');
							 $('#seeMoreCars a').bind('click',function(e){
								e.preventDefault();
								displayMoreCars('more');
							});"
			));
		} else {
			if (count($aVehicules['CARS']) < 4 || $aVehicules['COUNT'] < $iCount) {
				$this->getRequest()->addResponseCommand('script', array(
						'value' => "$('#iCount').val(" . $iCount . ");
								 $('#seeMoreCars a').html('" . t('VOIR_MOINS_STOCK') . "');
								 $('#seeMoreCars a').unbind('click');
								 $('#seeMoreCars a').bind('click',function(e){
									e.preventDefault();
									displayCarsNews('less');
								});"
				));
			} else {
				$this->getRequest()->addResponseCommand('script', array(
						'value' => "$('#iCount').val(" . $iCount . "); lazy.set($('img.lazy'));"
				));
			}
		}
	}

	/**
	 * Configuration du Google Map
	 */
	public function getMapConfigurationAction()
	{
		$aParams = $this->getParams();

		// Zone Véhicules Neufs
		$aZone = Pelican_Cache::fetch("Frontend/Page/ZoneTemplateByPageId", array(
				$aParams['page'],
				$aParams['version'],
				$aParams['ztid'],
				$_SESSION[APP]['LANGUE_ID'],
				$aParams['area'],
				$aParams['order']
		));

		// Page globale
		$pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
		));
		// Zone Configuration de la page globale
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
				1,
				5,
				2,
				$lat,
				$lng,
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



		echo json_encode($aConfig);
	}

	/**
	 * Liste des PDV dans le Google Map
	 */
	public function getStoreListAction()
	{
		$aParams = $this->getParams();

		$sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
		$sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));


		// Zone Véhicules Neufs
		$aZone = Pelican_Cache::fetch("Frontend/Page/ZoneTemplateByPageId", array(
				$aParams['page'],
				$aParams['version'],
				$aParams['ztid'],
				$_SESSION[APP]['LANGUE_ID'],
				$aParams['area'],
				$aParams['order']
		));

		$aDealers = Pelican_Cache::fetch("Frontend/Citroen/Annuaire/DealerList", array(
				$aParams['lat'],
				$aParams['long'],
				$sPays,
				$sLangue,
				$aParams['attribut'],
				$aParams['request'],
				$aZone['ZONE_TITRE13']
		));


		$aDealers = (!empty($aDealers)) ? $aDealers : 'vide';
		echo json_encode($aDealers);
	}

	/**
	 * Détail d'un PDV dans le Google Map
	 */
	public function getDealerAction()
	{
		$aData = $this->getParams();

		$sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
		$sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));

		$aDealer = Pelican_Cache::fetch("Frontend/Citroen/Annuaire/Dealer", array(
				$aData['id'],
				$sPays,
				$sLangue
		));



		echo json_encode($aDealer);
	}

	private static function _getCars($sPays, $sLanguageCode, $sTypeAff, $iMin, $iLimit = 6, $iStoreId = 0, $sDevise = "", $iLat = null, $iLng = null, $sModelCode = "", $sBodyCody = "", $sStoreRRDI = "", $imaxDistance = "", $typeSite = "ALL")
	{
		include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Devise.php');
		include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Vehicule.php');
		// Zone Configuration de la page globale
		// $aConfiguration = Pelican_Cache::fetch("Frontend/Citroen/Configuration", array(
		// $_SESSION[APP]['SITE_ID'],
		// $_SESSION[APP]['LANGUE_ID'],
		// Pelican::getPreviewVersion()
		// ));

		// Page globale
		$pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				Pelican::getPreviewVersion(),
				Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
		));
		// Zone Configuration de la page globale
		$aConfiguration = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
				$pageGlobal['PAGE_ID'],
				Pelican::$config['ZONE_TEMPLATE_ID']['CONFIGURATION'],
				$pageGlobal['PAGE_VERSION'],
				$_SESSION[APP]['LANGUE_ID']
		));


		if ($sTypeAff == 'PDV') {
			$aVehicules = Pelican_Cache::fetch("Frontend/Citroen/VehiculesNeufs/getStockWebstore", array(
					$iStoreId,
					$sPays,
					$sLanguageCode . '-' . $sPays,
					$iMin - 1,
					$iLimit,
					$sStoreRRDI,
					$sModelCode,
					$sBodyCody,
					date('YmdHi')
			));


			if (is_array($aVehicules) && count($aVehicules) > 0) {
				foreach ($aVehicules['CARS'] as $key => $vehicule) {
					$aVehicules['CARS'][$key]['PRIX_CATALOGUE_STRING'] = Frontoffice_Devise_Helper::formatDevise($_SESSION[APP]['LANGUE_ID'], $sDevise, $vehicule['VehiclePriceCatalogue']);
					$aVehicules['CARS'][$key]['BILAN_CARBONE'] = Frontoffice_Vehicule_Helper::getBilanCarbone($vehicule['VehicleCO2Rate']);
					if ($vehicule['VehicleWebstorePrice'] != 0) {
						$aVehicules['CARS'][$key]['PRIX_WEBSTORE_STRING'] = Frontoffice_Devise_Helper::formatDevise($_SESSION[APP]['LANGUE_ID'], $sDevise, $vehicule['VehicleWebstorePrice']);
					}
					$sSoitEco='';
					if ($aVehicules['CARS'][$key]['SOIT_ECO']) {
						$sSoitEco = preg_match('!\d+!', $aVehicules['CARS'][$key]['SOIT_ECO'], $matches);
						$iPriceSoitEco= number_format($matches[0], 0, ' ', ' ').' '.$sDevise;
						$aVehicules['CARS'][$key]['SOIT_ECO'] = str_replace(array('#PRICE#'), array($iPriceSoitEco), t('SOIT_ECO'));
					}
				}
			}
		} else {
			$aVehicules = Pelican_Cache::fetch("Frontend/Citroen/VehiculesNeufs/getVehicles", array(
					$sPays,
					$sLanguageCode . '-' . $sPays,
					$iMin,
					$iLimit,
					$iLat,
					$iLng,
					$sModelCode,
					$sBodyCody,
					$sTypeAff,
					$imaxDistance,
					date('YmdHi'),
					$typeSite
			));
			
			

			if (is_array($aVehicules) && count($aVehicules) > 0) {
				foreach ($aVehicules['CARS'] as $key => $vehicule) {
					$aVehicules['CARS'][$key]['PRIX_CATALOGUE_STRING'] = Frontoffice_Devise_Helper::formatDevise($_SESSION[APP]['LANGUE_ID'], $sDevise, $vehicule['CatalogPrice']);
					if(isset($vehicule['AIDE_REPRISE'])){
						$aVehicules['CARS'][$key]['AIDE_REPRISE']  = Frontoffice_Devise_Helper::formatDevise($_SESSION[APP]['LANGUE_ID'], $sDevise, $vehicule['AIDE_REPRISE']);
					}
					if(isset($vehicule['NationalDiscount'])){
						$aVehicules['CARS'][$key]['NationalDiscount']  = Frontoffice_Devise_Helper::formatDevise($_SESSION[APP]['LANGUE_ID'], $sDevise, $vehicule['NationalDiscount']);
					}
					if(isset($vehicule['LocalDiscount'])){
						$aVehicules['CARS'][$key]['LocalDiscount']  = Frontoffice_Devise_Helper::formatDevise($_SESSION[APP]['LANGUE_ID'], $sDevise, $vehicule['LocalDiscount']);
					}
					
					$aVehicules['CARS'][$key]['BILAN_CARBONE'] = Frontoffice_Vehicule_Helper::getBilanCarbone($vehicule['CO2Rate']);
					if ($vehicule['InternetPrice'] != 0) {
						if(isset($vehicule['AIDE_REPRISE'])){
							$iPrice = $vehicule['InternetPrice'] - $vehicule['AIDE_REPRISE'];
							$aVehicules['CARS'][$key]['PRIX_WEBSTORE_STRING'] = Frontoffice_Devise_Helper::formatDevise($_SESSION[APP]['LANGUE_ID'], $sDevise, $iPrice);
						}else{
							$aVehicules['CARS'][$key]['PRIX_WEBSTORE_STRING'] = Frontoffice_Devise_Helper::formatDevise($_SESSION[APP]['LANGUE_ID'], $sDevise, $vehicule['InternetPrice']);
						}
					}
					$sSoitEco='';
					if ($aVehicules['CARS'][$key]['SOIT_ECO']) {
						$sSoitEco = preg_match('!\d+!', $aVehicules['CARS'][$key]['SOIT_ECO'], $matches);
						$aVehicules['CARS'][$key]['SOIT_ECO'] = str_replace(array('#CURRENCY#',$matches[0]), array($sDevise,number_format($matches[0], 0, ' ', ' ')), $aVehicules['CARS'][$key]['SOIT_ECO']);

					}
					
				}

				// Obtient une liste de colonnes
				foreach ($aVehicules['CARS'] as $key => $row) {
					$distance[$key]  = $row['Distance'];
					$prix[$key] = $row['PRIX_WEBSTORE_STRING'];
				}

				// Trie les données par distance croissant, prix croissant
				// Ajoute $aVehicules['CARS'] en tant que dernier paramètre, pour trier par la clé commune
				array_multisort($distance, SORT_ASC, $prix, SORT_ASC, $aVehicules['CARS']);
			}
		}

		//carStore
		if (is_array($aVehicules) && count($aVehicules) > 0) {
			foreach ($aVehicules['CARS'] as $key => $vehicule) {
				if(!empty($vehicule['StoreDetailUrl'])){

					$vehicule['LCDV6'] = $sModelCode.$sBodyCody;
					$vehicule['LATITUDE'] = $iLat;
					$vehicule['LONGITUDE'] = $iLng;
					$vehicule['CULTURE'] = $sLanguageCode . '-' . $sPays;
					$vehicule['RADIUS'] = $imaxDistance;
					$aVehicules['CARS'][$key]['StoreDetailUrl'] = CarStore::getCarStoreUrl($vehicule, $aConfiguration);
				}elseif(empty($vehicule['VehicleWebstoreLink'])){

					$vehicule['LCDV6'] = $sModelCode.$sBodyCody;
					$vehicule['LATITUDE'] = $iLat;
					$vehicule['LONGITUDE'] = $iLng;
					$vehicule['CULTURE'] = $sLanguageCode . '-' . $sPays;
					$vehicule['RADIUS'] = $imaxDistance;
					$aVehicules['CARS'][$key]['VehicleWebstoreLink'] = CarStore::getCarStoreUrl($vehicule, $aConfiguration);
				}
			}
		}

		return $aVehicules;
	}

	public function orderArray(&$aAllVehicule, $vehicule){
		$order = array('practice_id', 'practice_location_id');

		foreach ($order as $key) {
			if (isset($a[$key]) && isset($b[$key]) && $a[$key] != $b[$key]) {
				return $a[$key] - $b[$key];
			}
		}

		return 0;
	}

}
