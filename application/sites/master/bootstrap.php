<?php
include_once ('config.php');

Pelican_Profiler::start('bootstrap', 'page');

pelican_import('Controller.Front');

Pelican::$config['COMPRESSOUPUT'] = false;
Pelican::$config['DROPCOMMENTS'] = false;
Pelican::$config['ENCODEEMAIL'] = false;
Pelican::$config['HIGHLIGHT'] = '';

Pelican_Request::$multidevice = false;

/*Pelican_Route::set('Citroen', '<dir><file>', array(
    'dir' => '(([a-zA-Z-_0-9]*)/?)',
	'file' => '([a-zA-Z0-9\'-_/\(\);]*)'
));

/*Pelican::$config['route_sequence'] = array(
    'Mvc',
    //'Citroen',
    'Clearurl',
    'Rewrite',
    'Sitemap'
);*/

$body = Pelican_Request::getInstance()->execute()->sendHeaders()->getResponse(Pelican::$config['COMPRESSOUPUT'], Pelican::$config['DROPCOMMENTS'], Pelican::$config['ENCODEEMAIL'], Pelican::$config['HIGHLIGHT']);

Pelican_Profiler::stop('bootstrap', 'page');

echo $body;
