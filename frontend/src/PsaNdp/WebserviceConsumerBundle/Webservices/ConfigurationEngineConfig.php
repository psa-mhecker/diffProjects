<?php
/**
 * User: Ayoub Hidri <ayoub.hidri@businessdecision.com>
 * Date: 21/07/15
 * Time: 13:59
 */

namespace PsaNdp\WebserviceConsumerBundle\Webservices;

class ConfigurationEngineConfig extends ConfigurationEngine
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
            'TaxIncluded',
            'ProfessionalUse',
            'TariffZone',
            'LocalCurrency',
            'ShowUnavailableLooks',
            'ShowUnavailableOptionalFeatures',
        ),
        'criteria' => array(
            'Version',
            'Feature',
            'GrEngine',
            'GrCommercialName',
        )

    );

    protected $response;


    public function config()
    {
        $parameters = array(
            'Config' => array(
                'ContextRequest' => $this->context,
                'ConfigCriteria' => $this->criteria
            )
        );

        $this->response = $this->call('Config', $parameters);

        return $this->response;
    }

    /**
     * @param string $version
     *
     * @return mixed
     */
    public function configByVersion($version)
    {
        $this->addCriteria('Version', $version);
        $response = $this->config();

        return $response->ConfigResponse->Version;
    }

    /**
     * @param string $version
     *
     * @return mixed
     */
    public function ColorByVersion($version)
    {
        $colors = [];
        $config  = $this->configByVersion($version);

        foreach($config->LookFeatures->ExteriorFeatures as $color)
        {
            $colors[] = $color;
        }

        return $colors;

    }

    /**
     * @param string $version
     *
     * @return mixed
     */
    public function getVersion()
    {
        $version = null;
        $version = $this->response->ConfigResponse->Version;

        return $version;
    }

  /**
     * @param string $version
     *
     * @return mixed
     */
    public function getFeaturesByVersion($version)
    {
        $features = [];
       $config  = $this->configByVersion($version);
        if (isset($config->StandardFeatures)) {
            foreach ($config->StandardFeatures->Category as $category) {
                $category->OptionalFeatures = [];
                $features[$category->id] = $category;
            }
        }
        if (isset($config->OptionalFeatures)) {
            foreach ($config->OptionalFeatures->Category as $category) {
                if (!isset($features[$category->id])) {
                    $category->StandardFeatures = [];
                    $features[$category->id] = $category;
                }
                $features[$category->id]->OptionalFeatures = $category->OptionalFeatures;
            }
        }

        return $features;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'WS_MOTEUR_CONFIG_CONFIG';
    }
}