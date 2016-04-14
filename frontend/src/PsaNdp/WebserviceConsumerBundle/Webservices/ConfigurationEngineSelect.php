<?php

namespace PsaNdp\WebserviceConsumerBundle\Webservices;

use Itkg\Consumer\Service\Service;
use PsaNdp\CacheBundle\Services\CacheService;
use PsaNdp\MappingBundle\Repository\PsaSiteWebserviceRepository;
use PsaNdp\MappingBundle\Repository\PsaWebserviceRepository;

/**
 * Class ConfigurationEngineSelect
 */
class ConfigurationEngineSelect extends ConfigurationEngine
{
    /**
     * @var array
     */
    protected $allowedArguments = array(
        'context' => array(
            'Client',
            'Brand',
            'Country',
            'Date',
            'TariffCode',
            'LanguageID',
            'Network',
            'TaxIncluded',
            'ProfessionalUse',
            'TariffZone',
            'LocalCurrency',
            'ShowAllVersions',
            'ResponseType',
        ),
        'criteria' => array(
            'VehicleUse',
            'Model',
            'BodyStyle',
            'GrBodyStyle',
            'Grade',
            'GrCommercialName',
            'TransmissionType',
            'GrTransmissionType',
            'Engine',
            'GrEngine',
            'Energy',
            'EcoLabel',
        )

    );

    /**
     *
     * @return mixed
     */
    public function select()
    {
        $parameters = array(
            'Select' => array(
                'ContextRequest' => $this->context,
                'SelectCriteria' => $this->criteria
            )
        );

        return $this->call('Select', $parameters);
    }

    public function getVersions($siteCode,$languageCode)
    {
        $this->resetCriteria();
        $this->addCriteria('VehicleUse','VP');
        $this->addContext('ResponseType','Versions');
        $this->addContext('LanguageID', $languageCode);
        $this->addContext('Country', $siteCode);
        $results = $this->select();

        return $results->SelectResponse->Versions->Version;
    }

    /**
     *
     * @param string $model
     * 
     * @return type
     */
    public function getVersionsCriterionByModel($model = "")
    {
        $versions = [];
        $this->resetCriteria();
        $this->addCriteria("Model", $model);
        $result = $this->select();
        
        foreach ($result->SelectResponse->Versions->Version as $version) {
            if(isset($version->VersionsCriterion)) {
                $versions[$version->GrCommercialName->id] = ['id' => $version->GrCommercialName->id, 'label' => $version->GrCommercialName->label];
            }
        }

        return $versions;
    }

    /**
     * @return array
     */
    public function getVersionsCriterion()
    {
        $versions = [];
        $this->resetCriteria();
        $result = $this->select();
        foreach ($result->SelectResponse->Criteria->VersionsCriterion->VersionCriterion as $version) {
                $versions[$version->id] = $version->id." - ".$version->label;
        }

        return $versions;
    }

    /**
     * @param $lcdv6
     *
     * @return array
     */
    public function getVersionsByLCDV6($lcdv6)
    {
        $versions = [];
        $this->resetCriteria();
        $result = $this->select();
        foreach ($result->SelectResponse->Versions->Version as $version) {
            $modelLCDV6 = substr($version->IdVersion->id, 0, 6);

            if ($modelLCDV6 === $lcdv6) {
                $versions[] = $version;
            }
        }

        return $versions;
    }

    /**
     * @param $lcdv4
     *
     * @return array
     */
    public function getVersionsByLCDV4($lcdv4)
    {
        $versions = [];
        $this->resetCriteria();
        $result = $this->select();
        foreach ($result->SelectResponse->Versions->Version as $version) {
            $modelLCDV4 = substr($version->IdVersion->id, 0, 4);

            if ($modelLCDV4 === $lcdv4) {
                $versions[] = $version;
            }
        }

        return $versions;
    }

    /**
     * @return array
     */
    public function getModelByLCDV6($lcdv6)
    {
        $versions = [];
        $this->resetCriteria();
        $result = $this->select();
        foreach ($result->SelectResponse->Versions->Version as $version) {
            $modelLCDV6 = substr($version->IdVersion->id,0,6);
            if ($modelLCDV6 == $lcdv6) {
                $versions[$version->GrCommercialName->id] = array(
                    'FINISHING_CODE' => $version->GrCommercialName->id,
                    'FINISHING_LABEL' => $version->GrCommercialName->label,
                    'BASE_PRICE' => $version->Price->basePrice,
                    'LCDV16' => $version->IdVersion->id,
                    'VEHICULE_USE' => $version->VehicleUse->id
                );
            }
            
        }
        
        return $versions;
    }

    /**
     * @return null|stdClass
     */
    public function getVersionByLCDV16($lcdv16)
    {
        $this->resetCriteria();
        $result = $this->select();
        foreach ($result->SelectResponse->Versions->Version as $version) {
            if ($lcdv16 == $version->IdVersion->id) {

                return $version;
            }

        }

        return null;
    }


