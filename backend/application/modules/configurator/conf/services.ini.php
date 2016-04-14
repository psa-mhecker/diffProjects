<?php

// include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/conf/local/services.ini.'.$_ENV["TYPE_ENVIRONNEMENT"].'.php');

/*** Parametres Webservice SERVICE_GDV ***/
\Itkg::$config['SERVICE_GDV']['class'] = 'Plugin_ServiceGDV';
\Itkg::$config['SERVICE_GDV']['configuration'] = 'Plugin_ServiceGDV_Configuration';
\Itkg::$config['SERVICE_GDV']['PARAMETERS'] = array();
/***/

/*** Parametres Webservice MotCfgCompareGrade ***/
\Itkg::$config['SERVICE_MOTCFGCOMPAREGRADE']['class'] = 'Plugin_MotCfgCompareGrade';
\Itkg::$config['SERVICE_MOTCFGCOMPAREGRADE']['configuration'] = 'Plugin_MotCfgCompareGrade_Configuration';
\Itkg::$config['SERVICE_MOTCFGCOMPAREGRADE']['PARAMETERS'] = array(
        'location' => 'http://172.17.0.1:8089/cfg00Web/services/compareGrades',
        //'http_auth_login' => '',
        //'http_auth_password' => 'svncpw00',
        'wsdl_cache' => 0,
        'timeout' => 10,
        'wsdl' => 'http://172.17.0.1:8089/cfg00Web/services/compareGrades?wsdl',
);
/***/

/*** Parametres Webservice MotCfgConfig ***/
\Itkg::$config['SERVICE_MOTCFGCONFIG']['class'] = 'Plugin_MotCfgConfig';
\Itkg::$config['SERVICE_MOTCFGCONFIG']['configuration'] = 'Plugin_MotCfgConfig_Configuration';
\Itkg::$config['SERVICE_MOTCFGCONFIG']['PARAMETERS'] = array(
        'location' => 'http://172.17.0.1:8089/cfg00Web/services/Config',
        //'http_auth_login' => '',
        //'http_auth_password' => 'svncpw00',
        'wsdl_cache' => 0,
        'timeout' => 10,
        'wsdl' => 'http://172.17.0.1:8089/cfg00Web/services/Config?wsdl',
);
/***/

/*** Parametres Webservice MotCfgEngineCriteria ***/
\Itkg::$config['SERVICE_MOTCFGENGINECRITERIA']['class'] = 'Plugin_MotCfgEngineCriteria';
\Itkg::$config['SERVICE_MOTCFGENGINECRITERIA']['configuration'] = 'Plugin_MotCfgEngineCriteria_Configuration';
\Itkg::$config['SERVICE_MOTCFGENGINECRITERIA']['PARAMETERS'] = array(
        'location' => 'http://172.17.0.1:8089/cfg00Web/services/EngineCriteria',
        //'http_auth_login' => '',
        //'http_auth_password' => 'svncpw00',
        'wsdl_cache' => 0,
        'timeout' => 10,
        'wsdl' => 'http://172.17.0.1:8089/cfg00Web/services/EngineCriteria?wsdl',
);
/***/

/*** Parametres Webservice MotCfgLookCombinations ***/
\Itkg::$config['SERVICE_MOTCFGLOOKCOMBINATIONS']['class'] = 'Plugin_MotCfgLookCombinations';
\Itkg::$config['SERVICE_MOTCFGLOOKCOMBINATIONS']['configuration'] = 'Plugin_MotCfgLookCombinations_Configuration';
\Itkg::$config['SERVICE_MOTCFGLOOKCOMBINATIONS']['PARAMETERS'] = array(
        'location' => 'http://172.17.0.1:8089/cfg00Web/services/LookCombinations',
        //'http_auth_login' => '',
        //'http_auth_password' => 'svncpw00',
        'wsdl_cache' => 0,
        'timeout' => 10,
        'wsdl' => 'http://172.17.0.1:8089/cfg00Web/services/LookCombinations?wsdl',
);
/***/

/*** Parametres Webservice MotCfgSelect ***/
\Itkg::$config['SERVICE_MOTCFGSELECT']['class'] = 'Plugin_MotCfgSelect';
\Itkg::$config['SERVICE_MOTCFGSELECT']['configuration'] = 'Plugin_MotCfgSelect_Configuration';
\Itkg::$config['SERVICE_MOTCFGSELECT']['PARAMETERS'] = array(
        'location' => 'http://172.17.0.1:8090/cfg00Web/services/Select',
        //'http_auth_login' => '',
        //'http_auth_password' => 'svncpw00',
        'wsdl_cache' => 0,
        'timeout' => 10,
        'wsdl' => 'http://172.17.0.1:8090/cfg00Web/services/Select?wsdl',
);
/***/

\Itkg::$config['SERVICE_SIMULFIN']['class'] = 'SimulFin';
\Itkg::$config['SERVICE_SIMULFIN']['configuration'] = 'Configuration';
\Itkg::$config['SERVICE_SIMULFIN']['PARAMETERS'] = array(
	//'location' => 'https://sfg-bpf.servicesgp.mpsa.com/fr/services/ServicePSAGF_Dealer.asmx',
  'location' => 'https://sfg-bpf.servicesgp.mpsa.com/fr/services/ServicePSAGF_Dealer.asmx',
  'login' => 'CFG',
  'password' => 'recette',
  'wsdl_cache' => 0,
  'timeout' => 10,
  'wsdl' => 'https://sfg-bpf.servicesgp.mpsa.com/fr/services/ServicePSAGF_Dealer.asmx?WSDL',
);

/*** Parametres Webservices AOA ***/
\Itkg::$config['SERVICE_AOA']['class'] = 'Plugin_ServiceAOA';
\Itkg::$config['SERVICE_AOA']['configuration'] = 'Plugin_ServiceAOA_Configuration';
\Itkg::$config['SERVICE_AOA']['PARAMETERS'] = array();
/***/

\Itkg::$config['SERVICE_CARS']['class'] = '\Service\GDVCars';
\Itkg::$config['SERVICE_CARS']['configuration'] = '\Service\GDVCars\Configuration';
\Itkg::$config['SERVICE_CARS']['PARAMETERS'] = array(
    //'host' => 'http://rest.canal.dev/GSA/'
    //'host' => 'http://rest.interakting.com/GSA/'
    'host' => 'http://media.psa-modules.com/modules/configurator/',
    //'login' => 'mdecpw00',
    //'password' => 'svncpw00',
    //'prefixe_collection' => 'CPPv2_INT_',
    //'relai' => 1,
);
