<?php
namespace PsaNdp\MappingBundle\Object\Block;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMulti;
use PsaNdp\MappingBundle\Object\BlockTrait\Pt22MyPeugeotTrait;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Factory\CtaFactory;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;
use PsaNdp\MappingBundle\Object\Slideshow;

/**
 * Class Pc19Slideshow
 * @package PsaNdp\MappingBundle\Object\Block
 */
class Pc19Slideshow extends Content
{
    use Pt22MyPeugeotTrait;

    const DESKTOP_FORMAT = 'NDP_PF2_DESKTOP';
    const MOBILE_FORMAT  = 'NDP_GENERIC_4_3_640';

    /**
     * @var array
     */
    protected $slides;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $speedTimer;

    /**
     * Pc19Slideshow constructor.
     *
     * @param CtaFactory $ctaFactory
     * @param MediaFactory $mediaFactory
     */
    public function __construct(CtaFactory $ctaFactory, MediaFactory $mediaFactory)
    {
        $this->ctaFactory   = $ctaFactory;
        $this->mediaFactory = $mediaFactory;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->block->getZoneParameters();
    }

    /**
     * @return string
     */
    public function getSpeedTimer()
    {
        return $this->block->getTimerSpeed();
    }


    /**
     * @return array
     */
    public function getSlides()
    {
        $size = ['desktop' => self::DESKTOP_FORMAT, 'mobile' => self::MOBILE_FORMAT];

        if ($this->block instanceof PsaPageZoneConfigurableInterface)
        {
            $this->type = $this->block->getZoneParameters();

            switch ($this->type)
            {
                case PsaPageZoneMulti::PAGE_ZONE_MULTI_VALUE_SLIDE_IMAGE:

                    foreach ($this->block->getMultis() as $multi)
                    {
                        if ($multi->getMedia()) {
                            $slideshow = new Slideshow($this->ctaFactory, $this->mediaFactory);

                            $slides[] = $slideshow->createSlideshowFromMulti($multi, ['size' => $size, 'autoCrop' => true]);
                        }
                    }
                    break;

                case PsaPageZoneMulti::PAGE_ZONE_MULTI_VALUE_SLIDE_VIDEO:
                    $slideshow = new Slideshow($this->ctaFactory, $this->mediaFactory);
                    $slides[] = $slideshow->createVideo($this->block);
                    break;
            }
        }

        return $slides;
    }

}
