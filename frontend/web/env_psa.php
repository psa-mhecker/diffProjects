<?php
    if (isset($_SERVER['HTTP_CLIENT_HOST']) && $_SERVER['HTTP_CLIENT_HOST'] !== $_SERVER['HTTP_HOST']) {// internet access
    putenv('SYMFONY__HTTP__MEDIA='.($_SERVER['SYMFONY__HTTP__MEDIA'] = $_ENV['SYMFONY__HTTP__MEDIA'] = getenv('HTTP_MEDIA_INTERNET')));
}
else {
    // intranet access
    putenv('SYMFONY__HTTP__MEDIA='.($_SERVER['SYMFONY__HTTP__MEDIA'] = $_ENV['SYMFONY__HTTP__MEDIA'] = getenv('HTTP_MEDIA_INTRANET')));
}

switch (getenv('TYPE_ENVIRONNEMENT')) {
    case "PSA_PRODUCTION":
        $return['env']   = 'prod';
        $return['debug'] = false;
        break;
    case "PSA_PREPRODUCTION":
        $return['env']   = 'preprod';
        $return['debug'] = false;
        break;
    case "PSA_INTEGRATION":
        $return['env']   ='int';
        $return['debug'] = false;
        break;
    case "PSA_INTEGRATIONGIT":
        $return['env'] = 'intgit';
        $return['debug'] = false;
        break;
    case "PSA_RECETTE":
        $return['env']   ='recette';
        $return['debug'] = false;
        break;
    case "ITK_RECETTE":
        $return['env']   ='rec';
        $return['debug'] = false;
        break;
    case "VM":
        $return['env']   ='dev';
        $return['debug'] = true;
        break;
    case "VMAMP":
        $return['env']   ='vmamp';
        $return['debug'] = false;
        break;
    default :
        $return['env']   = 'recette';
        $return['debug'] = true;
}

$return['redis_connection'] = getenv('SYMFONY__REDIS__CONNECTION') ?: "tcp://127.0.0.1:6379";

return $return;

