<?php
class Layout_Citroen_ContenusRecommandes_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $aParams = $this->getParams();
        $usingPersoData = Frontoffice_Zone_Helper::usingPersoData($aParams);
        
        if (
            ($this->isMobile() && isset($aParams['__perso_ZONE_MOBILE']) && $aParams['__perso_ZONE_MOBILE'] === false) ||
            (!$this->isMobile() && isset($aParams['__perso_ZONE_WEB']) && $aParams['__perso_ZONE_WEB'] === false)
        ){
            return;
        }
        
        $this->assign("aParams", $aParams);
        $this->assign("session", $_SESSION[APP]);
        
        /*
         * Contenus recommandés
         */
        $productMedia = Pelican_Cache::fetch("Frontend/Citroen/Perso/ProductMedia", array(
            $_SESSION[APP]['SITE_ID']
        ));
        if ($aParams['ZONE_LABEL2']) {
            $aTemp = explode('|', $aParams['ZONE_LABEL2']);
            $contenusRecommandes = Pelican_Cache::fetch("Frontend/Citroen/ContenusRecommandes", array(
                $_SESSION[APP]['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                $aTemp
            ));
        }
        if ($contenusRecommandes) {
            foreach($contenusRecommandes as $key => $value) {
                $mediaPath = ($value['MEDIA_ID_GENERIQUE'] && !empty($_SESSION[APP]['FLAGS_USER']['preferred_product']) && !empty($productMedia[$_SESSION[APP]['FLAGS_USER']['preferred_product']]['CONTENUS_RECOMMANDES'])) ? $productMedia[$_SESSION[APP]['FLAGS_USER']['preferred_product']]['CONTENUS_RECOMMANDES'] : $value['MEDIA_PATH'];
                $contenusRecommandes[$key]['MEDIA_PATH'] = Citroen_Media::getFileNameMediaFormat($mediaPath, Pelican::$config['MEDIA_FORMAT_ID']['WEB_CONTENU_RECOMMANDE']);
                
                // Injection paramètre origin si il s'agit d'un slide perso, sur les URL internes
                if ($usingPersoData
                 && !empty($contenusRecommandes[$key]['CONTENU_RECOMMANDE_URL'])
                 && preg_match('#^/#', $contenusRecommandes[$key]['CONTENU_RECOMMANDE_URL'])) {
                    $contenusRecommandes[$key]['CONTENU_RECOMMANDE_URL'] = Frontoffice_Zone_Helper::setUrlQueryString($contenusRecommandes[$key]['CONTENU_RECOMMANDE_URL'], array('origin' => 'ctaperso'));
                }
            }
        }
        $this->assign("contenusRecommandes", $contenusRecommandes);

        $pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
        ));

        /*
         * Navigation Push
         */
        if($aParams['ZONE_ATTRIBUT']){
            if($aParams['ZONE_TOOL']) 
                {
                    if(!is_array($aParams['ZONE_TOOL']))
                    {
                    $aTemp = explode('|', $aParams['ZONE_TOOL']);
                    }
                    else
                    {
                    $aTemp = $aParams['ZONE_TOOL'];
                    }

                }
           
            $generateurLeads = Pelican_Cache::fetch("Frontend/Citroen/BarreOutils", array(
                $_SESSION[APP]['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                $aTemp
            ));
        }else{
            $aParams2 = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
                $pageGlobal['PAGE_ID'],
                Pelican::$config['ZONE_TEMPLATE_ID']['GENERATEUR_LEADS'],
                $pageGlobal['PAGE_VERSION'],
                $_SESSION[APP]['LANGUE_ID']
            ));
            if ($aParams2['ZONE_PARAMETERS']) {
                $aTemp = explode('|', $aParams2['ZONE_PARAMETERS']);
                $generateurLeads = Pelican_Cache::fetch("Frontend/Citroen/BarreOutils", array(
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    $aTemp
                ));
            }
        }

        


        if (is_array($generateurLeads) && !empty($generateurLeads) && $aParams['ZONE_WEB'] == true) {
            foreach ($generateurLeads as $key=>$OneOutil) {

                $OneOutil['PERSO']= $usingPersoData;
                $aParams['CTA'] = $OneOutil;
				$aParams['CONTENUS'] = 'RECOMMANDES';
                 
                $generateurLeads[$key] = Pelican_Request::call('_/Layout_Citroen_CTA/',$aParams);

             
            }
        }
        $this->assign("generateurLeads", $generateurLeads);
        
            if($aDataParams["PAGE_ID"] == ""){
                $aDataParams["PAGE_ID"] = $aParams['PAGE_ID'];
            }
            if($aDataParams['LANGUE_ID'] == ""){
                $aDataParams['LANGUE_ID'] = $aParams['LANGUE_ID'];
            }

		$aDataParams = array_merge($aParams,$aDataParams);
        $this->assign('aDataColors', $aDataParams);
        
        $this->fetch();
    }

}