<?php

class Layout_Citroen_PromotionList_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {

        $aData = $this->getParams();

        $affichTranch = 1;
        $forcePublished = true;
        if ($aData['ZONE_TITRE2'] == 1) {
            $slideShow = Pelican_Cache::fetch("Frontend/Page/ZoneTemplateByPageId", array(
                $aData['PAGE_ID'],
                $aData['PAGE_VERSION'],
                Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_ACCUEIL_SLIDESHOW'],
                $_SESSION[APP]['LANGUE_ID']
            ));

            $usingPersoDataSlideshow = Frontoffice_Zone_Helper::usingPersoData($slideShow) ? true : false;

            if ($usingPersoDataSlideshow == true) {
                $affichTranch = 0;
            } else {
                $affichTranch = 1;
            }

        }
        $this->assign("affichTranch", $affichTranch);

        if (isset($aData['ZONE_TITRE']) && !empty($aData['ZONE_TITRE'])) {
            $vehiculeId = $aData['ZONE_TITRE'];
            $aListePromotions = Pelican_Cache::fetch("Frontend/Citroen/Promotion", array(
                $_SESSION[APP]['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                '', // Page id
                Pelican::getPreviewVersion(),
                $vehiculeId,
                $forcePublished
            ));

            $aZonesHeritesW = array();
            $aZonesHeritesM = array();

            $layoutParent = new Pelican_Layout();
            $layoutParent->initSite();
            $layoutParent->initData();
            $aZones = new Citroen_Layout_Desktop($layoutParent->aPage);

            $aIndexZone = array();

            if (count($aListePromotions) > 0) {
                $aCheck = array();
                $current = 0;
                foreach ($aListePromotions as $onePromotion) {

                    $aPageZone = Pelican_Cache::fetch('Frontend/Page/Zone', array(
                        $onePromotion["PAGE_ID"],
                        $_SESSION[APP]['LANGUE_ID'],
                        Pelican::getPreviewVersion()
                    ));
                    // filtrer Areas héritée
                    $areas = array();
                    $zones = array();
                    foreach ($aPageZone['areas'] as $key => $area) {
                        if (in_array($area['AREA_ID'], Pelican::$config["HERITABLE_AREA"])) {
                            $areas[] = $area;
                            $zones[$area['AREA_ID']] = $aPageZone['zones'][$area['AREA_ID']];
                        }
                    }
                    //$aZones->tabAreas = $aPageZone['areas'];
                    $aZones->aPage = $onePromotion;
                    $aZones->tabAreas = $areas;
                    $aZones->tabZones = $zones;

                    $aZones->getModuleResponse();
                }
            }

            $this->assign("response", $aZones->response);
        } else {
            if (isset($_GET["vid"]) && !empty($_GET["vid"])) {
                $vehiculeId = $_GET["vid"];
            }

            $mediaDetailPush6 = Pelican_Cache::fetch("Media/Detail", array(
                $aData["MEDIA_ID6"]
            ));

            $this->assign('MEDIA_PATH6', $mediaDetailPush6["MEDIA_PATH"]);
            $this->assign('MEDIA_TITLE6', $mediaDetailPush6["MEDIA_TITLE"]);

            $aListePromotions = Pelican_Cache::fetch("Frontend/Citroen/MultiPromotion", array(
                $aData['PAGE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                $aData["PAGE_VERSION"],
                Pelican::$config['ZONE_TEMPLATE_ID']['LISTE_PROMOTIONS'],
                "multiPromotion",
                $_SESSION[APP]['SITE_ID'],
                Pelican::getPreviewVersion(),
                Pelican::$config['TEMPLATE_PAGE']['DETAIL_PROMOTION'],
                "Promotion",
                $vehiculeId,
                $forcePublished
            ));
        }

        $pathVisual = str_replace("index.tpl", "visuel.tpl", $this->getTemplate());
        $pathMixte = str_replace("index.tpl", "mixte.tpl", $this->getTemplate());
        $pathVisualMobi = str_replace("index.mobi", "visuel.mobi", $this->getTemplate());
        $pathMixteMobi = str_replace("index.mobi", "mixte.mobi", $this->getTemplate());

        if (is_array($aListePromotions) && !empty($aListePromotions)) {
            foreach ($aListePromotions as $key => $Promo) {
                if (!empty($Promo['YOUTUBE_ID'])) {
                    $mediaDetail2 = Pelican_Cache::fetch("Media/Detail", array(
                        $Promo['YOUTUBE_ID']
                    ));

                    if ($mediaDetail2['MEDIA_TYPE_ID'] == 'video') {
                        $aListePromotions[$key]['MEDIA_TYPE_ID'] = 'video';
                        $aListePromotions[$key]['YOUTUBE_ID'] = Pelican::$config['MEDIA_HTTP'] . $mediaDetail2["MEDIA_PATH"];

                        if ($mediaDetail2['MEDIA_ID_REFERENT'] != "") {
                            $OMP_mediaDetail2 = Pelican_Cache::fetch("Media/Detail", array(
                                $mediaDetail2['MEDIA_ID_REFERENT']
                            ));

                            $aListePromotions[$key]['YOUTUBE_ID'] .= "|" . Pelican::$config['MEDIA_HTTP'] . $OMP_mediaDetail2["MEDIA_PATH"];
                        }
                    } elseif ($mediaDetail2['MEDIA_TYPE_ID'] == 'youtube') {
                        $aListePromotions[$key]['MEDIA_TYPE_ID'] = 'youtube';
                        if (!$this->isMobile()) {
                            $aListePromotions[$key]['YOUTUBE_ID'] = Frontoffice_Video_Helper::getPlayer($Promo["YOUTUBE_ID"]);
                        } else {
                            $aListePromotions[$key]['YOUTUBE_ID'] = Frontoffice_Video_Helper::setYoutube($Promo["YOUTUBE_ID"]);
                        }
                    }
                }
                if ($Promo['MEDIA_ID6']) {
                    $mediaDetailPush6 = Pelican_Cache::fetch("Media/Detail", array(
                        $Promo["MEDIA_ID6"]
                    ));
                    $aListePromotions[$key]['MEDIA_PATH6'] = $mediaDetailPush6["MEDIA_PATH"];
                    $aListePromotions[$key]['MEDIA_TITLE6'] = $mediaDetailPush6["MEDIA_TITLE"];
                }

                if (isset($Promo["PAGE_ZONE_MULTI_URL16"]) && $Promo["PAGE_ZONE_MULTI_URL16"] != "") {
                    $pagePopUp = Pelican_Cache::fetch("Frontend/Page", array(
                        $Promo["PAGE_ZONE_MULTI_URL16"],
                        $aData['SITE_ID'],
                        $aData['LANGUE_ID']
                    ));
                    $aListePromotions[$key]['PAGE_ZONE_MULTI_URL16'] = $pagePopUp['PAGE_CLEAR_URL'];
                }
            }
        }
        $aListePromotionsTemp = array();
        $aListePromotionsTemp2 = array();
        if (is_array($aListePromotions)) {
            foreach ($aListePromotions as $key => $value) {
                if (isset($value['PAGE_ZONE_MULTI_ORDER']) && !empty($value['PAGE_ZONE_MULTI_ORDER'])) {
                    $aListePromotionsTemp[$value['PAGE_ZONE_MULTI_ORDER']] = $value;
                } else { // Si pas d'ordre la liste de promotion se place à la fin
                    $aListePromotionsTemp2[] = $value;
                }
            }
            ksort($aListePromotionsTemp);
            $aListePromotions = array_merge($aListePromotionsTemp, $aListePromotionsTemp2);
        }


        $actionContext = '';
        if (Pelican::$config['TEMPLATE_PAGE_CODE'][$aData['TEMPLATE_PAGE_ID']] == 'LISTE_PROMOTION' || Pelican::$config['TEMPLATE_PAGE_CODE'][$aData['TEMPLATE_PAGE_ID']] == 'LISTE_DETAIL_PROMOTION') {
            $actionContext = 'Promotion';
        }
        if (Pelican::$config['TEMPLATE_PAGE_CODE'][$aData['TEMPLATE_PAGE_ID']] == 'SHOWROOM_INTERNE') {
            $actionContext = 'Showroom';
        }
        $_GET['aIgnorePromotions'] = $aIgnorePromotions;
        $this->assign('actionContext', $actionContext);
        $this->assign("aData", $aData);
        $this->assign("titre", $aData["ZONE_TITRE"]);
        $this->assign("vidUrl", $_GET["vid"]);
        $this->assign("currentUrl", $aData["PAGE_CLEAR_URL"]);
        $this->assign("ListePromotions", $aListePromotions);
        $this->assign("pathVisual", $pathVisual);
        $this->assign("pathMixte", $pathMixte);
        $this->assign("pathVisualMobi", $pathVisualMobi);
        $this->assign("pathMixteMobi", $pathMixteMobi);
        $this->fetch();
    }

}

?>
