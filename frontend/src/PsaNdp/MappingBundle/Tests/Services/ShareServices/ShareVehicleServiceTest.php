<?php

namespace PsaNdp\MappingBundle\Tests\Services\ShareServices;

use Phake;
use PSA\MigrationBundle\Entity\Page\PsaPage;
use PSA\MigrationBundle\Entity\Page\PsaPageVersion;
use PsaNdp\MappingBundle\Repository\Vehicle\PsaModelSilhouetteSiteRepository;
use PsaNdp\MappingBundle\Services\ShareServices\ShareVehicleService;
use PsaNdp\MappingBundle\Utils\ModelSilouhetteSiteUtils;
use PsaNdp\WebserviceConsumerBundle\Webservices\ConfigurationEngineSelect;
use PsaNdp\WebserviceConsumerBundle\Webservices\RangeManager;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ShareVehicleServiceTest
 */
class ShareVehicleServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ShareVehicleService
     */
    protected $shareVehicleService;

    /**
     * @var ConfigurationEngineSelect
     */
    protected $configurationEngine;

    /**
     * @var RangeManager
     */
    protected $rangeManager;

    /**
     * @var ModelSilouhetteSiteUtils
     */
    protected $modelSilhouetteSiteUtils;

    /**
     * @var PsaModelSilhouetteSiteRepository
     */
    protected $modelSilhouetteSiteRepository;

    /**
     * @var PsaPage
     */
    protected $node;

    /**
     * @var PsaPageVersion
     */
    protected $pageVersion;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * Set Up the test
     */
    public function setUp()
    {
        $this->translator = Phake::mock('Symfony\Component\Translation\TranslatorInterface');
        $this->pageVersion = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPageVersion');
        $site = Phake::mock('PSA\MigrationBundle\Entity\Site\PsaSite');
        $language = Phake::mock('PSA\MigrationBundle\Entity\Language\PsaLanguage');
        $this->node = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPage');
        Phake::when($this->node)->getSite()->thenReturn($site);
        Phake::when($this->node)->getLangue()->thenReturn($language);
        Phake::when($this->node)->getSiteId()->thenReturn(2);
        Phake::when($this->node)->getLanguage()->thenReturn('fr');
        Phake::when($this->node)->getVersion()->thenReturn($this->pageVersion);
        $modelSihouetteSite = Phake::mock('PsaNdp\MappingBundle\Entity\Vehicle\PsaModelSilhouetteSite');
        $this->modelSilhouetteSiteRepository = Phake::mock('PsaNdp\MappingBundle\Repository\Vehicle\PsaModelSilhouetteSiteRepository');
        Phake::when($this->modelSilhouetteSiteRepository)->findOneBySiteIdLanguageCodeLcdvAndGroupingCode(Phake::anyParameters())->thenReturn($modelSihouetteSite);
        $this->modelSilhouetteSiteUtils = Phake::mock('PsaNdp\MappingBundle\Utils\ModelSilouhetteSiteUtils');
        Phake::when($this->modelSilhouetteSiteUtils)->generateImgUrl(Phake::anyParameters())->thenReturn('/url/img');
        $this->configurationEngine = Phake::mock('PsaNdp\WebserviceConsumerBundle\Webservices\ConfigurationEngineSelect');
        $this->rangeManager = Phake::mock('PsaNdp\WebserviceConsumerBundle\Webservices\RangeManager');

        $this->shareVehicleService = new ShareVehicleService($this->configurationEngine, $this->rangeManager, $this->modelSilhouetteSiteUtils, $this->modelSilhouetteSiteRepository);
        $this->shareVehicleService->setNode($this->node);
        $this->shareVehicleService->setTranslator($this->translator);
    }


    /**
     * Test getModelSilhouetteInformation
     */
    public function testGetModelSilhouetteInformation()
    {
        $result = $this->shareVehicleService->getModelSilhouetteInformation();

        $this->assertInstanceOf('PsaNdp\MappingBundle\Entity\Vehicle\PsaModelSilhouetteSite', $result);
    }

    /**
     * @param $rangeManagerStatus
     * @param $rangeManagerStatus
     *
     * @dataProvider provideGetModelSilhouette
     */
    public function testGetModelSilhouette($rangeManagerStatus, $lcdv6)
    {
        Phake::when($this->pageVersion)->getGammeVehiculeLcvd6()->thenReturn($lcdv6);
        Phake::when($this->pageVersion)->getGammeVehiculeSilouhette()->thenReturn('s00000066');
        Phake::when($this->rangeManager)->getWebserviceStatus(Phake::anyParameters())->thenReturn($rangeManagerStatus);
        Phake::when($this->rangeManager)->getCheapestByLcdv6AndGrBodyStyle(Phake::anyParameters())->thenReturn(array());

        $result = $this->shareVehicleService->getModelSilhouette();

        if ($lcdv6 && $rangeManagerStatus) {
            Phake::verify($this->modelSilhouetteSiteUtils)->resetOptions();
            $this->assertArrayHasKey('cheapest', $result);
            $this->assertArrayHasKey('version', $result);
            $this->assertArrayHasKey('imgSrc', $result);
        } else {
            $this->assertSame(null, $result);
        }

    }

    /**
     * @return array
     */
    public function provideGetModelSilhouette()
    {
        return array(
            array(true, 'APT1A5'),
            array(false, 'APT1A5'),
            array(true, null),
            array(false, null),
        );
    }
}
