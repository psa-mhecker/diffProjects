<?php
use Itkg\Service\Model as BaseModel;

/** * Classe SaveCalculationDisplayResponse.
 */class SaveCalculationDisplayResponse extends BaseModel
{    protected $responseDisplay;    /**     *     */    public function __toLog()
    {
        return ' Response : OK';

    }

}
