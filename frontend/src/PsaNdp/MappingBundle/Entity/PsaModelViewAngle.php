<?php
namespace PsaNdp\MappingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

    /**
     * Class PsaModelViewAngle
     *
     * @ORM\Table(name="psa_model_view_angle")})
     * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\PsaModelViewAngleRepository")
     */
class PsaModelViewAngle
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="LCDV4", type="string", length=4, nullable=false)
     */
    protected $lcdv4;

    /**
     * @var PsaModel
     * @ORM\ManyToOne(targetEntity="PsaNdp\MappingBundle\Entity\PsaModel", inversedBy="viewAngles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="LCDV4", referencedColumnName="LCDV4", nullable=false, onDelete="CASCADE" )
     * })
     *
     */
    protected $model;

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="CODE", type="string", length=3, nullable=false)
     */
    protected $code;

    /**
     * @var boolean
     * @ORM\Column(name="START_ANGLE", type="boolean", nullable=false)
     */
    protected $startAngle;

    /**
     * @var integer
     * @ORM\Column(name="ANGLE_ORDER", type="integer", nullable=false)
     */
    protected $order;

    /**
     * @return string
     */
    public function getLcdv4()
    {
        return $this->lcdv4;
    }

    /**
     * @param string $lcdv4
     */
    public function setLcdv4($lcdv4)
    {
        $this->lcdv4 = $lcdv4;

        return $this;
    }

    /**
     * @return PsaModel
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param PsaModel $model
     */
    public function setModel(PsaModel $model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isStartAngle()
    {
        return $this->startAngle;
    }

    /**
     * @param boolean $startAngle
     */
    public function setStartAngle($startAngle)
    {
        $this->startAngle = $startAngle;

        return $this;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }
}
