<?php

/**
 * Classe d'affichage Front de la tranche Menu principal de Mon projet
 *
 * @package Layout
 * @subpackage Citroen
 */
class Layout_Citroen_MonProjet_MenuPrincipal_Controller extends Pelican_Controller_Front
{

	public function indexAction()
	{
		$aParams = $this->getParams();
		$this->assign('aParams', $aParams);

		$user = \Citroen\UserProvider::getUser();
		$this->assign('user', $user);

		$aMenu = Pelican_Cache::fetch("Frontend/Citroen/MonProjet/Menu", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				Pelican::getPreviewVersion()
				)
		);
		$this->assign('aMenu', $aMenu);

		$this->fetch();
	}

}
