<?php


namespace Itkg\Migration\XML\EntityParser;

use DOMXPath;
use DOMElement;
use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use PSA\MigrationBundle\Entity\Page\PsaPageMultiZone;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Transformers\Pf53FinitionsMotorisationDataTransformer;

/**
 * Class ShowroomXMLSlicePf53FinitionsMotorisationParser
 * @package Itkg\Migration\XML\EntityParser
 */
class ShowroomXMLSlicePf53FinitionsMotorisationParser extends AbstractShowroomXMLSliceParser
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'PF53';
    }

    /**
     * @param ShowroomUrlManager $urlManager showroom urlManager
     * @param PsaPageShowroomMetadata $currentPage
     * @param DOMXPath $rootXPath xml root using xpath
     * @param DOMElement $sliceNode
     * @param AddReportingMessageInterface $reporting
     *
     * @return PsaPageZoneConfigurableInterface
     */
    public function parse(ShowroomUrlManager $urlManager, PsaPageShowroomMetadata $currentPage, DOMXPath $rootXPath, DOMElement $sliceNode, AddReportingMessageInterface $reporting)
    {
        $block = $this->entityFactory->createDynamicBlockForSliceId(
            $currentPage->getPage(),
            $currentPage->getDynamicBlocksZoneOrder(),
            $this->getName()
        );
        // Parse block parameters
        $this->fillBlock($block, $urlManager, $currentPage, $rootXPath, $sliceNode, $reporting);

        return $block;
    }

    /**
     * @param PsaPageMultiZone $block
     * @param ShowroomUrlManager $urlManager
     * @param PsaPageShowroomMetadata $currentPage
     * @param DOMXPath $rootXPath
     * @param DOMElement $sliceNode
     */
    private function fillBlock(PsaPageMultiZone $block, ShowroomUrlManager $urlManager, PsaPageShowroomMetadata $currentPage, DOMXPath $rootXPath, DOMElement $sliceNode, AddReportingMessageInterface $reporting)
    {
        if ($this->nodeType === ShowroomXMLEntityParserFactory::WIDGET_TYPE_TECH_MOTORLIST) {
            // Motorisations
            $block->setZoneAttribut(Pf53FinitionsMotorisationDataTransformer::DISPLAY_MOTORISATIONS);
            // Activate Version Compare Table
            $block->setZoneAttribut2(2);
        } else {
            // Finitions
            $block->setZoneAttribut(Pf53FinitionsMotorisationDataTransformer::DISPLAY_FINITIONS);
            // Activate Version Compare Table
            $block->setZoneAttribut2(1);
        }
    }

}
