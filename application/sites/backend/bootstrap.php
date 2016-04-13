<?php
$backend = true;

include_once ('config.php');

if (! empty($_SESSION[APP]["user"]["id"])) {
    Pelican::$config["SHOW_DEBUG"] = true;
}

/**
 * Langue par defaut
 */
if (empty($_SESSION[APP]['LANG'])) {
    if (empty($_POST)) {
        $_REQUEST['lang'] = 2;
        $lang = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
        if ($lang == 'fr') {
            $_REQUEST['lang'] = 1;
        }
    }
}
Pelican_Application::setLang();

pelican_import('Controller.Back');

Pelican_Profiler::start('bootstrap', 'page');

include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Div.php');
include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Button.php');
// include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Tab.php');
include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Form.php');
include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Media.php');
include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/File.php');
include_once (Pelican::$config['APPLICATION_LIBRARY'] . '/Citroen/View/Helper/Global.php');

Pelican::$config['COMPRESSOUPUT'] = false;
Pelican::$config['DROPCOMMENTS'] = false;
Pelican::$config['ENCODEEMAIL'] = false;
Pelican::$config['HIGHLIGHT'] = '';

$body = Pelican_Request::getInstance()->execute()
    ->sendHeaders()
    ->getResponse(Pelican::$config['COMPRESSOUPUT'], Pelican::$config['DROPCOMMENTS'], Pelican::$config['ENCODEEMAIL'], Pelican::$config['HIGHLIGHT']);

Pelican_Profiler::stop('bootstrap', 'page');

echo $body;