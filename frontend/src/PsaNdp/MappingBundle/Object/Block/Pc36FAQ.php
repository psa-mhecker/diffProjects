<?php

namespace PsaNdp\MappingBundle\Object\Block;

use PsaNdp\MappingBundle\Object\Content;

/**
 * Class Pc36FAQ
 * @codeCoverageIgnore
 */
class Pc36FAQ extends Content
{

    protected $mapping = array(
        'datalayer' => 'dataLayer',
        'urlJson' => 'url',
        'faqTitle' => 'title',
        'faqSubTitle' => 'subtitle',
        'faqCTA' => 'ctaList',
    );

    /**
     * @var string $faqBackURL
     */
    protected $faqBackURL;

    /**
     * @var array $faqCat
     */
    protected $faqCat = array();

    /**
     * @var array $faqSubCat
     */
    protected $faqSubCat = array();

    /**
     * @var array $faqSurvey
     */
    protected $faqSurvey = array();

    /**
     * @var array $ctaList
     */
    protected $ctaList = array();

    /**
     * @return array
     */
    public function getCtaList()
    {
        return $this->ctaList;
    }

    /**
     * @param array $ctaList
     *
     * @return Content|void
     */
    public function setCtaList($ctaList)
    {
        $this->ctaList = $ctaList;

        return $this;
    }

    /**
     * @return string
     */
    public function getFaqBackURL()
    {
        return $this->faqBackURL;
    }

    /**
     * @param string $faqBackURL
     */
    public function setFaqBackURL($faqBackURL)
    {
        $this->faqBackURL = $faqBackURL;
    }

    /**
     * @return array
     */
    public function getFaqCat()
    {
        return $this->faqCat;
    }

    /**
     * @param array $faqCat
     */
    public function setFaqCat($faqCat)
    {
        $this->faqCat = $faqCat;
    }

    /**
     * @return array
     */
    public function getFaqSubCat()
    {
        return $this->faqSubCat;
    }

    /**
     * @param array $faqSubCat
     */
    public function setFaqSubCat($faqSubCat)
    {
        $this->faqSubCat = $faqSubCat;
    }

    /**
     * @return array
     */
    public function getFaqSurvey()
    {
        return $this->faqSurvey;
    }

    /**
     * @param array $faqSurvey
     */
    public function setFaqSurvey($faqSurvey)
    {
        $this->faqSurvey = $faqSurvey;
    }

}
