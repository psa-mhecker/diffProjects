<?php
namespace Citroen\Perso\Profile\Type;

use Citroen\Perso\Flag\Detail;
use Citroen\Perso\Profile\Type;
use Ruler\RuleBuilder;
use Ruler\Context;

class InteressePreControleTech extends Type
{
    public function init()
    {
        $rb = new RuleBuilder();
        $rule = $rb->create($rb->logicalAnd(
        $rb['dateAchat']->lessThanOrEqualTo($rb['dateMin']),
        $rb['dateAchat']->greaterThanOrEqualTo($rb['dateMax']),
        $rb['userValue']->equalTo($rb['baseValue'])));
        $maxDate = strtotime('-4 year', time());
        $minDate = strtotime('+6 month', $maxDate);
        $context = new Context(array(
            'baseValue' => 'Oui',
            'userValue' => Detail::$recentClient,
            'dateAchat' => strtotime(Detail::$datePurchase),
            'dateMin' => $minDate,
            'dateMax' => $maxDate,
        ));
        return $rule->evaluate($context);
    }
}