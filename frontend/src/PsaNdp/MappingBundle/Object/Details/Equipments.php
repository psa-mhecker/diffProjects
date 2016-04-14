<?php

namespace PsaNdp\MappingBundle\Object\Details;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PsaNdp\MappingBundle\Object\Content;

/**
 * Class Equipments
 */
class Equipments extends Content
{
    protected $mapping = array(
        'cell' => 'equipments',
        'texts' => 'equipments',
    );

    /**
     * @var Collection
     */
    protected $equipments;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->equipments = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getEquipments()
    {
        return $this->equipments;
    }

    /**
     * @param Collection $equipments
     *
     * @return $this
     */
    public function setEquipments($equipments)
    {
        $this->equipments = $equipments;

        return $this;
    }

    /**
     * @param Equipment $equipment
     */
    public function addEquipment(Equipment $equipment)
    {
        $this->equipments->add($equipment);
    }
}
