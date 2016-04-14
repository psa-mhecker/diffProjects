<?php

require_once Pelican::$config['LIB_ROOT']."/External/Facebook/facebook.php";

class Citroen_Facebook extends Facebook
{
    protected function getHttpProtocol()
    {
        if ($this->trustForwarded && isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            if ($_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
                return 'https';
            }

            return 'http';
        }
        /* apache + variants specific way of checking for https */
        if (isset($_SERVER['HTTPS']) &&
            ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] == 1)) {
            return 'https';
        }
        /* nginx way of checking for https */
        if (isset($_SERVER['SERVER_PORT']) &&
            ($_SERVER['SERVER_PORT'] === '443')) {
            return 'https';
        }
        if (strpos($_SERVER['REMOTE_ADDR'], '172.21.') !== false || strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.com' !== false)) {
            return 'https';
        }

        return 'http';
    }

    protected function getHttpHost()
    {
        if ($this->trustForwarded && isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            return $_SERVER['HTTP_X_FORWARDED_HOST'];
        }
        if (strpos($_SERVER['REMOTE_ADDR'], '172.21.') !== false || strpos(@$_SERVER['HTTP_CLIENT_HOST'], '.citroen.com' !== false)) {
            return $_SERVER['HTTP_CLIENT_HOST'];
        }

        return $_SERVER['HTTP_HOST'];
    }
}
