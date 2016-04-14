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
use PsaNdp\MappingBundle\Transformers\Pc68Contenu1Article2Ou3VisuelsDataTransformer;

/**
 * Class ShowroomXMLSlicePc68Contenu1Article2Ou3VisuelsParser
 * @package Itkg\Migration\XML\EntityParser
 */
class ShowroomXMLSlicePc68Contenu1Article2Ou3VisuelsParser extends AbstractShowroomXMLSliceParser
{
    const MAX_CTA = 4;
    const MAX_CTA_CHILD = 15;

    /**
     * @return string
     */
    public function getName()
    {
        return 'PC68';
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

        // Fill text
        $block->setZoneTitre($title);
        $block->setZoneTexte($this->updateTooltip($content));

        // Parse media
        $mediasParser = $this->xmlEntityParserFactory->createMediasParser($this->nodeType);
        $mediasParser->setMaxIndex(3);
        /** @var PsaMedia[] $medias */
        $medias = $mediasParser->parse($urlManager, $currentPage, $rootXPath, $articleNode, $reporting);
        // Put media 1 to 3 in block
        for ($i = 0; $i < 3; $i++) {
            $setterIndex = $i + 1;
            if (isset($medias[$i])) {
                if ($setterIndex > 1) {
                    $setter = 'setMedia' . $setterIndex;
                } else {
                    $setter = 'setMedia';
                }
                $block->$setter($medias[$i]);
            }
        }

        // Set block parameters
        $visuelsNumberType = count($medias) === 3 ? Pc68Contenu1Article2Ou3VisuelsDataTransformer::VISUELS_3 : Pc68Contenu1Article2Ou3VisuelsDataTransformer::VISUELS_2;
        $block->setZoneTool($visuelsNumberType);

        // Parse CTA
        $this->fillCtasForReferenceOwner($block, $urlManager, $currentPage, $rootXPath, $articleNode, $reporting);
    }

}
