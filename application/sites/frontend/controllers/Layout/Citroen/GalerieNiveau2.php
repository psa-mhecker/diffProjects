<?php
class Layout_Citroen_GalerieNiveau2_Controller extends Pelican_Controller_Front  
{    
    public function indexAction()  
    {  
        $aData = $this->getParams();
        
        $aRsGalerie = Pelican_Cache::fetch("Frontend/Citroen/GalerieNiveau2", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            $aData['pid'],
            Pelican::getPreviewVersion()
        ));
        
        if(count($aRsGalerie) > 0)
        {
            $i = 0;
            foreach($aRsGalerie as $key){
                $aRsGalerie[$i++]['MEDIA_ID2']= Pelican::$config['MEDIA_HTTP'].Citroen_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($key['MEDIA_ID2']),Pelican::$config['MEDIA_FORMAT_ID']['WEB_MASTER_N1_STANDARD']);
            }
        }


        
        $this->assign("aData", $aData);
        $this->assign("aRsGalerie", $aRsGalerie);
        $this->fetch();  
    }  
}
?>