    /**
     * @param string $lcdv4
     *
     * @return array
     */
    public function getModelByLCDV4($lcdv4)
    {
        $versions = [];
        $this->resetCriteria();
        $result = $this->select();
        foreach ($result->SelectResponse->Versions->Version as $version) {
            $modelLCDV4 = substr($version->IdVersion->id,0,4);
            if ($modelLCDV4 == $lcdv4) {
                $versions[$version->GrCommercialName->id] = array(
                    'FINISHING_CODE' => $version->GrCommercialName->id,
                    'FINISHING_LABEL' => $version->GrCommercialName->label,
                    'BASE_PRICE' => $version->Price->basePrice,
                    'LCDV16' => $version->IdVersion->id,
                    'VEHICULE_USE' => $version->VehicleUse->id
                );
            }

        }

        return $versions;
    }

    /**
     * @return array
     */
    public function getCustomerType()
    {
        $versions = [];
        $this->resetCriteria();
        $result = $this->select();
        foreach ($result->SelectResponse->Versions->Version as $version) {
            $versions[$version->CustomerType->id] = $version->CustomerType->label;
        }

        return $versions;
    }

    public function getFinitionCode()
    {
        $versions = [];
        $this->resetCriteria();
        $this->addCriteria('VehicleUse', 'VP');
        $result = $this->select();
        
        foreach ($result->SelectResponse->Versions->Version as $version) {
            $versions[] = array(
                'CODE' => $version->GrCommercialName->id,
                'FINITION' => $version->GrCommercialName->label,
                'CUSTOMER_TYPE' => $version->CustomerType->id,
                'VERSIONS_CRITERION' => $version->VersionsCriterion->VersionCriterion
            );            
        }

        return $versions;
    }

    /**
     * @param string $model
     * @param string $silhouetteGroup
     *
     * @return array
     */
    public function getVersionsByModeleRegroupementSilhouette($model, $silhouetteGroup, $fullResponse = false)
    {

        $versions = [];
        $this->resetCriteria();
        $this->addCriteria('Model',$model)
            ->addCriteria('GrBodyStyle',$silhouetteGroup);
        $result = $this->select();

        if($fullResponse){
            $versions = $result->SelectResponse->Versions->Version;
        }else{

            foreach($result->SelectResponse->Versions->Version as $version)
            {
                $versions[] = array(
                    'lcdv16'=> $version->IdVersion->id,
                    'name'=>  $version->IdVersion->label,
                    'model'=>  $version->Model->label,
                    'grbodyStyle'=>  $version->GrbodyStyle->label,
                );
            }
        }

        return $versions;
    }

    /**
     * @param string $model
     * @param string $silhouette
     *
     * @return array
     */
    public function getVersionsBySilhouette($model, $silhouette)
    {

        $versions = [];
        $this->resetCriteria();
        $this->addCriteria('Model', $model)->addCriteria('BodyStyle',$silhouette);
        $result = $this->select();

        foreach($result->SelectResponse->Versions->Version as $version)
        {
            $versions[] = array(
                'lcdv16'=> $version->IdVersion->id,
                'name'=> $version->IdVersion->label,
            );
        }

        return $versions;
    }

    /**
     * @param string $lcdv6
     * @param string $silhouetteGroup
     *
     * @return array
     */
    public function getVersionsByRegroupementModeleRegroupementSilhouette($lcdv6, $silhouetteGroup)
    {
        $model = substr($lcdv6,0,4);
        $temp= $this->getVersionsByModeleRegroupementSilhouette($model,$silhouetteGroup);

        $versions = array_filter($temp,function($v) use($lcdv6){
                return (substr($v['lcdv16'],0,6) === $lcdv6);
        });


        return $versions;
    }

    /**
     * @param string $grCommercialName
     *
     * @return mixed
     */
    public function getVersionByGrCommercialName($grCommercialName)
    {
        $this->resetCriteria();
        $this->addCriteria('GrCommercialName', $grCommercialName);
        $result = $this->select();

        return $result->SelectResponse->Versions->Version;
    }


    /**
     * @param string $lcdv4
     * @return array
     */
    public function getGrCommercialName($lcdv4)
    {
        $this->resetCriteria();
        $this->addCriteria('Model', $lcdv4);
        $result = $this->select();

        foreach($result->SelectResponse->Versions->Version as $version)
        {
            $versions[$version->IdVersion->id] = array(
                'GrCommercialName_id'=> $version->GrCommercialName->id,
                'GrCommercialName'=> $version->GrCommercialName->label,
            );
        }

        return $versions;
    }


      /**
     * @param string $lcdv4
     * @param string $siteCode ex: 'FR'
     * @param string $languageCode ex: 'fr'
     *
     * @return array $result[lcdv6][grBodyStyle] = $Version (cheapest)
     */
    public function getCheapestVersionsForLcdv6AndGrBodyStyleCodesByLcdv4($lcdv4, $siteCode, $languageCode)
    {
        
        return $this->getCheapestVersion($lcdv4, $siteCode, $languageCode, true);
    }

    /**
     *
     * @throws \Exception
     */
    public function getCheapestVersion()
    {
        throw new \Exception('Deprecated : the cheapest version should be fetch from Range Manager ');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'WS_MOTEUR_CONFIG_SELECT';
    }
}
