<?php
include_once 'Abstract.php';

class Pelican_Request_Route_Mvc extends Pelican_Request_Route_Abstract
{
    public function eligible()
    {
        $return = ($this->uri == '' || (substr($this->uri, 0, 2) == '_/' && ! is_null($this->routes)));

        return $return;
    }

    public function match()
    {
        $return = null;
        foreach ($this->routes as $name => $route) {
            if ($params = $route->matches($this->uri)) {
                $return ['route'] = $route;
                $return ['params'] = $params;
                break;
            }
        }

        return $return;
    }
}
