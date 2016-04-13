<?php
namespace Citroen\Service;

use Itkg\Service;
use Itkg\Rest\Client;
use Itkg\Helper\DataTransformer;

/**
 *
 */
class AnnuPDV extends Service
{

    /**
     * Client SOAP
     */
    protected $client;

    /*
     *
     */
    protected $isDirect;

    /*
     *
     */
    public function init()
    {
        $this->client = new Client(
            $this->configuration->getParameter('host'),
            $this->configuration->getParameters()
        );
    }

    /*
     *
     */
    public function monitor()
    {
    }

    /*
     *
     */
    public function geoLocalize($requestModel, $responseClass, $mapping)
    {
        $aOptions = $this->configuration->getParameters();
        $aOptions['host'] .= 'GeoLocalize';
        try {
            $aResponse = $this->client->call('GET', $aOptions['host'], $requestModel->__toRequest(), $aOptions);
			$return = json_decode($aResponse['body']);
        }
        catch (\Exception $exception) {
        }
        return $return;
    }

    /*
     *
     */
    public function getDealer($requestModel, $responseClass, $mapping)
    {
        $aOptions = $this->configuration->getParameters();
        $aOptions['host'] .= 'GetDealer';
        try {
            $aResponse = $this->client->call('GET', $aOptions['host'], $requestModel->__toRequest(), $aOptions);
            $return = json_decode($aResponse['body']);
        }
        catch (\Exception $exception) {
        }
        return $return;
    }

    /*
     *
     */
    public function getDealersList($requestModel, $responseClass, $mapping)
    {
        $aOptions = $this->configuration->getParameters();
        $aOptions['host'] .= 'GetDealerList';
        try {
            $aResponse = $this->client->call('GET', $aOptions['host'], $requestModel->__toRequest(), $aOptions);
            $return = json_decode($aResponse['body']);
        }
        catch (\Exception $exception) {
        }
        return $return;
    }

    /*
     *
     */
    public function getBusinessList ($requestModel, $responseClass, $mapping)
    {
        $aOptions = $this->configuration->getParameters();
        $aOptions['host'] .= 'GetBusinessList';
        try {
            $aResponse = $this->client->call('GET', $aOptions['host'], $requestModel->__toRequest(), $aOptions);
            $return = json_decode($aResponse['body']);
        }
        catch (\Exception $exception) {
        }
        return $return;
    }

}