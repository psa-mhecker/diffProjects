<?php


namespace Itkg\Migration\XML\EntityParser;

use DOMXPath;
use DOMElement;
use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use Itkg\Transaction\PsaEntityFactory;
use PSA\MigrationBundle\Entity\Media\PsaMedia;
use PSA\MigrationBundle\Entity\Page\PsaPageMultiZone;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Transformers\Pc9Contenu1Article1VisuelDataTransformer;

/**
 * Class ShowroomXMLSlicePc9Contenu1Article1VisuelParser
 * @package Itkg\Migration\XML\EntityParser
 */
class ShowroomXMLSlicePc9Contenu1Article1VisuelParser extends AbstractShowroomXMLSliceParser
{
    const MAX_CTA = 4;
    const MAX_CTA_CHILD = 15;

    /**
     * @return string
     */
    public function getName()
    {
        return 'PC9';
    }

    /**
     * Init default setting for cta parsing
     * Method Overriding parent class default setting
     */
    protected function setDefaultCtaSetting()
    {
        $this->maxCta = self::MAX_CTA;
        $this->maxCtaChild = self::MAX_CTA_CHILD;
        $this->ctaReferenceType = PsaEntityFactory::CTA_REF_TYPE_LEVELCTA;
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
    public function parse(ShowroomUrlManager $urlManager, PsaPageShowroomMetadata $currentPage, DOMXPath $rootXPath, DOMElement $sliceNode, AddReportingMessageInterface $reporting = null)
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
        $articleNode = $this->xPathQuery->queryFirstDOMElement('./articles/article', $rootXPath, $sliceNode);
        $title = $this->xPathQuery->queryFirstDOMElementNodeValue('./articles/article/title', $rootXPath, $sliceNode);
        $content = $this->xPathQuery->queryFirstDOMElementNodeValue('./articles/article/content', $rootXPath, $sliceNode);
        $page = $currentPage->getPage();
        $zoneOrder = $block->getZoneOrder();
        $blockVisualSize = Pc9Contenu1Article1VisuelDataTransformer::SIZE_600_FORMAT;
        $multiVisualSize = Pc9Contenu1Article1VisuelDataTransformer::MULTI_TYPE_684;

        // Fill text
        $block->setZoneTitre($title);
        $block->setZoneTexte($this->updateTooltip($content));

        // Set Default parameters
        $block->setZoneAttribut($blockVisualSize);
        switch ($this->nodeType) {
            case ShowroomXMLEntityParserFactory::WIDGET_TYPE_SINGLE_LEFT_BASIC:
            case ShowroomXMLEntityParserFactory::WIDGET_TYPE_SINGLE_LEFT_ADVANCED:
            case ShowroomXMLEntityParserFactory::WIDGET_TYPE_SINGLE_SLIDER_LEFT:
                $block->setZoneParameters(Pc9Contenu1Article1VisuelDataTransformer::SLIDE_LEFT);
                break;
            case ShowroomXMLEntityParserFactory::WIDGET_TYPE_SINGLE_RIGHT_BASIC:
            case ShowroomXMLEntityParserFactory::WIDGET_TYPE_SINGLE_RIGHT_ADVANCED:
            case ShowroomXMLEntityParserFactory::WIDGET_TYPE_SINGLE_SLIDER_RIGHT:
                $block->setZoneParameters(Pc9Contenu1Article1VisuelDataTransformer::SLIDE_RIGHT);
                break;
        }

        // Parse media
        $mediasParser = $this->xmlEntityParserFactory->createMediasParser($this->nodeType);
        if ($this->nodeType !== ShowroomXMLEntityParserFactory::WIDGET_TYPE_SINGLE_SLIDER_LEFT &&
            $this->nodeType !== ShowroomXMLEntityParserFactory::WIDGET_TYPE_SINGLE_SLIDER_RIGHT) {
            $mediasParser->setMaxIndex(1);
        }
        /** @var PsaMedia[] $medias */
        $medias = $mediasParser->parse($urlManager, $currentPage, $rootXPath, $articleNode, $reporting);

        // Put media as a list in block multi
        foreach ($medias as $index => $media) {
            $blockMulti = $this->entityFactory->createDynamicBlockMulti($page, $zoneOrder, $index+1, $multiVisualSize);
            $blockMulti->setMedia($media);
            $block->addMulti($blockMulti);
        }

        // Parse CTA
        $this->fillCtasForReferenceOwner($block, $urlManager, $currentPage, $rootXPath, $articleNode, $reporting);
    }

}
