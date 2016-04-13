<?php
namespace Citroen\Service\BoutiqAcc\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetCriteriaValuesResponse
 */
class GetCriteriaValuesResponse extends BaseModel
{

    protected $criteriaValueOut;

    /**
     *
     */
    public function __toLog()
    {
        return ' Response : OK';
    }
}