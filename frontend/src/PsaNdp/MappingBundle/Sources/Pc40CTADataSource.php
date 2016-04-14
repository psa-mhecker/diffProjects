<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Cta\PsaCta;
use PSA\MigrationBundle\Entity\Page\PsaPageZone;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Data source for Pc40CTA block
 */
class Pc40CTADataSource extends AbstractDataSource
{

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
        /* @var PsaPageZoneConfigurableInterface $block */
        $data['block'] = $block;

        $data['mode_3visuels'] = false;
        $data['has_3visuels'] = false;

        $zoneAffichage = intval($block->getZoneAffichage());

        if (intval(PsaPageZone::ZONE_AFFICHAGE_CTA_3VISUAL) === $zoneAffichage) {
            $data['mode_3visuels'] = true;
            $data['has_3visuels']  = $block->getCtas()->forAll(
                function ($index, $cta) {
                    /** @var PsaCta $cta */
                    $media = $cta->getMediaWeb();

                    return !empty($media);
                }
            );
        }

        // Grand Visual Image
        $data['mediaGrandVisuel'] = $isMobile ? $block->getMedia2() : $block->getMedia();

        // No Visual, force image to be empty
        if ($zoneAffichage === intval(PsaPageZone::ZONE_AFFICHAGE_CTA_NOVISUAL)) {
            $data['mediaGrandVisuel'] = null;
            $data['has_3visuels'] = false;
            $data['mode_3visuels'] = true;
        }

        return $data;
    }

}
