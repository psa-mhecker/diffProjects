<?php

namespace Citroen;

use Citroen\Html\Util;
use Pelican;


/**
 * Class Configurateur
 *
 * @author Pierre Pottié <pierre.pottie@businessdecision.com>
 *
 */
class Configurateur
{

    function getConfigurateurUrl($vehicule, $configuration, $isMobile = false)
    {

        $configurateurUrl = '';
        $tags = array(
            '##LCDV_CURRENT##' => $vehicule['LCDV6'],
            '##LCDV##' => $vehicule['LCDV6'],
            '##LCDV6##' => $vehicule['LCDV6'],
            '##GRADES##' => $vehicule['GRADES'],
            '##VERSION##' => $vehicule['VERSION'],
        );
        $configurateurUrl = $configuration['URL_REBOND_CFG_PRO'];
        if ($vehicule['GAMME'] != Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VU']) {
            $configurateurUrl = $configuration['URL_CONFIGURATEUR' . (($isMobile == true) ? '_MOBILE' : '')];
        }
        $configurateurUrl = Util::replaceTagsInUrl($configurateurUrl, $tags, true);

        return $configurateurUrl;
    }

// empeche de boucler indefiniment sur la page de rebond
    protected function inLoop($url)
    {
        $url = parse_url($url);
        $url_current = parse_url($_SERVER['REQUEST_URI']);

        return ($url['path'] == $url_current['path']);
    }
}
