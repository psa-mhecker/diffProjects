<?php

/** Fichier de configuration de l'environnement de PREPRODUCTION PSA - BACKOFFICE **/
//Pelican::$config["LOGIN_ADMIN_BO"] = 'admin';
//Pelican::$config["PWD_ADMIN_BO"] = 'adminAL83';
Pelican::$config["VISUEL_3D_PATH"] = 'http://visuel3d.citroen.com/V3DImage.ashx';
Pelican::$config['mail'] = array(
);

Pelican::$config["HTTP_HOST"] = $_SERVER["HTTP_HOST"];

/**
 * Base de donn�es
 */
Pelican::$config["DATABASE_HOST"] = "slave1preprod"; // Entrée dans le fichier mysqlnd_ms_plugin.ini
Pelican::$config["DATABASE_TYPE"] = "mysql";
Pelican::$config["DATABASE_NAME"] = "CPW_PRE";
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
    'db'=>'CPWPRE'
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
//Pelican::$config["HTTP_MEDIA"] =   "media.ct.cppv2.citroen.preprod.inetpsa.com";
Pelican::$config['HTTP_MEDIA'] = $_SERVER['HTTP_MEDIA_INTRANET']; // Variable d'env a définir dans chaque vhost FO + BO (CPW-1597)
Pelican::$config["HTTP_BACKEND"] =    "admin.cppv2.citroen.preprod.inetpsa.com";

/*prb de chargement des js en acces externe : on doit mettre ici l'adresse IP du serveur */
/** Il faut indiquer ici l'adresse IP du serveur externe de PSA
 ** mais elle peut changer. Ou alors utiliser le fait que le REMOTE_HOST contienne "citroen.com"
 ** Dans le doute, on fait les deux !
 **/

if (strpos($_SERVER['REMOTE_ADDR'], '172.21.') !== false || strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.com') !== false )
{
/**
 * Host internet
 **/
	//Pelican::$config["HTTP_MEDIA"] =   "media.ct.cppv2.citroen.mpsa.com";
	Pelican::$config['HTTP_MEDIA'] = $_SERVER['HTTP_MEDIA_INTERNET']; // Variable d'env a définir dans chaque vhost FO + BO (CPW-1597)	
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
Pelican::$config["SHOW_DEBUG"] = true;
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
Pelican::$config['DIRECTORY_LDAP']['type'] = 'ldap'; // Pour utiliser le bouchon xml : mettre "xml"
Pelican::$config['DIRECTORY_LDAP']['filepath'] = Pelican::$config["DOCUMENT_INIT"] . '/application/configs/ldap-prod.properties'; // pour utiliser le bouchon xml : mettre "/ldap.xml"
Pelican::$config['LOG_LDAP']['filepath'] = Pelican::$config["VAR_ROOT"] . '/logs/cpw.log';
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
Pelican::$config['wurflapi']['DATABASE_CURLOPTIONS'] = array (
        CURLOPT_PROXY          => Pelican::$config['PROXY.CURL.OPTIONS']['CURLOPT_PROXY'],
        CURLOPT_PROXYUSERPWD   => Pelican::$config['PROXY.CURL.OPTIONS']['CURLOPT_PROXYUSERPWD'],
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
    );
Pelican::$config['PROXY']['NO_PROXY_FOR'] = '/.inetpsa.com|localhost/'; // Regex



/**
 * googleKey
 */
Pelican::$config ['youtube']['key'] = 'AIzaSyBENQlleT3Afwv-pnAX7O8lsTF83HrfcxM';

// Facebook/Twitter/Google Connect
Pelican::$config['FACEBOOK']['appId'] = "584470404954676";
Pelican::$config['FACEBOOK']['secret'] = "60bb9d15dc26083a25f67d9fe3f6b145";
Pelican::$config['GOOGLE']['clientId'] = "895361473414-bk6avk1kgihge47k8g9l7pt3ajpjbrs8.apps.googleusercontent.com";
Pelican::$config['GOOGLE']['clientSecret'] = "nNRuJ05miPM_g5rC9xGOPDe1";
Pelican::$config['GOOGLE']['developerKey'] = "AIzaSyB6jE9DY0nI7Lgr7-5qosnS9qkDfJOfp_g";
Pelican::$config['TWITTER']['consumerKey'] = "xOKpQrztbP4rfedruObOw";
Pelican::$config['TWITTER']['consumerSecret'] = "TyD4VxSb9qUrRysDcYqJR71p0GeYnD5SYmamF3Y0k0";
Pelican::$config['TWITTER']['oauth_token'] = "82178459-FDRErU0sAaiwRa7JBERgPtxOg4lFnzxOiL5fffD2d";
Pelican::$config['TWITTER']['oauth_token_secret'] = "gngLHPhOoQRI4l4v9pvTdGuAa3ykKYDkFSof92pVUcM0q";

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