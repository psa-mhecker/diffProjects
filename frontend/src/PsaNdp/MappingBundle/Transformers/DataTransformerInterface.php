<?php


namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Helper\HelperInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Interface DataSourceInterface
 *
 * @package PsaNdp\MappingBundle\Sources
 */
interface DataTransformerInterface
{
    /**
     * Return Data for a specific template rendering
     *
     * @param array $dataSource Data source fetch from its associated DataSourceInterface getData() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile);

    /**
     * @param TranslatorInterface $translator
     * @param string|null         $domain
     * @param string|null         $locale
     *
     * @return DataTransformerInterface
     */
    public function setTranslator(TranslatorInterface $translator, $domain = null, $locale = null);

    /**
     * @param string $mediaServer
     *
     * @return DataTransformerInterface
     */
    public function setMediaServer($mediaServer);

    /**
     * @param $helper HelperInterface
     *
     * @return DataTransformerInterface
     */
    public function addHelper(HelperInterface $helper);

}
