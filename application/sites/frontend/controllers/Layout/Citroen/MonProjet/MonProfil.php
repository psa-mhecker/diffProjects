<?php

/**
 * Classe d'affichage Front de la tranche Connexion de Mon projet
 *
 * @package Layout
 * @subpackage Citroen
 */
class Layout_Citroen_MonProjet_MonProfil_Controller extends Pelican_Controller_Front
{

	public function indexAction()
	{
		$aParams = $this->getParams();
		$this->assign('aParams', $aParams);

		$user = \Citroen\UserProvider::getUser();
		$this->assign('user', $user);

		if ($user && $user->isLogged()) {
			$aPageConnexion = Pelican_Cache::fetch("Frontend/Page/Template", array(
					$_SESSION[APP]['SITE_ID'],
					$_SESSION[APP]['LANGUE_ID'],
					Pelican::getPreviewVersion(),
					Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION']));
			$aZoneConnexion = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
					$aPageConnexion['PAGE_ID'],
					Pelican::$config['ZONE_TEMPLATE_ID']['CONNEXION'],
					$aPageConnexion['PAGE_VERSION'],
					$_SESSION[APP]['LANGUE_ID']
			));
			if ($aZoneConnexion['ZONE_PARAMETERS']) {
				$this->assign('aConnexionRS', explode('|', $aZoneConnexion['ZONE_PARAMETERS']));
			}
		}

		$this->fetch();
	}

}
