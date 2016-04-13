<?php

/**
 * Classe d'affichage Front de la tranche Promotions de Mon projet
 *
 * @package Layout
 * @subpackage Citroen
 */
class Layout_Citroen_MonProjet_Promotions_Controller extends Pelican_Controller_Front
{

	public function indexAction()
	{
		$aParams = $this->getParams();
		$this->assign('aParams', $aParams);

		if (isset($_GET['TROUVER'])) {
			$user = \Citroen\UserProvider::getUser();
			$this->assign('user', $user);

			// $_GET['select_vehicule'] défini dans le controleur Layout_Citroen_MonProjet_SelectionVehicules_Controller
			if ($_GET['select_vehicule']) {
				$aPagesPromotions = Pelican_Cache::fetch("Frontend/Citroen/PagesPromotionParVehicule", array(
						$_SESSION[APP]['SITE_ID'],
						$_SESSION[APP]['LANGUE_ID'],
						$_GET['select_vehicule'],
						Pelican::getPreviewVersion(),
				));
				if ($aPagesPromotions) {
					$PID_TEMP = $_GET['pid'];
					foreach ($aPagesPromotions as $page) {
						$_GET['pid'] = $page['PAGE_ID'];
						$layoutParent = new Pelican_Layout();
						$layoutParent->initSite();
						$layoutParent->initData();
						$aZones = new Citroen_Layout_Desktop($layoutParent->aPage);
						$return = Pelican_Cache::fetch("Frontend/Page/Zone", array($page['PAGE_ID'], $_SESSION[APP]['LANGUE_ID'], Pelican::getPreviewVersion(), 'desktop'));
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
									if (in_array($zone['ZONE_ID'], array(Pelican::$config['ZONE']['AUTRES_PROMOTIONS'], Pelican::$config['ZONE']['SELECTION_VEHICULE'], Pelican::$config['ZONE']['ACCCORDEON'], Pelican::$config['ZONE']['PROMOTION'], Pelican::$config['ZONE']['OUTILS']))) {
										$_GET["vid"] = $_GET['select_vehicule'];
										$aZonesPromotions[] = $aZones->getDirectZone($zone);
									}
								}
							}
						}
					}
					$_GET['pid'] = $PID_TEMP;
					$this->assign('aZonesPromotions', $aZonesPromotions);
				}
			}
		}
		$this->fetch();
	}

}
