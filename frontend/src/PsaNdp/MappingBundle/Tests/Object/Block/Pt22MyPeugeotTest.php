<?php

namespace PsaNdp\MappingBundle\Tests\Object\Block;

use Phake;
use PsaNdp\MappingBundle\Object\BadgeApplication;
use PsaNdp\MappingBundle\Object\Block\Pt22ActionCompte;
use PsaNdp\MappingBundle\Object\Block\Pt22MyPeugeot;
use PsaNdp\MappingBundle\Object\Content;

class Pt22MyPeugeotTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Pt22MyPeugeot
     */
    protected $pt22;

    /**
     * @var Pt22MyPeugeot
     */
    protected $pt22WithValue;

    protected $ctaFactory;

    protected $title = 'title';

    /**
     * @var Content
     */
    protected $mainLinkUser;

    protected $description = array('ligne 1', 'ligne 2', 'ligne 3');

    /**
     * @var Pt22ActionCompte
     */
    protected $signIn;

    /**
     * @var Pt22ActionCompte
     */
    protected $signUp;

    protected $descriptionStoreApp = 'description store app';

    /**
     * @var array<Badge>
     */
    protected $appstores;

    protected $datalayer = '';

    protected $mediaServer = 'http://media.psa.test';
    protected $isMobile = false;

    /**
     * Set Up the test.
     */
    public function setUp()
    {
        // $this->ctaFactory = Phake::mock('PsaNdp\MappingBundle\Object\Factory\CtaFactory');
        // Si le constructeur a besoin de services, les créer avec Phake
        $this->ctaFactory = Phake::mock('PsaNdp\MappingBundle\Object\Factory\CtaFactory');

        // Objet vide > initialisé à vide pour être utilisé dans les autres tests.
        $this->pt22 = new Pt22MyPeugeot();
        $this->pt22->setCtaFactory($this->ctaFactory);

        // Initialisation des sous objets
        $this->initSousObjets();

        // Objet avec valeurs > initialisé dans le setup pour tester que les setter marchent avant de continuer avec les autres tests.
        $this->pt22WithValue = new Pt22MyPeugeot();
        $this->pt22WithValue->setCtaFactory($this->ctaFactory);
        $this->pt22WithValue->setMainLinkUser($this->mainLinkUser);
        $this->pt22WithValue->setDescription($this->description);
        $this->pt22WithValue->setSignIn($this->signIn);
        $this->pt22WithValue->setSignUp($this->signUp);
        $this->pt22WithValue->setDescriptionStoreApp($this->descriptionStoreApp);
        $this->pt22WithValue->setAppstores($this->appstores);
        $this->pt22WithValue->offsetSet('datalayer', $this->datalayer);
    }

    /**
     * Sorti dans une méthode pour ne pas engorger le setUp.
     */
    protected function initSousObjets()
    {
        $this->mainLinkUser = new Content();
        $this->mainLinkUser->setDataFromArray(
            array(
                'title' => 'MYPEUGEOT',
                'url' => '#',
            )
        );

        $this->signIn = new Pt22ActionCompte();
        $this->mainLinkUser->setDataFromArray(
            array(
                'label' => 'Dejà inscrit ?',
                'ctaList' => $this->pt22->getCtaFactory()->createFromArray(
                    array(
                        'title' => 'ME CONNECTER',
                        'url' => '#',
                        'target' => '_blank',
                    )
                ),
            )
        );

        $this->signUp = new Pt22ActionCompte();
        $this->signUp->setDataFromArray(
            array(
                'label' => 'Nouveau membre ?',
                'ctaList' => $this->pt22->getCtaFactory()->createFromArray(
                    array(
                        'title' => 'Créer un compte',
                        'url' => '#',
                        'target' => '_blank',
                    )
                ),
            )
        );

        $this->appstores = array();
        $badges = array(
            array(
                'title' => 'App Store',
                'href' => '#',
                'src' => 'http://media.psa-ndp.com/design/frontend/desktop/img/app_store.png',
                'target' => '_blank',
            ),
            array(
                'title' => 'Google Play',
                'href' => '#',
                'src' => 'http://media.psa-ndp.com/design/frontend/desktop/img/app_playstore.png',
                'target' => '_blank',
            ),
        );

        for ($index = 0; $index < 3 && isset($badges[$index]); ++$index) {
            $appstore = new BadgeApplication();
            $this->appstores[] = $appstore->setDataFromArray($badges[$index]);
        }
    }

    /**
     * Test setDataFromArray.
     */
    public function testSetDataFromArray()
    {
        $data = array(
            'title' => $this->title,
            'description' => $this->description,
            'signIn' => $this->signIn,
            'signUp' => $this->signUp,
            'descriptionStoreApp' => $this->descriptionStoreApp,
            'appstores' => $this->appstores,
            'datalayer' => $this->datalayer,
        );

        $this->pt22->setDataFromArray($data);

        $this->assertSame($this->title, $this->pt22->getTitle());

        $this->assertSame($this->description, $this->pt22->getDescription());
        $this->assertSame($this->description, $this->pt22->offsetGet('blockContent'));

        $this->assertSame($this->signIn, $this->pt22->getSignIn());

        $this->assertSame($this->signUp, $this->pt22->getSignUp());

        $this->assertSame($this->descriptionStoreApp, $this->pt22->getDescriptionStoreApp());

        $this->assertSame($this->appstores, $this->pt22->getAppstores());

        $this->assertSame($this->datalayer, $this->pt22->offsetGet('datalayer'));
    }

    /**
     * @param string $key
     * @param mixed  $value
     * @param bool   $exception
     *
     * @dataProvider provideProperty
     *
     * @throws \Exception
     */
    public function testOffsetSet($key, $value, $exception)
    {
        $this->pt22->offsetSet($key, $value);

        if (!$exception) {
            $this->assertSame($value, $this->pt22->offsetGet($key));
        }
    }

    /**
     * @return array
     */
    public function provideProperty()
    {
        return array(
            array('datalayer', $this->datalayer, false),
            array('blockContent', $this->description, false),
        );
    }
}
