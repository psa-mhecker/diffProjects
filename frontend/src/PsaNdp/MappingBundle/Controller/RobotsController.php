<?php

namespace PsaNdp\MappingBundle\Controller;

use OpenOrchestra\ModelInterface\Model\ReadSiteInterface;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use PSA\MigrationBundle\Entity\Site\PsaSiteDns;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RobotsController
 */
class RobotsController extends Controller
{
    /**
     * @param Request $request
     *
     * @Config\Route("/robots.txt", name="ndp_mapping_robots")
     * @Config\Method({"GET"})
     *
     * @return Response
     */
    public function showAction(Request $request)
    {
        $siteRepository = $this->get('open_orchestra_model.repository.site');

        $site = $siteRepository->findByAliasDomain($request->getHost());

        if (is_array($site)) {
            $site = $site[0];
        }

        $content = null;
        if ($site instanceof PsaSite) {
            $content = $site->getSiteRobotDesk();
            $siteMapDirectory = $this->getParameter('sitemap.default.path').$site->getSiteId();
            if (file_exists($siteMapDirectory)) {
                $finder = new Finder();
                $finder->files()->in($siteMapDirectory);
                $content .= "\r\n";

                foreach ($finder as $file) {
                    $content .= sprintf("Sitemap: %s/sitemap.xml?lg=%s\r\n", $request->getHttpHost(), $file->getRelativePath());
                }
            }
        }

        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/plain');

        $response = $this->updateHeaderResponse($response, $site);

        return $response;
    }

    /**
     * @param Response          $response
     * @param ReadSiteInterface $site
     *
     * @return Response
     */
    protected function updateHeaderResponse(Response $response, ReadSiteInterface $site)
    {
        $tagManager = $this->get('open_orchestra_base.manager.tag');
        $cacheableManager = $this->get('open_orchestra_display.manager.cacheable');

        // Add cache tags
        $cacheTags = array(
            $tagManager->formatKeyIdTag('type', 'robots'),
            $tagManager->formatSiteIdTag($site->getSiteId()),
        );

        if ($site instanceof PsaSite) {
            foreach ($site->getDns() as $dns) {
                if ($dns instanceof PsaSiteDns) {
                    $cacheTags[] = $tagManager->formatKeyIdTag('host', $dns->getSiteDns());
                }
            }
        }

        $cacheableManager->setResponseCacheTags($response, $cacheTags);

        $maxAge = -1;
        $cacheableManager->setResponseMaxAge($response, $maxAge);

        // Set as public cache
        $response->setPublic();

        return $response;
    }
}
