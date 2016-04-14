<?php

namespace PsaNdp\MappingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="psa_carselectorfilter")
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\PsaCarSelectorFilterRepository")
 */
class PsaCarSelectorFilter
{

    /**
     * @var PsaSite
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Site\PsaSite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SITE_ID", referencedColumnName="SITE_ID")
     * })
     */
    public $site;

    /**
     * @var float
     *
     * @ORM\Column(name="PRICE_GAUGE", type="float")
     */
    public $priceGauge;

    /**
     * @var float
     *
     * @ORM\Column(name="CONSO_GAUGE", type="float")
     */
    public $consoGauge;

    /**
     * @var float
     *
     * @ORM\Column(name="LENGTH_GAUGE", type="float")
     */
    public $lengthGauge;

    /**
     * @var float
     *
     * @ORM\Column(name="WIDTH_GAUGE", type="float")
     */
    public $widthGauge;

    /**
     * @var float
     *
     * @ORM\Column(name="HEIGHT_GAUGE", type="float")
     */
    public $heightGauge;

    /**
     * @var float
     *
     * @ORM\Column(name="VOLUME_LVL1", type="float")
     */
    public $volumeLvl1;

    /**
     * @var float
     *
     * @ORM\Column(name="VOLUME_LVL2", type="float")
     */
    public $volumeLvl2;

    /**
     * @var string
     *
     * @ORM\Column(name="CLASS_A_LABEL", type="string")
     */
    public $classALabel;

    /**
     * @var string
     *
     * @ORM\Column(name="CLASS_B_LABEL", type="string")
     */
    public $classBLabel;

    /**
     * @var string
     *
     * @ORM\Column(name="CLASS_C_LABEL", type="string")
     */
    public $classCLabel;

    /**
     * @var string
     *
     * @ORM\Column(name="CLASS_D_LABEL", type="string")
     */
    public $classDLabel;

    /**
     * @var string
     *
     * @ORM\Column(name="CLASS_E_LABEL", type="string")
     */
    public $classELabel;

    /**
     * @var string
     *
     * @ORM\Column(name="CLASS_F_LABEL", type="string")
     */
    public $classFLabel;

    /**
     * @var string
     *
     * @ORM\Column(name="CLASS_G_LABEL", type="string" )
     */
    public $classGLabel;

    /**
     * @var float
     *
     * @ORM\Column(name="PRICE_GAUGE_MONTHLY", type="float")
     */
    public $priceGaugeMonthly;

}
