<?php

namespace PsaNdp\MappingBundle\Object\Block;

use Doctrine\Common\Collections\ArrayCollection;
use PsaNdp\MappingBundle\Object\Cta;
use PSA\MigrationBundle\Entity\Cta\PsaCtaReferenceCommonInterface;
use PsaNdp\MappingBundle\Object\Content;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMultiConfigurableInterface;
use PsaNdp\MappingBundle\Object\ModelSilouhetteVignette;
use PsaNdp\MappingBundle\Object\Streamlike;
use PSA\MigrationBundle\Entity\Media\PsaMedia;
use PsaNdp\MappingBundle\Manager\PriceManager;
use PsaNdp\MappingBundle\Object\Popin\PopinFinancement;
use PsaNdp\MappingBundle\Object\Block\Pf2Object\Pf2Infos;

/**
 * Class Pf2PresentationShowroom.
 */
class Pf2PresentationShowroom extends Content
{
    const DESKTOP_FORMAT = 'NDP_PF2_DESKTOP';
    const MOBILE_FORMAT = 'NDP_GENERIC_4_3_640';
    const COMMERCIAL = 3;
    const UNIT_FUEL = 0;
    const UNIT_CO2 = 1;
    const UNIT_BY_FUEL = 2;
    const UNIT_BY_CO2 = 3;

    /**
     * @var array
     */
    protected $mapping = array(
        'datalayer' => 'dataLayer',
        );

    /**
     * @var bool
     */
    protected $isMobile;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $multis;

    /**
     * @var Streamlike
     */
    protected $video;

    /**
     * @var string
     */
    protected $mediaServer;

    /**
     * @var array
     */
    protected $slideItems = [];

    /**
     * @var array
     */
    protected $infobulle;

    /**
     * @var array|null
     */
    protected $popin;

    /**
     * @var array
     */
    protected $infosTechnique;

    /**
     * @var array
     */
    protected $infos;

    /**
     * @var string
     */
    protected $monthLabel;

    /**
     * @var string
     */
    protected $orLabel;

    /**
     * @var string
     */
    protected $dayLabel;

    /**
     * @var string
     */
    protected $popinImgSrc;

    /**
     * @var array
     */
    protected $paramUnites;

    /**
     * @var string
     */
    protected $popinMentionLegaleConso;

    /**
     *  @var PriceManager
     */
    protected $priceManager;

    /**
     *  @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $silhouette;

    /**
     * @var string
     */
    protected $model;

    /**
     * @var bool
     */
    protected $isNew;

    /**
     * @var bool
     */
    protected $showTitle;

    /**
     * @param PriceManager $priceManager
     */
    public function __construct(PriceManager $priceManager)
    {
        $this->priceManager = $priceManager;
    }

    public function init()
    {
        $this->initInfos();
        $this->initMedia();
    }

    protected function initMedia()
    {
        if ($this->multis) {
            foreach ($this->multis as $multi) {
                /* @var PsaPageZoneMultiConfigurableInterface $multi  */
                $this->setSlideImage($multi);
            }
        }
        if ($this->block->getMedia()) {
            $this->setSlideVideo($this->block->getMedia());
        }
    }

    /**
     * @param PsaPageZoneMultiConfigurableInterface $multi
     *
     * @return array
     */
    protected function setSlideImage(PsaPageZoneMultiConfigurableInterface $multi)
    {
        $media = $multi->getMedia();
        if ($media) {
            $size = ['desktop' => self::DESKTOP_FORMAT,'mobile' => self::MOBILE_FORMAT];
            $slide = $this->mediaFactory->createFromMedia($media, ['size' => $size, 'autoCrop' => true]);
            $this->slideItems[] = $slide;
        }
    }

