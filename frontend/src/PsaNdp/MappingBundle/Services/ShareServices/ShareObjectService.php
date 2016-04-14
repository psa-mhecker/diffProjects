<?php

namespace PsaNdp\MappingBundle\Services\ShareServices;

use PSA\MigrationBundle\Entity\Page\PsaPage;
use PsaNdp\MappingBundle\Object\Vehicle;
use Symfony\Component\Translation\TranslatorInterface;
use PsaNdp\MappingBundle\Translation\TranslatorAwareTrait;

/**
 * Class ShareObjectService
 */
class ShareObjectService
{
    use TranslatorAwareTrait;

    /**
     * @var bool
     */
    protected $isMobile;

    /**
     * @var PsaPage;
     */
    protected $node;

    /**
     * @var Vehicle
     */
    protected $vehicle;

    /**
     * @var ShareMyPeugeotService
     */
    protected $myPeugeot;

    /**
     * @param Vehicle               $vehicle
     * @param ShareMyPeugeotService $myPeugeotService
     * @param TranslatorInterface   $translator
     */
    public function __construct(
        Vehicle $vehicle,
        ShareMyPeugeotService $myPeugeotService,
        TranslatorInterface $translator
    ) {
        $this->vehicle = $vehicle;
        $this->myPeugeot = $myPeugeotService;
        $this->translator = $translator;
    }

    /**
     * @return bool
     */
    public function isMobile()
    {
        return $this->isMobile;
    }

    /**
     * @param bool $isMobile
     *
     * @return ShareObjectService
     */
    public function setIsMobile($isMobile)
    {
        $this->isMobile = $isMobile;

        return $this;
    }

    /**
     * @return PsaPage
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @param PsaPage $node
     *
     * @return ShareObjectService
     */
    public function setNode($node)
    {
        $this->node = $node;
        $this->domain = (string) $this->node->getSiteId();
        $this->locale = $this->node->getLanguage();

        $this->node->initBlockPosition();

        return $this;
    }

    /**
     * @return ShareMyPeugeotService
     */
    public function getMyPeugeot()
    {
        $this->myPeugeot->setTranslator($this->translator, $this->domain, $this->locale);

        return $this->myPeugeot->getMyPeugeot($this->node);
    }

    /**
     * @param ShareMyPeugeotService $myPeugeot
     *
     * @return $this
     */
    public function setMyPeugeot($myPeugeot)
    {
        $this->myPeugeot = $myPeugeot;

        return $this;
    }

    /**
     * @return null|Vehicle
     */
    public function getVehicle()
    {
        $return = null;

        if ($this->hasVehicle()) {
            if ($this->vehicle->getModelName() === null) {
                $this->vehicle->initializeVehicle($this->getNode(), $this->translator);
            }
            $return = $this->vehicle;
        }

        return $return;
    }

    /**
     * @param Vehicle $vehicle
     *
     * @return $this
     */
    public function setVehicle($vehicle)
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasVehicle()
    {
        $vehicle = $this->node->getVersion()->getGammeVehicule();

        return !empty($vehicle);
    }
}
