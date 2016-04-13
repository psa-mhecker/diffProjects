<?php

class Layout_Model_ContentByTag_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $list = Pelican_Cache::fetch("Frontend/Content/Tag", array(
            $_SERVER['REDIRECT_QUERY_STRING'] , 
            $_SESSION[APP]['SITE_ID'] , 
            $_SESSION[APP]['LANGUE_ID'] , 
            Pelican::getPreviewVersion() , 
            ($_GET['pid'] == $_SESSION[APP]['HOME_PAGE_ID']) , 
            '' , 
            '' , 
            date("d.m.y")));
        $this->listModel($list);
        $this->fetch();
    }
}
