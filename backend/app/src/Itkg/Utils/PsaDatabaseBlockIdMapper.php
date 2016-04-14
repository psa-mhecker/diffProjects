<?php

namespace Itkg\Utils;

use Itkg\Utils\Exception\BlockIdGabaritMappingNotFoundException;
use Itkg\Utils\Exception\BlockIdSliceMappingNotFoundException;
use PSA\MigrationBundle\Entity\Page\PsaPageVersion;

/**
 * Static Mapping from PSA database to get a specific block id inside a zone template:
 *  - For static block, mapping of gabarit (by PSA_PAGE_TYPE / PAGE_TYPE_CODE) to static block types (String) to a block Zone tempalte Id (PSA_PAGE_ZONE / ZONE_TEMPLATE_ID)
 *  - For dynamic block, mapping of dynamic block type (String) to a block Template Id (PSA_PAGE_MULTI_ZONE / ZONE_ID)
 * Mapping should be done statically from the configuration file (in parameters.yml for this class)
 *
 * Class PsaDatabaseBlockIdMapper
 * @package Itkg\Utils
 */
class PsaDatabaseBlockIdMapper
{
    /** @var array */
    protected $staticBlockZoneTemplateIdMapping;
    /** @var array */
    protected $dynamicBlockZoneIdMapping;

    /**
     * Constructor
     *
     * @param array $staticBlockZoneTemplateIdMapping
     *              ex:
     *                 [  PAGE_TYPE_CODE => [SLICE_ID => ZONE_TEMPLATE_ID],
     *                    G02 => [PT21 => 4342, PN7 => 4343, PT2 => 4345] ]
     * @param array $dynamicBlockZoneIdMapping
     *              ex : [SLICE_ID => ZONE_TEMPLATE_ID, PC12 => 760, PC16 => 768]
     */
    public function __construct(array $staticBlockZoneTemplateIdMapping, array $dynamicBlockZoneIdMapping)
    {
        $this->staticBlockZoneTemplateIdMapping = $staticBlockZoneTemplateIdMapping;
        $this->dynamicBlockZoneIdMapping = $dynamicBlockZoneIdMapping;
    }

    /**
     * Mapping of gabarit (by PSA_PAGE_TYPE / PAGE_TYPE_CODE) to static block types (String) to a block Zone tempalte Id (PSA_PAGE_ZONE / ZONE_TEMPLATE_ID)
     *
     * @param PsaPageVersion $pageVersion
     * @param string         $sliceId   ex: PC7
     *
     * @return int
     */
    public function getStaticBlockZoneTemplateIdForPageVersion(PsaPageVersion $pageVersion, $sliceId)
    {
        return $this->getStaticBlockZoneTemplateId(
                $pageVersion->getTemplatePage()->getPageType()->getPageTypeCode(),
                $sliceId
            );
    }

    /**
     * Mapping of gabarit (by PSA_PAGE_TYPE / PAGE_TYPE_CODE) to static block types (String) to a block Zone tempalte Id (PSA_PAGE_ZONE / ZONE_TEMPLATE_ID)
     *
     * @param string $pageTypeCode ex: G02
     * @param string $sliceId ex: PC7
     *
     * @return int
     *
     * @throws BlockIdGabaritMappingNotFoundException
     * @throws BlockIdSliceMappingNotFoundException
     */
    public function getStaticBlockZoneTemplateId($pageTypeCode, $sliceId)
    {
        if (!isset($this->staticBlockZoneTemplateIdMapping[$pageTypeCode])) {
            $e = new BlockIdGabaritMappingNotFoundException(sprintf(
                "No Gabarit with pageTypeCode: %s has been configured. No ZoneTemplateId could be returned for (pageTypeCode, sliceId) : %s, %s",
                $pageTypeCode, $pageTypeCode, $sliceId
            ));
            $e->setSliceId($sliceId);
            $e->setPageTypeCode($pageTypeCode);

            throw $e;
        }
        if (!isset($this->staticBlockZoneTemplateIdMapping[$pageTypeCode][$sliceId])) {
            $e = new BlockIdSliceMappingNotFoundException(sprintf(
                "No static slice Id : %s has been configured for gabarit with pageTypeCode: %s. No ZoneTemplateId could be returned",
                $sliceId, $pageTypeCode
            ));
            $e->setSliceId($sliceId);

            throw $e;
        }

        return $this->staticBlockZoneTemplateIdMapping[$pageTypeCode][$sliceId];
    }

    /**
     * Mapping of dynamic block type (String) to a block Template Id (PSA_PAGE_MULTI_ZONE / ZONE_ID)
     * Note for dynamic block we don't need to map by gabarit since it will be the same dynmaic block id for each gabarit
     *
     * @param string $sliceId ex: PC7
     *
     * @return int
     *
     * @throws BlockIdSliceMappingNotFoundException
     */
    public function getDynamicBlockZoneId($sliceId)
    {
        if (!isset($this->dynamicBlockZoneIdMapping[$sliceId])) {
            $e = new BlockIdSliceMappingNotFoundException(sprintf(
                "No ZoneId has been configured for dynamic slice Id : %s.",
                $sliceId
            ));
            $e->setSliceId($sliceId);

            throw $e;
        }

        return $this->dynamicBlockZoneIdMapping[$sliceId];
    }
}
