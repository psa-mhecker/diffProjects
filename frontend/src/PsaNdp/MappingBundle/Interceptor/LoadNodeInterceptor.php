<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 05/03/15
 * Time: 18:44
 */

namespace PsaNdp\MappingBundle\Interceptor;


use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;

class LoadNodeInterceptor implements MethodInterceptorInterface
{
    public function intercept(MethodInvocation $invocation)
    {
        return $invocation->proceed();
    }
}
