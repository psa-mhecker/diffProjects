<?php

use Symfony\Component\Config\ConfigCache;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{

    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),


            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Snc\RedisBundle\SncRedisBundle(),
            new Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle(),

            new NoiseLabs\Bundle\SmartyBundle\SmartyBundle(),
            new FOS\HttpCacheBundle\FOSHttpCacheBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Sonata\CacheBundle\SonataCacheBundle(),

            new OpenOrchestra\BaseBundle\OpenOrchestraBaseBundle(),
            new OpenOrchestra\BaseApiBundle\OpenOrchestraBaseApiBundle(),
            new OpenOrchestra\ThemeBundle\OpenOrchestraThemeBundle(),
            new OpenOrchestra\DisplayBundle\OpenOrchestraDisplayBundle(),
            new OpenOrchestra\BBcodeBundle\OpenOrchestraBBcodeBundle,
            new OpenOrchestra\FrontBundle\OpenOrchestraFrontBundle(),
            // gestion des webservices
            new Itkg\ConsumerBundle\ItkgConsumerBundle(),

            //NDP Bundles
            new PSA\MigrationBundle\PSAMigrationBundle(),
            new PsaNdp\MappingBundle\PsaNdpMappingBundle(),
            new PsaNdp\ApiBundle\PsaNdpApiBundle(),
            new PsaNdp\WebserviceConsumerBundle\PsaNdpWebserviceConsumerBundle(),
            new PsaNdp\LogBundle\PsaNdpLogBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test', 'int', 'rec', 'intgit'))) {

            $bundles[] = new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        // Load specific environnement configuration
        $loader->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');

        // Load Backend Configuration
        $loader->load(__DIR__ . '/../../backend/app/src/Itkg/Resources/config/config.yml');
    }

    public function getCacheDir()
    {
        return getenv('FRONTEND_VAR_PATH').'/cache/'.$this->environment;
    }

    public function getLogDir()
    {
        return getenv('FRONTEND_VAR_PATH').'/logs/'.$this->environment;
    }


    /**
     * Initializes the service container.
     *
     * The cached version of the service container is used when fresh, otherwise the
     * container is built.
     */
    protected function initializeContainer()
    {
        $class = $this->getContainerClass();
        $cache = new ConfigCache($this->getCacheDir().'/container/'.$class.'.php', $this->debug);
        $fresh = true;
        if (!$cache->isFresh()) {
            $container = $this->buildContainer();
            $container->compile();
            $this->dumpContainer($cache, $container, $class, $this->getContainerBaseClass());

            $fresh = false;
        }

        require_once $cache->getPath();

        $this->container = new $class();
        $this->container->set('kernel', $this);

        if (!$fresh && $this->container->has('cache_warmer')) {
            $this->container->get('cache_warmer')->warmUp($this->container->getParameter('kernel.cache_dir'));
        }
    }
}
