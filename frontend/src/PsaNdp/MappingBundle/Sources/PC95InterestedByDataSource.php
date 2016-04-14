<?php
namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\HttpFoundation\Request;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PsaNdp\MappingBundle\Repository\Vehicle\PsaModelSilhouetteSiteRepository;
use PsaNdp\WebserviceConsumerBundle\Webservices\ConfigurationEngineSelect;
use PsaNdp\MappingBundle\Entity\Vehicle\PsaModelSilhouetteSite;
use PsaNdp\WebserviceConsumerBundle\Webservices\FinancementSimulator;
use PsaNdp\MappingBundle\Utils\SiteConfiguration;
use PsaNdp\MappingBundle\Repository\Vehicle\PsaModelSilhouetteAngleRepository;
use PsaNdp\MappingBundle\Repository\PsaFinishingSiteRepository;
use PsaNdp\MappingBundle\Entity\PsaFinishingSite;
use PsaNdp\MappingBundle\Utils\ModelSilouhetteSiteUtils;

/**
 * Class PC95InterestedByDataSource
 * @package PsaNdp\MappingBundle\Sources
 */
class PC95InterestedByDataSource extends AbstractDataSource
{
    /**
     * @var PsaPageZoneConfigurableInterface
     */
    protected $block;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var int
     */
    protected $siteId;

    /**
     * @var bool
     */
    protected $isMobile;

    /**
     * @var PsaLanguage
     */
    protected $language;

    /**
     * @var PsaModelSilhouetteSiteRepository
     */
    protected $modelSilhouetteSiteRepository;

    /**
     * @var ConfigurationEngineSelect
     */
    protected $configurationEngine;

    /**
     * @var SiteConfiguration
     */
    protected $siteConfiguration;

    /**
     * @var PsaModelSilhouetteAngleRepository
     */
    protected $angleSilhouetteRepository;

    /**
     * @var PsaFinishingSiteRepository
     */
    protected $finishingRepository;

    /**
     * @var ModelSilouhetteSiteUtils
     */
    protected $modelSilouhetteSiteUtils;

    /**
     * @param PsaModelSilhouetteSiteRepository  $silhouetteSite
     * @param ConfigurationEngineSelect         $select
     * @param PsaFinishingSiteRepository        $finishing
     * @param PsaModelSilhouetteAngleRepository $angleSilhouetteRepository
     * @param modelSilouhetteSiteUtils           $modelSilouhetteSiteUtils
     */
    public function __construct(
        PsaModelSilhouetteSiteRepository $silhouetteSite,
        ConfigurationEngineSelect $select,
        PsaFinishingSiteRepository $finishing,
        PsaModelSilhouetteAngleRepository $angleSilhouetteRepository,
        ModelSilouhetteSiteUtils $modelSilouhetteSiteUtils
    ) {
        $this->modelSilhouetteSiteRepository = $silhouetteSite;
        $this->configurationEngine = $select;
        $this->finishingRepository = $finishing;
        $this->angleSilhouetteRepository = $angleSilhouetteRepository;
        $this->modelSilouhetteSiteUtils = $modelSilouhetteSiteUtils;
    }

    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input BlockInterface and current url Request
     *
     * @param ReadBlockInterface $block
     * @param Request            $request  Current url request displaying th block
     * @param bool               $isMobile Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        $this->modelSilouhetteSiteUtils->setTranslator($this->translator, $this->domain, $this->locale);
        /** @var PsaPageZoneConfigurableInterface $block */
        $this->block = $this->data['zone'] = $block;
        $this->siteId = $this->block->getPage()->getSiteId();
        $this->data['siteId'] = $this->siteId;
        $this->language = $this->block->getLangue();
        $this->isMobile = $isMobile;

        $this->getModels();

        return $this->data;
    }

    protected function getModels()
    {
        $this->data['version'] = null;
        $this->data['models'] = [];

        if($this->configurationEngine->getWebserviceStatus($this->siteId, $this->configurationEngine->getName())) {
            $lcdv6 = $this->block->getPage()->getVersion()->getGammeVehiculeLcvd6();
            $silouhette = $this->block->getPage()->getVersion()->getGammeVehiculeSilouhette();

            if ($lcdv6 !== null) {
                $lcdv4 = substr($lcdv6, 0, 4);

                $cheapestbyLcdv6AndBodyCodes = $this->configurationEngine
                    ->getCheapestVersionsForLcdv6AndGrBodyStyleCodesByLcdv4(
                        $lcdv4,
                        $this->block->getPage()->getSite()->getCountryCode(),
                        $this->block->getPage()->getLangue()->getLangueCode()
                    );
                // utilisation de la version la moins cher
                $this->data['version'] = isset($cheapestbyLcdv6AndBodyCodes[$lcdv6][$silouhette]) ? $cheapestbyLcdv6AndBodyCodes[$lcdv6][$silouhette] : null;
                $this->data['models'] = [];

                $regroupementSilhouettes = $this->modelSilhouetteSiteRepository->findOneBySiteIdLanguageCodeLcdv4(
                    $this->siteId,
                    $this->language->getLangueCode(),
                    $lcdv4
                );
                /** @var  PsaModelSilhouetteSite $regroupementSilhouette */
                foreach ($regroupementSilhouettes as $regroupementSilhouette) {
                    // If model from block page, then ignore
                    if ($regroupementSilhouette->getLcdv6() == $lcdv6) {
                        continue;
                    }
                    // Get info for version
                    $this->modelSilouhetteSiteUtils->resetOptions();
                    $this->data['models'][] = $this->modelSilouhetteSiteUtils->generateModelSilouhetteData(
                        $regroupementSilhouette,  $this->isMobile, null, ModelSilouhetteSiteUtils::SLICE_PC95
                    );
                }

        }

        }
    }

    protected function getFinishing($version)
    {
        $finishing = $version->GrCommercialName->label;

        if (isset($version->GrCommercialName->id)) {
            /** @var PsaFinishingSite $finishingSite */
            $finishingSite = $this->finishingRepository->findOneBySiteIdAndLanguageAndCode(
                $this->siteId,
                $this->language->getLangueCode(),
                $version->GrCommercialName->id
            );
            if ($finishingSite) {
                $finishing = $finishingSite->getFinition();
            }
        }

        return $finishing;
    }
}
