<?php

/** Fichier de configuration de l'environnement de PREPRODUCTION PSA **/
//Pelican::$config["LOGIN_ADMIN_BO"] = 'admin';
//Pelican::$config["PWD_ADMIN_BO"] = 'adminAL83';
Pelican::$config["VISUEL_3D_PATH"] = 'http://visuel3d.citroen.com/V3DImage.ashx';
Pelican::$config['mail'] = array(
);

/*
 * Base de données
 */
Pelican::$config["DATABASE_HOST"] = "slave1preprod"; // Entrée dans le fichier mysqlnd_ms_plugin.ini
Pelican::$config["DATABASE_TYPE"] = "mysql";
Pelican::$config["DATABASE_NAME"] = "NDP_00";
Pelican::$config["DATABASE_USER"] = "mwpndpdb";
Pelican::$config["DATABASE_PASS"] = "XXXXXX";
Pelican::$config["DATABASE_PERSISTENTCONNECTION"] = false;

/*
 * MongoDb
 
Pelican::$config["MONGODB_URI"] = "mongodb://localhost:27117";
Pelican::$config["MONGODB_PARAMS"] = array(
    'username' => 'mdecpw01',
    'password' => 'mdecpw01',
    'db' => 'CPWPRE',
);
*/

/*
 * Tracking actions feature
 */
Pelican::$config[APP]['enable_action_tracking'] = true;

/*
 * MongoDb CPPV2 Perso
 */
//Pelican::$config['MONGODB_CITROEN']['PERSO_SCORE_COLLECTION_NAME']='perso_user';
Pelican::$config['MONGODB_CITROEN']['PERSO_SCORE_COLLECTION_NAME'] = 'psa_perso_score';
Pelican::$config['MONGODB_CITROEN']['PERSO_INDICATEUR_COLLECTION_NAME'] = 'psa_perso_indicateur';

/*
 * Hosts intranet
 */


/*prb de chargement des js en acces externe */
// Methode Caroline
if (isset($_SERVER['HTTP_CLIENT_HOST']) && $_SERVER['HTTP_CLIENT_HOST'] !== $_SERVER['HTTP_HOST'])
{
  // Accès à partir de l'internet
    $_SERVER['SYMFONY__HTTP__MEDIA'] = $_SERVER['HTTP_MEDIA'] = Pelican::$config['HTTP_MEDIA'] = $_SERVER['HTTP_MEDIA_INTERNET']; // Variable d'env a définir dans chaque vhost FO + BO (CPW-1597)
    Pelican::$config["HTTP_BACKEND"] = "admin-ndp-preprod.peugeot.com";
    Pelican::$config["HTTP_HOST"] = $_SERVER['HTTP_CLIENT_HOST'];
}
else
{ // Accès à partir de l'intranet
	$_SERVER['SYMFONY__HTTP__MEDIA'] = $_SERVER['HTTP_MEDIA'] = Pelican::$config['HTTP_MEDIA'] = $_SERVER['HTTP_MEDIA_INTRANET']; // Variable d'env a définir dans chaque vhost FO + BO (CPW-1597)
	Pelican::$config["HTTP_BACKEND"] = "admin.ndp.preprod.peugeot.inetpsa.com";
	Pelican::$config["HTTP_HOST"] = $_SERVER["HTTP_HOST"];
}

/*
 * environnement
 */
Pelican::$config["IM_ROOT"] = "/soft/apc22/modules/GraphicsMagick/bin/gm convert";
Pelican::$config["ENVIRONNEMENT"] = strtolower($_ENV["TYPE_ENVIRONNEMENT"]);
Pelican::$config["TYPE_ENVIRONNEMENT"] = Pelican::$config["ENVIRONNEMENT"];

/*
 * debug
 */
Pelican::$config["SHOW_DEBUG"] = false;
Pelican::$config['DEBUG']['BAR'] = true;
Pelican::$config['DEBUG']['SHOW'] = true;
Pelican::$config['DEBUG']['PROFILING'] = true;

/*
 * Divers
 */
Pelican::$config["ENABLE_CACHE_SMARTY"] = false;
Pelican::$config["KEEP_IMAGE_FORMAT"] = false;
Pelican::$config['SECURITY']['CRYPT_KEY'] = 'skdgfdjkqsdh';

Pelican::$config["TRANSLATE_TRACE"] = true;

// LDAP
Pelican::$config['DIRECTORY_LDAP']['type'] = 'ldap';
Pelican::$config['DIRECTORY_LDAP']['filepath'] = Pelican::$config["DOCUMENT_INIT"].'/application/configs/local/ldap.properties';
Pelican::$config['LOG_LDAP']['filepath'] = Pelican::$config["VAR_ROOT"].'/logs/ndpldap.log';
Pelican::$config['PROFILS']['CONTRIBUTEUR'] = 'CONTRIBUTEUR';

/*
 * Security
 */
Pelican::$config ['server_configuration'] ['display_errors'] = 1;
Pelican::$config ['server_configuration'] ['error_reporting'] = E_ALL ^ E_NOTICE;

/*
 * Proxy
 */
Pelican::$config['PROXY']['HOST'] = 'relaishttp.sgppsa.com';
Pelican::$config['PROXY']['URL'] = 'http://' . Pelican::$config['PROXY']['HOST'];
Pelican::$config['PROXY']['PORT'] = '80';
Pelican::$config['PROXY']['LOGIN'] = 'mwpndp00';
Pelican::$config['PROXY']['PWD'] = 'ig59r2w1';
Pelican::$config['PROXY.CURL.OPTIONS'] = array(
	CURLOPT_PROXY          => Pelican::$config['PROXY']['URL'] . ':' . Pelican::$config['PROXY']['PORT'],
	CURLOPT_PROXYUSERPWD   => Pelican::$config['PROXY']['LOGIN'] . ':' . Pelican::$config['PROXY']['PWD'],
	CURLOPT_SSL_VERIFYPEER => false,
	CURLOPT_SSL_VERIFYHOST => false,
	CURLOPT_PROXYTYPE => 'CURLPROXY_HTTP',
);
Pelican::$config['wurflapi']['DATABASE_CURLOPTIONS'] = array(
    CURLOPT_PROXY          => Pelican::$config['PROXY.CURL.OPTIONS'][CURLOPT_PROXY],
    CURLOPT_PROXYUSERPWD   => Pelican::$config['PROXY.CURL.OPTIONS'][CURLOPT_PROXYUSERPWD],
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
);
Pelican::$config['PROXY']['NO_PROXY_FOR'] = '/.inetpsa.com|localhost/'; // Regex


/*
 * CitroenId
 */
Pelican::$config["OPENID_MOCK_ENABLE"] = true;

/*
 * Google Tag Manager
 */
Pelican::$config['GTM']['brand'] = 'peugeot';
Pelican::$config['GTM']['siteTypeLevel1'] = 'ndp';
Pelican::$config['GTM']['internalSearchType'] = 'Internal';

/*
 * Adresse email à laquelle sont envoyées les alertes de sécurité (classe Pelican_Security)
 */
Pelican::$config['SECURITY_ALERT_EMAIL_TO'] = 'ndp-etudes@mpsa.com';
