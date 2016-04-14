<?php

namespace PsaNdp\WebserviceConsumerBundle\Webservices;

use Doctrine\DBAL\Exception\InvalidArgumentException;

abstract class AbstractPsaSoapWebservice extends SoapConsumer  {

    const CLIENT = 'NDP';
    const BRAND = 'P';
    const DEFAULT_COUNTRY = 'FR';

    /**
     * @var array
     */
    protected $context = array();


    /**
     * @var array
     */
    protected $criteria = array();



    /**
     * @param string $member
     * @param string $name
     * @param mixed  $value
     *
     * @throws InvalidArgumentException
     * @return $this
     */
    protected function addArgumentValue($member, $name, $value)
    {
         if (!array_key_exists($member,$this->allowedArguments) || !in_array($name, $this->allowedArguments[$member]) ) {
            throw new InvalidArgumentException(sprintf('The argument %s is not allowed', $name));
        }

        $this->{$member}[$name] = $value;

        return $this;
    }

    /**
     * set the Default required parameters for context
     *
     * @return  ConfigurationEngineSelect
     */
    public function setDefaultContext()
    {
        $this->addContext('Country', self::DEFAULT_COUNTRY);

        return $this;
    }



    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return mixed
     */
    public function addContext($name, $value)
    {
        
        return $this->addArgumentValue('context', $name, $value);
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return mixed
     */

    public function addCriteria($name, $value)
    {
        
        return $this->addArgumentValue('criteria', $name, $value);
    }



}
