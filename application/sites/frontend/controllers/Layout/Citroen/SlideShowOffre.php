<?php
class Layout_Citroen_SlideShowOffre_Controller extends Pelican_Controller_Front
{
    public function indexAction()
    {
        $aData = $this->getParams();
        $aMulti =  Citroen_Cache::fetchProfiling($aData['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
            $aData["pid"],
            $aData['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            $aData['ZONE_TEMPLATE_ID'],
            'SLIDEOFFREADDFORM',
            $aData['AREA_ID'],
            $aData['ZONE_ORDER']
        ));
		


		
        $usingPerso = Citroen_Cache::$profilMatch;

        $aMulti = $this->setMediaAlt($aMulti);
        $aMulti = $this->patchImage($aMulti);
    $this->assign('usingPerso',$usingPerso);
         
        $this->assign('iNbMulti', count($aMulti));
        $this->assign('aData', $aData);
        $this->assign('aMulti', $aMulti);

        $this->fetch();
    }

    public static function patchImage($aMulti)
    {
        /* Initialisation des variables */
        $i = 0;
        $productMedia = Pelican_Cache::fetch("Frontend/Citroen/Perso/ProductMedia", array(
            $_SESSION[APP]['SITE_ID']
        ));
        if ( is_array($aMulti) && !empty($aMulti) ){
            foreach($aMulti as $aOneMulti){
                $mediaPath = ($aOneMulti['MEDIA_ID_GENERIQUE'] && !empty($_SESSION[APP]['FLAGS_USER']['preferred_product']) && !empty($productMedia[$_SESSION[APP]['FLAGS_USER']['preferred_product']]['SLIDESHOW_OFFRE_WEB'])) ? $productMedia[$_SESSION[APP]['FLAGS_USER']['preferred_product']]['SLIDESHOW_OFFRE_WEB'] : Pelican_Media::getMediaPath($aOneMulti['MEDIA_ID']);
				if ($mediaPath) {
					$aMulti[$i]['MEDIA_ID'] = Pelican::$config['MEDIA_HTTP'].Citroen_Media::getFileNameMediaFormat($mediaPath,Pelican::$config['MEDIA_FORMAT_ID']['WEB_SLIDESHOW_OFFRE']);
				}
                if($aOneMulti['MEDIA_ID2'] != 0){
                    $mediaPath2 = ($aOneMulti['MEDIA_ID2_GENERIQUE'] && !empty($_SESSION[APP]['FLAGS_USER']['preferred_product']) && !empty($productMedia[$_SESSION[APP]['FLAGS_USER']['preferred_product']]['SLIDESHOW_OFFRE_MOB'])) ? $productMedia[$_SESSION[APP]['FLAGS_USER']['preferred_product']]['SLIDESHOW_OFFRE_MOB'] : Pelican_Media::getMediaPath($aOneMulti['MEDIA_ID2']);
					if ($mediaPath2) {
						$aMulti[$i]['MEDIA_ID2'] = Pelican::$config['MEDIA_HTTP'].Citroen_Media::getFileNameMediaFormat($mediaPath2,Pelican::$config['MEDIA_FORMAT_ID']['WEB_SLIDESHOW_OFFRE']);
					}
                }
                $i++;
            }
        }

        return $aMulti;
    }

     public static function setMediaAlt($aMulti)
    {
         $i = 0;

       if ( is_array($aMulti) && !empty($aMulti) ){
        foreach($aMulti as $aOneMulti){
            if($aOneMulti['MEDIA_ID']){
                $aAlt = Pelican_Cache::fetch("Media/Detail", array(
                    $aOneMulti['MEDIA_ID']
                        ));
                $aMulti[$i]['MEDIA_ALT'] = $aAlt['MEDIA_ALT'];
            }
            $i++;
         }
      }
        return $aMulti;
    }
}
?>
