<?php
    require_once 'Pelican/Exception.php';

    class Pelican_Exception_NullParameter extends Pelican_Exception
    {
        const MESSAGE = 'Parameter {%param%} is NULL';

        public function __construct($param, $code = 0)
        {
            parent :: __construct();
            // bug dans php
            $this->code = $code;
            $this->message = str_replace('%param%', $param, Pelican_Exception_NullParameter::MESSAGE);
        }
    }
