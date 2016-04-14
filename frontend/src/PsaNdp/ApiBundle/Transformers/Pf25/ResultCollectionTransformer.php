<?php

namespace PsaNdp\ApiBundle\Transformers\Pf25;

use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\ApiBundle\Facade\Pf25\FilterValuesFacade;
use PsaNdp\ApiBundle\Facade\Pf25\ResultCollectionFacade;
use PsaNdp\WebserviceConsumerBundle\Webservices\FinancementSimulator;

class ResultCollectionTransformer extends AbstractTransformer
{

    /**
     * @var FinancementSimulator
     */
    private $financementSimulator;

//    public function __construct(FinancementSimulator $financementSimulator)
//    {
//        $this->financementSimulator = $financementSimulator;
//    }


    /**
     * @param ArrayCollection $mixed
     *
     * @return FacadeInterface
     */


    public function transform($mixed)
    {
        $collection = new ResultCollectionFacade();

        $filterValuesGroup = new FilterValuesFacade();

        if(!empty($mixed['filter_settings'])) {
            $collection->filterSettings = $this->getTransformer('pf25_filter_settings')->transform(
                $mixed['filter_settings']
            );
        }

        foreach ($mixed['models'] as $oneResultItem) {
            $oneResultItem->activeFilters = $mixed['active_filters'];

            if ( ! empty($oneResultItem->cheapestVersion)) {

                $cheapestVersion = $oneResultItem->cheapestVersion;
                $oneResultItem->lcdv16 = $cheapestVersion['LCDV16'];

            }

            $collection->addResultItem(
                $this->getTransformer('pf25_result_item')->transform($oneResultItem)
            );
        }


        return $collection;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pf25_result_collection';
    }
}
