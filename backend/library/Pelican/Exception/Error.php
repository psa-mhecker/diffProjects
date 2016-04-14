<?php
    require_once 'Pelican/Exception.php';

    class Pelican_Exception_Error extends Pelican_Exception
    {
        public function __construct($msg, $code = 0)
        {
            parent :: __construct($msg);
            // bug dans php
            $this->code = $code;
        }
    }
