<?php


namespace PsaNdp\ApiBundle\Facade\Pf25;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

class FinancementFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\FinancementUnitFacade")
     */
    public $totalprice;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\FinancementUnitFacade")
     */
    public $mtacompte;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\FinancementUnitFacade")
     */
    public $pmt1;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\FinancementUnitFacade")
     */
    public $pmt2;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\FinancementUnitFacade")
     */
    public $pmtasso;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\FinancementUnitFacade")
     */
    public $durfin;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\FinancementUnitFacade")
     */
    public $nommens;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\FinancementUnitFacade")
     */
    public $totalfin;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\FinancementUnitFacade")
     */
    public $legalText;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\FinancementUnitFacade")
     */
    public $generalLegalText;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\FinancementUnitFacade")
     */
    public $assufac;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\FinancementUnitFacade")
     */
    public $vehiclepricettc;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\FinancementUnitFacade")
     */
    public $pmtmaint_8_13;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\FinancementUnitFacade")
     */
    public $pmtsecurem;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\FinancementUnitFacade")
     */
    public $messagekm;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\FinancementUnitFacade")
     */
    public $mtvr;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\FinancementUnitFacade")
     */
    public $di;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\FinancementUnitFacade")
     */
    public $totaldi;

    /**
     * @Serializer\Type("array")
     */
    public $firstAccount;

    /**
     * @Serializer\Type("array")
     */
    public $financialDetailsTexts;


}
