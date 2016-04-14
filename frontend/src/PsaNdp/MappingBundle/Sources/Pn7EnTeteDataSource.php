<?php

namespace PsaNdp\MappingBundle\Sources;

use PsaNdp\MappingBundle\Services\ShareServices\ShareObjectService;
use Symfony\Component\HttpFoundation\Request;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PsaNdp\MappingBundle\Utils\StreamlikeMedia;
use PsaNdp\MappingBundle\Utils\PageUtils;

/**
 *
 */
class Pn7EnTeteDataSource extends AbstractDataSource
{
    /**
     * @var ShareObjectService
     */
    protected $share;
    /**
     * @var StreamlikeMedia
     */
    private $streamlikeMedia;

    /**
     * @var PageUtils
     */
    private $pageUtils;

    /**
     * @param StreamlikeMedia    $streamlikeMedia
     * @param PageUtils          $pageUtils
     * @param ShareObjectService $share
     */
    public function __construct(StreamlikeMedia $streamlikeMedia, PageUtils $pageUtils, ShareObjectService $share)
    {
        $this->streamlikeMedia = $streamlikeMedia;
        $this->pageUtils = $pageUtils;
        $this->share = $share;
    }

    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input BlockInterface and current url Request.
     *
     * @param ReadBlockInterface $block
     * @param Request            $request  Current url request displaying th block
     * @param bool               $isMobile Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        $data = [];
        /* @var $block PsaPageZoneConfigurableInterface */
        $data['block'] = $block;
        /* @TODO Perhaps try to use MediaFactory in the Object ... */
        $media = $block->getMedia();
        if ($media && $media->isStreamlike()) {
            $data['streamlike'] = $this->streamlikeMedia->get($media->getMediaRemoteId());
        }
        $data['breadcrumb'] = $this->pageUtils->getBreadcumb($block->getPage());
        $data['isMobile'] = $isMobile;
        $data['myPeugeot'] = $this->share->getMyPeugeot();

        return $data;
    }
}
