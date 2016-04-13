<?php
class Layout_Citroen_StickyBarPromo_Controller extends Pelican_Controller_Front
{
    public function indexAction()
    {
        $aParams = $this->getParams();

		
        $this->assign("aData", $aParams);
         /**
         * Page globale
         */
        $pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
        ));

        

        /**
         * Configuration
         */
        $aConfiguration = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
            $pageGlobal['PAGE_ID'],
            Pelican::$config['ZONE_TEMPLATE_ID']['CONFIGURATION'],
            $pageGlobal['PAGE_VERSION'],
            $_SESSION[APP]['LANGUE_ID']
        ));

        $this->assign('shortTitle',$aConfiguration['ZONE_TITRE17']);
        
        $pageParent = Pelican_Cache::fetch("Frontend/Page", array(
            $aParams['PAGE_PARENT_ID'],
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            Pelican::getPreviewVersion()
        ));
        
        if ($pageParent) {
            $stickyBar = Pelican_Cache::fetch("Frontend/Citroen/StickyBar", array(
                $pageParent['PAGE_ID'],
                $pageParent['PAGE_PARENT_ID'],
                $pageParent['TEMPLATE_PAGE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                Pelican::getPreviewVersion()
            ));
            $this->assign("stickyBar", $stickyBar);
            
            $this->assign("pidCourant", $aParams['PAGE_PARENT_ID']);
        }
        
        $this->fetch();
    }
}