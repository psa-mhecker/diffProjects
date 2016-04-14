<?php

namespace PsaNdp\MappingBundle\Object\Block;

use PsaNdp\MappingBundle\Entity\Vehicle\PsaModelSilhouetteSite;
use PsaNdp\MappingBundle\Manager\PriceManager;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Cta;
use PsaNdp\MappingBundle\Object\Factory\CtaFactory;
use PsaNdp\MappingBundle\Object\Vignette;
use PsaNdp\MappingBundle\Translation\TranslatorAwareTrait;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Pf27CarPicker
 */
class Pf27CarPicker extends Content
{
    use TranslatorAwareTrait;

    /**
     * @var PriceManager
     */
    protected $priceManager;

    /**
     * @var array
     */
    protected $models;

    /**
     * @var boolean
     */
    protected $showPrice;

    /**
     * @var string
     */
    protected $mentionsLegales;

    /**
     * @var array
     */
    protected $ctas;

    /**
     * Pf27CarPicker constructor.
     *
     * @param CtaFactory $ctaFactory
     * @param PriceManager $priceManager
     */
    public function __construct(CtaFactory $ctaFactory, PriceManager $priceManager)
    {
        $this->ctaFactory = $ctaFactory;
        $this->priceManager = $priceManager;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->block->getZoneTitre();
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
     * @return Pf27CarPicker
     */
    public function setPriceManager($priceManager)
    {
        $this->priceManager = $priceManager;

        return $this;
    }

    /**
     * @param $models
     */
    public function setModels($models)
    {
        $this->models = $models;
    }

    /**
     * @return array
     */
    public function getModels()
    {
        $models = array();

        /** @var PsaModelSilhouetteSite $model */
        foreach ($this->models as $model) {

            $vignette = new Vignette();

            if (isset($model->cheapestVersion['ThumbnailURL'])){
                $vignette->setThumbnail($model->cheapestVersion['ThumbnailURL']);
            }

            if ($this->isShowPrice()){

                $this->priceManager->setDomain($model->getSite()->getSiteId());
                $this->priceManager->setLocale($model->getLangue()->getLangueCode());
                $this->priceManager->setModelSilhouetteInformation($model);
                $this->priceManager->setCheapest($model->cheapestVersion);
                $this->priceManager->setVersion($model->cheapestVersion);

                $vignette->setPrice($this->priceManager->getPriceValue());

            }
            $strip = $model->getFirstActiveStrip();
            $vignette->setVignetteCommercial($this->trans($strip));

            $stripClass = strtolower(str_replace('_','-',$strip));
            $vignette->setVignetteCommercialClass($stripClass);
            $vignette->setUrl($model->url);
            $cta = array(
                $this->ctaFactory->createFromArray(
                    array(
                        "href"   => $model->url,
                        "title" => $this->translate['NDP_DISCOVER'],
                        "color" => Cta::NDP_CTA_VERSION_LIGHT_BLUE,
                        "type"  => Cta::NDP_CTA_TYPE_BUTTON
                    )
                )
            );

           $cta2 =  $this->ctaFactory->create(
                        $this->ctaList[$model->cheapestVersion['LCDV6'].'-'.$model->cheapestVersion['GrBodyStyle']['Code']],
                        array("type"  => Cta::NDP_CTA_TYPE_SIMPLELINK)
                    )->toArray();

            $vignette->setCtaList(
                array_merge($cta, $cta2)
            );

            $vignette->setTitle($model->getCommercialLabel());

            $models[] = $vignette;
        }

        return $models;
    }

    /**
     * @return string
     */
    public function getMentionsLegales()
    {
        if ($this->block->getZoneTexte()){
            return $this->block->getZoneTexte();
        }

        return $this->priceManager->getLegalNotice();
    }

    /**
     * @param string $mentionsLegales
     */
    public function setMentionsLegales($mentionsLegales)
    {
        $this->mentionsLegales = $mentionsLegales;
    }

    /**
     * @return boolean
     */
    public function isShowPrice()
    {
        return $this->block->getZoneAttribut();
    }

    /**
     * @return array
     */
    public function getCtas()
    {
        $this->ctas = $this->ctaFactory->create($this->block->getCtaReferences());
        return $this->ctas;
    }
}
