<?php
/**
 * Fichier de configuration des différentes classes du framework : form, list, databasefw, Pelican_Hierarchy.
 */
Pelican::$config['LANGUE_ID'] = 1;

/*
 * Paramétrage du Charset des pages
 */
Pelican::$config["CHARSET"] = "UTF-8";

/* Utilisation des urls claires */
Pelican::$config["CLEAR_URL"] = true;

/**************************/
/****Pelican/Db.php****/
/**************************/
// Type de base utilisé
// "mysql"
// "postgresql"
// "oracle"
// "odbc"
// "informix"


// Constantes pour identifier le SQL à générer
Pelican::$config["DATABASE_INSERT"] = "INS";
Pelican::$config["DATABASE_UPDATE"] = "UPD";
Pelican::$config["DATABASE_DELETE"] = "DEL";
Pelican::$config["DATABASE_INSERT_ID"] = "-2";
Pelican::$config["ORACLE_INTERMEDIA"] = false;

/**********************/
/****List.php****/
/**********************/

// Nb de lignes à afficher par page
Pelican::$config["LIST_LIMIT_ROWS"] = 20;

// Nb de pages à afficher
Pelican::$config["LIST_MAX_LINKS"] = 9;

// Utilisation d'une classe pour l'ordre
Pelican::$config["LIST_USE_ORDER_CLASS"] = false;

/**********************/
/****Form.php****/
/**********************/

// Préfixe des noms de tables (pour les méthodes génériques)
Pelican::$config['FW_PREFIXE_TABLE'] = APP_PREFIXE;

// Suffixe des champs identifiants (pour les méthodes génériques)
Pelican::$config["FW_SUFFIXE_ID"] = "_id";

// Suffixe des champs de libellé (pour les méthodes génériques)
Pelican::$config["FW_SUFFIXE_LIBELLE"] = "_label";

// paramètres du miniword
Pelican::$config["FW_EDITOR_WIDTH"] = "600";
Pelican::$config["FW_EDITOR_HEIGHT"] = "400";

/**********************/
/****Backend.php****/
/**********************/
Pelican::$config["HISTORIQUE_MAX"] = 5;

Pelican::$config["MODE_ZONE_VIEW"] = 'top';

Pelican::$config["page"]["CONTENT_USAGE"] = array(
    "#pref#_page_zone_content" => "CONTENT_ID" ,
    "#pref#_page_version_content" => "CONTENT_ID",
);

/*****************/
/*     Ajax     */
/*****************/
Pelican::$config['AJAX_ADAPTER'] = 'Jquery';

/*****************/
Pelican::$config['MINIFY_CSS'] = false;
Pelican::$config['MINIFY_JS'] = false;
