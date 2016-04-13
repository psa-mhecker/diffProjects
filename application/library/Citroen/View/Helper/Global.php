<?php
/**
 * View Global
 * 
 * @version 1.0
 * @since 21/08/2013
 */

Class Citroen_View_Helper_Global {
	
	/**
	* M�thode permettant de transformer un objet en array
	*
	* @param object $arrObjData
	* @return array $arrData
	*/
	
	public static function objectsIntoArray($arrObjData, $lan='')
    {
        $arrData = array();
        
        if (is_object($arrObjData)) {
            $arrObjData = get_object_vars($arrObjData);
        }
        
        if (is_array($arrObjData)) {
            foreach ($arrObjData as $index => $value) {
                if (is_object($value) || is_array($value)) {
                    $value = self::objectsIntoArray( $value, $lan );
                }
                if (is_string($value)) {
                	
                	if( preg_match( '/[\p{Cyrillic}]/u', $value) || mb_detect_encoding($data, 'UTF-8', true) == 'UTF-8' ){
						$value = $value;
					}else{
							$value = utf8_decode($value);
					}
                    
                }
                $arrData[$index] = $value;
            }
        }
        return $arrData;
    }

    /**
     * Méthode permettant de faire un htmlentites sur les values d'un tableau
     *
     * @param object $arrObjData
     * @return array $arrData
     */

    public static function arrHtmlDecode($arr)
    {
        $arrData = array();

        if (is_array($arr)) {
            foreach ($arr as $index => $value) {
                if (is_object($value) || is_array($value)) {
                    $value = self::arrHtmlDecode($value);
                }
                if (is_string($value)) {
                    $value = urldecode($value);

                }
                $arrData[$index] = $value;
            }
        }
        return $arrData;
    }

    /**
     * Méthode permettant de décoder les entités html sur les values d'un tableau
     *
     * @param object $arrObjData
     * @return array $arrData
     */

    public static function arrHtmlEncode($arr)
    {
        $arrData = array();

        if (is_array($arr)) {
            foreach ($arr as $index => $value) {
                if (is_object($value) || is_array($value)) {
                    $value = self::arrHtmlEncode($value);
                }
                if (is_string($value)) {
                    $value = urlencode($value);

                }
                $arrData[$index] = $value;
            }
        }
        return $arrData;
    }
    
    public static function querySearch() {
        
        $referer = $_SERVER['HTTP_REFERER'];
        
        if (isset($referer) && $referer != '') {
            $searchEngine = array(
                'yahoo.com' => 'p=',
                'altavista.com' => 'q=',
                'google' => 'q=',
                'lycos' => 'query=',
                'hotbot' => 'query=',
                'search.msn' => 'q=',
                'webcrawler.com' => 'search/web/',
                'excite.com' => 'search/web/',
                'netscape.com' => 'query=',
                'mamma.com' => 'query=',
                'alltheweb.com' => 'q=',
                'ledepart.com' => 'q=',
                'entireweb.com' => 'q=',
                'dir.com' => 'req=',
                'ask.com' => 'q=',
                'dmoz.org' => 'search=',
                'looksmart.com' => 'key=',
                'aol.com' => 'query=',
                'alexa.com' => 'q=',
                'wisenut.com' => 'q=',
                'overture.com' => 'Keywords=',
                'net.net' => 'Keywords=',
                'oemji.com' => 'Keywords=',
                'skynet.be' => 'keywords=',
                'instafinder.com' => 'Keywords=',
                'mirago.fr' => 'qry=',
                'excite.fr' => 'q=',
                'netscape.fr' => 'q=',
                'voila.fr' => 'kw=',
                'tiscali.fr' => 's=',
                'dmoz.fr' => 'search=',
                'aol.fr' => 'q=',
                'neuf.fr' => 'keywords=',
                'recherche.fr' => 'keywords=',
                'illiko.com' => 'Keywords=',
                'antisearch.net' => 'KEYWORDS='
            );
            
            $aReferer = pathinfo($referer);
            foreach ($searchEngine as $engine => $query) {
                if (strpos($aReferer['dirname'], $engine) !== false) {
                    $search = '';
                    if (preg_match('/[?|&|\/]'.$query.'([^&]+)/', $referer, $matches)) {
                        $search = $matches[1];
                    }
                    return $search;
                }
            }
            return false;
        } else {
            return false;
        }
    }
}
?>