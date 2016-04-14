<?php

namespace Psa;

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(\Pelican::$config['LIB_ROOT'].'/External'),
    get_include_path(),
)));

require_once \Pelican::$config['LIB_ROOT']."/External/HTTP/Request2/Exception.php";
require_once \Pelican::$config['LIB_ROOT']."/External/HTTP/Request2.php";
require_once \Pelican::$config['LIB_ROOT']."/External/HTTP/OAuth/Consumer/Request.php";
require_once \Pelican::$config['LIB_ROOT']."/External/HTTP/OAuth/Consumer.php";
require_once \Pelican::$config['LIB_ROOT']."/External/HTTP/OAuth/Store/Consumer/CacheLite.php";

/**
 * Class BrandId
 *
 * Gestion de l'authentification des utilisateur PSA avec l'OpenID
 * ainsi que la demande d'autorisation de partage via OAuth.
 */
class BrandId
{
    protected $requestParams = array();  // Paramètres de la requète
    protected $config        = null;     // Configuration (OpenID, OAuth, site code et proxy) objet Zend_Config

    protected $openId        = null;     // Object OpenID
    protected $openIdMode    = 'setup';  // Mode de l'OpenID (setup ou immediate)

    /**
     * Constructeur : init de données de fonctionnement du BrandID
     * @param array $requestParams Paramètres de la requète GET
     * @param array $config        Paramètres de configuration OpenID, OAuth
     */
    public function __construct($requestParams, Zend_Config $config = null)
    {
        $this->requestParams = $requestParams;

        if ($config !== null) {
            $this->config = $config;
        } else {
            // Cas où la configuration n'est pas fournie

            // Récupération de la configuration du BrandId
            $brandIdConfig = \Pelican_Cache::fetch('Frontend/Psa/BrandId', array($_SESSION[APP]['SITE_ID']));
            $langue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
            $pays = (empty($_SESSION[APP]['CODE_PAYS']) || strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT') ? 'FR' : strtoupper(substr($_SESSION[APP]['CODE_PAYS'], -2, 2));
            if ($_SESSION[APP]['marque'] == 'DS') {
                $requestParams['context'] = "driveds";
            }
            $culture = $langue.'-'.$pays;
            $aBrandIdConfig = array(
                'culture' => $culture,
                'context' => $requestParams['context'],
                'socialMedia' => $requestParams['social-media'],
                'openid' => array(
                    'identity'  => $brandIdConfig['OPENID_PROVIDER'].'/',
                    'realm'     => \Pelican::$config['DOCUMENT_HTTP'],
                    'returnUrl' => \Pelican::$config['DOCUMENT_HTTP'],
                ),
                'oauth' => array(
                    'provider'        => $brandIdConfig['OAUTH_PROVIDER'],
                    'urlrequesttoken' => $brandIdConfig['OAUTH_PROVIDER'].'/oauth/request-token',
                    'urlauthorize'    => $brandIdConfig['OAUTH_PROVIDER'].'/oauth/authorize',
                    'urlaccesstoken'  => $brandIdConfig['OAUTH_PROVIDER'].'/oauth/access-token',
                    'urlcallback'     => \Pelican::$config['DOCUMENT_HTTP'],
                    'consumerkey'     => $brandIdConfig['OAUTH_CONSUMERKEY'],
                    'consumersecret'  => $brandIdConfig['OAUTH_CONSUMERSECRET'],
                    'method'          => $brandIdConfig['OAUTH_METHOD'],
                ),
                'sitecode' => $brandIdConfig['SITECODE'],
            );

            $aBrandIdConfig['proxy'] = array();
            $aBrandIdConfig['proxy_oauth'] = array();

            if ($brandIdConfig['PROXY_ENABLE'] && \Pelican::$config['PROXY']) {
                $aBrandIdConfig['proxy']['proxy_host']     = \Pelican::$config['PROXY']['HOST'];
                $aBrandIdConfig['proxy']['proxy_port']     = \Pelican::$config['PROXY']['PORT'];
                $aBrandIdConfig['proxy']['proxy_login']    = \Pelican::$config['PROXY']['LOGIN'];
                $aBrandIdConfig['proxy']['proxy_password'] = \Pelican::$config['PROXY']['PWD'];
                
                $aBrandIdConfig['proxy_oauth']['proxy_host']     = \Pelican::$config['PROXY']['HOST'];
                $aBrandIdConfig['proxy_oauth']['proxy_port']     = \Pelican::$config['PROXY']['PORT'];
                $aBrandIdConfig['proxy_oauth']['proxy_user']     = \Pelican::$config['PROXY']['LOGIN'];
                $aBrandIdConfig['proxy_oauth']['proxy_password'] = \Pelican::$config['PROXY']['PWD'];
            } 

            $this->config = new \Zend_Config($aBrandIdConfig);
        }
    }

    /**
     * Instancie l'objet de gestion de l'OpenID
     * @param  string $mode     Le mode de login ("setup" ou "immediate")
     * @param  string $callback L'URL de retour
     * @return void
     */
    public function initOpenId($mode = 'setup', $callback = '')
    {
        // On instancie l'objet qui va gérer les échanges inter-serveurs pour l'OpenID
        $this->openId = new LightOpenId($this->config->openid->realm, $this->config->proxy->toArray());
        $this->setOpenIdMode($mode);
        $this->openId->identity = $this->config->openid->identity.'?culture='.$this->config->culture.($this->config->socialMedia ? '&social-media='.$this->config->socialMedia : '').($this->config->context ? '&context='.$this->config->context : '');
        $this->openId->returnUrl = $this->config->openid->returnUrl.$callback;
    }

    /**
     * Définit le mode de login de l'OpenID
     * @param void
     */
    public function setOpenIdMode($mode)
    {
        $this->openIdMode = $mode;

        if (!empty($this->openId)) {
            $this->openId->mode = $mode;
        }
    }

    /**
     * Test si l'utilisateur à annuler la connexion
     * @return boolean
     */
    public function isCancelled()
    {
        if ($this->requestParams['error'] == 'user_denied' || $this->requestParams['user_origin'] == 'user_denied') {
            return true;
        }

        return false;
    }

    public function isDenied()
    {
        if (isset($this->requestParams['denied'])) {
            return true;
        }

        return false;
    }

    /**
     * Test si l'utilisateur est loggué
     * @return mixed false si non loggué
     *               email de l'utilisateur si loggué
     */
    public function isAuth()
    {
        // Si retour en mode loggué
        if ($this->openId->mode === 'id_res' && $this->getUserEmail() !== null) {
            return true;
        }

        return false;
    }

    public function isDisconnected()
    {
        // Si retour en mode loggué
        if (isset($this->requestParams['user_origin']) && $this->requestParams['user_origin'] === 'disconnection') {
            return true;
        }

        return false;
    }

    public function isPasswordChanged()
    {
        // Si retour en mode loggué
        if (isset($this->requestParams['user_origin']) && $this->requestParams['user_origin'] === 'change-password') {
            return true;
        }

        return false;
    }

    public function isEmailChanged()
    {
        // Si retour en mode loggué
        if (isset($this->requestParams['user_origin']) && $this->requestParams['user_origin'] === 'change-mail') {
            return true;
        }

        return false;
    }

    public function isPasswordReset()
    {
        // Si retour en mode loggué
        if (isset($this->requestParams['user_origin']) && $this->requestParams['user_origin'] === 'setpassword') {
            return true;
        }

        return false;
    }

    /**
     * Retourne l'adresse email de l'utilisateur s'étant connecté
     * @return mixed string Adresse email de l'utilisateur si loggué
     *               null sinon
     */
    public function getUserEmail()
    {
        if (isset($this->requestParams['openid_identity']) && !empty($this->requestParams['openid_identity'])) {
            // Récupération des paramètres de l'URL
            $url = parse_url($this->requestParams['openid_identity']);

            // Si les paramètres sont éxistants
            if (isset($url['query'])) {
                // Split des paramètres
                parse_str(urldecode($url['query']), $params);

                // Si on a le paramètre openid : utilisateur loggué valide
                if (isset($params, $params['openid']) && !empty($params['openid'])) {
                    return $params['openid'];
                }
            }
        }

        return;
    }

    /**
     * Retourne l'URL de connexion du provider en fonction du mode
     * @return string L'URL de connexion du provider
     */
    public function getAuthUrl()
    {
        // Si pas setup demandé : config en mode setup
        // l'URL de connexion diffère selon le mode
        if ($this->openId->mode === 'setup_needed') {
            sleep(2);
            $this->setOpenIdMode('setup');
        }

        if ($this->openIdMode === 'immediate') {
            return $this->openId->authUrl(true);
        }

        return $this->openId->authUrl();
    }

    /**
     * Retourne l'URL de deconnexion
     * @return string
     */
    public function getLogoutUrl($callback = '')
    {
        return $this->config->openid->identity.'/account/disconnect?return_to='.urlencode(\Pelican::$config['DOCUMENT_HTTP'].$callback);
        //return $this->config->openid->identity . '/account/disconnect';
    }

    /**
     * Retourne l'URL d'information
     * @return string
     */
    public function getInfoUrl()
    {
        $urlInfo = $this->config->openid->identity.'/info/popup-info?culture='.$this->config->culture.($this->config->context ? '&context='.$this->config->context : '');

        return $urlInfo;
    }

    /**
     * Retourne l'URL de changement de mot de passe
     * @return string
     */
    public function getChangePasswordUrl($callback = '')
    {
        $url = $this->config->oauth->provider.'/account/change-password?culture='.$this->config->culture.($this->config->context ? '&context='.$this->config->context : '').'&return_to='.urlencode(\Pelican::$config['DOCUMENT_HTTP'].$callback);

        return $url;
    }

    /**
     * Retourne l'URL de changement d'adresse email
     * @return string
     */
    public function getChangeEmailUrl($callback = '')
    {
        $url = $this->config->oauth->provider.'/account/change-mail?culture='.$this->config->culture.($this->config->context ? '&context='.$this->config->context : '').'&return_to='.urlencode(\Pelican::$config['DOCUMENT_HTTP'].$callback);

        return $url;
    }

    /**
     * Retourne l'URL de mot de passe oublié
     * @return string
     */
    public function getForgotPasswordUrl($callback = '')
    {
        $url = $this->config->openid->identity.'/account/forgot-password?culture='.$this->config->culture.($this->config->context ? '&context='.$this->config->context : '').'&return_to='.urlencode(\Pelican::$config['DOCUMENT_HTTP'].$callback);

        return $url;
    }

    /**
     * Retourne l'URL de demande d'autorisation d'accés aux données
     * @param  string $callback L'URL de retour
     * @return mixed  string     L'URL de demande d'autorisation
     *                         false ??
     */
    public function getAuthorizationUrl($callback = '')
    {
        $sessionId = session_id();

        $httpRequest = new \HTTP_Request2(null, $this->config->oauth->method, $this->config->proxy_oauth->toArray());
        $httpRequest->setHeader('Accept-Encoding', '.*');

        $request = new \HTTP_OAuth_Consumer_Request();
        $request->accept($httpRequest);

        $consumer = new \HTTP_OAuth_Consumer($this->config->oauth->consumerkey, $this->config->oauth->consumersecret);
        $consumer->accept($request);

        $consumer->getRequestToken($this->config->oauth->urlrequesttoken, $this->config->oauth->urlcallback.$callback, array(), $this->config->oauth->method);

        $store = new \HTTP_OAuth_Store_Consumer_CacheLite();
        $store->setRequestToken($consumer->getToken(), $consumer->getTokenSecret(), 'oauth', $sessionId);

        $urlParam = array('culture' => $this->config->culture, 'context' => $this->config->context);

        return $consumer->getAuthorizeUrl($this->config->oauth->urlauthorize.'.', $urlParam);
    }

    /**
     * Retourne un jeton d'accés au données
     * @return mixed string Le jeton trouvé
     *               null si pas de jeton
     */
    public function getAccessToken()
    {
        $sessionId = session_id();

        $store = new \HTTP_OAuth_Store_Consumer_CacheLite();
        $tokens = $store->getRequestToken('oauth', $sessionId);

        if (!$tokens) {
            return;
        }

        $httpRequest = new \HTTP_Request2(null, $this->config->oauth->method, $this->config->proxy_oauth->toArray());
        $httpRequest->setHeader('Accept-Encoding', '.*');

        $request = new \HTTP_OAuth_Consumer_Request();
        $request->accept($httpRequest);

        $consumer = new \HTTP_OAuth_Consumer($this->config->oauth->consumerkey, $this->config->oauth->consumersecret, $tokens['token'], $tokens['tokenSecret']);
        $consumer->accept($request);

        $consumer->getAccessToken($this->config->oauth->urlaccesstoken, $this->requestParams['oauth_verifier'], array(), $this->config->oauth->method);

        return $consumer->getToken();
    }
}
