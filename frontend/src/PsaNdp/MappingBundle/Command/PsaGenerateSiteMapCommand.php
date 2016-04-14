<?php

namespace PsaNdp\MappingBundle\Command;

use PSA\MigrationBundle\Repository\PsaLanguageRepository;
use PSA\MigrationBundle\Repository\PsaPageRepository;
use PSA\MigrationBundle\Repository\PsaSiteRepository;
use PsaNdp\LogBundle\CommandEvents;
use PsaNdp\LogBundle\Event\CommandEvent;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Description of PsaGenerateSiteMapCommand
 *
 * @author Hafrit
 */
class PsaGenerateSiteMapCommand extends ContainerAwareCommand
{
    /**
     * @var PsaPageRepository
     */
    private $pageRepository;

    /**
     * @var PsaSiteRepository
     */
    private $siteRepository;

    /**
     * @var PsaLanguageRepository
     */
    private $languageRepository;

    /**
     * @var TwigEngine
     */
    private $templating;

    /**
     * @var array
     */
    private $xmlSiteMap = array();

    /**
     * @var string
     */
    private $defaultPath;

    /**
     * @var integer
     */
    private $siteId;

    /**
     * @var string
     */
    private $langueCode;

    /**
     * @var string
     */
    private $siteUrl;

    /**
     * @var object
     */
    private $siteMapPages;

    public function initialize(InputInterface $input, OutputInterface $output){
        $this->pageRepository = $this->getContainer()->get('open_orchestra_model.repository.node');
        $this->siteRepository = $this->getContainer()->get('open_orchestra_model.repository.site');
        $this->languageRepository = $this->getContainer()->get('psa_ndp_language_repository');
        $this->templating = $this->getContainer()->get('psa.templating');
        $this->defaultPath = $this->getContainer()->getParameter('sitemap.default.path');
    }


    protected function configure(){
        $this
            ->setName('psa:generate:sitemap')
            ->setDescription('generate sitemap')
            ->addOption('siteId', 'S', InputOption::VALUE_OPTIONAL, 'Site ID')
            ->addOption('langueCode', 'L', InputOption::VALUE_OPTIONAL, 'Language code')
        ;
    }

    /**
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output){

        $this->siteId = $input->getOption('siteId');
        $this->langueCode = $input->getOption('langueCode');

        /**
         * @var EventDispatcherInterface $eventDispatcher
         */
        $eventDispatcher = $this->getContainer()->get('event_dispatcher');
        $fs = new Filesystem();

        try{
            if (!$this->siteId && !$this->langueCode){
                $this->generateSiteMapForAllSites();
            }
            else{
                $this->generateSiteMapWithSiteIdAndOrLangueCode();
            }

            foreach($this->xmlSiteMap as $key => $data){
                $path = explode("_", $key);

                $folderPath = $this->defaultPath.$path[0]."/".$path[1];
                $filePath = $this->defaultPath.$path[0]."/".$path[1]."/sitemap.xml";

                if (!file_exists($folderPath)) {
                    try {
                        $fs->mkdir($folderPath);
                    }catch (IOException $e) {
                        $eventDispatcher->dispatch(CommandEvents::COMMAND_ERROR, new CommandEvent($this->siteId, array('folder_path' => $folderPath)));
                        $output->writeln('An error occurred while creating your directory at '.$e->getPath());

                        throw $e;
                    }
                }

                $fs->dumpFile($filePath, $data);
            }

            $eventDispatcher->dispatch(CommandEvents::COMMAND_SUCCESS, new CommandEvent($this->siteId, array()));

            return $output->writeln('End of siteMap generation.');

        } catch (\Exception $e) {
            //log errors here
            $eventDispatcher->dispatch(CommandEvents::COMMAND_ERROR, new CommandEvent($this->siteId, array()));
            $output->writeln('siteMap generation exception !');

            throw $e;
        }

    }

    private function generateSiteMapWithSiteIdAndOrLangueCode(){

        if ($this->siteId){

            $this->siteUrl = $this->siteRepository->findOneBySiteId($this->siteId)->getSiteUrl();

            if ($this->langueCode){

                $this->initializeSiteMap();

                if ($this->siteMapPages) {
                    $this->generateTemplate();
                }

            }
            else{
                $this->generateLanguagesAndGenerateTemplate();
            }
        }
        else{
            $allSites = $this->siteRepository->findByNotId(1);

            foreach($allSites as $site){

                $this->siteUrl = $site->getSiteUrl();
                $this->initializeSiteMap();

                if ($this->siteMapPages) {
                    $this->generateTemplate();
                }
            }
        }

    }

    private function generateSiteMapForAllSites(){

        //get All sites except the administrator
        $allSites = $this->siteRepository->findByNotId(1);

        foreach($allSites as $site){

            $this->siteUrl = $site->getSiteUrl();
            $this->siteId = $site->getSiteId();

            $this->generateLanguagesAndGenerateTemplate();
        }

    }

    private function generateLanguagesAndGenerateTemplate(){

        //get All languages
        $allLanguages = $this->languageRepository->findBySiteId($this->siteId);

        foreach($allLanguages as $language){

            $this->langueCode = $language["langueCode"];

            $this->initializeSiteMap();

            if ($this->siteMapPages){
                $this->generateTemplate();
            }
        }

    }

    private function initializeSiteMap(){
        $this->siteMapPages = $this->pageRepository->getSiteMapPages($this->siteId, $this->langueCode);
    }

    private function generateTemplate(){

        $this->xmlSiteMap[$this->siteId."_".$this->langueCode] = $this->templating->render(
            'PsaNdpMappingBundle::siteMap.html.twig',
            array(
                'siteMapPages' => $this->siteMapPages,
                'siteUrl' => $this->siteUrl,
            )
        );

    }

}
