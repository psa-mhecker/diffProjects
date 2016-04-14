<?php

namespace PsaNdp\MappingBundle\Object\BlockTrait;

trait Pt22MyPeugeotTrait
{
    /**
     * @var null|array
     */
    protected $myPeugeot;

    /**
     * @param null|array $myPeugeot
     *
     * @return $this
     */
    public function setMyPeugeot($myPeugeot)
    {
        $this->myPeugeot = $myPeugeot;

        return $this;
    }

    /**
     * @return null|array
     */
    public function getMyPeugeot()
    {
        return $this->myPeugeot;
    }
}
