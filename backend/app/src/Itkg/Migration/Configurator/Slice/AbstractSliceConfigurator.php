<?php

namespace Itkg\Migration\Configurator\Slice;

use Itkg\Migration\Transaction\PsaShowroomEntityFactory;

/**
 * Class AbstractSliceConfigurator
 *
 * @package Itkg\Migration\Configurator
 */
abstract class AbstractSliceConfigurator implements SliceConfiguratorInterface
{
    /** @var PsaShowroomEntityFactory */
    protected $entityFactory;

    /**
     * @param PsaShowroomEntityFactory $entityFactory
     */
    public function __construct(PsaShowroomEntityFactory $entityFactory)
    {
        $this->entityFactory = $entityFactory;
    }


    /**
     * @return string
     */
    abstract public function getName();
}
