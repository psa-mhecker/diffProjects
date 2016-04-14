<?php

namespace PsaNdp\MappingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PSA\MigrationBundle\Entity\Site\PsaSite;

/**
 * Class PsaBecomeAgent
 *
 * @ORM\Table(name="psa_pdv_deveniragent")
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\PsaBecomeAgentRepository")
 */
class PsaBecomeAgent
{
    /**
     * @var PsaSite
     *
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Site\PsaSite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SITE_ID", referencedColumnName="SITE_ID", nullable=false)
     * })
     */
    protected $site;

    /**
     * @var integer
     *
     * @ORM\Column(name="PDV_DEVENIRAGENT_ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="PDV_DEVENIRAGENT_NAME", type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="PDV_DEVENIRAGENT_DESC", type="string", length=255, nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="PDV_DEVENIRAGENT_ADDRESS1", type="string", length=255, nullable=true)
     */
    protected $address1;

    /**
     * @var string
     *
     * @ORM\Column(name="PDV_DEVENIRAGENT_ADDRESS2", type="string", length=255, nullable=true)
     */
    protected $address2;

    /**
     * @var string
     *
     * @ORM\Column(name="PDV_DEVENIRAGENT_ZIPCODE", type="string", length=10, nullable=true)
     */
    protected $zipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="PDV_DEVENIRAGENT_CITY", type="string", length=255, nullable=true)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="PDV_DEVENIRAGENT_COUNTRY", type="string", length=2, nullable=true)
     */
    protected $country;

    /**
     * @var string
     *
     * @ORM\Column(name="PDV_DEVENIRAGENT_EMAIL", type="string", length=255, nullable=true)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="PDV_DEVENIRAGENT_TEL1", type="string", length=20, nullable=true)
     */
    protected $phoneNumber1;

    /**
     * @var string
     *
     * @ORM\Column(name="PDV_DEVENIRAGENT_TEL2", type="string", length=20, nullable=true)
     */
    protected $phoneNumber2;

    /**
     * @var string
     *
     * @ORM\Column(name="PDV_DEVENIRAGENT_FAX", type="string", length=20, nullable=true)
     */
    protected $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="PDV_DEVENIRAGENT_RRDI", type="string", length=10, nullable=true)
     */
    protected $rrdi;

    /**
     * @var float
     *
     * @ORM\Column(name="PDV_DEVENIRAGENT_LAT", type="float", nullable=false)
     */
    protected $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="PDV_DEVENIRAGENT_LNG", type="float", nullable=false)
     */
    protected $longitude;

    /**
     * @var integer
     *
     * @ORM\Column(name="PDV_DEVENIRAGENT_LIAISON_ID", type="integer", nullable=true)
     */
    protected $linkId;

    /**
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * @param string $address2
     *
     * @return $this
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * @param string $address1
     *
     * @return $this
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     *
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * @param string $desc
     *
     * @return $this
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param string $fax
     *
     * @return $this
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     *
     * @return $this
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return int
     */
    public function getLinkId()
    {
        return $this->linkId;
    }

    /**
     * @param int $linkId
     *
     * @return $this
     */
    public function setLinkId($linkId)
    {
        $this->linkId = $linkId;

        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     *
     * @return $this
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneNumber1()
    {
        return $this->phoneNumber1;
    }

    /**
     * @param string $phoneNumber1
     *
     * @return $this
     */
    public function setPhoneNumber1($phoneNumber1)
    {
        $this->phoneNumber1 = $phoneNumber1;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneNumber2()
    {
        return $this->phoneNumber2;
    }

    /**
     * @param string $phoneNumber2
     *
     * @return $this
     */
    public function setPhoneNumber2($phoneNumber2)
    {
        $this->phoneNumber2 = $phoneNumber2;

        return $this;
    }

    /**
     * @return string
     */
    public function getRrdi()
    {
        return $this->rrdi;
    }

    /**
     * @param string $rrdi
     *
     * @return $this
     */
    public function setRrdi($rrdi)
    {
        $this->rrdi = $rrdi;

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
     * @param PsaSite $site
     *
     * @return $this
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     *
     * @return $this
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }
}
