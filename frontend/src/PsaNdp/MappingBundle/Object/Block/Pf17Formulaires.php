<?php namespace PsaNdp\MappingBundle\Object\Block;

use Doctrine\Common\Collections\Collection;
use PSA\MigrationBundle\Entity\Content\PsaContent;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Cta;
use PsaNdp\MappingBundle\Object\Factory\CtaFactory;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;
use PsaNdp\MappingBundle\Object\Media;
use PsaNdp\MappingBundle\Object\MediaInterface;

/**
 * Class Pf17Formulaires
 * @package PsaNdp\MappingBundle\Object\Block
 */
class Pf17Formulaires extends Content
{

    const CODE_EMAIL = '##e-mail##';
    const NDP_PF17_THANKS = 'NDP_PF17_THANKS';
    const NDP_PF17_RETURN_TO_HOME = 'NDP_PF17_RETURN_TO_HOME';
    const NDP_MY_PEUGEOT = 'NDP_FO_MY_PEUGEOT';
    const NDP_ERROR_FO_FORM_MESSAGE = 'NDP_ERROR_FO_FORM_MESSAGE';
    const NDP_INFOBULLE_ICON_I = 'NDP_INFOBULLE_ICON_I';

    protected $mapping = array(
        'datalayer' => 'dataLayer'
    );

    /**
     * @var string
     */
    protected $lang;

    /**
     * @var string
     */
    protected $country;

    /**
     * @var string
     */
    protected $lcdv16;

    /**
     * @var string
     */
    protected $formType;

    /**
     * @var PsaContent
     */
    protected $content;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var boolean
     */
    protected $isMobile;

    /**
     * @var Cta
     */
    protected $ctaMyPeugeot;

    /**
     * @var string
     */
    protected $myPeugeotUrl;

    /**
     * @var string
     */
    protected $urlJson;

    /**
     * @var Collection
     */
    protected $contentCtaList;

    /**
     * @var MediaInterface
     */
    protected $contentMedia;

    /**
     * @var bool
     */
    protected $instance = true;

    /**
     * @var string
     */
    protected $culture;

    /**
     * Environnement (DEV, RECETTE, PREPROD, PROD)
     *
     * @var string
     */
    protected $environment = 'PROD';

    /**
     * @param CtaFactory   $ctaFactory
     * @param MediaFactory $mediaFactory
     */
    public function __construct(CtaFactory $ctaFactory, MediaFactory $mediaFactory)
    {
        $this->ctaFactory = $ctaFactory;
        $this->mediaFactory = $mediaFactory;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getContent()->getCurrentVersion()->getContentTitle();
    }

    /**
     * @return string
     */
    public function getInstance()
    {
        $web = $this->getWebInstance();
        $return = '';
        if (!empty($web) && !$this->isMobile) {
            $return = $web;
        }
        $mobile = $this->getMobileInstance();
        if ($this->isMobile && !empty($mobile)) {
            $return = $mobile;
        }

        return $return;
    }

    /**
     * @return string
     */
    public function getWebInstance()
    {
        return $this->getContent()->getCurrentVersion()->getContentCode();
    }

    /**
     * @return string
     */
    public function getMobileInstance()
    {
        return $this->getContent()->getCurrentVersion()->getContentTitle13();
    }

    /**
     * @return string
     */
    public function getDemand()
    {
        return $this->getContent()->getCurrentVersion()->getContentText();
    }

    /**
     * @return string
     */
    public function getEmailConfirmation()
    {
        return $this->getContent()->getCurrentVersion()->getContentText2();
    }

    /**
     * @return string
     */
    public function getAdditionalText()
    {
        return $this->getContent()->getCurrentVersion()->getContentShorttext();
    }

    /**
     * @return string
     */
    public function getMyPeugeotDescription()
    {
        return $this->getContent()->getCurrentVersion()->getContentShorttext2();
    }

    /**
     * @return Collection
     */
    public function getContentCtaList()
    {
        return $this->ctaFactory->create(
            $this->contentCtaList,
            array(
                'type' => Cta::NDP_CTA_TYPE_SIMPLELINK,
            )
        );
    }

    /**
     * @return Media
     */
    public function getContentMedia()
    {
        $media = $this->content->getCurrentVersion()->getMedia();
        if ($media) {
            $this->contentMedia = $this->mediaFactory->createFromMedia($media);
        }

        return $this->contentMedia;
    }

    /**
     * @return string
     */
    public function getFormType()
    {
        return $this->formType;
    }

    /**
     * @param string $formType
     *
     * @return Pf17Formulaires
     */
    public function setFormType($formType)
    {
        $this->formType = $formType;

        return $this;
    }

    /**
     * @return Cta
     */
    public function getCtaMyPeugeot()
    {
        return $this->ctaFactory->create($this->ctaMyPeugeot);
    }

    /**
     * @param PsaContent $content
     *
     * @return Pf17Formulaires
     */
    public function setContent(PsaContent $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return PsaContent
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $myPeugeotUrl
     */
    public function setMyPeugeotUrl($myPeugeotUrl)
    {
        $this->myPeugeotUrl = $myPeugeotUrl;
    }

    /**
     * @return string
     */
    public function getLcdv16()
    {
        return $this->lcdv16;
    }

    public function hasInstance()
    {
        return $this->instance;
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
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCulture()
    {
        return $this->culture;
    }

    /**
     * @param string $culture
     */
    public function setCulture($culture)
    {
        $this->culture = $culture;
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param string $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * @return string
     */
    public function getBrandIdConnector()
    {
        $return = 'pc';
        if ($this->isMobile) {
            $return = 'mobile';
        }

        return $return;
    }

    /**
     * @return string
     */
    public function getContext()
    {
        $return = 'desktop';
        if ($this->isMobile) {
            $return = 'mobile';
        }

        return $return;
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param boolean $isMobile
     */
    public function setIsMobile($isMobile)
    {
        $this->isMobile = $isMobile;
    }

    /**
     * @return string
     */
    public function getUrlJson()
    {
        return $this->urlJson;
    }

    /**
     * @param string $urlJson
     */
    public function setUrlJson($urlJson)
    {
        $this->urlJson = $urlJson;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->translate[self::NDP_ERROR_FO_FORM_MESSAGE];
    }

    /**
     * @return string
     */
    public function getInformation()
    {
        return $this->translate[self::NDP_INFOBULLE_ICON_I];
    }

    /**
     * initialize PF17
     */
    public function init()
    {
        $this->instance = true;
        if ($this->getInstance() === '') {
            $this->instance = false;
        }
    }
}
