<?php

namespace PsaNdp\ApiBundle\Transformers;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\ApiBundle\Facade\MediaFacade;

/**
 * Class MediaTransformer
 */
class MediaTransformer extends AbstractTransformer
{
    /**
     * @param mixed $mixed
     *
     * @return FacadeInterface
     */
    public function transform($mixed)
    {
        $media = new MediaFacade();

        $media->src = $mixed['src'];
        $media->alt = $mixed['alt'];

        return $media;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'media';
    }
}
