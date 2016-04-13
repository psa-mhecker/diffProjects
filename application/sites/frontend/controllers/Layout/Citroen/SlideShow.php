<?php
require_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Showroom.php');

class Layout_Citroen_SlideShow_Controller extends Pelican_Controller_Front
{
    /**
     * Flag indiquant si le slideshow a matché un profil de personnalisation
     * Si plusieurs tranches slideshow sont présentes dans la page, chacune
     * est succeptible de passer le flag à true.
     * Une fois à true, le flag conserve sa valeur.
     */
    public static $persoMatch = false;
    
    public function indexAction()
    {
        $aData = $this->getParams();
        $usingPerso = false;
        if (
            ($this->isMobile() && isset($aData['__perso_ZONE_MOBILE']) && $aData['__perso_ZONE_MOBILE'] === false) ||
            (!$this->isMobile() && isset($aData['__perso_ZONE_WEB']) && $aData['__perso_ZONE_WEB'] === false)
        ){
            return;
        }
        
        // Page globale
        $pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
        		$_SESSION[APP]['SITE_ID'],
        		$_SESSION[APP]['LANGUE_ID'],
        		Pelican::getPreviewVersion(),
        		Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
        ));
        // Zone Configuration de la page globale
        $aConfiguration = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
        		$pageGlobal['PAGE_ID'],
        		Pelican::$config['ZONE_TEMPLATE_ID']['CONFIGURATION'],
        		$pageGlobal['PAGE_VERSION'],
        		$_SESSION[APP]['LANGUE_ID']
        ));
        
        $aData['is_new_slideshow'] = $aConfiguration['ZONE_TITRE23'];
        
        if ($_GET['ACCEUIL_PROMO_SLIDESHOW'] != 1) {
            //Récupération du multi
            $aSlideShow = Citroen_Cache::fetchProfiling($aData['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
                $aData['PAGE_ID'],
                $aData['LANGUE_ID'],
                Pelican::getPreviewVersion(),
                $aData['ZONE_TEMPLATE_ID'],
                'SLIDESHOW_GENERIC',
                $aData['AREA_ID'],
                $aData['ZONE_ORDER']
            ));
            
            // Mise à jour du flag perso
            if (Citroen_Cache::$profilMatch) {
                self::$persoMatch = true;
            }
            
            if(is_array($aSlideShow) && count($aSlideShow)>0){
                    $usingPerso = Citroen_Cache::$profilMatch;

                $productMedia = Pelican_Cache::fetch("Frontend/Citroen/Perso/ProductMedia", array(
                    $_SESSION[APP]['SITE_ID']
                ));
				
                foreach($aSlideShow as $key=>$result){
                    for($i=1;$i<6;$i++){
                        $iElement = ($i == 1) ? "" : $i;
                        if($result['MEDIA_ID'.$iElement] != '' || ($result['MEDIA_ID'.$iElement.'_GENERIQUE'] && !empty($_SESSION[APP]['FLAGS_USER']['preferred_product']))){
                            if($i != 3 && $i != 4){
                                if($i==1 || $i==2){
                                    $typeMedia = ($i==1) ? 'SLIDESHOW_WEB' : 'SLIDESHOW_MOB';
                                    if($result['MEDIA_ID'.$iElement.'_GENERIQUE'] && !empty($_SESSION[APP]['FLAGS_USER']['preferred_product']) && !empty($productMedia[$_SESSION[APP]['FLAGS_USER']['preferred_product']][$typeMedia])){
                                        $mediaPath = $productMedia[$_SESSION[APP]['FLAGS_USER']['preferred_product']][$typeMedia];
                                        $result['MEDIA_ID'.$iElement] = $media['MEDIA_ID'][$result['PRODUCT_ID']][$result['PRODUCT_MEDIA_TYPE']];
                                    }else{
                                        $mediaPath = Pelican_Media::getMediaPath($result['MEDIA_ID'.$iElement]);
                                    }
                                }else{
                                    $mediaPath = Pelican_Media::getMediaPath($result['MEDIA_ID'.$iElement]);
                                }
                                
                            	//deux format d'image pour le slideshow
                                $slideshowFormat = Pelican::$config['MEDIA_FORMAT_ID']['NEW_SLIDESHOW'];
                                if($aConfiguration['ZONE_TITRE23'] == false){
                                        $slideshowFormat = Pelican::$config['MEDIA_FORMAT_ID']['WEB_SLIDESHOW_PRINCIPAL'];
                                }
						        
                                $aSlideShow[$key]['MEDIA_PATH'.$iElement] = Pelican::$config['MEDIA_HTTP'].Citroen_Media::getFileNameMediaFormat($mediaPath, $slideshowFormat);
                            }else{
                                $aSlideShow[$key]['MEDIA_PATH'.$iElement] = Pelican::$config['MEDIA_HTTP'].Pelican_Media::getMediaPath($result['MEDIA_ID'.$iElement]);
                            }
                            $MEDIA_ALT = Citroen_Media::getMediaAlt($result['MEDIA_ID'.$iElement]);
                            
                            $aSlideShow[$key]['MEDIA_ALT'.$iElement] = empty($MEDIA_ALT)?$result['PAGE_ZONE_MULTI_TITRE']:$MEDIA_ALT;
                        }
                    }
                    if($result['YOUTUBE_ID'] != ''){
						
						$aMediaDetailsVideo = Pelican_Cache::fetch("Media/Detail", array($result['YOUTUBE_ID']));
						 if ( intval($aMediaDetailsVideo['MEDIA_ID_REFERENT_PICTURE'])> 0 ) {
                            $aMediaReferentPicture = Pelican_Cache::fetch("Media/Detail", array($aMediaDetailsVideo['MEDIA_ID_REFERENT_PICTURE'] ));
							$aSlideShow[$key]['REFERENT_PICTURE'] = Pelican::$config['MEDIA_HTTP'].$aMediaReferentPicture['MEDIA_PATH'];
                        }
						
                        $aSlideShow[$key]['VIDEOS'] = Frontoffice_Video_Helper::getPlayer($result['YOUTUBE_ID'],$aSlideShow[$key]['REFERENT_PICTURE']);
						
						
                    }
                    //Couleur typo/CTA
                    if($result['PAGE_ZONE_MULTI_LABEL3'] != '' && $result['PAGE_ZONE_MULTI_LABEL3']!= '#0000ffff'){
                        $aSlideShow[$key]['COULEUR_TYPO'] = $result['PAGE_ZONE_MULTI_LABEL3'];

                    }

                    if($result['PAGE_ZONE_MULTI_LABEL4'] != ''&& $result['PAGE_ZONE_MULTI_LABEL4']!= '#0000ffff'){
                        $aSlideShow[$key]['COULEUR_CTA'] = $result['PAGE_ZONE_MULTI_LABEL4'];
                    }

                    //MOBILE
                    if($result['PAGE_ZONE_MULTI_LABEL7'] != '' && $result['PAGE_ZONE_MULTI_LABEL7']!= '#0000ffff'){
                        $aSlideShow[$key]['MOBILE_COULEUR_TYPO'] = $result['PAGE_ZONE_MULTI_LABEL7'];
                    }

                    if($result['PAGE_ZONE_MULTI_LABEL8'] != ''&& $result['PAGE_ZONE_MULTI_LABEL8']!= '#0000ffff'){
                        $aSlideShow[$key]['MOBILE_COULEUR_CTA'] = $result['PAGE_ZONE_MULTI_LABEL8'];
                    }


                    //Si aucun libellé n'est renseigné
                    if($result['PAGE_ZONE_MULTI_LABEL'] == '' && $result['PAGE_ZONE_MULTI_LABEL2'] == ''){
                        //Si la première url est renseigné on l'utilise comme lien target pour le visuel
                        if($result['PAGE_ZONE_MULTI_URL3'] != ""){
                            $aSlideShow[$key]['URL_CLIC'] = $result['PAGE_ZONE_MULTI_URL3'];
                            $aSlideShow[$key]['TARGET_CLIC'] = ($result['PAGE_ZONE_MULTI_MODE4'] == 2) ? '_blank' : '_self';
                        }else{
                            if($result['PAGE_ZONE_MULTI_URL'] != ''){
                                $aSlideShow[$key]['URL_CLIC'] = $result['PAGE_ZONE_MULTI_URL'];
                                $aSlideShow[$key]['TARGET_CLIC'] = ($result['PAGE_ZONE_MULTI_MODE'] == 2) ? '_blank' : '_self';
                            //Si non on prend la deuxième
                            }elseif($result['PAGE_ZONE_MULTI_URL2'] != ''){
                                $aSlideShow[$key]['URL_CLIC'] = $result['PAGE_ZONE_MULTI_URL2'];
                                $aSlideShow[$key]['TARGET_CLIC'] =  ($result['PAGE_ZONE_MULTI_MODE2'] == 2) ? '_blank' : '_self';
                            }
                        }
                    }elseif($result['PAGE_ZONE_MULTI_LABEL'] != '' && $result['PAGE_ZONE_MULTI_LABEL2'] != ''){
                        //Quand un seul bouton est paramétré on l'utilise comme lien target pour le visuel
                        if($result['PAGE_ZONE_MULTI_MODE3'] != '' && $result['PAGE_ZONE_MULTI_URL3'] != ''){
                            $aSlideShow[$key]['URL_CLIC'] = $result['PAGE_ZONE_MULTI_URL3'];
                            $aSlideShow[$key]['TARGET_CLIC'] = ($result['PAGE_ZONE_MULTI_MODE4'] == 2) ? '_blank' : '_self';
                        }
                    }else{
                        //Quand un seul bouton est paramétré on l'utilise comme lien target pour le visuel
                        if($result['PAGE_ZONE_MULTI_LABEL'] != '' && $result['PAGE_ZONE_MULTI_URL'] != '' && $result['PAGE_ZONE_MULTI_LABEL2'] == ''){
                            $aSlideShow[$key]['URL_CLIC'] = $result['PAGE_ZONE_MULTI_URL'];
                            $aSlideShow[$key]['TARGET_CLIC'] =  ($result['PAGE_ZONE_MULTI_MODE2'] == 2) ? '_blank' : '_self';
                        }elseif($result['PAGE_ZONE_MULTI_LABEL2'] != '' && $result['PAGE_ZONE_MULTI_URL2'] != '' && $result['PAGE_ZONE_MULTI_LABEL'] == ''){
                            $aSlideShow[$key]['URL_CLIC'] = $result['PAGE_ZONE_MULTI_URL2'];
                            $aSlideShow[$key]['TARGET_CLIC'] =  ($result['PAGE_ZONE_MULTI_MODE2'] == 2) ? '_blank' : '_self';
                        }
                    }

                    //Mobile
                    if($result['PAGE_ZONE_MULTI_LABEL'] != '' && $result['PAGE_ZONE_MULTI_URL'] != ''){
                            $aSlideShow[$key]['URL_CLIC_MOBILE'] = $result['PAGE_ZONE_MULTI_URL2'];
                            $aSlideShow[$key]['TARGET_CLIC_MOBILE'] =  ($result['PAGE_ZONE_MULTI_MODE2'] == 2) ? '_blank' : '_self';
                        }elseif($result['PAGE_ZONE_MULTI_LABEL2'] != '' && $result['PAGE_ZONE_MULTI_URL2'] != ''
                        ){
                            $aSlideShow[$key]['URL_CLIC_MOBILE'] = $result['PAGE_ZONE_MULTI_URL2'];
                            $aSlideShow[$key]['TARGET_CLIC_MOBILE'] =  ($result['PAGE_ZONE_MULTI_MODE2'] == 2) ? '_blank' : '_self';
                        }
                    
                    // Injection paramètre origin si il s'agit d'un slide perso, sur les URL internes
                    //$usingPersoData = isset($result['PAGE_ZONE_MULTI_ID']) && Frontoffice_Zone_Helper::usingPersoData($aData, $result['PAGE_ZONE_MULTI_ID']) ? true : false;
                    if ($usingPerso ) {
                        $ctaUrlFields = array('PAGE_ZONE_MULTI_URL', 'PAGE_ZONE_MULTI_URL2');
                        foreach ($ctaUrlFields as $field) {
                            if (!empty($aSlideShow[$key][$field]) && preg_match('#^/#', $aSlideShow[$key][$field])) {
                                $aSlideShow[$key][$field] = Frontoffice_Zone_Helper::setUrlQueryString($aSlideShow[$key][$field], array('origin' => 'ctaperso'));
								$aSlideShow[$key]['IS_PERSO'] = true;
                            }
                        }
                    }
                }
            }
			


                        
            if ($aData['ZONE_ORDER']) {
                    $aData['ID_HTML_SLIDESHOW'] = $aData['AREA_ID'] . '_' . $aData['ZONE_ORDER'] .'_slide';
            } else {
                    $aData['ID_HTML_SLIDESHOW'] = $aData['ZONE_TEMPLATE_ID'].'_slide';
            }
            
            $web_img_count = 0;
            $mobile_img_count = 0;
            // Définition du champ _sync (rétro-compatibilité)
            if (is_array($aSlideShow)) {
                foreach ($aSlideShow as $key => $val) {
                    /*if ( isset($val['PAGE_ZONE_MULTI_ID'])) {*/
                    if(isset($val['PAGE_ZONE_MULTI_ATTRIBUT'])){
                        $aSlideShow[$key]['position'] = Pelican::$config['POSITION_CTA_POSITION'][$val['PAGE_ZONE_MULTI_ATTRIBUT']];
                    }
                    if(isset($val['PAGE_ZONE_MULTI_ID'])) {
                        $aSlideShow[$key]['_sync'] = $val['PAGE_ZONE_MULTI_ID'];
                    }
                    /*}*/

                    if($val['MEDIA_PATH'] != ''){
                        $web_img_count++;
                    }

                    if($val['MEDIA_PATH2'] != ''){
                        $mobile_img_count++;
                    }
                }
            }
            
            $iTiming = $aData['ZONE_CRITERIA_ID'] * 1000;
            $this->assign('usingPerso',$usingPerso);


            $this->assign("web_img_count", $web_img_count);
            $this->assign("mobile_img_count", $mobile_img_count);
            
            $this->assign("aSlideShow", $aSlideShow);
            $this->assign("iTiming", $iTiming);
            $this->assign("aData", $aData);
            $this->assign("bTplHome", (in_array($aData['TEMPLATE_PAGE_ID'], array(Pelican::$config['TEMPLATE_PAGE']['HOME'])))?1:0);
            $this->fetch();
        }
    }
}