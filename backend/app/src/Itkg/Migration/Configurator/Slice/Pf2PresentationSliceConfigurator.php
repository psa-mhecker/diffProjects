<?php

namespace Itkg\Migration\Configurator\Slice;

use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use Itkg\Migration\XML\EntityParser\ShowroomXMLSlicePf2PresentationShowroomParser;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMulti;

/**
 * Class Pf2PresentationSliceConfigurator
 *
 * @package Itkg\Migration\Configurator
 */
class Pf2PresentationSliceConfigurator extends AbstractSliceConfigurator
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'PF2';
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

        // Get welcome page media if already existing
        $welcomePage = $currentPage->getFirstLeveLParent();
        if ($welcomePage->getShowroomBackgroundImg() !== null) {
            // Use welcome page picture
            $blockMulti = $this->entityFactory->createBlockMulti($block, 1, PsaPageZoneMulti::PAGE_ZONE_MULTI_VALUE_SLIDE_IMAGE);
            $blockMulti->setPageZoneMultiOrder(1);

            $blockMulti->setMedia($welcomePage->getShowroomBackgroundImg());
            $block->addMulti($blockMulti);
        }

        // Set parameters
        $block->setZoneParameters(ShowroomXMLSlicePf2PresentationShowroomParser::AFFICHAGE_MARKETING);
        $block->setZoneAttribut(ShowroomXMLSlicePf2PresentationShowroomParser::TYPE_VISUEL);
        $block->setZoneLabel2(ShowroomXMLSlicePf2PresentationShowroomParser::DISPLAY_RIGHT);

        return $block;
    }
}
