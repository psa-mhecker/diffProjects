<?php

use Doctrine\Common\Collections\ArrayCollection;
use PsaNdp\MappingBundle\EventListener\KernelRequestSubscriber;

/**
 * Class KernelRequestSubscriberTest.
 */
class KernelRequestSubscriberTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var KernelRequestSubscriber
     */
    protected $kernel;
    protected $pageFinder;
    protected $siteRepository;
    protected $siteConfiguration;

    /**
     * Set Up the test.
     */
    public function setUp()
    {
        $this->pageFinder = Phake::mock('PsaNdp\MappingBundle\Services\PageFinder');

        $this->siteRepository = Phake::mock('PSA\MigrationBundle\Repository\PsaSiteRepository');

        $this->siteConfiguration = Phake::mock('PsaNdp\MappingBundle\Utils\SiteConfiguration');
        Phake::when($this->siteConfiguration)->getParameters()->thenReturn(array('SITE_DEFAULT_LANGUAGE' => '1'));

        $this->kernel = new KernelRequestSubscriber($this->pageFinder, $this->siteRepository, $this->siteConfiguration);
    }

    /**
     * test onKernelRequest.
     *
     * @param int    $siteId
     * @param string $pathInfo
     * @param bool   $findSite
     * @param int    $multiLanguage
     * @param string $preferredLanguage
     *
     * @dataProvider onKernelRequestProvider
     */
    public function testOnKernelRequest($siteId, $pathInfo, $findSite, $multiLanguage, $preferredLanguage)
    {
        $language1 = Phake::mock('PSA\MigrationBundle\Entity\Language\PsaLanguage');
        Phake::when($language1)->getLangueCode()->thenReturn('fr');
        Phake::when($language1)->getLangueId()->thenReturn(1);
        $language2 = Phake::mock('PSA\MigrationBundle\Entity\Language\PsaLanguage');
        Phake::when($language2)->getLangueCode()->thenReturn('en');
        Phake::when($language2)->getLangueId()->thenReturn(2);
        $languages = array($language1, $language2);
        $attributes = Phake::mock('Symfony\Component\HttpFoundation\ParameterBag');
        $browserLanguage = explode('_', $preferredLanguage);
        $browserLanguage = $browserLanguage[0];

        $request = Phake::mock('Symfony\Component\HttpFoundation\Request');
        $request->attributes = $attributes;
        Phake::when($request)->get('siteId')->thenReturn($siteId);
        Phake::when($request)->getPathInfo()->thenReturn($pathInfo);
        $event = Phake::mock('Symfony\Component\HttpKernel\Event\GetResponseEvent');
        Phake::when($event)->getRequest()->thenReturn($request);

        $pageVersion = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPageVersion');
        Phake::when($pageVersion)->getPageClearUrl()->thenReturn('/fr/test');
        $page = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPage');
        Phake::when($page)->getVersion()->thenReturn($pageVersion);

        if ($findSite) {
            $collectionLanguage = new ArrayCollection();
            for ($i = 0; $i < $multiLanguage; ++$i) {
                $collectionLanguage->add($language1);
            }
            $site = Phake::mock('PSA\MigrationBundle\Entity\Site\PsaSite');
            Phake::when($site)->getLanguages()->thenReturn($languages);
            Phake::when($site)->getLangues()->thenReturn($collectionLanguage);
            Phake::when($this->siteRepository)->findOneBySiteId($siteId)->thenReturn($site);
            Phake::when($this->pageFinder)->getHomePage(Phake::anyParameters())->thenReturn($page);
            if ($multiLanguage > 1) {
                Phake::when($request)->getPreferredLanguage()->thenReturn($preferredLanguage);
            }
        }

        $this->kernel->onKernelRequest($event);

        if ($pathInfo === '/' && !empty($siteId)) {
            if ($findSite) {
                if ($multiLanguage > 1 && $preferredLanguage) {
                    if ($preferredLanguage) {
                        if ($browserLanguage === 'fr' || $browserLanguage === 'en') {
                            Phake::verify($this->pageFinder)->getHomePage($siteId, $browserLanguage);
                        }
                    } else {
                        Phake::verify($this->pageFinder)->getHomePage($siteId, 'fr');
                    }
                } elseif ($multiLanguage === 1) {
                    Phake::verify($this->pageFinder)->getHomePage($siteId, 'fr');
                }
            }
        }
    }

    /**
     * @return array
     */
    public function onKernelRequestProvider()
    {
        return array(
            array(1, '/test', true, 2, 'fr_FR'),
            array(null, '/', true, 2, 'fr_FR'),
            array(1, '/', true, 1, 'fr_FR'),
            array(1, '/', true, 2, null),
            array(1, '/', true, 2, 'de'),
            array(1, '/', true, 1, 'de'),
            array(1, '/', true, 2, 'fr'),
            array(1, '/', true, 2, 'fr_FR'),
            array(1, '/', true, 2, 'en_EN'),
        );
    }

    /**
     * test onKernelRequest.
     *
     * @param bool   $findSite
     * @param int    $multiLanguage
     *
     * @expectedException Symfony\Component\HttpKernel\Exception\HttpException
     * @dataProvider onKernelRequestExceptionProvider
     */
    public function testOnKernelRequestWithException($findSite, $multiLanguage)
    {
        $language1 = Phake::mock('PSA\MigrationBundle\Entity\Language\PsaLanguage');
        Phake::when($language1)->getLangueCode()->thenReturn('fr');
        Phake::when($language1)->getLangueId()->thenReturn(1);
        $language2 = Phake::mock('PSA\MigrationBundle\Entity\Language\PsaLanguage');
        Phake::when($language2)->getLangueCode()->thenReturn('en');
        Phake::when($language2)->getLangueId()->thenReturn(2);
        $languages = array($language1, $language2);

        $request = Phake::mock('Symfony\Component\HttpFoundation\Request');
        Phake::when($request)->get('siteId')->thenReturn(2);
        Phake::when($request)->getPathInfo()->thenReturn('/');
        $event = Phake::mock('Symfony\Component\HttpKernel\Event\GetResponseEvent');
        Phake::when($event)->getRequest()->thenReturn($request);

        if ($findSite) {
            $collectionLanguage = new ArrayCollection();
            for ($i = 0; $i < $multiLanguage; ++$i) {
                $collectionLanguage->add($language1);
            }
            $site = Phake::mock('PSA\MigrationBundle\Entity\Site\PsaSite');
            Phake::when($site)->getLangues()->thenReturn($collectionLanguage);
            Phake::when($site)->getLanguages()->thenReturn($languages);
            Phake::when($this->siteRepository)->findOneBySiteId(2)->thenReturn($site);
        }

        $this->kernel->onKernelRequest($event);

        Phake::verify($this->pageFinder, Phake::never())->getHomePage(Phake::anyParameters());
    }

    /**
     * @return array
     */
    public function onKernelRequestExceptionProvider()
    {
        return array(
            array(false, 0),
            array(true, 0),
            array(false, 1),
        );
    }
}
