<?php

namespace PsaNdp\WebserviceConsumerBundle\Webservices;

use \InvalidArgumentException;


class ConfigurationEngineCompareGrade extends ConfigurationEngine
{

    protected $response;

    /**
     * @var array
     */
    protected $allowedArguments = array(
        'context' => array(
            'Client',
            'Brand',
            'Country',
            'Date',
            'LanguageID',
            'Network',
            'TariffZone',
            'LocalCurrency',
            'ShowAllVersions',
        ),
        'criteria' => array(
            'VehicleUse',
            'Model',
            'BodyStyle',
            'GrBodyStyle',
            'GrCommercialName',
        )

    );


    /**
     * @param $vehicleUse
     * @param $model
     * @param null $bodyStyle
     * @param null $grBodyStyle
     *
     * @throws InvalidArgumentException
     * @return array
     */
    public function compareGrades($vehicleUse, $model, $bodyStyle = null, $grBodyStyle = null)
    {

        if(is_null($bodyStyle) && is_null($grBodyStyle)) {
            throw new InvalidArgumentException('You need to set  $bodyStyle or $grBodyStyle arguments');
        }
        if(!is_null($bodyStyle))
        {
            $this->addCriteria('BodyStyle', $bodyStyle);
        }
        if(!is_null($grBodyStyle))
        {
            $this->addCriteria('GrBodyStyle', $grBodyStyle);
        }
        $this->addCriteria('VehicleUse',$vehicleUse)
            ->addCriteria('Model', $model)
        ;

        $parameters = array(
            'CompareGrades' => array(
                'ContextRequest' => $this->context,
                'CompareGradesCriteria' => $this->criteria
            )
        );

        $this->response = $this->call('CompareGrades', $parameters);

        return $this->response;
    }

    /**
     * @param $finishesId
     *
     * @return mixed
     */
    public function getEquipments($finishesId)
    {
        $grades = $this->response->CompareGradesResponse->Grades;

        foreach ($grades as $grade) {
            if ($finishesId === $grade->id) {
                return $grade->Categories;
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'WS_MOTEUR_CONFIG_COMPARE_GRADE';
    }
}
