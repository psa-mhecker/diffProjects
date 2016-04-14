<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pf6DragAndDropStrategy.
 */
class Pf6DragAndDropStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pf6.html.smarty';
    }

    /**
     *
     */
    protected function initStrategy()
    {
        $this->isPopin = true;
    }
}
