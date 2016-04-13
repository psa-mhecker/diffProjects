<?php
namespace Citroen\Service;

use Itkg\Service;
use Itkg\Soap\Client;
use Itkg\Helper\DataTransformer;

/**
 * Class Gamme des services wsGamme 
 *
 * @author Pierre Pottié <pierre.pottie@businessdecision.com>
 *
 */
class GammeVU extends Service
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
    public function getConfiguratorUrlList($requestModel, $responseClass, $mapping)
    {
        $return = array();
        $aOptions = $this->configuration->getParameters();
        
         if ($mapping) {
            $aOptions['classmap'] = $mapping;
        }
        try {
             $this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);
             
            $responseConfigurator = $this->client->call('getConfiguratorUrlList', $requestModel->__toXML());
 
            $i = 0;
            if ($responseConfigurator) {          
                
                foreach ($responseConfigurator->GetConfiguratorUrlListResult->UrlList->ConfiguratorUrl as $configuratorUrl) {
                    if ( !empty($configuratorUrl->Url) && !empty($configuratorUrl->Lcdv) ) {
                        $return[$configuratorUrl->Lcdv] = $configuratorUrl->Url;
                    }
                }
            }

        }

        catch (\Exception $exception) {
            
        }
        return $return;
    }

   

}
