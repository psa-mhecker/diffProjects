<?php
switch ($_ENV["TYPE_ENVIRONNEMENT"]) {
    case "DEV":
        $sPrefixeCollection = 'NDP_INT_';
        $relai = 1;
        break;
    case "PREPROD":
        $sPrefixeCollection = 'NDP_INT_';
        $relai = 1;
        break;
    case "PSA_INTEGRATION":
        $sPrefixeCollection = 'NDP_INT_';
        $relai = null;
        break;
    case "PSA_PREPRODUCTION":
        $sPrefixeCollection = 'NDP_PPR_';
        $relai = null;
        break;
    case "PSA_PRODUCTION":
        $sPrefixeCollection = 'NDP_PRO_';
        $relai = null;
        break;
    case "PSA_RECETTE":
        $sPrefixeCollection = 'NDP_REC_';
        $relai = null;
        break;
    default:
        $sPrefixeCollection = 'NDP_INT_';
        $relai = null;
        break;

}

function can_i_use_proxy($url)
{
    $return = false;
    if (isset(Pelican::$config['PROXY'])) {
        $return = (!preg_match(Pelican::$config['PROXY']['NO_PROXY_FOR'], $url));
    }

    return $return;
}
/* Tableau de paramètres généraux  pour le curl.options */
if (isset(Pelican::$config['PROXY.CURL.OPTIONS']) && isset(Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_PROXY"])) {
    $confProxy = array(
        'CURLOPT_PROXY' => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_PROXY"],
        'CURLOPT_PROXYUSERPWD' => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_PROXYUSERPWD"],
        'CURLOPT_SSL_VERIFYPEER' => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_SSL_VERIFYPEER"],
        'CURLOPT_SSL_VERIFYHOST' => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_SSL_VERIFYHOST"],
        'CURLOPT_PROXYTYPE' => Pelican::$config['PROXY.CURL.OPTIONS']["CURLOPT_PROXYTYPE"],
    );
}

/* Tableau de paramètres généraux pour la conf du proxy */
if (isset(Pelican::$config['PROXY'])) {
$confWS = array(
        'proxy_host' => Pelican::$config['PROXY']['HOST'],
        'proxy_port' => Pelican::$config['PROXY']['PORT'],
        'proxy_login' => Pelican::$config['PROXY']['LOGIN'],
        'proxy_password' => Pelican::$config['PROXY']['PWD'],

    );
}