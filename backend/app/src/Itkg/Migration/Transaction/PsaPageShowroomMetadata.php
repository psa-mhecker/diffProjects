<?php

namespace Itkg\Migration\Transaction;

use Itkg\Migration\Event\CallableEventsInterface;
use Itkg\Migration\XML\EntityParser\ShowroomXMLEntityParserFactory;
use PSA\MigrationBundle\Entity\Media\PsaMedia;
use PSA\MigrationBundle\Entity\Page\PsaPage;
use PSA\MigrationBundle\Entity\Page\PsaPageMultiZone;
use PSA\MigrationBundle\Entity\Page\PsaPageZone;

/**
 * Class for adding to Original PsaPage entity other metadata to transport for the migration
 *
 * Class PsaPageShowroomMetadata
 * @package Itkg\Migration\XML\EntityParser
 */
class PsaPageShowroomMetadata implements CallableEventsInterface
{
    const PAGE_TYPE_HOMEPAGE = "HOMEPAGE";
    const PAGE_TYPE_SUB_PAGE = "SUB_PAGE";

    const SHOWROOM_ROOT_MEDIA_DIRECTORY = 'Showroom';
    const SHOWROOM_HOMEPAGE_MEDIA_DIRECTORY = 'Homepage';
    const SHOWROOM_PDF_MEDIA_DIRECTORY = 'Pdf';

    // ** Callable event type
    // For Method to be call after saving all $page and $subPages data in database
    const CALLABLE_SLICE_POST_SAVING = 'SLICE_POST_SAVING';

    /** @var PsaPageShowroomMetadata */
    private $parent = null;
    /** @var  PsaPageShowroomMetadata[] */
    private $subPages = [];
    /** @var PsaPage */
    private $page;
    /** @var string */
    private $pageType = self::PAGE_TYPE_SUB_PAGE;
    /** @var string */
    private $pageUrlKey = '';
    /** @var string */
    private $showroomId = null;
    /** @var string */
    private $showroomUrlKey = '';
    /** @var string xml attribut 'id' from the node the page */
    private $xmlId;

    /** @var PsaBlockShowroomMetadata[] */
    private $blocksWithMetadata = [];
    /** @var AbstractPsaCtaReferentCommonShowroomMetadata[] List of cta generated inside the page's slice for post treatment to complete url of the cta */
    private $ctasWithMetadata = [];
    /** @var PsaMedia[] List of media generated inside the page's slice and to be saved in the 'Mediathèque' */
    private $medias = [];

    /**
     * @var PsaMedia Showroom Background from XML balise showroom_reponsive_html / background_img, use in PF2 and PC18
     * Usage : Save the Media use in homepage, for subpages to use the same media instead of recreating new one
     */
    protected $showroomBackgroundImg = null;
    /**
     * @var array List of Default PsaPageShowroomMetadata pages created
     */
    protected $defaultPages = [];


    /** @var array list of method to be called */
    private $callableEvents = [];

    /**
     * @return string
     */
    public function getPageMediaDirName()
    {
        $destinationMediaDirName = "No page name";

        if ($this->pageType === PsaPageShowroomMetadata::PAGE_TYPE_HOMEPAGE) {
            $destinationMediaDirName = self::SHOWROOM_HOMEPAGE_MEDIA_DIRECTORY;
        } else if ($this->pageUrlKey !== '') {
            $destinationMediaDirName = $this->pageUrlKey;
        }

        return $destinationMediaDirName;
    }

    /**
     * @return PsaPageShowroomMetadata[]
     */
    public function getSubPages()
    {
        return $this->subPages;
    }

    /**
     * @param string $xmlId
     *
     * @return PsaPageShowroomMetadata
     */
    public function getPageByXmlId($xmlId)
    {
        if ($this->xmlId === $xmlId) {
            return $this;
        }
        foreach($this->subPages as $subPage) {
            if ($subPage->getXmlId() === $xmlId) {
                return $subPage;
            }
        }

        return null;
    }


    /**
     * @param PsaPageShowroomMetadata $subPage
     *
     *
     * @return PsaPageShowroomMetadata
     *
     */
    public function addSubPage(PsaPageShowroomMetadata $subPage)
    {
        $subPage->setParent($this);
        $this->subPages[] = $subPage;

        return $this;
    }

    /**
     * @return PsaMedia[]
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * @param PsaMedia $media
     *
     * @return PsaPageShowroomMetadata
     */
    public function addMedia(PsaMedia $media)
    {
        $this->medias[] = $media;

        return $this;
    }


