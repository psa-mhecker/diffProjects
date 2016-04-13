<?php
include_once ('config.php');

Pelican_Profiler::start('bootstrap', 'page');

include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Date.php');
include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Vehicule.php');
include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Devise.php');
include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Design.php');
include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Zone.php');
include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Share.php');
include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Video.php');
include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Cookie.php');
include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Analytics.php');
include_once (Pelican::$config['APPLICATION_LIBRARY'] . '/Citroen/View/Helper/Global.php');
include_once (Pelican::$config['APPLICATION_LIBRARY'] . '/Citroen/Controller/Sitemap.php');
include_once (Pelican::$config['APPLICATION_LIBRARY'] . '/Citroen/Controller/CriteoCatalogFeed.php');

pelican_import('Controller.Front');
pelican_import('Request.Route.Citroen');

Pelican::$config['COMPRESSOUPUT'] = false;
Pelican::$config['DROPCOMMENTS'] = false;
Pelican::$config['ENCODEEMAIL'] = false;
Pelican::$config['HIGHLIGHT'] = '';

Pelican_Request::$multidevice = true;
Pelican_Request::$multidevice_template_switch = false;

// définition de routes Citroen
Pelican_Route::set('Citroen', '(<secure>/)(<lang>/)(<path>/)<title>(.<ext>)', array(
    'secure' => 'secure',
    'lang' => '[a-z]{2}(-[A-Z]{2})?',
    'path' => '[^_]*',
    'title' => '[^/]{2,}',
    'ext' => 'html?'
))->defaults(array(
    'controller' => 'index',
    'action' => 'index',
    'lang' => '%'
));

Pelican_Route::set('FormPerso', '<form>/<prefix>/<controller>/<action>', array(
    'form' => 'forms',
    'prefix' => '[^/]{2,}',
    'controller' => '[^/]{2,}',
    'action' => '[^/]{2,}'
))->defaults(array(
    'controller' => 'index',
    'action' => 'index'
));

// Rewrite repassé en 2nd pour les redirections 301
Pelican::$config['route_sequence'] = array(
    'EncyclopediqueUrl',
    'Mvc',
    'Rewrite',
    'FormPerso',
    'CriteoCatalogFeed',
    'Citroen',
    'Sitemap'
);

// JIRA 3390 : cas des erreurs 404, contrôle de cohérence
$routes = Pelican_Cache::fetch('StaticMethod', array(
    'route',
    'Pelican_Route',
    'cache',
    $_SERVER['HTTP_HOST']
));
if (empty($routes['Citroen'])) {
    Pelican_Cache::clean('StaticMethod', array(
        'route',
        'Pelican_Route',
        'cache',
        $_SERVER['HTTP_HOST']
    ));
}
// fin JIRA 3390

$body = Pelican_Request::getInstance()->execute()
    ->sendHeaders()
    ->getResponse(Pelican::$config['COMPRESSOUPUT'], Pelican::$config['DROPCOMMENTS'], Pelican::$config['ENCODEEMAIL'], Pelican::$config['HIGHLIGHT']);

Pelican_Profiler::stop('bootstrap', 'page');

echo cleanResponse($body);

function cleanResponse($body)
{
    $return = $body;
    if (count(Pelican::$config['SITE']['INFOS']['LANG']) == 1) {
        $lang = strtolower($_SESSION[APP]['LANGUE_CODE']);
        $return = str_replace(array(
            '"/' . $lang . '/',
            "'/'.$lang.'/"
        ), array(
            '"/',
            "'/"
        ), $return);
    }
    return $return;
}