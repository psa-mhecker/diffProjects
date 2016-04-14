<?php
/* Configuration des batchs */
Itkg::$config['BATCH_CONFIGURATION'] = 'Citroen\Batch\Configuration';
/*
 * Fichier de configuration applicatif dédié : NDP
 */
Pelican::$config['PROJET'] = 'NDP';
Pelican::$config['FRANCAIS'] = 1;
Pelican::$config['ANGLAIS'] = 2;
Pelican::$config['ITALIEN'] = 3;
Pelican::$config['ESPAGNOL'] = 4;
Pelican::$config['ALLEMAND'] = 10;
Pelican::$config['NEEDERLANDAIS'] = 27;
Pelican::$config['POLONAIS'] = 29;
Pelican::$config['PORTUGAIS'] = 30;
Pelican::$config['ROUMAIN'] = 31;
Pelican::$config['TURC'] = 39;
Pelican::$config['HONGRIE'] = 22;

Pelican::$config['SITE_BO'] = 1;
Pelican::$config["SITE_MASTER"] = 2;
Pelican::$config["SITE_NAME"] = 'NDP';


/*
 * Template back office
 */
Pelican::$config['TEMPLATE_ADMIN_TRADUCTION'] = 290;
Pelican::$config['MENTION_LEGAL_TEMPLATE'] = 213;
Pelican::$config['TEMPLATE_ADMIN_CONTENT'] = 24;
Pelican::$config['TEMPLATE_ADMIN_PAGE'] = 28;
Pelican::$config['TEMPLATE_ADMIN_DEALERLOCATOR_DEVENIRAGENT'] = 75;
Pelican::$config['TEMPLATE_ADMIN_PAGETAGGAGE'] = 105;
Pelican::$config['TEMPLATE_PRE_HOME'] = 214;

/*
 * Valeurs Robots Référencement BO
 */
Pelican::$config['ROBOTS_SEO_DEFAULT'] = 2; // all
Pelican::$config['ROBOTS_SEO'][1] = t('VIDE');
Pelican::$config['ROBOTS_SEO'][2] = t('ALL');
Pelican::$config['ROBOTS_SEO'][3] = t('NONE');
Pelican::$config['ROBOTS_SEO'][4] = t('NOINDEX FOLLOW');
Pelican::$config['ROBOTS_SEO'][5] = t('INDEX NOFOLLOW');

Pelican::$config['ROBOTS_SEO_FO'][1] = '';
Pelican::$config['ROBOTS_SEO_FO'][2] = 'all';
Pelican::$config['ROBOTS_SEO_FO'][3] = 'none';
Pelican::$config['ROBOTS_SEO_FO'][4] = 'noindex, follow';
Pelican::$config['ROBOTS_SEO_FO'][5] = 'index, nofollow';

Pelican::$config['USER_AGENT_LIST'] = array(
    "Android" => "Mozilla/5.0 (Android; Mobile; rv:14.0) Gecko/14.0 Firefox/14.0",
    "Android Tablette" => "Opera/9.80 (Android 3.2.1; Linux; Opera Tablet/ADR-1111101157; U; en) Presto/2.9.201 Version/11.50",
    "Desktop" => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9",
    "Iphone" => "Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Version/3.0 Mobile/1A543a Safari/419.3",
    "Ipad" => "Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.10",
);

Pelican::$config['MOBILE_DEVICE_NOFONT'] = array(
0 => "GT-S5570", //Galaxy Mini
);


Pelican::$config["PAGE_ETAT"]['BROUILLON'] = 1;

Pelican::$config['EMAIL']['WEBMASTEUR_CENTRAL'] = 'Webmasteur central';

/*
 * Verification JS sur les champs obligatoires
 * Evolution definis par Citroen
 */
Pelican::$config['VERIF_JS'] = 0;

/*
 * Template BO
 */
Pelican::$config['TPL_GLOBAL'] = 150;
Pelican::$config['TPL_HOME'] = 151;
Pelican::$config['TPL_DIFFUSION'] = 293;
Pelican::$config['TPL_IMPORTEXPORT'] = 294;

Pelican::$config['ZONE_TEMPLATE_ID'] = array(
    'NAVIGATION_PUSH' => 1782,
    'MON_PROJET' => 1783,
    'SHOPPING_TOOLS' => 1784,
    'CTA_MAJEUR' => 1785,
    'CTA_MINEUR' => 1786,
    'AIDES' => 1787,
    'AUTRES_SITES' => 1788,
    'ABONNEMENTS' => 1789,
    'PLAN_DU_SITE' => 1790,
    'ELEMENTS_LEGAUX' => 1791,
    'CTA_MOBILE' => 1792,
    'CONFIGURATION' => 1793,
    'GENERATEUR_LEADS' => 1794,
    'ACTU_GALERIE_ZONE' => 1830,
    'SHOWROOM_ACCUEIL_SELECT_TEINTE' => 1963,
    'SHOWROOM_ACCUEIL_POINTS_FORTS' => 1965,
    'SHOWROOM_ACCUEIL_RECAP_MODELE' => 1975,
    'SHOWROOM_INT_SELECT_TEINTE' => 2030,
    'SHOWROOM_INT_POINTS_FORTS' => 2035,
    'SHOWROOM_INT_OUTILS' => 2161,
    'SHOWROOM_INT_RECAP_MODELE' => 2167,
    'SHOWROOM_OUTILS' => 2159,
    'RECHERCHE_PDV' => 1955,
    '404' => 1980,
    'COOKIE' => 2061,
    'LISTE_PROMOTIONS' => 2101,
    'PROMOTIONS' => 2106,
    'RECHERCHE' => 2314,
    'CAR_SELECTOR_RESULTATS' => 1930,
    'CONNEXION' => 2497,
    'HOME_BUSINESS' => 2147,
    'LIBELLE_HOME' => 3980,
    'PROMOTION_SHOWROOM' => 2106,
    'ACCUEIL_PROMOTION_SLIDESHOW' => 2399,
    'GALERIE_N2_VEHICULE' => 1860,
    'SLIDESHOW' => 2315,
    'INTERSTITIEL' => 4109,
    'ELIGIBILITE_LINK_MY_CITROEN' => 4110,
    'NDP_PF25_CAR_SELECTOR' => 4173,
);

