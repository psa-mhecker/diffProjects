<?php

namespace PsaNdp\WebserviceConsumerBundle\Adapter;

use Itkg\Core\Cache\Adapter\Redis;

/**
 * Class PsaRedis
 */
class PsaRedis extends Redis
{
    /**
     * @param mixed $config
     */
    public function __construct($config)
    {
        if (is_array($config)) {
            parent::__construct($config);
        } else {
            $default = explode(':', $config);

            $port = '';
            $host = '';
            if (count($default) === 3) {
                $port = array_pop($default);
                $host = implode(':', $default);
            } elseif (count($default) === 2) {
                $host = $default[0];
                $port = $default[1];
            }

            $this->config['default']['host'] = $host;
            $this->config['default']['port'] = $port;
        }
    }
}
