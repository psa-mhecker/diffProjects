<?php
namespace Citroen\Service;

use Itkg\Service;
use Itkg\Rest\Client;
use Itkg\Helper\DataTransformer;

/**
 *
 */
class GDG extends Service
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
    public function getCarPicker($requestModel, $responseClass, $mapping)
    {
        $aOptions = $this->configuration->getParameters();
        $aOptions['host'] = $aOptions['host'] . 'cars/';
        try {
            $aResponse = $this->client->call('GET', $aOptions['host'], $requestModel->__toRequest(), $aOptions);
            $data = json_decode($aResponse['body']);

            return $data->CarPicker->Model;
        }
        catch (\Exception $exception) {
        }
        return $return;
    }
    
        /*
     *
     */
    public function getBrochure($requestModel, $responseClass, $mapping)
    {
        $aOptions = $this->configuration->getParameters();
        $aOptions['host'] = $aOptions['host'] . 'booklets/';
        try {
            $aResponse = $this->client->call('GET', $aOptions['host'], $requestModel->__toRequest(), $aOptions);
            $data = json_decode($aResponse['body']);
            
            return $data->CarPicker->Model;
        }
        catch (\Exception $exception) {
        }
        return $return;
    }

}