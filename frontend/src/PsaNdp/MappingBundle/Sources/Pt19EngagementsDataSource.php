<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\HttpFoundation\Request;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneContent;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneContentConfigurableInterface;
use PSA\MigrationBundle\RepositoryDispatcher\PsaPageZoneConfigurableRepositoryDispatcher;

/**
 * Data source for Pt19Engagements block
 */
class Pt19EngagementsDataSource extends AbstractDataSource
{
    /**
     * @var PsaPageZoneConfigurableRepositoryDispatcher
     */
    private $pageZoneConfigurableRepositoryDispatcher;

    /**
     * @param PsaPageZoneConfigurableRepositoryDispatcher $pageZoneConfigurableRepositoryDispatcher
     */
    public function __construct(PsaPageZoneConfigurableRepositoryDispatcher $pageZoneConfigurableRepositoryDispatcher)
    {
        $this->pageZoneConfigurableRepositoryDispatcher = $pageZoneConfigurableRepositoryDispatcher;
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
        /** @var PsaPageZoneContentConfigurableInterface $block */
        $data['zone'] = $block;

        $zoneParameters = array(
            PsaPageZoneContent::PAGE_ZONE_CONTENT_PARAM_LEFT,
            PsaPageZoneContent::PAGE_ZONE_CONTENT_PARAM_MIDDLE,
            PsaPageZoneContent::PAGE_ZONE_CONTENT_PARAM_RIGHT
        );

        $this->repository = $this->pageZoneConfigurableRepositoryDispatcher->getPsaPageZoneContentConfigurableRepository($block);
        $contents = $this->repository
            ->findAllPageZoneContentConfigurableByPageZoneConfigurableAndParameters($data['zone'], $zoneParameters);
        $data['contents'] = [];
        foreach ($contents as $content) {
            if ($content->getContent()) {
                $data['contents'][$content->getPageZoneParameters()] = $content->getContent()->getVersion();
            }
        }
        ksort($data['contents']);
        
        return $data;
    }
}
