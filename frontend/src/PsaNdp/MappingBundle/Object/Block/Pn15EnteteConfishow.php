<?php

namespace PsaNdp\MappingBundle\Object\Block;

use PsaNdp\MappingBundle\Object\BlockTrait\Pt22MyPeugeotTrait;
use PsaNdp\MappingBundle\Object\Breadcrumb;

/**
 * Class Pn15EnteteConfishow.
 * @codeCoverageIgnore
 */
class Pn15EnteteConfishow extends Breadcrumb
{
    use Pt22MyPeugeotTrait;

    const NDP_NEW_CAR = 'NDP_NEW_CAR';
    const NDP_PEUGEOT = 'NDP_PEUGEOT';

    /**
     * @var string
     */
    protected $silhouette;

    /**
     * @var string
     */
    protected $model;

    /**
     * @var bool
     */
    protected $isNew;

    /**
     * @return $this
     */
    public function init()
    {
        $this->breadcrumb = $this->initBreadcrumb();

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsNew()
    {
        return $this->isNew;
    }

    /**
     * @param bool $isNew
     *
     * @return $this
     */
    public function setIsNew($isNew)
    {
        $this->isNew = $isNew;

        return $this;
    }


    /**
     * @return string
     */
    public function getSilhouette()
    {
        return $this->silhouette;
    }

    /**
     * @param string $silhouette
     *
     * @return Pn15EnteteConfishow
     */
    public function setSilhouette($silhouette)
    {
        $this->silhouette = $silhouette;

        return $this;
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param string $model
     *
     * @return Pn15EnteteConfishow
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }


}
