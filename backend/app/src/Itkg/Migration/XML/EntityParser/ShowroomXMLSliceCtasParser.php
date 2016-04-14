<?php


namespace Itkg\Migration\XML\EntityParser;

use Doctrine\Common\Collections\ArrayCollection;
use DOMXPath;
use DOMElement;
use DOMNodeList;
use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Transaction\AbstractPsaCtaReferentCommonShowroomMetadata;
use Itkg\Migration\Transaction\PsaCtaChildReferentShowroomMetadata;
use Itkg\Migration\Transaction\PsaCtaReferentShowroomMetadata;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\Transaction\PsaShowroomEntityFactory;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use Itkg\Transaction\PsaEntityFactory;
use PSA\MigrationBundle\Entity\Cta\PsaCta;
use PSA\MigrationBundle\Entity\Cta\PsaCtaReferenceInterface;
use PSA\MigrationBundle\Entity\Page\PsaPage;

/**
 * Parse list of links in XML slice node to transform them as cta
 * CTA can be simple cta or dropdown list cta using the dropdown boolean
 *
 * Class ShowroomXMLSliceCtasParser
 * @package Itkg\Migration\XML\EntityParser
 */
class ShowroomXMLSliceCtasParser extends AbstractShowroomXMLEntityParser
{
    const TYPE_COLUMN = 'COLUMN';

    /** @var null|int */
    protected $maxCta = null;
    /** @var null|int */
    protected $maxCtaChild = null;
    /** @var null|int  */
    protected $referenceType = null;
    /** @var null|string */
    protected $referenceTypeGenerator = null;
    /** @var mixed should be an entity having a list of ctaReference to affect */
    protected $referentOwner;
    /** @var int if null, referentId will be equal to the order in the xml list */
    protected $referenceId = null;
    /** @var int if null, referenceOrder will be equal to the order in the xml list*/
    protected $referenceOrder = null;

    /**
     * @param ShowroomUrlManager $urlManager showroom urlManager
     * @param PsaPageShowroomMetadata $currentPage
     * @param DOMXPath $rootXPath xml root using xpath
     * @param DOMElement $articleNode
     * @param AddReportingMessageInterface $reporting
     *
     * @return PsaCtaReferenceInterface[]
     */
    public function parse(
        ShowroomUrlManager $urlManager,
        PsaPageShowroomMetadata $currentPage,
        DOMXPath $rootXPath,
        DOMElement $articleNode = null,
        AddReportingMessageInterface $reporting
    )
    {
        $result = [];

        if ($articleNode !== null) {
            if ($this->referentOwner === null) {
                throw new \RuntimeException(
                    sprintf(
                        "Cta referent owner wat not set. Cta could not be parse for XML node id '%s' in XML '%s'",
                        $articleNode->getAttribute('id'),
                        $urlManager->getXmlUrl()
                    )
                );
            }

            $dropDownTitle = $this->xPathQuery->queryFirstDOMElementNodeValue('./ctamaintitle', $rootXPath, $articleNode);

            if ($dropDownTitle === '') {
                $result = $this->parseCtaAsBtn($urlManager, $currentPage, $rootXPath, $articleNode, $reporting);
            } else {
                $result = $this->parseCtaAsDropDownList($dropDownTitle, $urlManager, $currentPage, $rootXPath, $articleNode, $reporting);
            }
        }

        return $result;
    }

    /**
     * @param ShowroomUrlManager $urlManager
     * @param PsaPageShowroomMetadata $currentPage
     * @param DOMXPath $rootXPath
     * @param DOMElement $articleNode
     * @param AddReportingMessageInterface $reporting
     *
     * @return array
     */
    private function parseCtaAsBtn(ShowroomUrlManager $urlManager, PsaPageShowroomMetadata $currentPage, DOMXPath $rootXPath, DOMElement $articleNode, AddReportingMessageInterface $reporting)
    {
        $result = [];
        $page = $currentPage->getPage();
        $linksNode = $rootXPath->query('./links/link', $articleNode);

        // Parse Cta button
        $maxIndex = $this->getMaxCtaToParse($this->maxCta, $linksNode, $urlManager, $currentPage, $articleNode, $reporting);
        for ($index = 0; $index < $maxIndex; $index++) {
            /** @var DOMElement $linkNode */
            $linkNode = $linksNode->item($index);

            $ctaReferentWithMetadata = $this->parseLinksAsBtn($urlManager, $page, $rootXPath, $linkNode, $index + 1);
            if ($ctaReferentWithMetadata !== null) {
                $result[] = $ctaReferentWithMetadata->getCtaReferent();
                $currentPage->addCtaWithMetadata($ctaReferentWithMetadata);

                //if a new Media (PDF) has been created, add it to the media list of the page to be dwd and created in the 'Médiathèque'
                if ($ctaReferentWithMetadata->getMedia() !== null) {
                    $currentPage->addMedia($ctaReferentWithMetadata->getMedia());
                }
            }
        }

        return $result;
    }

