<?php
/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Dev
 * @author __AUTHOR__
 */
pelican_import('Controller.Back');

/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Dev
 * @author __AUTHOR__
 */
class Cms_Controller extends Pelican_Controller_Back
{

    /**
     *
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $publication;

    /**
     *
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $cmsGetParams = array(
        'cid' => 'CONTENT',
        'pid' => 'PAGE'
    );

    /**
     * __DESC__
     *
     * @static __DESC__
     * @access protected
     * @var __TYPE__
     */
    protected $decachePublication = '';

    /**
     * __DESC__
     *
     * @static __DESC__
     * @access protected
     * @var __TYPE__
     */
    protected $decacheContent = '';

    /**
     * Activation du workflow
     *
     * @access protected
     * @var __TYPE__
     */
    protected $workflowField = "";

    /**
     * __DESC__
     *
     * @access protected
     * @var __TYPE__
     */
    protected $aLastValues = array();

    /**
     * __DESC__
     *
     * @access protected
     */
    protected $iVersionId;

    /**
     * __DESC__
     *
     * @access protected
     * @var __TYPE__
     */
    protected $iSharingId;

    /**
     * __DESC__
     *
     * @access protected
     * @var __TYPE__
     */
    protected $bVersioning = true;

    /**
     *
     * @access protected
     * @var __TYPE__ __DESC__
     */
    protected $bRewrite = true;

    /**
     * __DESC__
     *
     * @access protected
     * @var __TYPE__
     */
    protected $noResetVersion;

    /**
     *
     * @access protected
     * @var __TYPE__ __DESC__
     */
    protected $workflowFieldDeleteVersion;

    /**
     *
     * @access protected
     * @var __TYPE__ __DESC__
     */
    protected $createClearUrl;

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Controller#after()
     * @return __TYPE__
     */
    public function before()
    {
        /**
         * actions list/edit
         */
        if($this->getParam('versionSchedule')){
            $this->deletePageVersionSchedule($this->getParam('versionSchedule'));
        }
        if($this->getParam('scheduleFormClose')){
            unset($_SESSION[APP][$this->id][$_SESSION[APP][LANGUE_ID]]['SCHEDULE']);
        }
        if($this->getParam('schedulePage')){
            $_SESSION[APP][$this->id][$_SESSION[APP][LANGUE_ID]]['SCHEDULE'] = true;;
        }

        if ($this->administration && ! $this->show) {
            $_GET["error"] = t('Page réservée aux gestionnaires');
            $this->setResponse(Pelican_Request::call('Error'));
            $this->setAction('');
        }
       
        /**
         * actions insert/update/delete
         */
        if (! empty($this->form_name)) {
            $action = $this->getRequest()->getQuery('form_action');
            if ($this->getRequest()->isPost() || ! empty($action)) {
                
                /**
                 * CMS
                 */
                $this->beforeCms();
                parent::before();
                
                /**
                 * CMS
                 */
                $this->initWorkFlow();
                
                /**
                 * CMS
                 */
                $this->getClearUrl();
            }
        }       
    }
    
    private function deletePageVersionSchedule($pageVersion){
        $oConnection = Pelican_Db::getInstance();
        $aBind[":PAGE_VERSION"] = $pageVersion;            
        $sqlUpdate = "UPDATE #pref#_page SET PAGE_SCHEDULE_VERSION = NULL, SCHEDULE_STATUS = 0, PAGE_START_DATE_SCHEDULE = NULL, PAGE_END_DATE_SCHEDULE = NULL where PAGE_SCHEDULE_VERSION =:PAGE_VERSION";
        $oConnection->query($sqlUpdate, $aBind);
        unset($_SESSION[APP][$this->id][$_SESSION[APP][LANGUE_ID]]['SCHEDULE']);
    }
    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function after()
    {
        if (! empty($this->form_name)) {
            $action = $this->getRequest()->getQuery('form_action');
            if ($this->getRequest()->isPost() || ! empty($action)) {
                // debug($this->getParams());
                /**
                 * CMS
                 */
                if ($this->createClearUrl) {
                    $this->getClearUrl();
                }
                
                /**
                 * CMS
                 */
                $this->updateRewriting();
                // CPW-3893 : Intégration de balise hreflang
                $this->updateHreflang();
                
                
                /**
                 * CMS
                 */
                if ($_POST["form_preview"]) {
                    $_SESSION[APP]["form_preview"] = true;
                } else {
                    $_SESSION[APP]["form_preview"] = false;
                }
              
                /**
                 * CMS
                 */
                if (Pelican_Db::$values["NEW_LANGUE_ID"] != "") {
                    $_SESSION[APP]['LANGUE_ID'] = Pelican_Db::$values["NEW_LANGUE_ID"];
                }
                
                /**
                 * CMS
                 */
                if ($this->cybertag) {
                    include_once (Pelican::$config['LIB_ROOT'] . Pelican::$config['LIB_FRONT'] . "/cybertag.lib.php");
                    updateCyberTag($this->workflowField);
                }
            }
        }
        parent::after();
    }

    /**
     * *** Workflow *
     *
     * @access public
     * @return void
     */
    public function workflowAction()
    {
        if (Pelican_Security::validateCsrfToken()) {
            $oConnection = Pelican_Db::getInstance();
            if (Pelican_Db::$values[$this->workflowField . "_ID"] && !Pelican_Db::$values["isSubForm"]) {
                // WORKFLOW
                if (!Pelican_Db::$values["STATE_ID"] || Pelican_Db::$values["form_button"] == "save") {
                    Pelican_Db::$values["STATE_ID"] = Pelican::$config["DEFAULT_STATE"];
                }
                if (Pelican_Db::$values["form_schedule"]) {
                    Pelican_Db::$values["STATE_ID"] = 1;
                }
                $this->publication = Pelican_Cache::fetch("Backend/State/Publication", Pelican_Db::$values["STATE_ID"]);
                // Etat par défaut
                // la mise a jour des versions ne se fait pas pour le sous-formulaire
                if ($this->publication && Pelican_Db::$values["form_button"] != "save") {
                    Pelican_Db::$values["PUBLICATION"] = "1";
                }
                switch ($this->form_action) {
                    case Pelican_Db::DATABASE_INSERT: {
                        if (!Pelican_Db::$values[$this->workflowField . "_VERSION"]) {
                            // Etat par défaut, tout est à  la version 1
                            Pelican_Db::$values[$this->workflowField . "_VERSION"] = 1;
                            Pelican_Db::$values[$this->workflowField . "_DRAFT_VERSION"] = 1;
                            if (Pelican_Db::$values["PUBLICATION"]) {
                                Pelican_Db::$values[$this->workflowField . "_CURRENT_VERSION"] = 1; // wf
                                Pelican_Db::$values[$this->workflowField . "_STATUS"] = 1; // wf
                                if (!Pelican_Db::$values[$this->workflowField . "_PUBLICATION_DATE"]) {
                                    Pelican_Db::$values[$this->workflowField . "_PUBLICATION_DATE"] = ":DATE_COURANTE";
                                }
                            } else {
                                Pelican_Db::$values[$this->workflowField . "_CURRENT_VERSION"] = "";
                                Pelican_Db::$values[$this->workflowField . "_STATUS"] = "1";
                            } // wf
                        }
                        break;
                    }
                    case Pelican_Db::DATABASE_UPDATE: {
                        $url = parse_url($_GET['form_retour']);
                        parse_str($url['query']);
                        if ((Pelican_Db::$values["form_schedule"] || $_SESSION[APP][$this->id][$_SESSION[APP][LANGUE_ID]]['SCHEDULE'] || $schedulePage) && empty(Pelican_Db::$values[$this->workflowField . "_SCHEDULE_VERSION"])) {
                            Pelican_Db::$values[$this->workflowField . "_SCHEDULE_VERSION"] = Pelican_Db::$values[$this->workflowField . "_DRAFT_VERSION"];
                        }

                        if (!empty(Pelican_Db::$values[$this->workflowField . "_SCHEDULE_VERSION"])) {
                            Pelican_Db::$values[$this->workflowField . "_SCHEDULE_VERSION"] = Pelican_Db::$values[$this->workflowField . "_SCHEDULE_VERSION"];
                        }

                        if (Pelican_Db::$values[$this->workflowField . "_DRAFT_VERSION"] == Pelican_Db::$values[$this->workflowField . "_CURRENT_VERSION"] && !Pelican_Db::$values["form_schedule"] && !$_SESSION[APP][$this->id][$_SESSION[APP][LANGUE_ID]]['SCHEDULE'] && !$schedulePage) {
                            // Toute mise à  jour implique un état Brouillon
                            // Incrément de version si le contenu est publié (même version) => attention si c'est fait à  partir d'un historique
                            $aBind[":ID"] = Pelican_Db::$values[$this->workflowField . "_ID"];
                            $aBind[":LANGUE_ID"] = Pelican_Db::$values['LANGUE_ID'];
                            Pelican_Db::$values[$this->workflowField . "_DRAFT_VERSION"] = $oConnection->queryItem("select max(" . $this->workflowField . "_VERSION) +1 from #pref#_" . strtolower($this->workflowField) . "_version where " . $this->workflowField . "_ID=:ID AND LANGUE_ID=:LANGUE_ID", $aBind);
                            Pelican_Db::$values[$this->workflowField . "_VERSION"] = Pelican_Db::$values[$this->workflowField . "_DRAFT_VERSION"];
                            if (Pelican_Db::$values["PUBLICATION"]) {
                                Pelican_Db::$values[$this->workflowField . "_CURRENT_VERSION"] = Pelican_Db::$values[$this->workflowField . "_DRAFT_VERSION"];
                                if (Pelican_Db::$values['STATE_ID'] == Pelican::$config["CORBEILLE_STATE"]) {
                                    Pelican_Db::$values[$this->workflowField . "_STATUS"] = 0;
                                } else {
                                    Pelican_Db::$values[$this->workflowField . "_STATUS"] = 1;
                                }
                                if (!Pelican_Db::$values[$this->workflowField . "_PUBLICATION_DATE"]) {
                                    Pelican_Db::$values[$this->workflowField . "_PUBLICATION_DATE"] = ":DATE_COURANTE";
                                }
                            }

                            // Passage en insertion
                            $this->form_action = Pelican_Db::DATABASE_INSERT;

                            /**
                             * Mise à  jour de la version
                             */
                            $oConnection->updateTable(Pelican_Db::DATABASE_UPDATE, "#pref#_" . strtolower($this->workflowField));
                            /**
                             * on indique que la table suivante doit être en update et non en insertion
                             */
                            $oConnection->tableStopList = "#pref#_" . strtolower($this->workflowField);
                            // Suppression des historiques supérieurs à  Pelican::$config["HISTORIQUE_MAX"]
                            $sql = "select distinct " . $this->workflowField . "_VERSION from #pref#_" . strtolower($this->workflowField) . "_version where " . $this->workflowField . "_ID=" . Pelican_Db::$values[$this->workflowField . "_ID"] . " AND LANGUE_ID=:LANGUE_ID order by " . $this->workflowField . "_VERSION DESC ";
                            if ($this->workflowField == 'PAGE') {
                                $sql = "select distinct PAGE_VERSION from #pref#_page p inner join #pref#_page_version pv ON p.PAGE_ID = pv.PAGE_ID where pv.PAGE_ID=" . Pelican_Db::$values["PAGE_ID"] . " AND pv.LANGUE_ID=" . Pelican_Db::$values["LANGUE_ID"] . " AND p.PAGE_SCHEDULE_VERSION != pv.PAGE_VERSION AND p.SCHEDULE_STATUS != 1 order by PAGE_VERSION DESC ";
                            }
                            $sql = $oConnection->getLimitedSQL($sql, Pelican::$config["HISTORIQUE_MAX"], 10, true, $aBind);

                            $oConnection->query($sql, $aBind);
                            $this->workflowFieldDeleteVersion = $oConnection->data[$this->workflowField . "_VERSION"];
                        }
                        if (Pelican_Db::$values["form_schedule"] || $schedulePage) {
                            $aBind[":ID"] = Pelican_Db::$values[$this->workflowField . "_ID"];
                            $aBind[":LANGUE_ID"] = Pelican_Db::$values['LANGUE_ID'];
                            Pelican_Db::$values[$this->workflowField . "_SCHEDULE_VERSION"] = $oConnection->queryItem("select max(" . $this->workflowField . "_VERSION) +1 from #pref#_" . strtolower($this->workflowField) . "_version where " . $this->workflowField . "_ID=:ID AND LANGUE_ID=:LANGUE_ID", $aBind);
                            Pelican_Db::$values[$this->workflowField . "_VERSION"] = Pelican_Db::$values[$this->workflowField . "_SCHEDULE_VERSION"];
                            if (Pelican_Db::$values["PUBLICATION"]) {
                                Pelican_Db::$values[$this->workflowField . "_CURRENT_VERSION"] = Pelican_Db::$values[$this->workflowField . "_DRAFT_VERSION"];
                                if (Pelican_Db::$values['STATE_ID'] == Pelican::$config["CORBEILLE_STATE"]) {
                                    Pelican_Db::$values[$this->workflowField . "_STATUS"] = 0;
                                } else {
                                    Pelican_Db::$values[$this->workflowField . "_STATUS"] = 1;
                                }
                                if (!Pelican_Db::$values[$this->workflowField . "_PUBLICATION_DATE"]) {
                                    Pelican_Db::$values[$this->workflowField . "_PUBLICATION_DATE"] = ":DATE_COURANTE";
                                }
                            }

                            // Passage en insertion
                            $this->form_action = Pelican_Db::DATABASE_INSERT;

                            /**
                             * Mise à  jour de la version
                             */
                            $oConnection->updateTable(Pelican_Db::DATABASE_UPDATE, "#pref#_" . strtolower($this->workflowField));
                            /**
                             * on indique que la table suivante doit être en update et non en insertion
                             */
                            $oConnection->tableStopList = "#pref#_" . strtolower($this->workflowField);
                        }
                        if (Pelican_Db::$values["PUBLICATION"]) {
                            Pelican_Db::$values[$this->workflowField . "_CURRENT_VERSION"] = Pelican_Db::$values[$this->workflowField . "_DRAFT_VERSION"];
                            Pelican_Db::$values[$this->workflowField . "_STATUS"] = 1;
                            if (!Pelican_Db::$values[$this->workflowField . "_PUBLICATION_DATE"]) {
                                Pelican_Db::$values[$this->workflowField . "_PUBLICATION_DATE"] = ":DATE_COURANTE";
                            }
                        }
                        break;
                    }

                    case Pelican::$config["WORKFLOW"]: {
                        $oConnection->query("update #pref#_" . strtolower($this->workflowField) . "_version set STATE_ID=" . Pelican_Db::$values["STATE_ID"] . " where " . $this->workflowField . "_ID=" . Pelican_Db::$values[$this->workflowField . "_ID"] . " AND " . $this->workflowField . "_VERSION=" . Pelican_Db::$values[$this->workflowField . "_DRAFT_VERSION"]);
                        if (Pelican_Db::$values["STATE_ID"] == Pelican::$config["CORBEILLE_STATE"]) {
                            $oConnection->query("update #pref#_" . strtolower($this->workflowField) . " set " . strtolower($this->workflowField) . "_STATUS=0 where " . $this->workflowField . "_ID=" . Pelican_Db::$values[$this->workflowField . "_ID"]);
                        }
                        if (Pelican_Db::$values["PUBLICATION"]) {
                            $oConnection->query("update #pref#_" . strtolower($this->workflowField) . " set " . $this->workflowField . "_CURRENT_VERSION=" . Pelican_Db::$values[$this->workflowField . "_DRAFT_VERSION"] . ", " . $this->workflowField . "_STATUS=1 where " . $this->workflowField . "_ID=" . Pelican_Db::$values[$this->workflowField . "_ID"]);
                            $oConnection->query("update #pref#_" . strtolower($this->workflowField) . "_version set " . $this->workflowField . "_PUBLICATION_DATE=" . $oConnection->getNow() . " where " . $this->workflowField . "_ID=" . Pelican_Db::$values[$this->workflowField . "_ID"] . " and " . $this->workflowField . "_VERSION=" . Pelican_Db::$values[$this->workflowField . "_DRAFT_VERSION"]);
                        }
                        unset($this->form_action);
                        break;
                    }
                    case Pelican::$config["ACTION_ONLINE"]: {
                        // Mise en ligne ou hors ligne de la page via le statut PAGE_STATUS
                        // En ligne PAGE_STATUS = 1
                        // En ligne PAGE_STATUS = 0
                        // Le hors ligne va mettre la page en erreur 404 

                        $sqlEtatPageVersion = "update #pref#_" . strtolower($this->workflowField)
                            . " set " . $this->workflowField . "_STATUS=" . Pelican_Db::$values[$this->workflowField . "_STATUS"]
                            . " where " . $this->workflowField . "_ID=" . Pelican_Db::$values[$this->workflowField . "_ID"]
                            . " and LANGUE_ID=" . $_SESSION[APP]['LANGUE_ID'];
                        $oConnection->query($sqlEtatPageVersion);


                        $oConnection->query("update #pref#_research set RESEARCH_STATUS=" . Pelican_Db::$values[$this->workflowField . "_STATUS"] . " where RESEARCH_TYPE='" . $this->workflow . "' AND RESEARCH_ID=" . Pelican_Db::$values[$this->workflowField . "_ID"]);
                        if ($this->form_name == "content") {
                            Pelican_Cache::clean("Frontend/Content/Template", array(
                                Pelican_Db::$values[$this->workflowField . "_ID"]
                            ));
                            Pelican_Cache::clean("Frontend/Content", array(
                                Pelican_Db::$values[$this->workflowField . "_ID"]
                            ));
                        }
                        $aBind[":ID"] = Pelican_Db::$values[$this->workflowField . "_ID"];
                        $aBind[":LANGUE_ID"] = Pelican_Db::$values['LANGUE_ID'];
                        if ($this->form_name == "page") {
                            Pelican_Cache::clean("Backend/Page", array(
                                Pelican_Db::$values[$this->workflowField . "_ID"]
                            ), "", Pelican::$config["GROUP_DECACHE"]);
                        }
                        $_REQUEST['SITE_ID'] = $_SESSION[APP]['SITE_ID'];
                        self::execDecache();
                        unset($this->form_action);
                        Pelican_Db::$values["PUBLICATION"] = true;
                        $this->publication = true;

                        Pelican_Cache::clean("Frontend/Site/Tree", $_SESSION[APP]['SITE_ID']);
                        Pelican_Cache::clean("Frontend/Content/Sitemap", array(
                            $_SESSION[APP]['SITE_ID'],
                            $_SESSION[APP]['LANGUE_ID'],
                            'all'
                        ));
                        $this->bRewrite = false;

                        break;
                    }
                }
            }
        }else{
            echo ('<div style="background-color:red;color:white;text-align:center;font-size:25px;"><br /><br />'.t('PROBLEME_TECHNIQUE_SAUV').'<br /><br />'.t('VEULLIEZ_RAFRAICHIR_PAGE').'<br /><br /><br /><br /></div>');
            die();
        }
    }

