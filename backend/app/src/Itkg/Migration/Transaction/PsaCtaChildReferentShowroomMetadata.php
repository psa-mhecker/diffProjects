<?php

namespace Itkg\Migration\Transaction;

use PSA\MigrationBundle\Entity\Cta\PsaCtaCtaReferenceInterface;

/**
 * Class for adding to original entities implementing PsaCtaCtaReferenceInterface other metadata to transport for the migration
 *
 * Class PsaCtaChildReferentShowroomMetadata
 */
class PsaCtaChildReferentShowroomMetadata extends AbstractPsaCtaReferentCommonShowroomMetadata
{
    /** @var PsaCtaCtaReferenceInterface */
    private $ctaReferent;

    /**
     * @return PsaCtaCtaReferenceInterface
     */
    public function getCtaReferent()
    {
        return $this->ctaReferent;
    }

    /**
     * @param PsaCtaCtaReferenceInterface $ctaReferent
     *
     * @return PsaCtaChildReferentShowroomMetadata
     */
    public function setCtaReferent($ctaReferent)
    {
        $this->ctaReferent = $ctaReferent;

        return $this;
    }

}
