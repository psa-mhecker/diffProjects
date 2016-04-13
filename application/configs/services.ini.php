<?php
switch($_ENV["TYPE_ENVIRONNEMENT"]){
    case "DEV":
        $sPrefixeCollection = 'CPPv2_INT_';
        $relai = 1;
        break;
    case "PREPROD":
        $sPrefixeCollection = 'CPPv2_INT_';
        $relai = 1;
        break;
    case "PSA_INTEGRATION":
        $sPrefixeCollection = 'CPPv2_INT_';
        $relai = null;
        break;
    case "PSA_PREPRODUCTION":
        $sPrefixeCollection = 'CPPv2_PPR_';
        $relai = null;
        break;
    case "PSA_PRODUCTION":
        $sPrefixeCollection = 'CPPv2_PRO_';
        $relai = null;
        break;
    case "PSA_RECETTE":
        $sPrefixeCollection = 'CPPv2_REC_';
        $relai = null;
        break;
    default:
        $sPrefixeCollection = 'CPPv2_INT_';
        $relai = null;
        break;
        
}

function can_i_use_proxy($url)
{
	return (!preg_match(Pelican::$config['PROXY']['NO_PROXY_FOR'], $url));
}

$confProxy = array(
		'CURLOPT_PROXY' => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_PROXY"],
		'CURLOPT_PROXYUSERPWD' => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_PROXYUSERPWD"],
		'CURLOPT_SSL_VERIFYPEER' => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_SSL_VERIFYPEER"],
		'CURLOPT_SSL_VERIFYHOST' => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_SSL_VERIFYHOST"],
		'CURLOPT_PROXYTYPE' => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_PROXYTYPE"]
	);

/**********************************
 ** Annuaire des points de vente
 **/
\Itkg::$config['CITROEN_SERVICE_ANNUPDV']['class'] = '\Citroen\Service\AnnuPDV';
\Itkg::$config['CITROEN_SERVICE_ANNUPDV']['configuration'] = '\Citroen\Service\AnnuPDV\Configuration';
\Itkg::$config['CITROEN_SERVICE_ANNUPDV']['PARAMETERS'] = array(
	//'host' => 'http://rest.canal.dev/annuPDV/json/'
	'host' => $aWs['CITROEN_SERVICE_ANNUPDV']['url']
);
if (Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_PROXY"] != '' 
	&& can_i_use_proxy($aWs['CITROEN_SERVICE_ANNUPDV']['url']) ) {
	\Itkg::$config['CITROEN_SERVICE_ANNUPDV']['PARAMETERS']['curl.options'] = $confProxy;
}
 




/**********************************
 ** Moteur de recherche Google Search Appliance
 **/
\Itkg::$config['CITROEN_SERVICE_GSA']['class'] = '\Citroen\Service\GSA';
\Itkg::$config['CITROEN_SERVICE_GSA']['configuration'] = '\Citroen\Service\GSA\Configuration';
\Itkg::$config['CITROEN_SERVICE_GSA']['PARAMETERS'] = array(
	//'host' => 'http://rest.canal.dev/GSA/'
	'host' => $aWs['CITROEN_SERVICE_GSA']['url'],
	'login' => 'mdecpw00',     // Inutile chez PSA
	'password' => 'svncpw00',  // Inutile chez PSA
	'prefixe_collection' => $sPrefixeCollection,
	'relai' => $relai
);
if (Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_PROXY"] != '' 
	&& can_i_use_proxy($aWs['CITROEN_SERVICE_GSA']['url']) ) {
	\Itkg::$config['CITROEN_SERVICE_GSA']['PARAMETERS']['curl.options'] = $confProxy;
}
/**********************************
 ** Simulateur Financier Groupe
 **/
