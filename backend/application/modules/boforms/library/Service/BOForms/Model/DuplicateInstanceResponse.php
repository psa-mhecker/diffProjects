<?php
//namespace Service\BOForms\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe DuplicateInstanceResponse
 */
class Plugin_BOForms_Model_DuplicateInstanceResponse extends BaseModel
{

    protected $DuplicateInstanceResponse;

    /**
     *
     */
    public function __toLog()
    {
        return ' Response : OK';
    }
}