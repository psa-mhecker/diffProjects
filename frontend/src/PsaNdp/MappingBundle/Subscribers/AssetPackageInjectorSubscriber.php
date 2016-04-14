<?php

namespace PsaNdp\MappingBundle\Subscribers;

use \OpenOrchestra\ThemeBundle\EventSubscriber\AssetPackageInjectorSubscriber as BaseAssetPackageInjectorSubscriber;
use PsaNdp\MappingBundle\Asset\Package\NdpPathPackage;
use PsaNdp\MappingBundle\Services\MediaServerInitializer;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;

use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class AssetPackageInjectorSubscriber
 */
class AssetPackageInjectorSubscriber extends BaseAssetPackageInjectorSubscriber
{
    /**
     * @var MediaServerInitializer
     */
    protected  $mediaServer;

    /**
     * @param KernelInterface          $kernel
     * @param Packages                 $assetPackages
     * @param VersionStrategyInterface $versionStrategy
     * @param MediaServerInitializer   $mediaServerInitializer
     */
    public function __construct(
        KernelInterface $kernel,
        Packages $assetPackages,
        $version,
        MediaServerInitializer $mediaServerInitializer
    )
    {
        $this->kernel = $kernel;
        $this->assetsPackages = $assetPackages;
        $this->versionStrategy = new StaticVersionStrategy($version);
        $this->mediaServer = $mediaServerInitializer;
    }

    /**
     * Inject custom Asset package to Kernel assets helper
     */
    public function onKernelRequest()
    {
        foreach ($this->kernel->getBundles() as $bundle) {
            $bundlePathPackage = new NdpPathPackage($this->mediaServer, $this->versionStrategy);
            $this->assetsPackages->addPackage($bundle->getName(), $bundlePathPackage);
        }
    }
}
