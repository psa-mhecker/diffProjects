<?php
class Layout_Citroen_Home_Actualites_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $aParams = $this->getParams();
        $this->assign("aParams", $aParams);
        $this->assign("session", $_SESSION[APP]);
        $isMobile = $this->isMobile();

        $actualites = Pelican_Cache::fetch("Frontend/Citroen/Home/Actualites", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            "CURRENT",
            "1"
        ));

        if ($actualites) {
            foreach($actualites as $key => $value) {
                if ($actualites[$key]['MEDIA_PATH']) {
                    if ($isMobile) {
                        $actualites[$key]['MEDIA_PATH'] = Pelican_Media::getFileNameMediaFormat($actualites[$key]['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['MOBILE_ACTUALITE_STANDARD']);
                    }
                    else {
                        if ($key==0) {
                           $actualites[$key]['MEDIA_PATH'] = Pelican_Media::getFileNameMediaFormat($actualites[$key]['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['WEB_ACTUALITE_GRAND']);
                        }
                        else {
                            $actualites[$key]['MEDIA_PATH'] = Pelican_Media::getFileNameMediaFormat($actualites[$key]['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['WEB_ACTUALITE_PETIT']);
                        }
                    }
                }
            }
        }

        $this->assign("actualites", $actualites);
        $this->fetch();
    }

}