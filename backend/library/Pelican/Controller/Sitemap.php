<?php

/**
 * Classe de gestion du Sitemap.
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 27/01/2010
 */
class Pelican_Controller_Sitemap extends Pelican_Controller
{
    public function indexAction()
    {
        /* Recherche de l'url associée au DNS appelé */
        $url_site = Pelican_Cache::fetch("Frontend/Site/Url", strtoLower($_SERVER["HTTP_HOST"]));

        if (! $_SESSION[APP]['LANGUE_ID']) {
            $_SESSION[APP]['LANGUE_ID'] = 1;
        }

        /* Recherche de la home et de sa version en fonction de l'URL */
        $site = Pelican_Cache::fetch("Frontend/Site/Init", array(
            $url_site["SITE_URL"],
            $_SESSION[APP]['LANGUE_ID'],
        ));
        $site['LANGUE_ID'] = ($site['LANGUE_ID'] ? $site['LANGUE_ID'] : 1);

        /* Initialisation des variables de session de fonctionnement des sites */
        Pelican::$config["SITE_URL"] = "http://".$url_site["SITE_URL"];

        $_SESSION[APP]['SITE_ID'] = $site['SITE_ID'];
        $_SESSION[APP]["HOME_PAGE_ID"] = $site["PAGE_ID"];
        $_SESSION[APP]["HOME_PAGE_VERSION"] = $site["PAGE_CURRENT_VERSION"];
        $_SESSION[APP]["GLOBAL_PAGE_ID"] = $site["NAVIGATION_ID"];
        $_SESSION[APP]["GLOBAL_PAGE_VERSION"] = ($site["NAVIGATION_CURRENT_VERSION"] ? $site["NAVIGATION_CURRENT_VERSION"] : 1);
        if ($site["PARAMETERS"]) {
            foreach ($site["PARAMETERS"] as $key => $param) {
                $_SESSION[APP][$key] = $param;
            }
        }

        $_SESSION[APP]['LANGUE_ID'] = $site['LANGUE_ID'];

        $this->getRequest()
            ->setHeaders('Content-type', 'text/xml');
        $this->setResponse($this->_getSitemap());
    }

    protected function _getSitemap($step = "")
    {
        if (! $step) {
            $day = 60 * 24;
            $step = $day / 4;
        }

        $temp = Pelican_Cache::fetch("Frontend/Navigation", $_GET["pid"]);
        $aPath = @explode("#", $temp["PAGE_PATH"]);

        //Fichier XML de destination
        $fichierXML = Pelican::$config["CACHE_FW_ROOT"]."/sitemap/".$_SESSION[APP]['SITE_ID']."/sitemap.".Pelican_Cache::getTimeStep($step).".xml";
        if (! file_exists($fichierXML)) {
            if (! is_dir(dirname($fichierXML))) {
                mkdir(dirname($fichierXML), 0755, true);
            }

            $openfichier = fopen($fichierXML, "w+");

            //Entête du fichier XML
            $enteteXML = "<?xml version=\"1.0\" encoding=\"".(Pelican::$config["CHARSET"] ? Pelican::$config["CHARSET"] : "ISO-8859-1")."\"?>\n";
            $enteteXML .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">'."\n";

            //Site map
            $siteMapXML = "";

            // PID
            $oTree = Pelican_Cache::fetch("Frontend/Site/Tree", array(
                $_SESSION[APP]['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                "CURRENT",
                true,
                $aPath[0],
                $aPath[1],
                $aPath[2],
                $aPath[3],
            ));

            foreach ($oTree->aNodes as $node) {
                $siteMapXML .= "<url>\n";
                $siteMapXML .= "<loc>".Pelican::$config["DOCUMENT_HTTP"].$node->url."</loc>\n";
                $aLastMod = explode(" ", $node->lastmod);
                $siteMapXML .= "<lastmod>".$aLastMod[0]."</lastmod>\n";
                $siteMapXML .= "<changefreq>weekly</changefreq>\n";
                $siteMapXML .= "<priority>0.7</priority>\n";
                $siteMapXML .= "</url>\n";
            }

            //CID
            /*$content = Pelican_Cache::fetch("Frontend/Content/Sitemap", array(
                $_SESSION[APP]['SITE_ID'] ,
                $_SESSION[APP]['LANGUE_ID'] ,
                'all'
            ));
            foreach ($content as $value) {
                $siteMapXML .= "<url>\n";
                $siteMapXML .= "<loc>" . Pelican::$config["DOCUMENT_HTTP"] . $value['CONTENT_CLEAR_URL'] . "</loc>\n";
                $aLastMod = explode("/", $value['CONTENT_PUBLICATION_DATE']);
                $siteMapXML .= "<lastmod>" . $aLastMod[2] . '-' . $aLastMod[1] . '-' . $aLastMod[0] . "</lastmod>\n";
                $siteMapXML .= "<changefreq>weekly</changefreq>\n";
                $siteMapXML .= "<priority>0.5</priority>\n";
                $siteMapXML .= "</url>\n";
            }*/

            //Footer du fichier XML
            $footerXML = "</urlset>";
            $content = $enteteXML.$siteMapXML.$footerXML;

            fwrite($openfichier, $content);
            fclose($openfichier);
        } else {
            $content = file_get_contents($fichierXML);
        }

        return $content;
    }
}
