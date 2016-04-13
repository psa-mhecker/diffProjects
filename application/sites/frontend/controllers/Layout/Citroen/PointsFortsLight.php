<?php

require_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Showroom.php');

class Layout_Citroen_PointsFortsLight_Controller extends Pelican_Controller_Front {

    public function indexAction() {
        $aData = $this->getParams();

        //Récupération du multi
        $aSlideShow = Pelican_Cache::fetch("Frontend/Citroen/ZoneMulti", array(
                    $aData['PAGE_ID'],
                    $aData['LANGUE_ID'],
                    Pelican::getPreviewVersion(),
                    $aData['ZONE_TEMPLATE_ID'],
                    'POINTS_FORT_LIGHT',
                    $aData['AREA_ID'],
                    $aData['ZONE_ORDER']
        ));

        if (isset($aData['PAGE_VEHICULE'])) {
            $vehicule = Pelican_Cache::fetch("Frontend/Citroen/VehiculeById", array(
                        $aData['PAGE_VEHICULE'],
                        $_SESSION[APP]['SITE_ID'],
                        $_SESSION[APP]['LANGUE_ID']
            ));
            $vehicule_label = $vehicule['VEHICULE_LABEL'];
        }

        if (is_array($aSlideShow) && count($aSlideShow) > 0) {
            foreach ($aSlideShow as $key => $result) {

                if ($result['MEDIA_ID']) {
                    $aSlideShow[$key]['MEDIA_GRNAD_VISUEL'] = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getMediaPath($result['MEDIA_ID']);
                }
                if ($result['MEDIA_ID5']) {
                    $aSlideShow[$key]['3_COLONNE_MIXTE_GAUCHE'] = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getMediaPath($result['MEDIA_ID5']);
                }
                if ($result['MEDIA_ID6']) {
                    $aSlideShow[$key]['3_COLONNE_MIXTE_DROITE'] = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getMediaPath($result['MEDIA_ID6']);
                }
                if ($result['MEDIA_ID3']) {
                    $aSlideShow[$key]['SUPERPOSITION_VISUELS'] = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getMediaPath($result['MEDIA_ID3']);
                }
                if ($result['MEDIA_ID2']) {
                    $aSlideShow[$key]['MEDIA_GRNAD_VISUEL_MOBILE'] = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getMediaPath($result['MEDIA_ID2']);
                }
                if ($result['MEDIA_ID4']) {
                    $aSlideShow[$key]['SUPERPOSITION_VISUELS_MOBILE'] = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getMediaPath($result['MEDIA_ID4']);
                }
                if ($result['MEDIA_ID7']) {
                    $aSlideShow[$key]['3_COLONNE_MIXTE_MOBILE_HAUT'] = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getMediaPath($result['MEDIA_ID7']);
                }
                if ($result['MEDIA_ID12']) {
                    $aSlideShow[$key]['3_COLONNE_MIXTE_MOBILE_BAS'] = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getMediaPath($result['MEDIA_ID12']);
                }

                if (strpos($result['PAGE_ZONE_MULTI_LABEL3'], 'DROITE') !== false) {
                    $aSlideShow[$key]['TEXTE'] = 'DROITE';
                } elseif (strpos($result['PAGE_ZONE_MULTI_LABEL3'], 'GAUCHE') !== false) {
                    $aSlideShow[$key]['TEXTE'] = 'GAUCHE';
                }

                if (!empty($result['PAGE_ZONE_MULTI_ATTRIBUT2']) && intval($result['PAGE_ZONE_MULTI_ATTRIBUT2']) > 0) {
                    $aSlideShow[$key]['CTA_1'] = Pelican_Cache::fetch("Frontend/Cta", array($result['PAGE_ZONE_MULTI_ATTRIBUT2'], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']));
                }
                $aSlideShow[$key]['GTM_BUTTON2']['eventCategory'] = "Showroom::$vehicule_label::Strengths::".($key+1);
                $aSlideShow[$key]['GTM_BUTTON2']['eventAction'] = "Button 2::Click";
                $aSlideShow[$key]['GTM_BUTTON2']['eventLabel'] = $aSlideShow[$key]['CTA_1']['BARRE_OUTILS_URL_WEB']?$aSlideShow[$key]['CTA_1']['BARRE_OUTILS_URL_WEB']:$aSlideShow[$key]['PAGE_ZONE_MULTI_URL4'];

                if (!empty($result['PAGE_ZONE_MULTI_ATTRIBUT']) && intval($result['PAGE_ZONE_MULTI_ATTRIBUT']) > 0) {
                    $aSlideShow[$key]['CTA_2'] = Pelican_Cache::fetch("Frontend/Cta", array($result['PAGE_ZONE_MULTI_ATTRIBUT'], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']));
                }
                $aSlideShow[$key]['GTM_BUTTON1']['eventCategory'] = "Showroom::$vehicule_label::Strengths::".($key+1);
                $aSlideShow[$key]['GTM_BUTTON1']['eventAction'] = "Button 1::Click";
                $aSlideShow[$key]['GTM_BUTTON1']['eventLabel'] = $aSlideShow[$key]['CTA_2']['BARRE_OUTILS_URL_WEB']?$aSlideShow[$key]['CTA_2']['BARRE_OUTILS_URL_WEB']:$aSlideShow[$key]['PAGE_ZONE_MULTI_URL'];

                $aSlideShow[$key]['TARGET_CLIC_CTA1'] = ($result['PAGE_ZONE_MULTI_MODE4'] == 2) ? '_blank' : '_self';
                $aSlideShow[$key]['TARGET_CLIC_CTA2'] = ($result['PAGE_ZONE_MULTI_MODE2'] == 2) ? '_blank' : '_self';
            }
        }


        $iTiming = $aData['ZONE_CRITERIA_ID'] * 1000;

        $this->assign("aSlideShow", $aSlideShow);
        $this->assign("iTiming", $iTiming);
        $this->assign("aData", $aData);
        $this->assign("bTplHome", (in_array($aData['TEMPLATE_PAGE_ID'], array(Pelican::$config['TEMPLATE_PAGE']['HOME']))) ? 1 : 0);
        $this->fetch();
    }

}
