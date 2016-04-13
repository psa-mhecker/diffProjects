<?php
namespace Citroen\Service\MoteurConfig\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetVehiclesResponse
 */
class SelectResponse extends BaseModel
{

    protected $SelectResponse;

    /**
     *
     */
    public function __toLog()
    {
        return ' Response : OK';
    }
}