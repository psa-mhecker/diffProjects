<?php
include dirname(__FILE__) . '/wurfl-config.php';

$configuration['persistence']['params']['dir'] = str_replace('cache/mobile', 'cache/mobile_temp', $configuration['persistence']['params']['dir']);
