<?php

/**
 * __DESC__.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */

/**
 * __DESC__.
 *
 * @author __AUTHOR__
 */
class Pelican_Layout_Orchestra
{
    public $exclude = array(
      'ZONE_PERSO' => 0,
      'PAGE_ID' => 0,
      'PAGE_NB_ITEM_PAR_LIGNE' => 0,
      'PAGE_ID_SOURCE' => 0,
      'PAGE_DRAFT_VERSION' => 0,
      'PAGE_START_DATE' => 0,
      'PAGE_STATUS' => 0,
      'PAGE_PUBLICATION_DATE' => 0,
      'PAGE_LIBPATH' => 0,
      'PAGE_SUBTITLE' => 0,
      'PAGE_ARCHIVE' => 0,
      'PAGE_PICTO_URL' => 0,
      'STATE_ID' => 0,
      'PAGE_END_DATE' => 0,
      'PAGE_CODE' => 0,
      'PAGE_ORDER' => 0,
      'PAGE_MODE_AFFICHAGE' => 0,
      'TEMPLATE_PAGE_ID' => 0,
      'PAGE_CLEAR_URL' => 0,
      'PAGE_CREATION_DATE' => 0,
      'PAGE_CREATION_USER' => 0,
      'PAGE_DIFFUSION' => 0,
      'PAGE_VEHICULE' => 0,
      'LANGUE_ID' => 0,
      'PAGE_PRIORITY' => 0,
      'PAGE_URL' => 0,
      'PAGE_URL_EXTERNE_MODE_OUVERTURE' => 0,
      'PAGE_URL_EXTERNE' => 0,
      'PAGE_GAMME_VEHICULE' => 0,
      'PAGE_GENERAL' => 0,
      'PAGE_LONGITUDE' => 0,
      'PUB_ID' => 0,
      'SITE_ID' => 0,
      'PAGE_LANGUETTE_CLIENT' => 0,
      'PAGE_LANGUETTE_PRO' => 0,
      'PAGE_MENTIONS_LEGALES' => 0,
      'PAGE_SHORTTEXT' => 0,
      'PAGE_PROTOCOLE_HTTPS' => 0,
      'PAGE_TYPE_EXPAND' => 0,
      'PAGE_TYPE_ID' => 0,
      'PAGE_PARENT_ID' => 0,
      'PAGE_CURRENT_VERSION' => 0,
      'PAGE_VERSION_CREATION_DATE' => 0,
      'PAGE_VERSION_CREATION_USER' => 0,
      'PAGE_VERSION_UPDATE_DATE' => 0,
      'PAGE_VERSION' => 0,
      'PAGE_VERSION_UPDATE_USER' => 0,
      'PAGE_PERSO' => 0,
      'PAGE_DISPLAY_NAV' => 0,
      'PAGE_DISPLAY' => 0,
      'PAGE_DISPLAY_PLAN' => 0,
      'PAGE_DISPLAY_SEARCH' => 0,
      'PAGE_META_DESC' => 0,
      'PAGE_META_KEYWORD' => 0,
      'PAGE_META_ROBOTS' => 0,
      'PAGE_META_TITLE' => 0,
      'PAGE_META_URL_CANONIQUE' => 0,
      'PAGE_EXTERNAL_LINK' => 0,
      'PAGE_DATE' => 0,
      'PAGE_PATH' => 0,
      'PAGE_AUTHOR' => 0,
      'PAGE_METIER' => 0,
      'PAGE_LATITUDE' => 0,
      'PAGE_TITLE_BO' => 0,
      'PAGE_TITLE_URL' => 0,
      'PAGE_TITLE' => 0,
      'PAGE_OUVERTURE_DIRECT' => 0,
      'PAGE_OUVRIR_NIVEAU_3' => 0,
      'PAGE_TEXT' => 0,
      'PAGE_KEYWORD' => 0,
    );

