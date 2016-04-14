<?php


namespace Itkg\Migration\XML\EntityParser;

use DOMXPath;
use DOMElement;
use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\Transaction\PsaShowroomEntityFactory;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use Itkg\Transaction\PsaEntityFactory;
use PSA\MigrationBundle\Entity\Media\PsaMedia;
use PSA\MigrationBundle\Entity\Page\PsaPage;
use PSA\MigrationBundle\Entity\Page\PsaPageMultiZone;
use PSA\MigrationBundle\Entity\Page\PsaPageMultiZoneMulti;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMulti;
use PsaNdp\MappingBundle\Object\Block\Pc51Colonne;

/**
 * Class ShowroomXMLSlicePc5UneColonneParser
 * @package Itkg\Migration\XML\EntityParser
 */
class ShowroomXMLSlicePc5UneColonneParser extends AbstractShowroomXMLSliceParser
{
    const MAX_CTA = 4;
    const MAX_CTA_CHILD = 15;

    /**
     * @return string
     */
    public function getName()
    {
        return 'PC5';
    }

    /**
     * Init default setting for cta parsing
     * Method Overriding parent class default setting
     */
    protected function setDefaultCtaSetting()
    {
        $this->maxCta = self::MAX_CTA;
        $this->maxCtaChild = self::MAX_CTA_CHILD;
        $this->ctaReferenceType = PsaEntityFactory::CTA_REF_TYPE_LEVEL1;
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
        // Parse Multis with media
        $this->fillBlockMultis($block, $urlManager, $currentPage, $rootXPath, $sliceNode, $reporting);
        // Parse CTAs
        $articleNode = $this->xPathQuery->queryFirstDOMElement('./articles/article', $rootXPath, $sliceNode);
        $this->fillCtasForReferenceOwner($block, $urlManager, $currentPage, $rootXPath, $articleNode, $reporting);

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
    private function fillBlockMultis(PsaPageMultiZone $block, ShowroomUrlManager $urlManager, PsaPageShowroomMetadata $currentPage, DOMXPath $rootXPath, DOMElement $sliceNode, AddReportingMessageInterface $reporting)
    {
        $articleNode = $this->xPathQuery->queryFirstDOMElement('./articles/article', $rootXPath, $sliceNode);
        $title = $this->xPathQuery->queryFirstDOMElementNodeValue('./articles/article/title', $rootXPath, $sliceNode);
        $content = $this->xPathQuery->queryFirstDOMElementNodeValue('./articles/article/content', $rootXPath, $sliceNode);
        $page = $currentPage->getPage();
        $zoneOrder = $block->getZoneOrder();

        // Fill text
        $block->setZoneTitre($title);
        $block->setZoneTexte($this->updateTooltip($content));

        // Parse media
        $mediasParser = $this->xmlEntityParserFactory->createMediasParser($this->nodeType);
        if ($this->nodeType !== ShowroomXMLEntityParserFactory::WIDGET_TYPE_SINGLE_SLIDER) {
            $mediasParser->setMaxIndex(1);
        }
        /** @var PsaMedia[] $medias */
        $medias = $mediasParser->parse($urlManager, $currentPage, $rootXPath, $articleNode, $reporting);

        // Put media as a list in block multi
        // By default set block type of media to an image
        $block->setZoneParameters(Pc51Colonne::NO_MEDIA);
        switch ($this->nodeType) {
            case ShowroomXMLEntityParserFactory::WIDGET_TYPE_SINGLE_LARGE:
                if (isset($medias[0])) {
                    $media = $medias[0];
                    $multiTypeBlock = ($media->getMediaTypeId() === PsaShowroomEntityFactory::MEDIA_TYPE_IMAGE) ?
                        Pc51Colonne::VISUEL :
                        Pc51Colonne::VIDEO;
                    $block->setZoneParameters($multiTypeBlock);
                    $blockMulti = $this->createDynamicBlockMultiForMedia($media, $page, $zoneOrder, 1);
                    $blockMulti->setPageZoneMultiOrder(1);
                    $block->addMulti($blockMulti);
                }
                break;
            case ShowroomXMLEntityParserFactory::WIDGET_TYPE_SINGLE_PICTURE:
                // Set as picture by default
                if (isset($medias[0])) {
                    $media = $medias[0];
                    $blockMulti = $this->createDynamicBlockMultiForMedia($media, $page, $zoneOrder, 1);
                    $blockMulti->setPageZoneMultiOrder(1);
                    $block->addMulti($blockMulti);
                    $block->setZoneParameters( Pc51Colonne::VISUEL);
                }
                break;
            Case ShowroomXMLEntityParserFactory::WIDGET_TYPE_SINGLE_SLIDER:
                // Set as picture by default
                // add media
                $block->setZoneParameters( Pc51Colonne::VISUEL);
                foreach ($medias as $index => $media) {
                    $blockMulti = $this->createDynamicBlockMultiForMedia($media, $page, $zoneOrder, $index + 1);
                    $blockMulti->setPageZoneMultiOrder($index + 1);
                    $block->addMulti($blockMulti);
                }
                break;
        }

    }

    /**
     * @param PsaMedia $media
     * @param PsaPage $page
     * @param int $zoneOrder
     * @param int $multiId
     *
     * @return PsaPageMultiZoneMulti
     */
    private function createDynamicBlockMultiForMedia(PsaMedia $media, PsaPage $page, $zoneOrder, $multiId)
    {
        $multiType = ($media->getMediaTypeId() === PsaShowroomEntityFactory::MEDIA_TYPE_IMAGE) ?
            PsaPageZoneMulti::PAGE_ZONE_MULTI_VALUE_SLIDE_IMAGE :
            PsaPageZoneMulti::PAGE_ZONE_MULTI_VALUE_SLIDE_VIDEO;
        $blockMulti = $this->entityFactory->createDynamicBlockMulti($page, $zoneOrder, $multiId, $multiType);
        $blockMulti->setMedia($media);

        return $blockMulti;
    }

}
