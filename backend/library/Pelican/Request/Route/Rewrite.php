<?php
include_once 'Abstract.php';

class Pelican_Request_Route_Rewrite extends Pelican_Request_Route_Abstract
{
    protected $get = array(
        'PAGE' => 'pid',
        'CONTENT' => 'cid',
    );

    public function eligible()
    {
        $return = true;

        return $return;
    }

    public function match()
    {
        $return = false;

        $external = '';
        $alias = '/'.$this->uri;
        $alternative = Pelican_Cache::fetch('Request/Redirect', array(
            '/'.$this->uri,
            $_SESSION[APP]['SITE_ID'],
        ));

        if (! $alternative) {
            $tmp = explode('/', $this->uri);
            if ($tmp[1] == '_') {
                $alternative = Pelican_Cache::fetch('Request/Redirect', array(
                    '/'.$tmp[0],
                    $_SESSION[APP]['SITE_ID'],
                ));
                $external = str_replace($tmp[0].'/_', '', $this->uri);
                $alias = '/'.$tmp[0];
            }
        }

        if (! empty($alternative['TYPE'])) {
            if (! empty($this->get[$alternative['TYPE']])) {
                $params[$this->get[$alternative['TYPE']]] = $alternative['ID'];
                $_GET[$this->get[$alternative['TYPE']]] = $alternative['ID'];

                $params['alias'] = $alias;
                $_GET['alias'] = $alias;

                if ($external) {
                    $params['external'] = $external;
                    $_GET['external'] = $external;
                }

                $params['controller'] = Pelican_Controller::getDefaultController();
                $params['action'] = Pelican_Controller::getDefaultAction();

                $return['route'] = 'rewrite';
                $return['params'] = $params;
            }
        }

        if (! $return && ! empty($alternative['code'])) {
            // redirection
            $return = $alternative;
        }

        /*
         * l'url claire porte la valeur de la langue
         */
        Pelican_Application::resetLang($alternative['LANGUE_ID']);

        return $return;
    }
}
