<?php

namespace Citroen\Service\GDG\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetBrochureRequest
 */
class GetBrochureRequest extends BaseModel
{
	protected $languages;
	protected $countries;
	protected $brands;
	protected $ranges;
	protected $_format;

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
                                    '_format'   => $this->_format
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