Pelican::$config['TEMPLATE_PAGE'] = array(
    'GLOBAL' => 150,
    'HOME' => 1530,
    'CAR_SELECTOR' => 1538,
    'CONCEPT_CAR_DETAIL' => 222,
    'MASTER_PAGE_VEHICULES_N1' => 230,
    'MASTER_PAGE_VEHICULES_N2' => 232,
    'MASTER_PAGE_STANDARD_N2' => 257,
    'RESULTATS_RECHERCHE' => 233,
    'ACTU_DETAIL' => 236,
    'SHOWROOM_ACCUEIL' => 1533,
    '404' => 362,
    'TECHNOLOGIE_DETAIL' => 255,
    'SHOWROOM_INTERNE' => 259,
    'ACCUEIL_PROMOTION' => 270,
    'LISTE_PROMOTION' => 268,
    'DETAIL_PROMOTION' => 269,
    'MON_PROJET_SELECTION' => 278,
    'MON_PROJET_CONCESSIONS' => 279,
    'MON_PROJET_PREFERENCES' => 280,
    'MON_PROJET_CONSEILS' => 281,
    'ACTU_GALERIE' => 220,
    'COMPARATEUR' => 247,
    'GABARIT_BLANC' => 245,
    'FORFAIT_ENTRETIEN' => 285,
    'PLAN_DU_SITE' => 367,
    'NDP_G29_SERVICE_CONNECTE' => 379,
    'NDP_TP_SHOWROOM' => 1533,
    'NDP_MASTER_PAGE' => 366
);

Pelican::$config['ZONE'] = array(
    'HEADER' => 569,
    'PROMOTION' => 570,
    'FOOTER' => 578,
    'CONFIGURATION' => 621,
    'CONTENT_GRAND_VISUEL' => 636,
    'CONTENUS_RECOMMANDES' => 639,
    'VISUEL_CINEMASCOPE_CONCEPT_CAR' => 645,
    'ONGLET' => 652,
    'STICKYBAR' => 653,
    'ACTU_DETAIL' => 654,
    'FINITIONS' => 658,
    'SELECTEUR_DE_TEINTE' => 667,
    'SELECTEUR_DE_TEINTE_AUTO' => 683,
    'POINTS_FORTS' => 669,
    'VISUEL_TECHNOLOGIE' => 679,
    'OUTILS' => 672,
    'RECAPITULATIF_MODELE' => 674,
    'CONTRATS_SERVICE' => 694,
    'ACCCORDEON' => 699,
    'EQUIPEMENTS_CARACTERISTIQUES_TECHNIQUES' => 668,
    'SELECTION_VEHICULE' => 700,
    'STICKYBAR_PROMO' => 702,
    'AUTRES_PROMOTIONS' => 704,
    'MON_PROJET_MENU' => 711,
    'POINT_DE_VENTE' => 666,
    'CONNEXION' => 719,
    'CONCESSION_VNAV' => 713,
    'VEHICULES_NEUF' => 661,
    'SIMULATEUR_FINANCEMENT' => 730,
    'CAR_SELECTOR_RESULTATS' => 662,
    'RECAPITULATIF_MODELE' => 674,
    'ACCESSOIRES' => 660,
    'RESULTATS_RECHERCHE' => 649,
    'FORMULAIRE' => 740,
    'SLIDESHOW_AUTO' => 744,
    'ESSAYER' => 725,
    'MUR_MEDIA' => 664,
    '2COLONNES_MIXTE_ENRICHI' => 688,
    'SLIDESHOW' => 657,
    'SLIDESHOW_OFFRES' => 673,
    'EDITO' => 650,
    'DRAG_DROP' => 651,
    'ACCESSOIRES' => 660,
    'VEHICULES_NEUF' => 661,
    'OUTILS_CHOIX' => 733,
    'REMONTE_RX' => 696,
    'REMONTE_RX_HOME' => 632,
    'SIMULATEUR_FINANCEMENT' => 730,
    'OVERVIEW' => 681,
    'RECAPITULATIF_MODELE' => 674,
    'PAGER_SHOWROOM' => 708,
    'SLIDESHOW_AUTO' => 744,
    'ELIGIBILITE_LINK_MY_CITROEN' => 753,
    'MOSAIQUE' => 692,
    'CONTENUS_RECOMMANDES_SHOWROOM' => 677,
    'NDP_PC5_1_COLONNE' => 776,
    'NDP_PT20_MASTERPAGE' => 811,
    'NDP_PC7_2_COLONNES' => 781,
    'NDP_PC12_3_COLONNES' => 760,
    'NDP_PC68_1_ARTICLE_2_3_VISUELS' => 766,
    'NDP_PC69_2_COLONNES_MIXTES' => 767,
    'NDP_PC9_1_ARTICLE_1_VISUEL' => 752,
    'NDP_PF6_DRAG_DROP' => 786,
    //'NDP_PN21_USP_FULL' =>
    //'NDP_PC78_USP_MOSAIQUE' => 
    'NDP_PC79_MUR_MEDIA_MANUEL' => 816
);

