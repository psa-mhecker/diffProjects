<?php

namespace PsaNdp\MappingBundle\Generator;

/**
 * Description of SliceGenerator
 *
 * @author sthibault
 */
class SliceGenerator extends Generator
{

    protected $dirs;

    /**
     * Sets an array of directories to look for templates.
     *
     * The directories must be sorted from the most specific to the most
     * directory.
     *
     * @param array $skeletonDirs An array of skeleton dirs
     */
    public function setSkeletonDirs($skeletonDirs)
    {
        $this->dirs = $skeletonDirs;
        parent::setSkeletonDirs($skeletonDirs);
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
        $this->generateDataSource();
        $this->generateObjectBlock();
        $this->generateDataTransformer();
        $this->generateStrategy();
    }

    protected function generateDataSource()
    {

        $generator = $this->createDataSourceGenerator();
        $generator->setSkeletonDirs($this->dirs);
        $generator->setOverwrite($this->overwrite);
        $generator->setBundle($this->getBundle());
        $generator->generate($this->code, $this->name, $this->parameters);
    }


    protected function generateObjectBlock()
    {
        $generator = $this->createObjectBlockGenerator();
        $generator->setSkeletonDirs($this->dirs);
        $generator->setOverwrite($this->overwrite);
        $generator->setBundle($this->getBundle());
        $generator->generate($this->code, $this->name);
    }

    protected function generateDataTransformer()
    {

        $generator = $this->createDataTransformerGenerator();
        $generator->setSkeletonDirs($this->dirs);
        $generator->setOverwrite($this->overwrite);
        $generator->setBundle($this->getBundle());
        $generator->generate($this->code, $this->name);
    }

    protected function generateStrategy()
    {

        $generator = $this->createStrategyGenerator();
        $generator->setSkeletonDirs($this->dirs);
        $generator->setOverwrite($this->overwrite);
        $generator->setBundle($this->getBundle());
        $generator->generate($this->code, $this->name);
    }

    /**
     *
     * @return ObjectBlockGenerator
     */
    protected function createObjectBlockGenerator()
    {

        return new ObjectBlockGenerator($this->filesystem, $this->output);
    }

    /**
     *
     * @return DataTransformerGenerator
     */
    protected function createDataSourceGenerator()
    {

        return new DataSourceGenerator($this->filesystem, $this->output);
    }

    /**
     *
     * @return DataTransformerGenerator
     */
    protected function createDataTransformerGenerator()
    {

        return new DataTransformerGenerator($this->filesystem, $this->output);
    }

    /**
     *
     * @return DataTransformerGenerator
     */
    protected function createStrategyGenerator()
    {

        return new StrategyGenerator($this->filesystem, $this->output);
    }
}
