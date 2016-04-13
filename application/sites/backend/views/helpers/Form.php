<?php

class Backoffice_Form_Helper {

    /**
     * récupération de la table multi
     *
     */
    public static function getPageZoneMulti($controller, $type = '') {


        $oConnection = Pelican_Db::getInstance();
        $multiValues = array();
        if ($controller->zoneValues['ZONE_DYNAMIQUE']) {
            $aBind[":LANGUE_ID"] = $controller->zoneValues['LANGUE_ID'];
            $aBind[":PAGE_VERSION"] = $controller->zoneValues['PAGE_VERSION'];
            $aBind[":PAGE_ID"] = $controller->zoneValues['PAGE_ID'];
            $aBind[":AREA_ID"] = $controller->zoneValues['AREA_ID'];
            $aBind[":ZONE_ORDER"] = $controller->zoneValues['ZONE_ORDER'];
            $aBind[":PAGE_ZONE_MULTI_TYPE"] = $oConnection->strToBind($type);

            $SQL = "
                SELECT *
                FROM #pref#_page_multi_zone_multi
                WHERE
                LANGUE_ID = :LANGUE_ID
                and PAGE_VERSION = :PAGE_VERSION
                and PAGE_ID = :PAGE_ID
                and AREA_ID = :AREA_ID
                and ZONE_ORDER = :ZONE_ORDER";
            if ($type != '') {
                $SQL .= " and PAGE_ZONE_MULTI_TYPE = :PAGE_ZONE_MULTI_TYPE";
            }

            $multiValues = $oConnection->queryTab($SQL, $aBind);
        } else if ($controller->zoneValues['PERSO']) {
            $sType = ($type != "") ? $type : $controller->zoneValues['PERSO']['MULTI_NAME'];
            $multiValues = $controller->zoneValues['PERSO'][$sType];
        } else {
            $aBind[":LANGUE_ID"] = $controller->zoneValues['LANGUE_ID'];
            $aBind[":PAGE_VERSION"] = $controller->zoneValues['PAGE_VERSION'];
            $aBind[":PAGE_ID"] = $controller->zoneValues['PAGE_ID'];
            $aBind[":ZONE_TEMPLATE_ID"] = $controller->zoneValues['ZONE_TEMPLATE_ID'];
            $aBind[":PAGE_ZONE_MULTI_TYPE"] = $oConnection->strToBind($type);

            $SQL = "
                SELECT *
                FROM #pref#_page_zone_multi
                WHERE
                LANGUE_ID = :LANGUE_ID
                and PAGE_VERSION = :PAGE_VERSION
                and PAGE_ID = :PAGE_ID
                and ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID";
            if ($type != '') {
                $SQL .= " and PAGE_ZONE_MULTI_TYPE = :PAGE_ZONE_MULTI_TYPE";
            }

            $multiValues = $oConnection->queryTab($SQL, $aBind);
        }

        if ($controller->zoneValues['isPerso'] == '1' && is_array($multiValues) && !empty($multiValues)) {
            $aPersoResults = array();
            foreach ($multiValues as $keyPerso => $valuePerso) {
                if ($valuePerso["multi_display"] == '1') {
                    $aPersoResults[] = $valuePerso;
                }
            }
            $multiValues = $aPersoResults;
        }

        return $multiValues;
    }

    /**
     * récupération de la table multi
     *
     */
    public static function getPageZoneMultiHmvc($values, $type) {
        $oConnection = Pelican_Db::getInstance();

        $aBind[":LANGUE_ID"] = $values['LANGUE_ID'];
        $aBind[":PAGE_VERSION"] = $values['PAGE_VERSION'];
        $aBind[":PAGE_ID"] = $values['PAGE_ID'];
        $aBind[":ZONE_TEMPLATE_ID"] = $values['ZONE_TEMPLATE_ID'];
        $aBind[":PAGE_ZONE_MULTI_ID"] = $values['PAGE_ZONE_MULTI_ID'];
        $aBind[":PAGE_ZONE_MULTI_TYPE"] = $oConnection->strToBind($type);

        $SQL = "
                SELECT *
                FROM #pref#_page_zone_multi_multi
                WHERE
                LANGUE_ID = :LANGUE_ID
                and PAGE_VERSION = :PAGE_VERSION
                and PAGE_ID = :PAGE_ID
                and ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
                and PAGE_ZONE_MULTI_ID = :PAGE_ZONE_MULTI_ID ";
        if ($type != '') {
            $SQL .= " and PAGE_ZONE_MULTI_TYPE = :PAGE_ZONE_MULTI_TYPE";
        }
        $multiValues = $oConnection->queryTab($SQL, $aBind);
        return $multiValues;
    }

    /**
     * Suppression des enregistrement des multi
     *
     * @param $type string : type de multi
     */
    public static function deletePageZoneMulti($type = '') {
        $oConnection = Pelican_Db::getInstance();

        if (Pelican_Db::$values['ZONE_DYNAMIQUE']) {
            $aBind[":LANGUE_ID"] = Pelican_Db::$values['LANGUE_ID'];
            $aBind[":PAGE_VERSION"] = Pelican_Db::$values['PAGE_VERSION'];
            $aBind[":PAGE_ID"] = Pelican_Db::$values['PAGE_ID'];
            $aBind[":AREA_ID"] = Pelican_Db::$values['AREA_ID'];
            $aBind[":ZONE_ORDER"] = Pelican_Db::$values['ZONE_ORDER'];
            $aBind[":PAGE_ZONE_MULTI_TYPE"] = $oConnection->strToBind($type);

            $sqlDelete = "DELETE FROM #pref#_page_multi_zone_multi
                                    WHERE
                                    LANGUE_ID = :LANGUE_ID
                                    and PAGE_VERSION = :PAGE_VERSION
                                    and PAGE_ID = :PAGE_ID
                                    and AREA_ID = :AREA_ID
                                    and ZONE_ORDER = :ZONE_ORDER
                                    and PAGE_ZONE_MULTI_TYPE = :PAGE_ZONE_MULTI_TYPE
                                    ";
            $oConnection->query($sqlDelete, $aBind);
        } else {
            $aBind[":LANGUE_ID"] = Pelican_Db::$values['LANGUE_ID'];
            $aBind[":PAGE_VERSION"] = Pelican_Db::$values['PAGE_VERSION'];
            $aBind[":PAGE_ID"] = Pelican_Db::$values['PAGE_ID'];
            $aBind[":ZONE_TEMPLATE_ID"] = Pelican_Db::$values['ZONE_TEMPLATE_ID'];
            $aBind[":PAGE_ZONE_MULTI_TYPE"] = $oConnection->strToBind($type);

            $sqlDelete = "DELETE FROM #pref#_page_zone_multi
                                    WHERE
                                    LANGUE_ID = :LANGUE_ID
                                    and PAGE_VERSION = :PAGE_VERSION
                                    and PAGE_ID = :PAGE_ID
                                    and ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
                                    and PAGE_ZONE_MULTI_TYPE = :PAGE_ZONE_MULTI_TYPE
                                    ";
            $oConnection->query($sqlDelete, $aBind);
        }
    }

    /**
     * Suppression des enregistrement des multi
     *
     * @param $type string : type de multi
     */
    public static function deleteContentZoneMulti($type = '') {
        $oConnection = Pelican_Db::getInstance();

        $aBind[":CONTENT_ID"] = Pelican_Db::$values['CONTENT_ID'];
        $aBind[":LANGUE_ID"] = Pelican_Db::$values['LANGUE_ID'];
        $aBind[":CONTENT_VERSION"] = Pelican_Db::$values['CONTENT_VERSION'];
        $aBind[":CONTENT_ZONE_MULTI_TYPE"] = $oConnection->strToBind($type);

        $sqlDelete = "DELETE FROM #pref#_content_zone_multi
                                WHERE
                                LANGUE_ID = :LANGUE_ID
                                and CONTENT_VERSION = :CONTENT_VERSION
                                and CONTENT_ID = :CONTENT_ID
                                and CONTENT_ZONE_MULTI_TYPE = :CONTENT_ZONE_MULTI_TYPE
                                ";

        $oConnection->query($sqlDelete, $aBind);
    }

    /**
     * Suppression des enregistrement des multi
     *
     * @param $type string : type de multi
     */
    public static function deletePageZoneMultiMulti($values, $type = '') {
        $oConnection = Pelican_Db::getInstance();

        $aBind[":LANGUE_ID"] = Pelican_Db::$values['LANGUE_ID'];
        $aBind[":PAGE_VERSION"] = Pelican_Db::$values['PAGE_VERSION'];
        $aBind[":PAGE_ID"] = Pelican_Db::$values['PAGE_ID'];
        $aBind[":ZONE_TEMPLATE_ID"] = Pelican_Db::$values['ZONE_TEMPLATE_ID'];
        $aBind[":PAGE_ZONE_MULTI_TYPE"] = $oConnection->strToBind($type);
        $aBind[":PAGE_ZONE_MULTI_ID"] = Pelican_Db::$values['PAGE_ZONE_MULTI_ID'];

        $sqlDelete = "DELETE FROM #pref#_page_zone_multi_multi
                                WHERE
                                LANGUE_ID = :LANGUE_ID
                                and PAGE_VERSION = :PAGE_VERSION
                                and PAGE_ID = :PAGE_ID
                                and ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
                                and PAGE_ZONE_MULTI_TYPE = :PAGE_ZONE_MULTI_TYPE
                                and PAGE_ZONE_MULTI_ID = :PAGE_ZONE_MULTI_ID
                                ";
        $oConnection->query($sqlDelete, $aBind);
    }

    /**
     * Ajout d'un élément multi
     */
    public static function addPageZoneMulti($values) {


        $oConnection = Pelican_Db::getInstance();

        Pelican_Db::$values["LANGUE_ID"] = $values['LANGUE_ID'];
        Pelican_Db::$values["PAGE_VERSION"] = $values['PAGE_VERSION'];
        Pelican_Db::$values["PAGE_ID"] = $values['PAGE_ID'];

        if ($values['ZONE_DYNAMIQUE']) {
            Pelican_Db::$values["AREA_ID"] = $values['AREA_ID'];
            Pelican_Db::$values["ZONE_ORDER"] = $values['ZONE_ORDER'];

            $oConnection->insertQuery('#pref#_page_multi_zone_multi');
        } else {
            Pelican_Db::$values["ZONE_TEMPLATE_ID"] = $values['ZONE_TEMPLATE_ID'];
            $oConnection->insertQuery('#pref#_page_zone_multi');
        }
    }

    /**
     * Ajout d'un élément multi
     */
    public static function addContentZoneMulti($values) {
        $oConnection = Pelican_Db::getInstance();

        Pelican_Db::$values["LANGUE_ID"] = $_SESSION[APP]['LANGUE_ID'];
        Pelican_Db::$values["CONTENT_VERSION"] = $values['CONTENT_VERSION'];
        Pelican_Db::$values["CONTENT_ID"] = $values['CONTENT_ID'];
        Pelican_Db::$values["CONTENT_ZONE_ID"] = $values['CONTENT_ZONE_ID'];

        $oConnection->insertQuery('#pref#_content_zone_multi');
    }

    /**
     * Ajout d'un élément multi dans un multi
     */
    public static function addPageZoneMultiMulti($values) {

        $oConnection = Pelican_Db::getInstance();

        Pelican_Db::$values["LANGUE_ID"] = $values['LANGUE_ID'];
        Pelican_Db::$values["PAGE_VERSION"] = $values['PAGE_VERSION'];
        Pelican_Db::$values["PAGE_ID"] = $values['PAGE_ID'];
        Pelican_Db::$values["ZONE_TEMPLATE_ID"] = $values['ZONE_TEMPLATE_ID'];

        $oConnection->insertQuery('#pref#_page_zone_multi_multi');
    }

    /**
     * Création des éléments liés à la mutualisation
     *
     * @param Pelican_Form $oForm
     */
    public static function sharingForm(&$oForm) {
        
    }

    /**
     * Création des éléments liés à la gestion d'un système de marquage (cyberestat, xiti etc...)
     *
     * @param Pelican_Form $oForm
     */
    public static function cybertagForm(&$oForm, $id, $aValues, $readO, $param = "cid", $section = "contenu") {
        if (!valueExists($_GET, "cyber")) {
            include_once (Pelican::$config['LIB_ROOT'] . Pelican::$config['LIB_FRONT'] . "/cybertag.lib.php");
            $rubrique = "";
            if ($section) {
                return getTag($oForm, $param . "=" . $id, ($aValues['SITE_ID'] ? $aValues['SITE_ID'] : $_SESSION[APP]['SITE_ID']), $readO, $section, $rubrique, $param);
            }
        }
    }

    /**
     * Création des formulaires liés à l'utilisation du workflow
     *
     * @param Pelican_Form $oForm
     */
    public static function workflowForm(&$oForm, $aValues, $bVersioning, $field_id, $sFieldWorkflow, $noResetVersion, $id, $aLastValues, $iLanguageId, $readO, $hideUsers) {
        $form = '';

        if (!$noResetVersion) {
            $aValues[$sFieldWorkflow . "_VERSION"] = $aLastValues[$sFieldWorkflow . "_VERSION"];
            $aValues["STATE_ID"] = $aLastValues["STATE_ID"];
            $aValues[$sFieldWorkflow . "_STATUS"] = $aLastValues[$sFieldWorkflow . "_STATUS"];
        }

        /**
         * * Contrôle de présence des champs de versioning
         */
        if ($bVersioning) {
            $params['id'] = $id;
            $params['iLanguageId'] = $iLanguageId;
            $params['aValues'] = $aValues;
            self::workflowFieldExists($sFieldWorkflow . "_VERSION", false, $params);
            self::workflowFieldExists($sFieldWorkflow . "_CREATION_USER", false, $params);
            self::workflowFieldExists($sFieldWorkflow . "_CREATION_DATE", true, $params);
            self::workflowFieldExists($sFieldWorkflow . "_VERSION_CREATION_DATE", true, $params);
            self::workflowFieldExists($sFieldWorkflow . "_VERSION_CREATION_USER", false, $params);
            self::workflowFieldExists($sFieldWorkflow . "_PUBLICATION_DATE", true, $params);
        }
        if (!$aValues[$sFieldWorkflow . "_CREATION_USER"]) {
            $aValues[$sFieldWorkflow . "_CREATION_USER"] = $_SESSION[APP]["user"]["id"];
        }
        /* if ($_SESSION[APP]["user"]["main"] && !$hideUsers) {
          $aUsers = getComboValuesFromCache("Backend/User", $_SESSION[APP]['SITE_ID']);
          $form .= $oForm->showSeparator();
          // Construction du tableau pour la présélection des auteurs
          $arrayCreationUsers = explode(',', str_replace('#', '', str_replace('##', ',', $aValues[$sFieldWorkflow . "_CREATION_USER"])));
          $form .= $oForm->createComboFromList($sFieldWorkflow . "_CREATION_USER", t("Créateur(s)"), $aUsers, $arrayCreationUsers, false, $readO, "7", true);
          } else { */
        $form .= $oForm->createHidden($sFieldWorkflow . "_CREATION_USER", $aValues[$sFieldWorkflow . "_CREATION_USER"]);
        // }
        if (!valueExists($oForm->_inputName, $sFieldWorkflow . "_CREATION_DATE")) {
            $form .= $oForm->createHidden($sFieldWorkflow . "_CREATION_DATE", $aValues[$sFieldWorkflow . "_CREATION_DATE"]);
        }
        $form .= $oForm->createHidden($sFieldWorkflow . "_VERSION_CREATION_DATE", $aValues[$sFieldWorkflow . "_VERSION_CREATION_DATE"]);
        $form .= $oForm->createHidden($sFieldWorkflow . "_VERSION_CREATION_USER", $aValues[$sFieldWorkflow . "_VERSION_CREATION_USER"]);
        $form .= $oForm->createHidden("form_workflow", $sFieldWorkflow);
        if (!valueExists($oForm->_inputName, $sFieldWorkflow . "_PUBLICATION_DATE")) {
            $form .= $oForm->createHidden($sFieldWorkflow . "_PUBLICATION_DATE", $aValues[$sFieldWorkflow . "_PUBLICATION_DATE"]);
        }
        $form .= $oForm->createHidden($sFieldWorkflow . "_STATUS", $aValues[$sFieldWorkflow . "_STATUS"]);
        if ($field_id != "STATE_ID") {
            // if (!$aValues["STATE_ID"]) {
            if (!valueExists($aValues, "STATE_ID") || $aValues["STATE_ID"] == @Pelican::$config["PUBLICATION_STATE"]) {
                $aValues["STATE_ID"] = Pelican::$config["DEFAULT_STATE"];
            }
            $form .= $oForm->createHidden("STATE_ID", $aValues["STATE_ID"]);
        }
        $form .= $oForm->createHidden($sFieldWorkflow . "_CURRENT_VERSION", $aValues[$sFieldWorkflow . "_CURRENT_VERSION"]);
        $form .= $oForm->createHidden($sFieldWorkflow . "_DRAFT_VERSION", $aValues[$sFieldWorkflow . "_DRAFT_VERSION"]);
        if (!$oForm->_inputName[$sFieldWorkflow . "_VERSION"]) {
            $form .= $oForm->createHidden($sFieldWorkflow . "_VERSION", $aValues[$sFieldWorkflow . "_VERSION"]);
        }

        $return = $oForm->output($form);
        return $return;
    }

    /**
     * Contrôle d'existence de champs obligatoires dans les valeurs retournées
     *
     * @param string $var valeur de la variable
     * @param boolean $isDate True si c'est une date
     * @return void
     */
    public static function workflowFieldExists($var, $isDate = false, $params) {
        if ($params['id'] != Pelican::$config["DATABASE_INSERT_ID"] && !$params['iLanguageId']) {
            if ($params['aValues']) {
                if (!array_key_exists($var, $params['aValues'])) {
                    $msg = "ATTENTION, il manque le champ " . $var . " dans la requête du formulaire!";
                    if ($isDate) {
                        $msg .= "\r\nC'est un champ date, utiliser la méthode dateSqlToString pour le formatter";
                    }
                    //echo ("SI LE FORMULAIRE N'EST PAS CONCERNE PAR LE VERSIONING METTRE \$versioning=false; EN DEBUT DE SCRIPT");
                } elseif ($params['aValues'][$var] && $isDate) {
                    $aDate = explode("/", $params['aValues'][$var]);
                    if (count($aDate) < 3) {
                        
                    }
                }
            }
        }
    }

    /**
     * Création des éléments de gestion des urls claires
     *
     * @param Pelican_Form $oForm
     */
    public static function rewritingForm(&$oForm, $id, $sFieldWorkflow, $values, $readO) {
        $form = '';
        $oConnection = Pelican_Db::getInstance();
        $aBind[":ID"] = $id;
        $aBind[":TYPE"] = $oConnection->strToBind($sFieldWorkflow);
        $aBind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
        $aBind[":LANGUE_ID"] = $_SESSION[APP]['LANGUE_ID'];
        $result = $oConnection->queryTab("select REWRITE_URL, REWRITE_RESPONSE from #pref#_rewrite where
					SITE_ID=:SITE_ID
					AND LANGUE_ID=:LANGUE_ID
					AND REWRITE_TYPE = :TYPE
					AND REWRITE_ID=:ID
					order by REWRITE_ORDER", $aBind);
        if ($result) {
            foreach ($result as $rewrite) {
                $aRewrite[($rewrite['REWRITE_RESPONSE'] ? $rewrite['REWRITE_RESPONSE'] : '200')][] = $rewrite["REWRITE_URL"];
            }
        }
        
        //CPW-3600 
        $form .= $oForm->showSeparator();
        $form .= $oForm->createInput($sFieldWorkflow . "_OG_TITLE", t('OG title'), 150, "", false, $values[$sFieldWorkflow . "_OG_TITLE"], $readO, 100);
        $form .= $oForm->createTextArea($sFieldWorkflow . "_OG_DESC", t('OG description'), false, $values[$sFieldWorkflow . "_OG_DESC"], 256, $readO, 3, 100);
        $form .= $oForm->createMedia($sFieldWorkflow . "_OG_IMAGE", t('OG image'), false, 'image', '', $values[$sFieldWorkflow . "_OG_IMAGE"], $readO, true, false);
        $form .= $oForm->createInput($sFieldWorkflow . "_OG_URL", t("OG url"), 255, "internallink", false, $values[$sFieldWorkflow . "_OG_URL"], $readO, 100, false, "", "text");
        //CPW-3600
        
        $form .= $oForm->showSeparator();
        $aValues = Pelican:: $config['ROBOTS_SEO'];
        $values[$sFieldWorkflow . "_META_ROBOTS"] = ($values[$sFieldWorkflow . "_META_ROBOTS"] == "") ? Pelican:: $config['ROBOTS_SEO_DEFAULT'] : $values[$sFieldWorkflow . "_META_ROBOTS"];
        $form .= $oForm->createComboFromList($sFieldWorkflow . "_META_ROBOTS", t('Robots seo'), $aValues, $values[$sFieldWorkflow . "_META_ROBOTS"], false, $readO, "1", false, "", false);
        $form .= $oForm->createInput($sFieldWorkflow . "_PRIORITY", t('PAGE_PRIORITY'), 3, "", false, $values[$sFieldWorkflow . "_PRIORITY"], $readO, 3);
        
        $form .= $oForm->createInput($sFieldWorkflow . "_CLEAR_URL", t('URL claire'), 100, "", false, $values[$sFieldWorkflow . "_CLEAR_URL"], $readO, 100);
        //$form .= $oForm->createTextArea("REWRITE_ALIAS_URL", t('Raccourcis/Alias'), false, $aRewrite[200], "", $readO, 3, 100);
        $form .= $oForm->createInput($sFieldWorkflow . "_META_URL_CANONIQUE", t("Url canonique"), 255, "internallink", false, $values[$sFieldWorkflow . "_META_URL_CANONIQUE"], $readO, 100, false, "", "text");
        $form .= $oForm->createTextArea("REWRITE_REDIRECT_URL", t('Redirections'), false, $aRewrite[301], "", $readO, 3, 100);
        
        // CPW-3893 : Intégration de balise hreflang
        $result_href = Pelican_Cache::fetch("Request/Hreflang",array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            $id
        ));
        $form .= $oForm->createTextArea("HREFLANG_TEXT", t('Hreflang'), false, $result_href['HREFLANG_TEXT'], "", $readO, 3, 100);

        
        $oForm->createJS("if (obj." . $sFieldWorkflow . "_PRIORITY.value && obj." . $sFieldWorkflow . "_PRIORITY.value.match('^(0\.[0-9]$|1\.0)$') == null ){
          alert('".t('PAGE_PRIORITY_NO_VALID')."');
          $('#FIELD_BLANKS').val(1);
        } ");

        $return = $oForm->output($form);
        return $return;
    }

    public static function searchForm(&$oForm, $sFieldWorkflow, $aValues) {
        $form = $oForm->createHidden($sFieldWorkflow . "_KEYWORD", $aValues[$sFieldWorkflow . "_KEYWORD"]);
        return $oForm->output($form);
    }

    /* Construction du formulaire BO colonne 1à 4 et +
     * @param array $controller
     * @return string $return
     */