/*
 *  TODO : Ajouter les tranches de recherche de media
 */
Pelican::$config['ZONE_MEDIA_SHOWROOM'] = array(
    Pelican::$config['ZONE']['NDP_PC5_1_COLONNE'],
    Pelican::$config['ZONE']['NDP_PC7_2_COLONNES'],
    Pelican::$config['ZONE']['NDP_PC12_3_COLONNES'],
    Pelican::$config['ZONE']['NDP_PC68_1_ARTICLE_2_3_VISUELS'],
    Pelican::$config['ZONE']['NDP_PC69_2_COLONNES_MIXTES'],
    Pelican::$config['ZONE']['NDP_PC9_1_ARTICLE_1_VISUEL'],
    Pelican::$config['ZONE']['NDP_PF6_DRAG_DROP'],
    //Pelican::$config['ZONE']['NDP_PN21_USP_FULL'],
    //Pelican::$config['ZONE']['NDP_PC78_USP_MOSAIQUE'],
    Pelican::$config['ZONE']['NDP_PC79_MUR_MEDIA_MANUEL']
);

Pelican::$config['ZONE_MEDIA_USP_PC78'] = array(
    Pelican::$config['ZONE']['NDP_PC5_1_COLONNE'],
    Pelican::$config['ZONE']['NDP_PC7_2_COLONNES'],
    Pelican::$config['ZONE']['NDP_PC12_3_COLONNES'],
    Pelican::$config['ZONE']['NDP_PC68_1_ARTICLE_2_3_VISUELS'],
    Pelican::$config['ZONE']['NDP_PC69_2_COLONNES_MIXTES'],
    Pelican::$config['ZONE']['NDP_PC9_1_ARTICLE_1_VISUEL'],
    Pelican::$config['ZONE']['NDP_PF6_DRAG_DROP'],
);
/*

*  TODO : Ajouter les gabarits de recherche de media

*/

Pelican::$config['TEMPLATE_PAGE_SHOWROOM'] = array(
    Pelican::$config['TEMPLATE_PAGE']['NDP_TP_SHOWROOM'],
    1515, 1015, 1533 // gabarit sprint2
);

Pelican::$config['AREA'] = array(
    'DYNAMIQUE' => 150,
    'MAIN'        => 112,
    'HEADER'    => 121,
    'FOOTER'    => 122,
);

Pelican::$config['CONTENT_TYPE_ID'] = array(
    'NDP_CNT_ENGAGEMENT' => 72,
    'NDP_CNT_SLIDESHOW' => 73,
    'NDP_CNT_FORM' => 69,
    'NDP_CNT_PDV' => 70,
    'NDP_CNT_ACTU' => 74
);

/*
 * Association des zone_template_id des blocs Showroom interne avec leur bloc correspondant dans le Showroom Interne
 */
Pelican::$config['SHOWROOM_ZT_ASSOCIATION'][Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_INT_SELECT_TEINTE']] = Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_ACCUEIL_SELECT_TEINTE'];
Pelican::$config['SHOWROOM_ZT_ASSOCIATION'][Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_INT_POINTS_FORTS']] = Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_ACCUEIL_POINTS_FORTS'];
Pelican::$config['SHOWROOM_ZT_ASSOCIATION'][Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_INT_RECAP_MODELE']] = Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_ACCUEIL_RECAP_MODELE'];
Pelican::$config['SHOWROOM_ZT_ASSOCIATION'][Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_INT_OUTILS']] = Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_OUTILS'];

/*
 * ******************
 */
Pelican::$config['TYPE_RESEAUX_SOCIAUX'] = array(
    'FACEBOOK' => 1,
    'TWITTER' => 2,
    'YOUTUBE' => 3,
    'PINTEREST' => 4,
    'INSTAGRAM' => 5,
    'GOOGLE+' => 6,
    'FLICKR' => 7,
    'AUTRE' => 8,
);

Pelican::$config['TYPE_RESEAUX_SOCIAUX_DETAIL']['ADDTHIS'] = array(
    1 => array(
            'value' => 'addthis_button_facebook',
    ),
    2 => array(
            'value' => 'addthis_button_twitter',
    ),
    /*3 => array(
            'value' => 'addthis_button_youtube'
    ),*/
    4 => array(
            'value' => 'addthis_button_pinterest',
    ),
    /*5 => array(
            'value' => 'addthis_button_instagram'
    ),*/
    6 => array(
            'value' => 'addthis_button_google_plusone_share',
    ),
    /*7 => array(
            'value' => 'addthis_button_flickr'
    ),*/
    8 => array(
            'value' => 'addthis_button_compact',
    ),
);

