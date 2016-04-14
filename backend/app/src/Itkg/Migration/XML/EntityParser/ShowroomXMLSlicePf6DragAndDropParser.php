<?php


namespace Itkg\Migration\XML\EntityParser;

use DOMXPath;
use DOMElement;
use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\Transaction\PsaShowroomEntityFactory;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use Itkg\Transaction\PsaEntityFactory;
use PSA\MigrationBundle\Entity\Page\PsaPageMultiZone;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

/**
 * Class ShowroomXMLSlicePf6DragAndDropParser
 * @package Itkg\Migration\XML\EntityParser
 */
class ShowroomXMLSlicePf6DragAndDropParser extends AbstractShowroomXMLSliceParser
{
    const VERTICAL = 0;
    const MAX_CTA = 4;
    const MAX_CTA_CHILD = 15;

    /**
     * @return string
     */
    public function getName()
    {
        return 'PF6';
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
        // Parse Multis with media and Ctas
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
        $articleNode = $this->xPathQuery->queryFirstDOMElement('./articles/article', $rootXPath, $sliceNode);
        $title = $this->xPathQuery->queryFirstDOMElementNodeValue('./articles/article/title', $rootXPath, $sliceNode);
        $content = $this->xPathQuery->queryFirstDOMElementNodeValue('./articles/article/content', $rootXPath, $sliceNode);

        // Fill text and configuration
        $block->setZoneTitre($title);
        $block->setZoneTexte($content);
        $block->setZonePos(self::VERTICAL);

        // Parse media
        $mediaPath1 = $this->xPathQuery->queryFirstDOMElementNodeValue('article_media1', $rootXPath, $articleNode);
        $mediaPath2 = $this->xPathQuery->queryFirstDOMElementNodeValue('article_linked_media1', $rootXPath, $articleNode);

        if ($mediaPath1 !== '') {
            $mediaAlt1 = $this->xPathQuery->queryFirstDOMElementNodeValue('article_alt_media1', $rootXPath, $articleNode);
            $media1 = $this->entityFactory->createMedia(PsaShowroomEntityFactory::MEDIA_TYPE_IMAGE, $urlManager->getUser());
            $media1->setMediaPath($urlManager->generateMediaUrl($mediaPath1));
            $media1->setMediaTranslation(
                $this->entityFactory->createMediaAltTranslation($media1, $urlManager->getLanguage(), $mediaAlt1)
            );

            // Add Media to library and block
            $currentPage->addMedia($media1, $this->nodeType);
            $block->setMedia($media1);
        }
        if ($mediaPath2 !== '') {
            $mediaAlt2 = $this->xPathQuery->queryFirstDOMElementNodeValue('article_linked_alt_media1', $rootXPath, $articleNode);
            $media2 = $this->entityFactory->createMedia(PsaShowroomEntityFactory::MEDIA_TYPE_IMAGE, $urlManager->getUser());
            $media2->setMediaPath($urlManager->generateMediaUrl($mediaPath2));
            $media2->setMediaTranslation(
                $this->entityFactory->createMediaAltTranslation($media2, $urlManager->getLanguage(), $mediaAlt2)
            );

            // Add Media to library and block
            $currentPage->addMedia($media2, $this->nodeType);
            $block->setMedia2($media2);
        }

        // Parse CTA
        $this->fillCtasForReferenceOwner($block, $urlManager, $currentPage, $rootXPath, $articleNode, $reporting);
    }

}
