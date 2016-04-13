<?php

require_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Showroom.php');

class Layout_Citroen_MurMedia_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $aData = $this->getParams();	
        $aMulti =  Citroen_Cache::fetchProfiling($aData['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
            $aData["pid"],
            $aData['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            $aData['ZONE_TEMPLATE_ID'],
            'GALLERYFORM',
            $aData['AREA_ID'],
            $aData['ZONE_ORDER']
        ));


		
        $aMulti = $this->patchImage($aMulti);
        $this->assign("iNbMulti", count($aMulti));
        $this->assign("aData", $aData);
        $this->assign("aMulti", $aMulti);

        $this->fetch();
    }

    /**
    * Méthode permettant de construire le liens des image pour les vehicules
    *
    * @param array $aMulti Tableau des véhicules avec Multi
    * @return array $aNewMulti Tableau des véhicules avec leur lien image/video et le type d'affichage et de media 1(image) 2(video)
    */
    public static function patchImage($aMulti)
    {
        $aMurImg = array(
            1 => array("WEB_1_CINEMASCOPE"),
            2 => array("WEB_2_VISUELS_16_9", "WEB_2_VISUELS_16_9"),
            3 => array("WEB_VISUEL_PORTRAIT_PLUS", "WEB_VISUEL_PORTRAIT_PLUS_16_9_EMPILES", "WEB_VISUEL_PORTRAIT_PLUS_16_9_EMPILES"),
            4 => array("WEB_16_9_EMPILES", "WEB_16_9_EMPILES", "WEB_16_9_EMPILES_PLUS_PORTRAIT"),
            5 => array("WEB_2_VISUELS_FORMAT_CARRE", "WEB_2_VISUELS_FORMAT_CARRE"),
            6 => array("WEB_2_VISUELS_FORMAT_PORTRAIT", "WEB_2_VISUELS_FORMAT_PORTRAIT"),
            7 => array("WEB_3_VISUEL_CARRE", "WEB_3_VISUEL_CARRE", "WEB_3_VISUEL_CARRE")
        );
        /* Initialisation des variables */
        $i = 0;
        $aNewMulti = array();
        if ( is_array($aMulti) && !empty($aMulti) ){
            foreach($aMulti as $aOneMulti){
                $iMurImg=0;
                if($aOneMulti['MEDIA_ID']!= 0){
                    $mediaFormat = $aMurImg[$aOneMulti['PAGE_ZONE_MULTI_VALUE']][$iMurImg];
                    $aNewMulti[$i]['MEDIA_ID'] = Pelican::$config['MEDIA_HTTP'].Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aOneMulti['MEDIA_ID']),Pelican::$config['MEDIA_FORMAT_ID'][$mediaFormat]);
                    $aNewMulti[$i]['REAL_MEDIA_ID'] = $aOneMulti['MEDIA_ID'];
                    $aNewMulti[$i]['TYPE_MEDIA_ID'] = 1;
					$aNewMulti[$i]['MEDIA_ID_ZOOM'] = Pelican::$config['MEDIA_HTTP'].Pelican_Media::getMediaPath($aOneMulti['MEDIA_ID']);
					$aInfosMedia =  Pelican_Cache::fetch("Media/Detail", array($aOneMulti['MEDIA_ID']));
					$aNewMulti[$i]['MEDIA_TITLE']	=	$aInfosMedia['MEDIA_TITLE'];
                    $aNewMulti[$i]['MEDIA_ALT'] = $aInfosMedia['MEDIA_ALT'];                    
                    $iMurImg ++;
                }
                if($aOneMulti['MEDIA_ID2']!= 0){
                    
                    $aInfosMedia =  Pelican_Cache::fetch("Media/Detail", array($aOneMulti['MEDIA_ID2']));
                    $mediaFormat = $aMurImg[$aOneMulti['PAGE_ZONE_MULTI_VALUE']][$iMurImg];
                    //$aNewMulti[$i]['MEDIA_ID2'] = Pelican::$config['MEDIA_HTTP'].Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aOneMulti['MEDIA_ID2']),Pelican::$config['MEDIA_FORMAT_ID'][$mediaFormat]);
                    $aNewMulti[$i]['TYPE_MEDIA_ID'] = 2;
                    
                    if($aInfosMedia['MEDIA_TYPE_ID'] == 'video'){
                        $aNewMulti[$i]['MEDIA_ID_ZOOM_FOR_DATA_VIDEO'] = Pelican::$config['MEDIA_HTTP'].$aInfosMedia["MEDIA_PATH"];
                        $aNewMulti[$i]['MEDIA_ID_ZOOM'] = "|" . Pelican::$config['MEDIA_HTTP'].$aInfosMedia["MEDIA_PATH"];
                        
                        if ($aInfosMedia['MEDIA_ID_REFERENT'] != "" && !strpos($aNewMulti[$i]['MEDIA_ID_ZOOM'],'|')) {
                            $aInfosMedia2 = Pelican_Cache::fetch("Media/Detail", array(
                                        $aInfosMedia['MEDIA_ID_REFERENT']
                            ));

                            $aNewMulti[$i]['MEDIA_ID_ZOOM'] .= "|" . Pelican::$config['MEDIA_HTTP'] . $aInfosMedia2["MEDIA_PATH"];
                        }
                        else {
                            /*****/
                            $aNewMulti[$i]['MEDIA_ID_ZOOM'] .=  "|" .Pelican::$config['MEDIA_HTTP'].$aInfosMedia["MEDIA_PATH"];
                        }
						if (intval($aInfosMedia['MEDIA_ID_REFERENT_PICTURE'])> 0 ) {
							$aMediaReferentPicture = Pelican_Cache::fetch("Media/Detail", array($aInfosMedia['MEDIA_ID_REFERENT_PICTURE'] ));
							 $aNewMulti[$i]['REFERENT_PICTURE'] = Pelican::$config['MEDIA_HTTP'].$aMediaReferentPicture['MEDIA_PATH'];
						}	                        
                        $aNewMulti[$i]['OTHER_MEDIA_ID_ZOOM'] = Citroen_Media::childMediaImplodeToString($aOneMulti['MEDIA_ID2'], $aInfosMedia[$i]['MEDIA_ID_ZOOM']);                        
                    }elseif($aInfosMedia['MEDIA_TYPE_ID'] == 'youtube'){
                        $aNewMulti[$i]['MEDIA_ID_ZOOM'] = Frontoffice_Video_Helper::setYoutube($aInfosMedia["YOUTUBE_ID"]);
                        $aNewMulti[$i]['OTHER_MEDIA_ID_ZOOM'] = Frontoffice_Video_Helper::setYoutube($aInfosMedia["YOUTUBE_ID"]);
                    }
                    
                    $aNewMulti[$i]['MEDIA_TITLE'] = $aInfosMedia['MEDIA_TITLE'];
                    $aNewMulti[$i]['MEDIA_ALT'] = $aInfosMedia['MEDIA_ALT'];
                    $iMurImg ++;
                }
                if($aOneMulti['MEDIA_ID3']!= 0){
                    $mediaFormat = $aMurImg[$aOneMulti['PAGE_ZONE_MULTI_VALUE']][$iMurImg];
                    $aNewMulti[$i]['MEDIA_ID3'] = Pelican::$config['MEDIA_HTTP'].Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aOneMulti['MEDIA_ID3']),Pelican::$config['MEDIA_FORMAT_ID'][$mediaFormat]);
                    $aNewMulti[$i]['REAL_MEDIA_ID3'] = $aOneMulti['MEDIA_ID3'];
                    $aNewMulti[$i]['TYPE_MEDIA_ID3'] = 1;
					$aNewMulti[$i]['MEDIA_ID2_ZOOM'] = Pelican::$config['MEDIA_HTTP'].Pelican_Media::getMediaPath($aOneMulti['MEDIA_ID3']);
					$aInfosMedia =  Pelican_Cache::fetch("Media/Detail", array($aOneMulti['MEDIA_ID3']));
					$aNewMulti[$i]['MEDIA2_TITLE']	=	$aInfosMedia['MEDIA_TITLE'];
                    $aNewMulti[$i]['MEDIA2_ALT'] = $aInfosMedia['MEDIA_ALT'];
                    $iMurImg ++;
                }
                if($aOneMulti['MEDIA_ID4']!= 0){
                    
                    $aInfosMedia =  Pelican_Cache::fetch("Media/Detail", array($aOneMulti['MEDIA_ID4']));
                    $mediaFormat = $aMurImg[$aOneMulti['PAGE_ZONE_MULTI_VALUE']][$iMurImg];
                    //$aNewMulti[$i]['MEDIA_ID4'] = Pelican::$config['MEDIA_HTTP'].Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aOneMulti['MEDIA_ID4']),Pelican::$config['MEDIA_FORMAT_ID'][$mediaFormat]);
                    $aNewMulti[$i]['TYPE_MEDIA_ID3'] = 2;
		
                    if($aInfosMedia['MEDIA_TYPE_ID'] == 'video'){
                        $aNewMulti[$i]['MEDIA_ID2_ZOOM_FOR_DATA_VIDEO'] = Pelican::$config['MEDIA_HTTP'].$aInfosMedia["MEDIA_PATH"];

                        $aNewMulti[$i]['MEDIA_ID2_ZOOM'] = "|" . Pelican::$config['MEDIA_HTTP'].$aInfosMedia["MEDIA_PATH"];
                        
                        if ($aInfosMedia['MEDIA_ID_REFERENT'] != "" && !strpos($aNewMulti[$i]['MEDIA_ID2_ZOOM'],'|')) {
                            $aInfosMedia2 = Pelican_Cache::fetch("Media/Detail", array(
                                        $aInfosMedia['MEDIA_ID_REFERENT']
                            ));

                            $aNewMulti[$i]['MEDIA_ID2_ZOOM'] .= "|" . Pelican::$config['MEDIA_HTTP'] . $aInfosMedia2["MEDIA_PATH"];
                        }
                        else {
                            $aNewMulti[$i]['MEDIA_ID2_ZOOM'] .= "|" .Pelican::$config['MEDIA_HTTP'].$aInfosMedia["MEDIA_PATH"];
                        }
						if (intval($aInfosMedia['MEDIA_ID_REFERENT_PICTURE'])> 0 ) {
							$aMediaReferentPicture = Pelican_Cache::fetch("Media/Detail", array($aInfosMedia['MEDIA_ID_REFERENT_PICTURE'] ));
							 $aNewMulti[$i]['REFERENT_PICTURE'] = Pelican::$config['MEDIA_HTTP'].$aMediaReferentPicture['MEDIA_PATH'];
						}						
                        $aNewMulti[$i]['OTHER_MEDIA_ID2_ZOOM'] = Citroen_Media::childMediaImplodeToString($aOneMulti['MEDIA_ID4'], $aInfosMedia[$i]['MEDIA_ID2_ZOOM']);
                    }elseif($aInfosMedia['MEDIA_TYPE_ID'] == 'youtube'){
                        $aNewMulti[$i]['MEDIA_ID2_ZOOM'] = Frontoffice_Video_Helper::setYoutube($aInfosMedia["YOUTUBE_ID"]);
                        $aNewMulti[$i]['OTHER_MEDIA_ID2_ZOOM'] = Frontoffice_Video_Helper::setYoutube($aInfosMedia["YOUTUBE_ID"]);
                    }

                    
                    $aNewMulti[$i]['MEDIA2_TITLE'] = $aInfosMedia['MEDIA_TITLE'];
                    $aNewMulti[$i]['MEDIA2_ALT'] = $aInfosMedia['MEDIA_ALT'];
                    $iMurImg ++;
                }
                if($aOneMulti['MEDIA_ID5']!= 0){
                    $mediaFormat = $aMurImg[$aOneMulti['PAGE_ZONE_MULTI_VALUE']][$iMurImg];
                    $aNewMulti[$i]['MEDIA_ID5'] = Pelican::$config['MEDIA_HTTP'].Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aOneMulti['MEDIA_ID5']),Pelican::$config['MEDIA_FORMAT_ID'][$mediaFormat]);
                    $aNewMulti[$i]['REAL_MEDIA_ID5'] = $aOneMulti['MEDIA_ID5'];
                    $aNewMulti[$i]['TYPE_MEDIA_ID5'] = 1;
					$aNewMulti[$i]['MEDIA_ID3_ZOOM'] = Pelican::$config['MEDIA_HTTP'].Pelican_Media::getMediaPath($aOneMulti['MEDIA_ID5']);
					$aInfosMedia =  Pelican_Cache::fetch("Media/Detail", array($aOneMulti['MEDIA_ID5']));
					$aNewMulti[$i]['MEDIA3_TITLE']	=	$aInfosMedia['MEDIA_TITLE'];
                    $aNewMulti[$i]['MEDIA3_ALT'] = $aInfosMedia['MEDIA_ALT'];                    
                    $iMurImg ++;
                }
                if($aOneMulti['MEDIA_ID6']!= 0){
                    
                    $aInfosMedia =  Pelican_Cache::fetch("Media/Detail", array($aOneMulti['MEDIA_ID6']));
                    $mediaFormat = $aMurImg[$aOneMulti['PAGE_ZONE_MULTI_VALUE']][$iMurImg];
                    //$aNewMulti[$i]['MEDIA_ID6'] = Pelican::$config['MEDIA_HTTP'].Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aOneMulti['MEDIA_ID6']),Pelican::$config['MEDIA_FORMAT_ID'][$mediaFormat]);
                    $aNewMulti[$i]['TYPE_MEDIA_ID5'] = 2;
		
                    if($aInfosMedia['MEDIA_TYPE_ID'] == 'video'){
                        $aNewMulti[$i]['MEDIA_ID3_ZOOM_FOR_DATA_VIDEO'] = Pelican::$config['MEDIA_HTTP'].$aInfosMedia["MEDIA_PATH"];
                        $aNewMulti[$i]['MEDIA_ID3_ZOOM'] = "|" .Pelican::$config['MEDIA_HTTP'].$aInfosMedia["MEDIA_PATH"];
                        
                        if ($aInfosMedia['MEDIA_ID_REFERENT'] != "" && !strpos($aNewMulti[$i]['MEDIA_ID3_ZOOM'],'|')) {
                            $aInfosMedia2 = Pelican_Cache::fetch("Media/Detail", array(
                                        $aInfosMedia['MEDIA_ID_REFERENT']
                            ));

                            $aNewMulti[$i]['MEDIA_ID3_ZOOM'] .= "|" . Pelican::$config['MEDIA_HTTP'] . $aInfosMedia2["MEDIA_PATH"];
                        }
                        else {
                            $aNewMulti[$i]['MEDIA_ID3_ZOOM'] .= "|" .Pelican::$config['MEDIA_HTTP'].$aInfosMedia["MEDIA_PATH"];
                        }
						if (intval($aInfosMedia['MEDIA_ID_REFERENT_PICTURE'])> 0 ) {
							$aMediaReferentPicture = Pelican_Cache::fetch("Media/Detail", array($aInfosMedia['MEDIA_ID_REFERENT_PICTURE'] ));
							 $aNewMulti[$i]['REFERENT_PICTURE'] = Pelican::$config['MEDIA_HTTP'].$aMediaReferentPicture['MEDIA_PATH'];
						}
                        $aNewMulti[$i]['OTHER_MEDIA_ID3_ZOOM'] = Citroen_Media::childMediaImplodeToString($aOneMulti['MEDIA_ID6'], $aInfosMedia[$i]['MEDIA_ID3_ZOOM']);                        
                    }elseif($aInfosMedia['MEDIA_TYPE_ID'] == 'youtube'){
                        $aNewMulti[$i]['MEDIA_ID3_ZOOM'] = Frontoffice_Video_Helper::setYoutube($aInfosMedia["YOUTUBE_ID"]);
                        $aNewMulti[$i]['OTHER_MEDIA_ID3_ZOOM'] = Frontoffice_Video_Helper::setYoutube($aInfosMedia["YOUTUBE_ID"]);
                    }
                    
                    $aNewMulti[$i]['MEDIA3_TITLE'] = $aInfosMedia['MEDIA_TITLE'];
                    $aNewMulti[$i]['MEDIA3_ALT'] = $aInfosMedia['MEDIA_ALT'];
                    $iMurImg ++;
                }

                $aNewMulti[$i]['PAGE_ZONE_MULTI_VALUE'] = $aOneMulti['PAGE_ZONE_MULTI_VALUE'];

                $i++;
            }
        }

        return $aNewMulti;
    }
}
?>
