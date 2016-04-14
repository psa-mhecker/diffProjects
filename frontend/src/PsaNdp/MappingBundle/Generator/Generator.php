<?php

namespace PsaNdp\MappingBundle\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator as Base;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * Description of Generator
 *
 * @author sthibault
 */
class Generator extends Base
{

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $name;

    /**
     *
     * @var OutputInterface
     */
    protected $output;

    /**
     *
     * @var Filesystem 
     */
    protected $filesystem;

    /**
     *
     * @var boolean
     */
    protected $overwrite;

    /**
     *
     * @var BundleInterface
     */
    protected $bundle;

    /**
     * Constructor.
     *
     * @param Filesystem      $filesystem
     * @param OutputInterface $output
     */
    public function __construct(Filesystem $filesystem, OutputInterface $output)
    {
        $this->filesystem = $filesystem;
        $this->output     = $output;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }



    /**
     *
     * @return boolean
     */
    public function isOverwrite()
    {
        return $this->overwrite;
    }

    /**
     *
     * @param boolean $overwrite
     * 
     * @return Generator
     */
    public function setOverwrite($overwrite)
    {
        $this->overwrite = $overwrite;
        
        return $this;
    }

    /**
     *
     * @return BundleInterface
     */
    public function getBundle()
    {
        return $this->bundle;
    }

    /**
     * 
     * @return string
     */
    public function getBundlePath()
    {
        return $this->getBundle()->getPath();
    }

    /**
     *
     * @param BundleInterface $bundle
     *
     * @return \PsaNdp\MappingBundle\Generator\Generator
     */
    public function setBundle(BundleInterface $bundle)
    {
        $this->bundle = $bundle;

        return $this;
    }

    protected function renderFile($template, $target, $parameters)
    {

        if (!$this->filesystem->exists($target) || $this->isOverwrite()) {
            $this->output->writeln('<info>Generating file  : ' . $target . '</info>');
            parent::renderFile($template, $target, $parameters);
        }
    }

    protected function getCamelName()
    {

        return Container::camelize($this->name);
    }

    protected function getLowerCamelName()
    {

        return lcfirst(Container::camelize($this->name));
    }

    protected function getSnakeName()
    {

        return strtr(Container::underscore($this->name), array(' ' => '_'));
    }

    protected function getCamelCodeName()
    {

        return Container::camelize(trim($this->code) . ' ' . $this->name);
    }

    protected function getLowerCamelCodeName()
    {

        return lcfirst($this->getCamelCodeName());
    }

    protected function getSnakeCodeName()
    {

        return strtr(Container::underscore(trim($this->code) . ' ' . $this->name), array(' ' => '_'));
    }

    protected function getSourceName()
    {

        return $this->getCamelCodeName() . 'DataSource';
    }

    protected function getControllerName()
    {

        return $this->getCamelCodeName() . 'Controller';
    }

    protected function getSliceName()
    {
        return 'slice'.strtoupper($this->code);
    }

    protected function getDefaultData()
    {
        $data                       = [];
        $data['name']               = $this->name;
        $data['code']               = $this->code;
        $data['camelName']          = $this->getCamelName();
        $data['camelCodeName']      = $this->getCamelCodeName();
        $data['lowerCamelName']     = $this->getLowerCamelName();
        $data['lowerCamelCodeName'] = $this->getLowerCamelCodeName();
        $data['sliceName']          = $this->getSliceName();

        return $data;
    }
}
