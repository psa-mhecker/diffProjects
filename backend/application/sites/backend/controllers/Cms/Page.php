<?php
/**
 * * TRAITEMENT GENERIQUE DES ZONES DE PAGE
 * Nécessite :
 * - un formulaire de saisie de la Pelican_Index_Frontoffice_Zone (templates//application/sites/backend/layout/zone/Zone.php, le nom doit être en minuscules)
 * - le référencer dans le champs ZONE_NOM_PROC_AFF de la table des zones (avec ou sans le ".php")
 * - un fichier de transaction (processus//application/sites/backend/layout/zone/_zone.php)
 * Construction d'une Pelican_Index_Frontoffice_Zone :
 * - Créer une chaine sql ($strSqlZone) de sélection des données d'une Pelican_Index_Frontoffice_Zone (l'identifiant du contenu étant $this->id) elle ne sera pas exécutée en cas d'insertion
 * - Faire appel à la fonction générique initZone("db_zone.php", $strSqlZone, array(- Paramètres bind -), array(- Liste des champs CLOB -));
 * - Le tableau des valeurs contenant les champs pour la Pelican_Index_Frontoffice_Zone s'appelle alors $this->values
 * - Ecrire le template comme un template normal ensuite : l'objet Formulaire s'appelle $this->oForm.
 *
 * Valeurs par défaut :
 * - si une Pelican_Index_Frontoffice_Zone contient une valeur par défaut, le définir au tout début de la Pelican_Index_Frontoffice_Zone : setDefaultValue("champ", "valeur")
 * - si on est en update, c'est la valeur retrounée qui est utilisée sinon c'est la valeur par défaut
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @author Patrick Deroubaix <pderoubaix@businessdecision.com>
 *
 * @since 31/05/2004
 */


include_once dirname(__FILE__).'/../Cms.php';
include_once dirname(__FILE__).'/Page/Module.php';
include_once dirname(__FILE__).'/Page/Module/Navigation/1level.php';
require_once Pelican::$config['LIB_ROOT'].'/Pelican/Mail.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cache.php';

use Itkg\Utils\DatalayerFormHelper;
use PSA\MigrationBundle\Entity\Media\PsaMedia;

class Cms_Page_Controller extends Cms_Controller
{

    const PRE_HOME = 368;

    public $monoValues;
    public $zoneValues = array();
    public $pageValues = array();
    protected $form_name = "page";
    protected $field_id = "PAGE_ID";
    protected $clearurlId = 'pid';
    protected $cybertag = array(
        "pid",
        "page",
    );
    protected $workflowField = "PAGE";
    protected $userAllowed = true;
    protected $generalPage = false;

    protected $decacheBack = array(
        array(
            "Backend/Page",
            array(
                'SITE_ID',
                'LANGUE_ID',
            ),
        ),
        array(
            "backend/page_par_niveau_php",
            'SITE_ID',
        ),
        array(
            "Template/Page",
            'SITE_ID',
        )
    );
    protected $decachePublication = array();
    protected $decacheBackOrchestra = array();
    protected $decachePublicationOrchestra = array(
        'strategy' => array(
            // vide tous les cache lié a la page
            'strategy' => array(
                'siteId',
                'locale',
                'pageId',
            ),
            // vide les cache lie a la page general
            'general' => array(
                'general'),
            // vide les cache lie a la navigation
            'navigation' => array(
                'navigation'
            )
        ),
    );

    protected function _initValues()
    {
        
    }

    public function init()
    {
        if (!empty($_SESSION[APP]['PAGE_RETURN'])) {
            $tmp = $_SESSION[APP]['PAGE_RETURN'];
            unset($_SESSION[APP]['PAGE_RETURN']);
            unset($_REQUEST);
            unset($_POST);
            unset($_GET);
            echo($tmp);
        }
    }

    protected function setEditModel()
    {
        $oConnection = Pelican_Db::getInstance();

        $this->editModel = "select
			p.*,
			pv.*,
			pt.*,
			".$oConnection->dateSqlToString("PAGE_VERSION_CREATION_DATE")." as PAGE_VERSION_CREATION_DATE,
			".$oConnection->dateSqlToString("PAGE_PUBLICATION_DATE")." as PAGE_PUBLICATION_DATE,
			".$oConnection->dateSqlToString("PAGE_CREATION_DATE")." as PAGE_CREATION_DATE,
			".$oConnection->dateSqlToString("PAGE_START_DATE ", true)." as PAGE_START_DATE,
			".$oConnection->dateSqlToString("PAGE_END_DATE ", true)." as PAGE_END_DATE
			from
			#pref#_page p
			INNER JOIN #pref#_page_version pv on (p.PAGE_ID = pv.PAGE_ID AND pv.PAGE_VERSION = PAGE_DRAFT_VERSION AND p.LANGUE_ID = pv.LANGUE_ID)
			INNER JOIN #pref#_template_page tp on (pv.TEMPLATE_PAGE_ID=tp.TEMPLATE_PAGE_ID)
			INNER JOIN #pref#_page_type pt on (tp.PAGE_TYPE_ID=pt.PAGE_TYPE_ID)
			where
			pv.LANGUE_ID = :LANGUE_ID
			and p.PAGE_ID= :PAGE_ID
			and p.SITE_ID = :SITE_ID";
    }

