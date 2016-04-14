<?php

namespace PsaNdp\MappingBundle\EventListener;

use OpenOrchestra\FrontBundle\EventSubscriber\KernelExceptionSubscriber;
use OpenOrchestra\ModelInterface\Repository\ReadNodeRepositoryInterface;
use PsaNdp\MappingBundle\Exception\HttpUnavailableException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Guzzle\Http\Client;

/**
 * Class HttpNotFoundExceptionListener.
 */
class HttpUnavailableExceptionListener extends KernelExceptionSubscriber
{
    /**
     * @var ReadNodeRepositoryInterface
     */
    protected $nodeRepository;

    /**
     * @var array
     */
    protected $options;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     *
     * @throws \Exception
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof HttpUnavailableException &&  (Response::HTTP_SERVICE_UNAVAILABLE == $exception->getStatusCode())) {
            $site = $exception->getSite();
            $url = $site->getSiteMaintenanceUrl();
            $content = '503 Service Unavailable';
            if (!empty($url)) {
                $content = $this->get503Content($url);
            }

            $response = new Response();
            $response->setContent($content);
            $response->setStatusCode(Response::HTTP_SERVICE_UNAVAILABLE, '503 Service Unavailable');

            $event->setResponse($response);
        }
    }
    /**
     * @param $sourceUrl
     *
     * @return string
     */
    private function get503Content($sourceUrl)
    {
        $client = new Client();
        $response = $client->get($sourceUrl, null, $this->getHttpClientOptions())->send();

        return $response->getBody();
    }

    /**
     * @return array
     */
    private function getHttpClientOptions()
    {
        $options = $this->options;
        $clientOptions = [];
        if (!empty($options) && isset($options['isActive']) && $options['isActive']) {
            $auth = '';
            if (!empty($options['login']) && !empty($options['password'])) {
                $auth = $options['login'].':'.$options['password'].'@';
            }
            $proxy = $options['protocole'].'://'.$auth.$options['host'].':'.$options['port'];
            $clientOptions['proxy'] = $proxy;
        }

        return $clientOptions;
    }
}
