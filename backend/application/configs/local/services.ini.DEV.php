<?php
\Itkg::$config['CITROEN_SERVICE_ANNUPDV']['class'] = '\Citroen\Service\AnnuPDV';
\Itkg::$config['CITROEN_SERVICE_ANNUPDV']['configuration'] = '\Citroen\Service\AnnuPDV\Configuration';
\Itkg::$config['CITROEN_SERVICE_ANNUPDV']['PARAMETERS'] = array(
    //'host' => 'http://rest.canal.dev/annuPDV/json/'
    //'host' => 'http://rest.interakting.com/annuPDV/json/'
    'host' => 'https://annuaire-pdv.servicesgp.mpsa.com/Services/DealerService.svc/rest/',
);
\Itkg::$config['CITROEN_SERVICE_GSA']['class'] = '\Citroen\Service\GSA';
\Itkg::$config['CITROEN_SERVICE_GSA']['configuration'] = '\Citroen\Service\GSA\Configuration';
\Itkg::$config['CITROEN_SERVICE_GSA']['PARAMETERS'] = array(
    //'host' => 'http://rest.canal.dev/GSA/'
    //'host' => 'http://rest.interakting.com/GSA/'
    'host' => 'https://admin-cppv2-dev.citroen.com/psa/relay/relay.php?url=http://pyag01.inetpsa.com/',
    'login' => 'mdecpw00',
    'password' => 'svncpw00',
    'prefixe_collection' => 'CPPv2_INT_',
    'relai' => 1,
);

$sCodePays = empty($_SESSION[APP]['CODE_PAYS']) ? 'fr' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'fr' : strtolower($_SESSION[APP]['CODE_PAYS']));
\Itkg::$config['CITROEN_SERVICE_SIMULFIN']['class'] = '\Citroen\Service\SimulFin';
\Itkg::$config['CITROEN_SERVICE_SIMULFIN']['configuration'] = '\Citroen\Service\SimulFin\Configuration';
\Itkg::$config['CITROEN_SERVICE_SIMULFIN']['PARAMETERS'] = array(
    'location' => 'https://sfg-bpf.servicesgp.mpsa.com/'.$sCodePays.'/services/ServicePSAGF_Dealer.asmx',
    'login' => 'CFG',
    'password' => 'recette',
    'wsdl_cache' => 0,
    'timeout' => 10,
    'wsdl' => 'https://sfg-bpf.servicesgp.mpsa.com/'.$sCodePays.'/services/ServicePSAGF_Dealer.asmx?WSDL',
);

\Itkg::$config['CITROEN_SERVICE_WEBSTORE']['class'] = '\Citroen\Service\Webstore';
\Itkg::$config['CITROEN_SERVICE_WEBSTORE']['configuration'] = '\Citroen\Service\Webstore\Configuration';
\Itkg::$config['CITROEN_SERVICE_WEBSTORE']['PARAMETERS'] = array(
    'location' => 'https://admin-cppv2-dev.citroen.com/psa/relay/relay.php?url=http://ws-store.citroen.inetpsa.com/services/WebstoreServices.asmx&login=mdecpw00&password=svncpw00',
    'http_auth_login' => 'mdecpw00',
    'http_auth_password' => 'svncpw00',
    'wsdl_cache' => 0,
    'timeout' => 10,
    'wsdl' => Pelican::$config['APPLICATION_LIBRARY'].'/Citroen/Service/Webstore/wsdl/WSDL_webstoreServices.DEV.asmx.xml',
);

\Itkg::$config['CITROEN_SERVICE_BOUTIQACC']['class'] = '\Citroen\Service\BoutiqAcc';
\Itkg::$config['CITROEN_SERVICE_BOUTIQACC']['configuration'] = '\Citroen\Service\BoutiqAcc\Configuration';
\Itkg::$config['CITROEN_SERVICE_BOUTIQACC']['PARAMETERS'] = array(
    'location' => 'https://admin-cppv2-dev.citroen.com/psa/relay/relay.php?url=http://aoaccessoire.inetpsa.com/aoa00Pds/services/AccessoiresService&login=mdecpw00&password=svncpw00',
    //'location' => 'http://aoaccessoire.inetpsa.com/aoa00Pds/services/AccessoiresService',
    //'location' => 'http://webservices.canal.dev:8081/citroen-boutiqAcc',
    //'location' => 'http://soap.interakting.com:8081/citroen-boutiqAcc',
    'signature' => '',
    'http_auth_login' => 'mdecpw00',
    'http_auth_password' => 'svncpw00',
    'wsdl_cache' => 0,
    'timeout' => 10,
    'wsdl' => Pelican::$config['APPLICATION_LIBRARY'].'/Citroen/Service/BoutiqAcc/wsdl/AccessoiresService.dev.wsdl',
);
