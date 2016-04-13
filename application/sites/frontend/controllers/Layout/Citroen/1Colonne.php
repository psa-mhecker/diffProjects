<?php

require_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Showroom.php');

class Layout_Citroen_1Colonne_Controller extends Pelican_Controller_Front {

    public function indexAction() {
        $aData = $this->getParams();
        $this->assign('dataLayer', Citroen\GTM::$dataLayer);

        if ($aData["ZONE_WEB"] == 1 || $aData["ZONE_MOBILE"] == 1) {
            if (!empty($aData["MEDIA_ID"])) {

                // image 16/9
                $mediaDetail = Pelican_Cache::fetch("Media/Detail", array(
                            $aData["MEDIA_ID"]
                ));

                $this->assign('MEDIA_PATH', $mediaDetail["MEDIA_PATH"]);
                $this->assign('MEDIA_TITLE', $mediaDetail["MEDIA_TITLE"]);
                $this->assign('MEDIA_ALT', $mediaDetail["MEDIA_ALT"]);
                // video 
            }else {
                $this->assign('MEDIA_PATH', '');
                $this->assign('MEDIA_TITLE', '');
                $this->assign('MEDIA_ALT', '');
            }
            if (!empty($aData["MEDIA_ID2"])) {

                $httpMEDIA2 = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getMediaPath($aData["MEDIA_ID2"]);

                $this->assign('MEDIA_FLASH', $httpMEDIA2);

                $mediaDetailAlt = Pelican_Cache::fetch("Media/Detail", array(
                            $aData["MEDIA_ID3"]
                ));

                $this->assign('MEDIA_PATH3', $mediaDetailAlt["MEDIA_PATH"]);
                $this->assign('MEDIA_TITLE3', $mediaDetailAlt["MEDIA_TITLE"]);
                $this->assign('MEDIA_ALT3', $mediaDetailAlt["MEDIA_ALT"]);
            }else {
                $this->assign('MEDIA_PATH3', '');
                $this->assign('MEDIA_TITLE3', '');
                $this->assign('MEDIA_ALT3', '');
            }

            // infos du média pour la vidéo 1
            $mediaDetailPush1 = Pelican_Cache::fetch("Media/Detail", array(
                        $aData["MEDIA_ID7"]
            ));

            // infos du média pour la vidéo 2
            $mediaDetailPush2 = Pelican_Cache::fetch("Media/Detail", array(
                        $aData["MEDIA_ID8"]
            ));

            // infos du média pour la vidéo 1
            $mediaDetailPush5 = Pelican_Cache::fetch("Media/Detail", array(
                        $aData["MEDIA_ID5"]
            ));

            // infos du média pour la vidéo 2
            $mediaDetailPush9 = Pelican_Cache::fetch("Media/Detail", array(
                        $aData["MEDIA_ID9"]
            ));
            $mediaDetailPush4 = Pelican_Cache::fetch("Media/Detail", array(
                        $aData["MEDIA_ID4"]
            ));
			
			//info media video 
			if (!empty($aData["MEDIA_ID11"])) {
				
				$aMedia11Referent="";
				$aMedias11ReferentPicture="";

				$sMedia11Http     = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getMediaPath($aData["MEDIA_ID11"]);
				$aMedia11Details  = Pelican_Cache::fetch("Media/Detail", array($aData["MEDIA_ID11"]));
				
				if (intval($aMedia11Details['MEDIA_ID_REFERENT'])> 0 ) {
					$aMedia11ReferentVideo = Pelican_Cache::fetch("Media/Detail", array($aMedia11Details['MEDIA_ID_REFERENT'] ));
					$aMedia11Referent = Pelican::$config['MEDIA_HTTP'].$aMedia11ReferentVideo["MEDIA_PATH"];
				}
				 
				if (intval($aMedia11Details['MEDIA_ID_REFERENT_PICTURE'])> 0 ) {
						$aMediaReferentPicture    = Pelican_Cache::fetch("Media/Detail", array($aMedia11Details['MEDIA_ID_REFERENT_PICTURE'] ));
						$aMedias11ReferentPicture = Pelican::$config['MEDIA_HTTP'].$aMediaReferentPicture['MEDIA_PATH'];
				}
				
				$sMediaVideo = Frontoffice_Video_Helper::getPlayer($aData["MEDIA_ID11"],$aMedias11ReferentPicture);
				
	
				$this->assign('MEDIA11_VIDEO', $sMedia11Http);
				$this->assign('MEDIA11_PATH', $aMedia11Details["MEDIA_PATH"]);
				$this->assign('MEDIA11_TITLE', $aMedia11Details["MEDIA_TITLE"]);
				$this->assign('MEDIA11_ALT', $aMedia11Details["MEDIA_ALT"]);
				$this->assign('MEDIA11_AUTOLOAD', $aData["MEDIA_AUTOLOAD"]);
				$this->assign('MEDIA11_REFERENT', $aMedia11Referent);
				$this->assign('MEDIA11_REFERENT_PICTURE', $aMedias11ReferentPicture);
				$this->assign('sMediaVideo',$sMediaVideo,false);
            }

            $this->assign('VIGN_GALLERY', Pelican_Media::getMediaPath($aData['MEDIA_ID10']));

            if (isset($aData["ZONE_TITRE7"]) && $aData["ZONE_TITRE7"] != "") {
                $pagePopUp = Pelican_Cache::fetch("Frontend/Page", array(
                            $aData["ZONE_TITRE7"],
                            $aData['SITE_ID'],
                            $aData['LANGUE_ID']
                ));
            }

            $multiValues = Citroen_Cache::fetchProfiling($aData['ZONE_PERSO'], "Frontend/Citroen/ZoneMulti", array(
                        $aData['PAGE_ID'],
                        $aData['LANGUE_ID'],
                        Pelican::getPreviewVersion(),
                        $aData['ZONE_TEMPLATE_ID'],
                        'CTAFORM',
                        $aData['AREA_ID'],
                        $aData['ZONE_ORDER']
            ));

            if(is_array($multiValues)&& !empty($multiValues)){
                foreach($multiValues as $key=> $multi){
                    if(isset($multi['OUTIL']) && !empty($multi['OUTIL'])){
                         $aData['CTA'] = $multi['OUTIL'];
						 if($this->isMobile()){
							$aData['CTA']['ADD_CTT'] = 'buttonLeadPicto';
						 }
                         $multiValues[$key]['OUTIL'] = Pelican_Request::call('_/Layout_Citroen_CTA/',$aData);
                    }
                }
            }


            // Permet de récupérer les photos concernant la gallerie photos
            $multiValuesPush = Citroen_Cache::fetchProfiling($aData['ZONE_PERSO'], "Frontend/Citroen/ZoneMulti", array(
                        $aData['PAGE_ID'],
                        $aData['LANGUE_ID'],
                        Pelican::getPreviewVersion(),
                        $aData['ZONE_TEMPLATE_ID'],
                        'GALLERYFORM',
                        $aData['AREA_ID'],
                        $aData['ZONE_ORDER']
            ));
            // Construction du tableau pour la gallerie photo
            if (isset($multiValuesPush)) {
                foreach ($multiValuesPush as $key => $aColonne) {
                    $mediaDetailCol = Pelican_Cache::fetch("Media/Detail", array(
                                $aColonne["MEDIA_ID"]
                    ));
                    $aMedias[$aData['ZONE_ATTRIBUT']]['IMAGE'][$key]["MEDIA_PATH"] = $mediaDetailCol["MEDIA_PATH"];
                    $aMedias[$aData['ZONE_ATTRIBUT']]['IMAGE'][$key]["MEDIA_ALT"] = $mediaDetailCol["MEDIA_ALT"];
                    $aMedias[$aData['ZONE_ATTRIBUT']]['IMAGE'][$key]["MEDIA_ID"] = $mediaDetailCol["MEDIA_ID"];
                }
                if ($aData['ZONE_TITRE21'] != "") {
                    $aMedias[$aData['ZONE_ATTRIBUT']]['MEDIA_TITLE'] = $aData['ZONE_TITRE21'];
                }
            }

            // Construction du tableau pour la vidéo1
            if (isset($mediaDetailPush5['MEDIA_PATH']) && !empty($mediaDetailPush5['MEDIA_PATH']) && isset($mediaDetailPush1['MEDIA_PATH']) && !empty($mediaDetailPush1['MEDIA_PATH'])) {
                $aMedias[$aData['ZONE_ATTRIBUT3']]['VIDEO']['MEDIA_PATH'] = $mediaDetailPush5['MEDIA_PATH'];
                $aMedias[$aData['ZONE_ATTRIBUT3']]['VIDEO']['MEDIA_ID'] = $mediaDetailPush5['MEDIA_ID'];
                $aMedias[$aData['ZONE_ATTRIBUT3']]['VIDEO']['MEDIA_TYPE_ID'] = $mediaDetailPush5['MEDIA_TYPE_ID'];
                if ($mediaDetailPush5['MEDIA_TYPE_ID'] == "youtube") {
                    $aMedias[$aData['ZONE_ATTRIBUT3']]['VIDEO']['YOUTUBE_ID'] = $mediaDetailPush5['YOUTUBE_ID'];
                    $aMedias[$aData['ZONE_ATTRIBUT3']]['VIDEO']['YOUTUBE_URL'] = Frontoffice_Video_Helper::setYoutube($mediaDetailPush5['YOUTUBE_ID']);
                }
                $aMedias[$aData['ZONE_ATTRIBUT3']]['VIDEO']['MEDIA_ALT'] = $mediaDetailPush5["MEDIA_ALT"];
                $aMedias[$aData['ZONE_ATTRIBUT3']]['VIDEO']['MEDIA_PATH2'] = $mediaDetailPush1['MEDIA_PATH'];
                $aMedias[$aData['ZONE_ATTRIBUT3']]['VIDEO']['MEDIA_ALT2'] = $mediaDetailPush1["MEDIA_ALT"];
                $aMedias[$aData['ZONE_ATTRIBUT3']]['VIDEO']['MEDIA_ID2'] = $mediaDetailPush1["MEDIA_ID"];
                $aMedias[$aData['ZONE_ATTRIBUT3']]['VIDEO']['MEDIA_TITLE'] = $aData['ZONE_TITRE15'];

                if ($mediaDetailPush5['MEDIA_TYPE_ID'] != "youtube") {
                    $aMedias[$aData['ZONE_ATTRIBUT3']]['VIDEO']['OTHER_MEDIA_PATH'] = Pelican::$config['MEDIA_HTTP'] . $mediaDetailPush5["MEDIA_PATH"];
                    if ($mediaDetailPush5['MEDIA_ID_REFERENT'] != "") {
                        $OMP_mediaDetail5 = Pelican_Cache::fetch("Media/Detail", array(
                                    $mediaDetailPush5['MEDIA_ID_REFERENT']
                        ));
                        $aMedias[$aData['ZONE_ATTRIBUT3']]['VIDEO']['OTHER_MEDIA_PATH'] .= "|" . Pelican::$config['MEDIA_HTTP'] . $OMP_mediaDetail5["MEDIA_PATH"];
                    }
                } else {
                    $aMedias[$aData['ZONE_ATTRIBUT3']]['VIDEO']['OTHER_MEDIA_PATH'] = Frontoffice_Video_Helper::setYoutube($mediaDetailPush5['YOUTUBE_ID']);
                }
            }

            // Construction du tableau pour la vidéo2
            if (isset($mediaDetailPush9['MEDIA_PATH']) && !empty($mediaDetailPush9['MEDIA_PATH']) && isset($mediaDetailPush2['MEDIA_PATH']) && !empty($mediaDetailPush2['MEDIA_PATH'])) {
                $aMedias[$aData['ZONE_ATTRIBUT2']]['VIDEO']['MEDIA_PATH'] = $mediaDetailPush9['MEDIA_PATH'];
                $aMedias[$aData['ZONE_ATTRIBUT2']]['VIDEO']['MEDIA_ID'] = $mediaDetailPush9['MEDIA_ID'];
                $aMedias[$aData['ZONE_ATTRIBUT2']]['VIDEO']['MEDIA_TYPE_ID'] = $mediaDetailPush9['MEDIA_TYPE_ID'];
                if ($mediaDetailPush9['MEDIA_TYPE_ID'] == "youtube") {
                    $aMedias[$aData['ZONE_ATTRIBUT2']]['VIDEO']['YOUTUBE_ID'] = $mediaDetailPush9['YOUTUBE_ID'];
                    $aMedias[$aData['ZONE_ATTRIBUT2']]['VIDEO']['YOUTUBE_URL'] = Frontoffice_Video_Helper::setYoutube($mediaDetailPush9['YOUTUBE_ID']);
                }
                $aMedias[$aData['ZONE_ATTRIBUT2']]['VIDEO']['MEDIA_ALT'] = $mediaDetailPush9["MEDIA_ALT"];
                $aMedias[$aData['ZONE_ATTRIBUT2']]['VIDEO']['MEDIA_PATH2'] = $mediaDetailPush2['MEDIA_PATH'];
                $aMedias[$aData['ZONE_ATTRIBUT2']]['VIDEO']['MEDIA_ALT2'] = $mediaDetailPush2["MEDIA_ALT"];
                $aMedias[$aData['ZONE_ATTRIBUT2']]['VIDEO']['MEDIA_ID2'] = $mediaDetailPush2["MEDIA_ID"];
                $aMedias[$aData['ZONE_ATTRIBUT2']]['VIDEO']['MEDIA_TITLE'] = $aData['ZONE_TITRE20'];
                if ($mediaDetailPush9['MEDIA_TYPE_ID'] != "youtube") {
                    $aMedias[$aData['ZONE_ATTRIBUT2']]['VIDEO']['OTHER_MEDIA_PATH'] = Pelican::$config['MEDIA_HTTP'] . $mediaDetailPush9["MEDIA_PATH"];
                    if ($mediaDetailPush9['MEDIA_ID_REFERENT'] != "") {
                        $OMP_mediaDetail9 = Pelican_Cache::fetch("Media/Detail", array(
                                    $mediaDetailPush9['MEDIA_ID_REFERENT']
                        ));

                        $aMedias[$aData['ZONE_ATTRIBUT2']]['VIDEO']['OTHER_MEDIA_PATH'] .= "|" . Pelican::$config['MEDIA_HTTP'] . $OMP_mediaDetail9["MEDIA_PATH"];
                    }
                } else {
                    $aMedias[$aData['ZONE_ATTRIBUT2']]['VIDEO']['OTHER_MEDIA_PATH'] = Frontoffice_Video_Helper::setYoutube($mediaDetailPush9['YOUTUBE_ID']);
                }
            }

            // Permet de gérer l'ordre d'affichage des push média via le champs order
            // Ordre pour la video1         =>  $aData['ZONE_ATTRIBUT3']
            // Ordre pour la video2         =>  $aData['ZONE_ATTRIBUT2']
            // Ordre pour la gallerie photo =>  $aData['ZONE_ATTRIBUT']

            if (is_array($aMedias) && !empty($aMedias)) {
                ksort($aMedias);
            }
			

           
            $this->assign('aPush', $multiValuesPush);
            $this->assign('aCta', $multiValues);
            $this->assign('aMedias', $aMedias);
            $this->assign('aData', $aData);

            //mentions légales
            $this->assign('urlPopInMention', $pagePopUp["PAGE_CLEAR_URL"]);
            $this->assign('titlePopInMention', $pagePopUp["PAGE_TITLE"]);
            $this->assign('MEDIA_PATH4', $mediaDetailPush4["MEDIA_PATH"]);
            $this->assign('MEDIA_TITLE4', $mediaDetailPush4["MEDIA_TITLE"]);
            $this->assign('MEDIA_ALT4', $mediaDetailPush4["MEDIA_ALT"]);
        } else {
            unset($aData);
            $aData["ZONE_WEB"] = 0;
            $aData["ZONE_MOBILE"] = 0;
            $this->assign('aData', $aData);
        }
        $this->fetch();
    }

}