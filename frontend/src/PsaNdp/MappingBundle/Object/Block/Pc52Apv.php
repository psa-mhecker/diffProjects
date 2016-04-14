<?php

namespace PsaNdp\MappingBundle\Object\Block;

use PsaNdp\MappingBundle\Entity\PsaAfterSaleServices;
use PsaNdp\MappingBundle\Entity\PsaFilterAfterSaleServices;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Cta;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;
use PsaNdp\MappingBundle\Object\Factory\CtaFactory;
use PsaNdp\MappingBundle\Object\Formatter\PriceFormatter;
use PsaNdp\MappingBundle\Object\Vignette;

/**
 * Class Pc52Apv
 */
class Pc52Apv extends Content
{
    /**
     * @var string
     */
    protected $legalNotice;

    /**
     * @var array
     */
    protected $filters;

    /**
     * @var array
     */
    protected $afterSaleServices;

    /**
     * @var boolean
     */
    protected $withoutFilter = false;

    /**
     * @var array
     */
    protected $apv = array();

    /**
     * @var PriceFormatter
     */
    protected $priceFormatter;

    /**
     * @var string
     */
    protected $filtersTitle;

    /**
     * Pc52Apv constructor.
     *
     * @param CtaFactory     $ctaFactory
     * @param MediaFactory   $mediaFactory
     * @param PriceFormatter $priceFormatter
     */
    public function __construct(
        CtaFactory $ctaFactory,
        MediaFactory $mediaFactory,
        PriceFormatter $priceFormatter
    )
    {
        $this->ctaFactory = $ctaFactory;
        $this->mediaFactory = $mediaFactory;
        $this->priceFormatter = $priceFormatter;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->block->getZoneTitre();
    }

    /**
     * @return string
     */
    public function getLegalNotice()
    {
        return $this->block->getZoneTexte();
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param array $filters
     */
    public function setFilters(array $filters)
    {
        $this->filters = array();
        if (!empty($filters)) {
            foreach ($filters as $filter) {
                /** @var PsaFilterAfterSaleServices $filter */
                $this->filters[] = array(
                    'id' => $filter->getId(),
                    'label' => $filter->getTitle(),
                );
            }
        }
    }

    /**
     * @param array $afterSaleServices
     */
    public function setAfterSaleServices($afterSaleServices)
    {
        $this->afterSaleServices = $afterSaleServices;
    }

    /**
     * @return boolean
     */
    public function isWithoutFilter()
    {
        $this->withoutFilter = false;
        if (empty($this->filters)) {
            $this->withoutFilter = true;
        }

        return $this->withoutFilter;
    }

    /**
     * @return array
     */
    public function getApv()
    {
        $apv = array();

        foreach ($this->afterSaleServices as $afterSaleService) {

            $vignette = new Vignette();

            /** @var PsaAfterSaleServices $afterSaleService */
            if ($afterSaleService->getMedia()) {
                $vignette->setMedia($this->mediaFactory->createFromMedia($afterSaleService->getMedia()));
            }

            $vignette->setTitle($afterSaleService->getTitle());
            $vignette->setUrl($afterSaleService->getUrl());

            $vignette->setCtaList(
                array(
                    $this->ctaFactory->createFromArray(
                        array(
                            "href"   => $afterSaleService->getUrl(),
                            "title" => $afterSaleService->getLabelLink(),
                            "type"  => Cta::NDP_CTA_TYPE_SIMPLELINK
                        )
                    )
                )
            );

            // formatting price
            $this->priceFormatter->setFrom($afterSaleService->getPriceLabel());
            $this->priceFormatter->setFromPricePosition($afterSaleService->getPricePosition());
            $vignette->setPrice($this->priceFormatter->getOrderedPricePart($afterSaleService->getPrice()));

            if ($afterSaleService->getColumnNumber() === 1) {
                if ($afterSaleService->getPrice() > $afterSaleService->getPrice2()) {
                    // mise en forme du prix
                    $this->priceFormatter->setFrom($afterSaleService->getPriceLabel2());
                    $vignette->setPrice($this->priceFormatter->getOrderedPricePart($afterSaleService->getPrice2()));
                }
            }

            $filterId = [];
            foreach ($afterSaleService->getFilters() as $filter)
            {
                $filterId[] = $filter->getId();
            }

            $vignette->setFilters($filterId);

            $apv[] = $vignette;
        }

        return $apv;
    }

    /**
     * @return string
     */
    public function getFiltersTitle()
    {
        return $this->block->getZoneTitre2();
    }
}