    /**
     * __DESC__
     *
     * @static
     *
     *
     *
     * @access public
     * @param __TYPE__ $pageId
     *            __DESC__
     * @param string $typeId
     *            (option) __DESC__
     * @param string $id
     *            (option) __DESC__
     * @return __TYPE__
     */
    public static function getPageOrder($pageId, $typeId = "", $id = "")
    {
        $return = Pelican_Html::img(array(
            onclick => "popupSortHmvc('" . $pageId . "','" . $typeId . "', '" . $id . "');",
            src => "/library/public/images/sort.gif",
            border => 0,
            alt => "Ordre d'affichage",
            width => 17,
            height => 18,
            align => "center",
            hspace => 5,
            style => "cursor:pointer;"
        ));
        return $return;
    }

    /**
     * __DESC__
     *
     * @static
     *
     *
     *
     * @access public
     * @param __TYPE__ $page
     *            __DESC__
     * @param __TYPE__ $aId
     *            __DESC__
     * @param __TYPE__ $type
     *            __DESC__
     * @param __TYPE__ $langue
     *            (option) __DESC__
     * @return __TYPE__
     */
    static function setBatchPageOrder($page, $aId, $type, $langue = 1)
    {
        $oConnection = Pelican_Db::getInstance();
        $nb = count($aId);
        
        /**
         * on supprime l'entrée dans la table page_order
         */
        $DBVALUES_INI = Pelican_Db::$values;
        
        /**
         * suppression des contenus concernés dans la table d'order
         */
        $aBind[":LIMIT"] = $nb;
        $aBind[":PAGE_ID"] = $page;
        $aBind[":LANGUE_ID"] = $langue;
        $aBind[":PAGE_ORDER_TYPE"] = $type;
        $sSql = "delete from #pref#_page_order where PAGE_ID=:PAGE_ID AND LANGUE_ID=:LANGUE_ID AND PAGE_ORDER_ID in (" . implode(",", $aId) . ")";
        $oConnection->query($sSql, $aBind);
        if ($aId) {
            Pelican_Db::$values["PAGE_ID"] = $page;
            Pelican_Db::$values['LANGUE_ID'] = $langue;
            Pelican_Db::$values["PAGE_ORDER_TYPE"] = $type;
            foreach ($aId as $id) {
                if ($id) {
                    Pelican_Db::$values["PAGE_ORDER_ID"] = $id;
                    Pelican_Db::$values["PAGE_ORDER"] ++;
                    $oConnection->insertQuery("#pref#_page_order");
                }
            }
            self::cleanOrder($page);
        }
    }

    /**
     * __DESC__
     *
     * @static
     *
     *
     *
     * @access public
     * @param __TYPE__ $page
     *            __DESC__
     * @return __TYPE__
     */
    static function cleanOrder($page)
    {
        Pelican_Cache::clean("Frontend/Page/Childall", $page, "", Pelican::$config["GROUP_DECACHE"]);
        Pelican_Cache::clean("Frontend/Page/ChildContent", array(
            $page,
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID']
        ), "", Pelican::$config["GROUP_DECACHE"]);
        Pelican_Cache::clean("Frontend/Page/ChildContent", array(
            $page,
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            "DRAFT"
        ), "", Pelican::$config["GROUP_DECACHE"]);
    }

    /**
     * __DESC__
     *
     * @static
     *
     *
     *
     * @access public
     * @param __TYPE__ $id
     *            __DESC__
     * @param __TYPE__ $type
     *            __DESC__
     * @param string $title
     *            (option) __DESC__
     * @param string $path
     *            (option) __DESC__
     * @param string $mediaId
     *            (option) __DESC__
     * @param string $externalLink
     *            (option) __DESC__
     * @param string $xiti
     *            (option) __DESC__
     * @return __TYPE__
     */
    static function makeClearUrl($id, $type, $title = "", $path = "", $mediaId = "", $externalLink = "", $xiti = "")
    {
        $return = "/_/Index/?" . $type . "=" . $id;
        if ($externalLink) {
            $return = $externalLink . "\" target=\"_blank";
            $file = basename($externalLink);
        } elseif ($mediaId) {
            include_once (pelican_path('Media'));
            $return = Pelican::$config["MEDIA_HTTP"] . Pelican_Media::getMediaPath($mediaId);
            $file = basename($return);
        } elseif ($title) {
            // $return = str_replace("-.html", ".html", "/" . $type . $id . "-" . Pelican_Text::cleanText($title, "-", false, false) . ".html");
            $return = str_replace("-.html", ".html", "/" . Pelican_Text::cleanText($title, "-", false, false) . ".html");
            if ($path) {
                // Au premier enregistrement, le path est entouré de ces caractères
                $path = trim($path, "'#");
                $tmp = explode("#", $path);
                
                /**
                 * suppression du premier niveau
                 */
                /*
                 * old if (count($tmp) > 1) { array_shift($tmp); }
                 */
                if ($type == 'cid')                 // bugzilla 21147
                {
                    if (count($tmp) > 1) {
                        $tmp = array_slice($tmp, - 1, 1);
                    }
                } else {
                    if (count($tmp) > 1) {
                        array_shift($tmp);
                    }
                }
                if ($tmp) {
                    foreach ($tmp as $rep) {
                        if ($rep != '') {
                            $aRep = explode("|", $rep);
                            $aURL[] = Pelican_Text::cleanText($aRep[1], "-", false, false);
                        }
                    }
                }
                
                /**
                 * only for sites with multiples languages
                 */
                if (count(Cms_Controller::getLanguageSite()) > 1) {
                    $aLangue = Cms_Controller::getLanguageSite($_SESSION[APP]['LANGUE_ID']);
                    array_unshift($aURL, '/' . $aLangue[0]['LANGUE_CODE']);
                }
                
                if ($aURL) {
                    if ($type == "pid") {
                        array_pop($aURL);
                    }
                    // array_pop($aURL);
                    $return0 = implode("/", $aURL);
                }
                
                $return = "/" . $return0 . $return;
            }
            $return = str_replace("-/", "/", $return);
            $return = str_replace("/-", "/", $return);
            $return = str_replace("-.", ".", $return);
            $return = str_replace("//", "/", $return);
            $return = str_replace("---", "-", $return);
            $return = str_replace("--", "-", $return);
        }
        if ($xiti && $file) {
            $return .= "\" onclick=\"xt_med('C','" . $xiti . "','" . $file . "','T');";
        }
        $return = strtolower($return);
        return $return;
    }

    /**
     * __DESC__
     *
     * @access protected
     * @param __TYPE__ $param
     *            (option) __DESC__
     * @param __TYPE__ $param_lob
     *            (option) __DESC__
     * @return __TYPE__
     */
    protected function _initBack($param = array(), $param_lob = array())
    {
        if ($this->form_name == "page") {
            $this->iContentTypeId = 1;
        }
        if (! isset($this->id)) {
            $this->aButton["mutualisation"] = $this->getSharingRights();
        }
        parent::_initBack();
        if (! empty($this->id) && $this->form_action == Pelican_Db::DATABASE_DELETE) {
            echo $this->getUsage();
        }
    }

    /**
     * __DESC__
     *
     * @access protected
     * @return __TYPE__
     */
    protected function initParams()
    {
        parent::initParams();
        if (! empty($this->form_name)) {
            
            /**
             * init GET params
             */
            $paramList = array(
                'iVersionId' => 'version',
                'iSharingId' => 'mutualisation',
                'iContentTypeId' => 'uid',
                'iContentTypeIdSearch' => 'rechercheContentType'
            );
            $this->_assignVars($paramList);
        }
        if (((! empty($_SESSION[APP]["content_type"][$this->iContentTypeId]["state"]) || $this->form_name == "page") || ! $this->iContentTypeId)) {
            $this->bNoDelete = false;
        }
    }

    /**
     * __DESC__
     *
     * @access protected
     * @return __TYPE__
     */
    protected function beforeCms()
    {
        $oConnection = Pelican_Db::getInstance();
        
        /**
         * * Action de suppression dans les rubriques
         */
        if ($_POST["form_button"] == "delete") {
            $_POST["form_action"] = Pelican_Db::DATABASE_DELETE;
        }
        if (valueExists($_POST, "PAGE_PARENT_ID")) {
            $_POST["PAGE_PARENT_LEVEL2"] = $this->getParentPage($_POST["PAGE_PARENT_ID"]);
        }
        if ($_POST["OLD_PAGE_PARENT_ID"] == 'Array') {
            $_POST["OLD_PAGE_PARENT_ID"] = '';
        }
        if (valueExists($_POST, "OLD_PAGE_PARENT_ID")) {
            $_POST["OLD_PAGE_PARENT_LEVEL2"] = $this->getParentPage($_POST["OLD_PAGE_PARENT_ID"]);
        }
        if (isset($_POST[$this->workflowField . '_CREATION_USER']) && is_array($_POST[$this->workflowField . '_CREATION_USER'])) {
            $_POST[$this->workflowField . '_CREATION_USER'] = '#' . str_replace('#', '##', implode('#', $_POST[$this->workflowField . '_CREATION_USER'])) . '#';
        }
        if (isset($_POST['PAGE_ID']) && ($_POST['PAGE_ID'] != '') && isset($_POST['CONTENT_ID']) && ($_POST['CONTENT_ID'] == - 2)) {
            // Cas d'une création, on complà¨te la liste des créateurs avec celle de la rubrique parente
            $this->aBind[':PAGE_ID'] = $_POST['PAGE_ID'];
            $srtSql = '
					SELECT
					PAGE_CREATION_USER
					FROM
					#pref#_page
					WHERE
					PAGE_ID = :PAGE_ID
					AND LANGUE_ID = 1
					';
            $arrayCreationUsers = $oConnection->queryTab($srtSql, $this->aBind);
            foreach ($arrayCreationUsers as $k => $v) {
                if (strpos($_POST['CONTENT_CREATION_USER'], $v['PAGE_CREATION_USER']) === false) {
                    $_POST['CONTENT_CREATION_USER'] .= $v['PAGE_CREATION_USER'];
                }
            }
        }
    }

