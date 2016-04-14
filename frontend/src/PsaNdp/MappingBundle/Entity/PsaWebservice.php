<?php

namespace PsaNdp\MappingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Itkg\ConsumerBundle\Document\ClientConfig;
use Itkg\ConsumerBundle\Model\ServiceConfig;

/**
 * PsaWebservice
 *
 * @ORM\Table(name="psa_liste_webservices")
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\PsaWebserviceRepository")
 */
class PsaWebservice extends ServiceConfig
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ws_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ws_name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     * keeping ws_url to be compatible with psa table structure ..
     *
     * @ORM\Column(name="ws_url", type="string", length=255, nullable=false)
     */
    protected $baseUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="proxy_host", type="string", nullable=true)
     */
    protected $proxyHost;

    /**
     * @var string
     *
     * @ORM\Column(name="proxy_port",type="string", nullable=true)
     */
    protected $proxyPort;

    /**
     * @var string
     *
     * @ORM\Column(name="proxy_login",type="string", nullable=true)
     */
    protected $proxyLogin;

    /**
     * @var string
     *
     * @ORM\Column(name="proxy_password", type="string", nullable=true)
     */
    protected $proxyPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="auth_login", type="string", nullable=true)
     */
    protected $authLogin;

    /**
     * @var string
     *
     * @ORM\Column(name="auth_password", type="string", nullable=true)
     */
    protected $authPassword;

    /**
     * @var string
     * @ORM\Column(name="response_type",type="string", nullable=true)
     */
    protected $responseType;

    /**
     * @var string
     * @ORM\Column(name="response_format",type="string", nullable=true)
     */
    protected $responseFormat;

    /**
     * Get wsId
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param  string $name
     *
     * @return PsaWebservice
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get $name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get baseUrl
     *
     * ${THROWS_DOC}
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @return string
     */
    public function getServiceKey(){

        return $this->name;
    }

    public function toOptions(){

        $options = parent::toOptions();
        $options['identifier'] = $this->name;
        $options['response_format'] = ($this->responseFormat)?$this->responseFormat:'json';
        $options['response_type']= $this->responseType;

        return $options;
    }

    public function getClientConfig(){
        if($this->clientConfig ==null){
            $this->initClientConfig();
        }

        return $this->clientConfig;
    }

    private function initClientConfig(){
        $this->clientConfig = new ClientConfig();
        $this->clientConfig
            ->setBaseUrl($this->baseUrl)
            ->setProxyHost($this->proxyHost)
            ->setProxyPort($this->proxyPort)
            ->setProxyLogin($this->proxyPort)
            ->setProxyPassword($this->proxyPassword)
            ->setAuthLogin($this->authLogin)
            ->setAuthPassword($this->authPassword)
            ->setTimeout(10)
        ;
    }


}
