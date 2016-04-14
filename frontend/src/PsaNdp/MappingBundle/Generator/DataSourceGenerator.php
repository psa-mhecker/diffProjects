<?php

namespace PsaNdp\MappingBundle\Generator;

use Symfony\Component\Yaml\Yaml;

/**
 * Description of DataSourceGenerator
 *
 * @author sthibault
 */
class DataSourceGenerator extends Generator
{
    const YAML_INDENTATION_LEVEL = 3;

    /**
     *
     * @var array
     */
    protected $parameters;

    /**
     *
     * @var array
     */
    protected $uses = array();

    private function generateDataSource()
    {
        $data                  = $this->getDefaultData();
        $data['parameters']    = $this->parameters;
        $data['uses']          = $this->uses;
        $data['sourceName']    = $this->getSourceName();
        $data['argTypeFormat'] = $this->getArgTypeFormat();
        $path                  = $this->getBundlePath() . '/Sources/';
        $filename              = $path . $this->getSourceName() . '.php';
        $this->renderFile('datasource/datasource.php.twig', $filename, $data);
    }

    private function getArgTypeFormat()
    {
        $length = 0;
        foreach ($this->parameters as $param) {
            $length = max($length, strlen($param['type']));
        }

        return '%-' . $length . 's';
    }

    /**
     * 
     */
    private function generateDataSourceConfig()
    {
        $file   = $this->getBundlePath() . '/Resources/config/services_data_source.yml';
        $config = [];
        if (file_exists($file)) {
            $config = Yaml::parse(file_get_contents($file));
        }
        /* @todo  entity name should be prefixed by the bundle name !! */
        $className     = 'PsaNdp\\MappingBundle\\Sources\\' . $this->getSourceName();
        $parameterName = 'psa_ndp_mapping.' . $this->getSnakeCodeName() . '_data_source.class';
        $serviceName   = 'psa_ndp_mapping.' . $this->getSnakeCodeName() . '_data_source';

        $config['parameters'][$parameterName] = $className;
        $config['services'][$serviceName]     = $this->getServiceDefinition($parameterName);
        file_put_contents($file, Yaml::dump($config, self::YAML_INDENTATION_LEVEL));
    }

    /**
     *
     * @param string $parameterName
     */
    private function getServiceDefinition($parameterName)
    {

        $definition          = [];
        $definition['class'] = '%' . $parameterName . '%';
        foreach ($this->parameters as $parameter) {
            $definition['arguments'][] = $parameter['configName'];
        }

        return $definition;
    }

    /**
     *
     * @param string $code
     * @param string $name
     * @param array  $parameters
     */
    public function generate($code, $name, array $parameters)
    {

        $this->code       = $code;
        $this->name       = $name;
        $this->parameters = $parameters;
        foreach ($this->parameters as $param) {
            if (($param['use'] != '' ) && !in_array($param['use'], $this->uses)) {
                $this->uses[] = $param['use'];
            }
        }
        $this->generateDataSource();
        $this->generateDataSourceConfig();
    }
}
