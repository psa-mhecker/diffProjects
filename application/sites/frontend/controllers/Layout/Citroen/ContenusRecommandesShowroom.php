<?php
class Layout_Citroen_ContenusRecommandesShowroom_Controller extends Pelican_Controller_Front
{
    public function indexAction()
    {
        $aData = $this->getParams();
        $usingPersoData = Frontoffice_Zone_Helper::usingPersoData($aData);
        
        if (
            ($this->isMobile() && isset($aData['__perso_ZONE_MOBILE']) && $aData['__perso_ZONE_MOBILE'] === false) ||
            (!$this->isMobile() && isset($aData['__perso_ZONE_WEB']) && $aData['__perso_ZONE_WEB'] === false)
        ){
            return;
        }
        
        $this->assign('aData', $aData);
        $this->assign("session", $_SESSION[APP]);


        if ($aData['ZONE_LABEL2']) {
            $productMedia = Pelican_Cache::fetch("Frontend/Citroen/Perso/ProductMedia", array(
                $_SESSION[APP]['SITE_ID']
            ));
            $aTemp = explode('|', $aData['ZONE_LABEL2']);
            $aRecommandes = Pelican_Cache::fetch("Frontend/Citroen/ContenusRecommandes", array(
                $_SESSION[APP]['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                $aTemp
            ));

            if ($aRecommandes) {
                foreach($aRecommandes as $key => $value) {
                    $mediaPath = ($value['MEDIA_ID_GENERIQUE'] && !empty($_SESSION[APP]['FLAGS_USER']['preferred_product']) && !empty($productMedia[$_SESSION[APP]['FLAGS_USER']['preferred_product']]['CONTENUS_RECOMMANDES'])) ? $productMedia[$_SESSION[APP]['FLAGS_USER']['preferred_product']]['CONTENUS_RECOMMANDES'] : $value['MEDIA_PATH'];
                    $aRecommandes[$key]['MEDIA_PATH'] = Citroen_Media::getFileNameMediaFormat($mediaPath, Pelican::$config['MEDIA_FORMAT_ID']['WEB_CONTENU_RECO_X_4']);
                    
                    // Injection paramètre origin si il s'agit d'un slide perso, sur les URL internes
                    if ($usingPersoData
                        && !empty($aRecommandes[$key]['CONTENU_RECOMMANDE_URL'])
                        && preg_match('#^/#', $aRecommandes[$key]['CONTENU_RECOMMANDE_URL'])
                        ) {
                        $aRecommandes[$key]['CONTENU_RECOMMANDE_URL'] = Frontoffice_Zone_Helper::setUrlQueryString($aRecommandes[$key]['CONTENU_RECOMMANDE_URL'], array('origin' => 'ctaperso'));
                    }
                }
            }
            
                    if($aDataParams["PAGE_ID"] == ""){
                        $aDataParams["PAGE_ID"] = $aData['PAGE_ID'];
                    }
                    if($aDataParams['LANGUE_ID'] == ""){
                        $aDataParams['LANGUE_ID'] = $aData['LANGUE_ID'];
                    }


            $aDataParams = array_merge($aData,$aDataParams);
            $this->assign('aRecommandes', $aRecommandes);
            $this->assign('usingPersoData', $usingPersoData);
            $this->assign('aDataColors', $aDataParams);
        }

        $this->fetch();
    }
}
?>
