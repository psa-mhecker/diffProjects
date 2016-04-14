<?php

namespace PsaNdp\MappingBundle\Config;

use Symfony\Component\Config\ConfigCacheFactoryInterface;

/**
 * Basic implementation for ConfigCacheFactoryInterface
 * that will simply create an instance of ConfigCache.
 *
 * @author Matthias Pigulla <mp@webfactory.de>
 */
class ConfigCacheFactory implements ConfigCacheFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function cache($file, $callback)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException(sprintf('Invalid type for callback argument. Expected callable, but got "%s".', gettype($callback)));
        }
        $cache = new ConfigCache($file);
        if (!$cache->isFresh()) {
            call_user_func($callback, $cache);
        }

        return $cache;
    }
}
