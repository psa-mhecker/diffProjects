<?php
namespace Citroen\Service\Webstore\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetStockWebstoreResponse
 */
class GetStockWebstoreResponse extends BaseModel
{

    protected $GetStockWebstoreResult;

    /**
     *
     */
    public function getVehicles()
    {
        return $this->GetStockWebstoreResult->vehiclesList->VehicleProperties;
    }

    /**
     *
     */
    public function __toLog()
    {
        return ' Response : OK';
    }
}