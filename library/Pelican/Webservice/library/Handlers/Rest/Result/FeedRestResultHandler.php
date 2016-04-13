<?php

class Pelican_Rest_Server_Result_Feed extends Pelican_Rest_Server_Result_Abstract
{
    protected $_format;
    protected $_title;

    public function __construct($format)
    {
        $this->_format = $format;
    }

    public function setTitle($title)
    {
        $this->_title = $title;
    }

    public function handle($result)
    {
        $data = array(
            'title' => $this->_title||'',
            'link'	=> htmlentities(Pelican::$config["DOCUMENT_HTTP"].$_SERVER['REQUEST_URI']),
            'charset' => 'iso-8859-1',
            'entries' => $result
        );

        //print_r( $data );
        $feed = Zend_Feed::importArray($data, $this->_format );

        //print_r( $feed );
        return $feed->saveXML();
    }

}
