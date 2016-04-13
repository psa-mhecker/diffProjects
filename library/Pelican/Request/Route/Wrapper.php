<?php
include_once 'Abstract.php';

class Pelican_Request_Route_Wrapper extends Pelican_Request_Route_Abstract
{

    public $alias = array(
        'magento' => array(
            'alias' => 'shop' ,
            'hosts' => 'localhost/magento'));

    public function eligible()
    {
        foreach ($this->alias as $key => $value) {
            if (substr($this->uri, 0, strlen($value['host'])) == $value['host']) {
                $this->route = $key;

                return true;
            }
        }

        return false;
    }

    public function match()
    {
        if ($this->route) {
            $params['type'] = $this->route;
            $params['url'] = substr($this->uri, strlen($this->alias[$this->route]['host']), strlen($this->uri));
            $params['root'] = 'library';
            $params['directory'] = 'Pelican/Controller';
            $params['controller'] = 'External/' . ucfirst($this->route);
            $params['action'] = 'index';
            $params['host'] = $this->alias[$this->route]['host'];

            $return['route'] = 'wrapper';
            $return['params'] = $params;
        }

        return $return;
    }
}
