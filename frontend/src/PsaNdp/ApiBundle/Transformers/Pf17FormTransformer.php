<?php

namespace PsaNdp\ApiBundle\Transformers;

use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\ApiBundle\Facade\Pf17FormFacade;
use PSA\MigrationBundle\Entity\Content\PsaContent;

class Pf17FormTransformer extends AbstractTransformer
{


    const NO_TYPE = "NDP_NO_TYPE";
    const TYPE_PDV = "NDP_TYPE_PDV";
    const TYPE_CAR = "NDP_TYPE_CAR";
    /**
     * @param array $mixed
     *
     * @return FacadeInterface
     */
    public function transform($mixed)
    {
        $results = new Pf17FormFacade();
        /** @var PsaContent $content */
        $content = $mixed['content'];
        $currentVersion = $content->getCurrentVersion();
        $results->country = strtoupper($content->getSite()->getCountryCode());
        $results->langue = strtolower($content->getLangue()->getLangueCode());
        $results->culture = $results->langue.'-'.$results->country;
        $results->instanceid = $currentVersion->getContentCode();
        if ($mixed['mobile']) {
            $results->instanceid = $currentVersion->getContentTitle13();
        }
        $type = $currentVersion->getContentCode2();

        switch($type) {
            case self::TYPE_CAR: // contextualisation vehicule

                $results->lcdv16 = ($currentVersion->getContentTitle2()) ? [$currentVersion->getContentTitle2()] : null  ;
                if(isset($mixed['showroom'])) {
                    // todo
                }
                break;
            case self::TYPE_PDV: // contextualisation point de vente
                $results->idSiteGeo = $currentVersion->getContentTitle5();
                break;
            default: // pas de contextualisation
                // do nothing
        }

        return $results;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pf17_forms';
    }
}
