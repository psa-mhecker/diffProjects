<?php
namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\AbstractObject;
use PsaNdp\MappingBundle\Object\Block\Pc79MurMediaManuel;
use PsaNdp\MappingBundle\Utils\StreamlikeMedia;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

/**
 * Class Pc79MurMediaManuelDataTransformer
 * Data transformer for Pc79MurMediaManuel block
 * @package PsaNdp\MappingBundle\Transformers
 */
class Pc79MurMediaManuelDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    const TYPE_OF_MULTI   = 'MUR_MEDIA_MANUEL';

    /**
     * @var Pc79MurMediaManuel
     */
    protected $pc79MurMediaManuel;

    /**
     * @var StreamlikeMedia
     */
    protected $streamLikeMedia;

    /**
     * @param Pc79MurMediaManuel $pc79MurMediaManuel
     * @param StreamlikeMedia $streamlikeMedia
     */
    public function __construct(Pc79MurMediaManuel $pc79MurMediaManuel, StreamlikeMedia $streamlikeMedia)
    {
        $this->pc79MurMediaManuel = $pc79MurMediaManuel;
        $this->streamLikeMedia = $streamlikeMedia;
    }

    /**
     *  Fetching data slice media wall (pc79)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $dataSource['close'] = $this->trans(AbstractObject::NDP_CLOSE);

        $this->pc79MurMediaManuel->setDataFromArray($dataSource);
        $this->pc79MurMediaManuel->initializeGallery();

        return array(
           'slicePC79' => $this->pc79MurMediaManuel
        );
    }
}
