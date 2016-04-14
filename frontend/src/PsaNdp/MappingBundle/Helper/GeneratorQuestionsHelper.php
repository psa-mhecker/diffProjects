<?php

namespace PsaNdp\MappingBundle\Helper;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sensio\Bundle\GeneratorBundle\Command\Helper\QuestionHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\DependencyInjection\Container;

/**
 * Description of GeneratorQuestionsSet
 *
 * @author sthibault
 */
class GeneratorQuestionsHelper extends QuestionHelper
{
    protected $input;
    protected $output;
    protected $container;

    /**
     *
     * @param InputInterface     $input
     * @param OutputInterface    $output
     * @param ContainerInterface $container
     */
    public function __construct(InputInterface $input, OutputInterface $output,ContainerInterface $container)
    {
        $this->input = $input;
        $this->output = $output;
        $this->container = $container;

    }

    public function getName()
    {

        return 'generator-question';
    }


    protected function getType($value, &$use)
    {

        $type = gettype($value);
        switch ($type) {
            case 'string':
                if (is_numeric($value)) {
                    $type = 'int';
                }
                break;
            case 'integer':
                $type = 'int';
                break;
            case 'object':
                $reflection = new \ReflectionObject($value);
                $use = $reflection->getName();
                $type = $reflection->getShortName();

                break;
            default:

                // do nothing;
        }

        return $type;
    }

    protected function getPrefix($value)
    {

        $type = gettype($value);
        $prefix = '';
        switch ($type) {
            case 'array':
                $prefix = 'array ';
                break;
            case 'object':
                $reflection = new \ReflectionObject($value);
                $prefix = $reflection->getShortName() . ' ';
                break;
            default:

                // do nothing;
        }

        return $prefix;
    }

    public function askCode() 
    {
        $this->output->writeln('<info>Enter the slice Code like "pc19", "pf12" </info>');
        return   $this->ask(
            $this->input,
            $this->output,
            new Question($this->getQuestion('Slice code ', $this->input->getOption('code')), $this->input->getOption('code'))
        );
    }

    public function askName() 
    {

          $this->output->writeln('<info>Enter the slice name like "commitments", "content two columns" </info>');
          return $this->ask(
              $this->input,
              $this->output,
              new Question($this->getQuestion('Slice name : ', $this->input->getOption('name')), $this->input->getOption('name'))
          );
    }

     public function askFolder() {

          $this->output->writeln('<info>Enter the folder suffix for the config files (itkg-sbob) </info>');
          return $this->ask(
                $this->input,
                $this->output,
                new Question($this->getQuestion('Suffix Folder : ', $this->input->getOption('folder')),  $this->input->getOption('folder'))
        );
    }


    /**
     *  Ask for a list of service used by data source
     */
    public function askForParameters()
    {

        $parameters = [];
        $listService = array_map(function($val) {
            return '@' . $val;
        }, $this->container->getServiceIds());

        $listParams = array_map(function($val) {
            return '%' . $val . '%';
        }, array_keys($this->container->getParameterBag()->all()));

        $list = array_merge($listService, $listParams);

        $question = new Question($this->getQuestion('Select parameters/services used by Data Source ', null));
        $question->setAutocompleterValues($list);
        do {
            $question->setAutocompleterValues($list);
            $answer = $this->ask(
                $this->input,
                $this->output,
                $question, null
            );
            if (!empty($answer)) {
                $parameters[] = $this->addDataSourceParameter($answer);
                if (false !== ($key = array_search($answer, $list))) {
                    unset($list[$key]);
                }
            }
        } while (!empty($answer));

        return $parameters;
    }


    /**
     *
     * @param string $parameter
     */
    protected function addDataSourceParameter($parameter)
    {
        $varname = strtr($parameter, array('%' => '', '@' => ''));
        $infos = [];
        $infos['name'] = lcfirst(Container::camelize($varname));
        if ('%' == $parameter{0}) {
            $value = $this->container->getParameter($varname);
        }
        if ('@' == $parameter{0}) {
            $value = $this->container->get($varname);
        }
        $use = '';
        $infos['type'] = $this->getType($value, $use);
        $infos['prefix'] = $this->getPrefix($value);
        $infos['configName'] = $parameter;
        $infos['use'] = $use;

        return $infos;
    }


}
