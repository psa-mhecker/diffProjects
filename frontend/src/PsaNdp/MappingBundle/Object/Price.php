<?php

namespace PsaNdp\MappingBundle\Object;

/**
 * Class Price
 */
class Price extends AbstractObject
{
    protected $mapping = array();

    /**
     * @var string $sum
     */
    protected $sum;

    /**
     * @var string $devise
     */
    protected $devise;

    /**
     * @var string $indice
     */
    protected $indice;

    /**
     * @var string $mode
     */
    protected $mode;

    /**
     * @var string $taxe
     */
    protected $taxe;

    /**
     * @var string $mention
     */
    protected $mention;

    /**
     * @var string $by
     */
    protected $by;

    /**
     * @var string $rent
     */
    protected $rent;

    /**
     * @return string
     */
    public function getDevise()
    {
        return $this->devise;
    }

    /**
     * @param string $devise
     *
     * @return $this
     */
    public function setDevise($devise)
    {
        $this->devise = $devise;

        return $this;
    }

    /**
     * @return string
     */
    public function getIndice()
    {
        return $this->indice;
    }

    /**
     * @param string $indice
     *
     * @return $this
     */
    public function setIndice($indice)
    {
        $this->indice = $indice;

        return $this;
    }

    /**
     * @return string
     */
    public function getMention()
    {
        return $this->mention;
    }

    /**
     * @param string $mention
     *
     * @return $this
     */
    public function setMention($mention)
    {
        $this->mention = $mention;

        return $this;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     *
     * @return $this
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @return string
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * @param string $sum
     *
     * @return $this
     */
    public function setSum($sum)
    {
        $this->sum = $sum;

        return $this;
    }

    /**
     * @return string
     */
    public function getTaxe()
    {
        return $this->taxe;
    }

    /**
     * @param string $taxe
     *
     * @return $this
     */
    public function setTaxe($taxe)
    {
        $this->taxe = $taxe;

        return $this;
    }

    /**
     * @return string
     */
    public function getBy()
    {
        return $this->by;
    }

    /**
     * @param string $by
     */
    public function setBy($by)
    {
        $this->by = $by;
    }

    /**
     * @return string
     */
    public function getRent()
    {
        return $this->rent;
    }

    /**
     * @param string $rent
     *
     * @return $this
     */
    public function setRent($rent)
    {
        $this->rent = $rent;

        return $rent;
    }
}
