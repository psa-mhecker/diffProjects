<?php
/**
 * Format XML Restler 3 pour les méthodes de listing de media
 *
 * @author Vincent Paré <vincent.pare@businessdecision.com>
 */

namespace MediaApi\v1\Format;

use Luracast\Restler\Format\XmlFormat;
use Luracast\Restler\Data\Object;
use XMLWriter;

class MediaXmlFormat extends XmlFormat
{
    public static $xsd = 'Mediatheque.xsd';
    
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
