<?php

namespace Itkg\Migration\Transaction;

use PSA\MigrationBundle\Entity\Cta\PsaCtaReferenceInterface;
use PSA\MigrationBundle\Entity\Media\PsaMedia;

/**
 * Class for adding to original entities implementing PsaCtaReferenceInterface other metadata to transport for the migration
 *
 * Class PsaCtaReferentShowroomMetadata
 */
class PsaCtaReferentShowroomMetadata extends AbstractPsaCtaReferentCommonShowroomMetadata
{
    /** @var PsaCtaReferenceInterface */
    private $ctaReferent;

    /**
     * @return PsaCtaReferenceInterface
     */
    public function getCtaReferent()
    {
        return $this->ctaReferent;
    }

    /**
     * @param PsaCtaReferenceInterface $ctaReferent
     *
     * @return PsaCtaReferentShowroomMetadata
     */
    public function setCtaReferent($ctaReferent)
    {
        $this->ctaReferent = $ctaReferent;

        return $this;
    }

}
