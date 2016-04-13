<?php

include_once (Pelican::$config['APPLICATION_CONTROLLERS'] . '/Citroen.php');

use Citroen\Financement;
use Citroen\GammeFinition\VehiculeGamme;

class Layout_Citroen_CarSelector_Resultats_Controller extends Citroen_Controller
{

	public function indexAction()
	{
		$aData = $this->getParams();
		//Récupération des filtres
		$aFiltres = array();
		$iTypeFiltre = (int) $_POST['filtreType'];
		$isMobile = (int) $_POST['isMobile'];
		if ($iTypeFiltre == 1) {
			foreach ($_POST as $key => $post) {
				if (strpos($key, 'silhouette') !== false) {
					$aFiltres['SILHOUETTE'][] = $post;
				}
			}
			if ($_POST['energy'] && $_POST['energy'] != 'TOUTENERGY') {
				$aFiltres['ENERGIE'] = $_POST['energy'];
			}
			if ($_POST['gears'] && $_POST['gears'] != 'TOUTGEARS') {
				$aFiltres['BOITE_VITESSE'] = $_POST['gears'];
			}
			$aFiltres['PRIX'] = ($_POST["isMobile"] == 1) ? $_POST['price'] : $_POST['priceLimit'];
			$aFiltres['PASSAGERS'] = $_POST['passengers'];
			$aFiltres['CONSO'] = $_POST['consum'];
			$aFiltres['EMISSION'] = $_POST['co2'];
			$aFiltres['LONGUEUR'] = $_POST['length'] * 1000;
		} else {
			//Filtres sélectionnés
			for ($i = 1; $i < 4; $i++) {
				if (!empty($_POST['critere' . $i])) {
					$aFiltres['CRITERES'][] = $_POST['critere' . $i];
				}
			}
			if (!empty($_POST['cash'])) {
				if (strpos($_POST['cash'], 'lt') !== false) {
					$aFiltres['PRIX']['START'] = substr($_POST['cash'], 2);
				}
				if (strpos($_POST['cash'], 'gt') !== false) {
					$aFiltres['PRIX']['END'] = substr($_POST['cash'], 2);
				}
				if (strpos($_POST['cash'], 'b') !== false) {
					$iPrix = substr($_POST['cash'], 1);
					$aPrix = explode('a', $iPrix);
					$aFiltres['PRIX']['START'] = $aPrix[0];
					$aFiltres['PRIX']['END'] = $aPrix[1];
				}
			}
		}

		//Récupération des vehicules
			$aPageFiltre = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
				$aData['PAGE_ID'],
				"1811",
				$aData['PAGE_VERSION'],
				$aData['LANGUE_ID']
				));


			switch ($aPageFiltre["ZONE_TITRE11"]) {
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
		$aVehicules = self::getVehicules($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $iTypeFiltre, $aFiltres, $isMobile, false, $typeGamme);

		if(count($aVehicules)){

			$aParams = $aDatas;
			$aDatas['CTA'] = array(
	            'BARRE_OUTILS_URL_WEB' => '##URL_CONFIGURATEUR##',
	            'BARRE_OUTILS_MODE_OUVERTURE' => 2,
	            'BARRE_OUTILS_TITRE' => t('CONFIGURER'),   
	            'NO_SPAN'=>true,        
	        );
			foreach($aVehicules['VEHICULES'] as $key_line=>$ligne){
				
				$aDatas['CTA']['COLOR'] = ($ligne['LABEL'] == 'LA_LIGNE_DS')?'brown':'grey';
			    
			    foreach ($ligne['CARS'] as  $key=>$vehicule){
			    	  $aDatas['vehicule'] = $vehicule;
	      			$aVehicules['VEHICULES'][$key_line]['CARS'][$key]['CONFIGURATEUR'] =  Pelican_Request::call('_/Layout_Citroen_CTA/',$aDatas);

			    }  
				
			}
		}
		//Récupération autres vehicules
		$aAutresVehicules = Pelican_Cache::fetch("Frontend/Citroen/CarSelector/AutresVehicules", array(
				$aData['ZONE_TEMPLATE_ID'],
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID']
		));
		if (is_array($aAutresVehicules) && count($aAutresVehicules) > 0) {
			foreach ($aAutresVehicules as $key => $result) {
				$aAutresVehicules[$key]['MEDIA_PATH'] = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getFileNameMediaFormat($result['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['CAR_SELECTOR_OTHER_CARS']);
				$aAutresVehicules[$key]['MEDIA_PATH_MOBILE'] = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getFileNameMediaFormat($result['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['CAR_SELECTOR_OTHER_CARS_MOBILE']);
			}
		}
		$this->assign('aVehicules', $aVehicules);
		$this->assign('aAutresVehicules', $aAutresVehicules);
		$this->assign('aData', $aData);


		//Mentions légales
		if ($aData['ZONE_TITRE7'] != '') {
			$aMentionsLegales = Pelican_Cache::fetch("Frontend/Page", array(
					$aData['ZONE_TITRE7'],
					$_SESSION[APP]['SITE_ID'],
					$_SESSION[APP]['LANGUE_ID'],
					Pelican::getPreviewVersion()
			));
		}
		if ($aData['MEDIA_ID4'] != '') {
			$sVisuelML = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aData['MEDIA_ID4']), Pelican::$config['MEDIA_FORMAT_ID']['ACTUALITES_PETIT']);
		}
		$this->assign('aMentionsLegales', $aMentionsLegales);
		$this->assign('sVisuelML', $sVisuelML);
		//modif label comparer
		$this->assign('useCompareVehicule', $aData["ZONE_TITRE11"]);
		$this->fetch();
	}

	public function addToCompareAction()
	{
		include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Vehicule.php');
		$aData = $this->getParams();
                $aComparateur = Pelican_Cache::fetch('Frontend/Page/Template', array(
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    Pelican::getPreviewVersion(),
                    Pelican::$config['TEMPLATE_PAGE']['COMPARATEUR']
                ));
                if(isset($aData['vehiculeId'])&& !empty($aData['vehiculeId'])){
                    //récuperer le bon vehicule
                    $aVehicule = Pelican_Cache::fetch(
                            'Frontend/Citroen/VehiculeById',
                             array(
                                $aData['vehiculeId'],
                                $_SESSION[APP]['SITE_ID'],
                                $_SESSION[APP]['LANGUE_ID'],
                             )
                      );
                    //recuperer le conde LCDV6
                    if(is_array($aVehicule) && !empty($aVehicule)){
                        ($aVehicule['VEHICULE_LCDV6_CONFIG'])? $sLcdv6= $aVehicule['VEHICULE_LCDV6_CONFIG']:$aVehicule['VEHICULE_LCDV6_MANUAL'];
                    }
                }

                if(is_array($aComparateur)&&!empty($aComparateur)){
                    $sUrlComparateur = $aComparateur['PAGE_CLEAR_URL'];
                }
                //récuperer la page appelante
                if(isset($aData['invoker'])&&!empty($aData['invoker'])){
                    switch($aData['invoker']){
                        //cas du car selector appelant le comparateur
                        case 'CARSELECTOR':

                            $sUrlParams = sprintf(
                                    'invoker=%s&vehicule_id[]=%s&lcdv6[]=%s',
                                    'CARSELECTOR',
                                    $aData['vehiculeId'],
                                    $sLcdv6
                                    );
                            $sRedirectUrl = Pelican::$config['DOCUMENT_HTTP'].$sUrlComparateur.'?'.$sUrlParams;
                            $this->getRequest()->addResponseCommand('script', array(
                                'value' => 'document.location.href="'.Citroen\Url::parse($sRedirectUrl).'";'
				));
                            break;

                    }
                }
		$bReturn = Frontoffice_Vehicule_Helper::putVehiculeCompInSession($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $aData['vehiculeId']);
		$sAlerte = ($bReturn == true) ? t('ADD_COMPARATEUR_OK') : t('ADD_COMPARATEUR_KO');
		/*$this->getRequest()->addResponseCommand('script', array(
			'value' => "alert('" . $sAlerte . "');"
		));
                if($bReturn){
                   $this->getRequest()->addResponseCommand('script', array(
			'value' => '$(li a[rel="'.$aData['finitionId'].'"]).hide();'
                        )
                   );
                }*/
	}

	public function carSelectorResultsAction()
	{
		$aPost = $this->getParams();

		$this->assign("aPost", $aPost);
		/*
		 *  Page globale
		 */
		$aData = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
				$aPost['pageId'],
				Pelican::$config['ZONE_TEMPLATE_ID']['CAR_SELECTOR_RESULTATS'],
				$aPost['pageVersion'],
				$aPost['langueID']
		));
		$this->assign("useCompareVehicule", $aData["ZONE_TITRE11"]);
		$aFiltres = array();
		$iTypeFiltre = (int) $aPost['filtreType'];
		$isMobile = (int) $aPost['isMobile'];
		if ($iTypeFiltre == 1) {
			foreach ($aPost as $key => $post) {
				if (strpos($key, 'silhouette') !== false) {
					$aFiltres['SILHOUETTE'][] = $post;
				}
			}
			if ($aPost['energy'] && $aPost['energy'] != 'TOUTENERGY') {
				$aFiltres['ENERGIE'] = $aPost['energy'];
			}
			if ($aPost['gears'] && $aPost['gears'] != 'TOUTGEARS') {
				$aFiltres['BOITE_VITESSE'] = $aPost['gears'];
			}
			$aFiltres['PRIX'] = ($aPost["isMobile"] == 1) ? $aPost['price'] : $aPost['priceLimit'];
			$aFiltres['PASSAGERS'] = $aPost['passengers'];
			$aFiltres['CONSO'] = $aPost['consum'];
			$aFiltres['EMISSION'] = $aPost['co2'];
			$aFiltres['LONGUEUR'] = $aPost['length'] * 1000;
		} else {
			//Filtres sélectionnés
			for ($i = 1; $i < 4; $i++) {
				if (!empty($aPost['critere' . $i])) {
					$aFiltres['CRITERES'][] = $aPost['critere' . $i];
				}
			}
			if (!empty($aPost['cash'])) {
				if (strpos($aPost['cash'], 'lt') !== false) {
					$aFiltres['PRIX']['END'] = substr($aPost['cash'], 2);
				}
				if (strpos($aPost['cash'], 'gt') !== false) {
					$aFiltres['PRIX']['START'] = substr($aPost['cash'], 2);
				}
				if (strpos($aPost['cash'], 'b') !== false) {
					$iPrix = substr($aPost['cash'], 1);
					$aPrix = explode('a', $iPrix);
					$aFiltres['PRIX']['START'] = $aPrix[0];
					$aFiltres['PRIX']['END'] = $aPrix[1];
				}
			}
		}

		//Récupération des vehicules

			$aPageFiltre = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
				$aPost['pageId'],
				"1811",
				$aPost['pageVersion'],
				$aPost['langueID']
				));
			switch ($aPageFiltre["ZONE_TITRE11"]) {
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
		$aVehicules = self::getVehicules($aPost['siteID'], $aPost['langueID'], $iTypeFiltre, $aFiltres, $isMobile, false, $typeGamme);

		if(count($aVehicules)){

			$aParams = $aDatas;
			$aDatas['CTA'] = array(
	            'BARRE_OUTILS_URL_WEB' => '##URL_CONFIGURATEUR##',
	            'BARRE_OUTILS_MODE_OUVERTURE' => 2,
	            'BARRE_OUTILS_TITRE' => t('CONFIGURER'),   
	            'NO_SPAN'=>true,        
	        );
			foreach($aVehicules['VEHICULES'] as $key_line=>$ligne){
				
				$aDatas['CTA']['COLOR'] = ($ligne['LABEL'] == 'LA_LIGNE_DS')?'brown':'grey';
			    
			    foreach ($ligne['CARS'] as  $key=>$vehicule){
			    	  $aDatas['vehicule'] = $vehicule;
	      			$aVehicules['VEHICULES'][$key_line]['CARS'][$key]['CONFIGURATEUR'] =  Pelican_Request::call('_/Layout_Citroen_CTA/',$aDatas);

			    }  
				
			}
		}
		$this->assign('aVehicules', $aVehicules);

		$this->assign('aData', $aData);


		//Mentions légales
		if ($aData['ZONE_TITRE7'] != '') {
			$aMentionsLegales = Pelican_Cache::fetch("Frontend/Page", array(
					$aData['ZONE_TITRE7'],
					$aPost['siteID'],
					$aPost['langueID'],
					Pelican::getPreviewVersion()
			));
		}
		if ($aData['MEDIA_ID4'] != '') {
			$sVisuelML = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aData['MEDIA_ID4']), Pelican::$config['MEDIA_FORMAT_ID']['ACTUALITES_PETIT']);
		}

		
		$this->assign('aMentionsLegales', $aMentionsLegales);
		$this->assign('sVisuelML', $sVisuelML);

		$this->assign('nbResults', ($aVehicules['COUNT'] == '' ? '0' : $aVehicules['COUNT']) . ' <span>' . ($aVehicules['COUNT'] > 1 ? t('RESULTATS') : t('RESULTAT')) . '</span>');

		$this->fetch();
	}

	/**
	 * Fonction récupérant les vehicules
	 * @param $iSiteId int : Identifiant du site
	 * @param $iLangueId int : Identifiant de la langue sélectionné
	 * @param $iTypeFiltre int : Identifiant du type de filtre (1 ou 2)
	 * @param $aFiltres array : Tableau des filtres sélectionnés
	 * @return $aResults array : Tableau de vehicules
	 */
	public static function getVehicules($iSiteId, $iLangueId, $iTypeFiltre, $aFiltres = array(), $isMobile = 0, $isCount = false, $typeGamme = "")
	{
		require_once('Pelican/Media.php');
		/*
		 *  Page globale
		 */
		$pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
				$iSiteId,
				$iLangueId,
				'CURRENT',
				Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
		));
		/*
		 *  Configuration
		 */
		$aConfiguration = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
				$pageGlobal['PAGE_ID'],
				Pelican::$config['ZONE_TEMPLATE_ID']['CONFIGURATION'],
				$pageGlobal['PAGE_VERSION'],
				$iLangueId
		));
		$sDevise = $aConfiguration['ZONE_TITRE2'];

		$aConfiguration = Pelican_Cache::fetch("Frontend/Citroen/Configuration", array(
				$iSiteId,
				$iLangueId,			
				Pelican::getPreviewVersion()
		));


		$aResults = Pelican_Cache::fetch("Frontend/Citroen/CarSelector/Resultats", array(
				$iSiteId,
				$iLangueId,
				$iTypeFiltre,
				$aFiltres,
				$isMobile,
				$typeGamme
		));
		if ($isCount == true) {
			return count($aResults);
			die;
		}
		

		$aVehicules = array();
		$sCodePays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));
		$sCodeLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
		$sCodeLangue = $sCodeLangue . "-" . strtolower($sCodePays);
		$iAffichPrixCredit = Frontoffice_Zone_Helper::getAffichePrixCredit();
		if (is_array($aResults) && count($aResults) > 0) {
			foreach ($aResults as $key => $result) {
				/*$sPrixHT = '';
				$sPrixTTC = '';
				if (!empty($result['VEHICULE_CASH_PRICE_TYPE'])) {
					if ($result['VEHICULE_CASH_PRICE_TYPE'] == 'CASH_PRICE_TTC') {
						$sPrixTTC = $result['VEHICULE_CASH_PRICE'];
					} else {
						$sPrixHT = $result['VEHICULE_CASH_PRICE'];
					}
				}
				if ($iAffichPrixCredit == 2) {
					$aResults[$key]['CREDIT'] = Financement::getCreditPrice($sCodePays, $sCodeLangue, Pelican::$config['DEVISE'][trim($sDevise)], $result['LCDV6'], $result['MODEL_LABEL'], '', $result['GAMME'], $sPrixHT, $sPrixTTC);
				}*/
				$aResults[$key]['MEDIA_PATH'] = Pelican_Media::getFileNameMediaFormat($result['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['WEB_CAR_SELECTOR_MASTER_N1_ET_N2']);
				$aResults[$key]['MEDIA_PATH_MOBILE'] = Pelican_Media::getFileNameMediaFormat($result['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['CAR_SELECTOR_RESULTATS_MOBILE']);

				$lcdvGamme = Citroen\GammeFinition\VehiculeGamme::getLCDV6Gamme(  
                            $result["VEHICULE_ID"],
                            $iSiteId,
                            $iLangueId
                            );
                $result = array_merge($result,$lcdvGamme);
                    
				
				
				//str_replace('##LCDV_CURRENT##', $result['LCDV6'], $aConfiguration['ZONE_TITRE_5']);
				//$aResults[$key]['URL_DETAIL'] = Frontoffice_Vehicule_Helper::getUrlDetailCar($result['VEHICULE_ID'],$_SESSION[APP]['SITE_ID'],$_SESSION[APP]['LANGUE_ID']);
				$aTemp = VehiculeGamme::getShowRoomVehicule($iSiteId, $iLangueId, $result['VEHICULE_ID'], $aFiltres['PRIX']['START']);
				$aResults[$key]['REGLE_PRIX'] = $aTemp[0];
				$aResults[$key]['FINANCEMENT'] = $aTemp[1];

				print_r("\n");
				switch ($result['VEHICULE_GAMME_LABEL']) {
					case 'GAMME_LIGNE_DS' :
						$aVehicules['VEHICULES'][0]['CARS'][] = $aResults[$key];
						break;
					case 'GAMME_LIGNE_C' :
						$aVehicules['VEHICULES'][1]['CARS'][] = $aResults[$key];
						break;
					default :
						$aVehicules['VEHICULES'][2]['CARS'][] = $aResults[$key];
						break;
				}
			}
		}
		if (!empty($aVehicules['VEHICULES'])) {
			if (!empty($aVehicules['VEHICULES'][0])) {
				$aVehicules['VEHICULES'][0]['LABEL'] = 'LA_LIGNE_DS';
			}
			if (!empty($aVehicules['VEHICULES'][1])) {
				$aVehicules['VEHICULES'][1]['LABEL'] = 'LA_LIGNE_C';
			}
			if (!empty($aVehicules['VEHICULES'][2])) {
				$aVehicules['VEHICULES'][2]['LABEL'] = 'UTILITAIRES';
			}
			$aVehicules['COUNT'] = count($aResults);
			ksort($aVehicules['VEHICULES']);
		}

		return $aVehicules;
	}

}
