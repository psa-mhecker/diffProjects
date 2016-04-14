<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use OpenOrchestra\DisplayBundle\DisplayBlock\Strategies\AbstractStrategy;
use OpenOrchestra\ModelInterface\Model\CacheableInterface;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Cta\PsaCta;
use PSA\MigrationBundle\Entity\Page\PsaPageMultiZoneContent;
use PSA\MigrationBundle\Entity\Page\PsaPageZone;
use PSA\MigrationBundle\Entity\Page\PsaPageMultiZone;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneContent;
use PsaNdp\CacheBundle\KeyGenerators\StrategyKeyGenerator;
use PsaNdp\MappingBundle\DisplayBlock\PsaDisplayBlockManager;
use PsaNdp\MappingBundle\Sources\DataSourceInterface;
use PsaNdp\MappingBundle\Transformers\DataTransformerInterface;
use PsaNdp\MappingBundle\Utils\AnchorUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use PsaNdp\MappingBundle\Manager\BlockManager;

/**
 * Class PsaAbstractStrategy.
 */
abstract class AbstractPsaStrategy extends AbstractStrategy
{
    const FORMAT_HTML = 'html';
    const DEVICE_MOBILE = 'mobile';
    const DEVICE_DESKTOP = 'desktop';
    const PROJECT_NAME = 'NDP';
    const NO_ADMIN_BLOCK = -1;

    protected $adminBlockId = self::NO_ADMIN_BLOCK;

    /**
     * @var PsaDisplayBlockManager
     */
    protected $manager;

    /** @var DataSourceInterface */
    protected $sourceService;

    /** @var DataTransformerInterface */
    protected $transformerService;

    /** @var Request */
    protected $request;

    /** @var bool */
    protected $isMobile = false;

    /** @var bool if the slice can be displayed as a popin */
    protected $isPopin = false;

    /** @var array */
    protected $dataFromSource;

    /** @var array */
    protected $dataFromTransformer;

    /** @var ReadBlockInterface */
    protected $block;

    /** @var ReadBlockInterface */
    protected $realBlock;

    /** @var RequestStack */
    protected $requestStack;

    /**
     * @param DataSourceInterface      $sourceService
     * @param DataTransformerInterface $transformerService
     * @param RequestStack             $requestStack
     */
    public function __construct(
        DataSourceInterface $sourceService,
        DataTransformerInterface $transformerService,
        RequestStack $requestStack
    ) {
        $this->sourceService = $sourceService;
        $this->transformerService = $transformerService;
        $this->requestStack = $requestStack;
        $this->isMobile = false;
        $this->initStrategy();
    }

    /**
     * @return int
     */
    public function getAdminBlockId()
    {
        return $this->adminBlockId;
    }

    /**
     * @param int $adminBlockId
     *
     * @return AbstractPsaStrategy
     */
    public function setAdminBlockId($adminBlockId)
    {
        $this->adminBlockId = $adminBlockId;

        return $this;
    }

    protected function hasAdminBlock()
    {
        return (self::NO_ADMIN_BLOCK !== $this->getAdminBlockId());
    }

    protected function overrideBlock()
    {
        /** @var BlockManager $blockManager */
        $blockManager = $this->manager->getBlockManager();

        return $blockManager->getAdminBlock($this->request, $this->getAdminBlockId());
    }

    abstract protected function getTemplateName();

    /**
     * Check if the strategy support this block.
     *
     * @param ReadBlockInterface $block
     *
     * @return bool
     */
    public function support(ReadBlockInterface $block)
    {
        return $this->getName() === $block->getComponent();
    }

    /**
     * Get the name of the strategy.
     *
     * @return string
     */
    public function getName()
    {
        $fqn = explode('\\', get_called_class());

        return array_pop($fqn);
    }

    /**
     * @param ReadBlockInterface $block
     *
     * @return Response
     */
    public function show(ReadBlockInterface $block)
    {
        // update the current request
        $this->request = $this->requestStack->getCurrentRequest();
        $this->block = $block;
        $responseContent = '';
        // init isMobile
        $this->initIsMobile();
        if ($this->hasAdminBlock()) {
            $this->realBlock = $block;
            $block = $this->overrideBlock();
        }
        $displayable = $this->isDisplayable($block);

        // Check if cache exist
        if ($displayable) {
            $responseContent = $this->getContent($block);
        }

        // Create Response
        $response = new Response($responseContent);

        // Add tags for cache
        $this->setHeaderTags($response, $block);

        return $response;
    }

