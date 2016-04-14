<?php

namespace PsaNdp\MappingBundle\Object\Block;

use Doctrine\Common\Collections\Collection;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Repository\PsaCtaRepository;
use PsaNdp\MappingBundle\Entity\Vehicle\PsaModelConfig;
use PsaNdp\MappingBundle\Entity\Vehicle\PsaSegmentationFinishesSite;
use PsaNdp\MappingBundle\Manager\PriceManager;
use PsaNdp\MappingBundle\Object\Filters\Filters;
use PsaNdp\MappingBundle\Entity\PsaFinishingSite;

/**
 * Class Pf53Finitions
 */
class Pf53Finitions extends Pf5358FinitionsMotorisations
{
    /**
    * @var PsaPageZoneConfigurableInterface
    */
    protected $block;

    /**
     * @var bool $comparatif
     */
    protected $comparatif;

    /**
     * @var string $mention
     */
    protected $mention;

    /**
     * @var array $segments
     */
    protected $segments;

    protected $defaultSegment;

    /**
     * Constructor
     *
     * @param Filters          $filters
     * @param PriceManager     $priceManager
     * @param PsaCtaRepository $ctaRepository
     */
    public function __construct(Filters $filters, PriceManager $priceManager, PsaCtaRepository $ctaRepository)
    {
        parent::__construct();
        $this->filters = $filters;
        $this->priceManager = $priceManager;
        $this->ctaRepository = $ctaRepository;
    }

    /**
    * @return PsaPageZoneConfigurableInterface
    */
    public function getBlock()
    {
        return $this->block;
    }

