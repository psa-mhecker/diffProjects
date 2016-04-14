<?php

namespace PsaNdp\MappingBundle\Object\Block\Pf11;

use PsaNdp\MappingBundle\Object\AbstractObject;
use JMS\Serializer\Annotation as Serializer;
/**
 * @codeCoverageIgnore
 */
class MapOptions extends AbstractObject{

    /**
     * @Serializer\Type("integer")
     */
    private $maxResults;

    /**
     * @Serializer\Type("integer")
     */
    private $mode;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var string
     */
    private $redirection;

    /**
     * @var string
     */
    private $dealersListsUrl;

    /**
     * @Serializer\Type("integer")
     */
    private $radius;
    /**
     * @Serializer\Type("boolean")
     */
    private $autocomplete;

    /**
     * @Serializer\Type("boolean")
     */
    private $markerClustering;

    /**
     * @Serializer\Type("integer")
     */
    private $maxDvn;

    /**
     * @Serializer\Type("string")
     */
    private $mediaServer;

    /**
     * @Serializer\Type("string")
     */
    private $googleMapClientId;

    /**
     * @Serializer\Type("string")
     */
    private $googleChannel;

    /**
     * @Serializer\Type("string")
     */
    private $countryCode;

    /**
     * Get maxResults
     *
     * @return integer
     */
    public function getMaxResults()
    {
        return $this->maxResults;
    }

    /**
     * @param integer $maxResults
     *
     * @return MapOptions
     */
    public function setMaxResults($maxResults)
    {
        $this->maxResults = $maxResults;

        return $this;
    }

    /**
     * Get mode
     *
     * @return boolean
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param float $mode
     *
     * @return MapOptions
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     *
     * @return MapOptions
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     *
     * @return MapOptions
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get redirection
     *
     * @return string
     */
    public function getRedirection()
    {
        return $this->redirection;
    }

    /**
     * @param string $redirection
     *
     * @return MapOptions
     */
    public function setRedirection($redirection)
    {
        $this->redirection = $redirection;

        return $this;
    }

    /**
     * Get dealersListsUrl
     *
     * @return string
     */
    public function getDealersListsUrl()
    {
        return $this->dealersListsUrl;
    }

    /**
     * @param mixed $dealersListsUrl
     *
     * @return MapOptions
     */
    public function setDealersListsUrl($dealersListsUrl)
    {
        $this->dealersListsUrl = $dealersListsUrl;

        return $this;
    }

    /**
     * Get autocomple
     *
     * @return mixed
     */
    public function getAutocomplete()
    {
        return $this->autocomplete;
    }

    /**
     * @param boolean $autocomplete
     *
     * @return MapOptions
     */
    public function setAutocomplete($autocomplete)
    {
        $this->autocomplete = $autocomplete;

        return $this;
    }

    /**
     * Get markerClustering
     *
     * @return boolean
     */
    public function getMarkerClustering()
    {
        return $this->markerClustering;
    }

    /**
     * @param boolean $markerClustering
     *
     * @return MapOptions
     */
    public function setMarkerClustering($markerClustering)
    {
        $this->markerClustering = $markerClustering;

        return $this;
    }

    /**
     * Get radius
     *
     * @return integer
     */
    public function getRadius()
    {
        return $this->radius;
    }

    /**
     * @param integer $radius
     *
     * @return MapOptions
     */
    public function setRadius($radius)
    {
        $this->radius = $radius;

        return $this;
    }

    /**
     * Get mediaServer
     *
     * @return mixed
     */
    public function getMediaServer()
    {
        return $this->mediaServer;
    }

    /**
     * @param mixed $mediaServer
     *
     * @return MapOptions
     */
    public function setMediaServer($mediaServer)
    {
        $this->mediaServer = $mediaServer;

        return $this;
    }

    /**
     * Get maxDvn
     *
     * @return mixed
     */
    public function getMaxDvn()
    {
        return $this->maxDvn;
    }

    /**
     * @param mixed $maxDvn
     *
     * @return MapOptions
     */
    public function setMaxDvn($maxDvn)
    {
        $this->maxDvn = $maxDvn;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGoogleMapClientId()
    {
        return $this->googleMapClientId;
    }

    /**
     * @param mixed $googleMapClientId
     *
     * @return $this
     */
    public function setGoogleMapClientId($googleMapClientId)
    {
        $this->googleMapClientId = $googleMapClientId;

        return $this;
    }

    /**
     * @return string
     */
    public function getGoogleChannel()
    {
        return $this->googleChannel;
    }

    /**
     * @param string $googleChannel
     *
     * @return $this
     */
    public function setGoogleChannel($googleChannel)
    {
        $this->googleChannel = $googleChannel;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param mixed $countryCode
     *
     * @return $this
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }
}
