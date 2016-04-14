<?php

namespace PsaNdp\ApiBundle\Facade\Pf8;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class SearchResultCollectionFacade
 * @package PsaNdp\ApiBundle\Facade\Pf8
 */
class SearchResultCollectionFacade
{
    /**
     * @Serializer\SerializedName("listDealer")
     * @Serializer\Type("array<PsaNdp\ApiBundle\Facade\Pf8\SearchResultDealerFacade>")
     */
    public $listDealer;

    /**
     * @param SearchResultDealerFacade $listDealer
     */
    public function addDealerItem(SearchResultDealerFacade $listDealer)
    {
        $this->listDealer[] = $listDealer;
    }
}