    /**
     * @param Response           $response
     * @param ReadBlockInterface $block
     *
     * @throws Exception
     */
    private function setHeaderTags(Response $response, ReadBlockInterface $block)
    {
        //TODO optimization: has disctinct header tag for caching and create unique key
        // Add cache tags
        $this->manager->getCacheableManager()->setResponseCacheTags($response, $this->getCacheTags($block));
        $cacheStatus = CacheableInterface::CACHE_PRIVATE;
        if ($this->isPublic($block)) {
            $cacheStatus = CacheableInterface::CACHE_PUBLIC;
        }

        if ($block->getMaxAge() === false) {
            throw new Exception(sprintf('The block max age is empty !!!'));
        }

        $this->manager->getCacheableManager()->setResponseCacheParameters(
            $response,
            $block->getMaxAge(),
            $cacheStatus
        );
    }

    /**
     * @{@inheritdoc}
     *
     * @param ReadBlockInterface $block
     *
     * @return Array
     */
    public function getCacheTags(ReadBlockInterface $block)
    {
        /* @var PsaPageZoneConfigurableInterface $block */
        $tagManager = $this->manager->getTagManager();

        // Add main tags
        $arrayTags = [
            $tagManager->formatKeyIdTag('type', 'block'),
            $tagManager->formatSiteIdTag($this->request->attributes->get('siteId')),
            $tagManager->formatLanguageTag($block->getLangue()->getLangueCode()),
            $tagManager->formatNodeIdTag($block->getPageId()),
        ];

        $arrayTags = array_merge($arrayTags, $this->getCacheTagsContent($block));

        // Todo parse block to get contents and ctas tags
        // Cta and content come from Block : PsaPageZone, PsaPageMultiZone (and PsaZone ?)
        // Create a getCacheTags() for each block entity with an interface
        // Refacto: Site, LanguageCode and NodeId should be return directly by the getCacheTags() also

        // Add custom additional tags from each block TODO check
        foreach ($this->getAdditionalCachedTags($block) as $tagKey => $tagValue) {
            $arrayTags[] = $tagKey.'-'.$tagValue;
        }

        return $arrayTags;
    }

    /**
     * @param $block
     *
     * @return array
     */
    protected function getCacheTagsContent($block)
    {
        /* @var PsaPageZoneConfigurableInterface $block */
        $tagManager = $this->manager->getTagManager();
        $result = array();

        if ($block->getContentId()) {
            $result['content-'.$block->getContentId()] = $tagManager->formatContentIdTag($block->getContentId());
            $result = array_merge($result, $this->getCacheTagsCta($block->getContent()->getCurrentVersion()->getCtas()));
        }

        if (method_exists($block, 'getMultis')) {
            if (null !== $block->getMultis()) {
                foreach ($block->getMultis() as $multi) {
                    $result = array_merge($result, $this->getCacheTagsContent($multi));
                }
            }

            if (null !== $block->getContentReferences()) {
                foreach ($block->getContentReferences() as $content) {
                    if (($content instanceof PsaPageZoneContent) || ($content instanceof PsaPageMultiZoneContent)) {
                        $result['content-'.$content->getContentId()] = $tagManager->formatContentIdTag($content->getContentId());
                        $result = array_merge($result, $this->getCacheTagsCta($content->getContent()->getCurrentVersion()->getCtas()));
                    }
                }
            }
        }

        $result = array_merge($result, $this->getCacheTagsCta($block->getCtas()));

        return $result;
    }

    /**
     * @param $cta
     *
     * @return array
     */
    protected function getCacheTagsCta($cta)
    {
        $tagManager = $this->manager->getTagManager();
        $result = array();

        if ($cta instanceof ArrayCollection) {
            foreach ($cta as $oneCta) {
                $result = array_merge($result, $this->getCacheTagsCta($oneCta));
            }
        } elseif ($cta instanceof PsaCta) {
            if ($cta->isRef()) {
                $result['cta-'.$cta->getId()] = $tagManager->formatKeyIdTag('cta', $cta->getId());
            }
        }

        return $result;
    }

