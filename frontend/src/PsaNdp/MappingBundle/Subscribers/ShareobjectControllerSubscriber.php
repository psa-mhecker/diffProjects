<?php


namespace PsaNdp\MappingBundle\Subscribers;

use PSA\MigrationBundle\Entity\Page\PsaPage;
use PsaNdp\CacheBundle\Exception\UseCachedResponseException;
use PsaNdp\MappingBundle\Controller\BlockController;
use PsaNdp\MappingBundle\Services\ShareServices\ShareObjectService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Event Subscriber for caching Open Orchestra NodeController showAction Response
 *
 * Class FrontNodeShowActionCacheSubscriber
 */
class ShareobjectControllerSubscriber implements EventSubscriberInterface
{
    /**
     * @var ShareObjectService
     */
    protected $share;

    /**
     * @param ShareObjectService  $share
     *
     * @internal param PageFinder $pageFinder
     */
    public function __construct(ShareObjectService $share)
    {
        $this->share = $share;
    }

    /**
     * Event to check if the node is already cached before calling open Orchestra NodeController showAction()
     * If the content is already cached, throw UseCachedResponseException to be catched and set a new Response with cached content
     *
     * @param FilterControllerEvent $event
     *
     * @throws UseCachedResponseException
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
        if(!($controller[0] instanceof BlockController)) {
            return;
        }

        /** @var PsaPage $node */
        $node = $this->share->getNode();

        $controller[0]->setNode($node);
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
            KernelEvents::CONTROLLER => array('onKernelController', 13)
        );
    }
}
