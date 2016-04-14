<?php

/**
 * Classe de gestion du Sitemap.
 *
 * @author Pierre Pottié <pierre.pottie@businessdecision.com>
 *
 * @since 20/11/2014
 */
class Citroen_Controller_Sitemap_Controller extends Pelican_Controller_Sitemap
{
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
                $siteMapXML .= "<priority>".(($node->priority === null) ? Pelican::$config["DEFAULT_PAGE_PRIORITY"] : $node->priority)."</priority>\n";
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