    /**
     * @access protected
     *
     * @var __TYPE__ __DESC__
     */
    protected $_tpl = '';

    /**
     * @access protected
     *
     * @var __TYPE__ __DESC__
     */
    protected $_pid = '';

    /**
     * @access protected
     *
     * @var __TYPE__ __DESC__
     */
    protected $_cid = '';

    /**
     * @access protected
     *
     * @var __TYPE__ __DESC__
     */
    protected $_ajax = '';

    /**
     * @access protected
     *
     * @var __TYPE__ __DESC__
     */
    protected $_iframe = '';

    /**
     * @access protected
     *
     * @var __TYPE__ __DESC__
     */
    protected $_type = 'desktop';

    /**
     * Constructeur.
     *
     * @access public
     *
     * @param __TYPE__ $aPage
     *                        __DESC__
     *
     * @return __TYPE__
     */
    public function __construct($aPage)
    {
        $this->aPage = $aPage;
        if (!empty($_GET["pid"])) {
            $this->_pid = $_GET["pid"];
        }
        if (!empty($_GET["tpl"])) {
            $this->_tpl = $_GET["tpl"];
        }
        if (!empty($_GET["cid"])) {
            $this->_cid = $_GET["cid"];
        }
        if (!empty($_GET["preview"])) {
            $this->preview = $_GET["preview"];
        }
        if (!empty($_GET["ajax"])) {
            $this->_ajax = $_GET["ajax"];
        }
        if (!empty($_GET["iframe"])) {
            $this->_iframe = $_GET["iframe"];
        }
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function getModules()
    {
        if ($this->aPage) {
            $return = Pelican_Cache::fetch(
              "Frontend/Page/Zone",
              array(
                $this->_pid,
                $_SESSION[APP]['LANGUE_ID'],
                Pelican::getPreviewVersion(),
                $this->_type,
              )
            );
            $this->tabAreas = $return["areas"];
            $this->tabZones = $return["zones"];

            /*
             * si un contenu est défini
             */
            if ($this->_tpl) {
                $template = Pelican_Cache::fetch("Template", $this->_tpl);
                $this->aTemplate = $template[0];
                if ($this->aTemplate["TEMPLATE_PATH_FO"]) {
                    // Génère la Pelican_Index_Frontoffice_Zone du contenu
                    $this->getModuleResponse($this->aTemplate["TEMPLATE_PATH_FO"], $this->_type);
                } else {
                    Pelican_Request::getInstance()->sendError(404);
                }
            } else {
                $this->getModuleResponse('');
            }
        }
        if (is_array($this->response)) {
            return $this->response;
        }
    }

    /**
     * DESC.
     *
     * @access public
     *
     * @param string $tpl
     *                    (option) __DESC__
     */
    public function getModuleResponse($tpl = "")
    {

        // Suppression de la balise <div role="main"> pour le gabarit CITROEN SOCIAL
        foreach ($this->tabAreas as $key => $aZone) {
            if ($aZone['AREA_ID'] == Pelican::$config['AREA']['MAIN'] && $aZone['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['CITROEN_SOCIAL']) {
                $this->tabAreas[$key]['AREA_HEAD'] = '';
                $this->tabAreas[$key]['AREA_FOOT'] = '';
            }
        }

        $aOnglets = array();

        $this->blocCount = 0;
        $this->blocCountWeb = 0;
        $this->blocCountMobile = 0;

        if ($this->tabAreas && $this->tabZones) {
            $trancheEnfant = 0;
            $trancheParent = 0;
            // ** Variable pour Gestion Accordeon WEB MOBILE ** //
            $traitementItem = false;
            $itemId = 1;

            foreach ($this->tabAreas as $area) {
                if (empty($this->response['PAGE'])) {
                    $page = array_intersect_key($area, $this->exclude);
                    $this->response['PAGE'] = $page;
                }

                if ($bMobile && $area['AREA_MOBILE'] == 1) {
                    $this->response['AREAS'][$area['TEMPLATE_PAGE_AREA_ORDER']][] = $area["AREA_HEAD_MOBILE"];
                } else {
                    $this->response['AREAS'][$area['TEMPLATE_PAGE_AREA_ORDER']][] = $area["AREA_HEAD"];
                }
                if ($this->tabZones[$area["AREA_ID"]]) {
                    $this->response['AREAS'][$area['TEMPLATE_PAGE_AREA_ORDER']][] = Pelican_Html::comment(
                      "Area ".$area["AREA_ID"]." : ".$area["AREA_LABEL"]
                    );

                    $area2 = array_diff_key($area, $this->exclude);
                    ksort($area2);

                    $this->response['AREAS'][$area['TEMPLATE_PAGE_AREA_ORDER']] = $area2;

                    $avoid = 0;
                    $aBack = array();
                    $isOnglet = false;
                    $nOnglet = 0;
                    $nOngletOriginal = 0;
                    foreach ($this->tabZones[$area["AREA_ID"]] as $idx => $listData) {
                        $data = array();
                        $zoneDataOrigin = $listData[0];

                        if (empty($data)) {
                            $data = $zoneDataOrigin;
                        }

                        /*
                         * Gestion de l'affichage des zones avant la StickyBar, si le traitement est actif
                         *
                         * Le traitement pour le mobile est après l'affichage de la zone
                         */

                        // Affichage des zones dans le cas normal
                        if (true) {

                            // temporaire
                            $data["ZONE_FO_PATH"] = str_replace('pageLayout', 'Layout', $data["ZONE_FO_PATH"]);

                            /*
                             * zones héritables
                             */
                            if ($data["ZONE_TYPE_ID"] == 3) {
                                $savePath = $data["ZONE_FO_PATH"];
                                if (!$data["PAGE_ID"]) {
                                    $data["PAGE_ID"] = $this->_pid;
                                    $data['LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
                                }
                                $data = Pelican_Cache::fetch(
                                  "Frontend/Page/Heritable",
                                  array(
                                    "heritable",
                                    $data["PAGE_ID"],
                                    $data["ZONE_TEMPLATE_ID"],
                                    Pelican::getPreviewVersion(),
                                    $_SESSION[APP]['LANGUE_ID'],
                                  )
                                );
                                $data["ZONE_FO_PATH"] = $savePath;
                                $savePath = "";
                            }

                            $blockOrder = $data['ZONE_TEMPLATE_ORDER'];

                            if ($area['AREA_DROPPABLE']) {
                                $blockOrder = $data['ZONE_ORDER'];
                            }

//                            print "<pre>";
//                            print_r($data);
//                            print "<hr>";
                            if (Pelican::$config["SHOW_DEBUG"]) {
                                $this->response['AREAS'][$area['TEMPLATE_PAGE_AREA_ORDER']]['BLOCS'][$blockOrder] = Pelican_Html::comment(
                                  "Bloc ".$data["ZONE_TEMPLATE_ID"]." : ".$data["ZONE_FO_PATH"]
                                );
                            } else {
                                $this->response['AREAS'][$area['TEMPLATE_PAGE_AREA_ORDER']]['BLOCS'][$blockOrder] = Pelican_Html::comment(
                                  "Bloc ".$data["ZONE_TEMPLATE_ID"]
                                );
                            }
                            $data2 = array_diff_key($data, array_merge($this->exclude, array('AREA_ID' => 0)));
                            $this->response['AREAS'][$area['TEMPLATE_PAGE_AREA_ORDER']]['BLOCS'][$blockOrder] = $data2;

                            if (!empty($data["ZONE_FO_PATH"])) {
                                // plugin
                                $data = Pelican_Layout::identifyPlugins($data);
                                if (empty($data["ZONE_CACHE_TIME"]) || !empty($this->preview)) {
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

                                /*
                                 * temporaire
                                 */
                                if ($this->_ajax) {
                                    $data["ZONE_AJAX"] = true;
                                }
                                if ($this->_iframe) {
                                    $data["ZONE_IFRAME"] = true;
                                }

                                /*
                                 * output
                                 */
                                $this->recapZone[] = $data["ZONE_FO_PATH"];
                                $time0 = microtime(true);

                                // Décompte des tranches enfant à afficher
                                if (isset($nbTranche) && $nbTranche > 0) {
                                    $nbTranche--;

                                    $trancheEnfant++;
                                } else {
                                    // Une fois les tranches enfants récupérés on vide la variable $trancheEnfant
                                    unset($trancheEnfant);
                                }

                                $isTrancheParent = false;
                                if (isset($data['ZONE_LANGUETTE']) && $data['ZONE_LANGUETTE'] == 1) {

                                    // On récupère le nombre de tranche enfant à afficher
                                    // Par défaut c'est 1 tranche.
                                    $nbTranche = 1;
                                    if (isset($data['ZONE_TITRE17']) && !empty($data['ZONE_TITRE17'])) {
                                        $nbTranche = ($data['ZONE_TITRE17']);
                                    }

                                    // On active le faite qu'on soit sur une tranche parent
                                    $isTrancheParent = true;

                                    // On incrémente le compteur des tranches parentes
                                    $trancheParent++;
                                }                                 // Traitement de l'accordeon Item Web et Mobile
                                elseif (isset($data['ZONE_LANGUETTE']) && $data['ZONE_LANGUETTE'] == 2) {

                                    /* Récupere le zone template ID */
                                    $aTemplate = Pelican_Cache::fetch(
                                      "ZoneTemplate",
                                      array(
                                        $data['PAGE_ID'],
                                        $data['ZONE_ID'],
                                        Pelican::getPreviewVersion(),
                                      )
                                    );

                                    if ($data['ZONE_WEB'] == 1 || $data['ZONE_MOBILE'] == 1) {
                                        /*
                                         * Gestion des Items Accordeons Web et Mobile
                                         */
                                        $aToggle = Pelican_Cache::fetch(
                                          "Frontend/Citroen/ZoneMulti",
                                          array(
                                            $data['PAGE_ID'],
                                            $_SESSION[APP]['LANGUE_ID'],
                                            Pelican::getPreviewVersion(),
                                            $aTemplate['ZONE_TEMPLATE_ID'],
                                            'TOGGLE',
                                            $data['AREA_ID'],
                                            $data['ZONE_ORDER'],
                                          )
                                        );

                                        if ($aToggle) {
                                            $maxToggle = sizeof($aToggle);
                                            // traitement effectué que si il + ou = a 2 elements
                                            if ($maxToggle >= 2) {
                                                $bTraitementItemAccordeon = true;
                                                // $traitementItem = true;
                                                $bOrderAccordeon = $aToggle[0]['ZONE_ORDER'];
                                                $Item = 0;
                                                $itemId = 1;
                                            } else {
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
                                } elseif (sizeof(
                                    $aOnglets
                                  ) > 0 && ($data['AREA_ID'].'-'.$data['ZONE_ORDER']) != $aOngletId
                                ) {
                                    $ongletId = $aOngletId.'-'.array_shift($aOnglets);
                                    $this->getOutputZone(
                                      $data,
                                      $cache,
                                      '',
                                      $trancheParent,
                                      '',
                                      false,
                                      $ongletId,
                                      '',
                                      $nbTranche
                                    );
                                } elseif ($iTrancheDansAccordeon) { // cas pour l'accordeon web on affiche pas le data courant
                                    if (!$bMobile) {
                                        $this->getOutputZone(
                                          $data,
                                          $cache,
                                          '',
                                          $trancheParent,
                                          $trancheEnfant,
                                          $isTrancheParent,
                                          '',
                                          $bOrderAccordeon.'_'.$Item
                                        );
                                    } elseif ($bMobile && $_GET['accordeon'] == $bOrderAccordeon.'_'.$Item) {
                                        $this->getOutputZone($data, $cache, '', '', '', false);
                                    }
                                } else {
                                    // Fonctionnement des tranches parent/enfant uniquement pour le mode normal pour l'instant
                                    if (!($_GET['accordeon'] && $bMobile) || in_array(
                                        $data['ZONE_ID'],
                                        array(
                                          Pelican::$config['ZONE']['HEADER'],
                                          Pelican::$config['ZONE']['FOOTER'],
                                        )
                                      )
                                    ) {
                                        $this->getOutputZone(
                                          $data,
                                          $cache,
                                          '',
                                          $trancheParent,
                                          $trancheEnfant,
                                          $isTrancheParent,
                                          '',
                                          '',
                                          '',
                                          $bTraitementStickyBar
                                        );
                                    }
                                }
                                unset($zoneId);
                                $time = microtime(true);
                                Pelican_Log::control(
                                  sprintf(PROFILE_FORMAT_TIME, ($time - $time0)).' : '.$data["ZONE_FO_PATH"],
                                  'generation'
                                );
                            }
                        }

                        /*
                         * Gestion de l'affichage des zones avant la StickyBar, si le traitement est actif
                         * Le traitement pour le web est avant l'affichage de la zone
                         */
                        if ($bTraitementStickyBar) {
                            if ($bMobile) {
                                if ($data['ZONE_ID'] == Pelican::$config['ZONE']['STICKYBAR'] || $data['ZONE_ID'] == Pelican::$config['ZONE']['STICKYBAR_PROMO']) {
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
                                Pelican::$config['ZONE']['RECAPITULATIF_MODELE'],
                              )
                            )) {
                                $this->response['AREAS'][$area['TEMPLATE_PAGE_AREA_ORDER']]['BLOCS'][] = '<div class="body">';
                            }

                            foreach (Pelican::$config['DEPLOYABLE_BLOC'] as $kDeployable => $deployable) {
                                $this->response['BLOCS'][] = '
										<div class="secret" id="deployable_'.$kDeployable.'" name="deployable_'.$kDeployable.'">
										<div class="closer"></div>';
                                if ($deployable == 'PDV') {
                                    $aPDV = Pelican_Cache::fetch(
                                      "Frontend/Page/ZoneTemplate",
                                      array(
                                        $_SESSION[APP]['GLOBAL_PAGE_ID'],
                                        Pelican::$config['ZONE_TEMPLATE_ID']['RECHERCHE_PDV'],
                                        $_SESSION[APP]['GLOBAL_PAGE_VERSION'],
                                        $_SESSION[APP]['LANGUE_ID'],
                                      )
                                    );
                                    $aZoneDeploy = Pelican_Cache::fetch(
                                      "Zone",
                                      array(
                                        Pelican::$config['ZONE']['POINT_DE_VENTE'],
                                      )
                                    );
                                    if ($aPDV && $aZoneDeploy[0]) {
                                        $dataDeploy = array_merge($aPDV, $aZoneDeploy[0]);
                                    }
                                    $dataDeploy['REFERER'] = 'TRANCHE_OUTILS';
                                    $this->getOutputZone($dataDeploy, $cache);
                                } else {
                                    $aZoneDeploy = Pelican_Cache::fetch(
                                      "Zone",
                                      array(
                                        Pelican::$config['ZONE']['FORMULAIRE'],
                                      )
                                    );
                                    $aTab = explode('_', $deployable);
                                    $aFormulaire = Pelican_Cache::fetch(
                                      "Frontend/Citroen/ZoneTemplate",
                                      array(
                                        $aTab[0],
                                        $aTab[1],
                                        Pelican::getPreviewVersion(),
                                        false,
                                        $aTab[2],
                                        $aTab[3],
                                        Pelican::$config['ZONE']['FORMULAIRE'],
                                      )
                                    );

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
                                        if ($aFormulaire['ZONE_TITRE4'] != 'CHOIX') {
                                            $formulaire2 = Pelican_Cache::fetch(
                                              "Frontend/Citroen/Formulaire",
                                              array(
                                                $aFormulaire["ZONE_TITRE3"],
                                                $aFormulaire['ZONE_TITRE4'],
                                                (($bMobile == true) ? 'MOB' : 'WEB'),
                                                $_SESSION[APP]['SITE_ID'],
                                                $_SESSION[APP]['LANGUE_ID'],
                                                '',
                                                '',
                                                $vehicule,
                                              )
                                            );
                                            if (!empty($formulaire2)) {
                                                $formulaire = array_merge($formulaire, $formulaire2);
                                            }
                                        }
                                    }

                                    if (!empty($formulaire)) {
                                        $formulaire['PAGE_VEHICULE'] = $data['PAGE_VEHICULE'];
                                        $formulaire['TRANCHE_VEHICULE'] = $data['ZONE_TITRE2'];
                                    }
                                    $form['FORM_DEPLOYE'] = $formulaire;
                                    $aZoneDeploy[0]['ZONE_ORDER'] = $kDeployable;
                                    if ($form && $aZoneDeploy[0]) {
                                        $dataDeploy = array_merge($form, $aZoneDeploy[0]);
                                    }
                                    $forms = true;
                                    $this->getOutputZone(
                                      $dataDeploy,
                                      $cache,
                                      '',
                                      '',
                                      '',
                                      '',
                                      '',
                                      '',
                                      '',
                                      $bTraitementStickyBar,
                                      $forms
                                    );
                                }

                                $this->response['AREAS'][$area['AREA_ID']]['BLOCS'][] = '
											<span class="popClose"><span>'.t('FERMER').'</span></span>
											</div>';
                                $this->response['AREAS'][$area['TEMPLATE_PAGE_AREA_ORDER']]['BLOCS'][] = Pelican_Html::comment(
                                  "/#deployable_ ".$kDeployable
                                );
                            }
                            if (in_array(
                              $data['ZONE_ID'],
                              array(
                                Pelican::$config['ZONE']['CONTENUS_RECOMMANDES'],
                                Pelican::$config['ZONE']['RECAPITULATIF_MODELE'],
                              )
                            )) {
                                $this->response['AREAS'][$area['TEMPLATE_PAGE_AREA_ORDER']]['BLOCS'][] = '</div>';
                            }
                            unset(Pelican::$config['DEPLOYABLE_BLOC']);
                        }
                    }
                }
                if ($bMobile && $area['AREA_MOBILE'] == 1) {
                    $this->response['BLOCS'][] = $area["AREA_FOOT_MOBILE"];
                } else {
                    $this->response['BLOCS'][] = $area["AREA_FOOT"];
                }
            }
        }
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $data
     *                        __DESC__
     * @param bool     $cache
     *                        (option) __DESC__
     * @param string   $type
     *                        (option) __DESC__
     *
     * @return __TYPE__
     */
    public function getOutputZone($data, $cache = false, $type = "")
    {
        $data2 = array_diff_key($data, array_merge($this->exclude, array('AREA_ID' => 0)));
        ksort($data2);

        $this->response['BLOCS'][] = array(
          'path' => trim($data["ZONE_FO_PATH"]),
          'data' => $data2,
          'cache' => $data["ZONE_CACHE_TIME"],
        );
    }

    /**
     * Récupération de l'objet Vue.
     *
     * @access public
     *
     * @return Pelican_View
     */
    public function getView()
    {
        if ($this->view == null) {
            $this->view = &Pelican_Factory::getInstance('View');
        }

        return $this->view;
    }
}

class Frontoffice_Design_Helper
{
    public static function getModeAffichage($data, $data2, $data3)
    {
    }
}
