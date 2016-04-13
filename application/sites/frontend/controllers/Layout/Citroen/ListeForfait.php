<?php

/**
 * Classe d'affichage Front de la tranche ListeForfait
 *
 * @package Layout
 * @subpackage Citroen
 * @author Joseph Franclin <joseph.franclin@businessdecision.com>
 * @since 23/10/2013
 */
class Layout_Citroen_ListeForfait_Controller extends Pelican_Controller_Front {

    public function indexAction() {

        /* Initialisation des variables */
        $aParams = $this->getParams();
        /* On test si un forfait est selectionné */

        if (isset($_GET['Forfait'])) {
            /** on affiche le forfait **/
            $CONTENT_ID = $_GET['Forfait'];

            $aRsListeForfait = Pelican_Cache::fetch("Frontend/Citroen/ListeForfait", array(
                        $_SESSION[APP]['LANGUE_ID'],
                        'VISUELFORFAIT',
                        $CONTENT_ID,
                        $aParams['pid']
            ));
        } else {
        /** on force l'affichage du 1er contenu ***/
            $aRsMenuForfait = Pelican_Cache::fetch("Frontend/Citroen/MenuForfait", array(
                        $_SESSION[APP]['LANGUE_ID'],
                        $aParams['pid']
            ));
            
            $aRsMenuForfait = self::dateForfait($aRsMenuForfait);

            $aTrie = Pelican_Cache::fetch("Frontend/Page/ChildContent", array(
                    $aParams["pid"] ,
                    $aParams['SITE_ID'] ,
                    $_SESSION[APP]['LANGUE_ID'] ,
                    "CURRENT" ,
                    Pelican::$config['ASSISTANT']['CONTENT'][3] ,
                    20 ,
                    "" ,
                    "" 
                ));
            $aTrie = $aTrie[0]; 
        
            $aMenuForfaitTrie = array();               
            // tri par ordre d'affichage
            for($it=0; $it < count($aTrie); $it++)
            {
                for ($yt=0; $yt < count($aTrie); $yt++) 
                { 
                    if($aTrie[$it]["ID"] == $aRsMenuForfait[$yt]["CONTENT_ID"] )
                    {
                        $aMenuForfaitTrie[] = $aRsMenuForfait[$yt];
                    }
                }
            }
            $aRsMenuForfait = $aMenuForfaitTrie;

            
            foreach($aRsMenuForfait as $forfait){
                $CONTENT_ID = $forfait['CONTENT_ID'];
                break;
            }
            
            $aRsListeForfait = Pelican_Cache::fetch("Frontend/Citroen/ListeForfait", array(
                        $_SESSION[APP]['LANGUE_ID'],
                        'VISUELFORFAIT',
                        $CONTENT_ID,
                        $aParams['pid']
            ));
        }


        $this->assign('VIGN_GALLERY_TOP', Pelican::$config['MEDIA_HTTP'] . Citroen_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aRsListeForfait[0]['MEDIA_ID8']), Pelican::$config['MEDIA_FORMAT_ID']['WEB_2_COLONNE_ENRICHI']));

        $aMultiForfait = Pelican_Cache::fetch("Frontend/Citroen/ZonesContentMulti", array(
                    $_SESSION[APP]['LANGUE_ID'],
                    $aParams['pid'],
                    $CONTENT_ID,
                    'PRIXFORFAIT'                  
        ));

        if ($aMultiForfait) {
            $i = 0;
            foreach ($aMultiForfait as $forfait) {

                $aMultiForfait[$i]['CONTENT_ZONE_MULTI_TEXT'] = str_replace('<p>', '<div class="zonetexte"><p>', $aMultiForfait[$i]['CONTENT_ZONE_MULTI_TEXT']);
                $aMultiForfait[$i]['CONTENT_ZONE_MULTI_TEXT'] = str_replace('</p>', '</p></div>', $aMultiForfait[$i]['CONTENT_ZONE_MULTI_TEXT']);
                $aMultiForfait[$i]['CONTENT_ZONE_MULTI_TEXT'] = str_replace('<ul>', '<ul class="checks">', $aMultiForfait[$i]['CONTENT_ZONE_MULTI_TEXT']);
                $i++;
            }
        }

        $iNbMulti = count($aMultiForfait);

        $aMultiVisuel = Pelican_Cache::fetch("Frontend/Citroen/ZonesContentMulti", array(
                    $_SESSION[APP]['LANGUE_ID'],
                    $aParams['pid'],
                    $CONTENT_ID,
                    'VISUELFORFAIT'                  
        ));


        if ($aMultiVisuel) {
            $v = 0;

            for ($b = 0; $b < count($aMultiVisuel); $b++) {
            if($this->isMobile()){
                $format = Pelican::$config['MEDIA_FORMAT_ID']['MOBILE_2_COLONNE_ENRICHI'];
                
            }else{
                $format = Pelican::$config['MEDIA_FORMAT_ID']['WEB_2_COLONNE_ENRICHI'];
            }
                
                $aMEDIA_ID2[$v] = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aMultiVisuel[$b]['MEDIA_ID']),$format );
                $v++;
            }
            $this->assign('aMEDIA_ID2', $aMEDIA_ID2);
        }

        $multiValues = Pelican_Cache::fetch("Frontend/Citroen/ZonesContentMulti", array(
                    $_SESSION[APP]['LANGUE_ID'],
                    $aParams['pid'],
                    $CONTENT_ID,
                    'CTAFORFAIT'
        ));

        // Permet de récupérer les photos concernant la gallerie photos
        $multiValuesPush = Pelican_Cache::fetch("Frontend/Citroen/ZonesContentMulti", array(
                    $_SESSION[APP]['LANGUE_ID'],
                    $aParams['pid'],
                    $CONTENT_ID,
                    'GALLERYFORM'
        ));

        $this->assign('VIGN_GALLERY', Pelican_Media::getMediaPath($multiValuesPush[0]['MEDIA_ID9']));


        if (isset($aRsListeForfait[0]["CONTENT_TITLE2"]) && $aRsListeForfait[0]["CONTENT_TITLE2"] != "") {

            $aParams['ZONE_TITRE5'] = $aRsListeForfait[0]["CONTENT_TITLE2"];
            $aParams['ZONE_TITRE6'] = $aRsListeForfait[0]["CONTENT_TITLE3"];
            $aParams['ZONE_TEXTE4'] = $aRsListeForfait[0]["CONTENT_TEXT"];

            $pagePopUp = Pelican_Cache::fetch("Frontend/Page", array(
                        $aRsListeForfait[0]["CONTENT_TITLE4"],
                        $aParams['SITE_ID'],
                        $_SESSION[APP]['LANGUE_ID']
            ));
        }

        $mediaDetailPush1 = Pelican_Cache::fetch("Media/Detail", array(
                    $aRsListeForfait[0]["MEDIA_ID3"]
        ));
        $mediaDetailPush2 = Pelican_Cache::fetch("Media/Detail", array(
                    $aRsListeForfait[0]["MEDIA_ID4"]
        ));

        $mediaDetailPush3 = Pelican_Cache::fetch("Media/Detail", array(
                    $aRsListeForfait[0]["MEDIA_ID6"]
        ));
        $mediaDetailPush4 = Pelican_Cache::fetch("Media/Detail", array(
                    $aRsListeForfait[0]["MEDIA_ID5"]
        ));

        // mention legale
        $mediaDetailPush5 = Pelican_Cache::fetch("Media/Detail", array(
                    $aRsListeForfait[0]["MEDIA_ID2"]
        ));

        // Construction du tableau pour la gallerie photo
        if (isset($multiValuesPush)) {
            foreach ($multiValuesPush as $key => $aColonne) {
                $mediaDetailCol = Pelican_Cache::fetch("Media/Detail", array(
                            $aColonne["MEDIA_ID"]
                ));
                $aMedias[$aRsListeForfait[0]['CONTENT_CODE']]['IMAGE'][$key]["MEDIA_PATH"] = $mediaDetailCol["MEDIA_PATH"];
                $aMedias[$aRsListeForfait[0]['CONTENT_CODE']]['IMAGE'][$key]["MEDIA_ALT"] = $mediaDetailCol["MEDIA_TITLE"];
            }

            if ($multiValuesPush[0]['CONTENT_TITLE7'] != "") {
                $aMedias[$aRsListeForfait[0]['CONTENT_CODE']]['MEDIA_TITLE'] = $multiValuesPush[0]['CONTENT_TITLE7'];
            }
        }

        // Construction du tableau pour la vidéo1
        if (isset($mediaDetailPush1['MEDIA_PATH']) && !empty($mediaDetailPush1['MEDIA_PATH']) && isset($mediaDetailPush2['MEDIA_PATH']) && !empty($mediaDetailPush2['MEDIA_PATH'])) {
            $aMedias[$aRsListeForfait[0]['CONTENT_CODE3']]['VIDEO']['MEDIA_PATH'] = $mediaDetailPush1['MEDIA_PATH'];
            $aMedias[$aRsListeForfait[0]['CONTENT_CODE3']]['VIDEO']['MEDIA_TYPE_ID'] = $mediaDetailPush1['MEDIA_TYPE_ID'];
            if ($mediaDetailPush1['MEDIA_TYPE_ID'] == "youtube") {
                $aMedias[$aRsListeForfait[0]['CONTENT_CODE3']]['VIDEO']['YOUTUBE_ID'] = $mediaDetailPush1['YOUTUBE_ID'];
                $aMedias[$aRsListeForfait[0]['CONTENT_CODE3']]['VIDEO']['YOUTUBE_URL'] = Frontoffice_Video_Helper::setYoutube($mediaDetailPush1['YOUTUBE_ID']);
            }
            $aMedias[$aRsListeForfait[0]['CONTENT_CODE3']]['VIDEO']['MEDIA_ALT'] = $mediaDetailPush1["MEDIA_TITLE"];
            $aMedias[$aRsListeForfait[0]['CONTENT_CODE3']]['VIDEO']['MEDIA_PATH2'] = $mediaDetailPush2['MEDIA_PATH'];
            $aMedias[$aRsListeForfait[0]['CONTENT_CODE3']]['VIDEO']['MEDIA_ALT2'] = $mediaDetailPush2["MEDIA_TITLE"];
            $aMedias[$aRsListeForfait[0]['CONTENT_CODE3']]['VIDEO']['MEDIA_TITLE'] = $aRsListeForfait[0]['CONTENT_TITLE5'];

            if ($mediaDetailPush1['MEDIA_TYPE_ID'] != "youtube") {
                $aMedias[$aRsListeForfait[0]['CONTENT_CODE3']]['VIDEO']['OTHER_MEDIA_PATH'] = Pelican::$config['MEDIA_HTTP'] . $mediaDetailPush1["MEDIA_PATH"];
                if ($mediaDetailPush1['MEDIA_ID_REFERENT'] != "") {
                    $OMP_mediaDetail = Pelican_Cache::fetch("Media/Detail", array(
                                $mediaDetailPush1['MEDIA_ID_REFERENT']
                    ));
                    $aMedias[$aRsListeForfait[0]['CONTENT_CODE3']]['VIDEO']['OTHER_MEDIA_PATH'] .= "|" . Pelican::$config['MEDIA_HTTP'] . $OMP_mediaDetail["MEDIA_PATH"];
                }
            } else {
                $aMedias[$aRsListeForfait[0]['CONTENT_CODE3']]['VIDEO']['OTHER_MEDIA_PATH'] = Frontoffice_Video_Helper::setYoutube($mediaDetailPush1['YOUTUBE_ID']);
            }
        }

        // Construction du tableau pour la vidéo2
        if (isset($mediaDetailPush3['MEDIA_PATH']) && !empty($mediaDetailPush3['MEDIA_PATH']) && isset($mediaDetailPush4['MEDIA_PATH']) && !empty($mediaDetailPush4['MEDIA_PATH'])) {
            $aMedias[$aRsListeForfait[0]['CONTENT_CODE2']]['VIDEO']['MEDIA_PATH'] = $mediaDetailPush4['MEDIA_PATH'];
            $aMedias[$aRsListeForfait[0]['CONTENT_CODE2']]['VIDEO']['MEDIA_TYPE_ID'] = $mediaDetailPush4['MEDIA_TYPE_ID'];
            if ($mediaDetailPush4['MEDIA_TYPE_ID'] == "youtube") {
                $aMedias[$aRsListeForfait[0]['CONTENT_CODE2']]['VIDEO']['YOUTUBE_ID'] = $mediaDetailPush4['YOUTUBE_ID'];
                $aMedias[$aRsListeForfait[0]['CONTENT_CODE2']]['VIDEO']['YOUTUBE_URL'] = Frontoffice_Video_Helper::setYoutube($mediaDetailPush4['YOUTUBE_ID']);
            }
            $aMedias[$aRsListeForfait[0]['CONTENT_CODE2']]['VIDEO']['MEDIA_ALT'] = $mediaDetailPush4["MEDIA_TITLE"];
            $aMedias[$aRsListeForfait[0]['CONTENT_CODE2']]['VIDEO']['MEDIA_PATH2'] = $mediaDetailPush3['MEDIA_PATH'];
            $aMedias[$aRsListeForfait[0]['CONTENT_CODE2']]['VIDEO']['MEDIA_ALT2'] = $mediaDetailPush3["MEDIA_TITLE"];
            $aMedias[$aRsListeForfait[0]['CONTENT_CODE2']]['VIDEO']['MEDIA_TITLE'] = $aRsListeForfait[0]['CONTENT_TITLE6'];

            if ($mediaDetailPush4['MEDIA_TYPE_ID'] != "youtube") {
                $aMedias[$aRsListeForfait[0]['CONTENT_CODE2']]['VIDEO']['OTHER_MEDIA_PATH'] = Pelican::$config['MEDIA_HTTP'] . $mediaDetailPush4["MEDIA_PATH"];
                if ($mediaDetailPush4['MEDIA_ID_REFERENT'] != "") {
                    $OMP_mediaDetail = Pelican_Cache::fetch("Media/Detail", array(
                                $mediaDetailPush4['MEDIA_ID_REFERENT']
                    ));
                    $aMedias[$aRsListeForfait[0]['CONTENT_CODE2']]['VIDEO']['OTHER_MEDIA_PATH'] .= "|" . Pelican::$config['MEDIA_HTTP'] . $OMP_mediaDetail["MEDIA_PATH"];
                }
            } else {
                $aMedias[$aRsListeForfait[0]['CONTENT_CODE2']]['VIDEO']['OTHER_MEDIA_PATH'] = Frontoffice_Video_Helper::setYoutube($mediaDetailPush4['YOUTUBE_ID']);
            }
        }

        // Permet de gérer l'ordre d'affichage des push média via le champs order
        // Ordre pour la video1         =>  $aParams['ZONE_ATTRIBUT3']
        // Ordre pour la video2         =>  $aParams['ZONE_ATTRIBUT2']
        // Ordre pour la gallerie photo =>  $aParams['ZONE_ATTRIBUT']

        if (is_array($aMedias) && !empty($aMedias)) {
            ksort($aMedias);
        }

        if ($aMultiForfait) {
            foreach ($aMultiForfait as $key => $aMulti) {
                $aMultiForfait[$key]['CONTENT_ZONE_MULTI_TEXT'] = str_replace("<ul>", "<ul class='checks'>", $aMulti['CONTENT_ZONE_MULTI_TEXT']);
            }
        }

        $mediaVignette = Pelican_Cache::fetch("Media/Detail", array(
                    $aRsListeForfait[0]['MEDIA_ID8']
        ));
        
        if ($aRsListeForfait[0]['MEDIA_ID']) {

            $mediaVideo = Pelican_Cache::fetch("Media/Detail", array(
                        $aRsListeForfait[0]['MEDIA_VIDEO']
            ));
            
            $MEDIA_VIDEO = '';
            if ($mediaVideo['MEDIA_TYPE_ID'] == 'video') {
                $MEDIA_VIDEO = Pelican::$config['MEDIA_HTTP'] . $mediaVideo["MEDIA_PATH"];

                if ($mediaVideo['MEDIA_ID_REFERENT'] != "") {
                    $OMP_mediaDetail = Pelican_Cache::fetch("Media/Detail", array(
                                $mediaVideo['MEDIA_ID_REFERENT']
                    ));

                    $MEDIA_VIDEO .= "|" . Pelican::$config['MEDIA_HTTP'] . $OMP_mediaDetail["MEDIA_PATH"];
                }
            } elseif ($mediaVideo['MEDIA_TYPE_ID'] == 'youtube') {
                $MEDIA_VIDEO = Frontoffice_Video_Helper::setYoutube($mediaVideo["YOUTUBE_ID"]);
            }
        }

        $this->assign('MEDIA_PATH', $mediaVignette['MEDIA_PATH']);
        $this->assign('MEDIA_VIDEO', $MEDIA_VIDEO);
        
        $this->assign('aPush', $multiValuesPush);
        $this->assign('aCta', $multiValues);
        $this->assign('aMedias', $aMedias);

        $this->assign('aListeForfait', $aRsListeForfait[0]);
        $this->assign('MultiForfait', $aMultiForfait);
        $this->assign('iNbMulti', $iNbMulti);
        $aParams['TITRE_FORFAIT'] = $aRsListeForfait[0]['TITRE'];
        $this->assign('aParams', $aParams);

        $this->assign('urlPopInMention', $pagePopUp["PAGE_CLEAR_URL"]);
        $this->assign('titlePopInMention', $pagePopUp["PAGE_TITLE"]);
        $this->assign('MEDIA_PATH4', $mediaDetailPush5["MEDIA_PATH"]);
        $this->assign('MEDIA_TITLE4', $mediaDetailPush5["MEDIA_TITLE"]);


        $this->fetch();
    }

    static private function dateForfait($aResultsForfait) {

        $aTemp = array();
        $n = 0;
        // test des dates de publications et fin
        if (is_array($aResultsForfait) && !empty($aResultsForfait)) {
            foreach ($aResultsForfait as $aOneHistoire) {
                if ($aOneHistoire['CONTENT_START_DATE']) {
                    //si date de debut
                    if ($aOneHistoire['CONTENT_END_DATE']) {
                        //si date debut + fin
                        if ($aOneHistoire['CONTENT_END_DATE'] >= date("Y-m-d G:i:s") && date("Y-m-d G:i:s") >= $aOneHistoire['CONTENT_START_DATE']) {
                            $aTemp[$n] = $aOneHistoire;
                        }
                    } else {
                        //juste debut
                        if (date("Y-m-d G:i:s") >= $aOneHistoire['CONTENT_START_DATE']) {
                            $aTemp[$n] = $aOneHistoire;
                        }
                    }
                } else {
                    if ($aOneHistoire['CONTENT_END_DATE']) {
                        //si date de fin sans debut
                        if ($aOneHistoire['CONTENT_END_DATE'] >= date("Y-m-d G:i:s")) {
                            $aTemp[$n] = $aOneHistoire;
                        }
                    } else {
                        //si ni fin ni debut
                        $aTemp[$n] = $aOneHistoire;
                    }
                }
                $n++;
            }
        }
        return $aTemp;
    }

}

?>