$sCodePays = empty($_SESSION[APP]['CODE_PAYS']) ? 'fr' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'fr' : strtolower($_SESSION[APP]['CODE_PAYS']));
$aWs['CITROEN_SERVICE_SIMULFIN']['url'] = str_replace('#CODE_PAYS#', $sCodePays, $aWs['CITROEN_SERVICE_SIMULFIN']['url']);
\Itkg::$config['CITROEN_SERVICE_SIMULFIN']['class'] = '\Citroen\Service\SimulFin';
\Itkg::$config['CITROEN_SERVICE_SIMULFIN']['configuration'] = '\Citroen\Service\SimulFin\Configuration';
\Itkg::$config['CITROEN_SERVICE_SIMULFIN']['PARAMETERS'] = array(
	//'location' => 'https://sfg-bpf.servicesgp.mpsa.com/fr/services/ServicePSAGF_Dealer.asmx',
	'location' => $aWs['CITROEN_SERVICE_SIMULFIN']['url'],
	'login' => 'CPP',          // login/password utilis� par le SFG
	'password' => 'recette',
	'wsdl_cache' => 0,
	'timeout' => 10,
	'wsdl' => $aWs['CITROEN_SERVICE_SIMULFIN']['url'] . "?WSDL"
);

/* Tableau de param�tres g�n�ral pour la conf du proxy */
$confWS = array(
		'proxy_host' => Pelican::$config['PROXY']['HOST'],
		'proxy_port' => Pelican::$config['PROXY']['PORT'],
		'proxy_login' => Pelican::$config['PROXY']['LOGIN'],
		'proxy_password' => Pelican::$config['PROXY']['PWD']

	);

if (Pelican::$config['PROXY']['HOST'] && can_i_use_proxy($aWs['CITROEN_SERVICE_SIMULFIN']['url']) ) {
	\Itkg::$config['CITROEN_SERVICE_SIMULFIN']['PARAMETERS'] = array_merge(\Itkg::$config['CITROEN_SERVICE_SIMULFIN']['PARAMETERS'], $confWS);

}

/**********************************
 ** CarStore
 **/
\Itkg::$config['CITROEN_SERVICE_WEBSTORE']['class'] = '\Citroen\Service\Webstore';
\Itkg::$config['CITROEN_SERVICE_WEBSTORE']['configuration'] = '\Citroen\Service\Webstore\Configuration';
\Itkg::$config['CITROEN_SERVICE_WEBSTORE']['PARAMETERS'] = array(
	//'location' => 'http://ws-store.citroen.inetpsa.com/services/webstoreServices.asmx',
	//'location' => 'http://webservices.canal.dev:8081/citroen-webstore',
	'location' => $aWs['CITROEN_SERVICE_WEBSTORE']['url'],
	'http_auth_login' => 'mdecpw00',    // Inutile chez PSA
	'http_auth_password' => 'svncpw00', // Inutile chez PSA
	'wsdl_cache' => 0,
	'timeout' => 10,
	'wsdl' => Pelican::$config['APPLICATION_LIBRARY'] . '/Citroen/Service/Webstore/wsdl/WSDL_webstoreServices.asmx.xml'
);
	
if (Pelican::$config['PROXY']['HOST'] && can_i_use_proxy($aWs['CITROEN_SERVICE_WEBSTORE']['url']) ) {
	//\Itkg::$config['CITROEN_SERVICE_WEBSTORE']['PARAMETERS'] = $confWS;

	\Itkg::$config['CITROEN_SERVICE_WEBSTORE']['PARAMETERS'] = array_merge(\Itkg::$config['CITROEN_SERVICE_WEBSTORE']['PARAMETERS'], $confWS);
}
/**********************************
 ** Moteur de config
 **/
 
 
\Itkg::$config['CITROEN_SERVICE_MOTEUR_CONFIG']['class'] = '\Citroen\Service\MoteurConfig';
\Itkg::$config['CITROEN_SERVICE_MOTEUR_CONFIG']['configuration'] = '\Citroen\Service\MoteurConfig\Configuration';
\Itkg::$config['CITROEN_SERVICE_MOTEUR_CONFIG']['PARAMETERS'] = array(
	'location' => 'http://sgp.wssoap.inetpsa.com/cfg/services/Select',
	'http_auth_login' => 'mdecpw00',    // Inutile chez PSA
	'http_auth_password' => 'svncpw00', // Inutile chez PSA
	'wsdl_cache' => 0,
	'timeout' => 30,
	'wsdl' => Pelican::$config['APPLICATION_LIBRARY'] . '/Citroen/Service/MoteurConfig/wsdl/Select.wsdl'
);

