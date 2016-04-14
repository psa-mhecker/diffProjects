<?php

namespace Itkg\Migration\XML;

use Itkg\Migration\UrlManager\ShowroomUrlManager;

/**
 * Class XpathQueryHelper
 * @package Itkg\Migration\XML
 */
class XPathQueryHelper
{

    /**
     * Return the first element matching the $query for a given XML $rootXPath searching inside a $node
     *
     * @param $query
     * @param \DOMXPath $rootXPath
     * @param \DOMElement $node
     *
     * @return \DOMElement
     */
    public function queryFirstDOMElement($query, \DOMXPath $rootXPath, \DOMElement $node = null)
    {
        $result = null;
        $entries = $rootXPath->query($query, $node);

        if ($entries->length >= 1) {
            $result = $entries->item(0);
        }

        return $result;
    }

    /**
     * Return the string node value of the first element matching the $query
     * If no element found, return empty string
     *
     * @param $query
     * @param \DOMXPath $rootXPath
     * @param \DOMElement $node
     *
     * @return string
     */
    public function queryFirstDOMElementNodeValue($query, \DOMXPath $rootXPath, \DOMElement $node = null)
    {
        $result = '';
        $element = $this->queryFirstDOMElement($query, $rootXPath, $node);

        if (null !== $element) {
            $result = $element->nodeValue;
        }

        return $result;
    }


    /**
     * @param ShowroomUrlManager $urlManager
     * @param \DOMXPath $rootXPath
     * @param \DOMElement $pageNode
     * @param $urlKeyPrefix
     *
     * @return string
     */
    public function generateSubPagePath(ShowroomUrlManager $urlManager, \DOMXPath $rootXPath, \DOMElement $pageNode, $urlKeyPrefix = 'p=')
    {
        $result = '';
        $showroomResponsiveHtmlXmlNode = $this->queryFirstDOMElement('showroom_responsive_html', $rootXPath);

        if (null !== $showroomResponsiveHtmlXmlNode) {
            $urlKey = $pageNode->getAttribute('urlkey');
            $rootUrlKey = $showroomResponsiveHtmlXmlNode->getAttribute('urlkey');
            $result = $urlManager->generateSubPagePath($urlKey, $rootUrlKey, $urlKeyPrefix);
        }

        return $result;
    }


    /**
     * Return new welcome page url path for new page created
     *
     * @param ShowroomUrlManager $urlManager
     * @param \DOMXPath $rootXPath
     *
     * @return string
     *
     */
    public function generateWelcomePagePath(ShowroomUrlManager $urlManager, \DOMXPath $rootXPath)
    {
        $result = '';
        $showroomResponsiveHtmlXmlNode = $this->queryFirstDOMElement('showroom_responsive_html', $rootXPath);

        if (null !== $showroomResponsiveHtmlXmlNode) {
            $urlKey = $showroomResponsiveHtmlXmlNode->getAttribute('urlkey');
            $result = $urlManager->generateWelcomePagePath($urlKey);
        }

        return $result;
    }

    /**
     * @param \DOMElement $node
     * @return string
     */
    public function getXmlId(\DOMElement $node)
    {
        return $node->getAttribute('id');
    }
}
