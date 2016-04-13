<?php
require_once(pelican_path('Media'));

class Layout_Common_FlashPlayer_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $this->getView()
            ->getHead()
            ->setSwfObject();
        $data = $this->getParams();
        $flash = Pelican_Media::getFlashPlayer($data["ZONE_TEMPLATE_ID"], (! empty($data["MEDIA_ID"]) ? $data["MEDIA_ID"] : ''), (! empty($data["ZONE_PARAMETERS"]) ? $data["ZONE_PARAMETERS"] : ''));
        $this->assign("flash", $flash);
        $this->fetch();
    }
}