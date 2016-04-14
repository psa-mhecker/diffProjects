<?php

namespace PsaNdp\MappingBundle\Generator;

use Symfony\Component\Yaml\Yaml;

/**
 * Description of StrategyGenerator
 *
 * @author sthibault
 */
class StrategyGenerator extends Generator
{

    const YAML_INDENTATION_LEVEL = 4;

    /**
     *
     * @return string
     */
    private function getStrategyName()
    {

        return $this->getCamelCodeName() . 'Strategy';
    }

    private function generateStrategy()
    {
        $data                 = $this->getDefaultData();
        $data['strategyName'] = $this->getStrategyName();
        $path                 = $this->getBundlePath() . '/DisplayBlock/Strategies/';
        $filename             = $path . $this->getStrategyName() . '.php';
        $this->renderFile('strategy/strategy.php.twig', $filename, $data);
    }

    private function generateDisplayConfig()
    {
        $file   = $this->getBundlePath() . '/Resources/config/services_display.yml';
        $config = [];

        if (file_exists($file)) {
            $config = Yaml::parse(file_get_contents($file));
        }

        /* @todo Entity name should be prefixed by bundle name */
        $className     = 'PsaNdp\\MappingBundle\\DisplayBlock\\Strategies\\' . $this->getStrategyName();
        $parameterName = 'psa_ndp_mapping.display.' . $this->getSnakeCodeName() . '.class';
        $serviceName   = 'psa_ndp_mapping.display.' . $this->getSnakeCodeName();

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

        $definition              = [];
        $definition['class']     = '%' . $parameterName . '%';
        $definition['arguments'] = array(
            '@psa_ndp_mapping.' . $this->getSnakeCodeName() . '_data_source',
            '@psa_ndp_mapping.' . $this->getSnakeCodeName() . '_data_transformer',
            '@request_stack'
        );
        $definition['tags']      = [];
        $definition['tags'][]    = array('name' => 'open_orchestra_display.display_block.strategy');

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
        $this->generateStrategy();
        $this->generateDisplayConfig();
    }
}