    public function editAction()
    {
        if ($this->form_name == 'page' && $_GET["toprefresh"]) {
            echo("<script>top.location.href=top.location.href;</script>");
            die();
        }

        $oConnection = Pelican_Db::getInstance();

        $head = $this->getView()->getHead();

        $head->endJs('/js/zoning.js');
        //$head->setJs('/js/ssm.js');

        if (!valueExists($_GET, "sid")) {
            $_SESSION[APP]["session_start_page"] = $_SERVER["REQUEST_URI"];
        }

        /* contrôle des pages filles */
        if ($this->id) {
            $child = $oConnection->queryItem("select count(*) from #pref#_page where PAGE_PARENT_ID=".$this->id." OR (PAGE_ID=".$this->id." AND PAGE_PARENT_ID IS NULL)");
        }
        if ($this->id != Pelican::$config["DATABASE_INSERT_ID"]) {
            if (!$_SESSION[APP]["user"]["main"]) {
                $this->aBind[":PAGE_ID"] = $this->id;
                $this->aBind[":SITE_ID"] = ($this->values['SITE_ID'] ? $this->values['SITE_ID'] : $_SESSION[APP]['SITE_ID']);
                $user = $oConnection->queryItem("select PAGE_CREATION_USER from #pref#_page where SITE_ID=:SITE_ID and PAGE_ID=:PAGE_ID", $this->aBind);
                if (strpos($user, '#'.$_SESSION[APP]["user"]["id"].'#') === false) {
                    $this->userAllowed = false;
                }
            }
        }

        /*
         * * Récupération des infos de la page
         */
        $this->aBind[":LANGUE_ID"] = ($this->values['LANGUE_ID'] ? $this->values['LANGUE_ID'] : $_SESSION[APP]['LANGUE_ID']);
        $this->aBind[":PAGE_ID"] = $this->id;
        $this->aBind[":SITE_ID"] = ($this->values['SITE_ID'] ? $this->values['SITE_ID'] : $_SESSION[APP]['SITE_ID']);

        if ($this->userAllowed) {
            parent::editAction();

            // controle de variable de session
            //utilité?
            if (empty($this->values) && $this->id != -2) {
                $site = $oConnection->queryItem("select SITE_ID from #pref#_page where PAGE_ID = :PAGE_ID  AND LANGUE_ID = :LANGUE_ID and SITE_ID != :SITE_ID", $this->aBind);
                if ($site && $site != $_SESSION[APP]['SITE_ID']) {
                    echo('<div style="background-color:red;color:white;text-align:center;font-size:25px;"><br /><br />'.t('ATTENTION_AUTRE_NAVIGATEUR_OUVERT_SUR_AUTRE_SITE').'<br /><br />'.t('VEULLIEZ_RAFRAICHIR_PAGE').'<br /><br /><br /><br /></div>');
                    die();
                }
            } else {
                $_SESSION[APP]['TEMPLATE_PAGE_ID'] = $this->values['TEMPLATE_PAGE_ID'];
                if (!empty($_GET['gid']) && $_GET['gid'] !== $this->values['TEMPLATE_PAGE_ID']) {
                    $_SESSION[APP]['TEMPLATE_PAGE_ID'] = $_GET['gid'];
                }
            }
            Pelican_Cache::clean("Backend/State/Page");

            $form = '<table width="100%" border="0">';

            if (!$child && isset($_GET['readO']) && ($_GET['readO'] == true)) {
                $form .= '<tr><td style=\"width:30px\" valign=\"top\">&nbsp;</td><td class="erreur" style="width: 95%;">'.t('DELETE_PAGE_ALERT').'</td></tr>';
            }
            if (Pelican::$config["MODE_ZONE_VIEW"] == 'top') {
                $form .= ("<tr><td valign=\"top\">");
            } else {
                $form .= ("<tr><td  style=\"width:30px\" valign=\"top\">&nbsp;</td><td valign=\"top\">");
            }

            $this->oForm = Pelican_Factory::getInstance('Form', true);
            $this->oForm->bDirectOutput = false;
            $this->oForm->setView($this->getView());

            $form .= $this->oForm
                ->open(Pelican::$config["DB_PATH"]);
            $form .= $this->beginForm($this->oForm);
            $page_id = $this->id;

            if (valueExists($_GET, "pid")) {
                $this->aBind[":PAGE_ID"] = $_GET["pid"];
                $this->aBind[":LANGUE_ID"] = $_SESSION[APP]['LANGUE_ID'];
                $parent = $oConnection->queryRow("select * from #pref#_page where PAGE_ID=:PAGE_ID and LANGUE_ID=:LANGUE_ID", $this->aBind);
                $this->values["PAGE_ID"] = $page_id;
                $this->values["PAGE_PARENT_ID"] = $_GET["pid"];
            }

            if ($this->id == Pelican::$config["DATABASE_INSERT_ID"] || !$this->values["PAGE_PARENT_ID"]) {
                $this->values["PAGE_DISPLAY_PLAN"] = 1;
                $this->values["PAGE_DISPLAY"] = 1;
                $this->values["PAGE_DISPLAY_NAV"] = 1;
            }

            if (!valueExists($this->values, "PAGE_PATH")) {
                $this->aBind[":LANGUE_ID"] = $_SESSION[APP]['LANGUE_ID'];
                $this->aBind[":PAGE_PARENT_ID"] = $this->values["PAGE_PARENT_ID"];
                $this->values["PAGE_PATH"] = $oConnection->queryItem("select PAGE_PATH from #pref#_page where PAGE_ID=:PAGE_PARENT_ID and LANGUE_ID=:LANGUE_ID", $this->aBind);
                $this->values["PAGE_LIBPATH"] = $oConnection->queryItem("select PAGE_LIBPATH from #pref#_page where PAGE_ID=:PAGE_PARENT_ID and LANGUE_ID=:LANGUE_ID", $this->aBind);

                // Permet de connaître le chemin de la page courante pour l'arborescence
                if ($this->values["PAGE_PATH"]) {
                    $_SESSION[APP]['CURRENT_PAGE_PATH'] = implode('/', explode('#', $this->values["PAGE_PATH"]));
                } else {
                    $_SESSION[APP]['CURRENT_PAGE_PATH'] = '';
                }

                $this->values["PAGE_PATH"] .= "#%";
                $this->values["PAGE_LIBPATH"] .= "#%";
            } else {
                // Permet de connaître le chemin de la page courante pour l'arborescence
                $_SESSION[APP]['CURRENT_PAGE_PATH'] = implode('/', explode('#', $this->values["PAGE_PATH"]));
            }

            $this->generalPage = (($this->values["PAGE_GENERAL"] && $this->id != Pelican::$config["DATABASE_INSERT_ID"]));

            $form .= $this->oForm
                ->setTab("1", t('BLOCS'));
            if (!$this->generalPage) {
                $form .= $this->oForm
                    ->setTab("3", t('PUBLICATION'));
                $form .= $this->oForm
                    ->setTab("4", t('NDP_CMS_TAGGAGE'));
            }

            $form .= $this->oForm
                ->setTab("2", t('SEO'));

            if (!$this->generalPage) {


                $form .= $this->oForm
                    ->beginFormTable();

                if ($this->id != - 2) {
                    $form .= $this->oForm->createLabel("pid", $this->id);
                    $baseUrl =  $this->getBaseUrl();
                    $aPageType = Pelican_Cache::fetch("PageType/Template", array(
                            $this->values['TEMPLATE_PAGE_ID'],
                    ));
                    if ($aPageType['PAGE_TYPE_SHORTCUT']) {
                        $shortcut = str_replace('//', '/', '/'.$aPageType['PAGE_TYPE_SHORTCUT']);
                        $form .= $this->oForm->createLabel(t('SHORT_ADDRESS'), Pelican_Html::a(array(
                                'href' =>$baseUrl.$shortcut,
                                'target' => "_blank",
                                ), $shortcut));
                    }

                    $labelValue = $this->values["PAGE_CLEAR_URL"];

                    if ($this->pageIsPublished()) {
                        $labelValue = Pelican_Html::a(array(
                                'href' => $baseUrl.$this->values["PAGE_CLEAR_URL"],
                                'target' => "_blank",
                                ), $this->values["PAGE_CLEAR_URL"]);
                    }
                    $form .= $this->oForm->createLabel(t('LONG_ADDRESS'), $labelValue);
                }

                // recuperation de l'url réelle courante
                $form .= $this->oForm->createHidden("OLD_URL", $this->values["PAGE_CLEAR_URL"]);

                $aTemplate = getComboValuesFromCache("Template/Page", array(
                    $_SESSION[APP]["SITE_ID"],
                    "",
                    "",
                    ($this->values['PAGE_TYPE_ID'] ? $this->values['PAGE_TYPE_ID'] : - 1),
                    $_SESSION[APP]['LANGUE_ID'],
                    Pelican::$config["SITE_MASTER"]
                ));

                foreach ($aTemplate as $key => $template) {
                    $aTemplate[$key] = t($template);
                }
                $form .= $this->oForm->createComboFromList("TEMPLATE_PAGE_ID", t('LAYOUT'), $aTemplate, ($_GET["gid"] ? $_GET["gid"] : $this->values["TEMPLATE_PAGE_ID"]), true, $this->readO, "1", false, "", true, false, "onchange=\"changeGabarit(this);\"");
                $form .= $this->oForm->createHidden("PROFILE_LIST", $this->values['PROFILE_LIST']);
                if (valueExists($_GET, "gid") || valueExists($this->values, "TEMPLATE_PAGE_ID")) {

                    $form .= $this->oForm
                        ->createTextArea("PAGE_TITLE_BO", t('SHORT_TITLE'), true, $this->values["PAGE_TITLE_BO"], 60, $this->readO, 2, 75, false, "", true);

                    $form .= $this->oForm
                        ->createHidden("PAGE_OLD_TITLE", $this->values["PAGE_TITLE_BO"]);

                    $form .= $this->oForm
                        ->createHidden("PAGE_OLD_CLEAR_URL", '');

                    $maxLength = 60;
                    $form .= $this->oForm->createJS("
						if(obj.PAGE_TITLE != undefined && obj.PAGE_TITLE.value.length > $maxLength){
						alert('".t('ALERT_PAGE_TITLE_LONG_MAX', 'js', array('max_length' => $maxLength))."');

						}
						if(obj.PAGE_TITLE_BO.value != obj.PAGE_OLD_TITLE.value && obj.PAGE_OLD_TITLE.value != '' && obj.OLD_URL.value != ''){
							if(confirm('".t('CHANGE_CLEAR_URL', 'js')."')){
								obj.PAGE_CLEAR_URL.value = '';
								if(confirm('".t('ADD_REDIRECTION_301', 'js')."')){
									obj.PAGE_OLD_CLEAR_URL.value = '".$this->values["PAGE_CLEAR_URL"]."';
								}
							}
						}
					");
                }

                if (valueExists($_GET, "sid")) {
                    $this->aButton["addpage"] = false;
                    $this->aButton["deletepage"] = false;
                    $this->aButton["up"] = false;
                    $this->aButton["down"] = false;
                } else {
                    $this->aButton["addpage"] = true;
                    $this->aButton["deletepage"] = (!$child ? true : false);
                    $this->aButton["up"] = ($this->values["PAGE_PARENT_ID"] ? true : false);
                    $this->aButton["down"] = ($this->values["PAGE_PARENT_ID"] ? true : false);
                }

                $form .= $this->oForm->endFormTable();
            } else {
                $form .= $this->oForm->createHidden("PAGE_TITLE", t("NDP_PAGE_GENERALE"));
                $form .= $this->oForm->createHidden("PAGE_TITLE_BO", t("NDP_PAGE_GENERALE"));
                unset($this->oForm->aTab["2"]);
            }
            $form .= $this->oForm->createHidden("OLD_PAGE_TITLE_BO", $this->values["PAGE_TITLE_BO"]);

            if (!valueExists($_GET, "tpl") && valueExists($this->values, "TEMPLATE_PAGE_ID")) {
                $_GET["tpl"] = $this->values["TEMPLATE_PAGE_ID"];
            }

            // debut si program
            /*
             * AFFICHAGE DES ZONES
             */
            if (valueExists($_GET, "tpl")) {
                $this->aBind[":TPL"] = $_GET["tpl"];
                $sqlZone = "select distinct
				zt.*,
				z.*,
				tpa.*,
				a.AREA_LABEL,
				a.AREA_HORIZONTAL,
				a.AREA_DROPPABLE
				from
				#pref#_zone_template zt
				INNER JOIN #pref#_template_page_area tpa on (tpa.TEMPLATE_PAGE_ID=zt.TEMPLATE_PAGE_ID AND tpa.AREA_ID=zt.AREA_ID)
				INNER JOIN #pref#_zone z on (z.ZONE_ID=zt.ZONE_ID)
				INNER JOIN #pref#_area a on (a.AREA_ID=tpa.AREA_ID)
				where
				zt.TEMPLATE_PAGE_ID = :TPL
				order by LIGNE, COLONNE, ZONE_TEMPLATE_ORDER";
                $tabZones = $oConnection->getTab($sqlZone, $this->aBind);
            }

            /* changement de gabarit */
            if (valueExists($_GET, "gid")) {
                if ($tabZones) {
                    foreach ($tabZones as $layout) {
                        $zone1[$layout["ZONE_ID"]][] = $layout["ZONE_TEMPLATE_ID"];
                    }
                }

                $sGabarit = "select distinct
				zt.*,
				z.*,
				tpa.*,
                a.AREA_LABEL,
				a.AREA_HORIZONTAL,
				a.AREA_DROPPABLE
				from
				#pref#_zone_template zt
				INNER JOIN #pref#_template_page_area tpa on (tpa.TEMPLATE_PAGE_ID=zt.TEMPLATE_PAGE_ID AND tpa.AREA_ID=zt.AREA_ID)
				INNER JOIN #pref#_zone z on (z.ZONE_ID=zt.ZONE_ID)
                INNER JOIN #pref#_area a on (a.AREA_ID=tpa.AREA_ID)
				where
				zt.TEMPLATE_PAGE_ID = ".$_GET["gid"]."
				order by LIGNE, COLONNE, ZONE_TEMPLATE_ORDER";
                $tabZones = $oConnection->getTab($sGabarit, $this->aBind);

                if ($tabZones) {
                    foreach ($tabZones as $layout) {
                        $this->aNewZone[$layout["ZONE_TEMPLATE_ID"]] = @array_shift($zone1[$layout["ZONE_ID"]]);
                    }
                }
            }

            /* Multi general */
            $this->aBind[':PAGE_VERSION'] = $this->values["PAGE_VERSION"];
            $sSqlMulti = "
                select *
                from #pref#_page_multi
                where PAGE_ID = :PAGE_ID
                and LANGUE_ID = :LANGUE_ID
                and PAGE_VERSION = :PAGE_VERSION
                order by PAGE_MULTI_TYPE asc, PAGE_MULTI_ID asc";
            $aMultiValues = $oConnection->queryTab($sSqlMulti, $this->aBind);
            $multiValues = array();
            if ($aMultiValues) {
                foreach ($aMultiValues as $values) {
                    if (!isset($multiValues[$values['PAGE_MULTI_TYPE']])) {
                        $multiValues[$values['PAGE_MULTI_TYPE']] = array();
                    }
                    $multiValues[$values['PAGE_MULTI_TYPE']][$values['PAGE_MULTI_ID']] = $values;
                }
            }

            $pageI = 0;
            $nbrbloc = sizeOf($tabZones);
            if (valueExists($_GET, "gid") || valueExists($this->values, "TEMPLATE_PAGE_ID")) {
                $form .= $this->oForm->beginTab("1");
                $templatePageId = $this->values['TEMPLATE_PAGE_ID'];
                if (empty($templatePageId)) {
                    $templatePageId = $_GET['gid'];
                }
                if (!valueExists($_GET, "blc")) {
                    if ($this->generalPage || !$this->values["PAGE_PARENT_ID"]) {

                        $form .= $this->oForm->createHidden("PAGE_VERSION", $this->values["PAGE_VERSION"]);
                        if ($this->generalPage) {
                            $form .= $this->oForm->createHidden("TEMPLATE_PAGE_ID", $this->values["TEMPLATE_PAGE_ID"]);
                        }
                        $form .= $this->oForm->createHidden("PAGE_DISPLAY_PLAN", ($this->values["PAGE_GENERAL"] ? 0 : 1));
                        $form .= $this->oForm->createHidden("PAGE_DISPLAY", 1);
                        $form .= $this->oForm->createHidden("PAGE_DISPLAY_NAV", ($this->values["PAGE_GENERAL"] ? 0 : 1));

                        // evol 2862
                        $form .= $this->oForm->createFreeHtml($this->_beginZone("0", "<b>".t('GLOBAL_PAGE')."</b>", 0, "1", (!$this->readO && $this->id != - 2), true, true, '', '', false, false));
                        $form .= $this->oForm->beginFormTable("0", "0", "form", true, "tabletogglezone".$pageI);
                        $form .= $this->oForm->createInput("PAGE_SHORTTEXT", t('FORM_LINK_LABEL'), 30, "", false, $this->values["PAGE_SHORTTEXT"], $this->readO, 50);
                        $form .= $this->oForm->endFormTable();
                        $form .= $this->oForm->createFreeHtml($this->_endZone());
                        // fin evol
                    } else {

                        if (self::PRE_HOME != $templatePageId) {
                            $form .= $this->oForm->createFreeHtml($this->_beginZone("0", "<b>".t('GLOBAL_PAGE')."</b>", 0, "1", (!$this->readO && $this->id != - 2), true, true, '', '', false, false));
                            $form .= $this->oForm->beginFormTable("0", "0", "form", true, "tabletogglezone".$pageI);
                            if ($this->values["PAGE_PARENT_ID"]) {
                                /* Cases affichées uniquement pour les niveaux 1 et 2 */
                                $form .= $this->oForm->createCheckBoxFromList("PAGE_DISPLAY_NAV", t('AFFICHER'), array("1" => t('IN_SITE_MENU')), $this->values["PAGE_DISPLAY_NAV"], false, $this->readO, "h"
                                );
                                $form .= $this->oForm->createCheckBoxFromList("PAGE_DISPLAY_PLAN", "", array("1" => t('IN_SITE_MAP')), $this->values["PAGE_DISPLAY_PLAN"], false, $this->readO, "h");
                                $form .= $this->oForm->createHidden("PAGE_DISPLAY", 1);
                            }

                            $form .= $this->oForm->createCheckBoxFromList("PAGE_CODE", t('NDP_DISPLAY_BACK_TO_MOBILE_BUTTON'), array("1" => ""), $this->values["PAGE_CODE"], false, $this->readO, "h");
                            $parameters = [];
                            $codePaysById = Pelican_Cache::fetch('Ndp/CodePaysById');
                            $parameters['languages'] = strtolower($_SESSION[APP]['LANGUE_CODE']);
                            $parameters['countries'] = $codePaysById[$_SESSION[APP]['SITE_ID']];
                            $aDataValues = $this->container->get('range_manager')->getGammesVehiculesByModelSilhouette($parameters);

                            if ($this->values['PAGE_ID'] == Pelican::$config["DATABASE_INSERT_ID"] && count(explode('#', $this->values['PAGE_PATH'])) > 2) {
                                $aBind = array(':PAGE_PARENT_ID' => $this->values['PAGE_PARENT_ID']);
                                $parentPageGammeVehiculeSql = 'SELECT
                                    pv.PAGE_GAMME_VEHICULE
                                    FROM #pref#_page as p, #pref#_page_version as pv
                                    WHERE p.PAGE_ID=:PAGE_PARENT_ID
                                    AND p.PAGE_ID = pv.PAGE_ID
                                    AND p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION
                                    AND p.LANGUE_ID = pv.LANGUE_ID
                                ';
                                $this->values["PAGE_GAMME_VEHICULE"] = $oConnection->queryItem($parentPageGammeVehiculeSql, $aBind);
                            }
                            $form .= $this->oForm->createComboFromList('PAGE_GAMME_VEHICULE', t('NDP_MODEL_GRP_SILH'), $aDataValues, $this->values["PAGE_GAMME_VEHICULE"], false, $this->readO);
                            $form .= $this->oForm->createInput("PAGE_SHORTTEXT", t('REEVOO_ID'), 15, "text", false, $this->values["PAGE_SHORTTEXT"], $this->readO, 50);
                            $form .= $this->oForm
                                ->createInput("PAGE_TITLE",
                                    t('LONG_TITLE'),
                                    60,
                                    "text",
                                    false,
                                    $this->values["PAGE_TITLE"],
                                    $this->readO,
                                    70
                                );
                            $form .= $this->oForm->createTextArea("PAGE_TEXT", t('CHAPO'), false, $this->values["PAGE_TEXT"], 255, $this->readO, 5, 70, false, "", true);
                            $form .= $this->oForm->showSeparator();
                            $form .= $this->oForm->createInput("PAGE_URL_EXTERNE", t('URL_EXTERNE'), 255, "internallink", false, $this->values['PAGE_URL_EXTERNE'], $this->readO, 70);
                            if ($this->isChildOfMasterPage($this->values['PAGE_PARENT_ID'])) {
                                $form .= $this->oForm->createMedia("MEDIA_ID2", t('NDP_VISUEL_PARENT_PAGE'), false, "image", "", $this->values["MEDIA_ID2"], $this->readO, true, false, 'NDP_RATIO_16_9:858x481');
                            }
                            $form .= $this->oForm->createCheckBoxFromList("PAGE_OUVERTURE_DIRECT", t('PAGE_OUVERTURE_DIRECT'), array('1' => ''), $this->values['PAGE_OUVERTURE_DIRECT'], false, $this->readO);
                            if (!isset($this->values['PAGE_URL_EXTERNE_MODE_OUVERTURE'])) {
                                $this->values['PAGE_URL_EXTERNE_MODE_OUVERTURE'] = '1';
                            }
                            $form .= $this->oForm->createRadioFromList("PAGE_URL_EXTERNE_MODE_OUVERTURE", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $this->values['PAGE_URL_EXTERNE_MODE_OUVERTURE'], true, $this->readO);

                            $form .= $this->oForm->showSeparator();
                            if (empty($last["PAGE_VERSION"])) {
                                $last["PAGE_VERSION"] = 1;
                            }

                            $form .= $this->oForm->endFormTable();
                            $form .= $this->oForm->createFreeHtml($this->_endZone());
                        }
                    }
                    $form .= $this->oForm->createhidden("increment_page", "page");
                    $form .= $this->oForm->createhidden("count_page", sizeOf($tabZones) - 1);
                    $form .= $this->oForm->createhidden("PAGE_PARENT_ID", $this->values["PAGE_PARENT_ID"]);
                    $form .= $this->oForm->createhidden("PAGE_ORDER", $this->values["PAGE_ORDER"]);
                    $form .= $this->oForm->createhidden("PAGE_DIFFUSION", $this->values["PAGE_DIFFUSION"]);
                    $form .= $this->oForm->createhidden("PAGE_LOGO", (isset($this->values["PAGE_LOGO"]) ? $this->values["PAGE_LOGO"] : ""));
                    $form .= $this->oForm->createHidden("PAGE_GENERAL", $this->values["PAGE_GENERAL"]);
                    $form .= $this->oForm->createHidden("PAGE_CHILD_ORDER", $this->values["PAGE_CHILD_ORDER"]);
                    $form .= $this->oForm->createhidden("PAGE_PATH", $this->values["PAGE_PATH"]);
                    $form .= $this->oForm->createhidden("PAGE_LIBPATH", $this->values["PAGE_LIBPATH"]);

                    $this->pageValues = $page = $this->values;
                    $currentArea = '';
                    if ($tabZones) {
                        foreach ($tabZones as $data) {
                            if ($data["AREA_DROPPABLE"] == 1) {
                                $this->oForm->setMulti();
                                $this->getMultiZones($data, $currentArea, $form, $zoneDynamiqueJs);
                            } elseif ($data["ZONE_TYPE_ID"] != 2) {
                                if ($currentArea != $data["AREA_ID"] && $currentArea == '') {
                                    $form .= $this->oForm
                                        ->createFreeHtml('<tbody><tr><td colspan="2">');

                                    $currentArea = $data["AREA_ID"];

                                    if ($_SESSION[APP]['SITE_ID'] > Pelican::$config["ADM_MINISITE_ID"]) {
                                        $areaLabelTab = explode(" - ", $data['AREA_LABEL']);
                                        $nameZone = $areaLabelTab[1];
                                    } else {
                                        $nameZone = 'Zone '.$data['TEMPLATE_PAGE_AREA_ORDER'];
                                    }

                                    $form .= $this->oForm->createFreeHtml('<br/><fieldset class="page">'.Pelican_Html::legend(t($nameZone)));
                                    $form .= $this->oForm->createFreeHtml('<table style="width: 100%">');
                                } elseif ($currentArea != $data["AREA_ID"]) {
                                    $form .= $this->oForm->createFreeHtml('</table>');
                                    $form .= $this->oForm->createFreeHtml('</fieldset>');

                                    $currentArea = $data["AREA_ID"];

                                    if ($_SESSION[APP]['SITE_ID'] > Pelican::$config["ADM_MINISITE_ID"]) {
                                        $areaLabelTab = explode(" - ", $data['AREA_LABEL']);
                                        $nameZone = $areaLabelTab[1];
                                    } else {
                                        $nameZone = 'Zone '.$data['TEMPLATE_PAGE_AREA_ORDER'];
                                    }

                                    $form .= $this->oForm->createFreeHtml('<br/><fieldset class="page">'.Pelican_Html::legend(t($nameZone)));
                                    $form .= $this->oForm->createFreeHtml('<table style="width: 100%">');
                                }

                                /*
                                 * * sélection du template de saisie et du template de traitement
                                 */
                                $root = ($data["PLUGIN_ID"] ? Pelican::$config["PLUGIN_ROOT"].'/'.$data["PLUGIN_ID"].'/backend/controllers' : Pelican::$config['APPLICATION_CONTROLLERS']);
                                $module = $root.'/'.str_replace("_", "/", $data["ZONE_BO_PATH"]).".php";
                                $moduleClass = $data["ZONE_BO_PATH"];

                                /*
                                 * * Input cachés identifiants les zones et les fichiers de transaction
                                 */
                                $ZONE_TEMPLATE_ID[] = $this->oForm
                                    ->createHidden("ZONE_TEMPLATE_ID[]", $data["ZONE_TEMPLATE_ID"], true);

                                /*
                                 * * Etat ouvert ou non : initialisé par les cookies
                                 */
                                $closed = false;
                                $setCookie = false;
                                if ($data["ZONE_TEMPLATE_ID"] == 1) {
                                    $setCookie = false;
                                } elseif (isset($_COOKIE["togglezone".$data["ZONE_TEMPLATE_ID"]])) {
                                    $setCookie = true;
                                    if ($_COOKIE["togglezone".$data["ZONE_TEMPLATE_ID"]] != "false") {
                                        $closed = true;
                                    }
                                }
                                $this->multi = "multi".$pageI."_";
                                $pageI ++;

                                /* valeurs par défaut : pour l'intercepter il faut mettre la commande dans le template */
                                $this->zoneValues = $this->_getZoneValues($page, $data, $moduleClass);
                                $this->zoneValues["PAGE_ID"] = $page_id;
                                $this->zoneValues['LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
                                $this->zoneValues["PAGE_VERSION"] = $page["PAGE_VERSION"];
                                $this->zoneValues['ALT_TEMPLATE_PAGE_ID'] = $page['ALT_TEMPLATE_PAGE_ID'];

                                /*
                                 * * Affichage de la Pelican_Index_Frontoffice_Zone dans une table
                                 */
                                $persoZone = null;
                                $label = t($data["ZONE_TEMPLATE_LABEL"]);
                                if ( ! empty($this->zoneValues['ZONE_TITRE'])) {
                                    $label .= ' - <b>'. $this->limitLength($this->zoneValues['ZONE_TITRE'], 50).'</b>';
                                }

                                if ($data["IS_DROPPABLE"] == 1) {
                                    //si le bloc se trouve dans une Pelican_Index_Frontoffice_Zone droppable
                                    $form .= $this->oForm
                                        ->createFreeHtml($this->_beginZone($data["ZONE_TEMPLATE_ID"], $label."<!--".$data["ZONE_TEMPLATE_ID"]."-->", $nbrbloc, $data["ZONE_TYPE_ID"], true, $setCookie, true, $this->multi."ZONE_EMPTY", $data["ZONE_PROGRAM"], $this->zoneValues["ZONE_EMPTY"], false));
                                    $form .= $this->oForm
                                        ->beginFormTable("0", "0", "form", true, "tabletogglezone".$pageI);
                                } elseif ($data["ZONE_TYPE_ID"] == 3) {
                                    $form .= $this->oForm
                                        ->createFreeHtml($this->_beginZone($data["ZONE_TEMPLATE_ID"], $label."<!--".$data["ZONE_TEMPLATE_ID"]."-->", $nbrbloc, $data["ZONE_TYPE_ID"], true, $setCookie, true, $this->multi."deleteZone", $data["ZONE_PROGRAM"], $this->zoneValues["ZONE_EMPTY"], false));
                                    $form .= $this->oForm
                                        ->beginFormTable("0", "0", "form", true, "tabletogglezone".$pageI);
                                } elseif ($data["ZONE_TYPE_ID"] == 1) {
                                    $form .= $this->oForm
                                        ->createFreeHtml($this->_beginZone($data["ZONE_TEMPLATE_ID"], $label."<!--".$data["ZONE_TEMPLATE_ID"]."-->", $nbrbloc, $data["ZONE_TYPE_ID"], true, $setCookie, true, "", $data["ZONE_PROGRAM"], "", false));
                                    $form .= $this->oForm
                                        ->beginFormTable("0", "0", "form", true, "tabletogglezone".$pageI);
                                }

                                /*
                                 * * Si le fichiers n'existe pas, la mention A FAIRE est affichée
                                 */
                                $form .= $this->oForm
                                    ->createhidden($this->multi."multi_display", 1);
                                if (!file_exists($module)) {
                                    $form .= $this->oForm
                                        ->createFreeHtml("<span class=\"erreur\">".$module." => A FAIRE</span>");
                                } else {
                                    include_once $module;
                                    $tmpPerso = '';

                                    /*
                                     * ID HTML
                                     */
                                    $idHTML = '#'.Pelican_Text::cleanText($this->zoneValues['ZONE_TEMPLATE_LABEL'], '-', false, false).'_'.$this->zoneValues['ZONE_TEMPLATE_ID'];
                                    $tmpPerso .= $this->oForm->createLabel(t('ID_HTML'), $idHTML);

                                    $tmp = call_user_func_array(array(
                                        $moduleClass,
                                        'render',
                                        ), array(
                                        $this,
                                    ));

                                    //Prise en compte de la class zend_form
                                    if ($this->generalPage) {
                                        $form .= $this->oForm->createFreeHtml($tmpPerso.$tmp);
                                    } else {
                                        $form .= $tmpPerso.$tmp;
                                    }
                                }

                                // si la verif est activer (CheckBox mobile/ web)
                                // helper backend getFormAffichage
                                if ($this->zoneValues['VERIF_JS'] == 1) {
                                    $form .= $this->oForm->createJS('}');
                                }

                                $form .= $this->oForm
                                    ->createhidden($this->multi."ZONE_TYPE_ID", $data["ZONE_TYPE_ID"]);
                                $form .= $this->oForm
                                    ->createhidden($this->multi."ZONE_TEMPLATE_ID", $data["ZONE_TEMPLATE_ID"]);
                                $form .= $this->oForm
                                    ->createhidden($this->multi."ZONE_ID", $data["ZONE_ID"]);
                                $form .= $this->oForm
                                    ->createhidden($this->multi."PLUGIN_ID", $data["PLUGIN_ID"]);

                                $form .= $this->oForm
                                    ->endFormTable();

                                $form .= $this->oForm
                                    ->createFreeHtml($this->_endZone());
                            }
                        }

                        $form .= $this->oForm
                            ->createFreeHtml('</table>');
                        $form .= $this->oForm
                            ->createFreeHtml('</fieldset>');
                        if ($ZONE_TEMPLATE_ID) {
                            $form .= $this->oForm
                                ->createFreeHtml(implode('', $ZONE_TEMPLATE_ID));
                        }
                        if ($this->moduleList) {
                            foreach ($this->moduleList as $mod) {
                                $form .= $this->oForm
                                    ->createHidden("ZONE_DB[]", $mod, true);
                            }
                        }
                    }
                } else {
                    $sqlZone = "select
					#pref#_zone_template.*,
					#pref#_zone.ZONE_BO_PATH,
					#pref#_zone.ZONE_LABEL,
					#pref#_zone_template.ZONE_TEMPLATE_LABEL,
					#pref#_zone.ZONE_TYPE_ID,
					#pref#_zone.ZONE_PROGRAM
					from
					#pref#_zone_template,
					#pref#_zone
					where
					#pref#_zone.ZONE_ID=#pref#_zone_template.ZONE_ID
					and ZONE_TEMPLATE_ID=".$_GET["blc"]."
					order by LIGNE, COLONNE";
                    $aProgZones = $oConnection->queryrow($sqlZone);

                    $form .= $this->oForm
                        ->createMulti($oConnection, "form_program", $aProgZones["ZONE_TEMPLATE_LABEL"], Pelican::$config['APPLICATION_CONTROLLERS'].'/'.$aProgZones["ZONE_BO_PATH"], null, "program_number", $this->readO, "", true);
                    $form .= $this->oForm
                        ->createhidden("ZONE_TEMPLATE_ID", $_GET["blc"]);
                    $form .= $this->oForm
                        ->createhidden("ZONE_PROGRAM", $aProgZones["ZONE_PROGRAM"]);
                    $form .= $this->oForm
                        ->createhidden("PAGE_ID", $this->id);
                }
                $this->values = $page;

                // Publication
                if (!$this->generalPage) {
                   $form .= $this->getPublicationTab();
                   $form .= $this->getTaggageTab();
                }
                $form .= $this->oForm
                    ->createFreeHtml($this->getWorkflowFields($this->oForm, $this->generalPage));

                $form .= $this->getSeoTab();

                $form .= $this->oForm->createFreeHtml($this->endForm($this->oForm, (valueExists($_GET, "sid") ? "" : "noback")));
                $form .= $this->oForm->endTab();

                $form .= $this->oForm->close();
                $form .= "</td></tr></table>";

                if (valueExists($_GET, "popup_content")) {
                    /*
                     * * dans le cas où le tea_id est défini (popup)
                     */
                    $form .= $this->oForm->createFreeHtml("<script type=\"text/javascript\">top.history = escape(document.fForm.form_retour.value);top.id = ''; top.refresh();</script>");
                }

                /*
                 * * VERSION 2
                 */
                if (!valueExists($_GET, "pid")) {
                    $tableZone = $this->_generateZone($tabZones);
                    $zone_types = Pelican_Cache::fetch("Backend/ZoneType");
                    if ($zone_types) {
                        if (Pelican::$config["MODE_ZONE_VIEW"] == 'top') {
                            $tableZone .= '<table align="center" id="visualZoneLegende"><tr>';
                        }
                        foreach ($zone_types as $zone_type) {
                            if (Pelican::$config["MODE_ZONE_VIEW"] == 'top') {
                                $tableZone .= '<td width="33%" align="center">';
                            }
                            $tableZone .= "&nbsp;<span class=\"zonetype".$zone_type["ZONE_TYPE_ID"]."\" height=\"10px\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;".$zone_type["ZONE_TYPE_LABEL"]."<br />";
                            if (Pelican::$config["MODE_ZONE_VIEW"] == 'top') {
                                $tableZone .= '</td>';
                            }
                        }
                        if (Pelican::$config["MODE_ZONE_VIEW"] == 'top') {
                            $tableZone .= '</tr></table>';
                        }
                    }

                    if (Pelican::$config["MODE_ZONE_VIEW"] == 'top') {
                        $tableZone = '
                        <div id="visualZoneTop" style="display:none">
                            <table cellpadding="0" cellspacing="0" width="99%">
                                <tr id="visualZoneView">
                                    <td bgcolor="white">
                                        <div>'.$tableZone.'</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" align="center" style="position:relative" >
                                        <div id="visualZoneDisplay">
                                            <p align="center"><font size="2" face="Verdana" color="black"><b>ZONING</b></font></p>
                                            <p id="visualZoneDisplayMin" align="center"><font size="2" face="Verdana" color="black">v</font></p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>';
                    } else {
                        $tableZone = '
                        <div id="visualZone" style="display:none">
                            <table cellpadding="0" cellspacing="1" bgcolor="black" width="382">
                                <tr>
                                    <td  width="349" bgcolor="white">'.$tableZone.'</td>
                                    <td width="30" valign="top" bgcolor="#79AFEC" align="center" style="filter: progid:DXImageTransform.Microsoft.Gradient(gradientType=1,startColorStr=#DDECFE,endColorStr=#79AFEC);">
                                        <p align="center"><font size="2" face="Verdana" color="black"><b> <br>Z<br>O<br>N<br>E<br>S<br></b></font></p>
                                    </td>
                                </tr>
                            </table>
                        </div>';
                    }
                }
            }

            // Zend_Form start
            if (($this->oForm instanceof Zend_Form)) {
                /*                 * ******** Pour faire correspondre ********* */
                $form = '<table width="100%" border="0">';
                if (!$child && isset($_GET['readO']) && ($_GET['readO'] == true)) {
                    $form .= '<tr><td style="width:30px" valign="top">&nbsp;</td><td class="erreur" style="width: 95%;">'.t('DELETE_PAGE_ALERT').'</td></tr>';
                }
                $form .= '<tr><td  style="width:30px" valign="top">&nbsp;</td><td valign="top">';
                $form .= formToString($this->oForm, $form);
                $form .= '</td></tr></table>';
                /*                 * ****************************************** */
            }
            // Zend_Form stop

            $form .= "
					<script type=\"text/javascript\">
						$('textarea[name=REWRITE_REDIRECT_URL]').focusout(function(){
							callAjax({
								type: \"POST\",
								data: {urls : $(this).val(), id : '".$this->values["PAGE_ID"]."', field : 'PAGE_CLEAR_URL', langue : '".$this->values["LANGUE_ID"]."', isFromRubrique : false},
								url: '/_/Ndp_Administration_Url/ajaxVerifUrl'
							});
						});
						$('#PAGE_CLEAR_URL').focusout(function(){
							callAjax({
								type: \"POST\",
								data: {urls : $(this).val(), id : '".$this->values["PAGE_ID"]."', field : 'PAGE_CLEAR_URL', langue : '".$this->values["LANGUE_ID"]."', isFromRubrique : true},
								url: '/_/Ndp_Administration_Url/ajaxVerifUrl'
							});
						});
					</script>
					";
            if (!empty($this->values["TEMPLATE_PAGE_ID"]) && $this->values["TEMPLATE_PAGE_ID"] != Pelican::$config['TEMPLATE_PAGE']['GLOBAL']) {

                $statePageglobal = Pelican_Cache::fetch("Backend/State/Page", array(
                        $_SESSION[APP]['SITE_ID'],
                        $_SESSION[APP]['LANGUE_ID'],
                        Pelican::$config['TEMPLATE_PAGE']['GLOBAL'],
                        'CURRENT'
                ));


                if ($statePageglobal != Pelican::$config["PUBLISH_STATE"]) {

                    $this->aButton["state_1"] = true;
                    $this->aButton["state_2"] = true;
                    $this->aButton["state_3"] = true;
                    $this->aButton["state_4"] = true;
                    $this->aButton["state_5"] = true;
                    Backoffice_Button_Helper::init($this->aButton, true);
                    $form = '<div style="background-color:red;color:white;text-align:center;font-size:25px;"><br /><br />'.t('NDP_GLOBAL_PAGE_NOT_PUBLISHED').'<br /><br /><br /></div>';
                }
            }

            if ($this->values["PAGE_ID"] > 1) {

                $form .= "<script>

                            var page_id = ".$this->values["PAGE_ID"].";
                            var state_id = ".$this->values["STATE_ID"].";
                            var page_status = ".$this->values["PAGE_STATUS"].";
                            var publishFlag = ".$this->datePublicationFlag().";
                            var my_elem = $('#node_' + page_id, window.parent.document.body);

                            // si la page n'est pas publié l'icone reste tel qu'il était avant
                            //par contre si publié il faut changer la couleur en fonction de la date de publication
                            if(state_id == ". Pelican::$config["PUBLISH_STATE"].") {
                                var nodeClass ='green';
                                // avant date publication
                                if(publishFlag == -1) {
                                    nodeClass ='green_oh';
                                }
                                //apres date publication
                                if(publishFlag == 1) {
                                    nodeClass ='orange_oh';
                                }
                                my_elem.removeClass('red').removeClass('orange_oh').removeClass('grey').removeClass('green_oh').removeClass('green').addClass(nodeClass);
                            }

                            // si la page est hors ligne l'icon est forcément rouge
                            if (page_status == 0) {
                                my_elem.removeClass('grey').removeClass('orange_oh').removeClass('green').removeClass('green_oh').addClass('red');
                            }
                          </script>";
            }

            $this->assign('zoneDynamiqueJs', $zoneDynamiqueJs, false);
            $this->assign('versionForm', $this->getVersioningForm(), false);

            $this->assign('tableZone', $tableZone, false);

            $this->assign('form', $form, false);
            $this->assign('openZone', $_GET["openZone"]);
            $this->assign('nbrbloc', $nbrbloc);
            $this->assign('height', ($_SESSION["screen_height"] - 450));
            $this->assign('clean_url', str_replace("&gid=".$_GET["gid"], "", $_SERVER["REQUEST_URI"]));
            $this->replaceTemplate('index', 'edit');
            $this->fetch();
        }
    }

    protected function getSeoTab() {
        // seo
        $form = $this->oForm->beginTab("2");

        if (!$this->generalPage) {
            $form .= $this->oForm->createTextArea("PAGE_META_TITLE", t('Meta title'), false, $this->values["PAGE_META_TITLE"], 255, $this->readO, 2, 100, false, "", false);
            $form .= $this->oForm->createTextArea("PAGE_META_DESC", t('Meta description'), true, $this->values["PAGE_META_DESC"], 300, $this->readO, 5, 100, false, "", true);
        } else {
            $this->bRewrite = false;
            $this->cybertag = array();
        }

        return $form;
    }
    protected function getPublicationTab()
    {

        $form = $this->oForm->beginTab("3");
        $form .= $this->oForm->createCheckBoxFromList("PAGE_PROTOCOLE_HTTPS", t('Protocole'), array("1" => ""), $this->values ["PAGE_PROTOCOLE_HTTPS"], false, $this->readO, "h");
        $form .= $this->oForm->showSeparator();
        $form .= '<tr><td class="formlib">'.t('Display date begin').'</td><td class="formval">';
        $form .= $this->oForm->createInput("PAGE_START_DATE", t('Display date begin'), 10, "date", false, trim(preg_replace('/[0-9]{2}:[0-9]{2}/', '', $this->values ["PAGE_START_DATE"])), $this->readO, 10, true);
        $form .= $this->oForm->createInput("PAGE_START_DATE_HEURE", t('Display date begin'), 10, "heure", false, trim(preg_replace('/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/', '', $this->values ["PAGE_START_DATE"])), $this->readO, 10, true);
        $form .= '</td>';
        $form .= '<tr><td class="formlib">'.t('Display end date').'</td><td class="formval">';
        $form .= $this->oForm->createInput("PAGE_END_DATE", t('Display end date'), 10, "date", false, trim(preg_replace('/[0-9]{2}:[0-9]{2}/', '', $this->values ["PAGE_END_DATE"])), $this->readO, 10, true);
        $form .= $this->oForm->createInput("PAGE_END_DATE_HEURE", t('Display end date'), 10, "heure", false, trim(preg_replace('/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/', '', $this->values ["PAGE_END_DATE"])), $this->readO, 10, true);
        $form .= '</td>';

        return$form;
    }

    protected function getTaggageTab()
    {
        $form = $this->oForm->beginTab('4');
        $lang = Pelican_Cache::fetch("Language", [$this->values['LANGUE_ID']]);
        $this->values['LANGUE_CODE'] = $lang['LANGUE_CODE'];

        $form .= $this->oForm->createLabel('', t('NDP_TAGGAGE_DESCRIPTION'));

        $helper = new DatalayerFormHelper();

        $helper->setForm($this->oForm)->setContainer($this->getContainer())->setValues($this->values);
        $form .= $helper->getFormView();

        return $form;
    }

    protected function limitLength($string, $maxLength, $comp = '...') {
        $words = explode(' ', $string);
        $return = array_shift($words);

        if (!empty($words)) {
            $word = array_shift($words);
            while ((strlen($return.' '.$word) < $maxLength) && $word !== false ) {
                $return .= ' '.$word;
                $word = array_shift($words);
            }
            if (!empty($words)) {
                $return .= $comp;
            }
        }

        return ucfirst(mb_strtolower(html_entity_decode($return)));
    }

    /**
     *  -1 before publication
     *   0 publish
     *   1 after publication
     *
     * @return int
     */
    protected function datePublicationFlag()
    {
        $return = 0;
        $now = new DateTime("now");
        if (!empty($this->values['PAGE_START_DATE'])) {
            $start_published = DateTime::createFromFormat('d/m/Y H:i', $this->values['PAGE_START_DATE']);
            $return = -(int) ($start_published > $now);
        }
        if (!empty($this->values['PAGE_END_DATE']) &&  $return === 0) {
            $end_published = DateTime::createFromFormat('d/m/Y H:i', $this->values['PAGE_END_DATE']);
            $return = (int) ($end_published < $now);
        }

        return $return;
    }
    protected function pageIsPublished()
    {
        $return = ! (bool) $this->datePublicationFlag() ;

        return $return && ($this->values['PAGE_STATUS'] == 1) && ($this->values['STATE_ID'] == Pelican::$config["PUBLISH_STATE"]);
    }

    /**
     *
     * @param integer $parentId
     *
     * @return boolean
     */
    protected function isChildOfMasterPage($parentId)
    {
        $isChildOfMasterPage = false;
        $connection = Pelican_Db::getInstance();
        $bind = [':TPID' => Pelican::$config['TEMPLATE_PAGE']['NDP_MASTER_PAGE'], ':PAGE_ID' => $parentId];
        $sql = "SELECT pv.TEMPLATE_PAGE_ID from #pref#_page_version pv, #pref#_page p
                WHERE pv.PAGE_ID = p.PAGE_ID
                AND p.PAGE_ID = :PAGE_ID
                AND pv.TEMPLATE_PAGE_ID = :TPID
                AND (pv.PAGE_VERSION = p.PAGE_CURRENT_VERSION OR pv.PAGE_VERSION = p.PAGE_DRAFT_VERSION)
            ";
        $result = $connection->queryTab($sql, $bind);
        if (count($result) > 0) {
            $isChildOfMasterPage = true;
        }

        return $isChildOfMasterPage;
    }

    public function getMultiZones($data, &$currentArea, &$form, &$js)
    {
        $oConnection = Pelican_Db::getInstance();
        $cptMultiZone = -1;

        if ($currentArea != $data["AREA_ID"]) {
            if ($currentArea == '') {
                $form .= $this->oForm
                    ->createFreeHtml('<tbody><tr><td colspan="2">');
            } else {
                $form .= $this->oForm
                    ->createFreeHtml('</table>');
                $form .= $this->oForm
                    ->createFreeHtml('</fieldset>');
            }

            $currentArea = $data["AREA_ID"];

            if ($_SESSION[APP]['SITE_ID'] > Pelican::$config["ADM_MINISITE_ID"]) {
                $areaLabelTab = explode(" - ", $data['AREA_LABEL']);
                $nameZone = $areaLabelTab[1];
            } else {
                $nameZone = 'Zone '.$data['TEMPLATE_PAGE_AREA_ORDER'];
            }

            $form .= $this->oForm
                ->createHidden('db_pageMulti[]', $data['AREA_ID']);

            $form .= $this->oForm
                ->createFreeHtml('<br/><fieldset class="page dynamique">'.Pelican_Html::legend(t($nameZone)));
            $form .= $this->oForm
                ->createFreeHtml('
                    <div class="displayZoneDispo">
                        <div></div>
                        <img id="button_toggle_'.$data['AREA_ID'].'" width="17" height="17" border="0" style="" alt="Masquer" src="/library/public/images/icon-toggle-open.png" />
                    </div>');
            $form .= $this->oForm
                ->createFreeHtml('<div id="td_ZONE_DYNAMIQUE_'.$data['AREA_ID'].'" class="zoneDynamique">');

            $sSql = '
                SELECT pmz.*,
                    z.ZONE_BO_PATH,
                    z.ZONE_LABEL as LABEL_ZONE,
                    z.ZONE_TYPE_ID,
                    z.PLUGIN_ID
                FROM #pref#_page_multi_zone pmz
                    INNER JOIN #pref#_zone z
                        ON (pmz.ZONE_ID = z.ZONE_ID)
                WHERE PAGE_ID = :PAGE_ID
                    AND PAGE_VERSION = :PAGE_VERSION
                    AND LANGUE_ID = :LANGUE_ID
                    AND AREA_ID = :AREA_ID
                ORDER BY ZONE_ORDER ASC';

            $this->aBind[':AREA_ID'] = $data['AREA_ID'];
            $aMultiZones = $oConnection->queryTab($sSql, $this->aBind);

            if (!empty($aMultiZones)) {
                foreach ($aMultiZones as $zone) {
                    $cptMultiZone++;
                    $zone['ZONE_DYNAMIQUE'] = 1;
                    $this->getMultiZone($form, $zone, $cptMultiZone, $data['AREA_ID']);
                }
            }

            $form .= $this->oForm
                ->createHidden('count_pageMulti'.$data['AREA_ID'], $cptMultiZone);

            $form .= $this->oForm
                ->createFreeHtml('</div>');
            $form .= $this->oForm
                ->createFreeHtml('<table id="slide_'.$data['AREA_ID'].'" class="slide">');

            $this->oForm->_aIncludes["multi"] = true;
            // il faut inclure tous les js pour les contrôles de saisie des champs ajoutés à la volée (on ne peut pas savoir ce dont on va avoir besoin à l'avance)
            $this->oForm->_aIncludes["num"] = true;
            $this->oForm->_aIncludes["text"] = true;
            $this->oForm->_aIncludes["date"] = true;
            $this->oForm->_aIncludes["list"] = true;
            $this->oForm->_aIncludes["popup"] = true;
            $this->oForm->_aIncludes["crosstab"] = true;
            //$this->oForm->_bUseMulti = true;

            $js .= '
                var nbZone'.$data['AREA_ID'].' = '.$cptMultiZone.';
                var duration = 1000;
                var animationType = "blind";
                var slideMenuSize = "210px";

                $(function() {
                   var $zd= $("#td_ZONE_DYNAMIQUE_'.$data['AREA_ID'].'")
                   $("#slide_'.$data['AREA_ID'].'").after($zd);
                   var $container = $zd.parent();
            ';

            //if user profil is translator so disable order & drag&drop of tranches
            if (!Cms_Page_Ndp::isTranslator()){
                $js .= '
                $("#slide_'.$data['AREA_ID'].' div").draggable({
                         cursor: "pointer",
                         connectWith: "#td_ZONE_DYNAMIQUE_'.$data['AREA_ID'].'",
                         helper: "clone",
                         opacity: 0.5,
                         zIndex: 10,
                         connectToSortable : "#td_ZONE_DYNAMIQUE_'.$data['AREA_ID'].'"
                    });
                    var updateContent = function (e, ui) {
                        $("td.index", ui.item.parent()).each(function (i) {
                            $(this).html(i + 1);
                        });
                        ui.item.find("iframe").each(function(index) {
                            $(this).contents().find("head").html("<link rel=\'stylesheet\' type=\'text/css\' href=\'/css/editorPeugeot.css\' />");
                            $(this).contents().find("body").html(document.getElementById($(this).attr("id").replace(/\iframeText(.+?)/, "$1")).value);
                        })
                    };
                    $zd.sortable({
                        stop: updateContent,
                        connectWith: "#td_ZONE_DYNAMIQUE_'.$data['AREA_ID'].'",
                        placeholder: "sortable-placeholder",
                        cursor: "pointer",
                         update: function( event, ui ) {
                             zoneCit.refreshZone();
                         }
                    }).droppable({

                        accept: ".btnAddZone",
                        drop: function(event, ui) {
                            nbZone'.$data['AREA_ID'].'++;
                            ui.draggable
                                .attr(\'id\', \'zone_multi_\' + nbZone'.$data['AREA_ID'].')
                                .removeAttr(\'class\')
                                .css(\'display\', \'block\')
                                .html(\'<img src="/library/public/images/ajax/ajax-loader.gif" class="ajaxZoneMulti" />\');
                            callAjax({
                                type: "POST",
                                data: {area_id: \''.$data['AREA_ID'].'\',zone_id : ui.draggable.attr(\'title\').replace(\'zone_multi_\', \'\'),cpt: nbZone'.$data['AREA_ID'].'},
                                url: \'/_/Cms_Page_Dynamique/ajaxAddZone\',
                                success: function(data) {
                                    $(".ajaxZoneMulti").hide();
                                    zoneCit.initNewZone ();
                                   zoneCit.refreshZone();
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    var complement = "";
                                    if (xhr.statusText == "timeout") {
                                        complement = ": timeout";
                                    } else if (xhr.responseText != "") {
                                        if(xhr.responseText.match("page=_/Index/login")){
                                            complement = ": '.t('NOT_CONNECTED').'";
                                        } else {
                                            complement = xhr.responseText;
                                        }
                                    }

                                    alert("'.t('ERROR_AJAX').'" + complement);
                                    $(".ajaxZoneMulti").hide();
                                    var count = eval($("#count_pageMulti'.$data['AREA_ID'].'").val() || 0);
                                    $("#count_pageMulti'.$data['AREA_ID'].'").val( count + 1);
                                    ui.draggable.remove();
                                }
                            });

                            ui.draggable.removeAttr(\'title\');
                        }
                    });
                ';
            }

            $js .= '
                    $("#slide_'.$data['AREA_ID'].'").toggle();
                    $("#button_toggle_'.$data['AREA_ID'].'").click(function(){
                        if ( $("#slide_'.$data['AREA_ID'].'").is(":visible") ) {

                            $("#button_toggle_'.$data['AREA_ID'].'").attr("src","/library/public/images/icon-toggle-open.png");
                            $zd.animate({width: \'+=\'+slideMenuSize},duration);
                            $("#slide_'.$data['AREA_ID'].'").hide(animationType, { direction: "right" }, duration);

                        }
                        else {
                            $("#button_toggle_'.$data['AREA_ID'].'").attr("src","/library/public/images/icon-toggle-close.png");
                            $zd.animate({width: \'-=\'+slideMenuSize},duration);
                            $("#slide_'.$data['AREA_ID'].'").show(animationType, { direction: "right" }, duration);
                            $zd.css("min-height", $container.height());
                        }
                    });
                });
            ';
            $this->oForm->createJS("
                var onglet = 0;
                var nbTranche = 0;
                var cptOngletWeb = 0;
                var cptOngletMobile = 0;
                var validOnglet = true;


                $('#td_ZONE_DYNAMIQUE_".$data['AREA_ID']." .tdtogglezone').each(function() {
                    i = $(this).attr('id').replace('DivtogglezonezoneDynamique_', '');

                    if (document.getElementById('MULTI_ZONE_".$data['AREA_ID']."_ID['+i+']') && document.getElementById('MULTI_ZONE_".$data['AREA_ID']."_ID['+i+']').value == ".Pelican::$config['ZONE']["ONGLET"].") {
                        onglet++;
						var nbOnglet = document.getElementById('count_multiZone".$data['AREA_ID']."_'+i+'_ONGLET').value;

                        nbTranche = 0;
						for (var cpt=0;cpt<=nbOnglet;cpt++) {
							if ($('#multiZone".$data['AREA_ID']."_'+i+'_ONGLET'+cpt+'_PAGE_ZONE_MULTI_OPTION')){
                                nbTranche += eval($('#multiZone".$data['AREA_ID']."_'+i+'_ONGLET'+cpt+'_PAGE_ZONE_MULTI_OPTION').val() || 0);
							}
						}

                        if (onglet > 1 && (cptOngletMobile > 0 || cptOngletWeb > 0)) {
                            alert('".t('ONGLET_ON_ONGLET', 'js')."');
                            return validOnglet = false;
                        }

                        if ($('input[name=multiZone".$data['AREA_ID']."_'+i+'_ZONE_MOBILE]').is(':checked')) {
                            cptOngletMobile = nbTranche;
                        }
                        if ($('input[name=multiZone".$data['AREA_ID']."_'+i+'_ZONE_WEB]').is(':checked')) {
                            cptOngletWeb = nbTranche;
                        }

                    } else {
                        if ($('input[name=multiZone".$data['AREA_ID']."_'+i+'_ZONE_MOBILE]').is(':checked')) {
                            cptOngletMobile--;
                        }
                        if ($('input[name=multiZone".$data['AREA_ID']."_'+i+'_ZONE_WEB]').is(':checked')) {
                            cptOngletWeb--;
                        }
                    }

                });
                if (!validOnglet) return false;

            ");

            $this->oForm->createJS("
                var accordeon = 0;          // Compte le nombre de blocs accordéon dans la zone dynamique
                var nbTranche = 0;          // Compte le nombre de tranches absorbées par la tranche accordéon
                var cptAccordeonWeb = 0;    // Compteur de recouvrement de tranche affichée en web
                var cptAccordeonMobile = 0; // Compteur de recouvrement de tranche affichée en mobile
                var validAccordeon = true;  // Flag indiquant si la configuration des accordéons est cohérente ou pas

                // Pour tous les blocs de la zone dynamique
                $('#td_ZONE_DYNAMIQUE_".$data['AREA_ID']." .tdtogglezone').each(function() {
                    i = $(this).attr('id').replace('DivtogglezonezoneDynamique_', '');

                    // Si le bloc est de type accordéon => traitement du bloc onglet
                    if (document.getElementById('MULTI_ZONE_".$data['AREA_ID']."_ID['+i+']') && document.getElementById('MULTI_ZONE_".$data['AREA_ID']."_ID['+i+']').value == ".Pelican::$config['ZONE']['ACCCORDEON'].") {
                        accordeon++;

                        // Récupération du nombre de toggle paramétré dans le bloc accordéon courant (nombre de multi)
                        // ATTENTION : nbAccordeon = <nombre de toggle> - 1
                        var nbAccordeon = document.getElementById('count_multiZone".$data['AREA_ID']."_'+i+'_ADDTOGGLE').value;

                        // Parcours du multi du bloc onglet, en additionnant les champs nombre de tranches
                        // pour obtenir le nombre total de tranches inférieures \"absorbées\" par le bloc onglet courant
                        nbTranche = 0;
                        for (var cpt=0;cpt<=nbAccordeon;cpt++) {
                            try{
                                nbTranche += parseInt($('#multiZone".$data['AREA_ID']."_'+i+'_ADDTOGGLE'+cpt+'_PAGE_ZONE_MULTI_MODE').val()) || 0;
                            } catch(err){}
                        }

                        // Condition de validation
                        if (accordeon > 1 && (cptAccordeonMobile > 0 || cptAccordeonWeb > 0)) {
                            alert('".t('ACCORDEON_ON_ACCORDEON', 'js')."');
                            return validAccordeon = false;
                        }

                        // Initialisation des compteurs de tranches à recouvrir
                        if ($('input[name=multiZone".$data['AREA_ID']."_'+i+'_ZONE_MOBILE]').is(':checked')) {
                            cptAccordeonMobile = nbTranche;
                        }
                        if ($('input[name=multiZone".$data['AREA_ID']."_'+i+'_ZONE_WEB]').is(':checked')) {
                            cptAccordeonWeb = nbTranche;
                        }

                    }

                    // Sinon (le bloc n'est pas de type accordéon), on décrémente les compteurs de recouvrement
                    else {
                        if ($('input[name=multiZone".$data['AREA_ID']."_'+i+'_ZONE_MOBILE]').is(':checked')) {
                            cptAccordeonMobile--;
                        }
                        if ($('input[name=multiZone".$data['AREA_ID']."_'+i+'_ZONE_WEB]').is(':checked')) {
                            cptAccordeonWeb--;
                        }
                    }

                });
                if (!validAccordeon) return false;
            ");

        }

        $label = t($data['ZONE_TEMPLATE_LABEL']);

        $form .= $this->oForm
            ->createFreeHtml('<tr><td><div title="zone_multi_'.$data['ZONE_ID'].'" class="btnAddZone draggable"   data-area_id="'.$data['AREA_ID'].'" data-zone_id="'.$data['ZONE_ID'].'" >'.$label.'</div></td></tr>');


    }

    public function getMultiZone(&$form, $data, $cpt, $area_id, $ajax = false)
    {
        $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];

        /*
         * * sélection du template de saisie et du template de traitement
         */
        $root = ($data["PLUGIN_ID"] ? Pelican::$config["PLUGIN_ROOT"].'/'.$data["PLUGIN_ID"].'/backend/controllers' : Pelican::$config['APPLICATION_CONTROLLERS']);
        $module = $root.'/'.str_replace("_", "/", $data["ZONE_BO_PATH"]).".php";
        $moduleClass = $data["ZONE_BO_PATH"];

        /*
         * * Etat ouvert ou non : initialisé par les cookies
         */
        $setCookie = false;
        if ($data["ZONE_TEMPLATE_ID"] == 1) {
            $setCookie = false;
        } elseif (isset($_COOKIE["togglezone".$data["ZONE_TEMPLATE_ID"]])) {
            $setCookie = true;
            if ($_COOKIE["togglezone".$data["ZONE_TEMPLATE_ID"]] != "false") {
                $closed = true;
            }
        }

        $this->multi = "multiZone".$area_id."_".$cpt."_";
        $persoZone = null;

        if (!$ajax) {
            $form .= '<div data-area_id="'.$area_id.'" data-zone_id="'.$data['ZONE_ID'].'"  data-uniq-uid="'.$data["UID"].'" >';
        }
        $form .= $this->oForm->beginFormTable();
        $label = t($data["LABEL_ZONE"]);
        if (!empty($data['ZONE_TITRE'])) {
            $label .= ' - <b>'.$this->limitLength($data['ZONE_TITRE'], 50).'</b>';
        }
        $form .= $this->oForm
            ->createFreeHtml(
            $this->_beginZone(
                'zoneDynamique_'.$cpt, $label . "<!-- zoneDynamique_".$cpt."-->", 1, $data["ZONE_TYPE_ID"], true, $setCookie, true, "", $data["ZONE_PROGRAM"], false, true
            )
        );

        if (!$moduleClass) {
            $moduleClass = 'Cms_Page_Module';
        }

        $form .= $this->oForm->createHidden("MULTI_ZONE_".$area_id."_DB[".$cpt."]", $moduleClass, true);
        $form .= $this->oForm->createHidden("MULTI_ZONE_".$area_id."_ID[".$cpt."]", $data["ZONE_ID"], true);
        if (empty($data["UID"])) {
            $data["UID"] = uniqid();
        }
        $form .= $this->oForm->createHidden("MULTI_ZONE_".$area_id."_UID[".$cpt."]", $data["UID"], true);
        $form .= $this->oForm->createHidden("MULTI_ZONE_".$area_id."_".$data["UID"]."_DISPLAY_ON_FO", $data["DISPLAY_ON_FO"], true);
        $form .= $this->oForm
            ->beginFormTable("0", "0", "form", true, "tabletogglezone".$cpt);
        $form .= $this->oForm->createHidden($this->multi."multi_display", "1");

        if ($data["ZONE_TYPE_ID"] == 2) {
            $form .= $this->oForm
                ->createFreeHtml("<span class=\"\">".t('ZONE_AUTOMATIQUE')."</span>");
            $form .= $this->oForm->createHidden($this->multi."ZONE_TEXTE", 1, true);
        } elseif (!file_exists($module)) {
            $form .= $this->oForm
                ->createFreeHtml("<span class=\"erreur\">".$module." => ".t('A_FAIRE')."</span>");
        } else {
            include_once $module;
            $this->oForm->_sJS .= "if (document.getElementById('".$this->multi."multi_display')) {\n if (document.getElementById('".$this->multi."multi_display').value) {\n";

            $this->zoneValues = $data;
            $tmpPerso = '';

            /*
             * ID HTML
             */
            $idHTML = '#'.Pelican_Text::cleanText($this->zoneValues['LABEL_ZONE'], '-', false, false).'_'.$this->zoneValues['AREA_ID'].'_'.$this->zoneValues['ZONE_ORDER'];
            $tmpPerso .= $this->oForm->createLabel(t('ID_HTML'), $idHTML);


            $tmp = call_user_func_array(array(
                $moduleClass,
                'render',
                ), array(
                $this,
            ));

            $this->oForm->_sJS .= "}\n}\n";

            // si la verif est activer (CheckBox mobile/ web)
            // helper backend getFormAffichage
            if ($this->zoneValues['VERIF_JS'] == 1) {
                $this->oForm->_sJS .= "}\n";
            }

            //Prise en compte de la class zend_form
            if ($this->generalPage) {
                $form .= $this->oForm
                    ->createFreeHtml($tmpPerso.$tmp);
            } else {
                $form .= $tmpPerso.$tmp;
            }
        }
        $form .= $this->oForm
            ->endFormTable();

        $form .= $this->oForm
            ->createFreeHtml($this->_endZone());
        $form .= $this->oForm->endFormTable();
        if (!$ajax) {
            $form .= '</div>';
        }
    }

    public static function multiPush($oForm, $values, $readO, $multi)
    {
        $return = $oForm->createInput($multi."PAGE_MULTI_LABEL", t('LIBELLE'), 40, "", true, $values['PAGE_MULTI_LABEL'], $readO, 40);
        $return .= $oForm->createMedia($multi."MEDIA_ID", t('IMAGE'), false, "image", "", $values['MEDIA_ID'], $readO, true, false, '16_9', null, $values['MEDIA_ID_GENERIQUE']);
        $return .= $oForm->createInput($multi."PAGE_MULTI_URL", t('URL'), 255, "internallink", true, $values['PAGE_MULTI_URL'], $readO, 75);
        $return .= $oForm->createRadioFromList($multi."PAGE_MULTI_OPTION", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $values['PAGE_MULTI_OPTION'], true, $readO);

        return $return;
    }

    public static function multiCTA($oForm, $values, $readO, $multi)
    {
        $return = $oForm->createInput($multi."PAGE_MULTI_LABEL", t('LIBELLE'), 255, "", true, $values['PAGE_MULTI_LABEL'], $readO, 75);
        $return .= $oForm->createInput($multi."PAGE_MULTI_URL", t('URL'), 255, "internallink", true, $values['PAGE_MULTI_URL'], $readO, 75);
        $return .= $oForm->createRadioFromList($multi."PAGE_MULTI_OPTION", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $values['PAGE_MULTI_OPTION'], true, $readO);

        return $return;
    }

    /**
     * @deprecated
     */
    public function moveAction()
    {
        $oConnection = Pelican_Db::getInstance();

        Pelican_Db::$values["id"] = $this->getParam(0);
        Pelican_Db::$values["direction"] = $this->getParam(1);

        $change = $oConnection->updateOrder("#pref#_page", "PAGE_ORDER", "PAGE_ID", Pelican_Db::$values["id"], "", Pelican_Db::$values["direction"], "PAGE_PARENT_ID", false, "SITE_ID=".$_SESSION[APP]['SITE_ID']." AND LANGUE_ID=".$_SESSION[APP]['LANGUE_ID']);
        if ($change) {
            $_SESSION["MOVE"]["id"] = Pelican_Db::$values["id"];
            Pelican_Cache::clean("Frontend/Site/Tree", $_SESSION[APP]['SITE_ID']);
            Pelican_Cache::clean("Backend/Page", $_SESSION[APP]['SITE_ID']);
            $this->getRequest()
                ->addResponseCommand('script', array(
                    'value' => 'top.location.href=top.location.href;',
            ));
        }
    }

    public function movePageAction()
    {
        $aParams = $this->getParams();
        if ($aParams['dragged'] && $aParams['target'] && isset($aParams['order'])) {

            //parent different: on lance la machine
            if ($aParams['dragged']['pid'] != $aParams['target']['id']) {
                $aPagesByLang = $this->getPages($aParams['dragged']['id'], $aParams['target']['id'], $aParams['order']);
                $this->updateChildren($aParams['target']['id'], $aParams['target']['path'], $aParams['dragged']['id'], $aParams['order'], $aPagesByLang);
            }
            if ($aParams['order'] != $aParams['dragged']['order']) {
                $oConnection = Pelican_Db::getInstance();
                $iParentId = $aParams['target']['id'];

                $this->ordonnancementPagesByPageParent($iParentId);

                $aBind = array(
                    ':PAGE_ID' => $aParams['dragged']['id'],
                    ':PAGE_ORDER' => $aParams['order'],
                    ':PAGE_PARENT_ID' => $iParentId,
                );

                $sReorderNodesSql = 'UPDATE #pref#_page p
                                     LEFT JOIN #pref#_page_version pv
                                     ON (
                                        p.PAGE_ID = pv.PAGE_ID
                                        AND p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION

                                        )
                                     SET p.PAGE_ORDER = PAGE_ORDER+1
                                     WHERE p.PAGE_ORDER >=:PAGE_ORDER
                                     AND p.PAGE_PARENT_ID = :PAGE_PARENT_ID
                                     AND (pv.STATE_ID <> 5)';

                //mets à jour le nouvel ordre de la page deplacée
                $sUpdatePageOrder = 'UPDATE #pref#_page set PAGE_ORDER = :PAGE_ORDER WHERE PAGE_ID= :PAGE_ID';

                $oConnection->query($sReorderNodesSql, $aBind);
                $oConnection->query($sUpdatePageOrder, $aBind);
            }

            //décacher les pages en front pour que la nouvelle organisation soit prise en compte
            Pelican_Db::$values['DRAG_N_DROP'] = true;
            $_REQUEST["PAGE_ID"] = $aParams['dragged']['id'];
            $_REQUEST["PAGE_PARENT_ID"] = $aParams['target']['id'];
            $_REQUEST['SITE_ID'] = $_SESSION[APP]['SITE_ID'];

            $this->execDecache();
        }
    }

    //réorganisation des pages pour éliminer toute possibilité de gap
    protected function orderRootPage()
    {
        $connection = Pelican_Db::getInstance();
        $bind = array(
            ':SITE_ID' => $_SESSION[APP]['SITE_ID'],
        );
        $connection->query('UPDATE #pref#_page set PAGE_ORDER = 0 WHERE SITE_ID=:SITE_ID AND PAGE_GENERAL=1 ',$bind);
        $sql = "SELECT p.PAGE_ID,p.PAGE_ORDER FROM #pref#_page p
                            LEFT JOIN #pref#_page_version pv
                             ON (
                                p.PAGE_ID = pv.PAGE_ID
                                AND p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION

                                )
                            WHERE PAGE_PARENT_ID IS NULL
                            AND p.SITE_ID=:SITE_ID
                            AND (pv.STATE_ID <> 5)
                            AND p.PAGE_GENERAL =0
                            GROUP BY PAGE_ID
                            ORDER BY p.PAGE_ORDER ASC
                            ";
        $pages = $connection->queryTab($sql, $bind);

        if (count($pages)) {
            $this->updateOrderPages($pages, 1);
        }

    }

    protected function updateOrderPages($pages, $start = 0) {
        $connection = Pelican_Db::getInstance();
        $pagesReordered = array();

        foreach ($pages as  $aPage) {
            $pagesReordered[] = array(
                'PAGE_ID' => $aPage['PAGE_ID'],
                'PAGE_ORDER' => $start++,
            );
        }

        foreach ($pagesReordered as $pageReordered) {
            $bind = array(
                ':PAGE_ID' => $pageReordered['PAGE_ID'],
                ':SITE_ID' => $_SESSION[APP]['SITE_ID'],
                ':NEW_ORDER' => $pageReordered['PAGE_ORDER'],
            );
            $sql = 'UPDATE #pref#_page set PAGE_ORDER = :NEW_ORDER WHERE  PAGE_ID = :PAGE_ID AND  SITE_ID=:SITE_ID ';
            $connection->query($sql, $bind);
        }
    }

    //réorganisation des pages pour éliminer toute possibilité de gap
    protected function orderChildPage($parentId)
    {
        $connection = Pelican_Db::getInstance();
        $bind = array(
            ':PAGE_PARENT_ID' => $parentId,
            ':SITE_ID' => $_SESSION[APP]['SITE_ID'],
        );
        $sql = "SELECT p.PAGE_ID,p.PAGE_ORDER FROM #pref#_page p
                            LEFT JOIN #pref#_page_version pv
                             ON (
                                p.PAGE_ID = pv.PAGE_ID
                                AND p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION

                                )
                            WHERE PAGE_PARENT_ID = :PAGE_PARENT_ID
                            AND p.SITE_ID=:SITE_ID
                            AND (pv.STATE_ID <> 5)
                            GROUP BY PAGE_ID
                            ORDER BY p.PAGE_ORDER
                            ";
        $pages = $connection->queryTab($sql, $bind);

        if (count($pages)) {
            $this->updateOrderPages($pages);
        }
    }

    public function ordonnancementPagesByPageParent($parentId = 0)
    {
        if (empty($parentId)) {
            $this->orderRootPage();
        } else {
            $this->orderChildPage($parentId);
        }
    }

    public function updateChildren($iParentId, $sParentPath, $iPageId, $iOrder = null, $aPagesByLang = null)
    {

        //start get pages
        $oConnection = Pelican_Db::getInstance();
        $sFindPagesSql = "SELECT
									 DISTINCT p.LANGUE_ID,
									  p.PAGE_ID,
									  p.PAGE_PATH,
									  p.PAGE_LIBPATH,
									  p.LANGUE_ID,
									  pv.PAGE_TITLE_BO
			 FROM #pref#_page p INNER JOIN #pref#_page_version pv
			 ON (p.PAGE_ID = pv.PAGE_ID AND p.LANGUE_ID=pv.LANGUE_ID AND p.page_draft_version = pv.page_version )
			 WHERE p.PAGE_ID = :PAGE_ID
			 GROUP BY p.langue_id
			 ";

        $aBind = array(
            ':PAGE_ID' => $iPageId,
            ':PARENT_ID' => $iParentId,
            //':ORDER'=>$iOrder
        );

        //fetch pages

        $aPages = $oConnection->queryTab($sFindPagesSql, $aBind);

        $aPagesByLang = array();
        //create parents in available languages.
        foreach ($aPages as $page) {
            $aPagesByLang[$page['LANGUE_ID']] = $page;
            $this->addCmsPageParentByLanguage($iParentId, $page['LANGUE_ID']);
        }

        //end get pages

        $sFindPagesSql = "SELECT
									 DISTINCT p.LANGUE_ID,
									  p.PAGE_ID,
									  p.PAGE_PATH,
									  p.PAGE_LIBPATH,
									  p.LANGUE_ID,
									  pv.PAGE_TITLE_BO
			 FROM #pref#_page p INNER JOIN #pref#_page_version pv
			 ON (p.PAGE_ID = pv.PAGE_ID AND p.LANGUE_ID=pv.LANGUE_ID AND p.page_draft_version = pv.page_version )
			 WHERE p.PAGE_ID = :PAGE_ID
			 GROUP BY p.langue_id
			 ";

        //find direct parents
        $aBind[':PAGE_ID'] = $iParentId;
        $aParents = $oConnection->queryTab($sFindPagesSql, $aBind);
        //Update des donnees de la page
        //on prend le premier page path vu que c'est des valeurs numeriques
        $sNewPagePath = sprintf('%s#%s', $aParents[0]['PAGE_PATH'], $iPageId);
        //generate sql queries to updates nodes

        foreach ($aParents as $parent) {
            if (isset($aPagesByLang[$parent['LANGUE_ID']])) {
                $sNewPagePathLib = sprintf(
                    '%s#%s|%s', $parent['PAGE_LIBPATH'], $iPageId, trim(
                        $aPagesByLang[$parent['LANGUE_ID']]['PAGE_TITLE_BO']
                    )
                );

                $aBind = array(
                    ':PAGE_ID' => $iPageId, // dragged_id was here
                    ':PAGE_PARENT_ID' => intval($parent['PAGE_ID']),
                    ':PAGE_PATH' => $oConnection->strtobind($sNewPagePath),
                    ':PAGE_LIBPATH' => $oConnection->strtobind($sNewPagePathLib),
                    ':LANGUE_ID' => $parent['LANGUE_ID'],
                );

                $sUpdatePageSql = "UPDATE #pref#_page set PAGE_PARENT_ID=:PAGE_PARENT_ID,PAGE_PATH=:PAGE_PATH, PAGE_LIBPATH=:PAGE_LIBPATH where PAGE_ID=:PAGE_ID AND LANGUE_ID=:LANGUE_ID";
                $oConnection->query($sUpdatePageSql, $aBind);
                $aBind = array(
                    ':PAGE_PARENT_ID' => $iPageId,
                );
                $sGetChildrenSql = "SELECT p.PAGE_ID FROM #pref#_page as p WHERE p.PAGE_PARENT_ID=:PAGE_PARENT_ID GROUP BY PAGE_ID";
                $aChildren = $oConnection->queryTab($sGetChildrenSql, $aBind);

                if (count($aChildren)) {
                    //public function updateChildren($iParentId,$sParentPath,$iPageId,$aPagesByLang)
                    foreach ($aChildren as $aChildPage) {
                        $this->updateChildren($iPageId, $aChildPage['PAGE_PATH'], $aChildPage['PAGE_ID']);
                    }
                }
            }
            //getChildren of current node
        }
        $oConnection->commit();
    }

    protected function getPages($page_id, $parent_id, $order = null)
    {
        $oConnection = Pelican_Db::getInstance();
        $sFindPagesSql = "SELECT
									 DISTINCT p.LANGUE_ID,
									  p.PAGE_ID,
									  p.PAGE_PATH,
									  p.PAGE_LIBPATH,
									  p.LANGUE_ID,
									  pv.PAGE_TITLE_BO
			 FROM #pref#_page p INNER JOIN #pref#_page_version pv
			 ON (p.PAGE_ID = pv.PAGE_ID AND p.LANGUE_ID=pv.LANGUE_ID AND p.page_draft_version = pv.page_version )
			 WHERE p.PAGE_ID = :PAGE_ID
			 GROUP BY p.langue_id
			 ";

        $aBind = array(
            ':PAGE_ID' => $page_id,
            ':PARENT_ID' => $parent_id,
        );
        $aPages = $oConnection->queryTab($sFindPagesSql, $aBind);

        $aPagesByLang = array();
        //create parents in available languages.
        foreach ($aPages as $page) {
            $aPagesByLang[$page['LANGUE_ID']] = $page;
            $this->addCmsPageParentByLanguage($parent_id, $page['LANGUE_ID']);
        }

        return $aPagesByLang;
    }

    /**
     * test si un mediaId correspond a une image
     * @param $mediaId
     *
     * @return bool
     */
    protected function isMediaImage($mediaId)
    {
        $con = Pelican_Db::getInstance();

        $sql = 'SELECT MEDIA_TYPE_ID FROM #pref#_media WHERE MEDIA_ID =:MEDIA_ID';
        $type = $con->queryItem($sql, [':MEDIA_ID'=>$mediaId]);

        return $type == PsaMedia::IMAGE;
    }

    /**
     * test if string is key for a media id field
     * @param $name
     *
     * @return bool
     */
    protected function isMediaKey($name){

        return  (bool) preg_match('#MEDIA_ID[2-9]?#', $name);
    }

    /**
     * recupère les $limit medias dans le tableau de $values
     * .
     * @param $values
     * @param $limit
     *
     * @return array
     */
    protected function getMedias($values, $limit)
    {
        $medias = [];
        foreach ($values as $name=>$value)
        {
            if(is_array($value)) {
              $temp = $this->getMedias($value, $limit - count($medias));
              $medias = array_slice(array_merge($medias, $temp),0 , $limit);
            } else {
                if (is_numeric($value) && $this->isMediaKey($name) && $this->isMediaImage($value)) {
                    $medias[] = $value;
                }
            }

            if(count($medias)>= $limit ) {
                break;
            }
        }

        return $medias;
    }
    /**
     * sauvegarde des medias pour les partages sur les reseaux sociaux NDPA-759
     * on parcours tous les données post de la page et on enregistre le premiers MEDIA_ID
     *
     */
    protected function savePageMedia()
    {
        $medias = $this->getMedias(Pelican_Db::$values, 1);
        foreach($medias as $id=>$mediaId) {
            Pelican_Db::$values["MEDIA_ID_".($id+1)] = $mediaId;
        }
    }

    public function saveAction()
    {
        $goToNewPage = false;
        $this->savePageMedia();
        $oConnection = Pelican_Db::getInstance();
        $helper = new DatalayerFormHelper();
        $helper->saveDatalayer();


        $_SESSION[APP]['form_button'] = Pelican_Db::$values['form_button'];
        $setNewUrl = false;
        if(empty(Pelican_Db::$values['PAGE_LIBPATH'])) {
            $setNewUrl= true;
        }
        if (Pelican_Db::$values['STATE_ID'] == 4) {
            $this->aBind[':PAGE_ID'] = Pelican_Db::$values["PAGE_ID"];
            $diffusion = $oConnection->getRow("select PAGE_DIFFUSION from #pref#_page where PAGE_ID = :PAGE_ID", $this->aBind);
            if (true == $diffusion['PAGE_DIFFUSION']) {
                $this->sendMailDiffusion(Pelican_Db::$values['SITE_ID'], Pelican_Db::$values['PAGE_TITLE_BO']);
                Pelican_Db::$values["PAGE_DIFFUSION"] = false;
            }
        }

        if (Pelican_Db::$values["PAGE_ID"] == - 2) {
            $goToNewPage = true;
            /* cas de la création */
            $_SESSION[APP]['PAGE_RETURN'] = "<script type=\"text/javascript\">top.location.href=top.location.href;</script>";
        }

        if (strpos(Pelican_Db::$values["PAGE_PATH"], '#') == 0) {
            Pelican_Db::$values["PAGE_PATH"] = substr_replace(Pelican_Db::$values["PAGE_PATH"], '', 0, 1);
        }

        if (isset(Pelican_Db::$values["PAGE_CREATION_USER"]) && (strpos(Pelican_Db::$values["PAGE_CREATION_USER"], '#') === false)) {
            Pelican_Db::$values["PAGE_CREATION_USER"] = '#'.Pelican_Db::$values["PAGE_CREATION_USER"].'#';
        }

        if (Pelican_Db::$values["PAGE_TEXT"] && (!Pelican_Db::$values["PAGE_META_DESC"] || Pelican_Db::$values["PAGE_META_DESC"] == "")) {
            Pelican_Db::$values["PAGE_META_DESC"] = Pelican_Db::$values["PAGE_TEXT"];
        }

        if (Pelican_Db::$values["PAGE_TITLE"] && (!Pelican_Db::$values["PAGE_META_TITLE"] || Pelican_Db::$values["PAGE_META_TITLE"] == "")) {
            Pelican_Db::$values["PAGE_META_TITLE"] = Pelican_Db::$values["PAGE_TITLE"];
        }
        if (Pelican_Db::$values["PAGE_START_DATE"]) {
            Pelican_Db::$values["PAGE_START_DATE"] = Pelican_Db::$values["PAGE_START_DATE"]." ".Pelican_Db::$values["PAGE_START_DATE_HEURE"].":00";
        }
        if (Pelican_Db::$values["PAGE_END_DATE"]) {
            Pelican_Db::$values["PAGE_END_DATE"] = Pelican_Db::$values["PAGE_END_DATE"]." ".Pelican_Db::$values["PAGE_END_DATE_HEURE"].":00";
        }
        include_once 'Pelican/Taxonomy.php';
        $oTaxonomy = Pelican_Factory::getInstance('Taxonomy');
        $oTaxonomy->saveTermsRelationships(array(
            'TAXONOMY',
            'TAXONOMY2',
            ), Pelican_Db::$values["PAGE_ID"], 3);

        /* Page générale */
        $aPageType = Pelican_Cache::fetch("PageType/Template", array(
                Pelican_Db::$values['TEMPLATE_PAGE_ID'],
        ));
        Pelican_Db::$values['PAGE_GENERAL'] = ($aPageType["PAGE_TYPE_CODE"] == 'GENERAL' ? "1" : "0");

        // Suppression de l'apostrophe de Word
        Pelican_Db::$values['PAGE_TITLE'] = htmlspecialchars(Pelican_Db::$values['PAGE_TITLE']);
        Pelican_Db::$values['PAGE_TITLE_BO'] = htmlspecialchars(Pelican_Db::$values['PAGE_TITLE_BO']);


        /* clean associated contents */
        if (Pelican_Db::$values["PAGE_VERSION"]) {
            $this->aBind[":PAGE_ID"] = Pelican_Db::$values["PAGE_ID"];
            $this->aBind[":PAGE_VERSION"] = Pelican_Db::$values["PAGE_VERSION"];
            $this->aBind[":LANGUE_ID"] = Pelican_Db::$values['LANGUE_ID'];
            $sSql = "delete from #pref#_page_version_content where PAGE_ID=:PAGE_ID and PAGE_VERSION=:PAGE_VERSION and LANGUE_ID = :LANGUE_ID";
            $oConnection->query($sSql, $this->aBind);
        }

        if ($this->form_action != Pelican_Db::DATABASE_DELETE) {

            //mise a jour des modules
            /* ATTENTION, la création d'une version rend inactif l'update de la table #pref#_page ($oConnection->tableStopList) */
            $oConnection->updateTable($this->form_action, "#pref#_page");

            /* Mise à jour des chemins */
            if ($this->form_action != Pelican_Db::DATABASE_DELETE) {
                // Mise à jour du chemin "identifiant"
                $this->aBind[":PAGE_ID"] = Pelican_Db::$values["PAGE_ID"];
                $this->aBind[":LANGUE_ID"] = Pelican_Db::$values['LANGUE_ID'];
                $path = explode("#", Pelican_Db::$values["PAGE_PATH"]);
                array_pop($path);
                $path[] = Pelican_Db::$values["PAGE_ID"];
                $this->aBind[":PAGE_PATH"] = $oConnection->strtobind(implode("#", $path));

                // Mise à jour du chemin "textuel"
                // Calcul du chemin complet de la rubrique - couplé à la répercution du nouveau chemin sur les rubriques filles, cela permet d'éviter les problèmes d'accent en prod
                $this->aBind[':PAGE_PARENT_INC'] = Pelican_Db::$values["PAGE_ID"];
                $sqlParent = 'SELECT PAGE_PARENT_ID, p.PAGE_ID, PAGE_TITLE_BO FROM #pref#_page p INNER JOIN #pref#_page_version pv ON (p.PAGE_PARENT_ID = pv.PAGE_ID AND p.LANGUE_ID=pv.LANGUE_ID) where p.PAGE_ID=:PAGE_PARENT_INC
                  AND p.LANGUE_ID = :LANGUE_ID order by pv.PAGE_VERSION DESC';
                $parentPage = $oConnection->queryRow($sqlParent, $this->aBind);
                $arrayPageLibPath = array();
                $limitloop = 10;
                while ((sizeof($parentPage) > 0) && ($limitloop > 0)) {
                    $this->aBind[':PAGE_PARENT_INC'] = $parentPage["PAGE_PARENT_ID"];
                    $arrayPageLibPath[] = $parentPage['PAGE_PARENT_ID'].'|'.$parentPage['PAGE_TITLE_BO'];
                    $parentPage = $oConnection->queryRow($sqlParent, $this->aBind);
                    $limitloop --;
                }
                $arrayPageLibPath = array_reverse($arrayPageLibPath);
                $arrayPageLibPath[] = Pelican_Db::$values["PAGE_ID"]."|".Pelican_Db::$values["PAGE_TITLE_BO"];
                $newpath = implode('#', $arrayPageLibPath);

                $oldpath = Pelican_Db::$values["PAGE_LIBPATH"];
                $this->aBind[":PAGE_LIBPATH"] = $oConnection->strtobind($newpath);

                // Mise à jour du chemin pour la page courante */
                $sql = "update #pref#_page set PAGE_PATH=:PAGE_PATH,PAGE_LIBPATH=:PAGE_LIBPATH where PAGE_ID=:PAGE_ID AND LANGUE_ID=:LANGUE_ID";
                $oConnection->query($sql, $this->aBind);

                $this->aBind[":PATH"] = $oConnection->strToBind(implode("#", $path)."#%");
                $aDecachePath = $oConnection->getTab("select distinct PAGE_ID from #pref#_page where PAGE_PATH like :PATH", $this->aBind);

                // Répercution du nouveau chemin de la page courante sur les chemin des pages filles
                if ($oldpath != $newpath) {
                    $this->aBind[":PAGE_PATH"] = $oConnection->strtobind(implode("#", $path)."#%");

                    $this->aBind[":PAGE_OLDPATH"] = $oConnection->strtobind($oldpath."#");
                    $this->aBind[":PAGE_NEWPATH"] = $oConnection->strtobind($newpath."#");
                    $this->aBind[":LANGUE_ID"] = Pelican_Db::$values['LANGUE_ID'];
                    $sql = "update #pref#_page set PAGE_LIBPATH=REPLACE(PAGE_LIBPATH, :PAGE_OLDPATH, :PAGE_NEWPATH)  where PAGE_PATH like :PAGE_PATH AND LANGUE_ID=:LANGUE_ID";
                    $oConnection->query($sql, $this->aBind);

                    Pelican_Db::$values["PAGE_LIBPATH"] = $this->aBind[":PAGE_NEWPATH"];
                    if ($setNewUrl) {
                        $this->setNewUrl();                    
                    }
                }
                if ($aDecachePath) {
                    foreach ($aDecachePath as $path) {
                        //On décache les données du fil d'Ariane pour toutes les pages filles et parentes
                        Pelican_Cache::clean("Frontend/Page/Path", $path["PAGE_ID"]);
                    }
                }
            }

            if (empty(Pelican_Db::$values["PAGE_PRIORITY"])){
                Pelican_Db::$values["PAGE_PRIORITY"] = $this->getPagePriority();
            }

            $oConnection->updateTable($this->form_action, "#pref#_page_version");

            // avant la planification des blocs
            $this->aBind[":PAGE_ID"] = Pelican_Db::$values["PAGE_ID"];
            $this->aBind[":PAGE_VERSION"] = Pelican_Db::$values["PAGE_VERSION"];
            $this->aBind[":PAGE_ID"] = Pelican_Db::$values["PAGE_ID"];
            $this->aBind[":LANGUE_ID"] = Pelican_Db::$values['LANGUE_ID'];

            $aCountChildPages = $this->getCountChildPages(Pelican_Db::$values['PAGE_PARENT_ID']);

            if ((int) $aCountChildPages['count_children'] > 1) {
                $iOrder = (int) $aCountChildPages['count_children'] - 1;
            } else {
                $iOrder = 0;
            }

            if (isset(Pelican_Db::$values["PAGE_ORDER"]) &&
                Pelican_Db::$values["PAGE_ORDER"] !== null &&
                Pelican_Db::$values["PAGE_ORDER"] !== ''
            ) {
                $this->aBind[":PAGE_ORDER"] = Pelican_Db::$values["PAGE_ORDER"];
            } else {
                $this->aBind[":PAGE_ORDER"] = $iOrder;
            }

            // MISE A JOUR des liens CTA en fonction de l'adresse réelle si elle change
            $this->updateLinkCTA();

            /**/
            $sSQL = "delete from #pref#_page_multi where PAGE_ID=:PAGE_ID and LANGUE_ID=:LANGUE_ID and PAGE_VERSION=:PAGE_VERSION";
            $oConnection->query($sSQL, $this->aBind);
            $aMultiGeneral = array('PUSH', 'PUSH_OUTILS_MAJEUR', 'PUSH_OUTILS_MINEUR', 'PUSH_CONTENU_ANNEXE');
            foreach ($aMultiGeneral as $multiGeneral) {
                readMulti($multiGeneral, $multiGeneral);
                if (Pelican_Db::$values[$multiGeneral]) {
                    Pelican_Db::$values['PAGE_MULTI_TYPE'] = $multiGeneral;
                    unset($id);
                    $id = 0;
                    foreach (Pelican_Db::$values[$multiGeneral] as $i => $item) {
                        if ($item['multi_display'] == 1) {
                            $id++;
                            $DBVALUES_SAVE = Pelican_Db::$values;
                            Pelican_Db::$values['MEDIA_ID'] = '';
                            Pelican_Db::$values['PAGE_MULTI_ID'] = $id;
                            Pelican_Db::$values['PAGE_MULTI_LABEL'] = $item['PAGE_MULTI_LABEL'];
                            if ($item['MEDIA_ID']) {
                                Pelican_Db::$values['MEDIA_ID'] = $item['MEDIA_ID'];
                            }
                            if ($item['PAGE_ZONE_MULTI_ORDER']) {
                                Pelican_Db::$values['PAGE_ZONE_MULTI_ORDER'] = $item['PAGE_ZONE_MULTI_ORDER'];
                            }
                            Pelican_Db::$values['PAGE_MULTI_URL'] = $item['PAGE_MULTI_URL'];
                            Pelican_Db::$values['PAGE_MULTI_OPTION'] = $item['PAGE_MULTI_OPTION'];
                            $oConnection->updateTable(Pelican::$config['DATABASE_INSERT'], "#pref#_page_multi");
                            Pelican_Db::$values = $DBVALUES_SAVE;
                        }
                    }
                }
            }

            $oConnection->query("update #pref#_page set PAGE_ORDER = :PAGE_ORDER WHERE PAGE_ID=:PAGE_ID", $this->aBind);
            $this->ordonnancementPagesByPageParent(Pelican_Db::$values['PAGE_PARENT_ID']);
            $_SESSION["MOVE"]["id"] = Pelican_Db::$values["id"];

            $oConnection->query("delete from #pref#_navigation where PAGE_ID=:PAGE_ID and PAGE_VERSION=:PAGE_VERSION AND LANGUE_ID = :LANGUE_ID", $this->aBind);
            $oConnection->query("delete from #pref#_page_zone_content where PAGE_ID=:PAGE_ID and PAGE_VERSION=:PAGE_VERSION AND LANGUE_ID = :LANGUE_ID", $this->aBind);
            $oConnection->query("delete from #pref#_page_zone_media where PAGE_ID=:PAGE_ID and PAGE_VERSION=:PAGE_VERSION AND LANGUE_ID = :LANGUE_ID", $this->aBind);

            $oConnection->query("delete from #pref#_page_zone where PAGE_ID=:PAGE_ID AND PAGE_VERSION=:PAGE_VERSION AND LANGUE_ID = :LANGUE_ID", $this->aBind);
            $oConnection->query("delete from #pref#_page_version_media where PAGE_ID=:PAGE_ID AND PAGE_VERSION=:PAGE_VERSION AND LANGUE_ID = :LANGUE_ID", $this->aBind);

            //Mise à jour des Pelican_Media associés à la page
            for ($i = 1; $i <= 5; $i ++) {
                Pelican_Db::$values["MEDIA_ID"] = Pelican_Db::$values["MEDIA_ID_".$i];
                if (Pelican_Db::$values["MEDIA_ID"]) {
                    Pelican_Db::$values["PAGE_MEDIA_TYPE"] = "IMG".$i;
                    $oConnection->updateTable(Pelican_Db::DATABASE_INSERT, "#pref#_page_version_media");
                }
            }

            readMulti("page");

            //mise a jours des modules
            $this->monoValues = Pelican_Db::$values;

            if ($this->monoValues["ZONE_DB"]) {
                $zoneCompte = 0;

                foreach ($this->monoValues["ZONE_TEMPLATE_ID"] as $index => $id) {
                    $zoneCompte ++;

                    $db = $this->monoValues["ZONE_DB"][$index];

                    Pelican_Db::$values = $this->monoValues["page"][$index];
                    Pelican_Db::$values["PAGE_ID"] = $this->monoValues["PAGE_ID"];
                    Pelican_Db::$values["PAGE_VERSION"] = $this->monoValues["PAGE_VERSION"];
                    Pelican_Db::$values['LANGUE_ID'] = $this->monoValues['LANGUE_ID'];

                    Pelican_Db::$values["PAGE_ID_OLDORIGINE"] = $this->monoValues["PAGE_ID"];
                    Pelican_Db::$values["PAGE_VERSION_ORIGINE"] = $this->monoValues["PAGE_VERSION"];

                    if (Pelican_Db::$values['ZONE_DB_MULTI']) {
                        $db = true;
                    }

                    $root = (Pelican_Db::$values["PLUGIN_ID"] ? Pelican::$config["PLUGIN_ROOT"].'/'.Pelican_Db::$values["PLUGIN_ID"].'/backend/controllers' : Pelican::$config['APPLICATION_CONTROLLERS']);
                    $module = $root.'/'.str_replace("_", "/", $db).".php";
                    $moduleClass = $db;

                    if (!Pelican_Db::$values["ZONE_DB_MULTI"]) {
                        if (file_exists($module)) {
                            include_once $module;

                            $property = new ReflectionProperty($moduleClass, 'decacheBack');
                            $moduleDecacheBack = $property->getValue();
                            $property = new ReflectionProperty($moduleClass, 'decachePublication');
                            $moduleDecachePublication = $property->getValue();
                            if (is_array($moduleDecacheBack)) {
                                $this->listDecache = array_merge($this->listDecache, $moduleDecacheBack);
                            }
                            if (Pelican_Db::$values["PUBLICATION"]) {
                                if (is_array($moduleDecachePublication)) {
                                    $this->listDecache = array_merge($this->listDecache, $moduleDecachePublication);
                                }
                            }

                            $property = new ReflectionProperty($moduleClass, 'decacheBackOrchestra');
                            $moduleDecacheBackOrchestra = $property->getValue();
                            $property = new ReflectionProperty($moduleClass, 'decachePublicationOrchestra');
                            $moduleDecachePublicationOrchestra = $property->getValue();
                            if (is_array($moduleDecacheBackOrchestra)) {
                                $this->listDecacheOrchestra = array_merge($this->listDecacheOrchestra, $moduleDecacheBackOrchestra);
                            }
                            if (Pelican_Db::$values["PUBLICATION"]) {
                                if (is_array($moduleDecachePublicationOrchestra)) {
                                    $this->listDecacheOrchestra = array_merge($this->listDecacheOrchestra, $moduleDecachePublicationOrchestra);
                                }
                            }

                            call_user_func_array(array(
                                $moduleClass,
                                'save',
                                ), array(
                                $this,
                            ));

                            call_user_func_array(array(
                                $moduleClass,
                                'addCache',
                                ), array(
                                $this,
                            ));
                        }
                    }
                }
            }

            //mise a jours des modules
            Pelican_Db::$values = $this->monoValues;

            $this->saveMultiZones();

            /* cas d'une suppression de version */
            if ($this->workflowFieldDeleteVersion) {
                foreach ($this->workflowFieldDeleteVersion as $delete_version) {
                    // => initialisation de la version à supprimer (on en a fait la sauvegarde juste avant)
                    Pelican_Db::$values["PAGE_VERSION"] = $delete_version;
                    $this->form_action = Pelican_Db::DATABASE_DELETE;
                    $this->_deletePage($delete_version);
                }
            }

            Pelican_Db::$values = $this->monoValues;
            if (Pelican_Db::$values["STATE_ID"] == Pelican::$config["CORBEILLE_STATE"]) {
                //mise de pages enfants  et des contenus
                $pageId = Pelican_Db::$values["PAGE_ID"];
                //Pelican_Db::$values = array();
               Ndp_Page::_updateChildPage($pageId, Pelican::$config["CORBEILLE_STATE"]);

                // Vidage du cache pour prendre en compte la suppression des pages fille
                // Caches basés sur le SITE_ID
                Pelican_Cache::clean("Frontend/Page/Template", array(Pelican_Db::$values['SITE_ID']));
                Pelican_Cache::clean("Frontend/Url", array(Pelican_Db::$values['SITE_ID']));
                Pelican_Cache::clean("Request/Redirect", array(Pelican_Db::$values['SITE_ID']));

                // Caches basés sur le PAGE_ID
                if (!empty(Ndp_Page::$trashUpdatedPages)) {
                    foreach (Ndp_Page::$trashUpdatedPages as $val) {
                        Pelican_Cache::clean("Frontend/Page", array($val));
                        Pelican_Cache::clean("Frontend/Page/Zone", array($val));
                    }
                }
                Ndp_Page::$trashUpdatedPages=[];
                $_SESSION[APP]['PAGE_RETURN'] = "<script type=\"text/javascript\">top.location.href=top.location.href;</script>";
            }
        } else {
            $this->_deletePage();
            $oConnection->deleteQuery("#pref#_page");
            $_SESSION[APP]['PAGE_RETURN'] = "<script type=\"text/javascript\">top.location.href=top.location.href;</script>";
        }

        $oConnection->commit();

        if ($goToNewPage === true) {
            /* cas de la création */
            $_SESSION[APP]['PAGE_ID'] = Pelican_Db::$values["PAGE_ID"];
        }
    }
    
    private function setNewUrl() {
        Pelican_Db::$values['PAGE_CLEAR_URL'] = '';
        $tmpId = Pelican::$config["DATABASE_INSERT_ID"];
        Pelican::$config["DATABASE_INSERT_ID"] = 0;
        $this->getClearUrl();
        Pelican::$config["DATABASE_INSERT_ID"] = $tmpId;
    }

    public function updateLinkCTA()
    {
        $oConnection = Pelican_Db::getInstance();
        $aParams = $this->getParams();
        $this->aBind[':SITE_ID'] = $aParams['SITE_ID'];
        $this->aBind[':LANGUE_ID'] = $aParams['LANGUE_ID'];

        // si Adresse réelle a était changé
        if (!empty(Pelican_Db::$values["OLD_URL"]) && Pelican_Db::$values["OLD_URL"] != Pelican_Db::$values['PAGE_CLEAR_URL']) {
            $this->aBind[':CTA_NEW_URL'] = $oConnection->strtobind(Pelican_Db::$values['PAGE_CLEAR_URL']);
            $this->aBind[':CTA_OLD_URL'] = $oConnection->strtobind(Pelican_Db::$values['OLD_URL']);

            // verification sur la table psa_content_version
            $sSql = '
                SELECT cv.CONTENT_ID, MAX(cv.CONTENT_VERSION) as MAX_VERS, cv.CONTENT_URL2
                FROM #pref#_content_version cv, #pref#_content c
                WHERE
                cv.CONTENT_ID = c.CONTENT_ID
                AND c.SITE_ID =:SITE_ID
                AND c.LANGUE_ID = :LANGUE_ID
                AND cv.CONTENT_URL2=:CTA_OLD_URL
                GROUP BY cv.CONTENT_ID';

            $aContent = $oConnection->queryTab($sSql, $this->aBind);
            if (!empty($aContent) && is_array($aContent)) {
                foreach ($aContent as $i => $champ) {
                    $this->aBind[':ID'] = $champ["CONTENT_ID"];
                    $this->aBind[':VERSION'] = $champ["MAX_VERS"];
                    if (!empty($champ["CONTENT_URL2"]) && $champ["CONTENT_URL2"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_content_version SET CONTENT_URL2=:CTA_NEW_URL where CONTENT_ID=:ID AND CONTENT_VERSION=:VERSION AND CONTENT_URL2=:CTA_OLD_URL", $this->aBind);
                    }
                }
            }

            // verification sur la table psa_content_zone_multi
            $sSql = '
                SELECT czm.CONTENT_ID, MAX(czm.CONTENT_VERSION) as MAX_VERS, czm.CONTENT_ZONE_MULTI_URL, czm.CONTENT_ZONE_MULTI_URL2
                FROM #pref#_content_zone_multi czm, #pref#_content c
                WHERE
                czm.CONTENT_ID = c.CONTENT_ID
                AND c.SITE_ID =:SITE_ID
                AND c.LANGUE_ID = :LANGUE_ID
                AND (
                    czm.CONTENT_ZONE_MULTI_URL=:CTA_OLD_URL
                    OR czm.CONTENT_ZONE_MULTI_URL2=:CTA_OLD_URL
                    )
                GROUP BY czm.CONTENT_ID';
            $aContentMulti = $oConnection->queryTab($sSql, $this->aBind);
            if (!empty($aContentMulti) && is_array($aContentMulti)) {
                foreach ($aContentMulti as $i => $champ) {
                    $this->aBind[':ID'] = $champ["CONTENT_ID"];
                    $this->aBind[':VERSION'] = $champ["MAX_VERS"];
                    if (!empty($champ["CONTENT_ZONE_MULTI_URL"]) && $champ["CONTENT_ZONE_MULTI_URL"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_content_zone_multi SET CONTENT_ZONE_MULTI_URL=:CTA_NEW_URL where CONTENT_ID=:ID AND CONTENT_VERSION=:VERSION AND CONTENT_ZONE_MULTI_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["CONTENT_ZONE_MULTI_URL2"]) && $champ["CONTENT_ZONE_MULTI_URL2"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_content_zone_multi SET CONTENT_ZONE_MULTI_URL2=:CTA_NEW_URL where CONTENT_ID=:ID AND CONTENT_VERSION=:VERSION AND CONTENT_ZONE_MULTI_URL2=:CTA_OLD_URL", $this->aBind);
                    }
                }
            }

            // verification sur la table psa_page_multi_zone_multi
            $sSql = '
                SELECT pmzm.PAGE_ID, MAX(pmzm.PAGE_VERSION) as MAX_VERS, pmzm.PAGE_ZONE_MULTI_URL,
                    pmzm.PAGE_ZONE_MULTI_URL2,
                    pmzm.PAGE_ZONE_MULTI_URL3,
                    pmzm.PAGE_ZONE_MULTI_URL4,
                    pmzm.PAGE_ZONE_MULTI_URL5,
                    pmzm.PAGE_ZONE_MULTI_URL6,
                    pmzm.PAGE_ZONE_MULTI_URL7,
                    pmzm.PAGE_ZONE_MULTI_URL8
                FROM #pref#_page_multi_zone_multi pmzm, #pref#_page p
                WHERE
                p.PAGE_ID = pmzm.PAGE_ID
                AND p.SITE_ID =:SITE_ID
                AND p.LANGUE_ID = :LANGUE_ID
                AND (
                    pmzm.PAGE_ZONE_MULTI_URL=:CTA_OLD_URL
                    OR pmzm.PAGE_ZONE_MULTI_URL2=:CTA_OLD_URL
                    OR pmzm.PAGE_ZONE_MULTI_URL3=:CTA_OLD_URL
                    OR pmzm.PAGE_ZONE_MULTI_URL4=:CTA_OLD_URL
                    OR pmzm.PAGE_ZONE_MULTI_URL5=:CTA_OLD_URL
                    OR pmzm.PAGE_ZONE_MULTI_URL6=:CTA_OLD_URL
                    OR pmzm.PAGE_ZONE_MULTI_URL7=:CTA_OLD_URL
                    OR pmzm.PAGE_ZONE_MULTI_URL8=:CTA_OLD_URL
                    )
                GROUP BY pmzm.PAGE_ID';
            $aContentMulti = $oConnection->queryTab($sSql, $this->aBind);
            if (!empty($aContentMulti) && is_array($aContentMulti)) {
                foreach ($aContentMulti as $i => $champ) {
                    $this->aBind[':ID'] = $champ["PAGE_ID"];
                    $this->aBind[':VERSION'] = $champ["MAX_VERS"];
                    if (!empty($champ["PAGE_ZONE_MULTI_URL"]) && $champ["PAGE_ZONE_MULTI_URL"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_multi_zone_multi SET PAGE_ZONE_MULTI_URL=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND PAGE_ZONE_MULTI_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["PAGE_ZONE_MULTI_URL2"]) && $champ["PAGE_ZONE_MULTI_URL2"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_multi_zone_multi SET PAGE_ZONE_MULTI_URL2=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND PAGE_ZONE_MULTI_URL2=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["PAGE_ZONE_MULTI_URL3"]) && $champ["PAGE_ZONE_MULTI_URL3"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_multi_zone_multi SET PAGE_ZONE_MULTI_URL3=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND PAGE_ZONE_MULTI_URL3=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["PAGE_ZONE_MULTI_URL4"]) && $champ["PAGE_ZONE_MULTI_URL4"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_multi_zone_multi SET PAGE_ZONE_MULTI_URL4=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND PAGE_ZONE_MULTI_URL4=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["PAGE_ZONE_MULTI_URL5"]) && $champ["PAGE_ZONE_MULTI_URL5"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_multi_zone_multi SET PAGE_ZONE_MULTI_URL5=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND PAGE_ZONE_MULTI_URL5=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["PAGE_ZONE_MULTI_URL6"]) && $champ["PAGE_ZONE_MULTI_URL6"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_multi_zone_multi SET PAGE_ZONE_MULTI_URL6=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION  AND PAGE_ZONE_MULTI_URL6=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["PAGE_ZONE_MULTI_URL7"]) && $champ["PAGE_ZONE_MULTI_URL7"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_multi_zone_multi SET PAGE_ZONE_MULTI_URL7=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION  AND PAGE_ZONE_MULTI_URL7=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["PAGE_ZONE_MULTI_URL8"]) && $champ["PAGE_ZONE_MULTI_URL8"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_multi_zone_multi SET PAGE_ZONE_MULTI_URL8=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION  AND PAGE_ZONE_MULTI_URL8=:CTA_OLD_URL", $this->aBind);
                    }
                }
            }

            // verification sur la table psa_page_zone_multi
            $sSql = '
                SELECT pzm.PAGE_ID, MAX(pzm.PAGE_VERSION) as MAX_VERS, pzm.PAGE_ZONE_MULTI_URL,
                    pzm.PAGE_ZONE_MULTI_URL2,
                    pzm.PAGE_ZONE_MULTI_URL3,
                    pzm.PAGE_ZONE_MULTI_URL4,
                    pzm.PAGE_ZONE_MULTI_URL5,
                    pzm.PAGE_ZONE_MULTI_URL6,
                    pzm.PAGE_ZONE_MULTI_URL7,
                    pzm.PAGE_ZONE_MULTI_URL8
                FROM #pref#_page_zone_multi pzm, #pref#_page p
                WHERE
                p.PAGE_ID = pzm.PAGE_ID
                AND p.SITE_ID =:SITE_ID
                AND p.LANGUE_ID = :LANGUE_ID
                AND (
                    pzm.PAGE_ZONE_MULTI_URL=:CTA_OLD_URL
                    OR pzm.PAGE_ZONE_MULTI_URL2=:CTA_OLD_URL
                    OR pzm.PAGE_ZONE_MULTI_URL3=:CTA_OLD_URL
                    OR pzm.PAGE_ZONE_MULTI_URL4=:CTA_OLD_URL
                    OR pzm.PAGE_ZONE_MULTI_URL5=:CTA_OLD_URL
                    OR pzm.PAGE_ZONE_MULTI_URL6=:CTA_OLD_URL
                    OR pzm.PAGE_ZONE_MULTI_URL7=:CTA_OLD_URL
                    OR pzm.PAGE_ZONE_MULTI_URL8=:CTA_OLD_URL
                    )
                GROUP BY pzm.PAGE_ID';
            $aContentMulti = $oConnection->queryTab($sSql, $this->aBind);
            if (!empty($aContentMulti) && is_array($aContentMulti)) {
                foreach ($aContentMulti as $i => $champ) {
                    $this->aBind[':ID'] = $champ["PAGE_ID"];
                    $this->aBind[':VERSION'] = $champ["MAX_VERS"];
                    if (!empty($champ["PAGE_ZONE_MULTI_URL"]) && $champ["PAGE_ZONE_MULTI_URL"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_zone_multi SET PAGE_ZONE_MULTI_URL=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND PAGE_ZONE_MULTI_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["PAGE_ZONE_MULTI_URL2"]) && $champ["PAGE_ZONE_MULTI_URL2"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_zone_multi SET PAGE_ZONE_MULTI_URL2=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND PAGE_ZONE_MULTI_URL2=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["PAGE_ZONE_MULTI_URL3"]) && $champ["PAGE_ZONE_MULTI_URL3"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_zone_multi SET PAGE_ZONE_MULTI_URL3=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND PAGE_ZONE_MULTI_URL3=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["PAGE_ZONE_MULTI_URL4"]) && $champ["PAGE_ZONE_MULTI_URL4"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_zone_multi SET PAGE_ZONE_MULTI_URL4=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND PAGE_ZONE_MULTI_URL4=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["PAGE_ZONE_MULTI_URL5"]) && $champ["PAGE_ZONE_MULTI_URL5"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_zone_multi SET PAGE_ZONE_MULTI_URL5=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND PAGE_ZONE_MULTI_URL5=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["PAGE_ZONE_MULTI_URL6"]) && $champ["PAGE_ZONE_MULTI_URL6"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_zone_multi SET PAGE_ZONE_MULTI_URL6=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND PAGE_ZONE_MULTI_URL6=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["PAGE_ZONE_MULTI_URL7"]) && $champ["PAGE_ZONE_MULTI_URL7"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_zone_multi SET PAGE_ZONE_MULTI_URL7=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND PAGE_ZONE_MULTI_URL7=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["PAGE_ZONE_MULTI_URL8"]) && $champ["PAGE_ZONE_MULTI_URL8"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_zone_multi SET PAGE_ZONE_MULTI_URL8=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND PAGE_ZONE_MULTI_URL8=:CTA_OLD_URL", $this->aBind);
                    }
                }
            }

            // verification sur la table psa_page_zone
            $sSql = '
                SELECT pz.PAGE_ID, MAX(pz.PAGE_VERSION) as MAX_VERS, pz.ZONE_URL,
                    pz.ZONE_URL2,
                    pz.ZONE_TITRE3,
                    pz.ZONE_TITRE5,
                    pz.ZONE_TITRE6,
                    pz.ZONE_TITRE7,
                    pz.ZONE_TITRE8,
                    pz.ZONE_TITRE9,
                    pz.ZONE_TITRE10,
                    pz.ZONE_TITRE11,
                    pz.ZONE_TITRE12,
                    pz.ZONE_TEXTE3,
                    pz.ZONE_TEXTE6
                FROM #pref#_page_zone pz, #pref#_page p
                WHERE
                p.PAGE_ID = pz.PAGE_ID
                AND p.SITE_ID =:SITE_ID
                AND p.LANGUE_ID = :LANGUE_ID
                AND (
                    pz.ZONE_URL=:CTA_OLD_URL
                    OR pz.ZONE_URL2=:CTA_OLD_URL
                    OR pz.ZONE_TITRE3=:CTA_OLD_URL
                    OR pz.ZONE_TITRE5=:CTA_OLD_URL
                    OR pz.ZONE_TITRE6=:CTA_OLD_URL
                    OR pz.ZONE_TITRE7=:CTA_OLD_URL
                    OR pz.ZONE_TITRE8=:CTA_OLD_URL
                    OR pz.ZONE_TITRE9=:CTA_OLD_URL
                    OR pz.ZONE_TITRE10=:CTA_OLD_URL
                    OR pz.ZONE_TITRE11=:CTA_OLD_URL
                    OR pz.ZONE_TITRE12=:CTA_OLD_URL
                    OR pz.ZONE_TEXTE3=:CTA_OLD_URL
                    OR pz.ZONE_TEXTE6=:CTA_OLD_URL
                    )
                GROUP BY pz.PAGE_ID';
            $aContentMulti = $oConnection->queryTab($sSql, $this->aBind);
            if (!empty($aContentMulti) && is_array($aContentMulti)) {
                foreach ($aContentMulti as $i => $champ) {
                    $this->aBind[':ID'] = $champ["PAGE_ID"];
                    $this->aBind[':VERSION'] = $champ["MAX_VERS"];
                    if (!empty($champ["ZONE_URL"]) && $champ["ZONE_URL"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_zone SET ZONE_URL=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_URL=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["ZONE_URL2"]) && $champ["ZONE_URL2"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_zone SET ZONE_URL2=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_URL2=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["ZONE_TITRE3"]) && $champ["ZONE_TITRE3"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_zone SET ZONE_TITRE3=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_TITRE3=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["ZONE_TITRE5"]) && $champ["ZONE_TITRE5"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_zone SET ZONE_TITRE5=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_TITRE5=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["ZONE_TITRE6"]) && $champ["ZONE_TITRE6"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_zone SET ZONE_TITRE6=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_TITRE6=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["ZONE_TITRE7"]) && $champ["ZONE_TITRE7"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_zone SET ZONE_TITRE7=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_TITRE7=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["ZONE_TITRE8"]) && $champ["ZONE_TITRE8"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_zone SET ZONE_TITRE8=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_TITRE8=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["ZONE_TITRE9"]) && $champ["ZONE_TITRE9"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_zone SET ZONE_TITRE9=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_TITRE9=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["ZONE_TITRE10"]) && $champ["ZONE_TITRE10"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_zone SET ZONE_TITRE10=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_TITRE10=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["ZONE_TITRE11"]) && $champ["ZONE_TITRE11"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_zone SET ZONE_TITRE11=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_TITRE11=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["ZONE_TITRE12"]) && $champ["ZONE_TITRE12"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_zone SET ZONE_TITRE12=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_TITRE12=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["ZONE_TEXTE3"]) && $champ["ZONE_TEXTE3"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_zone SET ZONE_TEXTE3=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_TEXTE3=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if (!empty($champ["ZONE_TEXTE6"]) && $champ["ZONE_TEXTE6"] == Pelican_Db::$values["OLD_URL"]) {
                        $oConnection->query("update #pref#_page_zone SET ZONE_TEXTE6=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_TEXTE6=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                }
            }
        }
    }

    public function saveMultiZones()
    {
        $oConnection = Pelican_Db::getInstance();

        if (Pelican_Db::$values['db_pageMulti'] && !empty(Pelican_Db::$values['db_pageMulti'])) {
            $oConnection->query("delete from #pref#_page_multi_zone_content where PAGE_ID=:PAGE_ID and PAGE_VERSION=:PAGE_VERSION AND LANGUE_ID = :LANGUE_ID", $this->aBind);
            $oConnection->query("delete from #pref#_page_multi_zone_media where PAGE_ID=:PAGE_ID and PAGE_VERSION=:PAGE_VERSION AND LANGUE_ID = :LANGUE_ID", $this->aBind);

            $oConnection->query("delete from #pref#_page_multi_zone where PAGE_ID=:PAGE_ID AND PAGE_VERSION=:PAGE_VERSION AND LANGUE_ID = :LANGUE_ID", $this->aBind);

            foreach (Pelican_Db::$values['db_pageMulti'] as $area_id) {
                unset(Pelican_Db::$values['page']);
                if (Pelican_Db::$values["MULTI_ZONE_".$area_id."_DB"]) {
                    $tmpKeys = array_keys(Pelican_Db::$values["MULTI_ZONE_".$area_id."_DB"]);
                    sort($tmpKeys);
                    Pelican_Db::$values["count_pageMulti".$area_id] = $tmpKeys[count($tmpKeys) - 1];
                    readMulti('pageMulti'.$area_id, 'multiZone'.$area_id.'_');

                    //mise a jours des modules
                    $this->monoValues = Pelican_Db::$values;

                    $zoneTotal = count($this->monoValues["ZONE_TEMPLATE_ID"]);
                    $zoneCompte = 0;

                    foreach ($this->monoValues["MULTI_ZONE_".$area_id."_DB"] as $index => $db) {
                        $zoneCompte ++;

                        Pelican_Db::$values = $this->monoValues['pageMulti'.$area_id][$index];

                        Pelican_Db::$values["ZONE_DYNAMIQUE"] = 1;

                        Pelican_Db::$values["PAGE_ID"] = $this->monoValues["PAGE_ID"];
                        Pelican_Db::$values["PAGE_VERSION"] = $this->monoValues["PAGE_VERSION"];
                        Pelican_Db::$values['LANGUE_ID'] = $this->monoValues['LANGUE_ID'];

                        //??$this->form_action = $this->monoValues["form_action"];
                        Pelican_Db::$values["PAGE_ID_OLDORIGINE"] = $this->monoValues["PAGE_ID"];
                        Pelican_Db::$values["PAGE_VERSION_ORIGINE"] = $this->monoValues["PAGE_VERSION"];

                        Pelican_Db::$values["AREA_ID"] = $area_id;
                        Pelican_Db::$values["DB_INDEX"] = $index;
                        Pelican_Db::$values["ZONE_ORDER"] = $zoneCompte;
                        Pelican_Db::$values["ZONE_ID"] = $this->monoValues["MULTI_ZONE_".$area_id."_ID"][$index];
                        Pelican_Db::$values["UID"] = $this->monoValues["MULTI_ZONE_".$area_id."_UID"][$index];
                        Pelican_Db::$values["DISPLAY_ON_FO"] = $this->monoValues["MULTI_ZONE_".$area_id."_".Pelican_Db::$values["UID"]."_DISPLAY_ON_FO"];

                        if (Pelican_Db::$values['ZONE_DB_MULTI']) {
                            $db = true;
                        }

                        $root = (Pelican_Db::$values["PLUGIN_ID"] ? Pelican::$config["PLUGIN_ROOT"].'/'.Pelican_Db::$values["PLUGIN_ID"].'/backend/controllers' : Pelican::$config['APPLICATION_CONTROLLERS']);
                        $module = $root.'/'.str_replace("_", "/", $db).".php";
                        $moduleClass = $db;

                        if (!Pelican_Db::$values["ZONE_DB_MULTI"]) {
                            if (file_exists($module)) {
                                include_once $module;

                                call_user_func_array(array(
                                    $moduleClass,
                                    'save',
                                    ), array(
                                    $this,
                                ));

                                call_user_func_array(array(
                                    $moduleClass,
                                    'addCache',
                                    ), array(
                                    $this,
                                ));
                            }
                        } else {
                            $TEMP_FORM = Pelican_Db::$values;

                            Pelican_Db::$values = $TEMP_FORM;
                        }
                    }
                }
            }

            Pelican_Db::$values = $this->monoValues;
        }
    }

    public static function setPageOrder($page, $id, $type, $order = 1)
    {
        $oConnection = Pelican_Db::getInstance();

        /* on supprime l'entrée dans la table page_order */
        $DBVALUES_INI = Pelican_Db::$values;
        Pelican_Db::$values["PAGE_ID"] = $page;
        Pelican_Db::$values["PAGE_ORDER_ID"] = $id;
        Pelican_Db::$values["PAGE_ORDER_TYPE"] = $type;
        Pelican_Db::$values["PAGE_ORDER"] = $order;
        $oConnection->deleteQuery("#pref#_page_order");

        if ($order) {
            $aBind[":PAGE_ID"] = $page;
            $aBind[":LANGUE_ID"] = Pelican_Db::$values['LANGUE_ID'];
            $aBind[":PAGE_ORDER_TYPE"] = $type;
            $aBind[":PAGE_ORDER"] = $order;
            $sSql = "update #pref#_page_order set PAGE_ORDER=PAGE_ORDER+1 where PAGE_ID=:PAGE_ID AND LANGUE_ID=:LANGUE_ID AND PAGE_ORDER_TYPE=:PAGE_ORDER_TYPE AND PAGE_ORDER>=:PAGE_ORDER";
            $oConnection->query($sSql, $aBind);

            $oConnection->insertQuery("#pref#_page_order");
            self::cleanPageOrder($page, 1);
        }
        Pelican_Db::$values = $DBVALUES_INI;
    }

    public static function cleanPageOrder($id, $type)
    {
        $oConnection = Pelican_Db::getInstance();

        /* on supprime l'entrée dans la table page_order */
        $DBVALUES_INI = Pelican_Db::$values;
        Pelican_Db::$values["PAGE_ORDER_TYPE"] = $type;
        Pelican_Db::$values["PAGE_ORDER_ID"] = $id;
        $oConnection->updateTable(Pelican::$config["DATABASE_DELETE"], "#pref#_page_order", "PAGE_ID");
        Pelican_Db::$values = $DBVALUES_INI;
    }

    /**
     * Création d'une Pelican_Index_Frontoffice_Zone cliquable dans la miniature de la page.
     *
     * @param mixed $tabZones Tableau de définition des zones
     */
    protected function _generateZone($tabZones)
    {
        $oConnection = Pelican_Factory::getConnection();
        if (Pelican::$config["MODE_ZONE_VIEW"] == 'top') {
            $return = "<table width=\"100%\" border=\"1\" bordercolor=\"black\" cellpadding=\"0\" style=\"table-layout:fixed;border-color:#f9f9f9;\">";
        } else {
            $return = "<table width=\"348\" height=\"300\" border=\"1\" bordercolor=\"black\" cellpadding=\"0\" style=\"table-layout:fixed;border-color:#f9f9f9;\">";
        }
        $nbrbloc = sizeOf($tabZones);

        $i = 1;
        if ($tabZones) {
            $tr = array();

            $tabZones2 = $tmp = array();
            foreach ($tabZones as $data) {
                if (!$data['AREA_DROPPABLE']) {
                    $tabZones2[] = $data;
                } elseif ($data['AREA_DROPPABLE'] && !$tmp[$data['AREA_ID']]) {
                    $tmp[$data['AREA_ID']] = 1;
                    $sSql = '
                        SELECT pmz.*,
                            z.ZONE_BO_PATH,
                            z.ZONE_LABEL as LABEL_ZONE,
                            z.ZONE_TYPE_ID
                        FROM #pref#_page_multi_zone pmz
                            INNER JOIN #pref#_zone z
                                ON (pmz.ZONE_ID = z.ZONE_ID)
                        WHERE PAGE_ID = :PAGE_ID
                            AND PAGE_VERSION = :PAGE_VERSION
                            AND LANGUE_ID = :LANGUE_ID
                            AND AREA_ID = :AREA_ID
                        ORDER BY ZONE_ORDER ASC';
                    $this->aBind[':AREA_ID'] = $data['AREA_ID'];
                    $aMultiZones = $oConnection->queryTab($sSql, $this->aBind);
                    if (!empty($aMultiZones)) {
                        foreach ($aMultiZones as $key => $multiZone) {
                            $multiZone['ZONE_TEMPLATE_ID'] = 'zoneDynamique_'.$key;
                            $multiZone['ZONE_TEMPLATE_LABEL'] = $multiZone['LABEL_ZONE'];
                            $multiZone['LARGEUR'] = $data['LARGEUR'];
                            $tabZones2[] = $multiZone;
                        }
                    }
                }
            }

            $tabZones = $tabZones2;
            foreach ($tabZones as $data) {
                if ($data["ZONE_TYPE_ID"] > 0) {
                    if ($data["ZONE_TYPE_ID"] != 2) {
                        $label = Pelican_Html::a(array(
                                href => "#anchor".$data["ZONE_TEMPLATE_ID"],
                                ), $data["ZONE_TEMPLATE_LABEL"]);
                    } else {
                        $label = t($data["ZONE_TEMPLATE_LABEL"]);
                    }

                    if (!@$tr[$data["LIGNE"]][$data["COLONNE"]]) {
                        $tr[$data["LIGNE"]][$data["COLONNE"]] = Pelican_Html::td(array(
                                align => "center",
                                valign => "top",
                                colspan => $data["LARGEUR"],
                                rowspan => $data["HAUTEUR"],
                                height => "100%",
                                ), "");
                    }
                    $tr[$data["LIGNE"]][$data["COLONNE"]] .= "";
                    $horizontal[$data["LIGNE"]] = $data['AREA_HORIZONTAL'];

                    $border = "onmouseover=\"this.style.borderColor='red'\" onmouseout=\"this.style.borderColor='#CACACA'\"";
                    $click = "onclick=\"showHideZone('".$data["ZONE_TEMPLATE_ID"]."', false,'".$nbrbloc."');\"";
                    $bloc[$data["LIGNE"]][$data["COLONNE"]][$i] = "<tr><td class=\"zonetype".$data["ZONE_TYPE_ID"]."\" ";
                    if ($data["ZONE_TYPE_ID"] != 2) {
                        $bloc[$data["LIGNE"]][$data["COLONNE"]][$i] .= $click." ".$border;
                    }
                    $bloc[$data["LIGNE"]][$data["COLONNE"]][$i] .= ">";
                    $bloc[$data["LIGNE"]][$data["COLONNE"]][$i] .= str_replace(" ".t('(HOME)'), "", $label);
                    if ($data["ZONE_PROGRAM"]) {
                        $bloc[$data["LIGNE"]][$data["COLONNE"]][$i] .= " ".Pelican_Html::img(array(
                                src => Pelican::$config["IMAGE_PATH"]."/prog.gif",
                                alt => t('SCHEDULING_BLOC'),
                                border => "0",
                                valign => "middle",
                        ));
                    }
                    $bloc[$data["LIGNE"]][$data["COLONNE"]][$i] .= "</td></tr>";

                    $i ++;
                }
            }
            foreach ($tr as $row => $td) {
                $cell = array();
                foreach ($td as $col => $value) {
                    $size = round(100 / sizeof($bloc[$row][$col]));
                    foreach ($bloc[$row][$col] as $key => $val) {
                        $bloc[$row][$col][$key] = str_replace('<td', '<td height="'.$size.'%"', $val);
                    }

                    if ($horizontal[$row]) {
                        $cell[] = str_replace("</td>", Pelican_Html::table(array(
                                width => "100%",
                                height => "100%",
                                ), str_replace('</tr><tr>', '', implode("", $bloc[$row][$col])))."</td>", $tr[$row][$col]);
                    } else {
                        $cell[] = str_replace("</td>", Pelican_Html::table(array(
                                width => "100%",
                                height => "100%",
                                ), implode("", $bloc[$row][$col]))."</td>", $tr[$row][$col]);
                    }
                }
                $return .= Pelican_Html::tr(implode("", $cell));
            }
        }
        $return .= "</table>";

        return $return;
    }

    /**
     * Initialisation d'une Pelican_Index_Frontoffice_Zone :
     * - Définition des $this->values (contient les valeurs de la Pelican_Index_Frontoffice_Zone ou les valeurs par défaut)
     * - Création du champs caché contenant le chemin du fichier de traitement.
     *
     * @param string $traitement  Nom du fichier de traitement du type "/layout/zone/zone_db.php"
     * @param string $sql         Chaine SQL de sélection des valeurs de la Pelican_Index_Frontoffice_Zone
     * @param mixed  $this->aBind Paramètres Bind de la requête
     * @param mixed  $aLob        Liste des champs CLOB de la requête
     */
    protected function _initZone($module = "", $zone_template_id, $sql = "", $aBind = array(), $aLob = array())
    {
        $oConnection = Pelican_Db::getInstance();

        if ($_REQUEST["id"] && $_REQUEST["id"] != Pelican::$config["DATABASE_INSERT_ID"] && $sql) {
            $this->values = $oConnection->queryForm($sql, $aBind, $aLob);
        }

        $this->moduleList[$zone_template_id] = $module;
    }

    protected function _getZoneValues($page, $data, $module)
    {
        $this->aBind = array();
        $this->aBind[":PAGE_ID"] = $page["PAGE_ID"];
        $this->aBind[":PAGE_VERSION"] = $page["PAGE_DRAFT_VERSION"];
        $this->aBind[":LANGUE_ID"] = $_SESSION[APP]['LANGUE_ID'];
        $this->aBind[":ZONE_TEMPLATE_ID"] = $data["ZONE_TEMPLATE_ID"];

        if ($this->aNewZone) {
            if ($this->aNewZone[$data["ZONE_TEMPLATE_ID"]]) {
                $this->aBind[":ZONE_TEMPLATE_ID"] = $this->aNewZone[$data["ZONE_TEMPLATE_ID"]];
            }
        }

        $sSQLZone = "
				SELECT *
				FROM #pref#_page_zone
				WHERE ZONE_TEMPLATE_ID =:ZONE_TEMPLATE_ID
				AND PAGE_ID =:PAGE_ID
				AND PAGE_VERSION = :PAGE_VERSION
				AND LANGUE_ID = :LANGUE_ID";

        $this->_initZone($module, $data["ZONE_TEMPLATE_ID"], $sSQLZone, $this->aBind, array(
            "ZONE_TEXTE",
            "ZONE_TEXTE2",
        ));
        if ($data) {
            $this->values += $data;
        }

        return $this->values;
    }

    public function _getZoneContentValues()
    {
        $oConnection = Pelican_Db::getInstance();
        $aSelectedValues = array();
        $this->aBind = array();

        if ($this->values) {
            $this->aBind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
            $this->aBind[":LANGUE_ID"] = $_SESSION[APP]['LANGUE_ID'];
            $this->aBind[":PAGE_ID"] = $this->id;
            $this->aBind[":PAGE_VERSION"] = $this->values["PAGE_VERSION"];
            $this->aBind[":ZONE_TEMPLATE_ID"] = $this->values["ZONE_TEMPLATE_ID"];

            $strSqlAssoc = "SELECT
					cv.CONTENT_ID AS id,
					".$oConnection->getNVLClause("cv.CONTENT_TITLE_BO", "cv.CONTENT_TITLE")." AS lib
					FROM
					#pref#_content c,
					#pref#_content_version cv,
					#pref#_page_zone_content pzc";
            $strSqlAssoc .= "
					WHERE
					c.CONTENT_ID = cv.CONTENT_ID
					AND c.LANGUE_ID=cv.LANGUE_ID
					AND c.CONTENT_DRAFT_VERSION=cv.CONTENT_VERSION
					and c.SITE_ID=:SITE_ID
					AND cv.LANGUE_ID=:LANGUE_ID
					AND c.CONTENT_ID=pzc.CONTENT_ID
					AND pzc.PAGE_ID=:PAGE_ID
					AND pzc.ZONE_TEMPLATE_ID=:ZONE_TEMPLATE_ID
					AND pzc.PAGE_VERSION=:PAGE_VERSION";
            $this->oForm
                ->_getValuesFromSQL($strSqlAssoc, $aSelectedValues, $this->aBind);
        }

        return $aSelectedValues;
    }

    /**
     * Tag de début d'un toggle (version speciale de la fonction toogle de Pelican_Form class).
     *
     * @return string
     *
     * @param string  $id            Identifiant du toggle
     * @param string  $label         Libellé du toggle
     * @param string  $state         Etat du toggle ("" pour l'ouvrir, "none" pour le masquer) : "" par défaut
     * @param string  $width         Largeur du toggle : "90%" par défaut
     * @param boolean $bDirectOutput true pour un affichage direct, false pour que les méthodes retournent le code Pelican_Html sous forme de texte
     */
    protected function _beginZone($id, $label, $nbrzone, $zone_id, $closed = true, $setCookie = true, $bDirectOutput = true, $deleteToggle = "", $program = "", $empty = false, $deleteZone = false)
    {
        if (valueExists($_GET, "readO")) {
            $closed = false;
        }

        if ($closed) {
            $state = "none";
        }
        if ($state == "none") {
            $image = "close";
            $alt = t('AFFICHER');
        } else {
            $image = "open";
            $alt = t('MASQUER');
        }

        $strSetCookie = "false";
        if ($setCookie) {
            $strSetCookie = "true";
        }

        $strTemp = "<tr onclick=\"showHideZone('".$id."', ".$strSetCookie.",".$nbrzone.")\">";

        $strTemp .= "<td class=\"zonetype".$zone_id."\" valign=\"middle\" style=\"cursor:pointer\">";

        if ($deleteZone && !Cms_Page_Ndp::isTranslator()) {
            $strTemp .= Pelican_Html::img(array(
                    id => 'deleteZone'.$this->multi,
                    src => Pelican::$config['LIB_PATH']."/public/images/toggle_zone_delete.gif",
                    alt => t('DELETE'),
                    align => "right",
                    hspace => "3",
                    width => "17",
                    height => "17",
                    border => "0",
                    style => "cursor:pointer;",
                    onclick => "deleteZone(this)",
            ));
        }
        $strTemp .= Pelican_Html::img(array(
                id => "togglezone".$id,
                src => Pelican::$config['LIB_PATH']."/public/images/toggle_zone_".$image.".gif",
                alt => $alt,
                align => "right",
                hspace => "3",
                width => "17",
                height => "17",
                border => "0",
                style => "cursor:pointer;"
        ));

        $strTemp .= "&nbsp;<a name=\"anchor".$id."\"></a>". $label;
        // $strTemp .= "</td><td class=\"formtogglezone\" align=\"right\">";
        if ($deleteToggle && !$_GET["readO"]) {
            $strTemp .= "&nbsp;&nbsp;(<input type=\"Checkbox\" value=".$id." name=\"".$deleteToggle."\" ".($empty ? "checked=\"checked\"" : "").">Vide)";
        }
        if ($program) {
            $strTemp .= " ".Pelican_Html::img(array(
                    src => Pelican::$config["IMAGE_PATH"]."/prog.gif",
                    alt => t('SCHEDULING_BLOC'),
                    border => "0",
                    valign => "middle",
            ));
        }
        $strTemp .= "</td></tr>\n";
        $strTemp .= "<tr><td class=\"tdtogglezone\" id=\"Divtogglezone".$id."\" style=\"display:".$state."\" colspan=\"2\">";

        return $strTemp;
    }

    /**
     * Tag de fin d'un toggle.
     *
     * @return string
     *
     * @param string  $id            Identifiant du Toggle
     * @param boolean $bDirectOutput true pour un affichage direct, false pour que les méthodes retournent le code Pelican_Html sous forme de texte
     */
    protected function _endZone()
    {
        $strTemp = "</td></tr>\n";

        return $strTemp;
    }

    /**
     * Fonction de suppression d'une page.
     *
     * @param string $version Définition d'une version uniquement à supprimer (sinon toutes les versions sont traitées)
     */
    protected function _deletePage($version = "")
    {
        $oConnection = Pelican_Db::getInstance();

        $SEQUENCE = array(
            "#pref#_navigation",
            "#pref#_page_version_content",
            "#pref#_page_multi_zone_content",
            "#pref#_page_multi_zone_media",
            "#pref#_page_multi_zone",
            "#pref#_page_zone_content",
            "#pref#_page_zone_media",
            "#pref#_page_zone",
            "#pref#_page_version_media",
            "#pref#_page_version",
        );

        $aStopList[] = "#pref#_page_ID";
        $aStopList[] = "ZONE_TEMPLATE_ID";
        $aStopList[] = "MEDIA_ID";
        $aStopList[] = "NAVIGATION_ID";
        $aStopList[] = "MEDIA_FORMAT_ID";
        $aStopList[] = "CONTENT_ID";
        $aStopList[] = "CONTENT_VERSION";
        $aStopList[] = "COLOR_ID";
        $aStopList[] = "CONTENT_PAGE_TYPE";
        $aStopList[] = "CONTENT_PAGE_NUMBER";
        $aStopList[] = "PAGE_MEDIA_TYPE";
        $aStopList[] = "SECTION_ID";
        $aStopList[] = "AREA_ID";    // psa_page_multi_zone
        $aStopList[] = "ZONE_ORDER"; // psa_page_multi_zone

        if (!$version) {
            /* on supprime toutes les versions */
            $aStopList[] = "PAGE_VERSION";
            $aStopList[] = 'LANGUE_ID';

            self::cleanPageOrder(Pelican_Db::$values["PAGE_ID"], 1);
        }

        if ($version) {
            Pelican_Db::$values["PAGE_VERSION"] = $version;
        }
        foreach ($SEQUENCE as $table) {
            $oConnection->deleteQuery($table, $aStopList);
        }
    }

    protected function _getTreeParams($tree)
    {
        $return["id"] = $tree->id;
        $return["pid"] = $tree->pid;
        $return["lib"] = str_replace(" ", "&nbsp;", str_repeat("&nbsp;&nbsp;", ($tree->level - 2)).$tree->lib);
        $return["order"] = $tree->order;

        return $return;
    }
    /*
     * Changement d'etat
     * @param $pageId
     * @param $state
     */

    protected function _updatePageMoreAndChlid($pageId, $state)
    {
        if (!empty($pageId)) {
            //mise à jour des contenus de la page racine $pageId
            $test = Pelican_Request::call('/_/Administration_Directory_Corbeille/updateContentState', array($pageId, $state));

            //mise à jour des pages enfants de $pageId et des contenus
            Pelican_Request::call('/_/Administration_Directory_Corbeille/updateChildPage', array($pageId, $state));
        }
    }
    
    
   

    public function sendMailDiffusion($siteId, $aTitlePages)
    {
        $siteLabel = $this->getLabelSiteBySiteId($siteId);
        $objet = Pelican::$config['SITE_NAME'].' '.$siteLabel['SITE_LABEL'].' information: broadcasting of content: '.$aTitlePages.' is going on line.';

        $body = 'The content: '.$aTitlePages.' has been setted on line.';
        $body .= '<br/>';
        $body .= '<br/>';
        $body .= '-----------------------------------';
        $body .= '<br/>';
        $body .= '<br/>';
        $body .= 'Le contenu: '.$aTitlePages.' a été mis en ligne.';
        $body .= '<br/>';
        $body .= '<br/>';

        $oMail = new Pelican_Mail();
        $oMail->setSubject(utf8_decode($objet));
        $oMail->setBodyHtml(utf8_decode($body));
        $oMail->setFrom(Pelican::$config ['EMAIL'] ['WEBMASTEUR_CENTRAL']);
        foreach ($this->getMailWebmasteurBysiteId($siteId) as $to) {
            $oMail->addTo($to);
        }
        $oMail->send();
    }

    public function getMailWebmasteurBysiteId($siteId)
    {
        $oConnection = Pelican_Db::getInstance();
        $this->aBind[':SITE_ID'] = $siteId;

        $sql = 'SELECT  SITE_MAIL_WEBMASTER
   				   FROM #pref#_site
   				   WHERE `SITE_ID` 	= :SITE_ID';
        $aMailSite = $oConnection->queryRow($sql, $this->aBind);
        if (is_array($aMailSite) && !empty($aMailSite)) {
            return $aMailSite;
        }

        return false;
    }

    public function getLabelSiteBySiteId($siteId)
    {
        $oConnection = Pelican_Db::getInstance();
        $this->aBind[':SITE_ID'] = $siteId;

        $sql = 'SELECT SITE_LABEL
   				   FROM #pref#_site
   				   WHERE `SITE_ID` 	= :SITE_ID';
        $labelSite = $oConnection->queryRow($sql, $this->aBind);
        if (is_array($labelSite) && !empty($labelSite)) {
            return $labelSite;
        }

        return false;
    }
    /*
     * Méthode générant la zone
     * @param $form : Form
     * @param $indexTab : identifiant du tab
     * @param $multiId : chaine du multi
     * @param $sZonePath : Path de la zone
     * @param $bGeneral : true si on se trouve sur la zone générale
     * @param $iPid : Pid de la page
     * @param $iTpid : Id du template de la page
     */

    public function getZone(&$form, $indexTab, $multiId, $sZonePath, $bGeneral, $iPid, $iTpid, $iTypeExpand, $aAdditionalData = null)
    {
        $form .= '<div>';
        $form .= $this->oForm->beginFormTable();

        $form .= $this->oForm->createHidden($this->multi."ORDER", $indexTab);

        if ($bGeneral == false) {
            if (empty($this->zoneValues)) {
                $this->zoneValues['ZONE_BO_PATH'] = $sZonePath;
            }
        }

        if ($bGeneral == false) {
            $form .= $this->oForm->createHr();
            $this->getControllerZone($form, $sZonePath, $aAdditionalData);
        } else {
            $this->getGeneralZone($form, $iPid, $iTpid, $iTypeExpand);
        }

        // si la verif est activee (CheckBox mobile/ web)
        // helper backend getFormAffichage
        if ($this->zoneValues['VERIF_JS'] == 1) {
            $this->oForm->_sJS .= "}\n";
        }

        $form .= $this->oForm->endFormTable();
        $form .= '</div>';
    }

    public function getCountChildPages($iPageParentId)
    {
        $oConnection = Pelican_Db::getInstance();
        $sSQL = "SELECT MAX(DISTINCT p.PAGE_ID) as count_children
                FROM #pref#_page p
                LEFT JOIN #pref#_page_version pv
                    ON (
                    p.PAGE_ID = pv.PAGE_ID
                    AND p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION
                )
                WHERE SITE_ID = :SITE_ID
                AND p.PAGE_PARENT_ID = :PAGE_PARENT_ID
                AND (pv.STATE_ID <> 5)
                GROUP BY p.PAGE_PARENT_ID
                ";
        $aBind = array(
            ':SITE_ID' => (int) $_SESSION[APP]['SITE_ID'],
            ':PAGE_PARENT_ID' => (int) $iPageParentId,
        );

        return $oConnection->queryRow($sSQL, $aBind);
    }
    /*
     * Méthode générant la zone classique
     * @param $form : Form
     * @param $sZonePath : Path de la zone
     */

    public function getControllerZone(&$form, $sZonePath, $aAdditionalData = null)
    {
        $module = Pelican::$config['APPLICATION_CONTROLLERS'].'/'.str_replace("_", "/", $sZonePath).".php";
        $moduleClass = $sZonePath;

        if (!file_exists($module)) {
            $form .= $this->oForm
                ->createFreeHtml("<span class=\"erreur\">".$module." => ".t('A_FAIRE')."</span>");
        } else {
            include_once $module;
            $tmp = call_user_func_array(array(
                $moduleClass,
                'render',
                ), array(
                $this,
                $aAdditionalData,
                )
            );

            $form .= $this->oForm
                ->createFreeHtml($tmp);
        }
    }
    /*
     * Méthode générant la zone générale
     * @param $form : Form
     * @param $iPid : Pid de la page
     * @param $iTpid : Id du template de la page
     * @param $iTypeExpand : Type de l'expan
     */

    public function getGeneralZone(&$form, $iPid, $iTpid, $iTypeExpand)
    {
        $oConnection = Pelican_Db::getInstance();
        $sSQL = "SELECT
                    PAGE_PATH
                FROM
                    #pref#_page
                WHERE PAGE_ID = :PAGE_ID
        ";
        $this->aBind[':PAGE_ID'] = (int) $iPid;
        $sPagePath = $oConnection->queryItem($sSQL, $this->aBind);

        if (substr_count($sPagePath, "#") && substr_count($sPagePath, "#") == 1 && $iTypeExpand == '0') {
            $form .= $this->oForm->createMultiHmvc($this->multi."PUSH", t('PUSH'), array('path' => dirname(__FILE__).'/Page.php', 'class' => 'Cms_Page_Controller', 'method' => 'multiPush'), $this->values['PUSH'], "PUSH", $this->readO, 3, true, true, "PUSH", "values", "multi", "2", "", "");
        }
        if (substr_count($sPagePath, "#") && substr_count($sPagePath, "#") == 2) {
            if ($iTpid != 257) {
                $form .= $this->oForm->createMultiHmvc($this->multi."PUSH_OUTILS_MAJEUR", t('PUSH_OUTILS_MAJEUR'), array('path' => dirname(__FILE__).'/Page.php', 'class' => 'Cms_Page_Controller', 'method' => 'multiCTA'), $this->values['PUSH_OUTILS_MAJEUR'], "PUSH_OUTILS_MAJEUR", $this->readO, 1, true, true, "PUSH_OUTILS_MAJEUR");
                $form .= $this->oForm->createMultiHmvc($this->multi."PUSH_OUTILS_MINEUR", t('PUSH_OUTILS_MIINEUR'), array('path' => dirname(__FILE__).'/Page.php', 'class' => 'Cms_Page_Controller', 'method' => 'multiCTA'), $this->values['PUSH_OUTILS_MINEUR'], "PUSH_OUTILS_MINEUR", $this->readO, 4, true, true, "PUSH_OUTILS_MINEUR");
                $form .= $this->oForm->createMultiHmvc($this->multi."PUSH_CONTENU_ANNEXE", t('PUSH_CONTENU_ANNEXE'), array('path' => dirname(__FILE__).'/Page.php', 'class' => 'Cms_Page_Controller', 'method' => 'multiPush'), $this->values['PUSH_CONTENU_ANNEXE'], "PUSH_CONTENU_ANNEXE", $this->readO, 2, true, true, "PUSH_CONTENU_ANNEXE", "values", "multi", "2", "", "");
            }
        }
    }

    //Nettoyage de values de toutes donnees du multi passe en parametre a l'exception du tableau cree par readmulti
    private static function _clean($strName)
    {
        if ($strName != '' && count(Pelican_Db::$values) > 0 && is_array(Pelican_Db::$values)) {
            foreach (Pelican_Db::$values as $key => $value) {
                if ($key != '') {
                    if (strpos($key, $strName) !== false && !is_array($value)) {
                        unset(Pelican_Db::$values[$key]);
                    }
                }
            }
        }
    }

    // auto calculate page priority instead of level into tree
    // exp: second level tree will be (1 - (2/10)) = 0.8
    private function getPagePriority(){

        return (1 - (count(explode('/', $_SESSION[APP]['CURRENT_PAGE_PATH'])) / 10));

    }
}
