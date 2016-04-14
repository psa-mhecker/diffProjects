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
Pelican::$config["HTTP_MEDIA"] = "media.ndp.vmamp.inetpsa.com";
Pelican::$config["HTTP_BACKEND"] = "admin.ndp.vmamp.inetpsa.com";

if ($_SERVER['HTTP_MEDIA']) {
    Pelican::$config["HTTP_MEDIA"] = $_SERVER['HTTP_MEDIA'];
}
/*
 * environnement
 */
Pelican::$config["IM_ROOT"] = "gm convert"; // linux
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
Pelican::$config['PROXY']['URL'] = 'http://http.internetpsa.inetpsa.com';
Pelican::$config['PROXY']['HOST'] = 'http.internetpsa.inetpsa.com';
Pelican::$config['PROXY']['PORT'] = '80';
Pelican::$config['PROXY']['LOGIN'] = 'mdendp00';
Pelican::$config['PROXY']['PWD'] = 'rcpel8z6';
Pelican::$config['PROXY.CURL.OPTIONS'] = array(
    CURLOPT_PROXY          => Pelican::$config['PROXY']['URL'] . ':' . Pelican::$config['PROXY']['PORT'], //'http://relaishttp.sgppsa.com:80',
    CURLOPT_PROXYUSERPWD   => Pelican::$config['PROXY']['LOGIN'] . ':' . Pelican::$config['PROXY']['PWD'], //'mdendp00:rcpel8z6',
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
Pelican::$config['SECURITY_ALERT_EMAIL_TO'] = 'jerome.forestier@mpsa.com';

ini_set('xdebug.show_exception_trace', 'Off');
