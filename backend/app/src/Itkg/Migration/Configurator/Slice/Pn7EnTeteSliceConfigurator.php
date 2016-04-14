<?php

namespace Itkg\Migration\Configurator\Slice;

use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Object\Block\Pn7EnTeteData;

/**
 * Class Pn7EnTeteSliceConfigurator
 *
 * @package Itkg\Migration\Configurator
 */
class Pn7EnTeteSliceConfigurator extends AbstractSliceConfigurator
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'PN7';
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
        $block = $this->entityFactory->createStaticBlockForSlideId(
            $currentPage->getPage(),
            $this->getName()
        );

        // Don't display desktop by default
        $block->setZoneWeb(0);
        // Display format : 'Classique'
        $block->setZoneTitre3(Pn7EnTeteData::CLASSIQUE);
        // Get title from page
        if ($currentPage->getPage() && $currentPage->getPage()->getDraftVersion()) {
            $block->setZoneTitre($currentPage->getPage()->getDraftVersion()->getPageTitle());
        }

        return $block;
    }

}
