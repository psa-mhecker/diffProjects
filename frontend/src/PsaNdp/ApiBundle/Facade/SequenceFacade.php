<?php

namespace PsaNdp\ApiBundle\Facade;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class SequenceFacade
 */
class SequenceFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("integer")
     */
    public $startFrame;

    /**
     * @Serializer\Type("integer")
     */
    public $frames;

    /**
     * @Serializer\Type("array<PsaNdp\ApiBundle\Facade\MediaFacade>")
     */
    public $source = array();

    /**
     * @param FacadeInterface $media
     */
    public function addMedia(FacadeInterface $media)
    {
        $this->source[] = $media;
    }
}
