<?php

/**
 * Classe de gestion du Pelican_Index_Frontoffice (Contrôleur)
 *
 * @package Pelican
 * @subpackage Layout
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @since 01/02/2011
 * @link http://www.interakting.com
 */

/**
 * Classe de gestion du Psa_Layout
 *
 * @package Psa
 * @subpackage Layout
 * @author Laurent Boulay <laurent.boulay@businessdecision.com>
 * @since 29/08/2013
 */
require_once 'Pelican/Layout.php';

class Psa_Layout extends Pelican_Layout
{

    /**
     * Initialisation des sessions associées au site consulté
     *
     * @access public
     * @return void
     */
    public function initSite()
    {
        /**
         * Recherche de l'url associée au DNS appelé
         */
        $url_site = Pelican_Cache::fetch("Frontend/Site/Url", strtoLower($_SERVER["HTTP_HOST"]));
        if (! $_SESSION[APP]['LANGUE_ID']) {
            $aLangue = Pelican_Cache::fetch("Frontend/Citroen/SiteLangues", $url_site['SITE_ID']);

            if (is_array($aLangue) && count($aLangue) > 1 && $_SERVER['REQUEST_URI'] != '/robots.txt') {
                $languageDevice = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
                $languageDevice = strtolower(substr(chop($languageDevice[0]), 0, 2));

                foreach ($aLangue as $lng) {
                    if ($lng['LANGUE_CODE'] == $languageDevice) {
                        $_SESSION[APP]['LANGUE_ID'] = $lng['LANGUE_ID'];
                        break;
                    }
                }

                if (! $_SESSION[APP]['LANGUE_ID']) {
                    $_SESSION[APP]['LANGUE_ID'] = $aLangue[0]['LANGUE_ID'];

                    $page = Pelican_Cache::fetch("Frontend/Page/Template", array(
                        $url_site['SITE_ID'],
                        $_SESSION[APP]['LANGUE_ID'],
                        Pelican::getPreviewVersion(),
                        Pelican::$config['TEMPLATE_PRE_HOME'],
                    ));
                    header('location:'.$page['PAGE_CLEAR_URL']);
                    die();
                }
            } elseif (count($aLangue) == 1) {
                $_SESSION[APP]['LANGUE_ID'] = $aLangue[0]['LANGUE_ID'];
            } else {
                $_SESSION[APP]['LANGUE_ID'] = 1;
            }
        }

        /**
         * Recherche de la home et de sa version en fonction de l'URL
         */
        $site = Pelican_Cache::fetch("Frontend/Site/Init", array(
            $url_site["SITE_URL"],
            $_SESSION[APP]['LANGUE_ID'],
        ));
        $site['LANGUE_ID'] = ($site['LANGUE_ID'] ? $site['LANGUE_ID'] : $_SESSION[APP]['LANGUE_ID']);
        /**
         * Initialisation des variables de session de fonctionnement des sites
         */
        Pelican::$config["SITE_URL"] = "http://".$url_site["SITE_URL"];
        $_SESSION[APP]['SITE_ID'] = $site['SITE_ID'];
        $_SESSION[APP]['CODE_PAYS'] = $site['SITE_CODE_PAYS'];
        $_SESSION[APP]["HOME_PAGE_ID"] = $site["PAGE_ID"];
        $_SESSION[APP]["HOME_PAGE_VERSION"] = $site["PAGE_".Pelican::getPreviewVersion()."_VERSION"];
        $_SESSION[APP]["GLOBAL_PAGE_ID"] = $site["NAVIGATION_ID"];
        $_SESSION[APP]["GLOBAL_PAGE_VERSION"] = ($site["NAVIGATION_".Pelican::getPreviewVersion()."_VERSION"] ? $site["NAVIGATION_".Pelican::getPreviewVersion()."_VERSION"] : 1);
        if (! empty($site["PARAMETERS"])) {
            foreach ($site["PARAMETERS"] as $key => $param) {
                $_SESSION[APP][$key] = $param;
            }
        }
        if (! $_SESSION[APP]['LANGUE_ID']) {
            $_SESSION[APP]['LANGUE_ID'] = $site['LANGUE_ID'];
        }
        if (! valueExists($_SESSION[APP], "GLOBAL_PAGE_ID")) {
            $_SESSION[APP]["GLOBAL_PAGE_ID"] = $_SESSION[APP]["HOME_PAGE_ID"];
            $_SESSION[APP]["GLOBAL_PAGE_VERSION"] = ($site["HOME_PAGE_VERSION"] ? $site["HOME_PAGE_VERSION"] : 1);
        }
        $this->iSite = $_SESSION[APP]['SITE_ID'];
        $this->iLanguage = $_SESSION[APP]['LANGUE_ID'];
        $this->idHome = $_SESSION[APP]["HOME_PAGE_ID"];
        $this->versionHome = $_SESSION[APP]["HOME_PAGE_VERSION"];

        $aPage = Pelican_Cache::fetch("Frontend/Page", array(
            $this->idHome,
            $this->iSite,
            $this->iLanguage,
            Pelican::getPreviewVersion(),
        ));
        $_SESSION[APP]["HOME_PAGE_SHORT_URL"] = $aPage['PAGE_TYPE_SHORTCUT'];
        $_SESSION[APP]["HOME_PAGE_URL"] = $aPage['PAGE_CLEAR_URL'];

        if ($_SERVER['REQUEST_URI'] == '/') {
            if ($_SESSION[APP]["HOME_PAGE_URL"] != $_SERVER['REQUEST_URI'] && empty($site['SITE_HOMEPAGE_REDIRECT'])) {
                header('HTTP/1.0 301 Moved Permanently');
                header('location:' . $_SESSION[APP]["HOME_PAGE_URL"], true, 301);
                die();
            }
            if (empty($_SESSION[APP]['LANGUE_CODE'])) {
                Pelican_Application::resetLang($_SESSION[APP]['LANGUE_ID']);
            }
        } elseif (($_SESSION[APP]["HOME_PAGE_URL"] == $_SERVER['REQUEST_URI']
                  || $_SESSION[APP]["HOME_PAGE_SHORT_URL"] == $_SERVER['REQUEST_URI'])
                  && !empty($site['SITE_HOMEPAGE_REDIRECT'])){
            //Cas pour eviter du DUST : Differents URLs Same Text. Le risque c’est que Google privilégie une URL au détriment de l’autre.
            header('HTTP/1.0 301 Moved Permanently');
            header('location:/', true, 301);
            die();
        }

    }

