<?php

namespace PsaNdp\MappingBundle\Controller;

use OpenOrchestra\ModelInterface\Model\ReadNodeInterface;
use OpenOrchestra\FrontBundle\Controller\NodeController as BaseNodeController;
use PSA\MigrationBundle\Entity\Page\PsaPage;
use PSA\MigrationBundle\Entity\PsaRewrite;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use PsaNdp\MappingBundle\Exception\HttpRedirectionException;
use PsaNdp\MappingBundle\Exception\HttpUnavailableException;
use PsaNdp\MappingBundle\Exception\RedirectionException;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;
use PsaNdp\MappingBundle\Object\Image;
use PsaNdp\MappingBundle\Object\Meta\MetaName;
use PsaNdp\MappingBundle\Object\Meta\MetaProperty;
use PsaNdp\MappingBundle\Utils\SiteConfiguration;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PSA\MigrationBundle\Entity\Page\PsaPageVersion;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class NodeController.
 */
class NodeController extends BaseNodeController
{
    const NO_PEUGEOT_FONT = 'no-custom-font';
    /**
     * @var PsaPage
     */
    private $node;

    /**
     * @var PsaSite
     */
    private $site;

    /**
     * @param Request $request
     * @param string  $clearUrl
     *
     * @return PsaPage
     */
    public function getNode(Request $request, $clearUrl)
    {
        if (!isset($this->node)) {
            $siteId = $request->get('siteId');
            /** @var PsaPage $node */
            $node = $this->get('open_orchestra_model.repository.node')
                ->findOnePublishedByClearurlAndSiteIdInLastVersion('/'.$clearUrl, $siteId, $request->attributes->get('prefix') === null);

            if (!($node instanceof ReadNodeInterface)) {
                $this->tryRedirection($request, $clearUrl);
                throw new HttpException(Response::HTTP_NOT_FOUND);
            }

            $this->node = $node;
            $request->setLocale($node->getLanguage());
        }

        return $this->node;

    }

    /**
     * @param PsaPage $node
     *
     * @return $this
     */
    public function setNode($node)
    {
        $this->node = $node;

        return $this;
    }

    /**
     * Support routes prefixed with "/preview" to display node preview
     * {@inheritdoc}
     */
    public function showAction(Request $request, $clearUrl)
    {

        $siteId = $request->get('siteId');
        $this->site = $this->get('open_orchestra_model.repository.site')->findOneBySiteId($siteId);

        if ($this->site->getSiteMaintenance()) {
            $unavailableException = new HttpUnavailableException(Response::HTTP_SERVICE_UNAVAILABLE);
            $unavailableException->setSite($this->site);
            throw $unavailableException;
        }
        $node = $this->getNode($request, $clearUrl);



        $translatedPages = $this->get('open_orchestra_model.repository.node')
            ->findPublishedTranslatedByIdCurrentLanguageAndSiteId($node->getId(), $node->getLangueId(), $node->getSiteId());

        $node->setTranslatedPages($translatedPages);

        if ($request->get('_choose_language')) {
            $pagesInOtherLanguages = array();
            foreach ($this->site->getLanguages() as $language) {
                $languageCode = $language->getLangueCode();
                $pagesInOtherLanguages[$languageCode]['language'] = $language;
                $pagesInOtherLanguages[$languageCode]['pages'] = $this->get('psa_ndp.services.page_finder')->getFirstLevelPages($node->getSiteId(), $languageCode);
            }
            $node->setFirstLevelTranslatedPages($pagesInOtherLanguages);
        }

        $node->setPreview((bool) $request->get('prefix'));
        $node->sortBlock($this->isMobile());

        /* @var $version PsaPageVersion */
        $version = $node->getVersion();
        if ($version->getPageUrlExterne()) {
            //TODO confirm if need to set cache header for redirect ?
            return $this->updateHeaderResponse(
                $this->redirect($version->getPageUrlExterne(), Response::HTTP_MOVED_PERMANENTLY),
                $node
            );
        }

        $shareObject = $this->get('psa_ndp.services.share_object');
        $shareObject->setNode($node);
        $shareObject->setIsMobile($this->isMobile());

        $request->attributes->set('datalayer', $this->initDatalayer($node, $request));
        $parameters = $this->container
                            ->get('open_orchestra_front.manager.sub_query_parameters')
                            ->generate($request, $node);

        $parameters['javascriptTranslation'] = $this->initJavascriptTranslation($node);
        $parameters['bodyClass'] = $this->getBodyClass();
        $parameters['metas'] = $this->getMetas($request);

        $response = $this->renderNode($node, $parameters);

        return $this->updateHeaderResponse($response, $node);
    }

    protected function getBodyClass()
    {
        $return = 'isDesktop';
        if ($this->isMobile()) {
            $return = 'isMobile';
        }
        if ($this->isTablet()) {
            $return = 'isTablet';
        }

        //get national parameters by site id
        $nationalParameters = $this->get('psa_ndp_site_configuration')->getNationalParameters();
        if (isset($nationalParameters['USE_PEUGEOT_FONT']) && !$nationalParameters['USE_PEUGEOT_FONT']) {
            $return .= ' '.self::NO_PEUGEOT_FONT;
        }

        return $return;
    }

