<?php

use Citroen\GammeFinition\VehiculeGamme;
use Citroen\Financement;

require_once(Pelican::$config['APPLICATION_CONTROLLERS'] . '/Layout/Citroen/Comparateur.php');
require_once(Pelican::$config['APPLICATION_CONTROLLERS'] . '/Layout/Citroen/MonProjet/SelectionVehicules.php');

/**
 * Classe d'affichage Front de la tranche Message informatif de Mon projet
 *
 * @package Layout
 * @subpackage Citroen
 * @author Ayoub Hidri <ayoub.hidri@businessdecision.com>
 * @since 14/10/2013
 */
class Layout_Citroen_SimulateurFinancement_Controller extends Pelican_Controller_Front
{

	public function indexAction()
	{
		$aParams = $this->getParams();
		if ($aParams['ZONE_WEB'] == 1) {
			$bTrancheVisible = true;
			$step2action = '/_/Layout_Citroen_SimulateurFinancement/step2Ajax';
			//$step2action =  '/_/Layout_Citroen_MonProjet_SimulateurFinancement/step2Ajax';
			// Dans le gabarit Mon Projet / Mes sélections, la tranche utilise le véhicule selectionné et ne s'affiche que si celui-ci est défini.
			if ($aParams['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION']) {
				
				$step2action =  '/_/Layout_Citroen_MonProjet_SimulateurFinancement/step2Ajax';

				if (!isset($_GET['FINANCER'])) {
					$bTrancheVisible = false;
				} else {
					$oUser = \Citroen\UserProvider::getUser();
					if (!$oUser || !$oUser->isLogged()) {
						$bTrancheVisible = false;
					}
				}
			}
			if ($bTrancheVisible) {
				$iStep = 1;
				// On récupère les véhicules via les pages Showroom Accueil tout en gardant l'ordre des pages.
				$aVehiculesFromNavigation = Layout_Citroen_Comparateur_Controller::getVehiculeModeleFromNavigation();
				// On récupère tous les véhicules VP du configurateur
				$aVehiculesFromConfigurateur = VehiculeGamme::getVehiculesGamme($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']);
				if (count($aVehiculesFromConfigurateur)) {
					foreach ($aVehiculesFromConfigurateur as $aVehiculeFromConfigurateur) {
						if ($aVehiculeFromConfigurateur['GAMME'] == Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VP']) {
							$aVehiculesVpFromConfigurateur[$aVehiculeFromConfigurateur['VEHICULE_ID']] = $aVehiculeFromConfigurateur['MODEL_LABEL'];
						}
					}

					// Si un véhicule du configurateur n'existe pas dans les pages showroom alors on les positionnent à la fin
					if ($aVehiculesVpFromConfigurateur) {
						foreach ($aVehiculesVpFromConfigurateur as $idVehicule => $labelVehicule) {
							if (!array_key_exists($idVehicule, $aVehiculesFromNavigation)) {
								$aVehiculesFromNavigation[$idVehicule] = $labelVehicule;
							}
						}
					}
				}

				if (isset($aParams['ZONE_PARAMETERS'])) {
					if ($aParams['ZONE_PARAMETERS'] == 'pixel') {
						$sIframeUnit = 'px';
					} elseif ($aParams['ZONE_PARAMETERS'] == 'percent') {
						$sIframeUnit = '%';
					}
				}
				$this->assign('aVehicules', $aVehiculesFromNavigation);
				$this->assign('iStep', $iStep);
				$this->assign('sIframeUnit', $sIframeUnit);
			}

			//véhicule prérenseigné
			if(isset($_GET['Car'])&&!empty($_GET['Car'])){
				$this->assign('sLcdv6PreRempli',$_GET['Car']);
			}

			$this->assign('bTrancheVisible', $bTrancheVisible);
			$this->assign('aParams', $aParams);
			$this->assign('step2action', $step2action);
			$this->fetch();
		}
	}

	public function getFinitionsByGammeAjaxAction()
	{
		$aParams = $this->getParams();
		$aFinitions = Pelican_Cache::fetch("Frontend/Citroen/SimulateurFinancement/Finitions", array(
			$aParams['v'], //LCDV6
			Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VP'],
			$_SESSION[APP]['SITE_ID'],
			$_SESSION[APP]['LANGUE_ID'],
		));

		// Récupération des informations su le véhicule
		$aVehicule = VehiculeGamme::getVehiculeByLCDVGamme($aParams['v'], Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VP'], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']);

		$this->assign('finitionsSelect', $aFinitions);
		$this->assign('sLCDV6', $aParams['v']);
		$this->assign('aVehicule', $aVehicule);
		$this->assign('aParams', $aParams);
		$this->fetch();
	}

