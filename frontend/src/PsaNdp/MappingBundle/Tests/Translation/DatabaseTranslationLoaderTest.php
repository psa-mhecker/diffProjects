<?php

namespace PsaNdp\MappingBundle\Tests\Translation;

use Doctrine\Common\Collections\ArrayCollection;
use Phake;
use PsaNdp\MappingBundle\Translation\DatabaseTranslationLoader;

class DatabaseTranslationLoaderTest extends \PHPUnit_Framework_TestCase
{
    protected $translationReposiorty;
    protected $languageRepository;

    protected $databaseTranslationLoader;

    protected $resource;

    public function setUp()
    {
        $this->translationReposiorty = Phake::mock('PSA\MigrationBundle\Repository\PsaLabelRepository');
        $this->languageRepository = Phake::mock('PSA\MigrationBundle\Repository\PsaLanguageRepository');
        $this->domain = 1;
        $this->language = Phake::mock('PSA\MigrationBundle\Entity\Language\PsaLanguage');
        Phake::when($this->languageRepository)->findByCode(Phake::anyParameters())->thenReturn($this->language);
        Phake::when($this->languageRepository)->getTranslations(Phake::anyParameters())->thenReturn(array());
        Phake::when($this->language)->getLangueId()->thenReturn(1);
        Phake::when($this->translationReposiorty)->getTranslations(Phake::anyParameters())->thenReturn(
            $this->getTranslationKeys()
        );
        $this->databaseTranslationLoader = new DatabaseTranslationLoader(
            $this->translationReposiorty,
            $this->languageRepository
        );
        $this->resource = __DIR__.'/resources/1.fr.db';
    }

    public function testLoad()
    {
        $catalogue = $this->databaseTranslationLoader->load($this->resource, 'fr', $this->domain);
        $this->assertInstanceOf('Symfony\Component\Translation\MessageCatalogue', $catalogue);
        $this->assertTrue($catalogue->has('LABEL_KEY_1', $this->domain));
        $this->assertFalse($catalogue->has('NonExistingLabelKey', $this->domain));
        $this->assertEquals($catalogue->get('NonExistingLabelKey', $this->domain), 'NonExistingLabelKey');
        $this->assertEquals($catalogue->get('LABEL_KEY_1', $this->domain), 'LABEL_KEY_TRANSLATION_1');
    }

    public function getTranslationKeys()
    {
        $label1 = Phake::mock('PSA\MigrationBundle\Entity\Language\PsaLabel');
        $label2 = Phake::mock('PSA\MigrationBundle\Entity\Language\PsaLabel');
        $label3 = Phake::mock('PSA\MigrationBundle\Entity\Language\PsaLabel');

        Phake::when($label1)->getId(Phake::anyParameters())->thenReturn('LABEL_KEY_1');
        Phake::when($label1)->getTranslation(Phake::anyParameters())->thenReturn('LABEL_KEY_TRANSLATION_1');

        Phake::when($label2)->getId(Phake::anyParameters())->thenReturn('LABEL_KEY_2');
        Phake::when($label2)->getTranslation(Phake::anyParameters())->thenReturn('LABEL_KEY_TRANSLATION_2');

        Phake::when($label3)->getId(Phake::anyParameters())->thenReturn('LABEL_KEY_3');
        Phake::when($label3)->getTranslation(Phake::anyParameters())->thenReturn('LABEL_KEY_TRANSLATION_3');

        return new ArrayCollection([$label1, $label2, $label3]);
    }
}
