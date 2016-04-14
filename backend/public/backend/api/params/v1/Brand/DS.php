<?php
/**
 * Informations du brand DS
 *
 * @author David Moaté <david.moate@businessdecision.com>
 */

namespace ParamsApi\v1\Brand;
use ParamsApi\v1\Data;

class DS extends Data
{ 
    /**
     * tableau des datas à récupérer
     */
    public static $datas = array(
        'WSgamme'       => true,
        'MotCFG'        => true,
        'WebStore'      => true,
        'SFG'           => false,
        'Configurateur' => false,
        'Confishow'     => false,
    );
}
