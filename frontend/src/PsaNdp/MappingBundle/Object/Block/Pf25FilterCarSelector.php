<?php

namespace PsaNdp\MappingBundle\Object\Block;

use PsaNdp\MappingBundle\Object\Content;

/**
 * Class Pf25FilterCarSelector
 */
class Pf25FilterCarSelector extends Content
{
    /**
     * @var string
     */
    protected $urlJson;

    /**
     * @return string
     */
    public function getUrlJson()
    {
        return $this->urlJson;
    }

    /**
     * @param string $urlJson
     */
    public function setUrlJson($urlJson)
    {
        $this->urlJson = $urlJson;
    }

    /**
     * @return string
     */
    public function getLegalMention()
    {
        $return = '';

        if (isset($this->translate['legalMention'])) {
            $return = $this->translate['legalMention'];
        }

        return $return;
    }

    /**
     * @return array
     */
    public function getResults()
    {
        return array();
    }
}
