<?php

/**
 * Gestion des pages
 *
 * @copyright Copyright (c) 2001-2013 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */

require_once('Pelican/Layout/Desktop.php');
class Citroen_Layout_Desktop extends Pelican_Layout_Desktop{

    /**
     * Compteurs de nombre de blocs mobile et web dans la page (à l'exception du header et du footer)
     */
    public $blocCount;
    public $blocCountWeb;
    public $blocCountMobile;
    public $zonesPeroActives = array();

    public function __construct($aPage) {
        $bMobile = Pelican_Controller::isMobile();
        if($bMobile){
            $this->_type = "mobile";
        }
        $this->zonesPeroActives = Pelican_Cache::fetch("Frontend/Citroen/Perso/Activation", $_SESSION[APP]['SITE_ID']);

        parent::__construct($aPage);
    }


    /**
    /**
     * DESC
     *
     * @access public
     * @param string $tpl (option) __DESC__
     * @return void
     */
    public function getModuleResponse($tpl = "") {
        /** cas d'un template imposé par un contenu ou par le paramètre tpl*/
        if ($tpl) {
            $tabAreas = $this->tabAreas;
            $tabZones = $this->tabZones;
            $template_contenu = Pelican_Cache::fetch("Template/Content", $_SESSION[APP]['SITE_ID']);
            $return = Pelican_Cache::fetch("Frontend/Template_Page", array($template_contenu["TEMPLATE_PAGE_ID"], $this->_type));
            $this->tabAreas = $return["areas"];
            $this->tabZones = $return["zones"];
            foreach($this->tabAreas as $area => $tmp) {
                if ($this->tabZones[$this->tabAreas[$area]["AREA_ID"]]) {
                    foreach($this->tabZones[$this->tabAreas[$area]["AREA_ID"]] as $data => $values) {
                        if ($values["ZONE_CONTENT"]) {
                            $this->tabZones[$this->tabAreas[$area]["AREA_ID"]][$data]["ZONE_FO_PATH"] = $tpl;
                        }
                    }
                }
            }
        }
        // Suppression de la balise <div role="main"> pour le gabarit CITROEN SOCIAL
        foreach($this->tabAreas as $key => $aZone){
            if( $aZone['AREA_ID'] == Pelican::$config['AREA']['MAIN'] && $aZone['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['CITROEN_SOCIAL'] ){
                $this->tabAreas[$key]['AREA_HEAD']	= '';
                $this->tabAreas[$key]['AREA_FOOT']	= '';
            }
        }
        /*
         * Gestion de la StickyBar
         */
        $bTraitementStickyBar = false;
        $data['STICKY_ZONE_TRANSVERSE'] = false;
        $aStickyBar = Pelican_Cache::fetch("Frontend/Citroen/StickyBar", array(
            $this->aPage['PAGE_ID'],
            $this->aPage['PAGE_PARENT_ID'],
            $this->aPage['TEMPLATE_PAGE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            Pelican::getPreviewVersion()
        ));
        $bMobile = Pelican_Controller::isMobile();
        // Le traitement de la StickyBar n'est effectué s'il y a plus de 2 éléments,
        if (sizeof($aStickyBar) >= 2) {
            // Et que sur les pages de niveau N+1 pour la version Web

            if ($aStickyBar[0]['PAGE_ID'] != $this->aPage['PAGE_ID']) {
                $bTraitementStickyBar = true;
                $bStickyBarZoneVisible = false;
            }
            // Sur mobile, le traitement est tjs actif
            elseif ($bMobile) {
                $bTraitementStickyBar = true;
                // Les zones précedant la sticky sont affichés sur la page N,
                if ($aStickyBar[0]['PAGE_ID'] == $this->aPage['PAGE_ID'] && !isset($_GET['sticky'])) {
                    /** JIRA CPW-3150
                     * Changé sur False à la place de True
                     * Les tranches en dessous de la sticky ne remontent pas
                     **/
                    if ($this->aPage['TEMPLATE_PAGE_ID'] != Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL'])
                    {
                        $bStickyBarZoneVisible = false;
                    }
                    else
                    {
                        $bStickyBarZoneVisible = true;
                    }
                }
                // Sur les page N+1, elles ne sont pas.
                else {
                    $bStickyBarZoneVisible = false;
                }
            }
            if ($bMobile &&
                $this->aPage['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_INTERNE']
                || $this->aPage['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['ACCUEIL_PROMOTION']) {
                $bTraitementStickyBar = false;
            }
        }

        $aOnglets = array();

        $this->blocCount = 0;
        $this->blocCountWeb = 0;
        $this->blocCountMobile = 0;

        if ($this->tabAreas && $this->tabZones) {
            $trancheEnfant	= 0;
            $trancheParent	= 0;
            // ** Variable pour Gestion Accordeon WEB MOBILE ** //
            $traitementItem     = false;
            $itemId             = 1;
            // ** Fin ** //
            /* PERSO */
            $flagUser = $_SESSION[APP]['FLAGS_USER'];
            $profileUser = $_SESSION[APP]['PROFILES_USER'];
            $products = Pelican_Cache::fetch("Frontend/Citroen/Perso/Lcdv6ByProduct", array(
                $_SESSION[APP]['SITE_ID']
            ));


            /**
             * Active WS for Current SITE
             */
            $aSiteWS = Pelican_Cache::fetch('Frontend/Citroen/SiteWs',array($_SESSION[APP]['SITE_ID']));

            foreach($this->tabAreas as $area) {


                if ($bMobile && $area['AREA_MOBILE'] == 1) {
                    $this->response[] = $area["AREA_HEAD_MOBILE"] . "\n";
                } else {
                    $this->response[] = $area["AREA_HEAD"] . "\n";
                }
                if ($this->tabZones[$area["AREA_ID"]]) {

                    $avoid = 0;
                    $aBack = array();
                    $isOnglet = false;
                    $nOnglet = 0;
                    $nOngletOriginal = 0;
                    foreach($this->tabZones[$area["AREA_ID"]] as $idx => $listData) {
                        if($listData[0]["ZONE_ID"] == 652)
                        {
                            $isOnglet = true;
                        }

                        if($avoid != 0 && $isOnglet && $nOnglet > $nOngletOriginal)
                        {
                            $nOnglet--;
                            $aBack = $listData[0];
                            $exTP = $trancheParent;
                            $exNB = $nbTranche;
                            $exTF =$trancheEnfant;

                            $nbTranche  =   1;
                            if(isset($data['ZONE_TITRE17']) && !empty($data['ZONE_TITRE17'])){
                                $nbTranche  =   ($data['ZONE_TITRE17']);
                            }



                            if(isset($nbTranche ) && $nbTranche  > 0){

                                $nbTranche--;

                                $trancheEnfant++;

                            }


                            $idTranche =   $trancheParent . '_' . $trancheEnfant;


                            $TempZone ="<div id='tranche_$idTranche' class='trancheEnfant$trancheParent' style='display:none'>";

                            // récupération de la tranche enfant.
                            $TempZone .= $this->getDirectZone($aBack, $cache);

                            // fermeture de la div de surcharge
                            $TempZone .= '</div>';

                            $this->response[] = $TempZone;

                            $avoid--;
                            //  $trancheParent = $exTP;
                            //  $trancheEnfant = $exTF;
                            //   $nbTranche = $exNB;
                            continue;
                        }

                        /*Check WS*/
                        $data = array();
                        $zoneDataOrigin = $listData[0];

                        if($zoneDataOrigin["ZONE_TITRE17"] > 0 && $zoneDataOrigin['ZONE_LANGUETTE'] == 1 && $isOnglet && $avoid == 0)
                        {
                            $avoid = $zoneDataOrigin["ZONE_TITRE17"];

                        }

                        if(is_array($aSiteWS) && !empty($aSiteWS)){
                            //si la tranche dépend d'un webservice
                            if(isset(Pelican::$config['ZONE_WS'][$zoneDataOrigin['ZONE_ID']])){
                                //fetch webservices dont la tranche dépends
                                $aActiveZoneWs = Pelican::$config['ZONE_WS'][$zoneDataOrigin['ZONE_ID']];
                                //si webservices renseignés
                                if(is_array($aActiveZoneWs)&& count($aActiveZoneWs)){
                                    //check si webservices actifs pour le site courant
                                    foreach($aActiveZoneWs as $oneWs){
                                        //webservice désactivé

                                        if(!array_key_exists($oneWs,$aSiteWS)){
                                            $bNoWs = true;
                                        }
                                    }

                                    //ignorer la tranche et passer à la suivante
                                    if($bNoWs){
                                        continue;
                                    }
                                }
                            }
                        }

                        //fix CPW-4606 ajout du test si la tranche est personnalisable
                        if(is_array($profileUser) && count($profileUser)>0 && in_array($zoneDataOrigin['ZONE_ID'], $this->zonesPeroActives)){
                            $listData    =   Citroen_Cache::setPrioriteIndicateursAndProfils($listData);                          
                            foreach($listData as $key=>$oneData){
                                $explodeKey = array();
                                $field = '';
                                if(strpos($key, '_') !== false){
                                    $explodeKey = explode('_', $key);
                                    switch($explodeKey[1]){
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
                                    $aProducts =  explode(":", $explodeKey[2]);
                                }

                                $aTrancheCaseAffWeb =  array(
                                    Pelican::$config['ZONE']['CONTENUS_RECOMMANDES'],
                                    Pelican::$config['ZONE']['MOSAIQUE'],
                                    Pelican::$config['ZONE']['CONTENUS_RECOMMANDES_SHOWROOM']
                                );
                                if(in_array($zoneDataOrigin['ZONE_ID'], $aTrancheCaseAffWeb)){
                                    if(!isset($oneData['ZONE_WEB'])){
                                        $oneData['ZONE_WEB']    =   '';
                                    }
                                }else{
                                    if(!isset($oneData['ZONE_WEB'])){
                                        $oneData['ZONE_WEB']    =   1;
                                    }
                                }

                                if((in_array($key,$profileUser, true) && Citroen_Cache::isPubliePerso($oneData) && $oneData['ZONE_WEB'] == 1) ||
                                    (
                                        !empty($explodeKey)
                                        && in_array($explodeKey[0],$profileUser)
                                        && in_array($field, $aProducts, true)
                                        && !empty($oneData)
                                        && Citroen_Cache::isPubliePerso($oneData)
                                        && $oneData['ZONE_WEB'] == 1
                                    )
                                )
                                {

                                    //Merge data perso dans zone
                                    $data = array_merge($zoneDataOrigin, $oneData);
                                    break;
                                }

                            }
                        }
                        if(empty($data)){
                            $data = $zoneDataOrigin;
                        }


                        // Mise à jour des compteurs de bloc (sauf pour les blocs qui appartiennent au header ou au footer, ainsi que certains blocs auto)
                        if (
                            !in_array($data['AREA_ID'], array(Pelican::$config['AREA']['HEADER'], Pelican::$config['AREA']['FOOTER'])) &&
                            !in_array($data['ZONE_ID'], array(Pelican::$config['ZONE']['STICKYBAR'], Pelican::$config['ZONE']['STICKYBAR_PROMO'], Pelican::$config['ZONE']['CONTENUS_RECOMMANDES']))
                        ){
                            $this->blocCount++;
                            if ($data['ZONE_WEB'] == 1 || !isset($data['ZONE_WEB'])) {
                                $this->blocCountWeb++;
                                $this->blocCountDebug['web'][] = $data['ZONE_ID'].' - '.$data['ZONE_FO_PATH'];
                            }
                            if (($data['ZONE_MOBILE'] == 1 || !isset($data['ZONE_MOBILE'])) &&  !in_array($data['ZONE_ID'], array(
                                    Pelican::$config['ZONE']['EDITO'],
                                    Pelican::$config['ZONE']['DRAG_DROP'],
                                    Pelican::$config['ZONE']['ACCESSOIRES'],
                                    Pelican::$config['ZONE']['VEHICULES_NEUF'],
                                    Pelican::$config['ZONE']['OUTILS_CHOIX'],
                                    Pelican::$config['ZONE']['REMONTE_RX'],
                                    Pelican::$config['ZONE']['REMONTE_RX_HOME'],
                                    Pelican::$config['ZONE']['SIMULATEUR_FINANCEMENT'],
                                    Pelican::$config['ZONE']['RECAPITULATIF_MODELE'],
                                    Pelican::$config['ZONE']['PAGER_SHOWROOM'],
                                    Pelican::$config['ZONE']['SLIDESHOW_AUTO'],
                                    Pelican::$config['ZONE']['OVERVIEW']))) {
                                $this->blocCountMobile++;
                                $this->blocCountDebug['mobile'][] = $data['ZONE_ID'].' - '.$data['ZONE_FO_PATH'];
                            }
                        }

                        if ($data['ZONE_ORDER']) {
                            $data['ORDER'] = 'd'.$data['ZONE_ORDER'];
                            $data['ID_HTML'] = Pelican_Text::cleanText($data['ZONE_TEMPLATE_LABEL'], '-' ,false, false) . '_' . $data['AREA_ID'] . '_' . $data['ZONE_ORDER'];
                        } else {
                            $data['ORDER'] = 'f'.$data['ZONE_TEMPLATE_ORDER'];
                            $data['ID_HTML'] = Pelican_Text::cleanText($data['ZONE_TEMPLATE_LABEL'], '-' ,false, false) . '_' . $data['ZONE_TEMPLATE_ID'];
                        }
                        if(false === self::isWebService( $data  )){
                            continue;
                        }

                        /**
                         * Gestion de l'affichage des zones avant la StickyBar, si le traitement est actif
                         *
                         * Le traitement pour le mobile est après l'affichage de la zone
                         */
                        if ($bTraitementStickyBar) {
                            if(!$bMobile) {
                                if ($data['ZONE_ID'] == Pelican::$config['ZONE']['STICKYBAR'] || $data["ZONE_ID"] == Pelican::$config['ZONE']['STICKYBAR_PROMO']) {
                                    $bStickyBarZoneVisible = true;
                                }
                            }
                        }
                        // Zone Onglet
                        if ($data['ZONE_ID'] == Pelican::$config['ZONE']['ONGLET']
                            && (($bMobile && $data['ZONE_MOBILE']) || (!$bMobile && $data['ZONE_WEB']))
                        ) {
                            $idOngletTab = 0; // variable pour controle sur l'affichage web/mobile
                            $aZoneOnglet = Pelican_Cache::fetch("Frontend/Citroen/ZoneMulti", array(
                                $data['PAGE_ID'],
                                $_SESSION[APP]['LANGUE_ID'],
                                Pelican::getPreviewVersion(),
                                $data['ZONE_TEMPLATE_ID'],
                                'ONGLET',
                                $data['AREA_ID'],
                                $data['ZONE_ORDER']
                            ));
                            $nOnglet = count($aZoneOnglet);
                            $nOngletOriginal = $nOnglet - 1;
                            if ($aZoneOnglet) {
                                $aOngletId = (($data['ZONE_TEMPLATE_ID']!='')?$data['ZONE_TEMPLATE_ID']:$data['AREA_ID'] . '-' . $data['ZONE_ORDER']);
                                foreach($aZoneOnglet as $i => $onglet) {
                                    for ($j = 0; $j < (int)$onglet['PAGE_ZONE_MULTI_OPTION']; $j++) {
                                        $aOnglets[] = $i+1;
                                    }
                                }
                            }
                        }

                        // non affichage des onglet si il n'y a que des tranche active web et non pour le mobile
                        if($bMobile && sizeof($aOnglets) > 0 && $data['ZONE_ID'] != Pelican::$config['ZONE']['ONGLET']){
                            if($data['ZONE_WEB'] == 1 && $data['ZONE_MOBILE'] == 0){
                                unset($aOnglets[$idOngletTab]);
                                ksort($aOnglets);
                                $idOngletTab++;
                            }else{
                                $idOngletTab++;
                            }
                        }

                        // RAZ Onglet avant la zone Contenus Recommandés
                        if (sizeof($aOnglets) > 0 && $data['ZONE_ID'] == Pelican::$config['ZONE']['CONTENUS_RECOMMANDES']) {
                            unset($aOnglets);
                        }elseif ($bMobile && sizeof($aOnglets) > 0 && $data['ZONE_ID'] == Pelican::$config['ZONE']['FOOTER']){
                            unset($aOnglets);
                        }

                        // Affichage des zones dans le cas normal
                        if (!$bTraitementStickyBar
                            // Affichage spéficique à la StickyBar
                            || ($bTraitementStickyBar
                                && ($bStickyBarZoneVisible
                                    // Les zones Header et Footer sont toujours affichées
                                    || in_array($data['ZONE_ID'], array(Pelican::$config['ZONE']['HEADER'], Pelican::$config['ZONE']['FOOTER']))
                                    // La zone Grand visuel est toujours affichée dans le cas du mobile
                                    || ($bMobile &&
                                        in_array(
                                            $data['ZONE_ID'],
                                            array(
                                                Pelican::$config['ZONE']['CONTENT_GRAND_VISUEL'],
                                                Pelican::$config['ZONE']['SELECTEUR_DE_TEINTE'],
                                                Pelican::$config['ZONE']['SELECTEUR_DE_TEINTE_AUTO'],
                                                Pelican::$config['ZONE']['STICKYBAR'],
                                                Pelican::$config['ZONE']['STICKYBAR_PROMO'],
                                                Pelican::$config['ZONE']['OUTILS']
                                            )
                                        )
                                    )
                                )
                            )
                        ) {
                            /**
                             * on informe qu'il y a une zone transverse à la sticky
                             */

                            if (!in_array(
                                $data['ZONE_ID'],
                                array(
                                    Pelican::$config['ZONE']['HEADER'],
                                    Pelican::$config['ZONE']['CONTENT_GRAND_VISUEL'],
                                    Pelican::$config['ZONE']['SLIDESHOW_AUTO'],
                                    Pelican::$config['ZONE']['STICKYBAR'],
                                    Pelican::$config['ZONE']['STICKYBAR_PROMO']
                                )
                            )) {
                                $data['STICKY_ZONE_TRANSVERSE'] = true;
                                Pelican::$config['STICKY_ZONE_TRANSVERSE'] = true;
                            }

                            // temporaire
                            $data["ZONE_FO_PATH"] = str_replace('pageLayout', 'Layout', $data["ZONE_FO_PATH"]);


                            //recuperation des couleurs primaires et secondaires
							
                            if(!isset($data['PAGE_PRIMARY_COLOR']) && !isset($data['PAGE_SECOND_COLOR']) && $data['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_INTERNE']){
							$aPage = Frontoffice_Showroom_Helper::getShowroomColor($this->_pid,$_SESSION[APP]['LANGUE_ID'],Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']);
							}elseif(intval($data['PAGE_PARENT_ID'])>0 && empty($data['PAGE_PRIMARY_COLOR']) && empty($data['PAGE_SECOND_COLOR'])){
									$aPage = Frontoffice_Showroom_Helper::getShowroomColor($this->_pid,$_SESSION[APP]['LANGUE_ID'],true);
							}
							
							if(!empty($aPage['PAGE_PRIMARY_COLOR']) &&  !empty($aPage['PAGE_SECOND_COLOR']) && is_array($aPage)){ // recuperer les couleurs de la page du niveau -1
								
								$data['PRIMARY_COLOR'] = $aPage['PAGE_PRIMARY_COLOR'];
								$data['SECOND_COLOR']  = $aPage['PAGE_SECOND_COLOR'];
								
							}elseif(intval($this->_pid)>0){ // en cas de redirection 
							
								$aPage = Pelican_Cache::fetch("Frontend/Page", array(
										$this->_pid,
										$_SESSION[APP]['SITE_ID'],
										$_SESSION[APP]['LANGUE_ID'],
										Pelican::getPreviewVersion()
									));	
								if(!empty($aPage['PAGE_PRIMARY_COLOR']) &&  !empty($aPage['PAGE_SECOND_COLOR'])){
									$data['PRIMARY_COLOR'] = $aPage['PAGE_PRIMARY_COLOR'];
									$data['SECOND_COLOR']  = $aPage['PAGE_SECOND_COLOR'];
								}
							}
                            /**fin recuperation des couleurs**/


                            /** zones héritables */
                            if ($data["ZONE_TYPE_ID"] == 3) {
                                $savePath = $data["ZONE_FO_PATH"];
                                if (!$data["PAGE_ID"]) {
                                    $data["PAGE_ID"] = $this->_pid;
                                    $data['LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
                                }
                                $data = Pelican_Cache::fetch("Frontend/Page/Heritable", array("heritable", $data["PAGE_ID"], $data["ZONE_TEMPLATE_ID"], Pelican::getPreviewVersion(), $_SESSION[APP]['LANGUE_ID']));
                                $data["ZONE_FO_PATH"] = $savePath;
                                $savePath = "";
                            }
                            if (Pelican::$config["SHOW_DEBUG"]) {
                                $this->response[] = Pelican_Html::comment("Bloc " . $data["ZONE_TEMPLATE_ID"] . " : " . $data["ZONE_FO_PATH"]) . "\n";
                            } else {
                                $this->response[] = Pelican_Html::comment("Bloc " . $data["ZONE_TEMPLATE_ID"]) . "\n";
                            }
                            if (!empty($data["ZONE_FO_PATH"])) {
                                // plugin
                                $data = Pelican_Layout::identifyPlugins($data);
                                if (empty($data["ZONE_CACHE_TIME"]) || $data["ZONE_CACHE_TIME"] == 0 || !empty($this->preview)) {
                                    $cache = false;
                                } else {
                                    $cache = Pelican::$config["ENABLE_CACHE_SMARTY"];
                                }

                                if (valueExists($this->aPage, "PAGE_TITLE")) {
                                    $data["PAGE_TITLE"] = $this->aPage["PAGE_TITLE"];
                                }



                                if (valueExists($this->aPage, "PAGE_SUBTITLE")) {
                                    $data["PAGE_SUBTITLE"] = $this->aPage["PAGE_SUBTITLE"];
                                }

                                /** temporaire */
                                if ($this->_ajax) {
                                    $data["ZONE_AJAX"] = true;
                                }
                                if ($this->_iframe) {
                                    $data["ZONE_IFRAME"] = true;
                                }

                                /** output */
                                $this->recapZone[] = $data["ZONE_FO_PATH"];
                                $time0 = microtime(TRUE);

                                //Décompte des tranches enfant à afficher
                                if(isset($nbTranche ) && $nbTranche  > 0){

                                    $nbTranche--;

                                    $trancheEnfant++;

                                }else{
                                    // Une fois les tranches enfants récupérés on vide la variable $trancheEnfant
                                    unset($trancheEnfant);
                                }

                                $isTrancheParent	=	false;
                                if( isset($data['ZONE_LANGUETTE']) && $data['ZONE_LANGUETTE'] == 1){

                                    //On récupère le nombre de tranche enfant à afficher
                                    //Par défaut c'est 1 tranche.
                                    $nbTranche 	=	1;
                                    if(isset($data['ZONE_TITRE17']) && !empty($data['ZONE_TITRE17'])){
                                        $nbTranche 	=	($data['ZONE_TITRE17']);
                                    }

                                    // On active le faite qu'on soit sur une tranche parent
                                    $isTrancheParent	=	true;

                                    // On incrémente le compteur des tranches parentes
                                    $trancheParent++;

                                }
                                // Traitement de l'accordeon Item Web et Mobile
                                elseif(isset($data['ZONE_LANGUETTE']) && $data['ZONE_LANGUETTE'] == 2){

                                    /* Récupere le zone template ID */
                                    $aTemplate =  Pelican_Cache::fetch("ZoneTemplate", array(
                                        $data['PAGE_ID'],
                                        $data['ZONE_ID'],
                                        Pelican::getPreviewVersion()
                                    ));

                                    if ($data['ZONE_WEB'] == 1 || $data['ZONE_MOBILE'] == 1) {
                                        /*
                                         * Gestion des Items Accordeons Web et Mobile
                                         */
                                        $aToggle =  Pelican_Cache::fetch("Frontend/Citroen/ZoneMulti", array(
                                            $data['PAGE_ID'],
                                            $_SESSION[APP]['LANGUE_ID'],
                                            Pelican::getPreviewVersion(),
                                            $aTemplate['ZONE_TEMPLATE_ID'],
                                            'TOGGLE',
                                            $data['AREA_ID'],
                                            $data['ZONE_ORDER']
                                        ));

                                        if($aToggle){
                                            $maxToggle = sizeof($aToggle);
                                            // traitement effectué que si il + ou = a 2 elements
                                            if($maxToggle >= 2){
                                                $bTraitementItemAccordeon = true;
                                                //$traitementItem = true;
                                                $bOrderAccordeon = $aToggle[0]['ZONE_ORDER'];
                                                $Item = 0;
                                                $itemId = 1;
                                            }
                                            else{
                                                $bTraitementItemAccordeon = false;
                                                $bOrderAccordeon = '';
                                            }
                                        }
                                    }
                                }

                                $iTrancheDansAccordeon = false;

                                if (!$trancheEnfant && $bTraitementItemAccordeon && $data['ZONE_BO_PATH'] != 'Cms_Page_Citroen_AccordeonWebMobile' && $data['ZONE_FO_PATH'] != 'Layout_Citroen_Global_Footer') {
                                    $iTrancheDansAccordeon = true;

                                    if ($NbItem <= 0 && $Item < $maxToggle) {
                                        $NbItem = $aToggle[$Item]['PAGE_ZONE_MULTI_MODE'];
                                        $Item++;
                                    } elseif ($NbItem <= 0 && $Item >= $maxToggle) {
                                        $bTraitementItemAccordeon = false;
                                        $bOrderAccordeon = '';
                                        $iTrancheDansAccordeon = false;
                                    }

                                    $NbItem--;
                                }

                                // Fin Traitement Web Mobile
                                if ($data["ZONE_IFRAME"]) {
                                    $this->getOutputZone($data, $cache, 'iframe');
                                } elseif ($data["ZONE_AJAX"]) {
                                    $this->getOutputZone($data, $cache, 'ajax');
                                } elseif (sizeof($aOnglets) > 0 && ($data['AREA_ID'] . '-' . $data['ZONE_ORDER']) != $aOngletId) {
                                    $ongletId = $aOngletId.'-'.array_shift($aOnglets);
                                    $this->getOutputZone($data, $cache, '', $trancheParent, '', false, $ongletId, '', $nbTranche);
                                } elseif($iTrancheDansAccordeon){ // cas pour l'accordeon web on affiche pas le data courant
                                    if (!$bMobile) {
                                        $this->getOutputZone($data, $cache, '', $trancheParent, $trancheEnfant, $isTrancheParent, '', $bOrderAccordeon.'_'.$Item);
                                    } elseif ($bMobile && $_GET['accordeon'] == $bOrderAccordeon.'_'.$Item) {
                                        $this->getOutputZone($data, $cache, '', '', '', false);
                                    }
                                } else{
                                    // Fonctionnement des tranches parent/enfant uniquement pour le mode normal pour l'instant
                                    if (!($_GET['accordeon'] && $bMobile) || in_array($data['ZONE_ID'], array(Pelican::$config['ZONE']['HEADER'], Pelican::$config['ZONE']['FOOTER']))) {
                                        $this->getOutputZone($data, $cache, '', $trancheParent, $trancheEnfant, $isTrancheParent,'','','',$bTraitementStickyBar);
                                    }
                                }
                                unset($zoneId );
                                $time = microtime(TRUE);
                                Pelican_Log::control(sprintf(PROFILE_FORMAT_TIME, ($time - $time0)) . ' : ' . $data["ZONE_FO_PATH"], 'generation');
                            }
                        }

                        /**
                         * Gestion de l'affichage des zones avant la StickyBar, si le traitement est actif
                         * Le traitement pour le web est avant l'affichage de la zone
                         */
                        if ($bTraitementStickyBar) {
                            if($bMobile) {
                                if ($data['ZONE_ID'] == Pelican::$config['ZONE']['STICKYBAR'] || $data['ZONE_ID'] == Pelican::$config['ZONE']['STICKYBAR_PROMO'] ) {
                                    if ($bStickyBarZoneVisible) {
                                        $bStickyBarZoneVisible = false;
                                    } else {
                                        $bStickyBarZoneVisible = true;
                                    }
                                }
                            }
                        }

                        if (!empty(Pelican::$config['DEPLOYABLE_BLOC']) && !$bMobile) {

                            if (in_array(
                                $data['ZONE_ID'],
                                array(
                                    Pelican::$config['ZONE']['CONTENUS_RECOMMANDES'],
                                    Pelican::$config['ZONE']['RECAPITULATIF_MODELE']
                                )
                            )) {
                                $this->response[] = '<div class="body">';
                            }

                            /*****/

                            $sShowroom = '';
                            $borders = '';
                            $sElCloser='';
                            $sPopClose='';

                            if(!empty($data['PAGE_PRIMARY_COLOR'])){
                                $sShowroom = 'showroom';
                                $borders = 'style="border-top:4px solid '.$data["PAGE_PRIMARY_COLOR"].'!important; border-bottom:4px solid '.$data["PAGE_PRIMARY_COLOR"].'!important;"';
                                $sElCloser='style="background:'.$data["PAGE_PRIMARY_COLOR"].';"';
                                $sPopClose='style="border:4px solid '.$data["PAGE_PRIMARY_COLOR"].';"';
                            }

                            /*****/
                            foreach(Pelican::$config['DEPLOYABLE_BLOC'] as $kDeployable => $deployable) {
                                $tool_id =Pelican::$config['DEPLOYABLE_BLOC_TOOL_ID'][$kDeployable];
                                $this->response[] = '

										<div class="'.$sShowroom.' secret deployable_'.$tool_id.'" id="deployable_'.$kDeployable.'" name="deployable_'.$kDeployable.'" '.$borders.'>
										<div class="closer" '.$sElCloser.'></div>';
                                if ($deployable == 'PDV') {
                                    $aPDV = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
                                        $_SESSION[APP]['GLOBAL_PAGE_ID'],
                                        Pelican::$config['ZONE_TEMPLATE_ID']['RECHERCHE_PDV'],
                                        $_SESSION[APP]['GLOBAL_PAGE_VERSION'],
                                        $_SESSION[APP]['LANGUE_ID']
                                    ));
                                    $aZoneDeploy = Pelican_Cache::fetch("Zone", array(Pelican::$config['ZONE']['POINT_DE_VENTE']));
                                    if ($aPDV && $aZoneDeploy[0]) {
                                        $dataDeploy = array_merge($aPDV, $aZoneDeploy[0]);
                                    }
                                    $dataDeploy['REFERER']	=	'TRANCHE_OUTILS';
                                    $this->getOutputZone($dataDeploy, $cache);
                                }else{
                                    $aZoneDeploy = Pelican_Cache::fetch("Zone", array(Pelican::$config['ZONE']['FORMULAIRE']));
                                    $aTab = explode('_', $deployable);
                                    $aFormulaire = Pelican_Cache::fetch("Frontend/Citroen/ZoneTemplate", array(
                                        $aTab[0],
                                        $aTab[1],
                                        Pelican::getPreviewVersion(),
                                        false,
                                        $aTab[2],
                                        $aTab[3],
                                        Pelican::$config['ZONE']['FORMULAIRE']
                                    ));

                                    $formulaire = array();
                                    if (!empty($aFormulaire)) {
                                        $formulaire = $aFormulaire;
                                        $formulaire['FORM_MODE_AFF'] = $aFormulaire['ZONE_TITRE19'];
                                        $formulaire['FORM_TITRE'] = $aFormulaire['ZONE_TITRE'];
                                        $formulaire['FORM_CHAPO'] = $aFormulaire['ZONE_TEXTE'];
                                        $formulaire['FORM_TITRE_THANKS'] = $aFormulaire['ZONE_TITRE8'];
                                        $formulaire['FORM_TEXTE_THANKS'] = $aFormulaire['ZONE_TEXTE2'];
                                        $formulaire['FORM_TITRE_SHARE'] = $aFormulaire['ZONE_TITRE9'];
                                        $formulaire['FORM_SHARE'] = $aFormulaire['ZONE_LABEL2'];
                                        $formulaire['FORM_ML_TYPE'] = $aFormulaire['ZONE_TITRE5'];
                                        $formulaire['FORM_ML_TITRE'] = $aFormulaire['ZONE_TITRE6'];
                                        $formulaire['FORM_ML_TEXTE'] = $aFormulaire['ZONE_TEXTE4'];
                                        $formulaire['FORM_ML_MEDIA'] = $aFormulaire['MEDIA_ID4'];
                                        $formulaire['FORM_ML_LIEN_PAGE'] = $aFormulaire['ZONE_TITRE7'];
                                        $vehicule = $data['ZONE_TITRE2'] != '' ? $data['ZONE_TITRE2'] : $data['PAGE_VEHICULE'];
                                        if($aFormulaire['ZONE_TITRE4'] != 'CHOIX'){
                                            $formulaire2 = Pelican_Cache::fetch("Frontend/Citroen/Formulaire", array(
                                                $aFormulaire["ZONE_TITRE3"],
                                                $aFormulaire['ZONE_TITRE4'],
                                                (($bMobile == true) ? 'MOB' : 'WEB'),
                                                $_SESSION[APP]['SITE_ID'],
                                                $_SESSION[APP]['LANGUE_ID'],
                                                '',
                                                '',
                                                $vehicule
                                            ));
                                            if(!empty($formulaire2)){
                                                $formulaire = array_merge($formulaire, $formulaire2);
                                            }
                                        }
                                    }

                                    if(!empty($formulaire)){
                                        $formulaire['PAGE_VEHICULE'] = $data['PAGE_VEHICULE'];
                                        $formulaire['TRANCHE_VEHICULE'] = $data['ZONE_TITRE2'];
                                    }
                                    $form['FORM_DEPLOYE'] = $formulaire;
                                    $aZoneDeploy[0]['ZONE_ORDER'] = $kDeployable;
                                    if ($form && $aZoneDeploy[0]) {
                                        $dataDeploy = array_merge($form, $aZoneDeploy[0]);
                                    }
                                    $forms	=	true;
                                    $this->getOutputZone($dataDeploy, $cache, '', '', '', '', '', '', '', $bTraitementStickyBar, $forms);
                                }

                                $this->response[] = '
											<span class="popClose"><span '.$sPopClose.'>'.t('FERMER').'</span></span>
											</div>';
                                $this->response[] = Pelican_Html::comment("/#deployable_ " . $kDeployable) . "\n";
                            }
                            if (in_array( $data['ZONE_ID'], array( Pelican::$config['ZONE']['CONTENUS_RECOMMANDES'],Pelican::$config['ZONE']['RECAPITULATIF_MODELE']))) {
                                $this->response[] = '</div>';
                            }
                            unset(Pelican::$config['DEPLOYABLE_BLOC']);
                        }
                    }
                }

                if ($bMobile && $area['AREA_MOBILE'] == 1) {
                    $this->response[] = $area["AREA_FOOT_MOBILE"] . "\n";
                } else {

                    $this->response[] = $area["AREA_FOOT"] . "\n";
                }
            }
            if (!$data["PAGE_ID"]) {
                $data["PAGE_ID"] = $this->_pid;
                $data['AMINE']='AMINE';
            }



        }
    }

    public function getOutputZone($data, $cache = false, $type = '', $trancheParent = '', $trancheEnfant='', $isTrancheParent = false, $ongletId = '', $itemId = '', $nTranches='', $bTraitementStickyBar = false, $forms = false) {

        $bZoneVisible = true;
        // Cas particulier de la zone dynamique du template de page 'Mon projet'
        // La tranche n'est affiché que si l'onglet 'Profiter' est sélectionné
        if ($data['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION']
            && $data['AREA_ID'] == Pelican::$config['AREA']['DYNAMIQUE']
            && !isset($_GET['PROFITER'])) {
            $bZoneVisible = false;
        }

        if ($bZoneVisible) {
            switch ($type) {
                case "ajax": {
                    $this->response[] = $this->getAjaxZone($data, $cache);
                    break;
                }
                case "iframe": {
                    $this->response[] = $this->getIframeZone($data, $cache);
                    break;
                }
                default: {
                    // Cas d'une zone associée à un onglet
                    if ($ongletId ) {

                        $reponseTemp = '<div class="data-onglet" data-onglet="'.$ongletId.'">'.$this->getDirectZone($data, $cache).'</div>';


                        //On modifie l'id par défaut de la tranche parent "trancheParent"  par le mot "tranche_" + l'id de la tranche parent
                        $reponseTemp  = str_replace ( 'trancheParent', "tranche_$trancheParent" , $reponseTemp);

                        //On active la tranche en mode trancheParent en ajoutant dans la div une classe parentActif
                        $reponseTemp  = str_replace ( 'class="parent"', 'class="parentActif"' , $reponseTemp);


                        $this->response[] = $reponseTemp;

                    }
                    // Si on est dans une tranche enfant
                    elseif(!empty($trancheEnfant)){
                        // création de l'id enfant (association id parent/ id enfant) pour avoir un id enfant unique
                        $idTranche	=	$trancheParent . '_' . $trancheEnfant;

                        //Surcharge de la tranche enfant avec une div
                        //La div a un id (asociation de du mot "tranche" + l'id de l'enfant)
                        //La div a une classe (asociation de du mot "trancheEnfant" + l'id du parent)
                        //Par défaut la tranche enfant est caché avec le style='display:none'
                        $this->response[] ="<div id='tranche_$idTranche' class='trancheEnfant$trancheParent' style='display:none'>";

                        // récupération de la tranche enfant.
                        $this->response[] = $this->getDirectZone($data, $cache);

                        // fermeture de la div de surcharge
                        $this->response[] = '</div>';
                    }
                    // Cas pour les accordeon web et mobile
                    /*elseif($itemId){

                        $this->response[] = '<div class="tog" data-group="'.$itemId.'">'.$this->getDirectZone($data, $cache).'</div>';
                    }*/
                    else{
                        $reponseTemp	  =	$this->getDirectZone($data, $cache);
                        if(($bTraitementStickyBar && $data['AREA_ID'] == Pelican::$config['AREA']['HEADER']) || ($bTraitementStickyBar && $forms)){
                            // $Hx = array("h1", "h2", "h3", "h4", "h5", "h6");
                            // $reponseTemp = str_replace($Hx, 'div', $reponseTemp);
                        }
                        // Si on est dans une tranche parent
                        if(false !== strpos($reponseTemp, 'trancheParent') && true === $isTrancheParent){
                            //On modifie l'id par défaut de la tranche parent "trancheParent"  par le mot "tranche_" + l'id de la tranche parent
                            $reponseTemp  =	str_replace ( 'trancheParent', "tranche_$trancheParent" , $reponseTemp);
                        }
                        //On active la tranche en mode trancheParent en ajoutant dans la div une classe parentActif
                        if(false !== strpos($reponseTemp, 'class="parent"') && true === $isTrancheParent){
                            $reponseTemp  =	str_replace ( 'class="parent"', 'class="parentActif"' , $reponseTemp);
                        }
                        if ($itemId) {
                            $this->response[] = '<div class="tog" data-group="'.$itemId.'">'.$reponseTemp.'</div>';
                        } else {
                            $this->response[] = $reponseTemp;

                        }
                        //$this->response[] = $reponseTemp;
                    }
                    break;
                }
            }
        }
    }

    public static function isWebService( $tranche = ''){
        /**
        La vérification va se faire dans un fichier de config ou dans le BO
        On vérifie si la tranche utilise un WS
        Si oui on vérifie que le WS est bien activé pour ce Pays
        Si oui on renvoi true
        Si non on renvoi false
        Si non on renvoi false
         */
        return true;
    }

    public static function getModeDeploye($data){
        $bMobile = Pelican_Controller::isMobile();

        if (!empty(Pelican::$config['DEPLOYABLE_BLOC']) && !$bMobile) {
            $aReponse = "";
            if (in_array(
                $data['ZONE_ID'],
                array(
                    Pelican::$config['ZONE']['CONTENUS_RECOMMANDES'],
                    Pelican::$config['ZONE']['RECAPITULATIF_MODELE']
                )
            )) {
                $aReponse .= '<div class="body">';
            }

            $sShowroom = '';
            $borders = '';
            $sElCloser='';
            $sPopClose='';
            if(!empty($data['PAGE_PRIMARY_COLOR'])){
                $sShowroom = 'showroom';
                $borders = 'style="border-top:4px solid '.$data["PAGE_PRIMARY_COLOR"].'!important; border-bottom:4px solid '.$data["PAGE_PRIMARY_COLOR"].'!important;"';
                $sElCloser='style="background:'.$data["PAGE_PRIMARY_COLOR"].';"';
                $sPopClose='style="border:4px solid '.$data["PAGE_PRIMARY_COLOR"].';"';
            }


            foreach(Pelican::$config['DEPLOYABLE_BLOC'] as $kDeployable => $deployable) {

                $aReponse .= '
                <div class="'.$sShowroom.' secret" id="deployable_'.$kDeployable.'" '.$borders.'>
                    <div class="closer" '.$sElCloser.'></div>';

                if ($deployable == 'PDV') {
                    $aPDV = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
                        $_SESSION[APP]['GLOBAL_PAGE_ID'],
                        Pelican::$config['ZONE_TEMPLATE_ID']['RECHERCHE_PDV'],
                        $_SESSION[APP]['GLOBAL_PAGE_VERSION'],
                        $_SESSION[APP]['LANGUE_ID']
                    ));
                    $aZoneDeploy = Pelican_Cache::fetch("Zone", array(Pelican::$config['ZONE']['POINT_DE_VENTE']));
                    if ($aPDV && $aZoneDeploy[0]) {
                        $dataDeploy = array_merge($aPDV, $aZoneDeploy[0]);
                    }
                    $aReponse .= Citroen_Request::cachedCall(trim($dataDeploy["ZONE_FO_PATH"]), $dataDeploy, $cache, $data["ZONE_CACHE_TIME"]);

                }else{
                    $aZoneDeploy = Pelican_Cache::fetch("Zone", array(Pelican::$config['ZONE']['FORMULAIRE']));

                    $aTab = explode('_', $deployable);
                    $aFormulaire = Pelican_Cache::fetch("Frontend/Citroen/ZoneTemplate", array(
                        $aTab[0],
                        $aTab[1],
                        Pelican::getPreviewVersion(),
                        false,
                        $aTab[2],
                        $aTab[3],
                        Pelican::$config['ZONE']['FORMULAIRE']
                    ));

                    $formulaire = array();
                    if (!empty($aFormulaire)) {
                        $formulaire = $aFormulaire;
                        $formulaire['FORM_MODE_AFF'] = $aFormulaire['ZONE_TITRE19'];
                        $formulaire['FORM_TITRE'] = $aFormulaire['ZONE_TITRE'];
                        $formulaire['FORM_CHAPO'] = $aFormulaire['ZONE_TEXTE'];
                        $formulaire['FORM_TITRE_THANKS'] = $aFormulaire['ZONE_TITRE8'];
                        $formulaire['FORM_TEXTE_THANKS'] = $aFormulaire['ZONE_TEXTE2'];
                        $formulaire['FORM_TITRE_SHARE'] = $aFormulaire['ZONE_TITRE9'];
                        $formulaire['FORM_SHARE'] = $aFormulaire['ZONE_LABEL2'];
                        $formulaire['FORM_ML_TYPE'] = $aFormulaire['ZONE_TITRE5'];
                        $formulaire['FORM_ML_TITRE'] = $aFormulaire['ZONE_TITRE6'];
                        $formulaire['FORM_ML_TEXTE'] = $aFormulaire['ZONE_TEXTE4'];
                        $formulaire['FORM_ML_MEDIA'] = $aFormulaire['MEDIA_ID4'];
                        $formulaire['FORM_ML_LIEN_PAGE'] = $aFormulaire['ZONE_TITRE7'];

                        if($aFormulaire['ZONE_TITRE4'] != 'CHOIX'){
                            $formulaire2 = Pelican_Cache::fetch("Frontend/Citroen/Formulaire", array(
                                $aFormulaire["ZONE_TITRE3"],
                                $aFormulaire['ZONE_TITRE4'],
                                (($bMobile == true) ? 'MOB' : 'WEB'),
                                $_SESSION[APP]['SITE_ID'],
                                $_SESSION[APP]['LANGUE_ID'],
                                '',
                                '',
                                $data['PAGE_VEHICULE']
                            ));
                            if(!empty($formulaire2)){
                                $formulaire = array_merge($formulaire, $formulaire2);
                            }
                        }
                    }

                    if(!empty($formulaire)){
                        $formulaire['PAGE_VEHICULE'] = $data['PAGE_VEHICULE'];
                    }
                    $form['FORM_DEPLOYE'] = $formulaire;
                    $aZoneDeploy[0]['ZONE_ORDER'] = $kDeployable;
                    if ($form && $aZoneDeploy[0]) {
                        $dataDeploy = array_merge($form, $aZoneDeploy[0]);
                    }
                    $aReponse .= Citroen_Request::cachedCall(trim($dataDeploy["ZONE_FO_PATH"]), $dataDeploy, $cache, $data["ZONE_CACHE_TIME"]);
                }

                $aReponse .= '
                     <span class="popClose"><span '.$sPopClose.'>'.t('FERMER').'</span></span>
					
                </div>';
                $aReponse .= Pelican_Html::comment("/#deployable_ " . $kDeployable) . "\n";
            }

            if (in_array(
                $data['ZONE_ID'],
                array(
                    Pelican::$config['ZONE']['CONTENUS_RECOMMANDES'],
                    Pelican::$config['ZONE']['RECAPITULATIF_MODELE']
                )
            )) {
                $aReponse .= '</div>';
            }
            $Hx = array("h1", "h2", "h3", "h4", "h5", "h6");
            $aReponse = str_replace($Hx, 'div', $aReponse);
            unset(Pelican::$config['DEPLOYABLE_BLOC']);
        }
        return $aReponse;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $data __DESC__
     * @param bool $cache (option) __DESC__
     * @return __TYPE__
     */
    public Function getDirectZone($data, $cache = false) {
        return Citroen_Request::cachedCall(trim($data["ZONE_FO_PATH"]), $data, $cache, $data["ZONE_CACHE_TIME"]);
    }
}