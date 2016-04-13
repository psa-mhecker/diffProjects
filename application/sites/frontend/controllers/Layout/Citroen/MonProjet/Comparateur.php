<?php

require_once(Pelican::$config['APPLICATION_CONTROLLERS'] . "/Layout/Citroen/Comparateur.php");

use Citroen\GammeFinition\VehiculeGamme;
use Citroen\SelectionVehicule;

/**
 * Classe d'affichage Front de la tranche Comparateur de Mon projet
 *
 * @package Layout
 * @subpackage Citroen
 */
class Layout_Citroen_MonProjet_Comparateur_Controller extends Layout_Citroen_Comparateur_Controller
{

	public function indexAction()
	{

		$aParams = $this->getParams();
		$this->assign('aParams', $aParams);

		if (isset($_GET['COMPARER'])) {
			$aGroupeReseauxSociaux = Pelican_Cache::fetch("Frontend/Citroen/GroupeReseauxSociaux", array(
					$aParams['ZONE_TITRE3'],
					$_SESSION[APP]['SITE_ID'],
					$_SESSION[APP]['LANGUE_ID']
			));

			$this->assign('aGroupeReseauxSociaux', $aGroupeReseauxSociaux);

			//Sharer
			$sSharer = Backoffice_Share_Helper::getSharer($aParams['ZONE_TITRE3'], $aParams['SITE_ID'], $aParams['LANGUE_ID'], Pelican::$config['MODE_SHARER'][0], array('getParams' => $aParams));
			$this->assign("sSharer", $sSharer);

			$user = \Citroen\UserProvider::getUser();
			$this->assign('user', $user);

			$vehiculesCompares = array();
			// Chargement automatique de la derniere comparaison effectué par l'utilisateur
			if (isset($_GET['lastcomp']) && isset($_SESSION[APP]['COMPARATEUR_PERSO'])) {
				$this->assign('aVehiculeInSession', $_SESSION[APP]['COMPARATEUR_PERSO']);
				foreach ($_SESSION[APP]['COMPARATEUR_PERSO'] as $key => $vehicule) {
					if (isset($vehicule['LCDV6']) && !empty($vehicule['LCDV6'])) {
						$aLcdv6Gamme['LCDV6'] = $vehicule['LCDV6'];
						$aLcdv6Gamme['GAMME'] = Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VP'];
						$aFinitions = Pelican_Cache::fetch("Frontend/Citroen/Finitions", array(
								$aLcdv6Gamme,
								$_SESSION[APP]['SITE_ID'],
								$_SESSION[APP]['LANGUE_ID']
						));
						if (is_array($aFinitions)) {
							foreach ($aFinitions as $aFinition) {
								if ($aFinition['GAMME'] == Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VP']) {
									$finitionsSelect[$key][$aFinition['FINITION_CODE']]['FINITION_LABEL'] = $aFinition['FINITION_LABEL'];
									$finitionsSelect[$key][$aFinition['FINITION_CODE']]['LCDV6'] = $aLcdv6Gamme['LCDV6'];
								}
							}
						}
						if ($vehicule['FINITION_CODE']) {
							// Récupération des motorisations
							$aEngineList = VehiculeGamme::getEngineList($vehicule['FINITION_CODE'], $vehicule['LCDV6'], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']);
							if (is_array($aEngineList)) {
								foreach ($aEngineList as $aEngine) {
									$engineSelect[$key][$aEngine['ENGINE_CODE']]['ENGINE_LABEL'] = $aEngine['ENGINE_LABEL'];
								}
							}
						}
					}
				}
				$this->assign('finitionsSelect', $finitionsSelect);
				$this->assign('engineSelect', $engineSelect);
			}
			// Chargement du véhicule par défaut de l'utilsateur
			else {
				// $_GET['select_vehicule'] défini dans le controleur Layout_Citroen_MonProjet_SelectionVehicules_Controller
				if ($_GET['select_vehicule']) {
					$vehiculesCompares[0] = array('id' => $_GET['select_vehicule']);

					$aLcdv6Gamme = VehiculeGamme::getLCDV6Gamme($vehiculesCompares[0]['id'], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']);
					$this->assign('aLcdv6Gamme', $aLcdv6Gamme);

					$aVehicule = VehiculeGamme::getVehiculeByLCDVGamme($aLcdv6Gamme['LCDV6'], $aLcdv6Gamme['GAMME'], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']);
					$this->assign('aVehicule', $aVehicule);

					$aFinitions = Pelican_Cache::fetch("Frontend/Citroen/Finitions", array(
							$aLcdv6Gamme,
							$_SESSION[APP]['SITE_ID'],
							$_SESSION[APP]['LANGUE_ID']
					));
					$finitionsSelect = array();
					if (is_array($aFinitions)) {
						foreach ($aFinitions as $aFinition) {
							if ($aFinition['GAMME'] == Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VP']) {
								$finitionsSelect[$aFinition['FINITION_CODE']]['FINITION_LABEL'] = $aFinition['FINITION_LABEL'];
								$finitionsSelect[$aFinition['FINITION_CODE']]['LCDV6'] = $aLcdv6Gamme['LCDV6'];
							}
						}
					}
				}
				$this->assign('finitionsSelect', $finitionsSelect);
			}
			if ($finitionsSelect) {
				$aVehiculesFromNavigation = self::getVehiculeModeleFromNavigation();
				$aCompSelect = array();
				$aCompSelect['LISTE1']['MODELS'] = $aVehiculesFromNavigation;
				$aCompSelect['LISTE2']['MODELS'] = $aVehiculesFromNavigation;
				$aCompSelect['LISTE3']['MODELS'] = $aVehiculesFromNavigation;
				$this->assign('aCompSelect', $aCompSelect);
			}
		}
		$this->fetch();
	}

	public function getEngineByFinitionAjaxAction()
	{
		Pelican_Request::call('/_/Layout_Citroen_Comparateur/getEngineByFinitionAjax');
		$this->_template = Pelican::$config['APPLICATION_VIEWS'] . '/Layout/Citroen/Comparateur/getEngineByFinitionAjax.tpl';
		$this->fetch();
	}

	public function getFinitionsByModelAjaxAction()
	{
		Pelican_Request::call('/_/Layout_Citroen_Comparateur/getFinitionsByModelAjax');
		$this->_template = Pelican::$config['APPLICATION_VIEWS'] . '/Layout/Citroen/Comparateur/getFinitionsByModelAjax.tpl';
		$this->fetch();
	}

}
