<?php

namespace Citroen\Service\GDG\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetCarPickerRequest
 */

class GetCarPickerRequest extends BaseModel
{
    protected $languages;
    protected $countries;
    protected $brands;
    protected $ranges;
    protected $_format;
    protected $contexts;

    /**
     *
     */
    public function __toRequest()
    {
            $aParams = array(
                                'languages' => $this->languages,
                                'countries' => $this->countries,
                                'brands'    => $this->brands,
                                'ranges'    => $this->ranges,
                                '_format'   => $this->_format,
                                'contexts'   => $this->contexts
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