    /**
     * __DESC__
     *
     * @access protected
     * @param __TYPE__ $oForm
     *            __DESC__
     * @return __TYPE__
     */
    protected function endFormCms(&$oForm)
    {
        $form = '';
        
        /**
         * Mutualisation
         */
        $form .= Backoffice_Form_Helper::sharingForm($oForm);
        
        /**
         * Urls claires
         */
        if ($this->workflowField && $this->bRewrite) {
            $form .= Backoffice_Form_Helper::rewritingForm($oForm, $this->id, $this->workflowField, $this->values, $this->readO);
        }
        
        /**
         * Marqueurs
         */
        /*
         * if ($this->cybertag) { $form.= Backoffice_Form_Helper::cybertagForm($oForm, $this->id, $this->values, $this->readO, $this->cybertag[0], $this->cybertag[1]); }
         */
        return $form;
    }

    /**
     * __DESC__
     *
     * @access protected
     * @param __TYPE__ $oForm
     *            __DESC__
     * @return __TYPE__
     */
    protected function beginForm(&$oForm)
    {
        parent::beginForm($oForm);
        $this->setDefaultValue("CONTENT_TYPE_ID", (isset($_GET["uid"]) ? $_GET["uid"] : ""));
        $this->setDefaultValue("STATE", (isset(Pelican::$config["STATE_DEFAUT"]) ? Pelican::$config["STATE_DEFAUT"] : ""));
        $this->setDefaultValue("PAGE_STATUT", (isset(Pelican::$config["STATUT_DEFAUT"]) ? Pelican::$config["STATUT_DEFAUT"] : ""));
        if (valueExists($_SESSION[APP], "form_preview")) {
            $site = Pelican_Cache::fetch("Frontend/Site", $_SESSION[APP]['SITE_ID']);
            $url = $site["SITE_URL"];
            $protocole = 'http://';
            $urlBO = ($_SERVER['HTTP_CLIENT_HOST'] != '') ? $_SERVER['HTTP_CLIENT_HOST'] : $_SERVER['SERVER_NAME'];
            if ($site['DNS'][$urlBO]) {
                if ($site['DNS'][$urlBO]['SITE_DNS_BO']) {
                    $url = $site['DNS'][$urlBO]['SITE_DNS_BO'];
                }
                if ($site['DNS'][$urlBO]['SITE_DNS_HTTP']) {
                    $protocole = ($site['DNS'][$urlBO]['SITE_DNS_HTTP'] == 'https') ? 'https://' : 'http://';
                }
            }
            if (! $_GET["uid"]) {
                $param = "pid";
            } else {
                $param = "cid";
            }
            $this->getView()
                ->getHead()
                ->setScript("window.open('" . $protocole . $url . "/_/Index/preview?" . $param . "=" . $_GET["id"] . "', '_blank');");
            $_SESSION[APP]["form_preview"] = false;
        }
        
        if (valueExists($_SESSION[APP], "form_schedule")) {
            $_SESSION[APP]["form_schedule"] = false;
        }        
        if (! empty($_SESSION[APP]["REWRITE_ERROR"]) || ! empty($_SESSION[APP]["REWRITE_ERROR_CLEAR_URL"])) {
            
            $urls = '';
            
            if ($_SESSION[APP]["REWRITE_ERROR_CLEAR_URL"] != '') {
                // $urls .= "L\'url claire est déjà  utilisées sur " . $_SESSION[APP]["REWRITE_ERROR_CLEAR_URL"];
                // $urls .= "\\nElle a été modifiée.";
                
                $urls .= t("CLEAR_URL_USED", 'js', array(
                    $_SESSION[APP]["REWRITE_ERROR_CLEAR_URL"]
                ));
                $urls .= "\\n";
                $urls .= t("URL_CHANGE", 'js');
            }
            
            if ($_SESSION[APP]["REWRITE_ERROR_CLEAR_URL"] != '' && ! empty($_SESSION[APP]["REWRITE_ERROR"])) {
                $urls .= "\\n\\n";
            }
            
            if (! empty($_SESSION[APP]["REWRITE_ERROR"])) {
                // $urls .= "Les urls alternatives suivantes sont déjà  utilisées :";
                $urls .= t("REDIRECT_URL_USED", 'js');
                $urls .= "\\n- " . implode("\\n- ", $_SESSION[APP]["REWRITE_ERROR"]);
            }
            echo Pelican_Html::script("alert('" . $urls . "');");
            
            unset($_SESSION[APP]["REWRITE_ERROR"]);
            unset($_SESSION[APP]["REWRITE_ERROR_CLEAR_URL"]);
        }
    }

    /**
     * __DESC__
     *
     * @access protected
     * @param __TYPE__ $oForm
     *            __DESC__
     * @param __TYPE__ $type
     *            (option) __DESC__
     * @param string $retour
     *            (option) __DESC__
     * @param bool $noSave
     *            (option) __DESC__
     * @param bool $noBack
     *            (option) __DESC__
     * @param bool $noDelete
     *            (option) __DESC__
     * @return __TYPE__
     */
    protected function endForm(&$oForm, $type = array(), $retour = "", $noSave = false, $noBack = false, $noDelete = false)
    {
        
        /**
         * Cms
         */
        $form = '';
        $form .= $this->endFormCms($oForm);
        $form .= $this->getDefaultField($oForm, $retour);
        
        /**
         * Gestion des boutons
         */
        $form .= $this->getFormButtons($oForm, $type);
        if ($this->workflowField) {
            if (! $this->bNoDelete && ! $this->readO) {
                $form .= $this->getWorkflowButtons($oForm);
            }
        }
        if ($noSave) {
            $this->aButton["save"] = "";
        }
        if ($noBack) {
            $this->aButton["back"] = "";
        }
        if ($noDelete) {
            $this->aButton["delete"] = "";
        }
        Backoffice_Button_Helper::init($this->aButton);
        return $form;
    }

    /**
     * __DESC__
     *
     * @access protected
     * @param __TYPE__ $oForm
     *            __DESC__
     * @param string $retour
     *            (option) __DESC__
     * @return __TYPE__
     */
    protected function getDefaultField(&$oForm, $retour = "")
    {
        parent::getDefaultField($oForm, $retour);
        
        /**
         * Gestion de contenu
         */
        if ($this->iContentTypeId) {
            
            /**
             * type de contenu
             */
            if (! valueExists($oForm->_inputName, "CONTENT_TYPE_ID")) {
                if (! valueExists($this->values, "CONTENT_TYPE_ID")) {
                    $this->values["CONTENT_TYPE_ID"] = $this->iContentTypeId;
                }
                $oForm->createHidden("CONTENT_TYPE_ID", $this->iContentTypeId);
            }
            
            /**
             * alertes mails
             */
            $sqlAlerte = "select count(1) as count from #pref#_content_type_site where CONTENT_TYPE_ID=" . $this->iContentTypeId . " and SITE_ID=" . $_SESSION[APP]['SITE_ID'] . " AND CONTENT_ALERTE=1";
            $oConnection = Pelican_Db::getInstance();
            $AlerteResult = $oConnection->queryrow($sqlAlerte);
            if (valueExists($AlerteResult, "COUNT")) {
                $oForm->createHidden("form_alerte", "");
            }
        }
        $oForm->createHidden("old_STATE_ID", $this->values['STATE_ID']);
    }
    // # workflow
    
    /**
     * Création des éléments liés au versioning (historique, champs)
     *
     * @access protected
     * @return __TYPE__
     */
    protected function getVersioningForm()
    {
        $oConnection = Pelican_Db::getInstance();
        $sworkflowFieldTitleName = $this->workflowField . "_TITLE";
        if ($this->form_name == "content") {
            // cas de contenu
            $sworkflowFieldTitleName .= "_BO";
        }        
        if ($this->bVersioning && $this->values[$this->workflowField . "_VERSION"] && ! $this->bPopup) {            
            /**
             * * sélection des versions publiées
             */
            $aBind = array();
            $aBind[":LANGUE_ID"] = $_SESSION[APP]['LANGUE_ID'];
            $sqlPageVersionSchedule = 'SELECT PAGE_SCHEDULE_VERSION FROM #pref#_page WHERE PAGE_ID =' . $this->id;
            $pageVersionSchedule = $oConnection->queryRow($sqlPageVersionSchedule);

            $sql = "select
					USER_NAME,
					" . $this->workflowField . "_VERSION,
					" . $sworkflowFieldTitleName . ",
					" . $this->workflowField . "_VERSION_UPDATE_USER,
					" . $oConnection->dateSqlToString($this->workflowField . "_VERSION_UPDATE_DATE", "DD/MM/YYYY HH24:MI:SS") . " as " . $this->workflowField . "_UPDATE_DATE
					from
					#pref#_user,
					#pref#_" . strtolower($this->workflowField) . "_version,
					#pref#_" . strtolower($this->workflowField) . "
					where
					#pref#_" . strtolower($this->workflowField) . "_version." . $this->workflowField . "_ID=#pref#_" . strtolower($this->workflowField) . "." . $this->workflowField . "_ID
					and #pref#_" . strtolower($this->workflowField) . "." . $this->workflowField . "_ID=" . $this->id . "
					and " . $this->workflowField . "_VERSION <= " . $this->workflowField . "_CURRENT_VERSION ";
            $sql .= " and #pref#_" . strtolower($this->workflowField) . ".LANGUE_ID = #pref#_" . strtolower($this->workflowField) . "_version.LANGUE_ID ";
            if($this->workflowField == 'PAGE' && !empty($pageVersionSchedule['PAGE_SCHEDULE_VERSION'])){
                $sql .= " and #pref#_" . strtolower($this->workflowField) . ".PAGE_SCHEDULE_VERSION != #pref#_" . strtolower($this->workflowField) . "_version." . $this->workflowField . "_VERSION ";
            }
            $sql .= " and #pref#_" . strtolower($this->workflowField) . "_version.LANGUE_ID = :LANGUE_ID
                
					and #pref#_user.USER_LOGIN = " . $this->workflowField . "_VERSION_UPDATE_USER
					order by " . $this->workflowField . "_VERSION DESC LIMIT 0," . Pelican::$config["HISTORIQUE_MAX"];
            $versionResult = $oConnection->queryTab($sql, $aBind);
            
            /**
             * * Création de la liste des versions
             */
            if ($versionResult) {
                $i = 0;
                foreach ($versionResult as $valeur) {
                    $aToggle = array();
                    $aToggle[] = Pelican_Html::td(array(
                        "class" => "tblaltb",
                        width => "2%"
                    ), "(" . $valeur[$this->workflowField . "_VERSION"] . ")");
                    $aToggle[] = Pelican_Html::td(array(
                        "class" => "tblaltb",
                        width => "40%"
                    ), $valeur[$sworkflowFieldTitleName]);
                    $aToggle[] = Pelican_Html::td(array(
                        "class" => "tblaltb",
                        width => "40%"
                    ), t('PUBLICATION_ON') . $valeur[$this->workflowField . "_UPDATE_DATE"] . "&nbsp;" . t('PUBLICATION_BY') . $valeur["USER_NAME"] . " - " . $valeur[$this->workflowField . "_VERSION_UPDATE_USER"]);
                    if (! $this->readO) {
                        if ($i == 0) {
                            if (! $this->values[$this->workflowField . "_STATUS"]) {
                                $etat = t('ON_LINE');
                                $state = "1";
                            } else {
                                $etat = t('OFFLINE');
                                $state = "0";
                            }
                            $aToggle[] = Pelican_Html::td(array(
                                "class" => "tblaltb",
                                width => "10%"
                            ), Pelican_Html::input(array(
                                name => $etat,
                                type => "button",
                                "class" => "button",
                                value => $etat,
                                onclick => "top.putonline(" . $this->values[$this->workflowField . "_ID"] . "," . $state . ", '" . $this->workflowField . "');"
                            )));
                            $i ++;
                        } else {
                            $aToggle[] = Pelican_Html::td(array(
                                "class" => "tblaltb",
                                width => "10%"
                            ), "&nbsp;");
                        }
                        $aToggle[] = Pelican_Html::td(array(
                            "class" => "tblaltb",
                            width => "10%"
                        ), Pelican_Html::input(array(
                            name => t('FORM_BUTTON_COPY'),
                            type => "button",
                            "class" => "button",
                            value => t('FORM_BUTTON_COPY'),
                            onclick => "document.location.href='" . $_SERVER["REQUEST_URI"] . "&version=" . $valeur[$this->workflowField . "_VERSION"] . "';"
                        )));
                    }
                    $aTr[] = Pelican_Html::tr(array(), implode("", $aToggle));
                }
                $toggle = Pelican_Html::table(array(
                    cellpadding => "2",
                    cellspacing => "0",
                    "class" => "version"
                ), implode("", $aTr));
                $plusieurs = (count($versionResult) > 1 ? "s" : "");
                if($plusieurs){
                    $version =  t('VERSIONS_PUBLIEES');
                }else{
                    $version =  t('VERSION_PUBLIEE');
                }
                $return = createToggle("historique", "(" . count($versionResult) . "&nbsp;" . $version . ")", $toggle, true, true, false);
                // $return = Pelican_Html::tr(Pelican_Html::td(array(colspan=>2), $return));
                return Pelican_Html::div(array(
                    "class" => "versioning"
                ), $return);
            }
        }
    }
    
