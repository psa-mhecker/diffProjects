<?php
include_once 'config.php';
Pelican_Profiler::start('bootstrap', 'page');

pelican_import('Controller.Front');
pelican_import('Request.Route.Citroen');

Pelican::$config['COMPRESSOUPUT'] = false;
Pelican::$config['DROPCOMMENTS'] = false;
Pelican::$config['ENCODEEMAIL'] = false;
Pelican::$config['HIGHLIGHT'] = '';

Pelican_Request::$multidevice = true;
Pelican_Request::$multidevice_template_switch = false;

// définition de routes Citroen
Pelican_Route::set(
  'Citroen',
  '(<secure>/)(<lang>/)(<path>/)<title>(.<ext>)',
  array(
    'secure' => 'secure',
    'lang' => '[a-z]{2}(-[A-Z]{2})?',
    'path' => '[^_]*',
    'title' => '[^/]{2,}',
    'ext' => 'html?',
  )
)->defaults(
  array(
    'controller' => 'index',
    'action' => 'index',
    'lang' => '%',
  )
);

Pelican_Route::set(
  'FormPerso',
  '<form>/<prefix>/<controller>/<action>',
  array(
    'form' => 'forms',
    'prefix' => '[^/]{2,}',
    'controller' => '[^/]{2,}',
    'action' => '[^/]{2,}',
  )
)->defaults(
  array(
    'controller' => 'index',
    'action' => 'index',
  )
);

// Rewrite repassé en 2nd pour les redirections 301
Pelican::$config['route_sequence'] = array(
    'Mvc',
    'Rewrite',
    'FormPerso',
    'CriteoCatalogFeed',
    'Citroen',
    'Sitemap',
);

$body = Pelican_Request::getInstance()->execute()
  ->sendHeaders()
  ->getResponse(
    Pelican::$config['COMPRESSOUPUT'],
    Pelican::$config['DROPCOMMENTS'],
    Pelican::$config['ENCODEEMAIL'],
    Pelican::$config['HIGHLIGHT']
  )
;

Pelican_Profiler::stop('bootstrap', 'page');
header('Content-Type: application/json');
print $body;