    public function getMetaTag()
    {
        Pelican::$config['WINDOW_TITLE'] = (valueExists($this->aContent, "CONTENT_META_TITLE") ? $this->aContent["CONTENT_META_TITLE"] : $this->aPage["PAGE_META_TITLE"]);
        $keyword = (valueExists($this->aContent, "CONTENT_META_KEYWORD") ? $this->aContent["CONTENT_META_KEYWORD"] : $this->aPage["PAGE_META_KEYWORD"]);
        $desc = (valueExists($this->aContent, "CONTENT_META_DESC") ? $this->aContent["CONTENT_META_DESC"] : $this->aPage["PAGE_META_DESC"]);
        if ($keyword) {
            $keyword = str_replace('"', "&quot;", $keyword);
            $this->getView()
                ->getHead()
                ->setMeta("name", "keywords", $keyword);
        }
        if ($desc) {
            $desc = str_replace('"', "&quot;", $desc);
            $this->getView()
                ->getHead()
                ->setMeta("name", "description", $desc);
            $this->getView()
                ->getHead()
                ->setMeta("name", "dc.description", $desc);
        }
        $this->getView()
            ->getHead()
            ->setMeta("name", "Author", Pelican::$config['SITE']['INFOS']['SITE_TITLE']);
    }
}
