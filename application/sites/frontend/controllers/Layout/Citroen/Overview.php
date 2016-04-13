<?php
class Layout_Citroen_Overview_Controller extends Pelican_Controller_Front  
{  
    public function indexAction()  
    {  
        $aData = $this->getParams();        
        $this->assign('aData', $aData);
        $aRs = Pelican_Cache::fetch("Frontend/Citroen/Overview", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            $aData['pid'],
            Pelican::getPreviewVersion(),
            $this->isMobile()
        ));
        
        $aTemp = Pelican_Cache::fetch('Citroen/GroupeReseauxSociaux',array($_SESSION[APP]['SITE_ID'],$_SESSION[APP]['LANGUE_ID']));
                   
        $sSharer = Backoffice_Share_Helper::getSharer($aTemp[0]['GROUPE_RESEAUX_SOCIAUX_ID'], $aParams['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], Pelican::$config['MODE_SHARER'][0], array('getParams' => $aParams));
        
        $this->assign("sSharer", $sSharer);
		$this->assign("aData", $aData);
        $this->assign('aOverview', $aRs);
        $this->fetch();  
    }  
} 
?>
