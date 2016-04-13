<?php
namespace Citroen\Service\Webstore\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetVehiclesResponse
 */
class GetVehiclesResponse extends BaseModel
{

    protected $GetVehiclesResult;

    /**
     *
     */
    public function getVehicles()
    {
         return $this->GetVehiclesResult;
    }

    /**
     *
     */
    public function __toLog()
    {
        return ' Response : OK';
    }
}