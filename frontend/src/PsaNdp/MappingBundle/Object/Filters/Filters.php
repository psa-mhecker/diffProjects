<?php

namespace PsaNdp\MappingBundle\Object\Filters;

use PsaNdp\MappingBundle\Object\AbstractObject;
use PsaNdp\MappingBundle\Object\Filters\Price;

/**
 * Class Filters
 */
class Filters extends AbstractObject
{
    protected $mapping = array();

    /**
     * @var Price $price
     */
    protected $price;

    /**
     * @var Gear $gear
     */
    protected $gear;

    /**
     * @var Energy $energy
     */
    protected $energy;

    /**
     * @param Price  $price
     * @param Energy $energy
     * @param Gear   $gear
     */
    public function __construct(Price $price, Energy $energy, Gear $gear)
    {
        parent::__construct();
        $this->gear = $gear;
        $this->price = $price;
        $this->energy = $energy;
    }

    /**
     * @return Energy
     */
    public function getEnergy()
    {
        return $this->energy;
    }

    /**
     * @param Energy $energy
     *
     * @return $this
     */
    public function setEnergy(Energy $energy)
    {
        $this->energy = $energy;

        return $this;
    }

    /**
     * @return Gear
     */
    public function getGear()
    {
        return $this->gear;
    }

    /**
     * @param Gear $gear
     *
     * @return $this
     */
    public function setGear(Gear $gear)
    {
        /**
         * Affiché si il existe au moin une version avec chacun des types de boite de vitesse
         * Ordre AO remonté par moteur de conf
         */
        $this->gear = $gear;

        return $this;
    }

    /**
     * @return Price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param Price $price
     *
     * @return $this
     */
    public function setPrice(Price $price)
    {
        /**
         * Le choix du mode d'affichage des prix est affiché uniquement si l'affichage des prix mensualisés a été activé en BO
         * Et si des offres de financement valide existe pour au moin une version du modèle regroupement de silhouette
         */
        $this->price = $price;

        return $this;
    }
}
