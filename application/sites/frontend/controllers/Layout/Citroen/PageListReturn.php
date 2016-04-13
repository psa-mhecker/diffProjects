<?php
class Layout_Citroen_PageListReturn_Controller extends Pelican_Controller_Front  
{  
    public function indexAction()  
    {  
       $aData = $this->getParams();
			
       $aPageParent = Pelican_Cache::fetch("Frontend/Page", array($aData['PAGE_PARENT_ID'],$_SESSION[APP]['SITE_ID'],$_SESSION[APP]['LANGUE_ID']));
       $urlPrecedente = $aPageParent["PAGE_CLEAR_URL"];

        $this->assign("url", $urlPrecedente);
        $this->assign("aData",$aData);
        $this->fetch();
    }
} 
?>
