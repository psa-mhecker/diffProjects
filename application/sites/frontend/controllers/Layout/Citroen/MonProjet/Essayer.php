<?php

/**
 * Classe d'affichage Front de la tranche Essayer de Mon projet
 *
 * @package Layout
 * @subpackage Citroen
 */
class Layout_Citroen_MonProjet_Essayer_Controller extends Pelican_Controller_Front
{

	public function indexAction()
	{
		$aParams = $this->getParams();
		$this->assign('aParams', $aParams);

		if (isset($_GET['ESSAYER'])) {

		}

		$this->fetch();
	}

}
