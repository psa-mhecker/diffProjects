<?php
include_once ('Pelican/Request/Route/Abstract.php');

class Citroen_Request_Route_CriteoCatalogFeed extends Pelican_Request_Route_Abstract
{

    public function eligible()
    {
        return $this->uri === 'criteocatalogfeed.xml' ? true : false;
    }

    public function match()
    {
        $return['route'] = 'sitemap';
        $return['params'] = array(
            'root' => 'library',
            'directory' => 'Citroen/Controller',
            'controller' => 'CriteoCatalogFeed',
            'action' => 'index',
        );
        return $return;
    }
}
