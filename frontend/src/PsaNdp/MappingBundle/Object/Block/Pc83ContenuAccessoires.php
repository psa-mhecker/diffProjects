<?php
namespace PsaNdp\MappingBundle\Object\Block;

use PSA\MigrationBundle\Entity\Cta\PsaCta;
use PSA\MigrationBundle\Entity\Cta\PsaPageZoneCta;
use PSA\MigrationBundle\Repository\PsaCtaRepository;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Entity\PsaSitesEtWebservicesPsa;
use PsaNdp\MappingBundle\Entity\Accessories\PsaAccessoriesSite;
use PSA\MigrationBundle\Entity\Cta\PsaCtaReferenceInterface;

/**
 * Class Pc83ContenuAccessoires
 * @package PsaNdp\MappingBundle\Object\Block
 */
class Pc83ContenuAccessoires extends Content
{
    const CTA_REF                     = 2;
    const CTA_NEW                     = 3;
    const TTC                         = "TTC";
    const HT                          = "HT";
    const AFTER_PRICE                 = 2;
    const DEV                         = "VM";
    const ISOBAR_CTA_STYLE_SIMPLELINK = 'STYLE_SIMPLELINK'; // Simple Link
    const CONSUMMER                   = "CFGAP";
    const BOTH                        = 1;
    const DEALDER                     = 2;
    const TECH                        = 3;
    const MEDIA_ACCESSOIRE_PROXY      = '/extimage.php?service=aoa&image=';
    const MEDIA_BASE_URL              = 'http://aoaccessoire.inetpsa.com/aoa00Pds/servlet';
    const IMG_TYPE = '0177487678999sxl';

    protected $mapping = [];

    /**
     * @var PsaPageZoneConfigurableInterface
     */
    protected $block;

    /**
     *
     * @var string
     */
    protected $mediaPath;

    /**
     *
     * @var string
     */
    protected $mediaServer;

    /**
     *
     * @var bool
     */
    protected $isMobile;

    /**
     *
     * @var array
     */
    protected $accessoires;

    /**
     *
     * @var string
     */
    protected $error;

    /**
     *
     * @var array
     */
    protected $errorLink;

    /**
     *
     * @var array
     */
    protected $seeMoreItems;

    /**
     *
     * @var array
     */
    protected $seeOurStore;

    /**
     *
     * @var string
     */
    protected $mentionsLegale;

    /**
     *
     * @var PsaCtaRepository
     */
    protected $psaCtaRepository;

    /**
     *
     * @var string
     */
    protected $referenceLabel;

    /**
     *
     * @var array
     */
    protected $linkForAccessories;

    /**
     *
     * @var array
     */
    protected $paramsForPrice;

    /**
     *
     * @var string
     */
    protected $lcdv6;

    /**
     *
     * @var array
     */
    protected $noticeAccessoires;

    /**
     *
     * @var PsaAccessoriesSite
     */
    protected $paramsAccessoires;

    /**
     * @var array
     */
    protected $siteSettings;

    /**
     *
     * @param PsaCtaRepository $psaCtaRepository
     */

    public function __construct(PsaCtaRepository $psaCtaRepository)
    {
        $this->psaCtaRepository = $psaCtaRepository;
    }

