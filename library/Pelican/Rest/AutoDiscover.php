<?php
require_once 'Zend/Rest/Server.php';

class Pelican_Rest_Autodiscover extends Pelican_Rest_Server
{
    protected $_function_prefix = 'service_';

    public function handle($request = false)
    {
        $this->_oConnection = Pelican_Db::getInstance();
        if (!$request) {
            $request = $_REQUEST;
        }

        $this->_method = $request['method'];

        $service = new Pelican_Webservice();
        $result = $service->autodiscover($request['namespace']);

        // Gestion du résultat
        if ( isset($this->_resultHandler) ) {
            $this->_resultHandler->setContext($this->_functions, $this->_method);
            $response = $this->_resultHandler->handle($result);
        }

        if (!$this->returnResponse()) {
            if (!headers_sent()) {
                foreach ($this->_headers as $header) {
                    header($header);
                }
            }
        }
        echo $response;

        return;
    }

}

class PelicanRestAutoDiscoverException extends Exception{}
