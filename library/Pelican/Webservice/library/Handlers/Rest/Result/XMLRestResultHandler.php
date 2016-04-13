<?php

class Pelican_Rest_Server_Result_Xml extends Pelican_Rest_Server_Result_Abstract
{
    public function handle($result)
    {
        if ($result instanceof SimpleXMLElement) {
            $response = $result->asXML();
        } elseif ($result instanceof DOMDocument) {
            $response = $result->saveXML();
        } elseif ($result instanceof DOMNode) {
            $response = $result->ownerDocument->saveXML($result);
        } elseif (is_array($result) || is_object($result)) {
            $response = $this->_handleStruct($result);
        } else {
            $response = $this->_handleScalar($result);
        }
        $response = ereg_replace('generator="zend"', 'generator="Pelican"', $response);

        return $response;
    }

}
