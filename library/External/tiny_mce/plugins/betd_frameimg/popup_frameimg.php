<?php
	/** Popup de sélection de contenus internes simplifié
	*
	* @author Raphaël Carles <rcarles@businessdecision.com>
	* @since 15/10/2004
	* @package Pelican
	* @subpackage External/tinyMCE
	*/
	 
	/** Fichier de configuration */
	include_once('config.php');
	$_GET["tid"] = Pelican::$config["TPL_CONTENT"];
	$_GET["action"] = "editorframeimg";
	 
	 
	//index_child avec le tid correspondant aux contenus
	include(Pelican::$config['DOCUMENT_ROOT']."/index_child.php");
?>