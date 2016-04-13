<?php

/**
 * Classe d'affichage Front de la tranche Liaison Reseaux Sociaux de Mon projet
 *
 * @package Layout
 * @subpackage Citroen
 */
class Layout_Citroen_MonProjet_LiaisonReseauxSociaux_Controller extends Pelican_Controller_Front
{

	public function indexAction()
	{
		$aParams = $this->getParams();
		$this->assign('aParams', $aParams);

		$user = \Citroen\UserProvider::getUser();
		$this->assign('user', $user);

		$this->fetch();
	}

}