<?php
namespace Citroen\Service\BoutiqAcc\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetAccessoriesResponse.
 */
class GetAccessoriesResponse extends BaseModel
{
    protected $accessoryList;

    /**
     *
     */
    public function __toLog()
    {
        return ' Response : OK';
    }
}
