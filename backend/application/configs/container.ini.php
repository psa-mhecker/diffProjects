<?php
use Symfony\Component\ClassLoader\ApcClassLoader;
$loader = require_once __DIR__.'/../../../frontend/app/bootstrap.php.cache';
$apcLoader = new ApcClassLoader(getenv('TYPE_ENVIRONNEMENT').'_sf2_psa_', $loader);
$loader->unregister();
$apcLoader->register(true);
require_once __DIR__.'/../../../frontend/app/AppKernel.php';
$envVars = require_once __DIR__.'/../../../frontend/web/env_psa.php';

$setContainer = $_SERVER['HTTP_HOST'] !== getenv('HTTP_MEDIA');
if ($setContainer) {
    $kernel = new AppKernel($envVars['env'], $envVars['debug']);
    $kernel->boot();
    Pelican_Application::setContainer($kernel->getContainer());
}
