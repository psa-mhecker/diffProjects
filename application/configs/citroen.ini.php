<?php
/* Configuration des batchs */
Itkg::$config['BATCH_CONFIGURATION'] = 'Citroen\Batch\Configuration';
/**
 * Fichier de configuration applicatif dédié : CITROEN
 */
Pelican::$config['PROJET'] = 'Citroen';
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
Pelican::$config["SITE_MASTER"] = 5;

Pelican::$config['CODE_LANGUE_BO'][Pelican::$config['FRANCAIS']] = 'fr';
Pelican::$config['CODE_LANGUE_BO'][Pelican::$config['ANGLAIS']] = 'en';

Pelican::$config['URL_CITROEN_ADVISER']="http://www.citroen-advisor";
/**
 *   SiteId concernant les sites PSA en prod
 */
 
Pelican::$config['SITE']['SUISSE'] = 22;
if($_ENV["TYPE_ENVIRONNEMENT"] == 'preprod' || $_ENV["TYPE_ENVIRONNEMENT"] == 'PREPROD'){
    Pelican::$config['SITE']['SUISSE'] = 2;
}else if($_ENV["TYPE_ENVIRONNEMENT"] == 'PSA_RECETTE' || $_ENV["TYPE_ENVIRONNEMENT"] == 'psa_recette'){
    Pelican::$config['SITE']['SUISSE'] = 25;
}else if($_ENV["TYPE_ENVIRONNEMENT"] == 'PSA_RECETTE_PROJET' || $_ENV["TYPE_ENVIRONNEMENT"] == 'psa_recette_projet'){
    Pelican::$config['SITE']['SUISSE'] = 25;


}

/**
 * Template back office
 */
Pelican::$config['TEMPLATE_ADMIN_TRADUCTION'] = 290;
Pelican::$config['MENTION_LEGAL_TEMPLATE'] = 213;
Pelican::$config['TEMPLATE_ADMIN_CONTENT'] = 24;
Pelican::$config['TEMPLATE_ADMIN_PAGE'] = 28;

/**
 * Template back office
 */
Pelican::$config['TEMPLATE_PRE_HOME'] = 214;

/**
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
    "Ipad" => "Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.10"
);

Pelican::$config['MOBILE_DEVICE_NOFONT'] = array(
0 => "GT-S5570", //Galaxy Mini
);

// Variable PHP créée "à la main"
Pelican::$config['RECHERCHE_RATIO'] = array(
    '1.77' => t('SEIZE_NEUVIEME'),
    //'1.33' => t('QUATRE_TIER'),
    '1' => t('CARRE'),
    '2.35' => t('CINEMASCOPE'),
    '0.86' => t('PORTRAIT'),
    '2.96' => t('SLIDESHOW'),
	'4.3' => t('NEW_SLIDESHOW'),
	'1.09' => t('NEW_SLIDESHOW_MOBILE'),
    '1.3' => t('OFFRE'),
    '4.8' => t('GRAND_VISUEL'),
    '2.64' => t('CONCEPT_CAR_TECHNO'),
    '0.37' => t('300x790'),
    '1.51' => t('600x395'),
    '0.75' => t('300x395'),
    '0.76' => t('600x790'),
    '2.23' => t('interstitiel')
);

Pelican::$config['RECHERCHE_RATIO_DETAIL'] = array(
    '16_9' => array(
        'value' => '1.77',
        'lib' => t('SEIZE_NEUVIEME'),
        'marge'=>'5',
        'pixel'=>'200x141'
    ),
    '4_3' => array(
        'value' => '1.33',
        'lib' => t('QUATRE_TIER'),
        'marge'=>'5',
        'pixel'=>''
    ),
    'carre' => array(
        'value' => '1',
        'lib' => t('CARRE'),
        'marge'=>'5',
        'pixel'=>'80x80'
    ),
    'cinemascope' => array(
        'value' => '2.35',
        'lib' => t('CINEMASCOPE'),
        'marge'=>'5',
        'pixel'=>'1500x646'
    ),
    'portrait' => array(
        'value' => '0.86',
        'lib' => t('PORTRAIT'),
        'marge'=>'5',
        'pixel'=>'737x845'
    ),
    'slideshow' => array(
        'value' => '2.96',
        'lib' => t('SLIDESHOW'),
        'marge'=>'5',
        'pixel'=>'800x400'
    ),
	'new_chart_showroom' => array(
			'value' => '4.3',
			'lib' => t('NEW_SLIDESHOW'),
			'marge'=>'5',
			'pixel'=>'1250x290'
	),
	'new_chart_showroom_mobile' => array(
		'value' => '1.09',
		'lib' => t('NEW_SLIDESHOW_MOBILE'),
		'marge'=>'5',
		'pixel'=>'576x525'
	),
    'offre' => array(
        'value' => '1.3',
        'lib' => t('OFFRE'),
        'marge'=>'5',
        'pixel'=>'408x313'
    ),
    'grand_visuel' => array(
        'value' => '4.8',
        'lib' => t('GRAND_VISUEL'),
        'marge'=>'5',
        'pixel'=>'1440x300'
    ),
    'concept_car' => array(
        'value' => '2.64',
        'lib' => t('CONCEPT_CAR_TECHNO'),
        'marge'=>'5',
        'pixel'=>'1800x681'
    ),
    '300x790' => array(
        'value' => '0.39',
        'lib' => t('300x790'),
        'marge'=>'5',
        'pixel'=>'300x790'
    ),
     '600x395' => array(
        'value' => '1.51',
        'lib' => t('600x395'),
        'marge'=>'5',
        'pixel'=>'600x395'
    ),
    '600x790' => array(
        'value' => '0.76',
        'lib' => t('600x790'),
        'marge'=>'5',
        'pixel'=>'600x790'
    ),
    '300x395' => array(
        'value' => '0.75',
        'lib' => t('300x395'),
        'marge'=>'5',
        'pixel'=>'300x395'
    )
    ,
    'INTERSTITIEL' => array(
        'value' => '2.23',
        'lib' => t('interstitiel'),
        'marge'=>'5',
        'pixel'=>'990x434'
    ),
    '960x960' => array(
        'value' => '1',
        'lib' => t('960x960'),
        'marge'=>'5',
        'pixel'=>'960x960'
    )	
);

Pelican::$config["PAGE_ETAT"]['BROUILLON'] = 1;

Pelican::$config['EMAIL']['WEBMASTEUR_CENTRAL'] = 'Webmasteur central';


/*
 * Verification JS sur les champs obligatoires
 * Evolution definis par Citroen
 */
Pelican::$config['VERIF_JS'] = 0;

/**
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
    'SHOWROOM_ACCUEIL_SLIDESHOW'=>4104,
    'SHOWROOM_INT_SELECT_TEINTE' => 2030,
    'SHOWROOM_INT_POINTS_FORTS' => 2035,
    'SHOWROOM_INT_OUTILS' => 2161,
    'SHOWROOM_INT_RECAP_MODELE' => 2167,
    'SHOWROOM_OUTILS' => 2159,
	'ACCEUIL_OUTILS' => 2352,
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
    'EXPANDGAMME'=> 4226,
	'OUTILS'=> 4231,
	'CONTENU_TEXT_CTA'=> 1865,
);

Pelican::$config['TEMPLATE_PAGE'] = array(
    'GLOBAL' => 150,
    'HOME' => 151,
    'CAR_SELECTOR' => 218,
    'CONCEPT_CAR_DETAIL' => 222,
    'MASTER_PAGE_VEHICULES_N1' => 230,
    'MASTER_PAGE_VEHICULES_N2' => 232,
    'MASTER_PAGE_STANDARD_N2' => 257,
    'RESULTATS_RECHERCHE' => 233,
    'ACTU_DETAIL' => 236,
    'SHOWROOM_ACCUEIL' => 252,
    '404' => 253,
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
    'PLAN_DU_SITE' => 288,
    'CITROEN_SOCIAL' => 175,
    'GABARIT_BLANC_NO_FOOTER_HEADER' => 292,
    'POINT_DE_VENTE_IFRAME' => 293,
);
Pelican::$config['TEMPLATE_PAGE_CODE'] = array_flip(Pelican::$config['TEMPLATE_PAGE']);

Pelican::$config['TEMPLATE_PAGE_EXCLUDE_FROM_STICKY'] = array(
    Pelican::$config['TEMPLATE_PAGE']['ACCUEIL_PROMOTION'],
    Pelican::$config['TEMPLATE_PAGE']['LISTE_PROMOTION'],
    Pelican::$config['TEMPLATE_PAGE']['FORFAIT_ENTRETIEN'],
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
    'CONCESSION_VNAV'=>713,
	'VEHICULES_NEUF'=>661,
    'SIMULATEUR_FINANCEMENT'=>730,
    'CAR_SELECTOR_RESULTATS'=>662,
	'RECAPITULATIF_MODELE'=>674,
    'ACCESSOIRES'=>660,
    'RESULTATS_RECHERCHE'=>649,
    'FORMULAIRE'=>740,
    'SLIDESHOW_AUTO'=>744,
    'ESSAYER'=>725,
    'MUR_MEDIA'=>664,
    '2COLONNES_MIXTE_ENRICHI'=>688,
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
    'DISPO_SUR'=>659,
    'EXPANDGAMME'=> 762,
);

Pelican::$config['ZONE_CODE'] = array_flip(Pelican::$config['ZONE']);

Pelican::$config['AREA'] = array(
    'DYNAMIQUE' => 150,
	'MAIN'		=> 112,
	'HEADER'	=> 121,
	'FOOTER'	=> 122,
	'CORPS_PAGE'	=> 148,
	
);

Pelican::$config["HERITABLE_AREA"] = array(
    150
);

Pelican::$config['CONTENT_TYPE_ID'] = array(
    'ACTUALITE' => 8,
    'FAQ' => 11,
    'HISTOIRE' => 12,
    'FORFAIT_ENTRETIEN' => 13
);

/*
 * Association des zone_template_id des blocs Showroom interne avec leur bloc correspondant dans le Showroom Interne
 */
