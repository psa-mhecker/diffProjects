<?php

abstract class Pelican_Rest_Server_Result_Abstract
{
    protected $_functions;
    protected $_method;
    protected $_encoding;
    protected $_returnResponse;
    protected $_functionPrefix = 'service_';

    abstract public function handle($result);

    /**
    * Get XML encoding
    *
    * @return string
    */
    public function getEncoding()
    {
        return $this->_encoding;
    }

    public function returnResponse($flag = null)
    {
        if (null == $flag) {
            return $this->_returnResponse;
        }

        $this->_returnResponse = ($flag) ? true : false;

        return $this;
    }

    public function setContext($functions, $method)
    {
        $this->_functions = $functions;
        $this->_method = $method;
    }

    /**
    * Handle an array or object result
    *
    * @param array|object $struct Result Value
    * @return string XML Response
    */
    protected function _handleStruct($struct)
    {
        $function = $this->_functions[$this->_method];
        if ($function instanceof Zend_Server_Reflection_Method) {
            $class = $function->getDeclaringClass()->getName();
        } else {
            $class = false;
        }

        $method = $function->getName();

        $dom    = new DOMDocument('1.0', $this->getEncoding());
        if ($class) {
            $root   = $dom->createElement($class);
            $method = $dom->createElement(ereg_replace($this->_functionPrefix,'',$method));
            $root->appendChild($method);
        } else {
            $root   = $dom->createElement(ereg_replace($this->_functionPrefix,'',$method));
            $method = $root;
        }
        $root->setAttribute('generator', 'zend');
        $root->setAttribute('version', '1.0');
        $dom->appendChild($root);

        $this->_structValue($struct, $dom, $method);

        $struct = (array) $struct;
        if (!isset($struct['status'])) {
            $status = $dom->createElement('status', 'success');
            $method->appendChild($status);
        }

        return $dom->saveXML();
    }

    /**
    * Recursively iterate through a struct
    *
    * Recursively iterates through an associative array or object's properties
    * to build XML response.
    *
    * @param mixed $struct
    * @param DOMDocument $dom
    * @param DOMElement $parent
    * @return void
    */
    protected function _structValue($struct, DOMDocument $dom, DOMElement $parent, $parentKey='')
    {
        $struct = (array) $struct;

        foreach ($struct as $key => $value) {
            if ($value === false) {
                $value = 0;
            } elseif ($value === true) {
                $value = 1;
            }

            if (ctype_digit((string) $key)) {
                if (ereg('s$',$parentKey)) {
                    $key = ereg_replace('s$','', $parentKey);
                } else {
                    $key = 'key_' . $key;
                }
            }

            if (is_array($value) || is_object($value)) {
                $element = $dom->createElement($key);
                $this->_structValue($value, $dom, $element, $key);
            } else {
                $element = $dom->createElement($key);
                $element->appendChild($dom->createTextNode($value));
            }

            $parent->appendChild($element);
        }
    }

    /**
    * Handle a single value
    *
    * @param string|int|boolean $value Result value
    * @return string XML Response
    */
    protected function _handleScalar($value)
    {
        $function = $this->_functions[$this->_method];
        if ($function instanceof Zend_Server_Reflection_Method) {
            $class = $function->getDeclaringClass()->getName();
        } else {
            $class = false;
        }

        $method = $function->getName();

        $dom = new DOMDocument('1.0', $this->getEncoding());
        if ($class) {
            $xml = $dom->createElement($class);
            $methodNode = $dom->createElement($method);
            $xml->appendChild($methodNode);
        } else {
            $xml = $dom->createElement($method);
            $methodNode = $xml;
        }
        $xml->setAttribute('generator', 'zend');
        $xml->setAttribute('version', '1.0');
        $dom->appendChild($xml);

        if ($value === false) {
            $value = 0;
        } elseif ($value === true) {
            $value = 1;
        }

        if (isset($value)) {
            $element = $dom->createElement('response');
            $element->appendChild($dom->createTextNode($value));
            $methodNode->appendChild($element);
        } else {
            $methodNode->appendChild($dom->createElement('response'));
        }

        $methodNode->appendChild($dom->createElement('status', 'success'));

        return $dom->saveXML();
    }
}
