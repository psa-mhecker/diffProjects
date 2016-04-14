<?php
/**
 * Classe de gestion du Pelican_Index_Frontoffice (Contrôleur).
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @since 01/02/2011
 * @link http://www.interakting.com
 */

/**
 * Classe de gestion du Pelican_Index_Frontoffice (Contrôleur).
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 01/02/2011
 */
class Pelican_Layout
{
    /**
     * __DESC__.
     *
     * @access public
     *
     * @var __TYPE__
     */
    public $title = '';

    /**
     * __DESC__.
     *
     * @access public
     *
     * @var array
     */
    public $aPage = array();

    /**
     * __DESC__.
     *
     * @access public
     *
     * @var array
     */
    public $aContent = array();

    /**
     * __DESC__.
     *
     * @access public
     *
     * @var int
     */
    public $iSite;

    /**
     * __DESC__.
     *
     * @access public
     *
     * @var int
     */
    public $iLanguage;

    /**
     * __DESC__.
     *
     * @access public
     *
     * @var int
     */
    public $idHome;

    /**
     * __DESC__.
     *
     * @access public
     *
     * @var int
     */
    public $versionHome;

    /**
     * __DESC__.
     *
     * @access public
     *
     * @var bool
     */
    public $bBuffer = true;

    /**
     * __DESC__.
     *
     * @access public
     */
    public function __destruct()
    {
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param string $complementCybertag (option) __DESC__
     * @param string $confirm            (option) __DESC__
     */
    public function getCybertag($complementCybertag = "", $confirm = "")
    {
        if (!isset($_GET["cid"])) {
            $_GET["cid"] = "";
        }
        if (!isset($_GET["tpl"])) {
            $_GET["tpl"] = "";
        }
        $infos = Pelican_Cache::fetch("Frontend/Site", array($_SESSION[APP]['SITE_ID']));
        $tag = Pelican_Cache::fetch("Frontend/Cybertag", array($_SESSION[APP]['SITE_ID'], $_SERVER["QUERY_STRING"], $_SESSION[APP]["HOME_PAGE_ID"], $_GET["pid"], $_GET["cid"], $_GET["tpl"], $complementCybertag));
        $script = Pelican_Cache::fetch("Tag/Type", array($_SESSION[APP]['SITE_ID'], Pelican::$config["SERVER_PROTOCOL"]));
        $trans["%%CLIENT%%"] = $script["CLIENT"];
        $trans["%%HTTP_SITE%%"] = "http://".$infos["SITE_URL"];
        $trans["%%URL%%"] = (!empty($infos["TAG_URL"]) ? $infos["TAG_URL"] : '');
        $trans["%%SECTION%%"] = $tag[1];
        if ($confirm) {
            $trans["%%RUBRIQUE%%"] = $tag[0]."_confirm";
        } else {
            $trans["%%RUBRIQUE%%"] = $tag[0];
        }
        if (Pelican::$config["TYPE_ENVIRONNEMENT"] == "prod" || Pelican::$config["TYPE_ENVIRONNEMENT"] == "preprod") {
            return (strtr($script["TAG"], $trans));
        } else { //echo nl2br(str_replace("\\\\r\\\\n","\r\n",htmlentities(strtr($script["TAG"], $trans))));
        }
    }

    /**
     * __DESC__.
     *
     * @access public
     */
    public function echoLoadEnd()
    {
        echo Pelican_Html::script(array(type => "text/javascript"), 'loadPage = false;');
    }

    /**
     * Génération des zones de la page.
     *
     * @access public
     */
    public function getZones()
    {
        $module = 'Layout.'.ucfirst($this->pageType);
        pelican_import($module);
        $classname = pelican_classname($module);
        $this->oZone = new $classname($this->aPage);
        $return = $this->oZone->getModules();

        return $return;
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
        if (empty($this->view)) {
            $this->view = & Pelican_Factory::getInstance('View');
        }

        return $this->view;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function getInfos()
    {
        // Site initialisation
        $this->initSite();
        // Data initialisation
        $this->initData();
        // redirect
        if (!empty($this->aPage["PAGE_REDIRECT_URL"])) {
            header("location: ".$layout->aPage["PAGE_REDIRECT_URL"]);
            exit();
        }
    }

    /**
     * Initialisation des sessions associées au site consulté.
     *
     * @access public
     */
    public function initSite()
    {

        /* Recherche de l'url associée au DNS appelé */
        $url_site = Pelican_Cache::fetch("Frontend/Site/Url", strtoLower($_SERVER["HTTP_HOST"]));
        if (!$_SESSION[APP]['LANGUE_ID']) {
            $_SESSION[APP]['LANGUE_ID'] = 1;
        }

        /* Recherche de la home et de sa version en fonction de l'URL */
        $site = Pelican_Cache::fetch("Frontend/Site/Init", array($url_site["SITE_URL"], $_SESSION[APP]['LANGUE_ID']));
        $site['LANGUE_ID'] = ($site['LANGUE_ID'] ? $site['LANGUE_ID'] : $_SESSION[APP]['LANGUE_ID']);

        /* Initialisation des variables de session de fonctionnement des sites */
        Pelican::$config["SITE_URL"] = "http://".$url_site["SITE_URL"];
        $_SESSION[APP]['SITE_ID'] = $site['SITE_ID'];
        $_SESSION[APP]["HOME_PAGE_ID"] = $site["PAGE_ID"];
        $_SESSION[APP]["HOME_PAGE_VERSION"] = $site["PAGE_".Pelican::getPreviewVersion()."_VERSION"];
        $_SESSION[APP]["GLOBAL_PAGE_ID"] = $site["NAVIGATION_ID"];
        $_SESSION[APP]["GLOBAL_PAGE_VERSION"] = ($site["NAVIGATION_".Pelican::getPreviewVersion()."_VERSION"] ? $site["NAVIGATION_".Pelican::getPreviewVersion()."_VERSION"] : 1);
        if (!empty($site["PARAMETERS"])) {
            foreach ($site["PARAMETERS"] as $key => $param) {
                $_SESSION[APP][$key] = $param;
            }
        }
        if (!$_SESSION[APP]['LANGUE_ID']) {
            $_SESSION[APP]['LANGUE_ID'] = $site['LANGUE_ID'];
        }
        if (!valueExists($_SESSION[APP], "GLOBAL_PAGE_ID")) {
            $_SESSION[APP]["GLOBAL_PAGE_ID"] = $_SESSION[APP]["HOME_PAGE_ID"];
            $_SESSION[APP]["GLOBAL_PAGE_VERSION"] = ($site["HOME_PAGE_VERSION"] ? $site["HOME_PAGE_VERSION"] : 1);
        }
        $this->iSite = $_SESSION[APP]['SITE_ID'];
        $this->iLanguage = $_SESSION[APP]['LANGUE_ID'];
        $this->idHome = $_SESSION[APP]["HOME_PAGE_ID"];
        $this->versionHome = $_SESSION[APP]["HOME_PAGE_VERSION"];
    }

    /**
     * __DESC__.
     *
     * @access public
     */
    public function initData()
    {

        /* Pour la prévisu */
        if (valueExists($_GET, "preview")) {
            $this->type_version = "DRAFT";
        } else {
            $this->type_version = "CURRENT";
        }
        if (valueExists($_POST, "cid")) {
            $_GET["cid"] = $_POST["cid"];
        }
        if (valueExists($_POST, "tpl")) {
            $_GET["tpl"] = $_POST["tpl"];
        }

        /* on récupère les infos liées au contenu : template, meta données etc...*/
        if (valueExists($_GET, "cid")) {
            $this->aContent = Pelican_Cache::fetch("Frontend/Content/Template", array($_GET["cid"], $this->iSite, $this->iLanguage, Pelican::getPreviewVersion()));
            if (!$this->aContent) {
                error_log('[Pelican] [FrontOffice.php] this->aContent est vide : cid->'.$_GET['cid']);
                $this->sendError(404);
            }
            if (!valueExists($_GET, "pid") && $this->aContent["PAGE_ID"]) {
                $_GET["pid"] = $this->aContent["PAGE_ID"];
            }
            if (!valueExists($_GET, "tpl")) {
                if ($this->aContent["TEMPLATE_ID"]) {
                    $_GET["tpl"] = $this->aContent["TEMPLATE_ID"];
                }
            }
        }

        /* !!!!! IMPORTANT : si un cid est défini, on recherche le pid associé sinon on prend par défaut la page d'accueil */
        if (!valueExists($_GET, "pid")) {
            if (valueExists($_GET, "cid") && valueExists($this->aContent, "PAGE_ID")) {
                $_GET["pid"] = $this->aContent["PAGE_ID"];
            } else {
                $_GET["pid"] = $this->idHome;
            }
        }
        if (empty($_GET["pid"])) {
            error_log('[Pelican] [FrontOffice.php] pas de pid');
        }
        $this->aPage = Pelican_Cache::fetch("Frontend/Page", array($_GET["pid"], $this->iSite, $this->iLanguage, Pelican::getPreviewVersion()));

        /* identification du type de page */
        $aPageType = Pelican_Cache::fetch("PageType/Template", array($this->aPage['TEMPLATE_PAGE_ID']));
        $this->aPage['PAGE_TYPE_CODE'] = $aPageType['PAGE_TYPE_CODE'];

        /* prise en charge standard des flash */
//        $this->getView()->getHead()->setSwfObject();

        /* titre de la page */
        if (valueExists($this->aContent, "WINDOW_TITLE") && $this->aPage) {
            $this->aPage['PAGE_TITLE'] = strip_tags($this->aContent["WINDOW_TITLE"]);
        }
        if ($this->aContent && $this->aPage) {
            $this->aPage["PAGE_SUBTITLE"] = ($this->aContent["CONTENT_CATEGORY_LABEL"] ? $this->aContent["CONTENT_CATEGORY_LABEL"] : $this->aContent["CONTENT_SUBTITLE"]);
        }
        if ($this->aPage) {
            $this->aPage['PAGE_TITLE'] = strip_tags($this->aPage['PAGE_TITLE']);
        }
        if (valueExists($_GET, "tpl")) {

            /* SPECIFIQUE MEN : Interception pour les contenus à 2 colonnes */
            $template = Pelican_Cache::fetch("Template", $_GET["tpl"]);
        }

        /* Pour le alt des images de titre */
        Pelican::$config["ALT_TITLE"] = $this->aPage['PAGE_TITLE'];
        if (empty($this->aPage['PAGE_TITLE']) or $this->aPage['PAGE_TITLE'] == '') {
            error_log('[error] 404 [URL : '.$_SERVER['REQUEST_URI'].']');
        } else {
            $this->setTitle($this->aPage['PAGE_TITLE']);
        }
        if (!(Pelican_Request::$multidevice_template_switch)) {
            $this->pageType = 'desktop';
        } else {
            $this->pageType = Pelican_Request::$userAgentFeatures['type'];
        }

        /* initialisation de la fonction portal */
        if ($this->aPage['PAGE_TYPE_CODE'] == 'PORTAL') {
            $this->pageType = 'portal';
        }
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function isValid()
    {
        if ($this->idHome) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Génération du titre.
     *
     * @access public
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param field_type $title __DESC__
     *
     * @return __TYPE__
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function getMetaTag()
    {
        Pelican::$config['WINDOW_TITLE'] = (valueExists($this->aContent, "CONTENT_META_TITLE") ? $this->aContent["CONTENT_META_TITLE"] : $this->aPage["PAGE_META_TITLE"]);
        $keyword = (valueExists($this->aContent, "CONTENT_META_KEYWORD") ? $this->aContent["CONTENT_META_KEYWORD"] : $this->aPage["PAGE_META_KEYWORD"]);
        $desc = (valueExists($this->aContent, "CONTENT_META_DESC") ? $this->aContent["CONTENT_META_DESC"] : $this->aPage["PAGE_META_DESC"]);
        if ($keyword) {
            $this->getView()->getHead()->setMeta("name", "keywords", $keyword);
        }
        if ($desc) {
            $this->getView()->getHead()->setMeta("name", "description", $desc);
            $this->getView()->getHead()->setMeta("name", "dc.description", $desc);
        }
        $this->getView()->getHead()->setMeta("name", "Author", Pelican::$config['SITE']['INFOS']['SITE_TITLE']);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function getPageTitle()
    {
        Pelican::$config['WINDOW_TITLE'] = (Pelican::$config['WINDOW_TITLE'] ? Pelican::$config['WINDOW_TITLE'] : $this->aPage['PAGE_TITLE']);

        return Pelican::$config['WINDOW_TITLE']." - ".Pelican::$config['SITE']['INFOS']['SITE_TITLE'];
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param __TYPE__ $data __DESC__
     *
     * @return __TYPE__
     */
    public static function identifyPlugins($data)
    {
        if ($data['PLUGIN_ID'] && $data["ZONE_FO_PATH"]) {
            $data["ZONE_FO_PATH"] = 'module/'.$data['PLUGIN_ID'].'/'.$data["ZONE_FO_PATH"];
        }

        return $data;
    }
}
