<?php

namespace Citroen\Html;
/**
 * __DESC__
 *
 * @package Citroen
 * @subpackage Html
 * @copyright Copyright (c) 2014- Business&Decision
 * @author Pierre Pottié  <pierre.pottie@businessdecision.com>
 */
class Util {


	 /**
     * __DESC__
     *
     * @access public
     * @param string $url url contents tags to replace
     * @param array $tags Assoc Array ( tag => replace_value )
     * @param boolean $suppParam delete parameter from url if tag associed with this parameter in the url is not found in $tags or value is empty 
     * @return string
     */
	public function replaceTagsInUrl($url, $tags, $suppParam=false,$addParams=array()){

		$parameters = explode('?',$url);
		$url = $parameters[0];
		if(sizeof($parameters)>3){ return 'not a google format url';}
		if(sizeof($parameters)>2){
			$url .= '?'.$parameters[1];
			$parameters = $parameters[2];
		}else{
			$parameters = $parameters[1];
		}
		
		$params = explode('&',$parameters);
		$parameters =array();
		$add_sharp= '';
		// si un markeur de page dans l'url est à ajouter à (pour differencier d'un marqueur de tags)
		
		if(sizeof($params)){
			$last = sizeof($params)-1;
			if(strpos( $params[$last],'[%DIESE%]',0)){
				$last_param= explode('[%DIESE%]',$params[$last]);
				$params[$last] = $last_param[0];
				$add_sharp = '#'.$last_param[1];
			}
			foreach ($params as $param) {
				list($key,$value) = explode('=',$param);
			 	$value = str_replace(array_keys($tags),array_values($tags),$value);
				if(!empty($key) && (!$suppParam || !empty($value) ) ){
						$parameters [$key]= $key.'='.$value;
				}
			}
		}
		$url = str_replace(array_keys($tags),array_values($tags),$url);
		if(count($addParams)){
			$parameters = array_merge($addParams,$parameters);
		}
		return $url .( sizeof($parameters)?'?'.implode('&',$parameters):'').$add_sharp;
	}
}