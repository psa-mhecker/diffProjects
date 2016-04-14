<?php
/**
 * User: Ayoub Hidri <ayoub.hidri@businessdecision.com>
 * Date: 21/07/15
 * Time: 13:59
 */

namespace PsaNdp\WebserviceConsumerBundle\Webservices;

use \InvalidArgumentException;
use Symfony\Component\HttpFoundation\RequestStack;
use PsaNdp\MappingBundle\Utils\SiteConfiguration;


class ConfigurationEngine extends SoapConsumer
{
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



    public function hasArgument($member, $name)
    {

        return isset($this->allowedArguments[$member]) && in_array($name, $this->allowedArguments[$member]);
    }


    /**
     * @param string $member
     * @param string $name
     * @param mixed  $value
     *
     * @throws InvalidArgumentException
     * @return $this
     */
    private function addArgumentValue($member, $name, $value)
    {
        if (!$this->hasArgument($member, $name)) {
            throw new InvalidArgumentException(sprintf('The argument %s is not allowed', $name));
        }

        $this->{$member}[$name] = $value;

        return $this;
    }

    /**
     * set the Default required parameters for context
     *
     * @param SiteConfiguration $siteConfiguration
     * @param RequestStack      $requestStack
     *
     * @return  ConfigurationEngineSelect
     */
    public function setDefaultContext(SiteConfiguration $siteConfiguration, RequestStack $requestStack = null)
    {

        $this->addContext('Client', self::CLIENT);
        $this->addContext('Brand', self::BRAND);
        $this->addContext('Date', date('Y-m-d'));
        $this->addContext('Country', self::DEFAULT_COUNTRY);

        if(null !== $requestStack && (($request = $requestStack->getCurrentRequest()) !== null)) {
            $request = $requestStack->getCurrentRequest();
            $siteId = $request->attributes->get('siteId');
            if(is_numeric($siteId)) {
                $languageCode = $request->attributes->get('language');
                $this->siteConfiguration = $siteConfiguration;
                $this->siteConfiguration->setSiteId($siteId);
                $this->siteConfiguration->loadConfiguration();
                $site = $this->siteConfiguration->getSite();
                $this->addContext('Country', $site->getCountryCode());
                if($this->hasArgument('context','LanguageID')) {
                    $this->addContext('LanguageID', $languageCode);
                }
            }

        }

        return $this;
    }



    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return ConfigurationEngineConfig
     */
    public function addContext($name, $value)
    {
        return $this->addArgumentValue('context', $name, $value);

    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return ConfigurationEngineConfig
     */

    public function addCriteria($name, $value)
    {
        return $this->addArgumentValue('criteria', $name, $value);
    }


    public function resetCriteria(){
        $this->criteria = [];
    }

}
