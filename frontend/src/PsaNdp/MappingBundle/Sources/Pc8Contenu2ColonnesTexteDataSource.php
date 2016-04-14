<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\HttpFoundation\Request;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

/**
 * Data source for Pc8Contenu2ColonnesTexte block
 *
 *
 */
class Pc8Contenu2ColonnesTexteDataSource extends AbstractDataSource
{
    const CTA_COLUMN_1_CTA = 'COLONNE1';
    const CTA_COLUMN_2_CTA = 'COLONNE2';

    /**
     *
     * @param  ReadBlockInterface $block
     * @param  Request            $request  Current url request displaying th block
     * @param  bool               $isMobile Indicate if is a mobile display
     * @return array
     */

    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
    /** @var PsaPageZoneConfigurableInterface $block */
        $data = [];
        $data['block'] = $block;
        $data['column1'] = $block->getCtaReferencesByType(self::CTA_COLUMN_1_CTA);
        $data['column2'] = $block->getCtaReferencesByType(self::CTA_COLUMN_2_CTA);

        return $data;
    }

}
