<?php

namespace PsaNdp\MappingBundle\Object\Block;

use Exception;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Factory\CtaFactory;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;
use PsaNdp\MappingBundle\Object\Media;

/**
 * Class Pc59Tolls
 */
class Pc59Tools extends Content
{
    /**
     * @var bool
     */
    protected $displayMedia = false;

    /**
     * @param CtaFactory $ctaFactory
     * @param MediaFactory $mediaFactory
     */
    public function __construct(CtaFactory $ctaFactory, MediaFactory $mediaFactory)
    {
        $this->ctaFactory = $ctaFactory;
        $this->mediaFactory = $mediaFactory;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getTitre()
    {
        if ($this->block instanceof PsaPageZoneConfigurableInterface) {
            return $this->block->getZoneTitre();
        }

        throw new Exception(sprintf('Block object is not instance of %s', 'PsaPageZoneConfigurableInterface'));
    }

    /**
     * Initialize cta and media if is 4 cta
     */
    public function initCta()
    {
        $this->ctaList = array();
        if ($this->block instanceof PsaPageZoneConfigurableInterface) {

            $this->ctaList = $this->ctaFactory->create($this->block->getCtaReferences(), array('icon' => true));
        }
    }
}
