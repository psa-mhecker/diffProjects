<?php

/**
 * Classe d'affichage Front de la tranche Découvrir de Mon projet
 *
 * @package Layout
 * @subpackage Citroen
 */
class Layout_Citroen_MonProjet_Decouvrir_Controller extends Pelican_Controller_Front
{

	public function indexAction()
	{
		$aParams = $this->getParams();
		$this->assign('aParams', $aParams);

		if (isset($_GET['DECOUVRIR'])) {
			$user = \Citroen\UserProvider::getUser();
			$this->assign('user', $user);

			// $_GET['select_vehicule'] défini dans le controleur Layout_Citroen_MonProjet_SelectionVehicules_Controller
			if ($_GET['select_vehicule']) {
				$aPageShowroom = Pelican_Cache::fetch("Frontend/Citroen/UrlVehiculeById", array(
						$_GET['select_vehicule'],
						Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_ACCUEIL_SELECT_TEINTE'],
						Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL'],
						$_SESSION[APP]['LANGUE_ID'],
						$_SESSION[APP]['SITE_ID'],
						Pelican::getPreviewVersion(),
						false
				));
				if ($aPageShowroom['PAGE_ID']) {
					$bZoneOutilsVisible = true;
					$PID_TEMP = $_GET['pid'];
					$_GET['pid'] = $aPageShowroom['PAGE_ID'];
					$layoutParent = new Pelican_Layout();
					$layoutParent->initSite();
					$layoutParent->initData();
					$aZones = new Citroen_Layout_Desktop($layoutParent->aPage);
					$return = Pelican_Cache::fetch("Frontend/Page/Zone", array($aPageShowroom['PAGE_ID'], $_SESSION[APP]['LANGUE_ID'], Pelican::getPreviewVersion(), 'desktop'));
					$aZones->tabAreas = $return["areas"];
					$aZones->tabZones = $return["zones"];
					/* PERSO */
					$flagUser = $_SESSION[APP]['FLAGS_USER'];
					$profileUser = $_SESSION[APP]['PROFILES_USER'];
					$products = Pelican_Cache::fetch("Frontend/Citroen/Perso/Lcdv6ByProduct", array(
							$_SESSION[APP]['SITE_ID']
					));
					foreach ($aZones->tabAreas as $area) {
						if (isset($aZones->tabZones[$area['AREA_ID']])) {
							foreach ($aZones->tabZones[$area['AREA_ID']] as $listZone) {
								/* PERSO */
								$zone = array();
								$zoneDataOrigin = $listZone[0];
								if (is_array($profileUser) && count($profileUser) > 0) {
									foreach ($listZone as $key => $oneData) {
										$explodeKey = array();
										$field = '';
										if (strpos($key, '_') !== false) {
											$explodeKey = explode($key, '_');
											switch ($explodeKey[1]) {
												case 13 :
													$field = $flagUser['preferred_product'];
													break;
												case 7 :
													$field = $flagUser['product_owned'];
													break;
												case 11 :
													$field = $flagUser['current_product'];
													break;
												case 12 :
													$field = $flagUser['product_best_score'];
													break;
												case 14 :
													$field = $flagUser['recent_product'];
													break;
											}
										}
										if ((in_array($key, $profileUser) || (!empty($explodeKey) && in_array($explodeKey[0], $profileUser) && $products[$explodeKey[2]] == $field)) && !empty($oneData)) {
											$zone = array_merge($zoneDataOrigin, $oneData);
											break;
										}
									}
								}
								if (empty($zone)) {
									$zone = $zoneDataOrigin;
								}
								// Récuperation du HTML de la zone
								if (in_array($zone['ZONE_ID'], array(Pelican::$config['ZONE']['POINTS_FORTS'], Pelican::$config['ZONE']['OUTILS'], Pelican::$config['ZONE']['MUR_MEDIA'], Pelican::$config['ZONE']['RECAPITULATIF_MODELE'], Pelican::$config['ZONE']['FINITIONS'], Pelican::$config['ZONE']['EQUIPEMENTS_CARACTERISTIQUES_TECHNIQUES']))) {
									if ($this->isMobile()) {
										if (in_array($zone['ZONE_ID'], array(Pelican::$config['ZONE']['POINTS_FORTS'], Pelican::$config['ZONE']['OUTILS'], Pelican::$config['ZONE']['RECAPITULATIF_MODELE'], Pelican::$config['ZONE']['FINITIONS'], Pelican::$config['ZONE']['EQUIPEMENTS_CARACTERISTIQUES_TECHNIQUES']))) {
											$zone['FROM_MON_PROJET'] = 1;
											// Cas du détail point de vente, seul la tranche Outils s'affiche
											if ($_GET['id'] && $zone['ZONE_ID'] == Pelican::$config['ZONE']['OUTILS']) {
												// Et elle ne s'affiche qu'une seule fois
												if ($bZoneOutilsVisible) {
													$aZonesShowroom[] = $aZones->getDirectZone($zone);
													$bZoneOutilsVisible = false;
												}
											}
											// Cas hors détail du point de vente
											elseif (!$_GET['id']) {
												$aZonesShowroom[] = $aZones->getDirectZone($zone);
											}
										}
									} else {
										if (in_array($zone['ZONE_ID'], array(Pelican::$config['ZONE']['POINTS_FORTS'], Pelican::$config['ZONE']['OUTILS'], Pelican::$config['ZONE']['MUR_MEDIA'], Pelican::$config['ZONE']['RECAPITULATIF_MODELE']))) {
											$aZonesShowroom[] = $aZones->getDirectZone($zone);
										}
									}
								}
								// Cas particulier, il ya une fermeture de div au debut de cette zone
								elseif (in_array($zone['ZONE_ID'], array(Pelican::$config['ZONE']['RECAPITULATIF_MODELE']))) {
									$aZonesShowroom[] = ((!$this->isMobile()) ? "<div>" : "") . $aZones->getDirectZone($zone);
								}
							}
						}
					}
					$_GET['pid'] = $PID_TEMP;
					$this->assign('aZonesShowroom', $aZonesShowroom);
				}
			}
		}
		$this->fetch();
	}

}
