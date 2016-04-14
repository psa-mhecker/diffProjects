<?php

namespace PsaNdp\MappingBundle\EventListener;

use OpenOrchestra\FrontBundle\EventSubscriber\KernelExceptionSubscriber;
use OpenOrchestra\ModelInterface\Model\ReadSiteInterface;
use OpenOrchestra\ModelInterface\Repository\ReadSiteRepositoryInterface;
use PSA\MigrationBundle\Entity\Page\PsaPage;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use PSA\MigrationBundle\Repository\PsaSiteRepository;
use PsaNdp\MappingBundle\Services\PageFinder;
use PsaNdp\MappingBundle\Services\ShareServices\ShareObjectService;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Class HttpNotFoundExceptionListener
 */
class HttpNotFoundExceptionListener extends KernelExceptionSubscriber
{
    /**
     * @var PageFinder
     */
    protected $pageFinder;

    /**
     * @var PsaSiteRepository
     */
    protected $siteRepository;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var PsaPage
     */
    protected $node;

    /**
     * @var ShareObjectService
     */
    protected $shareObject;

    /**
     * @param PageFinder                  $pageFinder
     * @param ReadSiteRepositoryInterface $siteRepository
     * @param RequestStack                $requestStack
     * @param EngineInterface             $templating
     * @param ShareObjectService          $share
     */
    public function __construct(
        PageFinder $pageFinder,
        ReadSiteRepositoryInterface $siteRepository,
        RequestStack $requestStack,
        EngineInterface $templating,
        ShareObjectService $share
    )
    {
        $this->pageFinder = $pageFinder;
        $this->siteRepository = $siteRepository;
        $this->request = $requestStack->getMasterRequest();
        $this->templating = $templating;
        $this->shareObject = $share;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     * @throws \Exception
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof HttpExceptionInterface &&  ((Response::HTTP_NOT_FOUND == $exception->getStatusCode()) || ( Response::HTTP_GONE == $exception->getStatusCode())) ) {
            $siteInfo = $this->getCurrentSiteInfo(
                trim($this->request->getHost(), '/'),
                trim($this->request->getPathInfo(), '/')
            );

            if (isset($siteInfo['site']) && $siteInfo['site'] instanceof PsaSite) {
                if (isset($siteInfo['language'])) {
                    /**
                     * @var PsaPage $page
                     */
                    $page = $this->pageFinder->get404Page($siteInfo['site']->getSiteId(), $siteInfo['language']);

                    if ($page instanceof PsaPage) {
                        $this->node = $page;
                        $this->shareObject->setNode($page);
                        if ($html = $this->getCustom404Html($siteInfo['site'], $siteInfo['language'])) {
                            $event->setResponse(new Response($html, $exception->getStatusCode()));
                        }
                    }
                }
            }
        }
    }

    /**
     * Try to find and set the current site and language
     *
     * @param string $host
     * @param string $path
     *
     * @return array
     */
    protected function getCurrentSiteInfo($host, $path)
    {
        $path = $this->formatPath($path);
        $possibleSite = null;
        $possibleAlias = null;
        $matchingLength = -1;
        /** @var array $matchingSites */
        $matchingSites = $this->siteRepository->findByAliasDomain($host);

        /** @var ReadSiteInterface $site */
        foreach ($matchingSites as $site) {
            foreach ($site->getAliases() as $alias) {
                $aliasPrefix = $this->formatPath($alias->getPrefix());
                if ($host == $alias->getDomain() && strpos($path, sprintf('%s%s/', $aliasPrefix, $alias->getLanguage())) === 0) {
                    $splitLength = count(explode('/', $aliasPrefix));
                    if ($splitLength > $matchingLength) {
                        $possibleAlias = $alias;
                        $possibleSite = $site;
                        $matchingLength = $splitLength;
                    }
                }

            }

        }

        if(null === $possibleSite) {
            reset($matchingSites);
            $possibleSite = current($matchingSites);
        }
        
        if(null === $possibleAlias) {

            $possibleAlias = current($possibleSite->getAliases());
        }

        return array(
            'site' => $possibleSite,
            'language' => $possibleAlias->getLanguage()
        );
    }

    /**
     * Get the 404 custom page for the current site / language if it has been contributed
     *
     * @param ReadSiteInterface $site
     * @param string            $language
     *
     * @return string | null
     */
    protected function getCustom404Html(ReadSiteInterface $site, $language)
    {
        $return = null;

        if ($this->node && $site && $language) {
            $return = $this->templating->render(
                'OpenOrchestraFrontBundle:Node:show.html.twig',
                array(
                    'node' => $this->node,
                    'parameters' => array(
                        'bodyClass' => 'isDesktop',
                        'siteId'    => $this->node->getSiteId(),
                        '_locale'   => $this->node->getLanguage(),
                        'datalayer'   => [],
                        'javascriptTranslation' => ''
                    )
                )
            );
        }

        return $return;
    }
}
