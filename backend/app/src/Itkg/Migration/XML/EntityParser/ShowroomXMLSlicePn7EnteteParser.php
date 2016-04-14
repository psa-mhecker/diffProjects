<?php

namespace Itkg\Migration\XML\EntityParser;

use DOMXPath;
use DOMElement;
use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\Transaction\PsaShowroomEntityFactory;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZone;
use PsaNdp\MappingBundle\Object\Block\Pn7EnTeteData;

/**
 * Class ShowroomXMLSlicePn14NavigationParser.
 */
class ShowroomXMLSlicePn7EnteteParser extends AbstractShowroomXMLSliceParser
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'PN7';
    }

    /**
     * @param ShowroomUrlManager           $urlManager  showroom urlManager
     * @param PsaPageShowroomMetadata      $currentPage
     * @param DOMXPath                     $rootXPath   xml root using xpath
     * @param DOMElement                   $sliceNode
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
     * @param PsaPageZone             $block
     * @param ShowroomUrlManager      $urlManager
     * @param PsaPageShowroomMetadata $currentPage
     * @param DOMXPath                $rootXPath
     * @param DOMElement              $sliceNode
     */
    private function fillBlock(PsaPageZone $block, ShowroomUrlManager $urlManager, PsaPageShowroomMetadata $currentPage, DOMXPath $rootXPath, DOMElement $sliceNode, AddReportingMessageInterface $reporting)
    {
        $block->setZoneWeb(1);
        $block->setZoneMobile(1);
        // Display format : 'Classique'
        $block->setZoneTitre3(Pn7EnTeteData::VISUEL_TEXTE);
        // Get title from page
        if ($currentPage->getPage() && $currentPage->getPage()->getDraftVersion()) {
            $title = $currentPage->getPage()->getDraftVersion()->getPageTitle();
            if (empty($title)) {
                $title = $currentPage->getPage()->getDraftVersion()->getPageTitleBo();
            }
            $block->setZoneTitre($title);
        }
        // Get welcome page media if already parsed
        $welcomePage = $currentPage->getFirstLeveLParent();
        if ($welcomePage->getShowroomBackgroundImg() !== null) {
            $block->setMedia($welcomePage->getShowroomBackgroundImg());
        } else {
            // Parse For existing Media
            $showroomResponsiveHtmlXmlNode = $this->xPathQuery->queryFirstDOMElement(
                'showroom_responsive_html',
                $rootXPath
            );
            $backgroundImg = $this->xPathQuery->queryFirstDOMElementNodeValue(
                './background_img',
                $rootXPath,
                $showroomResponsiveHtmlXmlNode
            );

            if ($backgroundImg !== '') {
                $media = $this->entityFactory->createMedia(
                    PsaShowroomEntityFactory::MEDIA_TYPE_IMAGE,
                    $urlManager->getUser()
                );
                $media->setMediaPath($urlManager->generateMediaUrl($backgroundImg));
                $block->setMedia($media);

                //Add media to the media to be saved in "Mediatheque" and as showroom backgroung image
                $currentPage->addMedia($media);
                $currentPage->setShowroomBackgroundImg($media);
            }
        }
    }
}
