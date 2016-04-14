<?php

namespace Itkg\Migration\Configurator\Slice;

use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

/**
 * Class Pc60RecapitulatifEtCtaConfigurator
 *
 * @package Itkg\Migration\Configurator
 */
class Pc60RecapitulatifEtCtaConfigurator extends AbstractSliceConfigurator
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'PC60';
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
        // Active configure CTA
        $block->setZoneAttribut(1);
        // De-Active configure CTA
        $block->setZoneAttribut2(0);


        return $block;
    }
}
