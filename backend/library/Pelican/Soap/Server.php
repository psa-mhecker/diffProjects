<?php
/**
 */
require_once 'Zend/Soap/Server.php';

class Pelican_Soap_Server extends Zend_Soap_Server
{
    /**
     * Attach a class to a server.
     *
     * Accepts a class name to use when handling requests. Any additional
     * arguments will be passed to that class' constructor when instantiated.
     *
     * See {@link setObject()} to set preconfigured object instances as request library/Handlers/Rest/Result.
     *
     * @param string $class Class Name which executes SOAP Requests at endpoint.
     *
     * @return Zend_Soap_Server
     *
     * @throws Zend_Soap_Server_Exception if called more than once, or if class
     *                                    does not exist
     */
    public function setClass($class, $namespace = '', $argv = null)
    {
        debug($_SERVER);

        return $this;

        if (isset($this->_class)) {
            require_once 'Zend/Soap/Server/Exception.php';
            throw new Zend_Soap_Server_Exception('A class has already been registered with this soap server instance');
        }

        if (!is_string($class)) {
            require_once 'Zend/Soap/Server/Exception.php';
            throw new Zend_Soap_Server_Exception('Invalid class argument ('.gettype($class).')');
        }

        if (!class_exists($class)) {
            require_once 'Zend/Soap/Server/Exception.php';
            throw new Zend_Soap_Server_Exception('Class "'.$class.'" does not exist');
        }

        $this->_class = $class;
        if (1 < func_num_args()) {
            $argv = func_get_args();
            array_shift($argv);
            $this->_classArgs = $argv;
        }

        return $this;
    }
}

class PelicanSoapServerException extends Exception
{
}
