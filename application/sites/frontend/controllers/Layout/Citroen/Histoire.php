<?php

/**
 * Classe d'affichage Front de la tranche Histoire
 *
 * @package Layout
 * @subpackage Citroen
 * @author Moaté David
 * @since 11/09/2013
 */
class Layout_Citroen_Histoire_Controller extends Pelican_Controller_Front {

    public function indexAction() {

        $aParams = $this->getParams();
        $this->assign("aParams", $aParams);

        $aResultsHistoire = Pelican_Cache::fetch("Frontend/Citroen/ListeHistoire", array(
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    Pelican::$config['CONTENT_TYPE_ID']['HISTOIRE'],
                    Pelican::getPreviewVersion()
        ));

        $aResultsHistoire = self::dateArticle($aResultsHistoire);

        $aPageShowroomColor = Frontoffice_Showroom_Helper::getShowroomColor($aParams['PAGE_ID'],$_SESSION[APP]['LANGUE_ID'],Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']);
        if(!empty($aPageShowroomColor['PAGE_PRIMARY_COLOR']) &&  !empty($aPageShowroomColor['PAGE_SECOND_COLOR'])){
            $aParams['PRIMARY_COLOR'] = $aPageShowroomColor['PAGE_PRIMARY_COLOR'];
            $aParams['SECOND_COLOR']  = $aPageShowroomColor['PAGE_SECOND_COLOR'];
        }
        
        if (is_array($aResultsHistoire)) {
            foreach ($aResultsHistoire as $key => $article) {
                $aHistoireTemp[substr($article['ANNEE'], 0, -1) . 0][] = $article;
            }
        }
        if (is_array($aHistoireTemp)) {
            foreach ($aHistoireTemp as $decenie => $aArticles) {
                $frise[] = $decenie;
                if (is_array($aArticles)) {
                    foreach ($aArticles as $key => $article) {

                        if ($article['BLOC'] != 2) {
                            unset($article['IMAGE']);
                        }

                        if ($article['BLOC'] != 3) {
                            unset($article['VIDEO']);
                        }

                        // On gère le format de l'image Portrait/Paysage
                        $formatImage = Pelican::$config['MEDIA_FORMAT_ID']['HISTOIRE_IMAGE_PAYSAGE'];
                        if ($article['FORMAT_IMAGE'] == 1) {
                            $formatImage = Pelican::$config['MEDIA_FORMAT_ID']['HISTOIRE_IMAGE_PORTRAIT'];
                        }

                        $date = new Zend_Date($article['DATE_FR']);
                        $mois = t(strtoupper(dropaccent($date->toString('MMMM','fr_FR'))));
                        // On gère le format de la date UK/FR et de la version (annee/ mois-anne/ jour-mois-annee)
                        if ($aParams['ZONE_ATTRIBUT'] == 1) {

                            if ($article['FORMAT_DATE'] == 1) {
                                $article['DATE'] = $date->toString('YYYY');
                            } elseif ($article['FORMAT_DATE'] == 2) {
                                $article['DATE'] = $mois;
                                $article['DATE'] .= ucfirst($date->toString(' YYYY'));
                            } else {
                                $article['DATE'] = $mois;
                                $article['DATE'] .= $date->toString(' dd, YYYY');

                            }
                        } else {
                            if ($article['FORMAT_DATE'] == 1) {
                                $article['DATE'] = $date->toString('YYYY');
                            } elseif ($article['FORMAT_DATE'] == 2) {
                                $article['DATE'] = $mois;
                                $article['DATE'] .= ucfirst($date->toString(' YYYY'));
                            } else {
                             
                                $article['DATE'] = $date->toString('dd ');
                                $article['DATE'] .= $mois;
                                $article['DATE'] .= $date->toString(' YYYY');
                            }
                        }
                        if (isset($article['IMAGE']) && !empty($article['IMAGE'])) {
                            $article['MEDIA'] = Pelican::$config['MEDIA_HTTP'] . Citroen_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($article['IMAGE']), $formatImage);
                        } elseif (isset($article['VIDEO']) && !empty($article['VIDEO'])) {
                            // Mise en place du player 
                            $head = $this->getView()->getHead();
                            $head->setSwfObject();
                            $head->setJs(Pelican::$config["MEDIA_HTTP"] . '/flashplayer/ufo.js');
                            $infosMedia = Pelican_Media::getMediaInfo($article['VIDEO']);
                            // Si c'est une vidéo youtube
                            if ($infosMedia['MEDIA_TYPE_ID'] == 'youtube') {
                                
                                //var_dump($infosMedia);
                                $article['MEDIA'] = Frontoffice_Video_Helper::setYoutube($infosMedia["YOUTUBE_ID"]);
                                $article['MEDIA_TYPE_ID'] = 'youtube';
                                $article['MEDIA_PATH'] = $infosMedia['MEDIA_PATH'];
                            }
                            // Si c'est une video qui necessite un player
                            elseif ($infosMedia['MEDIA_TYPE_ID'] == 'video') {
                                $size['WIDTH'] = Pelican::$config['FORMAT_VIDEO_PLAYER']['HISTOIRE'] ['WIDTH'];
                                $size['HEIGHT'] = Pelican::$config['FORMAT_VIDEO_PLAYER']['HISTOIRE'] ['HEIGHT'];
                                // Récupération du JS et de  L'HTML pour afficher le player
                                $article['MEDIA_VIDEO_PLAYER'] = Frontoffice_Video_Helper::getPlayer($article['VIDEO']);
                            }
                        }
                        //var_dump($article);
                        // Le modulo permet de gérer l'affichage la colonne de gauche et la colonne de droite
                        $modulo = $key % 2;
                        if (!isset($modulo) || empty($modulo)) {
                            $aHistoire[$decenie]['COLONNE_GAUCHE'][] = $article;
                        } else {
                            $aHistoire[$decenie]['COLONNE_DROITE'][] = $article;
                        }
                        // Gestion des articles pour la version mobile qui ne possede pas de colonne						
                        $aHistoire[$decenie]['MOBILE'][] = $article;
                    }
                }
            }
        }
		
		if(is_array(Pelican::$config['JAVASCRIPT_FOOTER']['WEB'])){
			array_push(Pelican::$config['JAVASCRIPT_FOOTER']['WEB'],
					Pelican::$config['DESIGN_HTTP']."/assets/js/common/Tranches/histoire.js"
			);
		}	
        
        /* Assignation des variables SMARTY */
        $this->assign('aFrise', $frise);
        $this->assign('aHistoires', $aHistoire);
        $this->fetch();
    }

