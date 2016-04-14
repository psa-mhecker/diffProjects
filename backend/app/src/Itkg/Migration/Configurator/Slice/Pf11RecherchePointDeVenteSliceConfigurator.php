<?php

namespace Itkg\Migration\Configurator\Slice;

use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Transformers\Pf11RecherchePointDeVenteDataTransformer;

/**
 * Class Pf11RecherchePointDeVenteSliceConfigurator
 *
 * @package Itkg\Migration\Configurator
 */
class Pf11RecherchePointDeVenteSliceConfigurator extends AbstractSliceConfigurator
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'PF11';
    }

    /**
     * create a new slice
     *
     * @param ShowroomUrlManager $urlManager
     * @param PsaPageShowroomMetadata $currentPage
     * @param AddReportingMessageInterface $reporting
     *
     * @return PsaPageZoneConfigurableInterface
     */
    public function create(
        ShowroomUrlManager $urlManager,
        PsaPageShowroomMetadata $currentPage,
        AddReportingMessageInterface $reporting
    )
    {
        $block = $this->entityFactory->createDynamicBlockForSliceId(
            $currentPage->getPage(),
            $currentPage->getDynamicBlocksZoneOrder(),
            $this->getName()
        );

        // Mode search PDV
        $block->setZoneCriteriaId(Pf11RecherchePointDeVenteDataTransformer::MODE_SEARCH_PDV);
        // Filter
        $block->setZoneCriteriaId2(Pf11RecherchePointDeVenteDataTransformer::FILTER_BY_RADIUS);
        // Deactivate filter by pdv name
        $block->setZoneCriteriaId3(0);
        // Activate Regroupement
        $block->setZoneAttribut(1);
        // Activate Autocompletion
        $block->setZoneAttribut2(1);
        // Activate See Telephone
        $block->setZoneAttribut3(0);

        return $block;
    }
}
