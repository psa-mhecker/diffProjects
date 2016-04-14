<?php
namespace PsaNdp\MappingBundle\Object\Block\Pf2Object;

use PsaNdp\MappingBundle\Object\Content;
/**
 * @codeCoverageIgnore
 */
class Pf2Infos extends Content
{
    /**
     * @var string
     */
    protected $date;

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     *
     * @return Pf2Infos
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

}
