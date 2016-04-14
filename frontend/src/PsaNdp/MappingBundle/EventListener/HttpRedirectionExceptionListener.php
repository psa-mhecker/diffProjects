<?php

namespace PsaNdp\MappingBundle\EventListener;

use OpenOrchestra\FrontBundle\EventSubscriber\KernelExceptionSubscriber;
use OpenOrchestra\ModelInterface\Repository\ReadNodeRepositoryInterface;
use PSA\MigrationBundle\Entity\Page\PsaPage;
use PsaNdp\MappingBundle\Exception\HttpRedirectionException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * Class HttpNotFoundExceptionListener.
 */
class HttpRedirectionExceptionListener extends KernelExceptionSubscriber
{
    /**
     * @var ReadNodeRepositoryInterface
     */
    protected $nodeRepository;

    /**
     * @var PsaPage
     */
    protected $node;

    /**
     * @param ReadNodeRepositoryInterface $nodeRepository
     */
    public function __construct(ReadNodeRepositoryInterface $nodeRepository)
    {
        $this->nodeRepository = $nodeRepository;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     *
     * @throws \Exception
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof HttpRedirectionException &&  (Response::HTTP_MOVED_PERMANENTLY == $exception->getStatusCode())) {
            /** @var \PSA\MigrationBundle\Entity\PsaRewrite $rewrite */
            $rewrite = $exception->getRedirection();
            if($rewrite->isExternal())
            {
                $event->setResponse(new RedirectResponse($rewrite->getExternalUrl(), Response::HTTP_MOVED_PERMANENTLY));
            } else {
                /** @var PsaPage $node */
                $node = $this->nodeRepository->findOnePublishedByNodeIdAndLanguageAndSiteIdInLastVersion(
                    $rewrite->getRewriteId(),
                    $rewrite->getLangue()->getLangueCode(),
                    $rewrite->getSiteId()
                );

                if ($node instanceof PsaPage) {
                    $event->setResponse(new RedirectResponse($node->getVersion()->getPageClearUrl(), Response::HTTP_MOVED_PERMANENTLY));
                }
            }

        }
    }
}