    /**
     * Création des éléments liés au versioning des pages planifiés
     *
     * @access protected
     * @return __TYPE__
     */
    protected function getScheduleForm()
    {
        $oConnection = Pelican_Db::getInstance();
        $sworkflowFieldTitleName = $this->workflowField . "_TITLE";
        if ($this->bVersioning && $this->values[$this->workflowField . "_VERSION"] && ! $this->bPopup) {           
            /**
             * * sélection des versions publiées
             */
            $aBind = array();
            $aBind[":LANGUE_ID"] = $_SESSION[APP]['LANGUE_ID'];
            $sql = "select					
                    USER_NAME,
                    #pref#_" . strtolower($this->workflowField) . '.' . $this->workflowField . "_ID,
                    " . $this->workflowField . "_VERSION,
                    " . $sworkflowFieldTitleName . ",
                    " . $this->workflowField . "_VERSION_UPDATE_USER,
                    " . $oConnection->dateSqlToString($this->workflowField . "_START_DATE_SCHEDULE", false) . " as " . $this->workflowField . "_START_DATE_SCHEDULE,
                    " . $oConnection->dateSqlToString($this->workflowField . "_END_DATE_SCHEDULE", false) . " as " . $this->workflowField . "_END_DATE_SCHEDULE,
                    SCHEDULE_STATUS
                    from
                    #pref#_user,
                    #pref#_" . strtolower($this->workflowField) . "_version,
                    #pref#_" . strtolower($this->workflowField) . "
                    where
                    #pref#_" . strtolower($this->workflowField) . "_version." . $this->workflowField . "_ID=#pref#_" . strtolower($this->workflowField) . "." . $this->workflowField . "_ID
                    and #pref#_" . strtolower($this->workflowField) . "." . $this->workflowField . "_ID=" . $this->id . "
                    and " . $this->workflowField . "_VERSION = " . $this->workflowField . "_SCHEDULE_VERSION ";
            $sql .= " and #pref#_" . strtolower($this->workflowField) . ".LANGUE_ID = #pref#_" . strtolower($this->workflowField) . "_version.LANGUE_ID ";
            $sql .= " and #pref#_" . strtolower($this->workflowField) . "_version.LANGUE_ID = :LANGUE_ID
					and #pref#_user.USER_LOGIN = " . $this->workflowField . "_VERSION_UPDATE_USER
					order by " . $this->workflowField . "_VERSION DESC";
            $versionResult = $oConnection->queryTab($sql, $aBind);

            /**
             * * Création de la liste des versions
             */
            if ($versionResult) {
                $i = 0;
                foreach ($versionResult as $valeur) {
                    $aToggle = array();
                    
                    $labelSchedule = t('SCHEDULE_ON') . ' ' . $valeur[$this->workflowField . "_START_DATE_SCHEDULE"] . "&nbsp;" . t('TO') . $valeur[$this->workflowField . "_END_DATE_SCHEDULE"] . ' ' . t('PUBLICATION_BY') . ' ' . $valeur["USER_NAME"];
                    if($valeur['SCHEDULE_STATUS']){
                        $labelSchedule = t('IN_PROGRESS_OF_SCHEDULE_UNTIL') . ' ' .  $valeur[$this->workflowField . "_END_DATE_SCHEDULE"] . ' ' . t('PUBLICATION_BY') . ' ' . $valeur["USER_NAME"];
                        $msgInfos = ' (' . t('VERSION_ORIGINAL') . ')';
                    }
                    $aToggle[] = Pelican_Html::td(array(
                        "class" => "tblaltb",
                        width => "2%"
                    ), "(" . $valeur[$this->workflowField . "_VERSION"] . ")");
                    $aToggle[] = Pelican_Html::td(array(
                        "class" => "tblaltb",
                        width => "40%"
                    ), $valeur[$sworkflowFieldTitleName] . $msgInfos);
                    $aToggle[] = Pelican_Html::td(array(
                        "class" => "tblaltb",
                        width => "40%"
                    ),  $labelSchedule);
                    if (! $this->readO) {
                        $pageId = $valeur[$this->workflowField . "_ID"];
                        $site = Pelican_Cache::fetch("Frontend/Site", $_SESSION[APP]['SITE_ID']);
                        $url = $site["SITE_URL"];
                        $protocole = 'http://';
                        $urlBO = ($_SERVER['HTTP_CLIENT_HOST'] != '') ? $_SERVER['HTTP_CLIENT_HOST'] : $_SERVER['SERVER_NAME'];
                        if ($site['DNS'][$urlBO]) {
                            if ($site['DNS'][$urlBO]['SITE_DNS_BO']) {
                                $url = $site['DNS'][$urlBO]['SITE_DNS_BO'];
                            }
                            if ($site['DNS'][$urlBO]['SITE_DNS_HTTP']) {
                                $protocole = ($site['DNS'][$urlBO]['SITE_DNS_HTTP'] == 'https') ? 'https://' : 'http://';
                            }
                        }
                        $aToggle[] = Pelican_Html::td(array(
                            "class" => "tblaltb",
                            width => "10%"
                        ), Pelican_Html::input(array(
                            name => t('PREVIEW'),
                            type => "button",
                            "class" => "button",
                            value => t('PREVIEW'),
                            onclick => "window.open('" . $protocole . $url . "/_/Index/preview?schedule=1&pid=" . $pageId . "', '_blank');"
                        )));
                        $labelBoutton = t("CONFIMATION_OUVERTURE_MODE_EDITION_PAGE_PLANIFIER");
                        $aToggle[] = Pelican_Html::td(array(
                            "class" => "tblaltb",
                            width => "10%"
                        ), Pelican_Html::input(array(
                            name => t('EDIT_PAGE_SCHEDULE'),
                            type => "button",
                            "class" => "button",
                            value => t('EDIT_PAGE_SCHEDULE'),
                            onclick => "reloadPage('/_/Index/child?tid={$_GET['tid']}&id={$pageId}&view=&schedulePage=1','iframeRight', '{$labelBoutton}');"
                        )));
                        $aToggle[] = Pelican_Html::td(array(
                            "class" => "tblaltb",
                            width => "10%"
                        ), Pelican_Html::input(array(
                            name => t('FORM_BUTTON_COPY'),
                            type => "button",
                            "class" => "button",
                            value => t('FORM_BUTTON_COPY'),
                            onclick => "document.location.href='" . $_SERVER["REQUEST_URI"] . "&version=" . $valeur[$this->workflowField . "_VERSION"] . "';"
                        )));                            
                        $aToggle[] = Pelican_Html::td(array(
                            "class" => "tblaltb",
                            width => "10%"
                        ), Pelican_Html::input(array(
                            name => t('DELETE'),
                            type => "button",
                            "class" => "button",
                            value => t('DELETE'),
                            onclick => "document.location.href='" . $_SERVER["REQUEST_URI"] . "&versionSchedule=" . $valeur[$this->workflowField . "_VERSION"] . "';"
                        )));                        
                    }
                    $aTr[] = Pelican_Html::tr(array(), implode("", $aToggle));
                }
                $toggle = Pelican_Html::table(array(
                    cellpadding => "2",
                    cellspacing => "0",
                    "class" => "version"
                ), implode("", $aTr));
                $return = createToggle("schedule", t('VERSION_SCHEDULE') , $toggle, true, true, false);
                return Pelican_Html::div(array(
                    "class" => "versioning"
                ), $return);
            }
        }
    }    

    /**
     * Réinitialise les valeurs de workflow liées au contenu
     *
     * @access protected
     * @return __TYPE__
     */
    protected function resetWorkflowValues()
    {
        
        /**
         * * Dans le cas de l'interception on écrase certaines valeurs avec les plus récentes
         */
        if (! $this->noResetVersion) {
            $this->values[$this->workflowField . "_VERSION"] = $this->aLastValues[$this->workflowField . "_VERSION"];
            $this->values["STATE_ID"] = $this->aLastValues["STATE_ID"];
            $this->values[$this->workflowField . "_STATUS"] = $this->aLastValues[$this->workflowField . "_STATUS"];
        }
    }

    /**
     * Initialisation des objets de données liés au formulaire
     *
     * @access protected
     * @return __TYPE__
     */
    protected function setFormValues()
    {
        if ($this->id) {
            if ($this->getEditModel()) {
                $oConnection = Pelican_Db::getInstance();
                
                /**
                 * * On intercepte la version dans le cas d'une duplication : on remplace simplement le contenu
                 */
                if ($this->workflowField) {
                    if ($this->bVersioning && $this->iVersionId) {
                        $this->aLastValues = $oConnection->queryForm($this->editModel, $this->aBind, $this->aBindLob);
                        
                        /**
                         * * plusieurs variantes possibles dans la chaine SQL pour DRAFT_VERSION
                         */
                        $this->editModel = str_replace("#pref#_" . strtolower($this->workflowField) . "." . $this->workflowField . "_DRAFT_VERSION", $this->workflowField . "_DRAFT_VERSION", $this->editModel);
                        $this->editModel = str_replace("c." . $this->workflowField . "_DRAFT_VERSION", $this->workflowField . "_DRAFT_VERSION", $this->editModel);
                        $this->editModel = str_replace($this->workflowField . "_DRAFT_VERSION", $this->iVersionId, $this->editModel);
                        
                        /**
                         * * REQUEST_URI est l'iframe de versions déjà  publiées, mauvaise valeur pour form_retour
                         */
                        if ($this->workflowField == "CONTENT" && ! $this->bPopup) { // PLA20130130 : correction du retour aprà¨s dupliacation. TODO : vérifier si nécessaire en popup
                            $_SESSION[APP]["session_start_page" . $this->bPopup] = str_replace("&version=" . $this->iVersionId, "", str_replace("&id=" . $this->id, "", $_SERVER["REQUEST_URI"]));
                        } elseif ($this->workflowField == "PAGE" && ! $this->bPopup) { // PLA201300201 : correction du retour aprà¨s dupliacation. TODO : vérifier si nécessaire en popup
                            $_SESSION[APP]["session_start_page" . $this->bPopup] = str_replace("&version=" . $this->iVersionId, "", $_SERVER["REQUEST_URI"]);
                        }
                    }
                }
                $this->values = $oConnection->queryForm($this->editModel, $this->aBind, $this->aBindLob);
                if (! $this->aLastValues) {
                    $this->aLastValues = $this->values;
                } else {
                    if ($this->workflowField) {
                        $this->values["STATE_ID"] = $this->aLastValues["STATE_ID"];
                        $this->values[$this->workflowField . "_STATUS"] = $this->aLastValues[$this->workflowField . "_STATUS"];
                        $this->values[$this->workflowField . "_DRAFT_VERSION"] = $this->iVersionId;
                        $this->values[$this->workflowField . "_CURRENT_VERSION"] = $this->iVersionId;
                    }
                }
            }
            $this->setFormValuesCms();
        }
    }

