<?php
/**
 * Brand factory
 *
 * @version 1
 */

namespace ParamsApi\v1;
use Luracast\Restler\RestException;
use ParamsApi\v1\Brand\Peugeot;
use ParamsApi\v1\Brand\Citroen;
use ParamsApi\v1\Brand\DS;

class BrandFactory
{
    /**
     * Retourne une instance brand
     *
     * @param string $brand marque du site {@from path}
     */
    public static function getInstance($brand)
    {
        switch ($brand) {
            case 'AP':
                if (class_exists('ParamsApi\v1\Brand\Peugeot')) {
                
                    return new Peugeot;
                } else {
                    throw new RestException(500, "La classe Peugeot n'existe pas");
                }
                break;
            case 'AC':
                if (class_exists('ParamsApi\v1\Brand\Citroen',true)) {
                
                    return new Citroen;
                } else {
                    throw new RestException(500, "La classe Citroen n'existe pas");
                }
                break;
            case 'DS':
                if (class_exists('ParamsApi\v1\Brand\DS')) {
                
                    return new DS;
                } else {
                    throw new RestException(500, "La classe DS n'existe pas");
                }
                break;
            default:
                throw new RestException(500, "le brand {$brand} n'existe pas");
                break;
        }
    }
}
