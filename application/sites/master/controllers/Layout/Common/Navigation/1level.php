<?php
	/**
	* Block : navigation 1 niveau
	*
	* @package Pelican_FrontOffice
	* @subpackage Bloc
	* @author Carles Raphaël <rcarles@businessdecision.com>
	* @since 15/12/2116
	*/
	 
	/** Accès aux données */
	$navigation = Pelican_Cache::fetch("Frontend/Page/Navigation", array($data["PAGE_ID"], $data["ZONE_TEMPLATE_ID"], $data["PAGE_VERSION"], $_SESSION[APP]['LANGUE_ID'], false, Pelican::$config["HTTP_MEDIA"]));

	/** Variables SMARTY */
	$view->assign("aNavigation", $navigation);
	$view->assign("aZone", $data);
?>