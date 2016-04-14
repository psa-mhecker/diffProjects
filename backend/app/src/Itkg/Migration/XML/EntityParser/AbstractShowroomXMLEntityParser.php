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
abstract class AbstractShowroomXMLEntityParser implements ShowroomXMLEntityParserInterface
{
    /** @var PsaShowroomEntityFactory  */
    protected $entityFactory;
    /** @var XPathQueryHelper */
    protected $xPathQuery;
    /** @var ShowroomXMLEntityParserFactory */
    protected $xmlEntityParserFactory;
    /** @var string */
    protected $nodeType;

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
        $this->entityFactory = $entityFactory;
        $this->xPathQuery = $xPathQuery;
        $this->xmlEntityParserFactory = $xmlEntityParserFactory;
        $this->nodeType = $nodeType;
    }

    /**
     * @param ShowroomUrlManager $urlManager showroom urlManager
     * @param PsaPageShowroomMetadata $currentPage
     * @param DOMXPath $rootXPath xml root using xpath
     * @param DOMElement $node
     * @param AddReportingMessageInterface $reporting
     *
     * @return mixed
     */
    abstract public function parse(
        ShowroomUrlManager $urlManager,
        PsaPageShowroomMetadata $currentPage,
        DOMXPath $rootXPath,
        DOMElement $node,
        AddReportingMessageInterface $reporting
    );

    /**
     * @return string
     */
    public function getNodeType()
    {
        return $this->nodeType;
    }

    /**
     * @param string $nodeType
     *
     * @return AbstractShowroomXMLEntityParser
     */
    public function setNodeType($nodeType)
    {
        $this->nodeType = $nodeType;

        return $this;
    }


    /**
     * @param PsaCtaReferenceOwnerInterface $ctaOwner
     * @param ShowroomUrlManager $urlManager
     * @param PsaPageShowroomMetadata $currentPage
     * @param DOMXPath $rootXPath
     * @param DOMElement $articleNode
     * @param AddReportingMessageInterface $reporting
     * @param null $maxCta
     * @param null $referenceTypeGenerator
     * @param null $referenceId             Optional for forcing value instead of generating it
     * @param null $referenceOrder          Optional for forcing value instead of generating it
     */
    protected function fillCtasForOwner(
        PsaCtaReferenceOwnerInterface $ctaOwner,
        ShowroomUrlManager $urlManager,
        PsaPageShowroomMetadata $currentPage,
        DOMXPath $rootXPath,
        DOMElement $articleNode,
        AddReportingMessageInterface $reporting,
        $maxCta = null,
        $referenceTypeGenerator = null,
        $referenceId = null,
        $referenceOrder = null
    )
    {
        $ctasParser = $this->xmlEntityParserFactory->createCtasParser();
        $ctasParser->setReferentOwner($ctaOwner);
        $ctasParser->setReferenceTypeGenerator($referenceTypeGenerator);
        $ctasParser->setMaxCta($maxCta);
        $ctasParser->setReferenceId($referenceId);
        $ctasParser->setReferenceOrder($referenceOrder);
        $ctaReferences = $ctasParser->parse($urlManager, $currentPage, $rootXPath, $articleNode, $reporting);

        foreach ($ctaReferences as $ctaReference) {
            /** @var $ctaReference */
            $ctaOwner->addCtaReferences($ctaReference);
        }
    }

}
