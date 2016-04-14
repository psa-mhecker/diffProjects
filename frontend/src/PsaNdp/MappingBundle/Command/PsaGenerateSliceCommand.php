<?php

/**
 * @todo : Ajouter flag pour ecrasé les fichierrs existant (et supprimer les fichiers )
 *         Ajouter les parametres comme le bundle de destination
 *         Decouper dans différent génerateur  (utilise les classes de base de symfony)
 *         Ajouter la validation des données saisie
 *         Ajouter la config des services
 *          
 */

namespace PsaNdp\MappingBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 
 */
class PsaGenerateSliceCommand extends Command
{

    protected $parameters;
    protected $sliceCode;
    protected $sliceName;
    protected $input;
    protected $output;

    protected function configure()
    {
        $this
                ->setName('psa:generate:slice')
                ->setDescription('generate a new slice structure')
                ->addOption('force', 'f', InputOption::VALUE_NONE, 'Si définie, ecrasera les fichiers existant')
                ->addOption('code', 'c', InputOption::VALUE_OPTIONAL, 'Code de la tranche', 'pc99')
                ->addOption('name', 'N', InputOption::VALUE_OPTIONAL, 'Nom de la tranche', 'acme slice')
                ->addOption('bundle', 'b', InputOption::VALUE_OPTIONAL, 'Bundle destination', 'PsaNdpMappingBundle')
        ;
    }

    /**
     *
     */
    public function writeGeneratorIntroduction()
    {

        $questionHelper = $this->getQuestionHelper();
        $questionHelper->writeSection($this->output, 'Welcome to the Slice generator');

        // namespace
        $this->output->writeln(array(
            '',
            'This command helps you generate Open Orchestra Slice.',
            '',
            'First, you need to give the slice <comment>code</comment> and <comment>name</comment>.',
            ''
        ));
    }
    /**
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * 
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output     = $output;
        $this->input      = $input;
        $this->writeGeneratorIntroduction();
        $this->askCode();
        $this->askName();
        $this->parameters = $this->getQuestionHelper()->askForParameters();
        $generator        = $this->getGenerator();
        $generator->setOverwrite($input->getOption('force'));
        $generator->generate($this->sliceCode, $this->sliceName, $this->parameters);
    }

    /**
     *
     * @return string
     */
    protected function getGeneratorClassName()
    {
        return 'PsaNdp\\MappingBundle\\Generator\\SliceGenerator';
    }
}