Pelican::$config['MODE_SHARER'] = array(
    0 => "classic",
    1 => "count_small",
    2 => "count_medium",
    3 => "count_full",
    4 => "count_bar",
    5 => "count_small_mob",
);

Pelican::$config['TRANCHE_FORMULAIRE_CONTACT'] = array(
    'PDV' => "Recherche points de vente",
);

Pelican::$config['MODE_AFFICHAGE'] = array(
    1 => "Ligne C",
    2 => "Ligne DS",
    0 => "Neutre",
);

Pelican::$config['TYPE_EXPAND'] = array(
    0 => "Standard",
    1 => "Véhicule",
);

Pelican::$config['TYPE_PICTO_CDS'] = array(
    "pictoplus" => t("PICTO_PLUS"),
    "pictoegale" => t("PICTO_EGALE"),
    "nopicto" => t("NO_PICTO"),
);

Pelican::$config['TRANCHE_COL']['MODE_AFF'] = array(
    'C' => t('LIGNE_C'),
    'DS' => t('LIGNE_DS'),
    'NEUTRE' => t('Neutre'),
);
/* Gestion des classes CSS à utiliser pour les différents modes d'affichage */
Pelican::$config['TRANCHE_COL']['MODE_AFF_CSS'] = array(
    'C' => 'c-skin',
    'DS' => 'ds',
    'NEUTRE' => '',
);
/* Gestion des classes CSS à utiliser pour les différents modes d'affichage */
Pelican::$config['TRANCHE_COL']['MODE_AFF_CSS_MOBILE'] = array(
    'C' => 'c-skin',
    'DS' => 'ds',
    'NEUTRE' => '',
);

Pelican::$config['TRANCHE_COL']['MODE_AFF_LIGNE'] = array(
    "1" => t("ONE_LINE"),
    "2" => t("TWO_LINE"),
);
Pelican::$config['TRANCHE_COL']['BLANK_SELF'] = array(
    "SELF" => "_self",
    "BLANK" => "_blank",
);
Pelican::$config['TRANCHE_COL']['GAUCHE_DROITE'] = array(
    "1" => t("GAUCHE"),
    "2" => t("DROITE"),
);
Pelican::$config['TRANCHE_COL']['YES_NO'] = array(
    "1" => t("OUI"),
    "0" => t("NON"),
);
Pelican::$config['TRANCHE_COL']['WEBMOB'] = array(
    0 => t('NE_PAS_AFFICHER'),
    1 => t('UNIQUEMENT_WEB_TABLETTE'),
    2 => t('UNIQUEMENT_MOBILE'),
    3 => t('WEB_TABLETTE_MOBILE'),
);
Pelican::$config['TRANCHE_COL']["WEB"] = array(
    0 => t('NE_PAS_AFFICHER'),
    1 => t('UNIQUEMENT_WEB_TABLETTE'),
);
Pelican::$config['TRANCHE_COL']["MOB"] = array(
    0 => t('NE_PAS_AFFICHER'),
    2 => t('UNIQUEMENT_MOBILE'),
);
Pelican::$config['TRANCHE_COL']["AFF_MODE_MENTION"] = array(
    "ROLL" => t("ROLL_OVER"),
    "POP_IN" => t("POP_IN"),
    "TEXT" => t("TEXTE_IN_PAGE"),
);
Pelican::$config['TRANCHE_COL']["AFF_MODE_MENTION2"] = array(
    1 => t("ROLL_OVER"),
    2 => t("POP_IN"),
);
Pelican::$config['IFRAME']["UNITE_LARGEUR"] = array(
    "px" => "px",
    "%" => "%",
);

Pelican::$config['SLIDESHOW']["MODE_AFF"] = array(
    'IMAGE' => t('Image'),
    'VIDEO' => t('VIDEO'),
    'FLASH' => t('FLASH'),
    'HTML5' => t('HTML5'),
);
Pelican::$config['MODE_GESTION'] = array(
    'PDV' => t('PAR_PDV'),
    'CP_CITY' => t('PAR_CP_CITY'),
    'PRODUCT' => t('PAR_PRODUCT'),
);

Pelican::$config['COULEUR_TYPO_TAB'][''] = array(
    0 => "",
);
Pelican::$config['COULEUR_CTA_TAB'][''] = array(
    0 => "",
);
Pelican::$config['COULEUR_TYPO_TAB'][t("LIGNE_C")] = array(
    1 => t("BLANC"),
    2 => t("NOIR"),
    3 => t("ROUGE"),
    4 => t("GRIS"),
);
Pelican::$config['COULEUR_TYPO_TAB'][t("LIGNE_DS")] = array(
    5 => t("CARMIN"),
    6 => t("CHAMPAGNE"),
    7 => t("NOIR"),
    8 => t("BLANC"),
);
Pelican::$config['COULEUR_TYPO'] = array(
    1 => t("BLANC"),
    2 => t("NOIR"),
    3 => t("ROUGE"),
    4 => t("GRIS"),
    5 => t("CARMIN"),
    6 => t("CHAMPAGNE"),
    7 => t("NOIR"),
    8 => t("BLANC"),
);
Pelican::$config['COULEUR_CTA_TAB'][t("LIGNE_C")] = array(
    1 => t("ROUGE"),
    2 => t("BLEU"),
    3 => t("GRIS"),
);
Pelican::$config['COULEUR_CTA_TAB'][t("LIGNE_DS")] = array(
    4 => t("CARMIN"),
    5 => t("CHAMPAGNE"),
);
Pelican::$config['COULEUR_CTA'] = array(
    1 => t("ROUGE"),
    2 => t("BLEU"),
    3 => t("GRIS"),
    4 => t("CARMIN"),
    5 => t("CHAMPAGNE"),
);