    /**
     * @param ShowroomUrlManager $urlManager
     * @param PsaPage $page
     * @param DOMXPath $rootXPath
     * @param DOMElement $linkNode
     * @param int $order
     *
     * @return PsaCtaReferentShowroomMetadata
     */
    private function parseLinksAsBtn(ShowroomUrlManager $urlManager, PsaPage $page, DOMXPath $rootXPath, DOMElement $linkNode, $order)
    {
        $ctaReferentWithMetadata = new PsaCtaReferentShowroomMetadata();
        $referenceId = ($this->referenceId !== null) ? $this->referenceId : $order;
        $referenceOrder = ($this->referenceOrder !== null) ? $this->referenceOrder : $order;

        $cta = $this->entityFactory->createCta($page->getSite(), $page->getLangue());
        $ctaReferent = $this->entityFactory->createCtaReference(
            $this->referentOwner, $page, $cta, $referenceId, $referenceOrder,
            PsaEntityFactory::CTA_REFERENCE_STATUS_NEW_CTA,
            $this->generateReferenceType($order),
            PsaCta::STYLE_NIVEAU4
        );
        $ctaReferentWithMetadata->setCtaReferent($ctaReferent);
        $this->fillCtaReferentWithMetadata($ctaReferentWithMetadata, $urlManager, $rootXPath, $linkNode);

        return $ctaReferentWithMetadata;
    }

    /**
     * @param string $dropDownTitle
     * @param ShowroomUrlManager $urlManager
     * @param PsaPageShowroomMetadata $currentPage
     * @param DOMXPath $rootXPath
     * @param DOMElement $articleNode
     * @param AddReportingMessageInterface $reporting
     *
     * @return array
     */
    private function parseCtaAsDropDownList($dropDownTitle, ShowroomUrlManager $urlManager, PsaPageShowroomMetadata $currentPage, DOMXPath $rootXPath, DOMElement $articleNode, AddReportingMessageInterface $reporting)
    {
        $page = $currentPage->getPage();
        $linksNode = $rootXPath->query('./links/link', $articleNode);

        // Create parent cta
        // Note, with the imported xml structure it is not be possible to have more than 1 cta dropdown list par "article" node
        $order = 1;
        $referenceId = ($this->referenceId !== null) ? $this->referenceId : $order;
        $referenceOrder = ($this->referenceOrder !== null) ? $this->referenceOrder : $order;
        $ctaParent = $this->entityFactory->createCta($page->getSite(), $page->getLangue());
        $ctaParentReferent = $this->entityFactory->createCtaReference(
            $this->referentOwner, $page, $ctaParent, $referenceId, $referenceOrder,
            PsaEntityFactory::CTA_REFERENCE_STATUS_DROPDOWN_LIST,
            $this->generateReferenceType($order),
            PsaCta::STYLE_NIVEAU4
        );
        $ctaParent->setTitle($dropDownTitle);
        $ctaParentReferent->setReferenceLabel($dropDownTitle);
        $result = [$ctaParentReferent];

        // Parse child Ctas
        $child = new ArrayCollection();
        $maxIndex = $this->getMaxCtaToParse($this->maxCtaChild, $linksNode, $urlManager, $currentPage, $articleNode, $reporting);
        for ($index = 0; $index < $maxIndex; $index++) {
            /** @var DOMElement $linkNode */
            $linkNode = $linksNode->item($index);

            $ctaReferentWithMetadata = $this->parseLinksAsDropDownList($ctaParentReferent, $urlManager, $page, $rootXPath, $linkNode, $index + 1);
            if ($ctaReferentWithMetadata !== null) {
                $ctaChildReferent = $ctaReferentWithMetadata->getCtaReferent();
                $currentPage->addCtaWithMetadata($ctaReferentWithMetadata);
                $child->add($ctaChildReferent);

                //if a new Media (PDF) has been created, add it to the media list of the page to be dwd and created in the 'Médiathèque'
                if ($ctaReferentWithMetadata->getMedia() !== null) {
                    $currentPage->addMedia($ctaReferentWithMetadata->getMedia());
                }
            }
        }
        $ctaParentReferent->setChildCtas($child);

        return $result;
    }

