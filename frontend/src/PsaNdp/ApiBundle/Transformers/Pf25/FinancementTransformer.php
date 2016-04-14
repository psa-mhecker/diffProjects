<?php

namespace PsaNdp\ApiBundle\Transformers\Pf25;

use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\ApiBundle\Facade\Pf25\FinancementFacade;
use PsaNdp\ApiBundle\Facade\Pf25\FinancementUnitFacade;
use PsaNdp\WebserviceConsumerBundle\Webservices\FinancementSimulator;

class FinancementTransformer extends AbstractTransformer
{
    public function transform($financementSimulator)
    {

        $financialUnits = array();
        $financementFacade = new FinancementFacade();



        foreach($financementSimulator->getFinancialDetailsKeys() as $key) {
            $memberName = strtolower($key);
            $financialUnit = $financementSimulator->getFinancementDetailsUnit($key);
            $financialUnitFacade = new FinancementUnitFacade($financialUnit['label'], $financialUnit['displayValue'],$financialUnit['value']);
            $financementFacade->$memberName = $financialUnitFacade;
        }


        $financementFacade->firstAccount = $financementSimulator->getFirstAccount();
        $financementFacade->financialDetailsTexts = $financementSimulator->getFinancialDetailsTexts();


        return $financementFacade;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return 'financement';
    }
}
