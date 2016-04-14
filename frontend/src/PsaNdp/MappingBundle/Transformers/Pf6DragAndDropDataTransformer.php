<?php

namespace PsaNdp\MappingBundle\Transformers;

use \Doctrine\Common\Collections\Collection;
use PsaNdp\MappingBundle\Object\Block\Pf6DragDrop;

/**
 * Data transformer for Pf6DragAndDrop block
 */
class Pf6DragAndDropDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{



    /**
     * @var Pf6DragDrop
     */
    protected $pf6DragDrop;

    /**
     * @param Pf6DragDrop  $f6DragDrop
     * @param Pf6DragDrop  $f6DragDrop
     */
    public function __construct(Pf6DragDrop $pf6DragDrop) {

        $this->pf6DragDrop = $pf6DragDrop;
    }

    /**
     *  Fetching data slice Drag and Drop (pf6)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $this->pf6DragDrop->setDataFromArray($dataSource);

        $this->pf6DragDrop->init();

        return array(
            'slicePF6' => $this->pf6DragDrop,
        );
    }

}
