<?php

namespace PsaNdp\ApiBundle\Transformers;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\ApiBundle\Facade\ColorFacade;
use PsaNdp\ApiBundle\Facade\SequenceFacade;
use PsaNdp\ApiBundle\Facade\TintFacade;
use PsaNdp\MappingBundle\Entity\Vehicle\PsaModelSilhouetteAngle;
use PsaNdp\MappingBundle\Repository\Vehicle\PsaModelSilhouetteAngleRepository;

/**
 * Class RangeTransformer
 */
class ColorTransformer extends AbstractTransformer
{
    const BASE_URL_VEHICLE_VIEW = 'http://visuel3d.peugeot.com/V3DImage.ashx?client=CFGAP3D&version=';
    const WIDTH_VIEW = '960';

    /**
     * @var PsaModelSilhouetteAngleRepository
     */
    protected $angleRepository;

    /**
     * @var mixed
     */
    protected $viewAngles;

    /**
     * @var integer
     */
    protected $start = 0;

    /**
     * @param PsaModelSilhouetteAngleRepository $angleRepository
     */
    public function __construct(PsaModelSilhouetteAngleRepository $angleRepository)
    {
        $this->angleRepository = $angleRepository;
    }

    /**
     * @param mixed $mixed
     *
     * @return FacadeInterface
     */
    public function transform($mixed)
    {
        $color = new ColorFacade();
        $tints = array();
        $this->setViewAnglesByLcdv6($mixed->getLcdv6());

        foreach ($mixed->getColors() as $tint) {
            $tintFacade = new TintFacade();
            $sequence = new SequenceFacade();

            $sequence->startFrame = $this->start;
            $sequence->frames      = count($this->viewAngles);
            $medias = $this->generateVehicleViews($mixed->getVersion(), $tint->getCode());
            foreach ($medias as $media) {
                $sequence->addMedia($this->getTransformer('media')->transform($media));
            }

            $tintFacade->src = $tint->getUrl();
            $tintFacade->alt = $tint->getLabel();
            $tintFacade->label = $tint->getLabel();
            $tintFacade->sequence = $sequence;

            $tints[] = $tintFacade;
        }

        $color->default = $mixed->isDefault();
        $color->id      = $mixed->getId();
        $color->label   = $mixed->getLabel();
        $color->name    = 'radio_colors';
        $color->tint    = $tints;

        return $color;
    }

    /**
     * @param $lcdv6
     */
    protected function setViewAnglesByLcdv6($lcdv6)
    {
        $this->start = 0;
        $defaultAngleView = new PsaModelSilhouetteAngle();
        $defaultAngleView->setCode('001');
        $this->viewAngles = array($defaultAngleView);
        $viewAngles =  $this->angleRepository->findByLcdv6($lcdv6);
        if (is_array($viewAngles)) {
            foreach ($viewAngles as $idx => $angle) {
                if ($angle->getAngleInitial()) {
                    $this->start = (int) $idx;
                }
            }

            $this->viewAngles = $viewAngles;
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'color';
    }

    /**
     * @param string $version
     * @param string $color
     * @param string $view
     *
     * @return string
     */
    protected function generateVehicleViewUrl($version, $color, $view)
    {
        return self::BASE_URL_VEHICLE_VIEW.$version.'&color='.$color.'&trim=0P7A0RFX&width='.self::WIDTH_VIEW.'&view='.$view;
    }

    /**
     * @param string $version
     * @param string $color
     *
     * @return array
     */
    protected function generateVehicleViews($version, $color)
    {
        $medias = array();

        foreach ($this->viewAngles as $i => $angle) {
            $angleCode = $angle->getCode();
            $medias[] = array(
                'src' => $this->generateVehicleViewUrl($version, $color, $angleCode),
                'alt' => $version.$color.$angleCode,
            );
        }

        return $medias;
    }
}
