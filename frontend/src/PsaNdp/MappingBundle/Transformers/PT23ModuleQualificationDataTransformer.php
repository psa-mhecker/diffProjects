<?php
namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\PT23ModuleQualification;

/**
 * Class PT23ModuleQualificationDataTransformer
 * Data transformer for PT23ModuleQualification block
 * @package PsaNdp\MappingBundle\Transformers
 */
class PT23ModuleQualificationDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{

    /**
     * @var PT23ModuleQualification
     */
    protected $pT23ModuleQualification;

    /**
     * @param PT23ModuleQualification $pT23ModuleQualification
     */
    public function __construct(PT23ModuleQualification $pT23ModuleQualification)
    {
        $this->pT23ModuleQualification = $pT23ModuleQualification;
    }

    /**
     *  Fetching data slice Module Qualification (PT23)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {

        $PT23 = $this->pT23ModuleQualification->setDataFromArray($dataSource);

        return array(
           'slicePT23' =>  $PT23
        );
    }
}
