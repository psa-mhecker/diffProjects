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
 * Class ShowroomXMLSlicePc69Contenu2ColonnesParser
 * @package Itkg\Migration\XML\EntityParser
 */
class ShowroomXMLSlicePc69Contenu2ColonnesParser extends AbstractShowroomXMLSliceParser
{
    const XML_TYPE_3_4 = 'big_part';
    const DISPLAY_FIRST_THREE_FOURTH = 1;
    const DISPLAY_FIRST_ONE_FOURTH = 2;
    const MAX_CTA_PER_COLUMN = 2;
    const MAX_CTA_CHILD_PER_COLUMN = 15;

    /**
     * @return string
     */
    public function getName()
    {
        return 'PC69';
    }

    /**
     * Init default setting for cta parsing
     * Method Overriding parent class default setting
     */
    protected function setDefaultCtaSetting()
    {
        $this->maxCta = self::MAX_CTA_PER_COLUMN;
        $this->maxCtaChild = self::MAX_CTA_CHILD_PER_COLUMN;
        $this->ctaReferenceType = null; // To be filled dynamically during parsing
        $this->ctaReferenceTypeGenerator = null;
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
        $doubleColumnNodes = $rootXPath->query('./articles/article', $sliceNode);
        $page = $currentPage->getPage();
        $zoneOrder = $block->getZoneOrder();

        $orderThreeFourth = 0;
        for($index = 0; $index <= 1; $index++) {
            $colNumber = $index + 1;
            $blockMulti = $this->entityFactory->createDynamicBlockMulti($page, $zoneOrder, $colNumber, null);

            // Fill column multi data if existing
            if ($doubleColumnNodes->length >= $index) {
                /** @var DOMElement $columnNode */
                $columnNode = $doubleColumnNodes->item($index);
                $title = $this->xPathQuery->queryFirstDOMElementNodeValue('./title', $rootXPath, $columnNode);
                $content = $this->xPathQuery->queryFirstDOMElementNodeValue('./content', $rootXPath, $columnNode);
                $articleType = $this->xPathQuery->queryFirstDOMElementNodeValue('./article_type', $rootXPath, $columnNode);

                if ($articleType === self::XML_TYPE_3_4) {
                    $orderThreeFourth = $index;
                }
                $multiType = ($articleType === self::XML_TYPE_3_4) ? PsaPageZoneMulti::PAGE_ZONE_MULTI_TYPE_CONTENT_COLUMN_3_4 : PsaPageZoneMulti::PAGE_ZONE_MULTI_TYPE_CONTENT_COLUMN_1_4;
                $blockMulti->setPageZoneMultiType($multiType);

                // Fill text
                $blockMulti->setPageZoneMultiTitre($title);
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
                $multiTypeCta = ($articleType === self::XML_TYPE_3_4) ? PsaPageZoneMulti::PAGE_ZONE_MULTI_TYPE_CONTENT_COLUMN_3_4_CTA : PsaPageZoneMulti::PAGE_ZONE_MULTI_TYPE_CONTENT_COLUMN_1_4_CTA;
                $this->ctaReferenceType = $multiTypeCta;
                $this->fillCtasForReferenceOwner($block, $urlManager, $currentPage, $rootXPath, $columnNode, $reporting);
            }

            $block->addMulti($blockMulti);
        }

        $columnDisplay = ($orderThreeFourth === 0) ? self::DISPLAY_FIRST_THREE_FOURTH : self::DISPLAY_FIRST_ONE_FOURTH;
        $block->setZoneParameters($columnDisplay);
    }

}
