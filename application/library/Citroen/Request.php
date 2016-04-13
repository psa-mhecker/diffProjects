<?php
require_once ('Pelican/Request.php');

class Citroen_Request extends Pelican_Request
{
    /**
     * __DESC__
     *
     * @access public
     * @param string $uri
     *            __DESC__
     * @param array $localParams
     *            (option) __DESC__
     * @param bool $activeCache
     *            (option) __DESC__
     * @param int $lifetime
     *            (option) __DESC__
     * @return __TYPE__
     */
    public function cachedCall ($uri, $localParams = array(), $activeCache = false, $lifetime = 30)
    {
        /**
         * parametrage de smarty pour le bloc
         * $caching true or false
         * $cache_lifetime durée en seconde du Pelican_Cache
         * -1 = Illimitée
         */
        Pelican_Profiler::start($uri, 'bloc');
        if ($activeCache) {
            self::$cacheUsed = 1;
            $timestamp = $timestamp = Pelican_Cache::getSecondTimeStep($lifetime);
            (Pelican_Controller::isMobile())?$idcache = 1:$idcache = 0;
            $idcache .= md5(Pelican::$config["SERVER_PROTOCOL"].Pelican::$config["HTTP_HOST"]);
            $idcache = Pelican_View::getCacheId($idcache, array(
                $_SESSION[APP]['LANGUE_ID']
            ));
            $response = Pelican_Cache::fetch('Request', array(
                $uri,
                serialize($localParams),
                $idcache,
                $timestamp
            ));
        } else {
            $response = self::call($uri, $localParams);
        }
        /**
         * remise a false du cache pour le bloc suivant #peut etre inutile vus que le param de Pelican_Cache est par defaut false
         */
        Pelican_Profiler::stop($uri, 'bloc');
        if ($activeCache) {
            if (self::$cacheUsed) {
                $msg = '[cache de reponse ' . $lifetime . ' sec. : OK]';
            } else {
                $msg = '[sans cache de reponse]';
            }
            Pelican_Profiler::rename($uri, '&nbsp;&nbsp;' . $msg . '&nbsp;&nbsp;' . $uri, 'bloc');
        }
        return $response;
    }
}