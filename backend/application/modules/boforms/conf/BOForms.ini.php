<?php

/* constantes à adapter si besoin */
Pelican::$config['BOFORMS_BRAND_ID'] = 'AP';
Pelican::$config['BOFORMS_CONSUMER'] = 'NDP'; 
Pelican::$config['BOFORMS_BLOC_EDITO_FORMS'] = '';
/**/

//liste des comptes super Administrateur, permet l'affichage de la fonctionnalité "débloquer"/"bloqué" des formulaires.
Pelican::$config['BOFORMS_USER_SUPER_ADMIN']= array('E452238','E462944','E458302','E387729','E446065','E461936','E463435','E446273','E464610','C069778','J534985','E464305','admin');


Pelican::$config['BOFORMS_REFERENTIAL_TYPE'] = array(/*'CULTURE'=>array('table'=>'boforms_culture', 'prefix'=>'CULTURE_'),
													 'BRAND'=>array('table'=>'boforms_brand', 'prefix'=>'BRAND_'), 						
													 'CUSTOMER_TYPE'=>array('table'=>'boforms_target', 'prefix'=>'TARGET_'),
													 'DEVICE'=>array('table'=>'boforms_device', 'prefix'=>'DEVICE_'),
													 'FORM_CONTEXT'=>array('table'=>'boforms_context', 'prefix'=>'CONTEXT_'),
													 'FORM_TYPE'=>array('table'=>'boforms_opportunite', 'prefix'=>'OPPORTUNITE_'),
													 'SITE'=>array('table'=>'boforms_formulaire_site', 'prefix'=>'FORMSITE_')*/);


//Active le caclule du statut des formulaires par la table psa_boforms_state_history
Pelican::$config['BOFORMS_STATE_HISTORY'] = false;

//FORMS site id
Pelican::$config['BOFORMS_DEFAULT_SITE_ID'] = '1';
Pelican::$config['BOFORMS_FORMSITE_ID']['PERSONAL_SPACE'] = 2;
Pelican::$config['BOFORMS_FORMSITE_ID']['LANDING_PAGE']= 3;
Pelican::$config['BOFORMS_FORMSITE_ID']['CONFIGURATOR']= 4;
Pelican::$config['BOFORMS_FORMSITE_ID']['EDEALER'] = 5;
Pelican::$config['BOFORMS_FORMSITE_ID']['STORE'] = 6;
Pelican::$config['BOFORMS_FORMSITE_ID']['DERIVED_PRODUCT'] = 7;

//id des site LP
Pelican::$config['LANDING_PAGE_SITE_ID'] = array(3,14,'3','14','03');
 
// lot 2 (support requests)

Pelican::$config['BOFORMS_STATE'] = array ("PUBLISH"=>1, "DRAFT" => 2, "AUTO" => 2); 


	
// liste des environnements (champ "customfield_10300", à préciser pour une création de jira de type anomalie)
Pelican::$config['BOFORMS_JIRA']['ENV'] = array( 
	                            "Production" => "10233",
                                "Formation" => "10450",
                                "Recette" => "10451",
                                "Qualité" => "10260",
                                "Préproduction" => "10232",
                                "Intégration" => "10452",
                                "Réception" => "10453",
                                "Développement" => "10231",
                                "Bac à sable" => "10454",
                                "Tous environnements" => "10234");

Pelican::$config['BOFORMS_JIRA']['ENV2'] = array( 
	                            "PSA_PRODUCTION" => "Production",
                                "PSA_RECETTE" => "Recette",
                                "PSA_PREPRODUCTION" => "Préproduction",
                                "PSA_INTEGRATION" => "Intégration",
                                "PREPROD" => "Préprod",
                                "DEV" => "Développement");

//clé cryptage
Pelican::$config['BOFORMS_PRIVATE_KEY'] = '4bG6h5t423KkWnDZ';

// type de demande
Pelican::$config['BOFORMS_JIRA']['ISSUE_TYPE'] = array(
	'DEMANDE_EVOLUTION' => 13,
	'ANOMALIE' =>  1
);

