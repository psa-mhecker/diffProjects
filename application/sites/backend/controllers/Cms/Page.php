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
 * - Ecrire le template comme un template normal ensuite : l'objet Formulaire s'appelle $this->oForm
 *
 * Valeurs par défaut :
 * - si une Pelican_Index_Frontoffice_Zone contient une valeur par défaut, le définir au tout début de la Pelican_Index_Frontoffice_Zone : setDefaultValue("champ", "valeur")
 * - si on est en update, c'est la valeur retrounée qui est utilisée sinon c'est la valeur par défaut
 *
 * @package Pelican_BackOffice
 * @subpackage Page
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @author Patrick Deroubaix <pderoubaix@businessdecision.com>
 * @since 31/05/2004
 */

include_once (dirname(__FILE__) . '/../Cms.php');
include_once (dirname(__FILE__) . '/Page/Module.php');
include_once (dirname(__FILE__) . '/Page/Module/Navigation/1level.php');
require_once(Pelican::$config['LIB_ROOT'].'/Pelican/Mail.php');

use Citroen\Perso\Synchronizer;

class Cms_Page_Controller extends Cms_Controller
{

    public $monoValues;

    public $zoneValues = array();

    protected $form_name = "page";

    protected $field_id = "PAGE_ID";

    protected $clearurlId = 'pid';

    protected $cybertag = array(
        "pid" ,
        "page"
    );

    protected $workflowField = "PAGE";

    protected $userAllowed = true;

    protected $generalPage = false;

    // Liste des pid des pages filles mises à jour lors de la suppression de leur page parente
    protected $trashUpdatedPages = array();

    protected $decacheBack = array(
        array(
            "Backend/Page" ,
            array(
                'SITE_ID' ,
                'LANGUE_ID'
            )
        ) ,
        array(
            "backend/page_par_niveau_php" ,
            'SITE_ID'
        ) ,
        array(
            "Template/Page" ,
            'SITE_ID'
        ) ,
        array(
            "Frontend/Citroen/GalerieNiveau2" ,
            'SITE_ID'
        ),
        array(
            "Frontend/Citroen/ContenusRecommandes" ,
            'SITE_ID'
        ),
        array(
            "Frontend/Citroen/Actualites/Detail",
            "SITE_ID"
        ),
        array(
            "Frontend/Citroen/Actualites/PageClearUrlByActu",
            "SITE_ID"
        ),
        array(
            "Frontend/Citroen/Actualites/Pager",
            "SITE_ID"
        ),
        array(
            "Frontend/Citroen/Actualites/Liste",
            "SITE_ID"
        ),
        array(
            "Frontend/Citroen/Home/Actualites",
            "SITE_ID"
        ),
        array(
            "Frontend/Citroen/VehiculeShowroomById",
            "SITE_ID"
        ),
        array(
            "Frontend/Citroen/Promotion",
            "SITE_ID"
        ),
        array(
            "Frontend/Citroen/MultiPromotion",
            "PAGE_ID"
        ),
        array(
            "Frontend/Citroen/OtherPromotions",
            "SITE_ID"
        )
    );

    protected $decachePublication = array(
        array(
            "Frontend/Page/Path" ,
            "PAGE_ID"
        ) ,
        array(
            "Frontend/Page/Heritable" ,
            "heritable"
        ) ,
        array(
            "Frontend/Site/Tree" ,
            'SITE_ID'
        ) ,
        array(
            "Frontend/Navigation" ,
            "PAGE_ID"
        ) ,
        array(
            "Frontend/Page" ,
            "PAGE_ID"
        ) ,
        array(
            "Frontend/Page/Url" ,
            "PAGE_ID"
        ) ,
        array(
            "Frontend/Page/Media" ,
            "PAGE_ID"
        ) ,
        array(
            "Frontend/Page/Navigation" ,
            "PAGE_ID"
        ) ,
        array(
            "Frontend/Page/Zone" ,
            "PAGE_ID"
        ) ,
        array(
            "Portal/Page/Zone" ,
            "PAGE_ID"
        ) ,
        array(
            "Frontend/Page/ZoneTemplate" ,
            "PAGE_ID"
        ) ,
        array(
            "Frontend/Page/ZoneTemplateId" ,
            "PAGE_ID"
        ) ,
        array(
            "Frontend/Page/ZoneMulti" ,
            "PAGE_ID"
        ) ,
        array(
            "Frontend/Page/ChildContent" ,
            "PAGE_ID"
        ) ,
        array(
            "Frontend/Content/Page" ,
            "PAGE_ID"
        ) ,
        array(
            "Frontend/Page/Template" ,
            'SITE_ID'
        ) ,
        array(
            "Frontend/Page/ZoneTemplateIdPage" ,
            'SITE_ID'
        ) ,
        array(
            "Frontend/Navigation" ,
            "PAGE_ID"
        ) ,
        array(
            "Frontend/Url" ,
            'SITE_ID'
        ) ,
        array(
            "Frontend/Citroen/GalerieNiveau2" ,
            'SITE_ID'
        ) ,
        array(
            "Frontend/Citroen/Navigation",
            'SITE_ID'
        ),
        array(
            "Frontend/Citroen/ZoneMulti" ,
            "PAGE_ID"
        ) ,
        array(
            "Frontend/Citroen/StickyBar"
        ),
        array(
            "Frontend/Citroen/FilAriane"
        ),
        array(
            "Frontend/Citroen/ConceptCars/Galerie",
            "SITE_ID"
        ),
        array(
            "Frontend/Citroen/CarSelector/Resultats",
            "SITE_ID"
        ),
        array(
            "Frontend/Citroen/Overview",
            "SITE_ID"
        ),
        array(
            "Frontend/Citroen/Actualites/Detail",
            "SITE_ID"
        ),
        array(
            "Frontend/Citroen/Actualites/PageClearUrlByActu",
            "SITE_ID"
        ),
        array(
            "Frontend/Citroen/Actualites/Pager",
            "SITE_ID"
        ),
        array(
            "Frontend/Citroen/Actualites/Liste",
            "SITE_ID"
        ),
        array(
            "Frontend/Citroen/Home/Actualites",
            "SITE_ID"
        ),
        array(
            "Frontend/Citroen/VehiculeShowroomById",
            "SITE_ID"
        ),
        array(
            "Frontend/Citroen/MonProjet/Menu",
            array("SITE_ID", "LANGUE_ID")
        ),
        array(
            "Frontend/Citroen/ZoneTemplate"
        ),
        array(
            "Frontend/Citroen/PageByMultiZone"
        ),
        array(
            "Frontend/Citroen/Promotion",
            "SITE_ID"
        ),
        array(
            "Frontend/Citroen/MultiPromotion",
            "PAGE_ID"
        ),
        array(
            "Frontend/Citroen/OtherPromotions",
            "SITE_ID"
        )
    );

    protected function _initValues ()
    {

    }

    public function init ()
    {
        if (! empty($_SESSION[APP]['PAGE_RETURN'])) {
            $tmp = $_SESSION[APP]['PAGE_RETURN'];
            unset($_SESSION[APP]['PAGE_RETURN']);
            unset($_REQUEST);
            unset($_POST);
            unset($_GET);
            echo ($tmp);
        }
    }

    protected function setEditModel ()
    {
        $oConnection = Pelican_Db::getInstance();
        $this->aBind[":PAGE_ID"] = $this->id;
        $this->aBind[":LANGUE_ID"] = $_SESSION[APP][LANGUE_ID];

        $pagePlanifiee = $oConnection->queryRow("select count(PAGE_ID) AS NB_PAGE from #pref#_page p WHERE PAGE_SCHEDULE_VERSION IS NOT NULL AND  PAGE_ID = :PAGE_ID AND LANGUE_ID = :LANGUE_ID", $this->aBind);

        // On supprime la session de planification suite au passage du batch
        if($pagePlanifiee['NB_PAGE'] != 1){
            unset($_SESSION[APP][$this->id][$_SESSION[APP][LANGUE_ID]]['SCHEDULE']);
        }

        $statusPage = 'DRAFT';

        if($_SESSION[APP][$this->id][$_SESSION[APP][LANGUE_ID]]['SCHEDULE']){
            $statusPage = 'SCHEDULE';
        }

        $this->editModel = "select
			p.*,
			pv.*,
			pt.*,
			" . $oConnection->dateSqlToString("PAGE_VERSION_CREATION_DATE") . " as PAGE_VERSION_CREATION_DATE,
			" . $oConnection->dateSqlToString("PAGE_PUBLICATION_DATE") . " as PAGE_PUBLICATION_DATE,
			" . $oConnection->dateSqlToString("PAGE_CREATION_DATE") . " as PAGE_CREATION_DATE,
			" . $oConnection->dateSqlToString("PAGE_START_DATE ", true) . " as PAGE_START_DATE,
			" . $oConnection->dateSqlToString("PAGE_END_DATE ", true) . " as PAGE_END_DATE,
                        " . $oConnection->dateSqlToString("PAGE_START_DATE_SCHEDULE ", true) . " as PAGE_START_DATE_SCHEDULE,
                        " . $oConnection->dateSqlToString("PAGE_END_DATE_SCHEDULE ", true) . " as PAGE_END_DATE_SCHEDULE
			from
			#pref#_page p
			INNER JOIN #pref#_page_version pv on (p.PAGE_ID = pv.PAGE_ID AND pv.PAGE_VERSION = PAGE_{$statusPage}_VERSION AND p.LANGUE_ID = pv.LANGUE_ID)
			INNER JOIN #pref#_template_page tp on (pv.TEMPLATE_PAGE_ID=tp.TEMPLATE_PAGE_ID)
			INNER JOIN #pref#_page_type pt on (tp.PAGE_TYPE_ID=pt.PAGE_TYPE_ID)
			where
			pv.LANGUE_ID = :LANGUE_ID
			and p.PAGE_ID= :PAGE_ID
			and p.SITE_ID = :SITE_ID";
    }