	public function getEnginesByFinitionAjaxAction()
	{
		$aParams = $this->getParams();
		if (isset($aParams['v'])) {
			$aArgs = explode('|', $aParams['v']);
		}
		$aEngineList = VehiculeGamme::getEngineList($aArgs[0], $aArgs[1], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']);

		// Récupération des informations su le véhicule
		$aVehicule = VehiculeGamme::getVehiculeByLCDVGamme($aParams['v'], Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VP'], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']);

		$this->assign('enginesSelect', $aEngineList);
		$this->assign('sFinitionCode', $aParams['v']);
		$this->assign('sLCDV6', $aParams['lcdv6']);
		$this->assign('aVehicule', $aVehicule);
		$this->fetch();
	}

	public function step2AjaxAction()
	{
		$aParams = $this->getParams();
		$sLcdv6 = $aParams['sim_fin_select0'];
		$aFinition = explode('|', $aParams['sim_fin_select1']);
		$aEngine = explode('|', $aParams['sim_fin_select2']);
		$sFinition = $aFinition[0];
		$sEngine = $aEngine[2];
		$sIframeUrl = $this->etape2($sLcdv6, $sFinition, $sEngine);
		($aParams['ZONE_PARAMETERS'] == 'percent') ? $sIframeUnit = '%' : $sIframeUnit = 'px';
		$aVehicule = Layout_Citroen_MonProjet_SelectionVehicules_Controller::getInfosVehicule($sLcdv6, $sFinition, $sEngine);
		//print_r($aVehicule);
		$this->assign('aData', $aVehicule);
		//$this->fetch();
		$this->getRequest()->addResponseCommand('assign', array(
			'id' => 'sim_fin_step2_iframe',
			'attr' => 'src',
			'value' => $sIframeUrl
		));
		$this->getRequest()->addResponseCommand('assign', array(
			'id' => 'result-wrapper',
			'attr' => 'innerHTML',
			'value' => $this->getResponse()
		));
		$this->fetch();
	}