    protected function initInfos()
    {
        if (($this->block->getZoneAttribut2() || $this->block->getZoneTitre()) && $this->block->getZoneParameters() != self::COMMERCIAL) {
            $this->infos = new Pf2Infos();
            //Decompte du Reveal
            $data = null;
            $date = $this->buildDate();
            if ($this->block->getZoneAttribut2() && !empty($date)) {
                $data['subtitle'] = $this->block->getZoneTitre2();
                $data['date'] = $date;
            } else {
                $titre = $this->block->getZoneTitre();
                if (!empty($titre)) {
                    $data['title'] = $titre;
                }
            }

            $this->infos = $data;
        }
        if ($this->block->getZoneParameters() == self::COMMERCIAL) {
            $data = [];
            //Recupération technique + CTA
            if ($this->block->getCtaReferences()) {
                $ctaReference = $this->block->getCtaReferences()->first();
                if ($ctaReference->getReferenceStatus() != PsaCtaReferenceCommonInterface::PSA_REFERENCE_STATUS_DISABLED) {
                    $cta = $this->ctaFactory->create($this->block->getCtaReferences(), array('color' => Cta::NDP_CTA_VERSION_LIGHT_BLUE));
                    $data = ['ctaList' => $cta];
                }
            }
            switch ($this->block->getZoneAttribut3()) {
                case 0:
                    $model = $this->getModel();

                    if (!empty($model)) {
                        $data['title'] = '';
                        if ($this->getIsNew()) {
                            $data['title'] .= $this->translate['new'].' ';
                        }
                        $data['title'] .= $this->translate['peugeot'].' ';
                        $data['title'] .= $model.' ';
                        $data['title'] .= $this->getSilhouette();
                    }
                    break;
                case 1:
                    $data['title'] = $this->block->getZoneTitre4();
                    break;
                case 2:
                default:
                    unset($data['title']);
            }
            $model = $this->getModel();
            if ($model) {
                $data = $this->initPriceManagerWithVersion($data);
                $data ['isNew'] = $this->getIsNew();
                $data ['model'] = $this->getModel();
                $data ['silhouette'] = $this->getSilhouette();
            }
            $this->infosTechnique = $data;
        }
    }

    protected function buildDate()
    {
        $label = null;
        $startDay = $this->block->getZoneDate();
        $startHour = $this->block->getZoneTitre3();
        $endDay = $this->block->getPage()->getVersion()->getPageEndDate();
        if ($startDay && $startHour && $endDay) {
            $startHour = explode(':', $startHour);
            $startDay->setTime($startHour[0], (isset($startHour[1]) ? $startHour[1] : 00));
            $now = new \Datetime('now');
            if ($now >= $startDay) {
                $now->setTime(00, 00);
                $endDay->setTime(00, 00);
                $diff = $now->diff($endDay);
                if ($diff->d >= 0) {
                    $label = $this->dayLabel.' '.max($diff->d, 1);
                }
                if ($diff->m > 0) {
                    $label = $this->monthLabel.' '.$diff->m;
                }
            }
        }

        return $label;
    }

    /**
     * @param PsaMedia $media
     */
    protected function setSlideVideo(PsaMedia $media)
    {
        $this->video = $this->mediaFactory->createFromMedia($media);
    }

    /**
     * Initialize object for pricing setting.
     *
     * @param array $data
     *
     * @return ModelSilouhetteVignette
     */
    public function initPriceManagerWithVersion(array $data = [])
    {
        $priceManager = $this->getPriceManager();
        $version = $priceManager->getVersion();

        if (!empty($version->Co2Rate) && !empty($version->MixedConsumption)) {
            $data['carburantG'] = $this->getPriceManager()->getVersion()->Co2Rate.$this->paramUnites[self::UNIT_CO2];
            $data['carburantCo'] = $this->paramUnites[self::UNIT_BY_CO2];
            $data['carburantLitre'] = $this->getPriceManager()->getVersion()->MixedConsumption.$this->paramUnites[self::UNIT_FUEL];
            $data['carburantKm'] = $this->paramUnites[self::UNIT_BY_FUEL];
        }

        if ($priceManager->canShowPrice()) {
            if ($priceManager->canShowNotice()) {
                $data['infotxt'] = $priceManager->getLegalNoticeCashPrice();
            }

            if ($priceManager->isMonthlyPrice()) {
                $data['prixmois'] = $this->getPriceManager()->getPriceByMonth().' '.$this->getPriceManager()->getFirstAccountValue();

                $data['infotxt'] = $priceManager->getLegalNoticeByMonth();

               // $this->popin = $this->getPopinInfo();
            } else {
                $prix = $priceManager->getCashPrice();
                if (!empty($prix)) {
                    $data['prix'] = $prix;
                }
            }
        }
        if (!empty($data['carburantLitre'])) {
            $id = 'PF2'.(int) $data['carburantLitre'];
            $this->infobulle = ['consommation' => ['id' => $id, 'content' => $this->popinMentionLegaleConso]];
            if ($this->isMobile) {
                $this->infobulle = ['infocarburant' => ['id' => $id, 'text' => $this->popinMentionLegaleConso], 'close' => $this->translate['close']];
            }
        }

        return $data;
    }

