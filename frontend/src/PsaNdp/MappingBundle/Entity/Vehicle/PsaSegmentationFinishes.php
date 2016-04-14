<?php

namespace PsaNdp\MappingBundle\Entity\Vehicle;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PsaSegmentationFinishes
 *
 * @ORM\Table(name="psa_segmentation_finition", options={"collate"="utf8_swedish_ci"})
 * @ORM\Entity()
 */
class PsaSegmentationFinishes
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
     * @ORM\Column(name="CODE", type="string", length=255, nullable=false)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="LABEL", type="string", length=255, nullable=false)
     */
    protected $label;

    /**
     * @var integer
     *
     * @ORM\Column(name="ORDER_TYPE", type="integer", nullable=true)
     */
    protected $orderType;

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
     * @return PsaSegmentationFinishess
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
     * @return PsaSegmentationFinishess
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
    public function getLabel()
    {
        return $this->label;
    }

    /**
     *
     * @param string $label
     *
     * @return PsaSegmentationFinishess
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     *
     * @return int
     */
    public function getOrderType()
    {
        return $this->orderType;
    }

    /**
     *
     * @param int $orderType
     *
     * @return PsaSegmentationFinishess
     */
    public function setOrderType($orderType)
    {
        $this->orderType = $orderType;

        return $this;
    }
}
