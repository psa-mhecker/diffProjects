<?php

namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Translation\TranslatorAwareTrait;
use Symfony\Component\Config\Definition\Exception\Exception;
use PsaNdp\MappingBundle\Helper\HelperInterface;

abstract class AbstractDataTransformer implements DataTransformerInterface
{
    use TranslatorAwareTrait;

    /** @var array */
    protected $helpers = array();
    /** @var string */
    protected $mediaServer;

    /**
     * @param $helper HelperInterface
     *
     * @return DataTransformerInterface
     */
    public function addHelper(HelperInterface $helper) 
    {
        $this->helpers[$helper->getName()]  = $helper;

        return $this;
    }

    /**
     * @param $name
     *
     * @return  $helper HelperInterface
     */
    public function getHelper($name) 
    {
        if (!isset($this->helpers[$name])) {
            throw new Exception('Unknow Helper');
        }

        return $this->helpers[$name];
    }

    /**
     * @param string $mediaServer
     *
     * @return DataTransformerInterface
     */
    public function setMediaServer($mediaServer)
    {
        $this->mediaServer = $mediaServer;

        return $this;
    }

    /**
     * Get block
     *
     * @return mixed
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @param mixed $block
     *
     * @return AbstractDataTransformer
     */
    public function setBlock($block)
    {
        $this->block = $block;

        return $this;
    }

}
