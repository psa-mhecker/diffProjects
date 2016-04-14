<?php

namespace PsaNdp\LogBundle\Log;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class ConsumerLogger
 */
class ConsumerLogger extends AbstractLogger
{
    const APPLICATION_LOG_TYPE_WEB_SERVICES = 'web services';

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param string       $directory Path directory to save log. Ex /var/frontend/log
     * @param RequestStack $requestStack
     */
    public function __construct($directory, RequestStack $requestStack)
    {
        parent::__construct($directory, self::APPLICATION_LOG_TYPE_WEB_SERVICES);
        $this->request = $requestStack->getMasterRequest();
    }

    /**
     * @return int|string
     */
    protected function getSiteId()
    {
        $siteId = 0;
        if (!empty($this->request) && $this->request->get('siteId')) {
            $siteId = $this->request->get('siteId');
        } elseif (defined('APP') && $_SESSION[APP] && !empty($_SESSION[APP]['SITE_ID'])) {
            $siteId = $_SESSION[APP]['SITE_ID'];
        }

        return $siteId;
    }

    /**
     * @return bool
     */
    public function isBackend()
    {
        return empty($this->request);
    }

    /**
     * @return null|Request
     */
    public function getRequest()
    {
        $result = null;
        if (!empty($this->request)) {
            $result = $this->request;
        }

        return $result;
    }
}
