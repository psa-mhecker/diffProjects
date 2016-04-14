<?php
namespace PsaNdp\MappingBundle\Object;

use PsaNdp\MappingBundle\Object\Factory\CtaFactory;
use PsaNdp\MappingBundle\Object\Popin\PopinFinancement;
use PsaNdp\MappingBundle\Manager\PriceManager;
use PsaNdp\MappingBundle\Translation\TranslatorAwareTrait;
use PsaNdp\MappingBundle\Utils\ModelSilouhetteSiteUtils;
use PsaNdp\MappingBundle\Utils\ModelSiteUtils;


/**
 * Object for displaying a vehicule vignette
 *
 * Input can be generated should be generated using ModelSilouhetteSiteUtils
 * This object should be used for slices (SFD_FO_Transverses_final_20150414_v21.docx - section "LA VIGNETTE VEHICULE"):
 * PC77, PC82, PC95, PF8, PF23, PF25, PF27, PF31, PF33, PF37, PF40, PF42 PF46, PF53, PF57, PF58
 *
 * Class ModelSilouhetteVignette
 * @package PsaNdp\MappingBundle\Object
 */
class ModelSilouhetteVignette extends Content
{

    use TranslatorAwareTrait;

    /** @var string */
    protected $id;
    /** @var array */
    protected $img = [];
    /**
     * Depending of ISOBAR template the format expeccted is different
     *
     * @var string|array
     */
    protected $price;
    /** @var string */
    protected $finishing;
    /** @var string */
    protected $categoryVehicule;
    /** @var array */
    protected $actions = null;

    protected $infoPopin;

    protected $callPopin;

    protected $popinInfo;
    /** @var array */
    protected $reevoo = [];
    /** @var PriceManager */
    protected $priceManager;
    /** @var bool */
    protected $isMobile;
    /** @var string */
    protected $sliceId;
    /** @var mixed */
    protected $version;
    /** @var array */
    protected $sfg;
    /** @var string //TODO waiting for Isobar tpl for display */
    protected $commercialStrip;
    /** @var string */
    protected $mentions;

    /**
     * @param CtaFactory $ctaFactory
     * @param PriceManager $priceManager
     */
    public function __construct(CtaFactory $ctaFactory, PriceManager $priceManager)
    {
        parent::__construct();
        $this->priceManager = $priceManager;
        $this->ctaFactory = $ctaFactory;
    }

