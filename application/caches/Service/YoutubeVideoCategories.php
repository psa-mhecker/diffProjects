<?php

use Itkg\Apis\Google\Youtube\V3\Youtube;

class Service_YoutubeVideoCategories extends Pelican_Cache {
	
	var $duration = UNLIMITED;
	
	/** Valeur ou objet à mettre en Pelican_Cache */
	function getValue() {
		
		Itkg::$config['ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE']['PARAMETERS']['uri'] = '/youtube/v3/videoCategories';
		$oService = Itkg\Service\Factory::getService('ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE');

		$aDatas = array('part'=>'snippet', 'regionCode'=> 'fr', 'hl'=> 'fr', 'key'=>$key);
		$oResponse = $oService->call('videoCategoriesList', $aDatas);
		$aYoutubeCat = array();
		if(count($oResponse->items) > 0) {
			foreach($oResponse->items as $youtubeCat) {
				if($youtubeCat->snippet->assignable) {
					$aYoutubeCat[$youtubeCat->id] = $youtubeCat->snippet->title;
				}
			}
			natcasesort($aYoutubeCat);
		}
		$this->value = $aYoutubeCat;
	}
}

?>