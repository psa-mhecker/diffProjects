<?php

/**
 * @package Cache
 * @subpackage General
 */

/**
 * Fichier de Pelican_Cache : Mise en cache du resultat d'une Requ�te
 *
 * @package Cache
 * @subpackage General
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 30/06/2011
 */
class Request extends Pelican_Cache {
	
	/**
	 * Valeur ou objet à mettre en Pelican_Cache
	 */
	function getValue() {
		
		Pelican_Request::$cacheUsed = false;
		
		$uri = $this->params [0];
		$localParams = unserialize ( $this->params [1] );
		$cacheid = $this->params [2];
		
		$response = Pelican_Request::call ( $uri, $localParams );
		
		$this->value = $response;
	}
}
?>