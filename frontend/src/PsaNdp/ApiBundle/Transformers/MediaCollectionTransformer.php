<?php

namespace PsaNdp\ApiBundle\Transformers;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\ApiBundle\Facade\MediaCollectionFacade;

/**
 * Class MediaCollectionTransformer
 */
class MediaCollectionTransformer extends AbstractTransformer
{
    /**
     * @param mixed $mixed
     *
     * @return FacadeInterface
     */
    public function transform($mixed)
    {
        $medias = new MediaCollectionFacade();

        foreach ($mixed as $media) {
            $medias->addMedia($this->getTransformer('media')->transform($media));
        }

        return $medias;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'media_collection';
    }
}
