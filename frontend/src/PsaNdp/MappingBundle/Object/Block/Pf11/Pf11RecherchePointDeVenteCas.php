<?php

namespace PsaNdp\MappingBundle\Object\Block\Pf11;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Entity\PsaDealerService;
use PsaNdp\MappingBundle\Object\BlockTrait\AgentPointOfSaleSearchTrait;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Repository\PsaDealerServiceRepository;

/**
 * Class Pf11RecherchePointDeVente
 * @package PsaNdp\MappingBundle\Object\Block
 */
class Pf11RecherchePointDeVenteCas extends Content
{
    use AgentPointOfSaleSearchTrait;

    const MAX_FILTER_VISIBLE = 4;
    const MAX_FILTER =12;

    /** @var PsaDealerServiceRepository */
    protected $dealerServiceRepository;

    protected $mapping = array(
        'btnSubmit' => 'searchSubmit',
        'filter' => 'listFilter',
        'filterMore' => 'listMoreFilter'
    );

    public function __construct(PsaDealerServiceRepository $dealerServiceRepository)
    {
        $this->dealerServiceRepository = $dealerServiceRepository;
    }

    /**
     * @var string $hidePhone
     */
    protected $hidePhone;

    /**
     * @var string $tel
     */
    protected $tel;

    /**
     * @var string $seeMore
     */
    protected $seeMore;

    /**
     * @var string $infoDealer
     */
    protected $infoDealer;

    /**
     * @var string
     */
    protected $title;

    /**
     * @param array $listFilter
     *
     * @return $this
     */
    public function initListFilter(array $listFilter)
    {
        $listFilterResult = [];
        $listFilterMoreResult = [];
        $count = 0;
        $dealerServices = $this->findDealerServicesIds($listFilter);

        foreach ($listFilter as $filter) {
            if(isset($dealerServices[$filter])) {
                /** @var PsaDealerService $dealerService */
                $dealerService = $dealerServices[$filter];

                $value = $dealerService->getServiceLabel() !== null ? $dealerService->getServiceLabel() : $dealerService->getServiceLabelCustom();
                $newFilter = array(
                    'value' => $dealerService->getServiceCode(),
                    'label' => $value
                );
                //RG_FO_PF11_18
                if ($count < self::MAX_FILTER_VISIBLE) {
                    $listFilterResult[] = $newFilter;
                } else {
                    $listFilterMoreResult[] = $newFilter;
                }
                $count++;
                //RG_FO_PF11_17
                if($count >= self::MAX_FILTER){
                  break ;
                }
            }

        }

        //Note listFilter and listMoreFilter are mapped as var 'filter' and 'filterMore'
        $this->listFilter = array('displayFilter'=>(count($listFilterResult) > 0)  , 'listFilter' => $listFilterResult);
        $this->listMoreFilter = array('displayFilterMore'=>(count($listFilterMoreResult) > 0), 'listMoreFilter' => $listFilterMoreResult);

        return $this;
    }

    /**
     * Get all list of PsaDealerService in Database for a list fof ids
     * Return array result has for key the associated PsaDealerService->getId() instead of incremental index
     *
     * @param array $listFilter
     * @return PsaDealerService[]
     */
    private function findDealerServicesIds(array $listFilter)
    {
        $result = [];

        $dealerServices = $this->dealerServiceRepository->findByIds($listFilter);
        foreach($dealerServices as $dealerService) {
            /** @var PsaDealerService $dealerService */
            $result[$dealerService->getId()] = $dealerService;
        }

        return $result;
    }
    /**
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @param string $tel
     *
     * @return Pf11RecherchePointDeVenteCas
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * @return string
     */
    public function getSeeMore()
    {
        return $this->seeMore;
    }

    /**
     * @param string $seeMore
     *
     * @return Pf11RecherchePointDeVenteCas
     */
    public function setSeeMore($seeMore)
    {
        $this->seeMore = $seeMore;

        return $this;
    }

    /**
     * @return string
     */
    public function getInfoDealer()
    {
        return $this->infoDealer;
    }

    /**
     * @param string $infoDealer
     *
     * @return Pf11RecherchePointDeVenteCas
     */
    public function setInfoDealer($infoDealer)
    {
        $this->infoDealer = $infoDealer;

        return $this;
    }

    /**
     * @return string
     */
    public function getHidePhone()
    {
        return $this->hidePhone;
    }

    /**
     * @param string $hidePhone
     *
     * @return Pf11RecherchePointDeVenteCas
     */
    public function setHidePhone($hidePhone)
    {
        $this->hidePhone = $hidePhone;

        return $this;
    }

}