    /**
     * @return PsaPage
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param PsaPage $page
     *
     * @return PsaPageShowroomMetadata
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getShowroomId()
    {
        return $this->showroomId;
    }

    /**
     * @param mixed $showroomId
     *
     * @return PsaPageShowroomMetadata
     */
    public function setShowroomId($showroomId)
    {
        $this->showroomId = $showroomId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPageType()
    {
        return $this->pageType;
    }

    /**
     * @param mixed $pageType
     *
     * @return PsaPageShowroomMetadata
     */
    public function setPageType($pageType)
    {
        $this->pageType = $pageType;

        return $this;
    }

    /**
     * @return string
     */
    public function getShowroomUrlKey()
    {
        return $this->showroomUrlKey;
    }

    /**
     * @param string $showroomUrlKey
     *
     * @return PsaPageShowroomMetadata
     */
    public function setShowroomUrlKey($showroomUrlKey)
    {
        $this->showroomUrlKey = $showroomUrlKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getPageUrlKey()
    {
        return $this->pageUrlKey;
    }

    /**
     * @param string $pageUrlKey
     *
     * @return PsaPageShowroomMetadata
     */
    public function setPageUrlKey($pageUrlKey)
    {
        $this->pageUrlKey = $pageUrlKey;

        return $this;
    }

    /**
     * Important zoneOrder should start by 1
     */
    public function getDynamicBlocksZoneOrder()
    {
        if ($this->getPage() && $this->getPage()->getDraftVersion()) {
            return count($this->getPage()->getDraftVersion()->getDynamicPageBlocks()) + 1;
        }

        throw new \RuntimeException("No Page and Current Page Version set. Could not calculate ZoneOrder for DynamicBlocks.");
    }

    /**
     * @return string
     */
    public function getXmlId()
    {
        return $this->xmlId;
    }

    /**
     * @param string $xmlId
     *
     * @return PsaPageShowroomMetadata
     */
    public function setXmlId($xmlId)
    {
        $this->xmlId = $xmlId;

        return $this;
    }

    /**
     * @return PsaBlockShowroomMetadata[]
     */
    public function getBlocksWithMetadata()
    {
        return $this->blocksWithMetadata;
    }

    /**
     * @param PsaBlockShowroomMetadata $blockWithMetadata
     *
     * @return PsaPageShowroomMetadata
     */
    public function addBlockWithMetadata(PsaBlockShowroomMetadata $blockWithMetadata)
    {
        if ($blockWithMetadata->getXmlId() && $blockWithMetadata->getXmlId() !== '') {
            $this->blocksWithMetadata[$blockWithMetadata->getXmlId()] = $blockWithMetadata;
        }

        return $this;
    }

    /**
     * @return PsaCtaReferentShowroomMetadata[]
     */
    public function getCtasWithMetadata()
    {
        return $this->ctasWithMetadata;
    }

    /**
     * @param AbstractPsaCtaReferentCommonShowroomMetadata $ctaWithMetadata
     *
     * @return PsaPageShowroomMetadata
     */
    public function addCtaWithMetadata(AbstractPsaCtaReferentCommonShowroomMetadata $ctaWithMetadata)
    {
        $this->ctasWithMetadata[] = $ctaWithMetadata;

        return $this;
    }

    /**
     * @return array
     */
    public function getCallableEvents()
    {
        return $this->callableEvents;
    }

    /**
     * @param string $callableType
     * @param mixed $callableObject
     * @param string $callableFunction
     *
     * @return PsaPageShowroomMetadata
     */
    public function addCallableEvents($callableType, $callableObject, $callableFunction)
    {
        $callableTypeSupported = [self::CALLABLE_SLICE_POST_SAVING];

        if (!in_array($callableType, $callableTypeSupported)) {
            throw new \RuntimeException(
                sprintf(
                    'Callable Event Type %s is not supported. Supported callable event are : [%s]',
                    $callableType, implode(",", $callableTypeSupported)
                )
            );
        }

        if (!isset($this->callableEvents[$callableType])) {
            $this->callableEvents[$callableType] = [];
        }

        $callableEvent = [$callableObject, $callableFunction];

        if (is_callable($callableEvent)) {
            $this->callableEvents[$callableType][] = $callableEvent;
        } else {
            throw new \RuntimeException(
                sprintf(
                    '%s:%s is not a callable function.',
                    get_class($callableObject), $callableFunction
                )
            );
        }

        return $this;
    }

    /**
     * @param $callableType
     *
     * @return array
     */
    public function launchCallableEvent($callableType)
    {
        $result = [];

        if (isset($this->callableEvents[$callableType])) {
            foreach($this->callableEvents[$callableType] as $callableEvent) {
                $result[] = call_user_func($callableEvent);
            }
        }
        foreach($this->subPages as $subPage) {
            $result = array_merge($result, $subPage->launchCallableEvent($callableType));
        }

        return $result;
    }

    /**
     * @return PsaPageShowroomMetadata
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param PsaPageShowroomMetadata $parent
     *
     * @return PsaPageShowroomMetadata
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return PsaPageShowroomMetadata
     */
    public function getFirstLeveLParent()
    {
        if ($this->parent === null) {
            return $this;
        } else {
            return $this->parent->getFirstLeveLParent();
        }
    }


    /**
     * @return PsaMedia
     */
    public function getShowroomBackgroundImg()
    {
        return $this->showroomBackgroundImg;
    }

    /**
     * @param PsaMedia $showroomBackgroundImg
     *
     * @return PsaPageShowroomMetadata
     */
    public function setShowroomBackgroundImg($showroomBackgroundImg)
    {
        $this->showroomBackgroundImg = $showroomBackgroundImg;

        return $this;
    }

    /**
     * @param $sliceZoneId
     *
     * @return bool
     */
    public function containDynamicSlice($sliceZoneId)
    {
        if ($this->getPage() && $this->getPage()->getDraftVersion()) {
            foreach ($this->getPage()->getDraftVersion()->getDynamicPageBlocks() as $block) {
                /** @var PsaPageMultiZone $block */
                if ($block->getZoneId() === $sliceZoneId) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param $sliceZoneTemplateId
     *
     * @return bool
     */
    public function containStaticSlice($sliceZoneTemplateId)
    {
        if ($this->getPage() && $this->getPage()->getDraftVersion()) {
            foreach ($this->getPage()->getDraftVersion()->getBlocks() as $block) {
                /** @var PsaPageZone $block */
                if ($block->getZoneTemplateId() === $sliceZoneTemplateId) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getDefaultPages()
    {
        return $this->defaultPages;
    }

    /**
     * @param array $defaultPages
     *
     * @return PsaPageShowroomMetadata
     */
    public function setDefaultPages($defaultPages)
    {
        $this->defaultPages = $defaultPages;

        return $this;
    }
}
