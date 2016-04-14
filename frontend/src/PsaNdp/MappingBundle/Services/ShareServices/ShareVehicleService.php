<?php

namespace PsaNdp\MappingBundle\Services\ShareServices;

use OpenOrchestra\ModelInterface\Model\ReadNodeInterface;
use PsaNdp\MappingBundle\Entity\Vehicle\PsaModelSilhouetteSite;
use PsaNdp\MappingBundle\Repository\Vehicle\PsaModelSilhouetteSiteRepository;
use PsaNdp\MappingBundle\Utils\ModelSilouhetteSiteUtils;
use PsaNdp\WebserviceConsumerBundle\Webservices\ConfigurationEngineSelect;
use PsaNdp\WebserviceConsumerBundle\Webservices\RangeManager;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ShareVehicleService
 */
class ShareVehicleService
{
    /**
     * @var RangeManager
     */
    protected $rangeManager;

    /**
     * @var ConfigurationEngineSelect
     */
    protected $configurationEngine;

    /**
     * @var ModelSilouhetteSiteUtils
     */
    protected $modelSilouhetteSiteUtils;

    /**
     * @var PsaModelSilhouetteSiteRepository
     */
    protected $modelSilhouetteSiteRepository;

    /**
     * @var PsaModelSilhouetteSite
     */
    protected $modelSilhouetteInformation;

    /**
     * @var array
     */
    protected $modelSilhouette;

    /**
     * @var ReadNodeInterface
     */
    protected $node;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var bool
     */
    protected $isMobile = false;

    /**
     * @param ConfigurationEngineSelect          $configurationEngine
     * @param RangeManager                       $rangeManager
     * @param modelSilouhetteSiteUtils           $modelSilouhetteSiteUtils
     * @param PsaModelSilhouetteSiteRepository   $silhouetteSite
     */
    public function __construct(
        ConfigurationEngineSelect $configurationEngine,
        RangeManager $rangeManager,
        ModelSilouhetteSiteUtils $modelSilouhetteSiteUtils,
        PsaModelSilhouetteSiteRepository $silhouetteSite
    ) {
        $this->configurationEngine = $configurationEngine;
        $this->rangeManager = $rangeManager;
        $this->modelSilouhetteSiteUtils = $modelSilouhetteSiteUtils;
        $this->modelSilhouetteSiteRepository = $silhouetteSite;
    }

    /**
     * @return array
     */
    public function getModelSilhouette()
    {
        try {
            if (!isset($this->modelSilhouette)) {
                $this->modelSilouhetteSiteUtils->setTranslator($this->translator, $this->node->getSiteId(), $this->node->getLanguage());
                if (/*$this->configurationEngine->getWebserviceStatus($this->node->getSiteId(), $this->configurationEngine->getName())
                        && */$this->rangeManager->getWebserviceStatus($this->node->getSiteId(), $this->rangeManager->getName())) {
                    $lcdv6 = $this->node->getVersion()->getGammeVehiculeLcvd6();
                    $silouhette = $this->node->getVersion()->getGammeVehiculeSilouhette();
                    if ($lcdv6 !== null) {
                        $cheapest = $this->rangeManager
                            ->getCheapestByLcdv6AndGrBodyStyle(
                                $lcdv6,
                                $silouhette,
                                $this->node->getSite()->getCountryCode(),
                                $this->node->getLangue()->getLangueCode()
                            );

                        // utilisation de la version la moins cher
                        $this->modelSilhouette['cheapest'] = $cheapest;
    //                        $this->modelSilhouette['version'] = $this->configurationEngine->getVersionByLCDV16($cheapest['LCDV16']);
                        $this->modelSilhouette['version'] = $cheapest;
                        $this->modelSilouhetteSiteUtils->resetOptions();
                        $this->modelSilhouette['imgSrc'] = $this->modelSilouhetteSiteUtils->generateImgUrl($this->modelSilhouette['version'], $lcdv6, $this->isMobile);
                    }
                }
            }
        } catch (\Exception $e) {
        }

        return $this->modelSilhouette;
    }

    /**
     * @return array|PsaModelSilhouetteSite
     */
    public function getModelSilhouetteInformation()
    {
        if (!isset($this->modelSilhouetteInformation)) {
            $this->modelSilhouetteInformation = $this->modelSilhouetteSiteRepository->findOneBySiteIdLanguageCodeLcdvAndGroupingCode(
                $this->node->getSiteId(),
                $this->node->getLanguage(),
                $this->node->getVersion()->getGammeVehiculeLcvd6(),
                $this->node->getVersion()->getGammeVehiculeSilouhette()
            );
        }

        return $this->modelSilhouetteInformation;
    }

    /**
     * @param mixed $modelSilhouette
     *
     * @return $this
     */
    public function setModelSilhouette($modelSilhouette)
    {
        $this->modelSilhouette = $modelSilhouette;

        return $this;
    }

    /**
     * @return ReadNodeInterface
     */
    protected function getNode()
    {
        return $this->node;
    }

    /**
     * @param ReadNodeInterface $node
     *
     * @return $this
     */
    public function setNode(ReadNodeInterface $node)
    {
        $this->node = $node;

        return $this;
    }

    /**
     * @param TranslatorInterface $translator
     */
    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param $isMobile
     */
    public function setIsMobile($isMobile)
    {
        $this->isMobile = $isMobile;
    }
}