/* FONCTION AJAX BACKEND */
Pelican::$config['BACKEND_AJAX'] = array(
    'Administration_TraductionBo_Controller/checkExistenceCleAction',
    'Administration_TraductionFo_Controller/checkExistenceCleAction',
);
/* CASE SENSITIVE TYPE TRANSLATION */
Pelican::$config['LABEL_CASE'] = array(
    0 => t("UPPERCASE"),
    1 => t("LOWERCASE"),
);

/* CAR SELECTOR : FILTRE TYPE 1 */
Pelican::$config['FILTRE_TYPE_1'] = array(
    0 => t("SILHOUETTE"),
    1 => t("ENERGIE"),
    2 => t("BOITE_VITESSE"),
    3 => t("PRIX"),
    4 => t("NB_PASSAGERS"),
    5 => t("CONSO"),
    6 => t("EMISSION_CO2"),
    7 => t("LONGUEUR_EXT"),
);
Pelican::$config['FILTRE_TYPE_DEFAULT_1'] = array(
    0,
    1,
    2,
    3,
    4,
    5,
    6,
    7,
);

Pelican::$config['FILTRE_TYPE_1_CORRESPONDANCE'] = array(
    0 => "SILHOUETTE",
    1 => "ENERGIE",
    2 => "BOITE_VITESSE",
    3 => "PRIX",
    4 => "NB_PASSAGERS",
    5 => "CONSO",
    6 => "EMISSION_CO2",
    7 => "LONGUEUR_EXT",
);

Pelican::$config['SOUS_FILTRE_TYPE_1']['SILHOUETTE'] = array(
    array(
        "identifiant" => "berlineShape",
        "name" => "BERLINE",
        "value" => 1,
    ),
    array(
        "identifiant" => "spaceShape",
        "name" => "MONOSPACE",
        "value" => 2,
    ),
    array(
        "identifiant" => "wagonShape",
        "name" => "BREAK",
        "value" => 3,
    ),
    array(
        "identifiant" => "x4Shape",
        "name" => "4X4",
        "value" => 4,
    ),
    array(
        "identifiant" => "truckShape",
        "name" => "FOURGON",
        "value" => 5,
    ),
    array(
        "identifiant" => "multiShape",
        "name" => "MULTISPACE",
        "value" => 6,
    ),
    array(
        "identifiant" => "transportShape",
        "name" => "TRANSPORT_PERSONNE",
        "value" => 7,
    ),
);

Pelican::$config['SOUS_FILTRE_TYPE_1']['ENERGIE'] = array(
    array(
        "identifiant" => "allEnergy",
        "name" => "TOUT",
        "value" => 1,
    ),
    array(
        "identifiant" => "gasoline",
        "name" => "ESSENCE",
        "value" => 2,
    ),
    array(
        "identifiant" => "diesel",
        "name" => "DIESEL",
        "value" => 3,
    ),
    array(
        "identifiant" => "hybrid",
        "name" => "HYBRIDE-DIESEL",
        "value" => 4,
    ),
    array(
        "identifiant" => "electric",
        "name" => "ELECTRIQUE",
        "value" => 5,
    ),
);

Pelican::$config['SOUS_FILTRE_TYPE_1']['BOITE_VITESSE'] = array(
    array(
        "identifiant" => "allGears",
        "name" => "TOUT",
        "value" => 1,
    ),
    array(
        "identifiant" => "manual",
        "name" => "MANUELLE",
        "value" => 2,
    ),
    array(
        "identifiant" => "auto",
        "name" => "AUTOMATIQUE",
        "value" => 3,
    ),
    array(
        "identifiant" => "transmission",
        "name" => "TRANSMISSION",
        "value" => 4,
    ),
);

/* CAR SELECTOR : FILTRE TYPE 2 */
Pelican::$config['FILTRE_TYPE_2'] = array(
    0 => t("CRITERE_1"),
    1 => t("CRITERE_2"),
    2 => t("PRIX"),
    3 => t("CRITERE_3"),
);
Pelican::$config['FILTRE_TYPE_DEFAULT_2'] = array(
    0,
    1,
    2,
    3,
);

/* CAR SELECTOR : FILTRE MOBILE */
Pelican::$config['FILTRE_MOBILE'] = array(
    0 => t("SILHOUETTE"),
    1 => t("PRIX"),
);
Pelican::$config['FILTRE_MOBILE_DEFAULT'] = array(
    0,
    1,
);

