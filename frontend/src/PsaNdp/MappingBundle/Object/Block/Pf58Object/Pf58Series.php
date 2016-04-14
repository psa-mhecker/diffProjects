<?php

namespace PsaNdp\MappingBundle\Object\Block\Pf58Object;

use Doctrine\Common\Collections\Collection;
use PsaNdp\MappingBundle\Entity\PsaSitesEtWebservicesPsa;
use PsaNdp\MappingBundle\Entity\Vehicle\PsaModelConfig;
use PsaNdp\MappingBundle\Manager\PriceManager;
use PsaNdp\MappingBundle\Object\Block\Pf53Series;
use PsaNdp\MappingBundle\Entity\PsaFinishingColor;
use PsaNdp\MappingBundle\Object\Details\Detail;
use PsaNdp\MappingBundle\Object\Media;
use PsaNdp\MappingBundle\Object\Price;


/**
 * Class Pf58Series
 */
class Pf58Series extends Pf53Series
{
    /**
     * @var string $gear
     */
    protected $gear;

    /**
     * @param array $version
     *
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;
        $this->priceManager->setTranslator($this->translator, $this->domain, $this->locale);
        $this->priceManager->setVersion($version);
        $this->sfg = $this->priceManager->getSfg();

        $this->setPrice(array(
            'sum' => $this->priceManager->getCashPrice(),
            'devise' => '',
            'mode' => 'cash',
            'taxe' => ''
        ));

        if (isset($this->siteSettings['VEHICULE_PRICE_DISPLAY']) && $this->siteSettings['VEHICULE_PRICE_DISPLAY'] && !empty($this->sfg)) {
            $this->setPriceByMonth(array(
                'price' => array(
                    'sum' => $this->priceManager->getPriceByMonth(),
                    'rent' => $this->getPriceManager()->getFirstAccountValue(),
                ),
            ));
        }

        if ($this->version->EcoLabel) {
            $this->setSticker(array('src' => $this->version->EcoLabel, 'alt' => 'EcoLabel'));
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->version->GrEngine->label.' '.$this->version->TransmissionType->label;
    }

    /**
     * @return array
     */
    public function getLabel()
    {
        return array('text' => '', 'position' => false);
    }

    /**
     * @return string
     */
    public function getGear()
    {
        return $this->version->Engine->label;
    }

    /**
     * @param string $gear
     *
     * @return $this
     */
    public function setGear($gear)
    {
        $this->gear = $gear;

        return $this;
    }

    /**
     * @return array|Price
     */
    public function getPrice()
    {
        return $this->price;
    }


    /**
     * @param string $lcdv16
     */
    public function setLcdv16($lcdv16)
    {
        $this->lcdv16 = $lcdv16;
    }

    /**
     * @return Media
     */
    public function getImg()
    {

        $lcdv4 = substr($this->lcdv16, 0, 4);
        $lcdv6 = substr($this->lcdv16, 4, 2);

        $img = array('src' => self::MOTOR_V3D_BASE_URL.$lcdv4.'/'.$lcdv6.'/OverlayNew/ov_'.$this->version->Engine->id.'.png', 'alt' => '');

        return $this->mediaFactory->createFromArray($img);
    }

    /**
     * @return array
     */
    public function getInfo()
    {
        return $this->getMotorInfos($this->version);
    }

    /**
     * @return array
     */
    public function getInfoSup()
    {
        $return = null;
        if ($this->configuration instanceof PsaModelConfig && $this->configuration->getShowCharacteristic()) {
            $return = $this->getMotorInfosSup($this->engine);
        }

        return $return;
    }

    /**
     * @return Collection
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Initialize details
     */
    public function initDetails()
    {
        $details = array($this->getCaracteristiques(), $this->getMotor());

        foreach ($details as $detail) {
            $seriesDetail = new Detail();
            $seriesDetail->setDataFromArray($detail);
            $this->addDetail($seriesDetail);
        }
    }

    /**
     * @return array
     */
    public function getCaracteristiques()
    {
        $caracteristiques  = array(
            'class' => 'caracteristiques',
            'title' => $this->trans('NDP_CARACTERISTIQUES'),
            'caracteristiques' => [],
        ) ;

        foreach ($this->engine->TechnicalCharacteristics->Category as $category) {

            $table = array(
                'title' => $category->label,
                'cell' => [],
            );

            foreach ($category->TechnicalCharacteristic as $caracteristique)
            {
                if (count($table['cell']) === 3) {
                    $caracteristiques['caracteristiques'][] = $table;
                    $table = array(
                        'title' => '',
                        'cell' => [],
                    );
                }
                $table['cell'][] = array(
                    'title' => $caracteristique->label,
                    'text' => $caracteristique->value,
                );
            }

            $caracteristiques['caracteristiques'][] = $table;
        }

        return $caracteristiques;
    }