Pelican::$config['SHOWROOM_ZT_ASSOCIATION'][Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_INT_SELECT_TEINTE']] = Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_ACCUEIL_SELECT_TEINTE'];
Pelican::$config['SHOWROOM_ZT_ASSOCIATION'][Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_INT_POINTS_FORTS']] = Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_ACCUEIL_POINTS_FORTS'];
Pelican::$config['SHOWROOM_ZT_ASSOCIATION'][Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_INT_RECAP_MODELE']] = Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_ACCUEIL_RECAP_MODELE'];
Pelican::$config['SHOWROOM_ZT_ASSOCIATION'][Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_INT_OUTILS']] = Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_OUTILS'];

/**
 * ******************
 */

Pelican::$config['TYPE_RESEAUX_SOCIAUX'] = array(
    'FACEBOOK' => 1,
    'TWITTER' => 2,
    'YOUTUBE' => 3,
    'PINTEREST' => 4,
    'INSTAGRAM' => 5,
    'GOOGLEPLUS' => 6,
    'FLICKR' => 7,
    'AUTRE' => 8
);

Pelican::$config['YOUTUBE'] = 3;

Pelican::$config['TYPE_RESEAUX_SOCIAUX_DETAIL']['ADDTHIS'] = array(
    1 => array(
            'value' => 'addthis_button_facebook'
    ),
    2 => array(
            'value' => 'addthis_button_twitter'
    ),
    /*3 => array(
            'value' => 'addthis_button_youtube'
    ),*/
    4 => array(
            'value' => 'addthis_button_pinterest'
    ),
    /*5 => array(
            'value' => 'addthis_button_instagram'
    ),*/
    6 => array(
            'value' => 'addthis_button_google_plusone_share'
    ),
    /*7 => array(
            'value' => 'addthis_button_flickr'
    ),*/
    8 => array(
            'value' => 'addthis_button_compact'
    )
);

Pelican::$config['MODE_SHARER'] = array(
    0 => "classic",
    1 => "count_small",
    2 => "count_medium",
    3 => "count_full",
    4 => "count_bar",
    5 => "count_small_mob"
);

Pelican::$config['TRANCHE_FORMULAIRE_CONTACT'] = array(
    'PDV' => "Recherche points de vente"
);

Pelican::$config['MODE_AFFICHAGE'] = array(
    1 => "Ligne C",
    2 => "Ligne DS",
    0 => "Neutre"
);

Pelican::$config['TYPE_EXPAND'] = array(
    0 => "Standard",
    1 => "Véhicule"
);

Pelican::$config['TYPE_PICTO_CDS'] = array(
    "pictoplus" => t("PICTO_PLUS"),
    "pictoegale" => t("PICTO_EGALE"),
    "nopicto" => t("NO_PICTO")
);

Pelican::$config['TRANCHE_COL']['MODE_AFF'] = array(
    'C' => t('LIGNE_C'),
    'DS' => t('LIGNE_DS'),
    'NEUTRE' => t('Neutre'),
     'NOUVEAU_SHOWROOM'=>t('NOUVEAU_SHOWROOM')
);
/* Gestion des classes CSS à utiliser pour les différents modes d'affichage */
Pelican::$config['TRANCHE_COL']['MODE_AFF_CSS'] = array(
    'C' => 'c-skin',
    'DS' => 'ds',
    'NEUTRE' => ''
);
/* Gestion des classes CSS à utiliser pour les différents modes d'affichage */
Pelican::$config['TRANCHE_COL']['MODE_AFF_CSS_MOBILE'] = array(
    'C' => 'c-skin',
    'DS' => 'ds',
    'NEUTRE' => ''
);

Pelican::$config['TRANCHE_COL']['MODE_AFF_LIGNE'] = array(
    "1" => t("ONE_LINE"),
    "2" => t("TWO_LINE")
);
Pelican::$config['TRANCHE_COL']['BLANK_SELF'] = array(
    "SELF" => "_self",
    "BLANK" => "_blank"
);
Pelican::$config['TRANCHE_COL']['GAUCHE_DROITE'] = array(
    "1" => t("GAUCHE"),
    "2" => t("DROITE")
);
Pelican::$config['TRANCHE_COL']['YES_NO'] = array(
    "1" => t("OUI"),
    "0" => t("NON")
);
Pelican::$config['TRANCHE_COL']['WEBMOB'] = array(
    0 => t('NE_PAS_AFFICHER'),
    1 => t('UNIQUEMENT_WEB_TABLETTE'),
    2 => t('UNIQUEMENT_MOBILE'),
    3 => t('WEB_TABLETTE_MOBILE')
);
Pelican::$config['TRANCHE_COL']["WEB"] = array(
    0 => t('NE_PAS_AFFICHER'),
    1 => t('UNIQUEMENT_WEB_TABLETTE')
);
Pelican::$config['TRANCHE_COL']["MOB"] = array(
    0 => t('NE_PAS_AFFICHER'),
    2 => t('UNIQUEMENT_MOBILE')
);
Pelican::$config['TRANCHE_COL']["AFF_MODE_MENTION"] = array(
    "ROLL" => t("ROLL_OVER"),
    "POP_IN" => t("POP_IN"),
    "TEXT" => t("TEXTE_IN_PAGE")
);
Pelican::$config['TRANCHE_COL']["AFF_MODE_MENTION2"] = array(
    1 => t("ROLL_OVER"),
    2 => t("POP_IN")
);
Pelican::$config['IFRAME']["UNITE_LARGEUR"] = array(
    "px" => "px",
    "%" => "%"
);

Pelican::$config['SLIDESHOW']["MODE_AFF"] = array(
    'IMAGE' => t('Image'),
    'VIDEO' => t('VIDEO'),
    'FLASH' => t('FLASH'),
    'HTML5' => t('HTML5')
);
Pelican::$config['POINTS_FORTS_LIGHT']["MODE_AFF"] = array(
    'GRAND_VISUEL' => t('GRAND_VISUEL'),
    '2_COLONNE_MIXTE' => t('2_COLONNE_MIXTE'),
    'SUPERPOSITION_VISUELS' => t('SUPERPOSITION_VISUELS'),
    '3_COLONNE_MIXTE' => t('3_COLONNE_MIXTE')
);

Pelican::$config['TRANCHE_COL']["POSITION_TEXTE"] = array(
	'WEB'=>array(
		'GAUCHE_HAUT' => t('GAUCHE_HAUT'),
		'GAUCHE_MILIEU' => t('GAUCHE_MILIEU'),
		'GAUCHE_BAS' => t('GAUCHE_BAS'),
		'DROITE_HAUT' => t('DROITE_HAUT'),
		'DROITE_MILIEU' => t('DROITE_MILIEU'),
		'DROITE_BAS' => t('DROITE_BAS')),
	'MOBILE'=>array(
		'HAUT' => t('HAUT'),
		'BAS' => t('BAS')),
	'GENERAL'=>array(
		'GAUCHE' => t('GAUCHE'),
		'DROITE' => t('DROITE'))	
		
);

Pelican::$config['VEHICULES']["AFFICHER_SUR"] = array(1 => t('EXPAND'), 2 => t('HOME_PAGE'),3=>t('MASTER_PAGE'));


Pelican::$config['MODE_GESTION'] = array(
    'PDV' => t('PAR_PDV'),
    'CP_CITY' => t('PAR_CP_CITY'),
    'PRODUCT' => t('PAR_PRODUCT')
);

Pelican::$config['COULEUR_TYPO_TAB'][''] = array(
    0 => ""
);
Pelican::$config['COULEUR_CTA_TAB'][''] = array(
    0 => ""
);
Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS"] = "'D71F85','6FD4E4','C9DD03','BB4429','FF7F32','C3D3DF','CED60F','DFE383','C2B000','5F4B3D','F0D5A6','94420E','007C92','BABC18','40748E','A29791'";
Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS2"] = "'B29FA4','AEA79F','72D7E7','ACDEE6','447EC8','491C3E','6AADE4','A0CFEB','85CDDB','E6D5D4','CEC6C0','0081AB','BED600','009ABF','C6DAE7','85CDDB'";
Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS3"] = "'000000','FFFFFF','646464','AFADC3','C6D6E8','766A65','DAE351','68B9AD','868689','8A8D32','00AECB','FF5A00','91004B','C0B8B0','E14628','4F304A','E6E0DB','AD9D91','302725','AD0040'";

