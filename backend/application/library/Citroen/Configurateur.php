<?php

namespace Citroen;

use Citroen\Html\Util;
use Pelican;

/**
 * Class Configurateur.
 *
 * @author Pierre Pottié <pierre.pottie@businessdecision.com>
 */
class Configurateur
{
   public function getConfigurateurUrl($vehicule, $configuration, $isMobile = false)
   {
       $configurateurUrl = '';
       $tags = array(
              '##LCDV_CURRENT##' => $vehicule['LCDV6'],
              '##GRADES##' => $vehicule['GRADES'],
              '##VERSION##' => $vehicule['VERSION'],
          );

       if ($vehicule['GAMME'] ==  Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VU']) {
           //use WsGamme to discover url for configurator
            $sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
           $sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));

           if (empty($configuration['URL_REBOND_CFG_PRO']) || self::inLoop($configuration['URL_REBOND_CFG_PRO'])) {
               $configurateurUrls = \Pelican_Cache::fetch("Frontend/Citroen/Gamme/GetConfiguratorUrl", array(
                        $sPays,
                        $sLangue,
                ));

               if (is_array($configurateurUrls) && array_key_exists($vehicule['LCDV6'], $configurateurUrls)) {
                   $configurateurUrl = $configurateurUrls[$vehicule['LCDV6']];
               }
           } else {
               $configurateurUrl = $configuration['URL_REBOND_CFG_PRO'];
           }
       } else {
           $configurateurUrl = $configuration['URL_CONFIGURATEUR'.(($isMobile ==  true) ? '_MOBILE' : '')];
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
