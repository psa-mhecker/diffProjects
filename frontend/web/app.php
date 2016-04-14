<?php
use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
$envVars =  require_once 'env_psa.php';

// Use APC for autoloading to improve performance.
// Change 'sf2' to a unique prefix in order to prevent cache key conflicts
// with other applications also using APC.
if(!isset($_SERVER['HTTP_CLIENT_HOST'])) {
	$_SERVER['HTTP_CLIENT_HOST'] = '';
}
$apcLoader = new ApcClassLoader(
	getenv('TYPE_ENVIRONNEMENT'). // La clé du cache APC permet de distinguer les différents
	$_SERVER['HTTP_CLIENT_HOST']. // modes d'acces de l'appli (url internet vs url intranet)
	$_SERVER['HTTP_HOST'], $loader);
$loader->unregister();
$apcLoader->register(true);

// enabled notice error for dev
if ($envVars['debug']) {
    Debug::enable();
}
require_once __DIR__.'/../app/AppKernel.php';
require_once __DIR__.'/../app/AppCache.php';


$kernel = new AppKernel($envVars['env'], $envVars['debug']);
$kernel->loadClassCache();
$kernel = new AppCache($kernel, $envVars);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
