<?php
namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Repository\PsaPageZoneRepository;
use PSA\MigrationBundle\Repository\PsaSiteRepository;
use PsaNdp\MappingBundle\Repository\PsaAfterSaleServicesRepository;
use PsaNdp\MappingBundle\Repository\PsaFilterAfterSaleServicesRepository;
use Symfony\Component\HttpFoundation\Request;


/**
 * Data source
 */
class Pc52ApvDataSource extends AbstractDataSource
{
    /**
     * @var PsaSiteRepository
     */
    protected $siteRepository;

    /**
     * @var PsaFilterAfterSaleServicesRepository
     */
    protected $filterAfterSaleServicesRepository;

    /**
     * @var PsaPageZoneRepository
     */
    protected $pageZoneRepository;

    /**
     * @param PsaSiteRepository                    $siteRepository
     * @param PsaPageZoneRepository                $pageZoneRepository
     * @param PsaAfterSaleServicesRepository       $afterSaleServicesRepository
     * @param PsaFilterAfterSaleServicesRepository $filterAfterSaleServicesRepository
     */
    public function __construct(
        PsaSiteRepository $siteRepository,
        PsaPageZoneRepository $pageZoneRepository,
        PsaAfterSaleServicesRepository  $afterSaleServicesRepository,
        PsaFilterAfterSaleServicesRepository $filterAfterSaleServicesRepository
    )
    {
        $this->siteRepository = $siteRepository;
        $this->pageZoneRepository = $pageZoneRepository;
        $this->afterSaleServicesRepository = $afterSaleServicesRepository;
        $this->filterAfterSaleServicesRepository = $filterAfterSaleServicesRepository;
    }

    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input BlockInterface and current url Request
     *
     * @param ReadBlockInterface $block
     * @param Request        $request  Current url request displaying th block
     * @param bool           $isMobile Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        /** @var PsaPageZoneConfigurableInterface $block */
        $data['block'] = $block;

        $site = $this->siteRepository->findOneBySiteId($request->get('siteId'));
        $languageId = $block->getLangueId();
        $data['filters'] = array();

        if (!$site->getFilterAfterSaleService()) {
            // L'option pas de filtre n'est pas coché
            //récupérer les filtres
            $data['filters'] = $this->filterAfterSaleServicesRepository->findAll();
        }

        // récupérer les apv
        $apvBlocks = $this->pageZoneRepository->FindByZoneTemplateLabelAndLanguageIdAndSiteIdPublished('NDP_PC53_APV', $languageId, $site->getSiteId());

        $allApv = $this->afterSaleServicesRepository->findBySiteIdAndLanguageId($site->getSiteId(), $languageId);

        $data['afterSaleServices'] = $this->getAfterSaleServices($apvBlocks, $allApv);

        return $data;
    }

    /**
     * @param array $apvBlocks
     * @param array $allApv
     *
     * @return array
     */
    protected function getAfterSaleServices(array $apvBlocks, array $allApv)
    {
        $result = array();

        foreach ($apvBlocks as $apvBlock) {
            foreach ($allApv as $apv) {
                if ((int)$apvBlock->getZoneParameters() === $apv->getId()) {
                    $apv->setUrl($apvBlock->getPage()->getVersion()->getPageClearUrl());
                    $result[$apv->getId()] = $apv;
                }
            }
        }

        return $result;
    }
}
