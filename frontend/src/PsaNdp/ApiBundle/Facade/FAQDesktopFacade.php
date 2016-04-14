<?php
namespace PsaNdp\ApiBundle\Facade;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class FAQDesktopFacade
 */
class FAQDesktopFacade implements FacadeInterface
{
    /**
     * @Serializer\SerializedName("resultRubric")
     * @Serializer\Type("array<PsaNdp\ApiBundle\Facade\ResultRubricFacade>")
     */
    public $resultRubric = array();

    public function addResultRubric(FacadeInterface $resultRubric)
    {
        $this->resultRubric[] = $resultRubric;
    }
}
