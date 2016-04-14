<?php

namespace Itkg\Migration\Configurator\Slice;

use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Object\Block\Pc79MurMediaManuel;

/**
 * Class Pc79MurMediaManuelSliceConfigurator
 *
 * @package Itkg\Migration\Configurator
 */
class Pc79MurMediaManuelSliceConfigurator extends AbstractSliceConfigurator
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'PC79';
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
        // Activate link
        $block->setZoneParameters(1);

        return $block;
    }
}
