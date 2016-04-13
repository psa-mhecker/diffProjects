<?php
class Layout_Citroen_SlideShowAuto_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
		if ($this->isMobile()) {
            $aData = $this->getParams();
            $aData['PAGE_ID'] = $aData['pid'];
            $zoneNavigationPush = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
                $aData['pid'],
                Pelican::$config['ZONE_TEMPLATE_ID']['ACCUEIL_PROMOTION_SLIDESHOW'],
                $aData['PAGE_CURRENT_VERSION'],
                $_SESSION[APP]['LANGUE_ID']
            ));
            
            $aData = array_merge($aData, $zoneNavigationPush);
			


            $this->assign('aData', $aData);        
            $this->setResponse( Pelican_Request::call('_/Layout_Citroen_SlideShow', $aData));
            $_GET['ACCEUIL_PROMO_SLIDESHOW'] = 1;
        }
    }

}