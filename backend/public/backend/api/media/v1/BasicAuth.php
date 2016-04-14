<?php
/**
 * Authentification (contrôle d'accès à l'API)
 *
 * @author Vincent Paré <vincent.pare@businessdecision.com>
 */

namespace MediaApi\v1;

use Pelican;
use Luracast\Restler\iAuthenticate;
use Luracast\Restler\RestException;

class BasicAuth implements iAuthenticate
{
    /**
     * Vérification des identifiants HTTP
     */
    public function __isAllowed()
    {
        $creds = self::getHttpCredentials();
        
        if (empty($creds) || empty($creds->user) || empty($creds->pass)) {
            throw new RestException(401, 'Basic Authentication Required');
        }
        
        if (empty(Pelican::$config["API"]['MEDIA']['AUTH'])) {
            throw new RestException(500, 'Auth configuration is empty');
        }
        
        foreach (Pelican::$config["API"]['MEDIA']['AUTH'] as $key => $val) {
            if ($val['USER'] === $creds->user && $val['PASS'] === $creds->pass) {
                return true;
            }
        }
        
        throw new RestException(401, 'Wrong credentials');
    }

    /**
     * Composition du header HTTP WWW-Authenticate
     *
     * @return string
     */
    public function __getWWWAuthenticateString()
    {
        return 'BASIC realm="Unspecified"';
    }

    /**
     * Extraction des identifiants HTTP (user/pass) de la requête
     *
     * @return mixed Object, ou false si aucun identifiants n'a plus être extrait
     */
    protected static function getHttpCredentials()
    {
        $creds = new \stdClass;
        
        // Extraction depuis PHP_AUTH_USER/PHP_AUTH_PW
        if (isset($_SERVER['PHP_AUTH_USER']) || isset($_SERVER['PHP_AUTH_PW'])) {
            $creds->user = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null;
            $creds->pass = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : null;
            return $creds;
        }
        
        // Extraction depuis HTTP_AUTHORIZATION
        if (!isset($user) || !isset($pass)) {
            if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
                $httpAuth = $_SERVER['HTTP_AUTHORIZATION'];
            } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                $httpAuth = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            } else {
                return false;
            }
            list($user, $pass) = explode(':', base64_decode(substr($httpAuth, 6)));
            $creds->user = isset($user) ? $user : null;
            $creds->pass = isset($pass) ? $pass : null;
            return $creds;
        }
        
        return false;
    }
}