Pelican::$config['COULEUR_TYPO_TAB'][t("LIGNE_C")] = array(
    1 => t("BLANC"),
    2 => t("NOIR"),
    3 => t("ROUGE"),
    4 => t("GRIS")
);
Pelican::$config['COULEUR_TYPO_TAB'][t("LIGNE_DS")] = array(
    5 => t("CARMIN"),
    6 => t("CHAMPAGNE"),
    7 => t("NOIR"),
    8 => t("BLANC")
);
Pelican::$config['COULEUR_TYPO'] = array(
    1 => t("BLANC"),
    2 => t("NOIR"),
    3 => t("ROUGE"),
    4 => t("GRIS"),
    5 => t("CARMIN"),
    6 => t("CHAMPAGNE"),
    7 => t("NOIR"),
    8 => t("BLANC")
);
Pelican::$config['COULEUR_CTA_TAB'][t("LIGNE_C")] = array(
    1 => t("ROUGE"),
    2 => t("BLEU"),
    3 => t("GRIS")
);
Pelican::$config['COULEUR_CTA_TAB'][t("LIGNE_DS")] = array(
    4 => t("CARMIN"),
    5 => t("CHAMPAGNE")
);
Pelican::$config['COULEUR_CTA'] = array(
    1 => t("ROUGE"),
    2 => t("BLEU"),
    3 => t("GRIS"),
    4 => t("CARMIN"),
    5 => t("CHAMPAGNE")
);

/* FONCTION AJAX BACKEND */
Pelican::$config['BACKEND_AJAX'] = array(
    'Administration_TraductionBo_Controller/checkExistenceCleAction',
    'Administration_TraductionFo_Controller/checkExistenceCleAction'
);
/* CASE SENSITIVE TYPE TRANSLATION */
Pelican::$config['LABEL_CASE'] = array(
    0 => t("UPPERCASE"),
    1 => t("LOWERCASE")
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
    7 => t("LONGUEUR_EXT")
);
Pelican::$config['FILTRE_TYPE_DEFAULT_1'] = array(
    0,
    1,
    2,
    3,
    4,
    5,
    6,
    7
);

Pelican::$config['FILTRE_TYPE_1_CORRESPONDANCE'] = array(
    0 => "SILHOUETTE",
    1 => "ENERGIE",
    2 => "BOITE_VITESSE",
    3 => "PRIX",
    4 => "NB_PASSAGERS",
    5 => "CONSO",
    6 => "EMISSION_CO2",
    7 => "LONGUEUR_EXT"
);

Pelican::$config['SOUS_FILTRE_TYPE_1']['SILHOUETTE'] = array(
    array(
        "identifiant" => "berlineShape",
        "name" => "BERLINE",
        "value" => 1
    ),
    array(
        "identifiant" => "spaceShape",
        "name" => "MONOSPACE",
        "value" => 2
    ),
    array(
        "identifiant" => "wagonShape",
        "name" => "BREAK",
        "value" => 3
    ),
    array(
        "identifiant" => "x4Shape",
        "name" => "4X4",
        "value" => 4
    ),
    array(
        "identifiant" => "truckShape",
        "name" => "FOURGON",
        "value" => 5
    ),
    array(
        "identifiant" => "multiShape",
        "name" => "MULTISPACE",
        "value" => 6
    ),
    array(
        "identifiant" => "transportShape",
        "name" => "TRANSPORT_PERSONNE",
        "value" => 7
    )
);

Pelican::$config['SOUS_FILTRE_TYPE_1']['ENERGIE'] = array(
    array(
        "identifiant" => "allEnergy",
        "name" => "TOUT",
        "value" => 1
    ),
    array(
        "identifiant" => "gasoline",
        "name" => "ESSENCE",
        "value" => 2
    ),
    array(
        "identifiant" => "diesel",
        "name" => "DIESEL",
        "value" => 3
    ),
    array(
        "identifiant" => "hybrid",
        "name" => "HYBRIDE-DIESEL",
        "value" => 4
    ),
    array(
        "identifiant" => "electric",
        "name" => "ELECTRIQUE",
        "value" => 5
    )
);

Pelican::$config['SOUS_FILTRE_TYPE_1']['BOITE_VITESSE'] = array(
    array(
        "identifiant" => "allGears",
        "name" => "TOUT",
        "value" => 1
    ),
    array(
        "identifiant" => "manual",
        "name" => "MANUELLE",
        "value" => 2
    ),
    array(
        "identifiant" => "auto",
        "name" => "AUTOMATIQUE",
        "value" => 3
    ),
    array(
        "identifiant" => "transmission",
        "name" => "TRANSMISSION",
        "value" => 4
    )
);

/* CAR SELECTOR : FILTRE TYPE 2 */
Pelican::$config['FILTRE_TYPE_2'] = array(
    0 => t("CRITERE_1"),
    1 => t("CRITERE_2"),
    2 => t("PRIX"),
    3 => t("CRITERE_3")
);
Pelican::$config['FILTRE_TYPE_DEFAULT_2'] = array(
    0,
    1,
    2,
    3
);

/* CAR SELECTOR : FILTRE MOBILE */
Pelican::$config['FILTRE_MOBILE'] = array(
    0 => t("SILHOUETTE"),
    1 => t("PRIX")
);
Pelican::$config['FILTRE_MOBILE_DEFAULT'] = array(
    0,
    1
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
	'HISTOIRE_IMAGE_PAYSAGE' =>60,
	'DISPO_SUR' =>61,
    'GRAND_CINEMASCOPE' =>62,
    'CINEMASCOPE' =>63,
    '16_9' =>64,
    'PETIT_CARRE' =>65,
    'GRAND_16_9' =>66,
    'PORTRAIT' =>67,
    'TRES_GRAND_16_9' =>68,
    'SHOPPING_PICTO' =>69,
    'WEB_1_COLONNE' =>70,
    'WEB_1_COLONNE_TEXTE_PICTO' =>71,
    'WEB_2_COLONNES' =>72,
    'WEB_2_COLONNES_MIXTE' =>73,
    'WEB_3_COLONNES' =>74,
    'WEB_4_COLONNES_ET_PLUS' =>75,
    'WEB_DRAG_DROP' =>76,
    'WEB_1_CINEMASCOPE' =>77,
    'WEB_2_VISUELS_16_9' =>78,
    'WEB_VISUEL_PORTRAIT_PLUS' =>79,
    'WEB_VISUEL_PORTRAIT_PLUS_16_9_EMPILES' =>80,
    'WEB_16_9_EMPILES' =>81,
    'WEB_16_9_EMPILES_PLUS_PORTRAIT' =>82,
    'WEB_2_VISUELS_FORMAT_CARRE' =>83,
    'WEB_2_VISUELS_FORMAT_PORTRAIT' =>84,
    'WEB_3_VISUEL_CARRE' =>85,
    'WEB_SLIDESHOW_PRINCIPAL' =>86,
    'WEB_SLIDESHOW_OFFRE' =>87,
    'WEB_GAMME' =>88,
    'WEB_2_COLONNES_TEL' =>89,
    'WEB_PUSH_MEDIA_' =>90,
    'WEB_CONTENU_RECOMMANDE' =>91,
    'WEB_GRAND_VISUEL' =>92,
    'WEB_ACTUALITE' =>93,
    'WEB_TECHNOLOGIE' =>94,
    'WEB_HISTOIRE' =>95,
    'WEB_CONCEPT_CAR' =>96,
    'WEB_CAR_SELECTOR_MASTER_N1_ET_N2' =>97,
    'WEB_EXPAND_VEHICULE' =>98,
    'WEB_DISPONIBLE_SUR' =>99,
    'WEB_FAQ_SOMMAIRE_' =>100,
    'WEB_MASTER_N1_STANDARD' =>101,
    'WEB_KEY_ACCOUNT_MANGER' =>102,
    'WEB_VEHICULE_SELECTIONNE' =>103,
    'MOBILE_1_COLONNE' =>104,
    'MOBILE_1_COLONNE_TEXTE_PLUS_PICTO' =>105,
    'MOBILE_2_COLONNES' =>106,
    'MOBILE_2_COLONNES_MIXTE' =>107,
    'MOBILE_3_COLONNES' =>108,
    'MOBILE_4_COLONNES_ET_PLUS' =>109,
    'MOBILE_1_CINEMASCOPE' =>110,
    'MOBILE_2_VISUELS_16_9' =>111,
    'MOBILE_VISUEL_PORTRAIT_PLUS' =>112,
    'MOBILE_VISUEL_PORTRAIT_PLUS_16_9_EMPILES' =>113,
    'MOBILE_16_9_EMPILES' =>114,
    'MOBILE_16_9_EMPILES_PLUS_PORTRAIT' =>115,
    'MOBILE_2_VISUELS_FORMAT_CARRE' =>116,
    'MOBILE_2_VISUELS_FORMAT_PORTRAIT' =>117,
    'MOBILE_3_VISUEL_CARRE' =>118,
    'MOBILE_SLIDESHOW_PRINCIPAL' =>119,
    'MOBILE_SLIDESHOW_OFFRE' =>120,
    'MOBILE_2_COLONNES_TEL' =>121,
    'MOBILE_PUSH_MEDIA_' =>122,
    'MOBILE_GRAND_VISUEL' =>123,
    'MOBILE_ACTUALITE' =>124,
    'MOBILE_TECHNOLOGIE' =>125,
    'MOBILE_HISTOIRE' =>126,
    'MOBILE_CONCEPT_CAR' =>127,
    'MOBILE_CAR_SELECTOR_MASTER_N1_ET_N2' =>128,
    'MOBILE_MASTER_N1_STANDARD' =>129,
    'MOBILE_KEY_ACCOUNT_MANGER' =>130,
    'MOBILE_VEHICULE_SELECTIONNE' =>131,
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
    'INTERSTITIEL' => 165,
    'NEW_SLIDESHOW' => 166,
	'VISUEL_INTERIEUR' => 168,
	'NEW_CHART_SHOWROOM' => 169,
	'NEW_CHART_SHOWROOM_MOBILE' => 170
);

