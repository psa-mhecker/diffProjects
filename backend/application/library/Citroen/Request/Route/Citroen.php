<?php
// include_once ('Abstract.php');
include_once 'Pelican/Request/Route/Abstract.php';

class Citroen_Request_Route_Citroen extends Pelican_Request_Route_Abstract
{
    protected $_params = array();

    public function eligible()
    {
        // JIRA 2921
       $return = (! empty($this->routes['Citroen']) && !(substr_count($this->uri, 'sitemap.xml') ? true : false));

        return $return;
    }

    public function match()
    {
        $route = $this->routes['Citroen'];

        if ($params = $route->matches($this->uri)) {
            $uri = '/'.$this->uri;

            $paramsPage = Pelican_Cache::fetch("Frontend/Url", array(
                $_SESSION[APP]['SITE_ID'],
                $uri,
                Pelican::getPreviewVersion(),
            ));

            /*
             * patch pour les pages en /lang/ résiduelles : si pas de page identifiée et /lang dans l'url => on tente sans /lang
             */
            if (empty($paramsPage) && ! empty($params['lang'])) {
                if ($params['lang'] != '%') {
                    $paramsPage = Pelican_Cache::fetch("Frontend/Url", array(
                        $_SESSION[APP]['SITE_ID'],
                        str_replace('/'.$params['lang'].'/', '/', $uri),
                        Pelican::getPreviewVersion(),
                    ));
                }
            }

            /*
             * l'url claire porte la valeur de la langue
             */
            Pelican_Application::resetLang($paramsPage['LANGUE_ID']);

            // var_dump($params);
            if (empty($paramsPage) && strpos($params['title'], 'pid') !== 0) {
                $return['route'] = $route;
                $return['params'] = $params;
                $return['params']['pid'] = - 1;
                $_GET['pid'] = - 1;

                return $return;
            }

            // RÃ©cupÃ©ration de la tranche date de publication de la page
            $aInfosPage = Pelican_Cache::fetch("Frontend/Page", array(
                $paramsPage['pid'],
                $_SESSION[APP]['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                Pelican::getPreviewVersion(),
            ));
            $dateStart = new DateTime($aInfosPage['PAGE_START_DATE']);
            $dateFin = new DateTime($aInfosPage['PAGE_END_DATE']);
            $dateDuJour = new DateTime(date("Y-m-d H:i:s"));
            $intervalStart = $dateStart->diff($dateDuJour);
            $intervalFin = $dateDuJour->diff($dateFin);
            // Si la date du jour n'est pas dans tranche de date de la page on affiche une page 404
            if (true == $intervalStart->invert || true == $intervalFin->invert) {
                return $return;
            }

            if (! empty($paramsPage['cid']) || ! empty($paramsPage['pid'])) {
                $protocol = (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
                if ($paramsPage['HTTPS'] && (! $params['secure'] || $protocol == 'http')) {
                    header('location: https://'.Pelican::$config['HTTP_HOST'].'/secure'.$uri.'?'.$_SERVER['QUERY_STRING']);
                } elseif (! $paramsPage['HTTPS'] && ($params['secure'] || $protocol == 'https')) {
                    header('location: http://'.Pelican::$config['HTTP_HOST'].$uri.'?'.$_SERVER['QUERY_STRING']);
                }

                $return['route'] = $route;
                $return['params'] = $params;
                $return['params']['pid'] = $paramsPage['pid'];
                $return['params']['cid'] = $paramsPage['cid'];

                if ($return['params']['pid']) {
                    $_GET['pid'] = $return['params']['pid'];
                }

                if ($return['params']['cid']) {
                    $_GET['cid'] = $return['params']['cid'];
                }
            }
        }

        return $return;
    }
}
