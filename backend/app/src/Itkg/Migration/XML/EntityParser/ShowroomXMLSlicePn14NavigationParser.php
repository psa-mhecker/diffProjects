<?php


namespace Itkg\Migration\XML\EntityParser;

use DOMXPath;
use DOMElement;
use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

/**
 * Class ShowroomXMLSlicePn14NavigationParser
 * @package Itkg\Migration\XML\EntityParser
 */
class ShowroomXMLSlicePn14NavigationParser extends AbstractShowroomXMLSliceParser
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'PN14';
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
