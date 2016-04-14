<?php

namespace PsaNdp\MappingBundle\DisplayBlock;

use FOS\HttpCache\Handler\TagHandler;
use OpenOrchestra\BaseBundle\Context\CurrentSiteIdInterface;
use OpenOrchestra\BaseBundle\Manager\TagManager;
use OpenOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use OpenOrchestra\DisplayBundle\DisplayBlock\DisplayBlockManager;
use OpenOrchestra\DisplayBundle\Exception\DisplayBlockStrategyNotFoundException;
use OpenOrchestra\DisplayBundle\Manager\CacheableManager;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PsaNdp\CacheBundle\KeyGenerators\StrategyKeyGenerator;
use PsaNdp\CacheBundle\Services\CacheService;
use PsaNdp\MappingBundle\Manager\PsaCacheableManager;
use PsaNdp\MappingBundle\Manager\PsaTagManager;
use PsaNdp\MappingBundle\Utils\AnchorUtils;
use PsaNdp\MappingBundle\Services\MediaServerInitializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;
use PSA\MigrationBundle\Repository\PsaSiteCodeRepository;
use PsaNdp\MappingBundle\Manager\BlockManager;
use PsaNdp\MappingBundle\Utils\DeviceUtils;

/**
 * Class PsaDisplayBlockManager
 * @package PsaNdp\MappingBundle\DisplayBlock
 */
class PsaDisplayBlockManager extends DisplayBlockManager
{

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var string
     */
    protected $mediaServer;

    /**
     * @var PsaSiteCodeRepository
     */
    protected $siteCodeRepository;

    /**
     * @var BlockManager
     */
    protected $blockManager;

    /**
     * @var AnchorUtils
     */
    protected $anchorUtils;

    /**
     * @var DeviceUtils
     */
    protected $deviceUtils;

    /**
     * @param EngineInterface        $templating
     * @param CacheableManager       $cacheableManager
     * @param PsaSiteCodeRepository  $siteCodeRepository
     * @param TranslatorInterface    $translator
     * @param MediaServerInitializer $mediaServerInitializer
     * @param TagManager             $tagManager
     * @param CurrentSiteIdInterface $currentSiteIdInterface
     * @param BlockManager           $blockManager
     * @param AnchorUtils            $anchorUtils
     * @param DeviceUtils            $deviceUtils
     */
    public function __construct(
        EngineInterface $templating,
        CacheableManager $cacheableManager,
        PsaSiteCodeRepository $siteCodeRepository,
        TranslatorInterface $translator,
        MediaServerInitializer $mediaServerInitializer,
        TagManager $tagManager,
        CurrentSiteIdInterface $currentSiteIdInterface,
        BlockManager $blockManager,
        AnchorUtils $anchorUtils,
        DeviceUtils $deviceUtils
    )
    {
        parent::__construct($templating, $cacheableManager,$tagManager, $currentSiteIdInterface);
        $this->translator = $translator;
        $this->mediaServer = $mediaServerInitializer->getMediaServer();
        $this->siteCodeRepository = $siteCodeRepository;
        $this->blockManager = $blockManager;
        $this->anchorUtils = $anchorUtils;
        $this->deviceUtils = $deviceUtils;
    }

    /**
     * Perform the show action for a block
     *
     * @param ReadBlockInterface $block
     *
     * @throws DisplayBlockStrategyNotFoundException
     *
     * @return Response
     */
    public function show(ReadBlockInterface $block)
    {
        if (isset($this->strategies[$block->getComponent()])) {
            $response = $this->strategies[$block->getComponent()]->show($block);

            return $response;
        }

        throw new DisplayBlockStrategyNotFoundException($block->getComponent());
    }

    /**
     * @return PsaCacheableManager
     */
    public function getCacheableManager()
    {
        return $this->cacheableManager;
    }

    /**
     * @return PsaTagManager
     */
    public function getTagManager()
    {
        return $this->tagManager;
    }

    /**
     * @return TranslatorInterface
     */
    public function getTranslator()
    {

        return $this->translator;
    }

    /**
     * @return string
     */
    public function getMediaServer()
    {

        return $this->mediaServer;
    }

    /**
     * @return PsaSiteCodeRepository
     */
    public function getSiteCodeRepository()
    {
        return $this->siteCodeRepository;
    }

    /**
     * @return BlockManager
     */
    public function getBlockManager()
    {
        return $this->blockManager;
    }

    /**
     * @param BlockManager $blockManager
     *
     * @return PsaDisplayBlockManager
     */
    public function setBlockManager($blockManager)
    {
        $this->blockManager = $blockManager;

        return $this;
    }

    /**
     * @return DeviceUtils
     */
    public function getDeviceUtils()
    {
        return $this->deviceUtils;
    }

    /**
     * @return AnchorUtils
     */
    public function getAnchorUtils()
    {
        return $this->anchorUtils;
    }

    /**
     * @return TagHandler
     */
    public function getTagHandler()
    {
        return $this->tagHandler;
    }

}
