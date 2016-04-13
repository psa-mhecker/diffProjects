<?php

namespace Citroen\Service\GammeVU\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetConfiguratorUrlListRequest
 */
class GetConfiguratorUrlListRequest extends BaseModel
{

    protected $country;
    protected $culture;
    
    /**
     *
     */
    public function __toRequest()
    {
        $parameters = array();
        $parameters['country'] = $this->country;
        $parameters['culture'] = $this->culture;
        $aParams = array(
            'parameters' => urlencode(json_encode($parameters))
        );
     
        return $aParams;
    }

    public function __toXML()
    {
        $XML ="
				<psa:GetConfiguratorUrlList xmlns:psa=\"PSA.CFG3D.VU.Services\" xmlns:psa1=\"http://schemas.datacontract.org/2004/07/PSA.CFG3D.VU.Services.Input\">
					 <!--Optional:-->
					 <psa:input >
						<!--Optional:-->
						<psa1:Country xmlns:psa1=\"http://schemas.datacontract.org/2004/07/PSA.CFG3D.VU.Services.Input\">".$this->country."</psa1:Country>
						<!--Optional:-->
						<psa1:Culture xmlns:psa1=\"http://schemas.datacontract.org/2004/07/PSA.CFG3D.VU.Services.Input\">".$this->culture."</psa1:Culture>
					 </psa:input>
				  </psa:GetConfiguratorUrlList>";
        return $XML;
    }


    /**
     *
     */
    public function __toLog()
    {
        return ' Request : ';
    }

}
