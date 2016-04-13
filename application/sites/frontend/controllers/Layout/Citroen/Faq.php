<?php

/**
 * Classe d'affichage Front de la tranche FAQ
 *
 * @package Layout
 * @subpackage Citroen
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 * @since 23/08/2013
 */
class Layout_Citroen_Faq_Controller extends Pelican_Controller_Front {

    public function indexAction() {
        /* Initialisation des variables */
        /* Tableau des rubriques sélectionnée pour la page */
        $aPageRubriques = array();
        $aParams = $this->getParams();
        /* Affichage du bloc ou non */
        $bDisplayBlock = false;
        /* Rubrique sélectionnée */
        $mFaqRubSelect = null;
        /* Tableau contenant les informations de la rubrique sélectionnée (pour le Web) */
        $aFaqSelected = array();

        /* Récupération de la rubrique de FAQ sélectionnée */
        if (isset($_GET['frid']) && !empty($_GET['frid'])) {
            $mFaqRubSelect = (int) $_GET['frid'];
        }

        /* Nombre minimum de rubriques de FAQ nécessaires pour l'affichage de la
         * tranche
         */
        $iNbMinRub = 1;
        /* Nombre maximum de rubriques de FAQ à afficher
         */
        $iNbMaxRub = 15;

        /* Par défaut j'initialise les valeurs pour l'affichage des contenus Web
         * Affichage en mode Web et non mobile
         */
        $bWebContent = true;
        $bMobileContent = false;

        /* Si l'on affiche les contenus mobiles */
        if ($this->isMobile() === true) {
            $bWebContent = false;
            $bMobileContent = true;
        }

        /* Récupération des rubriques de FAQ associées à la page */
        if (!empty($aParams['ZONE_PARAMETERS'])) {
            $aPageRubriques = explode('|', $aParams['ZONE_PARAMETERS']);
        }

        /* Vérification de la l'affichage de la tranche ou non si il y a au moins
         * 5 rubriques de FAQ à remonter on ne récupère par les contenus associés
         */
        if (is_array($aPageRubriques) && !empty($aPageRubriques) && count($aPageRubriques) >= $iNbMinRub) {
            /* Initialisation des variables */
            $i = 0;

            $aMostAskedQuestions = array();
            $aFaq = array();

            foreach ($aPageRubriques as $iFaqRubriqueId) {
                $iFaqRubriqueId = (int) $iFaqRubriqueId;
                /* Récupération des informations de la rubrique */
                $aRubrique = Pelican_Cache::fetch(
                                'Frontend/Citroen/Faq/Rubrique', array(
                            $_SESSION[APP]['SITE_ID'],
                            $_SESSION[APP]['LANGUE_ID'],
                            $iFaqRubriqueId
                                )
                );

                /* Récupération des contenus possèdant un ordre ou pas */
                $aQuestions = Pelican_Cache::fetch(
                                'Frontend/Citroen/Faq/RubriqueContent', array(
                            $_SESSION[APP]['SITE_ID'],
                            $_SESSION[APP]['LANGUE_ID'],
                            $iFaqRubriqueId,
                            Pelican::$config['CONTENT_TYPE_ID']['FAQ'],
                            Pelican::getPreviewVersion(),
                            $bWebContent,
                            $bMobileContent,
                            false
                                )
                );
                $aQuestions = self::dateQuestion($aQuestions);

                /* Récupération des contenus "questions les plus demandés
                 * dans la rubrique et qui possède un ordre ou pas */
                $aMostAskedQuestionsInRub = Pelican_Cache::fetch(
                                'Frontend/Citroen/Faq/RubriqueContent', array(
                            $_SESSION[APP]['SITE_ID'],
                            $_SESSION[APP]['LANGUE_ID'],
                            $iFaqRubriqueId,
                            Pelican::$config['CONTENT_TYPE_ID']['FAQ'],
                            Pelican::getPreviewVersion(),
                            $bWebContent,
                            $bMobileContent,
                            true
                                )
                );

                $aMostAskedQuestionsInRub = self::dateQuestion($aMostAskedQuestionsInRub);
                /* Création du tableau de l'ensemble des questions par rubrique */
                if (is_array($aRubrique) && !empty($aRubrique) && is_array($aQuestions) && !empty($aQuestions)) {
                    $aFaq[$i]['RUBRIQUE_ID'] = $aRubrique[0]['FAQ_RUBRIQUE_ID'];
                    $aFaq[$i]['RUBRIQUE_LABEL'] = $aRubrique[0]['FAQ_RUBRIQUE_LABEL'];
                    $aFaq[$i]['RUBRIQUE_LABEL'] = $aRubrique[0]['FAQ_RUBRIQUE_LABEL'];
                    $aFaq[$i]['CONTENT_MOBILE'] = $aQuestions[0]['CONTENT_MOBILE'];
                    $aFaq[$i]['CONTENT_WEB'] = $aQuestions[0]['CONTENT_WEB'];
                    $aFaq[$i]['CONTENT_SHORTTEXT'] = $aQuestions[0]['CONTENT_SHORTTEXT'];


                    $aFaq[$i]['RUBRIQUE_PICTO_PATH'] = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($aRubrique[0]['PICTO_PATH']), Pelican::$config['MEDIA_FORMAT_ID']['PETIT_CARRE']);

                    $aFaq[$i]['RUBRIQUE_PICTO_ALT'] = $aRubrique[0]['PICTO_ALT'];
                    $aFaq[$i]['RUBRIQUE_HREF'] = $aParams['PAGE_CLEAR_URL'] . '?frid=' . $aRubrique[0]['FAQ_RUBRIQUE_ID'];

                    /* Mise en avant de la rubrique de FAQ sélectionnée */
                    if (!is_null($mFaqRubSelect) && $aFaq[$i]['RUBRIQUE_ID'] == $mFaqRubSelect) {
                        $aFaq[$i]['RUBRIQUE_SELECTED'] = true;
                    } else {
                        $aFaq[$i]['RUBRIQUE_SELECTED'] = false;
                    }


                    $n = 0;
                    $aVIGN_GALLERY = array();


                    foreach ($aQuestions as $numQ => $aOneQuestion) {
                        $aQuestions[$numQ]['MEDIA_ID9'] = Pelican_Media::getMediaPath($aOneQuestion['MEDIA_ID9']);

                        $aQuestions[$n]['countPM'] = 0;
                        foreach ($aOneQuestion['PUSH_MEDIA'] as $aPushMedia) {

                            switch ($aPushMedia['type']) {
                                case 'VIDEO':
                                    if ($aPushMedia['video'] != "") {
                                        $aQuestions[$n]['countPM']++;
                                    }
                                    break;
                                case 'GALLERY':
                                    if (count($aPushMedia['gallery']) > 0) {
                                        $aQuestions[$n]['countPM']++;
                                    }
                                    break;
                            }
                        }

                        $n++;
                    }

                    $aFaq[$i]['QUESTIONS'] = $aQuestions;
                }

                /* Création du tableau d'un élément sélectionné */
                if (!is_null($mFaqRubSelect) && $iFaqRubriqueId === $mFaqRubSelect) {
                    $aFaqSelected = $aFaq[$i];
                }
                /* Création du tableau des questions les plus posées qui reprend
                 * les contenus FAQ cochés 'Questions les plus posées' pour les
                 * rubriques sélectionnées dans la page
                 */
                if (is_array($aMostAskedQuestions) && is_array($aMostAskedQuestionsInRub)) {
                    $aMostAskedQuestions = array_merge($aMostAskedQuestions, $aMostAskedQuestionsInRub);
                }

                /* Si le traitement  atteint les 15 rubriques maximum on arrête
                 * la récupération des données
                 */
                if ($i == $iNbMaxRub - 1) {
                    break;
                }
                if (is_array($aRubrique) && !empty($aRubrique) && is_array($aQuestions) && !empty($aQuestions)) {
                    $i++;
                }
            }

            /* Vérification qu'il y a entre  5 et 15 rubriques de FAQ associées
             * à des contenus. Si ce n'est pas le cas, le bloc ne sera pas affiché
             */
            if (is_array($aFaq) &&
                    count($aFaq) >= $iNbMinRub &&
                    is_array($aMostAskedQuestions) &&
                    !empty($aMostAskedQuestions)
            ) {

                $aFaqTemp['RUBRIQUE_ID'] = '';
                $aFaqTemp['RUBRIQUE_LABEL'] = t('FAQ_FREQUENTLY_ASKED_QUESTIONS');
                $aFaqTemp['RUBRIQUE_PICTO_PATH'] = Pelican::$config['IMAGE_FRONT_HTTP'] . '/picto/help.JPG';

                $aFaqTemp['RUBRIQUE_PICTO_ALT'] = t('FAQ_FREQUENTLY_ASKED_QUESTIONS');
                $aFaqTemp['RUBRIQUE_HREF'] = $aParams['PAGE_CLEAR_URL'];
                /* Mise en avant de la rubrique de FAQ sélectionnée */
                if (is_null($mFaqRubSelect) || empty($mFaqRubSelect)) {
                    $aFaqTemp['RUBRIQUE_SELECTED'] = true;
                } else {
                    $aFaqTemp['RUBRIQUE_SELECTED'] = false;
                }
                $aFaqTemp['QUESTIONS'] = $aMostAskedQuestions;
                /* Ajout du tableaux des contenus "les questions les plus posées"
                 * Au début du tableau de FAQ
                 */
                array_unshift($aFaq, $aFaqTemp);
                /* Création du tableau d'un élément sélectionné */
                if (is_null($mFaqRubSelect) || empty($mFaqRubSelect)) {
                    $aFaqSelected = $aFaqTemp;
                }

                $bDisplayBlock = true;

                /* Si des Questions sont présentes mais qu'il n'y a pas de contenus
                 * "Les questions les plus posées", on affiche quand même les informations
                 */
            } elseif (is_array($aFaq) && count($aFaq) >= $iNbMinRub) {
                /* Si aucune rubrique n'est sélectionnée on affiche la première
                 * rubrique disponible
                 */
                if (is_null($mFaqRubSelect) || empty($mFaqRubSelect)) {
                    $aFaq[0]['RUBRIQUE_SELECTED'] = true;
                    $aFaqSelected = $aFaq[0];
                }
                $bDisplayBlock = true;
            }
        }




        /* Assignation des variables SMARTY */
        $this->assign('aParams', $aParams);
        $this->assign('bDisplayBlock', $bDisplayBlock);
        $this->assign('aFaq', $aFaq);

        foreach ($aFaqSelected['QUESTIONS'] as $id => $Faq) {
            if ($Faq['MEDIA_ID9']) {
                $temp = Pelican_Cache::fetch("Media/Detail", array(
                            $Faq['MEDIA_ID9']
                ));
                $aFaqSelected['QUESTIONS'][$id]['MEDIA_ID9'] = $temp["MEDIA_PATH"];
            }
        }

        $this->assign('aFaqSelected', $aFaqSelected);

        //debug($aParams);
        //debug($aFaq);
        //debug($bDisplayBlock);
        //debug($aMostAskedQuestions);

        $this->fetch();
    }

    static private function dateQuestion($aResultsQuestion) {

        $aTemp = array();
        $n = 0;
        // test des dates de publications et fin
        if (!is_null($aResultsQuestion)) {
            foreach ($aResultsQuestion as $aOneHistoire) {

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