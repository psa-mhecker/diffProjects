<?php
\Itkg::$config['CITROEN_SERVICE_ANNUPDV']['class'] = '\Citroen\Service\AnnuPDV';
\Itkg::$config['CITROEN_SERVICE_ANNUPDV']['configuration'] = '\Citroen\Service\AnnuPDV\Configuration';
\Itkg::$config['CITROEN_SERVICE_ANNUPDV']['PARAMETERS'] = array(
    'host' => 'https://annuaire-pdv.servicesgp.mpsa.com/Services/DealerService.svc/rest/',
    'curl.options' => array (
        'CURLOPT_PROXY'          => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_PROXY"],
        'CURLOPT_PROXYUSERPWD'   => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_PROXYUSERPWD"],
        'CURLOPT_SSL_VERIFYPEER' => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_SSL_VERIFYPEER"],
        'CURLOPT_SSL_VERIFYHOST' => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_SSL_VERIFYHOST"],
        'CURLOPT_PROXYTYPE' => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_PROXYTYPE"],
    ),
);
\Itkg::$config['CITROEN_SERVICE_GSA']['class'] = '\Citroen\Service\GSA';
\Itkg::$config['CITROEN_SERVICE_GSA']['configuration'] = '\Citroen\Service\GSA\Configuration';
\Itkg::$config['CITROEN_SERVICE_GSA']['PARAMETERS'] = array(
    /*'host' => 'http://rest.interakting.com/GSA/',
    'curl.options' => array (
        'CURLOPT_PROXY'          => 'http://http.internetpsa.inetpsa.com:80',
        'CURLOPT_PROXYUSERPWD'   => 'mdecpw00:svncpw00',
        'CURLOPT_SSL_VERIFYPEER' => false,
        'CURLOPT_SSL_VERIFYHOST' => false,
        'CURLOPT_PROXYTYPE' => 'CURLPROXY_HTTP',
    ),*/
    'host' => 'http://pyag01.inetpsa.com/',
    'login' => 'mdecpw00',
    'password' => 'svncpw00',
    'prefixe_collection' => 'CPPv2_INT_',
);

$sCodePays = empty($_SESSION[APP]['CODE_PAYS']) ? 'fr' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'fr' : strtolower($_SESSION[APP]['CODE_PAYS']));
\Itkg::$config['CITROEN_SERVICE_SIMULFIN']['class'] = '\Citroen\Service\SimulFin';
\Itkg::$config['CITROEN_SERVICE_SIMULFIN']['configuration'] = '\Citroen\Service\SimulFin\Configuration';
\Itkg::$config['CITROEN_SERVICE_SIMULFIN']['PARAMETERS'] = array(

    'location' => 'https://sfg-bpf.servicesgp.mpsa.com/' . $sCodePays . '/services/ServicePSAGF_Dealer.asmx',
    'wsdl' => 'https://sfg-bpf.servicesgp.mpsa.com/' . $sCodePays . '/services/ServicePSAGF_Dealer.asmx?WSDL',
    'login' => 'CFG',
    'password' => 'recette',
    'proxy_host' => Pelican::$config['PROXY']['HOST'],
    'proxy_port' => Pelican::$config['PROXY']['PORT'],
    'proxy_login' => Pelican::$config['PROXY']['LOGIN'],
    'proxy_password' => Pelican::$config['PROXY']['PWD'],
    'wsdl_cache' => 0,
    'timeout' => 10
);

\Itkg::$config['CITROEN_SERVICE_WEBSTORE']['class'] = '\Citroen\Service\Webstore';
\Itkg::$config['CITROEN_SERVICE_WEBSTORE']['configuration'] = '\Citroen\Service\Webstore\Configuration';
\Itkg::$config['CITROEN_SERVICE_WEBSTORE']['PARAMETERS'] = array(
	'location' => 'http://ws-store.citroen.inetpsa.com/services/webstoreServices.asmx',
    'http_auth_login' => 'mdecpw00',
    'http_auth_password' => 'svncpw00',
    'wsdl_cache' => 0,
    'timeout' => 10,
    'wsdl' => Pelican::$config['APPLICATION_LIBRARY'] . '/Citroen/Service/Webstore/wsdl/WSDL_webstoreServices.asmx.xml'
);

\Itkg::$config['CITROEN_SERVICE_BOUTIQACC']['class'] = '\Citroen\Service\BoutiqAcc';
\Itkg::$config['CITROEN_SERVICE_BOUTIQACC']['configuration'] = '\Citroen\Service\BoutiqAcc\Configuration';
\Itkg::$config['CITROEN_SERVICE_BOUTIQACC']['PARAMETERS'] = array(
    'location' => 'http://aoaccessoire.inetpsa.com/aoa00Pds/services/AccessoiresService',
    'http_auth_login' => 'mdecpw00',
    'http_auth_password' => 'svncpw00',
    'signature' => '',
    'login' => '',
    'password' => '',
    'wsdl_cache' => 0,
    'timeout' => 10,
    'wsdl' => Pelican::$config['APPLICATION_LIBRARY'] . '/Citroen/Service/BoutiqAcc/wsdl/AccessoiresService.dev.wsdl'
);