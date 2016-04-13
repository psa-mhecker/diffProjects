<?php
/**
 * Page de redimensionnement d'une image
 *
 * @package Pelican
 * @subpackage Pelican_Media
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 15/05/2004
 */

/**
 * Fichier de configuration
 */
include_once ('config.php');

Pelican_Security::checkSessionValue ( $_SESSION [APP] ["user"] ["id"], Pelican::$config ["INDEX_PATH"] . "/login.php" );

/**
 * Librairie de gestion de la mediathèque
 */
require_once (pelican_path ( 'Media' ));
pelican_import ( 'Index' );

Pelican::$frontController = Pelican_Factory::getInstance ( 'Index', false );

$fileUrl = Pelican::$config ["MEDIA_HTTP"] . $_GET ["path"];
$filePath = Pelican_Media::cleanDirectory ( Pelican::$config ["MEDIA_ROOT"] . str_replace ( Pelican::$config ["MEDIA_HTTP"], "", $fileUrl ) );
$file = Pelican_Media::cleanDirectory ( str_replace ( Pelican::$config ["MEDIA_HTTP"], "", $fileUrl ) );
$opacityColor = "#3366dd";
$format = Pelican_Cache::fetch ( "Frontend/MediaFormat", $_GET ["format"] );

if ($_POST) {
	$uploaded = 0;
	/**
	 * Le format a été forcé
	 */
	if ($_FILES ["file_name"] ["name"]) {
		/**
		 * On a forcé le format par un upload
		 */
		$fileName = Pelican_Media::getFileNameMediaFormat ( $_POST ["path"], $_POST ["format"] );
		if (! copy ( $_FILES ["file_name"] ["tmp_name"], getUploadRoot ( $fileName ) )) {
			/**
			 * Erreur lors de la copie du fichier
			 */
			echo ("<script>alert(\"Problème de transfert de fichier.\");history.go(-1);</script>");
			exit ();
		} else {
			chmod ( getUploadRoot ( $fileName ), 0777 );
			$uploaded = 1;
		}
	} else {
		/**
		 * On a forcé le format par un recadrage
		 */
		$_REQUEST ["path"] = rawurlencode ( $_REQUEST ["path"] );
		$_REQUEST ["nocache"] = true;
		require_once (Pelican::$config ['LIB_ROOT'] . Pelican::$config ['LIB_MEDIA'] . "/image_format.php");
	}
	if (isset ( $_REQUEST ["preview"] ) && $_REQUEST ["preview"] != "1") {
		
		/**
		 * On récupère l'id du Pelican_Media et on fait un annule/remplace dans
		 * la table des formats forcés
		 */
		$pathinfo = pathinfo ( rawurldecode ( $_REQUEST ["path"] ) );
		$aPath = explode ( '.', $pathinfo ["basename"] );
		$mdaId = $aPath [count ( $aPath ) - 2];
		
		$oConnection = Pelican_Db::getInstance ();
		
		Pelican_Db::$values ["MEDIA_ID"] = $mdaId;
		Pelican_Db::$values ["MEDIA_FORMAT_ID"] = $_POST ["format"];
		Pelican_Db::$values ["MEDIA_FORMAT_UPLOAD"] = $uploaded;
		
		$oConnection->deletequery ( "#pref#_media_format_intercept" );
		$oConnection->insertquery ( "#pref#_media_format_intercept" );
		
		/**
		 * Rechargement du opener et fermeture de la popup
		 */
		echo ("<script>opener.location.href = opener.location.href; self.close();</script>");
	}
} else {
	
	/**
	 * Récupération des infos de l'image
	 */
	$imageSize = @getimagesize ( $filePath );
	if (! $imageSize) {
		$imageSize [0] = 0;
		$imageSize [1] = 0;
	}
	
	Pelican::$frontController->setTitle ( "Pelican" );
	pelican_import ( 'Controller.Back' );
	include_once (Pelican::$config ['APPLICATION_VIEW_HELPERS'] . '/Div.php');
	
	@Pelican_Controller_Back::_setSkin ( Pelican::$frontController );
	Pelican::$frontController->setJs ( Pelican::$config ['LIB_PATH'] . "/External/bsJavascript/lib/LibCrossBrowser.js" );
	Pelican::$frontController->setJs ( Pelican::$config ['LIB_PATH'] . "/External/bsJavascript/lib/EventHandler.js" );
	Pelican::$frontController->setJs ( Pelican::$config ['LIB_PATH'] . "/External/bsJavascript/core/form/Bs_FormUtil.lib.js" );
	Pelican::$frontController->setJs ( Pelican::$config ['LIB_PATH'] . "/External/bsJavascript/components/slider/Bs_Slider.class.js" );
	Pelican::$frontController->setIncludeHeader ( Pelican::$config ['LIB_ROOT'] . Pelican::$config ['LIB_MEDIA'] . "/js/media_editor.js.php", array ("imageSize" ) );
	Pelican::$frontController->setIncludeHeader ( Pelican::$config ['LIB_ROOT'] . Pelican::$config ['LIB_MEDIA'] . "/css/media_editor.css.php", array ("imageSize", "opacityColor", "fileUrl" ) );
	Pelican::$config['AJAX_ADAPTER'] = false;
    $header = Pelican::$frontController->getHeader ();
	?>
<html>
<head>
<?=$header?>
</head>
<body id="body_popup" style="margin: 0 0 0 0;" onLoad="Init()" onResize="getPreview()">
	<div class="center">
		<br />
		<form target="_blank" action="" method="post" enctype="multipart/form-data" name="formulaire" id="formulaire">
			<fieldset>
				<div class="center">
					<legend><b>Recadrage</b></legend>
					<br />
					<center>
						<div id="sliderDiv" style="text-align : left;width: <?=$imageSize[0]?>px;height: 15px;"></div>
						<div id="container" class="container"><div class="resizeMe" id="haut" name="haut"></div><div class="resizeMe" id="gauche" name="gauche"></div><div class="resizeMe" id="droite" name="droite"></div><div class="resizeMe" id="bas" name="bas"></div><div id="selection" class="selection" onmousedown="drags(event)" onmouseup="undrags(event)" ondblclick="submit()"></div></div>
						<br />
					</center>
				</div>

				<input type="hidden" name="left" value="0px" id="left" onChange="javascript:setPosition(this)"> <input type="hidden" name="top" value="0px" id="top" onChange="javascript:setPosition(this)"> <input type="hidden" name="width" value="<?=$imageSize[0]?>px" id="width" onChange="javascript:setPosition(this)"> <input type="hidden" name="height" value="<?=$imageSize[1]?>px" id="height" onChange="javascript:setPosition(this)"> <input type="hidden" name="crop" value="" id="crop"> <input type="hidden" name="path" value="<?=$_GET["path"]?>" id="path"> <input type="hidden" name="format" value="" id="format"> <input type="hidden" name="preview" value=""> <input type="hidden" name="bypass" value="1">
				<!--			<div id="previewDiv" style="position:absolute;top:0px;left:0px;border:solid 1px lightgrey;display:none"><img id="preview" src=""></div>-->
				<center><button onclick="submitpreview();" name="show">Visualiser</button></center>

				<fieldset>
					<center>
						<legend><b>Remplacement</b></legend>
						<br /> 
						Utiliser le fichier : <input type="file" name="file_name" class="text"> 
						<br /> 
						<br />
					</center>
				</fieldset>
			</fieldset>
		</form>
		<center>
			<p class="bottom">
				<button onclick="submit();" name="<?=t('POPUP_BUTTON_SAVE')?>" id="<?=t('POPUP_BUTTON_SAVE')?>"><?=t('POPUP_BUTTON_OK')?></button>
				&nbsp;
				<button onclick="window.close();"><?=t('POPUP_BUTTON_CANCEL')?></button>
			</p>
		</center>
	</div>
	<iframe id="iframeMediaFormat" width="0" height="0" style="display: none;"></iframe>
</body>
</html>
<?php
}
?>
