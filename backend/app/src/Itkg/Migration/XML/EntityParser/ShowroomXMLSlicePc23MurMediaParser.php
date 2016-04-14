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
use PsaNdp\ApiBundle\Service\Pc23MurMediaDataWebService;
use PsaNdp\MappingBundle\Object\Block\Pc23MurMedia;
use PsaNdp\MappingBundle\Object\Block\Pc23Object\StructureManager;

/**
 * Class ShowroomXMLSlicePc23MurMediaParser
 * @package Itkg\Migration\XML\EntityParser
 */
class ShowroomXMLSlicePc23MurMediaParser extends AbstractShowroomXMLSliceParser
{

    /** @var PsaPageMultiZone */
    private $galleryBlock;
    /** @var PsaPageShowroomMetadata */
    private $currentPage;

    private $medias;
    /**
     * @return string
     */
    public function getName()
    {
        return 'PC23';
    }

    /**
     * @param ShowroomUrlManager $urlManager showroom urlManager
     * @param PsaPageShowroomMetadata $currentPage
     * @param DOMXPath $rootXPath xml root using xpath
     * @param DOMElement $pageNode
     * @param AddReportingMessageInterface $reporting
     *
     * @return PsaPageZoneConfigurableInterface
     */
    public function parse(ShowroomUrlManager $urlManager, PsaPageShowroomMetadata $currentPage, DOMXPath $rootXPath, DOMElement $pageNode, AddReportingMessageInterface $reporting)
    {
        $block = $this->entityFactory->createDynamicBlockForSliceId(
            $currentPage->getPage(),
            $currentPage->getDynamicBlocksZoneOrder(),
            $this->getName()
        );
        $this->currentPage = $currentPage;
        // Fill list of manual media
        $this->fillBlock($urlManager, $rootXPath, $pageNode, $reporting);

        // Note: The list of slices' media and the showroom Id should be filled as post treatment after saving the showroom
        //       The list of slices' media to import is not the one from the pageNode, but all the images that has been imported in the showroom.
        $this->galleryBlock = $block;
        $currentPage->addCallableEvents(
            PsaPageShowroomMetadata::CALLABLE_SLICE_POST_SAVING,
            $this,
            'postSavingCallableEvent'
        );

        return $block;
    }

    /**
     * @param ShowroomUrlManager            $urlManager
     * @param DOMXPath                      $rootXPath
     * @param DOMElement                    $pageNode
     * @param AddReportingMessageInterface  $reporting
     */
    private function fillBlock(ShowroomUrlManager $urlManager, DOMXPath $rootXPath, DOMElement $pageNode, AddReportingMessageInterface $reporting)
    {
        $mediaNodes = $rootXPath->query('./articles/article', $pageNode);

        foreach($mediaNodes as $mediaNode) {
            // Parse media
            $media = $this->parseMedia($urlManager, $rootXPath, $mediaNode, $reporting);

            if (null !== $media) {
                $this->medias[] = $media;
                $this->currentPage->addMedia($media);
            }
        }
    }


    /**
     * @param ShowroomUrlManager $urlManager
     * @param DOMXPath $rootXPath
     * @param DOMElement $articleNode
     * @param AddReportingMessageInterface $reporting
     *
     * @return null|PsaMedia
     */
    private function parseMedia(ShowroomUrlManager $urlManager, DOMXPath $rootXPath, DOMElement $articleNode, AddReportingMessageInterface $reporting)
    {
        $media = null;
        $articleVideo = $this->xPathQuery->queryFirstDOMElementNodeValue('article_video1', $rootXPath, $articleNode);

        if ($articleVideo !== '') {
            // Create video media
            $media = $this->entityFactory->createStreamLikeMedia(
                $urlManager,
                $reporting,
                $articleVideo,
                $this->xPathQuery->queryFirstDOMElementNodeValue('article_video1_subtitle', $rootXPath, $articleNode)
            );
        } else {
            // Create an image media (using second media)
            $media = $this->entityFactory->createImageMedia(
                $urlManager,
                $this->xPathQuery->queryFirstDOMElementNodeValue('article_media2', $rootXPath, $articleNode),
                $this->xPathQuery->queryFirstDOMElementNodeValue('article_alt_media2', $rootXPath, $articleNode)
            );
        }

        return $media;
    }



    /**
     * Post Call event to fill list of media from other slice and select the showroom created
     * @return PsaPageMultiZone
     */
    public function postSavingCallableEvent()
    {
        $homePage = $this->currentPage->getFirstLeveLParent();
        $homePageId = $homePage->getPage()->getId();
        $structureManager = new StructureManager();
        $structures =   $structureManager->autoFill($this->medias, true);
        $newSlices = [];
        $this->galleryBlock->setZoneAttribut($homePageId);
        $page = $this->currentPage->getPage();
        $zoneOrder = $this->galleryBlock->getZoneOrder();
        $index = 1;
        foreach ($structures as $structure) {
            $blockMulti = $this->entityFactory->createDynamicBlockMulti($page, $zoneOrder, $index++, Pc23MurMedia::TYPE_MUR_MEDIA);
            $mediaNames = $structureManager->getMediaNames();

            foreach ($mediaNames as $prop ) {
                if (!empty($structure[$prop])) {
                    $blockMulti[$prop] =$structure[$prop];
                }
                $blockMulti['PAGE_ZONE_MULTI_VALUE'] = $structure['PAGE_ZONE_MULTI_VALUE'];
            }
            $this->galleryBlock->addMulti($blockMulti);
            $newSlices[] = $blockMulti;

        }

        return [
            'block' => $this->galleryBlock,
            'newSlices' => $newSlices
        ];
    }

}
