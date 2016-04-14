<?php

namespace PsaNdp\ApiBundle\Transformers;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\ApiBundle\Facade\AvailabilityFacade;
use PsaNdp\ApiBundle\Facade\InformationFacade;
use PsaNdp\ApiBundle\Facade\TableHeadFacade;

/**
 * Class InformationTransformer
 */
class InformationTransformer extends AbstractTransformer
{
    /**
     * @param array $mixed
     *
     * @return FacadeInterface
     */
    public function transform($mixed)
    {
        $info = new InformationFacade();

        $info->more = $mixed['more'];

        foreach ($mixed['thead'] as $title) {
            $tableHead = new TableHeadFacade();

            $tableHead->title = $title;

            $info->addTableHead($tableHead);
        }

        if (isset($mixed['availability']) && is_array($mixed['availability'])) {
            foreach ($mixed['availability'] as $availability) {
                $availabilityFacade = new AvailabilityFacade();

                $availabilityFacade->checked = $availability['checked'];

                if (isset($availability['checkVisu'])) {
                    $availabilityFacade->checkVisu = $availability['checkVisu'];
                }

                if (isset($availability['option'])) {
                    $availabilityFacade->option = $availability['option'];
                }

                $info->addAvailability($availabilityFacade);
            }
        }

        return $info;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'information';
    }
}