	protected function etape2($sLcdv6, $sFinition, $sVersion)
	{
		/*
		  $pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
		  $_SESSION[APP]['SITE_ID'],
		  $_SESSION[APP]['LANGUE_ID'],
		  Pelican::getPreviewVersion(),
		  Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
		  ));
		 * */

		/*
		 *  Configuration
		 */
		/*    $aConfiguration = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
		  $pageGlobal['PAGE_ID'],
		  Pelican::$config['ZONE_TEMPLATE_ID']['CONFIGURATION'],
		  $pageGlobal['PAGE_VERSION'],
		  $_SESSION[APP]['LANGUE_ID']
		  ));
		 */
		//getVehicule
		/*    $aVehicules = VehiculeGamme::getVehiculesGamme(
		  $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $sLCDV6, null, 'combo'
		  );



		  if (array_key_exists('VEHICULE_LCDV6_CONFIG', $aVehicules[0]) && !empty($aVehicules[0]['VEHICULE_LCDV6_CONFIG'])) {
		  $mVehiculeFirstCashPrice = \Citroen\GammeFinition\VehiculeGamme::getWSVehiculeFirstCashPrice($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $aVehicules[0]['VEHICULE_LCDV6_CONFIG']);
		  } else {
		  /* Prix comptant
		  $mVehiculeFirstCashPrice = $aVehicules[0]['VEHICULE_CASH_PRICE'];
		  } */
		/*
		  if (isset($aVehicules[0]['VEHICULE_CASH_PRICE_TYPE']) && !empty($aVehicules[0]['VEHICULE_CASH_PRICE_TYPE'])) {
		  if ($aVehicules[0]['VEHICULE_CASH_PRICE_TYPE'] == Pelican::$config['TAXE_TYPE']['TTC']) {
		  $sPrixHTVehicule = null;
		  $sPrixTTCVehicule = $mVehiculeFirstCashPrice;
		  } else {
		  $sPrixHTVehicule = $mVehiculeFirstCashPrice;
		  $sPrixTTCVehicule = null;
		  }
		  }
		 */
		// $sDevise = $aConfiguration['ZONE_TITRE2'];

		/* $sGammeVehicule = $aVehicules[0]['GAMME'];
		  $sLabelVehicule = $aVehicules[0]['MODEL_LABEL'];
		 */
		/* $aResultats = Financement::saveCalculationDisplay(
		  $sCodePays, $sCodeLangue, $sDevise, $sLCDV6, $sLabelVehicule, '', $sGammeVehicule, $sPrixHTVehicule, $sPrixTTCVehicule
		  ); */


		$sIframeUrl = '';
		$serviceParams = array(
			'country' => $sPays,
			'language' => $sLanguage,
			'financingMake' => 'AC',
			'currency' => $sDevise,
			'flowDate' => gmdate("Y-m-d\TH:i:s.uP")
		);
		try {
			$service = \Itkg\Service\Factory::getService('CITROEN_SERVICE_SIMULFIN', array());
			$iIdSession = $service->call('openSession', $serviceParams);
			if ($iIdSession) {
				$sCodePays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));
				$sCodePays = strtolower($sCodePays);
				$sCodeLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
				$sCodeLangue = $sCodeLangue . "-" . $sCodePays;
				$sIframeUrl = sprintf('http://finance-bpf.citroen.com/%s/Site_finance/perso/indexPerso.html?Session=%s|%s|Citroen|true|%s|%s|AC|648', $sCodePays, $iIdSession, Itkg::$config['CITROEN_SERVICE_SIMULFIN']['PARAMETERS']['login'], $sCodePays, $sCodeLangue);
			}
		} catch (\Exception $e) {
			// TODO à passer dans les logs
			//echo $e->getMessage();
		}
		return $sIframeUrl;
	}

	public function getVehiculeImagePrixAjaxAction()
	{
		$aParams = $this->getParams();
		$urlArgs = explode('|', $aParams['v']);
		$sFinition = $urlArgs[0];
		(isset($urlArgs[1])) ? $sLcdv6 = $urlArgs[1] : $sLcdv6 = null;
		(isset($urlArgs[2])) ? $sVersion = $urlArgs[2] : $sVersion = null;

		$aData = Layout_Citroen_MonProjet_SelectionVehicules_Controller::getInfosVehicule($sLcdv6, $sFinition, $sVersion);
		$this->assign('IMAGE', $aData['IMAGE']);
		$this->assign('PRICE_TYPE', $aData['PRICE_TYPE']);
		$this->assign('aData', $aData);
		$this->fetch();
		$this->getRequest()->addResponseCommand('assign', array(
			'id' => 'result-wrapper',
			'attr' => 'innerHTML',
			'value' => $this->getResponse()
		));
	}

	public function _cleanCacheAction()
	{
		Pelican_Cache::clean("Frontend/Citroen/SimulateurFinancement/Finitions");
		Pelican_Cache::clean("Citroen/GammeVehiculeGamme");
	}

}
