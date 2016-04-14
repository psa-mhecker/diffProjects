<?php
namespace Service;

use Itkg\Service;
use Itkg\Rest\Client;

/**
 *
 */
class GDVCars extends Service
{
    /**
     * Client SOAP.
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
    public function cars($requestModel, $responseClass)
    {
        $aOptions = $this->configuration->getParameters();
        $params = $requestModel->__toRequest();
        $params['site'] = $aOptions['prefixe_collection'].$params['site'];
        $aOptions['host'] .= 'cars';
        if ($aOptions['relai'] && is_array($params) && !empty($params)) {
            $tmp = array();
            foreach ($params as $key => $value) {
                $tmp[] = $key.'='.$value;
            }
            $aOptions['host'] .= '?'.implode('%26', $tmp);
        }
        try {
            $aResponse = $this->client->call('GET', $aOptions['host'], $params, $aOptions);
            $return = (string) $aResponse['body'];
        } catch (\Exception $exception) {
        }

        return $return;
    }

}
