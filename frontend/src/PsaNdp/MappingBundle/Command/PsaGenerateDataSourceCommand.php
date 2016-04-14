<?php

namespace PsaNdp\MappingBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of PsaGenerateDataTransformerCommand
 *
 * @author sthibault
 */
class PsaGenerateDataSourceCommand extends Command
{

    /**
     *
     * @var array
     */
    protected $parameters;

    protected function configure()
    {
        $this
                ->setName('psa:generate:data-source')
                ->setDescription('generate a new data source')
                ->addOption('force', 'f', InputOption::VALUE_NONE, 'Si définie, ecrasera les fichiers existant')
                ->addOption('code', 'c', InputOption::VALUE_OPTIONAL, 'Code de la tranche', 'pc99')
                ->addOption('name', 'N', InputOption::VALUE_OPTIONAL, 'Nom de la tranche', 'acme slice')
                ->addOption('bundle', 'b', InputOption::VALUE_OPTIONAL, 'Bundle destination', 'PsaNdpMappingBundle')
        ;
    }

    public function writeGeneratorIntroduction() 
    {

        $questionHelper = $this->getQuestionHelper();
        $questionHelper->writeSection($this->output, 'Welcome to the Data Source generator');

        // namespace
        $this->output->writeln(array(
            '',
            'This command helps you generate Open Orchestra Data Source.',
            '',
            'First, you need to give the slice <comment>code</comment> and <comment>name</comment>.',
            ''
        ));


    }

    /**
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->input = $input;
        $this->writeGeneratorIntroduction();
        $this->askCode();
        $this->askName();
        $this->parameters = $this->getQuestionHelper()->askForParameters();
        $generator =  $this->getGenerator();
        $generator->setOverwrite($input->getOption('force'));
        $generator->generate($this->sliceCode, $this->sliceName, $this->parameters);
    }

    /**
     *
     * @return string
     */
    protected function getGeneratorClassName()
    {
        return 'PsaNdp\\MappingBundle\\Generator\\DataSourceGenerator';
    }
}
