<?php

namespace Itkg\Migration\Configurator\Slice;

use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

/**
 * Class Pn14NavigationManuelSliceConfigurator
 *
 * @package Itkg\Migration\Configurator
 */
class Pn14NavigationManuelSliceConfigurator extends AbstractSliceConfigurator
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'PN14';
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
        $block = $this->entityFactory->createStaticBlockForSlideId(
            $currentPage->getPage(),
            $this->getName()
        );

        // Set parameters
        if ($urlManager->getUrlType() === ShowroomUrlManager::URL_TYPE_TECHNO_PUBLISHED) {
            $block->setZoneParameters(1);
        } else {
            $block->setZoneParameters(0);
        }

        return $block;
    }
}
