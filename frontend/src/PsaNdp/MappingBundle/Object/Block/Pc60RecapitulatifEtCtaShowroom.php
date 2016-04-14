<?php

namespace PsaNdp\MappingBundle\Object\Block;

use Doctrine\Common\Collections\Collection;
use PsaNdp\MappingBundle\Entity\Vehicle\PsaModelSilhouetteSite;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Cta;
use PsaNdp\MappingBundle\Object\Factory\CtaFactory;

/**
 * Class Pc60RecapitulatifEtCtaShowroom
 */
class Pc60RecapitulatifEtCtaShowroom extends Content
{
    /**
     * @var array
     */
    protected $links = array();
    protected $cta;
    protected $model;

    /**
     * @param CtaFactory $ctaFactory
     */
    public function __construct(CtaFactory $ctaFactory)
    {
        $this->ctaFactory = $ctaFactory;
    }

    /**
     * @return Cta|null
     */
    public function getCta()
    {
        return $this->cta;
    }

    /**
     * @param Collection $cta
     *
     * @return $this
     */
    public function setCta($cta)
    {
        if (!empty($cta)) {
            $this->cta = $this->ctaFactory->create($cta, array('color' => Cta::NDP_CTA_VERSION_LIGHT_BLUE));
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param Collection $links
     *
     * @return $this
     */
    public function setLinks($links)
    {
        if (count($links)>0) {
            $this->links = $this->ctaFactory->create($links, array('type' => Cta::NDP_CTA_TYPE_SIMPLELINK));
        }

        return $this;
    }
}
