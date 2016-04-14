<?php

namespace PsaNdp\MappingBundle\Entity\Accessories;

use Doctrine\ORM\Mapping as ORM;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use PSA\MigrationBundle\Entity\Media\PsaMedia;

/**
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\Accessories\PsaAccessoriesRepository")
 * @ORM\Table(name="psa_accessoires")})
 */

class PsaAccessories {

    /**
     * @var PsaSite
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Site\PsaSite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SITE_ID", referencedColumnName="SITE_ID", nullable=false)
     * })
     */
    protected $site;

    /**
     * @var PsaMedia
     *
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Media\PsaMedia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="MEDIA_ID", referencedColumnName="MEDIA_ID", nullable=false)
     * })
     */
    private $media;

    /**
     *
     * @return PsaSite
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     *
     * @return PsaMedia
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     *
     * @param PsaSite $site
     *
     * @return PsaAccessories
     */
    public function setSite(PsaSite $site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     *
     * @param PsaMedia $media
     *
     * @return PsaAccessories
     */
    public function setMedia(PsaMedia $media)
    {
        $this->media = $media;

        return $this;
    }
}
