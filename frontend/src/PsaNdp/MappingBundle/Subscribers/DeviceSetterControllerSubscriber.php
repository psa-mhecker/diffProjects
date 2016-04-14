<?php
namespace PsaNdp\MappingBundle\Subscribers;

use Itkg\CombinedHttpCache\Client\RedisClient;
use PsaNdp\MappingBundle\Utils\UserAgent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerResolver;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Event Subscriber for caching Open Orchestra NodeController showAction Response
 *
 * Class FrontNodeShowActionCacheSubscriber
 * @package PsaNdp\CacheBundle\Subscribers
 */
class DeviceSetterControllerSubscriber implements EventSubscriberInterface
{
    /** @var ControllerResolver */
    protected $resolver;

    /**
     * @var RedisClient
     */
    protected $cacheClient;

    /**
     * DeviceSetterControllerSubscriber constructor.
     * @param ControllerResolver $resolver
     * @param RedisClient $cacheClient
     *
     */
    public function __construct(ControllerResolver $resolver, RedisClient $cacheClient)
    {
        $this->resolver = $resolver;
        $this->cacheClient = $cacheClient;
    }

    /**
     *
     * @param FilterControllerEvent $event
     *
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * $controller peut être une classe ou une closure.
         * Si c'est une classe, elle est au format array
         */
        if (!is_array($controller)) {
            return;
        }
        $request = $event->getRequest();
        if(!$request->headers->has('x-ua-device') && $controller[0] instanceof Controller ) {
            $config = $controller[0]->get('service_container')->getParameter('user_agent');
            $userAgent = new UserAgent($request, $config, $this->cacheClient);
            // Set device type header for Open Orchestra
            $device =   $userAgent->getDeviceType();
            if($request->query->get('isMobile') === 'true') {
                $device = 'mobile';
            }
            if($request->query->get('isTablet') === 'true') {
                $device = 'tablet';
            }
            $request->headers->set('x-ua-device',$device );

        }
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {

        // Priority Should be higher than the FrontNodeShowActionCacheSubscriber
        return array(
            KernelEvents::CONTROLLER => array('onKernelController', 11)
        );
    }
}
