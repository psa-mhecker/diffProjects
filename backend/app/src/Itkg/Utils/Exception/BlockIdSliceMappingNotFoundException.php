<?php

namespace Itkg\Utils\Exception;
use Itkg\Migration\Exception\MigrationExceptionInterface;

/**
 * Class BlockIdGabaritNotFoundException
 */
class BlockIdSliceMappingNotFoundException extends \Exception implements MigrationExceptionInterface
{
    /** @var int */
    protected $sliceId;

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
}
