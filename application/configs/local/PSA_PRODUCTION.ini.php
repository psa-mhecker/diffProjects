<?php

/** Fichier de configuration de l'environnement de PRODUCTION PSA **/
//Pelican::$config["LOGIN_ADMIN_BO"] = 'admin';
//Pelican::$config["PWD_ADMIN_BO"] = 'adminAL83';
Pelican::$config["VISUEL_3D_PATH"] = 'http://visuel3d.citroen.com/V3DImage.ashx';
Pelican::$config['mail'] = array(
);

Pelican::$config["HTTP_HOST"] = $_SERVER["HTTP_HOST"];

/**
 * Base de donn�es
 */
Pelican::$config["DATABASE_HOST"] = "localhost";
Pelican::$config["DATABASE_TYPE"] = "mysql";
Pelican::$config["DATABASE_NAME"] = "CPW_PRD";
Pelican::$config["DATABASE_USER"] = "mdecpw00";
Pelican::$config["DATABASE_PASS"] = "mdecpw00";
Pelican::$config["DATABASE_PERSISTENTCONNECTION"] = false;

/**
 * MongoDb
 */
Pelican::$config["MONGODB_URI"] ="mongodb://localhost:27117";
Pelican::$config["MONGODB_PARAMS"] =array(
    'username'=>'mdecpw01',
    'password'=>'mdecpw01',
    'db'=>'CPWPRD'
);

/**
 * Tracking actions feature
 */
Pelican::$config[APP]['enable_action_tracking'] = true;


/**
 * MongoDb CPPV2 Perso
 */
//Pelican::$config['MONGODB_CITROEN']['PERSO_SCORE_COLLECTION_NAME']='perso_user';
Pelican::$config['MONGODB_CITROEN']['PERSO_SCORE_COLLECTION_NAME']='psa_perso_score';
Pelican::$config['MONGODB_CITROEN']['PERSO_INDICATEUR_COLLECTION_NAME']='psa_perso_indicateur';

/**
 * Hosts intranet
 */
Pelican::$config["HTTP_MEDIA"] =   "media.ct.cppv2.citroen.inetpsa.com";
Pelican::$config["HTTP_BACKEND"] =    "admin.cppv2.citroen.inetpsa.com";

/*prb de chargement des js en acces externe : on doit mettre ici l'adresse IP du serveur */
/** Il faut indiquer ici l'adresse IP du serveur externe de PSA
 ** mais elle peut changer. Ou alors utiliser le fait que le REMOTE_HOST contienne "citroen.com"
 ** Dans le doute, on fait les deux !
 **/

if (strpos($_SERVER['REMOTE_ADDR'], '172.21.') !== false // IP externe Intranet
|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.com') !== false 
|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.fr') !== false
|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.dk') !== false
|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.nl') !== false
|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.co.uk') !== false
|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.de') !== false)
{
/**
 * Host internet
 **/
	Pelican::$config["HTTP_MEDIA"] =   "media.ct.cppv2.citroen.mpsa.com";
	Pelican::$config["HTTP_BACKEND"] =    "admin.cppv2.citroen.mpsa.com";
	Pelican::$config["HTTP_HOST"] = $_SERVER['HTTP_CLIENT_HOST'];
}

if ($_SERVER['HTTP_MEDIA']) {
    Pelican::$config["HTTP_MEDIA"] = $_SERVER['HTTP_MEDIA'];
}

/**
 * environnement
 */
Pelican::$config["IM_ROOT"] = "/usersdev/cpw/soft/GraphicsMagick/bin/gm convert";
//Pelican::$config["IM_ROOT"] = "/usersdev/cpw/soft/GraphicsMagickTRACEUR.sh gm convert";
Pelican::$config["ENVIRONNEMENT"] = strtolower($_ENV["TYPE_ENVIRONNEMENT"]);
Pelican::$config["TYPE_ENVIRONNEMENT"] = strtolower($_ENV["TYPE_ENVIRONNEMENT"]);

/**
 * debug
 */
Pelican::$config["SHOW_DEBUG"] = false;
Pelican::$config['DEBUG']['BAR'] = true;
Pelican::$config['DEBUG']['SHOW'] = true;
Pelican::$config['DEBUG']['PROFILING'] = true;

/**
 * Divers
 */
Pelican::$config["ENABLE_CACHE_SMARTY"] = false;
Pelican::$config["KEEP_IMAGE_FORMAT"] = false;
Pelican::$config['SECURITY']['CRYPT_KEY'] = 'skdgfdjkqsdh';

Pelican::$config["TRANSLATE_TRACE"] = true;

// LDAP
Pelican::$config['DIRECTORY_LDAP']['type'] = 'ldap';
Pelican::$config['DIRECTORY_LDAP']['filepath'] = Pelican::$config["DOCUMENT_INIT"] . '/application/configs/ldap-prod.properties';
Pelican::$config['LOG_LDAP']['filepath'] = Pelican::$config["VAR_ROOT"] . '/logs/cpwldap.log';
Pelican::$config['PROFILS']['CONTRIBUTEUR'] = 'CONTRIBUTEUR';

/**
 * Security
 */
Pelican::$config ['server_configuration'] ['display_errors'] = 1;
Pelican::$config ['server_configuration'] ['error_reporting'] = E_ALL ^ E_NOTICE;

/**
 * Proxy
 */
Pelican::$config['PROXY']['URL'] = 'http://http.internetpsa.inetpsa.com';
Pelican::$config['PROXY']['HOST'] = 'http.internetpsa.inetpsa.com';
Pelican::$config['PROXY']['PORT'] = '80';
Pelican::$config['PROXY']['LOGIN'] = 'mdecpw00';
Pelican::$config['PROXY']['PWD'] = 'svncpw00';
Pelican::$config['PROXY.CURL.OPTIONS'] = array (
        'CURLOPT_PROXY'          => 'http://http.internetpsa.inetpsa.com:80',
        'CURLOPT_PROXYUSERPWD'   => 'mdecpw00:svncpw00',
        'CURLOPT_SSL_VERIFYPEER' => false,
        'CURLOPT_SSL_VERIFYHOST' => false,
        'CURLOPT_PROXYTYPE' => 'CURLPROXY_HTTP',
    );
Pelican::$config['PROXY']['NO_PROXY_FOR'] = '/.inetpsa.com|localhost/'; // Regex

/**
 * CitroenId
 */
Pelican::$config["OPENID_MOCK_ENABLE"] = true;

/**
 * Google Tag Manager

 */
Pelican::$config['GTM']['brand'] = 'citroen';
Pelican::$config['GTM']['siteTypeLevel1'] = 'cpp';
Pelican::$config['GTM']['internalSearchType'] = 'Internal';


/**
 * Adresse email à laquelle sont envoyées les alertes de sécurité (classe Pelican_Security)
 */
Pelican::$config['SECURITY_ALERT_EMAIL_TO'] = 'cppv2-etudes@mpsa.com';