<?php
namespace Citroen;

/**
 * Class Url gérant l'encapsulation de l'url 
 * CPW-4042
 *
 * @author Wei DU <wei.du@ext.mpsa.com>
 *
 */
class Url{
    // les variables à chercher dans la session
    static $supplements = array(
        'utm_source',
        'utm_content',
        'utm_campaign',
        'utm_medium',
        'utm_term'
    );
    
    /**
     * Encapsuler l'url utilisé vers externe
     * 
     * @param string $url : l'url utilisé
     * @return styring $url : l'url encapsulé
     */
    public static function parse($url){
        $parts = parse_url($url);
        
        // vérifier si l'url vers l'externe
        if(isset($parts['host']) && $parts['host'] != $_SERVER['HTTP_HOST']){
            // vérifier la présence du query HTTP
            $querys = array();
            if(isset($parts['query'])){
                parse_str($parts['query'],$querys);
            }
            // chercher la présences des paramètres désirés dans la session
            self::supplementsToArray($querys);
            // reconstituer l'url
            $parts['query'] = http_build_query($querys);
            $url = $parts['scheme'] . '://'
		. (empty($parts['username'])?''
				:(empty($parts['password'])? "{$parts['username']}@"
				:"{$parts['username']}:{$parts['password']}@"))
				. $parts['host']
				. (empty($parts['port'])?'':":{$parts['port']}")
				. (empty($parts['path'])?'':$parts['path'])
				. (empty($parts['query'])?'':"?{$parts['query']}")
				. (empty($parts['fragment'])?'':"#{$parts['fragment']}");
        }
        return $url;
    }
    
    public static function supplementsToUrlQuery(){
        $arr = self::supplementsToArray();
        return http_build_query($arr);
    }
    
    public static function registeSupplementsToSession($params_on_get){
        foreach(self::$supplements as $key){
            if(isset($params_on_get[$key])){
                $_SESSION[APP][$key] = $params_on_get[$key];
            }
        }
    }
    
    private static function supplementsToArray(&$arr = array()){
        foreach(self::$supplements as $key){
            if(isset($_SESSION[APP][$key])){
                // ajouter la variable dans le query du HTTP
                $arr[$key] = $_SESSION[APP][$key];
            }
        }
        return $arr;
    }
}
