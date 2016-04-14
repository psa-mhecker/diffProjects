<?php

namespace PsaNdp\ApiBundle\Transformers\Pf11DealerLocator;

use PsaNdp\ApiBundle\Facade\Pf11\DealerCollectionFacade;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;
use PsaNdp\MappingBundle\Translation\TranslatorAwareTrait;
use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class DealerCollectionTransformer extends AbstractTransformer
{
    use TranslatorAwareTrait;

    private $pdv;

    /**
     * @var string
     */
    private $start;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var string
     */
    private $langueCode;

    /**
     * @var int
     */
    private $siteId;

    /**
     * @var array
     */
    private $filters;

    /**
     * @var array
     */
    private $overriddenServices;

    /**
     * @var MediaFactory
     */
    private $mediaFactory;

    /**
     * @var int
     */
    private $minimumDVN;

    /**
     * @param MediaFactory $mediaFactory
     */
    public function __construct(MediaFactory $mediaFactory)
    {
        $this->mediaFactory = $mediaFactory;
    }

    /**
     * @param string $start
     *
     * @return DealerCollectionTransformer
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @param string $langueCode
     *
     * @return DealerCollectionTransformer
     */
    public function setLangueCode($langueCode)
    {
        $this->langueCode = $langueCode;

        return $this;
    }

    /**
     * @param int $siteId
     *
     * @return DealerCollectionTransformer
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;

        return $this;
    }

    /**
     * @param array $filters
     *
     * @return DealerCollectionTransformer
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param Router $router
     *
     * @return DealerCollectionTransformer
     */
    public function setRouter($router)
    {
        $this->router = $router;

        return $this;
    }

    /**
     * @param mixed $mixed
     *
     * @return FacadeInterface
     */
    public function transform($mixed)
    {
        $collection = new DealerCollectionFacade();

        $dvnFirst = $this->getMinimumDVN();

        foreach ($mixed['DealersFull'] as $pdv) {
            $this->pdv = $pdv;

            $collection->add(
                $this->getTransformer('dealer')
                    ->setMediaFactory($this->mediaFactory)
                    ->setSiteId($this->siteId)
                    ->setLanguageCode($this->langueCode)
                    ->setStart($this->start)
                    ->setOverriddenServices($this->overriddenServices)
                    ->transform($pdv),
                $dvnFirst
            );
        }

        if ($dvnFirst) {
            $collection->newVehicleDealersFirst();
        }

        return $collection;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dealer_collection';
    }

    /**
     * @param $services
     *
     * @return $this
     */
    public function setOverriddenServices($services)
    {
        $this->overriddenServices = $services;

        return $this;
    }

    /**
     * Get minimumDVN.
     *
     * @return int
     */
    public function getMinimumDVN()
    {
        return $this->minimumDVN;
    }

    /**
     * @param int $minimumDVN
     *
     * @return DealerCollectionTransformer
     */
    public function setMinimumDVN($minimumDVN)
    {
        $this->minimumDVN = intval($minimumDVN);

        return $this;
    }
}
