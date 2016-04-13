<?php
namespace Citroen\Perso\Profile;

use Ruler\RuleBuilder;
use Ruler\Context;

/**
 * Classe Type
 *
 * Cette classe permet la fabrication de règles
 *
 * @package Profile/Type
 * @author Khadidja MESSAOUDI <khadidja.messaoudi@businessdecision.com>
 */

class Type
{
    /*
     * Vérifie que userValue (Indicateur) est égal à baseValue (valeur souhaitée pour le test)
     *
     * @return bool
     */
    public function equalTo($userValue, $baseValue){
        $rb = new RuleBuilder();
        $rule = $rb->create($rb['userValue']->equalTo($rb['baseValue']));
        $context = new Context(array(
            'userValue' => $userValue,
            'baseValue' => $baseValue
        ));
        return $rule->evaluate($context);
    }

    /*
     * Vérifie que userValue (Indicateur) est égal à baseValueA ou à baseValueB
     * (toutes deux correspondant aux valeurs souhaitée pour le test)
     *
     * @return bool
     */
    public function equalToAorB($userValue, $baseValueA, $baseValueB){
        $rb = new RuleBuilder();
        $rule = $rb->create(
            $rb->logicalOr(
                $rb['userValue']->equalTo($rb['baseValueA']),
                $rb['userValue']->equalTo($rb['$baseValueB'])
            )
        );
        $context = new Context(array(
            'userValue' => $userValue,
            'baseValueA' => $baseValueA,
            'baseValueB' => $baseValueB
        ));
        return $rule->evaluate($context);
    }

    /*
     * Vérifie que userValueA (Indicateur) est égal à baseValueA
     * et que userValueB est égal à baseValueB
     * (toutes deux correspondant aux valeurs souhaitée pour le test)
     *
     * @return bool
     */
    public function equalToAandB($userValueA, $baseValueA, $userValueB, $baseValueB){
        $rb = new RuleBuilder();
        $rule = $rb->create(
            $rb->logicalAnd(
                $rb['userValueA']->equalTo($rb['baseValueA']),
                $rb['userValueB']->equalTo($rb['baseValueB'])
            )
        );
        $context = new Context(array(
            'userValueA' => $userValueA,
            'baseValueA' => $baseValueA,
            'userValueB' => $userValueB,
            'baseValueB' => $baseValueB
        ));

        return $rule->evaluate($context);
    }

    /*
     * Vérifie que userValueA (Indicateur) est égal à baseValueA ou à baseValueB
     * et que userValueB est égale à baseValueC
     *
     * @return bool
     */
     public function compareTwoFlags($userValue, $baseValueA, $baseValueB, $userValueB, $baseValueC){
        $rb = new RuleBuilder();
        $rule = $rb->create(
            $rb->logicalAnd(
                $rb->logicalOr(
                    $rb['userValue']->equalTo($rb['baseValueA']),
                    $rb['userValue']->equalTo($rb['baseValueB'])
                ),
                $rb['userValueB']->equalTo($rb['baseValueC'])
            )
        );
        $context = new Context(array(
            'userValue' => $userValue,
            'baseValueA' => $baseValueA,
            'baseValueB' => $baseValueB,
            'userValueB' => $userValueB,
            'baseValueC' => $baseValueC
        ));
        return $rule->evaluate($context);
    }

    /*
     * Vérifie que userValueA (Indicateur) est égal à baseValueA ou à baseValueB
     * et que userValueB est égal à baseValueC
     * et que userValueC est égal à baseValueD
     *
     * @return bool
     */
    public function compareThreeFlags($userValue, $baseValueA, $baseValueB, $userValueB, $baseValueC, $userValueC, $baseValueD){
        $rb = new RuleBuilder();
        $rule = $rb->create(
            $rb->logicalAnd(
                $rb->logicalOr(
                    $rb['userValue']->equalTo($rb['baseValueA']),
                    $rb['userValue']->equalTo($rb['baseValueB'])
                ),
                $rb['userValueB']->equalTo($rb['baseValueC']),
                $rb['userValueC']->equalTo($rb['baseValueD'])
            )
        );
        $context = new Context(array(
            'userValue' => $userValue,
            'baseValueA' => $baseValueA,
            'baseValueB' => $baseValueB,
            'userValueB' => $userValueB,
            'baseValueC' => $baseValueC,
            'userValueC' => $userValueC,
            'baseValueD' => $baseValueD
        ));
        return $rule->evaluate($context);
    }
}