    /**
    * @param PsaPageZoneConfigurableInterface $block
     *
     * @return $this
    */
    public function setBlock(PsaPageZoneConfigurableInterface $block)
    {
        $this->block = $block;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * @param array $series
     *
     * @return $this
     */
    public function setSeries(array $series)
    {
        $this->series = $this->groupByFinishing($series);

        return $this;
    }

    /**
     * @return boolean
     */
    public function isComparatif()
    {
        return $this->comparatif;
    }

    /**
     * @param boolean $comparatif
     */
    public function setComparatif($comparatif)
    {
        $this->comparatif = $comparatif;
    }

    /**
     * @return string
     */
    public function getMention()
    {
        return $this->mention;
    }

    /**
     * @param string $mention
     *
     * @return $this
     */
    public function setMention($mention)
    {
        $this->mention = $mention;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        $nbFinition = $this->countSeries();
        $title= $this->trans('NDP_DISCOVER_ONE_FINISH');
        if ($nbFinition > 1) {
            $title = $this->trans('NDP_DISCOVER_SEVERAL_FINISH', array('%nbFinition%' => $nbFinition)); // Découvrez les $nbFinition Finitions Disponibles
        }

        return $title;
    }

    /**
     * @return mixed
     */
    public function getDefaultSegment()
    {
        return $this->defaultSegment;
    }

    /**
     * @param mixed $defaultSegment
     */
    public function setDefaultSegment($defaultSegment)
    {
        $this->defaultSegment = $defaultSegment;
    }

    /**
     * @return array
     */
    public function getSegments()
    {
        return $this->segments;
    }

    /**
     * @param array $segments
     */
    public function setSegments($segments)
    {
        $this->segments = $segments;
    }

    /**
     * @return int
     */
    public function countSeries()
    {
        return count($this->series);
    }

    /**
     * initialize series
     */
    public function initSeries()
    {
        $return = [];
        $count = 0;

        foreach ($this->series as $segments) {
            foreach ($segments['serie'] as $serie) {
                $serie = array_merge($serie,$this->trans);
                $pf53Series = new Pf53Series();

                if (!empty($serie['finishingReference'])) {
                    $pf53Series->setFinishingReference($serie['finishingReference']);
                }

                // Ajoute le titre du segment à la finition sauf si c'est celui par défaut
                if (isset($segments['segment']) && $segments['segment'] instanceof PsaSegmentationFinishesSite && $segments['segment']->getId() !== 1) {
                    $pf53Series->setSegmentTitle($segments['segment']->getLabelLocal());
                }
                $pf53Series->setAngleView($this->angleView);
                $pf53Series->setTranslate($this->translate);
                $pf53Series->setTrans($this->trans);
                $pf53Series->setSiteSettings($this->siteSettings);
                $pf53Series->setSiteAndWebservices($this->siteAndWebservices);
                $pf53Series->setConfiguration($this->configuration);
                if (isset($serie['urlVp'])) {
                    $pf53Series->setUrlVp($serie['urlVp']);
                }
                $pf53Series->setSilhouette($this->silhouette);
                $pf53Series->setTranslator($this->translator, $this->domain, $this->locale);
                $pf53Series->setCompareGrade($this->compareGrade);
                $pf53Series->setConfigurationConfig($this->configurationConfig);
                $pf53Series->setCountryCode($this->countryCode);
                $pf53Series->setLanguageCode($this->languageCode);
                $pf53Series->setConfigurationSelect($this->configurationSelect);
                $pf53Series->setPriceManager($this->priceManager);
                $pf53Series->setEngineCriteria($this->engineCriteria);
                $pf53Series->setDataFromArray($serie);
                $pf53Series->initPopin();
                $pf53Series->initDetails();


                $return[] = $pf53Series;
                $count++;
            }
        }

        $this->series = $return;
    }

    /**
     * @param array PsaSegmentationFinishesSite $segments
     * @param PsaFinishingSite                  $finishing
     *
     * @return PsaSegmentationFinishesSite |null
     */
    private function getSegmentForFinishing($segments, PsaFinishingSite $finishing)
    {
        $segment = null;
        $versionCriterion = $finishing->getVersionsCriterion();
        $results = array_filter(
            $segments,
            function (PsaSegmentationFinishesSite $segment) use ($versionCriterion) {

                return $segment->hasMarketingCriterion($versionCriterion);
            }
        );

        if (! empty($results)) {
            $segment = array_pop($results);
        }

        return $segment;
    }

    /**
     * @param array  $finishings
     * @param string $orderMode
     */

    private function orderFinishing(&$finishings, $orderMode)
    {
        $this->orderSegmentations($finishings);

        switch ($orderMode) {
            case PsaModelConfig::ORDER_PRICE_ASC:
                $this->orderSegmentationFinishings($finishings);
                break;
            case PsaModelConfig::ORDER_PRICE_DESC:
                $this->orderSegmentationFinishings($finishings, false);
                break;
            case PsaModelConfig::ORDER_AO:
            default:
                break;
        }
    }

    /**
     * @param $finishings
     */
    private function orderSegmentations(&$finishings)
    {
        uasort(
            $finishings,
            function ($segmentation1, $segmentation2) {

                $segmentation1Order = intval($segmentation1['segment']->getOrderType());
                $segmentation2Order = intval($segmentation2['segment']->getOrderType());

                if ($segmentation1Order == $segmentation2Order) {
                    return 0;
                }

                return ($segmentation1Order < $segmentation2Order) ? -1 : 1;
            }
        );
    }

    /**
     * @param array $finishings
     * @param bool  $asc
     */
    private function orderSegmentationFinishings(&$finishings, $asc = true)
    {
        foreach ($finishings as $segmentationId => $segmentationFinishings) {
            uasort(
                $segmentationFinishings['serie'],
                function ($finishing1, $finishing2) use ($asc) {

                    $netPrice1 = intval($finishing1['version']->Price->netPrice);
                    $netPrice2 = intval($finishing2['version']->Price->netPrice);

                    if ($netPrice1 == $netPrice2) {
                        return 0;
                    }
                    $sort = ($asc) ? -1 : 1;

                    return ($netPrice1 > $netPrice2) ? -$sort : $sort;
                }
            );

            $finishings[$segmentationId] = $segmentationFinishings;
        }
    }

    /**
     * @param array $series
     *
     * @return array
     */
    protected  function groupByFinishing($series)
    {
        $resultFinition = [];
        $id = null;

        $series = $this->sortByAscendingOrder($series);

        foreach ($series as $key => $serie) {
            if (empty($serie['finishingReference']) && $id !== null) {
                $serie['finishingReference'] = $series[$id]['version'];
            }
            $id = $key;

            if ($serie['finishing'] instanceof PsaFinishingSite) {
                $segment = $this->getSegmentForFinishing($this->segments, $serie['finishing']);
                $segment = $segment ?: $this->defaultSegment;
                $resultFinition[$segment->getId()]['segment'] = $segment;

                if (! array_key_exists('serie', $resultFinition[$segment->getId()])) {
                    $resultFinition[$segment->getId()]['serie'] = array();
                }

                if (array_key_exists($segment->getId(), $resultFinition) && array_key_exists(
                        $serie['finishing']->getId(),
                        $resultFinition[$segment->getId()]['serie']
                    )
                ) {
                    $array = $resultFinition[$segment->getId()]['serie'][$serie['finishing']->getId()];
                    if ($serie['version']->Price->netPrice < $array['version']->Price->netPrice) {
                        $resultFinition[$segment->getId()]['serie'][$serie['finishing']->getId()] = $serie;
                    }
                } else {
                    $resultFinition[$segment->getId()]['serie'][$serie['finishing']->getId()] = $serie;
                }
            }
        }

        $this->orderFinishing($resultFinition, $this->configuration->getFinishingOrder());

        return $resultFinition;

    }

    /**
     * @return array|Filters
     */
    public function getFilters()
    {
        //Gestion des filtres
        $filters = [];

        if($this->isMensualPriceActive()) {
            $filters['price'] = $this->getPriceFilters();
        }

        return $filters;
    }
}
