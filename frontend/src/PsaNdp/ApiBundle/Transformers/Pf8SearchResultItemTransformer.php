<?php

namespace PsaNdp\ApiBundle\Transformers;

use Doctrine\Common\Collections\ArrayCollection;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\ApiBundle\Facade\Pf8\DaysFacade;
use PsaNdp\ApiBundle\Facade\Pf8\SearchResultItemFacade;
use PsaNdp\ApiBundle\Facade\Pf8\PricesFacade;
use PsaNdp\ApiBundle\Facade\Pf8\DisponibilityFacade;
use PsaNdp\MappingBundle\Translation\TranslatorAwareTrait;

/**
 * Class Pf8SearchResultItemTransformer
 * @package PsaNdp\ApiBundle\Transformers
 */
class Pf8SearchResultItemTransformer extends AbstractTransformer
{
    use TranslatorAwareTrait;

    /**
     * @param ArrayCollection $vehiclesCollection
     *
     * @return FacadeInterface
     */
    public function transform($vehiclesCollection)
    {
        $vehicles = $vehicle = array();
        foreach ($vehiclesCollection as $oVehicle) {
            if (is_object($oVehicle)) {
                $vehicle = get_object_vars($oVehicle);

                $daysFacade = new DaysFacade();
                $daysFacade->jour = $this->trans('NDP_DAY');
                $daysFacade->jours = $this->trans('NDP_DAYS');

                $disponibilityFacade = new DisponibilityFacade();
                $disponibilityFacade->text = $this->trans('NDP_AVAILABLE_IN');
                $disponibilityFacade->nbDays = $vehicle['AvailabilityDelay'];
                $disponibilityFacade->days = $daysFacade;

                $pricesFacade = new PricesFacade();
                $pricesFacade->price = $vehicle['InternetPrice'];
                $pricesFacade->advice = $vehicle['CatalogPrice'];
                $pricesFacade->saving = $vehicle['Reduction'];
                $pricesFacade->disponibility = $disponibilityFacade;

                $resultItemFacade = new SearchResultItemFacade();
                $resultItemFacade->imageSrc = $vehicle['VisuExt'];
                $resultItemFacade->imageAlt = $vehicle['ModelLabel'];
                $resultItemFacade->news = true;
                $resultItemFacade->title = $vehicle['CommercialLabel'];
                $resultItemFacade->engine = $vehicle['EngineLabel'];
                $resultItemFacade->painting = $vehicle['ExtFeatureLabel'];
                $resultItemFacade->trimming = $vehicle['IntFeatureLabel'];
                $resultItemFacade->consumption = $vehicle['ConsoMixte'];
                $resultItemFacade->emission = $vehicle['CO2Rate'];
                $resultItemFacade->urlCtaOffre = $vehicle['StoreDetailUrl'];
                $resultItemFacade->prices = $pricesFacade;

                $vehicles[] = $resultItemFacade;
            }
        }

        return $vehicles;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pf8_search_result_item';
    }

    /**
     * @param string $id
     * @param array $parameters
     * @param null $domain
     * @param null $locale
     * @return string
     */
    public function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        if ($domain == null) {
            $domain = $this->domain;
        }
        if ($locale == null) {
            $locale = $this->locale;
        }

        return $this->translator->trans($id, $parameters, $domain, $locale);
    }
}
