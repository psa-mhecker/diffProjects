<?php
include_once ('Pelican/Request/Route/Abstract.php');

class Citroen_Request_Route_Sitemap extends Pelican_Request_Route_Abstract
{

    public function eligible()
    {
        $return = (substr_count($this->uri, 'sitemap.') ? true : false);

        return $return;
    }

    public function match()
    {

        $params['root'] = 'library';
        $params['directory'] = 'Citroen/Controller';
        $params['controller'] = 'Sitemap';
        $params['action'] = 'index';

        $return['route'] = 'sitemap';
        $return['params'] = $params;

        return $return;
    }
}
