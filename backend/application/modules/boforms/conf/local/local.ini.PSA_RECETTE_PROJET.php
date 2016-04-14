<?php 
//URL utilisé pour le clear cache des insances
Pelican::$config['BOFORMS_URL_CLEARCACHE'] = "http://re7.dpdcr.citroen.inetpsa.com";
Pelican::$config['BOFORMS_URL_CLEARCACHE_KEY']="ad8fa89fddf389352456616b1846146ef6bf7c82";

//URL BO Landing page
Pelican::$config['BOFORMS_URL_LP'] = 'http://lp.re7.citroen.fr';

//URL Moteur de rendu
Pelican::$config['BOFORMS_URL_RENDERER'] = 'http://wpb01.citroen.com/forms/v2';


//Email DIG, pour récéption des mails (demande de support, soumettre ABTesting) 
Pelican::$config['BOFORMS_FORM_CENTRAL_VALIDATION_EMAIL'] = array('boformscentral@citroen.com');

/*********************** compte technique jira pour la connexion à l'API *******************/

Pelican::$config['BOFORMS_JIRA']['PROJECT_KEY'] = 'BOFPTEST';
Pelican::$config['BOFORMS_JIRA']['ISSUE_URL'] = 'http://jira-projets.inetpsa.com/SCDV/rest/api/2/issue/';

/****/

// compte technique à qui on affecte les jira
Pelican::$config['BOFORMS_JIRA']['ASSIGNEE_NAME'] = 'e462127';

// tableau php des comptes techniques en autre destinataires
Pelican::$config['BOFORMS_JIRA']['OTHER_ASSIGNEE'] = array('e441714','e458857');

/*** Proxies ***/

Pelican::$config['AC_PROXY'] = array(
		'URL' => '',
		'LOGIN' => '',
		'PWD' => '',
		'CURLPROXY_HTTP' => ''
);

Pelican::$config['AP_PROXY'] = array(
		'URL' => '',
		'LOGIN' => '',
		'PWD' => '',
		'CURLPROXY_HTTP' => ''
);

Pelican::$config['DS_PROXY'] = array(
		'URL' => '',
		'LOGIN' => '',
		'PWD' => '',
		'CURLPROXY_HTTP' => ''
);
