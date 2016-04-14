<?php
//namespace Service\BOForms\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetReferentialResponse
 */
class Plugin_BOForms_Model_GetReferentialResponse extends BaseModel
{

    protected $referentialList;

    /**
     *
     */
    public function __toLog()
    {
        return ' Response : OK';
    }
}