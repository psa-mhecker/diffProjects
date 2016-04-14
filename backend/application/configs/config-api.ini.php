<?php
Pelican::$config["API"]['PARAMS']['AUTH']['KEY'] = 'PSA';
Pelican::$config["API"]['PARAMS']['TIMEOUT']['MOT_CFG'] = 20;
Pelican::$config["API"]['PARAMS']['TIMEOUT']['WEBSTORE'] = 30;
Pelican::$config["API"]['PARAMS']['CFG_COUNTRY_ACTIVATED'] = array(
    'TR', 'UA', 'SI', 'AT', 'FR', 'BE', 'CH', 'CZ', 'DE','DK', 'ES', 'GB', 'HU', 'IT', 'LU', 'NO', 'NL', 'PL', 'PT','SE', 'RO', 'HR', 'SK', 'AR', 'RU', 'BR');

/**
 * Configuration web service médiathèque
 */
Pelican::$config['API']['MEDIA']['APP'] = 'NDP';
Pelican::$config['API']['MEDIA']['BRANDID'] = 'AP';
Pelican::$config["API"]['MEDIA']['AUTH'] = array(
    array('USER' => 'psa', 'PASS' => 'psa'),
);
