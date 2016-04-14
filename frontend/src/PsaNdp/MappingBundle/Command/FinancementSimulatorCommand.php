<?php

namespace PsaNdp\MappingBundle\Command;

use PsaNdp\LogBundle\CommandEvents;
use PsaNdp\LogBundle\Event\CommandEvent;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;

class FinancementSimulatorCommand extends ContainerAwareCommand
{
    private $financementSimulator;
    /**
     * @var Filesystem
     */
    private $fileSystem;

    public function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->financementSimulator = $this->getContainer()->get('financement_simulator');
        $this->fileSystem = new Filesystem();
    }

    public function configure()
    {
        $this
            ->setName('ndp:sfg:batch')
            ->setDescription('batch simulateur de financement')
            ->addArgument('site-code', InputArgument::REQUIRED, 'site code for which the batch should run')
            ->addArgument('output', InputArgument::REQUIRED, 'file path containing the output');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $siteCodeInput = $input->getArgument('site-code');
        $filePath = $input->getArgument('output');

        if ($siteCodeInput) {
            $siteCode = $this->getContainer()->get('psa_ndp_site_code_repository')->findOneBySiteCodePays(
                $siteCodeInput
            );

            $siteConfiguration = $this->getContainer()->get('psa_ndp_site_configuration');
            $siteConfiguration->setSiteId($siteCode->getSite()->getId());
            $siteConfiguration->loadConfiguration();
            $currencyCode = $siteConfiguration->getNationalParameter('CURRENCY_CODE');
            $siteLanguages = $this->getSiteLanguages($siteCode);
            /**
             * @var EventDispatcherInterface $eventDispatcher
             */
            $eventDispatcher = $this->getContainer()->get('event_dispatcher');

            foreach ($siteLanguages as $siteLanguage) {
                $models = $this->getModelSilhouettes(
                    $siteLanguage['siteId'],
                    $siteLanguage['languageCode']
                );


                $redisProtocol = '';
                foreach ($models as $model) {

                    try {
                        $cheapestVersion = $this->getContainer()
                            ->get('range_manager')
                            ->getCheapestByLcdv6AndGrBodyStyle(
                                $model->getLcdv6(),
                                $model->getGroupingCode(),
                                $siteConfiguration->getSite()->getCountryCode(),
                                $siteLanguage['languageCode']
                            );


                        if ( ! empty($cheapestVersion)) {

                            $culture = sprintf(
                                '%s-%s',
                                $siteLanguage['languageCode'],
                                strtolower($siteCode->getSiteCodePays())
                            );

                        $this->financementSimulator->getService()->setByPassCache(true);
                            $this->financementSimulator->setBasicParameters(
                                $cheapestVersion->IdVersion->id,
                                $culture,
                                $currencyCode,
                                $cheapestVersion->Price->netPrice,
                                $cheapestVersion->Price->basePrice
                            );

                            $this->financementSimulator->saveCalculationDisplay();

                            $redisProtocol .= $this->generateRedisProtocolLine();
                        }

                    } catch (\Exception $e) {
                        //log errors here
                        $eventDispatcher->dispatch(CommandEvents::COMMAND_ERROR, new CommandEvent($siteCode->getSite()->getId(), array(
                            'language' => $siteLanguage['languageCode'],
                            'cheapest_version' => $cheapestVersion
                        )));
                        throw $e;
                    }
                }
                $this->fileSystem->dumpFile($filePath.'.'.$siteLanguage['languageCode'], $redisProtocol);
            }
            $eventDispatcher->dispatch(CommandEvents::COMMAND_SUCCESS, new CommandEvent($siteCode->getSite()->getId()));
        }
    }

    /**
     * @param PsaSiteCode $siteCode
     *
     * @return array
     */
    private function getSiteLanguages($siteCode)
    {
        $siteLanguages = array();

        if ($siteCode !== null) {

            $site = $siteCode->getSite();
            $languages = $site->getLanguages();

            if ($languages) {

                foreach ($languages as $language) {
                    $siteLanguages[] = array(
                        'siteId' => $site->getId(),
                        'languageId' => $language->getLangueId(),
                        'languageCode' => $language->getLangueCode(),
                    );
                }

            }
        }

        return $siteLanguages;
    }

    /**
     * @param $siteId
     * @param $languageCode
     */
    private function getModelSilhouettes($siteId, $languageCode)
    {
        return $this->getContainer()->get(
            'psa_ndp.repository.vehicle.model_silhouette_site'
        )->findBySiteIdAndLanguageCode($siteId, $languageCode);
    }

    /**
     * @return string
     */
    private function getCacheKey()
    {
        return $this->financementSimulator->getService()->getHashKey();
    }

    /**
     * return string
     */
    private function generateRedisProtocolLine()
    {

        $key = $this->getCacheKey();
        $value = $this->financementSimulator->getService()->getDataForCache();

        return sprintf(
            "*3\r\n$3\r\nSET\r\n$%s\r\n%s\r\n$%s\r\n%s\r\n",
            strlen($key),
            $key,
            strlen($value),
            $value
        );
    }


}