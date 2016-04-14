<?php
    require_once 'Pelican/Exception.php';

    class Pelican_Exception_Security extends Pelican_Exception
    {
        const MESSAGE = 'User {%id%} is not available to use fonctionnality : {%class%:%fct%}';

        public function __construct($source, $user_id, $fonctionnalite, $code = 0)
        {
            parent :: __construct("");
            // bug dans php
            $this->code = $code;
            $this->message = str_replace('%id%', $user_id, self::MESSAGE);
            $this->message = str_replace('%fct%', $fonctionnalite, $this->message);
            $this->message = str_replace('%class%', get_class($this), $this->message);
        }
    }
