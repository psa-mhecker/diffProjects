<?php
namespace PsaNdp\MappingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class PsaModel
 *
 * @ORM\Table(name="psa_model")
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\PsaModelRepository")
 */
class PsaModel
{
    /**
     *
     */
    public function __construct()
    {
        $this->viewAngles = new ArrayCollection();
    }

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="LCDV4", type="string", length=4, nullable=false, unique=true)
     *
     */
    protected $lcdv4;

    /**
     * @var string
     * @ORM\Column(name="GENDER", type="string", length=2, nullable=false)
     *
     */
    protected $gender;


    /**
     * @var string
     * @ORM\Column(name="MODEL", type="string", length=50, nullable=false)
     *
     */
    protected $model;

    /**
     * @var ArrayCollection $viewAngle
     *
     * @ORM\OneToMany(targetEntity="PsaNdp\MappingBundle\Entity\PsaModelViewAngle", mappedBy="model",)
     */
    protected $viewAngles;

    /**
     * @return string
     */
    public function getLcdv4()
    {
        return $this->lcdv4;
    }

    /**
     * @param string $lcdv4
     *
     * @return PsaModel
     */
    public function setLcdv4($lcdv4)
    {
        $this->lcdv4 = $lcdv4;

        return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     *
     * @return PsaModel
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param string $model
     *
     * @return PsaModel
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getViewAngles()
    {
        return $this->viewAngles;
    }

    /**
     * @param ArrayCollection $viewAngles
     *
     * @return PsaModel
     */
    public function setViewAngles(ArrayCollection $viewAngles)
    {
        $this->viewAngles = $viewAngles;

        return $this;
    }
}
