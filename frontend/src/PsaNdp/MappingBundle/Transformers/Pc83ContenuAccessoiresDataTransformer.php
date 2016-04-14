<?php
namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Pc83ContenuAccessoires;
use PsaNdp\MappingBundle\Entity\PsaSitesEtWebservicesPsa;

/**
 * Class Pc83ContenuAccessoiresDataTransformer
 * Data transformer for Pc83ContenuAccessoires block
 * @package PsaNdp\MappingBundle\Transformers
 */
class Pc83ContenuAccessoiresDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    const STORE        = 2;
    const SHOWROOM     = 1;
    const BOTH         = 1;
    const DEALDER      = 2;
    const TECH         = 3;
    const MEDIA_FORMAT = 114;

    /**
     * @var Pc83ContenuAccessoires
     */
    protected $pc83ContenuAccessoires;

    /**
     * @param Pc83ContenuAccessoires $pc83ContenuAccessoires
     */
    public function __construct(Pc83ContenuAccessoires $pc83ContenuAccessoires)
    {
        $this->pc83ContenuAccessoires = $pc83ContenuAccessoires;
    }

    /**
     *  Fetching data slice contenu accessoires (pc83)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {

        $this->pc83ContenuAccessoires->setIsMobile($isMobile);
        $this->pc83ContenuAccessoires->setMediaServer($this->mediaServer);
        $this->pc83ContenuAccessoires->setError($this->trans('NDP_ERROR_ACCESSORY'));
        if (!empty($dataSource['accessoiresParams'])) {
            $this->pc83ContenuAccessoires->setParamsAccessoires($dataSource['accessoiresParams']);
            $this->pc83ContenuAccessoires->initCtaErreur();
        }
        if (!empty($dataSource['LCDV6'])) {
            $this->pc83ContenuAccessoires->setLcdv6($dataSource['LCDV6']);
            $this->pc83ContenuAccessoires->setMentionsLegale($this->trans('NDP_ACCESSORY_GENERIC_LEGAL_MENTION'));
            $this->pc83ContenuAccessoires->setReferenceLabel($this->trans('NDP_REFERENCE_LABEL'));
            $this->setLabelForAccessoriesLink($dataSource['siteAndWebservices']);
            $this->pc83ContenuAccessoires->initPriceForAccessories($dataSource['siteSettings']);
            $this->pc83ContenuAccessoires->initStore($dataSource['siteAndWebservices'], $this->trans('NDP_SEE_OUR_STORE'));
            if (!empty($dataSource['wsAccessoires'])) {
                $this->pc83ContenuAccessoires->setNoticeAccessoires($this->getNoticeAccessoires());
                if ($dataSource['defaultVisual']) {
                    $mediaPath = $this->mediaServer.$dataSource['defaultVisual']->getMediaPathWithFormat(self::MEDIA_FORMAT);
                    $this->pc83ContenuAccessoires->setMediaPath($mediaPath);
                }
                $this->pc83ContenuAccessoires->initAccessoires($dataSource['wsAccessoires']);
            }
        }
        $this->pc83ContenuAccessoires->setMapping($this->getMapping($isMobile));
        $this->pc83ContenuAccessoires->setDataFromArray($dataSource);

        return array(
            'slicePC83' =>  $this->pc83ContenuAccessoires,
        );
    }

    /**
     *
     * @return array
     */
    private function getNoticeAccessoires()
    {
        return [self::BOTH => $this->trans('NDP_NOTICE_BOTH_TYPE'), self::DEALDER => $this->trans('NDP_NOTICE_DEALER_TYPE'), self::TECH => $this->trans('NDP_NOTICE_TECH_TYPE')];
    }
    /**
     *
     * @param  bool  $isMobile
     * @return array
     */
    private function getMapping($isMobile)
    {
        $mapping = array(
            'datalayer' => 'dataLayer',
            'title' => 'title',
            'accessoires' => 'accessoires',
            'error' => 'error',
            'mentionsLegale' => 'mentionsLegale',
            'seeOurStore' => 'seeOurStore',
            'seeMoreItems' => 'seeMoreItems',
        );
        if ($isMobile) {
            $mapping = array(
                'datalayer' => 'dataLayer',
                'title' => 'title',
                'accessoires' => 'accessoires',
                'error' => 'error',
                'legalNoticesGA' => 'mentionsLegale',
                'shopLink' => 'seeOurStore',
                'seeMoreItems' => 'seeMoreItems',
            );
        }

        return $mapping;
    }

    /**
     *
     * @param PsaSitesEtWebservicesPsa $psaSitesEtWebservicesPsa
     */
    private function setLabelForAccessoriesLink(PsaSitesEtWebservicesPsa $psaSitesEtWebservicesPsa)
    {
        $label = '';
        switch ($psaSitesEtWebservicesPsa->getZoneBoutiqueShowroom()) {
            case self::SHOWROOM:
                $label = $this->trans('NDP_DISCOVER');
                break;
            case self::STORE:
                $label = $this->trans('NDP_BUY_ONLINE');
                break;
            default:
                //nothing
                break;
        }

        $this->pc83ContenuAccessoires->initTypeOfLinkAccessories($psaSitesEtWebservicesPsa, $label);
    }
}
