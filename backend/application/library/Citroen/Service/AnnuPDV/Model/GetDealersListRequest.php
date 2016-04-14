<?php

namespace Citroen\Service\AnnuPDV\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetDealersListRequest.
 */
class GetDealersListRequest extends BaseModel
{
    protected $details;
    protected $latitude;
    protected $longitude;
    protected $rmax;
    protected $resultmax;
    protected $criterias;
    protected $sort;
    protected $country;
    protected $culture;
    protected $consumer;
    protected $brand;
    protected $searchtype;
    protected $rmin;
    protected $rstep;
    protected $minpdv;
    protected $mindvn;

    /**
     *
     */
    public function __toRequest()
    {
        $parameters = array();
        $parameters['consumer'] = $this->consumer;
        $parameters['brand'] = $this->brand;
        $parameters['country'] = $this->country;
        $parameters['culture'] = $this->culture;
        $parameters['sort'] = $this->sort;
        $parameters['details'] = $this->details;
        $parameters['latitude'] = $this->latitude;
        $parameters['longitude'] = $this->longitude;
        $parameters['searchtype'] = $this->searchtype;
        $parameters['unit'] = $this->country == 'GB' ? 'mi' : 'km';
        if ($this->rmax) {
            $parameters['rmax'] = $this->rmax;
        }
        if ($this->resultmax) {
            $parameters['resultmax'] = $this->resultmax;
        }
        if ($this->rmin) {
            $parameters['rmin'] = $this->rmin;
        }
        if ($this->rstep) {
            $parameters['rstep'] = $this->rstep;
        }
        if ($this->minpdv) {
            $parameters['minpdv'] = $this->minpdv;
        }
        if ($this->mindvn) {
            $parameters['mindvn'] = $this->mindvn;
        }
        if ($this->criterias) {
            $parameters['criterias'] = $this->criterias;
        }
        $aParams = array(
            'parameters' => urlencode(json_encode($parameters)),
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