    protected function getMetas(Request $request)
    {
        $version = $this->node->getVersion();
        /** @var SiteConfiguration $siteConfiguration */
        $siteConfiguration = $this->container->get('psa_ndp_site_configuration');
        $siteConfiguration->setSiteId($this->site->getSiteId());
        $siteConfiguration->loadConfiguration();

        $metas=[];
        $metas[] = new MetaName('description', $version->getPageMetaDesc());
        $metas[] = new MetaName('keywords', $version->getPageMetaKeyword());
        $metas[] = new MetaName('robots', $version->getMetaRobots());
        $metas[] = new MetaProperty('og:title', $version->getPageMetaTitle());
        $metas[] = new MetaProperty('og:description',$version->getPageMetaDesc());
        $metas[] = new MetaProperty('og:type', 'website');
        $metas[] = new MetaProperty('og:url', $request->getUri(), ENT_QUOTES);
        $metas[] = new MetaProperty('og:locale',$this->node->getLanguage().'_'.$this->site->getCountryCode(), ENT_QUOTES);
        $metas[] = new MetaName('twitter:card', 'summary_large_image', ENT_QUOTES);
        $metas[] = new MetaName('twitter:site', $siteConfiguration->getParameter('TWITTER_ID'));
        $metas[] = new MetaName('twitter:title',$version->getPageMetaTitle());
        $metas[] = new MetaName('twitter:description', $version->getPageMetaDesc());

        if($version->getMediaReferences()->count()) {
            $size = ['desktop' => 'NDP_MEDIA_16_9'];
            /** @var MediaFactory $mediaFactory */
            $mediaFactory = $this->container->get('psa_ndp_mapping.object.factory.media');
            /** @var Image $media */
            $media = $mediaFactory->createFromMedia($version->getMediaReferences()->first()->getMedia(),['size' => $size, 'autoCrop' => true]);
            $urls = $media->getSize();
            $metas[] = new MetaName('twitter:image', $urls['desktop'], ENT_QUOTES);
            $metas[] = new MetaProperty('og:image', $urls['desktop'], ENT_QUOTES);
        }

        return $metas;
    }

    /**
     * Update response headers.
     *
     * @param Response          $response
     * @param ReadNodeInterface $node
     *
     * @return Response
     */
    protected function updateHeaderResponse(Response $response, ReadNodeInterface $node)
    {
        $tagManager = $this->get('open_orchestra_base.manager.tag');
        $cacheableManager = $this->get('open_orchestra_display.manager.cacheable');

        // Add cache tags
        $cacheTags = array(
            $tagManager->formatNodeIdTag($node->getNodeId()),
            $tagManager->formatLanguageTag($node->getLanguage()),
            $tagManager->formatSiteIdTag($node->getSiteId()),
        );
        $cacheableManager->setResponseCacheTags($response, $cacheTags);

        /** @var PsaPageVersion $version */
        $version = $node->getVersion();
        $maxAge = $version->getMaxAge();
        if ($this->getParameter('node_cache_max_age') != -1) {
            $maxAge = min($maxAge, $this->getParameter('node_cache_max_age'));
        }
        // Add max age tags
        $cacheableManager->setResponseMaxAge($response, $maxAge);

        // Set as public cache
        $response->setPublic();

        return $response;
    }

    private function initDatalayer(ReadNodeInterface $node, Request $request)
    {
        $this->get('psa_ndp.context')->setNode($node);
        $this->get('psa_ndp.context')->setRequest($request);
        if ($node->getVehicleCode()) {
            $this->get('psa_ndp.context')->setRangeManager($this->get('range_manager'));
        }
        $this->get('psa_ndp.datalayer')->setContext($this->get('psa_ndp.context'));

        return $this->get('psa_ndp.datalayer')->init();
    }

    /**
     * @param ReadNodeInterface $node
     *
     * @return array
     */
    private function initJavascriptTranslation(ReadNodeInterface $node)
    {
        $jsTranslation = $this->get('psa_ndp.javascript_translation');
        $jsTranslation->setLocale($node->getLanguage());
        $jsTranslation->setSiteId($node->getSiteId());

        return $jsTranslation->getJavascriptTranslation();
    }

    private function tryRedirection(Request $request, $clearUrl)
    {
        $this->siteId = $request->get('siteId');
            /* @var PsaPage $node */
            $rewrite = $this->get('psa_ndp.repository.rewrite')
                ->findOneByRewriteUrlAndSiteId('/'.$clearUrl, $this->siteId);

        if (($rewrite instanceof PsaRewrite)) {
            if ($rewrite->getRewriteResponse() == Response::HTTP_MOVED_PERMANENTLY) {
                $redirectionException = new HttpRedirectionException(Response::HTTP_MOVED_PERMANENTLY);
                $redirectionException->setRedirection($rewrite);
                throw $redirectionException;
            }
            throw new HttpException(Response::HTTP_GONE);
        }
    }

    public function isMobile()
    {
        return $this->container->get('open_orchestra_display.display_block_manager')->getDeviceUtils()->isMobile();
    }

    public function isTablet()
    {
        return $this->container->get('open_orchestra_display.display_block_manager')->getDeviceUtils()->isTablet();
    }
}


