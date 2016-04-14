<?php 

namespace PsaNdp\WebserviceConsumerBundle\Webservices;


class BoForms extends AbstractPsaSoapWebservice
{

    const BRAND = 'AP';
    
  
    protected $allowedArguments = array(
        'context' => array(
            'country',
            'brand',
        ),
    );

     public function setDefaultContext()
    {
        $this->addContext('brand', self::BRAND)
            ->addContext('country',self::DEFAULT_COUNTRY);
        
        return $this;
    }
    
    public function getInstanceById($id)
    {
        $parameters = array(
            'getInstanceByIdRequest' => array(
                'instanceId' => $id,
            )
        );

        return $this->call('getInstanceById', $parameters);
    }

    public function getInstances( )
    {

        $parameters = array(
            'getInstancesRequest' => $this->context,
        );
        $result = $this->call('getInstances', $parameters);

        return $result->getInstancesResponse->instances->instance;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'WS_BO_FORMS';
    }
}
