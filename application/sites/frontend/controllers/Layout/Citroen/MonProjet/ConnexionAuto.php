<?php

/**
 * Classe d'affichage Front de la tranche Connexion hérité de Mon projet
 *
 * @package Layout
 * @subpackage Citroen
 */
class Layout_Citroen_MonProjet_ConnexionAuto_Controller extends Pelican_Controller_Front
{

	public function indexAction()
	{
		$aParams = $this->getParams();
		$this->assign('aParams', $aParams);

		$user = \Citroen\UserProvider::getUser();
		$this->assign('user', $user);

		if (!$user || !$user->isLogged()) {
			$aPageConnexion = Pelican_Cache::fetch("Frontend/Page/Template", array(
					$_SESSION[APP]['SITE_ID'],
					$_SESSION[APP]['LANGUE_ID'],
					Pelican::getPreviewVersion(),
					Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION']));
			if ($aPageConnexion) {
				$PID_TEMP = $_GET['pid'];
				$_GET['pid'] = $aPageConnexion['PAGE_ID'];
				$layoutParent = new Pelican_Layout();
				$layoutParent->initSite();
				$layoutParent->initData();
				$aZones = new Citroen_Layout_Desktop($layoutParent->aPage);
				$return = Pelican_Cache::fetch("Frontend/Page/Zone", array($aPageConnexion['PAGE_ID'], $_SESSION[APP]['LANGUE_ID'], Pelican::getPreviewVersion(), 'desktop'));
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
							if ($zone['ZONE_ID'] == Pelican::$config['ZONE']['CONNEXION']) {
								$aZoneConnexion = $aZones->getDirectZone($zone);
								break;
							}
						}
					}
				}
				$_GET['pid'] = $PID_TEMP;
				if ($aParams['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_PREFERENCES']) {
					$aZoneConnexion = str_replace("<div class=\"row of12 connectList withBorder\">", "<div class=\"row of12 connectList\">", $aZoneConnexion);
				}
				$this->assign('aZoneConnexion', $aZoneConnexion, false);
			}
		}
		$this->fetch();
	}

}
