<?php
namespace PsaNdp\MappingBundle\Tests\Command;

use Phake;
use Doctrine\Common\Collections\ArrayCollection;
use PsaNdp\LogBundle\CommandEvents;
use Symfony\Component\Console\Application;
use PsaNdp\MappingBundle\Command\PsaGenerateSiteMapCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class PsaGenerateSiteMapCommandTest
 */
class PsaGenerateSiteMapCommandTest extends \PHPUnit_Framework_TestCase
{
    protected $container;

    /**
     * @var Command
     */
    protected $command;

    private $pageRepository;

    private $siteRepository;

    private $languageRepository;

    private $templating;

    /**
     * @var string
     */
    private $defaultPath = 'web/sitemap/';

    private $psaSite;

    private $language;

    private $eventDispatcher;

    /**
     * Set Up the test
     */
    public function setUp()
    {
        $this->pageRepository = Phake::mock('PSA\MigrationBundle\Repository\PsaPageRepository');
        $this->siteRepository = Phake::mock('PSA\MigrationBundle\Repository\PsaSiteRepository');
        $this->languageRepository = Phake::mock('PSA\MigrationBundle\Repository\PsaLanguageRepository');
        $this->templating = Phake::mock('Symfony\Bundle\TwigBundle\TwigEngine');
        $this->container = Phake::mock('Symfony\Component\DependencyInjection\Container');
        $this->psaSite = Phake::mock('PSA\MigrationBundle\Entity\Site\PsaSite');
        $this->language = Phake::mock('PSA\MigrationBundle\Entity\Language\PsaLanguage');
        $this->eventDispatcher = Phake::mock('Symfony\Component\EventDispatcher\EventDispatcher');

        Phake::when($this->container)->get('open_orchestra_model.repository.node')->thenReturn($this->pageRepository);
        Phake::when($this->container)->get('open_orchestra_model.repository.site')->thenReturn($this->siteRepository);
        Phake::when($this->container)->get('psa_ndp_language_repository')->thenReturn($this->languageRepository);
        Phake::when($this->container)->get('psa.templating')->thenReturn($this->templating);
        Phake::when($this->container)->getParameter('sitemap.default.path')->thenReturn($this->defaultPath);
        Phake::when($this->container)->get('event_dispatcher')->thenReturn($this->eventDispatcher);

        $application = new Application();
        $application->add(new PsaGenerateSiteMapCommand());

        $this->command = $application->find('psa:generate:sitemap');
        $this->command->setContainer($this->container);
    }

    /**
     * Test method with command options
     */
    public function testExecute()
    {
        Phake::when($this->siteRepository)->findOneBySiteId(2)->thenReturn($this->psaSite);
        Phake::when($this->psaSite)->getSiteUrl()->thenReturn('fr.psa-ndp.com');

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(
            array(
                '--siteId'    => '2',
                '--langueCode'  => 'fr',
            )
        );

        $this->assertRegExp('/End of siteMap generation./', $commandTester->getDisplay());

        Phake::verify($this->eventDispatcher)->dispatch(Phake::anyParameters());
    }

    /**
     * Test method without command options
     */
    public function testExecuteWithoutOptions()
    {
        $collection = new ArrayCollection();
        $collection->add($this->psaSite);

        Phake::when($this->siteRepository)->findByNotId(1)->thenReturn($collection);
        Phake::when($this->psaSite)->getSiteUrl()->thenReturn('fr.psa-ndp.com');
        Phake::when($this->psaSite)->getSiteId()->thenReturn(2);
        Phake::when($this->languageRepository)->findBySiteId(2)->thenReturn(array($this->language, $this->language));

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(array());

        $this->assertRegExp('/End of siteMap generation./', $commandTester->getDisplay());
    }

    /**
     * Test method with only siteId option
     */
    public function testExecuteBySiteId()
    {
        Phake::when($this->siteRepository)->findOneBySiteId(2)->thenReturn($this->psaSite);
        Phake::when($this->psaSite)->getSiteUrl()->thenReturn('fr.psa-ndp.com');
        Phake::when($this->languageRepository)->findBySiteId(2)->thenReturn(array($this->language, $this->language));

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(
            array(
                '--siteId'    => '2',
            )
        );

        $this->assertRegExp('/End of siteMap generation./', $commandTester->getDisplay());
    }

    /**
     * Test method with only languageCode option
     */
    public function testExecuteByLanguageCode()
    {
        $collection = new ArrayCollection();
        $collection->add($this->psaSite);

        Phake::when($this->siteRepository)->findByNotId(1)->thenReturn($collection);
        Phake::when($this->psaSite)->getSiteUrl()->thenReturn('fr.psa-ndp.com');
        Phake::when($this->psaSite)->getSiteId()->thenReturn(2);

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(
            array(
                '--langueCode'  => 'fr',
            )
        );

        $this->assertRegExp('/End of siteMap generation./', $commandTester->getDisplay());
    }

}
