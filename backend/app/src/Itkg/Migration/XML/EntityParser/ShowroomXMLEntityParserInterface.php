<?php

namespace Itkg\Migration\XML\EntityParser;


use DOMXPath;
use DOMElement;
use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\UrlManager\ShowroomUrlManager;

/**
 * Interface ShowroomXMLEntityParserInterface
 * @package Itkg\Migration\XML\EntityParser
 */
interface ShowroomXMLEntityParserInterface
{
    /**
     * Parse XML and return an entity object filled with the XML data
     *
     * @param ShowroomUrlManager $urlManager
     * @param PsaPageShowroomMetadata $currentPage
     * @param DOMXPath $rootXPath
     * @param DOMElement $node
     * @param AddReportingMessageInterface $reporting
     *
     * @return mixed
     */
    public function parse(
        ShowroomUrlManager $urlManager,
        PsaPageShowroomMetadata $currentPage,
        DOMXPath $rootXPath,
        DOMElement $node,
        AddReportingMessageInterface $reporting
    );

    /**
     * @return string
     */
    public function getNodeType();

    /**
     * @param string $nodeType
     *
     * @return ShowroomXMLEntityParserInterface
     */
    public function setNodeType($nodeType);

}
