<?php

class Layout_Citroen_Actualites_Contenu_Controller extends Pelican_Controller_Front {

    public function indexAction() {
        $aData = $this->getParams();
        $aParams = $this->getParams();
        $aContent = Pelican_Cache::fetch("Frontend/Citroen/Actualites/Detail", array(
                    $aData['CONTENT_ID'],
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID']
        ));
        // CPW-3600
        $_SESSION[APP]['ACTU_MEDIA_ID'] = $aContent['MEDIA_ID'];
        
        if (!$aContent) {
            $this->sendError(404, '');
        }
        $aPager = Pelican_Cache::fetch("Frontend/Citroen/Actualites/Pager", array(
                    $aData['pid'],
                    $aData['PAGE_PARENT_ID'],
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID']
        ));
        $this->assign("aPager", $aPager);
        
        if ($aContent['MEDIA_PATH']) {
            $aContent['MEDIA_PATH'] = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getFileNameMediaFormat($aContent['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['ACTUALITES_DETAIL']);
        }
        // 1 = UK 2 = FR
        if($aContent["CONTENT_CODE2"] == 1)
        {
        $aContent['DATE_FORMATEE'] = Frontoffice_Date_Helper::formatDate(2, $aContent['DATE_LETTER']);
        }
        else
        {
         $aContent['DATE_FORMATEE'] = Frontoffice_Date_Helper::formatDate(1, $aContent['DATE_LETTER']);   
        }
        if ($aContent['DOC_ID']) {
            
            $mediaDetail = Pelican_Cache::fetch("Media/Detail", array(
                $aContent['DOC_ID']
            ));
            
            if ($mediaDetail['MEDIA_TYPE_ID'] == 'video') {
                $MEDIA_VIDEO = Pelican::$config['MEDIA_HTTP'] . $mediaDetail["MEDIA_PATH"];

                if ($mediaDetail['MEDIA_ID_REFERENT'] != "") {
                    $OMP_mediaDetail = Pelican_Cache::fetch("Media/Detail", array(
                                $mediaDetail['MEDIA_ID_REFERENT']
                    ));

                    $MEDIA_VIDEO .= "|" . Pelican::$config['MEDIA_HTTP'] . $OMP_mediaDetail["MEDIA_PATH"];
                }

            } elseif ($mediaDetail['MEDIA_TYPE_ID'] == 'youtube') {
                $MEDIA_VIDEO = Frontoffice_Video_Helper::setYoutube($mediaDetail["YOUTUBE_ID"]);
            }
        }
        else {
            $MEDIA_VIDEO = '';
        }
        $this->assign('MEDIA_VIDEO', $MEDIA_VIDEO);
        
        $aPageParent = Pelican_Cache::fetch("Frontend/Page", array(
                    $aData['PAGE_PARENT_ID'],
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID']
        ));

        $aGalerieZone = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
                    $aPageParent['PAGE_ID'],
                    Pelican::$config['ZONE_TEMPLATE_ID']['ACTU_GALERIE_ZONE'],
                    $aPageParent['PAGE_VERSION'],
                    $_SESSION[APP]['LANGUE_ID']
        ));
        $sUrlRSS = $aGalerieZone['ZONE_URL'];
        $this->assign('bNewsLetter', $aGalerieZone['ZONE_TITRE11']);
        $this->assign("aPageParent", $aPageParent);
        $this->assign("aContent", $aContent);
        $this->assign("sUrlRSS", $sUrlRSS);
 

        //Sharer
        $sSharer = Backoffice_Share_Helper::getSharer($aParams['ZONE_LABEL2'], $aParams['SITE_ID'], $aParams['LANGUE_ID'], Pelican::$config['MODE_SHARER'][1], array('getParams' => $aParams));
        $this->assign("sSharer", $sSharer);

        //Sharer
        $sSharer = Backoffice_Share_Helper::getSharer($aParams['ZONE_LABEL2'], $aParams['SITE_ID'], $aParams['LANGUE_ID'], Pelican::$config['MODE_SHARER'][5], array('getParams' => $aParams));
        $this->assign("sSharerMob", $sSharer);
        
        $this->assign('aParams', $aParams);

        //Newsletters
        $pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    '',
                    Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
        ));
        $abonnements = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
                    $pageGlobal['PAGE_ID'],
                    Pelican::$config['ZONE_TEMPLATE_ID']['ABONNEMENTS'],
                    $pageGlobal['PAGE_VERSION'],
                    $_SESSION[APP]['LANGUE_ID']
        ));
        $this->assign("abonnements", $abonnements);
        $this->fetch();
    }

}