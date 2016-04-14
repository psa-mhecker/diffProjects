<?php

namespace PsaNdp\MappingBundle\Transformers;

use Doctrine\Common\Collections\ArrayCollection;
use PSA\MigrationBundle\Entity\Page\PsaPageZone;
use PSA\MigrationBundle\Repository\PsaPageRepository;
use PsaNdp\MappingBundle\Entity\PsaPageTypesCode;
use PsaNdp\MappingBundle\Manager\TreeManager;
use PsaNdp\MappingBundle\Object\Block\Pt2\Footer;

/**
 * Data transformer for Pt2Footer block
 */
class Pt2FooterDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    /**
     * @var Footer
     */
    private $footer;


    /**
     * Constructor
     *
     * @param Footer $footer
     *
     */
    public function __construct( Footer $footer)
    {
        $this->footer = $footer;
    }

    /**
     *  Fetching data slice Footer (pt2)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     *
     * @todo traduction
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $result = [];

        $this->footer->setDataFromArray($dataSource);

        if($isMobile) {

            $result['footerVersionSection'] = array(
                'title' => 'Version',
                'desktopTitle' => 'Desktop',
                'mobileTitle' => 'Mobile',
                'target' => '_self',
                'mobileURL' => '#'
            );

        }

        $result = array(
            'footer'=>$this->footer
        );
        return $result;
    }
}
