<?php

namespace PsaNdp\MappingBundle\Manager;

use FOS\HttpCache\Handler\TagHandler;
use OpenOrchestra\DisplayBundle\Manager\CacheableManager;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CacheableManager
 */
class PsaCacheableManager extends CacheableManager
{
    /**
     * @var string
     */
    protected $maxAgeParameter;

    /**
     * @param TagHandler $tagHandler
     * @param string     $maxAgeParameter
     */
    public function __construct(TagHandler $tagHandler, $maxAgeParameter)
    {
        parent::__construct($tagHandler);
        $this->maxAgeParameter = $maxAgeParameter;
    }

    /**
     * Set response max age
     *
     * @param Response $response
     * @param int      $maxAge
     */
    public function setResponseMaxAge(Response $response, $maxAge)
    {
        if (-2 === $maxAge) {
            $maxAge = $this->maxAgeParameter;
        }

        if (-1 === $maxAge) {
            $maxAge = 2629743;
        }

        if ($maxAge > 0) {
            // Add in max age and share max age for HttpCache
            $response->setMaxAge($maxAge);
            $response->setSharedMaxAge($maxAge);

            // Add Vary headers for HttpCache taking mobile recognition
            $response->setVary(array('x-ua-device x-psa-locality')); // beware ' ' as a separator is the only working fix
        }
    }


    /**
     * Set response cache headers used by CombinedHttpCache
     *
     * @param Response  $response
     * @param array     $cacheTags
     *
     * @return Response $response
     */
    public function setResponseCacheTags(Response $response, array $cacheTags)
    {
        $response->headers->set(
            'X-ITKG-Cache-Tags',
            implode(",", $cacheTags)
        );

        return $response;
    }

}
