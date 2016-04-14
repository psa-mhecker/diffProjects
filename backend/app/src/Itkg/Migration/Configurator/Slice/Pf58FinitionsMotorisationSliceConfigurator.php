<?php

namespace Itkg\Migration\Configurator\Slice;

use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

/**
 * Class Pf58FinitionsMotorisationSliceConfigurator
 *
 * @package Itkg\Migration\Configurator
 */
class Pf58FinitionsMotorisationSliceConfigurator extends Pf53FinitionsMotorisationSliceConfigurator
{
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
        $block = parent::create($urlManager, $currentPage, $reporting);

        // Activate Finition display
        $block->setZoneAttribut(2);

        return $block;
    }
}
