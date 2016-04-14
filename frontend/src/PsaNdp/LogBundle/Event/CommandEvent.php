<?php

namespace PsaNdp\LogBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class CommandEvent
 */
class CommandEvent extends Event
{
    /**
     * @var string
     */
    protected $siteId;

    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * @param string $siteId
     * @param array  $parameters
     */
    public function __construct($siteId, array $parameters = array())
    {
        $this->siteId = $siteId;
        $this->parameters = $parameters;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return string
     */
    public function getSiteId()
    {
        return $this->siteId;
    }
}
