<?php
    require_once 'Pelican/Exception.php';

    class Pelican_Exception_UnsupportedParameter extends Pelican_Exception
    {
        public function __construct($msg)
        {
            parent :: __construct($msg);
            // bug dans php
            $this->code = $code;
        }
    }
