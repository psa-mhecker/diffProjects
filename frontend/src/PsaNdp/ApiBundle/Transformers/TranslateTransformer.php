<?php
namespace PsaNdp\ApiBundle\Transformers;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\ApiBundle\Facade\TranslateFacade;

/**
 * Class TranslateTransformer
 */
class TranslateTransformer extends AbstractTransformer
{
    /**
     * @param array $mixed
     *
     * @return FacadeInterface
     */
    public function transform($mixed)
    {
        $translate = new TranslateFacade();

        if (isset($mixed['yes'])) {
            $translate->yes = $mixed['yes'];
        }

        if (isset($mixed['no'])) {
            $translate->no = $mixed['no'];
        }

        return $translate;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'translate';
    }
}
