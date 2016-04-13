<?php
/**
 * @package Pelican_Cache
 * @subpackage Common
 */

/**
 * Fichier de Pelican_Cache : Liste des skins disponibles
 *
 * @package Pelican_Cache
 * @subpackage General
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 03/10/2009
 */
class Skin_List extends Pelican_Cache {
	
	/** Valeur ou objet à mettre en Pelican_Cache */
	public function getValue() {
		
		
		$cmd = "find " . Pelican::$config ['MEDIA_ROOT'] . "/design/skins -type d";
		exec ( $cmd, $output );
		if ($output) {
			foreach ( $output as $folder ) {
				if (! in_array ( trim($folder), array ('images', 'js' ) )) {
					$path [] = $folder;
				}
			}
		}
		debug ( $path );
		die ();
		$this->value = $result;
	}
}

?>