    /**
     * By default set Block as Public for caching
     * This function should be override by extending class when needed.
     *
     * @{@inheritdoc}
     *
     * @param ReadBlockInterface $block
     *
     * @return bool
     */
    public function isPublic(ReadBlockInterface $block)
    {
        return true;
    }

    /**
     * Set Ismobile variable.
     *
     * @todo For template, possibility to use Open Orchestra method with OpenOrchestra\FrontBundle\Twig\Renderable on
     *       SmartyEngine instead of TwigEngine to implement :
     *       https://github.com/open-orchestra/open-orchestra-docs/blob/master/en/developer_guide/multi_device.rst
     */
    private function initIsMobile()
    {
        $this->isMobile = $this->manager->getDeviceUtils()->isMobile();
    }


    /**
     * @param ReadBlockInterface $block
     *
     * @return string
     */
    private function getContent(ReadBlockInterface $block)
    {
        // get translator local and domain for data transformer
        $siteId = $this->request->attributes->get('siteId');
        $locale = $this->request->attributes->get('language');

        // Get datas from source and transformers services
        $this->dataFromSource = $this->sourceService
            ->setTranslator($this->manager->getTranslator(), $siteId, $locale)
            ->setMediaServer($this->manager->getMediaServer())
            ->setRealBlock($this->realBlock)
            ->setBlock($block)
            ->fetch($block, $this->request, $this->isMobile);
        $this->dataFromTransformer = $this->transformerService
            ->setTranslator($this->manager->getTranslator(), $siteId, $locale)
            ->setMediaServer($this->manager->getMediaServer())
            ->setBlock($block)
            ->fetch($this->dataFromSource, $this->isMobile);

        // Add transverse data to dataTransformer
        $this->addTransverseTransformerData();

        // Get final content from data
        $templateName = $this->getTemplateName();
        $cachedContent = $this->render($templateName, $this->dataFromTransformer)->getContent();

        return $cachedContent;
    }

    private function addTransverseTransformerData()
    {
        $firstKey = key($this->dataFromTransformer);
        $permanentId = $this->request->get('blockPermanentId');
        /** @var AnchorUtils $anchorUtils */
        $anchorUtils = $this->manager->getAnchorUtils();

        // Add datalayer array to all transformer result
        if (!isset($this->dataFromTransformer[$firstKey]['datalayer'])) {
            $this->dataFromTransformer[$firstKey]['datalayer'] = '';
        }

        // Add anchor Id (used by PN13 for example)
         $this->dataFromTransformer[$firstKey]['anchorId'] = $anchorUtils->formatAnchorId($permanentId);

        // Add popIn Id (used by pc78 for example)
        if ($this->isPopin) {
            $this->dataFromTransformer[$firstKey]['popintrancheid'] = $permanentId;
        }

        //add isMobile to all transformer result
        $this->dataFromTransformer['isMobile'] = $this->isMobile;
    }

    /**
     * Checks whether the block configuration enable it to be displayed or not.
     *
     * @param ReadBlockInterface $block
     *
     * @return bool
     */
    protected function isDisplayable(ReadBlockInterface $block)
    {
        $displayable = true;
        if (($this->isMobile && !$block->getZoneMobile()) || (!$this->isMobile && !$block->getZoneWeb())) {
            $displayable = false;
        }

        return $displayable;
    }

    /**
     * Additional tag Key/value to add to cache key
     * By default no tag is added. This function should be override by extending class when needed
     * Note: Use $this->manager->getTagManager() for generating tag for Header.
     *
     * @param PsaPageZoneConfigurableInterface $block
     *
     * @return array
     */
    protected function getAdditionalCachedTags(PsaPageZoneConfigurableInterface $block)
    {
        //default implementation
        return [];
    }

    /**
     * Use for slice personalized setting, call by the constructor
     * To be override by child class if need personalized setting.
     */
    protected function initStrategy()
    {
        //default implementation : do nothing
        return;
    }
}
