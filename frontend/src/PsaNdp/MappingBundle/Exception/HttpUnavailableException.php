<?php

namespace PsaNdp\MappingBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class HttpUnavailableException extends HttpException
{
    protected $site;

    /**
     * @return mixed
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param mixed $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }
}
