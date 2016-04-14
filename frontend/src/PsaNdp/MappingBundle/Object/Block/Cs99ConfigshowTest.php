<?php
namespace PsaNdp\MappingBundle\Object\Block;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Object\Content;

/**
 * Class Cs99ConfigshowTest
 * @package PsaNdp\MappingBundle\Object\Block
 */
class Cs99ConfigshowTest extends Content
{
    /**
     * @var string
     */
    protected $content;
    /**
     * @var string
     */
    protected $filename;

    public function setBlock(PsaPageZoneConfigurableInterface $block)
    {
        parent::setBlock($block);

        $this->filename= $block->getZoneParameters();
        $this->content = '';
        $doc = new \DOMDocument();
        $doc->strictErrorChecking = false;
        $doc->validateOnParse = false;
        // i know this is evil but patternlab html is not validate by DomDocument :(
        @$doc->loadHTMLFile($this->filename);
        $xpath = new \DOMXPath($doc);
        $nodes = $xpath->query('/html/body/section');
        $output = new  \DOMDocument();

        foreach ($nodes as $node) {
            $output->appendChild($output->importNode($node, true));

        }
        $this->content .= $output->saveHTML();

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }
}
