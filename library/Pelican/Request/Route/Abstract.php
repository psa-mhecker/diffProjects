<?php

abstract class Pelican_Request_Route_Abstract
{

    public function __construct($uri, $routes = null)
    {
        $this->uri = $uri;
        $this->routes = $routes;
    }

    public function process()
    {
        if ($this->eligible()) {
            return $this->match();
        } else {
            return false;
        }
    }

    abstract public function eligible();

    abstract public function match();

}
