<?php
include_once 'Abstract.php';

class Pelican_Request_Route_Clearurl extends Pelican_Request_Route_Abstract
{
    public function eligible()
    {
        $return = ! empty($this->routes['clearurl']);

        return $return;
    }

    public function match()
    {
        $route = $this->routes['clearurl'];
        $return = '';
        if ($params = $route->matches($this->uri)) {
            if (! empty($params['cid']) || ! empty($params['pid'])) {
                $return['route'] = $route;
                $return['params'] = $params;
            }
        }

        return $return;
    }
}
