<?php
namespace Citroen\Perso\Profile\Type;

use Citroen\Perso\Profile\Type;
use Citroen\Perso\Flag\Detail;
use Ruler\RuleBuilder;
use Ruler\Context;

class AutreLigneBusinessPreferee extends Type
{
    public function init()
    {
        $ligne = \Citroen_View_Helper_Vehicule::getProductLigne($_SESSION[APP]['SITE_ID'], Detail::$preferredProduct);
        $productType = \Citroen_View_Helper_Vehicule::getProductGamme($_SESSION[APP]['SITE_ID'], Detail::$preferredProduct);
        $rb = new RuleBuilder();
        $rule = $rb->create($rb->logicalAnd(
            $rb['userValue']->notEqualTo($rb['baseValueA']),
            $rb['userValue']->notEqualTo($rb['baseValueB']),
            $rb['userValue']->notEqualTo($rb['baseValueC']),
            $rb['userValueB']->equalTo($rb['baseValueD'])
        ));
        $context = new Context(array(
            'userValue'  => $ligne,
            'baseValueA' => 'GAMME_LIGNE_C',
            'baseValueB' => 'GAMME_LIGNE_DS',
            'baseValueC' => 'GAMME_VEHICULE_UTILITAIRE',
            'userValueB' => $productType,
            'baseValueD' => 'VU'
        ));
        return $rule->evaluate($context);
    }
}