<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\HttpFoundation\Request;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Repository\PsaReseauSocialRepository;

/**
 * Data source for Pf16AutresReseauxSociaux block
 */
class Pf16AutresReseauxSociauxDataSource extends AbstractDataSource
{
    /**
     * @var PsaReseauSocialRepository
     */
    private $reseauSocialRepository;

    /**
     * @param PsaReseauSocialRepository $reseauSocialRepository
     */
    public function __construct(PsaReseauSocialRepository $reseauSocialRepository)
    {
        $this->reseauSocialRepository = $reseauSocialRepository;
    }

    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input BlockInterface and current url Request
     *
     * @param  ReadBlockInterface $block
     * @param  Request            $request  Current url request displaying th block
     * @param  bool               $isMobile Indicate if is a mobile display
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        /** @var PsaPageZoneConfigurableInterface $block */
        $langId = $block->getLangueId();
        $socialNetworkIds = $block->getParameters();

        $data['pageZone'] = $block;
        $data['socialNetworks'] = $this->reseauSocialRepository->findByLangAndIds($langId, $socialNetworkIds);

        return $data;
    }
}
