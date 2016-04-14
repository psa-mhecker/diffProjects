<?php
/** Fichier de configuration de l'environnement de PRE PRODUCTION PSA **/
Pelican::$config["LOGIN_ADMIN_BO"] = 'admin';
Pelican::$config["PWD_ADMIN_BO"] = 'adminAL83';
Pelican::$config["VISUEL_3D_PATH"] = 'http://visuel3d.citroen.com/V3DImage.ashx';
Pelican::$config['mail'] = array(
);

Pelican::$config["HTTP_HOST"] = $_SERVER["HTTP_HOST"];

/*
 * Base de données
 */
Pelican::$config["DATABASE_HOST"] = "xx";
Pelican::$config["DATABASE_TYPE"] = "mysql";
Pelican::$config["DATABASE_NAME"] = "xxx";
Pelican::$config["DATABASE_USER"] = "xxx";
Pelican::$config["DATABASE_PASS"] = "xxx";
Pelican::$config["DATABASE_PERSISTENTCONNECTION"] = false;

/*
 * Hosts
 */
Pelican::$config["HTTP_MEDIA"] = "media.ct.cppv2.dev.inetpsa.com";
Pelican::$config["HTTP_BACKEND"] = "admin.cppv2.dev.inetpsa.com";

/*
 * environnement
 */
Pelican::$config["IM_ROOT"] = "/usersdev/cpw/soft/GraphicsMagick/bin/gm convert";
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

// LDAP
Pelican::$config['DIRECTORY_LDAP']['type'] = 'ldap';
Pelican::$config['DIRECTORY_LDAP']['filepath'] = dirname(__FILE__).'/ldap.properties';
Pelican::$config['LOG_LDAP']['filepath'] = Pelican::$config["VAR_ROOT"].'/logs/cpw.log';
Pelican::$config['PROFILS']['CONTRIBUTEUR'] = 'CONTRIBUTEUR';

/*
 * Security
 */
Pelican::$config ['server_configuration'] ['display_errors'] = 1;
Pelican::$config ['server_configuration'] ['error_reporting'] = E_ALL ^ E_NOTICE;

/*
 * Proxy
 */
Pelican::$config['PROXY']['URL'] = 'http://http.internetpsa.inetpsa.com';
Pelican::$config['PROXY']['PORT'] = '80';
Pelican::$config['PROXY']['LOGIN'] = 'mdecpw00';
Pelican::$config['PROXY']['PWD'] = 'svncpw00';
Pelican::$config['PROXY.CURL.OPTIONS'] = array(
        'CURLOPT_PROXY'          => 'http://http.internetpsa.inetpsa.com:80',
        'CURLOPT_PROXYUSERPWD'   => 'mdecpw00:svncpw00',
        'CURLOPT_SSL_VERIFYPEER' => false,
        'CURLOPT_SSL_VERIFYHOST' => false,
        'CURLOPT_PROXYTYPE' => 'CURLPROXY_HTTP',
    );

/*
 * ZONE_TEMPLATE_ID
 */
Pelican::$config['ZONE_TEMPLATE_ID'] = array(
    'SHOWROOM_INT_RECAP_MODELE' => 2359,
);

/*
 * Adresse email à laquelle sont envoyées les alertes de sécurité (classe Pelican_Security)
 */
Pelican::$config['SECURITY_ALERT_EMAIL_TO'] = 'cppv2-etudes@mpsa.com';
