<?php

namespace PsaNdp\MappingBundle\Entity\Vehicle;

use Doctrine\ORM\Mapping as ORM;
use PSA\MigrationBundle\Entity\Media\PsaMedia;

/**
 * Class PsaVehicleCategory
 *
 * @ORM\Table(name="psa_vehicle_category", options={"collate"="utf8_swedish_ci"})
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\PsaVehicleCategoryRepository")
 */
class PsaVehicleCategory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="LABEL_CENTRAL", type="string", length=255, nullable=false)
     */
    private $labelCentral;

    /**
     * @var int
     *
     * @ORM\Column(name="MEDIA_ID", type="integer", nullable=false)
     */
    private $mediaId;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param int $id
     *
     * @return PsaVehicleCategory
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabelCentral()
    {
        return $this->labelCentral;
    }

    /**
     * @param string $labelCentral
     */
    public function setLabelCentral($labelCentral)
    {
        $this->labelCentral = $labelCentral;

        return $this;
    }

    /**
     *
     * @return int
     */
    public function getMediaId()
    {
        return $this->mediaId;
    }

    /**
     *
     * @param int $mediaId
     *
     * @return PsaVehicleCategory
     */
    public function setMediaId($mediaId)
    {
        $this->mediaId = $mediaId;

        return $this;
    }

    /**
     * @return PsaMedia
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param PsaMedia $media
     */
    public function setMedia(PsaMedia $media)
    {
        $this->media = $media;

        return $this;
    }
}
