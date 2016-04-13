<?php

class Layout_Citroen_Technologie_Detail_Controller extends Pelican_Controller_Front {

    public function indexAction() {
        $aData = $this->getParams();

        if ($aData["ZONE_WEB"] == 1 || $aData["ZONE_MOBILE"] == 1) {
            
            $aTechnologies = Pelican_Cache::fetch("Frontend/Citroen/Technologie/Gallerie", array(
                $_SESSION[APP]['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                Pelican::getPreviewVersion(),
                $aData["PAGE_PARENT_ID"]
            ));
            $aNumericIndexedTechnologies = array();

            if($aTechnologies)
            {
                foreach($aTechnologies as $theme=>$technology){
                  foreach ($technology as $sTechnology) {
                     $aNumericIndexedTechnologies[]= $sTechnology;
                  }
                }
             }

            for($i=0;$i<count($aNumericIndexedTechnologies);$i++){
                if($aData['PAGE_ID']==$aNumericIndexedTechnologies[$i]['PAGE_ID']){
                    $currentPage = $i;
                }
            }
   
            if(isset($aNumericIndexedTechnologies[$currentPage-1])){
                $previousPage = $currentPage-1;
            }else{
                $previousPage = null;
            }
            if(isset($aNumericIndexedTechnologies[$currentPage+1])){
                $nextPage = $currentPage+1;
            }else{
                $nextPage=null;
            }
            
            if (isset($aData['MEDIA_ID']))
                $aData['MEDIA_ID'] = Pelican::$config['MEDIA_HTTP'] . Citroen_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aData['MEDIA_ID']), Pelican::$config['MEDIA_FORMAT_ID']['WEB_TECHNO_DETAIL']);

            $mediaDetail = Pelican_Cache::fetch("Media/Detail", array(
                        $aData["MEDIA_ID"]
            ));

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
           
                 $this->assign('VIGN_GALLERY', Pelican_Media::getMediaPath($aData['MEDIA_ID10']));
            

            if (isset($aData["ZONE_TITRE7"]) && $aData["ZONE_TITRE7"] != "") {
                $pagePopUp = Pelican_Cache::fetch("Frontend/Page", array(
                            $aData["ZONE_TITRE7"],
                            $aData['SITE_ID'],
                            $aData['LANGUE_ID']
                ));
            }

            $multiValues = Citroen_Cache::fetchProfiling($aData['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
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
                        
                        $multiValues[$key]['OUTIL'] = Pelican_Request::call('_/Layout_Citroen_CTA/',$aData);
                    }
                }
            }

           // Permet de récupérer les photos concernant la gallerie photos
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
                    $aMedias[$aData['ZONE_ATTRIBUT']]['IMAGE'][$key]["MEDIA_ALT"] = $mediaDetailCol["MEDIA_TITLE"];
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
                $aMedias[$aData['ZONE_ATTRIBUT3']]['VIDEO']['MEDIA_ALT'] = $mediaDetailPush5["MEDIA_TITLE"];
                $aMedias[$aData['ZONE_ATTRIBUT3']]['VIDEO']['MEDIA_PATH2'] = $mediaDetailPush1['MEDIA_PATH'];
                $aMedias[$aData['ZONE_ATTRIBUT3']]['VIDEO']['MEDIA_ALT2'] = $mediaDetailPush1["MEDIA_TITLE"];
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
					if (intval($mediaDetailPush5['MEDIA_ID_REFERENT_PICTURE'])> 0 ) {
								$aMediaReferentPicture = Pelican_Cache::fetch("Media/Detail", array($mediaDetailPush5['MEDIA_ID_REFERENT_PICTURE'] ));
								$aMedias[$aData['ZONE_ATTRIBUT3']]['VIDEO']['REFERENT_PICTURE'] = Pelican::$config['MEDIA_HTTP'].$aMediaReferentPicture['MEDIA_PATH'];
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
                $aMedias[$aData['ZONE_ATTRIBUT2']]['VIDEO']['MEDIA_ALT'] = $mediaDetailPush9["MEDIA_TITLE"];
                $aMedias[$aData['ZONE_ATTRIBUT2']]['VIDEO']['MEDIA_PATH2'] = $mediaDetailPush2['MEDIA_PATH'];
                $aMedias[$aData['ZONE_ATTRIBUT2']]['VIDEO']['MEDIA_ALT2'] = $mediaDetailPush2["MEDIA_TITLE"];
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
					if (intval($mediaDetailPush9['MEDIA_ID_REFERENT_PICTURE'])> 0 ) {
							$aMediaReferentPicture = Pelican_Cache::fetch("Media/Detail", array($mediaDetailPush9['MEDIA_ID_REFERENT_PICTURE'] ));
							$aMedias[$aData['ZONE_ATTRIBUT2']]['VIDEO']['REFERENT_PICTURE'] = Pelican::$config['MEDIA_HTTP'].$aMediaReferentPicture['MEDIA_PATH'];
						}
                } else {
                    $aMedias[$aData['ZONE_ATTRIBUT2']]['VIDEO']['OTHER_MEDIA_PATH'] = Frontoffice_Video_Helper::setYoutube($mediaDetailPush9['YOUTUBE_ID']);
                }
            }


            // Permet de gérer l'ordre d'affichage des push média via le champs order
            // Ordre pour la video1         =>  $aData['ZONE_ATTRIBUT3']
            // Ordre pour la video2         =>  $aData['ZONE_ATTRIBUT2']
            // Ordre pour la gallerie photo =>  $aData['ZONE_ATTRIBUT']

            if (sizeof($aMedias) > 0) {
                ksort($aMedias);
            }

            $this->assign('aPush', $multiValuesPush);
            $this->assign('aCta', $multiValues);
            $this->assign('aMedias', $aMedias);
            $this->assign('aData', $aData);
        } else {
            unset($aData);
            $aData["ZONE_WEB"] = 0;
            $aData["ZONE_MOBILE"] = 0;
            $this->assign('aData', $aData);
        }

        //Sharer
        $sSharer = Backoffice_Share_Helper::getSharer($aData['ZONE_LABEL2'], $aData['SITE_ID'], $aData['LANGUE_ID'], Pelican::$config['MODE_SHARER'][0], array('getParams' => $aData));

        //Mentions légales
        if ($aData['ZONE_TITRE7'] != '') {
            $aMentionsLegales = Pelican_Cache::fetch("Frontend/Page", array(
                        $aData['ZONE_TITRE7'],
                        $_SESSION[APP]['SITE_ID'],
                        $_SESSION[APP]['LANGUE_ID'],
                        Pelican::getPreviewVersion()
            ));
        }

        if ($aData['MEDIA_ID4'] != '') {
            $sVisuelML = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aData['MEDIA_ID4']), Pelican::$config['MEDIA_FORMAT_ID']['ACTUALITES_PETIT']);
        }
        $this->assign('aMentionsLegales', $aMentionsLegales);
        $this->assign('sVisuelML', $sVisuelML);
        $this->assign('aTechnologies', $aNumericIndexedTechnologies);
        $this->assign('currentPage', $currentPage);
        $this->assign('previousPage', $previousPage);
        $this->assign('nextPage', $nextPage);
        $this->assign("sSharer", $sSharer);
        $this->fetch();
    }

}

?>