/* MEDIA VIDEO */
Pelican::$config['FORMAT_VIDEO_PLAYER']['HISTOIRE'] ['WIDTH']	=	520;
Pelican::$config['FORMAT_VIDEO_PLAYER']['HISTOIRE'] ['HEIGHT']	=	267;

/* TABLEAU DES DEVISES POUR WS */
Pelican::$config['DEVISE'] = array(
    '€' => 'EUR',
    '$' => 'USD',
    '£' => 'GBP'
);
/* TABLEAU DES TYPE TAXES TTC - HT */
Pelican::$config['CASH_PRICE_TAXE'] = array(
    'CASH_PRICE_TTC' => t('CASH_PRICE_TTC'),
    'CASH_PRICE_HT' => t('CASH_PRICE_HT')
);

/* Véhicule : Gammes */
Pelican::$config['VEHICULE_GAMME'] = array(
    'GAMME_LIGNE_DS' => 'GAMME_LIGNE_DS',
    'GAMME_LIGNE_C' => 'GAMME_LIGNE_C',
    'GAMME_VEHICULE_UTILITAIRE' => 'GAMME_VEHICULE_UTILITAIRE',
    'GAMME_BUSINESS' => 'GAMME_BUSINESS'
);


Pelican::$config['SHOWROOM_COLOR'] = array(
	'#007C92/#C9DD03' => 'Dark cyan / Vivid yellow',
	'#007C92/#6FD4E4' => 'Dark cyan / Soft cyan',
	'#007C92/#AEA79F' => 'Dark cyan / Dark grayish ',
	'#D71F85/#B29FA4' => 'Strong pink / Dark grayish pink',
	'#C9DD03/#B29FA4' => 'Vivid yellow / Dark grayish pink ',
	'#6FD4E4/#AEA79F' => 'Soft cyan / Dark grayish'
);


/* Véhicule : Gammes Trad FO */
Pelican::$config['VEHICULE_GAMME_FO'] = array(
    'GAMME_LIGNE_DS' => t('GAMME_LIGNE_DS_FO'),
    'GAMME_LIGNE_C' => t('GAMME_LIGNE_C_FO'),
    'GAMME_VEHICULE_UTILITAIRE' => t('GAMME_VEHICULE_UTILITAIRE_FO'),
    'GAMME_BUSINESS' => t('GAMME_BUSINESS_FO')
);
/* Contraintes de suppression des référentiels de contenus */
Pelican::$config['CONTRAINTES_SUPPRESSION_REFERENTIEL']['OUTILS'] = array(
    array(
        'ZONE_ID' => 649,
        'CHAMP' => 'ZONE_PARAMETERS',
        'SEPARATEUR' => '|'
    ),
    array(
        'ZONE_ID' => 666,
        'CHAMP' => 'ZONE_PARAMETERS',
        'SEPARATEUR' => '|'
    ),
    array(
        'ZONE_ID' => 666,
        'CHAMP' => 'ZONE_LABEL2',
        'SEPARATEUR' => '|'
    ),
    array(
        'ZONE_ID' => 672,
        'CHAMP' => 'ZONE_TEXTE',
        'SEPARATEUR' => ','
    ),
    array(
        'ZONE_ID' => 672,
        'CHAMP' => 'ZONE_TEXTE2',
        'SEPARATEUR' => ','
    )
);
Pelican::$config['CONTRAINTES_SUPPRESSION_REFERENTIEL']['CONTENUS_RECOMMANDES'] = array(
    array(
        'ZONE_ID' => 639,
        'CHAMP' => 'ZONE_LABEL2',
        'SEPARATEUR' => '|'
    )
);
Pelican::$config['CONTRAINTES_SUPPRESSION_REFERENTIEL']['RESEAUX_SOCIAUX'] = array(
    array(
        'ZONE_ID' => 632,
        'CHAMP' => 'ZONE_TITRE2',
        'SEPARATEUR' => ''
    ),
    array(
        'ZONE_ID' => 632,
        'CHAMP' => 'ZONE_TITRE3',
        'SEPARATEUR' => ''
    ),
    array(
        'ZONE_ID' => 632,
        'CHAMP' => 'ZONE_TITRE4',
        'SEPARATEUR' => ''
    ),
    array(
        'ZONE_ID' => 632,
        'CHAMP' => 'ZONE_TITRE5',
        'SEPARATEUR' => ''
    ),
    array(
        'ZONE_ID' => 644,
        'CHAMP' => 'ZONE_TITRE2',
        'SEPARATEUR' => ''
    ),
    array(
        'ZONE_ID' => 644,
        'CHAMP' => 'ZONE_TITRE3',
        'SEPARATEUR' => ''
    ),
    array(
        'ZONE_ID' => 644,
        'CHAMP' => 'ZONE_TITRE4',
        'SEPARATEUR' => ''
    ),
    array(
        'ZONE_ID' => 625,
        'CHAMP' => 'ZONE_PARAMETERS',
        'SEPARATEUR' => '|'
    )
);
Pelican::$config['CONTRAINTES_SUPPRESSION_REFERENTIEL']['THEME_TECHNOLOGIE'] = array(
    array(
        'ZONE_ID' => 679,
        'CHAMP' => 'ZONE_ATTRIBUT',
        'SEPARATEUR' => '|'
    )
);
/* Visuel 3D */
Pelican::$config['VISUEL_3D_PARAM'] = array(
    "RATIO" => 1,
    "QUALITY" => 100,
    "CLIENT" => "CPPv2",
    "VIEW" => 002
);

/* Gestion de l'utilisation des cookies */
Pelican::$config['ACCEPT_COOKIES'] = array(
    'FORCE_COOKIES' => t('FORCE_COOKIES'),
    'INFO_COOKIES' => t('INFO_COOKIES'),
    'ACCEPT_COOKIES' => t('ACCEPT_COOKIES')
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
	15 => t('15_JOURS')
);


/* Type de connexion via les reseaux sociaux */
Pelican::$config['CONNEXION_RESEAUX_SOCIAUX'] = array(
	1 => "Facebook",
	2 => "Twitter",
	3 => "Google"
);

/* Variable chemin XML source SLIDESHOW */
Pelican::$config['VARIABLE_XML_SLIDESHOW'] = 'contentURL';

/* Formulaires Citroën */
Pelican::$config['FORM']['EQUIPMENT'] = array(
    'WEB' => t('WEB'),
    'MOB' => t('MOBILE')
);
Pelican::$config['FORM']['USER_TYPE'] = array(
    'IND' => t('INDIVIDUAL'),
    'PRO' => t('PROFESSIONAL')
);
Pelican::$config['FORM']['CONTEXT'] = array(
    'STD' => t('STANDARD'),
    'CAR' => t('VEHICULE_LCDV6'),
    'RTO' => t('RETAIL_OUTLET')
);

Pelican::$config['SERVICE_ANNUPDV']['CONSUMER'] = "CPPV2.WEB.AC";
Pelican::$config['SERVICE_ANNUPDV']['BRAND'] = "AC";
/**
 * LISTE SERVICE ANNUPDV *
 */
