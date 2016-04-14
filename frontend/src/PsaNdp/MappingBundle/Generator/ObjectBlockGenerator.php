<?php

namespace PsaNdp\MappingBundle\Generator;

use Symfony\Component\Yaml\Yaml;

/**
 * Description of Object Block Generator
 *
 * @author sthibault
 */
class ObjectBlockGenerator extends Generator
{

    const YAML_INDENTATION_LEVEL = 3;

    /**
     *
     * @return string
     */
    private function getBlockName()
    {

        return $this->getCamelCodeName() ;
    }

    /**
     * generate the transformer base code
     */
    private function generateObjectBlock()
    {

        $data                    = $this->getDefaultData();
        $data['blockName']       = $this->getBlockName();
        $path                    = $this->getBundlePath() . '/Object/Block/';
        $filename                = $path . $this->getBlockName() . '.php';
        $this->renderFile('block/block.php.twig', $filename, $data);
    }

    private function generateObjectBlockConfig()
    {
        $file   = $this->getBundlePath() . '/Resources/config/object.yml';
        $config = [];
        if (file_exists($file)) {
            $config = Yaml::parse(file_get_contents($file));
        }
        $className     = 'PsaNdp\\MappingBundle\\Object\\Block\\' . $this->getBlockName();
        $parameterName = 'psa_ndp_mapping.object.block.' . $this->getSnakeCodeName() . '.class';
        $serviceName   = 'psa_ndp_mapping.object.block.' . $this->getSnakeCodeName() ;

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
        $this->generateObjectBlock();
        $this->generateObjectBlockConfig();
    }
}
