<?php
/**
 * Params API
 *
 * @version 1
 */

namespace ParamsApi\v1;
use Luracast\Restler\RestException;

class Params
{
    
    /**
     * Retourne les parametres d'un site
     *
     * @param string $brand marque du site {@from path}
     * @param string $countrie Code pays du site {@from path}
     */
    protected function index($brand, $countrie)
    {
        $brand = BrandFactory::getInstance($brand);
        $brand->setCountrie($countrie);
        if(!is_array($brand::$datas)){
            
            return false;
        }
        $params = array();
        foreach ($brand::$datas as $ws => $status){
            if(true !== $status){
                $params[$ws] = false;
                continue;
            }
            $getParam = 'get' . $ws;
            if(method_exists($brand, $getParam)){
                $params[$ws] = $brand->$getParam();
            }else{
                throw new RestException(500, "La méthode {$getParam} n'existe pas dans la classe ParamsApi\\v1\\DataBdd");
            }
        }
        if (is_a($this->restler->responseFormat, 'Luracast\\Restler\\Format\\XmlFormat')) {
            $format = new Format\ParamsInfosXmlFormat();
            $format->restler = $this->restler;
            $this->restler->responseFormat = $format;
            Format\ParamsInfosXmlFormat::$rootName = 'Params';
        }
        
        return $params;
    }
}