    /**
     * @return array
     */
    public function getMotor()
    {
        $motor = array(
            'class' => 'motorisations',
            'title' => $this->translate['finitionsAvailable'], // finitions disponible
            'motorisations' => array(), // Toutes les finitions disponible pour ce moteur !!!!
        );

        $motorisations = array();

        foreach ($this->version->finitions as $finition) {
            $this->priceManager->setTranslator($this->translator, $this->domain, $this->locale);
            $this->priceManager->setVersion($finition);
            $sfg = $this->priceManager->getSfg();
            $motorisation = array(
                'id' => 'motor'.$this->version->Engine->id.'-'.$finition->GrCommercialName->id,
                'img' => array(
                    'src' => self::VEHICLE_V3D_BASE_URL.$finition->IdVersion->id.'&width=350&height=162&ratio=1&format=png&quality=100&view='.$this->angleView,
                    'alt' => $finition->IdVersion->id,
                    'width' => '',
                    'height' => ''
                ),
                'title' => $this->getSilhouetteTitle(),
                'text' => '',
                'finition' => array(
                    'label' => $finition->GrCommercialName->label,
                    'color' => '#fffff',
                    'textWhite' => false,
                ),
                'libelle' => array(
                    'text' => '',
                    'position' => false
                ),
                'price' => array(
                    'sum' => $this->priceManager->getCashPrice(),
                    'devise' =>  '',
                    'indice' => '',
                    'taxe' => '',
                    'highlight' => true,
                ),
                'detailsfinition' => $this->getDetailsFinitions($finition)

            );

            // Si le cta configurer est actif dans le BO
            if ($this->siteAndWebservices instanceof PsaSitesEtWebservicesPsa && $this->siteAndWebservices->getZoneVp() === 1) {
                if (!empty($this->translate['configure'])) {
                    $link = array(
                        'style' => 'cta',
                        'url' => $this->urlVp['urlConfigure'],
                        'version' => 4,
                        'title' => $this->translate['configurer'],
                        'class' => 'confi',
                    );
                    if ($this->siteAndWebservices->getZoneVpPopin() === 1) {
                        $link['data'] = 'data-openpopin="transition"';
                    }
                    $motorisation['link'] = array(
                        $link
                    );
                }
            }

            if ($this->finishing->getColor() instanceof PsaFinishingColor) {
                $color =  $this->finishing->getColor()->getColorCode() ;
                $motorisation['finition']['color'] = $color;
                $motorisation['finition']['textWhite'] = $this->getContrastYIQ($color);
            }

            if (isset($this->siteSettings['VEHICULE_PRICE_DISPLAY']) && $this->siteSettings['VEHICULE_PRICE_DISPLAY'] && !empty($sfg)) {
                $motorisation['pricebyMonth'] = array(
                    'libelle' => array(
                        'text' => '',
                        'position' => false
                    ),
                    'price' => array(
                        'sum' =>  $this->priceManager->getPriceByMonth(),
                        'devise' =>  '',
                        'indice' => '',
                        'taxe' => '',
                        'by' => '',
                        'mention' => $this->priceManager->getFirstAccountValue(),
                    )
                );
            }


            $motorisations[$this->priceManager->getVersion()->Price->netPrice] = $motorisation;
        }

        ksort($motorisations);

        $motor['motorisations'] = $motorisations;

        return $motor;
    }

    /**
     * @param $hexcolor
     *
     * @return bool
     */
    public function getContrastYIQ($hexcolor){
        $hexcolor = str_replace('#','',$hexcolor);
            $r = hexdec(substr($hexcolor,0,2));
            $g = hexdec(substr($hexcolor,2,2));
            $b = hexdec(substr($hexcolor,4,2));
            $yiq = (($r*299)+($g*587)+($b*114))/1000;
        return ($yiq >= 128) ? false : true;
    }

    /**
     * init Popin
     */
    public function initPopin()
    {
        $this->popin->setTranslate($this->translate);
        $this->popin->setSilhouette($this->silhouette);
        $this->popin->setSiteSettings($this->siteSettings);
        $this->popin->setAngleView($this->angleView);
        $this->popin->setPriceManager($this->priceManager);
        $this->popin->setSfg($this->sfg);
        $this->popin->setVersion($this->version);
        $this->popin->setLcdv16($this->lcdv16);
        $this->popin->initPopinNew();

        if (!empty($this->sfg)) {
            $this->popin->initPopinfinancement();
        }

        $lcdv4 = substr($this->lcdv16, 0, 4);
        $lcdv6 = substr($this->lcdv16, 4, 2);
        $this->popin->initPopinMotor(array('src' => self::MOTOR_V3D_BASE_URL.$lcdv4.'/'.$lcdv6.'/OverlayNew/ov_'.$this->version->Engine->id.'.png'), $this->version, $this->sfg);
    }

