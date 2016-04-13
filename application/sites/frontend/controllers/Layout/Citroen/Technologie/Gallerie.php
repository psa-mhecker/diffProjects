<?php
class Layout_Citroen_Technologie_Gallerie_Controller extends Pelican_Controller_Front  
{    
    public function indexAction()  
    {     
        $aData = $this->getParams();     
        $this->assign("aData",$aData);   
        $aRsTechno = Pelican_Cache::fetch("Frontend/Citroen/Technologie/Gallerie", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            $aData['pid']
        ));
        $this->assign("url_home", $_SESSION[APP]["HOME_PAGE_URL"]);
        $this->assign("aRsTechno", $aRsTechno);
        $this->fetch();
    }
}
?>
