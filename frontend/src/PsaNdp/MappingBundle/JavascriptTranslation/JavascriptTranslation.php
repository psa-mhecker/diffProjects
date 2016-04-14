<?php

namespace PsaNdp\MappingBundle\JavascriptTranslation;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class JavascriptTranslation
 */
class JavascriptTranslation
{
    /**
     * @var array
     */
    protected $defaultValues = [
        'NDP_BROWSER_UNCONFORMABLE',
        'NDP_CLICK_HERE',
        'NDP_IGNORE',
        'NDP_VIEW_DETAILED_SHEET',
        'NDP_AGENT',
        'NDP_DEALER',
        'NDP_VISIT_WEBSITE',
        'NDP_OPENING_HOURS',
        'NDP_DEALER_SERVICES',
        'NDP_PHONENUMBER',
        'NDP_BACK_TO_RESULTS',
        'NDP_PF11_CONTACT',
        'NDP_PF11_VCF_CONTACT_SHEET',
        'NDP_PF11_CALL_US',
        'NDP_PF11_EMAIL_US',
    ];

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var string
     */
    protected $siteId;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator, SerializerInterface $serializer)
    {
        $this->translator = $translator;
        $this->serializer = $serializer;
    }

    /**
     * @param string $siteId
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getJavascriptTranslation()
    {
        $translation = array();

        foreach ($this->defaultValues as $trans) {
            $translation[$trans] = $this->translator->trans($trans, array(), $this->siteId, $this->locale);
        }

        return $this->serializer->serialize($translation,'json');
    }
}
