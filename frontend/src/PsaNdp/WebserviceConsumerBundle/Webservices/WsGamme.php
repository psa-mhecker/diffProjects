<?php

namespace PsaNdp\WebserviceConsumerBundle\Webservices;

/**
 * Class WsGamme
 * @package PsaNdp\WebserviceConsumerBundle\Webservices
 */
class WsGamme extends SoapConsumer
{
    const ENABLE_VERSION_STEP_UPSELLING = "EnableVersionStepUpselling";
    const INITIAL_VIEW                  = "Initial";
    const EXTERIOR_VIEW                 = "Exterior";

    /**
     * 
     * @return array
     */
    protected function getDefaultParameters()
    {
        $parameters = [];
        $parameters['input'] = [
            'Culture' => 'fr',
            'Country' => 'FR'
        ];

        return $parameters;
    }

    /**
     * set the Default required parameters for context
     *
     * @return  WsGamme
     */
    public function setDefaultContext()
    {
       
        return $this;
    }

    /**
     * @return mixed
     */
    public function ping()
    {
        return $this->call('Ping');
    }

    /**
     * @return mixed
     */
    public function getVersionList()
    {

        return $this->call('GetVersionList', $this->getDefaultParameters());
    }

    /**
     * @param array $params
     * 
     * @return mixed
     */
    public function getVersionStep($params = [])
    {

        $params =  array_merge($this->getDefaultParameters(), $params);
        
        return $this->call('VersionStep', $params);
    }

    /**
     * @param array $params
     *
     * @return mixed
     */
    public function getVersionStepWithCat($params = [])
    {

        $params =  array_merge($this->getDefaultParameters(), $params);

        return $this->call('VersionStepWithCat', $params);
    }

    /**
     * @param array $params
     *
     * @return mixed
     */
    public function getSpecifications($params = [])
    {

        $params =  array_merge($this->getDefaultParameters(), $params);

        return $this->call('GetSpecifications', $params);
    }

    /**
     *
     * @param array $params
     * 
     * @return mixed
     */
    public function getResources($params = [])
    {

        $params =  array_merge($this->getDefaultParameters(), $params);

        return $this->call('GetResources', $params);
    }

    /**
     * @param array $params
     * @return boolean
     */
    public function getStateOfEnableStepUpselling($params = [])
    {
        $ressources = $this->getResources($params);
        $response = false;
        foreach ($ressources->GetResourcesResult->Parameters->KeyValueOfstringstring as $keyString => $valueString) {
            if ($valueString->Key == self::ENABLE_VERSION_STEP_UPSELLING) {
                $response = $valueString->Value;
                break;
            }
        }

        return $response;
    }

    /**
     * @param array $params
     * 
     * @return array
     */
    public function getExteriorViewsByModel($params = [])
    {
        $ressources = $this->getResources($params);
        $response = [];
        $views = $ressources->GetResourcesResult->Views->KeyValueOfstringArrayOfstringty7Ep6D1;
        $order = 0;
        foreach ($views as $keyString => $valueString) {
            if ($valueString->Key == self::EXTERIOR_VIEW) {
                 
                foreach ($valueString->Value->string as $code) {
                    $response[$code] = ['code' => $code, 'initial' => false,'ANGLE_ORDER' => $order++];
                }
            }
            if ($valueString->Key == self::INITIAL_VIEW) {
               $response[$valueString->Value->string[0]]['initial'] = true;
               break;
            }
        }

        return $response;
    }


    /**
     *
     * @param array $params
     * 
     * @return array
     */
    public function getSegmentation($params = [])
    {
        $versions = $this->getVersionStepWithCat($params);
        $datas = [];
        foreach ($versions->VersionStepWithCatResult->Categories->ResetableCategoryOfVersionStepItemWithCat as $keySeg => $valueSeg) {
            $datas[] = [
                'label' => $valueSeg->CategoryType->Label,
                'code' => $valueSeg->CategoryType->Code,
                'hasUpselling' => $valueSeg->HasUpSelling,
            ];
        }

        return $datas;
    }

    /**
     *
     * @param array $params
     *
     * @return array
     */
    public function getUpsellingAndReference($params = [])
    {
        $versions = $this->getVersionStepWithCat($params);
        $datas = [];
        foreach ($versions->VersionStepWithCatResult->Categories->ResetableCategoryOfVersionStepItemWithCat as $keySeg => $valueSeg) {
            $items = [];
            foreach ($valueSeg->Items->VersionStepItemWithCat as $keyItem => $valueItem) {
                $items[] = [
                    'code' => $valueItem->Code,
                    'label' => $valueItem->Label,
                    'isUpselling' => $valueItem->IsUpselling,
                    'keyFeaturesVersionReference' => $valueItem->KeyFeaturesVersionReference
                ];
            }
            $datas[] = [
                'label' => $valueSeg->CategoryType->Label,
                'codeCategorie' => $valueSeg->CategoryType->Code,
                'hasUpselling' => $valueSeg->HasUpSelling,
                'items' => $items
            ];
        }

        return $datas;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'WS_GEST_GAMME';
    }
}
