<?php

namespace PsaNdp\MappingBundle\Sources;

use Symfony\Component\HttpFoundation\Request;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Repository\PsaPageRepository;

/**
 * Data source for Pt20MasterPage block
 */
class Pt20MasterPageDataSource extends AbstractDataSource
{
    /**
     * @var PsaPageRepository
     */
    private $pageRepository;

    /**
     * @param PsaPageRepository $pageRepository
     */
    public function __construct(PsaPageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input BlockInterface and current url Request
     *
     * @param BlockInterface $block
     * @param Request        $request  Current url request displaying th block
     * @param bool           $isMobile Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        /* @var $block PsaPageZoneConfigurableInterface */
        $data['block'] = $block;
        $data['subPages'] = $this->pageRepository->findSubPagesByPage($block->getPage());

        return $data;
    }

}