Pelican::$config['BOFORMS_JIRA']['PRIORITY'] = array(
	'BLOQUANTE' => 1, 'MAJEURE' => 3, 'MINEURE' => 4
);

Pelican::$config['BOFORMS_DATE_PREF'][1] = array(1=>"Jan",2=>"Fév",3=>"Mar",4=>"Avr",5=>"Mai",6=>"Juin",7=>"Juil",8=>"Août",9=>"Sept",10=>"Oct",11=>"Nov",12=>"Déc");
Pelican::$config['BOFORMS_DATE_PREF'][2] = array(1=>"Jan",2=>"Feb",3=>"Mar",4=>"Apr",5=>"May",6=>"June",7=>"Juil",8=>"Aug",9=>"Sept",10=>"Oct",11=>"Nov",12=>"Dec");

// parametres pour cusco et gdo (cusco = 8, gdo = 9)
Pelican::$config['BOFORMS_CONFIG_SI_PARAMETER'] = array(
		8 => array( 'POST_URL_PREPROD', 'POST_URL_DEV', 'POST_URL_PROD',
					'CONTENT_TYPE','MAIL_NO_REPLY',
					'RECIPIENT_DEV','RECIPIENT_PREPROD','RECIPIENT_PROD',
					'SENDER_DEV','SENDER_PREPROD','SENDER_PROD',
					'SUBJECT'),
		9 => array('WS_HOST_DEV','WS_HOST_PREPROD','WS_HOST_PROD',
					'WS_PASSWORD_DEV','WS_PASSWORD_PREPROD','WS_PASSWORD_PROD',
					'WS_URL_DEV','WS_URL_PREPROD','WS_URL_PROD',
					'WS_USER_DEV','WS_USER_PREPROD','WS_USER_PROD')
);

Pelican::$config['BOFORMS_CONFIG_INTERFACE'] = 8;

// parametres pour mettre a jour les referentiels
Pelican::$config['BOFORMS_REFERENTIAL_TYPE_UPDATE'] = array(
	/*'CULTURE'=>array('table'=>'boforms_culture', 'prefix'=>'CULTURE_'),*/
	'FORM_TYPE'=>array('table'=>'boforms_opportunite', 'prefix'=>'OPPORTUNITE_'),
	'BRAND'=>array('table'=>'boforms_brand', 'prefix'=>'BRAND_'), 						
	'CUSTOMER_TYPE'=>array('table'=>'boforms_target', 'prefix'=>'TARGET_'),
	'DEVICE'=>array('table'=>'boforms_device', 'prefix'=>'DEVICE_'),
	'FORM_CONTEXT'=>array('table'=>'boforms_context', 'prefix'=>'CONTEXT_'),

	'SITE'=>array('table'=>'boforms_formulaire_site', 'prefix'=>'FORMSITE_')
);


Pelican::$config['BOFORMS_LOG_PATH'] = Pelican::$config["PLUGIN_ROOT"] . '/boforms/var/log/';

Pelican::$config['TYPE_LANDING_PAGE'][13] = array('label' => 'LANDING_PAGE', 'refCode'=>13);
Pelican::$config['TYPE_LANDING_PAGE'][14] = array('label' => 'LANDING_PAGE_1', 'refCode'=>14);
Pelican::$config['TYPE_LANDING_PAGE'][15] = array('label' => 'LANDING_PAGE_2', 'refCode'=>15);

// configuration pour la preview des formulaires
if (Pelican::$config['BOFORMS_BRAND_ID'] == 'AP') {
	Pelican::$config['BOFORMS_PREVIEW_JS_CSS_PREFIX'] = '/version/vc'; 
	Pelican::$config['BOFORMS_PREVIEW_PATH_GET_FLUX'] = 'services';
} else {
	Pelican::$config['BOFORMS_PREVIEW_JS_CSS_PREFIX'] = '/dcrv2';
	Pelican::$config['BOFORMS_PREVIEW_PATH_GET_FLUX'] = '/dcr/srv/services';
}
