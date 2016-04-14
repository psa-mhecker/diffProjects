<?php
namespace PsaNdp\MappingBundle\Object\Block;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Cta;
use PsaNdp\MappingBundle\Object\Factory\CtaFactory;

/**
 * Class Pf8WebstoreVehicleNeuf
 */
class Pf8WebstoreVehicleNeuf extends Content
{
    const POINT_DE_VENTE = 1;
    const REGIONAL = 2;
    const PRODUIT = 3;

    protected $mapping = array(
        'datalayer' => 'dataLayer',
        'urlJson' => 'url',
        'noresult' => 'noResult',
        'errorload' => 'errorLoad',
    );

    /** @var PsaPageZoneConfigurableInterface $block */
    protected $block;

    /** @var CtaFactory */
    protected $ctaFactory;

    /** @var string $noResult */
    protected $noResult;

    /** @var string $title */
    protected $title;

    /** @var string $errorLoad */
    protected $errorLoad;

    /** @var string $searchTxt */
    protected $searchTxt;

    /** @var bool $pdv */
    protected $pdv = null;

    /** @var bool $isRegional */
    protected $isRegional = null;

    /** @var $hasNav */
    protected $hasNav = null;

    /** @var $noNav */
    protected $noNav = null;

    /** @var array $cas1 */
    protected $cas1 = array();

    /** @var array $searchType */
    protected $searchType = array();

    /**
     * @param CtaFactory $ctaFactory
     */
    public function __construct(CtaFactory $ctaFactory)
    {
        $this->ctaFactory = $ctaFactory;
    }

    /**
     * @param PsaPageZoneConfigurableInterface $block
     * @return $this
     */
    public function setBlock(PsaPageZoneConfigurableInterface $block)
    {
        $this->block = $block;

        $this->title = $block->getZoneTitre();

        return $this;
    }

    /**
     * @inheritdoc
     * @param array $translate
     * @return $this|Content
     */
    public function setTranslate(array $translate)
    {
        parent::setTranslate($translate);

        $this->setNoResult($translate['noresult']);

        $this->setErrorLoad($translate['errorload']);

        $this->setSearchTxt($translate['searchTxt']);

        $this->setCas1(array(
            'title' => $translate['cas1_title'],
            'btnAroundMe' => $translate['cas1_btnAroundMe'],
            'searchType' => array(
                'label1' => $translate['cas1_searchType_label1'],
                'placeholder1' => $translate['cas1_searchType_placeholder1'],
                'label2' => $translate['cas1_searchType_label2'],
                'placeholder2' => $translate['cas1_searchType_placeholder2'],
            ),
            'translate' => array(
                'btnSubmit' => $translate['searchTxt'],
                'or' => $translate['hasNav_or_txt'],
            ),
        ));

        return $this;
    }

    /**
     * @param string $errorLoad
     * @return Pf8WebstoreVehicleNeuf
     */
    public function setErrorLoad($errorLoad)
    {
        $this->errorLoad = $errorLoad;

        return $this;
    }

    /**
     * @return string
     */
    public function getErrorLoad()
    {
        return $this->errorLoad;
    }

    /**
     * @param array $hasNav
     * @return Pf8WebstoreVehicleNeuf
     */
    public function setHasNav($hasNav)
    {
        $this->hasNav = $hasNav;

        return $this;
    }

    /**
     * @return array
     */
    public function getHasNav()
    {
        return $this->hasNav;
    }

    /**
     * @param string $noResult
     * @return Pf8WebstoreVehicleNeuf
     */
    public function setNoResult($noResult)
    {
        $this->noResult = $noResult;

        return $this;
    }

    /**
     * @return string
     */
    public function getNoResult()
    {
        return $this->noResult;
    }

    /**
     * @param boolean $pdv
     * @return Pf8WebstoreVehicleNeuf
     */
    public function setPdv($pdv)
    {
        $this->pdv = $pdv;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getPdv()
    {
        return $this->pdv;
    }

    /**
     * @param boolean $isRegional
     * @return Pf8WebstoreVehicleNeuf
     */
    public function setIsRegional($isRegional)
    {
        $this->isRegional = $isRegional;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsRegional()
    {
        return $this->isRegional;
    }

    /**
     * @param string $searchTxt
     * @return Pf8WebstoreVehicleNeuf
     */
    public function setSearchTxt($searchTxt)
    {
        $this->searchTxt = $searchTxt;

        return $this;
    }

    /**
     * @return string
     */
    public function getSearchTxt()
    {
        return $this->searchTxt;
    }

    /**
     * @param array $cas1
     * @return Pf8WebstoreVehicleNeuf
     */
    public function setCas1($cas1)
    {
        $this->cas1 = $cas1;

        return $this;
    }

    /**
     * @return array
     */
    public function getCas1()
    {
        return $this->cas1;
    }

    /**
     * @param array $searchType
     * @return Pf8WebstoreVehicleNeuf
     */
    public function setSearchType($searchType)
    {
        $this->searchType = $searchType;

        return $this;
    }

    /**
     * @return array
     */
    public function getSearchType()
    {
        return $this->searchType;
    }

    /**
     * @param array $noNav
     * @return Pf8WebstoreVehicleNeuf
     */
    public function setNoNav($noNav)
    {
        $this->noNav = $noNav;

        return $this;
    }

    /**
     * @return array
     */
    public function getNoNav()
    {
        return $this->noNav;
    }

    /**
     * @param string $url
     * @param string $title
     * @return $this
     */
    public function initializeCta($url, $title)
    {
        $data = array(
            'url' => $url,
            'title' => $title,
        );
        $data['type'] = 'cta';

        $cta = $this->ctaFactory->createFromArray($data);

        $this->setCtaList(array($cta));

        return $this;
    }

    /**
     * 1 = Point de vente | 2 = Régional | 3 = Produit
     * @param integer $parcours
     * @param array $translate
     */
    public function initializeParcours($parcours, $translate)
    {
        $this->pdv = $this->isRegional = false;
        switch (intval($parcours)) {
            case self::POINT_DE_VENTE:
                $this->pdv = true;
                $this->isRegional = null;
                $this->setHasNav(array(
                    'or_txt' => $translate['hasNav_or_txt'],
                    'btnAroundMe' => $translate['hasNav_btnAroundMe'],
                    'pdvInput' => array(
                        'label' => $translate['hasNav_pdvInput_label'],
                    ),
                ));
                $this->setNoNav(null);
                break;
            case self::REGIONAL:
                $this->isRegional = true;
                $this->pdv = null;
                $this->setHasNav(array(
                    'or_txt' => $translate['hasNav_or_txt'],
                    'btnAroundMe' => $translate['hasNav_btnAroundMe'],
                    'pdvInput' => null,
                ));
                $this->setNoNav(null);
                break;
            case self::PRODUIT:
                $this->setNoNav(array(
                    'or_txt' => $translate['hasNav_or_txt'],
                    'btnAroundMe' => $translate['hasNav_btnAroundMe'],
                ));
                $this->setHasNav(null);
                break;
            default:
                break;
        }
    }
}
