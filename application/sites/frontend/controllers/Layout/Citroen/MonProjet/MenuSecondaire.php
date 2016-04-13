<?php

/**
 * Classe d'affichage Front de la tranche Message informatif de Mon projet
 *
 * @package Layout
 * @subpackage Citroen
 */
class Layout_Citroen_MonProjet_MenuSecondaire_Controller extends Pelican_Controller_Front
{

	public function indexAction()
	{
		// Si la tranche connexion est en mode formulaire, on n'affiche pas le menu
		if (!$_GET['AFFICHAGE_FORMULAIRE']) {
			$aParams = $this->getParams();
			$this->assign('aParams', $aParams);

			$user = \Citroen\UserProvider::getUser();
			$this->assign('user', $user);

			if (!isset($_GET['COMPARER']) && !isset($_GET['ESSAYER']) && !isset($_GET['TROUVER']) && !isset($_GET['FINANCER']) && !isset($_GET['PROFITER'])) {
				$_GET['DECOUVRIR'] = true;
			}
			$this->fetch();
		}
	}

}