    /**
     * @return mixed
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * @param array $img
     *
     * @return ModelSilouhetteVignette
     */
    public function setImg($img)
    {
        $this->img = array(
            'src' => $img['src'],
            'alt' => $this->getSilhouetteTitle(),
        );

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     *
     * @return ModelSilouhetteVignette
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param mixed $actions
     *
     * @return ModelSilouhetteVignette
     */
    public function setActions($actions)
    {
        $this->actions = [];

        // Get active cta
        if ($actions['ctaDiscover']['isActive']) {
            $this->actions['discover'] = $this->createCta(
                $this->trans('NDP_DECOUVRIR'),
                $actions['ctaDiscover']['url'],
                '_self',
                Cta::ISOBAR_CTA_STYLE_DEFAULT,
                false,
                Cta::ISOBAR_CTA_VERSION_NIVEAU4
            );
        }

        if ($actions['ctaConfigurer']['isActive']) {
            $configData['config'] = $this->createCta(
                $this->trans('NDP_CONFIGURER'),
                $actions['ctaConfigurer']['url'],
                '_self',
                Cta::ISOBAR_CTA_STYLE_SIMPLELINK,
                false,
                null
            );

            if ($this->actions !== null) {
                // Order correctly
                // TODO If necessary change Isobar template to have a common usage (ex in pf23.html.smarty with one foreach)
                if ($actions['ctaDiscover']['order'] <= $actions['ctaConfigurer']['order']) {
                    $this->actions = array_merge($this->actions, $configData);
                } else {
                    $this->actions = array_merge($configData, $this->actions);
                }
            } else {
                $this->actions = $configData;
            }
        }

        return $this;
    }


    /**
     * @param string $trans
     * @param string $url
     * @param string $target
     * @param string $style
     * @param bool $display
     * @param string|null $version
     * @return array
     */
    private function createCta($trans, $url, $target, $style, $display, $version = null)
    {
        $data = array(
            'title' => $trans, //TODO change trad id to generique name
            'target' => $target,
            'url' => $url,
            CtaFactory::OPTION_STYLE => $style,
            'version' => $version,
            'display' => $display
        );
        /** @var Cta $cta */
        $cta = $this->getCtaFactory()->createFromArray($data);

        return $cta;
    }

    /**
     * @return mixed
     */
    public function getPopinInfo()
    {
        $return = null;

        // TODO If necessary change Isobar template to have a common usage (ex in pc95.html.smarty popin)
        if ($this->getPriceManager()->isMonthlyPrice() && $this->getPriceManager()->getSfgStatus($this->getSiteId())) {
            $financement = new PopinFinancement();
            $financement->setPriceManager($this->getPriceManager());
            $data = array(
                'id' => $this->id,
                'model' => $this->getSilhouetteTitle(),
                'img' => $this->getImg(),
            );
            $financement->setDataFromArray($data);
            $financement->init();

            $return = array(
                'financement' => $financement,
            );
        }

        return $return;
    }

    /**
     * @return string
     */
    public function getSilhouetteTitle()
    {
        $return =  $this->version->Model->label.' '.$this->version->GrbodyStyle->label;

        return $return;
    }

    /**
     * @param mixed $popinInfo
     *
     * @return ModelSilouhetteVignette
     */
    public function setPopinInfo($popinInfo)
    {
        $this->popinInfo = $popinInfo;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInfoPopin()
    {
        return $this->infoPopin;
    }

    /**
     * @param mixed $infoPopin
     *
     * @return ModelSilouhetteVignette
     */
    public function setInfoPopin($infoPopin)
    {
        $this->infoPopin = $infoPopin;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCallPopin()
    {
        return $this->callPopin;
    }

    /**
     * @param mixed $callPopin
     *
     * @return ModelSilouhetteVignette
     */
    public function setCallPopin($callPopin)
    {
        $this->callPopin = $callPopin;

        return $this;
    }

    public function getTitle()
    {
        $title = $this->getSilhouetteTitle();
        if ($this->isMobile) {
            $title = '<span>'.$this->version->Model->label.'</span> '.$this->version->GrbodyStyle->label;
        }

        return $title;
    }

    public function getSubtitle()
    {
        switch ($this->getSliceId()) {
            case ModelSiteUtils::SLICE_PC95:
                $subtitle = $this->categoryVehicule;
                break;
            default:
                $subtitle = $this->finishing;
                break;
        }

        return $subtitle;
    }


    /**
     * @return array
     */
    public function getSfg()
    {
        return $this->sfg;
    }

    /**
     * @param array $sfg
     *
     * @return ModelSilouhetteVignette
     */
    public function setSfg($sfg)
    {
        $this->sfg = $sfg;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     *
     * @return ModelSilouhetteVignette
     */
    public function setVersion($version)
    {
        $this->version = $version;
        $this->initPriceManagerWithVersion();

        return $this;
    }

    /**
     * Initialize object for pricing setting
     *
     * @return ModelSilouhetteVignette
     */
    public function initPriceManagerWithVersion()
    {
        $priceManager = $this->getPriceManager();
        $priceManager->setTranslator($this->translator, $this->domain, $this->locale)
            ->setVersion($this->getVersion());

        // calcul du prix
        if($priceManager->canShowPrice()) {
            switch ($this->sliceId) {
                case ModelSilouhetteSiteUtils::SLICE_PF23:
                case ModelSilouhetteSiteUtils::SLICE_PC95:
                $price['value'] = $priceManager->getPriceValue();
                if ($priceManager->isMonthlyPrice() && $priceManager->getSfgStatus($this->getSiteId())) {
                    $price['value'] = $priceManager->getPriceByMonth();
                    $price['rent'] = $priceManager->getFirstAccountValue();
                    $this->mentions = $priceManager->getMentionLegalByMonth();
                }
                //$price['unit'] = ''; //TODO to confirm if to remove (waiting for MOA feedback on how to display for all slices)

                $this->price = $price;
                    break;
                // Case ModelSilouhetteSiteUtils::SLICE_DEFAULT
                default:
                    // If possible priorize case ModelSilouhetteSiteUtils::SLICE_PF23 for Isobar tpl refacto
                    $this->setPrice($priceManager->getPriceValue());
                    break;
            }
            // si prix mensualisé on affiche la popin
            if ($priceManager->isMonthlyPrice() && $priceManager->getSfgStatus($this->getSiteId())) {
                $this->infoPopin = true;
                $this->callPopin = true; // @todo simplifier le template isobar qu'il test qu'une seul des 3 variables
            }
        }

        return $this;
    }


    /**
     * @return PriceManager
     */
    public function getPriceManager()
    {
        return $this->priceManager;
    }

    /**
     * @param PriceManager $priceManager
     *
     * @return ModelSilouhetteVignette
     */
    public function setPriceManager($priceManager)
    {
        $this->priceManager = $priceManager;

        return $this;
    }

    /**
     * @return array
     */
    public function getReevoo()
    {
        return $this->reevoo;
    }

    /**
     * @param array $reevoo
     *
     * @return ModelSilouhetteVignette
     */
    public function setReevoo($reevoo)
    {
        $this->reevoo = $reevoo;

        return $this;
    }

    /**
     * @return string
     */
    public function getSliceId()
    {
        return $this->sliceId;
    }

    /**
     * @param string $sliceId
     *
     * @return ModelSilouhetteVignette
     */
    public function setSliceId($sliceId)
    {
        $this->sliceId = $sliceId;

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return ModelSilouhetteVignette
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getMentions()
    {
        return $this->mentions;
    }

    /**
     * @param string $mentions
     *
     * @return ModelSilouhetteVignette
     */
    public function setMentions($mentions)
    {
        $this->mentions = $mentions;

        return $this;
    }

}
