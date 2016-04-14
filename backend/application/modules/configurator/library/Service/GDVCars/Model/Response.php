<?php
namespace Service\GDVCars\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe CarsResponse.
 */
class CarsResponse extends BaseModel
{
    protected $gsp;

    /**
     *
     */
    public function __toLog()
    {
        return ' Response : OK';
    }
}