    /**
     * @return PsaPageZoneConfigurableInterface
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @param PsaPageZoneConfigurableInterface $block
     */
    public function setBlock(PsaPageZoneConfigurableInterface $block)
    {
        $this->block = $block;
        $this->title = $block->getZoneTitre();
        $this->initSeeMore($block);
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
     * @return Pc83ContenuAccessoires
     */
    public function setMediaServer($mediaServer)
    {
        $this->mediaServer = $mediaServer;

        return $this;
    }

    /**
     * @param PsaPageZoneConfigurableInterface $block
     */
    public function initSeeMore(PsaPageZoneConfigurableInterface $block)
    {
        $data = null;
        $ctaRef = $block->getCtaReferences();
        if (!empty($ctaRef) && $ctaRef->first()->getReferenceStatus() != PsaCtaReferenceInterface::PSA_REFERENCE_STATUS_DISABLED ) {

            /** @var PsaCtaReferenceInterface $ctaReference */
            $ctaReference = $block->getCtaReferences()->first();
            $cta = $ctaReference->getCta();
            $data['title']  = $cta->getTitle();
            $data['href']   = $cta->getAction();
            $data['target'] = $ctaReference->getTarget();
        }
        $this->setSeeMoreItems($data);
    }

    /**
     *
     * @param PsaSitesEtWebservicesPsa $psaSitesEtWebservicesPsa
     */
    public function initStore(PsaSitesEtWebservicesPsa $psaSitesEtWebservicesPsa, $title = '')
    {
        $enable = false;
        $this->setSeeOurStore(null);
        if ($this->paramsAccessoires) {
            $enable = $this->paramsAccessoires->getLinkDerivaties();
        }
        $url = $psaSitesEtWebservicesPsa->getZoneUrlProduitDerives();
        if (!empty($url) && $enable) {
            $this->setSeeOurStore(array('href' => $url, 'title' => $title, 'target' => '_blank', 'url' => $url));
        }
    }

    /**
     *
     * @param mixed $wsAccessoires
     */
    public function initAccessoires($wsAccessoires)
    {
        $accessoires = [];
        $haveParams = false;
        if ($this->paramsAccessoires) {
            $haveParams = true;
        }
        $replacement = [
            '##LCDV6##' =>$this->lcdv6,
            '##CONSUMMER##' => self::CONSUMMER,
            '##CULTURE##' => str_replace('_','-',$wsAccessoires->accessoryList->accessoryLocal[0]->locale),
        ];
        $this->linkForAccessories['href'] = strtr($this->linkForAccessories['href'], $replacement);
        $accessories = $this->groupByUnivers($wsAccessoires->accessoryList->accessoryLocal[0]->accessory, $haveParams);

        foreach ($accessories as $code=> $group) {
            foreach ($group as $oneAccessoire) {

                $refLink = str_replace('##REF##', $oneAccessoire->reference, $this->linkForAccessories['href']);
                $accessoires[$code]['titleAccessoires'] = $oneAccessoire->universe->label;
                $accessoires[$code]['order'] = $oneAccessoire->universe->order;
                $img = $this->getImg($oneAccessoire);

                $paragraph = $this->buildParagraph($oneAccessoire->arguments);

                $contentAccordion = [
                    'order' => $oneAccessoire->universe->subUniverses->subUniverse->order,
                    'subTitleAccessoires' => $oneAccessoire->designation.' '.$oneAccessoire->compDesignation,
                    'imgAccessoires' => [
                        'src' => $img,
                        'alt' => $oneAccessoire->universe->label,
                    ],
                    'descAccessoires' => $paragraph,
                    'refAccessoires' => ['label' => $this->referenceLabel, 'value' => $oneAccessoire->reference],
                    'priceAccessoires' => $this->getPriceOfAccesorie($oneAccessoire->pricing),
                ];
                if (!empty($this->linkForAccessories['href'])) {
                    $contentAccordion['linkToBayAccessoires']  =  $this->getCtaForAccessory($refLink);
                }

                $contentAccordion['noticeAccessoires'] = '';
                if (isset($oneAccessoire->techRefs)) {
                    $contentAccordion['noticeAccessoires'] = $this->initNoticeAccessoires($oneAccessoire->techRefs, $oneAccessoire->requiredDealerInst);
                }

                $accessoires[$code]['contentAccordion'][] = $contentAccordion;

            }

        }

        if (!empty($accessoires)) {
            $this->setAccessoires($accessoires);
        }
    }

    protected function getImg($oneAccessoire)
    {
        // regle de gestion expliquée dans https://jira-projets.mpsa.com/VTIS/browse/NDP-3791
        // image par defaut si on ne trouve pas d'image convenant
        $img = $this->mediaPath;
        $imgObjet = null;
        if (!empty($oneAccessoire->files)) {
          //filtre les images par type
          $typed = array_filter($oneAccessoire->files,function($file)  {
                return $file->type  == self::IMG_TYPE;
            });
          // on doit prendre la premiere qui a le bon model/silhouette
           foreach ($typed as $file) {
                foreach($file->applFiles->appl->lcdvs->lcdvCode as $bodyStyle)
                if ($file->applFiles->appl->productLine.$bodyStyle == $this->lcdv6 ) {
                    $imgObjet = $file;
                    break;
                }
           }
           // si aucun on prend la premiere filtré
           if(is_null($imgObjet) && !empty($typed)) {
               $imgObjet = reset($typed);
           }
        }

        if ($imgObjet) {
            $img = $this->getMediaUrl($imgObjet->relativePath);
        }

        return $img;
    }

    public function getMediaUrl($mediaUrl)
    {
        $url = $this->mediaServer.str_replace(self::MEDIA_BASE_URL, self::MEDIA_ACCESSOIRE_PROXY, $mediaUrl);

        return $url;
    }

    protected function groupByUnivers($accessories, $haveParams)
    {
        $max = $maxByUnivers = count($accessories);

        if ($haveParams) {
            $max = $this->paramsAccessoires->getMaxAccessories();
            $maxByUnivers =  $this->paramsAccessoires->getMaxAccessoriesByUnivers();
        }

        $univers = [];
        $temp = [];
        $return = [];
        // récupération de la liste des univers
        foreach ($accessories as $accessory) {
            $univers[ $accessory->universe->order] = $accessory->universe->code;
        }
        // init des tableau dans l'ordre
        ksort($univers);
        foreach ($univers as $code) {
            $temp[$code] = [];
            $return[$code] = [];
        }
        // creation d'une liste d'accessoire par univers
        foreach ($accessories as $key=>$accessory) {
            $temp[$accessory->universe->code][] = $key;
        }
        $count = 0;
        while ($count < $max && !empty($temp)) {
            // on parcours chaque univers en ajoutant un accessoire de celui jusqu'as ce qu'on est atteind le max
            // ou le max par univers
            foreach ($univers as $order=>$code) {
                if(!empty($temp[$code])) {
                    $key = array_shift($temp[$code]);
                    // es ce qu'on a atteind le max par univers ?
                    if((count($return[$code]) < $maxByUnivers) && $count < $max )  {
                        $return[$code][] = $accessories[$key];
                        $count++;
                    }

                }
                // quand un tableau est vide on le supprime de $temp
                if(empty ($temp[$code])) {
                    unset($temp[$code]);
                }
            }
        }


        return $return;
    }

    /**
     *
     * @param array   $techRefs
     * @param boolean $requiredDealerInst
     *
     * @return string
     */
    public function initNoticeAccessoires($techRefs, $requiredDealerInst)
    {
        $legal = $this->noticeAccessoires[self::BOTH];
        if (!$requiredDealerInst && is_array($techRefs)) {
            $legal = $this->noticeAccessoires[self::DEALDER];
        }
        if ($requiredDealerInst && !is_array($techRefs)) {
            $legal = $this->noticeAccessoires[self::TECH];
        }
        if (!$requiredDealerInst && !is_array($techRefs)) {
            $legal = '';
        }

        return $legal;
    }
    /**
     *
     * @param  string $refLink
     *                         *
     * @return array
     */
    public function getCtaForAccessory($refLink)
    {
        $cta = null;
        $cta = ['href' => $refLink, 'title' => $this->linkForAccessories['title'], 'target' => '_blank'];
        if ($this->isMobile) {
            $cta = [
                'ctaList' => [
                    0 => [
                        'style' => self::ISOBAR_CTA_STYLE_SIMPLELINK,
                        'target' => '_blank',
                        'url' => $refLink,
                        'title' => $this->linkForAccessories['title'],
                    ],
                ],
            ];
        }

        return $cta;
    }

    /**
     *
     * @param string $paragraph
     *
     * @return array
     */
    public function buildParagraph($paragraph)
    {
        $paragraph = explode(PHP_EOL, $paragraph);
        $data = [];
        foreach ($paragraph as $onePart) {
            $data[] = [ 'paragraph' => $onePart];
        }

        return $data;
    }
    /**
     *
     * @param array $pricing
     *
     * @return string
     */
    public function getPriceOfAccesorie($pricing)
    {
        $price = '';
        foreach ($pricing as $onePrice) {
            $formatedPrice = $this->getFormatedPrice($onePrice->{$this->paramsForPrice['FIELD']});
            if ($onePrice->brand == 'AP') {
                $price = $formatedPrice.$this->paramsForPrice['SYMBOL'].' '.$this->paramsForPrice['TYPE'];
                if ($this->paramsForPrice['POSITION'] != self::AFTER_PRICE) {
                    $price = $this->paramsForPrice['SYMBOL'].$formatedPrice.' '.$this->paramsForPrice['TYPE'];
                }
            }
        }

        return $price;
    }

    /**
     * @param $price
     *
     * @return string
     */
    public function getFormatedPrice($price)
    {
        $tempPrice = (float) str_replace(',', '.', $price);// des fois que le prix contienne une virgule pour le prix au lieu du .
        $nbDecimal = ($this->siteSettings['OTHER_PRICE_NB_DECIMAL'] > 0) ? $this->siteSettings['OTHER_PRICE_NB_DECIMAL'] : 2;
        $decimalPoint = isset($this->siteSettings['NB_DELIMITER_DECIMAL']) ? $this->siteSettings['NB_DELIMITER_DECIMAL'] : '.';
        $thousandSep = isset($this->siteSettings['NB_DELIMITER_THOUSAND']) ? $this->siteSettings['NB_DELIMITER_THOUSAND'] : ' ';

        $return = number_format($tempPrice, $nbDecimal, $decimalPoint, $thousandSep);

        return $return;
    }

    /**
     *
     * @param array $siteSettings
     */
    public function initPriceForAccessories($siteSettings)
    {
        $this->paramsForPrice = [];
        switch ($siteSettings['OTHER_PRICE_TYPE']) {
            case self::TTC:
                $this->paramsForPrice['TYPE']   = self::TTC;
                $this->paramsForPrice['SYMBOL'] = $siteSettings['CURRENCY_SYMBOL'];
                $this->paramsForPrice['FIELD'] = 'pvpTTCWP';
                $this->paramsForPrice['POSITION'] = $siteSettings['CURRENCY_POSITION'];
                break;
            case self::HT:
                $this->paramsForPrice['TYPE']   = self::HT;
                $this->paramsForPrice['SYMBOL'] = $siteSettings['CURRENCY_SYMBOL'];
                $this->paramsForPrice['FIELD'] = 'pvpHTWP';
                $this->paramsForPrice['POSITION'] = $siteSettings['CURRENCY_POSITION'];
                break;
            default:
                //nothing
                break;
        }
    }

    /**
     * Methode pour la gestion du CTA d'erreur
     */
    public function initCtaErreur()
    {
        switch ($this->paramsAccessoires->getCtaErreur()) {
            case self::CTA_REF:
                $cta = $this->psaCtaRepository->find($this->paramsAccessoires->getCtaErreurId());
                $ctaReference = new PsaPageZoneCta();
                $ctaReference->setCta($cta);
                $ctaReference->setStyle($this->paramsAccessoires->getCtaErreurStyle());
                $ctaReference->setTarget($this->paramsAccessoires->getCtaErreurTarget());
                $ct = $this->ctaFactory->create($ctaReference);
                $this->setErrorLink($ct);
                break;
            case self::CTA_NEW:
                $cta = new PsaCta();
                $cta->setAction($this->paramsAccessoires->getCtaErreurAction());
                $cta->setTitle($this->paramsAccessoires->getCtaErreurTitle());
                $ctaReference = new PsaPageZoneCta();
                $ctaReference->setCta($cta);
                $ctaReference->setStyle($this->paramsAccessoires->getCtaErreurStyle());
                $ctaReference->setTarget($this->paramsAccessoires->getCtaErreurTarget());
                $ct = $this->ctaFactory->create($ctaReference);
                $this->setErrorLink($ct);
                break;
            default:
                //nothing
                break;
        }
    }

    /**
     * @param PsaSitesEtWebservicesPsa $psaSitesEtWebservicesPsa
     * @param string                   $label
     */
    public function initTypeOfLinkAccessories(PsaSitesEtWebservicesPsa $psaSitesEtWebservicesPsa, $label = '')
    {
        $link = [];
        $link['href'] = $psaSitesEtWebservicesPsa->getZoneShowroomUrlWebFicheAccessoires();
        if ($this->getIsMobile()) {
            $link['href'] = $psaSitesEtWebservicesPsa->getZoneShowroomUrlWebFicheAccessoires();
        }
        $link['title'] = $label;
        if ($psaSitesEtWebservicesPsa->getZoneBoutiqueShowroom() == 0) {
            $link = [];
        }

        $this->setLinkForAccessories($link);
    }

    /**
     * @return PsaCtaRepository
     */
    public function getPsaCtaRepository()
    {
        return $this->psaCtaRepository;
    }

    /**
     * @param PsaCtaRepository $psaCtaRepository
     *
     * @return $this
     */
    public function setPsaCtaRepository(PsaCtaRepository $psaCtaRepository)
    {
        $this->psaCtaRepository = $psaCtaRepository;

        return $this;
    }

    /**
     * @return array
     */
    public function getLinkForAccessories()
    {
        return $this->linkForAccessories;
    }

    /**
     *
     * @param array $linkForAccessories
     *
     * @return $this
     */
    public function setLinkForAccessories($linkForAccessories)
    {
        $this->linkForAccessories = $linkForAccessories;

        return $this;
    }

    /**
     *
     * @return bool
     */
    public function getIsMobile()
    {
        return $this->isMobile;
    }

    /**
     * @return array
     */
    public function getParamsForPrice()
    {
        return $this->paramsForPrice;
    }

    /**
     * @param array $paramsForPrice
     *
     * @return $this
     */
    public function setParamsForPrice($paramsForPrice)
    {
        $this->paramsForPrice = $paramsForPrice;

        return $this;
    }

    /**
     * @param bool $isMobile
     *
     * @return $this
     */
    public function setIsMobile($isMobile)
    {
        $this->isMobile = $isMobile;

        return $this;
    }
    /**
     *
     * @return array
     */
    public function getAccessoires()
    {
        return $this->accessoires;
    }

    /**
     * @param array $accessoires
     *
     * @return $this
     */
    public function setAccessoires($accessoires)
    {
        $this->accessoires = $accessoires;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     *
     * @return array
     */
    public function getErrorLink()
    {
        return $this->errorLink;
    }

    /**
     *
     * @return array
     */
    public function getSeeMoreItems()
    {
        return $this->seeMoreItems;
    }

    /**
     *
     * @return array
     */
    public function getSeeOurStore()
    {
        return $this->seeOurStore;
    }

    /**
     *
     * @return string
     */
    public function getMentionsLegale()
    {
        return $this->mentionsLegale;
    }

    /**
     * @param string $error
     *
     * @return $this
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @param array $errorLink
     *
     * @return $this
     */
    public function setErrorLink($errorLink)
    {
        $this->errorLink = $errorLink;

        return $this;
    }

    /**
     *
     * @param array $seeMoreItems
     *
     * @return $this
     */
    public function setSeeMoreItems($seeMoreItems)
    {
        $this->seeMoreItems = $seeMoreItems;

        return $this;
    }

    /**
     *
     * @param null|array $seeOurStore
     *
     * @return $this
     */
    public function setSeeOurStore($seeOurStore )
    {
        $this->seeOurStore = $seeOurStore;

        return $this;
    }

    /**
     *
     * @param string $mentionsLegale
     *
     * @return $this
     */
    public function setMentionsLegale($mentionsLegale)
    {
        $this->mentionsLegale = $mentionsLegale;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getReferenceLabel()
    {
        return $this->referenceLabel;
    }

    /**
     * @param string $referenceLabel
     *
     * @return $this
     */
    public function setReferenceLabel($referenceLabel)
    {
        $this->referenceLabel = $referenceLabel;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getLcdv6()
    {
        return $this->lcdv6;
    }

    /**
     * @param string $lcdv6
     *
     * @return $this
     */
    public function setLcdv6($lcdv6)
    {
        $this->lcdv6 = $lcdv6;

        return $this;
    }

    /**
     *
     * @return array
     */
    public function getMapping()
    {
        return $this->mapping;
    }
    /**
     * @param array $mapping
     *
     * @return $this
     */
    public function setMapping($mapping)
    {
        $this->mapping = $mapping;

        return $this;
    }

    /**
     * @return array
     */
    public function getNoticeAccessoires()
    {
        return $this->noticeAccessoires;
    }

    /**
     * @param array $noticeAccessoires
     *
     * @return $this
     */
    public function setNoticeAccessoires($noticeAccessoires)
    {
        $this->noticeAccessoires = $noticeAccessoires;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getMediaPath()
    {
        return $this->mediaPath;
    }

    /**
     *
     * @param  string                 $mediaPath
     * @return Pc83ContenuAccessoires
     */
    public function setMediaPath($mediaPath)
    {
        $this->mediaPath = $mediaPath;

        return $this;
    }

    /**
     *
     * @return PsaAccessoriesSite
     */
    public function getParamsAccessoires()
    {
        return $this->paramsAccessoires;
    }

    /**
     *
     * @param PsaAccessoriesSite $paramsAccessoires
     *
     * @return Pc83ContenuAccessoires
     */
    public function setParamsAccessoires(PsaAccessoriesSite $paramsAccessoires)
    {
        $this->paramsAccessoires = $paramsAccessoires;

        return $this;
    }
}
