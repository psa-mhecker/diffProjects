<?php

namespace PsaNdp\WebserviceConsumerBundle\Webservices;

class ConfigurationEngineEngineCriteria extends ConfigurationEngine
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
            'LanguageID',
        ),
        'criteria' => array(
            'Version'
        )

    );


    /**
     * @param string $version
     *
     * @return array
     */
    public function engineCriteria($version)
    {
        $this->addCriteria('Version',$version);
        $parameters = array(
            'EngineCriteria' => array(
                'ContextRequest' => $this->context,
                'EngineCriteriaParameter' => $this->criteria
            )
        );
        $result =  $this->call('EngineCriteria', $parameters);

        return $result->EngineCriteriaResponse->Version;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'WS_MOTEUR_CONFIG_ENGINE_CRITERIA';
    }
}
