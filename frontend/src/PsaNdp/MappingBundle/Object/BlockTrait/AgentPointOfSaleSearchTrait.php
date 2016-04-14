<?php

namespace PsaNdp\MappingBundle\Object\BlockTrait;


/**
 * Common Smarty var used by Pf11 and Pf44
 *
 * Class AgentPointOfSaleCommonPropertiesTrait
 * @package PsaNdp\MappingBundle\Object\Block
 */
trait AgentPointOfSaleSearchTrait
{

    /**
     * @var string $or
     */
    protected $or;

    /**
     * @var array $searchType
     */
    protected $searchType;

    /**
     * @var string $filterBy
     */
    protected $filterBy;

    /**
     * @var string $btnAroundMe
     */
    protected $btnAroundMe;

    /**
     * @var string $moreFilter
     */
    protected $moreFilter;

    /**
     * @var string $moreFilterClose
     */
    protected $moreFilterClose;

    /**
     * @var string $resultFound
     */
    protected $resultFound;

    /**
     * @var string $resultFound
     */
    protected $resultNotFound;

    /**
     * @var array $listFilter
     */
    protected $listFilter;

    /**
     * @var array $listMoreFilter
     */
    protected $listMoreFilter;

    /**
     * @var array $mapParam
     */
    protected $mapParam;

    /**
     * @var string $searchSubmit
     */
    protected $searchSubmit;

    /**
     * @var string $searchSubmit
     */
    protected $distance;

    /**
     * @return string
     */
    public function getOr()
    {
        return $this->or;
    }

    /**
     * @param string $or
     */
    public function setOr($or)
    {
        $this->or = $or;
    }

    /**
     * @param array $searchType
     *
     * @return $this
     */
    public function setSearchType($searchType)
    {
        $this->searchType = $searchType;

        return $this;
    }

    /**
     * @return array
     */
    public function getSearchType()
    {
        return $this->searchType;
    }

    /**
     * @return string
     */
    public function getFilterBy()
    {
        return $this->filterBy;
    }

    /**
     * @param string $filterBy
     *
     * @return $this
     */
    public function setFilterBy($filterBy)
    {
        $this->filterBy = $filterBy;

        return $this;
    }

    /**
     * @param string $btnAroundMe
     */
    public function setBtnAroundMe($btnAroundMe)
    {
        $this->btnAroundMe = $btnAroundMe;
    }

    /**
     * @return string
     */
    public function getBtnAroundMe()
    {
        return $this->btnAroundMe;
    }

    /**
     * @param string $resultNotFound
     */
    public function setResultNotFound($resultNotFound)
    {
        $this->resultNotFound = $resultNotFound;
    }

    /**
     * @return string
     */
    public function getResultNotFound()
    {
        return $this->resultNotFound;
    }


    /**
     * @return string
     */
    public function getMoreFilter()
    {
        return $this->moreFilter;
    }

    /**
     * @param string $moreFilter
     */
    public function setMoreFilter($moreFilter)
    {
        $this->moreFilter = $moreFilter;
    }

    /**
     * @return string
     */
    public function getMoreFilterClose()
    {
        return $this->moreFilterClose;
    }

    /**
     * @param string $moreFilterClose
     */
    public function setMoreFilterClose($moreFilterClose)
    {
        $this->moreFilterClose = $moreFilterClose;
    }

    /**
     * @return array
     */
    public function getListFilter()
    {
        return $this->listFilter;
    }

    /**
     * @param array $listFilter
     *
     * @return AgentPointOfSaleSearchTrait
     */
    public function setListFilter($listFilter)
    {
        $this->listFilter = $listFilter;

        return $this;
    }

    /**
     * @return array
     */
    public function getListMoreFilter()
    {
        return $this->listMoreFilter;
    }

    /**
     * @param array $listMoreFilter
     *
     * @return AgentPointOfSaleSearchTrait
     */
    public function setListMoreFilter($listMoreFilter)
    {
        $this->listMoreFilter = $listMoreFilter;

        return $this;
    }

    /**
     * @param array $mapParam
     */
    public function setMapParam($mapParam)
    {
        $this->mapParam = $mapParam;
    }

    /**
     * @return array
     */
    public function getMapParam()
    {
        return $this->mapParam;
    }


    /**
     * @return string
     */
    public function getSearchSubmit()
    {
        return $this->searchSubmit;
    }

    /**
     * @param string $searchSubmit
     *
     * @return $this
     */
    public function setSearchSubmit($searchSubmit)
    {
        $this->searchSubmit = $searchSubmit;

        return $this;
    }

    /**
     * @return string
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @param string $distance
     *
     * @return AgentPointOfSaleSearchTrait
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * @return string
     */
    public function getResultFound()
    {
        return $this->resultFound;
    }

    /**
     * @param string $resultFound
     *
     * @return AgentPointOfSaleSearchTrait
     */
    public function setResultFound($resultFound)
    {
        $this->resultFound = $resultFound;

        return $this;
    }

}
