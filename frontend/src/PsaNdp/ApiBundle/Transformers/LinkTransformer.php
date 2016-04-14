<?php
namespace PsaNdp\ApiBundle\Transformers;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\ApiBundle\Facade\LinkFacade;

/**
 * Class LinkTransformer
 */
class LinkTransformer extends AbstractTransformer
{
    /**
     * @param array $mixed
     *
     * @return FacadeInterface
     */
    public function transform($mixed)
    {
        $link = new LinkFacade();

        if (isset($mixed['url'])) {
            $link->url = $mixed['url'];
        }

        if (isset($mixed['title'])) {
            $link->title = $mixed['title'];
        }

        return $link;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'link';
    }
}
