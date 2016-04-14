<?php
/**
 * Fichier de configuration applicatif dédié : MENESR.
 */

/**VARIABLES COMMUNES*/
setlocale(LC_CTYPE, 'fr_FR');
setlocale(LC_TIME, 'fr_FR');
//Pelican::$config["LANGUE_ID_FR"] = 1;

//Pelican::$config["FORM_LIGNE_LISTE"] = "20";
//Pelican::$config["FORM_TITRE_LISTE"] = "Liste";
//Pelican::$config["FORM_TITRE_AJOUT"] = "Ajout";
//Pelican::$config["FORM_TITRE_MODIFICATION"] = "Edition";
//Pelican::$config["FORM_TITRE_SUPPRESSION"] = "Suppression";

/* Active ou non le nouveau systeme d'arborescence ExtJS */
Pelican::$config['BO_EXTJS_VERSION'] = '3.1.0';
Pelican::$config['BO_USE_EXTJS_TREE'] = false; //isset($_GET['pierre']) && $_GET['pierre']==1;

/* Active ou non le nouveau systeme d'arborescence des fonctionnalités */
Pelican::$config['BO_USE_EXTJS_FUNCTIONALITY_TREE'] = false; //isset($_GET['pierre']) && $_GET['pierre']==1;

/* Boutons utilisés */
Pelican::$config["MASTER_BUTTON"][] = "add";
Pelican::$config["MASTER_BUTTON"][] = "save";
Pelican::$config["MASTER_BUTTON"][] = "delete";
Pelican::$config["MASTER_BUTTON"][] = "back";
Pelican::$config["MASTER_BUTTON"][] = "addpage";
Pelican::$config["MASTER_BUTTON"][] = "deletepage";
Pelican::$config["MASTER_BUTTON"][] = "preview";
Pelican::$config["MASTER_BUTTON"][] = "up";
Pelican::$config["MASTER_BUTTON"][] = "down";
Pelican::$config["MASTER_BUTTON"][] = "mutualisation";

Pelican::$config["ACTION_ONLINE"] = "ON";

Pelican::$config["DEFAULT_STATE"] = 1;
Pelican::$config["TO_PUBLISH_STATE"] = 3;
Pelican::$config["PUBLISH_STATE"] = 4;
Pelican::$config["CORBEILLE_STATE"] = 5;
Pelican::$config["PAGE_GENERALE_YES"] = 1;

/***Mini-word vide**/
Pelican::$config["CNT_EMPTY"] = "<p> </p>\r\n";
Pelican::$config["ALT_EMPTY"] = "";

Pelican::$config["USAGE"]["CONTENT_ID"] = array(
    "#pref#_content_version_content" => "CONTENT_CONTENT_ID" ,
    "#pref#_page_zone_content" => "CONTENT_ID" ,
    "#pref#_page_version_content" => "CONTENT_ID" ,
    "#pref#_navigation" => "CONTENT_NAVIGATION_ID",
);
Pelican::$config["USAGE"]["PAGE_ID"] = array(
    "#pref#_navigation" => "PAGE_NAVIGATION_ID",
);
Pelican::$config["USAGE"]["TAG_ID"] = array(
    "#pref#_page_version" => "TAG_ID",
);

/******************* Portail ********************/
Pelican::$config["AUTH_ERROR_SESSION"] = 'auth_error';
/******************* Portail ********************/

Pelican::$config["ONGLET_CONTENT"] = 27;
Pelican::$config['ADMINISTRATION_SITE_ID'] = 1;
Pelican::$config['ADMIN_LOGIN'] = 'admin';
Pelican::$config["TPL_PAGE"] = 28;
Pelican::$config["TPL_CONTENT"] = 24;

/* nombres d'enregistrements affichés par défaut pour la recherche */
Pelican::$config["RESULT_STEP"] = 20;

//table page
Pelican::$config["PAGE_TABLE"] = array(
    "#pref#_page_order",
    "#pref#_page_version",
    "#pref#_page_version_content",
    "#pref#_page_version_media",
    "#pref#_page_zone",
    "#pref#_page_zone_content",
    "#pref#_page_zone_media",
    "#pref#_page_zone_multi",
    "#pref#_page_multi_zone",
    "#pref#_page_multi_zone_content",
    "#pref#_page_multi_zone_media",
    "#pref#_page_multi_zone_multi",
    "#pref#_rewrite",
    "#pref#_page", );

//table content
Pelican::$config["CONTENT_TABLE"] = array(
    "#pref#_content_version",
    "#pref#_content_version_content",
    "#pref#_content_version_media",
    "#pref#_content_zone",
    "#pref#_content_zone_media",
    "#pref#_content_zone_multi",
    "#pref#_rewrite",
    "#pref#_content", );

//view BO
Pelican::$config["VIEW_BO_EDITORIAL"] = "O_27";
Pelican::$config["VIEW_BO_MEDIATHEQUE"] = "O_28";
