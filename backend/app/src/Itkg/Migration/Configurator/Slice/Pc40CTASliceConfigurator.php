<?php

namespace Itkg\Migration\Configurator\Slice;

use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use PSA\MigrationBundle\Entity\Cta\PsaCta;
use PSA\MigrationBundle\Entity\Page\PsaPageZone;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

/**
 * Class Pc40CTASliceConfigurator
 *
 * @package Itkg\Migration\Configurator
 */
class Pc40CTASliceConfigurator extends AbstractSliceConfigurator
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'PC40';
    }

    /**
     * create a new slice
     *
     * @param ShowroomUrlManager $urlManager
     * @param PsaPageShowroomMetadata $currentPage
     * @param AddReportingMessageInterface $reporting
     *
     * @return PsaPageZoneConfigurableInterface
     */
    public function create(
        ShowroomUrlManager $urlManager,
        PsaPageShowroomMetadata $currentPage,
        AddReportingMessageInterface $reporting
    )
    {
        $block = $this->entityFactory->createDynamicBlockForSliceId(
            $currentPage->getPage(),
            $currentPage->getDynamicBlocksZoneOrder(),
            $this->getName()
        );

        // Dark blue cta style
        $block->setZoneTool(PsaCta::STYLE_NIVEAU1);
        // Web Format: No visual
        $block->setZoneAffichage(PsaPageZone::ZONE_AFFICHAGE_CTA_NOVISUAL);

        return $block;
    }

}
