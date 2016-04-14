<?php

namespace PsaNdp\WebserviceConsumerBundle\Command;

//use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

/**
 * Description of PsaGenerateStrategyCommand
 *
 * @author sthibault
 */
class MonitorCommand extends ContainerAwareCommand
{
    protected $output;
    protected $input;

    protected function configure()
    {
        $this
                ->setName('psa:webservice:monitor')
                ->setDescription('Monitor Each Webservice')
         ;
    }

    /**
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->input  = $input;

        $this->monitorSfg();
        $this->monitorForms();
        $this->monitorConfigurationEngineSelect();
        $this->monitorConfigurationEngineCompareGrade();
        $this->monitorConfigurationEngineConfig();
        $this->monitorConfigurationEngineEngineCriteria();
        $this->monitorWsGammme();
        $this->monitorEDealer();
        $this->monitorRangeManagerSearch();
        $this->monitorAccessoiresAOA();
    }

    protected function monitorSfg()
    {
        $name = 'SFG';
        $status = '<info>[OK]</info>';
        try {
            $ws = $this->getContainer()->get('financement_simulator');
            $ws->setBasicParameters(
                '1PB1A5FKQ5B0U820',
                'fr-fr',
                '€',
                14500,
                14500
            );
            $ws->saveCalculationDisplay();

        } catch (\Exception $e) {
            $status = '<error>[FAIL]</error> ('.$e->getMessage().')';
        }

        $this->output->writeLn($status.' '.$name);
    }

    protected function monitorConfigurationEngineSelect()
    {
        $name = 'Configuration Engine Select';
        $status = '<info>[OK]</info>';
        try {
            $ws = $this->getContainer()->get('configuration_engine_select');
            $ws->select();
        } catch (\Exception $e) {
            $status = '<error>[FAIL]</error> ('.$e->getMessage().')';
        }

        $this->output->writeLn($status.' '.$name);
    }

    protected function monitorConfigurationEngineCompareGrade()
    {
        $name = 'Configuration Engine Compare Grade';
        $status = '<info>[OK]</info>';
        try {
            $ws = $this->getContainer()->get('configuration_engine_compare_grade');
            $ws->compareGrades('VP', '1PB1', 'A3');
        } catch (\Exception $e) {
            $status = '<error>[FAIL]</error> ('.$e->getMessage().')';
        }

        $this->output->writeLn($status.' '.$name);
    }

    protected function monitorConfigurationEngineConfig()
    {
        $name = 'Configuration Engine Config';
        $status = '<info>[OK]</info>';
        try {
            $ws = $this->getContainer()->get('configuration_engine_config');
            $ws->configByVersion('1PB1A5FKQ5B0U820');
        } catch (\Exception $e) {
            $status = '<error>[FAIL]</error> ('.$e->getMessage().')';
        }

        $this->output->writeLn($status.' '.$name);
    }

    protected function monitorForms()
    {
        $name = 'Forms';
        $status = '<info>[OK]</info>';
        try {
            $ws = $this->getContainer()->get('bo_forms');
            $ws->setDefaultContext();
            $ws->addContext('country','ES');
            $ws->getInstances();
        } catch (\Exception $e) {
            $client = $ws->getService()->getClient();
            $status = '<error>[FAIL]</error> ('.$e->getMessage().')';
        }

        $this->output->writeLn($status.' '.$name);
    }

    protected function monitorConfigurationEngineEngineCriteria()
    {
        $name = 'Configuration Engine Engine Criteria';
        $status = '<info>[OK]</info>';
        try {
            $ws = $this->getContainer()->get('configuration_engine_engine_criteria');
            $ws->engineCriteria('1PB1A5FKQ5B0U820');
        } catch (\Exception $e) {
            $status = '<error>[FAIL]</error> ('.$e->getMessage().')';
        }

        $this->output->writeLn($status.' '.$name);
    }

    protected function monitorWsGammme()
    {
        $name = 'Ws Gamme';
        $status = '<info>[OK]</info>';
        try {
            $ws = $this->getContainer()->get('ws_gamme');
            $ws->ping();
        } catch (\Exception $e) {
            $status = '<error>[FAIL]</error> ('.$e->getMessage().')';
        }

        $this->output->writeLn($status.' '.$name);
    }

    protected function monitorAccessoiresAOA()
    {
        $name = 'Accessoires AOA';
        $status = '<info>[OK]</info>';
        try {
            $ws = $this->getContainer()->get('accessoires_aoa');
            $ws->ping();
        } catch (\Exception $e) {
            $status = '<error>[FAIL]</error> ('.$e->getMessage().')';
        }

        $this->output->writeLn($status.' '.$name);
    }


    protected function monitorEDealer()
    {
        $name = 'Edealer';
        $status = '<info>[OK]</info>';
        try {
            $ws = $this->getContainer()->get('edealer');

            $ws->getFavoriteOffers('0000022737', 'FR-fr');
        } catch (\Exception $e) {
            $status = '<error>[FAIL]</error> ('.$e->getMessage().')';
        }

        $this->output->writeLn($status.' '.$name);
    }

    protected function monitorRangeManagerSearch()
    {
        $name = 'Range Manager Search';
        $status = '<info>[OK]</info>';
        try {
            $ws = $this->getContainer()->get('range_manager');

            $ws->getModelsBodyStyleFromSearch();
        } catch (\Exception $e) {
            $status = '<error>[FAIL]</error> ('.$e->getMessage().')';
        }

        $this->output->writeLn($status.' '.$name);
    }
}
