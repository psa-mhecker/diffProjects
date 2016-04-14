<?php

namespace Itkg\Migration\Transaction;

use PSA\MigrationBundle\Entity\Cta\PsaCtaCtaReferenceInterface;
use PSA\MigrationBundle\Entity\Cta\PsaCtaReferenceInterface;
use PSA\MigrationBundle\Entity\Media\PsaMedia;

/**
 * Class for adding to original entities implementing
 * PsaCtaCtaReferenceInterface or PsaCtaReferenceInterface other metadata to transport for the migration
 *
 * Class AbstractPsaCtaReferentCommonShowroomMetadata
 */
abstract class AbstractPsaCtaReferentCommonShowroomMetadata
{
    const LINK_TYPE_EXTERNAL = "EXTERNAL_LINK";
    const LINK_TYPE_INTERNAL_PDF_MEDIA = "INTERNAL_PDF_MEDIA_LINK";
    const LINK_TYPE_INTERNAL_PAGE = "INTERNAL_PAGE_LINK";

    // Data for TYPE_PDF_MEDIA_LINK
    /** @var PsaMedia Media where pdf should be dwd and saved */
    protected $media;
    /** @var string */
    protected $xmLinkType;

    // Data For TYPE_INTERNAL_LINK
    /** @var string xml id of the page for the cta url */
    protected $xmlPageId;
    /** @var string xml id of the block in the page for adding anchor to the cta url */
    protected $xmlWidgetId;

    /**
     * @return PsaCtaReferenceInterface|PsaCtaCtaReferenceInterface
     */
    abstract public function getCtaReferent();

    /**
     * @param mixed $ctaReferent
     *
     * @return PsaCtaReferentShowroomMetadata|PsaCtaChildReferentShowroomMetadata
     */
    abstract public function setCtaReferent($ctaReferent);

    /**
     * @return PsaMedia
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param mixed $media
     *
     * @return PsaCtaReferentShowroomMetadata|PsaCtaChildReferentShowroomMetadata
     */
    public function setMedia($media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getXmlPageId()
    {
        return $this->xmlPageId;
    }

    /**
     * @param mixed $xmlPageId
     *
     * @return PsaCtaReferentShowroomMetadata|PsaCtaChildReferentShowroomMetadata
     */
    public function setXmlPageId($xmlPageId)
    {
        $this->xmlPageId = $xmlPageId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getXmlWidgetId()
    {
        return $this->xmlWidgetId;
    }

    /**
     * @param mixed $xmlWidgetId
     *
     * @return PsaCtaReferentShowroomMetadata|PsaCtaChildReferentShowroomMetadata
     */
    public function setXmlWidgetId($xmlWidgetId)
    {
        $this->xmlWidgetId = $xmlWidgetId;

        return $this;
    }

    /**
     * @return string
     */
    public function getXmLinkType()
    {
        return $this->xmLinkType;
    }

    /**
     * @param string $xmLinkType
     *
     * @return PsaCtaReferentShowroomMetadata|PsaCtaChildReferentShowroomMetadata
     */
    public function setXmLinkType($xmLinkType)
    {
        $this->xmLinkType = $xmLinkType;

        return $this;
    }


}
