<?php
namespace Citroen\Service\GSA\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe SearchResponse
 */
class SearchResponse extends BaseModel
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