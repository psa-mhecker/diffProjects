<?php

class Layout_Model_ContentByTag_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $data = $this->getParams();
        if ($data['ZONE_PARAMETERS']) {
            $list = Pelican_Cache::fetch("frontend/content_by_tag_php", array(
                $_SESSION[APP]['SITE_ID'], 
                $_SESSION[APP]['LANGUE_ID'], 
                $data['ZONE_PARAMETERS'], 
                Pelican::getPreviewVersion()
            ));
        }
        $this->listModel($list);
        $this->fetch();
    }
}
