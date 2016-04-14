<?php

namespace PsaNdp\MappingBundle\Object\Block;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PsaNdp\MappingBundle\Entity\PsaFinishingBagde;
use PsaNdp\MappingBundle\Entity\PsaFinishingColor;
use PsaNdp\MappingBundle\Entity\PsaSitesEtWebservicesPsa;
use PsaNdp\MappingBundle\Entity\Vehicle\PsaModelSilhouetteSite;
use PsaNdp\MappingBundle\Object\Block\Pf58Object\Pf58Information;
use PsaNdp\MappingBundle\Object\Details\Detail;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;
use PsaNdp\MappingBundle\Object\Media;
use PsaNdp\MappingBundle\Object\Popin\Popin;
use PsaNdp\MappingBundle\Object\Price;
use PsaNdp\MappingBundle\Object\PriceByMonth;

/**
 * Class Pf53Series
 */
class Pf53Series extends Pf5358FinitionsMotorisations
{
    protected $mapping = array(
        'libelle' => 'label',
        'motorTitle' => 'title',
        'pricebyMonth' => 'priceByMonth',
        'infosup' => 'infoSup',
    );

    /**
     * @var string $id
     */
    protected $id;

    /**
     * @var string $bgColor
     */
    protected $bgColor;

    /**
     * @var string $color
     */
    protected $color;

    /**
     * @var Media $logo
     */
    protected $logo;

    /**
     * @var Media $img
     */
    protected $img;

    /**
     * @var array $label
     */
    protected $label = array();

    /**
     * @var array $sticker
     */
    protected $sticker = null;

    /**
     * @var Price $price
     */
    protected $price;

    /**
     * @var PriceByMonth $priceSeries
     */
    protected $priceSeries;

    /**
     * @var PriceByMonth $priceByMonth
     */
    protected $priceByMonth;

    /**
     * @var string $text
     */
    protected $text;

    /**
     * @var string $zoom
     */
    protected $zoom;

    /**
     * @var string $addToComparator
     */
    protected $addToComparator;

    /**
     * @var string $addOtherToComparator
     */
    protected $addOtherToComparator;

    /**
     * @var string $allSelected
     */
    protected $allSelected;

    /**
     * @var string $more
     */
    protected $more;

    /**
     * @var Popin $popin
     */
    protected $popin;

    /**
     * @var Collection $details
     */
    protected $details;

    /**
     * @var string $mode
     */
    protected $mode;

    /**
     * @var array $link
     */
    protected $link;

    /**
     * @var Collection $info
     */
    protected $info;

    /**
     * @var Collection înfoSup
     */
    protected $infoSup;

    /**
     * @var array
     */
    protected $finishing;

    /**
     * @var string
     */
    protected $pro;

