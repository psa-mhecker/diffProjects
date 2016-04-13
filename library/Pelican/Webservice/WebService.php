<?php

//pelican_import('Basic.PelicanDB.Adapter.Auth');
//pelican_import('Auth.Adapter.PelicanDB.Digest');
require_once(Pelican::$config['LIB_ROOT'].'/Pelican/Auth/Adapter/Basic.php');
require_once(Pelican::$config['LIB_ROOT'].'/Pelican/Auth/Adapter/Digest.php');

/**
* Classe de base pour tous les services Pélican
*
*
*/
class Pelican_Webservice
{
    protected $_oConnection;
    protected $_authenticated;
    protected $_username;
    protected $_function_prefix = 'service_';
    protected $_protocol = 'SOAP';

    public function __construct()
    {

        $this->_oConnection = Pelican_Db::getInstance();
    }

    //public function

    /*
    * Relaie la demande vers la fonction demandée
    *
    * @param string method
    * @param mixed arguments
    */
    public function __call($method,$arguments)
    {
        $method_private_name = $this->_function_prefix.$method;
        error_log(' Calling method '.$method_private_name);

        // Bloque les appels en direct aux methodes de la classe
        if (ereg($this->_function_prefix, $method)) {
            return $this->fault($this->_protocol, '404', 'Unknown method');
        }

        // Vérifie si le service est disponible
        $fault = $this->isAvailable($method_private_name, $this->_username);
        if ($fault!==true) {return $fault;}

        // Vérifie si le service nécéssite une authentification
        if ($this->mustAuthenticate($method_private_name, $this->_username) && !$this->_authenticated) {
            return $this->fault($this->_protocol, '401', 'Please authenticate');
        }

        // Le service est bien disponible, on relaie la demande
        error_log(' - authentication OK');
        $returnVal = call_user_func_array(array($this, $method_private_name), $arguments);

        return $returnVal;
    }

    /*
    * Authentication function
    * This is called by the Soap header of the same name. The function name is a Ws-security standard Pelican_Auth tag
    * and corresponds to the header tag.
    *
    * @param string username
    * @param string password
    */
    public function UsernameToken($infos)
    {
        //var_dump($infos);
        error_log( '  => Authentification en cours...' );

        $username = $infos->Username;
        $password = $infos->Password;

        $isBasic = !isset($infos->Created) || $infos->Created=='' || !isset($infos->Nonce) || $infos->Nonce=='';

        // Instanciation du vérificateur d'identité
        if ($isBasic) {
            $auth = new Pelican_Auth_Adapter_Basic();
            error_log(' - Basic authentication adapter');
        } else {
            $auth = new Pelican_Auth_Adapter_Digest();
            error_log(' - Digest authentication adapter');
        }
        $auth->setIdentity($username);
        $auth->setConfig(array('#pref#_webservice_account', 'WEBSERVICE_ACCOUNT_LOGIN', 'WEBSERVICE_ACCOUNT_PASSWORD'));

        if (!$isBasic) {
            $c = array(
                'Password' => $infos->Password,
                'Created' => $infos->Created,
                'Nonce' => $infos->Nonce
            );
            $auth->setCredential($c);
        } else {
            $auth->setCredential($password);
        }

        error_log( '  => $auth->authenticate()' );
        $result = $auth->authenticate();

        // Store username for logging
        $this->_username = $username;

        // Vérification de l'authentification
        if ( $result->IsValid() ) {
            error_log( '  - Authentification OK' );
            $this->_authenticated = true;
        } else {
            error_log( '  - Authentification echouee' );

            return $this->fault($this->_protocol, '401', 'Wrong login and/or password');
        }
    }

    /**
    * Détermine si le service est disponible ou non pour cet utilisateur
    *
    */
    public function isAvailable($method, $username='')
    {
        // Détermine si la méthode est accessible, selon le paramétrage back office
        $aAvailableServices = Pelican_Cache::fetch( 'Webservice/Action', array($method, $username) );

        if ( !is_array($aAvailableServices) ) {
            error_log(' - Methode non existante dans un package de services');

            return $this->fault('SOAP', '403', "Forbidden Method : ".ereg_replace($this->_function_prefix,'',$method));
        }

        if (!$fault && !$aAvailableServices['OUTPUT_SOAP']) {
            error_log(' - Format SOAP non autorise');

             return $this->fault('SOAP', '403', "Forbidden protocol ");
        }

        return true;
    }

