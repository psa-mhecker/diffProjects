<?php

//include_once ('Abstract.php');
include_once ('Pelican/Request/Route/Abstract.php');

class Citroen_Request_Route_FormPerso extends Pelican_Request_Route_Abstract {

    protected $_params = array();

    public function eligible() {
        $return =($this->uri !='' && substr( $this->uri, 0, 5) == 'forms' && null != $this->routes['FormPerso']);
        //var_dump(substr( $this->uri, 0, 5));
        return $return;
    }

    public function match() {
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
