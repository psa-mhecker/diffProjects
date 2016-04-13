<?php
class Layout_Citroen_Global_Navigation_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $navigation = Pelican_Cache::fetch("Frontend/Page/Navigation", array(
            $_SESSION[APP]["GLOBAL_PAGE_ID"] , 
            1598 , 
            $_SESSION[APP]["GLOBAL_PAGE_VERSION"] , 
            $_SESSION[APP]['LANGUE_ID'],
            false,
            Pelican::$config["HTTP_MEDIA"]));
        $this->assign("navigation", $navigation);
        $this->fetch();
    }
}