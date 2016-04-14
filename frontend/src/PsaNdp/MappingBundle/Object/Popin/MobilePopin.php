<?php

namespace PsaNdp\MappingBundle\Object\Popin;

use PsaNdp\MappingBundle\Object\Content;

/**
 * Class MobilePopin
 */
class MobilePopin extends Content
{
    protected $mapping = array();

    /**
     * @var string $close
     */
    protected $close;

    /**
     * @var Popin $listPopin
     */
    protected $listPopin;

    /**
     * @param Popin $popin
     */
    public function __construct(Popin $popin)
    {
        parent::__construct();
        $this->listPopin = $popin;
    }

    /**
     * @return string
     */
    public function getClose()
    {
        return $this->close;
    }

    /**
     * @param string $close
     *
     * @return $this
     */
    public function setClose($close)
    {
        $this->close = $close;

        return $this;
    }

    /**
     * @return Popin
     */
    public function getListPopin()
    {
        return $this->listPopin;
    }

    /**
     * @param Popin $listPopin
     *
     * @return $this
     */
    public function setListPopin(Popin $listPopin)
    {
        $this->listPopin = $listPopin;

        return $this;
    }
}
