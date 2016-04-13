<?php

class Cms_Page_Module_Youtube extends Cms_Page_Module {
	
	public static function render(Pelican_Controller $controller) {
		if ($controller->zoneValues ["ZONE_TEXTE"]) {
			$details = Pelican_Cache::fetch ( "Service/Youtube", array ('id', $controller->zoneValues ["ZONE_TEXTE"], date ( "M-d-Y", mktime () ) ) );
			if (! $controller->zoneValues ["ZONE_PARAMETERS"]) {
				$controller->zoneValues ["ZONE_PARAMETERS"] = $details ['width'] . 'x' . $details ['height'];
			}
		}
		
		$return = $controller->oForm->createInput ( $controller->multi . "ZONE_TITRE", t ( 'TITRE' ), 150, "", false, $controller->zoneValues ["ZONE_TITRE"], $controller->readO, 70 );
		$return .= $controller->oForm->createMedia ( $controller->multi . "ZONE_TEXTE", 'Vidéo Youtube', false, "youtube", "", $controller->zoneValues ["ZONE_TEXTE"], $controller->readO, true, false );
		$return .= $controller->oForm->createInput ( $controller->multi . "ZONE_PARAMETERS", t ( 'Player size' ), 20, "", false, $controller->zoneValues ["ZONE_PARAMETERS"], $controller->readO, 20 );
		
		return $return;
	}
}