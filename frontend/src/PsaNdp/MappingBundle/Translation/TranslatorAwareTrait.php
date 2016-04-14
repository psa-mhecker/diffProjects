<?php

namespace PsaNdp\MappingBundle\Translation;

use Symfony\Component\Translation\TranslatorInterface;

trait TranslatorAwareTrait
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var string
     */
    protected $domain;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @param TranslatorInterface $translator
     * @param null $domain
     * @param null $locale
     *
     * @return $this
     */
    public function setTranslator(TranslatorInterface  $translator, $domain = null, $locale = null)
    {
        $this->domain = $domain;
        $this->locale = $locale;
        $this->translator = $translator;

        return $this;
    }

    /**
     * @param  string $domain
     *
     * @return $this
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @param  string $locale
     *
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @param $id
     *
     * @param array $parameters
     * @param null $domain
     * @param null $locale
     *
     * @return string
     */
    public function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        if ($domain == null) {
            $domain = $this->domain;
        }
        if ($locale == null) {
            $locale = $this->locale;
        }

        $result =$this->translator->trans($id, $parameters, $domain, $locale);
        if ($result ==  $id) {
            $result = '';
        }

        return $result;
    }
}
