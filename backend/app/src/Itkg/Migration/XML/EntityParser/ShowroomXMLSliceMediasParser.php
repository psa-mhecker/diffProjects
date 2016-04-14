<?php


namespace Itkg\Migration\XML\EntityParser;

use DOMXPath;
use DOMElement;
use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\Transaction\PsaShowroomEntityFactory;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use PSA\MigrationBundle\Entity\Media\PsaMedia;

/**
 * Parse list of media in XML slice node
 * Media can be StreamLike video or a picture to upload
 *
 * Class ShowroomXMLSliceMediasParser
 * @package Itkg\Migration\XML\EntityParser
 */
class ShowroomXMLSliceMediasParser extends AbstractShowroomXMLEntityParser
{
    /** @var int */
    private $maxIndex = 9;

    /**
     * @param ShowroomUrlManager $urlManager showroom urlManager
     * @param PsaPageShowroomMetadata $currentPage
     * @param DOMXPath $rootXPath xml root using xpath
     * @param DOMElement $articleNode
     * @param AddReportingMessageInterface $reporting
     *
     * @return PsaMedia[]
     */
    public function parse(ShowroomUrlManager $urlManager, PsaPageShowroomMetadata $currentPage, DOMXPath $rootXPath, DOMElement $articleNode = null, AddReportingMessageInterface $reporting)
    {
        $result = [];

        if ($articleNode !== null) {
            for ($index = 1;  $index <= $this->maxIndex; $index++) {
                $media =  $this->parseMediaIndex($urlManager, $rootXPath, $articleNode, $index, $reporting);
                if ($media !== null) {
                    $result[] = $media;
                    $currentPage->addMedia($media, $this->nodeType);
                }
            }
        }

        return $result;
    }

    /**
     * @param ShowroomUrlManager $urlManager
     * @param DOMXPath $rootXPath
     * @param DOMElement $articleNode
     * @param $id
     * @param AddReportingMessageInterface $reporting
     *
     * @return null|PsaMedia
     */
    private function parseMediaIndex(ShowroomUrlManager $urlManager, DOMXPath $rootXPath, DOMElement $articleNode, $id, AddReportingMessageInterface $reporting)
    {
        $media = null;
        $articleVideo = $this->xPathQuery->queryFirstDOMElementNodeValue('article_video' . $id, $rootXPath, $articleNode);
        $articleMedia = $this->xPathQuery->queryFirstDOMElementNodeValue('article_media' . $id, $rootXPath, $articleNode);

        // Media image or video
        if ($articleMedia !== '' || $articleVideo !== '') {
            switch ($this->nodeType) {
                case ShowroomXMLEntityParserFactory::WIDGET_TYPE_SINGLE_LARGE:
                case ShowroomXMLEntityParserFactory::PAGE_TYPE_GALLERY:
                    $mediaType = ($articleVideo === '') ? PsaShowroomEntityFactory::MEDIA_TYPE_IMAGE : PsaShowroomEntityFactory::MEDIA_TYPE_STREAMLIKE;
                    break;
                default:
                    // Case ShowroomXMLEntityParserFactory::WIDGET_TYPE_SINGLE_SLIDER
                    //  and ShowroomXMLEntityParserFactory::WIDGET_TYPE_SINGLE_PICTURE
                    $mediaType = PsaShowroomEntityFactory::MEDIA_TYPE_IMAGE;
                    break;
            }
            switch ($mediaType) {
                case PsaShowroomEntityFactory::MEDIA_TYPE_STREAMLIKE:
                    $articleVideoSubtitle = $this->xPathQuery->queryFirstDOMElementNodeValue('article_video' . $id . '_subtitle', $rootXPath, $articleNode);
                    $media = $this->entityFactory->createStreamLikeMedia(
                        $urlManager,
                        $reporting,
                        $articleVideo,
                        $articleVideoSubtitle
                    );
                    break;
                case PsaShowroomEntityFactory::MEDIA_TYPE_IMAGE:
                    $articleAltMedia = $this->xPathQuery->queryFirstDOMElementNodeValue('article_alt_media' . $id, $rootXPath, $articleNode);
                    if (empty($articleAltMedia)&& 1 == $id ) {
                        $articleAltMedia = $this->xPathQuery->queryFirstDOMElementNodeValue('./title', $rootXPath, $articleNode);
                    }
                    $media = $this->entityFactory->createImageMedia(
                        $urlManager,
                        $articleMedia,
                        $articleAltMedia
                    );
                    break;
            }
        }

        return $media;
    }


    /**
     * @return int
     */
    public function getMaxIndex()
    {
        return $this->maxIndex;
    }

    /**
     * @param int $maxIndex
     *
     * @return ShowroomXMLSliceMediasParser
     */
    public function setMaxIndex($maxIndex)
    {
        $this->maxIndex = $maxIndex;

        return $this;
    }

}
