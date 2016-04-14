<?php

namespace PsaNdp\MappingBundle\Entity\Vehicle;

use Doctrine\ORM\Mapping as ORM;


/**
 * Class PsaModelSilhouette
 *
 * @ORM\Table(name="psa_ws_gdg_model_silhouette", options={"collate"="utf8_swedish_ci"})
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\PsaModelSilhouetteRepository")
 */
class PsaModelSilhouette
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
     * @ORM\Column(name="GENDER", type="string", length=2, nullable=false)
     */
    protected $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="LCDV6", type="string", length=6, nullable=false)
     */
    protected $lcdv6;

    /**
     * @var string
     *
     * @ORM\Column(name="MODEL", type="string", length=255, nullable=false)
     */
    protected $modele;

    /**
     * @var string
     *
     * @ORM\Column(name="SILHOUETTE", type="string", length=255, nullable=false)
     */
    protected $silhouette;


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
     * @return PsaModelSilhouette
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
    public function getGender()
    {
        return $this->gender;
    }

    /**
     *
     * @return string
     */
    public function getLcdv6()
    {
        return $this->lcdv6;
    }

    /**
     *
     * @param string $gender
     *
     * @return PsaModelSilhouette
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     *
     * @param string $lcdv6
     *
     * @return PsaModelSilhouette
     */
    public function setLcdv6($lcdv6)
    {
        $this->lcdv6 = $lcdv6;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getModel()
    {
        return $this->modele;
    }

    /**
     *
     * @return string
     */
    public function getSilhouette()
    {
        return $this->silhouette;
    }

    /**
     *
     * @param string $modele
     *
     * @return PsaModelSilhouette
     */
    public function setModel($modele)
    {
        $this->modele = $modele;

        return $this;
    }

    /**
     *
     * @param string $silhouette
     *
     * @return PsaModelSilhouette
     */
    public function setSilhouette($silhouette)
    {
        $this->silhouette = $silhouette;

        return $this;
    }
}
