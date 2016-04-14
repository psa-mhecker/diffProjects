<?php


namespace Itkg\Migration\XML\EntityParser;

use DOMXPath;
use DOMElement;
use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\Transaction\PsaShowroomEntityFactory;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use PSA\MigrationBundle\Entity\Page\PsaPageZone;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMulti;

/**
 * Class ShowroomXMLSlicePn14NavigationParser
 * @package Itkg\Migration\XML\EntityParser
 */
class ShowroomXMLSlicePf2PresentationShowroomParser extends AbstractShowroomXMLSliceParser
{
    const AFFICHAGE_REVEAL = 1;
    const AFFICHAGE_LAUNCH = 2;
    const AFFICHAGE_MARKETING = 3;
    const TYPE_VISUEL = 1;
    const DISPLAY_RIGHT = 'right';

    /**
     * @return string
     */
    public function getName()
    {
        return 'PF2';
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
        // Parse Multis and parameters
        $this->fillBlock($block, $urlManager, $currentPage, $rootXPath, $sliceNode, $reporting);

        return $block;
    }


    /**
     * @param PsaPageZone $block
     * @param ShowroomUrlManager $urlManager
     * @param PsaPageShowroomMetadata $currentPage
     * @param DOMXPath $rootXPath
     * @param DOMElement $sliceNode
     */
    private function fillBlock(PsaPageZone $block, ShowroomUrlManager $urlManager, PsaPageShowroomMetadata $currentPage, DOMXPath $rootXPath, DOMElement $sliceNode, AddReportingMessageInterface $reporting)
    {
        // Get welcome page media if already parsed
        $welcomePage = $currentPage->getFirstLeveLParent();
        if ($welcomePage->getShowroomBackgroundImg() !== null) {
            // Use welcome page picture
            $blockMulti = $this->entityFactory->createBlockMulti($block, 1, PsaPageZoneMulti::PAGE_ZONE_MULTI_VALUE_SLIDE_IMAGE);
            $blockMulti->setPageZoneMultiOrder(1);

            $blockMulti->setMedia($welcomePage->getShowroomBackgroundImg());
            $block->addMulti($blockMulti);
        } else {
            // Parse For existing Media
            $showroomResponsiveHtmlXmlNode = $this->xPathQuery->queryFirstDOMElement('showroom_responsive_html', $rootXPath);
            $backgroundImg = $this->xPathQuery->queryFirstDOMElementNodeValue('./background_img', $rootXPath, $showroomResponsiveHtmlXmlNode);

            if ($backgroundImg !== '') {
                $blockMulti = $this->entityFactory->createBlockMulti($block, 1, PsaPageZoneMulti::PAGE_ZONE_MULTI_VALUE_SLIDE_IMAGE);
                $blockMulti->setPageZoneMultiOrder(1);

                $media = $this->entityFactory->createMedia(PsaShowroomEntityFactory::MEDIA_TYPE_IMAGE, $urlManager->getUser());
                $media->setMediaPath($urlManager->generateMediaUrl($backgroundImg));
                $blockMulti->setMedia($media);
                $block->addMulti($blockMulti);

                //Add media to the media to be saved in "Mediatheque" and as showroom backgroung image
                $currentPage->addMedia($media);
                $currentPage->setShowroomBackgroundImg($media);
            }
        }

        // Set parameters
        $block->setZoneParameters(self::AFFICHAGE_MARKETING);
        $block->setZoneAttribut(self::TYPE_VISUEL);
        $block->setZoneLabel2(self::DISPLAY_RIGHT);
    }
}
