<?php

namespace PsaNdp\ApiBundle\Facade;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class MediaCollectionFacade
 */
class MediaCollectionFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("array<PsaNdp\ApiBundle\Facade\MediaFacade>")
     */
    protected $medias = array();

    /**
     * @param FacadeInterface $media
     */
    public function addMedia(FacadeInterface $media)
    {
        $this->medias[] = $media;
    }
}
