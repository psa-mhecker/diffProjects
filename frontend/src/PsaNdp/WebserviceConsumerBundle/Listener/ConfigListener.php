<?php

namespace PsaNdp\WebserviceConsumerBundle\Listener;

use Itkg\Consumer\Client\SoapClient;
use Itkg\Consumer\Event\ServiceEvent;
use Itkg\Consumer\Event\ServiceEvents;
use Itkg\Consumer\Service\Service;
use Itkg\Consumer\Service\ServiceConfigurableInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Itkg\Consumer\Client\RestClient;

/**
 * Class ConfigListener
 * @package Itkg\ConsumerBundle\Listener
 */
class ConfigListener implements EventSubscriberInterface
{

    /**
     * @var array
     */
    protected $options;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {

        $this->options = $options;
    }


    /**
     * @param ServiceEvent $event
     */
    public function onServiceRequest(ServiceEvent $event)
    {
        /** @var Service $service */
        $service = $event->getService();

        if (!$service instanceof ServiceConfigurableInterface) {
            return;
        }
        if(empty($this->options)) {
            return;
        }
        if( $service->getClient() instanceof SoapClient)
        {
           return;
        }
        if( $service->getClient() instanceof RestClient)
        {
            $this->overrideRestRequest($service);
        }


    }

    protected function overrideSoapRequest($service)
    {
        $options =  $service->getClient()->getNormalizedOptions();
        /** @var Request $request */
        $request = $service->getRequest();
        $requestParameters = [];
        $requestParameters['login'] = $this->options['auth_login'];
        $requestParameters['password'] = $this->options['auth_login'];
        $url =rtrim($options['base_url'],'/').'/'.$request->getRequestUri();
        $relayParameters = array('url'=>$url );
        $relayUrl = $this->options['base_url'].$this->options['uri'].'?'.http_build_query($relayParameters);
        $options['base_url'] =$relayUrl;
        $options['auth_login'] =$this->options['auth_login'];
        $options['auth_password'] =$this->options['auth_password'];
        $wrapRequest = Request::create(
            '',
            $request->getMethod(),
            $request->request->all()
        );
        $service->setRequest($wrapRequest);
        $service->getClient()->setNormalizedOptions($options);
    }

    protected function overrideRestRequest($service)
    {
        $options =  $service->getClient()->getNormalizedOptions();
        /** @var Request $request */
        $request = $service->getRequest();

        $url =rtrim($options['base_url'],'/').'/'.$request->getRequestUri();
        $relayParameters = array('url'=>$url);

        $wrapRequest = Request::create(
            $this->options['base_url'].$this->options['uri'],
            $request->getMethod(),
            $relayParameters
        );
        $service->setRequest($wrapRequest);
        $service->getClient()->setNormalizedOptions($this->options);
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            ServiceEvents::REQUEST => array('onServiceRequest', 20)
        );
    }
}
