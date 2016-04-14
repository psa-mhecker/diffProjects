<?php
/**
 */
require_once 'Zend/Soap/AutoDiscover.php';

/**
 * PelicanSoapAutoDiscover.
 *
 * @category   Pelican
 */
class Pelican_Soap_AutoDiscover extends Zend_Soap_AutoDiscover
{
    protected $_oConnection;
    protected $_availableServices;
    protected $_wsdl;

    /**
     * Set the Class the SOAP server will use.
     *
     * @param string $class     Class Name
     * @param string $namespace Class Namspace - Not Used
     * @param array  $argv      Arguments to instantiate the class - Not Used
     */
    public function setClass($class, $namespace = '', $argv = null)
    {
        $function_prefix = 'service_';

        // Ajout de la méthode d'authentification
        //$this->addFunction('UsernameToken');

        $uri = $this->getUri();
        $this->getAvailableServices($namespace);
        //print_r($this->_availableServices);

        if (!$this->_wsdl) {
            $wsdl = new Zend_Soap_Wsdl($class, $uri, $this->_strategy);
        } else {
            $wsdl = $this->_wsdl;
        }

        $port = $wsdl->addPortType($class.'Port');
        $binding = $wsdl->addBinding($class.'Binding', 'tns:'.$class.'Port');

        $wsdl->addSoapBinding($binding, $this->_bindingStyle['style'], $this->_bindingStyle['transport']);
        $wsdl->addService($class.'Service', $class.'Port', 'tns:'.$class.'Binding', $uri);
        foreach ($this->_reflection->reflectClass($class)->getMethods() as $method) {
            $method_index_name = str_replace($function_prefix, '', $method->getName());

            if (isset($this->_availableServices[$method->getName()])) {

                /* <wsdl:portType>'s */
                $portOperation = $wsdl->addPortOperation($port, $method_index_name, 'tns:'.$method_index_name.'Request', 'tns:'.$method_index_name.'Response');
                $desc = $method->getDescription();
                if (strlen($desc) > 0) {
                    /* @todo check, what should be done for portoperation documentation */
                    //$wsdl->addDocumentation($portOperation, $desc);
                }
                /* </wsdl:portType>'s */

                $this->_functions[] = $method_index_name;

                $selectedPrototype = null;
                $maxNumArgumentsOfPrototype = -1;
                foreach ($method->getPrototypes() as $prototype) {
                    $numParams = count($prototype->getParameters());
                    if ($numParams > $maxNumArgumentsOfPrototype) {
                        $maxNumArgumentsOfPrototype = $numParams;
                        $selectedPrototype = $prototype;
                    }
                }

                if ($selectedPrototype != null) {
                    $prototype = $selectedPrototype;
                    $args = array();
                    foreach ($prototype->getParameters() as $param) {
                        $args[$param->getName()] = $wsdl->getType($param->getType());
                    }

                    $message = $wsdl->addMessage($method_index_name.'Request', $args);
                    if (strlen($desc) > 0) {
                        //$wsdl->addDocumentation($message, $desc);
                    }
                    if ($prototype->getReturnType() != "void") {
                        $returnName = 'return';
                        $message = $wsdl->addMessage($method_index_name.'Response', array($returnName => $wsdl->getType($prototype->getReturnType())));
                    }

                    /* <wsdl:binding>'s */
                    $operation = $wsdl->addBindingOperation($binding, $method_index_name,  $this->_operationBodyStyle, $this->_operationBodyStyle);
                    $wsdl->addSoapOperation($operation, $uri.'#'.$method_index_name);
                    /* </wsdl:binding>'s */
                }
            }
        }
        $this->_wsdl = $wsdl;
    }

    protected function getAvailableServices($login = '')
    {
        $data = Pelican_Cache::fetch('Webservice/User/Action', array($login, '10'));
        //debug($data);
        //die;
        $this->_availableServices = array();

        if (is_array($data)) {
            foreach ($data as $k => $v) {
                //print_r($v);
                if ($v['OUTPUT_SOAP'] == 1) {
                    $this->_availableServices[$v['WEBSERVICE_ACTION_METHOD']] = $v;
                }
            }
        }
        //die;
    }
}
