<?php

require_once(Pelican::$config['APPLICATION_CONTROLLERS'] . "/Layout/Citroen/Comparateur.php");

use Citroen\GammeFinition\VehiculeGamme;

/**
 * Classe d'affichage Front de la tranche Comparateur de Mon projet
 *
 * @package Layout
 * @subpackage Citroen
 */
class Layout_Citroen_MonProjet_DerniereComparaison_Controller extends Layout_Citroen_Comparateur_Controller
{

	public function indexAction()
	{
		$aParams = $this->getParams();
		$this->assign('aParams', $aParams);

		//
		if (isset($_GET['COMPARER'])) {
			$user = \Citroen\UserProvider::getUser();
			$this->assign('user', $user);

			$this->assign('aVehiculeInSession', $_SESSION[APP]['COMPARATEUR_PERSO']);

			if ($_SESSION[APP]['COMPARATEUR_PERSO']) {
				// Récupération des véhicules
				$aVehiculesFromNavigation = self::getVehiculeModeleFromNavigation();
				$this->assign('aVehiculesFromNavigation', $aVehiculesFromNavigation);

				// Récupération des finitions pour les véhicules
				$finitionsSelect = array();
				$engineSelect = array();
				if ($_SESSION[APP]['COMPARATEUR_PERSO']) {
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
									//if ($aFinition['GAMME'] == Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VP']) {
										$finitionsSelect[$key][$aFinition['FINITION_CODE']]['FINITION_LABEL'] = $aFinition['FINITION_LABEL'];
										$finitionsSelect[$key][$aFinition['FINITION_CODE']]['LCDV6'] = $aLcdv6Gamme['LCDV6'];

										// Récupération des motorisations
										$aEngineList = VehiculeGamme::getEngineList($aFinition['FINITION_CODE'], $vehicule['LCDV6'], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']);
										if (is_array($aEngineList)) {
											foreach ($aEngineList as $aEngine) {
												$engineSelect[$aEngine['ENGINE_CODE']]['ENGINE_LABEL'] = $aEngine['ENGINE_LABEL'];
												$engineSelect[$aEngine['ENGINE_CODE']]['PRICE_DISPLAY'] = $aEngine['PRICE_DISPLAY'];
											}
										}
									//}
								}
							}
						}
					}
				}
				$this->assign('finitionsSelect', $finitionsSelect);
				$this->assign('engineSelect', $engineSelect);
			}
			else {
				if (!$user || !$user->isLogged()) {
					$aZoneConnexion = str_replace("<section class=\"clsconnexion formproject withOutBorder\">", "", $_SESSION[APP]['TEMP_BLOC_CONNEXION']);
					$aZoneConnexion = str_replace("<section class=\"clsconnexion formproject\">", "", $aZoneConnexion);
					$aZoneConnexion = "<section class=\"formproject\">" .$aZoneConnexion . "</section>";
					$this->assign('aZoneConnexion', $aZoneConnexion, false);
					unset($_SESSION[APP]['TEMP_BLOC_CONNEXION']);
				}
			}
		}

		$this->fetch();
	}

}
