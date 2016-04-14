<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\HttpFoundation\Request;
use PSA\MigrationBundle\Entity\Page\PsaPageZone;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMulti;
use PSA\MigrationBundle\Repository\PsaPageZoneMultiRepository;
use PsaNdp\MappingBundle\Exception\InvalidSliceConfigurationException;
use Symfony\Component\Routing\RouterInterface;

/**
 * Data source for Pt18PreHomeImportateur block
 */
class Pt18PreHomeImportateurDataSource extends AbstractDataSource
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var PsaPageZoneMultiRepository
     */
    private $pageZoneMultiRepository;

    /**
     * @param RouterInterface            $router
     * @param PsaPageZoneMultiRepository $pageZoneMultiRepository
     */
    public function __construct(RouterInterface $router, PsaPageZoneMultiRepository $pageZoneMultiRepository)
    {
        $this->router = $router;
        $this->pageZoneMultiRepository = $pageZoneMultiRepository;
    }

    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input BlockInterface and current url Request
     *
     * @param ReadBlockInterface $block
     * @param Request            $request
     * @param bool               $isMobile
     *
     * @return mixed
     * @throws InvalidSliceConfigurationException
     */

    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        /* @var $block PsaPageZone */
        $pageId = $block->getPageId();
        $langId = $block->getLangue()->getLangueId();
        $zoneTemplateId = $block->getZoneTemplateId();

        $data['importer1'] = $this->pageZoneMultiRepository->findPreHomeImporterByLangZoneTemplate(
            $langId,
            $pageId,
            $zoneTemplateId,
            PsaPageZoneMulti::PAGE_ZONE_MULTI_TYPE_IMPORTER1
        );

        if (empty($data['importer1'])) {
            throw new InvalidSliceConfigurationException('Importer 1 is not configured');
        }

        $data['importer2'] = $this->pageZoneMultiRepository->findPreHomeImporterByLangZoneTemplate(
            $langId,
            $pageId,
            $zoneTemplateId,
            PsaPageZoneMulti::PAGE_ZONE_MULTI_TYPE_IMPORTER2
        );

        if (empty($data['importer2'])) {
            throw new InvalidSliceConfigurationException('Importer 2 is not configured');
        }

        return $data;
    }
}