/* MEDIA FORMAT */
Pelican::$config['MEDIA_FORMAT_ID'] = array(
    'ACTUALITES_GRAND' => 30,
    'ACTUALITES_PETIT' => 31,
    'FILTRE_CAR_SELECTOR' => 32,
    'ACTUALITES_GALERIE' => 33,
    'ACTUALITES_GALERIE_MOBILE' => 34,
    'ACTUALITES_DETAIL' => 36,
    'SLIDESHOW_VISUEL' => 37,
    'NEW_CAR_VISUEL' => 38,
    'EXPAND_PUSH' => 39,
    'EXPAND_PAGE' => 40,
    'EXPAND_VEHICULE_X3' => 41,
    'EXPAND_VEHICULE_X4' => 42,
    'GAMME_VEHICULE' => 43,
    'GAMME_PUSH' => 44,
    'GAMME_AUTRES' => 45,
    'RECO' => 46,
    'CONCEPT_CAR' => 47,
    'GRAND_VISUEL' => 48,
    'ACTUALITES_MOBILE' => 49,
    'GRAND_VISUEL_MOBILE' => 50,
    'CONCEPT_CAR_MOBILE' => 51,
    'GAMME_PAGE' => 52,
    'GAMME_VEHICULE_MOBILE' => 53,
    'CAR_SELECTOR_RESULTATS' => 54,
    'CAR_SELECTOR_OTHER_CARS' => 55,
    'CAR_SELECTOR_RESULTATS_MOBILE' => 56,
    'CAR_SELECTOR_OTHER_CARS_MOBILE' => 57,
    'SLIDESHOW_OFFRES' => 58,
    'HISTOIRE_IMAGE_PORTRAIT' => 59,
    'HISTOIRE_IMAGE_PAYSAGE' => 60,
    'DISPO_SUR' => 61,
    'GRAND_CINEMASCOPE' => 62,
    'CINEMASCOPE' => 63,
    '16_9' => 64,
    'PETIT_CARRE' => 65,
    'GRAND_16_9' => 66,
    'PORTRAIT' => 67,
    'TRES_GRAND_16_9' => 68,
    'SHOPPING_PICTO' => 69,
    'WEB_1_COLONNE' => 70,
    'WEB_1_COLONNE_TEXTE_PICTO' => 71,
    'WEB_2_COLONNES' => 72,
    'WEB_2_COLONNES_MIXTE' => 73,
    'WEB_3_COLONNES' => 74,
    'WEB_4_COLONNES_ET_PLUS' => 75,
    'WEB_DRAG_DROP' => 76,
    'WEB_1_CINEMASCOPE' => 77,
    'WEB_2_VISUELS_16_9' => 78,
    'WEB_VISUEL_PORTRAIT_PLUS' => 79,
    'WEB_VISUEL_PORTRAIT_PLUS_16_9_EMPILES' => 80,
    'WEB_16_9_EMPILES' => 81,
    'WEB_16_9_EMPILES_PLUS_PORTRAIT' => 82,
    'WEB_2_VISUELS_FORMAT_CARRE' => 83,
    'WEB_2_VISUELS_FORMAT_PORTRAIT' => 84,
    'WEB_3_VISUEL_CARRE' => 85,
    'WEB_SLIDESHOW_PRINCIPAL' => 86,
    'WEB_SLIDESHOW_OFFRE' => 87,
    'WEB_GAMME' => 88,
    'WEB_2_COLONNES_TEL' => 89,
    'WEB_PUSH_MEDIA_' => 90,
    'WEB_CONTENU_RECOMMANDE' => 91,
    'WEB_GRAND_VISUEL' => 92,
    'WEB_ACTUALITE' => 93,
    'WEB_TECHNOLOGIE' => 94,
    'WEB_HISTOIRE' => 95,
    'WEB_CONCEPT_CAR' => 96,
    'WEB_CAR_SELECTOR_MASTER_N1_ET_N2' => 97,
    'WEB_EXPAND_VEHICULE' => 98,
    'WEB_DISPONIBLE_SUR' => 99,
    'WEB_FAQ_SOMMAIRE_' => 100,
    'WEB_MASTER_N1_STANDARD' => 101,
    'WEB_KEY_ACCOUNT_MANGER' => 102,
    'WEB_VEHICULE_SELECTIONNE' => 103,
    'MOBILE_1_COLONNE' => 104,
    'MOBILE_1_COLONNE_TEXTE_PLUS_PICTO' => 105,
    'MOBILE_2_COLONNES' => 106,
    'MOBILE_2_COLONNES_MIXTE' => 107,
    'MOBILE_3_COLONNES' => 108,
    'MOBILE_4_COLONNES_ET_PLUS' => 109,
    'MOBILE_1_CINEMASCOPE' => 110,
    'MOBILE_2_VISUELS_16_9' => 111,
    'MOBILE_VISUEL_PORTRAIT_PLUS' => 112,
    'MOBILE_VISUEL_PORTRAIT_PLUS_16_9_EMPILES' => 113,
    'MOBILE_16_9_EMPILES' => 114,
    'MOBILE_16_9_EMPILES_PLUS_PORTRAIT' => 115,
    'MOBILE_2_VISUELS_FORMAT_CARRE' => 116,
    'MOBILE_2_VISUELS_FORMAT_PORTRAIT' => 117,
    'MOBILE_3_VISUEL_CARRE' => 118,
    'MOBILE_SLIDESHOW_PRINCIPAL' => 119,
    'MOBILE_SLIDESHOW_OFFRE' => 120,
    'MOBILE_2_COLONNES_TEL' => 121,
    'MOBILE_PUSH_MEDIA_' => 122,
    'MOBILE_GRAND_VISUEL' => 123,
    'MOBILE_ACTUALITE' => 124,
    'MOBILE_TECHNOLOGIE' => 125,
    'MOBILE_HISTOIRE' => 126,
    'MOBILE_CONCEPT_CAR' => 127,
    'MOBILE_CAR_SELECTOR_MASTER_N1_ET_N2' => 128,
    'MOBILE_MASTER_N1_STANDARD' => 129,
    'MOBILE_KEY_ACCOUNT_MANGER' => 130,
    'MOBILE_VEHICULE_SELECTIONNE' => 131,
    'WEB_PUSH' => 132,
    'WEB_MENTION' => 133,
    'MOBILE_PUSH' => 134,
    'MOBILE_MENTION' => 135,
    'WEB_2_COLONNE_VISU_TEXT' => 136,
    'WEB_TECHNO_DETAIL' => 137,
    'MOBILE_2_COLONNE_VISU_TEXT' => 138,
    'MOBILE_TECHNO_DETAIL' => 139,
    'MOBILE_2_COLONNE_VISU_TEXT' => 140,
    'WEB_2_COLONNE_ENRICHI' => 141,
    'MOBILE_2_COLONNE_ENRICHI' => 142,
    'WEB_ACTUALITE_GRAND' => 143,
    'MOBILE_ACTUALITE_STANDARD' => 144,
    'WEB_ACTUALITE_PETIT' => 145,
    'WEB_CONCEPT_CAR_GALERIE' => 146,
    'MOBILE_CONCEPT_CAR_GALERIE' => 147,
    'WEB_POINT_FORT' => 148,
    'WEB_CONTENU_RECO_X_4' => 149,
    'WEB_MASTER_N1_VEHICULE' => 150,
    'WEB_MASTER_N2_VEHICULE' => 151,
    'WEB_RECAP_MODELE' => 152,
    'APP_MOBILE_STORE' => 153,
    'WEB_MOSAIQUE_300x395' => 158,
    'WEB_MOSAIQUE_600x395' => 159,
    'WEB_MOSAIQUE_600x790' => 160,
    'WEB_MOSAIQUE_300x790' => 161,
    'WEB_CONTRAT_SERVICE' => 162,
    'OFFRE_PLUS_MULTI' => 163,
    'INTERSTITIEL' => 165
);

