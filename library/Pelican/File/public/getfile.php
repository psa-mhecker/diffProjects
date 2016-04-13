<?php
	/**
	* @package Pelican
	* @subpackage File
	*/
	 
	/** Fichier de configuration */
	include_once('config.php');
	require_once(Pelican::$config['LIB_ROOT'].Pelican::$config['LIB_FILE'].Pelican::$config['CLASS_FILE']);
	 
	if (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE 6")) {
		$browser_version = 6;
	}
	else if(strstr($_SERVER["HTTP_USER_AGENT"], "MSIE 5")) {
		$browser_version = 5;
	}
	 
	$basedir = Pelican::$config["MEDIA_ROOT"]."/".$_GET["projet_id"]."/doc";
	$httpdir = "http://".str_replace("//", "/", str_replace(Pelican::$config['DOCUMENT_ROOT'], $_SERVER["HTTP_HOST"]."/", $basedir));
	$file = str_replace("//", "/", $_GET["file"]);
	$file_name = explode("/", $file);
	 
	$mtype = $type_mime[getExtension($file)][0];
	$size = @filesize($basedir.$file);
	 
	switch($_GET["action"]) {
		case "view" :
		switch($browser_version) {
			case 5:
			header("Content-Disposition: attachment; filename=\"".$file_name[count($file_name)-1]."\"");
			header("Content-type: $mtype");
			header("Content-length: $size");
			break;
			case 6 :
			default :
			header("Content-disposition:attachment; filename=\"".$file_name[count($file_name)-1]."\"");
			header("Content-type: $mtype");
			header("Pragma: no-cache");
			header("Expires: 0");
			header("Content-Length: ".$size);
			break;
		}
		break;
		case "download" :
		default :
		switch($browser_version) {
			case 5:
			header("Content-length: $size");
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"".$file_name[count($file_name)-1]."\"");
			header("Content-Description: PHP Generated Data" );
			header("Content-Transfer-Encoding: binary");
			break;
			case 6 :
			default :
			header("Content-disposition: attachment; filename=\"".$file_name[count($file_name)-1]."\"");
			header("Content-type: application/octetstream");
			header("Pragma: no-cache");
			header("Expires: 0");
			header("Content-Length: ".$size);
			break;
		}
		break;
	}
	readfile($basedir.$file);
	//header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
	//header("Cache-Control: post-check=0, pre-check=0", false);
	//header("Pragma: no-cache");
	 
?>