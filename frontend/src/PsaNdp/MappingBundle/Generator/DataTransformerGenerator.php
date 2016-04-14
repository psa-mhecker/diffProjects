<?php

namespace PsaNdp\MappingBundle\Generator;

use Symfony\Component\Yaml\Yaml;

/**
 * Description of DataTransformerGenerator
 *
 * @author sthibault
 */
class DataTransformerGenerator extends Generator
{
    const YAML_INDENTATION_LEVEL = 4;

    /**
     *
     * @return string
     */
    private function getTransformerName()
    {

        return $this->getCamelCodeName() . 'DataTransformer';
    }

    /**
     * generate the transformer base code
     */
    private function generateDataTransformer()
    {

        $data                    = $this->getDefaultData();
        $data['transformerName'] = $this->getTransformerName();
        $path                    = $this->getBundlePath() . '/Transformers/';
        $filename                = $path . $this->getTransformerName() . '.php';
        $this->renderFile('datatransformer/datatransformer.php.twig', $filename, $data);
    }

    private function generateDataTransformerConfig()
    {
        $file   = $this->getBundlePath() . '/Resources/config/services_data_transformer.yml';
        $config = [];
        if (file_exists($file)) {
            $config = Yaml::parse(file_get_contents($file));
        }
        /* @todo classname should be prefixed by bundle name */
        $className     = 'PsaNdp\\MappingBundle\\Transformers\\' . $this->getTransformerName();
        $parameterName = 'psa_ndp_mapping.' . $this->getSnakeCodeName() . '_data_transformer.class';
        $serviceName   = 'psa_ndp_mapping.' . $this->getSnakeCodeName() . '_data_transformer';

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
        $definition['arguments'] =  array('@psa_ndp_mapping.object.block.'.$this->getSnakeCodeName());

        return $definition;
    }

    /**
     *
     * @param string $code
     * @param string $name
     */
    public function generate($code, $name)
    {

        $this->code = $code;
        $this->name = $name;
        $this->generateDataTransformer();
        $this->generateDataTransformerConfig();
    }
}