    /**
     * __DESC__
     *
     * @access protected
     * @return __TYPE__
     */
    protected function setFormValuesCms()
    {
        if ($this->getEditModel()) {
            $oConnection = Pelican_Db::getInstance();
            
            /**
             * * cas de la mutualisation
             */
            if ($this->form_name == 'content') {
                if ($this->iSharingId) {
                    if ($this->readO) {
                        $this->iSharingId = $this->id;
                    }
                    $this->aBind[":CONTENT_ID"] = $this->iSharingId;
                    $this->values = $oConnection->queryForm($this->editModel, $this->aBind, $this->aBindLob);
                    $this->values["STATE_ID"] = "";
                    $this->values[$this->workflowField . "_STATUS"] = "";
                    $this->values[$this->workflowField . "_DRAFT_VERSION"] = "1";
                    $this->values[$this->workflowField . "_CURRENT_VERSION"] = "";
                    $this->values[$this->workflowField . "_CREATION_DATE"] = "";
                    $this->values[$this->workflowField . "_CREATION_USER"] = "";
                    $this->values[$this->workflowField . "_VERSION_CREATION_DATE"] = "";
                    $this->values[$this->workflowField . "_VERSION_CREATION_USER"] = "";
                    $this->values[$this->workflowField . "_PUBLICATION_DATE"] = "";
                }
            }
        }
        
        /**
         * cas des langues, bascule à  partir d'une langue existante
         */
        if ($this->form_name == 'content' || $this->form_name == 'page') {
            if (! $this->values && $this->id != Pelican::$config["DATABASE_INSERT_ID"] && $this->lang) {
                $aBind[":ID"] = $this->id;
                $aBind[":LANGUE_ID"] = $this->lang;
                $aBind[":VERSION"] = 1;
                $aBind[":TITLE_BO"] = $oConnection->strtobind(" ");
                $aBind[":TITLE"] = $oConnection->strtobind(" ");
                $aBind[":TYPE"] = ($this->iContentTypeId ? $this->iContentTypeId : 1);
                $aBind[":STATE_ID"] = 1;
                $aBind[":SITE"] = $_SESSION[APP]['SITE_ID'];
                if ($this->form_name == 'page') {
                    $page = true;
                    
                    /**
                     * on récupà¨re les valeurs de gabarit et de page parente
                     */
                    $sql = "select p.PAGE_ID, PAGE_PARENT_ID, TEMPLATE_PAGE_ID, PAGE_ORDER, PAGE_GENERAL, PAGE_PATH, PAGE_DISPLAY, PAGE_DISPLAY_NAV,PAGE_DISPLAY_NAV_MOBILE, PAGE_DISPLAY_SEARCH, PAGE_TITLE, PAGE_TITLE_BO from #pref#_page p inner join
								#pref#_page_version pv on (p.PAGE_ID=pv.PAGE_ID AND p.PAGE_DRAFT_VERSION=pv.PAGE_VERSION AND p.LANGUE_ID=pv.LANGUE_ID)
								WHERE p.PAGE_ID=:ID";
                    $valLangue = $oConnection->queryRow($sql, $aBind);
                    
                    $sPageLibPath = $this->createPageLibPath($valLangue, $this->lang);
                    $aBind[":PAGE_PARENT_ID"] = $valLangue["PAGE_PARENT_ID"];
                    $aBind[":TEMPLATE_PAGE_ID"] = $valLangue["TEMPLATE_PAGE_ID"];
                    $aBind[":PAGE_ORDER"] = $valLangue["PAGE_ORDER"];
                    $aBind[":PAGE_GENERAL"] = $valLangue["PAGE_GENERAL"];
                    $aBind[":PAGE_PATH"] = $oConnection->strtobind($valLangue["PAGE_PATH"]);
                    $aBind[':PAGE_LIBPATH'] = $oConnection->strtobind($sPageLibPath);
                    $aBind[":TYPE"] = 1;
                    $aBind[":PAGE_DISPLAY"] = $valLangue["PAGE_DISPLAY"];
                    $aBind[":PAGE_DISPLAY_NAV"] = $valLangue["PAGE_DISPLAY_NAV"];
                    $aBind[":PAGE_DISPLAY_NAV_MOBILE"] = $valLangue["PAGE_DISPLAY_NAV_MOBILE"];
                    
                    $aBind[":PAGE_DISPLAY_SEARCH"] = $valLangue["PAGE_DISPLAY_SEARCH"];
                    $aBind[":TITLE"] = $oConnection->strtobind("[" . $valLangue["PAGE_TITLE"] . "]");
                    $aBind[":TITLE_BO"] = $oConnection->strtobind("[" . $valLangue["PAGE_TITLE_BO"] . "]");
                } elseif ($this->form_name == 'content') {
                    
                    /**
                     * on récupà¨re les valeurs de gabarit et de page parente
                     */
                    $sql = "select CONTENT_TITLE, CONTENT_TITLE_BO, PAGE_ID from #pref#_content c inner join
								#pref#_content_version cv on (c.CONTENT_ID=cv.CONTENT_ID AND c.CONTENT_DRAFT_VERSION=cv.CONTENT_VERSION AND c.LANGUE_ID=cv.LANGUE_ID)
								WHERE c.CONTENT_ID=:ID";
                    $valLangue = $oConnection->queryRow($sql, $aBind);
                    $aBind[":TITLE"] = $oConnection->strtobind("[" . $valLangue["CONTENT_TITLE"] . "]");
                    $aBind[":TITLE_BO"] = $oConnection->strtobind("[" . $valLangue["CONTENT_TITLE_BO"] . "]");
                    $aBind[":PAGE_ID"] = $oConnection->strtobind("[" . $valLangue["PAGE_ID"] . "]");
                }
                
                /**
                 * contrôle d'existence des données
                 */
                $exists1 = $oConnection->queryRow("select count(*) as compte, SITE_ID from #pref#_" . strtolower($this->form_name) . " where " . $this->form_name . "_ID=:ID AND LANGUE_ID=:LANGUE_ID GROUP BY SITE_ID", $aBind);
                if (! $exists1) {
                    if ($this->form_name == 'page') {
                        /**
                         * contrôle de cohérence
                         */
                        $site = $oConnection->queryItem("select SITE_ID from #pref#_" . strtolower($this->form_name) . " where " . $this->form_name . "_ID=:ID AND PAGE_CREATION_DATE is not null", $aBind);
                        if ($site != $_SESSION[APP]['SITE_ID']) {
                            echo ('<div style="background-color:red;color:white;text-align:center;font-size:25px;"><br /><br />ATTENTION, un autre navigateur est ouvert sur un autre site : conflit de variables de sessions !<br /><br />Veuillez rafraichir votre page pour rétablir les bonnes variables<br /><br /><br /><br /></div>');
                            die();
                        }
                        $this->addCmsPageParentByLanguage($aBind[":PAGE_PARENT_ID"], $aBind[":LANGUE_ID"]);
                    }
                    /**
                     * ajout systematique des donnees par default de la page dans la langue
                     */
                    $sql = "insert into #pref#_" . strtolower($this->form_name) . "
							(" . $this->form_name . "_ID,
							" . $this->form_name . "_DRAFT_VERSION,
							LANGUE_ID,
							" . ($page ? "PAGE_ORDER " : "") . "
							" . ($page ? "" : $this->form_name . "_TYPE_ID") . ($page ? ",SITE_ID, PAGE_PARENT_ID, PAGE_GENERAL, PAGE_PATH" : "") . ") VALUES (
							:ID,
							:VERSION,
							:LANGUE_ID,
							" . ($page ? ":PAGE_ORDER" : "") . "
							" . ($page ? "" : ":TYPE") . ($page ? ",:SITE,:PAGE_PARENT_ID, :PAGE_GENERAL, :PAGE_PATH" : "") . ")";
                    $oConnection->query($sql, $aBind);
                }
                $exists2 = $oConnection->queryItem("select count(*) from #pref#_" . strtolower($this->form_name) . "_version where " . $this->form_name . "_ID=:ID AND LANGUE_ID=:LANGUE_ID", $aBind);
                if (! $exists2) {
                    $sql = "insert into #pref#_" . strtolower($this->form_name) . "_version
							(" . $this->form_name . "_ID,
							" . $this->form_name . "_VERSION,
							LANGUE_ID," . ($page ? "TEMPLATE_PAGE_ID, " . $this->form_name . "_DISPLAY," . $this->form_name . "_DISPLAY_NAV," . $this->form_name . "_DISPLAY_NAV_MOBILE," . $this->form_name . "_DISPLAY_SEARCH," : "") . "STATE_ID,
							" . $this->form_name . "_TITLE_BO,
							" . $this->form_name . "_TITLE
							) VALUES (
							:ID,
							:VERSION,
							:LANGUE_ID," . ($page ? ":TEMPLATE_PAGE_ID,:PAGE_DISPLAY,:PAGE_DISPLAY_NAV,:PAGE_DISPLAY_NAV_MOBILE,:PAGE_DISPLAY_SEARCH," : "") . ":STATE_ID,
							:TITLE_BO,
							:TITLE)";
                    // $sql = "insert into #pref#_".strtolower($this->form_name)."_version (".$this->form_name."_ID,".$this->form_name."_VERSION, LANGUE_ID".($page?", TEMPLATE_PAGE_ID, STATE_ID,".$this->form_name."_TITLE_BO,".$this->form_name."_TITLE ":", STATE_ID,".$this->form_name."_TITLE_BO,".$this->form_name."_TITLE ").") VALUES (:ID,:VERSION,:LANGUE_ID".($page?",:TEMPLATE_PAGE_ID,:STATE_ID,:TITLE_BO,:TITLE":",:STATE_ID,:TITLE_BO,:TITLE").")";
                    $oConnection->query($sql, $aBind);
                }
                $this->values = $oConnection->queryForm($this->editModel, $this->aBind, $this->aBindLob);
                
                /**
                 * on bloque la réinitialisation des valeurs de workflow
                 */
                $this->noResetVersion = true;
            }
            if ($this->values["STATE_ID"]) {
                $this->getView()->getHead()->sTitle .= $this->getStateLib($this->values["STATE_ID"]);
            }
        }
        
        /*
         * if ($this->form_name == 'page' && $_GET["toprefresh"]) { $this->getView()->getHead()->setScript("top.location.href=top.location.href;"); }
         */
    }

    /**
     * Création du LibPath avec support multilingue
     *
     * @author : ayoub.hidri@businessdecision.com
     * @return String PAGE_LIBPATH
     *        
     */
    protected function createPageLibPath($originalPage, $iLangueId)
    {
        $oConnection = Pelican_Db::getInstance();
        $aParentsIds = explode('#', $originalPage['PAGE_PATH']);
        
        // fetch pages for language
        
        $sFindPagesSql = "SELECT
									 DISTINCT p.LANGUE_ID,
									  p.PAGE_ID,
									  p.PAGE_PATH,
									  p.PAGE_LIBPATH,
									  p.LANGUE_ID,
									  pv.PAGE_TITLE_BO
			 	FROM #pref#_page p INNER JOIN #pref#_page_version pv
			 	ON (p.PAGE_ID = pv.PAGE_ID AND p.LANGUE_ID=pv.LANGUE_ID AND p.page_draft_version = pv.page_version )
			 	WHERE p.PAGE_ID IN (:PAGES_IDS) AND p.LANGUE_ID=:LANGUE_ID
			 	";
        
        // print $sFindPagesSql;
        
        $aBind = array(
            ':LANGUE_ID' => $iLangueId,
            ':PAGES_IDS' => $oConnection->strtobind(implode(',', $aParentsIds))
        );
        $aPages = $oConnection->queryTab($sFindPagesSql, $aBind);
        $aPageLibPath = array();
        foreach ($aParentsIds as $iParentId) {
            foreach ($aPages as $aPage) {
                if ($aPage['PAGE_ID'] == $iParentId) {
                    $aPageLibPath[] = sprintf('%s|%s', $aPage['PAGE_ID'], $aPage['PAGE_TITLE_BO']);
                }
            }
        }
        $sPageLibPath = implode('#', $aPageLibPath);
        $sPageLibPath = sprintf('%s#%s|[%s]', $sPageLibPath, $originalPage['PAGE_ID'], $originalPage['PAGE_TITLE_BO']);
        return $sPageLibPath;
    }

