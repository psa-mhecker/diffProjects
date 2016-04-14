<?php
namespace Service\GDVCars\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe CarsRequest.
 */
class CarsRequest extends BaseModel
{
    protected $ranges;
    protected $brands;
    protected $countries;
    protected $languages;
    protected $contexts;

    /**
     *
     */
    public function __toRequest()
    {
        $aParams = array(
            /*'q' => $this->q,
            'client' => $this->client,
            'output' => $this->output,
            'sort' => $this->sort,
            'site' => $this->site,
            'start' => $this->start,
            'num' => $this->num,
            'tlen' => $this->tlen,
            'filter' => 0,*/
            'ranges' => $this->ranges,
            'brands' => $this->brands,
            'countries' => $this->countries,
            'languages' => $this->languages,
            'contexts' => $this->contexts,
            'site' => 'toto'

        );

        return $aParams;
    }

    /**
     *
     */
    public function __toLog()
    {
        return ' Request : ';
    }
}
