<?php
$dir = dirname(__FILE__);
$configuration = array();
$configuration['wurfl']['main-file'] = Pelican::$config['VAR_ROOT'].'/wurfl/wurfl.xml';
$configuration['wurfl']['match-mode'] = 'performance';
$configuration['persistence']['provider'] = 'file';
$configuration['persistence']['params']['dir'] = Pelican::$config['VAR_ROOT'].'/cache/mobile';