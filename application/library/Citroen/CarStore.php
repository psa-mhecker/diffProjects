<?php

namespace Citroen;
use Citroen\Html\Util;
use Pelican;


/**
 * Class Carstore 
 *
 * @author Pierre Pottié <pierre.pottie@businessdecision.com>
 *
 */
class CarStore{

   function getCarStoreUrl($data,$configuration,$isMobile=false){

             $tags = array(
               '##LCDV_CURRENT##' => $data['LCDV6'],
               '##LCDV6##' => $data['LCDV6'],
               '##LCDV4##' => substr($data['LCDV6'], 0, 4),
               '##LCDV2##' => substr($data['LCDV6'], 4),
               '##CULTURE##'  => $data['CULTURE'],
               '##LATITUDE##' => $data['LATITUDE'],
               '##LONGITUDE##'=> $data['LONGITUDE'],
               '##RADIUS##' => $data['RADIUS']
               
            );
       

      $vehiculeGamme = \Pelican_Cache::fetch("Citroen/GammeVehiculeGamme", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            $data['LCDV6'],
            'row'
      ));
	  
	  	
      if(array_key_exists('StoreDetailUrl',$data) && !empty($data['StoreDetailUrl'])){
		 
         if(empty($configuration['URL_REBOND_CARSTORE']) || self::inLoop($configuration['URL_REBOND_CARSTORE'])  ){
                  $careStoreUrl = $data['StoreDetailUrl'];
               }else{
                  $careStoreUrl = $configuration['URL_REBOND_CARSTORE'];  

                  $careStoreUrl .= (strpos($careStoreUrl,'?')?'&':'?').'car='.$data['CarNum'];
                  //rebond necessaire l'url doit etre enregistré pour la recuperation dans la page de rebond
                  $_SESSION['CarStoreUrl'][$data['CarNum']] = $data['StoreDetailUrl'];
               }
            
      }elseif(array_key_exists('VehicleWebstoreLink',$data) && empty($data['VehicleWebstoreLink'])){
		 
		if(empty($configuration['URL_REBOND_CARSTORE']) || self::inLoop($configuration['URL_REBOND_CARSTORE'])  ){
			  $careStoreUrl = $data['VehicleWebstoreLink'];
		   }else{
			  $careStoreUrl = $configuration['URL_REBOND_CARSTORE'];  

			  $careStoreUrl .= (strpos($careStoreUrl,'?')?'&':'?').'car='.$data['VehicleCarNum'];
			  //rebond necessaire l'url doit etre enregistré pour la recuperation dans la page de rebond
			  $_SESSION['CarStoreUrl'][$data['VehicleCarNum']] = $data['VehicleWebstoreLink'];
		   }
	  
	   }else{
          
   		if($vehiculeGamme['GAMME'] ==  \Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VU']){
           
   			if(empty($configuration['URL_REBOND_CARSTORE']) || self::inLoop($configuration['URL_REBOND_CARSTORE'])  ){
   				  $careStoreUrl = $configuration['URL_CARSTORE_PRO'];  
               }else{
              		$careStoreUrl = $configuration['URL_REBOND_CARSTORE'];	
               }
   		}else{
			 
             
			   if(!empty($configuration['URL_CARSTORE'])){
				   $careStoreUrl = $configuration['URL_CARSTORE'.(($isMobile ==  true)?'_MOBILE':'')];
			   }elseif(empty($configuration['URL_REBOND_CARSTORE']) || self::inLoop($configuration['URL_REBOND_CARSTORE'])){
				    $careStoreUrl = $configuration['URL_REBOND_CARSTORE'];  
			   }
			}
        
		}
       $careStoreUrl = Util::replaceTagsInUrl( $careStoreUrl, $tags, true );

      return $careStoreUrl;
   }
// empeche de boucler indefiniment sur la page de rebond
   protected function inLoop($url){
      $url = parse_url($url);
      $url_current = parse_url( $_SERVER['REQUEST_URI']);
      
      return ($url['path'] == $url_current['path']);
   }
}