Pelican::$config['SERVICES_ANNUPDV'] = array(
    0 => array(
        'index' => 0,
        'code' => 'APV',
        'label' => t('APV'),
        'img' => '/design/frontend/images/picto/services/APV.png',
        'big' => '/design/frontend/images/picto/services/APV_big.png',
        'mobile' => '/design/frontend/images/mobile/picto/services/APV.png'
    ),
    1 => array(
        'index' => 1,
        'code' => 'PR',
        'label' => t('PR'),
        'img' => '/design/frontend/images/picto/services/PR.png',
        'big' => '/design/frontend/images/picto/services/PR_big.png',
        'mobile' => '/design/frontend/images/mobile/picto/services/PR.png'
    ),
    2 => array(
        'index' => 2,
        'code' => 'VN',
        'label' => t('VN'),
        'img' => '/design/frontend/images/picto/services/VN.png',
        'big' => '/design/frontend/images/picto/services/VN_big.png',
        'mobile' => '/design/frontend/images/mobile/picto/services/VN.png'
    ),
    3 => array(
        'index' => 3,
        'code' => 'VO',
        'label' => t('VO'),
        'img' => '/design/frontend/images/picto/services/VO.png',
        'big' => '/design/frontend/images/picto/services/VO_big.png',
        'mobile' => '/design/frontend/images/mobile/picto/services/VO.png'
    ),
    4 => array(
        'index' => 4,
        'code' => 'E',
        'label' => t('E'),
        'img' => '/design/frontend/images/picto/services/E.png',
        'big' => '/design/frontend/images/picto/services/E_big.png',
        'mobile' => '/design/frontend/images/mobile/picto/services/E.png'
    ),
    5 => array(
        'index' => 5,
        'code' => 'V',
        'label' => t('V'),
        'img' => '/design/frontend/images/picto/services/V.png',
        'big' => '/design/frontend/images/picto/services/V_big.png',
        'mobile' => '/design/frontend/images/mobile/picto/services/V.png'
    )
);
Pelican::$config['SERVICES_ANNUPDV_CORRESPONDANCE'] = array(
    'APV' => array(
        'index' => 0,
        'code' => 'APV'
    ),
    'PR' => array(
        'index' => 1,
        'code' => 'PR'
    ),
    'VN' => array(
        'index' => 2,
        'code' => 'VN'
    )
    ,
    'VO' => array(
        'index' => 3,
        'code' => 'VO'
    )
    ,
    'E' => array(
        'index' => 4,
        'code' => 'E',
        'label' => t('PDV_FILTRE_E_LABEL')
    ),
    'V' => array(
        'index' => 5,
        'code' => 'V'
    )
);
Pelican::$config['BARRE_OUTILS']['CONFIGURATEUR'] = 6;

Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VP'] = 'VP';
Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VU'] = 'VU';
Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VP+VU'] = 'VP+VU';

Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR_DEFAULT_1'] = array(
    'VP'=>0,
    'VU'=>0,
    'VP+VU'=>1
);

Pelican::$config['VIEW_PARAMS'] = array('SITE');

Pelican::$config['ASSISTANT']['CONTENT'] = array(
    8,  // actualité
    12, // histoire
    11, // FAQ
    13  // forfait entretien
);
Pelican::$config['ASSISTANT']['DIRECTORY_ID']['ADMINISTRATEUR'] = array(
    27,     // Editorial
    42,         // Contenu
    //184,        // Pages

    35,     // Tableau de bord

    28,     // Mediatheque

    1,      // Administration
    185,        // Corbeille
    186,        // Fonctions
    205,            // copier
    188,            // import
    191,        // Gestion des urls
    4,          // General
    183,            // interface site
    182,            // traduction FO
    189,        // Statistique
    190,            // Google Analytics
    62,         // Table de ref
    211,            // Arbre decisionnel
    193,            // barre d'outils
    203,            // contenus reco
    210,            // formulaire id
    209,            // type de form
    192,            // réseaux sociaux
    202,            // groupe de reseaux
    208,            // rubrique de FAQ
    206,            // thèmes de techno
    194,            // themes d'actu
    198,        // Vehicules
    199,            // critere 1
    200,            // critere 2
    201,            // critere 3
    204,            // vehicules
    221             // categ vehicules
);
Pelican::$config['ASSISTANT']['DIRECTORY_ID']['CONTRIBUTEUR'] = array(
    27,     // Editorial
    42,         // Contenu
    //184,        // Pages

    28,     // Mediatheque

    1,      // Administration
    62,         // Table de ref
    211,            // Arbre decisionnel
    193,            // barre d'outils
    203,            // contenus reco
    210,            // formulaire id
    209,            // type de form
    192,            // réseaux sociaux
    202,            // groupe de reseaux
    208,            // rubrique de FAQ
    206,            // thèmes de techno
    194,            // themes d'actu
    198,        // Vehicules
    199,            // critere 1
    200,            // critere 2
    201,            // critere 3
    204             // vehicules
);
Pelican::$config['ASSISTANT']['DIRECTORY_ID']['IMPORTATEUR'] = array(
    27,     // Editorial
    42,         // Contenu
    //184,        // Pages

    35,     // Tableau de bord

    28,     // Mediatheque

    1,      // Administration
    185,        // Corbeille
    186,        // Fonctions
    205,            // copier
    188,            // import
    191,        // Gestion des urls
    4,          // General
    182,            // traduction FO
    189,        // Statistique
    190,            // Google Analytics
    62,         // Table de ref
    211,            // Arbre decisionnel
    193,            // barre d'outils
    203,            // contenus reco
    210,            // formulaire id
    209,            // type de form
    192,            // réseaux sociaux
    202,            // groupe de reseaux
    208,            // rubrique de FAQ
    206,            // thèmes de techno
    194,            // themes d'actu
    198,        // Vehicules
    199,            // critere 1
    200,            // critere 2
    201,            // critere 3
    204,            // vehicules
    221             // categ vehicules
);
Pelican::$config['ASSISTANT']['DIRECTORY_ID']['WEBMASTER'] = array(
    27,     // Editorial
    42,         // Contenu
    //184,        // Pages

    35,     // Tableau de bord

    28,     // Mediatheque

    1,      // Administration
    185,        // Corbeille
    186,        // Fonctions
    205,            // copier
    188,            // import
    191,        // Gestion des urls
    4,          // General
    182,            // traduction FO
    189,        // Statistique
    190,            // Google Analytics
    62,         // Table de ref
    211,            // Arbre decisionnel
    193,            // barre d'outils
    203,            // contenus reco
    210,            // formulaire id
    209,            // type de form
    192,            // réseaux sociaux
    202,            // groupe de reseaux
    208,            // rubrique de FAQ
    206,            // thèmes de techno
    194,            // themes d'actu
    198,        // Vehicules
    199,            // critere 1
    200,            // critere 2
    201,            // critere 3
    204,            // vehicules
    221             // categ vehicules
);
Pelican::$config['PERSO']['SEARCH_TERM_SCORE_EXTERNE']=0.25;
Pelican::$config['PERSO']['SEARCH_TERM_SCORE']=0.25;

// Score utilisé pour l'arrivée depuis une bannière
Pelican::$config['PERSO']['BANNER_SCORE'] = 0.2;

Pelican::$config['PERSO']['FORMTYPES'] = \Pelican_Cache::fetch(
                    "Frontend/Citroen/FormType"
            );
