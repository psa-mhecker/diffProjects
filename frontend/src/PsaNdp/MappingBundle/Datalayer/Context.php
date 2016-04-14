<?php


namespace PsaNdp\MappingBundle\Datalayer;


use PSA\MigrationBundle\Entity\Page\PsaPage;
use PsaNdp\WebserviceConsumerBundle\Webservices\RangeManager;

class Context
{

    protected $request;
    /**
     * @var PsaPage
     */
    protected $node;

    /**
     * @var RangeManager
     */
    protected $rangeManager;


    /**
     * @return string
     */
    public function getTemplateCode()
    {
        return $this->node->getTypeCode();
    }

    /**
     * @return string
     */
    public function getTemplateCodeLabel()
    {
        $templateLabel = str_replace('NDP_TP_','',$this->node->getVersion()->getTemplatePage()->getTemplatePageLabel());

        return sprintf('%s_%s',$this->node->getTypeCode(),$templateLabel);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->node->getName();
    }

    /**
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->node->getMetaTitle();
    }


    /**
     * @return string
     */
    public function getDevice()
    {
        $device = $this->request->headers->get('x-ua-device');
        $device = (!is_null($device))? $device: 'desktop';

        return $device;
    }

    /**
     * Get node
     *
     * @return PsaPage
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @param PsaPage $node
     *
     * @return $this
     */
    public function setNode(PsaPage &$node)
    {
        $this->node = $node;

        return $this;
    }


    /**
     * @param mixed $request
     *
     * @return Context
     */
    public function setRequest($request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return RangeManager
     */
    public function getRangeManager()
    {
        return $this->rangeManager;
    }

    /**
     * @param RangeManager $rangeManager
     */
    public function setRangeManager($rangeManager)
    {
        $this->rangeManager = $rangeManager;
    }

    /**
     * @return string
     */
    public function getLanguageCode()
    {
        return $this->getNode()->getLanguage();
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->getNode()->getSite()->getCountryCode();
    }

    /**
     * @return string
     */
    public function getVehicleCode()
    {
        return $this->getNode()->getVehicleCode();
    }

    /**
     * @return string
     */
    public function getVehicleCodeBodyStyle()
    {
        return $this->getNode()->getVersion()->getGammeVehiculeSilouhette();
    }

    /**
     * @return string
     */
    public function getVehicleBodyStyleLabel()
    {
        $return = '';
        try {
            $cheapest = $this->rangeManager->getCheapestByLcdv6AndGrBodyStyle(
                $this->getVehicleCode(),
                $this->getVehicleCodeBodyStyle(),
                $this->getCountryCode(),
                $this->getLanguageCode()
            );
            $return = $cheapest['Label'];
        } catch(\Exception $e) {
            // silently ignore error, it should be logged by event
        }

        return $return;;
    }
}
