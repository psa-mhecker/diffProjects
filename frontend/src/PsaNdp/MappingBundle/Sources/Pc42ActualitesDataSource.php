<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\HttpFoundation\Request;
use PSA\MigrationBundle\Entity\Page\PsaPageZone;
use PSA\MigrationBundle\Repository\PsaContentRepository;

/**
 * Data source for Pc42Actualites block
 */
class Pc42ActualitesDataSource extends AbstractDataSource
{
    /**
     * @var PsaContentRepository
     */
    private $contentRepository;

    /**
     * @param PsaContentRepository $contentRepository
     */
    public function __construct(PsaContentRepository $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }

    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input BlockInterface and current url Request
     *
     * @param ReadBlockInterface $block
     * @param Request            $request  Current url request displaying th block
     * @param bool               $isMobile Indicate if is a mobile display

     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        /* @var $block PsaPageZone */
        $data['block'] = $block;

        //@TODO: récupérer ces valeurs via $request ou $block
        $siteId =  $block->getPage()->getSiteId();
        $langId = $block->getLangueId();
        $contentIds = [];

        $data = $this->contentRepository
            ->findNewsContentBySiteLangAndContent($siteId, $langId, $contentIds);

        return $data;
    }
}
