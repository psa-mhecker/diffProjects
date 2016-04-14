<?php

use Itkg\Service;
use Itkg\Soap\Client;

//use Itkg\Helper\DataTransformer;

/**
 *
 */
class Plugin_MotCfgConfig extends Service
{
    /**
     * Client SOAP.
     */
    protected $client;

    /*
     *
     */
    public function init()
    {
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
    public function config($requestModel, $responseClass, $mapping, $test = false)
    {
        $serviceWS = 'config';
        try {
            $aOptions = $this->configuration->getParameters();

            if ($mapping) {
                $aOptions['classmap'] = $mapping;
            }

            $this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);

            $responseModel = $this->client->call(
                $serviceWS, $requestModel->__toRequest()
            );

            if ($responseModel->ConfigResponse->Version) {
                return $responseModel->ConfigResponse->Version;
            } else {
                return false;
            }

            $this->logWS($serviceWS, 'OK', 'OK');
        } catch (\Exception $e) {
            $this->logWS($serviceWS, 'KO', $e->getMessage());
        }
    }

    private function logWS($service, $status, $message)
    {
        echo $service.' '.$status.' '.$message;
        //$log = '['.date('Y-m-d H:i:s').'] '.$service.' '.$status.' : '.$message.'\r\n';
        //error_log($log, 3, FunctionsUtils::getLogPath() . 'service.log');
    }
}
