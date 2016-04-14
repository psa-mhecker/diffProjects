<?php

namespace PsaNdp\MappingBundle\Entity\Vehicle;

use Doctrine\ORM\Mapping as ORM;
use PsaNdp\MappingBundle\Entity\Vehicle\PsaModelSilhouette;


/**
 * Class PsaModelSilhouetteAngle
 *
 * @ORM\Table(name="psa_ws_gdg_model_silhouette_angle", options={"collate"="utf8_swedish_ci"})
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\Vehicle\PsaModelSilhouetteAngleRepository")
 */
class PsaModelSilhouetteAngle
{

    /**
     * @var PsaModelSilhouette
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PsaNdp\MappingBundle\Entity\Vehicle\PsaModelSilhouette")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="MODEL_SILHOUETTE_ID", referencedColumnName="ID", nullable=false)
     * })
     */
    protected $modeleSilhouette;

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="CODE", type="string", length=3, nullable=false)
     */
    protected $code;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ANGLE", type="boolean", nullable=true)
     */
    protected $angleInitial;

    /**
     * @var integer
     *
     * @ORM\Column(name="ANGLE_ORDER", type="integer", length=11, nullable=false)
     */
    protected $angleOrder;


    /**
     *
     * @return PsaModelSilhouette
     */
    public function getModeleSilhouette()
    {
        return $this->modeleSilhouette;
    }

    /**
     *
     * @param PsaModelSilhouette $modeleSilhouette
     *
     * @return PsaModelSilhouetteAngle
     */
    public function setModeleSilhouette($modeleSilhouette)
    {
        $this->modeleSilhouette = $modeleSilhouette;

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
     * @return PsaModelSilhouetteAngle
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     *
     * @return int
     */
    public function getAngleOrdre()
    {
        return $this->angleOrder;
    }

    /**
     *
     * @param int $angleOrder
     *
     * @return PsaModelSilhouetteAngle
     */
    public function setAngleOrdre($angleOrder)
    {
        $this->angleOrder = $angleOrder;

        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function getAngleInitial()
    {
        return $this->angleInitial;
    }

    /**
     *
     * @param boolean $angleInitial
     *
     * @return PsaModelSilhouetteAngle
     */
    public function setAngleInitial($angleInitial)
    {
        $this->angleInitial = $angleInitial;

        return $this;
    }
}
