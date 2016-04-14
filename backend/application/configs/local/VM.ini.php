<?php
Pelican::$config["LOGIN_ADMIN_BO"] = 'admin';
Pelican::$config["PWD_ADMIN_BO"] = 'adminAL83';
Pelican::$config["VISUEL_3D_PATH"] = 'http://visuel3d.citroen.com/V3DImage.ashx';
Pelican::$config['mail'] = array(
    'patrice' => 'patrice.chegard@businessdecision.com',
);

Pelican::$config["HTTP_HOST"] = $_SERVER["HTTP_HOST"];

/*
 * Base de données
 */
Pelican::$config["DATABASE_HOST"] = 'localhost';
Pelican::$config["DATABASE_TYPE"] = 'mysqli';
Pelican::$config["DATABASE_NAME"] = 'psa-ndp';
Pelican::$config["DATABASE_USER"] = 'psa-ndp';
Pelican::$config["DATABASE_PASS"] = 'psa-ndp';

Pelican::$config["DATABASE_PERSISTENTCONNECTION"] = false;


/*
 * Hosts
 */
Pelican::$config["HTTP_MEDIA"] = "media.psa-ndp.com";
Pelican::$config["HTTP_BACKEND"] = "backend.psa-ndp.com";

if ($_SERVER['HTTP_MEDIA']) {
    Pelican::$config["HTTP_MEDIA"] = $_SERVER['HTTP_MEDIA'];
}
/*
 * environnement
 */
Pelican::$config["IM_ROOT"] = "gm convert"; // linux
Pelican::$config["PNGQUANT_ROOT"] = "/usr/local/bin/pngquant"; // linux
Pelican::$config["JPEGTRAN_ROOT"] = "/usr/local/bin/jpegtran"; // linux
Pelican::$config["ENVIRONNEMENT"] = strtolower($_ENV["TYPE_ENVIRONNEMENT"]);
Pelican::$config["TYPE_ENVIRONNEMENT"] = strtolower($_ENV["TYPE_ENVIRONNEMENT"]);

/*
 * debug
 */
Pelican::$config["SHOW_DEBUG"] = true;
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

/*
 * Ldap
 */
Pelican::$config['DIRECTORY_LDAP']['type'] = 'xml';
Pelican::$config['DIRECTORY_LDAP']['filepath'] = Pelican::$config["DOCUMENT_INIT"].'/public/backend/ldap.xml';
Pelican::$config['LOG_LDAP']['filepath'] = Pelican::$config["VAR_ROOT"].'/logs/cpw.log';
Pelican::$config['PROFILS']['CONTRIBUTEUR'] = 'CONTRIBUTEUR';

/*
 * Proxy
 */
Pelican::$config['PROXY.CURL.OPTIONS'] = '';

/*
 * Security
 */
Pelican::$config ['server_configuration'] ['display_errors'] = 1;
Pelican::$config ['server_configuration'] ['error_reporting'] = E_ALL ^ E_NOTICE;

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
Pelican::$config['SECURITY_ALERT_EMAIL_TO'] = 'raphael.carles@businessdecision.com';

ini_set('xdebug.show_exception_trace', 'Off');
