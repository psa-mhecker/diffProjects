<?php

namespace PsaNdp\MappingBundle\Command;

use PsaNdp\LogBundle\CommandEvents;
use PsaNdp\LogBundle\Event\CommandEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Description of PsaMigrationCleanCommand.
 *
 * @author sthibault
 */
class PsaMigrationCleanCommand extends ContainerAwareCommand
{
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    protected function configure()
    {
        $this
            ->setName('psa:migration:clean')
            ->setDescription('clean migration data')
            ->addOption('age', 'a', InputOption::VALUE_REQUIRED, ' max age of file to preserve ')
            ->addOption('dry-run', 'r', InputOption::VALUE_NONE, 'does not delete files, just show what will be deleted' );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $maxAge = $input->getOption('age');

        $this->eventDispatcher = $this->getContainer()->get('event_dispatcher');
        $finder = new Finder();
        $root = $this->getRootMigrationDir();
        $finder->files()->in($root)->date('before -'.$maxAge.' days');

        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $output->writeln(count($finder).' files will be deleted');
        }

        $dryRun = $input->getOption('dry-run');
        $files = array();
        if (!$dryRun) {
            $files[] = $finder;
            $fs = new Filesystem();
            $fs->remove($finder);
        } else {
            foreach ($finder as $file) {
                $files[] = $file->getRelativePathname();
                $output->writeln($file->getRelativePathname().' will be deleted');
            }
        }

        $this->eventDispatcher->dispatch(CommandEvents::COMMAND_SUCCESS, new CommandEvent(null, array(
            'files' => $files
        )));

        return true;
    }

    private function getRootMigrationDir()
    {
        $directoryNamesConfiguration = $this->getContainer()->getParameter(
            'migration.showroom.directories.configuration'
        );
        $path =getenv('BACKEND_VAR_PATH').DIRECTORY_SEPARATOR.$directoryNamesConfiguration['root'];
        $root = realpath($path);
        if ($this->output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $this->output->writeln(sprintf('searching %s directory for old files', $root));
        }
        if (empty($root)) {
            $this->eventDispatcher->dispatch(CommandEvents::COMMAND_ERROR, new CommandEvent(null, array()));
            throw new \Exception(sprintf('Invalid migration directory %s', $path));
        }

        return $root;
    }
}
