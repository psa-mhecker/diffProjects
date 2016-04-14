<?php

namespace Itkg\Utils\Exception;
use Itkg\Migration\Exception\MigrationExceptionInterface;

/**
 * Class BlockIdGabaritNotFoundException
 */
class BlockIdGabaritMappingNotFoundException extends \Exception implements MigrationExceptionInterface
{
    /** @var int */
    protected $sliceId;
    /** @var int */
    protected $pageTypeCode;

    /**
     * @return int
     */
    public function getSliceId()
    {
        return $this->sliceId;
    }

    /**
     * @param int $sliceId
     *
     * @return BlockIdSliceMappingNotFoundException
     */
    public function setSliceId($sliceId)
    {
        $this->sliceId = $sliceId;

        return $this;
    }

    /**
     * @return int
     */
    public function getPageTypeCode()
    {
        return $this->pageTypeCode;
    }

    /**
     * @param int $pageTypeCode
     *
     * @return BlockIdGabaritMappingNotFoundException
     */
    public function setPageTypeCode($pageTypeCode)
    {
        $this->pageTypeCode = $pageTypeCode;

        return $this;
    }
}
