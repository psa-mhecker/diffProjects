<?php
$dir = realpath(getenv('FRONTEND_VAR_PATH').'/..');
$configuration = array();
$configuration['wurfl']['main-dir'] = $dir.'/wurfl';
$configuration['wurfl']['main-file'] = $configuration['wurfl']['main-dir'].'/wurfl.xml';
$configuration['wurfl']['match-mode'] = 'performance';
$configuration['persistence']['provider'] = 'file';
$configuration['persistence']['params']['dir'] = $configuration['wurfl']['main-dir'].'/cache/mobile';

if(!file_exists($configuration['wurfl']['main-dir'])) {
    mkdir($configuration['wurfl']['main-dir'],0755,true);
}
if(!file_exists($configuration['persistence']['params']['dir'])) {
    mkdir($configuration['persistence']['params']['dir'],0755,true);
}