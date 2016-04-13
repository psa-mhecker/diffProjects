<?php

use Citroen\GammeFinition\VehiculeGamme;
use Citroen\GTM;

class Layout_Citroen_Home_Promotion_Controller extends Pelican_Controller_Front {

    public function indexAction() {
        $aData = $this->getParams();
        if ($_GET["vid"]) {
            $vehiculeId = $_GET["vid"];
            $aIgnorePromotions = array();
        }

        $iVehiculeId = (int) $aData['PAGE_VEHICULE'];
        $a2Result = VehiculeGamme::getShowRoomVehicule(
            $_SESSION[APP]['SITE_ID'], $aData['LANGUE_ID'], $iVehiculeId, null, $aData['PAGE_ID']
        );

        // Marquage GTM
        GTM::$dataLayer['vehicleModelBodystyle']      = $a2Result[0]['VEHICULE']['LCDV6'];
        GTM::$dataLayer['vehicleModelBodystyleLabel'] = $a2Result[0]['VEHICULE']['VEHICULE_LABEL'];

        $pathVisual = str_replace("/Home/Promotion/index.tpl", "/PromotionList/visuel.tpl", $this->getTemplate());
        $pathMixte = str_replace("/Home/Promotion/index.tpl", "/PromotionList/mixte.tpl", $this->getTemplate());
        $pathVisualMobi = str_replace("/Home/Promotion/index.mobi", "/PromotionList/visuel.mobi", $this->getTemplate());
        $pathMixteMobi = str_replace("/Home/Promotion/index.mobi", "/PromotionList/mixte.mobi", $this->getTemplate());
        $aListePromotions = Pelican_Cache::fetch("Frontend/Citroen/Promotion", array(
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    $aData['PAGE_ID'],
                    getPreviewVersion(),
                    $vehiculeId
                        )
        );
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
                        if(!$this->isMobile()){
                            $aListePromotions[$key]['YOUTUBE_ID'] = Frontoffice_Video_Helper::getPlayer($Promo["YOUTUBE_ID"]);
                        }else{
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
					
					
					
					//CPW-3522
					$aPageContent = Pelican_Cache::fetch("Frontend/Page/ZoneMultiByPageId", array(
                                $Promo["PAGE_ZONE_MULTI_URL16"],
                                $aData['SITE_ID'],
                                $aData['LANGUE_ID']
                    ));
					
					
					$aListePromotions[$key]['ZONE_TEXTE4'] = $aPageContent['ZONE_TEXTE4'];
					$aListePromotions[$key]['PAGE_TITLE'] = $aPageContent['PAGE_TITLE'];
					//FIN CPW-3522
                }
                if ($_GET["vid"]) {
                    $aIgnorePromotions[] = implode('||', array($Promo['PAGE_ID'], $Promo['LANGUE_ID'], $Promo['ZONE_TEMPLATE_ID'], $Promo['PAGE_ZONE_MULTI_ID']));
                }
                
                $sSharer = Backoffice_Share_Helper::getSharer($Promo['PAGE_ZONE_MULTI_LABEL6'],$aData['SITE_ID'], $aData['LANGUE_ID'], Pelican::$config['MODE_SHARER'][0], array('getParams' => $aParams));
                
            }

            $actionContext='';
            if(Pelican::$config['TEMPLATE_PAGE_CODE'][$aData['TEMPLATE_PAGE_ID']] == 'LISTE_PROMOTION' || Pelican::$config['TEMPLATE_PAGE_CODE'][$aData['TEMPLATE_PAGE_ID']] == 'DETAIL_PROMOTION' ){
		$actionContext = 'Promotion';
	    }
	    if(Pelican::$config['TEMPLATE_PAGE_CODE'][$aData['TEMPLATE_PAGE_ID']] == 'SHOWROOM_INTERNE' ){
		$actionContext = 'Showroom';
	    }

            $_GET['aIgnorePromotions'] = $aIgnorePromotions;
	    $this->assign('actionContext', $actionContext);
            $this->assign("sSharer", $sSharer);
            $this->assign("aData", $aData);
            $this->assign("titre", $aData["ZONE_TITRE"]);
            $this->assign("vidUrl", $_GET["vid"]);
            $this->assign("currentUrl", $aData["PAGE_CLEAR_URL"]);
            $this->assign("ListePromotions", $aListePromotions);
            $this->assign("pathVisual", $pathVisual);
            $this->assign("pathMixte", $pathMixte);
            $this->assign("pathVisualMobi", $pathVisualMobi);
            $this->assign("pathMixteMobi", $pathMixteMobi);
            $this->assign("aSelectionVehicules", $aSelectVehicules);
            $this->fetch();
        }
    }

}
