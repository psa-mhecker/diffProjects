<?php
if (is_array($argv) && !empty($argv)) {
    foreach ($argv as $key => $arg) {
        if (preg_match("/-env=(.*)/", $arg, $arr)) {
            $env = $arr[1];
            unset($argv[$key]);
        }
    }
}

if (isset($env) && $env != '') {
    $_SERVER["TYPE_ENVIRONNEMENT"] = $env;
} else {
    switch ($source) {
        case '/home/projects/dev/cppv2/application/configs/config-cli.php':
            $_SERVER["TYPE_ENVIRONNEMENT"] = 'DEV';
            break;
        case '/home/projects/preprod/cppv2/application/configs/config-cli.php':
            $_SERVER["TYPE_ENVIRONNEMENT"] = 'PREPROD';
            break;
        case '/usersdev/cpw/web/integration/application/configs/config-cli.php':
            $_SERVER["TYPE_ENVIRONNEMENT"] = 'PSA_INTEGRATION';
            break;
        case '/usersdev/cpw/web/recette/application/configs/config-cli.php':
            $_SERVER["TYPE_ENVIRONNEMENT"] = 'PSA_RECETTE';
            break;
    }
}
