<?php
/**
 * __DESC__.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */
require_once 'Zend/Rest/Server.php';

/**
 * __DESC__.
 *
 * @author __AUTHOR__
 */
class Pelican_Rest_Server extends Zend_Rest_Server
{
    /**
     * @access protected
     *
     * @var __TYPE__ __DESC__
     */
    protected $_resultHandler;

    /**
     * @access protected
     *
     * @var __TYPE__ __DESC__
     */
    protected $_authHandler;

    /**
     * @access protected
     *
     * @var __TYPE__ __DESC__
     */
    protected $_callingArgs;

    /**
     * @access protected
     *
     * @var __TYPE__ __DESC__
     */
    protected $_oConnection;

    /**
     * @access protected
     *
     * @var __TYPE__ __DESC__
     */
    protected $_functionPrefix = 'service_';

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $rh __DESC__
     *
     * @return __TYPE__
     */
    public function setResultHandler($rh)
    {
        $this->_resultHandler = $rh;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $ah __DESC__
     *
     * @return __TYPE__
     */
    public function setAuthHandler($ah)
    {
        $this->_authHandler = $ah;
    }

    /**
     * Gère la requête Rest.
     *
     * @access public
     *
     * @param bool $request (option) __DESC__
     *
     * @return __TYPE__
     */
    public function handle($request = false)
    {
        $this->_oConnection = Pelican_Db::getInstance();
        if (!$request) {
            $request = $_REQUEST;
        }
        // Détermine si l'appel est valide
        $validity = $this->_checkCallValidity($request);
        if ($validity !== true) {
            return $validity;
        }
        // Détermine si l'appel est autorisé par rapport aux services définis
        $validity = $this->_checkCallAutorization($request);
        // Authentification requise mais pas d'authentifier à disposition
        if ($validity !== true && !$this->_authHandler) {
            error_log(' - Pas d\'authentifier disponible');
            $this->fault('Impossible de s\'authentifier', 403);
            // Authentification requise
        } elseif ($validity !== true) {
            if ($validity == 401) {
                error_log(' - Authentification necessaire - verification en cours');
                $validity = $this->_checkCallAuthentication($request);
                // Autres erreurs
            } else {
                error_log(' - Erreur indefinie');
                $this->fault('Erreur indefinie', 400);

                return false;
            }
        }
        if ($validity !== true) {
            error_log(' - Appel non autorise - fin de traitement');

            return $validity;
        }
        // Effectue l'appel
        error_log('Appel de la methode demandee');
        $calling_args = $this->_getCallingArgs($request);
        $this->_method = $this->_functionPrefix.$this->_method;
        if ($this->_functions[$this->_method] instanceof Zend_Server_Reflection_Method) {
            // Get class
            $class = $this->_functions[$this->_method]->getDeclaringClass()->getName();
            if ($this->_functions[$this->_method]->isStatic()) {
                $result = $this->_callStaticMethod($class, $calling_args);
            } else {
                $result = $this->_callObjectMethod($class, $calling_args);
            }
        } elseif (!$result) {
            try {
                $result = call_user_func_array($this->_functions[$this->_method]->getName(), $calling_args);
            } catch (Exception $e) {
                $result = $this->fault($e, 400);
            }
        }
        // Gestion du résultat
        if (isset($this->_resultHandler)) {
            $this->_resultHandler->setContext($this->_functions, $this->_method);
            $response = $this->_resultHandler->handle($result);
        }
        if (!$this->returnResponse()) {
            if (!headers_sent()) {
                foreach ($this->_headers as $header) {
                    header($header);
                }
            }
        }
        echo $response;

        return;
    }

    /**
     * Détermine si l'appel REST est valide.
     *
     * @access protected
     *
     * @param __TYPE__ $request __DESC__
     *
     * @return __TYPE__
     */
    protected function _checkCallValidity($request)
    {
        error_log('Verification de la validite de l\'appel');
        // Le paramètre "method" doit être renseigné
        if (!isset($request['method'])) {
            error_log(' - Pas de methode specifiee dans l\'url (parametre "method")');
            $this->fault("No Method Specified.", 404);
        }
        $this->_method = $request['method'];
        // La méthode doit exister dans une des classes référencées
        if (!isset($this->_functions[$this->_functionPrefix.$this->_method])) {
            error_log(' - Methode non existante');
            $this->fault("Unknown Method '$this->_method'.", 404);
        }
        // La méthode doit être accessible et publique
        if (!($this->_functions[$this->_functionPrefix.$this->_method] instanceof Zend_Server_Reflection_Function || $this->_functions[$this->_functionPrefix.$this->_method] instanceof Zend_Server_Reflection_Method) && !$this->_functions[$this->_functionPrefix.$this->_method]->isPublic()) {
            error_log(' - Methode non publique ou pas une fonction valide');
            $this->fault("Unknown Method '$this->_method'.", 404);
        }
        $request_keys = array_keys($request);
        array_walk($request_keys, array(__CLASS__, "lowerCase"));
        $request = array_combine($request_keys, $request);
        // Récupération de la liste des paramètres attendus, et ceux fournis
        $func_args = $this->_functions[$this->_functionPrefix.$this->_method]->getParameters();
        $calling_args = $this->_getCallingArgs($request);
        // Si il y a moins de paramètres que prévu, il s'agit d'une erreur
        if (count($calling_args) < count($func_args)) {
            error_log(' - Pas le bon nombre de parametres attendu - '.count($func_args).' prevus - '.count($calling_args).' renseignes');
            $this->fault('Invalid Method Call to '.$this->_method.'. Requires '.count($func_args).', '.count($calling_args).' given.', 400);
        }
        // Détermine si la méthode est accessible, selon le paramétrage back office
        $aAvailableServices = Pelican_Cache::fetch('Webservice/Action', array($this->_functionPrefix.$this->_method));
        if (!is_array($aAvailableServices) || (is_array($aAvailableServices) && count($aAvailableServices) == 0)) {
            error_log(' - Methode non existante dans un package de services');
            $this->fault("Forbidden Method '$this->_method'.", 403);
        }
        if ((!isset($request['format']) || $aAvailableServices['OUTPUT_'.strtoupper($request['format']) ] != 1)) {
            error_log(' - Format de retour non autorise');
            $this->fault("Forbidden Format ".$request['format'], 403);
        }
        error_log(' - L\'appel est valide !');

        return true;
    }
    // Permet de déterminer si le service demandé est autorisé

    /**
     * __DESC__.
     *
     * @access protected
     *
     * @param __TYPE__ $request __DESC__
     *
     * @return __TYPE__
     */
    protected function _checkCallAutorization($request)
    {
        $result = true;
        // Détermine si l'appel est autorisé
        error_log('Verification de l\'acces au service');
        error_log(' - Verification de l existence du service dans le Pelican_Cache des services accessibles');
        $aAvailableServices = Pelican_Cache::fetch('Webservice/Action', array($this->_functionPrefix.$this->_method));
        if (!is_array($aAvailableServices) || (is_array($aAvailableServices) && count($aAvailableServices) == 0)) {
            error_log(' - Service inaccessible !');
            $this->fault("Service non existant", 404);
        } else {
            // Le service est accessible mais via authentification
            if ($aAvailableServices['WEBSERVICE_PACKAGE_PUBLIC'] != 1) {
                error_log(' - Le service est accessible avec authentification');
                $result = 401;
            } else {
                error_log(' - Le service est bien accessible');
            }
        }

        return $result;
    }

    /**
     * Etape optionnelle - verifie les droits utilisateur si le service est en acces
     * restreint.
     *
     * @access protected
     *
     * @param __TYPE__ $request __DESC__
     *
     * @return __TYPE__
     */
    protected function _checkCallAuthentication($request)
    {
        $result = true;
        // Détermine si l'appel est autorisé
        error_log('Verification de l\'authentification');
        if (isset($_SERVER['PHP_AUTH_USER']) || isset($_SERVER['PHP_AUTH_DIGEST'])) {
            try {
                error_log(' - Appel de _authHandler');
                $result = $this->_authHandler->authenticate($request);
            } catch (Exception $e) {
                die($e->getMessage());
            }
            error_log(' - User/Mot de passe ?');
            if ($result === true) {
                error_log(' - User/Mot de passe correct');
            } else {
                error_log(' - User/Mot de passe incorrect');
                $this->faultWithHeaders($this->_authHandler->getHeaders());
            }
        } else {
            error_log(' - User/Mot de passe non defini !');
            $this->fault("Authentification requise", 401);
        }

        return $result;
    }

    /**
     * __DESC__.
     *
     * @access protected
     *
     * @param __TYPE__ $request __DESC__
     *
     * @return __TYPE__
     */
    protected function _getCallingArgs($request)
    {
        if (isset($this->_callingArgs)) {
            return $this->_callingArgs;
        }
        $func_args = $this->_functions[$this->_functionPrefix.$this->_method]->getParameters();
        //debug($this->_method);
        $calling_args = array();
        foreach ($func_args as $arg) {
            if (isset($request[strtolower($arg->getName()) ])) {
                $calling_args[$arg->getName() ] = $request[strtolower($arg->getName()) ];
            } elseif ($arg->isOptional()) {
                $calling_args[$arg->getName() ] = $arg->getDefaultValue();
            }
        }
        // Sort arguments by key -- @see ZF-2279
        //ksort($calling_args);
        $this->_callingArgs = $calling_args;
        //debug($calling_args, 'args');
        return $calling_args;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $headers __DESC__
     *
     * @return __TYPE__
     */
    public function faultWithHeaders($headers)
    {
        foreach ($headers as $h) {
            header($h);
        }
        //print_r($headers);
        die;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param string $exception (option) __DESC__
     * @param string $code      (option) __DESC__
     *
     * @return __TYPE__
     */
    public function fault($exception = null, $code = null)
    {
        //error_log('handling error');
        //~ print_r(headers_list());
        //header('Content-Type: text/html');
        switch ($code) {
            case 400:
                error_log('header error 400');
                header("HTTP/1.0 400 Bad Request");
                die;
            break;
            case 401:
                error_log('header error 401');
                header("HTTP/1.0 401 Unauthorized");
                header('WWW-Authenticate: Digest realm="Services",qop="auth",nonce="'.uniqid().'",opaque="'.md5($realm).'"');
                print_r($_SERVER);
                die;
            break;
            case 403:
                error_log('header error 403');
                header("HTTP/1.0 403 Forbidden");
                die;
            break;
            case 404:
                error_log('header error 404');
                header("HTTP/1.0 404 Not Found");
                die;
            break;
        }
    }
}

/**
 * __DESC__.
 *
 * @author __AUTHOR__
 */
class PelicanRestServerException extends Exception
{
}
