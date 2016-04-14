<?php

namespace PsaNdp\MappingBundle\Translation;

use PSA\MigrationBundle\Repository\PsaLanguageRepository;
use PSA\MigrationBundle\Repository\PsaLabelRepository;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Translation\Loader\LoaderInterface as BaseTranslationLoader;
use Symfony\Component\Translation\MessageCatalogue;

class DatabaseTranslationLoader implements BaseTranslationLoader
{

    protected $translationRepository;
    protected $languageRepository;

    public function __construct(PsaLabelRepository $translationsRepository, PsaLanguageRepository $languageRepository)
    {
        $this->translationRepository = $translationsRepository;
        $this->languageRepository = $languageRepository;
    }

    /**
     * @param mixed  $resource
     * @param string $locale
     * @param string $domain
     *
     * @return MessageCatalogue
     */
    public function load($resource, $locale, $domain = 'messages')
    {

        $language = $this->languageRepository->findByCode($locale);
        $translationKeys = $this->translationRepository->getTranslations($language->getLangueId(), $domain, 'FRONTEND');
        $catalogue = new MessageCatalogue($locale);

        if (class_exists('Symfony\Component\Config\Resource\FileResource')) {
            $catalogue->addResource(new FileResource($resource));
        }

        foreach ($translationKeys as $translationKey) {
            $catalogue->set($translationKey->getId(), $translationKey->getTranslation($language->getLangueId(), $domain), $domain);
        }

        return $catalogue;
    }
}
