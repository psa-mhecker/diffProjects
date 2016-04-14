<?php

namespace PsaNdp\MappingBundle\Routing;

use OpenOrchestra\FrontBundle\Routing\RedirectionLoader as BaseRedirectionLoader;
use PSA\MigrationBundle\Entity\Page\PsaPage;
use PSA\MigrationBundle\Entity\PsaRewrite;
use Symfony\Component\Routing\RouteCollection;
use OpenOrchestra\ModelInterface\Model\ReadNodeInterface;

class RedirectionLoader extends BaseRedirectionLoader
{
    protected $cacheSite = [];

    /**
     * Loads a resource.
     *
     * @param mixed       $resource The resource
     * @param string|null $type     The resource type or null if unknown
     *
     * @throws \Exception If something went wrong
     *
     * @return RouteCollection
     */
    public function load($resource, $type = null)
    {
        $routes = new RouteCollection();
        $redirections = $this->redirectionRepository->findAll();
        /** @var ReadRedirectionInterface $redirection */
        foreach ($redirections as $redirection) {
            $site = $this->getSite($redirection->getSiteId());
            // si c'est une PsaPage c'est qu'elle a une url externe
            // on doit donc rediriger vers l'url externe
            if ($redirection instanceof PsaPage) {
                $parameterKey = 'path';
                $this->generateRouteForSite($site, $redirection, $parameterKey, null, $redirection->getUrlExterne(), $routes);
            }
            // si c'est une PsaRewrite on fait la redirection vers la page vers laquelle elle pointe
            if ($redirection instanceof PsaRewrite) {
                $node = $redirection->getPage();
                if ($node instanceof ReadNodeInterface) {
                    $parameterKey = 'path';
                    $this->generateRouteForSite($site, $redirection, $parameterKey, null, $node->getUrl(), $routes);
                }
            }
        }

        return $routes;
    }

    private function getSite($siteId)
    {
        if (!isset($this->cacheSite[$siteId])) {
            $this->cacheSite[$siteId] = $this->siteRepository->findOneBySiteId($siteId);
        }

        return $this->cacheSite[$siteId];
    }
}