    public static function getForm($fromController, $controller) {
        switch ($fromController) {

            case "Cms_Page_Citroen_1Colonne":
                $return .= self::getFormCommunStart($controller);
                $return .= self::getFormMultiColonneUnique($controller, false, false, 'cinemascope', '1colonne');
                $return .= self::getFormCommunEnd($controller, 3, '1Colonne');

                break;

            case "Cms_Page_Citroen_1ColonneTexte":
                $return .= self::getFormCommunStart($controller);
                $return .= self::getFormMultiColonneUnique($controller, true, false);
                $return .= self::getFormCommunEnd($controller);

                break;
            case "Cms_Page_Citroen_2Colonnes":

                $return .= self::getFormCommunStart($controller);
                $ValueAff = (empty($controller->zoneValues['ZONE_PARAMETERS'])) ? 1 : $controller->zoneValues['ZONE_PARAMETERS'];
                $return .= $controller->oForm->createRadioFromList($controller->multi . "ZONE_PARAMETERS", t('AFFICHAGE_LIEN_CTA'), array(1 => "Affichage Lien", 2 => "Affichage CTA"), $ValueAff, false, $controller->readO, 'h', false, 'onclick="Change_Link_Cta_2Colonne_'.$controller->multi.'()"');
                $return .= self::getFormMultiColonneSimple($controller, 2, false);

                $return .= self::getFormCommunEnd($controller);
                
                $return .= '
                    <script type="text/javascript">
                    
                        function Change_Link_Cta_2Colonne_'.$controller->multi.'(){
                        
                            var ValueAff = $("input[name='.$controller->multi.'ZONE_PARAMETERS]:checked").val();
                            var multiColonne = ".'.$controller->multi.'ADDCOLFORM";
                                
                            if(ValueAff == 1){ // cas ou lien on cache les cta
                                
                                for (i = 0; i < 2; i++) { 
                                    $(multiColonne+i+"_choix_cta").hide();
                                    $(multiColonne+i+"_choix_link").show();
                                }
                                
                            }else if(ValueAff == 2){ // cas ou lien on cache les liens
                            
                                for (i = 0; i < 2; i++) { 
                                    $(multiColonne+i+"_choix_link").hide();
                                    $(multiColonne+i+"_choix_cta").show();
                                }
                            }
                        }
                        
                        Change_Link_Cta_2Colonne_'.$controller->multi.'();
                        
                        $("input[name='.$controller->multi.'ADDCOLFORM][type=button]").click(function (){
                            Change_Link_Cta_2Colonne_'.$controller->multi.'();
                        });
                        
                    </script>
                ';

                break;
            case "Cms_Page_Citroen_2ColonnesMixte":

                $return .= self::getFormCommunStart($controller, true);
                $return .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID6", t('VIGNETTE_GALLERY'), false, "image", "", $controller->zoneValues["MEDIA_ID6"], $controller->readO, true, false, "16_9");


                $return .= $controller->oForm->createMultiHmvc($controller->multi . "ADDVISUELFORM", t('VISUEL_GALLERIE'), array(
                    "path" => Pelican::$config['APPLICATION_VIEW_HELPERS'] . "/Form.php",
                    "class" => "Backoffice_Form_Helper",
                    "method" => "newVisuelForm"
                        ), self::getPageZoneMultiValues($controller, 'VISUELFORM'), $controller->multi . "ADDVISUELFORM", $controller->readO, '', true, true, $controller->multi . "ADDVISUELFORM");
                $return .= $controller->oForm->showSeparator("formSep");
                $return .= self::getFormMultiColonneUnique($controller, false, true, '16_9');
                $return .= self::getFormCommunEnd($controller, 3);

                break;
            case "Cms_Page_Citroen_2ColonnesMediaDroiteAss":

                $return .= self::getFormCommunStart($controller);
                $return .= self::getFormMultiColonneUnique($controller, false, false, '16_9');
                $return .= self::getAssistance($controller);
                $return .= self::getFormCommunEnd($controller, 2);
                $return .= $controller->oForm->createJS("

            var image = document.getElementById('div" . $controller->multi . "MEDIA_ID');
            var video = document.getElementById('div" . $controller->multi . "MEDIA_ID2');
            
            if(image.innerHTML == '' && video.innerHTML == ''){
				if($('#form_button').val() =='state_5'){
						var tiltebo = $('#PAGE_TITLE_BO').val();
						if (tiltebo.indexOf('diff') !== -1) {
							return true;
						}
				}else{
					alert('" . t('VISUEL_OU_VIDEO', 'js') . "'); 
					$('#FIELD_BLANKS').val(1);
				}
            }
 
                    ");

                break;
            case "Cms_Page_Citroen_3Colonnes":

                $return .= self::getFormCommunStart($controller);
                $ValueAff = (empty($controller->zoneValues['ZONE_PARAMETERS'])) ? 1 : $controller->zoneValues['ZONE_PARAMETERS'];
                $return .= $controller->oForm->createRadioFromList($controller->multi . "ZONE_PARAMETERS", t('AFFICHAGE_LIEN_CTA'), array(1 => "Affichage Lien", 2 => "Affichage CTA"), $ValueAff, false, $controller->readO, 'h', false, 'onclick="Change_Link_Cta_3Colonne_'.$controller->multi.'()"');
                $return .= self::getFormMultiColonneSimple($controller, 12, false);
                $return .= self::getFormCommunEnd($controller);
                
                $return .= '
                    <script type="text/javascript">
                    
                        function Change_Link_Cta_3Colonne_'.$controller->multi.'(){
                        
                            var ValueAff = $("input[name='.$controller->multi.'ZONE_PARAMETERS]:checked").val();
                            var multiColonne = ".'.$controller->multi.'ADDCOLFORM";
                                
                            if(ValueAff == 1){ // cas ou lien on cache les cta
                                
                                for (i = 0; i < 12; i++) { 
                                    $(multiColonne+i+"_choix_cta").hide();
                                    $(multiColonne+i+"_choix_link").show();
                                }
                                
                            }else if(ValueAff == 2){ // cas ou lien on cache les liens
                            
                                for (i = 0; i < 12; i++) { 
                                    $(multiColonne+i+"_choix_link").hide();
                                    $(multiColonne+i+"_choix_cta").show();
                                }
                            }
                        }
                        
                        Change_Link_Cta_3Colonne_'.$controller->multi.'();
                        
                        $("input[name='.$controller->multi.'ADDCOLFORM][type=button]").click(function (){
                            Change_Link_Cta_3Colonne_'.$controller->multi.'();
                        });
                        
                    </script>
                ';
                
                break;
            case "Cms_Page_Citroen_4ColonnesPlus":

                $return .= self::getFormCommunStart($controller);
                $ValueAff = (empty($controller->zoneValues['ZONE_PARAMETERS'])) ? 1 : $controller->zoneValues['ZONE_PARAMETERS'];
                $return .= $controller->oForm->createRadioFromList($controller->multi . "ZONE_PARAMETERS", t('AFFICHAGE_LIEN_CTA'), array(1 => "Affichage Lien", 2 => "Affichage CTA"), $ValueAff, false, $controller->readO, 'h', false, 'onclick="Change_Link_Cta_4Colonne_'.$controller->multi.'()"');
                $return .= self::getFormMultiColonneSimple($controller, 8, true);
                $return .= self::getFormCommunEnd($controller);
                
                $return .= '
                    <script type="text/javascript">
                    
                        function Change_Link_Cta_4Colonne_'.$controller->multi.'(){
                        
                            var ValueAff = $("input[name='.$controller->multi.'ZONE_PARAMETERS]:checked").val();
                            var multiColonne = ".'.$controller->multi.'ADDCOLFORM";
                                
                            if(ValueAff == 1){ // cas ou lien on cache les cta
                                
                                for (i = 0; i < 8; i++) { 
                                	$(multiColonne+i+"_choix_cta").hide();
                                    $(multiColonne+i+"_choix_link").show();
                                }
                                
                            }else if(ValueAff == 2){ // cas ou lien on cache les liens
                            
                                for (i = 0; i < 8; i++) { 
                                    $(multiColonne+i+"_choix_link").hide();
                                    $(multiColonne+i+"_choix_cta").show();
                                }
                            }
                        }
                        
                        Change_Link_Cta_4Colonne_'.$controller->multi.'();
                        
                        $("input[name='.$controller->multi.'ADDCOLFORM][type=button]").click(function (){
                            Change_Link_Cta_4Colonne_'.$controller->multi.'();
                        });
                        
                    </script>
                ';
                
                break;
            case "Cms_Page_Citroen_2ColonnesMixtePortrait":
                $return .= self::getFormCommunPortraitSuperposition($controller, 2, 'carre', 'carre');
                break;
            case "Cms_Page_Citroen_SuperpositionADroite":              
                $return .= self::getFormCommunPortraitSuperposition($controller, 2, 'cinemascope', '16_9');
                break;
        }
        return $return;
    }

    public static function newVisuelForm($oForm, $values, $readO, $multi) {
        $return .= $oForm->createMedia($multi . "MEDIA_ID", t('VISUEL'), true, "image", "", $values["MEDIA_ID"], $readO, true, false);

        return $return;
    }

    /* Champ commun en début de formulaire
     * @param array $controller
     * @return string $return
     */

    public static function getFormCommunStart($controller, $titreColonne = false) {
        $return .= self::getFormAffichage($controller);
        $return .= self::getFormModeAffichage($controller);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE3", t('TITRE'), 255, "", false, $controller->zoneValues["ZONE_TITRE3"], $controller->readO, 100);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE4", t('SOUS_TITRE'), 255, "", false, $controller->zoneValues["ZONE_TITRE4"], $controller->readO, 100);
        if ($titreColonne) {
            $return .= $controller->oForm->createTextArea($controller->multi . "ZONE_TEXTE", t('TITRE_COL'), false, $controller->zoneValues["ZONE_TEXTE"], "", $controller->readO, 2, 100, false, "", false);
        } else {
            $return .= $controller->oForm->createTextArea($controller->multi . "ZONE_TEXTE", t('CHAPEAU'), false, $controller->zoneValues["ZONE_TEXTE"], "", $controller->readO, 2, 100, false, "", false);
        }
        return $return;
    }
    
    /* Champ commun tranche avec différence pour
     *  Cms_Page_Citroen_2ColonnesMixtePortrait, Cms_Page_Citroen_SuperpositionADroite
     * @param array $controller
     * @return string $return
     */
    public static function getFormCommunPortraitSuperposition($controller, $nbCta, $sizeVisuel1, $sizeVisuel2) {
        $return .= self::getFormAffichage($controller);
        $return .= self::getFormModeAffichage($controller); 
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE3", t('TITRE'), 255, "", true, $controller->zoneValues["ZONE_TITRE3"], $controller->readO, 100);                
        $return .= $controller->oForm->createTextArea($controller->multi . "ZONE_TEXTE", t('TEXTE'), true, $controller->zoneValues["ZONE_TEXTE"], "", $controller->readO, 2, 100, false, "", false);
        $return .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID", t('MEDIA_WEB'), true, "image", "", $controller->zoneValues["MEDIA_ID"], $controller->readO, true, false, $sizeVisuel1);
        $return .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID2", t('MEDIA_MOBILE'), true, "image", "", $controller->zoneValues["MEDIA_ID2"], $controller->readO, true, false, $sizeVisuel2);
        $aRadio = Pelican::$config['TRANCHE_COL']["GAUCHE_DROITE"];
        if ($controller->zoneValues["ZONE_TITRE4"] == "") {
            $controller->zoneValues["ZONE_TITRE4"] = 1;
        }
        $return .= $controller->oForm->createRadioFromList($controller->multi . "ZONE_TITRE4", t('POSITION_IMG'), $aRadio, $controller->zoneValues["ZONE_TITRE4"]);
        $return .= self::getCta($controller, $nbCta);
        
        return $return;
    }

    /* Champ spécifique multi colonne
     * @param obj
     * @param int
     * @param bool
     * @return string $return
     */

    public static function getFormMultiColonneSimple($controller, $nbCol = "", $modeNbLigne = false) {
        //affichage du nombre de ligne pour la tranche 4 colonnes
        if ($modeNbLigne == true) {
            $aMode = Pelican::$config['TRANCHE_COL']["MODE_AFF_LIGNE"];

            $return .= $controller->oForm->createComboFromList($controller->multi . "ZONE_TITRE11", t("MODE_AFFICHAGE"), $aMode, $controller->zoneValues["ZONE_TITRE11"], true, $controller->readO);
        }

        $multiValues = self::getPageZoneMulti($controller, 'ADDCOLFORM');
        
        $return .= $controller->oForm->createMultiHmvc($controller->multi . "ADDCOLFORM", t('ADD_FORM_COL'), array(
            "path" => Pelican::$config['APPLICATION_VIEW_HELPERS'] . "/Form.php",
            "class" => "Backoffice_Form_Helper",
            "method" => "newColAddForm"
                ), $multiValues, $controller->multi . "ADDCOLFORM", $controller->readO, $nbCol, true, true, $controller->multi . "ADDCOLFORM");


        return $return;
    }

    public static function newColAddForm($oForm, $values, $readO, $multi) {
        $oConnection = Pelican_Db::getInstance();
        
        $aBind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];

        $sql = 'SELECT
                    BARRE_OUTILS_ID ID,
                    BARRE_OUTILS_LABEL LIB
                FROM 
                    #pref#_barre_outils
                WHERE
                    BARRE_OUTILS_MODE_OUVERTURE IN (1,2)
                GROUP BY SITE_ID, LANGUE_ID, ID
                HAVING 
                    SITE_ID = :SITE_ID 
                AND LANGUE_ID = :LANGUE_ID';
        $val = $oConnection->queryTab($sql, $aBind);

        foreach ($val as $outil) {
            $aDataOutilWeb[$outil['ID']] = $outil['LIB'];
        }
        
        $aDataValues = Pelican::$config['TRANCHE_COL']["BLANK_SELF"];
        if ($values["PAGE_ZONE_MULTI_VALUE"] == "") {
            $values["PAGE_ZONE_MULTI_VALUE"] = 'SELF';
        }
        
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_LABEL", t('TITRE_COL'), 255, "", false, $values["PAGE_ZONE_MULTI_LABEL"], $readO, 100);
        $return .= $oForm->createMedia($multi . "MEDIA_ID", t('MEDIA'), true, "image", "", $values["MEDIA_ID"], $readO, true, false, 'cinemascope');
        $return .= $oForm->createEditor($multi . "PAGE_ZONE_MULTI_TEXT", t('TEXTE'), true, $values["PAGE_ZONE_MULTI_TEXT"], $readO, true, "", 650, 150);
        
