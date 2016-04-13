<?php
namespace Citroen\Perso\Profile\Type;

use Citroen\Perso\Flag\Detail;
use Citroen\Perso\Profile\Type;
use Ruler\RuleBuilder;
use Ruler\Context;

class InteresseExtensionGarantie extends Type
{
    public function init()
    {
        $rb = new RuleBuilder();
        $rule = $rb->create($rb->logicalAnd(
            $rb['dateAchat']->lessThanOrEqualTo($rb['dateMin']),
            $rb['dateAchat']->greaterThanOrEqualTo($rb['dateMax']),
            $rb['userValue']->equalTo($rb['baseValue']),
            $rb->create($rb->logicalOr(
                $rb['userValueB']->equalTo($rb['baseValueB']),
                $rb['userValueB']->equalTo($rb['baseValueC'])
                ))
        ));
        $maxDate = strtotime('-2 year', time());
        $minDate = strtotime('-1 year', time());
        $context = new Context(array(
            'baseValue' => 'Oui',
            'userValue' => Detail::$recentClient,
            'userValueB' => Detail::$extendedWarranty,
            'baseValueB' => 'Non',
            'baseValueC' => null,
            'dateAchat' => strtotime(Detail::$datePurchase),
            'dateMin' => $minDate,
            'dateMax' => $maxDate,
        ));
        return $rule->evaluate($context);
    }
}