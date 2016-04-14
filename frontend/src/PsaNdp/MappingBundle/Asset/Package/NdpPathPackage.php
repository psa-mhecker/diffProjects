<?php

namespace PsaNdp\MappingBundle\Asset\Package;

use PsaNdp\MappingBundle\Services\MediaServerInitializer;
use Symfony\Component\Asset\Context\ContextInterface;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;

/**
 * Class NdpPathPackage
 */
class NdpPathPackage extends Package
{
    /**
     * @var MediaServerInitializer
     */
    protected $mediaServer;

    /**
     * @param MediaServerInitializer   $mediaServerInitializer
     * @param VersionStrategyInterface $versionStrategy
     * @param ContextInterface         $context
     */
    public function __construct(
        MediaServerInitializer $mediaServerInitializer,
        VersionStrategyInterface $versionStrategy,
        ContextInterface $context = null
    )
    {
        parent::__construct($versionStrategy, $context);
        $this->mediaServer = $mediaServerInitializer;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function getUrl($path)
    {
        $path = sprintf('%s/design/frontend/%s', $this->mediaServer->getMediaServer(), $path);

        return $this->getVersionStrategy()->applyVersion($path);
    }
}
