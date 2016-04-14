<?php
namespace ParamsApi\v1;
use Luracast\Restler\iAuthenticate;

class Auth implements iAuthenticate
{

    function __isAllowed()
    {
        return isset($_GET['key']) && $_GET['key'] == \Pelican::$config["API"]['PARAMS']['AUTH']['KEY'] ? TRUE : FALSE;
    }

    public function __getWWWAuthenticateString()
    {
        return 'Query name="key"';
    }

    function key()
    {
        return Auth::KEY;
    }
}