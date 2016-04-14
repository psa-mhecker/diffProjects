<?php
namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Pn14NavigationConfishow;

/**
 * Class Pn14NavigationConfishowDataTransformer
 * Data transformer for Pn1 NavigationConfishow block
 * @package PsaNdp\MappingBundle\Transformers
 */
class Pn14NavigationConfishowDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    const NDP_TITLE_MOBILE= 'NDP_TITLE_NAV_SHOWROOM_MOBILE';
    const NDP_HOME= 'NDP_HOME';

    /**
     * @var Pn14NavigationConfishow
     */
    protected $pn14NavigationConfishow;

    /**
     * @param Pn14NavigationConfishow $pn14NavigationConfishow
     */
    public function __construct(Pn14NavigationConfishow $pn14NavigationConfishow)
    {
        $this->pn14NavigationConfishow = $pn14NavigationConfishow;
    }

    /**
     *  Fetching data slice NavigationConfishow (pn14)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        if($isMobile && !empty($dataSource['menu'])) {
            $dataSource['menu'][0]['title'] = $this->trans(self::NDP_HOME);
        }
        $this->pn14NavigationConfishow->setIsMobile($isMobile);
        $titleMobile = $this->trans(
            self::NDP_TITLE_MOBILE,
            array('%modelSilhouette%'=>$dataSource['modelSilhouette'])
        );
        $this->pn14NavigationConfishow->setTitleMobile($titleMobile);

        $pn14 = $this->pn14NavigationConfishow->setDataFromArray($dataSource);

        return array(
           'slicePN14' => $pn14,
        );
    }
}
