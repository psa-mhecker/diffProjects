<?php
/**
* Helper d'affichage de code de tracking web analytics
*
* @since 20/01/2014 17:19:05
*/
use Citroen\GTM;

class Frontoffice_Analytics_Helper
{
    /**
    * Retourne le code HTML de marquage Google Tag Manager
    */
    public static function getGtmTag()
    {
		// Récupération du code de tracking configuré en backoffice
		$tagType = Pelican_Cache::fetch("Tag/Type", array($_SESSION[APP]['SITE_ID'] , Pelican::$config["SERVER_PROTOCOL"]));
		
		// Construction du code HTML (dataLayer + code GTM)
        $html = "";
        $html .= "\n<script type=\"text/javascript\">\n<!-- NBA_dataLayer_v1 -->\nvar dataLayer = window[\"dataLayer\"]||[];\n dataLayer.push(".GTM::serializeDataLayerJS().");\n<!-- End of NBA dataLayer v1 -->\n</script>\n";
		$html .= $tagType['TAG'];
        return $html;
    }
}
?>