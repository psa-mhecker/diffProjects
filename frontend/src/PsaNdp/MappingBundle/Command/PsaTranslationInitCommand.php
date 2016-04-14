<?php

namespace PsaNdp\MappingBundle\Command;

use PsaNdp\LogBundle\CommandEvents;
use PsaNdp\LogBundle\Event\CommandEvent;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

/**
 * Description of PsaGenerateStrategyCommand.
 *
 * @author sthibault
 */
class PsaTranslationInitCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
                ->setName('psa:translation:init')
                ->setDescription('generate translations')
                ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var EventDispatcherInterface $eventDispatcher
         */
        $eventDispatcher = $this->getContainer()->get('event_dispatcher');
        $translationDir = $this->getContainer()->get('kernel')->getRootDir().'/Resources/translations/';
        $sites = $this->getContainer()->get('open_orchestra_model.repository.site')->findAll();
        $sitesId = array();
        /** @var \PSA\MigrationBundle\Entity\Site\PsaSite $site */
        foreach ($sites as $site) {
            $sitesId[] = $site->getSiteId();
            //$output->writeln($site->getSiteLabel());
            /** @var \PSA\MigrationBundle\Entity\Language\PsaLanguage $langue */
            foreach ($site->getLangues() as $langue) {
                $dbFile = $site->getSiteId().'.'.$langue->getLangueCode().'.db';
                $fs = new Filesystem();
                try {
                    $fs->touch($translationDir.$dbFile);
                } catch (IOExceptionInterface $e) {

                    $eventDispatcher->dispatch(CommandEvents::COMMAND_ERROR, new CommandEvent($site->getSiteId(), array(
                        'translation_directory' => $translationDir,
                        'sites' => $sitesId,
                        'language_code' => $langue->getLangueCode()
                    )));
                    $output->writeln(sprintf('<error>Error while writing %s <error>', $translationDir.$dbFile));

                    throw $e;
                }
            }
        }

        $eventDispatcher->dispatch(CommandEvents::COMMAND_SUCCESS, new CommandEvent(null, array(
            'translation_directory' => $translationDir,
            'sites' => $sitesId,
        )));
    }
}
