<?php

namespace PsaNdp\MappingBundle\Object;
use PsaNdp\MappingBundle\Object\configuration\Pf53Configuration1;
use PsaNdp\MappingBundle\Object\configuration\Pf53Configuration2;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;

/**
 * Class Transition
 */
class Transition extends Content
{
    protected $mapping = array(
        'visu' => 'media',
        'v1' => 'configuration1',
        'v2' => 'configuration2',
    );

    /**
     * @var Media $media
     */
    protected $media;

    /**
     * @var Pf53Configuration1 $configuration1
     */
    protected $configuration1;

    /**
     * @var Pf53Configuration2 $configuration2
     */
    protected $configuration2;

    /**
     * @param MediaFactory $mediaFactory
     * @param Pf53Configuration1 $configuration1
     * @param Pf53Configuration2 $configuration2
     */
    public function __construct(
        MediaFactory $mediaFactory,
        Pf53Configuration1 $configuration1,
        Pf53Configuration2 $configuration2
    )
    {
        parent::__construct();
        $this->media = $mediaFactory->createMedia();
        $this->configuration1 = $configuration1;
        $this->configuration2 = $configuration2;
    }

    /**
     * @return Pf53Configuration1
     */
    public function getConfiguration1()
    {
        return $this->configuration1;
    }

    /**
     * @param Pf53Configuration1 $configuration1
     */
    public function setConfiguration1($configuration1)
    {
        $this->configuration1 = $configuration1;
    }

    /**
     * @return Pf53Configuration2
     */
    public function getConfiguration2()
    {
        return $this->configuration2;
    }

    /**
     * @param Pf53Configuration2 $configuration2
     */
    public function setConfiguration2($configuration2)
    {
        $this->configuration2 = $configuration2;
    }

    /**
     * @return Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param Media $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }
}
