<?php

namespace PsaNdp\ApiBundle\Facade;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class InformationFacade
 */
class InformationFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $more;

    /**
     * @Serializer\Type("array<PsaNdp\ApiBundle\Facade\TableHeadFacade>")
     */
    protected $thead;

    /**
     * @Serializer\Type("array<PsaNdp\ApiBundle\Facade\AvailabilityFacade>")
     */
    protected $disponibility;

    /**
     * @param FacadeInterface $availability
     */
    public function addAvailability(FacadeInterface $availability)
    {
        $this->disponibility[] = $availability;
    }

    /**
     * @param FacadeInterface $tableHead
     */
    public function addTableHead(FacadeInterface $tableHead)
    {
        $this->thead[] = $tableHead;
    }
}
