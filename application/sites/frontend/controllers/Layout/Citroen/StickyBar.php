<?php

require_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Showroom.php');

class Layout_Citroen_StickyBar_Controller extends Pelican_Controller_Front {

    public function indexAction() {
        $aParams = $this->getParams();
        $aParams['ID_PS'] = $aParams['pid'];
        $pidCourant = $aParams['pid'];
        $this->assign("pidCourant", $pidCourant);
        $pageTitle = $aParams['PAGE_TITLE_BO'];

        /**
         * Page globale
         */
        $pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    Pelican::getPreviewVersion(),
                    Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
        ));



        /**
         * Configuration
         */
        $aConfiguration = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
                    $pageGlobal['PAGE_ID'],
                    Pelican::$config['ZONE_TEMPLATE_ID']['CONFIGURATION'],
                    $pageGlobal['PAGE_VERSION'],
                    $_SESSION[APP]['LANGUE_ID']
        ));

        $this->assign('shortTitle', $aConfiguration['ZONE_TITRE17']);


        $aVehicule = Pelican_Cache::fetch("Frontend/Citroen/VehiculeById", array(
                    $aParams['PAGE_VEHICULE'],
                    $aParams['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID']
        ));
        if (!isset($aVehicule)) {
            $aName = explode("|", $aParams['PAGE_LIBPATH']);
            $aName2 = explode("#", $aName[4]);
            $aVehicule['VEHICULE_LABEL'] = $aName2[0];
        }
        $this->assign('aVehicule', $aVehicule);


        $bMobile = $this->isMobile();
        $stickyBar = Pelican_Cache::fetch("Frontend/Citroen/StickyBar", array(
                    $pidCourant,
                    $aParams['PAGE_PARENT_ID'],
                    $aParams['TEMPLATE_PAGE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    Pelican::getPreviewVersion()
        ));

        if (is_array($stickyBar)) {
            foreach ($stickyBar as $key => $aPage) {
                $return = Pelican_Cache::fetch("Frontend/Page/Zone", array(
                            $aPage['PAGE_ID'],
                            $_SESSION[APP]['LANGUE_ID'],
                            Pelican::getPreviewVersion(),
                            'desktop'
                ));
                if (is_array($return['zones'][Pelican::$config['AREA']['DYNAMIQUE']])) {
                    foreach ($return['zones'][Pelican::$config['AREA']['DYNAMIQUE']] as $tranche) {
                        if ($tranche[0]['ZONE_ID'] == Pelican::$config['ZONE']['FORMULAIRE']) {
                            // Pour la version WEB
                            if ($tranche[0]['ZONE_ATTRIBUT'] == '0' || $tranche[0]['ZONE_ATTRIBUT'] == '2' && !$this->isMobile()) {
                                unset($stickyBar[$key]);
                            }

                            // Pour la version Mobile
                            if ($tranche[0]['ZONE_ATTRIBUT'] == '0' || $tranche[0]['ZONE_ATTRIBUT'] == '1' && $this->isMobile()) {
                                unset($stickyBar[$key]);
                            }
                        }
                    }
                }
            }
        }
        //debug($return['zones'][150]);
        if ($stickyBar[0]['PAGE_ID'] != $pidCourant && $this->isMobile()) {
            $return = Pelican_Cache::fetch("Frontend/Page/Zone", array($pidCourant, $_SESSION[APP]['LANGUE_ID'], Pelican::getPreviewVersion(), 'desktop'));
            $aZones->tabAreas = $return["areas"];
            $aZones->tabZones = $return["zones"];
        }

        $this->assign("stickyBar", $stickyBar);

        if (isset($_GET['sticky'])) {
            $this->assign("bStickyBarInactiveSurMobile", 1);
        }
        $this->assign("pageTitle", ($stickyBar[0]['PAGE_ID'] != $pidCourant) ? $pageTitle : $stickyBar[0]['PAGE_TITLE_BO']);

        // Héritage des zones de la page parente.
        if ($stickyBar && (
                $stickyBar[0]['PAGE_ID'] != $pidCourant || ($bMobile && $aParams['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_INTERNE']))
        ) {
            unset($aZonesHerites);
            $_GET['pid'] = $aParams['PAGE_PARENT_ID'];
            $layoutParent = new Pelican_Layout();
            $layoutParent->initSite();
            $layoutParent->initData();
            $aZones = new Citroen_Layout_Desktop($layoutParent->aPage);
            $return = Pelican_Cache::fetch("Frontend/Page/Zone", array($aParams['PAGE_PARENT_ID'], $_SESSION[APP]['LANGUE_ID'], Pelican::getPreviewVersion(), 'desktop'));
            $returnCurrent = Pelican_Cache::fetch("Frontend/Page/Zone", array($aParams['pid'], $_SESSION[APP]['LANGUE_ID'], Pelican::getPreviewVersion(), 'desktop'));

            // var_dump($returnCurrent);


            $zone_grand_visuel = null;
            $aPathLib = explode('|', $returnCurrent['areas'][0]["PAGE_LIBPATH"]);

            if ($aPathLib[4]) {
                foreach ($returnCurrent['zones'][148] as $keycurrent => $valuecurrent) {
                    if($valuecurrent[0]["ZONE_ID"] == 636 && !$valuecurrent[0]['ZONE_TITRE11']){
                        $zone_grand_visuel = $valuecurrent[0];
                    }
                    //ignorer les zones suivantes dans la stickybar
                    if (
                            !in_array($aParams['TEMPLATE_PAGE_ID'], Pelican::$config['TEMPLATE_PAGE_EXCLUDE_FROM_STICKY']) && // N'est pas la liste des gabarits exclus
                            $valuecurrent[0]["ZONE_ID"] != 636 && //CONTENT_GRAND_VISUEL
                            $valuecurrent[0]["ZONE_ID"] != 639 && //CONTENUS_RECOMMANDES
                            $valuecurrent[0]["ZONE_ID"] != 653 && //STICKYBAR
                            $valuecurrent[0]["ZONE_ID"] != 667 && //SELECTEUR_DE_TEINTE
                            $valuecurrent[0]["ZONE_ID"] != 683 && //SELECTEUR_DE_TEINTE_AUTO
                            $valuecurrent[0]["ZONE_ID"] != 685  //POINTS_FORTS AUTO    
                    ) {
                        $valuecurrent[0]['isHerite'] = 1;
                        $aZonesHeritesCurrent[] = $aZones->getDirectZone($valuecurrent[0]);
                    }
                }
                $this->assign("aZonesHeritesCurrent", $aZonesHeritesCurrent);
            }
            //var_dump($aZonesHeritesCurrent['zones']);
            //die;
            $aZones->tabAreas = $return["areas"];
            $aZones->tabZones = $return["zones"];
            /* PERSO */
            $flagUser = $_SESSION[APP]['FLAGS_USER'];
            $profileUser = $_SESSION[APP]['PROFILES_USER'];
            $products = Pelican_Cache::fetch("Frontend/Citroen/Perso/Lcdv6ByProduct", array(
                        $_SESSION[APP]['SITE_ID']
            ));



            foreach ($aZones->tabAreas as $area) {
                if ($area['AREA_ID'] == $aParams['AREA_ID'] && !empty($aZones->tabZones[$area['AREA_ID']])) {
                    foreach ($aZones->tabZones[$area['AREA_ID']] as $listZone) {
                        /* PERSO */
                        $zone = array();
                        $zoneDataOrigin = $listZone[0];
                        if (is_array($profileUser) && count($profileUser) > 0) {
                            foreach ($listZone as $key => $oneData) {
                                $explodeKey = array();
                                $field = '';
                                if (strpos($key, '_') !== false) {
                                    $explodeKey = explode($key, '_');
                                    switch ($explodeKey[1]) {
                                        case 13 :
                                            $field = $flagUser['preferred_product'];
                                            break;
                                        case 7 :
                                            $field = $flagUser['product_owned'];
                                            break;
                                        case 11 :
                                            $field = $flagUser['current_product'];
                                            break;
                                        case 12 :
                                            $field = $flagUser['product_best_score'];
                                            break;
                                        case 14 :
                                            $field = $flagUser['recent_product'];
                                            break;
                                    }
                                }
                                if ((in_array($key, $profileUser) || (!empty($explodeKey) && in_array($explodeKey[0], $profileUser) && $products[$explodeKey[2]] == $field)) && !empty($oneData)) {
                                    $zone = array_merge($zoneDataOrigin, $oneData);
                                    break;
                                }
                            }
                        }
                        if (empty($zone)) {
                            $zone = $zoneDataOrigin;
                        }
                        if ($zone['ZONE_ID'] == Pelican::$config['ZONE']['STICKYBAR']) {
                            break;
                        } else {
                            if ($zone['ZONE_ID'] == Pelican::$config['ZONE']['CONTENT_GRAND_VISUEL'] && $zone_grand_visuel) {
                                $zone = $zone_grand_visuel;
                            }
                            $aZonesHerites[$zoneDataOrigin['ZONE_ID']] = $aZones->getDirectZone($zone);
                            $aReponse[] = Citroen_Layout_Desktop::getModeDeploye($zone);
                            $aParams['ID_PS'] = $aParams['PAGE_PARENT_ID'];
                            
                        }
                    }
                }
            }

            $Hx = array("h1", "h2", "h3", "h4", "h5", "h6");
            if (is_array($aZonesHerites) && sizeof($aZonesHerites) > 0) {
                foreach ($aZonesHerites as $iKey => $aValueZone) {
                    if ($iKey != Pelican::$config['ZONE']['SELECTEUR_DE_TEINTE']) {
                        $aZonesPageHerites[] = str_replace($Hx, 'div', $aValueZone);
                    } else {
                        $aZonesPageHerites[] = $aValueZone;
                    }
                }
            }

            //fin foreach
            $_GET['pid'] = $pidCourant;
            $aZonesPageHerites[2] .= $aReponse[2];
            $this->assign("aZonesHerites", $aZonesPageHerites);

            $this->assign("bStickyBarInactiveSurMobile", 1);
        }

        $this->assign("stickyBar", $stickyBar);
        $temp = Pelican_Cache::fetch("Frontend/Citroen/HeritageGrandVisuel", array(
                    $aParams['ID_PS'],
                    $_SESSION[APP]['LANGUE_ID'],
                    "CURRENT"
        ));
        //Sharer
        if ($temp['ZONE_LABEL2']) {
            $sSharer = Backoffice_Share_Helper::getSharer($temp['ZONE_LABEL2'], $aParams['SITE_ID'], $temp['LANGUE_ID'], Pelican::$config['MODE_SHARER'][0], array('getParams' => $aParams));
        }






        $this->assign("sSharer", $sSharer);

        $this->assign("aData", $aParams);
        $this->fetch();
    }

}
