<?php

/**
 * Classe d'affichage Front de la tranche Message informatif de Mon projet
 *
 * @package Layout
 * @subpackage Citroen
 */
class Layout_Citroen_MonProjet_MessageInformatif_Controller extends Pelican_Controller_Front
{

	public function indexAction()
	{
		$aParams = $this->getParams();
		$this->assign('aParams', $aParams);

		if ($_SESSION[APP]['PRJ_MESSAGE_CLOSE'] == 1) {
			$this->assign('bMessageVisible', 0);
		} else {
			$_SESSION[APP]['PRJ_MESSAGE_CLOSE'] = 1;
			$this->assign('bMessageVisible', 1);
		}

		$this->fetch();
	}

	public function closeMessageAction()
	{
		$_SESSION[APP]['PRJ_MESSAGE_CLOSE'] = 1;
	}

}