        // Lien 
        $return .= '<tr class="' . $multi . 'choix_link"><td class="formlib">' . t('LIBELLE_lIEN') . '</td><td class="formval">' . $oForm->createInput($multi . "PAGE_ZONE_MULTI_LABEL2", t('LIBELLE_lIEN'), 255, "", false, $values["PAGE_ZONE_MULTI_LABEL2"], $readO, 100, true) . '</td></tr>';
        $return .= '<tr class="' . $multi . 'choix_link"><td class="formlib">' . t('URL_MOB') . '</td><td class="formval">' . $oForm->createInput($multi . "PAGE_ZONE_MULTI_URL", t('URL_MOB'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL"], $readO, 100, true) . '</td></tr>';
        $return .= '<tr class="' . $multi . 'choix_link"><td class="formlib">' . t('URL_WEB') . '</td><td class="formval">' . $oForm->createInput($multi . "PAGE_ZONE_MULTI_URL2", t('URL_WEB'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL2"], $readO, 100, true) . '</td></tr>';
        //$return .= $oForm->createComboFromList($multi . "PAGE_ZONE_MULTI_VALUE", t("MODE_OUVERTURE"), $aDataValues, $values["PAGE_ZONE_MULTI_VALUE"], false, $readO);
        $return .= '<tr class="' . $multi . 'choix_link"><td class="formlib">' . t('MODE_OUVERTURE') . '</td><td class="formval">' . $oForm->createRadioFromList($multi . "PAGE_ZONE_MULTI_VALUE", t("MODE_OUVERTURE"), $aDataValues, $values["PAGE_ZONE_MULTI_VALUE"], false, $readO, '', true) . '</td></tr>';

        // CTA 1
        $return .= '<tr class="' . $multi . 'choix_cta"><td class="formSep" colspan="2">&nbsp;</td></tr>';
        $return .= '<tr class="' . $multi . 'choix_cta"><td class="formlib">' . t('CTA_OUTIL') . '</td><td class="formval">' . $oForm->createComboFromList($multi . "PAGE_ZONE_MULTI_ATTRIBUT", t("CTA_OUTIL"), $aDataOutilWeb, $values["PAGE_ZONE_MULTI_ATTRIBUT"], false, $readO, '', false, '', true, true) . '</td></tr>';
        $return .= '<tr class="' . $multi . 'choix_cta"><td class="formSep" colspan="2">&nbsp;</td></tr>';
        $return .= '<tr class="' . $multi . 'choix_cta"><td class="formlib">' . t('LIBELLE') . '</td><td class="formval">' . $oForm->createInput($multi . "PAGE_ZONE_MULTI_LABEL3", t('LIBELLE'), 40, "", false, $values["PAGE_ZONE_MULTI_LABEL3"], $readO, 100, true) . '</td></tr>';
        $return .= '<tr class="' . $multi . 'choix_cta"><td class="formlib">' . t('URL_WEB') . '</td><td class="formval">' . $oForm->createInput($multi . "PAGE_ZONE_MULTI_URL3", t('URL_WEB'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL3"], $readO, 100, true) . '</td></tr>';
        $return .= '<tr class="' . $multi . 'choix_cta"><td class="formlib">' . t('URL_MOB') . '</td><td class="formval">' . $oForm->createInput($multi . "PAGE_ZONE_MULTI_URL4", t('URL_MOB'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL4"], $readO, 100, true) . '</td></tr>';
        $return .= '<tr class="' . $multi . 'choix_cta"><td class="formlib">' . t('MODE_OUVERTURE') . '</td><td class="formval">' . $oForm->createComboFromList($multi . "PAGE_ZONE_MULTI_VALUE2", t("MODE_OUVERTURE"), $aDataValues, strtoupper($values["PAGE_ZONE_MULTI_VALUE2"]), false, $readO, '', false, '', true, true) . '</td></tr>';
        
        // CTA 2
        $return .= '<tr class="' . $multi . 'choix_cta"><td class="formSep" colspan="2">&nbsp;</td></tr>';
        $return .= '<tr class="' . $multi . 'choix_cta"><td class="formlib">' . t('CTA_OUTIL') . '</td><td class="formval">' .$oForm->createComboFromList($multi . "PAGE_ZONE_MULTI_ATTRIBUT2", t("CTA_OUTIL"), $aDataOutilWeb, $values["PAGE_ZONE_MULTI_ATTRIBUT2"], false, $readO, '', false, '', true, true) . '</td></tr>';
        $return .= '<tr class="' . $multi . 'choix_cta"><td class="formSep" colspan="2">&nbsp;</td></tr>';
        $return .= '<tr class="' . $multi . 'choix_cta"><td class="formlib">' . t('LIBELLE') . '</td><td class="formval">' . $oForm->createInput($multi . "PAGE_ZONE_MULTI_LABEL4", t('LIBELLE'), 40, "", false, $values["PAGE_ZONE_MULTI_LABEL4"], $readO, 100, true) . '</td></tr>';
        $return .= '<tr class="' . $multi . 'choix_cta"><td class="formlib">' . t('URL_WEB') . '</td><td class="formval">' . $oForm->createInput($multi . "PAGE_ZONE_MULTI_URL5", t('URL_WEB'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL5"], $readO, 100, true) . '</td></tr>';
        $return .= '<tr class="' . $multi . 'choix_cta"><td class="formlib">' . t('URL_MOB') . '</td><td class="formval">' . $oForm->createInput($multi . "PAGE_ZONE_MULTI_URL6", t('URL_MOB'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL6"], $readO, 100, true) . '</td></tr>';
        $return .= '<tr class="' . $multi . 'choix_cta"><td class="formlib">' . t('MODE_OUVERTURE') . '</td><td class="formval">' . $oForm->createComboFromList($multi . "PAGE_ZONE_MULTI_VALUE3", t("MODE_OUVERTURE"), $aDataValues, strtoupper($values["PAGE_ZONE_MULTI_VALUE3"]), false, $readO, '', false, '', true, true) . '</td></tr>';
        
        return $return;
    }

    /* Champ spécifique 1 colonne
     * @param array $controller
     * @param bool $texte
     * @return string $return
     */

    public static function getFormMultiColonneUnique($controller, $texte = false, $showLeftRight = false, $mediaFormat = false, $tranche = '') {
        if ($texte == false) {
            if ($mediaFormat == false) {
                $return .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID", t('MEDIA'), false, "image", "", $controller->zoneValues["MEDIA_ID"], $controller->readO, true, false);
            } else {
                $return .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID", t('MEDIA'), false, "image", "", $controller->zoneValues["MEDIA_ID"], $controller->readO, true, false, $mediaFormat);
            }
            // $return .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID2", t ( 'VIDEO' ), false, "video", "", $controller->zoneValues["MEDIA_ID2"], $controller->readO, true, false);

            if ($tranche == '1colonne') {
                $return .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID2", t('FLASH'), false, "flash", "", $controller->zoneValues["MEDIA_ID2"], $controller->readO, true, false);
                $return .= $controller->oForm->createTextArea($controller->multi . "ZONE_TEXTE5", t('ALTERNATIVE_FLASH'), false, $controller->zoneValues["ZONE_TEXTE5"], "", $controller->readO, 2, 100, false, "", false);
                $return .= $controller->oForm->createTextArea($controller->multi . "ZONE_TEXTE2", t('HTML'), false, $controller->zoneValues["ZONE_TEXTE2"], "", $controller->readO, 2, 100, false, "", false);
				$return .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID11", t('VIDEO'), false, "video", "", $controller->zoneValues["MEDIA_ID11"], $controller->readO, true, false);
				$return .= $controller->oForm->createCheckBoxFromList($controller->multi . "MEDIA_AUTOLOAD", t('MEDIA_AUTOLOAD'), array(1 => ""), ($controller->zoneValues['MEDIA_AUTOLOAD'] == -2) ? 1 : $controller->zoneValues['MEDIA_AUTOLOAD'], '', $controller->readO);
			} else {
                $return .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID2", t('VIDEO'), false, "video", "", $controller->zoneValues["MEDIA_ID2"], $controller->readO, true, false);
            }

            $return .= $controller->oForm->createEditor($controller->multi . "ZONE_TEXTE3", t('TEXTE'), true, $controller->zoneValues["ZONE_TEXTE3"], $controller->readO, true, "", 650, 150);

            //affichage du nombre de ligne pour la tranche 4 colonnes
            if ($showLeftRight == true) {
                $aRadio = Pelican::$config['TRANCHE_COL']["GAUCHE_DROITE"];

                if ($controller->zoneValues["ZONE_TITRE18"] == "") {
                    $controller->zoneValues["ZONE_TITRE18"] = 2;
                }
                //$return .= $controller->oForm->createHidden($sFieldWorkflow . "ZONE_TITRE18", $controller->zoneValues["ZONE_TITRE18"]);
                //$return .= $controller->oForm->createRadioFromList($controller->multi . "ZONE_TITRE18", t ( 'SHOW_ASSISTANCE' ), $aRadio, $controller->zoneValues["ZONE_TITRE18"]);

                $return .= $controller->oForm->createRadioFromList($controller->multi . "ZONE_TITRE18", t('POSITION_IMG'), $aRadio, $controller->zoneValues["ZONE_TITRE18"]);
            }
        } else {
            //$oConnection = Pelican_Db::getInstance();

            $multiValues = self::getPageZoneMulti($controller, 'ADDLISTPICTO');

            $return .= $controller->oForm->createMultiHmvc($controller->multi . "ADDLISTPICTO", t('ADD_LIST_PICTO_TEXT'), array(
                "path" => Pelican::$config['APPLICATION_VIEW_HELPERS'] . "/Form.php",
                "class" => "Backoffice_Form_Helper",
                "method" => "newListAddForm"
                    ), $multiValues, $controller->multi . "ADDLISTPICTO", $controller->readO, $nbCol, true, true, $controller->multi . "ADDLISTPICTO");
        }
        return $return;
    }

    /* HMVC qui ajoute un picto et un texte pour être utiliser sous forme de liste (ex: 1 colonneTexte)
     * @param array $controller
     * @return string $return
     */

    public static function newListAddForm($oForm, $values, $readO, $multi) {
        $return .= $oForm->createMedia($multi . "MEDIA_ID", t('PICTO'), true, "image", "", $values["MEDIA_ID"], $readO, true, false, 'carre');
        $return .= $oForm->createEditor($multi . "PAGE_ZONE_MULTI_TEXT", t('TEXTE'), true, $values["PAGE_ZONE_MULTI_TEXT"], $readO, true, "", 650, 150);
        return $return;
    }

    /* Champ spécifique colonne mixte
     * @param array $controller
     * @return string $return
     */

    public static function getFormMultiColonneMixte($controller) {
        return $return;
    }

    /* Affiche les cases à cocher définissant l'affichage Web / Mobile
     * Controlle JS si case coché alors on verifie les enristrement obligatoire sinon on laisse passé
     * @param array $controller
     * @param bool $web : Affichage de la case à cocher Web
     * @param bool $mobile : Affichage de la case à cocher Mobile
     * @return string $return
     */

    public static function getFormAffichage($controller, $web = true, $mobile = true, $required = false) {
        $selectAllJs = "\n" . 'jQuery(document.fForm).find("select#' . $controller->multi . 'ZONE_TEXTE>option").prop("selected", true);' . "\n";
        if ($controller->zoneValues['ZONE_BO_PATH'] == 'Cms_Page_Citroen_Outil') {
            $selectAllJs .= 'selectAll(document.fForm.elements["' . $controller->multi . 'ZONE_TOOL[]"]);' . "\n";
            $selectAllJs .= 'selectAll(document.fForm.elements["' . $controller->multi . 'ZONE_TOOL2[]"]);' . "\n";
        } elseif ($controller->zoneValues['ZONE_BO_PATH'] == 'Cms_Page_Citroen_ContenusRecommandes') {
            $selectAllJs .= 'selectAll(document.fForm.elements["' . $controller->multi . 'ZONE_LABEL2[]"]);' . "\n";
            $selectAllJs .= 'selectAll(document.fForm.elements["' . $controller->multi . 'ZONE_TOOL[]"]);' . "\n";
        } elseif ($controller->zoneValues['ZONE_BO_PATH'] == 'Cms_Page_Citroen_ContenusRecommandesShowroom') {
            $selectAllJs .= 'selectAll(document.fForm.elements["' . $controller->multi . 'ZONE_LABEL2[]"]);' . "\n";
        } elseif ($controller->zoneValues['isPerso'] == 1 && ($controller->zoneValues['ORDER'] || $controller->getParam("indexTab"))) {
            $zoneId = ($controller->getParam('zid')) ? $controller->getParam('zid') : $controller->getParam('zoneId');
            if ($zoneId == Pelican::$config['ZONE']["CONTENUS_RECOMMANDES"] || $zoneId == Pelican::$config['ZONE']["CONTENUS_RECOMMANDES_SHOWROOM"]) { // CONTENUS_RECOMMANDES
                $iOrder = "";
                if ($controller->getParam("indexTab")) {
                    $iOrder = $controller->getParam("indexTab");
                } else {
                    $iOrder = $controller->zoneValues['ORDER'];
                }
		        if($zoneId == Pelican::$config['ZONE']["CONTENUS_RECOMMANDES"]){
                	$selectAllJs .= 'selectAll(document.fForm' . $iOrder . '.elements["' . $controller->multi . 'ZONE_TOOL[]"]);' . "\n";
		        }
                $selectAllJs .= 'selectAll(document.fForm' . $iOrder . '.elements["' . $controller->multi . 'ZONE_LABEL2[]"]);' . "\n";
            }
        }

        $controller->zoneValues['VERIF_JS'] = 0;

        if ($web && $mobile) {
            $return .= $controller->oForm->createJS($selectAllJs);
            $return .= $controller->oForm->createJS('
                if($(\'input[name=' . $controller->multi . 'ZONE_MOBILE]\').is(\':checked\') 
                    || $(\'input[name=' . $controller->multi . 'ZONE_WEB]\').is(\':checked\'))
                {
            ');
            $controller->zoneValues['VERIF_JS'] = 1;
        } elseif ($web && !$mobile) {
            $return .= $controller->oForm->createJS($selectAllJs);
            $return .= $controller->oForm->createJS('
                if($(\'input[name=' . $controller->multi . 'ZONE_WEB]\').is(\':checked\'))
                {
            ');
            $controller->zoneValues['VERIF_JS'] = 1;
        } elseif (!$web && $mobile) {
            $return .= $controller->oForm->createJS($selectAllJs);
            $return .= $controller->oForm->createJS('
                if($(\'input[name=' . $controller->multi . 'ZONE_MOBILE]\').is(\':checked\'))
                {
            ');
            $controller->zoneValues['VERIF_JS'] = 1;
        }

        if ($mobile) {
            $return .= $controller->oForm->createCheckBoxFromList($controller->multi . "ZONE_MOBILE", t('AFFICHAGE_MOB'), array(1 => ""), ($controller->zoneValues['PAGE_ID'] == -2) ? 1 : $controller->zoneValues['ZONE_MOBILE'], $required, $controller->readO);
        } else {
            $return .= $controller->oForm->createHidden($controller->multi . "ZONE_MOBILE", 0);
        }

        if ($web) {
            $return .= $controller->oForm->createCheckBoxFromList($controller->multi . "ZONE_WEB", t('AFFICHAGE_WEB'), array(1 => ""), ($controller->zoneValues['PAGE_ID'] == -2) ? 1 : $controller->zoneValues['ZONE_WEB'], $required, $controller->readO);
        } else {
            $return .= $controller->oForm->createHidden($controller->multi . "ZONE_WEB", 0);
        }
        return $return;
    }

    /* Fonction associé à getFormAffichage.
     * Reformate les cases à cocher pour l'insertion en base
     * A placer dans le saveAction(), avant le parent::save();
     */

    public static function saveFormAffichage() {
        if (!isset(Pelican_Db::$values['ZONE_MOBILE'])) {
            Pelican_Db::$values['ZONE_MOBILE'] = 0;
        }
        if (!isset(Pelican_Db::$values['ZONE_WEB'])) {
            Pelican_Db::$values['ZONE_WEB'] = 0;
        }
    }

    /* Affiche les cases à cocher définissant l'affichage Web / Mobile pour les contenus
     * @param array $oController
     * @param bool $bWeb : Affichage de la case à cocher Web
     * @param bool $mobile : Affichage de la case à cocher Mobile
     * @return string $return
     */

    public static function getContentFormAffichage($oController, $bWeb = true, $bMobile = true) {
        /* Intialisation des variabes */
        $bWeb = (bool) $bWeb;
        $bMobile = (bool) $bMobile;
        $sControllerForm = '';

        if ($bMobile === true) {
            $sControllerForm .= $oController->oForm->createCheckBoxFromList('CONTENT_MOBILE', t('AFFICHAGE_MOB'), array(1 => ''), ($oController->values['CONTENT_ID'] == -2) ? 1 : $oController->values['CONTENT_MOBILE'], false, $oController->readO);
        }
        if ($bWeb === true) {
            $sControllerForm .= $oController->oForm->createCheckBoxFromList('CONTENT_WEB', t('AFFICHAGE_WEB'), array(1 => ''), ($oController->values['CONTENT_ID'] == -2) ? 1 : $oController->values['CONTENT_WEB'], false, $oController->readO);
        }
        return $sControllerForm;
    }

    /* Méthode associé à getFormAffichage.
     * Reformate les cases à cocher pour l'insertion en base
     * A placer dans le saveAction(), avant le parent::save();
     */

    public static function saveContentFormAffichage() {
        if (!isset(Pelican_Db::$values['CONTENT_MOBILE'])) {
            Pelican_Db::$values['CONTENT_MOBILE'] = 0;
        }
        if (!isset(Pelican_Db::$values['CONTENT_WEB'])) {
            Pelican_Db::$values['CONTENT_WEB'] = 0;
        }
    }

    /* Affiche le menu déroulant définissant le mode d'affichage
     * @param array $controller
     * @return string $return
     */

    public static function getFormModeAffichage($controller) {
        $aModeAffichage = Pelican::$config['TRANCHE_COL']["MODE_AFF"];
        return $controller->oForm->createComboFromList($controller->multi . "ZONE_TITRE19", t("MODE_AFFICHAGE"), $aModeAffichage, ($controller->zoneValues['PAGE_ID'] == -2) ? 'NEUTRE' : $controller->zoneValues['ZONE_TITRE19'], true, $controller->readO);
    }

    /* Affiche le menu déroulant définissant le mode d'affichage dans un multi HMVC
     * @param array $oForm
     * @param array $values
     * @param boolean $readO
     * @param string $multi
     * @param string $champ
     * @return string $return
     */

    public static function getFormModeAffichageMultiHmvc($oForm, $values, $readO, $multi, $champ) {
        $aModeAffichage = Pelican::$config['TRANCHE_COL']["MODE_AFF"];
        return $oForm->createComboFromList($multi . $champ, t("MODE_AFFICHAGE"), $aModeAffichage, $values[$champ] ? $values[$champ] : 'NEUTRE', true, $readO);
    }

    /* Affiche le menu déroulant remontant les réseaux sociaux d'un type donné
     * @param array $controller
     * @param string $type : Type de réseau social (cf. Pelican::$config['TYPE_RESEAUX_SOCIAUX'])
     * @param string $champ : Nom du champ utilisé dans le formulaire
     * @return string $return
     */

    public static function getFormReseauSocial($controller, $type, $champ) {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':RESEAU_SOCIAL_TYPE'] = Pelican::$config['TYPE_RESEAUX_SOCIAUX'][$type];
        $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $aBind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $sSQL = "
            select
                RESEAU_SOCIAL_ID as id,
                RESEAU_SOCIAL_LABEL as lib
            from #pref#_reseau_social
            where RESEAU_SOCIAL_TYPE = :RESEAU_SOCIAL_TYPE
            and SITE_ID = :SITE_ID
            and LANGUE_ID = :LANGUE_ID
        ";
        return $controller->oForm->createComboFromSql($oConnection, $controller->multi . $champ, ucfirst(strtolower($type)), $sSQL, $controller->zoneValues[$champ], false, $controller->readO, "1", false, "", true, false, "", "", $aBind);
    }

    /* Affiche le menu déroulant remontant les groupes de réseaux sociaux
     * @param array $controller
     * @param string $champ : Nom du champ utilisé dans le formulaire
     * @param string $defaut : Entrée par défaut (case à cocher PUBLIC ou MEDIA activé dans le contenu)
     * @return string $return
     */

    public static function getFormGroupeReseauxSociaux($controller, $champ, $defaut = 'PUBLIC', $obligatoire = false, $bContent = false) {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $aBind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $sSQL = "
            select *
            from #pref#_groupe_reseaux_sociaux
            where SITE_ID = :SITE_ID
            and LANGUE_ID = :LANGUE_ID
            order by GROUPE_RESEAUX_SOCIAUX_LABEL asc";
        $aTemp = $oConnection->queryTab($sSQL, $aBind);
        $aDataValues = array();
        if ($aTemp) {
            foreach ($aTemp as $temp) {
                $aDataValues[$temp['GROUPE_RESEAUX_SOCIAUX_ID']] = $temp['GROUPE_RESEAUX_SOCIAUX_LABEL'];
                if ($temp['GROUPE_RESEAUX_SOCIAUX_DEFAUT_MEDIA'] == 1) {
                    $idMediaDefaut = $temp['GROUPE_RESEAUX_SOCIAUX_ID'];
                }
                if ($temp['GROUPE_RESEAUX_SOCIAUX_DEFAUT_PUBLIC'] == 1) {
                    $idPublicDefaut = $temp['GROUPE_RESEAUX_SOCIAUX_ID'];
                }
            }
        }
        if ($controller->zoneValues['PAGE_ID'] != -2) {
            $aSelectedValues = $controller->zoneValues[$champ];
        } elseif ($defaut == 'PUBLIC') {
            $aSelectedValues = $idPublicDefaut;
        } elseif ($defaut == 'MEDIA') {
            $aSelectedValues = $idMediaDefaut;
        }
        if (!$bContent) {
            return $controller->oForm->createComboFromList($controller->multi . $champ, t('TYPE_REGROUPEMENT_RESEAUX_SOCIAUX'), $aDataValues, $aSelectedValues, $obligatoire, $controller->readO);
        } else {
            return $controller->oForm->createComboFromList($champ, t('TYPE_REGROUPEMENT_RESEAUX_SOCIAUX'), $aDataValues, $controller->values[$champ], $obligatoire, $controller->readO);
        }
    }

    /* Affiche le menu déroulant remontant les groupes de réseaux sociaux dans un multi
     * @param array $controller
     * @param string $champ : Nom du champ utilisé dans le formulaire
     * @param string $defaut : Entrée par défaut (case à cocher PUBLIC ou MEDIA activé dans le contenu)
     * @return string $return
     */

    public static function getFormGroupeReseauxSociauxMultiHmvc($oForm, $values, $readO, $multi, $champ, $defaut = 'PUBLIC', $bRequired = true, $bChoissez = false) {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $sSQL = "
            select *
            from #pref#_groupe_reseaux_sociaux
            where SITE_ID = :SITE_ID
            order by GROUPE_RESEAUX_SOCIAUX_LABEL asc";
        $aTemp = $oConnection->queryTab($sSQL, $aBind);
        $aDataValues = array();
        if ($aTemp) {
            foreach ($aTemp as $temp) {
                $aDataValues[$temp['GROUPE_RESEAUX_SOCIAUX_ID']] = $temp['GROUPE_RESEAUX_SOCIAUX_LABEL'];
                if ($temp['GROUPE_RESEAUX_SOCIAUX_DEFAUT_MEDIA'] == 1) {
                    $idMediaDefaut = $temp['GROUPE_RESEAUX_SOCIAUX_ID'];
                }
                if ($temp['GROUPE_RESEAUX_SOCIAUX_DEFAUT_PUBLIC'] == 1) {
                    $idPublicDefaut = $temp['GROUPE_RESEAUX_SOCIAUX_ID'];
                }
            }
        }
        if ($values['PAGE_ID'] != -2) {
            $aSelectedValues = $values[$champ];
        } elseif ($defaut == 'PUBLIC') {
            $aSelectedValues = $idPublicDefaut;
        } elseif ($defaut == 'MEDIA') {
            $aSelectedValues = $idMediaDefaut;
        }

        return $oForm->createComboFromList($multi . $champ, t('TYPE_REGROUPEMENT_RESEAUX_SOCIAUX'), $aDataValues, $aSelectedValues, $bRequired, $readO, 1, false, "", $bChoissez);
    }

    public static function getMentionsLegales($controller, $bContent = false, $ratio = '') {
        $aDataValues = Pelican::$config['TRANCHE_COL']["AFF_MODE_MENTION"];
        $aPages = getComboValuesFromCache("Backend/Page", array($_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            "",
            Pelican::$config['MENTION_LEGAL_TEMPLATE']));

        if (is_array($aPages) && !empty($aPages)) {
            foreach ($aPages as $key => $page) {
                $aPages[$key] = preg_replace("/[0-9]/", "", $page);
            }
        } else {
            $aPages = array();
        }
        if (is_array($aPages) && array_key_exists('lib', $aPages) && array_key_exists('lib_path', $aPages)
        ) {
            $aPages['lib'] = $aPages['lib'] . $aPages['lib_path'];
        }

        if (!$bContent) {
            $aMentions = $controller->oForm->showSeparator("formSep");
            $aMentions .= $controller->oForm->createLabel(t("MENTIONS_LEGALES"), "");
            $aMentions .= $controller->oForm->createComboFromList($controller->multi . "ZONE_TITRE5", t("GESTION_MODE"), $aDataValues, $controller->zoneValues["ZONE_TITRE5"], false, $controller->readO);
            
            // Mentions légales véhicules particuliers
            $aMentions .= $controller->oForm->createInput($controller->multi."ZONE_TITRE6", t('MENTIONS_LEGALES_VEHICULE_PARTICULIER'), 255, "", false, $controller->zoneValues["ZONE_TITRE6"], $controller->readO, 100);
            $aMentions .= $controller->oForm->createInput($controller->multi.'ZONE_TITRE22', t('MENTIONS_LEGALES_VEHICULE_PARTICULIER_URL'), 255, 'internallink', false, $controller->zoneValues["ZONE_TITRE22"], $controller->readO, 50);
            
            // Mentions légales véhicules professionnel
            $aMentions .= $controller->oForm->createInput($controller->multi."ZONE_TITRE23", t('MENTIONS_LEGALES_VEHICULE_UTILITAIRE'), 255, "", false, $controller->zoneValues["ZONE_TITRE23"], $controller->readO, 100);
            $aMentions .= $controller->oForm->createInput($controller->multi.'ZONE_TITRE24', t('MENTIONS_LEGALES_VEHICULE_UTILITAIRE_URL'), 255, 'internallink', false, $controller->zoneValues["ZONE_TITRE24"], $controller->readO, 50);
            
            $aMentions .= $controller->oForm->createEditor($controller->multi . "ZONE_TEXTE4", t('TEXTE'), false, $controller->zoneValues["ZONE_TEXTE4"], $controller->readO, true, "", 650, 150);
            $aMentions .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID4", t('MEDIA'), false, "image", "", $controller->zoneValues["MEDIA_ID4"], $controller->readO, true, false, $ratio);
            $aMentions .= $controller->oForm->createComboFromList($controller->multi . "ZONE_TITRE7", t("LIEN_PAGE_MENTION"), $aPages, $controller->zoneValues["ZONE_TITRE7"], false, $controller->readO);

            $aMentions .= "<script type=\"text/javascript\">
           //gestion affichage champ mention légales
           $('#" . $controller->multi . "ZONE_TITRE5').change(function(){
               changeModeGestion(this);
            });
           function changeModeGestion(obj){
                if($(obj).find('option:selected').text() == '" . Pelican::$config['TRANCHE_COL']["AFF_MODE_MENTION"]["ROLL"] . "'
                    || $(obj).find('option:selected').text() == '" . Pelican::$config['TRANCHE_COL']["AFF_MODE_MENTION"]["TEXT"] . "'){

                    //$('#" . $controller->multi . "ZONE_TITRE6').parent().parent().css('display', '');
                    $('#" . $controller->multi . "ZONE_TEXTE4').parent().parent().css('display', '');
                    $('#div" . $controller->multi . "MEDIA_ID4').parent().parent().parent().parent().parent().css('display', '');
                    $('#" . $controller->multi . "ZONE_TITRE7').parent().parent().css('display', 'none');
                }else{
                    //$('#" . $controller->multi . "ZONE_TITRE6').parent().parent().css('display', 'none');
                    //alert($('#" . $controller->multi . "ZONE_TEXTE6').val());
                    $('#" . $controller->multi . "ZONE_TEXTE4').parent().parent().css('display', 'none');
                    $('#div" . $controller->multi . "MEDIA_ID4').parent().parent().parent().parent().parent().css('display', 'none');
                    $('#" . $controller->multi . "ZONE_TITRE7').parent().parent().css('display', '');
                }
           }
           $(document).ready(function() {
            changeModeGestion($('#" . $controller->multi . "ZONE_TITRE5'));
            });

        </script>";
        } else {
            $aMentions = $controller->oForm->showSeparator("formSep");
            $aMentions .= $controller->oForm->createLabel(t("MENTIONS_LEGALES"), "");
            $aMentions .= $controller->oForm->createComboFromList("CONTENT_TITLE2", t("GESTION_MODE"), $aDataValues, $controller->values["CONTENT_TITLE2"], false, $controller->readO);
            $aMentions .= $controller->oForm->createInput("CONTENT_TITLE3", t('TITRE'), 255, "", false, $controller->values["CONTENT_TITLE3"], $controller->readO, 100);
            $aMentions .= $controller->oForm->createEditor("CONTENT_TEXT", t('TEXTE'), false, $controller->values["CONTENT_TEXT"], $controller->readO, true, "", 650, 150);
            $aMentions .= $controller->oForm->createMedia("MEDIA_ID2", t('MEDIA'), false, "image", "", $controller->values["MEDIA_ID2"], $controller->readO, true, false);
            $aMentions .= $controller->oForm->createComboFromList("CONTENT_TITLE4", t("LIEN_PAGE_MENTION"), $aPages, $controller->values["CONTENT_TITLE4"], false, $controller->readO);

            $aMentions .= "<script type=\"text/javascript\">
           //gestion affichage champ mention légales
           $('#CONTENT_TITLE2').change(function(){
               changeModeGestion(this);
            });
           function changeModeGestion(obj){
                if($(obj).find('option:selected').text() == '" . Pelican::$config['TRANCHE_COL']["AFF_MODE_MENTION"]["ROLL"] . "'
                    || $(obj).find('option:selected').text() == '" . Pelican::$config['TRANCHE_COL']["AFF_MODE_MENTION"]["TEXT"] . "'){

                    $('#CONTENT_TEXT').parent().parent().css('display', '');
                    $('#divMEDIA_ID2').parent().parent().parent().parent().parent().css('display', '');
                    $('#CONTENT_TITLE4').parent().parent().css('display', 'none');
                }else{
                    $('#CONTENT_TEXT').parent().parent().css('display', 'none');
                    $('#divMEDIA_ID2').parent().parent().parent().parent().parent().css('display', 'none');
                    $('#CONTENT_TITLE4').parent().parent().css('display', '');
                }
           }
           $(document).ready(function() {
            changeModeGestion($('#CONTENT_TITLE2'));
            });

        </script>";
        }
        return $aMentions;
    }

    public static function getMentionsLegalesHmvc($oForm, $values, $readO, $multi) {
        $aDataValues = Pelican::$config['TRANCHE_COL']["AFF_MODE_MENTION"];
        $aPages = getComboValuesFromCache("Backend/Page", array($_SESSION [APP] ['SITE_ID'],
            $values['LANGUE_ID'],
            "",
            Pelican::$config['MENTION_LEGAL_TEMPLATE']));

        if (is_array($aPages) && !empty($aPages)) {
            foreach ($aPages as $key => $page) {
                $aPages[$key] = preg_replace("/[0-9]/", "", $page);
            }
        } else {
            $aPages = array();
        }
        if (is_array($aPages) && array_key_exists('lib', $aPages) && array_key_exists('lib_path', $aPages)
        ) {
            $aPages['lib'] = $aPages['lib'] . $aPages['lib_path'];
        }

        $aMentions = $oForm->showSeparator("formSep");
        $aMentions .= $oForm->createLabel(t("MENTIONS_LEGALES"), "");
        $aMentions .= $oForm->createComboFromList($multi . "PAGE_ZONE_MULTI_URL13", t("GESTION_MODE"), $aDataValues, $values["PAGE_ZONE_MULTI_URL13"], false, $readO);
        $aMentions .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_URL14", t('TITRE'), 255, "", false, $values["PAGE_ZONE_MULTI_URL14"], $readO, 100);
        $aMentions .= $oForm->createEditor($multi . "PAGE_ZONE_MULTI_TEXT4", t('TEXTE'), false, $values["PAGE_ZONE_MULTI_TEXT4"], $readO, true, "", 650, 150);
        $aMentions .= $oForm->createMedia($multi . "MEDIA_ID6", t('MEDIA'), false, "image", "", $values["MEDIA_ID6"], $readO, true, false, 'cinemascope');
        $aMentions .= $oForm->createComboFromList($multi . "PAGE_ZONE_MULTI_URL16", t("LIEN_PAGE_MENTION"), $aPages, $values["PAGE_ZONE_MULTI_URL16"], false, $readO);

        return $aMentions;
    }

    public static function getPushMedia($controller, $bContent = false) {
        if (!$bContent) {

            $aPushMedia = $controller->oForm->showSeparator("formSep");
            $aPushMedia .= $controller->oForm->createLabel(t("PUSH_MEDIA"), "");
            //$aPushMedia .= $controller->oForm->createComboFromList($controller->multi . "ZONE_TITRE8", t("MODE_GESTION"), $aDataValues, $controller->zoneValues["ZONE_TITRE8"], false, $controller->readO);
            $aPushMedia .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE15", t('LIBELLE'), 255, "", false, $controller->zoneValues["ZONE_TITRE15"], $controller->readO, 100);
            $aPushMedia .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID5", t('VIDEO'), false, "video", "", $controller->zoneValues["MEDIA_ID5"], $controller->readO, true, false);
            $aPushMedia .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID7", t('VIGNETTE_VIDEO'), false, "image", "", $controller->zoneValues["MEDIA_ID7"], $controller->readO, true, false);

            $aPushMedia .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE16", t('LIBELLE'), 255, "", false, $controller->zoneValues["ZONE_TITRE16"], $controller->readO, 100);
            $aPushMedia .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID9", t('VIDEO'), false, "video", "", $controller->zoneValues["MEDIA_ID9"], $controller->readO, true, false);
            $aPushMedia .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID8", t('VIGNETTE_VIDEO'), false, "image", "", $controller->zoneValues["MEDIA_ID8"], $controller->readO, true, false);

            $aPushMedia .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE21", t('LIBELLE'), 255, "", false, $controller->zoneValues["ZONE_TITRE21"], $controller->readO, 100);

            //$aPushMedia .= $controller->oForm->createComboFromList($controller->multi . "ZONE_TITRE10", t("SOCIAL_GROUP"), $aDataValues, $controller->zoneValues["ZONE_TITRE10"], false, $controller->readO);
            $aPushMedia .= self::getFormGroupeReseauxSociaux($controller, "ZONE_TITRE10", "PUBLIC", true);

            //si une vidéo est saisie, le libelle doit également être saisie sinon une alerte est levée
            $aPushMedia .= $controller->oForm->createJS("

                 var imageMulti = document.getElementById('div" . $controller->multi . "GALLERYFORM0_MEDIA_ID');
                media1 = document.getElementById('div" . $controller->multi . "MEDIA_ID5');
                media2 = document.getElementById('div" . $controller->multi . "MEDIA_ID9');

                image1 = document.getElementById('div" . $controller->multi . "MEDIA_ID7');
                image2 = document.getElementById('div" . $controller->multi . "MEDIA_ID8');

                texte1 = document.getElementById('" . $controller->multi . "ZONE_TITRE15');
                texte2 = document.getElementById('" . $controller->multi . "ZONE_TITRE20');
                    
                radio3 = $('input[name=\"" . $controller->multi . "ZONE_ATTRIBUT\"]').is(':checked');
                radio2 = $('input[name=\"" . $controller->multi . "ZONE_ATTRIBUT2\"]').is(':checked');
                radio1 = $('input[name=\"" . $controller->multi . "ZONE_ATTRIBUT3\"]').is(':checked');
				
				if($('#form_button').val() =='state_5'){
					var tiltebo = $('#PAGE_TITLE_BO').val();
					if (tiltebo.indexOf('diff') !== -1) {
						return true;
					}
				}else{
					if(!radio1 && media1.innerHTML != '' && image1.innerHTML != ''){
						alert('" . t('ORDRE_POUR_PUSH_MEDIA', 'js') . " 1" . "');
							$('#FIELD_BLANKS').val(1);
					}
					if(!radio2 && media2.innerHTML != '' && image2.innerHTML != ''){
						alert('" . t('ORDRE_POUR_PUSH_MEDIA', 'js') . " 2" . "');
							$('#FIELD_BLANKS').val(1);
					}
					if(!radio3 && imageMulti.innerHTML != ''){
						alert('" . t('ORDRE_POUR_PUSH_MEDIA', 'js') . " 3" . "');
							$('#FIELD_BLANKS').val(1);
					}

					radioval3 = '';
					$('input[name=\"" . $controller->multi . "ZONE_ATTRIBUT\"]').each(function(){
						if($(this).is(':checked')){
							radioval3 = $(this).val();
						}
					});
					radioval2 = '';
					$('input[name=\"" . $controller->multi . "ZONE_ATTRIBUT2\"]').each(function(){
						if($(this).is(':checked')){
							radioval2 = $(this).val();
						}
					});
					radioval1 = '';
					$('input[name=\"" . $controller->multi . "ZONE_ATTRIBUT3\"]').each(function(){
						if($(this).is(':checked')){
							radioval1 = $(this).val();
						}
					});

					if(radioval1 != ''){
						if(radioval1 == radioval2 || radioval1 == radioval3){
							alert('" . t('ORDRE_DIFFERENT_PUSH_MEDIA', 'js') . "');
							$('#FIELD_BLANKS').val(1);
						}
					}
					if(radioval2 != ''){
						if(radioval1 == radioval2 || radioval2 == radioval3){
							alert('" . t('ORDRE_DIFFERENT_PUSH_MEDIA', 'js') . "');
							$('#FIELD_BLANKS').val(1);
						}
					}
					if(radioval3 != ''){
						if(radioval1 == radioval3 || radioval2 == radioval3){
							alert('" . t('ORDRE_DIFFERENT_PUSH_MEDIA', 'js') . "');
							$('#FIELD_BLANKS').val(1);
						}
					}

					if(image1.innerHTML != '' && texte1.value == ''){
						alert('" . t('LIBELLE_OBLIGATOIRE_POUR_IMAGE', 'js') . " 1" . "');
						fwFocus(obj." . $controller->multi . "ZONE_TITRE15);
						$('#FIELD_BLANKS').val(1);
					}

					if(image2.innerHTML != '' && texte2.value == ''){
						alert('" . t('LIBELLE_OBLIGATOIRE_POUR_IMAGE', 'js') . " 2" . "');
						fwFocus(obj." . $controller->multi . "ZONE_TITRE20);
						$('#FIELD_BLANKS').val(1);
					}

					if(media1.innerHTML != '' && image1.innerHTML == ''){
						alert('" . t('IMAGE_OBLIGATOIRE_POUR_LA_VIDEO', 'js') . " 1" . "');
						fwFocus(obj." . $controller->multi . "MEDIA_ID7);
						$('#FIELD_BLANKS').val(1);
					}

					if(media2.innerHTML != '' && image2.innerHTML == ''){
						alert('" . t('IMAGE_OBLIGATOIRE_POUR_LA_VIDEO', 'js') . " 2" . "');
						fwFocus(obj." . $controller->multi . "MEDIA_ID8);
						$('#FIELD_BLANKS').val(1);
					}

					if(media1.innerHTML != '' && texte1.value == ''){
						alert('" . t('LIBELLE_OBLIGATOIRE_POUR_LA_VIDEO', 'js') . " 1" . "');
						fwFocus(obj." . $controller->multi . "ZONE_TITRE15);
						$('#FIELD_BLANKS').val(1);
					}

					if(media2.innerHTML != '' && texte2.value == ''){
						alert('" . t('LIBELLE_OBLIGATOIRE_POUR_LA_VIDEO', 'js') . " 2" . "');
						fwFocus(obj." . $controller->multi . "ZONE_TITRE20);
						$('#FIELD_BLANKS').val(1);
					}

					
					var social = document.getElementById('" . $controller->multi . "ZONE_TITRE10');

					if(imageMulti != null && imageMulti.innerHTML != ''){
						if(social.options[social.selectedIndex].value == 0){
							alert('" . t("OBLIGATOIRE_SOCIAL", "js") . "');
							fwFocus(obj." . $controller->multi . "ZONE_TITRE10);
							$('#FIELD_BLANKS').val(1);
						}
					}
				}
             ");

            $aPushMedia .= self::getGalleryPhoto($controller);
        } else {

            $aPushMedia .= $controller->oForm->showSeparator("formSep");
            $aPushMedia .= $controller->oForm->createLabel(t("PUSH_MEDIA"), "");

            $aPushMedia .= $controller->oForm->createMedia("MEDIA_ID3", t('VIGNETTE_VIDEO'), false, "image", "", $controller->values["MEDIA_ID3"], $controller->readO, true, false);
            $aPushMedia .= $controller->oForm->createInput("CONTENT_TITLE5", t('LIBELLE'), 255, "", false, $controller->values["CONTENT_TITLE5"], $controller->readO, 100);

            $aPushMedia .= $controller->oForm->createMedia("MEDIA_ID4", t('VIDEO'), false, "video", "", $controller->values["MEDIA_ID4"], $controller->readO, true, false);
            $aPushMedia .= $controller->oForm->createInput("CONTENT_TITLE6", t('LIBELLE'), 255, "", false, $controller->values["CONTENT_TITLE6"], $controller->readO, 100);

            $aPushMedia .= $controller->oForm->createMedia("MEDIA_ID5", t('VIGNETTE_VIDEO'), false, "image", "", $controller->values["MEDIA_ID5"], $controller->readO, true, false);
            $aPushMedia .= $controller->oForm->createInput("CONTENT_TITLE7", t('LIBELLE'), 255, "", false, $controller->values["CONTENT_TITLE7"], $controller->readO, 100);

            $aPushMedia .= $controller->oForm->createMedia("MEDIA_ID6", t('VIDEO'), false, "video", "", $controller->values["MEDIA_ID6"], $controller->readO, true, false);
            $aPushMedia .= $controller->oForm->createInput("CONTENT_TITLE8", t('LIBELLE'), 255, "", false, $controller->values["CONTENT_TITLE8"], $controller->readO, 100);

            //$aPushMedia .= $controller->oForm->createComboFromList($controller->multi . "ZONE_TITRE10", t("SOCIAL_GROUP"), $aDataValues, $controller->zoneValues["ZONE_TITRE10"], false, $controller->readO);
            $aPushMedia .= Backoffice_Form_Helper::getFormGroupeReseauxSociaux($controller, "CONTENT_TITLE9", "PUBLIC", true);

            //si une vidéo est saisie, le libelle doit également être saisie sinon une alerte est levée
            $aPushMedia .= $controller->oForm->createJS("

                media3 = document.getElementById('divMEDIA_ID3');
                media4 = document.getElementById('divMEDIA_ID4');
                media5 = document.getElementById('divMEDIA_ID5');
                media6 = document.getElementById('divMEDIA_ID6');

                texte1 = document.getElementById('CONTENT_TITLE5');
                texte2 = document.getElementById('CONTENT_TITLE6');
                texte3 = document.getElementById('CONTENT_TITLE7');
                texte4 = document.getElementById('CONTENT_TITLE8');
				
				if($('#form_button').val() =='state_5'){
					var tiltebo = $('#PAGE_TITLE_BO').val();
					if (tiltebo.indexOf('diff') !== -1) {
						return true;
					}
				}else{
					if(media3.innerHTML != '' && texte1.value == ''){
						alert('" . t("LIBELLE_OBLIGATOIRE", "js") . "');
						fwFocus(obj.CONTENT_TITLE5);
						$('#FIELD_BLANKS').val(1);
					}
				   if(media4.innerHTML != '' && texte2.value == ''){
						alert('" . t("LIBELLE_OBLIGATOIRE", "js") . "');
						fwFocus(obj.CONTENT_TITLE6);
						$('#FIELD_BLANKS').val(1);
					}
					if(media5.innerHTML != '' && texte3.value == ''){
						alert('" . t("LIBELLE_OBLIGATOIRE", "js") . "');
						fwFocus(obj.CONTENT_TITLE7);
						$('#FIELD_BLANKS').val(1);
					}
					if(media6.innerHTML != '' && texte4.value == ''){
						alert('" . t("LIBELLE_OBLIGATOIRE", "js") . "');
						fwFocus(obj.CONTENT_TITLE8);
						$('#FIELD_BLANKS').val(1);
					}

					if(media4.innerHTML != '' && media3.innerHTML == ''){
						 alert('" . t("IMAGE_OBLIGATOIRE", "js") . "');
						 fwFocus(obj.MEDIA_ID3);
						 $('#FIELD_BLANKS').val(1);
					 }

					if(media6.innerHTML != '' && media5.innerHTML == ''){
						 alert('" . t("IMAGE_OBLIGATOIRE", "js") . "');
						 fwFocus(obj.MEDIA_ID5);
						 $('#FIELD_BLANKS').val(1);
					 }

					var imageMulti = document.getElementById('divGALLERYFORM0_MEDIA_ID');
					var social = document.getElementById('CONTENT_TITLE9');

					if(imageMulti != null && imageMulti.innerHTML != ''){
						if(social.options[social.selectedIndex].value == 0){
							alert('" . t("OBLIGATOIRE_SOCIAL", "js") . "');
							fwFocus(obj.CONTENT_TITLE6);
							$('#FIELD_BLANKS').val(1);
						}
					}
				}
            ");
            $aPushMedia .= self::getGalleryPhoto($controller, true);
        }

        return $aPushMedia;
    }

    public static function getPushMediaCommun($controller, $bContent = false) {
        $aOrdres = array(
            1 => t('ORDRE') . ' 1',
            2 => t('ORDRE') . ' 2',
            3 => t('ORDRE') . ' 3'
        );
        $aPushMedia = $controller->oForm->showSeparator("formSep");
        $aPushMedia .= $controller->oForm->createLabel(t("PUSH_MEDIA"), "");

        if (!$bContent) {

            $aPushMedia .= $controller->oForm->createLabel(t("VIDEO_1"), "");
            $aPushMedia .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID5", t('SELECTIONNER_VIDE0'), false, "video", "", $controller->zoneValues["MEDIA_ID5"], $controller->readO, true, false);
            $aPushMedia .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID7", t('VIGNETTE_VIDEO'), false, "image", "", $controller->zoneValues["MEDIA_ID7"], $controller->readO, true, false, '16_9');
            $aPushMedia .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE15", t('LIBELLE_VIDEO'), 255, "", false, $controller->zoneValues["ZONE_TITRE15"], $controller->readO, 100);
            $aPushMedia .= $controller->oForm->createRadioFromList($controller->multi . 'ZONE_ATTRIBUT3', t('SELECTIONNER_ORDRE_VIDEO') . '1', $aOrdres, $controller->zoneValues["ZONE_ATTRIBUT3"], false, $oController->readO);
            $aPushMedia .= $controller->oForm->createLabel("", '<br/>');

            $aPushMedia .= $controller->oForm->createLabel(t("VIDEO_2"), "");
            $aPushMedia .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID9", t('SELECTIONNER_VIDEO'), false, "video", "", $controller->zoneValues["MEDIA_ID9"], $controller->readO, true, false);
            $aPushMedia .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID8", t('VIGNETTE_VIDEO'), false, "image", "", $controller->zoneValues["MEDIA_ID8"], $controller->readO, true, false, '16_9');
            $aPushMedia .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE20", t('LIBELLE_VIDEO'), 255, "", false, $controller->zoneValues["ZONE_TITRE20"], $controller->readO, 100);
            $aPushMedia .= $controller->oForm->createRadioFromList($controller->multi . 'ZONE_ATTRIBUT2', t('SELECTIONNER_ORDRE_VIDEO') . '2', $aOrdres, $controller->zoneValues["ZONE_ATTRIBUT2"], false, $oController->readO);

            $aPushMedia .= $controller->oForm->createLabel("", '<br/>');
            $aPushMedia .= self::getFormGroupeReseauxSociaux($controller, "ZONE_TITRE10", "PUBLIC", false);
            $aPushMedia .= $controller->oForm->createLabel("", '<br/>');
            $aPushMedia .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE21", t('SELECTIONNER_LIBELLE_GALERIE_PHOTO'), 255, "", false, $controller->zoneValues["ZONE_TITRE21"], $controller->readO, 100);
            $aPushMedia .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID10", t('VIGNETTE_GALLERY'), false, "image", "", $controller->zoneValues["MEDIA_ID10"], $controller->readO, true, false, "16_9");
            $aPushMedia .= self::getGalleryPhoto($controller);
            $aPushMedia .= $controller->oForm->createRadioFromList($controller->multi . 'ZONE_ATTRIBUT', t('SELECTIONNER_ORDRE_GALLERIE'), $aOrdres, $controller->zoneValues["ZONE_ATTRIBUT"], false, $oController->readO);
            $aPushMedia .= $controller->oForm->createLabel("", '<br/>');

            //si une vidéo est saisie, le libelle doit également être saisie sinon une alerte est levée
            $aPushMedia .= $controller->oForm->createJS("
                
                imageMulti = document.getElementById('div" . $controller->multi . "GALLERYFORM0_MEDIA_ID');
                media1 = document.getElementById('div" . $controller->multi . "MEDIA_ID5');
                media2 = document.getElementById('div" . $controller->multi . "MEDIA_ID9');

                image1 = document.getElementById('div" . $controller->multi . "MEDIA_ID7');
                image2 = document.getElementById('div" . $controller->multi . "MEDIA_ID8');

                texte1 = document.getElementById('" . $controller->multi . "ZONE_TITRE15');
                texte2 = document.getElementById('" . $controller->multi . "ZONE_TITRE20');
                    
                radio3 = $('input[name=\"" . $controller->multi . "ZONE_ATTRIBUT\"]').is(':checked');
                radio2 = $('input[name=\"" . $controller->multi . "ZONE_ATTRIBUT2\"]').is(':checked');
                radio1 = $('input[name=\"" . $controller->multi . "ZONE_ATTRIBUT3\"]').is(':checked');

                radioval3 = '';
                $('input[name=\"" . $controller->multi . "ZONE_ATTRIBUT\"]').each(function(){
                    if($(this).is(':checked')){
                        radioval3 = $(this).val();
                    }
                });
                radioval2 = '';
                $('input[name=\"" . $controller->multi . "ZONE_ATTRIBUT2\"]').each(function(){
                    if($(this).is(':checked')){
                        radioval2 = $(this).val();
                    }
                });
                radioval1 = '';
                $('input[name=\"" . $controller->multi . "ZONE_ATTRIBUT3\"]').each(function(){
                    if($(this).is(':checked')){
                        radioval1 = $(this).val();
                    }
                });
				
				if($('#form_button').val() =='state_5'){
					var tiltebo = $('#PAGE_TITLE_BO').val();
					if (tiltebo.indexOf('diff') !== -1) {
						return true;
					}
				}else{

					if(!radio1 && media1.innerHTML != '' && image1.innerHTML != ''){
						alert('" . t('ORDRE_POUR_PUSH_MEDIA', 'js') . " 1" . "');
							$('#FIELD_BLANKS').val(1);
					}
					if(!radio2 && media2.innerHTML != '' && image2.innerHTML != ''){
						alert('" . t('ORDRE_POUR_PUSH_MEDIA', 'js') . " 2" . "');
							$('#FIELD_BLANKS').val(1);
					}
					if(!radio3 && imageMulti != null){
						alert('" . t('ORDRE_POUR_PUSH_MEDIA', 'js') . " 3" . "');
							$('#FIELD_BLANKS').val(1);
					}

					if(radioval1 != ''){
						if(radioval1 == radioval2 || radioval1 == radioval3){
							alert('" . t('ORDRE_DIFFERENT_PUSH_MEDIA', 'js') . "');
							$('#FIELD_BLANKS').val(1);
						}
					}
					if(radioval2 != ''){
						if(radioval1 == radioval2 || radioval2 == radioval3){
							alert('" . t('ORDRE_DIFFERENT_PUSH_MEDIA', 'js') . "');
							$('#FIELD_BLANKS').val(1);
						}
					}
					if(radioval3 != ''){
						if(radioval1 == radioval3 || radioval2 == radioval3){
							alert('" . t('ORDRE_DIFFERENT_PUSH_MEDIA', 'js') . "');
							$('#FIELD_BLANKS').val(1);
						}
					}

					if(image1.innerHTML != '' && texte1.value == ''){
						alert('" . t('LIBELLE_OBLIGATOIRE_POUR_IMAGE', 'js') . " 1" . "');
						fwFocus(obj." . $controller->multi . "ZONE_TITRE15);
						$('#FIELD_BLANKS').val(1);
					}

					if(image2.innerHTML != '' && texte2.value == ''){
						alert('" . t('LIBELLE_OBLIGATOIRE_POUR_IMAGE', 'js') . " 2" . "');
						fwFocus(obj." . $controller->multi . "ZONE_TITRE20);
						$('#FIELD_BLANKS').val(1);
					}

					if(media1.innerHTML != '' && image1.innerHTML == ''){
						alert('" . t('IMAGE_OBLIGATOIRE_POUR_LA_VIDEO', 'js') . " 1" . "');
						fwFocus(obj." . $controller->multi . "MEDIA_ID7);
						$('#FIELD_BLANKS').val(1);
					}

					if(media2.innerHTML != '' && image2.innerHTML == ''){
						alert('" . t('IMAGE_OBLIGATOIRE_POUR_LA_VIDEO', 'js') . " 2" . "');
						fwFocus(obj." . $controller->multi . "MEDIA_ID8);
						$('#FIELD_BLANKS').val(1);
					}

					if(media1.innerHTML != '' && texte1.value == ''){
						alert('" . t('LIBELLE_OBLIGATOIRE_POUR_LA_VIDEO', 'js') . " 1" . "');
						fwFocus(obj." . $controller->multi . "ZONE_TITRE15);
						$('#FIELD_BLANKS').val(1);
					}

					if(media2.innerHTML != '' && texte2.value == ''){
						alert('" . t('LIBELLE_OBLIGATOIRE_POUR_LA_VIDEO', 'js') . " 2" . "');
						fwFocus(obj." . $controller->multi . "ZONE_TITRE20);
						$('#FIELD_BLANKS').val(1);
					}

					
					social = document.getElementById('" . $controller->multi . "ZONE_TITRE10');

					if(imageMulti != null && imageMulti.innerHTML != ''){
						if(social.options[social.selectedIndex].value == 0){
							alert('" . t("OBLIGATOIRE_SOCIAL", "js") . "');
							fwFocus(obj." . $controller->multi . "ZONE_TITRE10);
							$('#FIELD_BLANKS').val(1);
						}
					}
				}
            ");
        } else {// cas d'un contenus
            $aPushMedia .= $controller->oForm->createLabel(t("VIDEO_1"), "");
            $aPushMedia .= $controller->oForm->createMedia("MEDIA_ID3", t('SELECTIONNER_VIDEO'), false, "video", "", $controller->values["MEDIA_ID3"], $controller->readO, true, false);
            $aPushMedia .= $controller->oForm->createMedia("MEDIA_ID4", t('VIGNETTE_VIDEO'), false, "image", "", $controller->values["MEDIA_ID4"], $controller->readO, true, false, '16_9');
            $aPushMedia .= $controller->oForm->createInput("CONTENT_TITLE5", t('LIBELLE_VIDEO'), 255, "", false, $controller->values["CONTENT_TITLE5"], $controller->readO, 100);
            $aPushMedia .= $controller->oForm->createRadioFromList('CONTENT_CODE3', t('SELECTIONNER_ORDRE_VIDEO') . '1', $aOrdres, $controller->values["CONTENT_CODE3"], false, $oController->readO);
            $aPushMedia .= $controller->oForm->createLabel("", '<br/>');

            $aPushMedia .= $controller->oForm->createLabel(t("VIDEO_2"), "");
            $aPushMedia .= $controller->oForm->createMedia("MEDIA_ID5", t('SELECTIONNER_VIDEO'), false, "video", "", $controller->values["MEDIA_ID5"], $controller->readO, true, false);
            $aPushMedia .= $controller->oForm->createMedia("MEDIA_ID6", t('VIGNETTE_VIDEO'), false, "image", "", $controller->values["MEDIA_ID6"], $controller->readO, true, false, '16_9');
            $aPushMedia .= $controller->oForm->createInput("CONTENT_TITLE6", t('LIBELLE_VIDEO'), 255, "", false, $controller->values["CONTENT_TITLE6"], $controller->readO, 100);
            $aPushMedia .= $controller->oForm->createRadioFromList('CONTENT_CODE2', t('SELECTIONNER_ORDRE_VIDEO') . '2', $aOrdres, $controller->values["CONTENT_CODE2"], false, $oController->readO);

            $aPushMedia .= $controller->oForm->createLabel("", '<br/>');
            $aPushMedia .= self::getFormGroupeReseauxSociaux($controller, "CONTENT_TITLE8", "PUBLIC", false, true);
            $aPushMedia .= $controller->oForm->createLabel("", '<br/>');
            $aPushMedia .= $controller->oForm->createInput("CONTENT_TITLE7", t('SELECTIONNER_LIBELLE_GALERIE_PHOTO'), 255, "", false, $controller->values["CONTENT_TITLE7"], $controller->readO, 100);
            $aPushMedia .= $controller->oForm->createMedia("MEDIA_ID9", t('VIGNETTE_GALLERY'), false, "image", "", $controller->values["MEDIA_ID9"], $controller->readO, true, false, "16_9");
            $aPushMedia .= self::getGalleryPhoto($controller, true);
            $aPushMedia .= $controller->oForm->createRadioFromList('CONTENT_CODE', t('SELECTIONNER_ORDRE_GALLERIE'), $aOrdres, $controller->values["CONTENT_CODE"], false, $oController->readO);
            $aPushMedia .= $controller->oForm->createLabel("", '<br/>');

            //si une vidéo est saisie, le libelle doit également être saisie sinon une alerte est levée
            $aPushMedia .= $controller->oForm->createJS("

                var imageMulti = document.getElementById('count_GALLERYFORM');
                media1 = document.getElementById('divMEDIA_ID3');
                media2 = document.getElementById('divMEDIA_ID5');

                image1 = document.getElementById('divMEDIA_ID4');
                image2 = document.getElementById('divMEDIA_ID6');

                texte1 = document.getElementById('CONTENT_TITLE5');
                texte2 = document.getElementById('CONTENT_TITLE6');
                
                radio3 = $('input[name=\"CONTENT_CODE\"]').is(':checked');
                radio2 = $('input[name=\"CONTENT_CODE2\"]').is(':checked');
                radio1 = $('input[name=\"CONTENT_CODE3\"]').is(':checked');

                radioval3 = '';
                $('input[name=\"CONTENT_CODE\"]').each(function(){
                    if($(this).is(':checked')){
                        radioval3 = $(this).val();
                    }
                });
                radioval2 = '';
                $('input[name=\"CONTENT_CODE2\"]').each(function(){
                    if($(this).is(':checked')){
                        radioval2 = $(this).val();
                    }
                });
                radioval1 = '';
                $('input[name=\"CONTENT_CODE3\"]').each(function(){
                    if($(this).is(':checked')){
                        radioval1 = $(this).val();
                    }
                });
				
                if($('#form_button').val() =='state_5'){
                        //var tiltebo = $('#PAGE_TITLE_BO').val();
                        //if (tiltebo.indexOf('diff') !== -1) {
                                return true;
                        //}
                }else{

                if(!radio1 && media1.innerHTML != '' && image1.innerHTML != ''){
                    alert('" . t('ORDRE_POUR_PUSH_MEDIA', 'js') . " 1" . "');
                        $('#FIELD_BLANKS').val(1);
                }
                if(!radio2 && media2.innerHTML != '' && image2.innerHTML != ''){
                    alert('" . t('ORDRE_POUR_PUSH_MEDIA', 'js') . " 2" . "');
                        $('#FIELD_BLANKS').val(1);
                }
                if(!radio3 && imageMulti > -1){
                    alert('" . t('ORDRE_POUR_PUSH_MEDIA', 'js') . " 3" . "');
                       $('#FIELD_BLANKS').val(1);
                }

                if(radioval1 != ''){
                    if(radioval1 == radioval2 || radioval1 == radioval3){
                        alert('" . t('ORDRE_DIFFERENT_PUSH_MEDIA', 'js') . "');
                        $('#FIELD_BLANKS').val(1);
                    }
                }
                if(radioval2 != ''){
                    if(radioval1 == radioval2 || radioval2 == radioval3){
                        alert('" . t('ORDRE_DIFFERENT_PUSH_MEDIA', 'js') . "');
                        $('#FIELD_BLANKS').val(1);
                    }
                }
                if(radioval3 != ''){
                    if(radioval1 == radioval3 || radioval2 == radioval3){
                        alert('" . t('ORDRE_DIFFERENT_PUSH_MEDIA', 'js') . "');
                        $('#FIELD_BLANKS').val(1);
                    }
                }
                
                if(image1.innerHTML != '' && texte1.value == ''){
                    alert('" . t('LIBELLE_OBLIGATOIRE_POUR_IMAGE', 'js') . " 1" . "');
                    fwFocus(obj.CONTENT_TITLE5);
                    $('#FIELD_BLANKS').val(1);
                }

                if(image2.innerHTML != '' && texte2.value == ''){
                    alert('" . t('LIBELLE_OBLIGATOIRE_POUR_IMAGE', 'js') . " 2" . "');
                    fwFocus(obj.CONTENT_TITLE6);
                    $('#FIELD_BLANKS').val(1);
                }

                if(media1.innerHTML != '' && image1.innerHTML == ''){
                    alert('" . t('IMAGE_OBLIGATOIRE_POUR_LA_VIDEO', 'js') . " 1" . "');
                    fwFocus(obj.MEDIA_ID3);
                    $('#FIELD_BLANKS').val(1);
                }

                if(media2.innerHTML != '' && image2.innerHTML == ''){
                    alert('" . t('IMAGE_OBLIGATOIRE_POUR_LA_VIDEO', 'js') . " 2" . "');
                    fwFocus(obj.MEDIA_ID5);
                    $('#FIELD_BLANKS').val(1);
                }

                if(media1.innerHTML != '' && texte1.value == ''){
                    alert('" . t('LIBELLE_OBLIGATOIRE_POUR_LA_VIDEO', 'js') . " 1" . "');
                    fwFocus(obj.CONTENT_TITLE5);
                    $('#FIELD_BLANKS').val(1);
                }

                if(media2.innerHTML != '' && texte2.value == ''){
                    alert('" . t('LIBELLE_OBLIGATOIRE_POUR_LA_VIDEO', 'js') . " 2" . "');
                    fwFocus(obj.CONTENT_TITLE6);
                    $('#FIELD_BLANKS').val(1);
                }

                /*var imageMulti = document.getElementById('div" . $controller->multi . "GALLERYFORM0_MEDIA_ID');
                var social = document.getElementById('" . $controller->multi . "ZONE_TITRE10');

                if(imageMulti != null && imageMulti.innerHTML != ''){
                    if(social.options[social.selectedIndex].value == 0){
                        alert('" . t("OBLIGATOIRE_SOCIAL", "js") . "');
                        fwFocus(obj." . $controller->multi . "ZONE_TITRE10);
                        $('#FIELD_BLANKS').val(1);
                    }
                   }*/
				}
            ");
        }

        return $aPushMedia;
    }

    public static function getGalleryPhoto($controller, $bContent = false) {
        $oConnection = Pelican_Db::getInstance();

        $gallery = "";


        if (!$bContent) {

            $multiValues = self::getPageZoneMulti($controller, 'GALLERYFORM');

            $gallery .= $controller->oForm->createMultiHmvc($controller->multi . "GALLERYFORM", t('ADD_FORM_GALLERY'), array(
                "path" => Pelican::$config['APPLICATION_VIEW_HELPERS'] . "/Form.php",
                "class" => "Backoffice_Form_Helper",
                "method" => "galleryAddForm"
                    ), $multiValues, $controller->multi . "GALLERYFORM", $controller->readO, $nb/* array(1, $nb) */, true, true, $controller->multi . "GALLERYFORM");
        } else {

            $gallery .= $controller->oForm->createMultiHmvc("GALLERYFORM", t('ADD_FORM_GALLERY'), array(
                "path" => Pelican::$config['APPLICATION_VIEW_HELPERS'] . "/Form.php",
                "class" => "Backoffice_Form_Helper",
                "method" => "galleryAddFormContent"
                    ), self::getContentZoneMultiValues($controller, $controller->values["CONTENT_ID"], 'GALLERYFORM'), "GALLERYFORM", $controller->readO, $nb/* array(1, $nb) */, true, true, "GALLERYFORM");
        }
        return $gallery;
    }

    public static function galleryAddForm($oForm, $values, $readO, $multi) {
        $medias .= $oForm->createMedia($multi . "MEDIA_ID", t('MEDIA'), true, "image", "", $values["MEDIA_ID"], $readO, true, false, '16_9');

        return $medias;
    }

    public static function galleryAddFormContent($oForm, $values, $readO, $multi) {
        $medias .= $oForm->createMedia($multi . "MEDIA_ID", t('MEDIA'), true, "image", "", $values["MEDIA_ID"], $readO, true, false);

        return $medias;
    }

    /**
     * Méthode statique d'ajout de la partie "push media" pour un contenu
     * @param Objet controller     $oController    Objet controller pour la
     *                                             création de formulaire
     * @return string               Partie du formulaire contenant le Push Media
     */
    public static function getContentPushMedia(Pelican_Controller $oController) {
        $sContentZoneType = 'PUSH_MEDIA';
        $sGalleryType = 'GALLERYFORM';

        /* Recherche des Push Media pour le contenu */
        $aAllPushMedia = self::getContentZoneValues($oController, $sContentZoneType);
        $aPushMedia = array_shift($aAllPushMedia);
        /* Liste des groupe sociaux */
        $aSocialGroupValues = array();

        $sContentPushMedia = $oController->oForm->showSeparator('formSep');
        $sContentPushMedia .= $oController->oForm->createLabel(t('PUSH_MEDIA'), '');
        /* Libellé du Push media */
        $sContentPushMedia .= $oController->oForm->createInput($sContentZoneType . 'CONTENT_ZONE_TITLE', t('TITRE'), 255, '', false, $aPushMedia['CONTENT_ZONE_TITLE'], $oController->readO, 100);
        /* Vignette de la vidéo du Push media */
        $sContentPushMedia .= $oController->oForm->createMedia($sContentZoneType . 'MEDIA_ID2', t('VIDEO'), false, 'video', '', $aPushMedia['MEDIA_ID2'], $oController->readO, true, false);
        /* Vidéo du Push media */
        $sContentPushMedia .= $oController->oForm->createMedia($sContentZoneType . 'MEDIA_ID3', t('VIGNETTE_VIDEO'), false, 'image', '', $aPushMedia['MEDIA_ID3'], $oController->readO, true, false);
        /* Type de regroupement de réseaux sociaux */
        $sContentPushMedia .= $oController->oForm->createComboFromList($sContentZoneType . 'CONTENT_ZONE_TITLE2', t('SOCIAL_GROUP'), $aSocialGroupValues, $aPushMedia['CONTENT_ZONE_TITLE2'], false, $oController->readO);
        /* Galerie photo */
        $sContentPushMedia .= self::getContentGalleryPhoto($oController, $aPushMedia['CONTENT_ZONE_ID'], $sGalleryType);


        return $sContentPushMedia;
    }

    public static function getContentGalleryPhoto(Pelican_Controller $oController, $iContentZoneId, $sContentMultiZoneType) {
        /* Initialisation des variables */
        $sContentGalleryPhoto = '';

        $sMultiField = $sContentMultiZoneType . $iContentZoneId . 'GALLERYFORM';

        $sContentGalleryPhoto .= $oController->oForm->createMultiHmvc(
                $sMultiField, t('ADD_FORM_GALLERY'), array(
            'path' => __FILE__,
            'class' => __CLASS__,
            'method' => 'contentGalleryAddForm'
                ), getContentZoneMultiValues($oController, $iContentZoneId, $sContentMultiZoneType), $sMultiField, $oController->readO, '', true, true, $sMultiField
        );

        return $sContentGalleryPhoto;
    }

    public static function contentGalleryAddForm($oForm, $aValues, $bReadO, $sPrefixField) {
        /* Initialisation des variables */
        $sGalleryPhoto = '';
        $sGalleryPhoto .= $oForm->createMedia($sPrefixField . 'MEDIA_ID', t('MEDIA'), false, 'image', '', $aValues["MEDIA_ID"], $bReadO, true, false);

        return $sGalleryPhoto;
    }

    /**
     * Méthode statique récupérant les enregistrements présent dans content_zone
     * pour un contenu, une langue, une version
     *
     * Si un type de content_zone est passé en paramètre seuls les éléments de ce
     * type rattachés au contenu en cours seront remontés.
     *
     * @param Pelican_Controller     $oController    Objet controller pour la
     *                                              création de formulaire
     * @param NULL/string            $mContentZoneType
     *                                               Type de content_zone
     * @return array                 Tableau des content_zone (en fonction d'un
     *                               type ou non )
     */
    public static function getContentZoneValues(Pelican_Controller $oController, $mContentZoneType = null) {
        /* Initialisation des variables */
        $aContentZone = array();
        if (!is_null($mContentZoneType)) {
            $mContentZoneType = (string) $mContentZoneType;
        }

        $oConnection = Pelican_Db::getInstance();

        if (is_array($oController->values) && !empty($oController->values)) {
            $aBind[':LANGUE_ID'] = $oController->values['LANGUE_ID'];
            $aBind[':CONTENT_ID'] = $oController->values['CONTENT_ID'];
            $aBind[':CONTENT_VERSION'] = $oController->values['CONTENT_VERSION'];
            if (!is_null($mContentZoneType)) {
                $aBind[':CONTENT_ZONE_TYPE'] = $oConnection->strToBind($sContentZoneType);
            }
            /* Requête de récupération des content_zone rattaché au contenu */
            $sSql = <<<SQL
                    SELECT
                        cz.*
                    FROM
                        #pref#_content_zone cz
                    WHERE
                        cz.CONTENT_ID = :CONTENT_ID
                        AND cz.LANGUE_ID = :LANGUE_ID
                        AND cz.CONTENT_VERSION = :CONTENT_VERSION
SQL;
            /* Si un type de content_zone est passé en paramètre on filtre sur
             * ce type
             */
            if (!is_null($mContentZoneType)) {
                $sSql .= <<<SQL
                        AND cz.CONTENT_ZONE_TYPE = :CONTENT_ZONE_TYPE
SQL;
            }
            $sSql .= <<<SQL
                    ORDER BY
                        CONTENT_ZONE_TYPE, CONTENT_ZONE_ORDER
SQL;
            $aContentZone = $oConnection->queryTab($sSql, $aBind);
        }
        return $aContentZone;
    }

    /**
     * Méthode statique récupérant les enregistrements présent dans content_zone
     * pour un contenu, une langue, une version
     *
     * Si un type de content_zone est passé en paramètre seuls les éléments de ce
     * type rattachés au contenu en cours seront remontés.
     *
     * @param Pelican_Controller     $oController    Objet controller pour la
     *                                              création de formulaire
     * @param NULL/string            $mContentZoneType
     *                                               Type de content_zone
     * @return array                 Tableau des content_zone (en fonction d'un
     *                               type ou non )
     */

    /**
     * Méthode statique récupérant les enregistrements présents dans content_zone_multi
     * pour un contenu, une langue, une version, un content_zone et un type
     *
     * Si un type de content_zone_multi est passé en paramètre seuls les éléments de ce
     * type rattachés au content et content_zone en cours seront remontés.
     *
     * @param Pelican_Controller    $oController    Objet controller pour la
     *                                                  création de formulaire
     * @param int                   $iContentZoneId
     *                                              Identifiant de content_zone
     * @param NULL/string           $mContentZoneMultiType
     *                                              Type de multi à remonter pour
     *                                              la content_zone passée en paramètre
     * @return array                 Tableau des content_zone_multi (en fonction d'un
     *                               type ou non )
     */
    public static function getContentZoneMultiValues(Pelican_Controller $oController, $iContentId, $mContentZoneMultiType = '') {
        /* Initialisation des variables */
        $aContentZoneMulti = array();
        $oConnection = Pelican_Db::getInstance();

        if (is_array($oController->values) && !empty($oController->values)) {
            $aBind[':LANGUE_ID'] = $oController->values['LANGUE_ID'];
            $aBind[':CONTENT_VERSION'] = $oController->values['CONTENT_VERSION'];
            $aBind[':CONTENT_ID'] = $iContentId;
            if (!is_null($mContentZoneMultiType)) {
                $aBind[':CONTENT_ZONE_MULTI_TYPE'] = $oConnection->strToBind($mContentZoneMultiType);
            }

            /* SELECT *
              FROM psa_content_zone_multi czm
              WHERE czm.CONTENT_ID = 1775
              AND czm.LANGUE_ID = 1
              AND czm.CONTENT_VERSION = 32
              AND czm.CONTENT_ZONE_MULTI_TYPE = 'VISUELFORFAIT'
              ORDER BY CONTENT_ZONE_MULTI_TYPE, CONTENT_ZONE_MULTI_ORDER */

            /* Requête de récupération des content_zone rattaché au contenu */
            $sSql = <<<SQL
                    SELECT
                        czm.*,
                        czm.CONTENT_ZONE_MULTI_ORDER as PAGE_ZONE_MULTI_ORDER
                    FROM
                        #pref#_content_zone_multi czm
                    WHERE
                        czm.CONTENT_ID = :CONTENT_ID
                        AND czm.LANGUE_ID = :LANGUE_ID
                        AND czm.CONTENT_VERSION = :CONTENT_VERSION
SQL;
            /* Si un type de content_zone est passé en paramètre on filtre sur
             * ce type
             */
            if (!is_null($mContentZoneMultiType)) {
                $sSql .= <<<SQL
                        AND czm.CONTENT_ZONE_MULTI_TYPE = :CONTENT_ZONE_MULTI_TYPE
SQL;
            }
            $sSql .= <<<SQL
                    ORDER BY
                        CONTENT_ZONE_MULTI_TYPE, CONTENT_ZONE_MULTI_ORDER
SQL;
            $aContentZoneMulti = $oConnection->queryTab($sSql, $aBind);
        }
        return $aContentZoneMulti;
    }

    public static function getCta($controller, $nb = "") {

        $multiValues = self::getPageZoneMulti($controller, 'CTAFORM');

        $aCta .= $controller->oForm->createMultiHmvc($controller->multi . "CTAFORM", t('ADD_FORM_CTA'), array(
            "path" => Pelican::$config['APPLICATION_VIEW_HELPERS'] . "/Form.php",
            "class" => "Backoffice_Form_Helper",
            "method" => "ctaAddForm"
                ), $multiValues, $controller->multi . "CTAFORM", $controller->readO, $nb, true, true, $controller->multi . "CTAFORM");

        return $aCta;
    }

    public static function ctaAddForm($oForm, $values, $readO, $multi) {
        $aDataValues = Pelican::$config['TRANCHE_COL']["BLANK_SELF"];
        $oConnection = Pelican_Db::getInstance();
        $aBind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];

        $sql = 'SELECT
                    BARRE_OUTILS_ID ID,
                    BARRE_OUTILS_LABEL LIB
                FROM 
                    #pref#_barre_outils
                WHERE
                    BARRE_OUTILS_MODE_OUVERTURE IN (1,2)
                GROUP BY SITE_ID, LANGUE_ID, ID
                HAVING 
                    SITE_ID = :SITE_ID 
                AND LANGUE_ID = :LANGUE_ID';
        $val = $oConnection->queryTab($sql, $aBind);

        foreach ($val as $outil) {
            $aDataOutilWeb[$outil['ID']] = $outil['LIB'];
        }


        $return .= $oForm->createComboFromList($multi . "PAGE_ZONE_MULTI_ATTRIBUT", t("CTA_OUTIL"), $aDataOutilWeb, $values["PAGE_ZONE_MULTI_ATTRIBUT"], false, $readO);
        $return .= $oForm->showSeparator("formSep");
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_LABEL", t('LIBELLE'), 40, "", false, $values["PAGE_ZONE_MULTI_LABEL"], $readO, 100);
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_URL", t('URL_WEB'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL"], $readO, 100);
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_URL2", t('URL_MOB'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL2"], $readO, 100);
        $return .= $oForm->createComboFromList($multi . "PAGE_ZONE_MULTI_VALUE", t("MODE_OUVERTURE"), $aDataValues, strtoupper($values["PAGE_ZONE_MULTI_VALUE"]), false, $readO);

        return $return;
    }

    public static function getCtaHmvc($oForm, $values, $readO, $multi) {
        $oConnection = Pelican_Db::getInstance();
        if ($values) {
            $multiValues = self::getPageZoneMultiHmvc($values, 'CTAFORM');
        }
        $nb = 3;

        $aCta .= $oForm->createMultiHmvc($multi . "CTAFORM", t('ADD_FORM_CTA'), array(
            "path" => Pelican::$config['APPLICATION_VIEW_HELPERS'] . "/Form.php",
            "class" => "Backoffice_Form_Helper",
            "method" => "ctaAddFormHmvc"
                ), $multiValues, $multi . "CTAFORM", $readO, $nb, true, true, $multi . "CTAFORM");


        return $aCta;
    }

    public static function ctaAddFormHmvc($oForm, $values, $readO, $multi) {
        $aDataValues = Pelican::$config['TRANCHE_COL']["BLANK_SELF"];

        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_LABEL", t('LIBELLE'), 255, "", true, $values["PAGE_ZONE_MULTI_LABEL"], $readO, 100);
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_URL", t('URL_WEB'), 255, "internallink", true, $values["PAGE_ZONE_MULTI_URL"], $readO, 100);
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_URL2", t('URL_MOB'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL2"], $readO, 100);
        $return .= $oForm->createComboFromList($multi . "PAGE_ZONE_MULTI_VALUE", t("MODE_OUVERTURE"), $aDataValues, $values["PAGE_ZONE_MULTI_VALUE"], false, $readO);

        return $return;
    }

    /* Champ commun languette
     * @param array $controller
     * @return string $return
     */

    public static function getAssistance($controller) {
        $aPushAssistance = $controller->oForm->showSeparator("formSep");
        $aPushAssistance .= $controller->oForm->createLabel(t("ASSISTANCE"), "");
        $aPushAssistance .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE12", t('LIBELLE'), 255, "", false, $controller->zoneValues["ZONE_TITRE12"], $controller->readO, 100);
        $aPushAssistance .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE13", t('NUM_TEL'), 100, "", false, $controller->zoneValues["ZONE_TITRE13"], $controller->readO, 100);
        $aPushAssistance .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE14", t('TARIF_APP'), 100, "", false, $controller->zoneValues["ZONE_TITRE14"], $controller->readO, 100);

        return $aPushAssistance;
    }

    /* Champ commun languette
     * @param array $controller
     * @return string $return
     */

    public static function getLanguette($controller) {
        $aRadio = Pelican::$config['TRANCHE_COL']["YES_NO"];

        $aDataValues[1] = 1;
        $aDataValues[2] = 2;
        $aDataValues[3] = 3;

        $languette = $controller->oForm->showSeparator("formSep");
        $languette .= $controller->oForm->createLabel(t("LANGUETTE"), "");
        $languette .= $controller->oForm->createRadioFromList($controller->multi . "ZONE_LANGUETTE", t('ACTIVER_LANGUETTE'), $aRadio, $controller->zoneValues["ZONE_LANGUETTE"]);
        $languette .= $controller->oForm->createComboFromList($controller->multi . "ZONE_TITRE17", t("MODE_OUVERTURE"), $aDataValues, $controller->zoneValues["ZONE_TITRE17"], false, $readO);
		$languette .= $controller->oForm->createInput($controller->multi."ZONE_LANGUETTE_TEXTE_OPEN", t('ZONE_LANGUETTE_TEXTE_OPEN'), 255, "", false, $controller->zoneValues["ZONE_LANGUETTE_TEXTE_OPEN"], $controller->readO, 75);
		$languette .= $controller->oForm->createInput($controller->multi."ZONE_LANGUETTE_TEXTE_CLOSE", t('ZONE_LANGUETTE_TEXTE_CLOSE'), 255, "", false, $controller->zoneValues["ZONE_LANGUETTE_TEXTE_CLOSE"], $controller->readO, 75);
		

        return $languette;
    }

    /* Champ commun en fin de formulaire
     * @param array $controller
     * @return string $return
     */

    public static function getFormCommunEnd($controller, $nbCta = 3, $type = '') {
        $return .= self::getMentionsLegales($controller, false, 'cinemascope');
        $return .= self::getPushMediaCommun($controller);
        $return .= self::getCta($controller, $nbCta);
        $return .= self::getLanguette($controller);

        return $return;
    }

    /* Champ commun en fin de formulaire
     * @param array $controller
     * @return string $return
     */

    public static function getFormCommunEndMentionCta($controller, $nbCta = 3) {
        $return .= self::getMentionsLegales($controller, false, 'cinemascope');
        $return .= self::getCta($controller, $nbCta);

        return $return;
    }

    public static function saveCta() {
        $oConnection = Pelican_Db::getInstance();
        if (Pelican_Db::$values["form_action"] != Pelican_Db::DATABASE_DELETE) {
            /*
             * CTA
             */

            self::deletePageZoneMulti('CTAFORM');

            $aSiteAddMulti = Backoffice_Form_Helper::myReadMulti(Pelican_Db::$values, "CTAFORM");

            foreach ($aSiteAddMulti as $aSiteInfos) {
                //Vérification de la prise en compte du le multi
                if ($aSiteInfos['multi_display'] == 1) {
                    $saveValues = Pelican_Db::$values;
                    Pelican_Db::$values = array();

                    //if(strpos($aSiteInfos['PAGE_ZONE_MULTI_LABEL'], '<') || strpos($aSiteInfos['PAGE_ZONE_MULTI_LABEL'], '>'))
                    $id++;
                    Pelican_Db::$values["PAGE_ZONE_MULTI_TYPE"] = 'CTAFORM';
                    Pelican_Db::$values["PAGE_ZONE_MULTI_ATTRIBUT"] = $aSiteInfos['PAGE_ZONE_MULTI_ATTRIBUT'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_LABEL"] = htmlspecialchars($aSiteInfos['PAGE_ZONE_MULTI_LABEL'], ENT_QUOTES);
                    Pelican_Db::$values["PAGE_ZONE_MULTI_URL"] = $aSiteInfos['PAGE_ZONE_MULTI_URL'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_URL2"] = $aSiteInfos['PAGE_ZONE_MULTI_URL2'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_VALUE"] = strtolower($aSiteInfos['PAGE_ZONE_MULTI_VALUE']);
                    Pelican_Db::$values["PAGE_ZONE_MULTI_ID"] = $id;

                    if (!empty($aSiteInfos['PAGE_ZONE_MULTI_ORDER'])) {
                        Pelican_Db::$values["PAGE_ZONE_MULTI_ORDER"] = $aSiteInfos['PAGE_ZONE_MULTI_ORDER'];
                    }

                    self::addPageZoneMulti($saveValues);

                    Pelican_Db::$values = $saveValues;
                }
            }
        }
    }

    public static function saveCtaHmvc() {
        $oConnection = Pelican_Db::getInstance();
        if (Pelican_Db::$values["form_action"] != Pelican_Db::DATABASE_DELETE) {
            self::deletePageZoneMultiMulti(Pelican_Db::$values, 'CTAFORM');
            $aSiteAddMulti = Backoffice_Form_Helper::myReadMulti(Pelican_Db::$values, "CTAFORM");
            foreach ($aSiteAddMulti as $aSiteInfos) {
                //Vérification de la prise en compte du le multi
                if ($aSiteInfos['multi_display'] == 1) {
                    $saveValues = Pelican_Db::$values;
                    Pelican_Db::$values = array();

                    Pelican_Db::$values["PAGE_ZONE_MULTI_TYPE"] = 'CTAFORM';
                    Pelican_Db::$values["PAGE_ZONE_MULTI_LABEL"] = $aSiteInfos['PAGE_ZONE_MULTI_LABEL'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_URL"] = $aSiteInfos['PAGE_ZONE_MULTI_URL'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_URL2"] = $aSiteInfos['PAGE_ZONE_MULTI_URL2'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_VALUE"] = $aSiteInfos['PAGE_ZONE_MULTI_VALUE'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_ID"] = $saveValues['PAGE_ZONE_MULTI_ID'];
                    Pelican_Db::$values["ZONE_TEMPLATE_ID"] = $saveValues['ZONE_TEMPLATE_ID'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_ORDER"] = $aSiteInfos['PAGE_ZONE_MULTI_ORDER'];
                    $id++;
                    Pelican_Db::$values["PAGE_ZONE_MULTI_MULTI_ID"] = $id;

                    //Correction temporaire en attendant que le myReadMulti fonctionne correctemement
                    if (!empty(Pelican_Db::$values["PAGE_ZONE_MULTI_LABEL"]) || !empty($aSiteInfos['PAGE_ZONE_MULTI_URL']) || !empty($aSiteInfos['PAGE_ZONE_MULTI_URL2']) || !empty(Pelican_Db::$values["PAGE_ZONE_MULTI_VALUE"])) {
                        self::addPageZoneMultiMulti($saveValues);
                    }
                    Pelican_Db::$values = $saveValues;
                }
            }
        }
    }

    public static function savePushGallery() {
        $oConnection = Pelican_Db::getInstance();
        if (Pelican_Db::$values["form_action"] != Pelican_Db::DATABASE_DELETE) {
            //Push media gallery
            $aSiteAddMulti = Backoffice_Form_Helper::myReadMulti(Pelican_Db::$values, "GALLERYFORM");

            self::deletePageZoneMulti('GALLERYFORM');


            foreach ($aSiteAddMulti as $aSiteInfos) {
                //Vérification de la prise en compte du le multi
                if ($aSiteInfos['multi_display'] == 1) {
                    $saveValues = Pelican_Db::$values;
                    Pelican_Db::$values = array();

                    Pelican_Db::$values["PAGE_ZONE_MULTI_TYPE"] = 'GALLERYFORM';
                    Pelican_Db::$values["PAGE_ZONE_MULTI_ORDER"] = $aSiteInfos['PAGE_ZONE_MULTI_ORDER'];
                    Pelican_Db::$values["MEDIA_ID"] = $aSiteInfos['MEDIA_ID'];

                    $id++;
                    Pelican_Db::$values["PAGE_ZONE_MULTI_ID"] = $id;


                    self::addPageZoneMulti($saveValues);

                    Pelican_Db::$values = $saveValues;
                }
            }
        }
    }

    public static function saveMultiColumn() {
        $oConnection = Pelican_Db::getInstance();
        if (Pelican_Db::$values["form_action"] != Pelican_Db::DATABASE_DELETE) {
            /*
             * MultiColonneSimple
             */
            $aSiteAddMulti = Backoffice_Form_Helper::myReadMulti(Pelican_Db::$values, "ADDCOLFORM");

            self::deletePageZoneMulti('ADDCOLFORM');



            foreach ($aSiteAddMulti as $aSiteInfos) {
                //Vérification de la prise en compte du le multi
                if ($aSiteInfos['multi_display'] == 1) {
                    $saveValues = Pelican_Db::$values;
                    Pelican_Db::$values = array();

                    Pelican_Db::$values["PAGE_ZONE_MULTI_TYPE"] = 'ADDCOLFORM';

                    Pelican_Db::$values["PAGE_ZONE_MULTI_LABEL"] = $aSiteInfos['PAGE_ZONE_MULTI_LABEL'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_TEXT"] = $aSiteInfos['PAGE_ZONE_MULTI_TEXT'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_LABEL2"] = $aSiteInfos['PAGE_ZONE_MULTI_LABEL2'];
                    Pelican_Db::$values["MEDIA_ID"] = $aSiteInfos['MEDIA_ID'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_URL"] = $aSiteInfos['PAGE_ZONE_MULTI_URL'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_URL2"] = $aSiteInfos['PAGE_ZONE_MULTI_URL2'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_VALUE"] = $aSiteInfos['PAGE_ZONE_MULTI_VALUE'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_ORDER"] = $aSiteInfos['PAGE_ZONE_MULTI_ORDER'];
                    // CTA
                    Pelican_Db::$values["PAGE_ZONE_MULTI_ATTRIBUT"] = $aSiteInfos['PAGE_ZONE_MULTI_ATTRIBUT'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_LABEL3"] = $aSiteInfos['PAGE_ZONE_MULTI_LABEL3'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_URL3"] = $aSiteInfos['PAGE_ZONE_MULTI_URL3'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_URL4"] = $aSiteInfos['PAGE_ZONE_MULTI_URL4'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_VALUE2"] = $aSiteInfos['PAGE_ZONE_MULTI_VALUE2'];
                    
                    Pelican_Db::$values["PAGE_ZONE_MULTI_ATTRIBUT2"] = $aSiteInfos['PAGE_ZONE_MULTI_ATTRIBUT2'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_LABEL4"] = $aSiteInfos['PAGE_ZONE_MULTI_LABEL4'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_URL5"] = $aSiteInfos['PAGE_ZONE_MULTI_URL5'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_URL6"] = $aSiteInfos['PAGE_ZONE_MULTI_URL6'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_VALUE3"] = $aSiteInfos['PAGE_ZONE_MULTI_VALUE3'];
                    
                    $id++;
                    Pelican_Db::$values["PAGE_ZONE_MULTI_ID"] = $id;



                    self::addPageZoneMulti($saveValues);

                    Pelican_Db::$values = $saveValues;
                }
            }
        }
    }

    public static function saveMultiPicto() {
        if (Pelican_Db::$values["form_action"] != Pelican_Db::DATABASE_DELETE) {
            /*
             * MultiColonneSimple
             */
            $aSiteAddMulti = Backoffice_Form_Helper::myReadMulti(Pelican_Db::$values, "ADDLISTPICTO");

            self::deletePageZoneMulti('ADDLISTPICTO');



            foreach ($aSiteAddMulti as $aSiteInfos) {
                //Vérification de la prise en compte du le multi
                if ($aSiteInfos['multi_display'] == 1) {
                    $saveValues = Pelican_Db::$values;
                    Pelican_Db::$values = array();

                    Pelican_Db::$values["PAGE_ZONE_MULTI_TYPE"] = 'ADDLISTPICTO';

                    Pelican_Db::$values["PAGE_ZONE_MULTI_LABEL"] = $aSiteInfos['PAGE_ZONE_MULTI_LABEL'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_TEXT"] = $aSiteInfos['PAGE_ZONE_MULTI_TEXT'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_LABEL2"] = $aSiteInfos['PAGE_ZONE_MULTI_LABEL2'];
                    Pelican_Db::$values["MEDIA_ID"] = $aSiteInfos['MEDIA_ID'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_URL"] = $aSiteInfos['PAGE_ZONE_MULTI_URL'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_URL2"] = $aSiteInfos['PAGE_ZONE_MULTI_URL2'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_VALUE"] = $aSiteInfos['PAGE_ZONE_MULTI_VALUE'];
                    Pelican_Db::$values["PAGE_ZONE_MULTI_ORDER"] = $aSiteInfos['PAGE_ZONE_MULTI_ORDER'];

                    $id++;
                    Pelican_Db::$values["PAGE_ZONE_MULTI_ID"] = $id;



                    self::addPageZoneMulti($saveValues);

                    Pelican_Db::$values = $saveValues;
                }
            }
        }
    }

    /* Fonction de lecture des multis */

    public static function myReadMulti($table, $prefixe) {
        $aMulti = array();
        if (is_array($table) && !empty($table)) {
            foreach ($table as $key => $value) {
                $iPrefixeLenght = strlen($prefixe);
                if (substr($key, 0, $iPrefixeLenght) === $prefixe) {
                    $aTemp = explode('_', $key);
                    $index = substr($aTemp[0], $iPrefixeLenght);
                    $rest = substr(strstr($key, '_'), 1);
                    if ($rest && $index != '__CPT__' && $rest != 'subFormJS') {
                        $aMulti[$index][$rest] = $value;
                    }
                }
            }
        }

        return $aMulti;
    }
    
    /**
     * Lecture du multi nommé $prefix depuis les données à plat $data (qui contient des champs préfixés et indéxés)
     * Identique à Backoffice_Form_Helper::myReadMulti, sauf qu'elle fonctionne aussi quand le préfixe contient un underscore...
     */
    public static function standaloneReadMulti($data, $prefix)
    {
        // Contrôle des arguments
        if (empty($data) || !is_array($data)) {
            trigger_error('$data must be an array', E_USER_WARNING);
            return false;
        }
        if (empty($prefix) || !is_string($prefix)) {
            trigger_error('Bad prefix', E_USER_WARNING);
            return false;
        }
        
        // Lecture des données (vérification du préfixe du champ courant + extraction index et nom du champ)
        $result = array();
        $quotedPrefix = preg_quote($prefix, '/');
        foreach ($data as $key => $val) {
            if (!preg_match('/^'.$quotedPrefix.'(\d+)_(.*)/', $key, $matches)) {
                continue;
            }
            $result[$matches[1]][$matches[2]] = $val;
        }
        return $result;
    }

    /**
     * Liste déroulante d'ajout des types de contenus suivant les droits associés à l'utilisateur
     *
     */
    public static function getContentCombo($id) {
        ?>
        <script type="text/javascript">
            var iContentTypeID = '<?= $_GET["rechercheContentType"] ?>';
            var sPageIndex = '<?= Pelican::$config["PAGE_INDEX_IFRAME_PATH"] ?>';
            var sTid = '<?= $_GET["tid"] ?>';
            var sView = '<?= $_REQUEST["view"] ?>';
            var iDbInsert = '<?= Pelican::$config["DATABASE_INSERT_ID"] ?>';
            var bPopup = '<?= $_GET["popup_content"] ?>';
            function changeLocation(id) {
                var bMutualisation = false;
                var sHref = '';

                if (!id) {
                    iContentTypeID = document.getElementById('CONTENT_TYPE_ID').value;
                } else {
                    /** Cas de la mutualisation */
                    bMutualisation = id;
                }
                sHref = sPageIndex + '?&tid=' + sTid + '&view=' + sView + '&popup_content=' + bPopup + '&uid=' + iContentTypeID;

                sHref += '&id=' + iDbInsert;
                if (bMutualisation) {
                    sHref += '&mutualisation=' + bMutualisation;
                }

                if (iContentTypeID) {
                    top.getIFrameDocument('iframeRight').location.href = sHref;
                }
            }
            document.changeLocation = changeLocation;
        </script>
        <?php
        if (!$id) {
            $aTypeContenus = getComboValuesFromCache("Backend/ContentType", array(
                $_SESSION[APP]['SITE_ID'],
                "",
                implode(",", $_SESSION[APP]["content_type"]["id"]),
                true
            ));
            if ($_GET["rechercheContentType"] && $aTypeContenus[$_GET["rechercheContentType"]]) {
                echo Pelican_Html::div(Pelican_Html::button(array(
                            onclick => "changeLocation(-2)"
                                ), "<b>Ajouter : " . $aTypeContenus[$_GET["rechercheContentType"]] . "</b>")); //"onchange=changeLocation()");
            } else {
                $oForm = Pelican_Factory::getInstance('Form', true);
                $oForm->open("", "post", "fForm", false, true, "CheckForm", "", true, false);
                beginFormTable();
                $oForm->createComboFromList("CONTENT_TYPE_ID", "Créer un contenu de type : ", $aTypeContenus, "", false, false, "1", false, "165", true, false, "onchange=changeLocation()");
                endFormTable();
                $oForm->close();
            }
        }
    }

    /**
     * Méthode de création de tableau associatif en utilisant 2 requêtes SQL et
     * 2 tableaux de bind
     * @param objet $oController            Objet controller
     * @param string $sName                 Nom du champ dans le Formulaire HTML
     * @param string $sLabel                Libellé du champ dans le Formulaire HTML
     * @param string $sSqlAllValues         Requête SQL remontant l'ensemble des
     *                                      valeurs à dispo
     * @param array $aBindAllValues         Tableau de bind pour la requête SQL
     *                                      remontant l'ensemble des valeurs à dispo
     * @param string $sSqlSelectedValues    Requête SQL remontant l'ensemble des
     *                                      valeurs sélectionnées
     * @param array $aBindSelected          Tableau de bind pour la requête SQL
     *                                      remontant l'ensemble des valeurs sélectionnées
     * @param boolean $bRequired            La sélection dans le tableau associatif
     *                                      est-elle obligatoire
     * @param boolean $bDeleteOnAdd         Suppression des données dans la colonnes
     *                                      de droite une fois sélectionnées
     * @param boolean $bReadOnly            Mode lecture du tableau associatif
     * @param string $iSize                 Nombre de ligne du tableau associatif
     * @param int $iWidth                   Largeur du tableau associatif
     * @param boolean $bFormOnly            Si true ne ramène que le tableau
     *                                      associatif dans les deux colonnes de tableaus
     *                                      Sinon ramène le Tableau associatif
     *                                      dans les deux colonnes du formulaire classique
     * @return type string                  Tableau associatif généré
     */
    public static function createSimpleAssocFromSQL($oController, $sName, $sLabel, $sSqlAllValues = '', $aBindAllValues = array(), $sSqlSelectedValues = '', $aBindSelected = array(), $bRequired = false, $bDeleteOnAdd = true, $bReadOnly = false, $iSize = '5', $iWidth = 200, $bFormOnly = false) {
        /* Initialisation des variables */
        $sName = (string) $sName;
        $sLabel = (string) $sLabel;
        $sSqlAllValues = (string) $sSqlAllValues;
        $sSqlSelectedValues = (string) $sSqlSelectedValues;
        $bRequired = (bool) $bRequired;
        $bDeleteOnAdd = (bool) $bDeleteOnAdd;
        $bReadOnly = (bool) $bReadOnly;
        $iSize = (string) $iSize;
        $iWidth = (int) $iWidth;
        $bFormOnly = (bool) $bFormOnly;

        $sFormField = '';
        $aResultAll = array();
        $aResultSelected = array();

        $oConnection = Pelican_Db::getInstance();

        /* Recherche des résultats de la requête SQL de toutes les valeurs
         * passées en paramètres avec ou sans tableau de bind
         */
        if (!empty($sSqlAllValues) && is_array($aBindAllValues)) {
            $aAllValues = $oConnection->queryTab($sSqlAllValues, $aBindAllValues);
        } else {
            $aAllValues = $oConnection->queryTab($sSqlAllValues);
        }

        /* Recherche des résultats de la requête SQL des valeurs sélectionnées
         * passées en paramètres avec ou sans tableau de bind
         */
        if (!empty($sSqlSelectedValues) && is_array($aBindSelected)) {
            $aSelectedValues = $oConnection->queryTab($sSqlSelectedValues, $aBindSelected);
        } else {
            $aSelectedValues = $oConnection->queryTab($sSqlSelectedValues);
        }
        /* Remise en forme du tableau pour qu'il corresponde à
         * array(array('ID'=>'LIB), array('ID'=>'LIB))
         */
        $aResultAll = self::getQueryTabKeyValue($aAllValues, 'ID', 'LIB');
        $aResultSelected = self::getQueryTabValues($aSelectedValues, 'ID');

        /* Appel du createFromList */
        $sFormField .= $oController->oForm->createAssocFromList($oConnection, $sName, $sLabel, $aResultAll, $aResultSelected, $bRequired, $bDeleteOnAdd, $bReadOnly, $iSize, $iWidth, $bFormOnly);

        return $sFormField;
    }

    /**
     * Méthode permettant de modifier le format d'un tableau de la forme suivant
     *
     * array( 0 => array ( 'key11' => 'value11', 'key12' => 'value12'),
     *        1 => array ( 'key11' => 'value21', 'key12' => 'value22'),
     * En un tableau
     * array ('value11' => 'value12', 'value21' => 'value22')
     *
     * Pour généré le second tableau deux clés passées en paramètre sont utilsées
     *
     * @param array     $aValues            Tableau à modifier
     * @param string    $sFieldToBeKey      Clé du tableau de la deuxième dimension
     *                                      dont la valeur servira comme clé du
     *                                      tableau de sortie
     * @param string    $sFieldToBeValue    Clé du tableau de la deuxième dimension
     *                                      dont la valeur servira comme valeur du
     *                                      tableau de sortie
     * @return array                        Tableau remanié comme expliqué dans
     *                                      la description
     */
    public static function getQueryTabKeyValue($aValues, $sFieldToBeKey, $sFieldToBeValue) {
        /* Initialisation des variables */
        $aResult = array();
        $sFieldToBeKey = (string) $sFieldToBeKey;
        $sFieldToBeValue = (string) $sFieldToBeValue;

        if (is_array($aValues) && !empty($aValues)) {
            /* A partir de chaque ligne du tableau passé en paramètre on  créer
             * un tableau en utilisant les clés passées en paramètres
             */
            foreach ($aValues as $aOneLine) {
                if (is_array($aOneLine) && array_key_exists($sFieldToBeKey, $aOneLine) && array_key_exists($sFieldToBeValue, $aOneLine)
                ) {
                    $aResult[$aOneLine[$sFieldToBeKey]] = $aOneLine[$sFieldToBeValue];
                }
            }
        }

        return $aResult;
    }

    /**
     * Méthode permettant de modifier le format d'un tableau de la forme suivant
     *
     * array( 0 => array ( 'key11' => 'value11', 'key12' => 'value12'),
     *        1 => array ( 'key11' => 'value21', 'key12' => 'value22'),
     * En un tableau
     * array (0 => 'value11', 1 => 'value21')
     *
     * Pour généré le second tableau 1 clé passée en paramètre est utilisée
     *
     * @param array     $aValues            Tableau à modifier
     * @param string    $sFieldToBeValue    Clé du tableau de la deuxième dimension
     *                                      dont la valeur servira comme valeur du
     *                                      tableau de sortie
     * @return array                        Tableau remanié comme expliqué dans
     *                                      la description
     */
    public static function getQueryTabValues($aValues, $sFieldToBeValue) {
        /* Initialisation des variables */
        $aResult = array();
        $sFieldToBeKey = (string) $sFieldToBeKey;
        $sFieldToBeValue = (string) $sFieldToBeValue;

        if (is_array($aValues) && !empty($aValues)) {
            /* A partir de chaque ligne du tableau passé en paramètre on  créer
             * un tableau en utilisant les clés passées en paramètres
             */
            foreach ($aValues as $aOneLine) {
                if (is_array($aOneLine) && array_key_exists($sFieldToBeValue, $aOneLine)) {
                    $aResult[] = $aOneLine[$sFieldToBeValue];
                }
            }
        }

        return $aResult;
    }

    /**
     * Affiche le menu déroulant définissant un véhicule associé à une page
     * @param Objet controller     $oController    Objet controller pour la
     *                                             création de formulaire
     * @param boolean              $bRequired      Indique si l'ajout du véhicule
     *                                             est obligatoire ou non
     * @return array                               Tableaux des véhicules
     */
    public static function getVehicule($oController, $bRequired = true) {
        $oConnection = Pelican_Db::getInstance();
        /* Initialisation des variables */
        $aVehicules = array();
        /* Initialisation du tableau de Bind */
        $aBind[":LANGUE_ID"] = $_SESSION[APP]['LANGUE_ID'];
        $aBind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
        $sSQL = <<<SQL
            SELECT
                VEHICULE_ID,
                VEHICULE_LABEL,
                CASE WHEN (VEHICULE_GAMME_CONFIG <> '' OR  VEHICULE_GAMME_CONFIG IS NOT NULL)
                    THEN VEHICULE_GAMME_CONFIG
                    ELSE VEHICULE_GAMME_MANUAL
                END GAMME
            FROM
                #pref#_vehicule
            WHERE
                SITE_ID = :SITE_ID
                AND LANGUE_ID = :LANGUE_ID
SQL;
        $aResults = $oConnection->queryTab($sSQL, $aBind);

        if (is_array($aResults) && count($aResults) > 0) {
            foreach ($aResults as $aOneResult) {
                $aVehicules[$aOneResult['VEHICULE_ID']] = "({$aOneResult['GAMME']}) {$aOneResult['VEHICULE_LABEL']}";
            }
        }
        return $oController->oForm->createComboFromList($oController->multi . "ZONE_ATTRIBUT", t("VEHICULE_ASSOCIE"), $aVehicules, $oController->zoneValues['ZONE_ATTRIBUT'], $bRequired, $oController->readO);
    }

    /**
     * Méthode statique permettant de remonter l'ensemble des données de la table
     * page_zone_multi
     * @param Objet controller     $oController     Objet controller pour la
     *                                              création de formulaire
     * @param string                $sType          type du multi
     * @return array                Tableau des valeurs associés à la table d'enregistrement
     *                              des multis
     */
    public static function getPageZoneMultiValues($oController, $sType = '') {
        $oConnection = Pelican_Db::getInstance();
        /* Initialisation des variables */
        $aResult = array();
        /* $iZoneTemplateId    = (int)$oController->zoneValues['ZONE_TEMPLATE_ID'];
          $iLangueId          = (int)$oController->zoneValues['LANGUE_ID'];
          $iPageId            = (int)$oController->zoneValues['PAGE_ID'];
          $iPageVersion       = (int)$oController->zoneValues['PAGE_VERSION'];

          // Initialisation du tableau de bind
          $aBind[':ZONE_TEMPLATE_ID']     = $iZoneTemplateId;
          $aBind[':LANGUE_ID']            = $iLangueId;
          $aBind[':PAGE_ID']              = $iPageId;
          $aBind[':PAGE_VERSION']         = $iPageVersion;
          $aBind[':PAGE_ZONE_MULTI_TYPE'] = $oConnection->strToBind($sType);

          $sSQL = <<<SQL
          SELECT
         *
          FROM
          #pref#_page_zone_multi
          WHERE
          PAGE_ID=:PAGE_ID
          AND LANGUE_ID=:LANGUE_ID
          AND PAGE_VERSION=:PAGE_VERSION
          AND ZONE_TEMPLATE_ID=:ZONE_TEMPLATE_ID
          SQL;
          if($sType != ''){
          $sSQL .= <<<SQL
          AND PAGE_ZONE_MULTI_TYPE=:PAGE_ZONE_MULTI_TYPE
          SQL;
          }
          $aMultiValues = $oConnection->queryTab($sSQL, $aBind); */
        $aMultiValues = self::getPageZoneMulti($oController, $sType);

        if (is_array($aMultiValues) && !empty($aMultiValues)) {
            /* A partir de chaque ligne du tableau passé en paramètre on  créer
             * un tableau en utilisant les clés passées en paramètres
             */
            $aResult = $aMultiValues;
        }

        return $aResult;
    }

    /**
     * Méthode statique de sauvegarde des données d'un multi à enregistrer dans
     * la table page_zone_multi
     * @param string $sMultiName               libellé du multi
     */
    public static function savePageZoneMultiValues($sMultiName, $sMultiType = '') {
        /* Initialisation des variables */
        $sMultiName = (string) $sMultiName;
        $sMultiType = (string) $sMultiType;
        $aSaveValues = Pelican_Db::$values;
        $oConnection = Pelican_Db::getInstance();
        $iMultiId = 0;
        $sMultiTableName = '#pref#_page_zone_multi';
        /* $aBind[":LANGUE_ID"] = Pelican_Db::$values['LANGUE_ID'];
          $aBind[":PAGE_VERSION"] = Pelican_Db::$values['PAGE_VERSION'];
          $aBind[":PAGE_ID"] = Pelican_Db::$values['PAGE_ID'];
          $aBind[":ZONE_TEMPLATE_ID"] = Pelican_Db::$values['ZONE_TEMPLATE_ID'];
          $aBind[":PAGE_ZONE_MULTI_TYPE"] = $oConnection->strToBind($sMultiType);
          $sqlDataMulti = "Select * FROM #pref#_page_zone_multi
          WHERE
          LANGUE_ID = :LANGUE_ID
          and PAGE_VERSION = :PAGE_VERSION
          and PAGE_ID = :PAGE_ID
          and ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
          and PAGE_ZONE_MULTI_TYPE = :PAGE_ZONE_MULTI_TYPE
          ";
          $aDataMulti =   $oConnection->queryTab($sqlDataMulti, $aBind);
         */
        self::deletePageZoneMulti($sMultiType);
        // Création du tableau avec les champs nécessaires pour la suppression
        /* $aDeleteMulti['PAGE_ID']                = Pelican_Db::$values['PAGE_ID'];
          $aDeleteMulti['LANGUE_ID']              = Pelican_Db::$values['LANGUE_ID'];
          $aDeleteMulti['PAGE_VERSION']           = Pelican_Db::$values['PAGE_VERSION'];
          $aDeleteMulti['ZONE_TEMPLATE_ID']       = Pelican_Db::$values['ZONE_TEMPLATE_ID'];
          $aDeleteMulti['PAGE_ZONE_MULTI_TYPE']   = $sMultiType;
          Pelican_Db::$values = $aDeleteMulti;
         */
        // Suppression des éléments du multi
        //$oConnection->deleteQuery($sMultiTableName, '', array_keys($aDeleteMulti)); 

        /* Remise en place des données du values */
        Pelican_Db::$values = $aSaveValues;
        /* Intégration dans un tableau des données du multi */
        readMulti($sMultiName, $sMultiName);
        $aMultiFormValues = Pelican_Db::$values[$sMultiName];
        
        /* Enregistrement des données du multi et organise tableau multi */
        if (is_array($aMultiFormValues) && !empty($aMultiFormValues)) {
            // Récupération max PAGE_ZONE_MULTI_ID & indexation des éléments qui n'ont pas de PAGE_ZONE_MULTI_ID
            $pageZoneMultiIds = array();
            foreach ($aMultiFormValues as $key => $val) {
                if (isset($val['PAGE_ZONE_MULTI_ID']) && is_numeric($val['PAGE_ZONE_MULTI_ID'])) {
                    $pageZoneMultiIds[] = intval($val['PAGE_ZONE_MULTI_ID']);
                }
            }
            $cptId = !empty($pageZoneMultiIds) ? max($pageZoneMultiIds) : 0;
            foreach ($aMultiFormValues as $key => $val) {
                if (!isset($val['PAGE_ZONE_MULTI_ID'])) {
                    $aMultiFormValues[$key]['PAGE_ZONE_MULTI_ID'] = ++$cptId;
                }
            }
            unset($pageZoneMultiIds, $cptId);
            
            foreach ($aMultiFormValues as $aOneMulti) {

                /* Seuls les éléments non masqués sont insérés dans la table */
                if ($aOneMulti['multi_display'] == 1) {
                    $saveValues = Pelican_Db::$values;
                    Pelican_Db::$values = array();
                    Pelican_Db::$values["PAGE_ZONE_MULTI_TYPE"] = $sMultiType;
                    $iMultiId++;
                    /* Champs nécéssaires à l'inclusion d'une ligne de multi */
                    //$aMultiInfos['PAGE_ID']                 = Pelican_Db::$values['PAGE_ID'];
                    //$aMultiInfos['LANGUE_ID']               = Pelican_Db::$values['LANGUE_ID'];
                    //$aMultiInfos['PAGE_VERSION']            = Pelican_Db::$values['PAGE_VERSION'];
                    //$aMultiInfos['ZONE_TEMPLATE_ID']        = Pelican_Db::$values['ZONE_TEMPLATE_ID'];
                    Pelican_Db::$values['PAGE_ZONE_MULTI_ID'] = $iMultiId;

                    if (!empty($aOneMulti['PAGE_ZONE_MULTI_ORDER'])) {
                        Pelican_Db::$values['PAGE_ZONE_MULTI_ORDER'] = $aOneMulti['PAGE_ZONE_MULTI_ORDER'];
                    }
                    Pelican_Db::$values['PAGE_ZONE_MULTI_TYPE'] = $sMultiType;
                    /* Ajout des champs supplémentaires */
                    foreach ($aOneMulti as $sKeyFieldName => $sValue) {
                        if (is_string($sKeyFieldName) && !empty($sKeyFieldName)) {
                            //Ajout pour gérer automatiquement l'enregistrement des listes associatives
                            if (is_array($sValue) && !empty($sValue)) {
                                $sValue = implode(",", $sValue);
                            }

                            Pelican_Db::$values[$sKeyFieldName] = $sValue;
                        }
                    }
                    /* Intégration dans les Values pour l'utilisation des méthodescu FW */
                    /* Pelican_Db::$values = $aMultiInfos;
                      $oConnection->updateTable(Pelican::$config['DATABASE_INSERT'], $sMultiTableName); */
                    self::addPageZoneMulti($saveValues);

                    Pelican_Db::$values = $saveValues;
                }
            }
        }

        /* Remise en place des données du values */
        Pelican_Db::$values = $aSaveValues;
    }

    /**
     * Méthode statique de sauvegarde des données d'un multi à enregistrer dans
     * la table page_zone_multi
     * @param string $sMultiName               libellé du multi
     */
    public static function saveContentZoneMultiValues($sMultiName, $sMultiType = '') {
        /* Initialisation des variables */
        $sMultiName = (string) $sMultiName;
        $sMultiType = (string) $sMultiType;
        $aSaveValues = Pelican_Db::$values;
        $oConnection = Pelican_Db::getInstance();
        $iMultiId = 0;
        $sMultiTableName = '#pref#_content_zone_multi';

        $content_id = $aSaveValues['CONTENT_ID'];
        $version = $aSaveValues['CONTENT_VERSION'];
        $content_zone_id = $aSaveValues['PAGE_ID'];

        self::deleteContentZoneMulti($sMultiType);

        // Création du tableau avec les champs nécessaires pour la suppression
        /* $aDeleteMulti['PAGE_ID']                = Pelican_Db::$values['PAGE_ID'];
          $aDeleteMulti['LANGUE_ID']              = Pelican_Db::$values['LANGUE_ID'];
          $aDeleteMulti['PAGE_VERSION']           = Pelican_Db::$values['PAGE_VERSION'];
          $aDeleteMulti['ZONE_TEMPLATE_ID']       = Pelican_Db::$values['ZONE_TEMPLATE_ID'];
          $aDeleteMulti['PAGE_ZONE_MULTI_TYPE']   = $sMultiType;
          Pelican_Db::$values = $aDeleteMulti;
          // Suppression des éléments du multi
          $oConnection->deleteQuery($sMultiTableName, '', array_keys($aDeleteMulti)); */

        /* Remise en place des données du values */
        Pelican_Db::$values = $aSaveValues;

        /* Intégration dans un tableau des données du multi */
        readMulti($sMultiName, $sMultiName);

        $aMultiFormValues = Pelican_Db::$values[$sMultiName];

        /* Enregistrement des données du multi */
        if (is_array($aMultiFormValues) && !empty($aMultiFormValues)) {
            foreach ($aMultiFormValues as $aOneMulti) {
                /* Seuls les éléments non masqués sont insérés dans la table */
                if ($aOneMulti['multi_display'] == 1) {
                    $saveValues = $controller->values;
                    Pelican_Db::$values = array();

                    Pelican_Db::$values["CONTENT_ZONE_MULTI_TYPE"] = $sMultiType;

                    $iMultiId++;
                    /* Champs nécéssaires à l'inclusion d'une ligne de multi */
                    //$aMultiInfos['PAGE_ID']                 = Pelican_Db::$values['PAGE_ID'];
                    //$aMultiInfos['LANGUE_ID']               = Pelican_Db::$values['LANGUE_ID'];
                    //$aMultiInfos['PAGE_VERSION']            = Pelican_Db::$values['PAGE_VERSION'];
                    //$aMultiInfos['ZONE_TEMPLATE_ID']        = Pelican_Db::$values['ZONE_TEMPLATE_ID'];
                    Pelican_Db::$values['CONTENT_ZONE_MULTI_ID'] = $iMultiId;
                    if (!empty($aOneMulti['PAGE_ZONE_MULTI_ORDER'])) {
                        Pelican_Db::$values['CONTENT_ZONE_MULTI_ORDER'] = $aOneMulti['PAGE_ZONE_MULTI_ORDER'];
                    }
                    Pelican_Db::$values['CONTENT_ZONE_MULTI_TYPE'] = $sMultiType;
                    /* Ajout des champs supplémentaires */
                    foreach ($aOneMulti as $sKeyFieldName => $sValue) {
                        if (is_string($sKeyFieldName) && !empty($sKeyFieldName)) {
                            //Ajout pour gérer automatiquement l'enregistrement des listes associatives
                            if (is_array($sValue) && !empty($sValue)) {
                                $sValue = implode(",", $sValue);
                            }

                            Pelican_Db::$values[$sKeyFieldName] = $sValue;
                        }
                    }
                    /* Intégration dans les Values pour l'utilisation des méthodescu FW */
                    /* Pelican_Db::$values = $aMultiInfos;
                      $oConnection->updateTable(Pelican::$config['DATABASE_INSERT'], $sMultiTableName); */

                    $saveValues['CONTENT_ID'] = $content_id;
                    $saveValues['CONTENT_ZONE_ID'] = $content_zone_id;
                    $saveValues['CONTENT_VERSION'] = $version;
                    self::addContentZoneMulti($saveValues);

                    Pelican_Db::$values = $saveValues;
                }
            }
        }

        /* Remise en place des données du values */
        Pelican_Db::$values = $aSaveValues;
    }

    public static function savePageZoneMultiValuesHmvc($sMultiName, $sMultiType = '') {
        /* Initialisation des variables */
        $sMultiName = (string) $sMultiName;
        $sMultiType = (string) $sMultiType;
        $aSaveValues = Pelican_Db::$values;
        $oConnection = Pelican_Db::getInstance();
        $iMultiId = 0;
        $sMultiTableName = '#pref#_page_zone_multi';

        self::deletePageZoneMulti($sMultiType);

        /* Remise en place des données du values */
        Pelican_Db::$values = $aSaveValues;
        /* Intégration dans un tableau des données du multi */
        readMulti($sMultiName, $sMultiName);
        $aMultiFormValues = Pelican_Db::$values[$sMultiName];
        /* Enregistrement des données du multi */
        if (is_array($aMultiFormValues) && !empty($aMultiFormValues)) {
            foreach ($aMultiFormValues as $aOneMulti) {
                /* Seuls les éléments non masqués sont insérés dans la table */
                if ($aOneMulti['multi_display'] == 1) {
                    $saveValues = Pelican_Db::$values;
                    Pelican_Db::$values = array();
                    Pelican_Db::$values["PAGE_ZONE_MULTI_TYPE"] = $sMultiType;
                    $iMultiId++;
                    /* Champs nécéssaires à l'inclusion d'une ligne de multi */
                    Pelican_Db::$values['PAGE_ZONE_MULTI_ID'] = $iMultiId;
                    if (!empty($aOneMulti['PAGE_ZONE_MULTI_ORDER'])) {
                        Pelican_Db::$values['PAGE_ZONE_MULTI_ORDER'] = $aOneMulti['PAGE_ZONE_MULTI_ORDER'];
                    }
                    Pelican_Db::$values['PAGE_ZONE_MULTI_TYPE'] = $sMultiType;
                    /* Ajout des champs supplémentaires */
                    foreach ($aOneMulti as $sKeyFieldName => $sValue) {
                        if (is_string($sKeyFieldName) && !empty($sKeyFieldName)) {
                            //Ajout pour gérer automatiquement l'enregistrement des listes associatives
                            if (is_array($sValue) && !empty($sValue)) {
                                $sValue = implode(",", $sValue);
                            }
                            Pelican_Db::$values[$sKeyFieldName] = $sValue;
                        }
                    }
                    self::addPageZoneMulti($saveValues);

                    //On gère l'enregistrement des CTA présents à l'intérieur du multi
                    self::saveCtaHmvc();
                    Pelican_Db::$values = $saveValues;
                }
            }
        }

        /* Remise en place des données du values */
        Pelican_Db::$values = $aSaveValues;
    }

    public function verificationSuppressionReferentiel($type, $id, $siteId) {
        $oConnection = Pelican_Db::getInstance();
        $aZones = Pelican::$config['CONTRAINTES_SUPPRESSION_REFERENTIEL'][$type];
        $aPagesConflictuelles = array();
        if ($aZones) {
            foreach ($aZones as $zone) {
                $aBind[':SITE_ID'] = $siteId;
                $aBind[':ZONE_ID'] = $zone['ZONE_ID'];
                $sSQL = "
                    select
                        p.PAGE_ID,
                        pv.LANGUE_ID,
                        zt.ZONE_ID,
                        pv.PAGE_TITLE_BO
                    from #pref#_page p
                    inner join #pref#_page_version pv
                        on (pv.PAGE_ID = p.PAGE_ID
                            and pv.LANGUE_ID = p.LANGUE_ID
                            and pv.PAGE_VERSION = p.PAGE_CURRENT_VERSION)
                    inner join #pref#_zone_template zt
                        on (zt.TEMPLATE_PAGE_ID = pv.TEMPLATE_PAGE_ID)
                    inner join #pref#_page_zone pz
                        on (pz.PAGE_ID = pv.PAGE_ID
                            and pz.LANGUE_ID = pv.LANGUE_ID
                            and pz.PAGE_VERSION = pv.PAGE_VERSION
                            and pz.ZONE_TEMPLATE_ID = zt.ZONE_TEMPLATE_ID)
                    where p.SITE_ID = :SITE_ID
                    and zt.ZONE_ID = :ZONE_ID
                    and concat('" . $zone['SEPARATEUR'] . "',pz." . $zone['CHAMP'] . ",'" . $zone['SEPARATEUR'] . "') like concat('%" . $zone['SEPARATEUR'] . "','" . $id . "','" . $zone['SEPARATEUR'] . "%')
                ";
                $aResults = $oConnection->queryTab($sSQL, $aBind);
                if ($aResults) {
                    $aPagesConflictuelles = array_merge($aPagesConflictuelles, $aResults);
                }
            }
        }
        return $aPagesConflictuelles;
    }

    /**
     * Méthode statique permettant de mettre en place le/les tableau(x) associatif(s)
     * pour sélectionner les outils d'un bloc.La Méthode génère un ou deux tableaux
     * d'outils suivants les paramètres passés. Elle contrôle aussi le nombre
     * d'éléments ainsi que le caractère obligatoire des tableaux associatifs
     *
     * @param Objet controller      $oController    Objet controller pour la
     *                                              création de formulaire
     * @param boolean               $bDisplayWeb    Indique si l'on affiche le tableau
     *                                              associatif pour l'ajout d'outil Web
     * @param boolean               $bDisplayMob    Indique si l'on affiche le tableau
     *                                              associatif pour l'ajout d'outil Mobile
     * @param NULL/int              $iNbItemsMin    Si NULL pas de minimum de d'outils à
     *                                              sélectionner
     *                                              Si différent de NULL indique le minimum
     *                                              d'outils Web et/ou Mobile à sélectionner
     * @param NULL/int              $iNbItemsMax    Si NULL pas de minimum de d'outils à
     *                                              sélectionner
     *                                              Si différent de NULL indique le minimum
     *                                              d'outils Web et/ou Mobile à sélectionner
     * @param boolean               $bRequired     Indique si la sélection d'un outils
     *                                              Web et/ou Mobile est requis
     * @return string                               Tableau(x) associatif(s) des outils
     *                                              généré(s)
     */
    public static function getOutils($oController, $bDisplayWeb = true, $bDisplayMob = false, $iNbItemsMin = null, $iNbItemsMax = null, $bRequired = false,$iNbItemsMinMob= null ,$iNbItemsMaxMob = null,$bPageGenral =false) {
        /* Initialisation des variables */
		
        $aWebTools = array();
        $aMobileTools = array();
        $bDisplayWeb = (bool) $bDisplayWeb;
        $bDisplayMob = (bool) $bDisplayMob;
        $bRequired = (bool) $bRequired;
        $bBrochure = false;
        $sToolWebField = 'ZONE_TOOL';
        $sToolMobField = 'ZONE_TOOL2';
        $sSqlInBindWeb = '';
        $sSqlInBindMob = '';
        $aBindSelectedWeb = array();
        $aSelectedToolBarWeb = array();
        $aBindSelectedMob = array();
        $aSelectedToolBarMob = array();
        $sControllerForm = '';
        $aAllToolBarWeb = array();
        $aAllToolBarMobile = array();
        if (!is_null($iNbItemsMin)) {
            $iNbItemsMin = (int) $iNbItemsMin;
        }
        if (!is_null($iNbItemsMax)) {
            $iNbItemsMax = (int) $iNbItemsMax;
        }

        $aPage = Pelican_Cache::fetch("Frontend/Page", array(
                    $oController->zoneValues['PAGE_ID'],
                    $_SESSION[APP]['SITE_ID'],
                    $oController->zoneValues['LANGUE_ID']
        ));

        if ($aPage['TEMPLATE_PAGE_LABEL'] == 'Mon projet / Ma sélection') {
            $bBrochure = true;
        }
        $oConnection = Pelican_Db::getInstance();
        /* Récupération des valeurs pour la version Web */
        if (is_array($oController->zoneValues) && isset($oController->zoneValues[$sToolWebField]) && !empty($oController->zoneValues[$sToolWebField])
        ) {

            if (!is_array($oController->zoneValues[$sToolWebField]))
                $aWebTools = explode('|', $oController->zoneValues[$sToolWebField]);
        }

        /* Récupération des valeurs pour la version Mobile */
        if (is_array($oController->zoneValues) && isset($oController->zoneValues[$sToolMobField]) && !empty($oController->zoneValues[$sToolMobField])
        ) {
            if (!is_array($oController->zoneValues[$sToolMobField]))
                $aMobileTools = explode('|', $oController->zoneValues[$sToolMobField]);
        }
        /* Création de la requête Select générique */
        $sSqlToolBarSelectGen = <<<SQL
                SELECT
                    BARRE_OUTILS_ID ID,
                    BARRE_OUTILS_LABEL LIB
                FROM
                    #pref#_barre_outils
                WHERE
                    SITE_ID = :SITE_ID
                    AND LANGUE_ID = :LANGUE_ID
SQL;
        /* Création de la requête Order générique */
        $sSqlToolBarOrderGen = <<<SQL
                ORDER BY LIB
SQL;

        /* Initialisation du Bind des variables */
        $aBindAllItems[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $aBindAllItems[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        /* Condition permettant d'afficher la barre d'outils flaggé
         * pour la partie Web
         */
        if ($bDisplayWeb === true) {
            $aBindAllItems[':BARRE_OUTILS_AFFICHAGE_WEB'] = 1;
            $sSqlAllToolBarWebCond = <<<SQL
                    AND BARRE_OUTILS_AFFICHAGE_WEB = :BARRE_OUTILS_AFFICHAGE_WEB
SQL;
            /* Création du tableau ramenant l'ensemble des élément de la barre d'outils */
            $aAllToolBarWeb = $oConnection->queryTab($sSqlToolBarSelectGen . $sSqlAllToolBarWebCond . $sSqlToolBarOrderGen, $aBindAllItems);

            if ($bBrochure) {
                foreach ($aAllToolBarWeb as $tool) {
                    if ($tool['LIB'] == 'Demande de Brochure')
                        unset($tool);
                }
                array_shift($aAllToolBarWeb);
            }
        }

        /* Création de la partie sélection SQL les outils Web sélectionnés */
        if ($bDisplayWeb === true && is_array($aWebTools) && !empty($aWebTools)) {
            $aInResultWeb = self::getInSqlBind('BARRE_OUTILS_ID', $sToolWebField, $aWebTools);
            $aBindSelectedWeb = $aBindAllItems + $aInResultWeb['BIND'];
            $sSqlInBindWeb = ' AND ' . $aInResultWeb['SQL'];
            /* Création du tableau des outils Mobile sélectionnés */
            $aSelectedToolBarWeb = $oConnection->queryTab($sSqlToolBarSelectGen . $sSqlInBindWeb . $sSqlToolBarOrderGen, $aBindSelectedWeb);
            $aSelectedToolBarWeb = self::orderedTools($aSelectedToolBarWeb, $aBindSelectedWeb);
        }

        if ($bDisplayWeb === true) {
            /* Appel du createFromList */
            $sControllerForm .= $oController->oForm->createAssocFromList($oConnection, $oController->multi . 'ZONE_TOOL', t('OUTILS_WEB'), self::getQueryTabKeyValue($aAllToolBarWeb, 'ID', 'LIB'), self::getQueryTabValues($aSelectedToolBarWeb, 'ID'), $bRequired, true, $oController->readO, "5", 200, false, "", 'ordre', 0, false);
            if ($bPageGenral != true) {
			if (!is_null($iNbItemsMin) || !is_null($iNbItemsMax)) {
                $sJS = <<<JS
                        var toolsWeb = obj.elements['{$oController->multi
                        }ZONE_TOOL[]'];
			var toolsWebSelected = new Array();
			for (var i = 0; i < toolsWeb.length; i++){
				if (toolsWeb[i].selected) {
					toolsWebSelected.push(toolsWeb[i].value);
				}
			}
JS;
            }
            if (!is_null($iNbItemsMin)) {
                $sWebToolsMinMsg = str_replace('#nb#', $iNbItemsMin, t('ALERT_MSG_OUTILS_WEB_MIN', 'js'));
                $sJS .= <<<JS
                        if(toolsWebSelected.length < {$iNbItemsMin}){
				alert('{$sWebToolsMinMsg}');
				$('#FIELD_BLANKS').val(1);
			}
JS;
            }
            if (!is_null($iNbItemsMax)) {
                $sWebToolsMaxMsg = str_replace('#nb#', $iNbItemsMax, t('ALERT_MSG_OUTILS_WEB_MAX', 'js'));
                $sJS .= <<<JS
			if(toolsWebSelected.length > {$iNbItemsMax}){
				alert('{$sWebToolsMaxMsg}');
				$('#FIELD_BLANKS').val(1);
			}
JS;
            }
            if (!is_null($iNbItemsMin) || !is_null($iNbItemsMax)) {
                $oController->oForm->createJS($sJS);
            }
		}
        }

        /* Condition permettant d'afficher la barre d'outils flaggé
         * pour la partie Mobile
         */
        if ($bDisplayMob === true) {
            $aBindAllItems[':BARRE_OUTILS_AFFICHAGE_MOBILE'] = 1;
            $sSqlAllToolBarMobCond = <<<SQL
                    AND BARRE_OUTILS_AFFICHAGE_MOBILE = :BARRE_OUTILS_AFFICHAGE_MOBILE
SQL;
            /* Création du tableau ramenant l'ensemble des élément de la barre d'outils */
            $aAllToolBarMobile = $oConnection->queryTab($sSqlToolBarSelectGen . $sSqlAllToolBarMobCond . $sSqlToolBarOrderGen, $aBindAllItems);
        }

        /* Création de la partie sélection SQL les outils Web sélectionnés */
        if ($bDisplayMob === true && is_array($aMobileTools) && !empty($aMobileTools)) {
            $aInResultMob = self::getInSqlBind('BARRE_OUTILS_ID', $sToolMobField, $aMobileTools);
            $aBindSelectedMob = $aBindAllItems + $aInResultMob['BIND'];
            $sSqlInBindMob = ' AND ' . $aInResultMob['SQL'];
            /* Création de la requête SQL des outils Mobile sélectionnés */
            $aSelectedToolBarMob = $oConnection->queryTab($sSqlToolBarSelectGen . $sSqlInBindMob . $sSqlToolBarOrderGen, $aBindSelectedMob);
            $aSelectedToolBarMob = self::orderedTools($aSelectedToolBarMob, $aBindSelectedMob, true);
        }
        if ($bDisplayMob === true) {
            /* Appel du createFromList */
            $sControllerForm .= $oController->oForm->createAssocFromList($oConnection, $oController->multi . 'ZONE_TOOL2', t('OUTILS_MOBILE'), self::getQueryTabKeyValue($aAllToolBarMobile, 'ID', 'LIB'), self::getQueryTabValues($aSelectedToolBarMob, 'ID'), $bRequired, true, $oController->readO, "5", 200, false, "", 'ordre', 0, false);
            if ($bPageGenral != true) {
			if (!is_null($iNbItemsMinMob) || !is_null($iNbItemsMaxMob)) {
                $sJS = <<<JS
                        var toolsMob = obj.elements['{$oController->multi}ZONE_TOOL2[]'];
			var toolsMobSelected = new Array();
			for (var i = 0; i < toolsMob.length; i++){
				if (toolsMob[i].selected) {
					toolsMobSelected.push(toolsMob[i].value);
				}
			}
JS;
            }
			
            if (!is_null($iNbItemsMinMob)) {
                $sWebToolsMinMsg = str_replace('#nb#', $iNbItemsMinMob, t('ALERT_MSG_OUTILS_MOBILE_MIN', 'js'));
                $sJS .= <<<JS
                        if(toolsMobSelected.length < {$iNbItemsMinMob}){
				alert('{$sWebToolsMinMsg}');
				$('#FIELD_BLANKS').val(1);
			}
JS;
            }
            if (!is_null($iNbItemsMaxMob)) {
                $sWebToolsMaxMsg = str_replace('#nb#', $iNbItemsMaxMob, t('ALERT_MSG_OUTILS_MOBILE_MAX', 'js'));
                $sJS .= <<<JS
			if(toolsMobSelected.length > {$iNbItemsMaxMob}){
				alert('{$sWebToolsMaxMsg}');
				$('#FIELD_BLANKS').val(1);
			}
JS;
            }
            if (!is_null($iNbItemsMin) || !is_null($iNbItemsMax) || !is_null($iNbItemsMinMob) || !is_null($iNbItemsMaxMob) ) {
                $oController->oForm->createJS($sJS);
            }
		}
        }

        return $sControllerForm;
    }

    /**
     * Méthode statique sauvegardant remettant en ordre les outils
     */
    public static function orderedTools($aSelectedTools, $aBindSelected, $isMobile = false) {
        $toolsExist = true;
        $nTools = 0;
        $TOOL_MOBILE = "";
        if ($isMobile) {
            $TOOL_MOBILE = '2';
        }
        $aNewSelectedTools = array();
        while ($toolsExist) {
            if ($aBindSelected[':ZONE_TOOL' . $TOOL_MOBILE . $nTools]) {
                foreach ($aSelectedTools as $keyTools => $valueTools) {

                    if ($valueTools['ID'] == (int) str_replace("'", "", $aBindSelected[':ZONE_TOOL' . $TOOL_MOBILE . $nTools])) {
                        $aNewSelectedTools[] = $valueTools;
                    }
                }
                $nTools++;
            } else {
                $toolsExist = false;
            }
        }
        return $aNewSelectedTools;
    }

    /**
     * Méthode statique sauvegardant les données des outils Web et/ou Mobile
     * Reformate les cases à cocher pour l'insertion en base
     * A placer dans le saveAction(), avant le parent::save();
     */
    public static function saveOutils() {
        /* Vérification de la présence d'une valeur pour sélectionnée dans le
         * tableau associatif pour la partie Web
         */
        if (isset(Pelican_Db::$values['ZONE_TOOL']) && is_array(Pelican_Db::$values['ZONE_TOOL']) && !empty(Pelican_Db::$values['ZONE_TOOL'])) {
            Pelican_Db::$values['ZONE_TOOL'] = implode('|', Pelican_Db::$values['ZONE_TOOL']);
        }
        /* Vérification de la présence d'une valeur pour sélectionnée dans le
         * tableau associatif pour la partie Mobile
         */
        if (isset(Pelican_Db::$values['ZONE_TOOL2']) && is_array(Pelican_Db::$values['ZONE_TOOL2']) && !empty(Pelican_Db::$values['ZONE_TOOL2'])) {
            Pelican_Db::$values['ZONE_TOOL2'] = implode('|', Pelican_Db::$values['ZONE_TOOL2']);
        }
    }

    /**
     * Méthode statique permettant d'utiliser des Bind dans un IN d'une requête
     * SQL.
     * @param string    $sTableField    Nom du champ de la table sur lequel on
     *                                  fait le IN
     * @param string    $sBindPreKey    Préfixe des Variables Bind
     * @param array     $aValues        Tableau une dimension contenant les valeurs
     *                                  à intégrer dans le IN
     * @return  array['SQL']            Contient la partie SQL à intégrer à la requête
     *          array['BIND']           Contient le tableau des valeurs des bind
     *
     */
    public static function getInSqlBind($sTableField, $sBindPreKey, $aValues) {
        /* Initialisation des variables */
        $sTableField = (string) $sTableField;
        $sBindPreKey = (string) $sBindPreKey;
        $sType = (string) $sType;
        $aBindResult = array();
        $sSqlInBind = '';
        $aReturn = array();

        $oConnection = Pelican_Db::getInstance();
        /* Si un tableau de valeur est présent on peut commencer la requête Bind */
        if (is_array($aValues) && !empty($aValues) && !empty($sTableField) && !empty($sBindPreKey)
        ) {
            $i = 0;
            $sSqlInBind = $sTableField . ' IN (';
            /* Création du tableau de requête et de bind pour la sélection dans les in */
            foreach ($aValues as $mOneItem) {
                /* Création de la clé du bind en reprenant le préfixe ajouter en paramètre
                 * et le compteur initialisé
                 */
                $sBindKey = ':' . $sBindPreKey . $i;
                if ($i > 0) {
                    $sSqlInBind .= ', ';
                }
                /* Intégration du Bind dans la requête */
                $sSqlInBind .= $sBindKey;
                /* Création du tableau de Bind à réutiliser lors de la requête de sélection */
                if (is_string($mOneItem) === true) {
                    $aBindResult[$sBindKey] = (string) $oConnection->strToBind($mOneItem);
                } else {
                    $aBindResult[$sBindKey] = $mOneItem;
                }
                $i++;
            }
            $sSqlInBind .= ')';
            $aReturn['SQL'] = $sSqlInBind;
            $aReturn['BIND'] = $aBindResult;
        }

        return $aReturn;
    }

    /**
     * Méthode créant un tableau d'association à partir d'un table contenant l'ensemble
     * des valeurs disponibles et un champ qui contiendra les identifiants sélectionné
     *
     * @param Objet controller      $oController    Objet controller pour la
     *                                              création de formulaire
     * @param string                $sAssocName     Nom du tableau associatif
     * @param string                $sAssocLabel    Libellé du tableau associatif
     * @param int                   $iSiteId        Identifiant du site
     * @param int                   $iLangueId      Identifiant de la langue
     * @param string                $sTableName     Nom de la table où l'on doit prendre
     *                                              l'ensemble des valeurs
     * @param string                $sTableFieldId  Nom du champ servant d'identifiant
     *                                              ce sont les valeurs de ces identifiants
     *                                              qui seront enregistrés
     * @param string                $sTableFieldLib
     *                                              Nom du champ contenant les libellés
     *                                              à afficher dans le tableau
     * @param string                $sZoneMultiValues
     *                                              Nom du champ dans PAGE ou PAGE_ZONE
     *                                              stockant les identifiants sélectionnés
     * @param string                $sSeparator     Séparateur du champ multivalué
     * @param bool                  $bRequired      Le champ est-il requis
     * @param bool                  $bOrder         L'ordre est-il nécessaire
     * @param int                   $iNbMaxValues   Nombre de sélections maximum
     * @return string               Tableau associatif généré
     */
    public static function getCreateAssocFromMultiValuesField($oController, $sAssocName, $sAssocLabel, $iSiteId, $iLangueId, $sTableName, $sTableFieldId, $sTableFieldLib, $sZoneMultiValues, $sSeparator = '|', $bRequired = false, $bOrder = false, $iNbMaxValues = 0) {
        /* Initialisation des variables */
        $sAssocName = (string) $sAssocName;
        $sAssocLabel = (string) $sAssocLabel;
        $iSiteId = (int) $iSiteId;
        $iLangueId = (int) $iLangueId;
        $sTableName = (string) $sTableName;
        $sTableFieldId = (string) $sTableFieldId;
        $sTableFieldLib = (string) $sTableFieldLib;
        $sZoneMultiValues = (string) $sZoneMultiValues;
        $sSeparator = (string) $sSeparator;
        $bRequired = (bool) $bRequired;
        $bOrder = (bool) $bOrder;
        $iNbMaxValues = (int) $iNbMaxValues;
        $aSavedValues = array();
        $sFieldOrder = '';

        if ($bOrder === true) {
            $sFieldOrder = $oController->multi .
                    $sAssocName . 'ORDER';
        }

        $oConnection = Pelican_Db::getInstance();

        /* Tableau contenant les identifiants des valeurs précédemment sauvegardées */
        if (is_array($oController->zoneValues) && isset($oController->zoneValues[$sZoneMultiValues]) && !empty($oController->zoneValues[$sZoneMultiValues])
        ) {
            $aSavedValues = explode($sSeparator, $oController->zoneValues[
                    $sZoneMultiValues]);
        }

        /* Création de la requête Select générique */
        $sSqlGen1 = <<<SQL
                SELECT
                    {$sTableFieldId} ID,
                    {$sTableFieldLib} LIB
                FROM
                    #pref#_{$sTableName}
                WHERE
                    SITE_ID = :SITE_ID
                    AND LANGUE_ID = :LANGUE_ID
                ORDER BY LIB
SQL;
        /* Initialisation du Bind des variables */
        $aBind[':SITE_ID'] = $iSiteId;
        $aBind[':LANGUE_ID'] = $iLangueId;

        /* Création du tableau de toutes les catégories */
        $aAllValues = $oConnection->queryTab($sSqlGen1, $aBind);

        /* Création du tableau d'association */
        $sControllerForm = $oController->oForm->createAssocFromList(
                $oConnection, $oController->multi . $sAssocName, $sAssocLabel, Backoffice_Form_Helper::getQueryTabKeyValue($aAllValues, 'ID', 'LIB'), $aSavedValues, $bRequired, true, $oController->readO, '5', 200, false, '', $sFieldOrder, $iNbMaxValues
        );

        return $sControllerForm;
    }

    /**
     * Méthode de sauvegarde des champs multivalués
     * @param string $sAssocName    Nom du champ dans le formulaire à manipuler
     * @param string $sSaveField    Nom du champ de sauvegarde
     * @param string $sSeparator    Nom du séparateur de valeurs
     */
    public static function saveMultiValuesField($sAssocName, $sSaveField, $sSeparator = '|') {
        /* Initialisation des variables */
        $sSaveField = (string) $sSaveField;
        $sAssocName = (string) $sAssocName;
        $sSeparator = (string) $sSeparator;
        Pelican_Db::$values[$sSaveField] = array();

        /* Vérification de la présence d'une valeur pour sélectionnée dans le 
         * tableau associatif
         */
        if (isset(Pelican_Db::$values[$sAssocName]) && is_array(Pelican_Db::

                        $values[$sAssocName]) && !empty(Pelican_Db::$values[$sAssocName])) {
            Pelican_Db::$values[$sSaveField] = implode($sSeparator, Pelican_Db::$values[$sAssocName]);
        }
    }

    /**
     * Méthode de synchronisation entre une tranche général et une tranche personnalisée pour le slideshow genereique/perso
     * @param string $sMultiName    Nom du type du multi
     * @param bool $zoneDynamique Permet de savoir si on se trouve dans une tranche dynamique ou pas.      
     */
    public static function synchronisationDataPerso($sMultiName,  $zoneDynamique = false) {
        $aMultiFormValuesById = array();

        // Récupération de toutes les données non personnalisées concernant une tranche bien précise
        // donnée génériques
        readMulti($sMultiName, $sMultiName);
        $aMultisFormValues = Pelican_Db::$values[$sMultiName];


        foreach ($aMultisFormValues as $aMultiFormValues) {
            if (!isset($aMultiFormValues['PAGE_ZONE_MULTI_ID']) || empty($aMultiFormValues['PAGE_ZONE_MULTI_ID'])) {
                $aMultiFormValues['PAGE_ZONE_MULTI_ID'] = '-2';
            }
            if (isset($aMultiFormValues['multi_display']) && $aMultiFormValues['multi_display'] == 1) {
                $aMultiFormValuesById[$aMultiFormValues['PAGE_ZONE_MULTI_ID']] = $aMultiFormValues;
            }
        }

 
        //récupération des données de la perso.
        if ($zoneDynamique) {
            $aPerso = Backoffice_Form_Helper::getZoneDynamiquePerso(Pelican_Db::$values);
        } else {
            $aPerso = Backoffice_Form_Helper::getZonePerso(Pelican_Db::$values);
        }

        if (!empty($aPerso)) {
            $oPerso = json_decode($aPerso);
            if (is_object($oPerso)) {
                $oPerso = (array) $oPerso;
            }
            if (is_array($oPerso)&&!empty($oPerso)) {
                // On boucle sur tous les profiles
                
                foreach ($oPerso as $idProfile => $aDataPersoProfile) {
                    if($aDataPersoProfile){

                    $aSlides = $aDataPersoProfile->{$sMultiName};
                    if (is_array($aSlides) && count($aSlides)) {
                        // Ajout d'un slide générique s'il n existe pas dans la perso
                        // On boucle sur chaque slide                    
                        foreach ($aSlides as $id => $aSlide) {
                            if (is_array($aSlide) || is_object($aSlide)) {
                                // On synchronise uniqument les champs différents entre un slide géneral et un slide de la perso
                                if (
                                        isset($aSlide->multi_display) &&
                                        $aSlide->multi_display == 1 &&
                                        isset($aSlide->PAGE_ZONE_MULTI_ID) &&
                                        $aSlide->PAGE_ZONE_MULTI_ID != '-2' &&
                                        !empty($aSlide->PAGE_ZONE_MULTI_ID)
                                ) {

                                    $aSlideAsArray = (Array) $aSlide;
                                    if (is_array($aSlideAsArray) && is_array($aMultiFormValuesById[$aSlide->PAGE_ZONE_MULTI_ID])) {

                                        $aDiffKeys = array_diff_key($aSlideAsArray, $aMultiFormValuesById[$aSlide->PAGE_ZONE_MULTI_ID]);
                                    }
                                    //$aMultiFormValuesById[$aSlide->PAGE_ZONE_MULTI_ID]['PAGE_ZONE_MULTI_ORDER']=$aSlideAsArray['PAGE_ZONE_MULTI_ORDER'];

                                    if (count($aDiffKeys) && !isset($aSlide->__addedSlideMarker)) {
                                        //supprime les clés inexistantes dans le slide générique (non perso)
                                        foreach ($aDiffKeys as $sKeyToRemove => $valueToRemove) {
                                            if (strpos($sKeyToRemove, $sMultiName) == false) {
                                                unset($aSlide->$sKeyToRemove);
                                            }
                                        }
                                    }

                                    // Remplacement des données perso par les données génériques dans le slide courant
                                    if (count($aMultiFormValuesById[$aSlide->PAGE_ZONE_MULTI_ID])) {
                                        foreach ($aMultiFormValuesById[$aSlide->PAGE_ZONE_MULTI_ID] as $key => $value) {
                                            if (isset($key) && !empty($key)) {
                                                $oPerso[$idProfile]->{$sMultiName}[$id]->$key = is_string($value) ? urlencode($value) : $value;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                }
            }

            // Suppression du marqueur "__addedSlideMarker" (ajouté par addDataZoneMultiPerso) avant enregistrement dans la base
            if (is_array($oPerso)) {
                foreach ($oPerso as $keyProfile => $valProfile) {
                    $aSlides = $valProfile->{$sMultiName};
                    if (!is_array($aSlides) || empty($aSlides)) {
                        continue;
                    }
                    foreach ($aSlides as $keySlide => $valSlide) {
                        unset($valSlide->__addedSlideMarker);
                    }
                }
            }
        }
        if ($zoneDynamique) {
            Backoffice_Form_Helper::saveZoneDynamiquePerso(Pelican_Db::$values, $oPerso);
        } else {
            Backoffice_Form_Helper::saveZonePerso(Pelican_Db::$values, $oPerso);
        }
    }

    

    /**
     * Recuperation des multis d'une tranche
     * @param string $aValues    Pelican_Db::$values
     */
    public static function getDataZoneMultiValues($aValues = array()) {

        $aDatas = array();
        if (!empty($aValues)) {
            $oConnection = Pelican_Db::getInstance();
            $aBind[":LANGUE_ID"] = $aValues['LANGUE_ID'];
            $aBind[":PAGE_VERSION"] = $aValues['PAGE_VERSION'];
            $aBind[":PAGE_ID"] = $aValues['PAGE_ID'];
            $aBind[":ZONE_TEMPLATE_ID"] = $aValues['ZONE_TEMPLATE_ID'];
            $sSql = 'SELECT PAGE_ZONE_MULTI_ID, PAGE_ZONE_MULTI_TITRE
                        FROM #pref#_page_zone_multi
                        WHERE PAGE_ID = :PAGE_ID
                        AND LANGUE_ID = :LANGUE_ID
                        AND ZONE_TEMPLATE_ID =:ZONE_TEMPLATE_ID
                        AND PAGE_VERSION = :PAGE_VERSION';
            $aDatas = $oConnection->queryTab($sSql, $aBind);
        }
        return $aDatas;
    }

    /**
     * Set la zone perso de la table page_zone
     * @param array $aDatas Pelican_Db::$values 
     */
    public static function setZonePerso($aDatas = array(), $sMembre = '') {
        $aPerso = Backoffice_Form_Helper::getZonePerso($aDatas);
        if (!empty($aPerso) && !empty($sMembre)) {
            $oPerso = json_decode($aPerso);
            //renseigne le tableau [SLIDESHOW_NON_PERSO]
            //avec les id et les titres des push generiques
            if (is_array($oPerso) && !empty($oPerso)) {
                foreach ($oPerso as $key => $oSlide) {
                    $oPerso[$key]->$sMembre = Backoffice_Form_Helper::getDataZoneMultiValues($aDatas);
                }
                Backoffice_Form_Helper::saveZonePerso($aDatas, $oPerso);
                return true;
            }
        }
        return false;
    }

    /**
     * Retourne la zone perso de la table page_zone
     * @param array $aDatas Pelican_Db::$values 
     */
    public static function getZonePerso($aDatas = array()) {
        if (!empty($aDatas)) {
            $oConnection = Pelican_Db::getInstance();
            $aBind[":LANGUE_ID"] = $aDatas['LANGUE_ID'];
            $aBind[":PAGE_VERSION"] = $aDatas['PAGE_VERSION'];
            $aBind[":PAGE_ID"] = $aDatas['PAGE_ID'];
            $aBind[":ZONE_TEMPLATE_ID"] = $aDatas['ZONE_TEMPLATE_ID'];
            $sSql = 'SELECT ZONE_PERSO
                        FROM #pref#_page_zone
                        WHERE PAGE_ID = :PAGE_ID
                        AND LANGUE_ID = :LANGUE_ID
                        AND ZONE_TEMPLATE_ID =:ZONE_TEMPLATE_ID
                        AND PAGE_VERSION = :PAGE_VERSION';

            $aPerso = $oConnection->queryItem($sSql, $aBind);
            return $aPerso;
        }
        return false;
    }

    /**
     * Recuperation des multis d'une tranche
     * @param object $oPerso
     * @param array $aDatas Pelican_Db::$values      
     */
    public static function saveZonePerso($aDatas = array(), $oPerso = array()) {
        $oConnection = Pelican_Db::getInstance();
        if (is_array($oPerso) && !empty($oPerso) && is_array($aDatas) && !empty($aDatas)) {
            $aBind[":ZONE_PERSO"] = $oConnection->strToBind(json_encode($oPerso, 256));
            $aBind[":ZONE_PERSO"] = sprintf("'%s'", addslashes(json_encode($oPerso)));

            $aBind[":LANGUE_ID"] = $aDatas['LANGUE_ID'];
            $aBind[":PAGE_VERSION"] = $aDatas['PAGE_VERSION'];
            $aBind[":PAGE_ID"] = $aDatas['PAGE_ID'];
            $aBind[":ZONE_TEMPLATE_ID"] = $aDatas['ZONE_TEMPLATE_ID'];
            $sql = 'UPDATE #pref#_page_zone
                    SET ZONE_PERSO = :ZONE_PERSO 
                        WHERE PAGE_ID = :PAGE_ID 
                        AND LANGUE_ID = :LANGUE_ID 
                        AND PAGE_VERSION = :PAGE_VERSION 
                        AND ZONE_TEMPLATE_ID =:ZONE_TEMPLATE_ID';

            $oConnection->query($sql, $aBind);
            return true;
        }
        return false;
    }

    /**
     * Retourne la zone perso de la table page_multi_zone (zone dynamique)
     * @param array $aDatas Pelican_Db::$values 
     */
    public static function getZoneDynamiquePerso($aDatas = array()) {
        if (!empty($aDatas)) {
            $oConnection = Pelican_Db::getInstance();
            $aBind[":LANGUE_ID"] = $aDatas['LANGUE_ID'];
            $aBind[":PAGE_VERSION"] = $aDatas['PAGE_VERSION'];
            $aBind[":PAGE_ID"] = $aDatas['PAGE_ID'];
            $aBind[":AREA_ID"] = $aDatas['AREA_ID'];
            $aBind[":ZONE_ORDER"] = $aDatas['ZONE_ORDER'];
            $sSql = 'SELECT ZONE_PERSO
                        FROM #pref#_page_multi_zone
                        WHERE PAGE_ID = :PAGE_ID
                        AND LANGUE_ID = :LANGUE_ID
                        AND AREA_ID =:AREA_ID
                        AND PAGE_VERSION = :PAGE_VERSION
                        AND ZONE_ORDER = :ZONE_ORDER
                        ';

            $aPerso = $oConnection->queryItem($sSql, $aBind);
            return $aPerso;
        }
        return false;
    }

    /**
     * Recuperation des multis d'une tranche dynamique
     * @param string $aValues    Pelican_Db::$values
     */
    public static function getDataZoneDynamiqueMultiValues($aValues = array()) {

        $aDatas = array();
        if (!empty($aValues)) {
            $oConnection = Pelican_Db::getInstance();
            $aBind[":LANGUE_ID"] = $aValues['LANGUE_ID'];
            $aBind[":PAGE_VERSION"] = $aValues['PAGE_VERSION'];
            $aBind[":PAGE_ID"] = $aValues['PAGE_ID'];
            $aBind[":ZONE_ORDER"] = $aValues['ZONE_ORDER'];
            $sSql = 'SELECT PAGE_ZONE_MULTI_ID, PAGE_ZONE_MULTI_TITRE
                        FROM #pref#_page_multi_zone_multi
                        WHERE PAGE_ID = :PAGE_ID
                        AND LANGUE_ID = :LANGUE_ID
                        AND ZONE_ORDER = :ZONE_ORDER
                        AND PAGE_VERSION = :PAGE_VERSION';
            $aDatas = $oConnection->queryTab($sSql, $aBind);
        }
        return $aDatas;
    }

    /**
     * Set la zone perso de la table page_zone
     * @param array $aDatas Pelican_Db::$values
     * @param string $sMembre permet de set un membre de l'objet $oPerso      
     */
    public static function setZoneDynamiquePerso($aDatas = array(), $sMembre = '') {

        $aPerso = Backoffice_Form_Helper::getZoneDynamiquePerso($aDatas);
        if (!empty($aPerso) && !empty($sMembre)) {
            $oPerso = json_decode($aPerso);
            if (is_array($oPerso) && !empty($oPerso)) {
                foreach ($oPerso as $key => $oSlide) {
                    $oPerso[$key]->$sMembre = Backoffice_Form_Helper::getDataZoneDynamiqueMultiValues($aDatas);
                }
                Backoffice_Form_Helper::saveZoneDynamiquePerso($aDatas, $oPerso);
                return true;
            }
        }
        return false;
    }

    /**
     * Recuperation des multis d'une tranche dynamique
     * @param object $oPerso
     * @param array $aDatas Pelican_Db::$values     
     */
    public static function saveZoneDynamiquePerso($aDatas = array(), $oPerso = array()) {
        $oConnection = Pelican_Db::getInstance();
        if (is_array($oPerso) && !empty($oPerso) && is_array($aDatas) && !empty($aDatas)) {
            $aBind[":ZONE_PERSO"] = $oConnection->strToBind(json_encode($oPerso));
            $aBind[":LANGUE_ID"] = $aDatas['LANGUE_ID'];
            $aBind[":PAGE_VERSION"] = $aDatas['PAGE_VERSION'];
            $aBind[":PAGE_ID"] = $aDatas['PAGE_ID'];
            $aBind[":AREA_ID"] = $aDatas['AREA_ID'];
            $aBind[":ZONE_ORDER"] = $aDatas['ZONE_ORDER'];

            $sql = 'UPDATE #pref#_page_multi_zone
                    SET ZONE_PERSO = :ZONE_PERSO 
                        WHERE PAGE_ID = :PAGE_ID 
                        AND LANGUE_ID = :LANGUE_ID 
                        AND PAGE_VERSION = :PAGE_VERSION 
                        AND AREA_ID =:AREA_ID
                        AND ZONE_ORDER =:ZONE_ORDER';
            $oConnection->query($sql, $aBind);
            return true;
        }
        return false;
    }

    /**
     * Suppression des multis d'une tranche
     * @param array $aDatas Pelican_Db::$values
     * @param array $aIdDelete Tableau des slides à supprimer
     * @param string $sMultiName type du multi ex: SLIDESHOW_GENERIC
     * @param bool $dynamique Permet de savoir si on se trouve dans une tranche dynamique ou pas.     
     */
    public static function deleteDataZoneMultiPerso($aDatas = array(), $aIdDelete = '', $sMultiName = '', $dynamique = false) {
        $oConnection = Pelican_Db::getInstance();
        if ($dynamique) {
            $aPerso = Backoffice_Form_Helper::getZoneDynamiquePerso($aDatas);
        } else {
            $aPerso = Backoffice_Form_Helper::getZonePerso($aDatas);
        }
        if (!empty($aPerso)) {
            $oPerso = json_decode($aPerso);

            if (is_array($oPerso) && !empty($oPerso)) {
                // On boucle sur tous les profiles

                foreach ($oPerso as $idProfile => $aDataPersoProfile) {
                    $aSlides = $aDataPersoProfile->$sMultiName;
                    if (is_array($aSlides) && !empty($aSlides)) {
                        foreach ($aSlides as $key => $aSlide) {
                            if (in_array($aSlide->PAGE_ZONE_MULTI_ID, $aIdDelete)) {
                                unset($aSlides[$key]);
                            }
                        }
                        $oPerso[$idProfile]->$sMultiName = $aSlides;
                    }
                }
            }
            if ($dynamique) {
                Backoffice_Form_Helper::saveZoneDynamiquePerso($aDatas, $oPerso);
            } else {
                Backoffice_Form_Helper::saveZonePerso($aDatas, $oPerso);
            }
        }
    }

    /**
     * Ajout des multis d'une tranche
     * @param array $aDatas Pelican_Db::$values
     * @param array $aIdsAdd Tableau des slides à ajouter
     * @param string $sMultiName type du multi ex: SLIDESHOW_GENERIC
     * @param bool $dynamique Permet de savoir si on se trouve dans une tranche dynamique ou pas.
     */
    public static function addDataZoneMultiPerso($aDatas = array(), $aIdsAdd = '', $sMultiName = '', $dynamique = false) {
        $oConnection = Pelican_Db::getInstance();
        readMulti($sMultiName, $sMultiName);
        $aMultisFormValues = Pelican_Db::$values[$sMultiName];
        if ($dynamique) {
            $aPerso = Backoffice_Form_Helper::getZoneDynamiquePerso($aDatas);
        } else {
            $aPerso = Backoffice_Form_Helper::getZonePerso($aDatas);
        }
        if (!empty($aPerso)) {
            $oPerso = (array)json_decode($aPerso);            
            foreach ($oPerso as $idProfile => $aDataPersoProfile) {
                foreach ($aMultisFormValues as $aMultiForm) {
                    if (!isset($aMultiForm['PAGE_ZONE_MULTI_ID']) && empty($aMultiForm['PAGE_ZONE_MULTI_ID'])) {
                        if (isset($aMultiForm['multi_display']) && $aMultiForm['multi_display'] == 1) {
                            $MultiId = array_shift($aMultiForm);
                            $aMultiForm['PAGE_ZONE_MULTI_ID'] = $MultiId;
                            if (in_array($MultiId, $aIdsAdd)) {
                                if (isset($oPerso[$idProfile]->$sMultiName) && is_array($oPerso[$idProfile]->$sMultiName)) {
									 // Marquage du nouveau slide générique injecté dans la perso (pour éviter sa suppression par la synchronisation)
                                    $newMulti = $aMultiForm;
                                    $newMulti['__addedSlideMarker'] = true;
                                    if ($sMultiName == 'SLIDEOFFREADDFORM') {
                                        $oPerso[$idProfile]->SLIDEOFFREADDFORM[] = $newMulti;
                                    }
                                    if ($sMultiName == 'SLIDESHOW_GENERIC') {
                                        $oPerso[$idProfile]->SLIDESHOW_GENERIC[] = $newMulti;
                                    }
                                    $newMulti = null;
                                } else {
                                    if (!empty($oPerso[$idProfile]->$sMultiName)) {
                                        foreach ($oPerso[$idProfile]->$sMultiName as $oMultiForm) {
                                            if (isset($oMultiForm->multi_display) && $oMultiForm->multi_display == 1) {
                                                $aMultiForms[] = $oMultiForm;
                                            }
                                        }
                                    }
                                    if (is_array($aMultiForms) && !empty($aMultiForms)) {
                                        if (!empty($aMultiForm)) {
                                            $oPerso[$idProfile]->$sMultiName = array_merge($aMultiForms, array($aMultiForm));
                                        } else {
                                            $oPerso[$idProfile]->$sMultiName = $aMultiForms;
                                        }
                                    } elseif (!empty($aMultiForm)) {
                                        $oPerso[$idProfile]->$sMultiName = array($aMultiForm);
                                    }
                                }
                            }
                        }
                    }
                }
                if (is_array($oPerso[$idProfile]->SLIDESHOW_GENERIC) && !empty($oPerso[$idProfile]->SLIDESHOW_GENERIC)) {
                    // On bride l'ajout du nombre de slide à 5
                    $oPerso[$idProfile]->SLIDESHOW_GENERIC = \Citroen_View_Helper_Global::objectsIntoArray($oPerso[$idProfile]->SLIDESHOW_GENERIC);
					$aSlideTemp = array();
                    foreach ($oPerso[$idProfile]->SLIDESHOW_GENERIC as $aSlide) {
                        if ($aSlide['multi_display'] == 1) {
                            $aSlideTemp[] = $aSlide;
                        }
                    }
                    if (is_array($aSlideTemp) && !empty($aSlideTemp)) {
                        $oPerso[$idProfile]->SLIDESHOW_GENERIC = array_slice($aSlideTemp, 0, Pelican::$config['SLIDESHOW']['MAX_SLIDE']);
                    }
                }
            }
            // Mise a jour de l'objet perso avec les nouveaux slides supplémentaires
            if ($dynamique) {
                Backoffice_Form_Helper::saveZoneDynamiquePerso($aDatas, $oPerso);
            } else {
                Backoffice_Form_Helper::saveZonePerso($aDatas, $oPerso);
            }
        }
    }

    public function saveSynchronisation($sMultiName){
       $zoneDynamique  =   Pelican_Db::$values['ZONE_DYNAMIQUE'];

        // Récupération des données avant enregistrement
     
        $values=    $_REQUEST;        
        $values['ZONE_TEMPLATE_ID']   =   Pelican_Db::$values['ZONE_TEMPLATE_ID'];
        
        if(isset($zoneDynamique) && $zoneDynamique == 1){
            $valuesDb                   =   Pelican_Db::$values;
            $valuesDb['PAGE_VERSION']   =   $values['PAGE_VERSION'];  
            $aMultisBefore  =   Backoffice_Form_Helper::getDataZoneDynamiqueMultiValues($valuesDb);
        }else{
            $aMultisBefore  =   Backoffice_Form_Helper::getDataZoneMultiValues($values); 
        }

        
        Backoffice_Form_Helper::savePageZoneMultiValues($sMultiName,$sMultiName);
        
        // Mise à jour de la perso dans la table psa_page_zone         
        if(isset($zoneDynamique) && $zoneDynamique == 1){
            Backoffice_Form_Helper::setZoneDynamiquePerso(Pelican_Db::$values, 'SLIDESHOW_NON_PERSO'); 
        }else{
            Backoffice_Form_Helper::setZonePerso(Pelican_Db::$values, 'SLIDESHOW_NON_PERSO'); 
        }
        
        if(isset($zoneDynamique) && $zoneDynamique == 1){
            $aMultisAfter  =   Backoffice_Form_Helper::getDataZoneDynamiqueMultiValues(Pelican_Db::$values);
        }else{
            $aMultisAfter  =   Backoffice_Form_Helper::getDataZoneMultiValues(Pelican_Db::$values); 
        }
        
        foreach($aMultisAfter as $aMultiAfter){
            $aMultisAfterTemp[] =   $aMultiAfter['PAGE_ZONE_MULTI_ID'];
        }
        
        foreach($aMultisBefore as $aMultiBefore){
            $aMultisBeforeTemp[] =   $aMultiBefore['PAGE_ZONE_MULTI_ID'];
        }

        // Suppresion de slide
        if( empty($aMultisAfterTemp) && !empty($aMultisBeforeTemp)){
            $aIdsDelete     =   $aMultisBeforeTemp;
        }
        if(is_array($aMultisBeforeTemp) && is_array($aMultisAfterTemp) || !empty($aIdsDelete) ){
            if(empty($aIdsDelete)){
                $aIdsDelete = array_diff($aMultisBeforeTemp, $aMultisAfterTemp);
            }            
            if( is_array($aIdsDelete) && !empty($aIdsDelete) ){
                Backoffice_Form_Helper::deleteDataZoneMultiPerso(Pelican_Db::$values, $aIdsDelete, $sMultiName, $zoneDynamique );                       
            }
        }
        
        // Ajout de slide
        if( empty($aMultisBeforeTemp) && !empty($aMultisAfterTemp)){
            $aIdsAdd     =   $aMultisAfterTemp;
        }
        if(is_array($aMultisBeforeTemp) && is_array($aMultisAfterTemp) || !empty($aIdsAdd) ){
            if(empty($aIdsAdd)){
                $aIdsAdd = array_diff($aMultisAfterTemp, $aMultisBeforeTemp);
            }            
            if( is_array($aIdsAdd) && !empty($aIdsAdd) ){
                Backoffice_Form_Helper::addDataZoneMultiPerso(Pelican_Db::$values, $aIdsAdd, $sMultiName, $zoneDynamique );                         
            }
        }
        
        if(!empty($sMultiName)){
            Backoffice_Form_Helper::synchronisationDataPerso($sMultiName, $zoneDynamique);    
        }
    }

    public function createTitle($title, $class = "", $align = '', $valign = "h", $colspan = "2", $addon = "")
    {
        $title = Pelican_Html_Form::tr(Pelican_Html_Form::td(array('class' => $class.' form_title',
                    'style' => 'padding-top:15px;', 'valign' => $valign, 'align' => $align,
                    'colspan' => $colspan), Pelican_Html::h1($title.$addon)));

        return $this->output($title);
}
}