    /**
     * @param PsaCtaReferenceInterface $ctaParentReferent
     * @param ShowroomUrlManager $urlManager
     * @param PsaPage $page
     * @param DOMXPath $rootXPath
     * @param DOMElement $linkNode
     * @param int $order
     *
     * @return PsaCtaChildReferentShowroomMetadata
     */
    private function parseLinksAsDropDownList(PsaCtaReferenceInterface $ctaParentReferent, ShowroomUrlManager $urlManager, PsaPage $page, DOMXPath $rootXPath, DOMElement $linkNode, $order)
    {
        $ctaChildReferentWithMetadata = new PsaCtaChildReferentShowroomMetadata();
        $referenceId = $order;
        $referenceOrder = ($this->referenceOrder !== null) ? $this->referenceOrder : $order;

        $cta = $this->entityFactory->createCta($page->getSite(), $page->getLangue());
        $ctaChildReferent = $this->entityFactory->createCtaChildReference(
            $this->referentOwner, $ctaParentReferent,
            $page, $cta, $referenceId, $referenceOrder,
            PsaEntityFactory::CTA_REFERENCE_STATUS_NEW_CTA,
            null,
            PsaCta::STYLE_NIVEAU4
        );
        $ctaChildReferentWithMetadata->setCtaReferent($ctaChildReferent);
        $this->fillCtaReferentWithMetadata($ctaChildReferentWithMetadata, $urlManager, $rootXPath, $linkNode);

        return $ctaChildReferentWithMetadata;
    }


    /**
     * @param AbstractPsaCtaReferentCommonShowroomMetadata $ctaReferentWithMetadata
     * @param ShowroomUrlManager $urlManager
     * @param DOMXPath $rootXPath
     * @param DOMElement $linkNode
     */
    private function fillCtaReferentWithMetadata(AbstractPsaCtaReferentCommonShowroomMetadata $ctaReferentWithMetadata, ShowroomUrlManager $urlManager, DOMXPath $rootXPath, DOMElement $linkNode)
    {
        $ctaReferent = $ctaReferentWithMetadata->getCtaReferent();
        $cta = $ctaReferent->getCta();

        $directLink = $this->xPathQuery->queryFirstDOMElementNodeValue('./direct_section_link', $rootXPath, $linkNode);
        $url = $this->xPathQuery->queryFirstDOMElementNodeValue('./url', $rootXPath, $linkNode);
        $title = $this->xPathQuery->queryFirstDOMElementNodeValue('./title', $rootXPath, $linkNode);
        $linkType = $this->getLinkType($urlManager, $directLink, $url);
        $target = $this->xPathQuery->queryFirstDOMElementNodeValue('./target', $rootXPath, $linkNode);
        if ($target === '_parent') {
            $target = '_self';
        }

        $cta->setTitle($title);
        $cta->setTarget($target);
        $ctaReferent->setTarget($target);
        $ctaReferentWithMetadata->setXmLinkType($linkType);
        $cta->setAction($url);

        switch ($linkType) {
            case PsaCtaReferentShowroomMetadata::LINK_TYPE_INTERNAL_PDF_MEDIA:
                // Create Media for PDF
                $media = $this->entityFactory->createMedia(PsaShowroomEntityFactory::MEDIA_TYPE_FILE, $urlManager->getUser());
                if (substr($url, 0, 1) === '/') {
                    $url = $urlManager->getHost() . $url;
                }
                $media->setMediaPath($url);
                $ctaReferentWithMetadata->setMedia($media);
                break;
            case PsaCtaReferentShowroomMetadata::LINK_TYPE_INTERNAL_PAGE:
                $widgetLink = $this->xPathQuery->queryFirstDOMElementNodeValue('./direct_widget_link', $rootXPath, $linkNode);
                $ctaReferentWithMetadata->setXmlPageId($directLink);
                $ctaReferentWithMetadata->setXmlWidgetId($widgetLink);
                break;
            case PsaCtaReferentShowroomMetadata::LINK_TYPE_EXTERNAL:
                // Do nothing
                break;
        }
    }

