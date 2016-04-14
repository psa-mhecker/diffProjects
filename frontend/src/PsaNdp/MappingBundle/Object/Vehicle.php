<?php

namespace PsaNdp\MappingBundle\Object;

use OpenOrchestra\ModelInterface\Model\ReadNodeInterface;
use PsaNdp\MappingBundle\Entity\Vehicle\PsaModelSilhouetteSite;
use PsaNdp\MappingBundle\Services\ShareServices\ShareVehicleService;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Vehicle
 */
class Vehicle extends AbstractObject
{
    protected $overrideMapping = array('imgSrc' => 'image');

    /**
     * @var ShareVehicleService
     */
    protected $shareVehicleService;

    /**
     * @var string
     */
    protected $modelName;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $lcdv4;

    /**
     * @var string
     */
    protected $lcdv6;

    /**
     * @var string
     */
    protected $lcdv16;

    /**
     * @var string
     */
    protected $codeGrBodyStyle;

    /**
     * @var string
     */
    protected $labelGrBodyStyle;

    /**
     * @var string
     */
    protected $thumbnailUrl;

    /**
     * @var string
     */
    protected $price;

    /**
     * @var string
     */
    protected $image;

    /**
     * @var array
     */
    protected $version;

    /**
     * @var PsaModelSilhouetteSite
     */
    protected $modelSilhouetteInformation;

    /**
     * @param ShareVehicleService $shareVehicleService
     */
    public function __construct(ShareVehicleService $shareVehicleService)
    {
        $this->shareVehicleService = $shareVehicleService;
    }

    /**
     * @param ReadNodeInterface   $node
     * @param TranslatorInterface $translator
     * @param bool                $isMobile
     *
     * @return $this
     */
    public function initializeVehicle(ReadNodeInterface $node, TranslatorInterface $translator, $isMobile = false)
    {
        $this->shareVehicleService->setNode($node);
        $this->shareVehicleService->setTranslator($translator);
        $this->shareVehicleService->setIsMobile($isMobile);

        $modelSilhouette = $this->shareVehicleService->getModelSilhouette();
        $this->setModelSilhouetteInformation($this->shareVehicleService->getModelSilhouetteInformation());

        if (isset($modelSilhouette['cheapest'])) {
            $this->setDataFromArray($modelSilhouette['cheapest'])
            ->setVersion($modelSilhouette['cheapest'])
            ->setCodeGrBodyStyle($modelSilhouette['cheapest']['GrBodyStyle']['Code'])
            ->setLabelGrBodyStyle($modelSilhouette['cheapest']['GrBodyStyle']['Label'])
            ->setPrice($modelSilhouette['cheapest']['Price']['Display']);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getCodeGrBodyStyle()
    {
        return $this->codeGrBodyStyle;
    }

    /**
     * @param string $codeGrBodyStyle
     *
     * @return $this
     */
    public function setCodeGrBodyStyle($codeGrBodyStyle)
    {
        $this->codeGrBodyStyle = $codeGrBodyStyle;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabelGrBodyStyle()
    {
        return $this->labelGrBodyStyle;
    }

    /**
     * @param string $labelGrBodyStyle
     *
     * @return $this
     */
    public function setLabelGrBodyStyle($labelGrBodyStyle)
    {
        $this->labelGrBodyStyle = $labelGrBodyStyle;

        return $this;
    }

    /**
     * @return string
     */
    public function getLcdv16()
    {
        return $this->lcdv16;
    }

    /**
     * @param string $lcdv16
     *
     * @return $this
     */
    public function setLcdv16($lcdv16)
    {
        $this->lcdv16 = $lcdv16;

        return $this;
    }

    /**
     * @return string
     */
    public function getLcdv4()
    {
        return $this->lcdv4;
    }

    /**
     * @param string $lcdv4
     *
     * @return $this
     */
    public function setLcdv4($lcdv4)
    {
        $this->lcdv4 = $lcdv4;

        return $this;
    }

    /**
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
     * @return string
     */
    public function getModelName()
    {
        return $this->modelName;
    }

    /**
     * @param string $modelName
     *
     * @return $this
     */
    public function setModelName($modelName)
    {
        $this->modelName = $modelName;

        return $this;
    }

    /**
     * @return ShareVehicleService
     */
    public function getShareVehicleService()
    {
        return $this->shareVehicleService;
    }

    /**
     * @param ShareVehicleService $shareVehicleService
     *
     * @return $this
     */
    public function setShareVehicleService($shareVehicleService)
    {
        $this->shareVehicleService = $shareVehicleService;

        return $this;
    }

    /**
     * @return string
     */
    public function getThumbnailUrl()
    {
        return $this->thumbnailUrl;
    }

    /**
     * @param string $thumbnailUrl
     *
     * @return $this
     */
    public function setThumbnailUrl($thumbnailUrl)
    {
        $this->thumbnailUrl = $thumbnailUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param string $price
     *
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     *
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return PsaModelSilhouetteSite
     */
    public function getModelSilhouetteInformation()
    {
        return $this->modelSilhouetteInformation;
    }

    /**
     * @param PsaModelSilhouetteSite $modelSilhouetteInformation
     *
     * @return $this
     */
    public function setModelSilhouetteInformation($modelSilhouetteInformation)
    {
        $this->modelSilhouetteInformation = $modelSilhouetteInformation;

        return $this;
    }

    /**
     * @return array
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param array $version
     *
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }
}
