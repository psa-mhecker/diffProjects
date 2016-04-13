<?php
class Layout_Model_Header_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $data = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
            $_SESSION[APP]["GLOBAL_PAGE_ID"], 
            203, 
            $_SESSION[APP]["GLOBAL_PAGE_VERSION"], 
            $_SESSION[APP]['LANGUE_ID']
        ));
        
        $this->assign("data", $data);
        $this->fetch();
    }

}
