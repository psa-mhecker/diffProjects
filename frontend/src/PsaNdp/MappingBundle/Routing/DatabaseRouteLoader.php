<?php

namespace PsaNdp\MappingBundle\Routing;

use OpenOrchestra\FrontBundle\Routing\DatabaseRouteLoader as OrchestraDatabaseRouteLoader;
use OpenOrchestra\ModelInterface\Model\ReadNodeInterface;
use OpenOrchestra\ModelInterface\Model\ReadSiteAliasInterface;
use OpenOrchestra\ModelInterface\Model\ReadSiteInterface;
use OpenOrchestra\ModelInterface\Model\SchemeableInterface;
use PSA\MigrationBundle\Entity\Site\PsaSiteAlias;
use PSA\MigrationBundle\Entity\Site\PsaSiteDns;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class DatabaseRouteLoader
 */
class DatabaseRouteLoader extends OrchestraDatabaseRouteLoader
{

    /**
     * Loads a resource.
     *
     * @param mixed $resource The resource
     * @param string $type The resource type
     *
     * @return RouteCollection
     */
    public function load($resource, $type = null)
    {
        $routes = new RouteCollection();

        $sites = $this->siteRepository->findByDeleted(false);
        /** @var ReadSiteInterface $site */
        foreach ($sites as $site) {

            /**
             * @var PsaSiteDns $dns
             * @var int $key
             */
            foreach ($site->getDns() as $key => $alias) {
                $route = $this->generateNewRoute('/', $site->getSiteId(), $alias->getSiteDns(), null, null, array());
                $routes->add($key.'_'.$site->getId().'_home', $route);
                // route de preview
                $route = $this->generateNewRoute('/preview/{clearUrl}', $site->getSiteId(), $alias->getSiteDns(), null, true, array('clearUrl' => '.+'));
                $routes->add($key.'_'.$site->getId().'_preview', $route);
                // route normal
                $route = $this->generateNewRoute('{clearUrl}', $site->getSiteId(), $alias->getSiteDns(), null, null, array('clearUrl' => '.+'));
                $routes->add($key.'_'.$site->getId(), $route);
            }
        }

        return $routes;
    }

    /**
     * @param string $pattern
     * @param string $siteId
     * @param string $domain
     * @param string $scheme
     * @param string $prefix
     * @param array $requirement
     * @return Route
     */
    protected function generateNewRoute($pattern, $siteId, $domain, $scheme, $prefix = null, $requirement = [])
    {
        $route = new Route(
            $pattern,
            array(
                '_controller' => 'PsaNdp\MappingBundle\Controller\NodeController::showAction',
                'siteId' => $siteId,
                'prefix' => $prefix,
            ),
            $requirement,
            array(),
            $domain,
            $scheme
        );

        return $route;
    }
}

