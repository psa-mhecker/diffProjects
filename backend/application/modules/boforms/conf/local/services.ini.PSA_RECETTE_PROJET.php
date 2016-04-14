<?php

/*** Citroen Webservice BOForsm : getInstance, getInstanceByID ... ***/
\Itkg::$config['AC_SERVICE_BOFORMS']['class'] = 'Plugin_BOForms';
\Itkg::$config['AC_SERVICE_BOFORMS']['configuration'] = 'Plugin_BOForms_Configuration';
\Itkg::$config['AC_SERVICE_BOFORMS']['PARAMETERS'] = array(
		'location' => 'http://ac.bo.dcr.dev.inetpsa.com:38080/services/BOFormService',
		/*'http_auth_login' => 'mdecpw00',
		 'http_auth_password' => 'svncpw00',*/
		'wsdl_cache' => 0,
		'timeout' => 10,
		'wsdl' => 'http://ac.bo.dcr.dev.inetpsa.com:38080/services/BOFormService?wsdl'
);
/***/

/*** Peugeot Webservice BOForsm : getInstance, getInstanceByID ... ***/
\Itkg::$config['AP_SERVICE_BOFORMS']['class'] = 'Plugin_BOForms';
\Itkg::$config['AP_SERVICE_BOFORMS']['configuration'] = 'Plugin_BOForms_Configuration';
\Itkg::$config['AP_SERVICE_BOFORMS']['PARAMETERS'] = array(
		'location' => 'http://sgp.wssoap.preprod.inetpsa.com/dcr/integ/ap/services/BOFormService',
		/*'http_auth_login' => 'mdecpw00',
		 'http_auth_password' => 'svncpw00',*/
		'wsdl_cache' => 0,
		'timeout' => 10,
		'wsdl' => 'http://sgp.wssoap.preprod.inetpsa.com/dcr/integ/ap/services/BOFormService?wsdl'
);
/***/

/*** DS Webservice BOForsm : getInstance, getInstanceByID ... ***/
\Itkg::$config['DS_SERVICE_BOFORMS']['class'] = 'Plugin_BOForms';
\Itkg::$config['DS_SERVICE_BOFORMS']['configuration'] = 'Plugin_BOForms_Configuration';
\Itkg::$config['DS_SERVICE_BOFORMS']['PARAMETERS'] = array(
		'location' => 'http://ac.bo.dcr.dev.inetpsa.com:38080/services/BOFormService',
		/*'http_auth_login' => 'mdecpw00',
		 'http_auth_password' => 'svncpw00',*/
		'wsdl_cache' => 0,
		'timeout' => 10,
		'wsdl' => 'http://ac.bo.dcr.dev.inetpsa.com:38080/services/BOFormService?wsdl'
);
/***/

/*** Webservice Traduction Composants avancés ***/
\Itkg::$config['CITROEN_SERVICE_I18N']['class'] = 'Plugin_I18N';
\Itkg::$config['CITROEN_SERVICE_I18N']['configuration'] = 'Plugin_I18N_Configuration';
\Itkg::$config['CITROEN_SERVICE_I18N']['PARAMETERS'] = array(
		'location' => 'http://re7.dpdcr.citroen.inetpsa.com/boforms',
		/*'login' => 'CFG',
		'password' => 'recette',*/
		'wsdl_cache' => 0,
		'timeout' => 10,
		'wsdl' => 'http://re7.dpdcr.citroen.inetpsa.com/boforms?wsdl'
		
);
/***/


/*** Webservice Dealerservice ***/
\Itkg::$config['CITROEN_SERVICE_DEALERSERVICE']['class'] = 'Plugin_DealerService';
\Itkg::$config['CITROEN_SERVICE_DEALERSERVICE']['configuration'] = 'Plugin_DealerService_Configuration';
\Itkg::$config['CITROEN_SERVICE_DEALERSERVICE']['PARAMETERS'] = array(
		'location' => 'http://webservices.annuaire-pdv.inetpsa.com/Services/DealerService.svc/xml',
		'wsdl_cache' => 0,
		'timeout' => 10,
		'wsdl' => 'http://webservices.annuaire-pdv.inetpsa.com/Services/DealerService.svc?wsdl'

);

/***/

Pelican::$config['BOFORMS_FORM_XSD'] = 'http://re7.dpdcr.citroen.inetpsa.com/dcr/form/form.xsd';