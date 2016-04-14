<?php
namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\PC95InterestedBy;

/**
 * Class PC95InterestedByDataTransformer
 * Data transformer for PC95InterestedBy block
 * @package PsaNdp\MappingBundle\Transformers
 */
class PC95InterestedByDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{

    /**
     * @var PC95InterestedBy
     */
    protected $pC95InterestedBy;

    /**
     * @param PC95InterestedBy $pC95InterestedBy
     */
    public function __construct(PC95InterestedBy $pC95InterestedBy)
    {
        $this->pC95InterestedBy = $pC95InterestedBy;
    }

    /**
     *  Fetching data slice InterestedBy (PC95)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {

        $dataSource['translate'] = array(
            'close' => $this->trans('NDP_CLOSE'),
            'from' => $this->trans('NDP_FROM'),
        );
        foreach ($dataSource['models'] as $idx=>$model) {
            $dataSource['models'][$idx]['translate'] =  $dataSource['translate'];
        }

        $dataSource['title'] = $dataSource['block']->getZoneTitre();
        // cta changer de vehicule mobile
        $dataSource['ctaList'] = array(
            array(
                'style' => 'cta',
                'url' => $dataSource['block']->getZoneUrl(),
                'version' => '4',
                'dimension' => '12',
                'title' => $this->trans(PC95InterestedBy::NDP_CHANGE_VEHICLE)// 'changer de véhicule'
            ),
        );


        $PC95 = $this->pC95InterestedBy
            ->setTranslator($this->translator, $this->domain, $this->locale)
            ->setDataFromArray($dataSource);

        return array('slicePC95'=>$PC95);

    }
}
