<?php

namespace PsaNdp\MappingBundle\Object\Block\Pf58Object;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PsaNdp\MappingBundle\Object\Content;

/**
 * Class Pf58Consommation
 */
class Pf58Consommation extends Content
{
    protected $mapping = array();

    /**
     * @var array $text
     */
    protected $texts;


    /**
     * @return array
     */
    public function getTexts()
    {
        return $this->texts;
    }

    /**
     * @param array $text
     */
    public function setTexts(array $text)
    {
        $this->texts = $text;
    }
}
