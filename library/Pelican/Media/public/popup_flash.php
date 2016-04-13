<?php
	/**
	* Popup d'upload uniquement de la mediatheque
	*
	* @package FrameworkBetD
	* @subpackage media_tools
	* @author Raphaël Carles <rcarles@businessdecision.com>
	* @since 15/05/2004
	*/

	/** Fichier de configuration */
	require_once("config.php");
    require_once (pelican_path ( 'Media' ));
    pelican_import ( 'Index' );
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"> 
<html>
   <head>
   		<title>Prévisualisation de la vidéo</title>
		<link href="<?=Pelican::$config["CSS_BACK_PATH"];?>" rel="stylesheet" type="text/css" />
		<meta http-equiv="Content-Type" content="text/html<?=(Pelican::$config["CHARSET"]?"; charset=".Pelican::$config["CHARSET"]:"")?>" />
		<script type="text/javascript">getWindowTitle("POPUP_UPLOAD_TITLE")</script>
		<script src="<?=Pelican::$config["LIB_PATH"]?>/External/swfobject/swfobject.js" type="text/javascript"></script>
		<script src="<?=Pelican::$config["MEDIA_HTTP"]?>/flashplayer/ufo.js" type="text/javascript"></script>
   </head>
   <body class="nomargin" style="text-align:center;background-color:#cccccc">
   <div>
<?
	echo(Pelican_Media::getFlashPlayer(1,$_GET["id"],1));
	echo(Pelican_Media::getFlashPlayer(2,$_GET["id"],2));
?>
	</div>
   </body>
</html>