    public function editAction ()
    {
        if ($this->form_name == 'page' && $_GET["toprefresh"]) {
            echo("<script>top.location.href=top.location.href;</script>");
            die();
        }

        $oConnection = Pelican_Db::getInstance();

        $head = $this->getView()
            ->getHead();

        $head->endJs('/js/zoning.js');
        //$head->setJs('/js/ssm.js');

        if (! valueExists($_GET, "sid")) {
            $_SESSION[APP]["session_start_page"] = $_SERVER["REQUEST_URI"];
        }

        /** contrôle des pages filles */
        if ($this->id) {
            $child = $oConnection->queryItem("select count(*) from #pref#_page where PAGE_PARENT_ID=" . $this->id . " OR (PAGE_ID=" . $this->id . " AND PAGE_PARENT_ID IS NULL)");
        }
        if ($this->id != Pelican::$config["DATABASE_INSERT_ID"]) {
            if (! $_SESSION[APP]["user"]["main"]) {
                $this->aBind[":PAGE_ID"] = $this->id;
                $this->aBind[":SITE_ID"] = ($this->values['SITE_ID'] ? $this->values['SITE_ID'] : $_SESSION[APP]['SITE_ID']);
                $user = $oConnection->queryItem("select PAGE_CREATION_USER from #pref#_page where SITE_ID=:SITE_ID and PAGE_ID=:PAGE_ID", $this->aBind);
                if (strpos($user, '#' . $_SESSION[APP]["user"]["id"] . '#') === false) {
                    $this->userAllowed = false;
                }
            }
        }

        /**
         * * Récupération des infos de la page
         */
        $this->aBind[":LANGUE_ID"] = ($this->values['LANGUE_ID'] ? $this->values['LANGUE_ID'] : $_SESSION[APP]['LANGUE_ID']);
        $this->aBind[":PAGE_ID"] = $this->id;
        $this->aBind[":SITE_ID"] = ($this->values['SITE_ID'] ? $this->values['SITE_ID'] : $_SESSION[APP]['SITE_ID']);
        if ($this->userAllowed) {
            parent::editAction();
            // controle de variable de session
            if (empty($this->values) && $this->id != -2) {
                $site = $oConnection->queryItem("select SITE_ID from #pref#_page where PAGE_ID = :PAGE_ID and SITE_ID != :SITE_ID", $this->aBind);
                if ($site != $_SESSION[APP]['SITE_ID']) {
                    echo ('<div style="background-color:red;color:white;text-align:center;font-size:25px;"><br /><br />'.t('ATTENTION_AUTRE_NAVIGATUER_OUVERT_SUR_AUTRE_SITE').'<br /><br />'.t('VEULLIEZ_RAFRAICHIR_PAGE').'<br /><br /><br /><br /></div>');
                    die();
                }

            }

            $form = '<table width="100%" border="0">';
            if ( !$child && isset($_GET['readO']) && ($_GET['readO'] == true)) {
                $form .= '<tr><td style=\"width:30px\" valign=\"top\">&nbsp;</td><td class="erreur" style="width: 95%;">' . t('DELETE_PAGE_ALERT') . '</td></tr>';
            }
            if (Pelican::$config["MODE_ZONE_VIEW"] == 'top') {
                $form .= ("<tr><td valign=\"top\">");
            } else {
                $form .= ("<tr><td  style=\"width:30px\" valign=\"top\">&nbsp;</td><td valign=\"top\">");
            }

            $this->oForm = Pelican_Factory::getInstance('Form', true);
            $this->oForm->bDirectOutput = false;

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


            if ($this->id == Pelican::$config["DATABASE_INSERT_ID"] || ! $this->values["PAGE_PARENT_ID"]) {
                $this->values["PAGE_DISPLAY_PLAN"] = 1;
                $this->values["PAGE_DISPLAY"] = 1;
                $this->values["PAGE_DISPLAY_NAV"] = 1;
                $this->values["PAGE_DISPLAY_NAV_MOBILE"] = 1;
                $this->values["PAGE_DISPLAY_SEARCH"] = 1;
                $this->values["PAGE_DISPLAY_IN_ARIANE"] = 1;
            }

            if (! valueExists($this->values, "PAGE_PATH")) {
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
            if (! $this->generalPage) {
                $form .= $this->oForm
                    ->setTab("3", t('PUBLICATION'));
            }
            $form .= $this->oForm
                ->setTab("2", t('SEO'));
            if (! $this->generalPage) {
                $form .= $this->oForm
                    ->setTab("4", t('PLANIFICATION'));
            }
            if (! $this->generalPage) {
                $form .= $this->oForm
                    ->beginFormTable();

                if ($this->id != - 2) {

                    $form .= $this->oForm
                        ->createLabel("pid", $this->id);
                    $site = Pelican_Cache::fetch("Frontend/Site", $_SESSION[APP]['SITE_ID']);
                    $url = $site["SITE_URL"];
                    $protocole = 'http://';
                    $urlBO = ($_SERVER['HTTP_CLIENT_HOST']!='')?$_SERVER['HTTP_CLIENT_HOST']:$_SERVER['SERVER_NAME'];
                    if ($site['DNS'][$urlBO]) {
                        if ($site['DNS'][$urlBO]['SITE_DNS_BO']) {
                            $url = $site['DNS'][$urlBO]['SITE_DNS_BO'];
                        }
                        if ($site['DNS'][$urlBO]['SITE_DNS_HTTP']) {
                            $protocole = ($site['DNS'][$urlBO]['SITE_DNS_HTTP'] == 'https')?'https://':'http://';
                        }
                    }
                    $aPageType = Pelican_Cache::fetch("PageType/Template", array(
                        $this->values['TEMPLATE_PAGE_ID']
                    ));
                    if ($aPageType['PAGE_TYPE_SHORTCUT']) {
                        $shortcut = str_replace('//', '/', '/' . $aPageType['PAGE_TYPE_SHORTCUT']);
                        $form .= $this->oForm
                            ->createLabel(t('SHORT_ADDRESS'), Pelican_Html::a(array(
                                href => $protocole . $url . $shortcut ,
                                target => "_blank"
                            ), $shortcut));
                    }
                    $form .= $this->oForm
                        ->createLabel(t('LONG_ADDRESS'), Pelican_Html::a(array(
                            href => $protocole . $url . $this->values["PAGE_CLEAR_URL"] ,
                            target => "_blank"
                        ), $this->values["PAGE_CLEAR_URL"]));

                }

                // recuperation de l'url réelle courante
                $form .= $this->oForm->createHidden("OLD_URL", $this->values["PAGE_CLEAR_URL"]);

                $aTemplate = getComboValuesFromCache("Template/Page", array(
                    $_SESSION[APP]["SITE_ID"] ,
                    "" ,
                    "" ,
                    ($this->values['PAGE_TYPE_ID'] ? $this->values['PAGE_TYPE_ID'] : - 1) ,
                    $_SESSION[APP]['LANGUE_ID'],
                    Pelican:: $config["SITE_MASTER"]
                ));

                foreach( $aTemplate as $key => $template ){
                    $aTemplate[$key]    =   t($template);
                }
                $form .= $this->oForm
                    ->createComboFromList("TEMPLATE_PAGE_ID", t('LAYOUT'), $aTemplate, ($_GET["gid"] ? $_GET["gid"] : $this->values["TEMPLATE_PAGE_ID"]), true, $this->readO, "1", false, "", true, false, "onchange=\"changeGabarit(this);\"");
                $form .= $this->oForm->createHidden("PAGE_PERSO", $this->values['PAGE_PERSO']);
                $form .= $this->oForm->createHidden("PROFILE_LIST", $this->values['PROFILE_LIST']);
                if (valueExists($_GET, "gid") || valueExists($this->values, "TEMPLATE_PAGE_ID")) {
                    $form .= $this->oForm
                        ->createTextArea("PAGE_TITLE", t('LONG_TITLE'), true, $this->values["PAGE_TITLE"], 255, $this->readO, 2, 75, false, "", false);
                    $form .= $this->oForm
                        ->createInput("PAGE_TITLE_BO", t('SHORT_TITLE'), 255, "", false, $this->values["PAGE_TITLE_BO"], $this->readO, 70);
                    $form .= $this->oForm
                        ->createHidden("PAGE_OLD_TITLE", $this->values["PAGE_TITLE_BO"]);
                    $form .= $this->oForm
                        ->createHidden("PAGE_OLD_CLEAR_URL", '');
                    if(!empty($_SESSION[APP][$this->id][$_SESSION[APP][LANGUE_ID]]['SCHEDULE'])){
                        $schedule = $_SESSION[APP][$this->id][$_SESSION[APP][LANGUE_ID]]['SCHEDULE'];
                    }
                    $form .= $this->oForm->createJS("
                                                var schedule = '" .$schedule . "';
                                                if(document.getElementById('form_schedule').value == 1){
                                                    schedule = true;
                                                }
						if(obj.PAGE_TITLE.value.length > 70){
							alert('".t('ALERT_PAGE_TITLE_LONG_MAX', 'js')."');
						}
						if(obj.PAGE_TITLE_BO.value != obj.PAGE_OLD_TITLE.value && obj.PAGE_OLD_TITLE.value != ''){
							if(confirm('".t('CHANGE_CLEAR_URL', 'js')."')){
								obj.PAGE_CLEAR_URL.value = '';
                                                                if(!schedule){
                                                                    if(confirm('".t('ADD_REDIRECTION_301', 'js')."')){
                                                                            obj.PAGE_OLD_CLEAR_URL.value = '".$this->values["PAGE_CLEAR_URL"]."';
                                                                    }
                                                                }
							}
						}
					");

                }
                if($schedule || $_POST['form_schedule']){
                    $form .= $this->oForm->createJS ( "if(obj.PAGE_START_DATE_SCHEDULE.value == '' || obj.PAGE_END_DATE_SCHEDULE.value == ''){\n
                                                        alert('".t('VOUS_DEVEZ_SAISIR_UNE_DATE_DE_PLANIFICATION_DANS_ONGLET_PLANIFICATION', 'js')."');\n
                                                        return false;
                                                    }\n");
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

                $form .= $this->oForm
                    ->endFormTable();
            } else {
                $form .= $this->oForm
                    ->createHidden("PAGE_TITLE", $this->values["PAGE_TITLE"]);
                $form .= $this->oForm
                    ->createHidden("PAGE_TITLE_BO", $this->values["PAGE_TITLE_BO"]);
                unset($this->oForm->aTab["2"]);
            }
            $form .= $this->oForm->createHidden("OLD_PAGE_TITLE_BO", $this->values["PAGE_TITLE_BO"]);
            //$form .= $this->oForm->beginFormTable();



            if (! valueExists($_GET, "tpl") && valueExists($this->values, "TEMPLATE_PAGE_ID")) {
                $_GET["tpl"] = $this->values["TEMPLATE_PAGE_ID"];
            }

            $site = Pelican_Cache::fetch("Frontend/Site", $_SESSION[APP]['SITE_ID']);
            $url = $site["SITE_URL"];
            // debut si program
            /**
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

            /** changement de gabarit */
            if (valueExists($_GET, "gid")) {
                if ($tabZones) {
                    foreach ($tabZones as $layout) {
                        // @todo ????
                        if ($layout["ZONE_ID"] == 121) {
                            $layout["ZONE_ID"] = 124;
                        } elseif ($layout["ZONE_ID"] == 124) {
                            $layout["ZONE_ID"] = 121;
                        }
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
				zt.TEMPLATE_PAGE_ID = " . $_GET["gid"] . "
				order by LIGNE, COLONNE, ZONE_TEMPLATE_ORDER";
                $tabZones = $oConnection->getTab($sGabarit, $this->aBind);

                if ($tabZones) {
                    foreach ($tabZones as $layout) {
                        $this->aNewZone[$layout["ZONE_TEMPLATE_ID"]] = @array_shift($zone1[$layout["ZONE_ID"]]);
                    }
                }
            }

            /* Info activation perso */
            $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
            $sql = "
                SELECT
                    SITE_PERSO_ACTIVATION
                FROM
                    #pref#_site
                WHERE
                    SITE_ID = :SITE_ID
            ";
            $siteActivationPerso = $oConnection->queryItem($sql,$bind);
            $sql = "
                SELECT
                    ZONE_ID
                FROM
                    #pref#_site_personnalisation
                WHERE
                    SITE_ID = :SITE_ID
            ";
            $results = $oConnection->queryTab($sql,$bind);
            $zoneActivationPerso = array();
            if(is_array($results) && count($results)>0){
                foreach($results as $result){
                    $zoneActivationPerso[] =  $result['ZONE_ID'];
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
                order by PAGE_MULTI_TYPE asc, PAGE_ZONE_MULTI_ORDER asc";
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
                $form .= $this->oForm
                    ->beginTab("1");

                if($siteActivationPerso == 1){
                    $head = $this->getView()->getHead();
                    $head->setCss(Pelican::$config['MEDIA_HTTP'] . "/design/backend/css/perso.css");
                    $head->setJs(Pelican::$config['MEDIA_HTTP'] . "/design/backend/js/jquery.dialogextend.1_0_1.js");

                }

                if (! valueExists($_GET, "blc")) {
                    if ($this->generalPage || ! $this->values["PAGE_PARENT_ID"]) {
                        $form .= $this->oForm
                            ->createHidden("PAGE_VERSION", $this->values["PAGE_VERSION"]);
                        if ($this->generalPage) {
                            $form .= $this->oForm
                                ->createHidden("TEMPLATE_PAGE_ID", $this->values["TEMPLATE_PAGE_ID"]);
                        }
                        $form .= $this->oForm
                            ->createHidden("PAGE_DISPLAY_PLAN", ($this->values["PAGE_GENERAL"] ? 0 : 1));
                        $form .= $this->oForm
                            ->createHidden("PAGE_DISPLAY", 1);
                        $form .= $this->oForm
                            ->createHidden("PAGE_DISPLAY_NAV", ($this->values["PAGE_GENERAL"] ? 0 : 1));
                        $form .= $this->oForm
                            ->createHidden("PAGE_DISPLAY_NAV_MOBILE", ($this->values["PAGE_GENERAL"] ? 0 : 1));
                        $form .= $this->oForm
                            ->createHidden("PAGE_DISPLAY_SEARCH", ($this->values["PAGE_GENERAL"] ? 0 : 1));
                        $form .= $this->oForm
                            ->createHidden("PAGE_DISPLAY_SEARCH_SAUVE", ($this->values["PAGE_GENERAL"] ? 0 : 1));

                        // evol 2862
                        $form .= $this->oForm
                            ->createFreeHtml($this->_beginZone("0", "<b>" . t('GLOBAL_PAGE') . "</b>", 0, "1", (! $this->readO && $this->id != - 2), true, true, '','',false,false,$persoGlobal));
                        $form .= $this->oForm
                            ->beginFormTable("0", "0", "form", true, "tabletogglezone" . $pageI);
                        $form .= $this->oForm
                            ->createTextArea("PAGE_TEXT", t('CHAPO'), false, $this->values["PAGE_TEXT"], 16000, $this->readO, 5, 70, false, "", false);
                        $form .= $this->oForm
                            ->createMedia("MEDIA_ID", t('PHOTO'), false, "image", "", $this->values["MEDIA_ID"], $this->readO, true, false, '16_9');
                        $form .= $this->oForm
                            ->endFormTable();
                        $form .= $this->oForm
                            ->createFreeHtml($this->_endZone());
                        // fin evol

                    } else {
                        $persoGlobal = null;
                        if($siteActivationPerso == 1){
                            $persoGlobal = ($this->values['PAGE_PERSO'] != '') ? 'YES' : 'NO';


                        }
                        $form .= $this->oForm
                            ->createFreeHtml($this->_beginZone("0", "<b>" . t('GLOBAL_PAGE') . "</b>", 0, "1", (! $this->readO && $this->id != - 2), true, true, '','',false,false,$persoGlobal));
                        $form .= $this->oForm
                            ->beginFormTable("0", "0", "form", true, "tabletogglezone" . $pageI);
                        if ($this->values["PAGE_PARENT_ID"]) {
                            $form .= $this->oForm
                                ->createLabel(t('ASSOCIATED_CONTENT_ORDER'), $this->getPageOrder($this->values["PAGE_ID"], - 1));
                            /** Cases affichées uniquement pour les niveaux 1 et 2*/
                            $form .= $this->oForm
                                ->createHidden("PAGE_DISPLAY_SEARCH_SAUVE", $this->values["PAGE_DISPLAY_SEARCH"]);
                            $form .= $this->oForm
                                ->createCheckBoxFromList("PAGE_DISPLAY_SEARCH", t('AFFICHER'), array(
                                    "1" => "dans la Recherche"
                                ), $this->values["PAGE_DISPLAY_SEARCH"], false, $this->readO, "h");
                            $form .= $this->oForm
                                ->createCheckBoxFromList("PAGE_DISPLAY_NAV", "", array(
                                    "1" => t('DANS_LA_NAVIGATION_WEB')
                                ), $this->values["PAGE_DISPLAY_NAV"], false, $this->readO, "h");
                            $form .= $this->oForm
                                ->createCheckBoxFromList("PAGE_DISPLAY_NAV_MOBILE", "", array(
                                    "1" => t('DANS_LA_NAVIGATION_MOBILE')
                                ), $this->values["PAGE_DISPLAY_NAV_MOBILE"], false, $this->readO, "h");
                            $form .= $this->oForm
                                ->createCheckBoxFromList("PAGE_DISPLAY_IN_ARIANE", "", array(
                                    "1" => t('DANS_LE_FIL_DARIANE')
                                ), $this->values["PAGE_DISPLAY_IN_ARIANE"], false, $this->readO, "h");


                            $form .= $this->oForm
                                ->createCheckBoxFromList("PAGE_DISPLAY_PLAN", "", array(
                                    "1" => "dans le Plan du site"
                                ), $this->values["PAGE_DISPLAY_PLAN"], false, $this->readO, "h");
                            $form .= $this->oForm
                                ->createHidden("PAGE_DISPLAY", 1);
                        }

                        $form .= $this->oForm->createComboFromList("PAGE_MODE_AFFICHAGE", t('MODE_AFFICHAGE'), Pelican::$config['TRANCHE_COL']['MODE_AFF'], $this->values['PAGE_MODE_AFFICHAGE']?$this->values['PAGE_MODE_AFFICHAGE']:'NEUTRE', true, $this->readO, "1", false, "", false );
                        $form .= $this->oForm->createComboFromList("PAGE_GAMME_VEHICULE", t('GAMME'), Pelican::$config['VEHICULE_GAMME'], $this->values['PAGE_GAMME_VEHICULE'], false, $this->readO);

                        $sSQLVehicule = "select VEHICULE_ID as id, VEHICULE_LABEL as lib from #pref#_vehicule where SITE_ID = :SITE_ID and LANGUE_ID = :LANGUE_ID order by lib";
                        $form .= $this->oForm->createComboFromSql($oConnection, "PAGE_VEHICULE", t('VEHICULE'), $sSQLVehicule, $this->values['PAGE_VEHICULE'], false, $this->readO, "1", false, "", true, false, "", "", $this->aBind);

                        $form .= $this->oForm
                            ->createTextArea("PAGE_TEXT", t('CHAPO'), false, $this->values["PAGE_TEXT"], 16000, $this->readO, 5, 70, false, "", false);
                        $form .= $this->oForm
                            ->createMedia("MEDIA_ID", t('PHOTO'), false, "image", "", $this->values["MEDIA_ID"], $this->readO, true, false, '16_9');
                        $form .= $this->oForm->createCheckBoxFromList("PAGE_LANGUETTE_PRO", t('LANGUETTE_PRO'), array('1'=>''), $this->values['PAGE_LANGUETTE_PRO'], false, $this->readO);
                        $form .= $this->oForm->createCheckBoxFromList("PAGE_LANGUETTE_CLIENT", t('LANGUETTE_CLIENT'), array('1'=>''), $this->values['PAGE_LANGUETTE_CLIENT'], false, $this->readO);
                        $form .= $this->oForm->showSeparator();
                        $form .= $this->oForm->createInput("PAGE_URL_EXTERNE", t('URL_EXTERNE'), 255, "internallink", false, $this->values['PAGE_URL_EXTERNE'], $this->readO, 70);
                        $form .= $this->oForm->createRadioFromList("PAGE_URL_EXTERNE_MODE_OUVERTURE", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), ($this->values['PAGE_URL_EXTERNE_MODE_OUVERTURE'] == null)?1:$this->values['PAGE_URL_EXTERNE_MODE_OUVERTURE'], false, $this->readO);

                        if (substr_count($this->values["PAGE_PATH"], "#") && substr_count($this->values["PAGE_PATH"], "#") == 1) {
                            $form .= $this->oForm->showSeparator();
                            $form .= $this->oForm->createCheckBoxFromList("PAGE_OUVERTURE_DIRECT", t('PAGE_OUVERTURE_DIRECT'), array('1'=>''), $this->values['PAGE_OUVERTURE_DIRECT'], false, $this->readO);
                            //$form .= $this->oForm->createRadioFromList("PAGE_URL_EXTERNE_MODE_OUVERTURE", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $this->values['PAGE_URL_EXTERNE_MODE_OUVERTURE'], true, $this->readO);
                            $form .= $this->oForm->showSeparator();
                            $form .= $this->oForm->createComboFromList("PAGE_TYPE_EXPAND", t('TYPE_EXPAND'), Pelican::$config['TYPE_EXPAND'], $this->values['PAGE_TYPE_EXPAND'], true, $this->readO, "1", false, "", false, false, "onchange=\"changeTypeExpand()\"");
                            $sTmpPerso = Cms_Page_Citroen::addPerso($this, true);
                            $form .= $this->oForm
                                ->createFreeHtml($sTmpPerso);
                            $form .= $this->oForm->createMedia("MEDIA_ID2", t('VISUEL_EXPAND_GALERIE'), false, "image", "", $this->values["MEDIA_ID2"], $this->readO, true, false, '16_9');
                            $form .= $this->oForm->createMultiHmvc("PUSH", t('PUSH'), array('path'=>dirname(__FILE__) . '/Page.php', 'class'=>'Cms_Page_Controller', 'method'=>'multiPush'), $multiValues['PUSH'], "PUSH", $this->readO, 3, true, true, "PUSH");
                            $js = "<script type=\"text/javascript\">
                                function changeTypeExpand() {
                                    val = $('select[name=PAGE_TYPE_EXPAND] option:selected').val();
                                    if (val=='0') {
                                        $('#td_PUSH').parent().show();
                                        $('input[name=PUSH]').parent().parent().show();

                                    } else {
                                        $('#td_PUSH').parent().hide();
                                        $('input[name=PUSH]').parent().parent().hide();
                                    }
                                }
                                $(document).ready(function() {
                                    changeTypeExpand();
                                });
                            </script>";
                            $form .= $js;
                        }
                        if (substr_count($this->values["PAGE_PATH"], "#") && substr_count($this->values["PAGE_PATH"], "#") == 2) {
                            $form .= $this->oForm->createMedia("MEDIA_ID2", t('VISUEL_EXPAND_GALERIE'), false, "image", "", $this->values["MEDIA_ID2"], $this->readO, true, false, '16_9');
                            if($this->values['TEMPLATE_PAGE_ID'] != 257 && $this->getParam('gid') != 257)
                            {
                                $form .= $this->oForm->createCheckBoxFromList("PAGE_OUVRIR_NIVEAU_3", t('OUVRIR_NIVEAU_3'), array(1 => ""), $this->values["PAGE_OUVRIR_NIVEAU_3"], false, $this->readO, "h", false, "onchange=\"changeOuvrirNiveaux3()\"");
                                $js = "<script type=\"text/javascript\">
                                function changeOuvrirNiveaux3() {
                                    val = $('input[name=PAGE_OUVRIR_NIVEAU_3]:checked').val();
                                    if (val==1) {
                                        $('input[name=PAGE_NB_ITEM_PAR_LIGNE]').parent().parent().show();
                                        $('textarea[name=PAGE_MENTIONS_LEGALES]').parent().parent().show();

                                    } else {
                                        $('input[name=PAGE_NB_ITEM_PAR_LIGNE]').parent().parent().hide();
                                        $('textarea[name=PAGE_MENTIONS_LEGALES]').parent().parent().hide();
                                    }
                                }

                                $(document).ready(function() {
                                    changeOuvrirNiveaux3();
                                });
                            </script>";

                                $form .= $js;

                            }
                            $form .= $this->oForm->showSeparator();
                            //$form .= $this->oForm->createInput("PAGE_URL_EXTERNE", t('URL_EXTERNE'), 255, "internallink", false, $this->values['PAGE_URL_EXTERNE'], $this->readO, 70);
                            //$form .= $this->oForm->createCheckBoxFromList("PAGE_OUVERTURE_DIRECT", t('PAGE_OUVERTURE_DIRECT'), array('1'=>''), $this->values['PAGE_OUVERTURE_DIRECT'], false, $this->readO);
                            //$form .= $this->oForm->createRadioFromList("PAGE_URL_EXTERNE_MODE_OUVERTURE", t('MODE_OUVERTURE'), array(1 => "_self", 2 => "_blank"), ($this->values['PAGE_ID'] == -2)?1:$this->values['PAGE_URL_EXTERNE_MODE_OUVERTURE'], true, $this->readO);
                            //$form .= $this->oForm->showSeparator();
                            // nb Items bloqué a 5
                            //$form .= $this->oForm->createRadioFromList("PAGE_NB_ITEM_PAR_LIGNE", t('NOMBRE_ITEM_PAR_LIGNE')." *", array(1 => "3", 2 => "4", 3 => "5"), $this->values['PAGE_NB_ITEM_PAR_LIGNE'], false, $this->readO);
                            /*$form .= $this->oForm->createJS("
                                if($('input[name=PAGE_OUVRIR_NIVEAU_3]:checked').val()=='1' && !$('input:radio[name=PAGE_NB_ITEM_PAR_LIGNE]:checked').val()) {
                                    alert('" . t('NOMBRE_ITEM_PAR_LIGNE_OBLIGATOIRE', 'js') . "');
                                    return false;
                                }
                            ");*/
                            $form .= $this->oForm->createTextArea("PAGE_MENTIONS_LEGALES", t('MENTIONS_LEGALES'), false, $this->values['PAGE_MENTIONS_LEGALES'], 180, $this->readO, 3, 70, false, "", false);

                            //Si ce n'est pas le gabarit master page standard N2, id = 257
                            if($this->values['TEMPLATE_PAGE_ID'] != 257 && $this->getParam('gid') != 257)
                            {
                                $sTmpPerso = Cms_Page_Citroen::addPerso($this, true);
                                $form .= $this->oForm
                                    ->createFreeHtml($sTmpPerso);
                                $form .= $this->oForm->createLabel(t('VOUS_DEVEZ_SAISIR_UN_PUSH_MIMINUM'), '');
                                $form .= $this->oForm->createMultiHmvc("PUSH_OUTILS_MAJEUR", t('PUSH_OUTILS_MAJEUR'), array('path'=>dirname(__FILE__) . '/Page.php', 'class'=>'Cms_Page_Controller', 'method'=>'multiCTA'), $multiValues['PUSH_OUTILS_MAJEUR'], "PUSH_OUTILS_MAJEUR", $this->readO, 1, true, true, "PUSH_OUTILS_MAJEUR");
                                $form .= $this->oForm->createMultiHmvc("PUSH_OUTILS_MINEUR", t('PUSH_OUTILS_MINEUR'), array('path'=>dirname(__FILE__) . '/Page.php', 'class'=>'Cms_Page_Controller', 'method'=>'multiCTA'), $multiValues['PUSH_OUTILS_MINEUR'], "PUSH_OUTILS_MINEUR", $this->readO, 4, true, true, "PUSH_OUTILS_MINEUR");
                                $form .= $this->oForm->createMultiHmvc("PUSH_CONTENU_ANNEXE", t('PUSH_CONTENU_ANNEXE'), array('path'=>dirname(__FILE__) . '/Page.php', 'class'=>'Cms_Page_Controller', 'method'=>'multiPush'), $multiValues['PUSH_CONTENU_ANNEXE'], "PUSH_CONTENU_ANNEXE", $this->readO, 2, true, true, "PUSH_CONTENU_ANNEXE");
                            }

                        }
                        if (substr_count($this->values["PAGE_PATH"], "#") && substr_count($this->values["PAGE_PATH"], "#") >= 3) {
                            $form .= $this->oForm->createMedia("MEDIA_ID2", t('VISUEL_EXPAND'), false, "image", "", $this->values["MEDIA_ID2"], $this->readO, true, false, '16_9');
                        }
                        $form .= $this->oForm
                            ->showSeparator();
                        if (empty($last["PAGE_VERSION"]))
                            $last["PAGE_VERSION"] = 1;

                        //Les templates « Showroom accueil » , « Master Page Standard N2 » et « Accueil Promotions
                        if($this->values['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']
                            || $this->values['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['MASTER_PAGE_STANDARD_N2']
                            || $this->values['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['ACCUEIL_PROMOTION']){

                            $tradLibelle = t('URL_FLUX_XML');
                            switch($this->values['TEMPLATE_PAGE_ID']){
                                case Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']:
                                    $tradLibelle = t('MODELE_EN_DETAIL');
                                    break;
                                case Pelican::$config['TEMPLATE_PAGE']['MASTER_PAGE_STANDARD_N2']:
                                case Pelican::$config['TEMPLATE_PAGE']['ACCUEIL_PROMOTION']:
                                    $tradLibelle = t('OVERVIEW');
                                    break;
                            }

                            $form .= $this->oForm->createInput("PAGE_GENERIQUE_LIBELLE", $tradLibelle, 255, "", true, $this->values['PAGE_GENERIQUE_LIBELLE'], $this->readO, 70);

                        }

                        //$form .= $this->oForm->showSeparator();

                        if($this->values['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']){
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

                            if($aConfiguration['ZONE_TITRE22'] == 1){
                                $form .= $this->oForm->createInput("PAGE_URL_FLUX_XML", t('URL_FLUX_XML'), 255, "URL", false, $this->values['PAGE_URL_FLUX_XML'], $this->readO, 70);
                            }
                            else{
                                $form .= $this->oForm->createhidden("PAGE_URL_FLUX_XML", $this->values['PAGE_URL_FLUX_XML']);
                            }
                        }


                        if( ($_SESSION[APP]['PROFIL_LABEL'] == Pelican::$config['PROFILE']['ADMINISTRATEUR']) && ($this->values['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL'])){

                            $form .= $this->oForm->showSeparator();
                            $form .= $this->oForm->createInput("PAGE_PRIMARY_COLOR", t('PAGE_PRIMARY_COLOR'), 7, "", false, $this->values['PAGE_PRIMARY_COLOR'], $this->readO, 7);
                            $form .= $this->oForm->createFreeHtml("<tr><td class=\"formlib\">&nbsp;</td><td class=\"formval\"><span id=\"PRIMARY_COLOR\"></span></td></tr>");
                            $form .= $this->oForm->createInput("PAGE_SECOND_COLOR", t('PAGE_SECOND_COLOR'), 7, "", false, $this->values['PAGE_SECOND_COLOR'], $this->readO, 7);
                            $form .= $this->oForm->createFreeHtml("<tr><td class=\"formlib\" >&nbsp;</td><td class=\"formval\"><span id=\"SECOND_COLOR\"></span></td></tr><br/>");


                            $form .= $this->oForm->createJS("
							if(obj.PAGE_PRIMARY_COLOR){
								var page_primary_color = obj.PAGE_PRIMARY_COLOR.value;
								var page_second_color = obj.PAGE_SECOND_COLOR.value;
								var code = page_primary_color.slice(-5);
								var codeSecond = page_second_color.slice(-5);
								var bsubmit = true;

								if(page_primary_color.length > 0 || page_second_color.length > 0){

									if(page_primary_color.length != 7 || page_second_color.length != 7){ //code hexadecimal 7 cacractere
										bsubmit =false;
									}
									if((page_primary_color.match('^#') ==null) || (page_second_color.match('^#') ==null)){ //code hexadecimal commence par #
										bsubmit =false;
									}
									if((code.match(/[A-Za-z0-9]+$/) ==null) || (codeSecond.match(/[A-Za-z0-9]+$/) ==null)){ //code hexadecimal alphanumerique pour les 6 derniers caracteres
										bsubmit =false;
									}
								}

								if(bsubmit ===false){
										alert('".t('ALERT_PAGE_PRIMARY_COLOR', 'js')."');
										$('#FIELD_BLANKS').val(1);
								}
							}
							");

                            $js = "<script type=\"text/javascript\">
                                function colorShowrrom() {

									var color1 = $('input[name=PAGE_PRIMARY_COLOR]').val();
									if(color1){
										$('table#tableClassForm1 span#PRIMARY_COLOR').text(color1);
										$('table#tableClassForm1 span#PRIMARY_COLOR').attr('style', 'background-color:'+color1+';color:'+color1+'');
									}

									var color2 = $('input[name=PAGE_SECOND_COLOR]').val();
									if(color2){
										$('table#tableClassForm1 span#second_color').text(color2);
										$('table#tableClassForm1 span#second_color').attr('style', 'background-color:'+color2+';color:'+color2+'');
									}


									$('input[name=PAGE_PRIMARY_COLOR]').keyup(function() {

										var primary_color = $('input[name=PAGE_PRIMARY_COLOR]').val();


										if(primary_color.length == 7){
											$('table#tableClassForm1 span#PRIMARY_COLOR').text(primary_color);
											$('table#tableClassForm1 span#PRIMARY_COLOR').attr('style', 'background-color:'+primary_color+';color:'+primary_color+'');
										}else{
											$('table#tableClassForm1 span#PRIMARY_COLOR').text('');
											$('table#tableClassForm1 span#PRIMARY_COLOR').attr('');
										}


									});

									$('input[name=PAGE_SECOND_COLOR]').keyup(function() {

										var second_color = $('input[name=PAGE_SECOND_COLOR]').val();
										if(second_color.length == 7){
											$('table#tableClassForm1 span#second_color').text(second_color);
											$('table#tableClassForm1 span#second_color').attr('style', 'background-color:'+second_color+';color:'+second_color+'');
										}else{
											$('table#tableClassForm1 span#second_color').text('');
											$('table#tableClassForm1 span#second_color').attr('style', '');
										}

									});

                                }
                                $(document).ready(function() {
                                    colorShowrrom();

                                });
                            </script>";
                            $form .= $js;

                        }elseif( ($_SESSION[APP]['PROFIL_LABEL'] != Pelican::$config['PROFILE']['ADMINISTRATEUR'])){

                            $sColorPrimary = '';
                            $sColorSecond  = '';

                            $aPageShowroomColor = Pelican_Cache::fetch("Frontend/Page/Showroom", array($this->values['PAGE_ID'],$this->values['LANGUE_ID'],'',Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']));

                            if(!empty($aPageShowroomColor['PAGE_PRIMARY_COLOR']) && !empty($aPageShowroomColor['PAGE_PRIMARY_COLOR'])){
                                $sColorPrimary = '<span style="background-color:'.$aPageShowroomColor['PAGE_PRIMARY_COLOR'].';color:'.$this->color_inverse($aPageShowroomColor['PAGE_PRIMARY_COLOR']).'">'.$aPageShowroomColor['PAGE_PRIMARY_COLOR'].'</span>';
                                $sColorSecond = '<span style="background-color:'.$aPageShowroomColor['PAGE_SECOND_COLOR'].';color:'.$this->color_inverse($aPageShowroomColor['PAGE_SECOND_COLOR']).'">'.$aPageShowroomColor['PAGE_SECOND_COLOR'].'</span>';
                            }

                            $form .= $this->oForm->showSeparator();
                            $form .= $this->oForm->createLabel(t('PAGE_PRIMARY_COLOR'),$sColorPrimary );
                            $form .= $this->oForm->createHidden("PAGE_PRIMARY_COLOR", $this->values['PAGE_PRIMARY_COLOR']);
                            $form .= $this->oForm->createLabel(t('PAGE_SECOND_COLOR'), $sColorSecond);
                            $form .= $this->oForm->createHidden("PAGE_SECOND_COLOR", $this->values['PAGE_SECOND_COLOR']);

                        }elseif($this->values['TEMPLATE_PAGE_ID'] != Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_INTERNE']){
                            $form .= $this->oForm->createComboFromList("PAGE_DUO_COLORS", t('DUO_COULEUR'), Pelican::$config['SHOWROOM_COLOR'], $this->values['PAGE_PRIMARY_COLOR'].'/'.$this->values['PAGE_SECOND_COLOR'], false, $this->readO);
                            $form .= $this->oForm->createFreeHtml("<tr><td class=\"formlib\">&nbsp;</td><td class=\"formval\"><span id=\"PRIMARY_COLOR\"></span></td></tr>");
                            $form .= $this->oForm->createFreeHtml("<tr><td class=\"formlib\" >&nbsp;</td><td class=\"formval\"><span id=\"SECOND_COLOR\"></span></td></tr><br/>");

                            $form .= $this->oForm->showSeparator();
                            $form .= $this->oForm->createLabel(t('PAGE_PRIMARY_COLOR'), '<span style="background-color:'.$this->values['PAGE_PRIMARY_COLOR'].';color:'.$this->values['PAGE_PRIMARY_COLOR'].'">'.$this->values['PAGE_PRIMARY_COLOR'].'</span>');
                            $form .= $this->oForm->createHidden("PAGE_PRIMARY_COLOR", $this->values['PAGE_PRIMARY_COLOR']);
                            $form .= $this->oForm->createLabel(t('PAGE_SECOND_COLOR'), '<span style="background-color:'.$this->values['PAGE_SECOND_COLOR'].';color:'.$this->values['PAGE_SECOND_COLOR'].'">'.$this->values['PAGE_SECOND_COLOR'].'</span>');
                            $form .= $this->oForm->createHidden("PAGE_SECOND_COLOR", $this->values['PAGE_SECOND_COLOR']);


                            $js = "<script type=\"text/javascript\">
                                function colorShowrromDisplay() {

												$('#PAGE_DUO_COLORS').on('change', function() {
													  var value =  $(this).val();
													  if( value != ''){
														var valueColor    = value.split('/');
														 $('table#tableClassForm1 span#PRIMARY_COLOR').text(valueColor[0]);
														 $('table#tableClassForm1 span#PRIMARY_COLOR').attr('style', 'background-color:'+valueColor[0]+';color:'+valueColor[0]+'');
														 $('table#tableClassForm1 span#second_color').text(valueColor[1]);
														 $('table#tableClassForm1 span#second_color').attr('style', 'background-color:'+valueColor[1]+';color:'+valueColor[1]+'');
													}
													});

                                }
                                $(document).ready(function() {
                                    colorShowrromDisplay();

                                });
                            </script>";
                            $form .= $js;
                        }



                        $form .= $this->oForm
                            ->endFormTable();
                        $form .= $this->oForm
                            ->createFreeHtml($this->_endZone());
                    }
                    $form .= $this->oForm
                        ->createhidden("increment_page", "page");
                    $form .= $this->oForm
                        ->createhidden("count_page", sizeOf($tabZones) - 1);
                    $form .= $this->oForm
                        ->createhidden("PAGE_PARENT_ID", $this->values["PAGE_PARENT_ID"]);
                    $form .= $this->oForm
                        ->createhidden("PAGE_ORDER", $this->values["PAGE_ORDER"]);
                    $form .= $this->oForm
                        ->createhidden("PAGE_DIFFUSION", $this->values["PAGE_DIFFUSION"]);
                    $form .= $this->oForm
                        ->createhidden("PAGE_LOGO", (isset($this->values["PAGE_LOGO"]) ? $this->values["PAGE_LOGO"] : ""));
                    $form .= $this->oForm
                        ->createHidden("PAGE_GENERAL", $this->values["PAGE_GENERAL"]);
                    $form .= $this->oForm
                        ->createHidden("PAGE_CHILD_ORDER", $this->values["PAGE_CHILD_ORDER"]);
                    $form .= $this->oForm
                        ->createhidden("PAGE_PATH", $this->values["PAGE_PATH"]);
                    $form .= $this->oForm
                        ->createhidden("PAGE_LIBPATH", $this->values["PAGE_LIBPATH"]);
                    $form .= $this->oForm
                        ->createhidden("PAGE_SCHEDULE_VERSION", $this->values["PAGE_SCHEDULE_VERSION"]);
                    $form .= $this->oForm
                        ->createhidden("SCHEDULE_STATUS", $this->values["SCHEDULE_STATUS"]);
                    $page = $this->values;
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
                                        $nameZone = 'Zone ' . $data['TEMPLATE_PAGE_AREA_ORDER'];
                                    }

                                    $form .= $this->oForm
                                        ->createFreeHtml('<br/><fieldset class="page">' . Pelican_Html::legend(t($nameZone)));
                                    $form .= $this->oForm
                                        ->createFreeHtml('<table style="width: 100%">');

                                } elseif ($currentArea != $data["AREA_ID"]) {
                                    $form .= $this->oForm
                                        ->createFreeHtml('</table>');
                                    $form .= $this->oForm
                                        ->createFreeHtml('</fieldset>');

                                    $currentArea = $data["AREA_ID"];

                                    if ($_SESSION[APP]['SITE_ID'] > Pelican::$config["ADM_MINISITE_ID"]) {
                                        $areaLabelTab = explode(" - ", $data['AREA_LABEL']);
                                        $nameZone = $areaLabelTab[1];
                                    } else {
                                        $nameZone = 'Zone ' . $data['TEMPLATE_PAGE_AREA_ORDER'];
                                    }

                                    $form .= $this->oForm
                                        ->createFreeHtml('<br/><fieldset class="page">' . Pelican_Html::legend(t($nameZone)));
                                    $form .= $this->oForm
                                        ->createFreeHtml('<table style="width: 100%">');

                                }

                                /**
                                 * * sélection du template de saisie et du template de traitement
                                 */
                                $root = ($data["PLUGIN_ID"] ? Pelican::$config["PLUGIN_ROOT"] . '/' . $data["PLUGIN_ID"] . '/backend/controllers' : Pelican::$config['APPLICATION_CONTROLLERS']);
                                $module = $root . '/' . str_replace("_", "/", $data["ZONE_BO_PATH"]) . ".php";
                                $moduleClass = $data["ZONE_BO_PATH"];

                                /**
                                 * * Input cachés identifiants les zones et les fichiers de transaction
                                 */
                                //$form .=($form .= $this->oForm->createHidden("ZONE_TEMPLATE_ID[]", $data["ZONE_TEMPLATE_ID"], true));
                                $ZONE_TEMPLATE_ID[] = $this->oForm
                                    ->createHidden("ZONE_TEMPLATE_ID[]", $data["ZONE_TEMPLATE_ID"], true);

                                /**
                                 * * Etat ouvert ou non : initialisé par les cookies
                                 */
                                $closed = false;
                                $setCookie = false;
                                if ($data["ZONE_TEMPLATE_ID"] == 1) {
                                    $setCookie = false;
                                } elseif (isset($_COOKIE["togglezone" . $data["ZONE_TEMPLATE_ID"]])) {
                                    $setCookie = true;
                                    if ($_COOKIE["togglezone" . $data["ZONE_TEMPLATE_ID"]] != "false") {
                                        $closed = true;
                                    }
                                }
                                $this->multi = "multi" . $pageI . "_";
                                $pageI ++;

                                /** valeurs par défaut : pour l'intercepter il faut mettre la commande dans le template */
                                $this->zoneValues = $this->_getZoneValues($page, $data, $moduleClass);
                                $this->zoneValues["PAGE_ID"] = $page_id;
                                $this->zoneValues['LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
                                $this->zoneValues["PAGE_VERSION"] = $page["PAGE_VERSION"];
                                $this->zoneValues['ALT_TEMPLATE_PAGE_ID'] = $page['ALT_TEMPLATE_PAGE_ID'];

                                /**
                                 * * Affichage de la Pelican_Index_Frontoffice_Zone dans une table
                                 */
                                $persoZone = null;
                                if($siteActivationPerso == 1 && in_array($data['ZONE_ID'], $zoneActivationPerso)){
                                    $persoZone = ($this->values['ZONE_PERSO'] != '') ? 'YES' : 'NO';
                                }
                                if(preg_match('(é|à|è)',$data["ZONE_TEMPLATE_LABEL"])){
                                    $label = $data["ZONE_TEMPLATE_LABEL"];
                                }else{
                                    $label = t($data["ZONE_TEMPLATE_LABEL"]);
                                }
                                if ($data["IS_DROPPABLE"] == 1) {
                                    //si le bloc se trouve dans une Pelican_Index_Frontoffice_Zone droppable
                                    $form .= $this->oForm
                                        ->createFreeHtml($this->_beginZone($data["ZONE_TEMPLATE_ID"], $label . "<!--" . $data["ZONE_TEMPLATE_ID"] . "-->", $nbrbloc, $data["ZONE_TYPE_ID"], true, $setCookie, true, $this->multi . "ZONE_EMPTY", $data["ZONE_PROGRAM"], $this->zoneValues["ZONE_EMPTY"], false, $persoZone));
                                    $form .= $this->oForm
                                        ->beginFormTable("0", "0", "form", true, "tabletogglezone" . $pageI);
                                } elseif ($data["ZONE_TYPE_ID"] == 3) {
                                    $form .= $this->oForm
                                        ->createFreeHtml($this->_beginZone($data["ZONE_TEMPLATE_ID"], $label . "<!--" . $data["ZONE_TEMPLATE_ID"] . "-->", $nbrbloc, $data["ZONE_TYPE_ID"], true, $setCookie, true, $this->multi . "deleteZone", $data["ZONE_PROGRAM"], $this->zoneValues["ZONE_EMPTY"], false, $persoZone));
                                    $form .= $this->oForm
                                        ->beginFormTable("0", "0", "form", true, "tabletogglezone" . $pageI);
                                } elseif ($data["ZONE_TYPE_ID"] == 1) {
                                    $form .= $this->oForm
                                        ->createFreeHtml($this->_beginZone($data["ZONE_TEMPLATE_ID"], $label . "<!--" . $data["ZONE_TEMPLATE_ID"] . "-->", $nbrbloc, $data["ZONE_TYPE_ID"], true, $setCookie, true, "", $data["ZONE_PROGRAM"], "", false, $persoZone));
                                    $form .= $this->oForm
                                        ->beginFormTable("0", "0", "form", true, "tabletogglezone" . $pageI);
                                }

                                /**
                                 * * Si le fichiers n'existe pas, la mention A FAIRE est affichée
                                 */

                                $form .= $this->oForm
                                    ->createhidden($this->multi . "multi_display", 1);
                                if (! file_exists($module)) {
                                    $form .= $this->oForm
                                        ->createFreeHtml("<span class=\"erreur\">" . $module . " => A FAIRE</span>");
                                } else {
                                    include_once ($module);
                                    $tmpPerso = '';
                                    $tmpPerso = call_user_func_array(array(
                                        $moduleClass ,
                                        'beforeRender'
                                    ), array(
                                        $this,
                                        $this->generalPage
                                    ));

                                    /**
                                     * ID HTML
                                     */
                                    $idHTML = '#' . Pelican_Text::cleanText($this->zoneValues['ZONE_TEMPLATE_LABEL'], '-' ,false, false) . '_' . $this->zoneValues['ZONE_TEMPLATE_ID'];
                                    $tmpPerso .= $this->oForm->createLabel(t('ID_HTML'), $idHTML);


                                    $tmp = call_user_func_array(array(

                                        $moduleClass ,
                                        'render'
                                    ), array(
                                        $this
                                    ));

                                    //Prise en compte de la class zend_form
                                    if ($this->generalPage) {
                                        $form .= $this->oForm
                                            ->createFreeHtml($tmpPerso.$tmp);
                                    } else {
                                        $form .= $tmpPerso.$tmp;
                                    }
                                }

                                // si la verif est activer (CheckBox mobile/ web)
                                // helper backend getFormAffichage
                                if($this->zoneValues['VERIF_JS'] == 1){
                                    $form .= $this->oForm->createJS('}');
                                    //Pelican::$config['VERIF_JS'] = 0;
                                }

                                $form .= $this->oForm
                                    ->createhidden($this->multi . "ZONE_TYPE_ID", $data["ZONE_TYPE_ID"]);
                                $form .= $this->oForm
                                    ->createhidden($this->multi . "ZONE_TEMPLATE_ID", $data["ZONE_TEMPLATE_ID"]);
                                $form .= $this->oForm
                                    ->createhidden($this->multi . "ZONE_ID", $data["ZONE_ID"]);
                                $form .= $this->oForm
                                    ->createhidden($this->multi . "PLUGIN_ID", $data["PLUGIN_ID"]);

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
					and ZONE_TEMPLATE_ID=" . $_GET["blc"] . "
					order by LIGNE, COLONNE";
                    $aProgZones = $oConnection->queryrow($sqlZone);

                    $form .= $this->oForm
                        ->createMulti($oConnection, "form_program", $aProgZones["ZONE_TEMPLATE_LABEL"], Pelican::$config['APPLICATION_CONTROLLERS'] . '/' . $aProgZones["ZONE_BO_PATH"], $multiValuesQuestion, "program_number", $this->readO, "", true);
                    $form .= $this->oForm
                        ->createhidden("ZONE_TEMPLATE_ID", $_GET["blc"]);
                    $form .= $this->oForm
                        ->createhidden("ZONE_PROGRAM", $aProgZones["ZONE_PROGRAM"]);
                    $form .= $this->oForm
                        ->createhidden("PAGE_ID", $this->id);
                }
                $this->values = $page;

                // Publication
                if (! $this->generalPage) {
                    $form .= $this->oForm
                        ->beginTab("3");
                    /*$form .= $this->oForm
                        ->inputTaxonomy("TAXONOMY", t('Taxonomy'), "/_/Taxonomy/suggest", $this->values["PAGE_ID"], 3);*/

                    // Si le profil est admin ou si le profil n'a pas de métier on remonte tous les métiers
                    if( isset($_SESSION['LDAP']['ADMIN']) && $_SESSION['LDAP']['ADMIN'] == true || !isset($_SESSION['LDAP'][$_SESSION[APP]['SITE_ID']][$_SESSION[APP]['PROFILE_ID']])){
                        $sqlMetier	=	"select METIER_ID as id, METIER_LABEL as lib from #pref#_metier";
                    }
                    // Si le profil a au minimum 1 metier on lui affiche uniquement les métiers autorisés pour ce profil
                    elseif(isset($_SESSION['LDAP'][$_SESSION[APP]['SITE_ID']][$_SESSION[APP]['PROFILE_ID']])){
                        if(is_array( $_SESSION['LDAP'][$_SESSION[APP]['SITE_ID']][$_SESSION[APP]['PROFILE_ID']] )){
                            $profilsId	=	implode(',', $_SESSION['LDAP'][$_SESSION[APP]['SITE_ID']][$_SESSION[APP]['PROFILE_ID']]);
                            $sqlMetier	=	"select METIER_ID as id, METIER_LABEL as lib from #pref#_metier where METIER_ID IN(" . $profilsId . ")";
                        }
                    }
                    $form .= $this->oForm->createCheckBoxFromList ( "PAGE_PROTOCOLE_HTTPS", t ( 'Protocole' ), array ("1" => "" ), $this->values ["PAGE_PROTOCOLE_HTTPS"], false, $this->readO, "h" );
                    $form .= $this->oForm->createComboFromSql ( $oConnection, "PAGE_METIER", t ( 'Metier' ), $sqlMetier, $this->values ["PAGE_METIER"], false, $this->readO );
                    $form .= $this->oForm->showSeparator ();
                    $form .= '<tr><td class="formlib">'.t ( 'Display date begin' ).'</td><td class="formval">';
                    $form .= $this->oForm->createInput ( "PAGE_START_DATE", t ( 'Display date begin' ), 10, "date", false, trim(preg_replace( '/[0-9]{2}:[0-9]{2}/', '', $this->values ["PAGE_START_DATE"] )), $this->readO, 10, true );
                    $form .= $this->oForm->createInput ( "PAGE_START_DATE_HEURE", t ( 'Display date begin' ), 10, "heure", false, trim(preg_replace( '/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/', '', $this->values ["PAGE_START_DATE"] )), $this->readO, 10, true );
                    $form .= '</td>';
                    $form .= '<tr><td class="formlib">'.t ( 'Display end date' ).'</td><td class="formval">';
                    $form .= $this->oForm->createInput ( "PAGE_END_DATE", t ( 'Display end date' ), 10, "date", false, trim(preg_replace( '/[0-9]{2}:[0-9]{2}/', '', $this->values ["PAGE_END_DATE"] )), $this->readO, 10, true );
                    $form .= $this->oForm->createInput ( "PAGE_END_DATE_HEURE", t ( 'Display end date' ), 10, "heure", false, trim(preg_replace( '/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/', '', $this->values ["PAGE_END_DATE"] )), $this->readO, 10, true );
                    $form .= '</td>';
                }

                // Planification
                if (! $this->generalPage) {
                    $form .= $this->oForm
                        ->beginTab("4");
                    $form .= '<tr><td class="formlib">'.t ( 'Display date begin' ).'</td><td class="formval">';
                    $form .= $this->oForm->createInput ( "PAGE_START_DATE_SCHEDULE", t ( 'Display date begin' ), 10, "date", false, trim(preg_replace( '/[0-9]{2}:[0-9]{2}/', '', $this->values ["PAGE_START_DATE_SCHEDULE"] )), $this->readO, 10, true );
                    $form .= '</td>';
                    $form .= '<tr><td class="formlib">'.t ( 'Display end date' ).'</td><td class="formval">';
                    $form .= $this->oForm->createInput ( "PAGE_END_DATE_SCHEDULE", t ( 'Display end date' ), 10, "date", false, trim(preg_replace( '/[0-9]{2}:[0-9]{2}/', '', $this->values ["PAGE_END_DATE_SCHEDULE"] )), $this->readO, 10, true );
                    $form .= '</td>';
                    $this->oForm->_sJS.= "
                        var dateDebSchedule     = $('#PAGE_START_DATE_SCHEDULE').val();
                        if( dateDebSchedule != ''){
                            var aDateDebSchedule    = dateDebSchedule.split('/');
                            dateDebSchedule     = new Date(aDateDebSchedule[2],aDateDebSchedule[1],aDateDebSchedule[0]);
                            ladate = new Date();
                            dateDuJour = ladate.getDate()+\"/\"+(ladate.getMonth()+1)+\"/\"+ladate.getFullYear();
                            dateDebScheduleFormater = parseInt(aDateDebSchedule[0])+\"/\"+parseInt(aDateDebSchedule[1])+\"/\"+parseInt(aDateDebSchedule[2]);
                            if(dateDebScheduleFormater < dateDuJour){
                                alert('" . t('LA_DATE_DE_DEBUT_NE_DOIT_PAS_ETRE_PLUS_PETITE_QUE_LA_DATE_DU_JOUR') . " ');
                                fwFocus($('#Divtogglezone0'));
                                return false;
                            }
                        }

                        var dateFinSchedule     = $('#PAGE_END_DATE_SCHEDULE').val();
                        if( dateFinSchedule != ''){
                            var aDateFinSchedule    = dateFinSchedule.split('/');
                             dateFinSchedule     = new Date(aDateFinSchedule[2],aDateFinSchedule[1],aDateFinSchedule[0]);
                        }

                        if( dateDebSchedule != '' && dateFinSchedule != ''){
                            if(dateDebSchedule > dateFinSchedule){
                                alert('" . t('LA_DATE_DE_FIN_DOIT_EST_PLUS_GRANDE_QUE_LA_DATE_DE_DEBUT') . " ');
                                fwFocus($('#Divtogglezone0'));
                                return false;
                            }
                            if($('#PAGE_START_DATE_SCHEDULE').val() == $('#PAGE_END_DATE_SCHEDULE').val()){
                                alert('" . t('LA_DATE_DE_FIN_NE_DOIT_PAS_ETRE_EGALE_A_LA_DATE_DE_DEBUT') . " ');
                                fwFocus($('#Divtogglezone0'));
                                return false;
                            }
                        }
                    ";
                }
                $form .= $this->oForm
                    ->createFreeHtml($this->getWorkflowFields($this->oForm, $this->generalPage));

                // seo
                $form .= $this->oForm
                    ->beginTab("2");

                if (! $this->generalPage) {
                    $form .= $this->oForm
                        ->createTextArea("PAGE_META_TITLE", t('Meta title'), false, $this->values["PAGE_META_TITLE"], 255, $this->readO, 2, 100, false, "", false);
                    $form .= $this->oForm
                        ->createTextArea("PAGE_META_KEYWORD", t('Meta keywords'), false, $this->values["PAGE_META_KEYWORD"], 255, $this->readO, 2, 100, false, "", false);
                    $form .= $this->oForm
                        ->createTextArea("PAGE_META_DESC", t('Meta description'), false, $this->values["PAGE_META_DESC"], 16000, $this->readO, 5, 100, false, "", false);
                } else {
                    $this->bRewrite = false;
                    $this->cybertag = array();
                }

                $form .= $this->oForm
                    ->createFreeHtml($this->endForm($this->oForm, (valueExists($_GET, "sid") ? "" : "noback")));
                $form .= $this->oForm
                    ->endTab();
                $form .= $this->oForm
                    ->close();
                $form .= "</td></tr></table>";

                if (valueExists($_GET, "popup_content")) {
                    /**
                     * * dans le cas où le tea_id est défini (popup)
                     */
                    $form .= $this->oForm
                        ->createFreeHtml("<script type=\"text/javascript\">top.history = escape(document.fForm.form_retour.value);top.id = " . ($teaId ? $teaId : "''") . "; top.refresh();</script>");
                }

                /**
                 * * VERSION 2
                 */
                if (! valueExists($_GET, "pid")) {
                    $tableZone = $this->_generateZone($tabZones);
                    $zone_types = Pelican_Cache::fetch("Backend/ZoneType");
                    if ($zone_types) {
                        if (Pelican::$config["MODE_ZONE_VIEW"] == 'top') {
                            $tableZone .= '<table align="center" id="visualZoneLegende"><tr>';
                        }
                        foreach ($zone_types as $zone_type) {
                            if (Pelican::$config["MODE_ZONE_VIEW"] == 'top') $tableZone .= '<td width="33%" align="center">';
                            $tableZone .= "&nbsp;<span class=\"zonetype" . $zone_type["ZONE_TYPE_ID"] . "\" height=\"10px\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;" . $zone_type["ZONE_TYPE_LABEL"] . "<br />";
                            if (Pelican::$config["MODE_ZONE_VIEW"] == 'top') $tableZone .= '</td>';
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
                                        <div>' . $tableZone . '</div>
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
                                    <td  width="349" bgcolor="white">' . $tableZone . '</td>
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
                /********** Pour faire correspondre **********/
                $form = '<table width="100%" border="0">';
                if (! $child && isset($_GET['readO']) && ($_GET['readO'] == true)) {
                    $form .= '<tr><td style="width:30px" valign="top">&nbsp;</td><td class="erreur" style="width: 95%;">' . t('DELETE_PAGE_ALERT') . '</td></tr>';
                }
                $form .= '<tr><td  style="width:30px" valign="top">&nbsp;</td><td valign="top">';
                $form .= formToString($this->oForm, $form);
                $form .= '</td></tr></table>';
                /*********************************************/
            }
            // Zend_Form stop

            $form .= "
					<script type=\"text/javascript\">
						$('textarea[name=REWRITE_REDIRECT_URL]').focusout(function(){
							callAjax({
								type: \"POST\",
								data: {urls : $(this).val(), id : '".$this->values["PAGE_ID"]."', field : 'PAGE_CLEAR_URL', langue : '".$this->values["LANGUE_ID"]."', isFromRubrique : false},
								url: '/_/Citroen_Administration_Url/ajaxVerifUrl'
							});
						});
						$('#PAGE_CLEAR_URL').focusout(function(){
							callAjax({
								type: \"POST\",
								data: {urls : $(this).val(), id : '".$this->values["PAGE_ID"]."', field : 'PAGE_CLEAR_URL', langue : '".$this->values["LANGUE_ID"]."', isFromRubrique : true},
								url: '/_/Citroen_Administration_Url/ajaxVerifUrl'
							});
						});
					</script>
					";
            $this->aBind[":PAGE_ID"] = $this->id;
            $pageScheduleActive = $oConnection->queryRow("select SCHEDULE_STATUS from #pref#_page where PAGE_ID=:PAGE_ID", $this->aBind);
            $this->assign('isSchedule', $pageScheduleActive['SCHEDULE_STATUS'], false);
            $this->assign('zoneDynamiqueJs', $zoneDynamiqueJs, false);
            $this->assign('scheduleForm', $this->getScheduleForm(), false);
            $this->assign('versionForm', $this->getVersioningForm(), false);
            $this->assign('tableZone', $tableZone, false);
            $this->assign('form', $form, false);
            $this->assign('openZone', $_GET["openZone"]);
            $this->assign('nbrbloc', $nbrbloc);
            $this->assign('height', ($_SESSION["screen_height"] - 450));
            $this->assign('clean_url', str_replace("&gid=" . $_GET["gid"], "", $_SERVER["REQUEST_URI"]));
            $this->replaceTemplate('index', 'edit');
            $this->fetch();
        }

        Pelican_Cache::clean("Frontend/Citroen/Technologie/Gallerie");
        Pelican_Cache::clean("Frontend/Citroen/Actualites/Liste");
        Pelican_Cache::clean("Frontend/Citroen/Home/Actualites");
        Pelican_Cache::clean("Frontend/Citroen/GalerieNiveau2");
        Pelican_Cache::clean("Frontend/Citroen/Accessoires/Accessories");

    }

    public function getMultiZones($data, &$currentArea, &$form, &$js) {

        $oConnection = getConnection();
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
                $nameZone = 'Zone ' . $data['TEMPLATE_PAGE_AREA_ORDER'];
            }

            $form .= $this->oForm
                ->createHidden('db_pageMulti[]', $data['AREA_ID']);



            $form .= $this->oForm
                ->createFreeHtml('<br/><fieldset class="page dynamique">' . Pelican_Html::legend(t($nameZone)));
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
                foreach ($aMultiZones as $zone) {
                    $cptMultiZone++;
                    $zone['ZONE_DYNAMIQUE'] = 1;
                    $this->getMultiZone($form, $zone, $cptMultiZone, $data['AREA_ID']);
                }
            }

            $form .= $this->oForm
                ->createHidden('count_pageMulti' . $data['AREA_ID'], $cptMultiZone);

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
                $(".persoDialog").on("click",function(e){
                e.preventDefault();
                var _rel = $(this).attr("rel");
                ajaxPerso(_rel);
            });

                var nbZone'.$data['AREA_ID'].' = '.$cptMultiZone.';
                var duration = 1000;
                var animationType = "blind";
                var slideMenuSize = "210px";

                $(function() {
                    $("#slide_'.$data['AREA_ID'].' div").draggable({
                         cursor: "pointer",
                         connectWith: "#td_ZONE_DYNAMIQUE_'.$data['AREA_ID'].'",
                         helper: "clone",
                         opacity: 0.5,
                         zIndex: 10,
                         connectToSortable : "#td_ZONE_DYNAMIQUE_'.$data['AREA_ID'].'"
                    });
                    $("#td_ZONE_DYNAMIQUE_'.$data['AREA_ID'].'").sortable({
                        connectWith: "#td_ZONE_DYNAMIQUE_'.$data['AREA_ID'].'",
                        placeholder: "sortable-placeholder",
                        cursor: "pointer"
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

                    $("#slide_'.$data['AREA_ID'].'").toggle();
                    $("#button_toggle_'.$data['AREA_ID'].'").click(function(){
                        if ( $("#slide_'.$data['AREA_ID'].'").is(":visible") ) {
                            $("#button_toggle_'.$data['AREA_ID'].'").attr("src","/library/public/images/icon-toggle-open.png");
                            $("#td_ZONE_DYNAMIQUE_'.$data['AREA_ID'].'").animate({width: \'+=\'+slideMenuSize},duration);
                            $("#slide_'.$data['AREA_ID'].'").hide(animationType, { direction: "right" }, duration);
                        }
                        else {
                            $("#button_toggle_'.$data['AREA_ID'].'").attr("src","/library/public/images/icon-toggle-close.png");
                            $("#td_ZONE_DYNAMIQUE_'.$data['AREA_ID'].'").animate({width: \'-=\'+slideMenuSize},duration);
                            $("#slide_'.$data['AREA_ID'].'").show(animationType, { direction: "right" }, duration);
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


        if(preg_match('(é|à|è)',$data['ZONE_TEMPLATE_LABEL'])){
            $label =$data['ZONE_TEMPLATE_LABEL'];
        }else{
            $label = t($data['ZONE_TEMPLATE_LABEL']);
        }
        $form .= $this->oForm
            ->createFreeHtml('<tr><td><div title="zone_multi_'.$data['ZONE_ID'].'" class="btnAddZone draggable">'.$label.'</div></td></tr>');



    }

    public function getMultiZone(&$form, $data, $cpt, $area_id, $ajax = false) {

        $oConnection = Pelican_Db::getInstance();
        /* Info activation perso */
        $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $sql = "
                SELECT
                    SITE_PERSO_ACTIVATION
                FROM
                    #pref#_site
                WHERE
                    SITE_ID = :SITE_ID
            ";
        $siteActivationPerso = $oConnection->queryItem($sql,$bind);
        $sql = "
                SELECT
                    ZONE_ID
                FROM
                    #pref#_site_personnalisation
                WHERE
                    SITE_ID = :SITE_ID
            ";
        $results = $oConnection->queryTab($sql,$bind);
        $zoneActivationPerso = array();
        if(is_array($results) && count($results)>0){
            foreach($results as $result){
                $zoneActivationPerso[] =  $result['ZONE_ID'];
            }
        }

        /**
         * * sélection du template de saisie et du template de traitement
         */
        $root = ($data["PLUGIN_ID"] ? Pelican::$config["PLUGIN_ROOT"] . '/' . $data["PLUGIN_ID"] . '/backend/controllers' : Pelican::$config['APPLICATION_CONTROLLERS']);
        $module = $root . '/' . str_replace("_", "/", $data["ZONE_BO_PATH"]) . ".php";
        $moduleClass = $data["ZONE_BO_PATH"];

        /**
         * * Etat ouvert ou non : initialisé par les cookies
         */
        $closed = false;
        $setCookie = false;
        if ($data["ZONE_TEMPLATE_ID"] == 1) {
            $setCookie = false;
        } elseif (isset($_COOKIE["togglezone" . $data["ZONE_TEMPLATE_ID"]])) {
            $setCookie = true;
            if ($_COOKIE["togglezone" . $data["ZONE_TEMPLATE_ID"]] != "false") {
                $closed = true;
            }
        }

        $this->multi = "multiZone" . $area_id . "_" . $cpt . "_";
        $persoZone = null;
        if($siteActivationPerso == 1 && in_array($data['ZONE_ID'], $zoneActivationPerso)){
            $persoZone = ($data['ZONE_PERSO'] != '') ? 'YES' : 'NO';
        }

        if (!$ajax) $form .= '<div>';
        $form .= $this->oForm->beginFormTable();
        $form .= $this->oForm
            ->createFreeHtml(
                $this->_beginZone(
                    'zoneDynamique_' . $cpt,
                    t($data["LABEL_ZONE"]) . "<!-- zoneDynamique_" . $cpt . "-->",
                    1,
                    $data["ZONE_TYPE_ID"],
                    true,
                    $setCookie,
                    true,
                    "",
                    $data["ZONE_PROGRAM"],
                    false,
                    true,
                    $persoZone
                )
            );
        $form .= $this->oForm
            ->beginFormTable("0", "0", "form", true, "tabletogglezone" . $cpt);
        $form .= $this->oForm->createHidden($this->multi . "multi_display", "1");

        if (!$moduleClass) $moduleClass = 'Cms_Page_Module';

        $form .= $this->oForm->createHidden("MULTI_ZONE_" . $area_id . "_DB[" . $cpt . "]", $moduleClass, true);
        $form .= $this->oForm->createHidden("MULTI_ZONE_" . $area_id . "_ID[" . $cpt . "]", $data["ZONE_ID"], true);

        if ($data["ZONE_TYPE_ID"] == 2) {
            $form .= $this->oForm
                ->createFreeHtml("<span class=\"\">" . t('ZONE_AUTOMATIQUE') . "</span>");
            $form .= $this->oForm->createHidden($this->multi . "ZONE_TEXTE", 1, true);

        } else if (! file_exists($module)) {
            $form .= $this->oForm
                ->createFreeHtml("<span class=\"erreur\">" . $module . " => " . t('A_FAIRE') . "</span>");
        } else {
            include_once ($module);
            $this->oForm->_sJS.= "if (document.getElementById('" . $this->multi . "multi_display')) {\n if (document.getElementById('" . $this->multi . "multi_display').value) {\n";

            $this->zoneValues = $data;
            $tmpPerso = '';
            $tmpPerso = call_user_func_array(array(
                $moduleClass ,
                'beforeRender'
            ), array(
                $this,
                $this->generalPage
            ));

            /**
             * ID HTML
             */
            $idHTML = '#' . Pelican_Text::cleanText($this->zoneValues['LABEL_ZONE'], '-' ,false, false) . '_' . $this->zoneValues['AREA_ID'] . '_' . $this->zoneValues['ZONE_ORDER'];
            $tmpPerso .= $this->oForm->createLabel(t('ID_HTML'), $idHTML);


            $tmp = call_user_func_array(array(
                $moduleClass ,
                'render'
            ), array(
                $this
            ));

            $this->oForm->_sJS.= "}\n}\n";

            // si la verif est activer (CheckBox mobile/ web)
            // helper backend getFormAffichage
            if($this->zoneValues['VERIF_JS'] == 1){
                $this->oForm->_sJS.= "}\n";
                //Pelican::$config['VERIF_JS'] = 0;
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
        if (!$ajax) $form .= '</div>';
    }

    public static function multiPush($oForm, $values, $readO, $multi, $perso, $extendedArgs = null)
    {
        // Champ synchronisation (perso)
        self::displaySyncField($oForm, $values, $readO, $multi, $perso, $extendedArgs, $return);

        $return .= $oForm->createInput($multi."PAGE_MULTI_LABEL", t('LIBELLE'), 40, "", true, $values['PAGE_MULTI_LABEL'], $readO, 40);
        $return .= $oForm->createMedia($multi . "MEDIA_ID", t('IMAGE'), false,  "image", "", $values['MEDIA_ID'] , $readO, true, false, '16_9', null, $perso, $values['MEDIA_ID_GENERIQUE']);
        $return .= $oForm->createInput($multi."PAGE_MULTI_URL", t('URL'), 255, "internallink", true, $values['PAGE_MULTI_URL'], $readO, 75);
        $return .= $oForm->createRadioFromList($multi."PAGE_MULTI_OPTION", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $values['PAGE_MULTI_OPTION'], true, $readO);
        $return .= $oForm->createHidden($multi."MULTI_HASH", $values["MULTI_HASH"]);
        return $return;
    }

    public static function multiCTA($oForm, $values, $readO, $multi, $perso, $extendedArgs = null)
    {
        // Champ synchronisation (perso)
        self::displaySyncField($oForm, $values, $readO, $multi, $perso, $extendedArgs, $return);

        $return .= $oForm->createInput($multi."PAGE_MULTI_LABEL", t('LIBELLE'), 255, "", true, $values['PAGE_MULTI_LABEL'], $readO, 75);
        $return .= $oForm->createInput($multi."PAGE_MULTI_URL", t('URL'), 255, "internallink", true, $values['PAGE_MULTI_URL'], $readO, 75);
        $return .= $oForm->createRadioFromList($multi."PAGE_MULTI_OPTION", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $values['PAGE_MULTI_OPTION'], true, $readO);
        $return .= $oForm->createHidden($multi."MULTI_HASH", $values["MULTI_HASH"]);
        return $return;
    }

    /**
     * Affiche le champ de synchronisation dans le formulaire $oForm
     * Ne doit être appelé que par self::multiPush ou self::multiCTA (gestion commune du champ synchronisation)
     */
    public static function displaySyncField(&$oForm, &$values, &$readO, &$multi, &$perso, &$extendedArgs, &$return)
    {
        // Debug
        $debugMode = isset($_COOKIE['debug']) && preg_match('#perso_sync#', $_COOKIE['debug']) ? true : false;
        if ($debugMode) {
            ob_start();
            echo '<div>$multi : '; var_dump($multi); echo '</div>';
            Synchronizer::debugUI($extendedArgs, array('title' => '$extendedArgs'));
            $return .= '<tr><td colspan="50"><div style="padding:10px; background:rgba(0,0,0,.15); margin:10px;">'.ob_get_clean().'</div></td></tr>';
        }

        if ($perso) {
            // Extraction nom du multi
            preg_match('#^perso_\d+_(.*?)(\d+|__CPT__)_$#i', $multi, $matches);
            $pushName = isset($matches[1]) ? $matches[1] : null;

            // Liste des hash des multi ajoutés
            $addedMulti = array();
            if (isset($extendedArgs['perso_multi_metadata']['added_multi_index'][$pushName])) {
                $addedMulti = $extendedArgs['perso_multi_metadata']['added_multi_index'][$pushName];
            }

            $synchroValues = array('-2' => t('ACTIVER_LA_PERSO_POUR_CE_PUSH'));
            if (isset($extendedArgs['perso_default_multi']) && is_array($extendedArgs['perso_default_multi'])) {
                foreach ($extendedArgs['perso_default_multi'] as $key => $val) {
                    if (empty($val['MULTI_HASH'])) {
                        continue;
                    }

                    // Nettoyage éléments vides
                    $valCheck = $val;
                    unset($valCheck[$pushName]);
                    unset($valCheck['MULTI_HASH']);
                    unset($valCheck['PAGE_MULTI_ID']);
                    unset($valCheck['']);
                    if (empty($valCheck)) {
                        continue;
                    }

                    // Exclusion des nouveaux éléments multi génériques (pour ne pas créer de double ajout avec la synchro add/del)
                    if (in_array($val['MULTI_HASH'], $addedMulti)) {
                        continue;
                    }

                    $titre = !empty($val['PAGE_MULTI_LABEL']) ? $val['PAGE_MULTI_LABEL'] : t('PAS_DE_TITRE_POUR_CE_PUSH');
                    $label = t('SYNCHRONISATION_AVEC_LE_PUSH').' '.substr($val['MULTI_HASH'], 0, 7).' "'.$titre.'"';
                    $synchroValues[$val['MULTI_HASH']] = htmlspecialchars($label);
                }
            }

            // Définition de la valeur du champ synchronisation : valeur enregistrée | multi générique | activer la perso
            $synchroValue = -2;
            if (isset($values['_sync'])) {
                $synchroValue = $values['_sync'];
            } elseif (!empty($values['MULTI_HASH'])) {
                $synchroValue = $values['MULTI_HASH'];
            }

            // Avertissement rétro-compatibilité + forçage synchro à "Activer la perso"
            $usesOldIdentifier = function ($values, &$id = null) {
                $hashPattern = '#^[0-9a-z]{40}$#i';
                $oldPattern = '#^\d+$#';
                if (isset($values['_sync'])) {
                    $id = $values['_sync'];
                    if ($values['_sync'] == -2 || preg_match($hashPattern, $values['_sync'])) {
                        return true;
                    } else {
                        return false;
                    }
                }
                $id = isset($values['PAGE_MULTI_ID']) ? $values['PAGE_MULTI_ID'] : null;
                if ($id == -2) {
                    return true;
                }
                return false;
            };
            $isMultiTemplate = preg_match('#__CPT__.{0,5}$#', $multi);
            if (!$usesOldIdentifier($values, $id) && $extendedArgs['context'] != 'newprofile' && !$isMultiTemplate) {
                $synchroValue = -2;
                $return .= '<tr><td colspan="50"><div style="padding:10px; background:rgba(255,0,0,.15); color:#a00; margin:10px 0 3px;">'
                    ."Cet élément multi utilise <b style=\"font-weight:bold;\">un ancien identifiant de synchronisation</b> (".$id.") à la place du hash. "
                    ."Veuillez sélectionner le multi générique ci-dessous :"
                    .'</div></td></tr>';
            }

            $return .= $oForm->createComboFromList($multi."_sync", t('PUSH_PERSO_ACTIVE'), $synchroValues, $synchroValue, true, $readO);
        } else {
            // Affichage de l'identifiant de l'élément mutli
            $return .= sprintf(
                '<tr><td class="formlib">%s</td><td class="formval"><a class="multi-hash-display" title="%s">%s</a></td></tr>',
                htmlspecialchars(t('MULTI_SLIDE_ID')),
                $values["MULTI_HASH"],
                substr($values["MULTI_HASH"], 0, 7)
            );

            // Ajout du champ PAGE_MULTI_ID (partie de la clé primaire qui est propre à chaque élément multi) en champ caché
            // (utilisé pour construire la liste déroulante synchronisation dans la popin)
            if( !empty($values["PAGE_MULTI_ID"])){
                $return .= $oForm->createHidden($multi."PAGE_MULTI_ID", $values["PAGE_MULTI_ID"]);
            }
        }
    }

    /**
     * @deprecated
     */
    public function moveAction ()
    {

        $oConnection = Pelican_Db::getInstance();

        Pelican_Db::$values["id"] = $this->getParam(0);
        Pelican_Db::$values["direction"] = $this->getParam(1);

        $change = $oConnection->updateOrder("#pref#_page", "PAGE_ORDER", "PAGE_ID", Pelican_Db::$values["id"], "", Pelican_Db::$values["direction"], "PAGE_PARENT_ID", false, "SITE_ID=" . $_SESSION[APP]['SITE_ID'] . " AND LANGUE_ID=" . $_SESSION[APP]['LANGUE_ID']);
        if ($change) {
            $_SESSION["MOVE"]["id"] = Pelican_Db::$values["id"];
            Pelican_Cache::clean("Frontend/Site/Tree", $_SESSION[APP]['SITE_ID']);
            Pelican_Cache::clean("Backend/Page", $_SESSION[APP]['SITE_ID']);
            $this->getRequest()
                ->addResponseCommand('script', array(
                    'value' => 'top.location.href=top.location.href;'
                ));
        }
    }


    public function movePageAction(){
        $aParams = $this->getParams();
        if($aParams['dragged']&&$aParams['target']&&isset($aParams['order'])){
            $oConnection = Pelican_Db::getInstance();

            //parent different: on lance la machine
            if($aParams['dragged']['pid']!=$aParams['target']['id']){
                $aPagesByLang = $this->getPages($aParams['dragged']['id'],$aParams['target']['id'],$aParams['order']);
                $this->updateChildren($aParams['target']['id'],$aParams['target']['path'],$aParams['dragged']['id'],$aParams['order'], $aPagesByLang);
            }
            if($aParams['order']  !=  $aParams['dragged']['order'] || $aParams['dragged']['pid']!=$aParams['target']['id']){
                $oConnection =      Pelican_Db::getInstance();
                $iParentId   =      $aParams['target']['id'];

                $this->ordonnancementPagesByPageParent( $iParentId );

                $aBind = array(
                    ':PAGE_ID'          =>  $aParams['dragged']['id'],
                    ':PAGE_ORDER'       =>  $aParams['order'],
                    ':PAGE_PARENT_ID'   =>  $iParentId
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
            Pelican_Db::$values['DRAG_N_DROP']      =   true;
            $_REQUEST["PAGE_ID"]                    =   $aParams['dragged']['id'];
            $_REQUEST["PAGE_PARENT_ID"]             =   $aParams['target']['id'];
            $_REQUEST['SITE_ID']                    =   $_SESSION[APP]['SITE_ID'];

            $this->execDecache();
            /** decache lié au mouvement des pages vehicules non maj en front **/
            Pelican_Cache::clean("Frontend/Citroen/VehiculesParGamme");
        }
    }

    //réorganisation des pages pour éliminer toute possibilité de gap
    public function ordonnancementPagesByPageParent( $iParentId = 0 ){
        $oConnection = Pelican_Db::getInstance();
        $aBind = array(
            ':PAGE_PARENT_ID'   =>  $iParentId,
            ':SITE_ID'          =>  $_SESSION[APP]['SITE_ID']
        );

        $sGetAllPagesSql = "SELECT p.PAGE_ID,p.PAGE_ORDER FROM #pref#_page p
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
        $aPages = $oConnection->queryTab($sGetAllPagesSql, $aBind);

        if(count($aPages)){
            $aPagesReordered=array();
            foreach ($aPages as $i=>$aPage){
                $aPagesReordered[] = array(
                    'PAGE_ID'       =>  $aPage['PAGE_ID'],
                    'PAGE_ORDER'    =>  $i
                );
            }

            foreach($aPagesReordered as $aOnePagesReordered ){
                $aBind = array(
                    ':PAGE_ID'      =>  $aOnePagesReordered['PAGE_ID'],
                    ':SITE_ID'      =>  $_SESSION[APP]['SITE_ID'],
                    ':NEW_ORDER'    =>  $aOnePagesReordered['PAGE_ORDER']
                );
                $sReorderPagesSql = 'UPDATE #pref#_page set PAGE_ORDER = :NEW_ORDER WHERE  PAGE_ID = :PAGE_ID AND  SITE_ID=:SITE_ID ';
                $query = $oConnection->query($sReorderPagesSql, $aBind);
            }
        }
    }


    public function updateChildren($iParentId,$sParentPath,$iPageId,$iOrder=null,$aPagesByLang=null){

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



        $aBind=array(
            ':PAGE_ID'=>$iPageId,
            ':PARENT_ID'=>$iParentId
            //':ORDER'=>$iOrder
        );

        //fetch pages

        $aPages = $oConnection->queryTab($sFindPagesSql, $aBind);

        $aPagesByLang = array();
        //create parents in available languages.
        foreach ($aPages as $page) {
            $aPagesByLang[$page['LANGUE_ID']] =$page;
            $this->addCmsPageParentByLanguage($iParentId,$page['LANGUE_ID']);
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

        //$aParentIds = explode('#',$aParams['target']['path']);//what for?
        $aParentIds = explode('#',$sParentPath);//what for?

        //find direct parents
        //$aBind[':PAGE_ID'] = $aParams['target']['id'];
        $aBind[':PAGE_ID'] = $iParentId;
        $aParents = $oConnection->queryTab($sFindPagesSql, $aBind);
        //Update des donn?es de la page
        //on prends le premier page path vu que c'est des valeurs numeriques
        $sNewPagePath = sprintf('%s#%s',$aParents[0]['PAGE_PATH'],$iPageId);
        //generate sql queries to updates nodes

        foreach ($aParents as $parent) {

            if(isset($aPagesByLang[$parent['LANGUE_ID']])){
                $sNewPagePathLib = sprintf(
                    '%s#%s|%s',
                    $parent['PAGE_LIBPATH'],
                    $iPageId,
                    trim(
                        $aPagesByLang[$parent['LANGUE_ID']]['PAGE_TITLE_BO']
                    )
                );

                $aBind=array(
                    ':PAGE_ID'=>$iPageId,// dragged_id was here
                    ':PAGE_PARENT_ID'=>intval($parent['PAGE_ID']),
                    ':PAGE_PATH'=>$oConnection->strtobind($sNewPagePath),
                    ':PAGE_LIBPATH'=>$oConnection->strtobind($sNewPagePathLib),
                    ':LANGUE_ID'=>$parent['LANGUE_ID']
                );

                $sUpdatePageSql = "UPDATE #pref#_page set PAGE_PARENT_ID=:PAGE_PARENT_ID,PAGE_PATH=:PAGE_PATH, PAGE_LIBPATH=:PAGE_LIBPATH where PAGE_ID=:PAGE_ID AND LANGUE_ID=:LANGUE_ID";
                $oConnection->query($sUpdatePageSql, $aBind);
                $aBind = array(
                    ':PAGE_PARENT_ID'=>$iPageId
                );
                $sGetChildrenSql = "SELECT p.PAGE_ID FROM #pref#_page as p WHERE p.PAGE_PARENT_ID=:PAGE_PARENT_ID GROUP BY PAGE_ID";
                $aChildren = $oConnection->queryTab($sGetChildrenSql,$aBind);

                if(count($aChildren)){
                    //public function updateChildren($iParentId,$sParentPath,$iPageId,$aPagesByLang)
                    foreach ($aChildren as $aChildPage) {
                        $this->updateChildren($iPageId,$aChildPage['PAGE_PATH'],$aChildPage['PAGE_ID']);
                    }

                }
            }
            //getChildren of current node

        }
        $oConnection->commit();
    }

    protected function getPages($page_id,$parent_id,$order=null){
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



        $aBind=array(
            ':PAGE_ID'=>$page_id,
            ':PARENT_ID'=>$parent_id,
            //':ORDER'=>$order
        );

        //fetch pages

        $aPages = $oConnection->queryTab($sFindPagesSql, $aBind);

        $aPagesByLang = array();
        //create parents in available languages.
        foreach ($aPages as $page) {
            $aPagesByLang[$page['LANGUE_ID']] =$page;
            $this->addCmsPageParentByLanguage($parent_id,$page['LANGUE_ID']);
        }

        return $aPagesByLang;

    }

    public function saveAction ()
    {
        $oConnection = Pelican_Db::getInstance();

        //Pelican_Db::$values['OLD_PAGE_TITLE_BO'] = Pelican_Db::$values['PAGE_TITLE_BO'];

        /*if(Pelican_Db::$values["STATE_ID"] == Pelican::$config["CORBEILLE_STATE"]){
            //mise de pages enfants  et des contenus
            $pageId   =   Pelican_Db::$values["PAGE_ID"];
            Pelican_Db::$values = array();
            $this->_updateChildPage($pageId, Pelican::$config["CORBEILLE_STATE"]);
            $_SESSION[APP]['PAGE_RETURN'] = "<script type=\"text/javascript\">top.location.href=top.location.href;</script>";
            //$this->redirectRequest();
        }else{*/



        if(Pelican_Db::$values['STATE_ID'] == 4){
            $this->aBind[':PAGE_ID'] = Pelican_Db::$values["PAGE_ID"];
            $diffusion = $oConnection->getRow("select PAGE_DIFFUSION from #pref#_page where PAGE_ID = :PAGE_ID", $this->aBind);
            if(true == $diffusion['PAGE_DIFFUSION']){
                $this->sendMailDiffusion(Pelican_Db::$values['SITE_ID'], Pelican_Db::$values['PAGE_TITLE_BO']);
                Pelican_Db::$values["PAGE_DIFFUSION"] = false;
            }
        }

        if (Pelican_Db::$values["PAGE_ID"] == - 2) {
            /** cas de la création */
            $_SESSION[APP]['PAGE_RETURN'] = "<script type=\"text/javascript\">top.location.href=top.location.href;</script>";
        }

        if (strpos(Pelican_Db::$values["PAGE_PATH"], '#') == 0) {
            Pelican_Db::$values["PAGE_PATH"] = substr_replace(Pelican_Db::$values["PAGE_PATH"], '', 0, 1);
        }
		if(Pelican_Db::$values['TEMPLATE_PAGE_ID'] != Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']){
			if(isset(Pelican_Db::$values['PAGE_DUO_COLORS']) && !empty(Pelican_Db::$values['PAGE_DUO_COLORS'])){
				$aColors = explode('/',Pelican_Db::$values['PAGE_DUO_COLORS']);
				if(is_array($aColors)){
					Pelican_Db::$values["PAGE_PRIMARY_COLOR"] = $aColors[0];
					Pelican_Db::$values["PAGE_SECOND_COLOR"] = $aColors[1];
				}
			}elseif(empty(Pelican_Db::$values['PAGE_DUO_COLORS'])){
				Pelican_Db::$values["PAGE_PRIMARY_COLOR"] = '';
				Pelican_Db::$values["PAGE_SECOND_COLOR"] = '';
			}
		}

        if (isset(Pelican_Db::$values["PAGE_CREATION_USER"]) && (strpos(Pelican_Db::$values["PAGE_CREATION_USER"], '#') === false)) {
            Pelican_Db::$values["PAGE_CREATION_USER"] = '#' . Pelican_Db::$values["PAGE_CREATION_USER"] . '#';
        }

        if (Pelican_Db::$values["PAGE_TEXT"] && (! Pelican_Db::$values["PAGE_META_DESC"] || Pelican_Db::$values["PAGE_META_DESC"] == "")) {
            Pelican_Db::$values["PAGE_META_DESC"] = Pelican_Db::$values["PAGE_TEXT"];
        }

        if (! Pelican_Db::$values["PAGE_DISPLAY_SEARCH"])
            Pelican_Db::$values["PAGE_DISPLAY_SEARCH"] = 0;

        if (! Pelican_Db::$values["PAGE_DISPLAY_IN_ARIANE"])
            Pelican_Db::$values["PAGE_DISPLAY_IN_ARIANE"] = 0;

        if (Pelican_Db::$values["PAGE_TITLE"] && (! Pelican_Db::$values["PAGE_META_TITLE"] || Pelican_Db::$values["PAGE_META_TITLE"] == "")) {
            Pelican_Db::$values["PAGE_META_TITLE"] = Pelican_Db::$values["PAGE_TITLE"];
        }
        if(Pelican_Db::$values["PAGE_START_DATE"]){
            Pelican_Db::$values["PAGE_START_DATE"] = Pelican_Db::$values["PAGE_START_DATE"]." ".Pelican_Db::$values["PAGE_START_DATE_HEURE"].":00";
        }
        if(Pelican_Db::$values["PAGE_END_DATE"]){
            Pelican_Db::$values["PAGE_END_DATE"] = Pelican_Db::$values["PAGE_END_DATE"]." ".Pelican_Db::$values["PAGE_END_DATE_HEURE"].":00";
        }
        include_once ('Pelican/Taxonomy.php');
        $oTaxonomy = Pelican_Factory::getInstance('Taxonomy');
        $oTaxonomy->saveTermsRelationships(array(
            'TAXONOMY' ,
            'TAXONOMY2'
        ), Pelican_Db::$values["PAGE_ID"], 3);

        /** Page générale */
        $aPageType = Pelican_Cache::fetch("PageType/Template", array(
            Pelican_Db::$values['TEMPLATE_PAGE_ID']
        ));
        Pelican_Db::$values['PAGE_GENERAL'] = ($aPageType["PAGE_TYPE_CODE"] == 'GENERAL' ? "1" : "0");

        // Suppression de l'apostrophe de Word
        Pelican_Db::$values['PAGE_TITLE'] = htmlspecialchars(Pelican_Db::$values['PAGE_TITLE']);
        Pelican_Db::$values['PAGE_TITLE_BO'] = htmlspecialchars(Pelican_Db::$values['PAGE_TITLE_BO']);

        $TRUE_FORM = Pelican_Db::$values;

        /** clean associated contents */
        if (Pelican_Db::$values["PAGE_VERSION"]) {
            $this->aBind[":PAGE_ID"] = Pelican_Db::$values["PAGE_ID"];
            $this->aBind[":PAGE_VERSION"] = Pelican_Db::$values["PAGE_VERSION"];
            $this->aBind[":LANGUE_ID"] = Pelican_Db::$values['LANGUE_ID'];
            $sSql = "delete from #pref#_page_version_content where PAGE_ID=:PAGE_ID and PAGE_VERSION=:PAGE_VERSION and LANGUE_ID = :LANGUE_ID";
            $oConnection->query($sSql, $this->aBind);
        }

        if ($this->form_action != Pelican_Db::DATABASE_DELETE) {
            //mise a jour des modules
            /** ATTENTION, la création d'une version rend inactif l'update de la table #pref#_page ($oConnection->tableStopList) */

            $oConnection->updateTable($this->form_action, "#pref#_page");

            /** Mise à jour des chemins */
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
                    $arrayPageLibPath[] = $parentPage['PAGE_PARENT_ID'] . '|' . $parentPage['PAGE_TITLE_BO'];
                    $parentPage = $oConnection->queryRow($sqlParent, $this->aBind);
                    $limitloop --;
                }
                $arrayPageLibPath = array_reverse($arrayPageLibPath);
                $arrayPageLibPath[] = Pelican_Db::$values["PAGE_ID"] . "|" . Pelican_Db::$values["PAGE_TITLE_BO"];
                $newpath = implode('#', $arrayPageLibPath);

                $oldpath = Pelican_Db::$values["PAGE_LIBPATH"];
                $this->aBind[":PAGE_LIBPATH"] = $oConnection->strtobind($newpath);

                // Mise à jour du chemin pour la page courante */
                $sql = "update #pref#_page set PAGE_PATH=:PAGE_PATH,PAGE_LIBPATH=:PAGE_LIBPATH where PAGE_ID=:PAGE_ID AND LANGUE_ID=:LANGUE_ID";
                $oConnection->query($sql, $this->aBind);

                $this->aBind[":PATH"] = $oConnection->strToBind(implode("#", $path) . "#%");
                $aDecachePath = $oConnection->getTab("select distinct PAGE_ID from #pref#_page where PAGE_PATH like :PATH", $this->aBind);

                // Répercution du nouveau chemin de la page courante sur les chemin des pages filles
                if ($oldpath != $newpath) {
                    $this->aBind[":PAGE_PATH"] = $oConnection->strtobind(implode("#", $path) . "#%");

                    $this->aBind[":PAGE_OLDPATH"] = $oConnection->strtobind($oldpath . "#");
                    $this->aBind[":PAGE_NEWPATH"] = $oConnection->strtobind($newpath . "#");
                    $this->aBind[":LANGUE_ID"] = Pelican_Db::$values['LANGUE_ID'];
                    $sql = "update #pref#_page set PAGE_LIBPATH=REPLACE(PAGE_LIBPATH, :PAGE_OLDPATH, :PAGE_NEWPATH)  where PAGE_PATH like :PAGE_PATH AND LANGUE_ID=:LANGUE_ID";
                    $oConnection->query($sql, $this->aBind);

                    Pelican_Db::$values["PAGE_LIBPATH"] = $this->aBind[":PAGE_NEWPATH"];

                }
                if ($aDecachePath) {
                    foreach ($aDecachePath as $path) {
                        //On décache les données du fil d'Ariane pour toutes les pages filles et parentes
                        Pelican_Cache::clean("Frontend/Page/Path", $path["PAGE_ID"]);
                    }
                }

            }

            // Synchronisation (perso) : mise à jour des données pour les multi perso synchronisés
            try {
                $verbose = isset($_COOKIE['debug']) && preg_match('#perso_sync#', $_COOKIE['debug']) ? true : false;
                $persoData = Synchronizer::unserialize(Pelican_Db::$values['PAGE_PERSO']);
                $sync = new Synchronizer($persoData, Pelican_Db::$values, $_POST, $verbose);
                $sync->sync('PUSH', 3);
                $sync->sync('PUSH_OUTILS_MAJEUR', 1);
                $sync->sync('PUSH_OUTILS_MINEUR', 4);
                $sync->sync('PUSH_CONTENU_ANNEXE', 2);
                Pelican_Db::$values['PAGE_PERSO'] = Synchronizer::serialize($sync->persoData);
            } catch (Exception $ex) {
                switch ($ex->getCode()) {
                    case Synchronizer::EX_UNREADABLE_PERSODATA:
                        break;
                    default:
                        trigger_error($ex->getMessage(), E_USER_WARNING);
                        break;
                }
            }

            $oConnection->updateTable($this->form_action, "#pref#_page_version");

            // avant la planification des blocs
            $this->aBind[":PAGE_ID"] = Pelican_Db::$values["PAGE_ID"];
            $this->aBind[":PAGE_VERSION"] = Pelican_Db::$values["PAGE_VERSION"];
            $this->aBind[":PAGE_ID"] = Pelican_Db::$values["PAGE_ID"];
            $this->aBind[":LANGUE_ID"] = Pelican_Db::$values['LANGUE_ID'];

            $aCountChildPages = $this->getCountChildPages(Pelican_Db::$values['PAGE_PARENT_ID']);


            if((int)$aCountChildPages['count_children']>1){
                $iOrder = (int)$aCountChildPages['count_children']-1;
            }else{
                $iOrder = 0;
            }


            if(     isset(Pelican_Db::$values["PAGE_ORDER"])&&
                Pelican_Db::$values["PAGE_ORDER"]!==null &&
                Pelican_Db::$values["PAGE_ORDER"]!==''
            ){

                $this->aBind[":PAGE_ORDER"] = Pelican_Db::$values["PAGE_ORDER"];

            }else{
                $this->aBind[":PAGE_ORDER"]=$iOrder;
            }

            // MISE A JOUR des liens CTA en fonction de l'adresse réelle si elle change
            $this->updateLinkCTA();

            /*PERSO*/

            if(Pelican_Db::$values["PROFILE_LIST"] != ''){
                $sSQL = "delete from #pref#_perso_profile_page where PAGE_ID=:PAGE_ID";
                $oConnection->query($sSQL, $this->aBind);
                $aListProfile = explode('##',Pelican_Db::$values["PROFILE_LIST"] );

                if(is_array($aListProfile) && count($aListProfile)>0){
                    foreach($aListProfile as $profile){
                        $aProfile = explode('_',$profile);
                        $this->aBind[":ORDRE_PROFILE"] = $aProfile[0];
                        $this->aBind[":PROFILE_ID"] = $aProfile[1];
                        $this->aBind[":INDICATEUR_ID"] = isset($aProfile[2]) ?  $aProfile[2] : 0;
                        $this->aBind[":PRODUCT_ID"] =  isset($aProfile[3]) ?  $aProfile[3] : 0;
                        $sSQL = "
                                SELECT
                                    COUNT(*)
                                FROM
                                    #pref#_perso_profile_page
                                WHERE
                                    PAGE_ID = :PAGE_ID
                                and PROFILE_ID = :PROFILE_ID
                                and INDICATEUR_ID = :INDICATEUR_ID
                                and PRODUCT_ID = :PRODUCT_ID
                            ";
                        $countUse = $oConnection->queryItem($sSQL, $this->aBind);
                        if($countUse == '0'){
                            Pelican_Db::$values["ORDRE_PROFILE"] = $this->aBind[":ORDRE_PROFILE"];
                            Pelican_Db::$values["PROFILE_ID"] = $this->aBind[":PROFILE_ID"];
                            Pelican_Db::$values["INDICATEUR_ID"] = $this->aBind[":INDICATEUR_ID"];
                            Pelican_Db::$values["PRODUCT_ID"] = $this->aBind[":PRODUCT_ID"];
                            $oConnection->updateTable(Pelican::$config['DATABASE_INSERT'], "#pref#_perso_profile_page");
                        }
                    }
                }
            }

            /**/
            $sSQL = "delete from #pref#_page_multi where PAGE_ID=:PAGE_ID and LANGUE_ID=:LANGUE_ID and PAGE_VERSION=:PAGE_VERSION";
            $oConnection->query($sSQL, $this->aBind);
            $aMultiGeneral = array('PUSH', 'PUSH_OUTILS_MAJEUR', 'PUSH_OUTILS_MINEUR', 'PUSH_CONTENU_ANNEXE');
            foreach($aMultiGeneral as $multiGeneral) {
                readMulti($multiGeneral, $multiGeneral);
                if (Pelican_Db::$values[$multiGeneral]) {
                    Pelican_Db::$values['PAGE_MULTI_TYPE'] = $multiGeneral;
                    unset($id);
                    foreach (Pelican_Db::$values[$multiGeneral] as $i => $item) {
                        if ($item['multi_display'] == 1) {
                            $id++;
                            $DBVALUES_SAVE = Pelican_Db::$values;
                            Pelican_Db::$values['MEDIA_ID']='';
                            Pelican_Db::$values['PAGE_MULTI_ID'] = $id;
                            Pelican_Db::$values['PAGE_MULTI_LABEL'] = $item['PAGE_MULTI_LABEL'];
                            if ($item['MEDIA_ID']) {
                                Pelican_Db::$values['MEDIA_ID'] = $item['MEDIA_ID'];
                            }
                            Pelican_Db::$values['PAGE_ZONE_MULTI_ORDER'] = $item['PAGE_ZONE_MULTI_ORDER'];
                            Pelican_Db::$values['PAGE_MULTI_URL'] = $item['PAGE_MULTI_URL'];
                            Pelican_Db::$values['PAGE_MULTI_OPTION'] = $item['PAGE_MULTI_OPTION'];
                            Pelican_Db::$values['MULTI_HASH'] = $item['MULTI_HASH'];
                            $oConnection->updateTable(Pelican::$config['DATABASE_INSERT'], "#pref#_page_multi");
                            Pelican_Db::$values = $DBVALUES_SAVE;
                        }
                    }
                }
            }


            $oConnection->query("update #pref#_page set PAGE_ORDER = :PAGE_ORDER WHERE PAGE_ID=:PAGE_ID", $this->aBind);
            $this->ordonnancementPagesByPageParent( Pelican_Db::$values['PAGE_PARENT_ID'] );
            $_SESSION["MOVE"]["id"] = Pelican_Db::$values["id"];

            $oConnection->query("delete from #pref#_navigation where PAGE_ID=:PAGE_ID and PAGE_VERSION=:PAGE_VERSION AND LANGUE_ID = :LANGUE_ID", $this->aBind);
            $oConnection->query("delete from #pref#_page_zone_content where PAGE_ID=:PAGE_ID and PAGE_VERSION=:PAGE_VERSION AND LANGUE_ID = :LANGUE_ID", $this->aBind);
            $oConnection->query("delete from #pref#_page_zone_media where PAGE_ID=:PAGE_ID and PAGE_VERSION=:PAGE_VERSION AND LANGUE_ID = :LANGUE_ID", $this->aBind);

            $oConnection->query("delete from #pref#_page_zone where PAGE_ID=:PAGE_ID AND PAGE_VERSION=:PAGE_VERSION AND LANGUE_ID = :LANGUE_ID", $this->aBind);
            $oConnection->query("delete from #pref#_page_version_media where PAGE_ID=:PAGE_ID AND PAGE_VERSION=:PAGE_VERSION AND LANGUE_ID = :LANGUE_ID", $this->aBind);

            //Mise à jour des Pelican_Media associés à la page
            for ($i = 1; $i <= 5; $i ++) {
                Pelican_Db::$values["MEDIA_ID"] = Pelican_Db::$values["MEDIA_ID_" . $i];
                if (Pelican_Db::$values["MEDIA_ID"]) {
                    Pelican_Db::$values["PAGE_MEDIA_TYPE"] = "IMG" . $i;
                    $oConnection->updateTable(Pelican_Db::DATABASE_INSERT, "#pref#_page_version_media");
                }
            }

            readMulti("page");

            //mise a jours des modules
            $this->monoValues = Pelican_Db::$values;

            if ($this->monoValues["ZONE_DB"]) {

                $zoneTotal = count($this->monoValues["ZONE_TEMPLATE_ID"]);
                $zoneCompte = 0;

                foreach ($this->monoValues["ZONE_TEMPLATE_ID"] as $index => $id) {
                    $zoneCompte ++;

                    $db = $this->monoValues["ZONE_DB"][$index];

                    Pelican_Db::$values = $this->monoValues["page"][$index];
                    /*if (Pelican_Db::$values["ZONE_TYPE_ID"] == 2) {
                        $db = "db_standard.php";
                    }*/

                    Pelican_Db::$values["PAGE_ID"] = $this->monoValues["PAGE_ID"];
                    Pelican_Db::$values["PAGE_VERSION"] = $this->monoValues["PAGE_VERSION"];
                    Pelican_Db::$values['LANGUE_ID'] = $this->monoValues['LANGUE_ID'];

                    //??$this->form_action = $this->monoValues["form_action"];
                    Pelican_Db::$values["PAGE_ID_OLDORIGINE"] = $this->monoValues["PAGE_ID"];
                    Pelican_Db::$values["PAGE_VERSION_ORIGINE"] = $this->monoValues["PAGE_VERSION"];

                    if (Pelican_Db::$values['ZONE_DB_MULTI']) {
                        $db = true;
                    }

                    /*             if (!$db) {
                        $db = "Cms_Page_Module";
                        if ($del) {
                            $data = Pelican_Db::$values["ZONE_TEMPLATE_ID"];
                            Pelican_Db::$values = "";
                            Pelican_Db::$values["PAGE_ID"] = $this->monoValues["PAGE_ID"];
                            Pelican_Db::$values['LANGUE_ID'] = $this->monoValues['LANGUE_ID'];
                            Pelican_Db::$values["PAGE_VERSION"] = $this->monoValues["PAGE_VERSION"];
                            Pelican_Db::$values["ZONE_TEMPLATE_ID"] = $data;
                            Pelican_Db::$values["ZONE_EMPTY"] = 1;
                            $del = false;
                        }
                    }*/

                    $root = (Pelican_Db::$values["PLUGIN_ID"] ? Pelican::$config["PLUGIN_ROOT"] . '/' . Pelican_Db::$values["PLUGIN_ID"] . '/backend/controllers' : Pelican::$config['APPLICATION_CONTROLLERS']);
                    $module = $root . '/' . str_replace("_", "/", $db) . ".php";
                    $moduleClass = $db;

                    if (! Pelican_Db::$values["ZONE_DB_MULTI"]) {
                        if (file_exists($module)) {
                            include_once ($module);

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

                            call_user_func_array(array(
                                $moduleClass ,
                                'save'
                            ), array(
                                $this
                            ));

                            call_user_func_array(array(
                                $moduleClass ,
                                'addCache'
                            ), array(
                                $this
                            ));


                            // } elseif (file_exists(Pelican::$config["PLUGIN_ROOT"] . "/" . $db . "/application/sites/backend/actions/Bloc.php")) {
                            //     include(Pelican::$config["PLUGIN_ROOT"] . "/" . $db . "/application/sites/backend/actions/Bloc.php");
                        }
                    } else {

                        $TEMP_FORM = Pelican_Db::$values;

                        /** @todo multi
                        Pelican_Db::$values = $TRUE_FORM;
                        readMulti("multiZone" . $TEMP_FORM["ZONE_TEMPLATE_ID"], "multiZone" . $TEMP_FORM["ZONE_TEMPLATE_ID"]);
                        include(Pelican::$config["TRANSACTION_ROOT"] .  $TEMP_FORM["ZONE_DB_MULTI"]);
                         */
                        Pelican_Db::$values = $TEMP_FORM;

                    }

                }
            }

            //mise a jours des modules
            Pelican_Db::$values = $this->monoValues;

            $this->saveMultiZones();


            /** cas d'une suppression de version */
            if ($this->workflowFieldDeleteVersion) {

                foreach ($this->workflowFieldDeleteVersion as $delete_version) {
                    // => initialisation de la version à supprimer (on en a fait la sauvegarde juste avant)
                    Pelican_Db::$values["PAGE_VERSION"] = $delete_version;
                    $this->form_action = Pelican_Db::DATABASE_DELETE;
                    $this->_deletePage($delete_version);
                }
            }

            Pelican_Db::$values = $this->monoValues;
            if(Pelican_Db::$values["STATE_ID"] == Pelican::$config["CORBEILLE_STATE"]){
                //mise de pages enfants  et des contenus
                $pageId   =   Pelican_Db::$values["PAGE_ID"];
                //Pelican_Db::$values = array();
                $this->_updateChildPage($pageId, Pelican::$config["CORBEILLE_STATE"]);

                // Vidage du cache pour prendre en compte la suppression des pages fille
                // Caches basés sur le SITE_ID
                Pelican_Cache::clean("Frontend/Citroen/Configuration", array(Pelican_Db::$values['SITE_ID']));
                Pelican_Cache::clean("Frontend/Citroen/Home/Actualites", array(Pelican_Db::$values['SITE_ID']));
                Pelican_Cache::clean("Frontend/Citroen/Navigation", array(Pelican_Db::$values['SITE_ID']));
                Pelican_Cache::clean("Frontend/Citroen/VehiculeShowroomById", array(Pelican_Db::$values['SITE_ID']));
                Pelican_Cache::clean("Frontend/Citroen/VehiculesParGamme", array(Pelican_Db::$values['SITE_ID']));
                Pelican_Cache::clean("Frontend/Page/Template", array(Pelican_Db::$values['SITE_ID']));
                Pelican_Cache::clean("Frontend/Url", array(Pelican_Db::$values['SITE_ID']));
                Pelican_Cache::clean("Request/Redirect", array(Pelican_Db::$values['SITE_ID']));

                // Caches basés sur le PAGE_ID
                if (!empty($this->trashUpdatedPages)) {
                    foreach ($this->trashUpdatedPages as $val) {
                        Pelican_Cache::clean("Frontend/Citroen/FilAriane", array($val));
                        Pelican_Cache::clean("Frontend/Citroen/HeritageGrandVisuel", array($val));
                        Pelican_Cache::clean("Frontend/Citroen/StickyBar", array($val));
                        Pelican_Cache::clean("Frontend/Page", array($val));
                        Pelican_Cache::clean("Frontend/Page/Zone", array($val));
                        Pelican_Cache::clean("Frontend/Citroen/MasterPageVehiculesN1", array($val));
                    }
                }

                $_SESSION[APP]['PAGE_RETURN'] = "<script type=\"text/javascript\">top.location.href=top.location.href;</script>";
                //$this->redirectRequest();
            }
        } else {
            $this->_deletePage();
            $oConnection->deleteQuery("#pref#_page");
            $_SESSION[APP]['PAGE_RETURN'] = "<script type=\"text/javascript\">top.location.href=top.location.href;</script>";
        }

        $oConnection->commit();

        // Vidage du cache de la home lors de l'enregistrement de la Master page vehicule N1
        // (car la home utilise les mentions légales de la MPVN1)
        if (Pelican_Db::$values["TEMPLATE_PAGE_ID"] == Pelican::$config['TEMPLATE_PAGE']['MASTER_PAGE_VEHICULES_N1']) {
            Pelican_Cache::clean("Frontend/Page/ZoneTemplateIdLangue");
        }

        Pelican_Cache::clean("Frontend/Citroen/ZoneMulti");
        Pelican_Cache::clean("Frontend/Citroen/ZonesContentMulti");
        Pelican_Cache::clean("Frontend/Page/ZoneTemplate");
        Pelican_Cache::clean("Frontend/Page/Content");
        Pelican_Cache::clean("Frontend/Page/Zone/Content");
        Pelican_Cache::clean("Frontend/Page/ZoneMulti");
        Pelican_Cache::clean("Frontend/Citroen/MasterPageVehiculesN1");
        Pelican_Cache::clean("Frontend/Page/Showroom");
    }

    public function updateLinkCTA() {
        $oConnection = Pelican_Db::getInstance();
        $aParams = $this->getParams();
        $this->aBind[':SITE_ID'] = $aParams['SITE_ID'];
        $this->aBind[':LANGUE_ID'] = $aParams['LANGUE_ID'];

        // si Adresse réelle a était changé
        if(!empty(Pelican_Db::$values["OLD_URL"]) && Pelican_Db::$values["OLD_URL"] != Pelican_Db::$values['PAGE_CLEAR_URL']){

            $this->aBind[':CTA_NEW_URL'] = $oConnection->strtobind(Pelican_Db::$values['PAGE_CLEAR_URL']);
            $this->aBind[':CTA_OLD_URL'] = $oConnection->strtobind(Pelican_Db::$values['OLD_URL']);

            /*
            $aChampPageMulti = array("PAGE_ZONE_MULTI_URL", "PAGE_ZONE_MULTI_URL2", "PAGE_ZONE_MULTI_URL3", "PAGE_ZONE_MULTI_URL4", "PAGE_ZONE_MULTI_URL5", "PAGE_ZONE_MULTI_URL6", "PAGE_ZONE_MULTI_URL7", "PAGE_ZONE_MULTI_URL8");
            $aChampPage = array("ZONE_URL", "ZONE_URL2", "ZONE_TITRE3",	"ZONE_TITRE5", "ZONE_TITRE6", "ZONE_TITRE7", "ZONE_TITRE8", "ZONE_TITRE9", "ZONE_TITRE10", "ZONE_TITRE11", "ZONE_TITRE12", "ZONE_TEXTE3", "ZONE_TEXTE6");
              */
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
            if(!empty($aContent) && is_array($aContent)){
                foreach($aContent as $i=>$champ){
                    $this->aBind[':ID'] = $champ["CONTENT_ID"];
                    $this->aBind[':VERSION'] = $champ["MAX_VERS"];
                    if(!empty($champ["CONTENT_URL2"]) && $champ["CONTENT_URL2"] == Pelican_Db::$values["OLD_URL"]){
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
            if(!empty($aContentMulti) && is_array($aContentMulti)){
                foreach($aContentMulti as $i=>$champ){
                    $this->aBind[':ID'] = $champ["CONTENT_ID"];
                    $this->aBind[':VERSION'] = $champ["MAX_VERS"];
                    if(!empty($champ["CONTENT_ZONE_MULTI_URL"]) && $champ["CONTENT_ZONE_MULTI_URL"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_content_zone_multi SET CONTENT_ZONE_MULTI_URL=:CTA_NEW_URL where CONTENT_ID=:ID AND CONTENT_VERSION=:VERSION AND CONTENT_ZONE_MULTI_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["CONTENT_ZONE_MULTI_URL2"]) && $champ["CONTENT_ZONE_MULTI_URL2"] == Pelican_Db::$values["OLD_URL"]){
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
            if(!empty($aContentMulti) && is_array($aContentMulti)){
                foreach($aContentMulti as $i=>$champ){
                    $this->aBind[':ID'] = $champ["PAGE_ID"];
                    $this->aBind[':VERSION'] = $champ["MAX_VERS"];
                    if(!empty($champ["PAGE_ZONE_MULTI_URL"]) && $champ["PAGE_ZONE_MULTI_URL"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_multi_zone_multi SET PAGE_ZONE_MULTI_URL=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND PAGE_ZONE_MULTI_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["PAGE_ZONE_MULTI_URL2"]) && $champ["PAGE_ZONE_MULTI_URL2"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_multi_zone_multi SET PAGE_ZONE_MULTI_URL2=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND PAGE_ZONE_MULTI_URL2=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["PAGE_ZONE_MULTI_URL3"]) && $champ["PAGE_ZONE_MULTI_URL3"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_multi_zone_multi SET PAGE_ZONE_MULTI_URL3=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND PAGE_ZONE_MULTI_URL3=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["PAGE_ZONE_MULTI_URL4"]) && $champ["PAGE_ZONE_MULTI_URL4"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_multi_zone_multi SET PAGE_ZONE_MULTI_URL4=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND PAGE_ZONE_MULTI_URL4=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["PAGE_ZONE_MULTI_URL5"]) && $champ["PAGE_ZONE_MULTI_URL5"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_multi_zone_multi SET PAGE_ZONE_MULTI_URL5=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND PAGE_ZONE_MULTI_URL5=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["PAGE_ZONE_MULTI_URL6"]) && $champ["PAGE_ZONE_MULTI_URL6"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_multi_zone_multi SET PAGE_ZONE_MULTI_URL6=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION  AND PAGE_ZONE_MULTI_URL6=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["PAGE_ZONE_MULTI_URL7"]) && $champ["PAGE_ZONE_MULTI_URL7"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_multi_zone_multi SET PAGE_ZONE_MULTI_URL7=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION  AND PAGE_ZONE_MULTI_URL7=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["PAGE_ZONE_MULTI_URL8"]) && $champ["PAGE_ZONE_MULTI_URL8"] == Pelican_Db::$values["OLD_URL"]){
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
            if(!empty($aContentMulti) && is_array($aContentMulti)){
                foreach($aContentMulti as $i=>$champ){
                    $this->aBind[':ID'] = $champ["PAGE_ID"];
                    $this->aBind[':VERSION'] = $champ["MAX_VERS"];
                    if(!empty($champ["PAGE_ZONE_MULTI_URL"]) && $champ["PAGE_ZONE_MULTI_URL"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_zone_multi SET PAGE_ZONE_MULTI_URL=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND PAGE_ZONE_MULTI_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["PAGE_ZONE_MULTI_URL2"]) && $champ["PAGE_ZONE_MULTI_URL2"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_zone_multi SET PAGE_ZONE_MULTI_URL2=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND PAGE_ZONE_MULTI_URL2=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["PAGE_ZONE_MULTI_URL3"]) && $champ["PAGE_ZONE_MULTI_URL3"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_zone_multi SET PAGE_ZONE_MULTI_URL3=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND PAGE_ZONE_MULTI_URL3=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["PAGE_ZONE_MULTI_URL4"]) && $champ["PAGE_ZONE_MULTI_URL4"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_zone_multi SET PAGE_ZONE_MULTI_URL4=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND PAGE_ZONE_MULTI_URL4=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["PAGE_ZONE_MULTI_URL5"]) && $champ["PAGE_ZONE_MULTI_URL5"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_zone_multi SET PAGE_ZONE_MULTI_URL5=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND PAGE_ZONE_MULTI_URL5=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["PAGE_ZONE_MULTI_URL6"]) && $champ["PAGE_ZONE_MULTI_URL6"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_zone_multi SET PAGE_ZONE_MULTI_URL6=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND PAGE_ZONE_MULTI_URL6=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["PAGE_ZONE_MULTI_URL7"]) && $champ["PAGE_ZONE_MULTI_URL7"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_zone_multi SET PAGE_ZONE_MULTI_URL7=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND PAGE_ZONE_MULTI_URL7=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["PAGE_ZONE_MULTI_URL8"]) && $champ["PAGE_ZONE_MULTI_URL8"] == Pelican_Db::$values["OLD_URL"]){
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
            if(!empty($aContentMulti) && is_array($aContentMulti)){
                foreach($aContentMulti as $i=>$champ){
                    $this->aBind[':ID'] = $champ["PAGE_ID"];
                    $this->aBind[':VERSION'] = $champ["MAX_VERS"];
                    if(!empty($champ["ZONE_URL"]) && $champ["ZONE_URL"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_zone SET ZONE_URL=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_URL=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["ZONE_URL2"]) && $champ["ZONE_URL2"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_zone SET ZONE_URL2=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_URL2=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["ZONE_TITRE3"]) && $champ["ZONE_TITRE3"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_zone SET ZONE_TITRE3=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_TITRE3=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["ZONE_TITRE5"]) && $champ["ZONE_TITRE5"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_zone SET ZONE_TITRE5=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_TITRE5=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["ZONE_TITRE6"]) && $champ["ZONE_TITRE6"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_zone SET ZONE_TITRE6=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_TITRE6=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["ZONE_TITRE7"]) && $champ["ZONE_TITRE7"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_zone SET ZONE_TITRE7=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_TITRE7=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["ZONE_TITRE8"]) && $champ["ZONE_TITRE8"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_zone SET ZONE_TITRE8=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_TITRE8=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["ZONE_TITRE9"]) && $champ["ZONE_TITRE9"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_zone SET ZONE_TITRE9=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_TITRE9=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["ZONE_TITRE10"]) && $champ["ZONE_TITRE10"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_zone SET ZONE_TITRE10=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_TITRE10=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["ZONE_TITRE11"]) && $champ["ZONE_TITRE11"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_zone SET ZONE_TITRE11=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_TITRE11=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["ZONE_TITRE12"]) && $champ["ZONE_TITRE12"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_zone SET ZONE_TITRE12=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_TITRE12=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["ZONE_TEXTE3"]) && $champ["ZONE_TEXTE3"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_zone SET ZONE_TEXTE3=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_TEXTE3=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                    if(!empty($champ["ZONE_TEXTE6"]) && $champ["ZONE_TEXTE6"] == Pelican_Db::$values["OLD_URL"]){
                        $oConnection->query("update #pref#_page_zone SET ZONE_TEXTE6=:CTA_NEW_URL where PAGE_ID=:ID AND PAGE_VERSION=:VERSION AND ZONE_TEXTE6=ZONE_URL=:CTA_OLD_URL", $this->aBind);
                    }
                }
            }
        }
    }

    public function saveMultiZones() {
        $oConnection = Pelican_Db::getInstance();

        if (Pelican_Db::$values['db_pageMulti'] && !empty(Pelican_Db::$values['db_pageMulti'])) {
            $oConnection->query("delete from #pref#_page_multi_zone_content where PAGE_ID=:PAGE_ID and PAGE_VERSION=:PAGE_VERSION AND LANGUE_ID = :LANGUE_ID", $this->aBind);
            $oConnection->query("delete from #pref#_page_multi_zone_media where PAGE_ID=:PAGE_ID and PAGE_VERSION=:PAGE_VERSION AND LANGUE_ID = :LANGUE_ID", $this->aBind);

            $oConnection->query("delete from #pref#_page_multi_zone where PAGE_ID=:PAGE_ID AND PAGE_VERSION=:PAGE_VERSION AND LANGUE_ID = :LANGUE_ID", $this->aBind);


            foreach(Pelican_Db::$values['db_pageMulti'] as $area_id) {

                unset(Pelican_Db::$values['page']);
                if (Pelican_Db::$values["MULTI_ZONE_" . $area_id . "_DB"]) {

                    $tmpKeys = array_keys(Pelican_Db::$values["MULTI_ZONE_" . $area_id . "_DB"]);
                    sort($tmpKeys);
                    Pelican_Db::$values["count_pageMulti" . $area_id] = $tmpKeys[count($tmpKeys)-1];
                    readMulti('pageMulti'.$area_id, 'multiZone'.$area_id.'_');

                    //mise a jours des modules
                    $this->monoValues = Pelican_Db::$values;

                    //if ($this->monoValues["MULTI_ZONE_" . $area_id . "_DB"]) {

                    $zoneTotal = count($this->monoValues["ZONE_TEMPLATE_ID"]);
                    $zoneCompte = 0;

                    foreach ($this->monoValues["MULTI_ZONE_" . $area_id . "_DB"] as $index => $db) {

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
                        Pelican_Db::$values["ZONE_ID"] = $this->monoValues["MULTI_ZONE_" . $area_id . "_ID"][$index];

                        if (Pelican_Db::$values['ZONE_DB_MULTI']) {
                            $db = true;
                        }

                        $root = (Pelican_Db::$values["PLUGIN_ID"] ? Pelican::$config["PLUGIN_ROOT"] . '/' . Pelican_Db::$values["PLUGIN_ID"] . '/backend/controllers' : Pelican::$config['APPLICATION_CONTROLLERS']);
                        $module = $root . '/' . str_replace("_", "/", $db) . ".php";
                        $moduleClass = $db;

                        if (! Pelican_Db::$values["ZONE_DB_MULTI"]) {

                            if (file_exists($module)) {
                                include_once ($module);

                                call_user_func_array(array(
                                    $moduleClass ,
                                    'save'
                                ), array(
                                    $this
                                ));

                                call_user_func_array(array(
                                    $moduleClass ,
                                    'addCache'
                                ), array(
                                    $this
                                ));
                            }
                        } else {

                            $TEMP_FORM = Pelican_Db::$values;


                            Pelican_Db::$values = $TEMP_FORM;

                        }

                    }
                }
            }

            //$oConnection->query('SELECT * FROM WHERE');
            Pelican_Db::$values = $this->monoValues;
        }

    }

    public static function setPageOrder ($page, $id, $type, $order = 1)
    {

        $oConnection = Pelican_Db::getInstance();

        /** on supprime l'entrée dans la table page_order */
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

    public static function cleanPageOrder ($id, $type)
    {

        $oConnection = Pelican_Db::getInstance();

        /** on supprime l'entrée dans la table page_order */
        $DBVALUES_INI = Pelican_Db::$values;
        Pelican_Db::$values["PAGE_ORDER_TYPE"] = $type;
        Pelican_Db::$values["PAGE_ORDER_ID"] = $id;
        $oConnection->updateTable(Pelican::$config["DATABASE_DELETE"], "#pref#_page_order", "PAGE_ID");
        Pelican_Db::$values = $DBVALUES_INI;
    }

    /**
     * Création d'une Pelican_Index_Frontoffice_Zone cliquable dans la miniature de la page
     *
     * @return void
     * @param mixed $tabZones Tableau de définition des zones
     */
    protected function _generateZone ($tabZones)
    {
        $oConnection = getConnection();
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
                            $multiZone['ZONE_TEMPLATE_ID'] = 'zoneDynamique_' . $key;
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
                        if(preg_match('(é|à|è)',$data["ZONE_TEMPLATE_LABEL"])){
                            $label = $data["ZONE_TEMPLATE_LABEL"];
                        }else{
                            $label = t($data["ZONE_TEMPLATE_LABEL"]);
                        }

                        $label = Pelican_Html::a(array(
                            href => "#anchor" . $data["ZONE_TEMPLATE_ID"]
                        ), $data["ZONE_TEMPLATE_LABEL"]);
                    } else {
                        $label = t($data["ZONE_TEMPLATE_LABEL"]);
                    }

                    if (! @$tr[$data["LIGNE"]][$data["COLONNE"]]) {
                        $tr[$data["LIGNE"]][$data["COLONNE"]] = Pelican_Html::td(array(
                            align => "center" ,
                            valign => "top" ,
                            colspan => $data["LARGEUR"] ,
                            rowspan => $data["HAUTEUR"],
                            height => "100%"
                        ), "");
                    }
                    $tr[$data["LIGNE"]][$data["COLONNE"]] .= "";
                    $horizontal[$data["LIGNE"]] = $data['AREA_HORIZONTAL'];

                    $border = "onmouseover=\"this.style.borderColor='red'\" onmouseout=\"this.style.borderColor='#CACACA'\"";
                    $click = "onclick=\"showHideZone('" . $data["ZONE_TEMPLATE_ID"] . "', false,'" . $nbrbloc . "');\"";
                    $bloc[$data["LIGNE"]][$data["COLONNE"]][$i] = "<tr><td class=\"zonetype" . $data["ZONE_TYPE_ID"] . "\" ";
                    if ($data["ZONE_TYPE_ID"] != 2) {
                        $bloc[$data["LIGNE"]][$data["COLONNE"]][$i] .= $click . " " . $border;
                    }
                    $bloc[$data["LIGNE"]][$data["COLONNE"]][$i] .= ">";
                    $bloc[$data["LIGNE"]][$data["COLONNE"]][$i] .= str_replace(" " . t('(HOME)'), "", $label);
                    if ($data["ZONE_PROGRAM"]) {
                        $bloc[$data["LIGNE"]][$data["COLONNE"]][$i] .= " " . Pelican_Html::img(array(
                                src => Pelican::$config["IMAGE_PATH"] . "/prog.gif" ,
                                alt => t('SCHEDULING_BLOC') ,
                                border => "0" ,
                                valign => "middle"
                            ));
                    }
                    $bloc[$data["LIGNE"]][$data["COLONNE"]][$i] .= "</td></tr>";

                    $i ++;
                }
            }
            foreach ($tr as $row => $td) {
                $cell = array();
                foreach ($td as $col => $value) {
                    $size = round(100/sizeof($bloc[$row][$col]));
                    foreach($bloc[$row][$col] as $key => $val) {
                        $bloc[$row][$col][$key] = str_replace('<td', '<td height="'.$size.'%"', $val);
                    }

                    if ($horizontal[$row]) {
                        $cell[] = str_replace("</td>", Pelican_Html::table(array(
                                width => "100%" ,
                                height => "100%"
                            ), str_replace('</tr><tr>', '', implode("", $bloc[$row][$col]))) . "</td>", $tr[$row][$col]);
                    } else {
                        $cell[] = str_replace("</td>", Pelican_Html::table(array(
                                width => "100%" ,
                                height => "100%"
                            ), implode("", $bloc[$row][$col])) . "</td>", $tr[$row][$col]);
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
     * - Création du champs caché contenant le chemin du fichier de traitement
     *
     * @return void
     * @param string $traitement Nom du fichier de traitement du type "/layout/zone/zone_db.php"
     * @param string $sql Chaine SQL de sélection des valeurs de la Pelican_Index_Frontoffice_Zone
     * @param mixed $this->aBind Paramètres Bind de la requête
     * @param mixed $aLob Liste des champs CLOB de la requête
     */
    protected function _initZone ($module = "", $zone_template_id, $sql = "", $aBind = array(), $aLob = array())

    {

        $oConnection = Pelican_Db::getInstance();

        if ($_REQUEST["id"] && $_REQUEST["id"] != Pelican::$config["DATABASE_INSERT_ID"] && $sql) {
            $this->values = $oConnection->queryForm($sql, $aBind, $aLob);
        }

        $this->moduleList[$zone_template_id] = $module;

    }

    protected function _getZoneValues ($page, $data, $module)
    {
        $statusPage = 'DRAFT';
        if($_SESSION[APP][$this->id][$_SESSION[APP][LANGUE_ID]]['SCHEDULE']){
            $statusPage = 'SCHEDULE';
        }
        $this->aBind = array();
        $this->aBind[":PAGE_ID"] = $page["PAGE_ID"];
        $this->aBind[":PAGE_VERSION"] = $page["PAGE_{$statusPage}_VERSION"];
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
            "ZONE_TEXTE" ,
            "ZONE_TEXTE2"
        ));
        if ($data) {
            $this->values += $data;
        }
        return $this->values;
    }

    public function _getZoneContentValues ()
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
					" . $oConnection->getNVLClause("cv.CONTENT_TITLE_BO", "cv.CONTENT_TITLE") . " AS lib
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
                ->_getValuesFromSQL($oConnection, $strSqlAssoc, $aSelectedValues, $this->aBind);

        }
        return $aSelectedValues;
    }

    /**
     * Tag de début d'un toggle (version speciale de la fonction toogle de Pelican_Form class)
     *
     * @return string
     * @param string $id Identifiant du toggle
     * @param string $label Libellé du toggle
     * @param string $state Etat du toggle ("" pour l'ouvrir, "none" pour le masquer) : "" par défaut
     * @param string $width Largeur du toggle : "90%" par défaut
     * @param boolean $bDirectOutput true pour un affichage direct, false pour que les méthodes retournent le code Pelican_Html sous forme de texte
     */
    protected function _beginZone ($id, $label, $nbrzone, $zone_id, $closed = true, $setCookie = true, $bDirectOutput = true, $deleteToggle = "", $program = "", $empty = false, $deleteZone = false, $perso = null)
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

        $strTemp = "<tr onclick=\"showHideZone('" . $id . "', " . $strSetCookie . "," . $nbrzone . ")\">";

        $strTemp .= "<td class=\"zonetype" . $zone_id . "\" valign=\"middle\" style=\"cursor:pointer\">";

        if ($deleteZone) {
            $strTemp .= Pelican_Html::img(array(
                id => 'deleteZone' . $this->multi,
                src => Pelican::$config['LIB_PATH'] . "/public/images/toggle_zone_delete.gif" ,
                alt => t('DELETE') ,
                align => "right" ,
                hspace => "3" ,
                width => "17" ,
                height => "17" ,
                border => "0" ,
                style => "cursor:pointer;" ,
                onclick => "deleteZone(this)"
            ));
        }
        $strTemp .= Pelican_Html::img(array(
            id => "togglezone" . $id ,
            src => Pelican::$config['LIB_PATH'] . "/public/images/toggle_zone_" . $image . ".gif" ,
            alt => $alt ,
            align => "right" ,
            hspace => "3" ,
            width => "17" ,
            height => "17" ,
            border => "0" ,
            style => "cursor:pointer;" /*,
            onclick => "showHideZone('" . $id . "', " . $strSetCookie . "," . $nbrzone . ")"*/
        ));
        if($perso != null){
            $imgPerso = ($perso == 'YES') ?'perso_rempli':'perso_vide';

            $strTemp .= Pelican_Html::img(array(
                id => "togglezone" . $id ,
                src => Pelican::$config['LIB_PATH'] . "/public/images/" . $imgPerso . ".png" ,
                alt => $alt ,
                align => "right" ,
                hspace => "3" ,
                width => "33" ,
                height => "18" ,
                border => "0" ,
                style => "cursor:pointer;"/* ,
                onclick => "showHideZone('" . $id . "', " . $strSetCookie . "," . $nbrzone . ")"*/
            ));
        }


        $strTemp .= "&nbsp;<a name=\"anchor" . $id . "\"></a>" . str_replace(" (page d'accueil)", "", $label);
        // $strTemp .= "</td><td class=\"formtogglezone\" align=\"right\">";
        if ($deleteToggle && ! $_GET["readO"]) {
            $strTemp .= "&nbsp;&nbsp;(<input type=\"Checkbox\" value=" . $id . " name=\"" . $deleteToggle . "\" " . ($empty ? "checked=\"checked\"" : "") . ">Vide)";
        }
        if ($program) {
            $strTemp .= " " . Pelican_Html::img(array(
                    src => Pelican::$config["IMAGE_PATH"] . "/prog.gif" ,
                    alt => t('SCHEDULING_BLOC') ,
                    border => "0" ,
                    valign => "middle"
                ));
        }
        $strTemp .= "</td></tr>\n";
        $strTemp .= "<tr><td class=\"tdtogglezone\" id=\"Divtogglezone" . $id . "\" style=\"display:" . $state . "\" colspan=\"2\">";

        return $strTemp;

    }

    /**
     * Tag de fin d'un toggle
     *
     * @return string
     * @param string $id Identifiant du Toggle
     * @param boolean $bDirectOutput true pour un affichage direct, false pour que les méthodes retournent le code Pelican_Html sous forme de texte
     */
    protected function _endZone ()
    {
        // $strTemp .= endFormTable(false);
        $strTemp = "</td></tr>\n";
        return $strTemp;
    }

    /**
     * Fonction de suppression d'une page
     *
     * @param string $version Définition d'une version uniquement à supprimer (sinon toutes les versions sont traitées)
     */
    protected function _deletePage ($version = "")
    {

        $oConnection = Pelican_Db::getInstance();

        $SEQUENCE = array(
            "#pref#_navigation" ,
            "#pref#_page_version_content" ,
            "#pref#_page_multi_zone_content" ,
            "#pref#_page_multi_zone_media" ,
            "#pref#_page_multi_zone" ,
            "#pref#_page_zone_content" ,
            "#pref#_page_zone_media" ,
            "#pref#_page_zone" ,
            "#pref#_page_version_media" ,
            "#pref#_page_version"
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

        if (! $version) {
            /** on supprime toutes les versions*/
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

    protected function _getTreeParams ($tree)
    {
        $return["id"] = $tree->id;
        $return["pid"] = $tree->pid;
        $return["lib"] = str_replace(" ", "&nbsp;", str_repeat("&nbsp;&nbsp;", ($tree->level - 2)) . $tree->lib);
        $return["order"] = $tree->order;
        return $return;
    }

    /*
     *Changement d'etat
     *@param $pageId
     *@param $state
     */
    protected function _updatePageMoreAndChlid ($pageId, $state)
    {
        //require_once(Pelican::$config["APPLICATION_CONTROLLERS"] . "/Administration/Directory/Corbeille.php");

        if(!empty($pageId)){
            //mise à jour des contenus de la page racine $pageId
            $test = Pelican_Request::call('/_/Administration_Directory_Corbeille/updateContentState',array($pageId, $state));

            //mise à jour des pages enfants de $pageId et des contenus
            Pelican_Request::call('/_/Administration_Directory_Corbeille/updateChildPage',array($pageId, $state));
        }
    }

    /*
     * Retire une page de la corbeille à partir d'un parent (de restoreAction)
     * @param int $pageID id de la page à restaurer
     */
    protected function _updateChildPage ($pageID, $stateId)
    {
        $oConnection = Pelican_Db::getInstance();

        $noMoreChild = false;
        $aBind[":PAGE_ID"] = $pageID;
        $stmt = "SELECT DISTINCT PAGE_ID FROM #pref#_page p WHERE p.PAGE_PATH LIKE '%#:PAGE_ID%'";
        $result = $oConnection->queryTab($stmt, $aBind);
        $pidList = array();
        if (is_array($result)) {
            foreach ($result as $key => $val) {
                if (empty($val['PAGE_ID'])) continue;
                $pidList[] = $val['PAGE_ID'];
            }
        }
        $listPage = array('PAGE_ID' => implode(',', $pidList));
        $this->trashUpdatedPages = $pidList;

        // Mise à jour des enfants
        if(is_array($listPage)){
            $aBind[":STATE_ID"] = $stateId;
            $aBind[":PAGE_ID"] = $listPage["PAGE_ID"];
            $sqlUpdate2 = "update #pref#_page_version
                            set
                                STATE_ID = :STATE_ID
                            where
                                PAGE_ID in (:PAGE_ID)";
            $oConnection->query( $sqlUpdate2, $aBind );

            $this->_updateContentState($listPage["PAGE_ID"], $stateId);
            $noMoreChild = true;
        }else{
            $noMoreChild = false;
        }

        return $noMoreChild;
    }

    /*
     * Retire une page de la corbeille à partir d'un parent (de restoreAction)
     * @param int $pageID id de la page ou des contenus sont à restaurer
     * @param int $stateId changement d'etat (corbeille => à publier)
     */
    protected function _updateContentState ($pageIDs, $stateId)
    {

        $oConnection = Pelican_Db::getInstance();

        $aBind[":PAGE_ID"] = $pageIDs;
        $aBind[":STATE_ID"] = $stateId;

        $sqlUpdate = "update #pref#_content_version
                            set
                                STATE_ID = :STATE_ID
                            where
                                PAGE_ID in (:PAGE_ID)";
        $oConnection->query( $sqlUpdate, $aBind );
    }

    public function sendMailDiffusion($siteId, $aTitlePages){
        $siteLabel	=	$this->getLabelSiteBySiteId($siteId);
        $objet		=	'CPP ' . $siteLabel['SITE_LABEL'] . ' information: broadcasting of content: ' . $aTitlePages . ' is going on line.';

        $body	=	'The content: ' . $aTitlePages . ' has been setted on line.';
        $body	.=	'<br/>';
        $body	.=	'<br/>';
        $body	.=	'-----------------------------------';
        $body	.=	'<br/>';
        $body	.=	'<br/>';
        $body	.=	'Le contenu: ' . $aTitlePages . ' a été mis en ligne.';
        $body	.=	'<br/>';
        $body	.=	'<br/>';


        $oMail = new Pelican_Mail();
        $oMail->setSubject(utf8_decode($objet));
        $oMail->setBodyHtml(utf8_decode($body));
        $oMail->setFrom(Pelican::$config ['EMAIL'] ['WEBMASTEUR_CENTRAL']);
        foreach ($this->getMailWebmasteurBysiteId($siteId) as $to){
            $oMail->addTo($to);
        }
        $oMail->send();
    }

    public function getMailWebmasteurBysiteId($siteId){
        $oConnection			=	Pelican_Db::getInstance();
        $this->aBind[':SITE_ID']	=	$siteId;

        $sql	= 'SELECT  SITE_MAIL_WEBMASTER
   				   FROM #pref#_site
   				   WHERE `SITE_ID` 	= :SITE_ID';
        $aMailSite	=	$oConnection->queryRow($sql, $this->aBind);
        if(is_array($aMailSite) && !empty($aMailSite)){
            return $aMailSite;
        }
        return false;
    }

    public function getLabelSiteBySiteId($siteId){
        $oConnection			=	Pelican_Db::getInstance();
        $this->aBind[':SITE_ID']	=	$siteId;

        $sql	= 'SELECT SITE_LABEL
   				   FROM #pref#_site
   				   WHERE `SITE_ID` 	= :SITE_ID';
        $labelSite	=	$oConnection->queryRow($sql, $this->aBind);
        if(is_array($labelSite) && !empty($labelSite)){
            return $labelSite;
        }
        return false;
    }
    /*********************************************** PERSO ***********************************************/
    /*
     * Méthode affichant la popin de personnalisation
     */
    public function persoAction(){
        /* Include des Helpers */
        include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Div.php');
        include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Button.php');
        include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Form.php');
        include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Media.php');
        include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/File.php');

        $oConnection = Pelican_Db::getInstance();

        /* Paramètres récupérés en GET */
        $zoneId = $this->getParam('zid');
        $multiId = $this->getParam('multi');
        $bGeneral = $this->getParam('general');
        $iPid = $this->getParam('pid');
        $iPageVersion = $this->getParam('pv');
        $iTpid = $this->getParam('tpid');
        $iZtid = $this->getParam('ztid');
        $iTypeExpand = $this->getParam('expand');
        $aBind[":LANGUE_ID"] = $aValues['LANGUE_ID'];
        $aBind[":PAGE_VERSION"] = $aValues['PAGE_VERSION'];
        $aBind[":PAGE_ID"] = $aValues['PAGE_ID'];
        $aBind[":ZONE_TEMPLATE_ID"] = $aValues['ZONE_TEMPLATE_ID'];

        $aSlideNonPerso =   Backoffice_Form_Helper::getDataZoneMultiValues(array(
                'PAGE_ID'=>$iPid,
                'PAGE_VERSION'=>$iPageVersion,
                'ZONE_TEMPLATE_ID'=>$iZtid,
                'LANGUE_ID'=>$_SESSION[APP]['LANGUE_ID']
            )
        );
        /* Binding des variables */
        $this->aBind[':PAGE_ID'] = $iPid;
        $this->aBind[':ZONE_TEMPLATE_ID'] = $iZtid;
        $this->aBind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];

        /* Récupération du zone path BO */
        $sZonePath = $oConnection->queryItem(
            'SELECT
                    ZONE_BO_PATH
                 FROM
                    #pref#_zone
                 WHERE ZONE_ID = :ZONE_ID',
            array(':ZONE_ID'=>$zoneId)
        );

        /* S'il s'agit d'une zone classique on récupère les infos dans la table page_zone multi*/
        if($this->getParam('perso') != ""){
            $sPerso = $this->getParam('perso');
        }else{
            if($bGeneral == false){
                $sSQL = "SELECT
                            pz.ZONE_PERSO
                        FROM
                            #pref#_page p
                        INNER JOIN #pref#_page_version pv
                            ON (p.PAGE_ID = pv.PAGE_ID and p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION and p.LANGUE_ID = pv.LANGUE_ID)
                        LEFT JOIN #pref#_page_zone pz
                            ON (pz.PAGE_ID = pv.PAGE_ID AND pz.LANGUE_ID = pv.LANGUE_ID AND pz.PAGE_VERSION = pv.PAGE_VERSION)
                        LEFT JOIN #pref#_page_multi_zone pmz
                            ON (pmz.PAGE_ID = pv.PAGE_ID AND pmz.LANGUE_ID = pv.LANGUE_ID AND pmz.PAGE_VERSION = pv.PAGE_VERSION)
                        WHERE pz.ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
                        and p.PAGE_ID = :PAGE_ID
                        and p.LANGUE_ID = :LANGUE_ID";
            }else{
                /* S'il s'agit d'une zone général on récupère les infos dans la table page_version multi*/
                $sSQL = "SELECT
                            pv.PAGE_PERSO
                        FROM
                            #pref#_page p
                        INNER JOIN #pref#_page_version pv
                            ON (p.PAGE_ID = pv.PAGE_ID and p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION and p.LANGUE_ID = pv.LANGUE_ID)
                        WHERE p.PAGE_ID = :PAGE_ID
                        and p.LANGUE_ID = :LANGUE_ID";
            }
            $sPerso = $oConnection->queryItem($sSQL,$this->aBind);

        }

        if($this->getParam('form') != ""){
            $aMultiNames = explode(',',$this->getParam('listMulti'));
            $defaultValues = $this->clearTab($this->getParam('form'),$multiId, $aMultiNames, '',$bGeneral, $zoneId);
            $defaultValues['SLIDESHOW_NON_PERSO']   =$aSlideNonPerso;
            $defaultSerialize = \Citroen_View_Helper_Global::arrHtmlEncode($defaultValues);
            $defaultSerialize = json_encode($defaultSerialize);
        }



        /* Si on récupère des infos enregistrées on fait un json decode pour obtenir un tableau */
        if($sPerso != '' && is_string($sPerso)){
            $aTab = \Citroen_View_Helper_Global::arrHtmlDecode(json_decode($sPerso,JSON_OBJECT_AS_ARRAY));
        }
        /* Création des boutons de la popin de perso et mise en hidden des variables utiles pour les différentes actions */
        $form .= '<div id="dialog-confirm"></div>';
        $form .='<button class="addPerso">'.t('ADD_PERSO').'</button>
                <ul class="btnDialogPerso">
                    <li> <button class="savePerso">'.t('ENREGISTRER_FERMER').'</button></li>
                    <li><button class="closePerso" data-confirmText="'.t('CONFIRM_CLOSE_PERSO').'">'.t('FERMER').'</button> </li>
                </ul>
                <input type="hidden" name="profilTrad" id="profilTrad" value="'.t('PROFILE').'" />
                 <input type="hidden" name="zoneId" id="zoneId" value="'.$zoneId.'" />
                 <input type="hidden" name="ztid" id="ztid" value="'.$iZtid.'" />
                 <input type="hidden" name="multiId" id="multiId" value="'.$multiId.'" />
                 <input type="hidden" name="iPid" id="iPid" value="'.$iPid.'" />
                 <input type="hidden" name="iTpid" id="iTpid" value="'.$iTpid.'" />
                 <input type="hidden" name="bGeneral" id="bGeneral" value="'.$bGeneral.'" />
                 <input type="hidden" name="defaultSerialize" id="defaultSerialize" value="'.htmlspecialchars($defaultSerialize).'" />';
        /* S'il existe des infos on boucle sur le tableau et crée des formulaires pour chaque profil */
        if(is_array($aTab) && count($aTab)>0){

            $form .= '<div id="tabs" style="float:left;clear:both;width:100%;">';
            $form .= '<ul>';
            $j=1;
            $aLabelProfil   =   array();
            foreach($aTab as $tab){
                $iId =  $j;
                if(isset($tab['PROFIL_LABEL']) && !empty($tab['PROFIL_LABEL'])){
                    $sLabel =   $tab['PROFIL_LABEL'];
                }
                $form .= '<li><a href="#tabs-'.$iId.'">'.$sLabel.'</a></li>';
                $j++;
            }
            $form .= '</ul>';
            $j=1;

            foreach($aTab as $key=>$tab){
                $this->oForm = Pelican_Factory::getInstance('Form', false);

                $iId =  $j;
                /* Le nom de la méthode change : si on se trouve sur une zone classique on intègre le multi de la zone */
                $sNameFunctionCheck = ($bGeneral == false) ? "CheckFormPerso_".$multiId.$iId : "CheckFormPerso_".$iId;
                $this->multi = ($bGeneral == false) ? 'perso_' . $iId.'_'.$multiId : 'perso_' . $iId.'_';
                /* On alimente le zoneValues sur une zone classique et values sur la zone générale*/
                if($bGeneral == false){
                    /* Pour la lecture des multi on crée une entrée perso dans zoneValues */
                    $aTab[$key]['PERSO'] = $tab;
                    $aTab[$key]['PAGE_ID'] = $iPid;
                    $aTab[$key]['LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
                    $this->zoneValues = $aTab[$key];
                }else{
                    $this->values = $aTab[$key];
                }

                $form .= '<div id="tabs-'.$iId.'">
                                <input type="hidden" name="tabs-'.$iId.'-Order" id="tabs-'.$iId.'-Order" value="'.$iId.'" />
                    ';
                $form .= $this->oForm->open("", "post", "fForm".$iId, false, true, $sNameFunctionCheck);
                $form .= '<input type="hidden" name="idFormTabs" id="tabs-'.$iId.'-Order" value="'.$iId.'" />';
                pelican_import('Controller.Back');

                $this->getZone($form, $iId, $multiId, $sZonePath, $bGeneral, $iPid, $iTpid, $iTypeExpand,array('SLIDESHOW_NON_PERSO'=>$aSlideNonPerso));



                $this->oForm->_aIncludes["multi"] = true;
                $this->oForm->_aIncludes["num"] = true;
                $this->oForm->_aIncludes["text"] = true;
                $this->oForm->_aIncludes["date"] = true;
                $this->oForm->_aIncludes["list"] = true;
                $this->oForm->_aIncludes["popup"] = true;
                $this->oForm->_aIncludes["crosstab"] = true;
                $this->oForm->_sDefaultFocus = false;
                $form_js_clean    = addslashes($this->oForm->_sJS);
                $form_js_clean    = preg_replace('#//.*$#m', '', $form_js_clean);
                $jsHide = <<<JS
hideProduct($('#{$this->multi}INDICATEUR_ID').val(), '{$this->multi}');
JS;
                if($this->oForm->getUseMulti() == true){
                    $js .= <<<JS
var fonctionCheck = {$sNameFunctionCheck}_multi.toString();
fonctionCheck = fonctionCheck.substring(fonctionCheck.indexOf("{")+1, fonctionCheck.length - 2);
fonctionCheck = "{$form_js_clean}" + fonctionCheck;
{$sNameFunctionCheck}_multi = new Function("obj", fonctionCheck);
JS;
                }
                $form .= '<div class="blankForFooter"></div>';
                $this->oForm->setView($this->getView());
                $form .= $this->oForm->close();


                $deleteButton = new \Citroen\Html\Button(
                    $this->multi."SUPPRIME_PROFILE",
                    'button',
                    t("SUPPRIMER_PERSO"),
                    array('onclick' => "deleteTab(this,'".$iId."');"),
                    '',
                    '',
                    "data-title='".t('CONFIRM_DELETE_PROFIL')."'"
                );
                $deleteButton->wrap('<ul class="footerTabPerso"><li>|</li></ul>');
                $form .= $deleteButton->render();

                $form .= '</div>';
                $j++;
            }
            $form .= '</div>';

        }else{
            $form .= '<div id="tabs" style="float:left;clear:both;width:100%;"></div>';
        }

        /*if (Pelican::$config["CHARSET"] == "UTF-8") {
            pelican_import('Text.Utf8');
            $form = Pelican_Factory::staticCall('Text.Utf8', 'utf8_to_unicode', $form);
        }*/

        $this->addResponseCommand('assign', array(
            'id' => 'dialog',
            'attr' => 'innerHTML',
            'value' => $form
        ));
        $this->addResponseCommand('script', array('value'=>$jsHide));
        $this->addResponseCommand('script', array('value'=>str_replace(array(chr(10), chr(13)), '', $js)));
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
    public function getZone(&$form, $indexTab, $multiId, $sZonePath, $bGeneral, $iPid, $iTpid, $iTypeExpand,$aAdditionalData=null){
        $oConnection = Pelican_Db::getInstance();

        $form .= '<div>';
        $form .= $this->oForm->beginFormTable();

        $form .= $this->oForm->createHidden($this->multi."ORDER",$indexTab);

        //Récupération des profils
        $aProfil = array();
        $profiles = \Pelican_Cache::fetch("Citroen/PersoProfile", array('BO-'.$_SESSION[APP]['LANGUE_CODE'].'-'.$_SESSION[APP]['LANGUE_ID']));
        if (is_array($profiles) ){
            foreach ($profiles as $val) {
                $aProfil[$val['PROFILE_ID']] = !empty($val['locallabel']) ? $val['locallabel'] : $val['PROFILE_LABEL'];
            }
        }

        if($bGeneral == false){
            if(empty($this->zoneValues)){
                $this->zoneValues['ZONE_BO_PATH'] = $sZonePath;
            }
            $this->zoneValues['isPerso'] = 1;
            $data = $this->zoneValues;
        }else{
            $this->values['isPerso'] = 1;
            $data = $this->values;
        }

        $form .= $this->oForm->createComboFromList($this->multi."PROFILE_ID", t("PROFILE"), $aProfil, $data['PROFILE_ID'], true, $this->readO);

        //Récupération des indicateurs
        $aIndicateur = array(
            13  => t("PERSOINDIC_PRODUIT_PREFERE"),
            7   => t("PERSOINDIC_PRODUIT_POSSEDE"),
            11  => t("PERSOINDIC_PRODUIT_COURANT"),
            12  => t("PERSOINDIC_PRODUIT_LE_MIEUX_SCORE"),
            14  => t("PERSOINDIC_PRODUIT_LE_PLUS_RECENT"),
            16  => t("PERSOINDIC_RECONSULTATION"),
        );
        $form .= $this->oForm->createComboFromList($this->multi."INDICATEUR_ID", t("INDICATEUR"), $aIndicateur, $data['INDICATEUR_ID'], false, $this->readO, "1", false, "", true, false, "onChange=\"hideProduct(this.value, '".$this->multi."');\"");
        //Récupération des produits
        $aProduit = array();
        $sSQL = "SELECT
                    PRODUCT_ID,
                    PRODUCT_LABEL
                FROM
                    #pref#_perso_product
                WHERE
                    SITE_ID = :SITE_ID";
        $this->aBind[':SITE_ID'] = (int)$_SESSION[APP]['SITE_ID'];
        $aResultsProduit = $oConnection->queryTab($sSQL, $this->aBind);

        if( is_array($aResultsProduit) && count($aResultsProduit) > 0 ){
            foreach($aResultsProduit as $aOneResult){
                $aProduit[$aOneResult['PRODUCT_ID']] = $aOneResult['PRODUCT_LABEL'];
            }
        }
        natcasesort ($aProduit);
        //$form .= $this->oForm->createComboFromList($this->multi."PRODUCT_ID","", $aProduit, $data['PRODUCT_ID'], false, $this->readO);

        $form .= $this->oForm->createAssocFromList($oConnection, $oController->multi . 'PRODUCT_ID', t('PRODUCT'), $aProduit, $data['PRODUCT_ID'], false, true, $oController->readO, "5", 200, false, "", 'ordre', 0, false);
        if(empty($data["PROFIL_LABEL"])){
            $data["PROFIL_LABEL"]   =   'Profile ' . $indexTab;
        }
        $form .= $this->oForm->createInput($this->multi . "PROFIL_LABEL", t('PROFIL_LABEL'), 50, "", false, $data["PROFIL_LABEL"], $this->readO, 50);

        $form .= $this->oForm->createInput($this->multi . "PROFIL_DATE_DEB", t('PROFIL_DATE_DEB'), 50, "date", false, $data["PROFIL_DATE_DEB"], $this->readO, 50);


        $form .= $this->oForm->createInput($this->multi . "PROFIL_DATE_FIN", t('PROFIL_DATE_FIN'), 50, "date", false, $data["PROFIL_DATE_FIN"], $this->readO, 50);


        $form .= $this->oForm->createJS("
            var dateDeb     = $('#" . $this->multi . "PROFIL_DATE_DEB').val();
            if( dateDeb != ''){
                var aDateDeb    = dateDeb.split('/');
                dateDeb     = new Date(aDateDeb[2],aDateDeb[1],aDateDeb[0]);
            }

            var dateFin     = $('#" . $this->multi . "PROFIL_DATE_FIN').val();
            if( dateFin != ''){
                var aDateFin    = dateFin.split('/');
                 dateFin     = new Date(aDateFin[2],aDateFin[1],aDateFin[0]);
            }

            var labelProfil =   $('#" . $this->multi . "PROFIL_LABEL').val();
            if( dateDeb != '' && dateFin != ''){
                if(dateDeb > dateFin){
                    alert('" . t('LA_DATE_DE_FIN_DOIT_EST_PLUS_GRANDE_QUE_LA_DATE_DE_DEBUT_POUR_LE_PROFIL') . " ' + labelProfil);
                    return false;
                }
            }
        ");
        if($bGeneral == false){
            $form .= $this->oForm->createHr();
            $this->getControllerZone($form,$sZonePath,$aAdditionalData);
        }else{
            $this->getGeneralZone($form, $iPid, $iTpid, $iTypeExpand);
        }


        // si la verif est activer (CheckBox mobile/ web)
        // helper backend getFormAffichage
        if($this->zoneValues['VERIF_JS'] == 1){
            $this->oForm->_sJS.= "}\n";
            //Pelican::$config['VERIF_JS'] = 0;
        }

        $form .= $this->oForm->endFormTable();
        $form .= '</div>';



    }

    public function getCountChildPages($iPageParentId){
        $oConnection = Pelican_Db::getInstance();
        $sSQL = "SELECT
                    MAX(DISTINCT p.PAGE_ID) as count_children
                FROM
                #pref#_page p
                LEFT JOIN #pref#_page_version pv
                    ON (
                    p.PAGE_ID = pv.PAGE_ID
                    AND p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION
                )
                WHERE
                SITE_ID = :SITE_ID

                AND p.PAGE_PARENT_ID = :PAGE_PARENT_ID
                AND (pv.STATE_ID <> 5)
                GROUP BY p.PAGE_PARENT_ID

                ";
        $aBind =array(
            ':SITE_ID' => (int)$_SESSION[APP]['SITE_ID'],

            ':PAGE_PARENT_ID' => (int)$iPageParentId,
        );
        return $oConnection->queryRow($sSQL, $aBind);

    }


    /*
    * Méthode générant la zone classique
    * @param $form : Form
    * @param $sZonePath : Path de la zone
    */
    public function getControllerZone(&$form, $sZonePath,$aAdditionalData=null){
        $module = Pelican::$config['APPLICATION_CONTROLLERS'] . '/' . str_replace("_", "/", $sZonePath) . ".php";
        $moduleClass = $sZonePath;

        if (! file_exists($module)) {
            $form .= $this->oForm
                ->createFreeHtml("<span class=\"erreur\">" . $module . " => " . t('A_FAIRE') . "</span>");
        } else {
            include_once ($module);
            $tmp = call_user_func_array(array(
                $moduleClass ,
                'render'
            ),
                array(
                    $this,
                    $aAdditionalData
                )
            );


            $form .= $this->oForm
                ->createFreeHtml($tmp);
        }

    }



    private function getMultiDisplayOnly($expand){
        $results = array();
        foreach ($expand as $key => $value) {
            if($value["multi_display"] == '1'){
                $results[] = $value;
            }
        }
        return $results;
    }


    /*
    * Méthode générant la zone générale
    * @param $form : Form
    * @param $iPid : Pid de la page
    * @param $iTpid : Id du template de la page
    * @param $iTypeExpand : Type de l'expan
    */
    public function getGeneralZone(&$form, $iPid, $iTpid, $iTypeExpand){
        $oConnection = Pelican_Db::getInstance();
        $sSQL = "SELECT
                    PAGE_PATH
                FROM
                    #pref#_page
                WHERE PAGE_ID = :PAGE_ID
        ";
        $this->aBind[':PAGE_ID'] = (int)$iPid;
        $sPagePath = $oConnection->queryItem($sSQL, $this->aBind);
        if($this->values['isPerso'] == '1')
        {
            if($this->values['PUSH'])
            {
                $this->values['PUSH'] = $this->getMultiDisplayOnly($this->values['PUSH']);
            }
            if($this->values['PUSH_OUTILS_MAJEUR'])
            {
                $this->values['PUSH_OUTILS_MAJEUR'] = $this->getMultiDisplayOnly($this->values['PUSH_OUTILS_MAJEUR']);
            }
            if($this->values['PUSH_OUTILS_MINEUR'])
            {
                $this->values['PUSH_OUTILS_MINEUR'] = $this->getMultiDisplayOnly($this->values['PUSH_OUTILS_MINEUR']);
            }
            if($this->values['PUSH_CONTENU_ANNEXE'])
            {
                $this->values['PUSH_CONTENU_ANNEXE'] = $this->getMultiDisplayOnly($this->values['PUSH_CONTENU_ANNEXE']);
            }
        }

        $defaultPush = $this->getMultisGenericData();

        // Lecture des métadonnées multi
        $multiMetadataForm = $this->getParam('multiMetadata');
        parse_str($multiMetadataForm, $multiMetadata);

        // Détection du contexte (onglet existant ou nouvel onglet perso)
        $context = $this->getContext();

        if (substr_count($sPagePath, "#") && substr_count($sPagePath, "#") == 1 && $iTypeExpand == '0') {
            $form .= $this->oForm->createMultiHmvc(
                $this->multi."PUSH",             // $strName
                t('PUSH'),                       // $strLib
                array('path'=>dirname(__FILE__) . '/Page.php', 'class'=>'Cms_Page_Controller', 'method'=>'multiPush'),
                $this->values['PUSH'],           // $tabValues
                "PUSH",                          // $incrementField
                $this->readO,                    // $bReadOnly = false
                3,                               // $intMinMaxIterations = ""
                true,                            // $bAllowDeletion = true
                true,                            // $bAllowAdd = true
                "PUSH",                          // $strPrefixe = "multi"
                "values",                        // $line = "values"
                "multi",                         // $strCss = "multi"
                "2",                             // $sColspan = "2"
                "",                              // $sButtonAddMulti = ""
                "",                              // $complement = ""
                $this->values['isPerso'],        // $perso = false
                array(
                    'perso_default_multi' => isset($defaultPush['PUSH']) ? $defaultPush['PUSH'] : null,
                    'perso_multi_metadata' => $multiMetadata,
                    'context' => $context,
                )
            );
        }
        if (substr_count($sPagePath, "#") && substr_count($sPagePath, "#") == 2) {
            if ($iTpid != Pelican::$config['TEMPLATE_PAGE']['MASTER_PAGE_STANDARD_N2']) {
                $form .= $this->oForm->createMultiHmvc(
                    $this->multi."PUSH_OUTILS_MAJEUR",
                    t('PUSH_OUTILS_MAJEUR'),
                    array('path'=>dirname(__FILE__) . '/Page.php', 'class'=>'Cms_Page_Controller', 'method'=>'multiCTA'),
                    $this->values['PUSH_OUTILS_MAJEUR'],
                    "PUSH_OUTILS_MAJEUR",
                    $this->readO,
                    1,
                    true,
                    true,
                    "PUSH_OUTILS_MAJEUR",
                    "values",
                    "multi",
                    "2",
                    "",
                    "",
                    $this->values['isPerso'],
                    array(
                        'perso_default_multi' => isset($defaultPush['PUSH_OUTILS_MAJEUR']) ? $defaultPush['PUSH_OUTILS_MAJEUR'] : null,
                        'perso_multi_metadata' => $multiMetadata,
                        'context' => $context,
                    )
                );
                $form .= $this->oForm->createMultiHmvc(
                    $this->multi."PUSH_OUTILS_MINEUR",
                    t('PUSH_OUTILS_MINEUR'),
                    array('path'=>dirname(__FILE__) . '/Page.php', 'class'=>'Cms_Page_Controller', 'method'=>'multiCTA'),
                    $this->values['PUSH_OUTILS_MINEUR'],
                    "PUSH_OUTILS_MINEUR",
                    $this->readO,
                    4,
                    true,
                    true,
                    "PUSH_OUTILS_MINEUR",
                    "values",
                    "multi",
                    "2",
                    "",
                    "",
                    $this->values['isPerso'],
                    array(
                        'perso_default_multi' => isset($defaultPush['PUSH_OUTILS_MINEUR']) ? $defaultPush['PUSH_OUTILS_MINEUR'] : null,
                        'perso_multi_metadata' => $multiMetadata,
                        'context' => $context,
                    )
                );
                $form .= $this->oForm->createMultiHmvc(
                    $this->multi."PUSH_CONTENU_ANNEXE",
                    t('PUSH_CONTENU_ANNEXE'),
                    array('path'=>dirname(__FILE__) . '/Page.php', 'class'=>'Cms_Page_Controller', 'method'=>'multiPush'),
                    $this->values['PUSH_CONTENU_ANNEXE'],
                    "PUSH_CONTENU_ANNEXE",
                    $this->readO,
                    2,
                    true,
                    true,
                    "PUSH_CONTENU_ANNEXE",
                    "values",
                    "multi",
                    "2",
                    "",
                    "",
                    $this->values['isPerso'],
                    array(
                        'perso_default_multi' => isset($defaultPush['PUSH_CONTENU_ANNEXE']) ? $defaultPush['PUSH_CONTENU_ANNEXE'] : null,
                        'perso_multi_metadata' => $multiMetadata,
                        'context' => $context,
                    )
                );
            }
        }
    }

    /**
     * Retourne le contexte de la requête courante :
     *  - savedprofile : chargement de la popin de perso
     *  - newprofile   : ajout onglet profil perso dans la popin
     *
     * @return string Contexte
     */
    public function getContext()
    {
        $request = $this->getRequest();
        if ($request->controller == 'Page' && $request->action == 'perso') {
            return 'savedprofile';
        } elseif ($request->controller == 'Perso') {
            return 'newprofile';
        }
    }

    /**
     * Retourne les valeurs génériques (non perso) de chaque multi de la tranche (gère également le bloc général des pages)
     * La fonction s'adapte au contexte dans lequel elle est appelée (Request), il y a 2 contextes :
     *  1. Chargement de la popin de personnalisation
     *     => les données génériques sont lues depuis le champ listMulti
     *  2. Ajout d'un nouveau profil de personnalisation (dans une popin perso)
     *     => les données génériques sont lues depuis le champ defaultSerialize
     *
     * @return Array|false Contenu de tous les multi de la tranche (données génériques),
     *                     ou false si l'appel est effectué en dehors de la popin perso (=> contexte non géré)
     */
    public function getMultisGenericData()
    {
        $request = $this->getRequest();
        $context = $this->getContext();

        // Aucun traitement lors de l'affichage d'une tranche dans le formulaire d'édition de page
        if ($request->controller == 'Page' && $request->action == 'index') {
            return false;
        }

        // Contexte 1 : chargement de la popin de perso
        if ($context == 'savedprofile') {
            // Détection des types de push multi posté
            $listMulti = $this->getParam('listMulti');
            $multi = isset($listMulti) ? explode(',', $listMulti) : null;
            if (empty($multi)) {
                return false;
            }

            // Lecture des données par défaut du multi
            $defaultData = array();
            $form = $this->getParam('form');
            parse_str($form, $defaultData);
            if (empty($defaultData) || !is_array($defaultData)) {
                return false;
            }

            // Nettoyage $defaultData (suppression modèle multi : __CPT__)
            $quotedMulti = array_map(function ($str) {
                return preg_quote($str, '#');
            }, $multi);
            $multiNamePattern = '#^('.implode('|', $quotedMulti).')__CPT__#';
            foreach ($defaultData as $key => $val) {
                if (preg_match($multiNamePattern, $key)) {
                    unset($defaultData[$key]);
                }
            }

            // Lecture de chaque mutli
            $defaultPush = array();
            foreach ($multi as $val) {
                $defaultPush[$val] = Backoffice_Form_Helper::standaloneReadMulti($defaultData, $val);
            }
            return $defaultPush;
        }

        // Contexte 2 : ajout onglet profil perso dans la popin
        if ($context == 'newprofile') {
            // Lecture des données
            $defaultSerialize = $this->getParam('defaultSerialize');
            if (!isset($defaultSerialize)) {
                return false;
            }

            $defaultData = json_decode($defaultSerialize, true);
            if ($defaultData === null) {
                return false;
            }

            // Indique si un élément multi est vide ou pas
            // (la comparaison ignore la clé vide [''], utilisée comme identifiant de multi, qui parasite les données)
            $isEmptyMulti = function ($multiData) {
                unset($multiData['']);
                return empty($multiData) ? true : false;
            };

            // Filtrage des multis push (suppression des multi vides)
            $defaultPush = array();
            foreach ($defaultData as $field => $fieldVal) {
                if (!is_array($fieldVal)) {
                    continue;
                }
                foreach ($fieldVal as $key => $val) {
                    if ($isEmptyMulti($val)) {
                        continue;
                    }
                    $val = Citroen_View_Helper_Global::arrHtmlDecode($val);
                    $defaultPush[$field][] = $val;
                }
            }
            return $defaultPush;
        }

        //trigger_error("Unhandled context", E_USER_NOTICE);
        return false;
    }


    public function clearTab($arr,$sMultiName, $aMultiNames, $multiPerso,$bGeneral,$zoneId){
        $return = array();
        //On cr�e un tableau avec la s�rialisation du formulaire
        parse_str($arr, Pelican_Db::$values);
        if(is_array($aMultiNames) && count($aMultiNames)>0){
            //Pour chaque multi param�tr� sur la page, on v�rifie qu'il en existe un dans le controller courant
            foreach($aMultiNames as $multiName){
                if($multiName != ''){
                    $tempMultiName = str_replace($multiPerso, '',$multiName);
                    readMulti($multiPerso.$tempMultiName, $multiPerso.$tempMultiName);
                    self::_clean($multiPerso.$tempMultiName);
                    if(is_array(Pelican_Db::$values[$multiPerso.$tempMultiName]) && count(Pelican_Db::$values[$multiPerso.$tempMultiName])>0){

                        Pelican_Db::$values['MULTI_NAME'] = str_replace($multiPerso. $sMultiName, "", $multiPerso.$tempMultiName);
                    }
                }
            }
        }

        //On reconstruit un tableau propre
        if(is_array(Pelican_Db::$values) && count(Pelican_Db::$values)>0){
            if($bGeneral == 1){
                $profileList[] = (!empty(Pelican_Db::$values[$multiPerso. $sMultiName.'INDICATEUR_ID']) && !empty(Pelican_Db::$values[$multiPerso. $sMultiName.'PRODUCT_ID']) ) ? Pelican_Db::$values[$multiPerso. $sMultiName.'ORDER'].'_'.Pelican_Db::$values[$multiPerso. $sMultiName.'PROFILE_ID'].'_'.Pelican_Db::$values[$multiPerso. $sMultiName.'INDICATEUR_ID'].'_'.Pelican_Db::$values[$multiPerso. $sMultiName.'PRODUCT_ID'] : Pelican_Db::$values[$multiPerso. $sMultiName.'ORDER'].'_'.Pelican_Db::$values[$multiPerso. $sMultiName.'PROFILE_ID'];
            }
            foreach(Pelican_Db::$values as $key => $value) {
                if($key != "TRACK_MULTINAMES"){
                    $field = str_replace($multiPerso. $sMultiName, "", $key);
                    $valueSave = $value;
                    //Cas particulier des champs implodés (liste de checkbox ou sélection multiple)
                    if(is_array($valueSave) && !empty($valueSave)){
                        switch($zoneId){
                            case 572:
                                if($field == 'ZONE_PARAMETERS'){
                                    $valueSave = implode(',',$valueSave);
                                }
                                break;
                            case 644:
                                if($field == 'ZONE_TITRE' || $field == 'ZONE_TITRE5'){
                                    $valueSave = implode('##',$valueSave);
                                }
                                break;
                            case 625:
                                if($field == 'ZONE_PARAMETERS'){
                                    $valueSave = implode('|',$valueSave);
                                }
                                break;
                            case 627:
                                if($field == 'ZONE_PARAMETERS'){
                                    $valueSave = implode('|',$valueSave);
                                }
                                break;
                            case 635:
                                if($field == 'ZONE_TITRE' || $field == 'ZONE_TITRE2' || $field == 'ZONE_TITRE9'){
                                    $valueSave = implode('##',$valueSave);
                                }
                                break;
                            case 639:
                                if($field == 'ZONE_LABEL2'){
                                    $valueSave = implode('|',$valueSave);
                                }
                                break;
                            case 659:
                                if($field == 'ZONE_TEXTE'){
                                    $valueSave = implode('#',$valueSave);
                                }
                                break;
                            case 677:
                                if($field == 'ZONE_LABEL2'){
                                    $valueSave = implode('|',$valueSave);
                                }
                                break;
                            case 688:
                                if($field == 'ZONE_TITRE4'){
                                    $valueSave = implode('|',$valueSave);
                                }
                                break;
                            case 704:
                                if($field == 'ZONE_TEXTE2'){
                                    $valueSave = implode(',',$valueSave);
                                }
                                break;
                            case 719:
                                if($field == 'ZONE_PARAMETERS'){
                                    $valueSave = implode('|',$valueSave);
                                }
                                break;
                            case 737:
                                if($field == 'ZONE_TEXTE6' || $field == 'ZONE_TEXTE7'){
                                    $valueSave = implode(';',$valueSave);
                                }
                                break;
                            case 738:
                                if($field == 'ZONE_PARAMETERS'){
                                    $valueSave = implode('|',$valueSave);
                                }
                                break;

                        }
                    }

                    $return[$field] = $valueSave;
                }
            }
            //Cas particulier de la zone connexion
            if($zoneId == 719){
                $aMulti = array('NON_IDENTIFIE','INSCRIPTION', 'GESTION_ERREURS', 'FINALISATION_INSCRIPTION_CITROENID', 'FINALISATION_INSCRIPTION_RS', 'CONFIRMATION_INSCRIPTION', 'CONNECTE');
                foreach($aMulti as $type){
                    switch($type){
                        case 'NON_IDENTIFIE':
                            $return[$type][] = array(
                                'PAGE_ZONE_MULTI_TITRE' => $return['NON_IDENTIFIEPAGE_ZONE_MULTI_TITRE'],
                                'PAGE_ZONE_MULTI_TITRE2' => $return['NON_IDENTIFIEPAGE_ZONE_MULTI_TITRE2'],
                                'PAGE_ZONE_MULTI_TEXT2' => $return['NON_IDENTIFIEPAGE_ZONE_MULTI_TEXT2'],
                                'PAGE_ZONE_MULTI_TITRE3' => $return['NON_IDENTIFIEPAGE_ZONE_MULTI_TITRE3'],
                                'PAGE_ZONE_MULTI_TEXT3' => $return['NON_IDENTIFIEPAGE_ZONE_MULTI_TEXT3'],
                                'PAGE_ZONE_MULTI_TITRE4' => $return['NON_IDENTIFIEPAGE_ZONE_MULTI_TITRE4'],
                                'PAGE_ZONE_MULTI_LABEL5' => $return['NON_IDENTIFIEPAGE_ZONE_MULTI_LABEL5']
                            );
                            break;
                        case 'INSCRIPTION':
                            $return[$type][] = array(
                                'PAGE_ZONE_MULTI_TITRE' => $return['INSCRIPTIONPAGE_ZONE_MULTI_TITRE'],
                                'PAGE_ZONE_MULTI_TITRE2' => $return['INSCRIPTIONPAGE_ZONE_MULTI_TITRE2'],
                                'PAGE_ZONE_MULTI_TITRE3' => $return['INSCRIPTIONPAGE_ZONE_MULTI_TITRE3'],
                                'PAGE_ZONE_MULTI_LABEL' => $return['INSCRIPTIONPAGE_ZONE_MULTI_LABEL'],
                                'PAGE_ZONE_MULTI_LABEL2' => $return['INSCRIPTIONPAGE_ZONE_MULTI_LABEL2'],
                                'PAGE_ZONE_MULTI_LABEL3' => $return['INSCRIPTIONPAGE_ZONE_MULTI_LABEL3'],
                                'PAGE_ZONE_MULTI_TEXT' => $return['INSCRIPTIONPAGE_ZONE_MULTI_TEXT'],
                                'PAGE_ZONE_MULTI_TEXT2' => $return['INSCRIPTIONPAGE_ZONE_MULTI_TEXT2'],
                                'PAGE_ZONE_MULTI_TEXT3' => $return['INSCRIPTIONPAGE_ZONE_MULTI_TEXT3']
                            );
                            break;
                        case 'GESTION_ERREURS':
                            $return[$type][] = array(
                                'PAGE_ZONE_MULTI_TITRE' => $return['GESTION_ERREURSPAGE_ZONE_MULTI_TITRE'],
                                'PAGE_ZONE_MULTI_TITRE2' => $return['GESTION_ERREURSPAGE_ZONE_MULTI_TITRE2'],
                                'PAGE_ZONE_MULTI_TEXT' => $return['GESTION_ERREURSPAGE_ZONE_MULTI_TEXT'],
                                'PAGE_ZONE_MULTI_TEXT2' => $return['GESTION_ERREURSPAGE_ZONE_MULTI_TEXT2']
                            );
                            break;
                        case 'FINALISATION_INSCRIPTION_CITROENID':
                            $return[$type][] = array(
                                'PAGE_ZONE_MULTI_TITRE' => $return['FINALISATION_INSCRIPTION_CITROENIDPAGE_ZONE_MULTI_TITRE'],
                                'PAGE_ZONE_MULTI_TEXT' => $return['FINALISATION_INSCRIPTION_CITROENIDPAGE_ZONE_MULTI_TEXT']
                            );
                            break;
                        case 'FINALISATION_INSCRIPTION_RS':
                            $return[$type][] = array(
                                'PAGE_ZONE_MULTI_TITRE' => $return['FINALISATION_INSCRIPTION_RSPAGE_ZONE_MULTI_TITRE'],
                                'PAGE_ZONE_MULTI_TEXT' => $return['FINALISATION_INSCRIPTION_RSPAGE_ZONE_MULTI_TEXT']
                            );
                            break;
                        case 'CONFIRMATION_INSCRIPTION':
                            $return[$type][] = array(
                                'PAGE_ZONE_MULTI_TITRE' => $return['CONFIRMATION_INSCRIPTIONPAGE_ZONE_MULTI_TITRE'],
                                'PAGE_ZONE_MULTI_TITRE2' => $return['CONFIRMATION_INSCRIPTIONPAGE_ZONE_MULTI_TITRE2'],
                                'PAGE_ZONE_MULTI_TEXT' => $return['CONFIRMATION_INSCRIPTIONPAGE_ZONE_MULTI_TEXT'],
                                'PAGE_ZONE_MULTI_TEXT2' => $return['CONFIRMATION_INSCRIPTIONPAGE_ZONE_MULTI_TEXT2']
                            );
                            break;
                        case 'CONNECTE':
                            $return[$type][] = array(
                                'PAGE_ZONE_MULTI_TITRE' => $return['CONNECTEPAGE_ZONE_MULTI_TITRE'],
                                'PAGE_ZONE_MULTI_TEXT' => $return['CONNECTEPAGE_ZONE_MULTI_TEXT']
                            );
                            break;
                    }
                }
            }
        }
        return $return;
    }

    //Nettoyage de values de toutes donn�es du multi pass� en param�tre � l'exception du tableau cr�� par readmulti
    private static function _clean ($strName)
    {
        if($strName != '' && count(Pelican_Db::$values)>0 && is_array(Pelican_Db::$values)){
            foreach(Pelican_Db::$values as $key => $value) {
                if($key != ''){
                    if(strpos($key,$strName) !== false && !is_array($value)) {
                        unset(Pelican_Db::$values[$key]);
                    }
                }
            }
        }
    }

    // fontion pour éclairer le choix du couleur
    private function color_inverse($color){
        $color = str_replace('#', '', $color);
        if (strlen($color) != 6){ return '000000'; }
        $rgb = '';
        for ($x=0;$x<3;$x++){
            $c = 255 - hexdec(substr($color,(2*$x),2));
            $c = ($c < 0) ? 0 : dechex($c);
            $rgb .= (strlen($c) < 2) ? '0'.$c : $c;
        }
        return '#'.$rgb;
    }
}
