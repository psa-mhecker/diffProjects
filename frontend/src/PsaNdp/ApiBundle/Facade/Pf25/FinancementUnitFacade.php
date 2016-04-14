<?php

namespace PsaNdp\ApiBundle\Facade\Pf25;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;


class FinancementUnitFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $label;

    /**
     * @Serializer\Type("string")
     */
    public $displayValue;

    /**
     * @Serializer\Type("float")
     */
    public $value;

    /**
     * @Serializer\Type("string")
     */
    public $unit;

    public function __construct($label, $displayValue,$value)
    {
        $this->label = $label;
        $this->displayValue = $displayValue;
        $this->value = $value;

    }
}
