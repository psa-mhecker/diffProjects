<?php

namespace PsaNdp\ApiBundle\Transformers;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\ApiBundle\Facade\ColorPickerFacade;
use PsaNdp\ApiBundle\Object\Color;
use PsaNdp\ApiBundle\Object\ColorType;
use PsaNdp\MappingBundle\Repository\Vehicle\PsaColorTypeSiteRepository;
use PsaNdp\WebserviceConsumerBundle\Webservices\ConfigurationEngineConfig;

/**
 * Class ColorPickerTransformer
 */
class ColorPickerTransformer extends AbstractTransformer
{
    const BASE_URL_COLOR_VIEW = 'http://configurateur3d.peugeot.com/CFG3PSite/Images/V3DCentral/colors/NDP/th_';

    /**
     * @var PsaColorTypeSiteRepository
     */
    protected $colorTypeSiteRepository;

    /**
     * @var ConfigurationEngineConfig
     */
    protected $configurationEngine;

    /**
     * @param PsaColorTypeSiteRepository $colorTypeSiteRepository
     * @param ConfigurationEngine        $configurationEngine
     */
    public function __construct(
        PsaColorTypeSiteRepository $colorTypeSiteRepository,
        ConfigurationEngineConfig $configurationEngine
    ) {
        $this->colorTypeSiteRepository = $colorTypeSiteRepository;
        $this->configurationEngine     = $configurationEngine;
    }

    /**
     * @param mixed $mixed
     *
     * @return FacadeInterface
     */
    public function transform($mixed)
    {
        $colorPicker = new ColorPickerFacade();

        $colors = $this->configurationEngine->ColorByVersion($mixed['version']);
        // récupérer le type de couleur associé au teintes
        $colorTypes = $this->getColorTypes($colors);
        // ordonnée le type de couleur BO
        $colorTypesSite  = $this->colorTypeSiteRepository->findBySite($mixed['siteId']);
        $orderColorTypes = $this->orderColorType($colorTypes, $colorTypesSite, $mixed);
        $colorPicker->color_picker = $this->getTransformer('ranges')->transform($orderColorTypes);

        return $colorPicker;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'color_picker';
    }

    /**
     * @param array $colors
     *
     * @return array
     */
    protected function getColorTypes($colors)
    {
        $colorTypes = array();

        foreach ($colors as $color) {
            $colorType = substr($color->id, 2, 2);
            if (!in_array($colorType, $colorTypes)) {
                $colorTypes[$colorType][] = $color;
            }
        }

        return $colorTypes;
    }

    /**
     * @param array $colorTypes
     * @param array $colorTypesSite
     * @param array $mixed
     *
     * @return array
     */
    protected function orderColorType($colorTypes, $colorTypesSite, $mixed)
    {
        $colorTypeOrder = array();
        foreach ($colorTypesSite as $colorTypeSite) {
            $colorType = $colorTypeSite->getColorType();
            $codeColorType = $colorType->getCode();
            $order = $colorTypeSite->getOrderType();

            if (!empty($codeColorType) && array_key_exists($codeColorType, $colorTypes)) {
                if (!array_key_exists($order, $colorTypeOrder)) {
                    $objectColorType = $this->addColorByColorTypes($colorTypes[$codeColorType], new ColorType());
                    $objectColorType->setCode($codeColorType);
                    $objectColorType->setLabel($colorTypeSite->getLabelLocal());
                } else {
                    $objectColorType = $this->addColorByColorTypes(
                        $colorTypes[$codeColorType],
                        $colorTypeOrder[$order]
                    );
                }
                $objectColorType->setId($order);
                $objectColorType->setVersion($mixed['version']);
                $objectColorType->setSiteId($mixed['siteId']);
                $colorTypeOrder[$order] = $objectColorType;
            }
        }

        ksort($colorTypeOrder);
        if (!empty($colorTypeOrder)) {
            reset($colorTypeOrder);
            $firstColorType = current($colorTypeOrder);
            $firstColorType->setDefault(true);
        }

        return $colorTypeOrder;
    }

    /**
     * @param array $color
     *
     * @return array
     */
    protected function generateColorsUrl($color)
    {
        return self::BASE_URL_COLOR_VIEW.$color.'.png';
    }

    /**
     * @param array     $colorTypes
     * @param ColorType $objectColorType
     *
     * @return ColorType
     */
    protected function addColorByColorTypes($colorTypes, ColorType $objectColorType)
    {
        foreach ($colorTypes as $color) {
            $objectColor = new Color();
            $objectColor->setCode($color->id);
            $objectColor->setUrl($this->generateColorsUrl($color->id));
            $objectColor->setLabel($color->label);

            $objectColorType->addColors($objectColor);
        }

        return $objectColorType;
    }
}