    /**
     * @param ShowroomUrlManager $urlManager
     * @param string $directLink
     * @param string $url
     *
     * @return string
     */
    private function getLinkType(ShowroomUrlManager $urlManager, $directLink, $url)
    {
        if ($directLink === '') {
            $type = PsaCtaReferentShowroomMetadata::LINK_TYPE_EXTERNAL;
            $extension = $ext = pathinfo($url, PATHINFO_EXTENSION);

            // External link (PDF or other) or Internal PDF Link
            if ($extension === 'pdf') {
                $urlInfos = parse_url($url);

                // For PDF, if link is a local PDF from the website, then it should imported in the 'Médiathèque'
                if (substr($url, 0, 1) === '/' || $urlManager->getHost() === $urlInfos['host']) {
                    $type = PsaCtaReferentShowroomMetadata::LINK_TYPE_INTERNAL_PDF_MEDIA;
                }
            }
        } else {
            // Internal Page link
            $type = PsaCtaReferentShowroomMetadata::LINK_TYPE_INTERNAL_PAGE;
        }

        return $type;
    }

    /**
     * @param int|null $max
     * @param DOMNodeList $linksNode
     * @param ShowroomUrlManager $urlManager
     * @param PsaPageShowroomMetadata $currentPage
     * @param DOMElement $articleNode
     * @param AddReportingMessageInterface $reporting
     *
     * @return int
     */
    private function getMaxCtaToParse($max, DOMNodeList $linksNode, ShowroomUrlManager $urlManager, PsaPageShowroomMetadata $currentPage, DOMElement $articleNode, AddReportingMessageInterface $reporting)
    {
        if ($max === null) {
            $max = $linksNode->length;
        } else {
            if ($linksNode->length > $max) {
                $reporting->addWarningMessage(
                    sprintf(
                        "Found %s link to import, but only %s cta is allowed. In page %s for element '%s' in the XML '%s'. The additional link was not imported.",
                        $linksNode->length, $max, $currentPage->getPage()->getDraftVersion()->getPageClearUrl(), $articleNode->getAttribute('title'), $urlManager->getXmlUrl()
                    )
                );
            } else {
                $max = $linksNode->length;
            }
        }

        return $max;
    }

    /**
     * @return mixed
     */
    public function getReferenceTypeGenerator()
    {
        return $this->referenceTypeGenerator;
    }

    /**
     * @param mixed $referenceTypeGenerator
     *
     * @return ShowroomXMLSliceCtasParser
     */
    public function setReferenceTypeGenerator($referenceTypeGenerator)
    {
        $this->referenceTypeGenerator = $referenceTypeGenerator;

        return $this;
    }


    /**
     * @param $index
     *
     * @return string
     */
    private function generateReferenceType($index)
    {
        switch ($this->referenceTypeGenerator) {
            case self::TYPE_COLUMN:
                $referenceType = 'LEVEL' . $index . '_CTA';
                break;
            default:
                $referenceType = $this->referenceType;
                break;

        }

        return $referenceType;
    }


    /**
     * @return null
     */
    public function getMaxCta()
    {
        return $this->maxCta;
    }

    /**
     * @param null $maxCta
     *
     * @return ShowroomXMLSliceCtasParser
     */
    public function setMaxCta($maxCta)
    {
        $this->maxCta = $maxCta;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReferentOwner()
    {
        return $this->referentOwner;
    }

    /**
     * @param mixed $referentOwner
     *
     * @return ShowroomXMLSliceCtasParser
     */
    public function setReferentOwner($referentOwner)
    {
        $this->referentOwner = $referentOwner;

        return $this;
    }

    /**
     * @return int
     */
    public function getReferenceId()
    {
        return $this->referenceId;
    }

    /**
     * @param int $referenceId
     *
     * @return ShowroomXMLSliceCtasParser
     */
    public function setReferenceId($referenceId)
    {
        $this->referenceId = $referenceId;

        return $this;
    }

    /**
     * @return int
     */
    public function getReferenceType()
    {
        return $this->referenceType;
    }

    /**
     * @param int $referenceType
     *
     * @return ShowroomXMLSliceCtasParser
     */
    public function setReferenceType($referenceType)
    {
        $this->referenceType = $referenceType;

        return $this;
    }

    /**
     * @return int
     */
    public function getReferenceOrder()
    {
        return $this->referenceOrder;
    }

    /**
     * @param int $referenceOrder
     *
     * @return ShowroomXMLSliceCtasParser
     */
    public function setReferenceOrder($referenceOrder)
    {
        $this->referenceOrder = $referenceOrder;

        return $this;
    }
}
