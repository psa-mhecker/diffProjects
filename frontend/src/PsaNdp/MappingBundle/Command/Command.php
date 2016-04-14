<?php

namespace PsaNdp\MappingBundle\Command;

use Sensio\Bundle\GeneratorBundle\Command\GeneratorCommand;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PsaNdp\MappingBundle\Helper\GeneratorQuestionsHelper;
use PsaNdp\MappingBundle\Generator\Generator;

/**
 * Description of Command
 *
 * @author sthibault
 */
abstract class Command extends GeneratorCommand
{

    /**
     *
     * @var string
     */
    protected $sliceCode;
    /**
     *
     * @var string
     */
    protected $sliceName;
    /**
     *
     * @var OutputInterface
     */
    protected $output;
    /**
     *
     * @var InputInterface
     */
    protected $input;
    /**
     *
     * @var array
     */
    protected $uses = array();


    /**
     * ask user for slice code
     */
    public function askCode()
    {

        $questionHelper = $this->getQuestionHelper();
        $this->sliceCode = $questionHelper->askCode();
    }
    /**
     * asj user for slice name
     */
    public function askName()
    {
        $questionHelper = $this->getQuestionHelper();
        $this->sliceName = $questionHelper->askName();
    }


    public function askFolder()
    {
        $questionHelper = $this->getQuestionHelper();
        $this->sliceFolder = $questionHelper->askFolder();
    }


    /**
     *
     * @param BundleInterface $bundle
     *
     * @return string
     */
    protected function getSkeletonDirs(BundleInterface $bundle = null)
    {
        $skeletonDirs = array();
        if (null === $bundle) {
            $bundle = $this->getBundle();
        }
        $skeletonDirs[] =  $bundle->getPath().'/Resources/skeleton';
        $skeletonDirs[] =  $bundle->getPath().'/Resources';

        return $skeletonDirs;
    }

    /**
    *
    * @return BundleInterface
    */
    protected function getBundle()
    {
         return $this->getContainer()->get('kernel')->getBundle($this->input->getOption('bundle'));
    }

    /**
    *
    * @return string
    */
    protected function getLowerCamelName()
    {
        return lcfirst(Container::camelize($this->sliceName));
    }

    /**
    *
    * @return GeneratorQuestionsHelper
    */
    protected function getQuestionHelper()
    {
        if (!$this->getHelperSet()->has('generator-question')) {
            $this->getHelperSet()->set(new GeneratorQuestionsHelper($this->input, $this->output, $this->getContainer()));
        }
        $question = $this->getHelperSet()->get('generator-question');

        return $question;
    }

    /**
     *
     * @return Generator
     */
    protected function createGenerator()
    {
        $class = $this->getGeneratorClassName();
        $generator = new $class($this->getContainer()->get('filesystem'), $this->output );
        $bundle = $this->getBundle();
        $generator->setBundle($bundle);

        return $generator;
    }
}
