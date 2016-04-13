<?php
require_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Showroom.php');

class Layout_Citroen_Verbatim_Controller extends Pelican_Controller_Front  
{  
    public function indexAction()  
    {  
        $aData = $this->getParams();
        if ($aData["ZONE_WEB"] == 1 || $aData["ZONE_MOBILE"] == 1) {
            $mediaDetail = Pelican_Cache::fetch("Media/Detail", array(
                        $aData["MEDIA_ID"]
            ));
            $mediaDetail2 = Pelican_Cache::fetch("Media/Detail", array(
                        $aData["MEDIA_ID2"]
            ));
            $this->assign('MEDIA_ALT', $mediaDetail["MEDIA_ALT"]);
            $this->assign('MEDIA_ALT2', $mediaDetail2["MEDIA_ALT"]);
        }

		
        $this->assign('aData', $aData);  
        $this->fetch();  
    }  
} 
?>
