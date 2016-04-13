<?php

class Layout_Common_Navigation_1level_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $data = $this->getParams();
        
    	$navigation = Pelican_Cache::fetch("Frontend/Page/Navigation", array(
            $data["PAGE_ID"], 
            $data["ZONE_TEMPLATE_ID"], 
            $data["PAGE_VERSION"], 
            $_SESSION[APP]['LANGUE_ID'],
            false,
            Pelican::$config["HTTP_MEDIA"]
        ));
        
        $this->assign("aNavigation", $navigation);
        $this->fetch();
    }
}