Pelican::$config['PERSO']['AJAX_LIST'] = array(
    '/_/Layout_Citroen_CarSelector_Resultats/carSelectorResults'       => array(
        'lib'=>'CarSelector / result',
        'params'=>array('lcdv6')
        ),
    '/_/Layout_Citroen_CitroenSocial/moreSocial'                       => array(
        'lib'=>'CitroenSocial / more element',
        'params'=>array()
        ),
    '/_/Layout_Citroen_Comparateur/getFinitionsByModelAjax'            => array(
        'lib'=>'Comparateur / getFinitions',
        'params'=>array(
            'v'
            )
        ),
    '/_/Layout_Citroen_Comparateur/getEngineByFinitionAjax'            => array(
        'lib'=>'Comparateur / getEngines',
        'params'=>array(
            'lcdv6'
            )
        ),
    '/_/Layout_Citroen_MonProjet_Comparateur/getFinitionsByModelAjax'  => array(
        'lib'=>'Comparateur / Mon Projet / getFinitions',
        'params'=>array(
            'v'
            )
        ),
    '/_/Layout_Citroen_MonProjet_Comparateur/getEngineByFinitionAjax'  => array(
        'lib'=>'Comparateur / Mon Projet / getEngines',
        'params'=>array(
            'lcdv6'
            )
        ),
    '/_/Layout_Citroen_MonProjet_SimulateurFinacement/step2Ajax'  => array(
        'lib'=>'Simulateur Financement / Mon Projet ',
        'params'=>array(
            'sim_fin_select0'
            )
        ),
    '/_/Layout_Citroen_Histoire/ajaxGetArticlesx'                      => array(
        'lib'=>'Histoire / more'
        ),

     '/_/Layout_Citroen_MonProjet_ConcessionVNAV/getMapConfiguration/'  => array(
         'lib'=>'Mon Projet / Concessions / getMapConfig'

         ),

    '/_/Layout_Citroen_MonProjet_ConcessionVNAV/getStoreList'          => array('lib'=>'Mon Projet / Concessions / getStoreList'),
    '/_/Layout_Citroen_PointsDeVente/getDealer'                        => array('lib'=>'Mon Projet / Concessions / getDealer'),
    '/_/User/activation'                                               => array('lib'=>'Mon projet / Activation de compte'),
    '/_/User/connexionFacebook'                                        => array('lib'=>'Mon projet / Connection Facebook'),
    '/_/User/connexionTwitter'                                         => array('lib'=>'Mon projet / Connection Twitter'),
    '/_/User/connexionGoogle'                                          => array('lib'=>'Mon projet / Connection Google'),
    '/_/User/connexionCitroenId'                                       => array('lib'=>'Mon projet / Connection CitroënId'),
    '/_/User/inscriptionCID'                                           => array('lib'=>'Mon projet / Inscription CitroënID'),
    '/_/User/inscriptionRS'                                            => array('lib'=>'Mon projet / Inscription Réseaux sociaux'),
    '/_/User/maj'                                                      => array('lib'=>'Mon Projet / Mise à jour de compte'),
     '/_/Layout_Citroen_MonProjet_SelectionVehicules/getFinitionsByGammeAjax'  =>array(
         'lib'=>'Mon projet / Selecteur de véhicules / getFinitions',
         'params'=>array(
             'v'
          )

         ),
     '/_/Layout_Citroen_MonProjet_SelectionVehicules/addToSelectionAjax'  =>array(
         'lib'=>'Mon projet / Selecteur de véhicules / Ajouter à la Sélection',
         'params'=>array(
             'lcdv6'
          )

         ),
    '/_/Layout_Citroen_MonProjet_SelectionVehicules/getEnginesByFinitionAjax' => array(
        'lib'=>'Mon projet / Selecteur de véhicules / getEngines',
        'params'=>array(
            'v'
            )
        ),

    '/_/Layout_Citroen_PointsDeVente/getMapConfigurationUnique'        =>array('lib'=>'Point de vente / getMapConfif') ,

    '/_/Layout_Citroen_PointsDeVente/getStoreList'                     => array('lib'=>'Point de vente / getStoreList'),
    '/_/Layout_Citroen_PointsDeVente/getDealer'                        => array('lib'=>'Point de vente / getDealer'),

    '/_/Layout_Citroen_SimulateurFinancement/step2Ajax'                => array(
          'lib'=>'Simultateur de financement / Step2',
          'params'=>array(
              'sim_fin_select0'
            )
          ),

      '/_/Layout_Citroen_SimulateurFinancement/getFinitionsByGammeAjax'  => array(
          'lib'=>'Simultateur de financement / getFinitions',
          'params'=>array(
              'v'
            )
          ),
    '/_/Layout_Citroen_SimulateurFinancement/getEnginesByFinitionAjax' =>  array(
        'lib'=>'Simultateur de financement / getEngines',
        'params'=>array(
            'lcdv6'
            )
        ),

    '/_/Layout_Citroen_VehiculesNeufs/getMapConfiguration'             => array('lib'=>'Véhicules neufs / GetMapConfig'),
    '/_/Layout_Citroen_VehiculesNeufs/getStoreList'                    => array('lib'=>'Véhicules neufs / GetStoreList'),
    '/_/Layout_Citroen_VehiculesNeufs/getDealer'                       => array('lib'=>'Véhicules neufs / GetDealer'),
    '/_/Layout_Citroen_VehiculesNeufs/france'                          => array(
        'lib'=>'Véhicules neufs / GetStock / Concession',
        'params'=>array(
            'lcdv'
            )
        ),
    '/_/Layout_Citroen_VehiculesNeufs/belgique'                        => array(
        'lib'=>'Véhicules neufs / GetStock / Region',
        'params'=>array(
            'lcdv'
            )
        ),
    '/_/Layout_Citroen_ResultatsRecherche/suggest'                     => array(
        'lib'=>'Recherche / Suggest'
        ),
    '/_/Layout_Citroen_MonProjet_SelectionVehicules/onMyProjectAjax'=>array(
        'lib'=>'Mon Projet Reconsultation',
        'params'=>array(
            'lcdv6'
            )
        )

);

if(!empty(Pelican::$config['PERSO']['FORMTYPES'])){
    foreach(Pelican::$config['PERSO']['FORMTYPES'] as $oneFormtype){
        $urlIframe = sprintf('/forms/%s/Layout_Citroen_Formulaire/iframe',$oneFormtype);
        $urlFinalStep = sprintf('/forms/%s/Layout_Citroen_Formulaire/finalStep',$oneFormtype);
        Pelican::$config['PERSO']['AJAX_LIST'][$urlIframe] = array(
                'lib'=>sprintf('Formulaire: %s',  urldecode($oneFormtype)),
                'params'=>array(
                    'lcdv'
                    )
          );
        Pelican::$config['PERSO']['AJAX_LIST'][$urlFinalStep] = array(
                'lib'=>sprintf('Page de Remerciement: %s',  urldecode($oneFormtype)),
                'params'=>array(
                    'car'
                    )
          );
        //debug($aAjaxlistEntry);
    }
}

/* GSA */
Pelican::$config['GSA']['NOMBRE_RESULTAT'] = 15;

/**
 * Sticky
 */
Pelican::$config['STICKYBAR'][Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']] = array(
    Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_INTERNE']
);
Pelican::$config['STICKYBAR'][Pelican::$config['TEMPLATE_PAGE']['MASTER_PAGE_STANDARD_N2']] = array(
    Pelican::$config['TEMPLATE_PAGE']['FORFAIT_ENTRETIEN'],
    Pelican::$config['TEMPLATE_PAGE']['GABARIT_BLANC']
);
Pelican::$config['STICKYBAR'][Pelican::$config['TEMPLATE_PAGE']['ACCUEIL_PROMOTION']] = array(
    Pelican::$config['TEMPLATE_PAGE']['LISTE_PROMOTION'],
    Pelican::$config['TEMPLATE_PAGE']['DETAIL_PROMOTION']
);


/**
 * Zones/Webservices



WS : SimulFin
Tranches :
- SimulateurFinancement
- CarSelector/Resultats
- RecapitulatifModele
- SelecteurTeinte
 *
 *  */

Pelican::$config['WS_ACTIVE_LIST_INDEXED'] = Pelican_Cache::fetch('Frontend/Citroen/SiteWsIndexed',$_SESSION[APP]['SITE_ID']);
$aWs = Pelican_Cache::fetch('Frontend/Citroen/WsConfig');
Pelican::$config['ZONE_WS']=array(

    Pelican::$config['ZONE']['VEHICULES_NEUF']=>array(
        $aWs['CITROEN_SERVICE_ANNUPDV']['id'],
        $aWs['CITROEN_SERVICE_WEBSTORE']['id'],
        ),

    Pelican::$config['ZONE']['POINT_DE_VENTE']=>array(
        $aWs['CITROEN_SERVICE_ANNUPDV']['id']
        ),

    Pelican::$config['ZONE']['CONCESSION_VNAV']=>array(
        $aWs['CITROEN_SERVICE_ANNUPDV']['id']
        ),

    Pelican::$config['ZONE']['SIMULATEUR_FINANCEMENT']=>array(
        $aWs['CITROEN_SERVICE_SIMULFIN']['id']
        ),

    Pelican::$config['ZONE']['CAR_SELECTOR_RESULTATS']=>array(
        $aWs['CITROEN_SERVICE_SIMULFIN']['id']
        ),
    Pelican::$config['ZONE']['RECAPITULATIF_MODELE']=>array(
        //$aWs['CITROEN_SERVICE_SIMULFIN']['id']
        ),
    Pelican::$config['ZONE']['SELECTEUR_DE_TEINTE']=>array(
        //$aWs['CITROEN_SERVICE_SIMULFIN']['id']
        ),
    Pelican::$config['ZONE']['SELECTEUR_DE_TEINTE_AUTO']=>array(
        //$aWs['CITROEN_SERVICE_SIMULFIN']['id']
        ),
     Pelican::$config['ZONE']['ACCESSOIRES']=>array(
        $aWs['CITROEN_SERVICE_BOUTIQACC']['id']
        ),
);

/**
 * Perso Product Media
 */
Pelican::$config['PERSO_MEDIA_ZONE'] = array(
   1 => array(
      "label" => t("SLIDESHOW_WEB"),
      "type"  => "SLIDESHOW_WEB",
      "format"  => "slideshow"
   ),
   2 => array(
       "label" => t("SLIDESHOW_MOB"),
       "type"  => 'SLIDESHOW_MOB',
       "format"  => "slideshow"
   ),
    3 => array(
        "label" => t("SLIDESHOW_OFFRE_WEB"),
        "type"  => "SLIDESHOW_OFFRE_WEB",
        "format"  => "carre"
    ),
    4 => array(
        "label" => t("SLIDESHOW_OFFRE_MOB"),
        "type"  => "SLIDESHOW_OFFRE_MOB",
        "format"  => "carre"
    ),
    5 => array(
        "label" => t("EXPAND_STANDARD"),
        "type"  => "EXPAND_STANDARD",
        "format"  => "16_9"
    ),
    6 => array(
        "label" => t("EXPAND_VEHICULE"),
        "type"  => "EXPAND_VEHICULE",
        "format"  => "16_9"
    ),
    7 => array(
        "label" => t("CONTENUS_RECOMMANDES"),
        "type"  => "CONTENUS_RECOMMANDES",
        "format"  => "carre"
    )
);

