<?php


namespace Itkg\Migration\XML\EntityParser;

use DOMXPath;
use DOMElement;
use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\Transaction\PsaShowroomEntityFactory;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use PSA\MigrationBundle\Entity\Page\PsaPageMultiZone;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMulti;

/**
 * Class ShowroomXMLSlicePc77DimensionVehiculeParser
 * @package Itkg\Migration\XML\EntityParser
 */
class ShowroomXMLSlicePc77DimensionVehiculeParser extends AbstractShowroomXMLSliceParser
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'PC77';
    }

    /**
     * @param ShowroomUrlManager            $urlManager showroom urlManager
     * @param PsaPageShowroomMetadata       $currentPage
     * @param DOMXPath                      $rootXPath xml root using xpath
     * @param DOMElement                    $sliceNode
     * @param AddReportingMessageInterface  $reporting
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

        // Fill block for media
        $this->fillBlock($block, $urlManager, $currentPage, $rootXPath, $sliceNode, $reporting);

        return $block;
    }

    /**
     * @param PsaPageMultiZone              $block
     * @param ShowroomUrlManager            $urlManager
     * @param PsaPageShowroomMetadata       $currentPage
     * @param DOMXPath                      $rootXPath
     * @param DOMElement                    $sliceNode
     * @param AddReportingMessageInterface  $reporting
     */
    private function fillBlock(PsaPageMultiZone $block, ShowroomUrlManager $urlManager, PsaPageShowroomMetadata $currentPage, DOMXPath $rootXPath, DOMElement $sliceNode, AddReportingMessageInterface $reporting)
    {
        $columnNodes = $rootXPath->query('./articles/article', $sliceNode);
        $page = $currentPage->getPage();
        $zoneOrder = $block->getZoneOrder();
        $blockTitle = $this->xPathQuery->queryFirstDOMElementNodeValue('./title', $rootXPath, $sliceNode);
        $block->setZoneTitre($blockTitle);
        // Parse max 4 visual
        for($index = 0; $index <= 3; $index++) {
            $colNumber = $index + 1;
            $multiType = PsaPageZoneMulti::PAGE_ZONE_MULTI_TYPE_VISUALS;

            // Fill column multi data if existing
            if ($columnNodes->length > $index) {
                $blockMulti = $this->entityFactory->createDynamicBlockMulti($page, $zoneOrder, $colNumber, $multiType);
                /** @var DOMElement $columnNode */
                $columnNode = $columnNodes->item($index);
                $title = $this->xPathQuery->queryFirstDOMElementNodeValue('./title', $rootXPath, $columnNode);
                $text = $this->xPathQuery->queryFirstDOMElementNodeValue('./short_title', $rootXPath, $columnNode) .
                        $this->xPathQuery->queryFirstDOMElementNodeValue('./main_header', $rootXPath, $columnNode);

                // Fill text
                $blockMulti->setPageZoneMultiTitre($title);
                $blockMulti->setPageZoneMultiText($this->updateTooltip($text));

                // Parse media
                $mediaPath1 = $this->xPathQuery->queryFirstDOMElementNodeValue('article_media1', $rootXPath, $columnNode);
                $mediaPath2 = $this->xPathQuery->queryFirstDOMElementNodeValue('article_thumb1', $rootXPath, $columnNode);

                if ($mediaPath1 !== '') {
                    $mediaAlt1 = $this->xPathQuery->queryFirstDOMElementNodeValue('article_alt_media1', $rootXPath, $columnNode);
                    $media1 = $this->entityFactory->createMedia(PsaShowroomEntityFactory::MEDIA_TYPE_IMAGE, $urlManager->getUser());
                    $media1->setMediaPath($urlManager->generateMediaUrl($mediaPath1));
                    $media1->setMediaTranslation(
                        $this->entityFactory->createMediaAltTranslation($media1, $urlManager->getLanguage(), $mediaAlt1)
                    );

                    // Add Media to library and multi
                    $currentPage->addMedia($media1);
                    $blockMulti->setMedia($media1, $this->nodeType);
                }
                if ($mediaPath2 !== '') {
                    $mediaAlt2 = $this->xPathQuery->queryFirstDOMElementNodeValue('article_alt_media2', $rootXPath, $columnNode);
                    $media2 = $this->entityFactory->createMedia(PsaShowroomEntityFactory::MEDIA_TYPE_IMAGE, $urlManager->getUser());
                    $media2->setMediaPath($urlManager->generateMediaUrl($mediaPath2));
                    $media2->setMediaTranslation(
                        $this->entityFactory->createMediaAltTranslation($media2, $urlManager->getLanguage(), $mediaAlt2)
                    );

                    // Add Media to library and multi
                    $currentPage->addMedia($media2);
                    $blockMulti->setMediaId2($media2, $this->nodeType);
                }
                $block->addMulti($blockMulti);
            }


        }
    }

}
