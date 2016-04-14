<?php

namespace PsaNdp\LogBundle\EventSubscriber;

use Itkg\Consumer\Client\RestClient;
use Itkg\Consumer\Client\SoapClient;
use Itkg\Consumer\Event\ServiceEvent;
use Itkg\Consumer\Event\ServiceEvents;
use Itkg\Consumer\Listener\LoggerListener;
use Itkg\Consumer\Service\ServiceInterface;
use PsaNdp\LogBundle\Log\ConsumerLogger;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class LogConsumerSubscriber
 */
class LogConsumerSubscriber extends LoggerListener implements EventSubscriberInterface
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var ConsumerLogger
     */
    protected $consumerLogger;

    /**
     * @param ConsumerLogger $consumerLogger
     */
    public function __construct(ConsumerLogger $consumerLogger)
    {
        $this->consumerLogger = $consumerLogger;
        $this->logger = $consumerLogger->getLogger();
    }

    /**
     * @param ServiceEvent $event
     */
    public function onServiceSuccess(ServiceEvent $event)
    {
        $service = $event->getService();

        if ($service instanceof ServiceInterface) {
            $responseSize = strlen($service->getResponse()->getContent());
            if ($service->getClient() instanceof SoapClient) {
//                $responseSize = strlen(json_encode($service->getResponse()->getDeserializedContent()));
                $responseSize = '?'; // vue avec jérome : il est diffcile d'obtenir la taille reelle de la reponse
            }

            $context = $this->abstractContextConsumer($service);
            $context = array_merge($context, array(
                'response_content' => $responseSize,
                'parameters' => $this->getParameters($service),
                'time' => $service->getTime()
            ));

            $this->logger->info('Response success', $context);
        }
    }

    /**
     * @param ServiceEvent $event
     */
    public function onServiceException(ServiceEvent $event)
    {
        $service = $event->getService();
        $exception = $service->getException();
        if ($service instanceof ServiceInterface) {

            $response = $service->getResponse()->getContent();
            if ($service->getClient() instanceof SoapClient) {
                $response = json_encode($service->getResponse()->getDeserializedContent());
            }

            $context = $this->abstractContextConsumer($service);
            $context = array_merge($context, array(
                'trace' => $exception->getTraceAsString(),
                'response_content' => $response,
                'parameters' => $this->getParameters($service),
                'time' => $service->getTime(),
            ));

            $this->logger->error($exception->getMessage(), $context);
        }
    }

    /**
     * @param ServiceInterface $service
     *
     * @return string
     */
    protected function getUrl(ServiceInterface $service)
    {
        $url = $service->getRequest()->getUri();
        if ($service->getClient() instanceof SoapClient) {
            $url = $service->getClient()->location;
        }

        if ($service->getClient() instanceof RestClient) {
            $options = $service->getClient()->getNormalizedOptions();

            if (isset($options['base_url'])) {
                $url = $options['base_url'];
            }
        }

        return $url;
    }

    /**
     * @param ServiceInterface $service
     *
     * @return null|string
     */
    protected function getParameters(ServiceInterface $service)
    {
        $parameters = $service->getRequest()->getQueryString();
        if ($service->getClient() instanceof SoapClient) {
            $parameters = json_encode($this->getSoapParameters($service->getRequest()->request->all()));
        }

        return $parameters;
    }

    /**
     * @param $parameters
     *
     * @return array
     */
    protected function getSoapParameters($parameters)
    {
        if (!is_array($parameters)) {
            return $parameters;
        }

        if (count($parameters) > 0) {
            $result = array();
            foreach ($parameters as $key => $parameter) {
                if (is_array($parameter) && count($parameter) > 0) {
                    $result = array_merge($result, $this->getSoapParameters($parameter));
                } elseif (!is_array($parameter)) {
                    $result[$key] = $parameter;
                }
            }

            return $result;
        }
    }

    /**
     * @param ServiceInterface $service
     *
     * @return string
     */
    protected function getApplication(ServiceInterface $service)
    {
        $application = 'NDP/FO/'.$service->getIdentifier();
        if ($this->consumerLogger->isBackend()) {
            $application = 'NDP/BO/'.$service->getIdentifier();
        }

        return $application;
    }

    /**
     * @param ServiceInterface $service
     *
     * @return array
     */
    protected function abstractContextConsumer(ServiceInterface $service)
    {
        $result = array(
            'application' => $this->getApplication($service),
            'brand_id' => 'AP',
            'identifier' => $service->getIdentifier(),
            'url' => $this->getUrl($service),
            'referer' => ''
        );

        if (null !== session_id()) {
            $result['session_id'] = session_id();
        }

        if (null !== $this->consumerLogger->getRequest()) {
            $result['referer'] = $this->consumerLogger->getRequest()->geturi();
        }

        return $result;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            ServiceEvents::RESPONSE  => 'onServiceSuccess',
            ServiceEvents::EXCEPTION => 'onServiceException',
        );
    }
}