/* MEDIA VIDEO */
Pelican::$config['FORMAT_VIDEO_PLAYER']['HISTOIRE'] ['WIDTH']    =    520;
Pelican::$config['FORMAT_VIDEO_PLAYER']['HISTOIRE'] ['HEIGHT']    =    267;

/* TABLEAU DES DEVISES POUR WS */
Pelican::$config['DEVISE'] = array(
    '€' => 'EUR',
    '$' => 'USD',
    '£' => 'GBP',
);
/* TABLEAU DES TYPE TAXES TTC - HT */
Pelican::$config['CASH_PRICE_TAXE'] = array(
    'CASH_PRICE_TTC' => t('CASH_PRICE_TTC'),
    'CASH_PRICE_HT' => t('CASH_PRICE_HT'),
);

/* Véhicule : Gammes */
Pelican::$config['VEHICULE_GAMME'] = array(
    'GAMME_LIGNE_DS' => 'GAMME_LIGNE_DS',
    'GAMME_LIGNE_C' => 'GAMME_LIGNE_C',
    'GAMME_VEHICULE_UTILITAIRE' => 'GAMME_VEHICULE_UTILITAIRE',
    'GAMME_BUSINESS' => 'GAMME_BUSINESS',
);
/* Véhicule : Gammes Trad FO */
Pelican::$config['VEHICULE_GAMME_FO'] = array(
    'GAMME_LIGNE_DS' => t('GAMME_LIGNE_DS_FO'),
    'GAMME_LIGNE_C' => t('GAMME_LIGNE_C_FO'),
    'GAMME_VEHICULE_UTILITAIRE' => t('GAMME_VEHICULE_UTILITAIRE_FO'),
    'GAMME_BUSINESS' => t('GAMME_BUSINESS_FO'),
);
/* Contraintes de suppression des référentiels de contenus */
Pelican::$config['CONTRAINTES_SUPPRESSION_REFERENTIEL']['OUTILS'] = array(
    array(
        'ZONE_ID' => 649,
        'CHAMP' => 'ZONE_PARAMETERS',
        'SEPARATEUR' => '|',
    ),
    array(
        'ZONE_ID' => 666,
        'CHAMP' => 'ZONE_PARAMETERS',
        'SEPARATEUR' => '|',
    ),
    array(
        'ZONE_ID' => 666,
        'CHAMP' => 'ZONE_LABEL2',
        'SEPARATEUR' => '|',
    ),
    array(
        'ZONE_ID' => 672,
        'CHAMP' => 'ZONE_TEXTE',
        'SEPARATEUR' => ',',
    ),
    array(
        'ZONE_ID' => 672,
        'CHAMP' => 'ZONE_TEXTE2',
        'SEPARATEUR' => ',',
    ),
);
Pelican::$config['CONTRAINTES_SUPPRESSION_REFERENTIEL']['CONTENUS_RECOMMANDES'] = array(
    array(
        'ZONE_ID' => 639,
        'CHAMP' => 'ZONE_LABEL2',
        'SEPARATEUR' => '|',
    ),
);
Pelican::$config['CONTRAINTES_SUPPRESSION_REFERENTIEL']['RESEAUX_SOCIAUX'] = array(
    array(
        'ZONE_ID' => 632,
        'CHAMP' => 'ZONE_TITRE2',
        'SEPARATEUR' => '',
    ),
    array(
        'ZONE_ID' => 632,
        'CHAMP' => 'ZONE_TITRE3',
        'SEPARATEUR' => '',
    ),
    array(
        'ZONE_ID' => 632,
        'CHAMP' => 'ZONE_TITRE4',
        'SEPARATEUR' => '',
    ),
    array(
        'ZONE_ID' => 632,
        'CHAMP' => 'ZONE_TITRE5',
        'SEPARATEUR' => '',
    ),
    array(
        'ZONE_ID' => 644,
        'CHAMP' => 'ZONE_TITRE2',
        'SEPARATEUR' => '',
    ),
    array(
        'ZONE_ID' => 644,
        'CHAMP' => 'ZONE_TITRE3',
        'SEPARATEUR' => '',
    ),
    array(
        'ZONE_ID' => 644,
        'CHAMP' => 'ZONE_TITRE4',
        'SEPARATEUR' => '',
    ),
    array(
        'ZONE_ID' => 625,
        'CHAMP' => 'ZONE_PARAMETERS',
        'SEPARATEUR' => '|',
    ),
);
Pelican::$config['CONTRAINTES_SUPPRESSION_REFERENTIEL']['THEME_TECHNOLOGIE'] = array(
    array(
        'ZONE_ID' => 679,
        'CHAMP' => 'ZONE_ATTRIBUT',
        'SEPARATEUR' => '|',
    ),
);
/* Visuel 3D */
Pelican::$config['VISUEL_3D_PARAM'] = array(
    "RATIO" => 1,
    "QUALITY" => 100,
    "CLIENT" => "CPPv2",
    "VIEW" => 002,
);

