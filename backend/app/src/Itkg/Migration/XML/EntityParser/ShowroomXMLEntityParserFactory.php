<?php

namespace Itkg\Migration\XML\EntityParser;

use Itkg\Migration\Transaction\PsaShowroomEntityFactory;
use Itkg\Migration\XML\XPathQueryHelper;
use Itkg\Utils\PsaDatabaseBlockIdMapper;
use PsaNdp\MappingBundle\Utils\SiteConfiguration;
use PsaNdp\WebserviceConsumerBundle\Webservices\ConfigurationEngineSelect;

class ShowroomXMLEntityParserFactory
{
    const WIDGET_TYPE_SINGLE_LARGE = 'single_large';
    const WIDGET_TYPE_SINGLE_LARGE_COMPARATOR = 'single_large(comparator)';
    const WIDGET_TYPE_SINGLE_SLIDER = 'single_slider';
    const WIDGET_TYPE_SINGLE_PICTURE = 'single_picture';
    const WIDGET_TYPE_LEGAL = 'legal';
    const WIDGET_TYPE_DOUBLE_BASIC = 'double_basic';
    const WIDGET_TYPE_SINGLE_LEFT_BASIC = 'single_left_basic';
    const WIDGET_TYPE_SINGLE_RIGHT_BASIC = 'single_right_basic';
    const WIDGET_TYPE_SINGLE_LEFT_ADVANCED = 'single_left_advanced';
    const WIDGET_TYPE_SINGLE_RIGHT_ADVANCED = 'single_right_advanced';
    const WIDGET_TYPE_SINGLE_SLIDER_LEFT = 'single_slider_left';
    const WIDGET_TYPE_SINGLE_SLIDER_RIGHT = 'single_slider_right';
    const WIDGET_TYPE_TRIPLE_BASIC = 'triple_basic';
    const WIDGET_TYPE_TECH_PUSH_CONTENT = 'tech_content_push';
    const WIDGET_TYPE_SINGLE_MULTIPLE = 'single_multiple';
    const WIDGET_TYPE_THREE_QUARTS = 'three_quarts';
    const WIDGET_TYPE_TECH_SLIDER = 'tech_slider';
    const WIDGET_TYPE_DISCOVER_360 = 'discover_360';
    const WIDGET_TYPE_TECH_MOTORLIST = 'tech_motorlist';
    const SLICE_PN13 = 'PN13';
    const SLICE_PN7 = 'PN7';
    const SLICE_PN14 = 'PN14';
    const SLICE_PF2 = 'PF2';
    const PAGE_TYPE_GALLERY = 'gallery';
    const PAGE_TYPE_PRICES = 'prices';

    /** @var ConfigurationEngineSelect */
    private $wsConfigEngine;

    /** @var PsaShowroomEntityFactory */
    private $entityFactory;

    /** @var XPathQueryHelper */
    private $xPathQueryHelper;

    /** @var PsaDatabaseBlockIdMapper */
    protected $blockIdMapper;

    /** @var SiteConfiguration */
    protected $siteConfiguration;

    /**
     * @param PsaShowroomEntityFactory  $entityFactory
     * @param XPathQueryHelper          $xPathQueryHelper
     * @param ConfigurationEngineSelect $wsConfigEngine
     * @param PsaDatabaseBlockIdMapper  $blockIdMapper
     * @param SiteConfiguration         $siteConfiguration
     */
    public function __construct(
        PsaShowroomEntityFactory $entityFactory,
        XPathQueryHelper $xPathQueryHelper,
        ConfigurationEngineSelect $wsConfigEngine,
        PsaDatabaseBlockIdMapper $blockIdMapper,
        SiteConfiguration $siteConfiguration
    ) {
        $this->entityFactory = $entityFactory;
        $this->xPathQueryHelper = $xPathQueryHelper;
        $this->wsConfigEngine = $wsConfigEngine;
        $this->blockIdMapper = $blockIdMapper;
        $this->siteConfiguration = $siteConfiguration;
    }

    /**
     * @return ShowroomXMLPageParser
     */
    public function createPageParser()
    {
        return new ShowroomXMLPageParser($this->entityFactory, $this->xPathQueryHelper, $this);
    }

    /**
     * @return ShowroomXMLSliceCtasParser
     */
    public function createCtasParser()
    {
        return new ShowroomXMLSliceCtasParser($this->entityFactory, $this->xPathQueryHelper, $this);
    }

