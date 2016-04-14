<?php

namespace Citroen\Service;

use Itkg\Service;
use Itkg\Soap\Client;

/**
 *
 */
class SimulFin extends Service
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

    public function openSession($requestModel, $responseClass, $mapping)
    {
        $aOptions = $this->configuration->getParameters();
        if ($mapping) {
            $aOptions['classmap'] = $mapping;
        }
        $this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);
        $responseModel = $this->client->call(
            'openSession', $requestModel->__toRequest()
        );
        if ($responseModel) {
            return $responseModel->ResponseSave->IdSession;
        } else {
            return false;
        }
    }

    /*
     *
     */

    public function saveCalculationDisplay($requestModel, $responseClass, $mapping)
    {
        $aOptions = $this->configuration->getParameters();
        if ($mapping) {
            $aOptions['classmap'] = $mapping;
        }
        $this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);
        $responseModel = $this->client->call(
            'saveCalculationDisplay', $requestModel->__toRequest()
        );
        $return = array();
        $i = 0;
        if ($responseModel) {
            foreach ($responseModel->ResponseDisplay->DisplayList->Display as $display) {
                if ($display->DisplayContent) {
                    $return[$i] = array();
                    foreach ($display->DisplayContent as $content) {
                        if (in_array($content->ContentName, array('APD', 'APDLegalText', 'APDLegalTextLagarde', 'DTL'))) {
                            $return[$i][$content->ContentName] = $content->Content;
                        }
                    }
                    $i++;
                }
            }
        }

        return $return;
    }
}