    /**
     * @return mixed
     */
    public function getPopinInfo()
    {
        $return = null;
        if ($this->getPriceManager()->isMonthlyPrice()) {
            $financement = new PopinFinancement();
            $financement->setPriceManager($this->getPriceManager());
            $id = 'NDP_PF2'.$this->getPriceManager()->getVersion()->IdVersion->id;
            $data = array(
                'id' => $id,
                'model' => $this->getPriceManager()->getVersion()->Model->label.' '.$this->getPriceManager()->getVersion()->GrbodyStyle->label,
                'img' => ['src' => $this->popinImgSrc],
            );
            $this->id = $id;
            $financement->setDataFromArray($data);
            $financement->init();
            $return = array(
                'financement' => $financement,
            );
            if ($this->isMobile) {
                $return = ['close' => $this->translate['close'], 'listPopin' => ['financement' => [0 => $financement]]];
            }
        }

        return $return;
    }

    /**
     * @return bool
     */
    public function getIsMobile()
    {
        return $this->isMobile;
    }

    /**
     * @param bool $isMobile
     *
     * @return \PsaNdp\MappingBundle\Object\Block\Pn18IFrame
     */
    public function setIsMobile($isMobile)
    {
        $this->isMobile = $isMobile;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getMultis()
    {
        return $this->multis;
    }

    /**
     * @param ArrayCollection $multis
     * 
     * @return Pf2PresentationShowroom
     */
    public function setMultis(ArrayCollection $multis)
    {
        $this->multis = $multis;

        return $this;
    }

    /**
     * @return array
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @param array $video
     *
     * @return Pf2PresentationShowroom
     */
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }
    /**
     * @return string
     */
    public function getMediaServer()
    {
        return $this->mediaServer;
    }

    /**
     * @param string $mediaServer
     *
     * @return Pf2PresentationShowroom
     */
    public function setMediaServer($mediaServer)
    {
        $this->mediaServer = $mediaServer;

        return $this;
    }

    /**
     * @return array
     */
    public function getSlideItems()
    {
        return $this->slideItems;
    }

    /**
     * @param array $slideItems
     *
     * @return Pf2PresentationShowroom
     */
    public function setSlideItems($slideItems)
    {
        $this->slideItems = $slideItems;

        return $this;
    }

    /**
     * @return array
     */
    public function getInfobulle()
    {
        return $this->infobulle;
    }

    /**
     * @param array $infobulle
     *
     * @return Pf2PresentationShowroom
     */
    public function setInfobulle($infobulle)
    {
        $this->infobulle = $infobulle;

        return $this;
    }

    /**
     * @return array
     */
    public function getPopin()
    {
        return $this->popin;
    }

    /**
     * @param array $popin
     *
     * @return Pf2PresentationShowroom
     */
    public function setPopin($popin)
    {
        $this->popin = $popin;

        return $this;
    }

    /**
     * @return array
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param array $images
     *
     * @return Pf2PresentationShowroom
     */
    public function setImages($images)
    {
        $this->images = $images;

        return $this;
    }

