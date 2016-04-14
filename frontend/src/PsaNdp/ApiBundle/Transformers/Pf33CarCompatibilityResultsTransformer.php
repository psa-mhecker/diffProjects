<?php

namespace PsaNdp\ApiBundle\Transformers;

use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\ApiBundle\Facade\Pf33\ModelsFinitionsResultFacade;
use PsaNdp\ApiBundle\Facade\Pf33\ModelsFinitionsResultDealerFacade;
use PsaNdp\ApiBundle\Facade\Pf33\FinitionsResultItemFacade;
use PsaNdp\ApiBundle\Facade\Pf33\ConnectServiceItemFacade;
use PsaNdp\ApiBundle\Facade\Pf33\FinitionConnectServiceResultItemFacade;
use PsaNdp\MappingBundle\Entity\PsaServiceConnectFinitionGrouping;

class Pf33CarCompatibilityResultsTransformer extends AbstractTransformer
{
    const OPTION1= 'deserie';
    const OPTION2= 'enoption';
    const OPTION3= 'nondispo';
    const MAX_FINITION = 12;//RG_ FO_PF33_22

    /**
     * @var array
     */
    protected $used;

    /**
     * @var bool
     */
    protected $full;

    /**
     * @var bool
     */
    protected $detailed;


    /**
     * @var PsaServiceConnectFinitionGrouping
     */
    protected $item;


    /**
     * @return string
     */
    public function getName()
    {
        return 'pf33_result_collection';
    }

    /**
     * @return boolean
     */
    public function isFull()
    {
        return $this->full;
    }

    /**
     * @param boolean $full
     *
     * @return Pf33CarCompatibilityResultsTransformer
     */
    public function setFull($full)
    {
        $this->full = $full;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isDetailed()
    {
        return $this->detailed;
    }

    /**
     * @param boolean $detailed
     *
     * @return Pf33CarCompatibilityResultsTransformer
     */
    public function setDetailed($detailed)
    {
        $this->detailed = $detailed;

        return $this;
    }


    /**
     * @param ArrayCollection $mixed
     *
     * @return FacadeInterface
     */
    public function transform($mixed)
    {
        $results = new ModelsFinitionsResultFacade();


        foreach ($mixed['models'] as $lcdv4 => $infos) {

            $dealer = new  ModelsFinitionsResultDealerFacade();
            $dealer->full = true;
            if(!$this->isFull()) {
                $dealer->full = null;
                $dealer->light = true;
            }

            $dealer->id = $lcdv4;;
            $dealer->legend = $this->isDetailed();
            // remplir tabhaut
            $this->initListFinition($dealer, $infos['finitions'], $infos['scfgs']);
            // remplir line
            $this->initListConnectServices($dealer, $infos['scfgs']);
            $results->addDealerItem($dealer);

        }

        return $results;
    }

    protected function initListFinition(ModelsFinitionsResultDealerFacade $dealer, $finitions, $scfgs)
    {
        $this->used = [];
        $count = 0;
        foreach($scfgs as $id=> $scfsgByService) {
            /** @var PsaServiceConnectFinitionGrouping $scfg */
            foreach($scfsgByService['list'] as $scfg) {
                if (isset($finitions[$scfg->getFinitionGrouping()]) && !isset($this->used[$scfg->getFinitionGrouping()]) && ($count < self::MAX_FINITION ) ) {
                    $count++;
                    $this->used[$scfg->getFinitionGrouping()] = $finitions[$scfg->getFinitionGrouping()];
                }
            }
        }

        foreach($this->used as $finitionInfos) {
            $finition = new FinitionsResultItemFacade();
            $finition->label = $finitionInfos['FINISHING_LABEL'];

            $dealer->addFinitionItem($finition);
        }

    }

    protected function initListConnectServices(ModelsFinitionsResultDealerFacade $dealer, $scfgs)
    {
        foreach($scfgs as $scfgsByService) {
            $service = $scfgsByService['service'];
            $connect = new ConnectServiceItemFacade();
            $connect->title =  $service->getLabel();
            $connect->subtitle = $service->getDescription();
            $connect->empty = true;
            if($this->isDetailed()) {
                $connect->empty = null;
                $finitions = array_keys($this->used);
                foreach ($finitions as $finition) {

                    /** @var PsaServiceConnectFinitionGrouping $scfg */
                    $option = 3;
                    if (isset($scfgsByService['list'][$finition])) {
                        $scfg = $scfgsByService['list'][$finition];
                        if ($scfg->getOptions() === 1 || $scfg->getOptions() === 2) {
                            $option = $scfg->getOptions();
                        }
                    }

                    $finition = new FinitionConnectServiceResultItemFacade();
                    $finition->label = constant('self::OPTION' . $option);
                    $connect->addFinitionConnectServiceResultItem($finition);
                }
            }
            $dealer->addConnectServiceItem($connect);
        }
    }
}