    /**
    * Détermine si le service nécessite une authentification
    *
    */
    public function mustAuthenticate($method, $username)
    {
        $aAvailableServices = Pelican_Cache::fetch( 'Webservice/Action', array($method, $username) );

        if ( is_array($aAvailableServices) && $aAvailableServices['WEBSERVICE_PACKAGE_PUBLIC']=='1' ) {
            return false;
        }

        return true;
    }

    /**
    * Renvoie un code d'erreur si le service n'est pas disponible
    *
    */
    protected function fault($type, $code, $message)
    {
        if ($type=='REST') {
            return Exception($code, $message);
        } else {
            return new SoapFault($code, $message);
        }
    }

    public static function getHiddenMethods()
    {
        return array('getHiddenMethods', 'UsernameToken', 'isAvailable', 'mustAuthenticate', 'autodiscover');
    }

    public function autodiscover($login)
    {
        $data = Pelican_Cache::fetch( 'Webservice/User/Action', array($login, false) );

        $r = array();
        $params = array();

        // Récupération de tous les services accessibles pour ce login
        if(is_array($data))
        foreach ($data as $service) {

            $action_index_name = ereg_replace($this->_function_prefix,'', $service['WEBSERVICE_ACTION_METHOD']);

            // Récupération des paramètres des méthodes si nécessaire
            if (!is_array($params[$service["WEBSERVICE_CLASS_PATH"].$service["WEBSERVICE_CLASS"]])) {

                $class = Pelican::$config['LIB_ROOT'].'/../application/sites/frontend_services/services/'.$service["WEBSERVICE_CLASS_PATH"].'/'.$service["WEBSERVICE_CLASS"].'.php';

                $class_data = array();
                //error_log(file_exists($class));
                if (file_exists($class)) {

                    $params[$service["WEBSERVICE_CLASS_PATH"].$service["WEBSERVICE_CLASS"]] = array();

                    include_once($class);

                    $ref = new ReflectionClass($service["WEBSERVICE_CLASS"]);
                    foreach ( $ref->getMethods() as $method ) {

                        $method_index_name = ereg_replace($this->_function_prefix,'', $method->getName());
                        if ($method->isPublic() && !ereg('__', $method_index_name) ) {

                            $params[$service["WEBSERVICE_CLASS_PATH"].$service["WEBSERVICE_CLASS"]][$method_index_name] = array();
                            foreach ($method->getParameters() as $p) {
                                $params[$service["WEBSERVICE_CLASS_PATH"].$service["WEBSERVICE_CLASS"]][$method_index_name][] = $p->getName();
                            }
                        }
                    }

                }
            }

            // Construction du tableau de données concernant les services
            $r[] = array();
            $r[count($r)-1]['name'] = $service['WEBSERVICE_ACTION_NAME'];

            if(is_array($params[$service["WEBSERVICE_CLASS_PATH"].$service["WEBSERVICE_CLASS"]][$action_index_name]) &&
            count($params[$service["WEBSERVICE_CLASS_PATH"].$service["WEBSERVICE_CLASS"]][$action_index_name])>0){
                $r[count($r)-1]['url'] = Pelican::$config["FRONTSERVICE_HTTP"].'?method='.$action_index_name.'&'.implode($params[$service["WEBSERVICE_CLASS_PATH"].$service["WEBSERVICE_CLASS"]][$action_index_name], '=param&').'=param&format=format';
            } else {
                $r[count($r)-1]['url'] = Pelican::$config["FRONTSERVICE_HTTP"].'?method='.$action_index_name.'&format=format';
            }
            $r[count($r)-1]['description'] = $service['WEBSERVICE_ACTION_DESCRIPTION'];
            $r[count($r)-1]['formats'] = array();
            if($service['OUTPUT_XML']) $r[count($r)-1]['formats'][] = 'XML';
            if($service['OUTPUT_RSS']) $r[count($r)-1]['formats'][] = 'RSS';
            if($service['OUTPUT_ATOM']) $r[count($r)-1]['formats'][] = 'ATOM';
            if($service['OUTPUT_HTML']) $r[count($r)-1]['formats'][] = 'Pelican_Html';
            $r[count($r)-1]['parameters'] = $params[$service["WEBSERVICE_CLASS_PATH"].$service["WEBSERVICE_CLASS"]][$action_index_name];

        }

        return $r;
    }
}