    /**
     * @return array
     */
    public function getInfosTechnique()
    {
        return $this->infosTechnique;
    }

    /**
     * @param array infosTechnique
     *
     * @return Pf2PresentationShowroom
     */
    public function setInfosTechnique($infosTechnique)
    {
        $this->infosTechnique = $infosTechnique;

        return $this;
    }

    /**
     * @return array
     */
    public function getInfos()
    {
        return $this->infos;
    }

    /**
     * @param array infos
     *
     * @return Pf2PresentationShowroom
     */
    public function setInfos($infos)
    {
        $this->infos = $infos;

        return $this;
    }

    /**
     * @return string
     */
    public function getMonthLabel()
    {
        return $this->monthLabel;
    }

    /**
     * @return string
     */
    public function getDayLabel()
    {
        return $this->dayLabel;
    }

    /**
     * @param string $monthLabel
     * 
     * @return Pf2PresentationShowroom
     */
    public function setMonthLabel($monthLabel)
    {
        $this->monthLabel = $monthLabel;

        return $this;
    }

    /**
     * @param string $dayLabel
     * 
     * @return Pf2PresentationShowroom
     */
    public function setDayLabel($dayLabel)
    {
        $this->dayLabel = $dayLabel;

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
     * @return Pf2PresentationShowroom
     */
    public function setPriceManager(PriceManager $priceManager)
    {
        $this->priceManager = $priceManager;

        return $this;
    }

    /**
     * @return string
     */
    public function getPopinMentionLegaleConso()
    {
        return $this->popinMentionLegaleConso;
    }

    /**
     * @param string $popinMentionLegaleConso
     * 
     * @return Pf2PresentationShowroom
     */
    public function setPopinMentionLegaleConso($popinMentionLegaleConso)
    {
        $this->popinMentionLegaleConso = $popinMentionLegaleConso;

        return $this;
    }
    /**
     * @return array
     */
    public function getParamUnites()
    {
        return $this->paramUnites;
    }

    /**
     * @param array $paramUnites
     * 
     * @return Pf2PresentationShowroom
     */
    public function setParamUnites($paramUnites)
    {
        $this->paramUnites = $paramUnites;

        return $this;
    }
    /**
     * @return string
     */
    public function getOrLabel()
    {
        return $this->orLabel;
    }

    /**
     * @param string $orLabel
     *
     * @return Pf2PresentationShowroom
     */
    public function setOrLabel($orLabel)
    {
        $this->orLabel = $orLabel;

        return $this;
    }

    /**
     * @return string
     */
    public function getPopinImgSrc()
    {
        return $this->popinImgSrc;
    }

    /**
     * @param string $popinImgSrc
     *
     * @return Pf2PresentationShowroom
     */
    public function setPopinImgSrc($popinImgSrc)
    {
        $this->popinImgSrc = $popinImgSrc;

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
     * @return Pf2PresentationShowroom
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * return the css class of the information block position.
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->block->getZoneLabel2();
    }

    /**
     * Return true if showroom is in mode full.
     *
     * @return bool
     */
    public function isFull()
    {
        return  $this->block->getZoneParameters() == self::COMMERCIAL;
    }

    public function getTimerSpeed()
    {
        return  $this->block->getTimerSpeed();
    }

    /**
     * @return bool
     */
    public function getIsNew()
    {
        return $this->isNew;
    }

    /**
     * @param bool $isNew
     *
     * @return Pf2PresentationShowroom
     */
    public function setIsNew($isNew)
    {
        $this->isNew = $isNew;

        return $this;
    }

    /**
     * @return string
     */
    public function getSilhouette()
    {
        return $this->silhouette;
    }

    /**
     * @param string $silhouette
     *
     * @return Pf2PresentationShowroom
     */
    public function setSilhouette($silhouette)
    {
        $this->silhouette = $silhouette;

        return $this;
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param string $model
     *
     * @return Pf2PresentationShowroom
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }
}
