<?php

namespace PsaNdp\MappingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PsaFinishingColor
 *
 * @ORM\Table(name="psa_finishing_color")})
 *  * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\PsaFinishingColorRepository")
 */
class PsaFinishingColor
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="LABEL", type="string", length=50, nullable=false)
     */
    protected $label;

    /**
     * @var string
     *
     * @ORM\Column(name="COLOR_CODE", type="string", length=8, nullable=false)
     */
    protected $colorCode;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return PsaFinishingColor
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return PsaFinishingColor
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getColorCode()
    {
        return $this->colorCode;
    }

    /**
     * @param string $colorCode
     *
     * @return PsaFinishingColor
     */
    public function setColorCode($colorCode)
    {
        $this->colorCode = $colorCode;

        return $this;
    }

}
