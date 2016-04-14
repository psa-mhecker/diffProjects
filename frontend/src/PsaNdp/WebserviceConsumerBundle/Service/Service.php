<?php

namespace PsaNdp\WebserviceConsumerBundle\Service;

use Itkg\Consumer\Service\Service as BaseService;
use Symfony\Component\HttpFoundation\Request;
use Itkg\Consumer\Response;
use Itkg\Consumer\Event\ServiceEvents;
use Itkg\Consumer\Event\ServiceEvent;

class Service extends BaseService
{
    protected $bypassCache = false;
    protected $time;

    /**
     * Hash key getter.
     *
     * Hash key is null if service cache is disabled
     *
     * @return string
     */
    public function getHashKey()
    {
        if (null === $this->hashKey) {
            $this->hashKey =
                sprintf('WEBSERVICE_%s_%s_%s',
                    $this->getIdentifier(),
                    $this->getCountry(),
                    $this->getParameterHash()
            );
        }

        return $this->hashKey;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return  strtr(parent::getIdentifier(), ' ', '_');
    }

    /**
     * @return string
     */
    private function getParameterHash()
    {
        return  md5(
            sprintf(
                '%s_%s_%s',
                $this->request->getContent(),
                $this->request->getUri(),
                json_encode($this->getParameters())
            ));
    }

    private function getParameters()
    {
        $params = $this->request->query->all();
        $params = array_merge($params,  $this->request->request->all());

        return $params;
    }

    /**
     * Send request using current client.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function sendRequest(Request $request, Response $response = null)
    {
        $this->hashKey = null;
        $this->request = $request;
        $this->response = (null === $response) ? new Response() : $response;
        $this->setIsLoaded(false);
        $event = new ServiceEvent($this);

        $this->eventDispatcher->dispatch(ServiceEvents::REQUEST, $event);

        if ($this->bypassCache) {
            $this->setIsLoaded(false);
        }

        // don't check content but check if cache set isLoaded Flag  or not !!
        $startTime = microtime(true);
        if (!$this->isLoaded()) {
            try {
                $this->client->sendRequest($this->request, $this->response);
            } catch (\Exception $e) {
                $this->exception = $e;
                $endTime = microtime(true);
                $time = ($endTime - $startTime)*1000;
                $this->time = sprintf('%d', $time);
                $this->eventDispatcher->dispatch(ServiceEvents::EXCEPTION, $event);

                throw $e;
            }
        }

        $endTime = microtime(true);
        $time = ($endTime - $startTime)*1000;
        $this->time = sprintf('%d', $time);
        $this->eventDispatcher->dispatch(ServiceEvents::RESPONSE, $event);

        return $this;
    }

    /**
     * @param bool $bypassCache
     *
     * @return SoapConsumer
     */
    public function setBypassCache($bypassCache)
    {
        $this->bypassCache = $bypassCache;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        $return = 'ALL';
        $country = $this->searchCountry($this->getParameters());

        if (!empty($country)) {
            $return = $country;
        }

        return $return;
    }

    protected function searchCountry($params)
    {
        $return = null;
        $key = array_keys($params);
        $size = sizeOf($key);

        for ($i = 0, $continue = true; ($i < $size) && $continue; ++$i) {
            if (strtolower($key[$i]) == 'country' || strtolower($key[$i]) == 'countries') {
                $return = $params[$key[$i]];
                $continue = false;
            }
            if (is_array($params[$key[$i]])) {
                $return = $this->searchCountry($params[$key[$i]]);
                $continue = ($return === null);
            }
        }

        return $return;
    }
}
