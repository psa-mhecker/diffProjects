<?php
/**
 * Fichier de configuration locale
 *
 * @package Pelican
 * @subpackage config
 * @author Rapha�l Carles <rcarles@businessdecision.com>
 */

/**
 * Pour autoriser le debug
 * DEBUG_SERVER_NAME : nom du serveur, param�tre en environnement de d�veloppement pour afficher les d�bugs.
 * DEBUG_REMOTE_IP : IP autoris�e � afficher le debug, param�tre en environnement de production (IP vue de l'ext�rieur)
 */
Pelican::$config["DOCUMENT_INIT"] = str_replace("/application/configs/local.ini.php", "", str_replace("\\", "/", __FILE__));

if (! isset($_SERVER["HTTP_HOST"])) {
    $_SERVER["HTTP_HOST"] = 'localhost';
}
if (! isset($_SERVER["SERVER_NAME"])) {
    $_SERVER["SERVER_NAME"] = '';
}

Pelican::$config["HTTP_HOST"] = $_SERVER["HTTP_HOST"];

Pelican::$config["SHOW_DEBUG"] = false;

if (! empty($_SERVER["TYPE_ENVIRONNEMENT"])) {
    $_ENV["TYPE_ENVIRONNEMENT"] = $_SERVER["TYPE_ENVIRONNEMENT"];
}

switch ($_ENV["TYPE_ENVIRONNEMENT"]) {
    case "VM":
    case "DEV":
    case "PREPROD":
    case "RECETTE":
    case "INTEGRATION":
    case "PSA_INTEGRATION":
    case "PSA_RECETTE":
    case "dev":
    case "preprod":
    case "recette":
    case "integration":
    case "psa_integration":
    case "psa_preprod":
    case "psa_prod":
    case "psa_recette":
    case "psa_recette_projet":
    case "PSA_RECETTE_PROJET":
    case "psa_integration_vc":    
    case "psa_integration_prj":    
    case "PSA_INTEGRATION_VC":
    case "PSA_INTEGRATION_PRJ":
        {
            $include = $_ENV["TYPE_ENVIRONNEMENT"];
            include_once dirname(__FILE__) . '/local/' . $include . '.ini.php';
            break;
        }
    case "PSA_PREPRODUCTION":
    case "PSA_PRODUCTION":
		{  // Sur ces deux environnements, le fichier de conf est en local et non pas sur la baie
			$include = '/users/cpw00/web/application/configs/local/'.$_ENV['TYPE_ENVIRONNEMENT'];
                        include_once $include . '.ini.php';
			break;
		}
    default:
        {
            $include = 'default';
            include_once dirname(__FILE__) . '/local/' . $include . '.ini.php';
            break;
        }
}

if (! isset(Pelican::$config["DATABASE_PERSISTENTCONNECTION"])) {
    Pelican::$config["DATABASE_PERSISTENTCONNECTION"] = (Pelican::$config["DATABASE_TYPE"] == "oracle");
}

if (! isset(Pelican::$config["DATABASE_NAME_BO"]) && isset(Pelican::$config["DATABASE_NAME"])) {
    Pelican::$config["DATABASE_NAME_BO"] = Pelican::$config["DATABASE_NAME"];
}
if (Pelican::$config["BACK_OFFICE"]) {
    Pelican::$config["DATABASE_NAME"] = Pelican::$config["DATABASE_NAME_BO"];
}
