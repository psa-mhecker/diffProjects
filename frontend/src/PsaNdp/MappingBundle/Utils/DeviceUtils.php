<?php

namespace PsaNdp\MappingBundle\Utils;

use Symfony\Component\HttpFoundation\RequestStack;
use PsaNdp\MappingBundle\Subscribers\DeviceSetterControllerSubscriber;

/**
 * Class DeviceUtils
 * @package PsaNpd\MappingBundle\Utils
 */
class DeviceUtils
{
    /** @var RequestStack */
    protected $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Return is mobile or not
     *
     * @return boolean
     */
    public function isMobile()
    {
        $device = $this->getDevice();

        return (!is_null($device) && 'mobile' === $device);
    }

    /**
     * Return is tablet or not
     *
     * @return boolean
     */
    public function isTablet()
    {
        $device = $this->getDevice();

        return (!is_null($device) && 'tablet' === $device);
    }

    /**
     * @return string
     */
    private function getDevice()
    {
        $request = $this->requestStack->getCurrentRequest();

        return $request->headers->get('x-ua-device');
    }
}
