<?php
Pelican::$config["LOGIN_ADMIN_BO"] = 'admin';
Pelican::$config["PWD_ADMIN_BO"] = 'adminAL83';
Pelican::$config["VISUEL_3D_PATH"] = 'http://visuel3d.citroen.com/V3DImage.ashx';
Pelican::$config['mail'] = array(
    'patrice' => 'patrice.chegard@businessdecision.com',
    'amandine' => 'amandine.lorentz@businessdecision.com',
    'farida' => 'farida.tighilt@businessdecision.com'
);

Pelican::$config["HTTP_HOST"] = $_SERVER["HTTP_HOST"];

/** Base de données */
Pelican::$config["DATABASE_HOST"] = "miranda";
Pelican::$config["DATABASE_TYPE"] = "mysql";
if ($_SERVER['HTTP_HOST'] == 'cppv2.integration.media' ) {
    Pelican::$config["DATABASE_NAME"] = "cppv2_integration";
} else {
    //Pelican::$config["DATABASE_NAME"] = "cppv2_recette";
    Pelican::$config["DATABASE_NAME"] = "cppv2_prod";
}
Pelican::$config["DATABASE_USER"] = "cppv2";
Pelican::$config["DATABASE_PASS"] = "cppv2";
Pelican::$config["DATABASE_PERSISTENTCONNECTION"] = false;

/**
 * MongoDb
 */
Pelican::$config["MONGODB_URI"] ="mongodb://10.255.252.248:27017";
Pelican::$config["MONGODB_PARAMS"] =array(
    //'username'=>'',
    //'password'=>'',
    'db'=>'cppv2_recette'
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
 * Hosts
 */
Pelican::$config["HTTP_MEDIA"] = "media-cppv2.interakting.com";
Pelican::$config["HTTP_BACKEND"] = "cppv2.preprod.backend";

if ($_SERVER['HTTP_MEDIA'] && $_SERVER['HTTP_HOST'] != 'cppv2.interakting.com') {
    Pelican::$config["HTTP_MEDIA"] = $_SERVER['HTTP_MEDIA'];
}

/**
 * environnement
 */
Pelican::$config["IM_ROOT"] = "/usr/bin/convert"; // linux
Pelican::$config["ENVIRONNEMENT"] = strtolower($_ENV["TYPE_ENVIRONNEMENT"]);
Pelican::$config["TYPE_ENVIRONNEMENT"] = strtolower($_ENV["TYPE_ENVIRONNEMENT"]);

/**
 * debug
 */
Pelican::$config["SHOW_DEBUG"] = true;
Pelican::$config['DEBUG']['BAR'] = true;
Pelican::$config['DEBUG']['SHOW'] = true;
Pelican::$config['DEBUG']['PROFILING'] = true;
Pelican::$config['PROFILING'] = true;

/**
 * Divers
 */
Pelican::$config["ENABLE_CACHE_SMARTY"] = false;
Pelican::$config["KEEP_IMAGE_FORMAT"] = false;
Pelican::$config['SECURITY']['CRYPT_KEY'] = 'skdgfdjkqsdh';

Pelican::$config["TRANSLATE_TRACE"] = true;

/**
 * Ldap
 */
Pelican::$config['DIRECTORY_LDAP']['type'] = 'xml';
Pelican::$config['DIRECTORY_LDAP']['filepath'] = Pelican::$config["DOCUMENT_INIT"] . '/public/backend/ldap.xml';
Pelican::$config['LOG_LDAP']['filepath'] = Pelican::$config["VAR_ROOT"] . '/logs/cpw.log';
Pelican::$config['PROFILS']['CONTRIBUTEUR'] = 'CONTRIBUTEUR';

/**
 * Proxy
 */
Pelican::$config['PROXY.CURL.OPTIONS'] = '';

/**
 * Security
 */
Pelican::$config ['server_configuration'] ['display_errors'] = 1;
Pelican::$config ['server_configuration'] ['error_reporting'] = E_ALL ^ E_NOTICE;

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
Pelican::$config['SECURITY_ALERT_EMAIL_TO'] = 'raphael.carles@businessdecision.com';
