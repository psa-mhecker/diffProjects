<?php
//error_reporting ( E_ALL );
Pelican::$config ["HTTP_HOST"] = $_SERVER ["HTTP_HOST"];

/** Base de données */
Pelican::$config ["DATABASE_HOST"] = "miranda";
Pelican::$config ["DATABASE_TYPE"] = "mysql";
Pelican::$config ["DATABASE_NAME"] = "cppv2_preprod";
Pelican::$config ["DATABASE_USER"] = "cppv2";
Pelican::$config ["DATABASE_PASS"] = "cppv2";
Pelican::$config ["DATABASE_PERSISTENTCONNECTION"] = false;

/** Hosts */
Pelican::$config ["HTTP_MEDIA"] = "media-cppv2.interakting.com";//"cppv2.preprod.media";
Pelican::$config ["FRONTSERVICE_HTTP"] = 'http://cppv2.preprod.services/';
/*
 * Pelican::$config['DNS_SHARDING'] = array( 'cppv2.dev.media1' ,
 * 'cppv2.dev.media2' );
 */

/** environnement */
Pelican::$config ["IM_ROOT"] = "/usr/bin/convert"; // linux
Pelican::$config ["ENVIRONNEMENT"] = 'DEV_SITE';
Pelican::$config ["TYPE_ENVIRONNEMENT"] = "preprod";
Pelican::$config ["DEBUG_HOST_NAME"] = $hostname;
Pelican::$config ["DEBUG_REMOTE_IP"] = "";
Pelican::$config ["DEBUG_ALLOWED_SERVER_NAME"] = "dev-lnx-04";
Pelican::$config ["DEBUG_ALLOWED_IP"] = "";

/** Divers */
Pelican::$config ["ENABLE_CACHE_SMARTY"] = false;

Pelican::$config ["KEEP_IMAGE_FORMAT"] = false;

Pelican::$config ['SECURITY'] ['CRYPT_KEY'] = 'skdgfdjkqsdh';

Pelican::$config ['DEBUG'] ['BAR'] = true;
Pelican::$config ['DEBUG'] ['SHOW'] = true;
Pelican::$config ['DEBUG'] ['PROFILING'] = true;
