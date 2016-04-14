<?php
namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PsaNdp\MappingBundle\Repository\PsaAfterSaleServicesRepository;
use Symfony\Component\HttpFoundation\Request;


/**
 * Data source
 */
class Pc53ApvDataSource extends AbstractDataSource
{

    CONST CTA_LEVEL_1 = "LEVEL1_CTA";
    CONST CTA_LEVEL_2 = "LEVEL2_CTA";

    /**
     * @var PsaAfterSaleServices
     */
    protected $afterSaleServices;

    /**
     * @param PsaAfterSaleServicesRepository $pageRepository
     */
    public function __construct(PsaAfterSaleServicesRepository $afterSaleServices)
    {
        $this->afterSaleServices = $afterSaleServices;
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
        $siteId = $block->getPage()->getSiteId();
        $langId = $block->getLangueId();

        $data['afterSaleServices'] = $this->afterSaleServices->findOneBy(array(
            'site' => (int) $siteId,
            'language' => (int) $langId,
            'id' => (int) $block->getZoneParameters()
        ));

        $data['ctaLevel1']['ctaList'] = $block->getCtaReferencesByType(self::CTA_LEVEL_1);
        $data['ctaLevel2']['ctaList'] = $block->getCtaReferencesByType(self::CTA_LEVEL_2);

        return $data;
    }

}
