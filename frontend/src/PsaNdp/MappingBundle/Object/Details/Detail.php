<?php

namespace PsaNdp\MappingBundle\Object\Details;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PsaNdp\MappingBundle\Object\Content;

/**
 * Class Detail
 */
class Detail extends Content
{
    protected $mapping = array(
        'equActive' => 'equipmentActive',
        'equComplementaires' => 'equipmentAdditional',
        'caracteristiques' => 'equipmentActive',
        'motorisations' => 'infos',
        'caracteristiques' => 'infos',
    );

    /**
     * @var string $class
     */
    protected $class;

    /**
     * @var Collection $equipmentActive
     */
    protected $equipmentActive;

    /**
     * @var Collection $equipmentAdditional
     */
    protected $equipmentAdditional;

    /**
     * @var array infos
     */
    protected $infos;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->equipmentActive = new ArrayCollection();
        $this->equipmentAdditional = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getEquipmentActive()
    {
        return $this->equipmentActive;
    }

    /**
     * @param Collection $equipmentActive
     *
     * @return $this
     */
    public function setEquipmentActive($equipmentActive)
    {
        $this->equipmentActive = $equipmentActive;

        return $this;
    }

    /**
     * @param Equipments $equipments
     */
    public function addEquipmentActive(Equipments $equipments)
    {
        $this->equipmentActive->add($equipments);
    }

    /**
     * @return Collection
     */
    public function getEquipmentAdditional()
    {
        return $this->equipmentAdditional;
    }

    /**
     * @param Collection $equipmentAdditional
     *
     * @return $this
     */
    public function setEquipmentAdditional($equipmentAdditional)
    {
        $this->equipmentAdditional = $equipmentAdditional;

        return $this;
    }

    /**
     * @param Equipments $equipments
     */
    public function addEquipmentAdditional(Equipments $equipments)
    {
        $this->equipmentAdditional->add($equipments);
    }

    /**
     * @return array
     */
    public function getInfos()
    {
        return $this->infos;
    }

    /**
     * @param array $infos
     *
     * @return Detail
     */
    public function setInfos($infos)
    {
        $this->infos = $infos;

        return $this;
    }
}
