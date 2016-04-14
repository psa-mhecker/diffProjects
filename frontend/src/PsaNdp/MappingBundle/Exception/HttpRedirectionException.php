<?php

namespace PsaNdp\MappingBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class HttpRedirectionException extends HttpException
{
    protected $redirection;

    /**
     * @return mixed
     */
    public function getRedirection()
    {
        return $this->redirection;
    }

    /**
     * @param mixed $redirection
     */
    public function setRedirection($redirection)
    {
        $this->redirection = $redirection;
    }
}
