<?php


namespace Itkg\Migration\XML\EntityParser;

use DOMXPath;
use DOMElement;
use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use PSA\MigrationBundle\Entity\Media\PsaMedia;
use PSA\MigrationBundle\Entity\Page\PsaPageMultiZone;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMulti;

/**
 * Class ShowroomXMLSlicePc12TroisColonnesTexteParser
 * @package Itkg\Migration\XML\EntityParser
 */
class ShowroomXMLSlicePc12TroisColonnesTexteParser extends AbstractShowroomXMLSliceParser
{
    const MAX_CTA_PER_COLUMN = 3;
    const MAX_CTA_CHILD_PER_COLUMN = 15;

    /**
     * @return string
     */
    public function getName()
    {
        return 'PC12';
    }

    /**
     * Init default setting for cta parsing
     * Method Overriding parent class default setting
     */
    protected function setDefaultCtaSetting()
    {
        $this->maxCta = self::MAX_CTA_PER_COLUMN;
        $this->maxCtaChild = self::MAX_CTA_CHILD_PER_COLUMN;
        $this->ctaReferenceType = null;
        $this->ctaReferenceTypeGenerator = ShowroomXMLSliceCtasParser::TYPE_COLUMN;
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
        $this->fillBlock($block, $urlManager, $currentPage, $rootXPath, $sliceNode, $reporting);

        return $block;
    }

    /**
     * @param PsaPageMultiZone $block
     * @param ShowroomUrlManager $urlManager
     * @param PsaPageShowroomMetadata $currentPage
     * @param DOMXPath $rootXPath
     * @param DOMElement $sliceNode
     * @param AddReportingMessageInterface $reporting
     */
    private function fillBlock(PsaPageMultiZone $block, ShowroomUrlManager $urlManager, PsaPageShowroomMetadata $currentPage, DOMXPath $rootXPath, DOMElement $sliceNode, AddReportingMessageInterface $reporting)
    {
        $columnNodes = $rootXPath->query('./articles/article', $sliceNode);
        $page = $currentPage->getPage();
        $zoneOrder = $block->getZoneOrder();

        for($index = 0; $index <= 2; $index++) {
            $colNumber = $index + 1;
            $multiType = PsaPageZoneMulti::PAGE_ZONE_MULTI_TYPE_CONTENT_NDP_COLUMN . $colNumber;
            $blockMulti = $this->entityFactory->createDynamicBlockMulti($page, $zoneOrder, $colNumber, $multiType);

            // Fill column multi data if existing
            if ($columnNodes->length >= $index) {
                /** @var DOMElement $columnNode */
                $columnNode = $columnNodes->item($index);
                $title = $this->xPathQuery->queryFirstDOMElementNodeValue('./title', $rootXPath, $columnNode);
                $content = $this->xPathQuery->queryFirstDOMElementNodeValue('./content', $rootXPath, $columnNode);

                // Fill text
                $blockMulti->setPageZoneMultiTitre2($title);
                $blockMulti->setPageZoneMultiText($this->updateTooltip($content));

                // Parse media
                $mediasParser = $this->xmlEntityParserFactory->createMediasParser($this->nodeType);
                $mediasParser->setMaxIndex(1);

                /** @var PsaMedia[] $medias */
                $medias = $mediasParser->parse($urlManager, $currentPage, $rootXPath, $columnNode, $reporting);
                if (isset($medias[0])) {
                    $blockMulti->setMedia($medias[0]);
                }

                // Parse CTA
                $this->fillCtasForReferenceOwner(
                    $block, $urlManager, $currentPage, $rootXPath, $columnNode,$reporting, $colNumber, $colNumber
                );
            }

            $block->addMulti($blockMulti);
        }

    }

}