    /**
     * @param $finition
     *
     * @return array
     */
    public function getDetailsFinitions($finition)
    {
        $this->configurationSelect->addContext('Country', $this->countryCode)
            ->addContext('LanguageID', $this->languageCode)
            ->addCriteria('Model', $this->version->Model->id)
            ->addCriteria('GrBodyStyle', $finition->GrbodyStyle->id)
            ->addCriteria('GrCommercialName', $finition->GrCommercialName->id);
        $config = $this->configurationSelect->select();

        $this->priceManager->setTranslator($this->translator, $this->domain, $this->locale);
        $this->priceManager->setVersion($finition);

        $engines = $config->SelectResponse->Versions->Version;
        $motorNumber = count($engines);
        $detail =  array(
            'title' => $this->getSilhouetteTitle(),
            'subtitle' => $this->translate['finish'].' '.$finition->GrCommercialName->label,
            'img' =>  array('src' => self::VEHICLE_V3D_BASE_URL.$this->lcdv16.'&width=350&height=162&ratio=1&format=jpg&quality=100&view='.$this->angleView, 'alt' => $this->lcdv16),
            'text' => $this->trans('NDP_ONE_MOTOR_AVAILABLE', array('%motorName%' => $this->version->GrEngine->label.' '.$this->version->GrTransmissionType->label, '%motorNumber%' => $motorNumber)),
            'details' => $this->compareEquipments(),
        );

        $sfg = $this->priceManager->getSfg();

        if (!empty($sfg)) {
            $detail['mention'] = $this->priceManager->getFinancementDetailsTexts(PriceManager::FINANCEMENT_DETAILS_TEXT_LEGAL_TEXT);
        }

        if ($motorNumber > 1) {
            $detail['text'] = $this->trans('NDP_SEVERAL_MOTOR_AVAILABLE', array('%motorName%' => $this->version->GrEngine->label.' '.$this->version->GrTransmissionType->label, '%motorNumber%' => $motorNumber)); // avec le moteur $this->version->GrEngine->label <br> disponible en  $motorNumber motorisations.
        }

        // demander un prix mensuel et prix comptant pour chaque moteurs (récupérer le sfg pour chaque moteur)
        if (isset($this->siteSettings['VEHICULE_PRICE_DISPLAY']) && $this->siteSettings['VEHICULE_PRICE_DISPLAY'] && !empty($sfg) && !empty($this->sfg)) {
            $detail['libelle'] = array(
                'text' => '',
                'position' => false, // position paramètre généraux du site,
            );
            $detail['pricebymonth'] = array(
                'rent' => $this->priceManager->getFirstAccountValue(),//$this->sfg['startingPrice']['price'],
                'devise' => '',
                'taxe' => '',
                'mode' => 'monthly',

            );

            $detail['pricebymonth']['sum'] = $this->priceManager->getPriceByMonth();
            if ($this->sfg['startingPrice']['price'] !== $sfg['startingPrice']['price']) {
                $sum = $sfg['startingPrice']['price'] - $this->sfg['startingPrice']['price'];
                $detail['pricebymonth']['infoPrice'] = '+ '.$sum.' '.$sfg['startingPrice']['unit'];
            } else {
                $motorisation['pricebymonth']['infoPrice'] = $this->translate['include']; // inclus
            }
        }

        $detail['price']['sum'] = $this->priceManager->getCashPrice();
        if ($finition->Price->netPrice !== $this->version->Price->netPrice) {
            $detail['price']['infoPrice'] = $this->priceManager->getFormatedPrice($finition->Price->netPrice - $this->version->Price->netPrice);
        } else {
            $detail['price']['infoPrice'] = $this->translate['include']; // inclus
        }
        $detail['price']['devise'] = ''; //Pour ISOBAR

         return  $detail;
    }

    /**
     * @param $finition
     *
     * @return array
     */
    public function getDetailFinition($finition)
    {
        $equipment = array(
            'class' => 'equActive',
            'title' =>  $this->translate['equipments'].' '.$finition->GrCommercialName->label, // Equipements
        );
        $skf = $this->compareGrade->getEquipments($this->finishing->getCode());
        $standard = $this->version->StandardFeatures->Category;
        $optional = $this->version->OptionalFeatures->Category;
        $categories = $this->orderEquipments($skf->Category, $standard, $optional);
        $equipment['equActive'] = $this->initEquipment($categories);

        return array($equipment);
    }

}