/**
 * PERSO PROFILE
 */
Pelican::$config['PERSO_PROFILES'] = array(
    'PRO'           => 1,
    'PEUT_ETRE_PRO' => 2,
    'PAS_PRO'=> 3,
    'CLIENT'=> 4,
    'PEUT_ETRE_CLEINT'=> 5,
    'CLIENT_RECENT'=> 6,
    'SANS_CONTRAT_SERVICE'=> 7,
    'INTERESSE_EXTENSION_GARANTIE'=>8,
    'INTERESSE_PRECONTROLE_TECH'=>9,
    'PAS_CLIENT_RECENT'=>10,
    'PAS_DE_PROJET_OUVERT'=>11,
    'PAS_DE_PRODUIT_PREFERE'=>12,
    'TRANCHE_0'=>13,
    'TRANCHE_1'=>14,
    'TRANCHE_1_PRO'=>15,
    'TRANCHE_1_PARTICULIER'=>16,
    'TRANCHE_2'=>17,
    'TRANCHE_2_PRO'=>18,
    'TRANCHE_2_PARTICULIER'=>19,
    'TRANCHE_3'=>20,
    'TRANCHE_3_PRO'=>21,
    'TRANCHE_3_PARTICULIER'=>22,
    'TRANCHE_4'=>23,
    'TRANCHE_4_PRO'>24,
    'TRANCHE_4_PARTICULIER'=>25,
    'LIGNE_C_PREFEREE'=>26,
    'LIGNE_DS_PREFEREE'=>27,
    'LIGNE_UTILITAIRE_PREFEREE'=>28,
    'AUTRE_LIGNE_BUSINESS_PREFEREE'=>29,
    'CLIENT_RECENT_LIGNE_C'=>30,
    'CLIENT_RECENT_LIGNE_DS'=>31
);

/**
 * Indicateurs de la personnalisation
 */
Pelican::$config['PERSO_INDICATEURS'] = array(
    'PRO' => 1,
    'EMAIL' => 2,
    'CLIENT' => 4,
    'CLIENT_RECENT' => 3,
    'PEUT_ETRE_CLIENT' => 5,
    'CLIENT_RECENT' => 6,
    'PRODUIT_POSSEDE' => 7,
    'DATE_ACHAT' => 8,
    'EXTENSION_GARANTIE' => 9,
    'TRANCHE_SCORE' => 10,
    'PRODUIT_COURANT' => 11,
    'PRODUIT_LE_MIEUX_SCORE' => 12,
    'PRODUIT_PREFERE' => 13,
    'PRODUIT_LE_PLUS_RECENT' => 14,
    'MON_PROJET_OUVERT' => 15,
    'RECONSULTATION' => 16,
);

/**
* PERSO SANS Filtre Mobile/Web
*/
Pelican::$config['PERSO_SANS_WB'] = array(
    'PUSH',
    'PUSH_CONTENU_ANNEXE',
    'PUSH_OUTILS_MINEUR',
    'PUSH_OUTILS_MAJEUR',
    'SLIDEOFFREADDFORM',
);

/**
* MULTI_NAME Perso
*/
Pelican::$config['PERSO_MULTI_NAME'] = array(
    'PUSH' => 'PUSH' ,
    'SLIDESHOW_GENERIC' => 'SLIDESHOW_GENERIC',
    'SLIDEOFFREADDFORM' => 'SLIDEOFFREADDFORM',
    'PUSH_CONTENU_ANNEXE' => 'PUSH_CONTENU_ANNEXE',
    'PUSH_OUTILS_MINEUR' => 'PUSH_OUTILS_MINEUR',
);

/**
 * CODE IMAGE BOUTIQUE ACCESSOIRE
 */
Pelican::$config['BOUTIQUE_ACC']['CSA01']['IMAGE'] = '0108363126184iJL'; // Hires : '0108363126184iJL' Lowres : '0168416833252qZC';
Pelican::$config['BOUTIQUE_ACC']['CFGAC']['IMAGE'] = '0177487678999sxl'; // Hires : '0177487678999sxl' Lowres : '0178071720820UoD';

Pelican::$config['PROFILE']['ADMINISTRATEUR']	=	'ADMINISTRATEUR';
Pelican::$config['PROFILE']['IMPORTATEUR']	=	'IMPORTATEUR';

// Liste des polices disponible en front (Super admin > Interface site > onglet Général)
Pelican::$config['FO_FONT'] = array(
     1 => 'Citroen',
     0 => 'Ubuntu/Citroën',
     2 => 'Ubuntu',
);

// Liste des typologies d'outils (liste type => clé i18n)
Pelican::$config['REFOUTIL_TYPES'] = array(
     'essai'    => 'REFOUTIL_TYPE_ESSAI',
     'offre'    => 'REFOUTIL_TYPE_OFFRE',
     'brochure' => 'REFOUTIL_TYPE_BROCHURE',
);

Pelican::$config['WS']['BROCHURE_PICKER']['URL_EXT']    =   'http://data.citroen.fr/page/ServiceAvailable.aspx?output=json';

Pelican::$config['SLIDESHOW']['MAX_SLIDE']  =   5;

Pelican::$config["DEFAULT_PAGE_PRIORITY"] = 0.7;

Pelican::$config['CODE_ADVISOR'] = "290";
/* Durée des cookies de la perso */
Pelican::$config['MAX_DAYS'] = "91";
Pelican::$config['DUREE_COOKIE_PERSO'][0] =  "0 - ".t('SESSION_EN_COURS');
for ($i=1; $i < Pelican::$config['MAX_DAYS']  ; $i++) { 
    Pelican::$config['DUREE_COOKIE_PERSO'][$i] = $i;
}

//ajout des images renommé afin de résoudre le problème de code service différent entre les pays
Pelican::$config['CAS_SPECIAL_IMAGE'] = array("S_DE", "V_SI", "V_SE", "V_NL", "V_LU", "V_IT", "V_GB",
		"V_FR", "V_CH", "V_BE", "V_AT", "R_SE", "98_SI",
		"78_AR", "39_NO", "268_CZ", "255_HU", "24_DK", "240_NL",
		"220_PL", "208_DE", "200_IT", "198_IT", "195_SE",
		"185_BE", "183_BE", "164_FR","160_CH", "159_CH", "129_UK");

Pelican::$config["STATE_TRANSLATE"] = array(1=>t("BROUILLON"), 
                                            3=>t("A_PUBLIER"),
                                            4=>t("PUBLIER"),
                                            5=>t("A_SUPPRIMER")
                                            );

Pelican::$config['TYPE_TOOLBAR_GTM_ACTION']= array(
    'PDV' => 'DealerSearch',
    'essai' => 'Forms::TestDrive',
    'offre' => 'Forms::OfferRequest',
    'brochure' => 'Forms::BrochureRequest',
    'configurator' => 'Configurator'
    );

$eventActionToolbarShowroom =array(
    'default'=> 'Redirection',
     Pelican::$config['ZONE']['RECAPITULATIF_MODELE']  => 'Summary',
    );    



Pelican::$config['DEFAULT_TOOLBAR_GTM_ACTION']= array(
    'default' => 'Redirection',
    Pelican::$config['TEMPLATE_PAGE'] ['SHOWROOM_ACCUEIL'] => $eventActionToolbarShowroom,
    Pelican::$config['TEMPLATE_PAGE'] ['SHOWROOM_INTERNE'] =>  $eventActionToolbarShowroom,
    );