    /**
     * checke et ajoute si n'existe pas une entree par default de la page parente dans la langue (recursif)
     *
     * @access protected
     * @return __TYPE__
     */
    protected function addCmsPageParentByLanguage($pid, $langue_id)
    {
        if ($pid != '') {
            $oConnection = Pelican_Db::getInstance();
            $aBindParent[":ID"] = $pid;
            $aBindParent[":LANGUE_ID"] = $langue_id;
            $existsParent = $oConnection->queryItem("select count(*) from #pref#_page where PAGE_ID=:ID AND LANGUE_ID=:LANGUE_ID", $aBindParent);
            if (! $existsParent) {
                // si pas de page parente dans une langue, on copie les datas d'une autre langue
                $sql = "select SITE_ID, PAGE_PARENT_ID, TEMPLATE_PAGE_ID, PAGE_ORDER, PAGE_PATH, PAGE_GENERAL, PAGE_DISPLAY, PAGE_DISPLAY_NAV, PAGE_DISPLAY_NAV_MOBILE, PAGE_DISPLAY_SEARCH, PAGE_TITLE, PAGE_TITLE_BO from #pref#_page p inner join
                                    #pref#_page_version pv on (p.PAGE_ID=pv.PAGE_ID AND p.PAGE_DRAFT_VERSION=pv.PAGE_VERSION AND p.LANGUE_ID=pv.LANGUE_ID)
                                    WHERE p.PAGE_ID=:ID";
                $valParentLangue = $oConnection->queryRow($sql, $aBindParent);
                
                $sPageLibPath = $this->createPageLibPath($valParentLangue, $langue_id);
                
                // PAGE
                $aBindParentAdd[":ID"] = $aBindParent[":ID"];
                $aBindParentAdd[":VERSION"] = 1;
                $aBindParentAdd[":LANGUE_ID"] = $aBindParent[":LANGUE_ID"];
                $aBindParentAdd[":SITE"] = $valParentLangue['SITE_ID'];
                $aBindParentAdd[":PAGE_PARENT_ID"] = $valParentLangue["PAGE_PARENT_ID"];
                $aBindParentAdd[":PAGE_ORDER"] = $valParentLangue["PAGE_ORDER"];
                $aBindParentAdd[":PAGE_GENERAL"] = $valParentLangue["PAGE_GENERAL"];
                $aBindParentAdd[":PAGE_PATH"] = $oConnection->strtobind($valParentLangue["PAGE_PATH"]);
                $aBindParentAdd[':PAGE_LIBPATH'] = $oConnection->strtobind($sPageLibPath);
                
                // page version
                $aBindParentAdd[":TEMPLATE_PAGE_ID"] = $valParentLangue["TEMPLATE_PAGE_ID"];
                $aBindParentAdd[":PAGE_DISPLAY"] = $valParentLangue["PAGE_DISPLAY"];
                $aBindParentAdd[":PAGE_DISPLAY_NAV"] = $valParentLangue["PAGE_DISPLAY_NAV"];
                $aBindParentAdd[":PAGE_DISPLAY_NAV_MOBILE"] = $valParentLangue["PAGE_DISPLAY_NAV_MOBILE"];
                $aBindParentAdd[":PAGE_DISPLAY_SEARCH"] = $valParentLangue["PAGE_DISPLAY_SEARCH"];
                $aBindParentAdd[":STATE_ID"] = 1;
                $aBindParentAdd[":TITLE"] = $oConnection->strtobind("[" . $valParentLangue["PAGE_TITLE"] . "]");
                $aBindParentAdd[":TITLE_BO"] = $oConnection->strtobind("[" . $valParentLangue["PAGE_TITLE_BO"] . "]");
                
                $sql = "insert into #pref#_page (PAGE_ID, PAGE_DRAFT_VERSION, LANGUE_ID, PAGE_ORDER,SITE_ID, PAGE_PARENT_ID, PAGE_GENERAL, PAGE_PATH,PAGE_LIBPATH) VALUES (
						:ID, :VERSION, :LANGUE_ID, :PAGE_ORDER,:SITE,:PAGE_PARENT_ID, :PAGE_GENERAL, :PAGE_PATH,:PAGE_LIBPATH)";
                $oConnection->query($sql, $aBindParentAdd);
                // PAGE_LIB_PATH est fait lors d'un enregistrement reel par la suite
                
                $sql = "insert into #pref#_page_version (PAGE_ID, PAGE_DRAFT_VERSION, LANGUE_ID, PAGE_ORDER,SITE_ID, PAGE_PARENT_ID, PAGE_GENERAL) VALUES (
                                :ID, :VERSION, :LANGUE_ID, :PAGE_ORDER,:SITE,:PAGE_PARENT_ID, :PAGE_GENERAL)";
                $sql = "insert into #pref#_page_version
                            (PAGE_ID, PAGE_VERSION, LANGUE_ID, TEMPLATE_PAGE_ID, PAGE_DISPLAY, PAGE_DISPLAY_NAV, PAGE_DISPLAY_NAV_MOBILE, PAGE_DISPLAY_SEARCH, STATE_ID, PAGE_TITLE_BO, PAGE_TITLE
                            ) VALUES (
                            :ID, :VERSION, :LANGUE_ID, :TEMPLATE_PAGE_ID,:PAGE_DISPLAY,:PAGE_DISPLAY_NAV,:PAGE_DISPLAY_NAV_MOBILE,:PAGE_DISPLAY_SEARCH, :STATE_ID, :TITLE_BO,:TITLE
                            )";
                $oConnection->query($sql, $aBindParentAdd);
                // PAGE_CLEAR_URL fait lors d'un enregistrement reel par la suite
                
                // on continue recursivement
                $this->addCmsPageParentByLanguage($valParentLangue["PAGE_PARENT_ID"], $langue_id);
            }
        }
    }

    /**
     * Affiche un message interdisant la suppresion du contenu affiché s'il est
     * associé à  d'autres contenus
     *
     * @access protected
     * @return string
     */
    protected function getUsage()
    {
        global $child;
        $return = "";
        
        /**
         * * Contrôle d'utilisation du CONTENT_ID pour empêcher la supression
         */
        if ((valueExists($this->values, "CONTENT_ID") || valueExists($this->values, "PAGE_ID") || valueExists($this->values, "TAG_ID")) && $this->readO) {
            if ($this->values["CONTENT_ID"]) {
                $this->bNoDelete = $this->checkUsage($this->values["CONTENT_ID"], "CONTENT");
                $type = t('CONTENU_UTILISER');
            } elseif ($child) {
                $this->bNoDelete = true;
                $type = t('PAGE_CONTENT_HIER');
            } elseif ($this->values["PAGE_ID"]) {
                $this->bNoDelete = $this->checkUsage($this->values["PAGE_ID"], "PAGE");
                $type = t('PAGE_MENU_NAV');
            } elseif ($this->values["TAG_ID"]) {
                $this->bNoDelete = $this->checkUsage($this->values["TAG_ID"], "TAG");
                $type = t('TAG_PAGE');
            }
            if ($this->bNoDelete) {
                $return = Pelican_Html::div(array(
                    "class" => t('ERROR')
                ), $type . Pelican_Html::br() . Pelican_Html::b(t('SUPP_IMPOS'))) . Pelican_Html::br();
            }
        }
        return $return;
    }
    // # meta données
    
    /**
     * récupération des langues d'un site
     *
     * @access protected
     * @return array()
     */
    static function getLanguageSite($lang = false)
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
        $sql = "SELECT sl.LANGUE_ID , l.LANGUE_LABEL, l.LANGUE_CODE
				FROM #pref#_language l, #pref#_site_language sl
				WHERE sl.langue_id = l.langue_id
				AND sl.site_id = :SITE_ID";
        
        if ($lang) {
            $aBind[":LANGUE_ID"] = $lang;
            $sql .= " and l.langue_id = :LANGUE_ID";
        }
        $aLangue = $oConnection->queryTab($sql, $aBind);
        
        return $aLangue;
    }

    /**
     * Création des éléments de gestion du multilinguisme
     *
     * @access protected
     * @return __TYPE__
     */
    protected function getLanguageView()
    {
        if (! $this->bPopup) {
            $oConnection = Pelican_Db::getInstance();
            $aBind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
            $sql = "SELECT sl.LANGUE_ID , l.LANGUE_LABEL, l.LANGUE_CODE
					FROM #pref#_language l, #pref#_site_language sl
					WHERE sl.langue_id = l.langue_id
					AND sl.site_id = :SITE_ID";
            
            $aOngletLangue = $oConnection->queryTab($sql, $aBind);
            
            if ($aOngletLangue) {
                if (count($aOngletLangue) > 1) {
                    
                    /**
                     * Recherche juste la langue par default
                     */
                    $sql = "SELECT sl.LANGUE_ID , l.LANGUE_LABEL, l.LANGUE_CODE
					FROM #pref#_language l, #pref#_site_language sl
					WHERE sl.langue_id = l.langue_id
					AND sl.site_id = :SITE_ID
					AND sl.langue_id = " . (! empty($_SESSION[APP]["SITE_ITEM"]["LANGUE_ID_DEFAULT"]) ? $_SESSION[APP]["SITE_ITEM"]["LANGUE_ID_DEFAULT"] : $_SESSION[APP]['LANGUE_ID']);
                    
                    $aOngletLangueDefault = $oConnection->queryTab($sql, $aBind);
                }
                
                if (! $aOngletLangueDefault) {
                    
                    if ($aOngletLangue[0]['LANGUE_ID']) {
                        $_SESSION[APP]['LANGUE_ID'] = $aOngletLangue[0]['LANGUE_ID'];
                        $aOngletLangueDefault[0]['LANGUE_ID'] = $aOngletLangue[0]['LANGUE_ID'];
                    } else {
                        $_SESSION[APP]['LANGUE_ID'] = 1;
                        $aOngletLangueDefault[0]['LANGUE_ID'] = 1;
                    }
                }
                $_SESSION[APP]["LANGUE_ID_EDITORIAL_DEFAUT"] = $_SESSION[APP]['LANGUE_ID'];
                
                if (isset($_REQUEST['id']) && $_REQUEST['id'] != '') {
                    $_SESSION[APP]['PAGE_ID'] = $_REQUEST['id'];
                }
                if (count($aOngletLangue) > 0) {
                    
                    $strOngL .= Pelican_Html::script(array(
                        type => "text/javascript"
                    ), "
				function saveFormBeforeChangeLanguage(onglet) {
					var idEnCours = new Number(" . $_GET["id"] . ");
					var strUrlLang = document.location.href.replace('&langue=" . ($this->lang ? $this->lang : $aOngletLangueDefault[0]['LANGUE_ID']) . "','') + '&langue=' + onglet;
                        strUrlLang = strUrlLang.replace('&toprefresh=1','') + '&toprefresh=1';

					if (idEnCours == " . Pelican::$config["DATABASE_INSERT_ID"] . ") {
						var bSaveForm = true;
					/*
					} else {
						var bSaveForm = confirm('Would you like to save before changing language? \\n\\nClick on [OK] to save\\nClick on [Annuler] to continue without saving');
						*/
					}
					if (bSaveForm) {
						document.forms['fForm'].form_retour.value = strUrlLang;
						/*top.clickButton('SaveOnglet');*/
						document.location.href = strUrlLang;
					} else {
						document.location.href = strUrlLang;
					}
				}
				");
                    $strOngL .= Pelican_Html::script(array(
                        type => "text/javascript"
                    ), "
						/**
						* Gestion des onglets de langue
						*
						* @return void
						* @param string onglet Identifiant de l'onglet
						*/
						function activeOngletLangue(onglet) {
						" . ($_GET["id"] ? "saveFormBeforeChangeLanguage(onglet);" : "document.location.href = document.location.href.replace('&langue=" . ($this->lang ? $this->lang : $aOngletLangueDefault[0]['LANGUE_ID']) . "','') + '&langue=' + onglet;") . "
						}");
                    $image = Pelican_Html::img(array(
                        border => "0",
                        src => Pelican::$config["LIB_PATH"] . "/Pelican/Translate/public/images/flags/fr.png"
                    ));
                    $oTab = Pelican_Factory::getInstance('Form.Tab', "tabLanguage", $this->skinPath);
                    // $oTab->addTab($image . " Français", "ongletLangue1", ($_SESSION[APP]['LANGUE_ID'] == 1 or $_SESSION[APP]['LANGUE_ID'] == ""), "", "activeOngletLangue('1');", "", "petit", "", false);
                    foreach ($aOngletLangue as $oL) {
                        $image = Pelican_Html::img(array(
                            border => "0",
                            src => Pelican::$config["LIB_PATH"] . "/Pelican/Translate/public/images/flags/" . strtolower($oL["LANGUE_CODE"]) . ".png"
                        ));
                        $oTab->addTab($image . " " . $oL["LANGUE_LABEL"], "ongletLangue" . $oL['LANGUE_ID'], ($_SESSION[APP]['LANGUE_ID'] == $oL['LANGUE_ID']), "", "activeOngletLangue('" . $oL['LANGUE_ID'] . "');", "", "petit", "", false);
                    }
                    $strOngL .= Pelican_Html::div(array(
                        "class" => "petit_onglet_bas"
                    ), $oTab->getTabs());
                    $this->assign('languageTab', $strOngL, false);
                }
            }
        }
    }

    /**
     * Sélection des droits de mutualisation (émission/réception) du site sur le
     * type de contenu en cours
     *
     * @access protected
     * @return __TYPE__
     */
    protected function getSharingRights()
    {
        $oConnection = Pelican_Db::getInstance();
        $return = '';
        if ($this->iContentTypeIdSearch) {
            $aBind[":CONTENT_TYPE_ID"] = $this->iContentTypeIdSearch;
            $aBind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
            $aBind[":CONTENT_TYPE_SITE_RECEPTION"] = 1;
            $return = $oConnection->queryItem("select count(1) from #pref#_content_type_site where CONTENT_TYPE_ID=:CONTENT_TYPE_ID AND SITE_ID=:SITE_ID AND CONTENT_TYPE_SITE_RECEPTION=:CONTENT_TYPE_SITE_RECEPTION", $aBind);
            if ($return) {
                $return = $this->iContentTypeIdSearch;
            }
        }
        return $return;
    }

    /**
     * __DESC__
     *
     * @access protected
     * @return __TYPE__
     */
    protected function redirectRequest()
    {
        if (Pelican_Db::$values["CONTENT_ID"] && Pelican_Db::$values["form_retour"] && Pelican_Db::$values["MY_POPUP"] == 1) {
            Pelican_Db::$values["form_retour"] .= "?cid=" . Pelican_Db::$values["CONTENT_ID"];
        } elseif (Pelican_Db::$values["PAGE_ID"] && Pelican_Db::$values["form_retour"] && Pelican_Db::$values["MY_POPUP"] == 1) {
            Pelican_Db::$values["form_retour"] .= "?pid=" . Pelican_Db::$values["PAGE_ID"];
        }
        if (! empty(Pelican_Db::$values["PAGE_TITLE_BO"]) && ! empty(Pelican_Db::$values["OLD_PAGE_TITLE_BO"])) {
            if (Pelican_Db::$values["PAGE_TITLE_BO"] == Pelican_Db::$values["OLD_PAGE_TITLE_BO"]) {
                Pelican_Db::$values["form_retour"] = str_replace('&toprefresh=1', '', Pelican_Db::$values["form_retour"]);
            }
        }
        parent::redirectRequest();
    }

    /**
     * /**
     * __DESC__
     *
     * @access protected
     * @return __TYPE__
     */
    protected function execDecache()
    {
        if (Pelican_Db::$values["PAGE_ID"] && ! Pelican_Db::$values["PAGE_PARENT_ID"] && Pelican_Db::$values["form_name"] == "page") {
            // print(__FUNCTION__);
            $oConnection = Pelican_Db::getInstance();
            $url = $oConnection->queryItem("SELECT SITE_URL FROM #pref#_site WHERE SITE_ID=:SITE_ID", array(
                ":SITE_ID" => Pelican_Db::$values['SITE_ID']
            ));
            Pelican_Cache::clean("Frontend/Site/Init", $url);
            $site = Pelican_Cache::fetch("Frontend/Site/Init", array(
                $url,
                $_SESSION[APP]['LANGUE_ID']
            ));
        }
        if (Pelican_Db::$values["PUBLICATION"] || Pelican_Db::$values["DRAG_N_DROP"]) {
            // print_r($this->listDecache);
            // print_r($this->decachePublication);3
            
            if (is_array($this->decachePublication)) {
                
                $this->listDecache = array_merge($this->listDecache, $this->decachePublication);
                $this->decacheContentPage(Pelican_Db::$values["CONTENT_ID"], Pelican_Db::$values["DRAG_N_DROP"]);
            }
            $sitemapFile = Pelican::$config["CACHE_FW_ROOT"] . "/sitemap/" . $_SESSION[APP]['SITE_ID'] . "/sitemap.*.xml";
            system('rm -f ' . $sitemapFile);
            /*
             * if (is_array($this->decacheContent)) { $this->listDecache = array_merge($this->listDecache, $this->decacheContent); $this->decacheContentPage(Pelican_Db::$values["CONTENT_ID"]); } if ($this->form_name == "page" && is_array($this->decache["page"]["PUBLICATION"])) { $this->listDecache = array_merge($this->listDecache, $this->decache["page"]["PUBLICATION"]); } if ($this->form_name == "content" && $this->decache["content"]["PUBLICATION"]) { $this->listDecache = array_merge($this->listDecache, $this->decache["content"]["PUBLICATION"]); $this->decacheContentPage(Pelican_Db::$values["CONTENT_ID"]); }
             */
        }
        parent::execDecache();
    }

    /**
     * Fonction de decache d'un associé à  des pages
     *
     * @access protected
     * @param string $id
     *            Identifiant du contenu
     * @return void
     */
    protected function decacheContentPage($id, $dnd = false)
    {
        $oConnection = Pelican_Db::getInstance();
        $usage = array();
        
        if ($this->getRequest()->isAjax() && $dnd) {
            
            if ($this->decacheBack) {
                $this->listDecache = array_merge($this->listDecache, $this->decacheBack);
            }
            if ($this->listDecache) {
                $this->arrayDecache($this->listDecache);
            }
        }
        if (Pelican::$config["page"]["CONTENT_USAGE"]) {
            foreach (Pelican::$config["page"]["CONTENT_USAGE"] as $table => $field) {
                $page = $oConnection->queryItem("SELECT DISTINCT PAGE_ID FROM " . strtolower($table) . " WHERE " . $field . "=:ID", array(
                    ":ID" => $id
                ));
                if ($page) {
                    $usage[$page] ++;
                }
            }
            if ($usage) {
                foreach ($usage as $page => $count) {
                    $this->arrayDecache($this->decache["page"]["CONTENT"], $page);
                }
            }
        }
    }

    /**
     * *** workflow *
     *
     * @access protected
     * @return void
     */
    protected function initWorkFlow()
    {
        /**
         * * Workflow
         */        
        if ($this->workflowField) {
            if (! Pelican_Db::$values[$this->workflowField . "_ID"] && isset($_REQUEST[$this->workflowField . "_ID"])) {
                Pelican_Db::$values[$this->workflowField . "_ID"] = $_REQUEST[$this->workflowField . "_ID"];
            }
            if (isset(Pelican_Db::$values[$this->workflowField . "_TITLE"])) {
                $title = str_replace("  ", " ", str_replace("<br />", " ", nl2br(Pelican_Db::$values[$this->workflowField . "_TITLE"])));
                if (! Pelican_Db::$values[$this->workflowField . "_TITLE_BO"]) {
                    Pelican_Db::$values[$this->workflowField . "_TITLE_BO"] = $title;
                }
            }
            $this->setWorflowValues();
            $this->workflowAction();
        }
    }

    /**
     * *** workflow *
     *
     * @access protected
     * @return void
     */
    protected function setWorflowValues()
    {
        
        /**
         * * Suivi des modifications de contenu
         */
        if (! Pelican_Db::$values[$this->workflowField . "_CREATION_DATE"]) {
            // Date de création du contenu
            Pelican_Db::$values[$this->workflowField . "_CREATION_DATE"] = ":DATE_COURANTE";
        }
        if (! Pelican_Db::$values[$this->workflowField . "_CREATION_USER"]) {
            Pelican_Db::$values[$this->workflowField . "_CREATION_USER"] = Pelican_Db::$values["form_user"];
        }
        if (! Pelican_Db::$values[$this->workflowField . "_VERSION_CREATION_USER"]) {
            Pelican_Db::$values[$this->workflowField . "_VERSION_CREATION_DATE"] = ":DATE_COURANTE";
            Pelican_Db::$values[$this->workflowField . "_VERSION_CREATION_USER"] = Pelican_Db::$values["form_user"];
        }
        Pelican_Db::$values[$this->workflowField . "_VERSION_UPDATE_DATE"] = ":DATE_COURANTE";
        Pelican_Db::$values[$this->workflowField . "_VERSION_UPDATE_USER"] = Pelican_Db::$values["form_user"];
        
        /**
         * * Mise à  jour de la date de publication uniquement si le champ status est coché
         */
        if (! Pelican_Db::$values[$this->workflowField . "_STATUS"]) {
            Pelican_Db::$values[$this->workflowField . "_STATUS"] = $_REQUEST[$this->workflowField . "_STATUS"];
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $oForm
     *            __DESC__
     * @param bool $hideUsers
     *            (option) __DESC__
     * @return __TYPE__
     */
    public function getWorkflowFields(&$oForm, $hideUsers = false)
    {
        $form = '';
        if ($this->workflowField) {
            
            /**
             * gestion de contenu
             */
            $this->resetWorkflowValues();
            $form .= Backoffice_Form_Helper::workflowForm($oForm, $this->values, $this->bVersioning, $this->field_id, $this->workflowField, $this->noResetVersion, $this->id, $this->aLastValues, $this->lang, $this->readO, $hideUsers);
            
            /**
             * Recherche
             */
            $form .= Backoffice_Form_Helper::searchForm($oForm, $this->workflowField, $this->values);
        }
        return $form;
    }

    /**
     * Création des Boutons associés au workflow en fonction des droits de
     * l'utilisateur
     *
     * @access protected
     * @param Pelican_Form $oForm
     *            __DESC__
     * @return __TYPE__
     */
    protected function getWorkflowButtons(&$oForm)
    {
        $oConnection = Pelican_Db::getInstance();
        $this->aBind[":PAGE_ID"] = $this->id;
        $pageVersion = $oConnection->queryRow("select count(*)as COUNT from #pref#_page_version where PAGE_ID=:PAGE_ID", $this->aBind);
        $pageScheduleActive = $oConnection->queryRow("select SCHEDULE_STATUS from #pref#_page where PAGE_ID=:PAGE_ID", $this->aBind);
        
        if ($this->workflowField && $this->aLastValues["PAGE_GENERAL"] != Pelican::$config["PAGE_GENERALE_YES"] && ! $this->aLastValues["CONTENT_ID"]) {
            $this->aButton["preview"] = true;
            $this->aButton["schedule"] = true;
        }
        $state = '';
        if(!$_SESSION[APP][$this->id][$_SESSION[APP][LANGUE_ID]]['SCHEDULE']){
            $state = $_SESSION[APP]["content_type"][$this->iContentTypeId]["state"][$this->values["STATE_ID"]];
            $this->aButton["close_schedule"] = false; 
        }
        if($_SESSION[APP][$this->id][$_SESSION[APP][LANGUE_ID]]['SCHEDULE']){
            $this->aButton["preview"] = false;
            $this->aButton["schedule"] = false;
            $this->aButton["close_schedule"] = true;         
        }        
        if($pageVersion['COUNT'] == 0 || $pageScheduleActive['SCHEDULE_STATUS']){
            $this->aButton["schedule"] = false;
        }
        /**
         * on réaffiche l'état courant
         */
        $this->aButton["state_" . $this->values["STATE_ID"]] = $oForm->sFormName;
        /**
         * on affiche les boutons des autres états auxquels on a droit
         */

        if ($state && $this->bVersioning) {
            foreach ($state as $stateButton) {
                if (! $this->bPopup || ($this->bPopup && $stateButton["STATE_ID"] == Pelican::$config["PUBLICATION_STATE"])) {
                    $this->aButton["state_" . $stateButton["STATE_ID"]] = $oForm->sFormName;
                } else {
                    $this->aButton["state_" . $stateButton["STATE_ID"]] = "";
                }
                if ($stateButton["STATE_ID"] == Pelican::$config["CORBEILLE_STATE"] && ($this->aLastValues["PAGE_GENERAL"] == Pelican::$config["PAGE_GENERALE_YES"] || ($this->aLastValues["PAGE_PARENT_ID"] == null && ! $this->aLastValues["CONTENT_ID"]))) {
                    $this->aButton["state_" . $stateButton["STATE_ID"]] = "";
                }
            }
        }
    }

    /**
     * Retourne le libellé d'un état de workflow
     *
     * @access protected
     * @param int $id
     *            état de wrokflow
     * @return string
     */
    protected function getStateLib($id)
    {
        $return = "";
        if ($id) {
            $aStates = Pelican_Cache::fetch("Backend/State", array(
                $id
            ));           
            $return = " (" . $aStates[0]["lib"] . ")";            
            if($_SESSION[APP][$this->id][$_SESSION[APP][LANGUE_ID]]['SCHEDULE']){
                $return = " (" . t('SCHEDULE') . ")";
            }
        }
        return $return;
    }

    /**
     * __DESC__
     *
     * @access protected
     * @return __TYPE__
     */
    protected function updateRewriting()
    {
        if ($this->bRewrite) {
            $oConnection = Pelican_Db::getInstance();
            if ($this->workflowField) {
                $aBind[":ID"] = Pelican_Db::$values[$this->workflowField . "_ID"];
                $aBind[":TYPE"] = $oConnection->strToBind($this->workflowField);
                $aBind[":LANGUE_ID"] = $_SESSION[APP]['LANGUE_ID'];
                $aBind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
                $oConnection->query('select REWRITE_URL from #pref#_rewrite where REWRITE_TYPE=:TYPE AND REWRITE_ID=:ID AND LANGUE_ID=:LANGUE_ID AND SITE_ID=:SITE_ID', $aBind);
                $decache = $oConnection->data['REWRITE_URL'];
                $sql = "delete from #pref#_rewrite where REWRITE_TYPE=:TYPE AND REWRITE_ID=:ID AND LANGUE_ID=:LANGUE_ID AND SITE_ID=:SITE_ID";
                $oConnection->query($sql, $aBind);
            }
            if (Pelican_Db::$values["REWRITE_ALIAS_URL"]) {
                Pelican_Db::$values["REWRITE_URL"]['200'] = Pelican_Form::splitTextarea(Pelican_Db::$values["REWRITE_ALIAS_URL"]);
                Pelican_Db::$values["REWRITE_URL"]['200'] = array_map("cleanRewriting", Pelican_Db::$values["REWRITE_URL"]['200']);
            }
            Pelican_Db::$values["REWRITE_URL"]['CLEAR'][] = Pelican_Db::$values[$this->workflowField . "_CLEAR_URL"];
            if (! empty(Pelican_Db::$values[$this->workflowField . "_OLD_CLEAR_URL"])) {
                Pelican_Db::$values["REWRITE_REDIRECT_URL"] = empty(Pelican_Db::$values["REWRITE_REDIRECT_URL"]) ? Pelican_Db::$values[$this->workflowField . "_OLD_CLEAR_URL"] : Pelican_Db::$values["REWRITE_REDIRECT_URL"] . "\r\n" . Pelican_Db::$values[$this->workflowField . "_OLD_CLEAR_URL"];
            }
            if (Pelican_Db::$values["REWRITE_REDIRECT_URL"]) {
                Pelican_Db::$values["REWRITE_URL"]['301'] = Pelican_Form::splitTextarea(Pelican_Db::$values["REWRITE_REDIRECT_URL"]);
                Pelican_Db::$values["REWRITE_URL"]['301'] = array_map("cleanRewriting", Pelican_Db::$values["REWRITE_URL"]['301']);
            }
            if (Pelican_Db::$values["REWRITE_URL"] && ! Pelican_Db::$values["PAGE_GENERAL"]) {
                $init = Pelican_Db::$values;
                unset($_SESSION[APP]["REWRITE_ERROR"]);
                
                if ($init["REWRITE_URL"]) {
                    foreach ($init["REWRITE_URL"] as $code => $rewrite) {
                        $rewrite = array_unique($rewrite);
                        $i = 0;
                        foreach ($rewrite as $rewriting) {
                            if ($rewriting) {
                                Pelican_Db::$values["REWRITE_URL"] = str_replace("//", "/", "/" . $rewriting);
                                Pelican_Db::$values["REWRITE_ORDER"] = ++ $i;
                                Pelican_Db::$values["REWRITE_TYPE"] = strtoupper($this->workflowField);
                                Pelican_Db::$values["REWRITE_ID"] = Pelican_Db::$values[$this->workflowField . "_ID"];
                                Pelican_Db::$values["REWRITE_RESPONSE"] = $code;
                                $aBind[":REWRITE_URL"] = $oConnection->strToBind(Pelican_Db::$values["REWRITE_URL"]);
                                $aBind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
                                
                                $count1 = $oConnection->queryRow("select * from #pref#_rewrite WHERE REWRITE_URL=:REWRITE_URL and site_id = :SITE_ID", $aBind);
                                $count2 = $oConnection->queryRow("select PAGE_TYPE_CODE,PAGE_TYPE_SHORTCUT from #pref#_page_type WHERE " . $oConnection->getConcatClause(array(
                                    "PAGE_TYPE_SHORTCUT",
                                    "'/'"
                                )) . "=:REWRITE_URL", $aBind);
                                
                                if ($this->workflowField == 'CONTENT') {
                                    $count3 = $oConnection->queryRow("select PAGE_ID from #pref#_page_version WHERE page_clear_url=:REWRITE_URL", $aBind);
                                    $count4 = $oConnection->queryRow("select CONTENT_ID from #pref#_content_version WHERE content_clear_url =:REWRITE_URL and content_id != :ID", $aBind);
                                } elseif ($this->workflowField == 'PAGE') {
                                    $count3 = $oConnection->queryRow("select distinct pv.PAGE_ID, PAGE_TITLE_BO from #pref#_page_version pv INNER JOIN #pref#_page p ON (p.page_id = pv.page_id and p.langue_id = pv.langue_id) WHERE page_clear_url=:REWRITE_URL and pv.page_id != :ID and site_id = :SITE_ID and p.page_status = 1 and pv.state_id != 5", $aBind);
                                    $count4 = $oConnection->queryRow("select distinct cv.CONTENT_ID, CONTENT_TITLE_BO from #pref#_content_version cv INNER JOIN #pref#_content c ON (c.content_id = cv.content_id and c.langue_id = cv.langue_id) WHERE content_clear_url =:REWRITE_URL and site_id = :SITE_ID", $aBind);
                                }
                                $count = ($count1 ? 1 : 0) + ($count2 ? 1 : 0) + ($count3 ? 1 : 0) + ($count4 ? 1 : 0);
                                if (! $count && $_POST["form_action"] != Pelican_Db::DATABASE_DELETE) {
                                    if ($code != 'CLEAR') {
                                        $oConnection->insertQuery("#pref#_rewrite");
                                    }
                                } else {
                                    $temp = '';
                                    if ($count1["PAGE_ID"]) {
                                        if ($code == "CLEAR") {
                                            $_SESSION[APP]["REWRITE_ERROR_CLEAR_URL"] = $count1["PAGE_ID"];
                                        } else {
                                            // $temp = Pelican_Db::$values["REWRITE_URL"] . " pour la rubrique \"" . $count1["PAGE_ID"] . "\"";
                                            $temp = t("URL_USED_ON_PAGE", 'js', array(
                                                Pelican_Db::$values["REWRITE_URL"],
                                                $count1["PAGE_ID"]
                                            ));
                                        }
                                    }
                                    if ($count1["CONTENT_ID"]) {
                                        if ($code == "CLEAR") {
                                            $_SESSION[APP]["REWRITE_ERROR_CLEAR_URL"] = $count1["CONTENT_ID"];
                                        } else {
                                            // $temp = Pelican_Db::$values["REWRITE_URL"] . " pour le contenu \"" . $count1["CONTENT_ID"] . "\"";
                                            $temp = t("URL_USED_ON_CONTENT", 'js', array(
                                                Pelican_Db::$values["REWRITE_URL"],
                                                $count1["CONTENT_ID"]
                                            ));
                                        }
                                    }
                                    if ($count2["PAGE_TYPE_CODE"]) {
                                        if ($code == "CLEAR") {
                                            $_SESSION[APP]["REWRITE_ERROR_CLEAR_URL"] = $count2["PAGE_TYPE_CODE"];
                                        } else {
                                            // $temp = Pelican_Db::$values["REWRITE_URL"] . " pour le type de gabarit \"" . $count2["PAGE_TYPE_CODE"] . "\"";
                                            $temp = t("URL_USED_ON_GABARIT", 'js', array(
                                                Pelican_Db::$values["REWRITE_URL"],
                                                $count2["PAGE_TYPE_CODE"]
                                            ));
                                        }
                                    }
                                    if ($count3["PAGE_ID"]) {
                                        if ($code == "CLEAR") {
                                            $_SESSION[APP]["REWRITE_ERROR_CLEAR_URL"] = $count3["PAGE_ID"];
                                        } else {
                                            // $temp = Pelican_Db::$values["REWRITE_URL"] . " pour la rubrique \"" . $count3["PAGE/_TITLE_BO"] . "\"";
                                            $temp = t("URL_USED_ON_PAGE", 'js', array(
                                                Pelican_Db::$values["REWRITE_URL"],
                                                $count3["PAGE_TITLE_BO"]
                                            ));
                                        }
                                    }
                                    if ($count4["CONTENT_ID"]) {
                                        if ($code == "CLEAR") {
                                            $_SESSION[APP]["REWRITE_ERROR_CLEAR_URL"] = $count4["CONTENT_ID"];
                                        } else {
                                            // $temp = Pelican_Db::$values["REWRITE_URL"] . " pour le contenu \"" . $count4["CONTENT_TITLE_BO"] . "\"";
                                            $temp = t("URL_USED_ON_CONTENT", 'js', array(
                                                Pelican_Db::$values["REWRITE_URL"],
                                                $count4["CONTENT_TITLE_BO"]
                                            ));
                                        }
                                    }
                                    if ($temp != '') {
                                        $_SESSION[APP]["REWRITE_ERROR"][] = $temp;
                                    }
                                }
                                
                                if ($_SESSION[APP]["REWRITE_ERROR_CLEAR_URL"] && $code == "CLEAR") {
                                    $sqlUpdate = 'UPDATE #pref#_' . strtolower($this->workflowField) . '_version
                                                SET ' . $this->workflowField . '_CLEAR_URL = :CLEAR_URL
                                                WHERE ' . $this->workflowField . '_ID = :ID
                                                    AND LANGUE_ID = :LANGUE_ID
                                                    AND ' . $this->workflowField . '_VERSION = :VERSION';
                                    
                                    $aBind[':VERSION'] = Pelican_Db::$values[$this->workflowField . "_VERSION"];
                                    $aBind[':CLEAR_URL'] = $oConnection->strToBind(Pelican_Db::$values["REWRITE_URL"] . '.' . time());
                                    
                                    $oConnection->query($sqlUpdate, $aBind);
                                }
                                
                                $decache[] = Pelican_Db::$values["REWRITE_URL"];
                                $decache[] = '/' . trim(Pelican_Db::$values["REWRITE_URL"], '/');
                            }
                        }
                    }
                }
                if (! empty($decache)) {
                    $decache = array_unique($decache);
                    foreach ($decache as $item) {
                        Pelican_Cache::clean("Request/Redirect", $item);
                    }
                }
                Pelican_Db::$values = $init;
            }
        }
    }

    protected function updateHreflang()
    {
        if ($this->workflowField && $this->getParam('PAGE_ID')) {
            $oConnection = Pelican_Db::getInstance();
            $aBind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
            $aBind[":LANGUE_ID"] = $_SESSION[APP]['LANGUE_ID'];
            $aBind[":ID"] = $this->getParam('PAGE_ID');
            $aBind[":HREFLANG_TEXT"] =$oConnection->strToBind($this->getParam('HREFLANG_TEXT'));
            $sql = "replace into #pref#_hreflang "
                    . "(SITE_ID, LANGUE_ID, HREFLANG_ID, HREFLANG_TEXT) "
                    . "values "
                    . "(:SITE_ID, :LANGUE_ID, :ID, :HREFLANG_TEXT)";
            $oConnection->query($sql, $aBind);

            Pelican_Cache::clean("Request/Hreflang", array(
                $aBind[":SITE_ID"],
                $aBind[":LANGUE_ID"],
                $aBind[":ID"]
            ));
        }
    }
    
    /**
     * __DESC__
     *
     * @access protected
     * @return __TYPE__
     */
    protected function getClearUrl()
    {
        if ($_POST["form_action"] != Pelican::$config["DATABASE_DELETE"]) {
            $oConnection = Pelican_Db::getInstance();
            $field_url = strtoupper($this->form_name) . "_CLEAR_URL";
            $field_id = strtoupper($this->form_name) . "_ID";
            $field_version = strtoupper($this->form_name) . "_VERSION";
            $field_title = strtoupper($this->form_name) . "_TITLE_BO";
            $old = Pelican_Db::$values[$field_url];
            if (isset($this->clearurlId) && Pelican_Db::$values[$field_url] == '') {
                if (Pelican_Db::$values[$field_id] != Pelican::$config["DATABASE_INSERT_ID"]) {
                    if (Pelican_Db::$values["PAGE_ID"] && ! Pelican_Db::$values["PAGE_LIBPATH"]) {
                        Pelican_Db::$values['LANGUE_ID'] = (Pelican_Db::$values['LANGUE_ID']) ? Pelican_Db::$values['LANGUE_ID'] : '1';
                        Pelican_Db::$values["PAGE_LIBPATH"] = $oConnection->queryItem("select PAGE_LIBPATH from
							#pref#_page p WHERE p.PAGE_ID=" . Pelican_Db::$values["PAGE_ID"] . " AND LANGUE_ID=" . Pelican_Db::$values['LANGUE_ID']);
                    }
                    Pelican_Db::$values[$field_url] = self::makeClearUrl(Pelican_Db::$values[$field_id], $this->clearurlId, Pelican_Text::unhtmlentities(Pelican_Db::$values[$field_title]), Pelican_Db::$values["PAGE_LIBPATH"], "", $external, $xiti);
                    Pelican_Db::$values[$field_url] = str_replace("..html", ".html", Pelican_Db::$values[$field_url]);
                    $iCompteur = 0;
                    do {
                        $aBind[':PAGE_ID'] = Pelican_Db::$values["PAGE_ID"];
                        if ($iCompteur == 0) {
                            $aBind[':URL'] = $oConnection->strToBind(Pelican_Db::$values[$field_url]);
                            $sUrl = Pelican_Db::$values[$field_url];
                        } else {
                            $aBind[':URL'] = $oConnection->strToBind(str_replace(".html", "_" . $iCompteur . ".html", Pelican_Db::$values[$field_url]));
                            $sUrl = str_replace(".html", "_" . $iCompteur . ".html", Pelican_Db::$values[$field_url]);
                        }
                        $sSQL = "SELECT count(*) FROM #pref#_" . strtolower($this->form_name) . "_version WHERE " . $field_url . " = :URL and PAGE_ID <> PAGE_ID";
                        $countExistUrl = $oConnection->queryItem($sSQL, $aBind);
                        $iCompteur ++;
                        $bReturn = 1;
                        if ($countExistUrl > 0) {
                            $bReturn = 0;
                        }
                    } while ($bReturn != 1);
                    Pelican_Db::$values[$field_url] = $sUrl;
                    /**
                     * dans le cas d'une création, il faut mettre à  jour l'url claire => on ne connaissait pas son id Avant
                     */
                    if ($this->createClearUrl) {
                        $aBind = $oConnection->arrayToBind(Pelican_Db::$values);
                        $aBind[":" . $field_url] = $oConnection->strToBind($aBind[":" . $field_url]);
                        $aBind[":LANGUE_ID"] = Pelican_Db::$values['LANGUE_ID'];
                        $sSQL = "update #pref#_" . strtolower($this->form_name) . "_version
							set " . $field_url . "=:" . $field_url . "
							where " . $field_id . "=:" . $field_id . "
							AND LANGUE_ID=:LANGUE_ID
							AND " . $field_version . "=:" . $field_version;
                        $oConnection->query($sSQL, $aBind);
                    }
                } else {
                    $this->createClearUrl = true;
                }
            }
            $new = Pelican_Db::$values[$field_url];
            /*
             * if (!empty($old) && $old != $new) { //cas du 301 Pelican_Db::$values["REWRITE_REDIRECT_URL"] .= "\n".$old; }
             */
        }
    }

    /**
     * __DESC__
     *
     * @access protected
     * @return __TYPE__
     */
    protected function afterSave()
    {
        $this->mediaUsage();
        
        if (Pelican_Db::$values['STATE_ID'] != Pelican_Db::$values['old_STATE_ID']) {
            Pelican_Factory::getInstance('Mail');
            
            $aStateCache = Pelican_Cache::fetch("Backend/State");
            $aState = array();
            foreach ($aStateCache as $state) {
                $aState[$state['id']] = $state['lib'];
            }
            
            $texte = "La page \"" . Pelican_Db::$values['PAGE_TITLE'] . "\", url http://" . Pelican::$config['SITE']['INFOS']['SITE_URL'] . Pelican_Db::$values['PAGE_CLEAR_URL'] . " a changé d'état.
Elle a été passée de l'état \"" . t($aState[Pelican_Db::$values['old_STATE_ID']]) . "\" à  l'état \"" . t($aState[Pelican_Db::$values['STATE_ID']]) . "\" par l'utilisateur \"" . $_SESSION[APP]['user']['name'] . "\" le " . date('d/m/Y') . " à  " . date('H\hi') . ".
------------------------------
Page \"" . Pelican_Db::$values['PAGE_TITLE'] . "\" at url http://" . Pelican::$config['SITE']['INFOS']['SITE_URL'] . Pelican_Db::$values['PAGE_CLEAR_URL'] . " has changed its state.
It has been changed from state \"" . t($aState[Pelican_Db::$values['old_STATE_ID']]) . "\" to state \"" . t($aState[Pelican_Db::$values['STATE_ID']]) . "\" by user \"" . $_SESSION[APP]['user']['name'] . "\" on " . date('d/m/Y') . " at " . date('H\hi') . ".";
            
            $mail = new Pelican_Mail();
            $mail->setBodyText(utf8_decode($texte));
            $mail->setFrom(Pelican::$config['SITE']['INFOS']['SITE_MAIL_EXPEDITEUR']);
            $mail->addTo(Pelican::$config['SITE']['INFOS']['SITE_MAIL_WEBMASTER']);
            $mail->setSubject(utf8_decode('CPP "' . Pelican::$config['SITE']['INFOS']['SITE_LABEL'] . '" information : Page "' . Pelican_Db::$values['PAGE_TITLE_BO'] . '" changed to "' . t($aState[Pelican_Db::$values['STATE_ID']]) . '"'));
            //$mail->send();
        }
        
        parent::afterSave();
    }

    /**
     * Association entre les id de Pelican_Media et id de contenu pour empecher la
     * suppression de Pelican_Media lorsqu'ils sont utilisés
     *
     * @access protected
     * @return void
     */
    protected function mediaUsage()
    {
        $oConnection = Pelican_Db::getInstance();
        
        /**
         * Insertion des Pelican_Media associés au miniWord
         */
        if (Pelican_Db::$values[$this->workflowField . "_ID"]) {
            
            /**
             * Suppression des Pelican_Media associés au miniWord
             */
            $aBind[":USAGE_ID"] = Pelican_Db::$values[$this->workflowField . "_ID"];
            $aBind[":USAGE_VERSION"] = $this->sauve[$this->workflowField . "_VERSION"];
            $aBind[":USAGE_TYPE"] = $oConnection->strToBind($this->workflowField);
            $aBind[":LANGUE_ID"] = Pelican_Db::$values['LANGUE_ID'];
            $complement = "";
            if ($_POST["form_action"] != Pelican::$config["DATABASE_DELETE"]) {
                $complement = "AND (USAGE_VERSION = :USAGE_VERSION OR (USAGE_VERSION + " . Pelican::$config["HISTORIQUE_MAX"] . ") < :USAGE_VERSION )";
            }
            $oConnection->query("delete from #pref#_media_usage
					where
					USAGE_ID=:USAGE_ID
					" . $complement . "
					AND LANGUE_ID=:LANGUE_ID
					AND USAGE_TYPE=:USAGE_TYPE", $aBind);
            if (Pelican_Db::$values["editorImageList"] && $_POST["form_action"] != Pelican::$config["DATABASE_DELETE"]) {
                $editors = explode("#", implode("#", Pelican_Db::$values["editorImageList"]));
                if ($editors) {
                    foreach ($editors as $contentField) {
                        $usage .= Pelican_Db::$values[$contentField];
                    }
                    Pelican_Db::$values["MEDIA_ID"] = $this->extractMediaLinks($usage);
                }
                if (Pelican_Db::$values["MEDIA_ID"]) {
                    Pelican_Db::$values["USAGE_ID"] = Pelican_Db::$values[$this->workflowField . "_ID"];
                    Pelican_Db::$values["USAGE_VERSION"] = $this->sauve[$this->workflowField . "_VERSION"];
                    Pelican_Db::$values["USAGE_TYPE"] = $this->workflowField;
                    $oConnection->updateTable("INS", "#pref#_media_usage", "MEDIA_ID");
                }
            }
        }
    }

    /**
     * __DESC__
     *
     * @access protected
     * @param __TYPE__ $content
     *            __DESC__
     * @return __TYPE__
     */
    protected function extractMediaLinks($content)
    {
        $pattern = "/((@import\s+[\"'`]([\w:?=@&\/#._;-]+)[\"'`];)|";
        $pattern .= "(:\s*url\s*\([\s\"'`]*([\w:?=@&\/#._;-]+)";
        $pattern .= "([\s\"'`]*\))|<[^>]*\s+(src|href|url)\=[\s\"'`]*";
        $pattern .= "([\w:?=@&\/#._;-]+)[\s\"'`]*[^>]*>))/i";
        preg_match_all($pattern, stripslashes($content), $matches);
        $result = array_unique($matches[8]);
        $result = array_map("extractMediaId", $result);
        $result = array_unique($result);
        if (! $result[0]) {
            @array_shift($result);
        }
        return ($result);
    }

    /**
     * __DESC__
     *
     * @access protected
     * @param int $id
     *            Id de page dont l'on recherche le parent
     * @return int
     */
    protected function getParentPage($id)
    {
        $oConnection = Pelican_Db::getInstance();
        $return = $oConnection->queryItem("SELECT PAGE_PARENT_ID FROM #pref#_page where PAGE_ID=:PAGE_ID", array(
            ":PAGE_ID" => $id
        ));
        return $return;
    }
}

/**
 * __DESC__
 *
 * @param __TYPE__ $path
 *            __DESC__
 * @return __TYPE__
 */
function extractMediaId($path)
{
    if (substr_count($path, Pelican::$config["MEDIA_VAR"])) {
        $id = (int) basename($path);
    }
    return $id;
}

/**
 * __DESC__
 *
 * @param __TYPE__ $url
 *            __DESC__
 * @return __TYPE__
 */
function cleanRewriting($url)
{
    $return = "";
    if ($url) {
        $url = str_replace("http:/", "http://", $url);
        $url = str_replace("/http", "http", $url);
        $url = str_replace("http:///", "http://", $url);
        $info = @parse_url($url);
        $dir = str_replace(".", "/", dirname($info["path"]));
        $file = basename($info["path"]);
        $fin = ($dir == "/" ? "/" : "");
        $pathinfo = pathinfo($info["path"]);
        $info["path"] = "/" . $dir . "/" . $file . $fin;
        $info["path"] = str_replace("///", "/", $info["path"]);
        $info["path"] = str_replace("//", "/", $info["path"]);
        $info["path"] = str_replace("/./", "/", $info["path"]);
        if ($pathinfo["extension"]) {
            $info["path"] = str_replace($pathinfo["extension"] . "/", $pathinfo["extension"], $info["path"]);
        }
        if ($info["path"] || $info["query"]) {
            $return = str_replace("//", "/", $info["path"] . ($info["query"] ? "?" : "") . $info["query"]);
        }
    }
    return $return;
}
