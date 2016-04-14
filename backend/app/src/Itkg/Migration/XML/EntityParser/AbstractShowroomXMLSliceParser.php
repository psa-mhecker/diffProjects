<?php


namespace Itkg\Migration\XML\EntityParser;

use DOMXPath;
use DOMElement;
use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\Transaction\PsaShowroomEntityFactory;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use Itkg\Migration\XML\XPathQueryHelper;
use PSA\MigrationBundle\Entity\Cta\PsaCtaReferenceOwnerInterface;

/**
 * Class ShowroomXMLPageParser
 * @package Itkg\Migration\XML\EntityParser
 */
abstract class AbstractShowroomXMLSliceParser extends AbstractShowroomXMLEntityParser implements ShowroomXMLSliceParserInterface
{

    /** @var null|int */
    protected $maxCta;
    /** @var null|int */
    protected $maxCtaChild;
    /** @var null|int  */
    protected $ctaReferenceType;
    /** @var null|string */
    protected $ctaReferenceTypeGenerator;


    /**
     * @param PsaShowroomEntityFactory $entityFactory
     * @param XPathQueryHelper $xPathQuery
     * @param ShowroomXMLEntityParserFactory $xmlEntityParserFactory
     * @param string|null $nodeType
     */
    public function __construct(
        PsaShowroomEntityFactory $entityFactory,
        XPathQueryHelper $xPathQuery,
        ShowroomXMLEntityParserFactory $xmlEntityParserFactory,
        $nodeType = null
    )
    {
        parent::__construct($entityFactory, $xPathQuery, $xmlEntityParserFactory, $nodeType);
        $this->setDefaultCtaSetting();
    }


    /**
     * @return string
     */
    abstract public function getName();

    /**
     * Init default setting for cta parsing
     * This method should be override by child class if a different default setting is needed
     */
    protected function setDefaultCtaSetting()
    {
        $this->maxCta = null;
        $this->maxCtaChild = null;
        $this->ctaReferenceType = null;
        $this->ctaReferenceTypeGenerator = null;
    }


    /**
     * @param PsaCtaReferenceOwnerInterface $ctaOwner
     * @param ShowroomUrlManager $urlManager
     * @param PsaPageShowroomMetadata $currentPage
     * @param DOMXPath $rootXPath
     * @param DOMElement $articleNode
     * @param AddReportingMessageInterface $reporting
     * @param null $referenceId Optional for forcing value instead of generating it
     * @param null $referenceOrder Optional for forcing value instead of generating it
     */
    protected function fillCtasForReferenceOwner(
        PsaCtaReferenceOwnerInterface $ctaOwner,
        ShowroomUrlManager $urlManager,
        PsaPageShowroomMetadata $currentPage,
        DOMXPath $rootXPath,
        DOMElement $articleNode = null,
        AddReportingMessageInterface $reporting,
        $referenceId = null,
        $referenceOrder = null
    )
    {
        $ctasParser = $this->xmlEntityParserFactory->createCtasParser();
        // Set parser configuration value from parameters
        $ctasParser->setReferentOwner($ctaOwner);
        $ctasParser->setReferenceId($referenceId);
        $ctasParser->setReferenceOrder($referenceOrder);

        // Set parser configuration value from properties
        $ctasParser->setMaxCta($this->maxCta);
        $ctasParser->setMaxCta($this->maxCtaChild);
        $ctasParser->setReferenceType($this->ctaReferenceType);
        $ctasParser->setReferenceTypeGenerator($this->ctaReferenceTypeGenerator);

        // Parse CTA Links
        $ctaReferences = $ctasParser->parse($urlManager, $currentPage, $rootXPath, $articleNode, $reporting);

        // Add cta created to ctaOwner object
        foreach ($ctaReferences as $ctaReference) {
            /** @var $ctaReference */
            $ctaOwner->addCtaReferences($ctaReference);
        }
    }

    protected function updateTooltip($text) {
        $oldCodePattern = '#<span class="info_detail">i<span>(.*)</span></span>#U';

        $result = preg_replace_callback($oldCodePattern,function($matches) {
            $tooltip = strip_tags($matches[1]);
            $newCodePattern = '<span class="has-tip infobulle" title="%s" aria-haspopup="true" data-tooltip="">i</span>';
            return sprintf($newCodePattern,$tooltip);

        }, $text);

        return $result;
    }

}
