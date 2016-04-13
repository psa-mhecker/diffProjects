
<?php

class Layout_Citroen_Javascript_Controller extends Pelican_Controller_Front {

    public function indexAction() {
        $aData = $this->getParams();

        $result = Pelican_Cache::fetch("Frontend/Citroen/Javascript", array(
                    $aData["PAGE_ID"],
                    $aData['LANGUE_ID'],
                    $aData['PAGE_VERSION'],
                    $aData['ZONE_TEMPLATE_ID'],
                    $aData['AREA_ID'],
                    $aData['ZONE_ORDER'],
        ));
        
        //To check the page is HTTP or HTTPS
        if (strtoupper(Pelican::$config["SERVER_PROTOCOL"]) !== 'HTTPS') {
            $javascript = $result['ZONE_TEXTE'];
        } else {
            $javascript = $result['ZONE_TEXTE2'];
        }
        //To check the page is mobile or web;
        $isMobile = Pelican_Controller::isMobile();
        if(($isMobile === true && $result['ZONE_MOBILE'] == 1) || ($isMobile === false && $result['ZONE_WEB'] == 1))
                               {
            $this->setResponse($javascript);
                               }
                }
}
?>
