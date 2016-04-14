<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Interface DataSourceInterface
 *
 * @package PsaNdp\MappingBundle\Sources
 */
interface DataSourceInterface
{
    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input BlockInterface and current url Request
     *
     * @param ReadBlockInterface $block
     * @param Request            $request  Current url request displaying th block
     * @param bool               $isMobile Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile);

    /**
     * @param TranslatorInterface $translator
     * @param string|null         $domain
     * @param string|null         $locale
     *
     * @return DataSourceInterface
     */
    public function setTranslator(TranslatorInterface $translator, $domain = null, $locale = null);

    /**
     * @param ReadBlockInterface $block
     *
     * @return $this
     */
    public function setBlock(ReadBlockInterface $block);

    /**
     * @return ReadBlockInterface
     */
    public function getBlock();

    /**
     * @param $mediaServer
     *
     * @return mixed
     */
    public function setMediaServer($mediaServer);

    /**
     * @return string
     */
    public function getMediaServer();

    /**
     * @return ReadBlockInterface
     */
    public function getRealBlock();

    /**
     * @param ReadBlockInterface $realBlock
     * @return $this
     */
    public function setRealBlock($realBlock);
}
