<?php
namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Pf17Formulaires;

/**
 * Class Pf17FormulairesDataTransformer
 * Data transformer for Pf17Formulaires block
 * @package PsaNdp\MappingBundle\Transformers
 */
class Pf17FormulairesDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    /**
     * @var Pf17Formulaires
     */
    protected $pf17Formulaires;

    /**
     * @param Pf17Formulaires $pf17Formulaires
     */
    public function __construct(Pf17Formulaires $pf17Formulaires)
    {
        $this->pf17Formulaires = $pf17Formulaires;
    }

    /**
     *  Fetching data slice formulaires (pf17)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $pf17 = $this->pf17Formulaires;
        $pf17->setIsMobile($isMobile);
        $pf17->addTranslate('close', $this->trans(Pf17Formulaires::NDP_CLOSE));
        $pf17->addTranslate(Pf17Formulaires::NDP_PF17_RETURN_TO_HOME, $this->trans(Pf17Formulaires::NDP_PF17_RETURN_TO_HOME));
        $pf17->addTranslate(Pf17Formulaires::NDP_MY_PEUGEOT, $this->trans(Pf17Formulaires::NDP_MY_PEUGEOT));
        $pf17->addTranslate(Pf17Formulaires::NDP_ERROR_FO_FORM_MESSAGE, $this->trans(Pf17Formulaires::NDP_ERROR_FO_FORM_MESSAGE));
        $pf17->addTranslate(Pf17Formulaires::NDP_INFOBULLE_ICON_I, $this->trans(Pf17Formulaires::NDP_INFOBULLE_ICON_I));
        $pf17->setDataFromArray($dataSource);
        $pf17->init();

        return array(
            'slicePF17' =>  $pf17
        );
    }
}
