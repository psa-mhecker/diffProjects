<?php
// CAPTCHA
Pelican::$config['BOFORMS']['CAPTCHA']['PUBLIC_KEY'] = '6LcqM_QSAAAAAKn8ODVE3r81CckiSj3K4UzG_5iG';
Pelican::$config['BOFORMS']['CAPTCHA']['PRIVATE_KEY'] = '6LcqM_QSAAAAAOyhuNJR2kLnufac3X2aLYBpsM6v';

// MAIL
Pelican::$config['BOFORMS']['MAIL']['TYPE']['ID'] = array(
    'BOFORMS_TYPE_CLIENT',
    'BOFORMS_TYPE_ADMIN'
);

// 'BOFORMS_TYPE_PROVIDER'
Pelican::$config['BOFORMS']['MAIL']['TYPE']['DEFAULT'] = array(
    'BOFORMS_TYPE_CLIENT' => array(
        'BOFORMS_MAIL_DEST' => '%MAIL%'
    )
);

Pelican::$config['BOFORMS_REQUEST_TYPE'] = array ('VALIDATION_CENTRAL' => 0,
										'EVOLUTION_FORMULAIRE' => 1,
  										'NOTIFICATION_ANOMALIE' => 2,
										'NEW_FORM' => 3
);

Pelican::$config['BOFORMS_REQUEST_PRIORITY'] = array ('BOFORMS_REQUEST_BLOCKING' => 0,
										'BOFORMS_REQUEST_MAJOR' => 1,
  										'BOFORMS_REQUEST_MINOR' => 2
);


Pelican::$config['BOFORMS']['MAIL']['ATTACHMENT_NUMBER'] = 3;

Pelican::$config['BOFORMS']['CONTACT_SUPPORT']['NOTIFICATION_TYPE'] = array(
	'NEW_FIELDS' => 0,
	'REMOVE_MANDATORY_FIELDS' => 1,
	'MODIFY_IMPRINT_DISPLAY' => 2, // imprint = mentions légales
	'MODIFY_COMPONENT' => 3, // Evolution d’un composant métier
	'MODIFY_GRAPHICAL_INTERFACE' => 4, // Evolution du graphisme
	'MODIFY_OPT_IN' => 5,
	'MODIFY_STEP_ORDER' => 6,
	'OTHER_REQUEST' => 7
);

Pelican::$config['BOFORMS']['TARGET'] = array('PARTICULIER' => 1, 'PROFESSIONNEL' => 2);
