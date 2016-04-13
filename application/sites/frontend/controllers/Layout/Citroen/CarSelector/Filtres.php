<?php

use Citroen\GammeFinition\VehiculeGamme;

class Layout_Citroen_CarSelector_Filtres_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
		$aData = $this->getParams();

		/**
		** Traitement VP/VU
		**/

			switch ($aData['ZONE_TITRE11']) {
			case 'VU':
				$typeGamme = "VU";
				break;
			case 'VP':
				$typeGamme = "VP";
				break;
			
			default:
				$typeGamme = "";
				break;
		}

		/*
		 *  Page globale
		 */
		$pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
			$_SESSION[APP]['SITE_ID'],
			$_SESSION[APP]['LANGUE_ID'],
			'CURRENT',
			Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
		));

		/*
		 *  Filtres type 1
		 */

		//Si des filtres ont été sélectionné, on explode le champs dans lequel ils sont stockés
		if($aData['ZONE_TITRE'] != ""){
			$aSelectedBO1 = explode("##",$aData['ZONE_TITRE']);
		}
		$bDisplayFiltre1 = true;
		//Si moins de deux filtres est sélectionné, cette partie ne s'affiche pas
		if(count($aSelectedBO1) < 2){
			$bDisplayFiltre1 = false;
		}else{
			$bDisplaySilhouette = false;
			$bDisplayEnergie = false;
			$bDisplayBoiteVitesse = false;
			$bDisplayPrix = false;
			$bDisplayNbPassagers = false;
			$bDisplayConso = false;
			$bDisplayEmissionCo2 = false;
			$bDisplayLongueurExt = false;
			$sNouvelleColonne = "";
			//On boucle sur tous les filtres sélectionnés pour identifier ceux qui s'affichent
			foreach($aSelectedBO1 as $key=>$filtre){
				//Si plus de quatre filtres ont été sélectionné et on arrive au 5éme élément, on met la chaine de caractére lui correspondant dans une variable afin de lui ajouter une classe spécifique lui permettant d'aller é la ligne
				if($key == 5){
					$sNouvelleColonne = Pelican::$config['FILTRE_TYPE_1_CORRESPONDANCE'][$filtre];
				}
				switch($filtre){
					//Silhouette
					case "0" :
						$bDisplaySilhouette = true;
					break;
					//Energie
					case "1" :
						$bDisplayEnergie = true;
					break;
					//Boite de vitesse
					case "2" :
						$bDisplayBoiteVitesse = true;
					break;
					//Prix
					case "3" :
						$bDisplayPrix = true;
					break;
					//Nb passagers
					case "4" :
						$bDisplayNbPassagers = true;
					break;
					//Consommation
					case "5" :
						$bDisplayConso = true;
					break;
					//Emission Co2
					case "6" :
						$bDisplayEmissionCo2 = true;
					break;
					//Longueur extérieure
					case "7" :
						$bDisplayLongueurExt = true;
					break;
				}
			}
		}

		$aSilhouetteFiltre = VehiculeGamme::getBodiesVehicule($_SESSION[APP]['SITE_ID'],$_SESSION[APP]['LANGUE_ID'],$typeGamme);
		$aTransmissionFiltre = VehiculeGamme::getTransmissionsVehicule($_SESSION[APP]['SITE_ID'],$_SESSION[APP]['LANGUE_ID'],$typeGamme);
		if(is_array($aTransmissionFiltre) && count($aTransmissionFiltre)>0){
			array_unshift($aTransmissionFiltre , array('CRIT_TR_CODE'=>	'TOUTGEARS', 'CRIT_TR_LABEL'=>t('TOUT')));
		}
		$aEnergieFiltre = VehiculeGamme::getEnergiesVehicule($_SESSION[APP]['SITE_ID'],$_SESSION[APP]['LANGUE_ID']);
		if(is_array($aEnergieFiltre) && count($aEnergieFiltre)>0){
			array_unshift($aEnergieFiltre , array('ENERGY_CODE'=>'TOUTENERGY','ENERGY_LABEL'=>t('TOUT')));
		}

		//Prix
		$iPasPrix = $aData['ZONE_TITRE3'];
		$iMinPrix = $aData['ZONE_TITRE4'];
		$iMaxPrix = $aData['ZONE_TITRE5'];

		//Récupération des filtres
		$aFiltresType1 = array();
		$aCheckedSil = array();
		if(!empty($_POST)){
			foreach($_POST as $key=>$post){
				if(strpos($key, 'silhouette') !== false){
					$aCheckedSil[$post]  = "checked";

				}
			}
		}
		$this->assign("aCheckedSil", $aCheckedSil);
		$aFiltresType1['ENERGIE'] = $_POST['energy'];
		$aFiltresType1['BOITE_VITESSE'] = $_POST['gears'];
		$aFiltresType1['PRIX'] = ($_POST["isMobile"] ==  1) ? $_POST['price'] : $_POST['priceLimit'];
		$aFiltresType1['PASSAGERS'] = $_POST['passengers'];
		$aFiltresType1['CONSO'] = $_POST['consum'];
		$aFiltresType1['EMISSION'] = $_POST['co2'];
		$aFiltresType1['LONGUEUR'] = $_POST['length'];



		$this->assign("aFiltresType1", $aFiltresType1);
		$this->assign("aSilhouetteFiltre", $aSilhouetteFiltre);
		$this->assign("aTransmissionFiltre", $aTransmissionFiltre);
		$this->assign("aEnergieFiltre", $aEnergieFiltre);
		$this->assign("bDisplayFiltre1", $bDisplayFiltre1);
		$this->assign("bDisplaySilhouette", $bDisplaySilhouette);
		$this->assign("bDisplayEnergie", $bDisplayEnergie);
		$this->assign("bDisplayBoiteVitesse", $bDisplayBoiteVitesse);
		$this->assign("bDisplayPrix", $bDisplayPrix);
		$this->assign("bDisplayNbPassagers", $bDisplayNbPassagers);
		$this->assign("bDisplayConso", $bDisplayConso);
		$this->assign("bDisplayEmissionCo2", $bDisplayEmissionCo2);
		$this->assign("bDisplayLongueurExt", $bDisplayLongueurExt);
		$this->assign("sNouvelleColonne", $sNouvelleColonne);
		$this->assign("iPasPrix", $iPasPrix);
		$this->assign("iMinPrix", $iMinPrix);
		$this->assign("iMaxPrix", $iMaxPrix);


		/*
		 *  Filtres type 2
		 */

		if($aData['ZONE_TITRE2'] != "" && $aData['ZONE_TITRE11'] != "VU"){
			$aSelectedBO2 = explode("##",$aData['ZONE_TITRE2']);
		}
		$bDisplayFiltre2 = true;
		//Si moins de deux filtres est sélectionné, cette partie ne s'affiche pas
		if(count($aSelectedBO2) < 2){
			$bDisplayFiltre2 = false;
		}else{
			$bDisplayCritere1 = false;
			$bDisplayPrix2 = false;
			$bDisplayCritere2 = false;
			$bDisplayCritere3 = false;
			//On boucle sur tous les filtres sélectionnés pour identifier ceux qui s'affichent
			foreach($aSelectedBO2 as $key=>$filtre){
				switch($filtre){
					//Critere 1
					case "0" :
						$bDisplayCritere1 = true;
					break;
					//Critere 2
					case "1" :
						$bDisplayCritere2 = true;
					break;
					//Prix
					case "2" :
						$bDisplayPrix2  = true;
					break;
					//Critere 3
					case "3" :
						$bDisplayCritere3 = true;
					break;
				}
			}
		}



		$aCriteres = Pelican_Cache::fetch("Frontend/Citroen/Criteres", array($_SESSION[APP]['SITE_ID'],$_SESSION[APP]['LANGUE_ID']));

		$aTriTrois = array();
		if(count($$aCriteres[3]) > 0)
		{	
		foreach ($aCriteres[3] as $key => $row) {
			    $aTriTrois[$key]  = $row['CRITERE_ORDER'];
			}
		array_multisort($aTriTrois, SORT_ASC, $aCriteres[3]);
		}
		

		$sLogoCritere1 = Pelican::$config['MEDIA_HTTP'].Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aData['MEDIA_ID']), Pelican::$config['MEDIA_FORMAT_ID']['WEB_CAR_SELECTOR_MASTER_N1_ET_N2']);
		$sLogoCritere2 = Pelican::$config['MEDIA_HTTP'].Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aData['MEDIA_ID3']), Pelican::$config['MEDIA_FORMAT_ID']['WEB_CAR_SELECTOR_MASTER_N1_ET_N2']);
		$sLogoCritere3 = Pelican::$config['MEDIA_HTTP'].Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aData['MEDIA_ID4']), Pelican::$config['MEDIA_FORMAT_ID']['WEB_CAR_SELECTOR_MASTER_N1_ET_N2']);
		$sLogoPrix =Pelican::$config['MEDIA_HTTP'].Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aData['MEDIA_ID2']), Pelican::$config['MEDIA_FORMAT_ID']['WEB_CAR_SELECTOR_MASTER_N1_ET_N2']);

		//Prix
		$iSeuilPrix1Tranche1 = ($aData['ZONE_TITRE6'] != 0 && $aData['ZONE_TITRE6'] != "") ? (int)$aData['ZONE_TITRE6'] - 1 : "";
		$iSeuilPrix1Tranche2 = $aData['ZONE_TITRE6'];
		$iSeuilPrix2Tranche2 = ($aData['ZONE_TITRE7'] != 0 && $aData['ZONE_TITRE7'] != "") ? (int)$aData['ZONE_TITRE7'] - 1 : "";
		$iSeuilPrix2Tranche3 = $aData['ZONE_TITRE7'];
		$iSeuilPrix3Tranche3 = ($aData['ZONE_TITRE8'] != 0 && $aData['ZONE_TITRE8'] != "") ? (int)$aData['ZONE_TITRE8'] - 1 : "";
		$iSeuilPrix3Tranche4 = $aData['ZONE_TITRE8'];

		//Filtres sélectionnés
		$aFiltresType2['CRITERE_1'] = $_POST['critere1'];
		$aFiltresType2['CRITERE_2'] = $_POST['critere2'];
		$aFiltresType2['PRIX'] = $_POST['cash'];
		$aFiltresType2['CRITERE_3'] = $_POST['critere3'];

		$this->assign("aCriteres", $aCriteres);
		$this->assign("aFiltresType2", $aFiltresType2);
		$this->assign("bDisplayFiltre2", $bDisplayFiltre2);
		$this->assign("bDisplayCritere1", $bDisplayCritere1);
		$this->assign("bDisplayCritere2", $bDisplayCritere2);
		$this->assign("bDisplayPrix2", $bDisplayPrix2);
		$this->assign("bDisplayCritere3", $bDisplayCritere3);
		$this->assign("sLogoCritere1", $sLogoCritere1);
		$this->assign("sLogoCritere2", $sLogoCritere2);
		$this->assign("sLogoCritere3", $sLogoCritere3);
		$this->assign("sLogoPrix", $sLogoPrix);
		$this->assign("iSeuilPrix1Tranche1", $iSeuilPrix1Tranche1);
		$this->assign("iSeuilPrix1Tranche2", $iSeuilPrix1Tranche2);
		$this->assign("iSeuilPrix2Tranche2", $iSeuilPrix2Tranche2);
		$this->assign("iSeuilPrix2Tranche3", $iSeuilPrix2Tranche3);
		$this->assign("iSeuilPrix3Tranche3", $iSeuilPrix3Tranche3);
		$this->assign("iSeuilPrix3Tranche4", $iSeuilPrix3Tranche4);

		/*
		 *  Filtre mobile
		 */
		//Si des filtres ont été sélectionné, on explode le champs dans lequel ils sont stockés
		if($aData['ZONE_TITRE9'] != ""){
			$aFiltreTypeMobile = explode("##",$aData['ZONE_TITRE9']);
		}

		$this->assign("bDisplayFiltreMobile", (count($aFiltreTypeMobile)>1 ? true : false));

		/*
		 *  Configuration
		 */
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

		//Ordre des devises par rapport au prix
		$aOrderDevise = Frontoffice_Devise_Helper::orderDevise($_SESSION[APP]['LANGUE_ID']);

		//nb resultats
		$aFiltresSearch = array();
		$iTypeFiltreSearch = (int)$_POST['filtreType'];
		if($iTypeFiltreSearch == 1){
			foreach($_POST as $key=>$post){
				if(strpos($key, 'silhouette') !== false){
					$aFiltresSearch['SILHOUETTE'][] = $post;
				}
			}
			if($_POST['energy'] && $_POST['energy'] != 'TOUT'){
				$aFiltresSearch['ENERGIE'] = $_POST['energy'];
			}
			if($_POST['gears'] && $_POST['gears'] != 'TOUT'){
				$aFiltresSearch['BOITE_VITESSE'] = $_POST['gears'];
			}
			$aFiltresSearch['PRIX'] = ($_POST["isMobile"] ==  1) ? $_POST['price'] : $_POST['priceLimit'];
			$aFiltresSearch['PASSAGERS'] = $_POST['passengers'];
			$aFiltresSearch['CONSO'] = $_POST['consum'];
			$aFiltresSearch['EMISSION'] = $_POST['co2'];
			$aFiltresSearch['LONGUEUR'] = $_POST['length']*1000;
		}else{
			//Filtres sélectionnés
			for($i=1;$i<4;$i++){
				if(!empty($_POST['critere'.$i])){
					$aFiltresSearch['CRITERES'][] = $_POST['critere'.$i];
				}
			}
			if(!empty($_POST['cash'])){
				if(strpos($_POST['cash'], 'lt') !== false){
					$aFiltresSearch['PRIX']['START'] = substr($_POST['cash'], 2);
				}
				if(strpos($_POST['cash'], 'gt') !== false){
					$aFiltresSearch['PRIX']['END'] = substr($_POST['cash'], 2);
				}
				if(strpos($_POST['cash'], 'b') !== false){
					$iPrix = substr($_POST['cash'], 1);
					$aPrix = explode('a',$iPrix);
					$aFiltresSearch['PRIX']['START'] = $aPrix[0];
					$aFiltresSearch['PRIX']['END'] = $aPrix[1];
				}
			}
		}

		include_once(Pelican::$config["CONTROLLERS_ROOT"].'/Layout/Citroen/CarSelector/Resultats.php');
		$nbResult = Layout_Citroen_CarSelector_Resultats_Controller::getVehicules($_SESSION[APP]['SITE_ID'],$_SESSION[APP]['LANGUE_ID'], $iTypeFiltreSearch, $aFiltresSearch, $_POST["isMobile"], true, $typeGamme);
		$this->assign("aOrderDevise", $aOrderDevise);
		$this->assign("aData", $aData);
		$this->assign("sEmission", $sEmission);
		$this->assign("sDevise", $sDevise);
		$this->assign("sConso", $sConso);
		$this->assign("sTaille", $sTaille);
		$this->assign("nbResult", $nbResult);
		if($aData['ZONE_URL'])
		{
			$this->assign("OTHER_CAR_SELECTOR", $aData['ZONE_URL']);
		}

        $this->fetch();
    }


}