    /**
     * @param string $widgetType
     *
     * @return ShowroomXMLSliceMediasParser
     */
    public function createMediasParser($widgetType)
    {
        return new ShowroomXMLSliceMediasParser($this->entityFactory, $this->xPathQueryHelper, $this, $widgetType);
    }

    /**
     * @param string $widgetType
     * @param string $articleType
     *
     * @return ShowroomXMLSliceParserInterface
     */
    public function createSliceParser($widgetType, $articleType = null)
    {
        switch ($widgetType) {
            case self::WIDGET_TYPE_SINGLE_LARGE:
            case self::WIDGET_TYPE_SINGLE_SLIDER:
            case self::WIDGET_TYPE_SINGLE_PICTURE:
            case self::WIDGET_TYPE_LEGAL:
                return new ShowroomXMLSlicePc5UneColonneParser(
                    $this->entityFactory, $this->xPathQueryHelper, $this, $widgetType
                );
            case self::WIDGET_TYPE_SINGLE_LARGE_COMPARATOR:
                return new ShowroomXMLSlicePf6DragAndDropParser(
                    $this->entityFactory, $this->xPathQueryHelper, $this, $widgetType
                );

            case self::WIDGET_TYPE_DOUBLE_BASIC:
                return new ShowroomXMLSlicePc7DeuxColonnesParser(
                    $this->entityFactory, $this->xPathQueryHelper, $this, $widgetType
                );

            case self::WIDGET_TYPE_SINGLE_LEFT_BASIC:
            case self::WIDGET_TYPE_SINGLE_RIGHT_BASIC:
            case self::WIDGET_TYPE_SINGLE_LEFT_ADVANCED:
            case self::WIDGET_TYPE_SINGLE_RIGHT_ADVANCED:
            case self::WIDGET_TYPE_SINGLE_SLIDER_LEFT:
            case self::WIDGET_TYPE_SINGLE_SLIDER_RIGHT:
                return new ShowroomXMLSlicePc9Contenu1Article1VisuelParser(
                    $this->entityFactory, $this->xPathQueryHelper, $this, $widgetType
                );
            case self::WIDGET_TYPE_TRIPLE_BASIC:
            case self::WIDGET_TYPE_TECH_PUSH_CONTENT:
                return new ShowroomXMLSlicePc12TroisColonnesTexteParser(
                    $this->entityFactory, $this->xPathQueryHelper, $this, $widgetType
                );
            case self::WIDGET_TYPE_SINGLE_MULTIPLE:
                return new ShowroomXMLSlicePc68Contenu1Article2Ou3VisuelsParser(
                    $this->entityFactory, $this->xPathQueryHelper, $this, $widgetType
                );
            case self::WIDGET_TYPE_THREE_QUARTS:
                return new ShowroomXMLSlicePc69Contenu2ColonnesParser(
                    $this->entityFactory, $this->xPathQueryHelper, $this, $widgetType
                );
            case self::WIDGET_TYPE_TECH_SLIDER:
                return new ShowroomXMLSlicePc77DimensionVehiculeParser(
                    $this->entityFactory, $this->xPathQueryHelper, $this, $widgetType
                );
            case self::WIDGET_TYPE_DISCOVER_360:
                return;
            case self::WIDGET_TYPE_TECH_MOTORLIST:
                // PF53 to ignore
                return;
            // Slice generated without a widget type xml
            case self::SLICE_PN13:
                return new ShowroomXMLSlicePn13Anchor(
                    $this->entityFactory, $this->xPathQueryHelper, $this, $widgetType,
                    $this->wsConfigEngine, $this->blockIdMapper, $this->siteConfiguration
                );
            case self::SLICE_PN14:
                return new ShowroomXMLSlicePn14NavigationParser(
                    $this->entityFactory, $this->xPathQueryHelper, $this, $widgetType, $this->wsConfigEngine
                );
            case self::SLICE_PF2:
                return new ShowroomXMLSlicePf2PresentationShowroomParser(
                    $this->entityFactory, $this->xPathQueryHelper, $this, $widgetType, $this->wsConfigEngine
                );
            case self::SLICE_PN7:
                return new ShowroomXMLSlicePn7EnteteParser(
                    $this->entityFactory, $this->xPathQueryHelper, $this, $widgetType, $this->wsConfigEngine
                );
        }

        switch ($articleType) {
            case self::PAGE_TYPE_GALLERY:
                return new ShowroomXMLSlicePc23MurMediaParser(
                    $this->entityFactory, $this->xPathQueryHelper, $this, $articleType
                );
            case self::PAGE_TYPE_PRICES:
                // PF58 to ignore
                return;
        }

        return;
    }
}
