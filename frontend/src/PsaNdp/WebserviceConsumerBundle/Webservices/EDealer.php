<?php

namespace PsaNdp\WebserviceConsumerBundle\Webservices;


class EDealer extends SoapConsumer
{
    /**
     * @param $geoSiteId
     * @param $languageId
     *
     * @return mixed
     */
    public function getFavoriteOffers($geoSiteId, $languageId)
    {
        $parameters = array(
            'geoSiteId' => $geoSiteId,
            'languageId' => $languageId,
        );

        $response = $this->call('GetFavoriteOffers',  $parameters);
        return  $response->GetFavoriteOffersResult;

    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'WS_EDEALER';
    }
}
