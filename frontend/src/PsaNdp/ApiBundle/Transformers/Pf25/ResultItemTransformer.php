<?php

namespace PsaNdp\ApiBundle\Transformers\Pf25;

use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\ApiBundle\Facade\LinkFacade;
use PsaNdp\ApiBundle\Facade\Pf25\PriceFacade;
use PsaNdp\ApiBundle\Facade\Pf25\ResultItemFacade;
use PsaNdp\ApiBundle\Facade\Pf25\VehicleFacade;
use PsaNdp\MappingBundle\Entity\Vehicle\PsaModelSilhouetteSite;
use PsaNdp\MappingBundle\Manager\PriceManager;
use Symfony\Component\Translation\TranslatorInterface;

class ResultItemTransformer extends AbstractTransformer
{
    protected $translator;
    protected $priceManager;



    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator, PriceManager $priceManager)
    {
        $this->translator = $translator;
        $this->priceManager= $priceManager;
    }

    /**
     * @param PsaModelSilhouetteSite $mixed
     *
     * @return ResultItemFacade
     */
    public function transform($mixed)
    {
        $resultItemFacade = new ResultItemFacade();

        if ( ! empty($mixed->cheapestVersion)) {

            $vehicleFacade = new VehicleFacade();

            $showRoomCtaFacade = new LinkFacade();
            $comparatorCtaFacade = new LinkFacade();
            $storeLocatorCtaFacade = new LinkFacade();

            $modelName = $mixed->cheapestVersion['ModelName'];
            $grBodyStyleLabel = $mixed->cheapestVersion['GrBodyStyle']['Label'];

            $vehicleFacade->version = $mixed->lcdv16;

            $vehicleFacade->title = sprintf('%s %s', $modelName, $grBodyStyleLabel);

            if(!empty($mixed->cheapestVersion['ThumbnailURL'])){
                $vehicleFacade->thumbnail =   $mixed->cheapestVersion['ThumbnailURL'];
            }

            if(!empty($mixed->discover)){
                $showRoomCtaFacade->url = $mixed->discover;
                $showRoomCtaFacade->title = $this->translator->trans(
                    'NDP_DECOUVRIR',
                    array(),
                    $mixed->getSite()->getId(),
                    $mixed->getLangue()->getLangueCode()
                );
            }


            $this->priceManager->setModelSilhouetteInformation($mixed);
            /**
             * this is dirty because it's the way the price manager is dealing with things
             */
            $this->priceManager->setVersion($mixed->cheapestVersion);
            $this->priceManager->setCheapest($mixed->cheapestVersion);

            if($this->priceManager->canShowPrice()){
                $vehiclePriceFacade = new PriceFacade();
                $vehiclePriceFacade->display = $this->priceManager->getPriceValue();
                $vehicleFacade->price = $vehiclePriceFacade;
            }
            $strip = $mixed->getFirstActiveStrip();
            $resultItemFacade->commercialLabel = $this->translator->trans(
                $strip,
                array(),
                $mixed->getSite()->getId(),
                $mixed->getLangue()->getLangueCode()
            );
            $resultItemFacade->cssClass = str_replace('_','-', strtolower($strip));
            $resultItemFacade->id = $vehicleFacade->version;
            $resultItemFacade->vehicle = $vehicleFacade;

            $resultItemFacade->discover = $showRoomCtaFacade;
            $resultItemFacade->compare = $comparatorCtaFacade;
            $resultItemFacade->storeLocator = $storeLocatorCtaFacade;


        }

        return $resultItemFacade;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pf25_result_item';
    }
}
