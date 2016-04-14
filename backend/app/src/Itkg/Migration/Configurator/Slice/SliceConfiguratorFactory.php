<?php

namespace Itkg\Migration\Configurator\Slice;

use Itkg\Migration\Transaction\PsaShowroomEntityFactory;

/**
 * Class SliceConfiguratorFactory
 *
 * @package Itkg\Migration\Configurator\Slice
 */
class SliceConfiguratorFactory
{
    private $entityFactory;

    /**
     * @param PsaShowroomEntityFactory $entityFactory
     */
    public function __construct(PsaShowroomEntityFactory $entityFactory)
    {
        $this->entityFactory = $entityFactory;
    }


    /**
     * @param string $class
     *
     * @return SliceConfiguratorInterface|null
     */
    public function createSliceConfigurator($class)
    {
        $configurator = null;

        if (class_exists($class)) {
            $configurator = new $class($this->entityFactory);
        }

        if ($configurator === null || !($configurator instanceof SliceConfiguratorInterface)) {
            throw new \RuntimeException(
                sprintf(
                    "Class '%s' does not exist or is not an instance of SliceConfiguratorInterface. Configurator class could not be instantiate.",
                    $class
                )
            );
        }

        return $configurator;
    }

}
