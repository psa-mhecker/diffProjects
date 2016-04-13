<?php
    /**
    * __DESC__
    *
    * @package Pelican
    * @subpackage Acl
    * @author __AUTHOR__
    */
    require_once 'Pelican/Exception.php';

    /**
    * __DESC__
    *
    * @package Pelican
    * @subpackage Acl
    * @author __AUTHOR__
    */
    class Pelican_Acl_Exception extends Exception
    {
        /**
        * __DESC__
        *
        * @access public
        * @param __TYPE__ $msg __DESC__
        * @param string $code (option) __DESC__
        * @return __TYPE__
        */
        public function __construct($msg, $code = 0)
        {
            parent::__construct($msg);
            // bug dans php
            $this->code = $code;
        }
    }
