<?php 
\Itkg::$config['CITROEN_SERVICE_ANNUPDV']['class'] = '\Citroen\Service\AnnuPDV';
\Itkg::$config['CITROEN_SERVICE_ANNUPDV']['configuration'] = '\Citroen\Service\AnnuPDV\Configuration';
\Itkg::$config['CITROEN_SERVICE_ANNUPDV']['PARAMETERS'] = array(
    'host' => 'http://rest.interakting.com/annuPDV/json/',
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
    'host' => 'http://rest.interakting.com/GSA/',
    'curl.options' => array (
        'CURLOPT_PROXY'          => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_PROXY"],
        'CURLOPT_PROXYUSERPWD'   => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_PROXYUSERPWD"],
        'CURLOPT_SSL_VERIFYPEER' => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_SSL_VERIFYPEER"],
        'CURLOPT_SSL_VERIFYHOST' => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_SSL_VERIFYHOST"],
        'CURLOPT_PROXYTYPE' => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_PROXYTYPE"],
    ),
);
\Itkg::$config['CITROEN_SERVICE_SIMULFIN']['class'] = '\Citroen\Service\SimulFin';
\Itkg::$config['CITROEN_SERVICE_SIMULFIN']['configuration'] = '\Citroen\Service\SimulFin\Configuration';
\Itkg::$config['CITROEN_SERVICE_SIMULFIN']['PARAMETERS'] = array(
    //'location' => 'https://sfg-bpf.servicesgp.mpsa.com/fr/services/ServicePSAGF_Dealer.asmx',
    'location' => 'http://soap.interakting.com:8081/citroen-simulFin',
    'curl.options' => array (
        'CURLOPT_PROXY'          => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_PROXY"],
        'CURLOPT_PROXYUSERPWD'   => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_PROXYUSERPWD"],
        'CURLOPT_SSL_VERIFYPEER' => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_SSL_VERIFYPEER"],
        'CURLOPT_SSL_VERIFYHOST' => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_SSL_VERIFYHOST"],
        'CURLOPT_PROXYTYPE' => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_PROXYTYPE"],
    ),
    'login' => 'CFG',
    'password' => 'recette',
    'wsdl_cache' => 0,
    'timeout' => 10,
    'wsdl' => Pelican::$config['APPLICATION_LIBRARY'] . '/Citroen/Service/SimulFin/wsdl/ServicePSAGF_Dealer.dev.wsdl'
);

\Itkg::$config['CITROEN_SERVICE_WEBSTORE']['class'] = '\Citroen\Service\Webstore';
\Itkg::$config['CITROEN_SERVICE_WEBSTORE']['configuration'] = '\Citroen\Service\Webstore\Configuration';
\Itkg::$config['CITROEN_SERVICE_WEBSTORE']['PARAMETERS'] = array(
    //'location' => 'http://ws-store.citroen.inetpsa.com/services/webstoreServices.asmx',
    'location' => 'http://soap.interakting.com:8081/citroen-webstore',
    'curl.options' => array (
        'CURLOPT_PROXY'          => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_PROXY"],
        'CURLOPT_PROXYUSERPWD'   => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_PROXYUSERPWD"],
        'CURLOPT_SSL_VERIFYPEER' => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_SSL_VERIFYPEER"],
        'CURLOPT_SSL_VERIFYHOST' => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_SSL_VERIFYHOST"],
        'CURLOPT_PROXYTYPE' => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_PROXYTYPE"],
    ),
    'signature' => '',
    'login' => '',
    'password' => '',
    'wsdl_cache' => 0,
    'timeout' => 10,
    'wsdl' => Pelican::$config['APPLICATION_LIBRARY'] . '/Citroen/Service/Webstore/wsdl/WSDL_webstoreServices.new.dev.asmx.xml'
);

\Itkg::$config['CITROEN_SERVICE_BOUTIQACC']['class'] = '\Citroen\Service\BoutiqAcc';
\Itkg::$config['CITROEN_SERVICE_BOUTIQACC']['configuration'] = '\Citroen\Service\BoutiqAcc\Configuration';
\Itkg::$config['CITROEN_SERVICE_BOUTIQACC']['PARAMETERS'] = array(
    'location' => 'http://soap.interakting.com:8081/citroen-boutiqAcc',
    'curl.options' => array (
        'CURLOPT_PROXY'          => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_PROXY"],
        'CURLOPT_PROXYUSERPWD'   => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_PROXYUSERPWD"],
        'CURLOPT_SSL_VERIFYPEER' => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_SSL_VERIFYPEER"],
        'CURLOPT_SSL_VERIFYHOST' => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_SSL_VERIFYHOST"],
        'CURLOPT_PROXYTYPE' => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_PROXYTYPE"],
    ),
    'signature' => '',
    'login' => '',
    'password' => '',
    'wsdl_cache' => 0,
    'timeout' => 10,
    'wsdl' => Pelican::$config['APPLICATION_LIBRARY'] . '/Citroen/Service/BoutiqAcc/wsdl/AccessoiresService.dev.wsdl'
);