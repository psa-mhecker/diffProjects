<?php
class Layout_Citroen_Actualites_Pager_Controller extends Pelican_Controller_Front
{
    public function indexAction()
    {
		$aData = $this->getParams();
		$aPager = Pelican_Cache::fetch("Frontend/Citroen/Actualites/Pager", array(
			$aData['pid'],
			$aData['PAGE_PARENT_ID'],
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID']
        ));

        $dataMere = Pelican_Cache::fetch("Frontend/Page", array(
            $aData["PAGE_PARENT_ID"],
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID']
        ));

		$this->assign("parentPage", $dataMere["PAGE_CLEAR_URL"]);
		$this->assign("aPager", $aPager);
        $this->assign("aData",$aData);
        $this->fetch();
    }
}