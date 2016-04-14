<?php

namespace PsaNdp\MappingBundle\EventListener;

use PSA\MigrationBundle\Entity\Site\PsaSite;
use PSA\MigrationBundle\Repository\PsaSiteRepository;
use PsaNdp\MappingBundle\Exception\HttpRedirectionException;
use PsaNdp\MappingBundle\Services\PageFinder;
use PsaNdp\MappingBundle\Utils\SiteConfiguration;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class KernelRequestSubscriber
 */
class KernelRequestSubscriber implements EventSubscriberInterface
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
     * @var SiteConfiguration
     */
    protected $siteConfiguration;

    /**
     * @param PageFinder        $pageFinder
     * @param PsaSiteRepository $siteRepository
     * @param SiteConfiguration $siteConfiguration
     */
    public function __construct(PageFinder $pageFinder, PsaSiteRepository $siteRepository, SiteConfiguration $siteConfiguration)
    {
        $this->pageFinder = $pageFinder;
        $this->siteRepository = $siteRepository;
        $this->siteConfiguration = $siteConfiguration;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $siteId = $request->get('siteId');

        // tester la route
        if ($request->getPathInfo() === '/' && !empty($siteId)) {
            $site = $this->siteRepository->findOneBySiteId($siteId);

            $page = null;

            if ($site instanceof PsaSite) {
                // tester si le site a plusieurs langue
                if ($site->getLangues()->count() > 1) {

                    // tester la langue du navigateur
                    $browserLanguage = $request->getPreferredLanguage();
                    if ($browserLanguage) {
                        $browserLanguage = explode('_', $browserLanguage);

                        $browserLanguage = $browserLanguage[0];
                        if ($this->isSiteLanguage($browserLanguage, $site->getLanguages())) {
                            // allez sur la home
                            $page = $this->pageFinder->getHomePage($siteId, $browserLanguage);
                        }
                    }
                } elseif ($site->getLangues()->count() === 1) {
                    $languages = $site->getLanguages();
                    $page = $this->pageFinder->getHomePage($siteId, $languages[0]->getLangueCode());
                }
            }
            if (empty($page)) {
                $this->siteConfiguration->setSiteId($siteId);
                $this->siteConfiguration->loadConfiguration();
                $siteParam = $this->siteConfiguration->getParameters();
                if ($site instanceof PsaSite && isset($siteParam['SITE_DEFAULT_LANGUAGE'])) {
                    $defaultLanguageId = $siteParam['SITE_DEFAULT_LANGUAGE'];
                    foreach ($site->getLanguages() as $siteLanguage) {
                        if ($siteLanguage->getLangueId() === (int) $defaultLanguageId) {
                            $page = $this->pageFinder->getHomePage($siteId, $siteLanguage->getLangueCode());
                        }
                    }
                }
            }

            if (!empty($page)) {
                $event->setResponse(new RedirectResponse($page->getVersion()->getPageClearUrl(), Response::HTTP_FOUND));
            } else {
                throw new HttpException(Response::HTTP_NOT_FOUND);
            }
        }
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => 'onKernelRequest',
        );
    }

    /**
     * @param string $language
     * @param array  $siteLanguages
     *
     * @return bool
     */
    private function isSiteLanguage($language, $siteLanguages)
    {
        foreach ($siteLanguages as $siteLanguage) {
            if ($siteLanguage->getLangueCode() === $language) {
                return true;
            }
        }

        return false;
    }
}
