<?php
/**
 * Plugin urlParser appel Citroen\Url::parse($url)
 * CPW-4042
 * @param array $params : argument 'url' est nécessaire
 * @param type $view
 * @return string $url : l'url encapsulé 
 */
function smarty_function_urlParser($params, &$view)
{
    $url = '';
    if ( is_array($params) && isset($params['url']) && !empty($params['url']) ) {
        $url = $params['url'];
    }
 
    return Citroen\Url::parse($url);
}
