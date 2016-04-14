<?php

namespace PsaNdp\MappingBundle\Utils;

use PSA\MigrationBundle\Entity\Site\PsaSite;
use PSA\MigrationBundle\Repository\PsaSiteRepository;
use PsaNdp\MappingBundle\Repository\NationalParameterRepository;
use JMS\Serializer\SerializerInterface;

class SiteConfiguration
{
    protected $siteRepository;
    protected $nationalParameterRepository;
    protected $nationalParameters;
    private $siteId;
    private $site;

    /**
     * @param PsaSiteRepository           $siteRepository
     * @param NationalParameterRepository $nationalParameterRepository
     * @param SerializerInterface         $serializer
     */
    public function __construct(
        PsaSiteRepository $siteRepository,
        NationalParameterRepository $nationalParameterRepository,
        SerializerInterface $serializer
    ) {
        $this->siteRepository = $siteRepository;
        $this->nationalParameterRepository = $nationalParameterRepository;
        $this->serializer = $serializer;
    }

    /**
     *
     */
    public function loadConfiguration()
    {
        if (!empty($this->siteId)) {
            $nationalParameter = $this->nationalParameterRepository->findOneBySiteId($this->siteId);
            $this->site = $this->siteRepository->findOneById($this->siteId);
            $this->nationalParameters = $this->serializer->deserialize($nationalParameter->getParameters(), 'array', 'json');
        }
    }

    /**
     * @param $siteId
     *
     * @return $this
     */

    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;

        return $this;
    }

    /**
     * @return PsaSite
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param $paramaterName
     * @throws InvalidArgumentException
     * @return mixed
     */
    public function getNationalParameter($paramaterName)
    {
        if (array_key_exists($paramaterName, $this->nationalParameters)) {
            return $this->nationalParameters[$paramaterName];
        }

        throw new \InvalidArgumentException(sprintf(
            'invalid %s parameter name',
            $paramaterName
        ));
    }

    public function getParameter($parameterName)
    {
        return $this->site->getParameter($parameterName);
    }

    /**
     * @return mixed
     */
    public function getNationalParameters()
    {
        return $this->nationalParameters;
    }

    public function getParameters()
    {
        $return = [];
        /* @var \PSA\MigrationBundle\Entity\Site\PsaSiteParameter $param */
        foreach ($this->site->getParameters() as $param) {
            $return[$param->getSiteParameterId()] = $param->getSiteParameterValue();
        }

        return $return;
    }

}
