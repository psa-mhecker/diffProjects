<?php
/**
 * Informations du brand Citroen
 *
 * @author David Moaté <david.moate@businessdecision.com>
 */

namespace ParamsApi\v1\Brand;
use ParamsApi\v1\Data;

class Citroen extends Data
{ 
    /**
     * tableau des datas à récupérer
     */
    public static $datas = array(
        'WSgamme'       => true,
        'MotCFG'        => true,
        'WebStore'      => true,
        'SFG'           => true,
        'Configurateur' => true,
        'Confishow'     => false,
    );
}