/* Gestion de l'utilisation des cookies */
Pelican::$config['ACCEPT_COOKIES'] = array(
    'FORCE_COOKIES' => t('FORCE_COOKIES'),
    'INFO_COOKIES' => t('INFO_COOKIES'),
    'ACCEPT_COOKIES' => t('ACCEPT_COOKIES'),
);

/* Durée des sessions de connexion */
Pelican::$config['DUREE_SESSION_CONNEXION'] = array(
    0 => t('SESSION_EN_COURS'),
    1 => t('1_JOUR'),
    2 => t('2_JOURS'),
    3 => t('3_JOURS'),
    4 => t('4_JOURS'),
    5 => t('5_JOURS'),
    6 => t('6_JOURS'),
    7 => t('7_JOURS'),
    15 => t('15_JOURS'),
);

/* Type de connexion via les reseaux sociaux */
Pelican::$config['CONNEXION_RESEAUX_SOCIAUX'] = array(
    1 => "Facebook",
    2 => "Twitter",
    3 => "Google",
);

Pelican::$config['VIEW_PARAMS'] = array('SITE');

Pelican::$config["DEFAULT_PAGE_PRIORITY"] = 0.7;


Pelican::$config['PROFILE']['ADMINISTRATEUR'] = 'ADMINISTRATEUR';
Pelican::$config['PROFILE']['READONLY'] = 'VISITEUR';
Pelican::$config['PROFILE']['TRANSLATOR'] = 'TRADUCTEUR';
Pelican::$config['PROFILE']['TRANSLATOR_EXCLUDE_STATES'] = array(4,5);

Pelican::$config['BRAND'] = array(
'AC'=>"citroen",
'AP'=>"peugeot",
'DS'=>"ds"
    );

/**
 * Ldap
 */
Pelican::$config['LDAP']['PRD'] = 'NDP';
Pelican::$config['LDAP']['BRAND'] = array(
        'AC',
        'AP',
        'DS'
    );
Pelican::$config['LDAP']['RIGHTS_LEVEL'] = array(
        'ADMINISTRATEUR' => 7,
        'WEBMASTER' => 6,
        'CONTRIBUTEUR' => 5,
        'CONTENTMASTER' => 4,
        'TRADUCTEUR' => 1
    );
Pelican::$config['LDAP']['BUSINESS_LIST'] = array(
        'ACC',
        'APV',
        'BUSINESS',
        'COMM',
        'FIN',
        'MARCHES',
        'MKTCOMM',
        'MKTINTERNET',
        'PRODUITS',
        'RH',
        'RLC',
        'VN',
        'VO'
    );

Pelican::$config['SITES_WEBSERVICES_PSA_URL'] = array(
    "VP" => "http://www.configurator.peugeot.tld/configure-your-peugeot/##MODEL##/##GR_BODY_STYLE##/step=##STEP##/vs=##LCDV16##/gcn=##GR_COMMERCIAL_NAME##/",
    "FICHE_ACCESSOIRES" => "http://accessoires.peugeot.fr/CSA/Redirection.aspx?lcdv=##LCDV6##&ref=##REF##&lang=##CULTURE##&consummer=##CONSUMMER##",
    "WEBSTORE" => "http://www.webstore.peugeot.tld/Results?mbd=##LCDV4####GR_BODY_STYLE##"
);

include('idCodePaysClient.ini.php');
include(Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/MediaFormat.php');
