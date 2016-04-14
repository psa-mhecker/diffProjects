<?php

namespace PsaNdp\MappingBundle\Entity\Vehicle;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PsaColorType
 *
 * @ORM\Table(name="psa_type_couleur", options={"collate"="utf8_swedish_ci"})
 * @ORM\Entity()
 */
class PsaColorType
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
     * @ORM\Column(name="CODE", type="string", length=2, nullable=false)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="LABEL_CENTRAL", type="string", length=255, nullable=false)
     */
    protected $labelCentral;

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
     * @return PsaColorTypes
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     *
     * @param string $code
     *
     * @return PsaColorTypes
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getLabelCentral()
    {
        return $this->labelCentral;
    }

    /**
     *
     * @param string $labelCentral
     *
     * @return PsaColorTypes
     */
    public function setLabelCentral($labelCentral)
    {
        $this->labelCentral = $labelCentral;

        return $this;
    }
}
