<?php

use Citroen\ConcessionFavoris;

/**
 * Classe d'affichage Front de la tranche Concession mobile de Mon projet
 *
 * @package Layout
 * @subpackage Citroen
 */
class Layout_Citroen_MonProjet_ConcessionMobile_Controller extends Pelican_Controller_Front
{

	public function indexAction()
	{
		$aParams = $this->getParams();

		if ($this->isMobile()) {
			$oUser = \Citroen\UserProvider::getUser();
			if ($oUser && $oUser->isLogged()) {
				$iUserId = $oUser->getId();
				$aFavs = ConcessionFavoris::getFavorisConcessionsFromDB($iUserId);
			} else {
				$aFavs = ConcessionFavoris::getFavorisConcessionsFromSession();
			}
			if ($aFavs) {
				$sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
				$sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));
				foreach ($aFavs as $key => $value) {
					if ($value != null) {
						$aDealers[$key] = Pelican_Cache::fetch("Frontend/Citroen/Annuaire/Dealer", array($value, $sPays, $sLangue));
					}
				}
				if ($this->getParam('id')) {
					$sConntrollerPath = Pelican_Controller::getControllerPath('PointsDeVente', 'Layout/Citroen', 'frontend');
					//$sTemplatePath = $this->getTemplatePath($sConntrollerPath,'getDealerMobile');
					$this->assign('aDealer', Pelican_Cache::fetch("Frontend/Citroen/Annuaire/Dealer", array($this->getParam('id'), $sPays, $sLangue)));
					$this->setTemplate($this->getTemplatePath($sConntrollerPath, 'getDealerMobile'));
				}
				$this->assign('aDealers', $aDealers);
			}
		}
		$this->assign('aParams', $aParams);

		$this->fetch();
	}

}