$eventCategoryShowroom =array('default'=> 'Showroom');    
$eventCategoryShowroom[ Pelican::$config['ZONE']['OUTILS'] ]       = $eventCategoryShowroom['default'].'::Toolbar';
$eventCategoryShowroom[ Pelican::$config['ZONE']['ACCESSOIRES'] ]  = $eventCategoryShowroom['default'].'::Accessories';
$eventCategoryShowroom[ Pelican::$config['ZONE']['VEHICULES_NEUF'] ]= $eventCategoryShowroom['default'].'::Carstore';
$eventCategoryShowroom[ Pelican::$config['ZONE']['FINITIONS'] ]= $eventCategoryShowroom['default'].'::Finishing';
$eventCategoryShowroom[ Pelican::$config['ZONE']['EQUIPEMENTS_CARACTERISTIQUES_TECHNIQUES'] ] = $eventCategoryShowroom['default'].'::Equipment';
$eventCategoryShowroom[ Pelican::$config['ZONE']['PAGER_SHOWROOM'] ]   = $eventCategoryShowroom['default'].'::Equipment';
$eventCategoryShowroom[ Pelican::$config['ZONE']['SELECTEUR_DE_TEINTE'] ]       = $eventCategoryShowroom['default'];
$eventCategoryShowroom[ Pelican::$config['ZONE']['SELECTEUR_DE_TEINTE_AUTO'] ]       = $eventCategoryShowroom['default'];
$eventCategoryShowroom[ Pelican::$config['ZONE']['STICKYBAR'] ]   = $eventCategoryShowroom['default'];
//$eventCategoryShowroom[ Pelican::$config['ZONE']['VEHICULES_NEUF'] ]   = $eventCategoryShowroom['default'].'::Carstore';
$eventCategoryShowroom[ Pelican::$config['ZONE']['RECAPITULATIF_MODELE'] ]   = $eventCategoryShowroom['default'];
$eventCategoryShowroom[ Pelican::$config['ZONE']['CONTENUS_RECOMMANDES'] ]   = $eventCategoryShowroom['default'];
$eventCategoryShowroom[ Pelican::$config['ZONE']['CONTENUS_RECOMMANDES_SHOWROOM'] ]   = $eventCategoryShowroom['default'];
$eventCategoryShowroom[ Pelican::$config['ZONE']['ONGLET'] ]   = $eventCategoryShowroom['default'];
$eventCategoryShowroom[Pelican::$config['ZONE']['FOOTER']] = 'Footer';
    
$eventCategoryContent = array('default' => 'Content');
$eventCategoryContent[Pelican::$config['ZONE']['OUTILS']] = $eventCategoryContent['default'].'::Toolbar';
$eventCategoryContent[Pelican::$config['ZONE']['SLIDESHOW']] = $eventCategoryContent.'::Slideshow';
$eventCategoryContent[Pelican::$config['ZONE']['SLIDESHOW_OFFRES']] = 'SlideshowOffer';
$eventCategoryContent[Pelican::$config['ZONE']['VEHICULES_NEUF']] = 'Showroom::Carstore';
$eventCategoryContent[ Pelican::$config['ZONE']['CONTENUS_RECOMMANDES'] ]   = $eventCategoryContent['default'];
$eventCategoryContent[ Pelican::$config['ZONE']['STICKYBAR'] ]   = $eventCategoryContent['default'];
$eventCategoryContent[ Pelican::$config['ZONE']['SLIDESHOW'] ]   = $eventCategoryContent['default'].'::Slideshow';
$eventCategoryContent[ Pelican::$config['ZONE']['DISPO_SUR'] ]   = $eventCategoryContent['default'].'::Slideshow';
$eventCategoryContent[ Pelican::$config['ZONE']['CONTRATS_SERVICE'] ]   = $eventCategoryContent['default'].'::Slideshow';
$eventCategoryContent[Pelican::$config['ZONE']['FOOTER']] = 'Footer';



$eventCategoryHome = array('default' => 'Homepage');
$eventCategoryHome[Pelican::$config['ZONE']['SLIDESHOW_OFFRES']] = 'SlideshowOffer';
$eventCategoryHome[Pelican::$config['ZONE']['SLIDESHOW']] = 'Slideshow';
$eventCategoryHome[Pelican::$config['ZONE']['OUTILS']] = $eventCategoryHome['default'].'::Toolbar';
$eventCategoryHome[Pelican::$config['ZONE']['FOOTER']] = 'Footer';


    
     
Pelican::$config['GTM_CATEGORY'] = array(
    'default' => $eventCategoryContent,
    Pelican::$config['TEMPLATE_PAGE'] ['SHOWROOM_ACCUEIL'] => $eventCategoryShowroom,
    Pelican::$config['TEMPLATE_PAGE'] ['SHOWROOM_INTERNE'] =>  $eventCategoryShowroom,
    Pelican::$config['TEMPLATE_PAGE'] ['HOME'] => $eventCategoryHome
    );

/*temporaire pour l'integration des animations*/
if($_SESSION[APP]['CODE_PAYS']=='AT'){
	Pelican::$config['LCDV_C1'] = '1CB1B5';
}else{
	Pelican::$config['LCDV_C1'] = '1CB1A3';
}
Pelican::$config['LCDV_GRAND_C4_PICASSO'] = '1CH5CL';
Pelican::$config['LCDV_C4_PICASSO'] = '1CH5AF';
Pelican::$config['LCDV_C4_CACTUS'] = '1CE3A5' ;
Pelican::$config['LCDV_C3'] = '1CXAA5' ;
Pelican::$config['LCDV_C3_PICASSO'] = '1CWAAF' ;
Pelican::$config['LCDV_C5_TOURER'] = '1CX7C5' ;

Pelican::$config['TEMPLATE_ADMIN_ENCYCLOPEDIE_URL'] = 1366;

Pelican::$config['LCDV_C4'] = '1CB7A5' ;

Pelican::$config['FOLDER_C1'] = 'c1';
Pelican::$config['FOLDER_C4_PICASSO'] = 'c4picasso';
Pelican::$config['FOLDER_GRAND_C4_PICASSO'] = 'grandc4';
Pelican::$config['FOLDER_C4_CACTUS'] = 'c4cactus' ;
Pelican::$config['FOLDER_C4'] = 'c4' ;
Pelican::$config['FOLDER_C3'] = 'c3' ;
Pelican::$config['FOLDER_C3_PICASSO'] = 'c3picasso' ;
Pelican::$config['FOLDER_C5_TOURER'] = 'c5tourer' ;

/*equipement et caractéristique pictogramme*/
Pelican::$config['EQUIPEMENT_PICTO_DISPO'] = array(
    'Standard' => 'legend3.png',
    'Option' => 'legend1.png',
    '-' => 'legend2.png',
    'None' => 'legend2.png'
);

//Inclusion de javascript par tranche
Pelican::$config['JAVASCRIPT_FOOTER'] = array(
		"WEB" => array(),
		"MOBILE" => array()
);

Pelican::$config['EQUIPEMENT_PICTO_DISPO_DS'] = array(
    'Option' => 'legend-ds1.png',
    '-' => 'legend-ds2.png',
    'Standard' => 'legend-ds3.png',
    'None' => 'legend-ds2'
);

Pelican::$config['POSITION_CTA'] = array(
	1=>	t('GAUCHE')." / ".t("haut"),
	2=>	t('GAUCHE')." / ".t("MILIEU"),
	3=>	t('GAUCHE')." / ".t("BAS"),
	4=>	t('CENTRE')." / ".t("haut"),
	5=>	t('CENTRE')." / ".t("MILIEU"),
	6=>	t('CENTRE')." / ".t("BAS"),
	7=>	t('DROITE')." / ".t("haut"),
	8=>	t('DROITE')." / ".t("MILIEU"),
	9=>	t('DROITE')." / ".t("BAS")
);

//array(top bottom right left centre milieu)
Pelican::$config['POSITION_CTA_POSITION'] = array(
	1=>	array('top'=>'30px!important', 'bottom'=>'', 'right'=>'', 'left'=>'93px!important', 'centre'=>'', 'milieu'=>''),//t('GAUCHE')." / ".t("haut"),
	2=>	array('top'=>'', 'bottom'=>'', 'right'=>'', 'left'=>'93px!important', 'centre'=>'', 'milieu'=>'milieu'),//t('GAUCHE')." / ".t("MILIEU"),
	3=>	array('top'=>'', 'bottom'=>'', 'right'=>'', 'left'=>'93px!important', 'centre'=>'', 'milieu'=>''),//t('GAUCHE')." / ".t("BAS"),
	4=>	array('top'=>'30px!important', 'bottom'=>'', 'right'=>'', 'left'=>'', '40%!important', 'centre'=>'centre', 'milieu'=>''),//t('CENTRE')." / ".t("haut"),
	5=>	array('top'=>'', 'bottom'=>'', 'right'=>'', 'left'=>'', '40%!important', 'centre'=>'centre', 'milieu'=>'milieu'),//t('CENTRE')." / ".t("MILIEU"),
	6=>	array('top'=>'', 'bottom'=>'', 'right'=>'', 'left'=>'', '40%!important', 'centre'=>'centre', 'milieu'=>''),//t('CENTRE')." / ".t("BAS"),
	7=>	array('top'=>'30px!important', 'bottom'=>'', 'right'=>'93px!important', 'left'=>'inherit!important', 'centre'=>'', 'milieu'=>''),//t('DROITE')." / ".t("haut"),
	8=>	array('top'=>'', 'bottom'=>'', 'right'=>'93px!important', 'left'=>'inherit!important', 'centre'=>'', 'milieu'=>'milieu'),//t('DROITE')." / ".t("MILIEU"),
	9=>	array('top'=>'', 'bottom'=>'', 'right'=>'93px!important', 'left'=>'inherit!important', 'centre'=>'', 'milieu'=>'')//t('DROITE')." / ".t("BAS")
);

/**
 * Configuration pour le webservice GDG
 */
Pelican::$config['GDG']['BROCHURE'] = 1;
Pelican::$config['GDG']['CAR_PICKER'] = 2;