/**********************************
 ** Administrateur de l'Offre Accessoire (AOA - Boutique Accessoire)
 **/
\Itkg::$config['CITROEN_SERVICE_BOUTIQACC']['class'] = '\Citroen\Service\BoutiqAcc';
\Itkg::$config['CITROEN_SERVICE_BOUTIQACC']['configuration'] = '\Citroen\Service\BoutiqAcc\Configuration';
\Itkg::$config['CITROEN_SERVICE_BOUTIQACC']['PARAMETERS'] = array(
	//'location' => 'http://webservices.canal.dev:8081/citroen-boutiqAcc',
	'location' => $aWs['CITROEN_SERVICE_BOUTIQACC']['url'],
	'signature' => '',
	'http_auth_login' => 'mdecpw00',     // Controler par AOA
	'http_auth_password' => 'svncpw00',
	'wsdl_cache' => 0,
	'timeout' => 10,
	'wsdl' => Pelican::$config['APPLICATION_LIBRARY'] . '/Citroen/Service/BoutiqAcc/wsdl/AccessoiresService.wsdl'
);
if (Pelican::$config['PROXY']['HOST'] && can_i_use_proxy($aWs['CITROEN_SERVICE_BOUTIQACC']['url']) ) {
	//\Itkg::$config['CITROEN_SERVICE_BOUTIQACC']['PARAMETERS'] = $confWS;
	\Itkg::$config['CITROEN_SERVICE_BOUTIQACC']['PARAMETERS'] = array_merge(\Itkg::$config['CITROEN_SERVICE_BOUTIQACC']['PARAMETERS'], $confWS);
}


/**********************************
 ** Gamme Vehicules Utilitaires - pour recupération des Url du configurateur
 **/
\Itkg::$config['CITROEN_SERVICE_GAMMEVU']['class'] = '\Citroen\Service\GammeVU';
\Itkg::$config['CITROEN_SERVICE_GAMMEVU']['configuration'] = '\Citroen\Service\GammeVU\Configuration';
\Itkg::$config['CITROEN_SERVICE_GAMMEVU']['PARAMETERS'] = array(
	//'host' => 'http://rest.canal.dev/annuPDV/json/'
	'location' => $aWs['CITROEN_SERVICE_GAMMEVU']['url'].'/XML',
	'wsdl_cache' => 0,
	'timeout' => 120,
	'wsdl' => Pelican::$config['APPLICATION_LIBRARY'] . '/Citroen/Service/GammeVU/wsdl/WSDL_GammeVUServices.DEV.xml'
);
if (Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_PROXY"] != '' 
	&& can_i_use_proxy($aWs['CITROEN_SERVICE_GAMMEVU']['url']) ) {
	\Itkg::$config['CITROEN_SERVICE_GAMMEVU']['PARAMETERS'] = array_merge(\Itkg::$config['CITROEN_SERVICE_GAMMEVU']['PARAMETERS'], $confWS);
}

/**********************************
 ** CDG 
 **/
\Itkg::$config['CITROEN_SERVICE_GDG']['class'] = '\Citroen\Service\GDG';
\Itkg::$config['CITROEN_SERVICE_GDG']['configuration'] = '\Citroen\Service\GDG\Configuration';
\Itkg::$config['CITROEN_SERVICE_GDG']['PARAMETERS'] = array(
	'host' => $aWs['CITROEN_SERVICE_GDG']['url']
);
$http_auth_ident = 'mdecpw00:svncpw00';
$conf = array(
		'CURLOPT_USERPWD' => $http_auth_ident
	);
\Itkg::$config['CITROEN_SERVICE_GDG']['PARAMETERS']['curl.options'] = $conf;
