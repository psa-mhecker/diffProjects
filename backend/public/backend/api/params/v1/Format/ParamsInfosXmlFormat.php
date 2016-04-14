<?php
/**
 * Format XML Restler pour la méthode index de params
 *
 * @author David Moaté <david.moate@businessdecision.com>
 */

namespace ParamsApi\v1\Format;
use Luracast\Restler\Format\XmlFormat;
use Luracast\Restler\Data\Object;
use XMLWriter;

class ParamsInfosXmlFormat extends XmlFormat
{
    public static $xsd = 'Params.xsd';
    
    public function encode($data, $humanReadable = false)
    {
        $data = Object::toArray($data);
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->startDocument('1.0', $this->charset);
        if ($humanReadable) {
            $xml->setIndent(true);
            $xml->setIndentString('    ');
        }
        $xml->startElement(static::$rootName);
        $xml->writeAttribute('xsi:noNamespaceSchemaLocation', self::$xsd);
        $xml->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $this->write($xml, $data, static::$rootName);
        $xml->endElement();
        return $xml->outputMemory();
    }
}
