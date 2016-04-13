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

/**
 * Base de donn�es
 */
Pelican::$config["DATABASE_HOST"] = "localhost";
Pelican::$config["DATABASE_TYPE"] = "mysql";
Pelican::$config["DATABASE_NAME"] = "CPW_DEV";
Pelican::$config["DATABASE_USER"] = "mdecpw00";
Pelican::$config["DATABASE_PASS"] = "mdecpw00";
Pelican::$config["DATABASE_PERSISTENTCONNECTION"] = false;

/**
 * Hosts
 */
Pelican::$config["HTTP_MEDIA"] = "media.ct.cppv2.dev.inetpsa.com";
Pelican::$config["HTTP_BACKEND"] = "admin.cppv2.dev.inetpsa.com";

/**
 * environnement
 */
Pelican::$config["IM_ROOT"] = "/usersdev/cpw/soft/GraphicsMagick/bin/gm convert";
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
 * Proxy
 */
Pelican::$config['PROXY.CURL.OPTIONS'] = '';


/**
 * googleKey
 */
Pelican::$config ['youtube']['key'] = 'AIzaSyBENQlleT3Afwv-pnAX7O8lsTF83HrfcxM';

// Facebook/Twitter/Google Connect
Pelican::$config['FACEBOOK']['appId'] = "226662007494188";
Pelican::$config['FACEBOOK']['secret'] = "82fd18d4a5654c8efd25d7bc7a5ff3a7";
Pelican::$config['GOOGLE']['clientId'] = "895361473414-bk6avk1kgihge47k8g9l7pt3ajpjbrs8.apps.googleusercontent.com";
Pelican::$config['GOOGLE']['clientSecret'] = "nNRuJ05miPM_g5rC9xGOPDe1";
Pelican::$config['GOOGLE']['developerKey'] = "AIzaSyB6jE9DY0nI7Lgr7-5qosnS9qkDfJOfp_g";
Pelican::$config['TWITTER']['consumerKey'] = "gCHCGe8kTkazRMx7eqCCig";
Pelican::$config['TWITTER']['consumerSecret'] = "0sQet9E9c0uxLuwtOFi5dbjvdEAT6l1flTy6NykO0";
Pelican::$config['TWITTER']['oauth_token'] = "82178459-7k73wL6KwV7NbJshm2U2vwkx2ISDPkZAnOqk1tdwN";
Pelican::$config['TWITTER']['oauth_token_secret'] = "ZTAEQ1UWaesmXAQSRCAehdq5uDRdleev2KRZyHaAmcXC4";

/**
 * Google Tag Manager
 */
Pelican::$config['GTM']['brand'] = 'CITROEN';
Pelican::$config['GTM']['siteTypeLevel1'] = 'CPPV2';