    /**
     * @var array
     */
    protected $popinTransitionlink;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->popin = new Popin();
        $this->details = new ArrayCollection();
        $this->info = new ArrayCollection();
        $this->infoSup = new ArrayCollection();
        $this->price = new Price();
        $this->priceByMonth = new PriceByMonth();
        $this->mediaFactory = new MediaFactory();
    }

    /**
     * @return string
     */
    public function getAddOtherToComparator()
    {
        return $this->addOtherToComparator;
    }

    /**
     * @param string $addOtherToComparator
     *
     * @return $this
     */
    public function setAddOtherToComparator($addOtherToComparator)
    {
        $this->addOtherToComparator = $addOtherToComparator;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddToComparator()
    {
        $return = null;
        if ($this->configuration->getShowComparisonChart()) {
            $return = $this->addToComparator;
        }

        return $return;
    }

    /**
     * @param string $addToComparator
     *
     * @return $this
     */
    public function setAddToComparator($addToComparator)
    {
        $this->addToComparator = $addToComparator;

        return $this;
    }

    /**
     * @return string
     */
    public function getAllSelected()
    {
        return $this->allSelected;
    }

    /**
     * @param string $allSelected
     *
     * @return $this
     */
    public function setAllSelected($allSelected)
    {
        $this->allSelected = $allSelected;

        return $this;
    }

    /**
     * @return string
     */
    public function getBgColor()
    {
        return $this->bgColor;
    }

    /**
     * @param string $bgColor
     *
     * @return $this
     */
    public function setBgColor($bgColor)
    {
        $this->bgColor = $bgColor;

        return $this;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     *
     * @return $this
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param array $details
     *
     * @return $this
     */
    public function setDetails(array $details)
    {
        foreach ($details as $detail) {
            $seriesDetail = new Detail();
            $seriesDetail->setDataFromArray($detail);
            $this->addDetail($seriesDetail);
        }
        return $this;
    }

    /**
     * @param Detail $detail
     */
    public function addDetail(Detail $detail)
    {
        $this->details->add($detail);
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
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Media
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * @param array $img
     *
     * @return $this
     */
    public function setImg(array $img)
    {
        $this->img = $this->mediaFactory->createFromArray($img);

        return $this;
    }

    /**
     * @return array
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param array $label
     *
     * @return $this
     */
    public function setLabel(array $label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Media
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param array $logo
     *
     * @return $this
     */
    public function setLogo(array $logo)
    {
        $this->logo = $this->mediaFactory->createFromArray($logo);

        return $this;
    }

    /**
     * @return string
     */
    public function getMore()
    {
        return $this->more;
    }

    /**
     * @param string $more
     *
     * @return $this
     */
    public function setMore($more)
    {
        $this->more = $more;

        return $this;
    }

    /**
     * @return Popin
     */
    public function getPopin()
    {
        return $this->popin;
    }

    /**
     * @param array $popin
     *
     * @return $this
     */
    public function setPopin(array $popin)
    {
        $this->popin->setDataFromArray($popin);

        return $this;
    }

    /**
     * @return Price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param array $price
     *
     * @return $this
     */
    public function setPrice(array $price)
    {
        $this->price->setDataFromArray($price);

        return $this;
    }

    /**
     * @return PriceByMonth
     */
    public function getPriceByMonth()
    {
        if (empty($this->sfg)) {
            return null;
        }

        return $this->priceByMonth;
    }

    /**
     * @param array $priceByMonth
     *
     * @return $this
     */
    public function setPriceByMonth(array $priceByMonth)
    {
        $this->priceByMonth->setDataFromArray($priceByMonth);

        return $this;
    }

    /**
     * @return array
     */
    public function getSticker()
    {
        return $this->sticker;
    }

    /**
     * @param array $sticker
     *
     * @return $this
     */
    public function setSticker(array $sticker)
    {
        $this->sticker = $sticker;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getZoom()
    {
        return $this->zoom;
    }

    /**
     * @param string $zoom
     *
     * @return $this
     */
    public function setZoom($zoom)
    {
        $this->zoom = $zoom;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param array $infos
     *
     * @return $this
     */
    public function setInfo(array $infos)
    {
        foreach ($infos as $info) {
            $information = new Pf58Information();
            $information->setDataFromArray($info);
            $this->addInfo($information);
        }

        return $this;
    }

    /**
     * @param Pf58Information $information
     */
    public function addInfo(Pf58Information $information)
    {
        $this->info->add($information);
    }

    /**
     * @return Collection
     */
    public function getInfoSup()
    {
        return $this->infoSup;
    }

    /**
     * @param array $infoSup
     *
     * @return $this
     */
    public function setInfoSup(array $infoSup)
    {
        foreach ($infoSup as $info) {
            $information = new Pf58Information();
            $information->setDataFromArray($info);
            $this->addInfoSup($information);
        }

        return $this;
    }

    /**
     * @param Pf58Information $information
     */
    public function addInfoSup(Pf58Information $information)
    {
        $this->infoSup->add($information);
    }

    /**
     * @return array
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param array $link
     *
     * @return $this
     */
    public function setLink(array $link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     *
     * @return $this
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @return array
     */
    public function getFinishing()
    {
        return $this->finishing;
    }

    /**
     * @return string
     */
    public function getPro()
    {
        return $this->pro;
    }

    /**
     * @param string $pro
     */
    public function setPro($pro)
    {
        $this->pro = $pro;
    }

    /**
     * @param array $finishing
     */
    public function setFinishing($finishing)
    {
        $this->finishing = $finishing;
        if ($this->finishing->getColor() instanceof PsaFinishingColor) {
            $this->setBgColor($this->finishing->getColor()->getColorCode());
        }
        if ($this->finishing->getBadge() instanceof PsaFinishingBagde) {
            $this->setLogo(array('src' => $this->finishing->getBadge()->getBadgeUrl(), 'alt' => $this->finishing->getFinition()));
        }
    }

    /**
     * @param array $version
     *
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;
        $this->setId($version->IdVersion->id);
        $this->setTitle($version->IdVersion->label);
        $this->setLabel(array('text' => '', 'position' => false));

        if ($this->siteAndWebservices instanceof PsaSitesEtWebservicesPsa && $this->siteAndWebservices->getZoneVp() === 1) {
            $link = array(
                'style' => 'cta',
                'url' => $this->urlVp['urlConfigure'],
                'version' => 4,
                'title' => $this->translate['configurer'],
                'class' => 'confi',
            );
            if ($this->siteAndWebservices->getZoneVpPopin() === 1) {
                $link['data'] = 'data-openpopin="transition'.$this->id.'"';
            }
            $this->setLink(array($link));
            $this->setPopinTransitionlink(array(
                array(
                    'style' => 'STYLE_SIMPLELINK',
                    'url' => '#',
                    'class' => 'close back',
                    'title' => $this->translate['NDP_BACK_TO_SHOWROOM']
                ),
                array(
                    'style' => 'cta',
                    'url' => $this->urlVp['urlConfigure'],
                    'version' => 4,
                    'title' => $this->translate['NDP_CONTINUE']
                ),
            ));
        }
        $this->setTitle($this->getSegmentTitle().$version->GrCommercialName->label);

        $this->priceManager->setTranslator($this->translator, $this->domain, $this->locale);
        $this->priceManager->setVersion($version);

        $this->setPrice(array(
                'sum' => $this->priceManager->getCashPrice(),//$this->initPriceFormat($version->VehiclePrice->netPrice),
                'devise' => '',
                'mode' => 'cash',
                'taxe' => ''
            )
        );

        $this->sfg = $this->priceManager->getSfg();

        if (isset($this->siteSettings['VEHICULE_PRICE_DISPLAY']) && $this->siteSettings['VEHICULE_PRICE_DISPLAY'] && !empty($this->sfg)) {
            $this->setPriceByMonth(array(
                'price' => array(
                    'sum' => $this->priceManager->getPriceByMonth(),
                    'rent' => $this->getPriceManager()->getFirstAccountValue(),
                ),
            ));
        }

        return $this;
    }

    /**
     * @param string $lcdv16
     */
    public function setLcdv16($lcdv16)
    {
        $this->setImg(array('src' => self::VEHICLE_V3D_BASE_URL.$lcdv16.'&width=350&height=162&ratio=1&format=jpg&quality=100&view='.$this->angleView, 'alt' => $lcdv16));
        $this->lcdv16 = $lcdv16;
    }

    /**
     * @return array
     */
    public function getPopinTransitionlink()
    {
        return $this->popinTransitionlink;
    }

    /**
     * @param array $popinTransitionlink
     *
     * @return $this
     */
    public function setPopinTransitionlink(array $popinTransitionlink)
    {
        $this->popinTransitionlink = $popinTransitionlink;

        return $this;
    }

    /**
     * init Popin
     */
    public function initPopin()
    {
        $this->popin->setTranslate($this->translate);
        $this->popin->setSilhouette($this->silhouette);
        $this->popin->setConfiguration($this->configuration);
        $this->popin->setSiteSettings($this->siteSettings);
        $this->popin->setAngleView($this->angleView);
        $this->popin->setPriceManager($this->priceManager);
        $this->popin->setSfg($this->sfg);
        $this->popin->setVersion($this->version);
        $this->popin->setLcdv16($this->lcdv16);
        $this->popin->setSegmentTitle($this->segmentTitle);
        $this->popin->setEngineCriteria($this->engineCriteria);
        $this->popin->initPopinNew();
        if (!empty($this->sfg)) {
            $this->popin->initPopinfinancement();
        }
    }

    /**
     * init Details
     */
    public function initDetails()
    {
        $details = array();

        $equipments = $this->compareEquipments();
        foreach ($equipments as $equipment) {
            $details[] = $equipment;
        }
        $details[] = $this->initMotor();

        $this->setDetails($details);
    }

    /**
     * @return array
     */
    public function compareEquipments()
    {
        // Si finition de référence comparegrade finition de référence et crée le tableau
        if (!empty($this->finishingReference)) {
            $referentCategories = $this->getEquipmentsCategory($this->finishingReference);
            $details[] = array(
                'class' => 'equActive',
                'title' => $this->translate['equipments'].' '.$this->finishingReference->GrCommercialName->label,
                'equActive' => $this->initEquipment($referentCategories),
            );

            // Si finition de référence compareGrade de l'actuel et affiche la différence des 2
            $actual = $this->initEquipments($this->finishingReference, $referentCategories,'equComplementaires', true);
            if (!empty($actual)) {
                $details[] = $actual;
            }
        }
        else {
            // Si pas de finition de référence affiche la finition de l'actuel.
            $details[] = $this->initEquipments($this->version, $this->getEquipmentsCategory($this->version), 'equActive');
        }

        return $details;
    }

    /**
     * @param        $version
     * @param array  $categories
     * @param string $class
     * @param bool   $compare
     *
     * @return array
     */
    public function initEquipments($version, $categories, $class, $compare = false)
    {
        $title = $this->translate['equipments']; // Equipements

        if ($compare) {
            $actualCategories = $this->getEquipmentsCategory($this->version);
            $categories = $this->compareReferentAndActual($categories, $actualCategories);

            if (empty($categories)) {
                return $categories;
            }
            else {
                $title = $this->translate['equipmentsAdditional'];
            }
        }

        return array(
            'class' => $class,
            'title' => $title,
            $class => $this->initEquipment($categories)
        );
    }

    /**
     * @param $categories
     *
     * @return array
     */
    public function initEquipment($categories)
    {
        $equipments = array();
        $popin = array();
        $equipment = array();
        $lcdv4 = substr($this->lcdv16, 0, 4);
        $lcdv6 = substr($this->lcdv16, 4, 2);
        foreach ($categories as $category) {
            $count = 1;
            $equipments[]['cell'] = $equipment;
            $equipment = array();
            foreach ($category['features'] as $feature) {
                if (count($equipment) === 4) {
                    $equipments[]['cell'] = $equipment;
                    $equipment = array();
                }
                $eq = array();
                if (1 === $count) {
                    $eq['title'] = $category['label'];
                }
                $eq['info'] = true; // info true si une image ou une description
                $eq['text'] = $feature->label;
                $eq['subtitle'] = $feature->label;
                $eq['src'] = self::EQUIPMENT_V3D_BASE_URL.$lcdv4.'/'.$lcdv6.'/RolloversNew/Features/'.$feature->id.'/'.$feature->id.'_1.jpg';
                $eq['srcDefault'] = self::EQUIPMENT_V3D_BASE_URL.$lcdv4.'/'.$lcdv6.'/RolloversNew/Features/'.$feature->id.'/'.$feature->id.'_1.jpg';
                if (isset($feature->description)) {
                    $eq['description'] = $feature->description;
                }
                $count++;
                $equipment[] = $eq;
                $popin[] = $eq;
            }
        }

        if (!empty($equipment)) {
            $equipments[]['cell'] = $equipment;
        }

        $this->popin->initPopinEquipments($popin);

        return $equipments;
    }

    /**
     * @param array $referentCategories
     * @param array $actualCategories
     *
     * @return array
     */
    public function compareReferentAndActual($referentCategories, $actualCategories)
    {
        $equipment = array();

        foreach ($actualCategories as $key => $category) {
            if (!array_key_exists($key, $referentCategories)) {
                $equipment[$key] = $category;
            }
        }

        return $equipment;
    }

    /**
     * @param $version
     *
     * @return array
     */
    public function getEquipmentsCategory($version)
    {
        $super=[];
        $skf = $this->getSkfCategory($version);
        if($skf !== null) {
            $super = $skf->Category;
        }

        $standard = $version->StandardFeatures->Category;
        $optional = $version->OptionalFeatures->Category;

        return $this->orderEquipments($super, $standard, $optional);
    }

    /**
     * @param $version
     *
     * @return mixed
     */
    public function getSkfCategory($version)
    {
        $this->compareGrade->addContext('Country', $this->countryCode)
            ->addContext('LanguageID', $this->languageCode);

        // Par silhouette ou regroupement de silhouette
        if ($this->silhouette instanceof PsaModelSilhouetteSite && $this->silhouette->getShowFinishing() === 2) {
            $this->compareGrade->compareGrades($version->VehicleUse->id, $version->Model->id, $version->BodyStyle->id);
        }
        else {
            $this->compareGrade->compareGrades($version->VehicleUse->id, $version->Model->id, null, $version->GrbodyStyle->id);
        }

        return $this->compareGrade->getEquipments($version->GrCommercialName->id);
    }

    /**
     * @param array $skf
     * @param array $standard
     * @param array $optional
     *
     * @return mixed
     */
    public function orderEquipments($skf, $standard, $optional)
    {
        $equipments = array();

        // place les tableaux les un après les autres
        $features = array_merge($skf, $standard);
        $features = array_merge($features, $optional);

        foreach ($features as $feature) {
            $id = '';
            if (isset($feature->code)) {
                $id = $feature->code;
            }
            else {
                $id = $feature->id;
            }
            if (!array_key_exists($id, $equipments)) {
                $equipments[$id] = array(
                    'code' => $id,
                    'label' => $feature->label,
                    'features' => [],
                );
                if (isset($feature->Features)) {
                    $equipments[$id]['features'] = $feature->Features;
                } elseif (isset($feature->StandardFeatures)) {
                    $equipments[$id]['features'] = $feature->StandardFeatures;
                } elseif (isset($feature->OptionalFeatures)) {
                    $equipments[$id]['features'] = $feature->OptionalFeatures;
                }
            }
        }

        return $equipments;
    }

    /**
     * @return array
     */
    public function initMotor()
    {
        $motor = array(
            'class' => 'motorisations',
            'title' => $this->translate['priceAndEngineAvailable'], // Prix & motorisations disponibles
            'motorisations' => array(), //Tous les moteurs pour une finition
        );

        $this->configurationSelect->addContext('Country', $this->countryCode)
            ->addContext('LanguageID', $this->languageCode)
            ->addCriteria('Model', $this->version->Model->id)
            ->addCriteria('GrBodyStyle', $this->version->GrbodyStyle->id)
            ->addCriteria('GrCommercialName', $this->version->GrCommercialName->id);
        $config = $this->configurationSelect->select();

        $lcdv4 = substr($this->lcdv16, 0, 4);
        $lcdv6 = substr($this->lcdv16, 4, 2);
        $motorisations = array();

        $engines = $config->SelectResponse->Versions->Version;
        foreach ($engines as $engine) {

            $this->priceManager->setTranslator($this->translator, $this->domain, $this->locale);
            $this->priceManager->setVersion($engine);

            $sfg = $this->priceManager->getSfg();

            $motorisation = array(
                'img' => array('src' => self::MOTOR_V3D_BASE_URL.$lcdv4.'/'.$lcdv6.'/OverlayNew/ov_'.$engine->Engine->id.'.png', 'alt' => ''),
                'title' => $engine->GrEngine->label.' '.$engine->GrTransmissionType->label,
                'info' => true,
                'text' => $engine->Energy->label.' ', //evolution du WS mdc pour le type de boite de vitesse
                'id' => $engine->GrEngine->id,
            );
            if ($this->siteAndWebservices instanceof PsaSitesEtWebservicesPsa && $this->siteAndWebservices->getZoneVp() === 1) {
                $motorisation['link'] = array(array(
                    'style' => 'cta',
                    'url' => $this->urlVp['urlMotor'],
                    'version' => '4',
                    'title' => $this->translate['configurer'],
                    'data' => 'data-openpopin="transition'.$this->id.'"',
                ));
            }

            $motorisation['price']['sum'] = $this->priceManager->getCashPrice();
            if ($engine->Price->netPrice !== $this->version->Price->netPrice) {
                $motorisation['price']['infoPrice'] = '+ '.$this->priceManager->getFormatedPrice($engine->Price->netPrice - $this->version->Price->netPrice).' '.$this->currency;
            }
            else {
                $motorisation['price']['infoPrice'] = $this->translate['include']; // inclus
            }

            $motorisation['price']['devise'] = '';
            $motorisation['price']['taxecash'] = '';

            if (isset($this->siteSettings['VEHICULE_PRICE_DISPLAY']) && $this->siteSettings['VEHICULE_PRICE_DISPLAY'] && !empty($sfg) && !empty($this->sfg)) {
                $motorisation['libelle'] = array(
                    'text' => '',
                    'position' => false, // position paramètre généraux du site,
                );
                $motorisation['pricebymonth'] = array(
                    'rent' => $this->getPriceManager()->getFirstAccountValue(),
                    'mode' => 'monthly',
                    'mention' => '',
                    'devise' => '',
                );

                $motorisation['pricebymonth']['sum'] = $this->priceManager->getPriceByMonth();
                if ($this->sfg['startingPrice']['price'] !== $sfg['startingPrice']['price']) {
                    $sum = $sfg['startingPrice']['price'] - $this->sfg['startingPrice']['price'];
                    $motorisation['pricebymonth']['infoPrice'] = '+ '.$sum.' '.$sfg['startingPrice']['unit'];
                }
                else {
                    $motorisation['pricebymonth']['infoPrice'] = $this->translate['include']; // inclus
                }

            }

            $motorisations[] = $motorisation;

            $this->popin->initPopinMotor(array('src' => self::MOTOR_V3D_BASE_URL.$lcdv4.'/'.$lcdv6.'/OverlayNew/ov_'.$engine->Engine->id.'.png'), $engine, $sfg);
        }

        //Ordonner la motorisation par prix croissant
        uasort(
            $motorisations,
            function ($motor1, $motor2) {

                $netPrice1 = intval($motor1['price']['sum']);
                $netPrice2 = intval($motor2['price']['sum']);

                if ($netPrice1 == $netPrice2) {
                    return 0;
                }

                return ($netPrice1 > $netPrice2);
            }
        );

        $motorNumber = count($motorisations);

        $motor['motorisations'] = $motorisations;
        $this->setText($this->trans('NDP_ONE_MOTOR_AVAILABLE', array('%motorName%' => $this->version->GrEngine->label.' '.$this->version->GrTransmissionType->label, '%motorNumber%' => $motorNumber))); // avec le moteur $this->version->GrEngine->label
        if ($motorNumber > 1) {
            $this->setText($this->trans('NDP_SEVERAL_MOTOR_AVAILABLE', array('%motorName%' => $this->version->GrEngine->label.' '.$this->version->GrTransmissionType->label, '%motorNumber%' => $motorNumber))); // avec le moteur $this->version->GrEngine->label <br> disponible en  $motorNumber motorisations.
        }

        return $motor;
    }
}
