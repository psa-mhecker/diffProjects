<?php

/** Fichier de configuration de l'environnement de RECETTE PSA **/
Pelican::$config["LOGIN_ADMIN_BO"] = 'admin';
Pelican::$config["PWD_ADMIN_BO"] = 'adminAL83';
Pelican::$config["VISUEL_3D_PATH"] = 'http://visuel3d.citroen.com/V3DImage.ashx';
Pelican::$config['mail'] = array(
);

Pelican::$config["HTTP_HOST"] = $_SERVER["HTTP_HOST"];

/**
 * Base de données
 */
Pelican::$config["DATABASE_HOST"] = "localhost"; // Entrée dans le fichier mysqlnd_ms_plugin.ini
Pelican::$config["DATABASE_TYPE"] = "mysql";
Pelican::$config["DATABASE_NAME"] = "CPW_REC";
Pelican::$config["DATABASE_USER"] = "mdecpw00";
Pelican::$config["DATABASE_PASS"] = "mdecpw00";
Pelican::$config["DATABASE_PERSISTENTCONNECTION"] = false;

/**
 * MongoDb
 */
//Pelican::$config["MONGODB_URI"] ="mongodb://localhost:27017";
Pelican::$config["MONGODB_URI"] ="mongodb://mdecpw01:mdecpw01@localhost";
Pelican::$config["MONGODB_PARAMS"] =array(
    'username'=>'mdecpw01',
    'password'=>'mdecpw01',
    'db'=>'CPWREC'
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
//Pelican::$config["HTTP_MEDIA"] =   "media.rec.cppv2.dev.inetpsa.com";
Pelican::$config['HTTP_MEDIA'] = $_SERVER['HTTP_MEDIA_INTRANET']; // Variable d'env a définir dans chaque vhost FO + BO (CPW-1597)
Pelican::$config["HTTP_BACKEND"] = "admin.rec.cppv2.dev.inetpsa.com";

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
	|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.de') !== false
	|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.co.za') !== false
	|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.at') !== false
	|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.ch') !== false
	|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.it') !== false
	|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.cz') !== false
	|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.es') !== false
	|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.pt') !== false
	|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.hu') !== false
	|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.ru') !== false
	|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.lu') !== false
	|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.pl') !== false
	|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.ie') !== false
	|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.no') !== false
	|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.hr') !== false
	|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.si') !== false
	|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.ua') !== false
	|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.tr') !== false
	|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.br') !== false	
	|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.ar') !== false
	|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.be') !== false
	|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.de') !== false
	|| strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.sk') !== false
    || strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.se') !== false
)
//if (!Psa_Util::isInternal())
{
	/**
	 * Host internet
	 **/
	//Pelican::$config["HTTP_MEDIA"] =   "media-rec-cppv2-dev.citroen.com"; //"
	Pelican::$config['HTTP_MEDIA'] = $_SERVER['HTTP_MEDIA_INTERNET']; // Variable d'env a définir dans chaque vhost FO + BO (CPW-1597)
	Pelican::$config["HTTP_BACKEND"] = "admin-rec-cppv2-dev.citroen.com";
	Pelican::$config["HTTP_HOST"] = $_SERVER['HTTP_CLIENT_HOST'];
}
//var_dump($_SERVER['HTTP_CLIENT_HOST']);

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
if ($_SERVER['HTTP_CLIENT_HOST'] === NULL )
{
	Pelican::$config["SHOW_DEBUG"] = true;
	Pelican::$config['DEBUG']['BAR'] = true;
	Pelican::$config['DEBUG']['SHOW'] = true;
	Pelican::$config['DEBUG']['PROFILING'] = true;
}
/**
 * Divers
 */
Pelican::$config["ENABLE_CACHE_SMARTY"] = false;
Pelican::$config["KEEP_IMAGE_FORMAT"] = false;
Pelican::$config['SECURITY']['CRYPT_KEY'] = 'skdgfdjkqsdh';

Pelican::$config["TRANSLATE_TRACE"] = true;

// LDAP
Pelican::$config['DIRECTORY_LDAP']['type'] = 'ldap'; // Pour utiliser le bouchon xml : mettre "xml"
Pelican::$config['DIRECTORY_LDAP']['filepath'] = Pelican::$config["DOCUMENT_INIT"] . '/application/configs/local/ldap.properties'; // pour utiliser le bouchon xml : mettre "/ldap.xml"
Pelican::$config['LOG_LDAP']['filepath'] =  Pelican::$config["DOCUMENT_INIT"]  . '/var/logs/cpwldap.log';

/**
 * Security
 */
Pelican::$config ['server_configuration'] ['display_errors'] = 1;
Pelican::$config ['server_configuration'] ['error_reporting'] = E_ALL ^ E_NOTICE;

/**
 * Proxy
 */
Pelican::$config['PROXY']['URL'] = 'http://relaishttp.sgppsa.com'; // ancien proxy : http.internetpsa.inetpsa.com
Pelican::$config['PROXY']['HOST'] = 'http.internetpsa.inetpsa.com';//'http.internetpsa.inetpsa.com';
Pelican::$config['PROXY']['PORT'] = '80';
Pelican::$config['PROXY']['LOGIN'] = 'mdecpw00';
Pelican::$config['PROXY']['PWD'] = 'svncpw00';
Pelican::$config['PROXY.CURL.OPTIONS'] = array (
        'CURLOPT_PROXY'          => 'http://relaishttp.sgppsa.com:80',
		'CURLOPT_PROXY'          => 'http.internetpsa.inetpsa.com:80',
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
Pelican::$config['PROXY']['NO_PROXY_FOR'] ='/aoaccessoire\.inetpsa\.com|wssoap\.inetpsa\.com|preview-frfr\.cfg\.vu\.citroen\.integ\.inetpsa\.com|re7\.iddcr\.citroen\.com|ws-store\.citroen\.inetpsa.com|webservices\.annuaire-pdv\.inetpsa\.com|pyag01\.inetpsa\.com|localhost/'; // Regex
/**
 * CitroenId
 */
Pelican::$config["OPENID_MOCK_ENABLE"] = true;

/**
 * Google Tag Manager
 */
Pelican::$config['GTM']['brand'] = 'CITROEN';
Pelican::$config['GTM']['siteTypeLevel1'] = 'cpp';
Pelican::$config['GTM']['internalSearchType'] = 'Internal';

/**
 * Adresse email à laquelle sont envoyées les alertes de sécurité (classe Pelican_Security)
 */
Pelican::$config['SECURITY_ALERT_EMAIL_TO'] = 'cppv2-etudes@mpsa.com';