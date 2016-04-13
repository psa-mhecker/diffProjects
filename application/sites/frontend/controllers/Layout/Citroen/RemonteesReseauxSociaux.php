<?php

class Layout_Citroen_RemonteesReseauxSociaux_Controller extends Pelican_Controller_Front
{

	public function indexAction()
	{
		$aData = $this->getParams();
		$this->assign('aData', $aData);
		
		$reseauxSociaux = Pelican_Cache::fetch("Frontend/Citroen/ReseauxSociaux", array(
				$_SESSION[APP]['SITE_ID'],
				$_SESSION[APP]['LANGUE_ID'],
				date("Ymdh")
		));
		$this->assign("aReseauxSociaux", $reseauxSociaux);

		$sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
		$this->assign("sLangue", $sLangue);

		$this->fetch();
		$this->getRequest()->addResponseCommand("append", array(
			'id' => 'instagramFeed',
			'attr' => 'innerHTML',
			'value' => $this->getResponse()
		));
	}

}

?>
