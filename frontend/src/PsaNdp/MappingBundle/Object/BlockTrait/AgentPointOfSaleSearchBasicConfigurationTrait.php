<?php

namespace PsaNdp\MappingBundle\Object\BlockTrait;


/**
 * Common Smarty var used by Pf11 and Pf44
 *
 * Class AgentPointOfSaleCommonPropertiesTrait
 * @package PsaNdp\MappingBundle\Object\Block
 */
trait AgentPointOfSaleSearchBasicConfigurationTrait
{

    /**
     * @var string $urlJson
     */
    protected $urlJson;

    /**
     * @var boolean $autocompletion
     */
    protected $autocompletion;

    /**
     * @var boolean $regroupement
     */
    protected $regroupement;

    /**
     * @var string $errorload
     */
    protected $errorload;


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
    public function getUrlJson()
    {
        return $this->urlJson;
    }

    /**
     * @param boolean $autocompletion
     */
    public function setAutocompletion($autocompletion)
    {
        $this->autocompletion = $autocompletion;
    }

    /**
     * @return boolean
     */
    public function getAutocompletion()
    {
        return $this->autocompletion;
    }

    /**
     * @param boolean $regroupement
     */
    public function setRegroupement($regroupement)
    {
        $this->regroupement = $regroupement;
    }

    /**
     * @return boolean
     */
    public function getRegroupement()
    {
        return $this->regroupement;
    }

    /**
     * @param string $errorload
     */
    public function setErrorload($errorload)
    {
        $this->errorload = $errorload;
    }

    /**
     * @return string
     */
    public function getErrorload()
    {
        return $this->errorload;
    }
}
