<?php

namespace PsaNdp\MappingBundle\Datalayer;

use PsaNdp\MappingBundle\Repository\PsaPageDatalayerRepository;

/**
 * Class Datalayer.
 */
class Datalayer
{
    /**
     * @var PsaPageDatalayerRepository
     */
    private $datalayerRepository;

    /**
     * @var array
     */
    private $defaultValues = [];

    /**
     * @var array
     */
    private $persistedValues = [];

    /**
     * @var array
     */
    private $availableValues;
    /**
     * @var array
     */
    private $variables = [];

    /**
     * @var Context
     */
    private $context;

    /**
     * @param PsaPageDatalayerRepository $datalayerRepository
     * @param array                      $defaultValues
     */
    public function __construct(PsaPageDatalayerRepository $datalayerRepository, $defaultValues)
    {
        $this->defaultValues = $defaultValues;
        $this->datalayerRepository = $datalayerRepository;
    }

    /**
     * Get pageId.
     *
     * @return mixed
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * @param mixed $pageId
     *
     * @return Datalayer
     */
    public function setPageId($pageId)
    {
        $this->pageId = $pageId;

        return $this;
    }

    /**
     * sets default variables values
     * based on the current page template.
     */
    public function init()
    {
        $this->initTemplateValues();
        $this->addVariable('pageName', $this->getPageName());

        if ($this->context->getNode()->getVehicleCode()) {
            $this->addVariable('vehicleModelBodystyle', $this->context->getVehicleCode());
            $this->addVariable('vehicleModelBodystyleLabel', $this->context->getVehicleBodyStyleLabel());
        }

        return $this->variables;
    }

    
    public function initTemplateValues()
    {
        $this->initPersistedValues();

        foreach ($this->getDefaultKeys() as $key) {
            $this->addVariable($key, $this->getCurrentPageKeyValue($key));
        }

        $this->addVariable('language', $this->context->getLanguageCode());
        $this->addVariable('country', strtolower($this->context->getCountryCode()));
        $this->addVariable('virtualPageURL', $this->getVirtualPageURL());

        $this->variables = array_merge($this->variables, $this->persistedValues);
    }

    /**
     * Bootstraps persisted values.
     */
    public function initPersistedValues()
    {
        $pageDatalayer = $this->datalayerRepository->findOneBy(
            array(
                'pageId' => $this->context->getNode()->getId(),
            )
        );

        if (null !== $pageDatalayer) {
            $this->persistedValues = $pageDatalayer->getContent();
        }
    }

    /**
     * @return array
     */
    private function getDefaultKeys()
    {
        return array_keys($this->defaultValues['default']);
    }

    /**
     * @param $key
     * @param $value
     */
    private function addVariable($key, $value)
    {
        $this->variables[$key] = $value;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function getCurrentPageKeyValue($key)
    {
        $value = $this->getTemplateKeyValue($key);

        if (null === $value) {
            $value = $this->getDefaultKeyValue($key);
        }

        return $value;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function getTemplateKeyValue($key)
    {
        $templateCode = $this->context->getTemplateCode();

        return $this->getKeyValue($key, $templateCode);
    }

    /**
     * @param string $key
     * @param $templateCode
     *
     * @return string
     */
    private function getKeyValue($key, $templateCode)
    {
        $value = null;

        if (!empty($this->defaultValues[$templateCode][$key])) {
            $value = $this->defaultValues[$templateCode][$key];
        }

        return $value;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function getDefaultKeyValue($key)
    {
        return $this->getKeyValue($key, 'default');
    }

    /**
     * @return string
     */
    private function getPageName()
    {
        $pageNamePattern = 'siteTypeLevel1/siteTypeLevel2/siteOwner/siteTarget/siteFamily';

        $pageNameVariableNames = explode('/', $pageNamePattern);
        $pageNameVariables = array();
        foreach ($pageNameVariableNames as $variableName) {
            if (!empty($this->variables[$variableName])) {
                $pageNameVariables[] = $this->variables[$variableName];
            }
        }
        $pageNameVariables[] = $this->context->getTemplateCodeLabel();
        $pageNameVariables[] = $this->context->getDevice();

        if ($this->context->getNode()->getVehicleCode()) {
            $pageNameVariables[] = $this->context->getVehicleCode().'::'.$this->context->getVehicleCodeBodyStyle();
        }
        $pageNameVariables[] = $this->context->getMetaTitle();

        return implode('/', $pageNameVariables);
    }

    /**
     * @return string
     */
    private function getVirtualPageURL()
    {
        $templateVirtualPageUrl = $this->getTemplateKeyValue('virtualPageURL');

        $hasSlash = strpos($templateVirtualPageUrl, '/');
        $virtualPageUrl = $templateVirtualPageUrl;

        if (!$hasSlash && !empty($templateVirtualPageUrl)) {
            $virtualPageUrl = sprintf('%s/%s', $this->getDefaultKeyValue('siteTypeLevel1'), $this->getTemplateKeyValue('virtualPageURL'));
        }

        $vehicleCode = $this->context->getVehicleCode();
        if (!empty($vehicleCode)) {
            $virtualPageUrl  = sprintf('%s/%s', $virtualPageUrl, $vehicleCode);
        }

        return $virtualPageUrl;
    }

    /**
     * @param Context $context
     */
    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    /**
     * Get persistedValues.
     *
     * @return array
     */
    public function getPersistedValues()
    {
        return $this->persistedValues;
    }

    /**
     * @param string $name
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    public function getVariable($name)
    {
        if (!array_key_exists($name, $this->getVariables())) {
            throw new \InvalidArgumentException(sprintf('the variable %s does not exists', $name));
        }

        return $this->variables[$name];
    }

    /**
     * Get variables.
     *
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * fetches available values from configuration.
     */
    public function getAvailableValues()
    {
        if (empty($this->availableValues)) {
            foreach ($this->defaultValues as $availableSets) {
                foreach ($availableSets as $key => $value) {
                    if (!in_array($value, $this->availableValues[$key]) && !empty($value)) {
                        $this->availableValues[$key][$value] = $value;
                    }
                }
            }
        }

        return $this->availableValues;
    }
}
