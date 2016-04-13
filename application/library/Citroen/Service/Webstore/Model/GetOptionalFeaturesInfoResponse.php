<?php
namespace Citroen\Service\Webstore\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetVehiclesResponse
 */
class GetOptionalFeaturesInfoResponse extends BaseModel
{

    protected $GetOptionalFeaturesInfoResult;

    /**
     *
     */
    public function getOptionalFeaturesInfo()
    {
        return $this->GetOptionalFeaturesInfoResult->OptionalFeaturesList;
    }

    /**
     *
     */
    public function __toLog()
    {
        return ' Response : OK';
    }
}