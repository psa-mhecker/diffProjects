<?php
class Layout_Citroen_Global_Panoramique_Controller extends Pelican_Controller_Front
{
    public function indexAction()
    {

        $aData = $this->getParams();

        $mediaDetail = Pelican_Cache::fetch("Media/Detail", array(
                $aData["MEDIA_ID"]
            ));
        $aLangues = Pelican_Cache::fetch("Frontend/Citroen/SiteLangues", array(
                $aData['SITE_ID']
            ));
        $this->assign("bTplPreHome", (in_array($aData['TEMPLATE_PAGE_ID'], array(Pelican::$config['TEMPLATE_PRE_HOME'])))?1:0);
        $this->assign('aData', $aData);
        $title = $mediaDetail["MEDIA_TITLE"];
        //$title = $mediaDetail["MEDIA_TITLE"];
        if($aData["ZONE_PARAMETERS"]){
            $title = $aData["PAGE_TITLE"];
        }
        $this->assign('title', $title);
        $this->assign('aLangue', $aLangues);
        $this->assign('MEDIA_PATH', $mediaDetail["MEDIA_PATH"]);
        $this->assign('MEDIA_TITLE', $mediaDetail["MEDIA_TITLE"]);

        $this->fetch();
    }
}