    public function ajaxGetArticlesAction() {

        $aResultsHistoire = Pelican_Cache::fetch("Frontend/Citroen/ListeHistoire", array(
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    Pelican::$config['CONTENT_TYPE_ID']['HISTOIRE'],
                    Pelican::getPreviewVersion()
        ));

        $aResultsHistoire = self::dateArticle($aResultsHistoire);

        if (is_array($aResultsHistoire)) {
            foreach ($aResultsHistoire as $key => $article) {
                $aHistoireTemp[substr($article['ANNEE'], 0, -1) . 0][] = $article;
            }
        }

        if (is_array($aHistoireTemp)) {
            foreach ($aHistoireTemp as $decenie => $aArticles) {
                if (is_array($aArticles)) {
                    $aArticlesJsonTemp['date'] = $decenie;
                    foreach ($aArticles as $key => $aArticle) {
                        /* if(isset($aArticle['IMAGE'])){
                          $aArticlesJson['date']['articles'][$key]['media']		=	Pelican::$config['MEDIA_HTTP'].Pelican_Media::getFileNameMediaFormat($aArticle['IMAGE'], Pelican::$config['MEDIA_FORMAT_ID']['ACTUALITES_DETAIL']);
                          } */
                        $aArticlesJsonTemp['articles'][$key]['format'] = "235-100";
                        $aArticlesJsonTemp['articles'][$key]['title'] = $aArticle['DATE_FR'];
                        $aArticlesJsonTemp['articles'][$key]['subtitle'] = $aArticle['CONTENT_TITLE'];
                        $aArticlesJsonTemp['articles'][$key]['texts'][] = $aArticle['CONTENT_TEXT'];
                    }
                }
                //$aArticlesJson	=	 array_merge($aArticlesJson, $aArticlesJsonTemp);
            }
        }
        echo json_encode($aArticlesJsonTemp);
    }

    static private function dateArticle($aResultsHistoire) {

        $aTemp = array();
        $n = 0;
        // test des dates de publications et fin
        if($aResultsHistoire)
        {
        foreach ($aResultsHistoire as $aOneHistoire) {

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