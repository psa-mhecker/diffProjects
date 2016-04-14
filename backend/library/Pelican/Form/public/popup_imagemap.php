<?php
/**
* @package Pelican
* @subpackage Pelican_Form
* 
 */
include_once('config.php');
include(pelican_path('Media'));
if (is_numeric($_GET["image"])){
	$imagePath=Pelican::$config["MEDIA_HTTP"].Pelican_Media::getMediaPath($_GET["image"]);
} else{
	$imagePath=$_GET["image"];
}
$width=($_GET["width"]?$_GET["width"]:"");
$height=($_GET["height"]?$_GET["height"]:"");

?>
<html>
<?php
pelican_import('Index');
Pelican::$frontController = new Pelican_Index ( false );
Pelican::$frontController->setTitle ( "Images map");
Pelican::$frontController->setBackofficeSkin ( Pelican::$config ["SKIN"], "/library/Pelican/Index/Backoffice/public/skins", Pelican::$config ["DOCUMENT_INIT"], "screen,print" );
Pelican::$frontController->setCss ( Pelican::$frontController->skinPath . "/css/popup.css.php" );
echo Pelican::$frontController->getHeader ();
?>
<body id="body_popup" style="text-align:let !important;" leftmargin="3" topmargin="3">
  	<script type="text/javascript">
		var libDir='<?=Pelican::$config["LIB_PATH"]?>';
		var mediaDir='<?=Pelican::$config["MEDIA_LIB_PATH"]?>';
		var httpMediaDir='<?=Pelican::$config["MEDIA_HTTP"]?>';
	</script>
	<script src="../Pelican/Translate/public/js/language.js.php" type="text/javascript"></script>
	<script src="./js/xt_popup_fonctions.js" type="text/javascript"></script>
	<script>
	function select() {
		if (document.getElementById('area_html').value) {
			window.returnValue = document.getElementById('area_html').value;
		}
		window.close();
		
	}
	</script>
	<script type="text/javascript" src="imgmap/excanvas.js"></script>
	<link rel="stylesheet" href="imgmap/imgmap.css" type="text/css">
<div id="maindivcontainer">
<div id="maindiv">
<div id="div_content" style="width: 100%;">
	<form id="img_area_form">
		<fieldset>
			<legend>
				<a onclick="javascript:toggleFieldset(this.parentNode.parentNode)">Zones</a>
			</legend>
			<div id="actions2">
				<img id="i_add"       onclick="addNewArea()">
				<img id="i_delete"    onclick="removeArea(currentid)">
				<img id="i_preview"   onclick="togglePreview()">
			</div>
			<div id="img_area_container">
			<!-- ide jonnek a formelemek -->
       </div>
		</fieldset>
		<br />
		<fieldset>
			<legend>
				<a onclick="javascript:toggleFieldset(this.parentNode.parentNode)"><?=t('Image')?></a>
			</legend>
			<div id="pic_container" style="text-align: left;">
			<img id="pic" src="<?=$imagePath?>" <?php if($width && $height){?>width="<?=$width?>" height="<?=$height?>"<?php }?> usemap="">
			<div id="map_preview">
			<!-- feed preview imgmap Pelican_Html here -->
			</div>
			</div>			
		</fieldset>
			<div id="status">Prêt</div>

		<fieldset id="fieldset_html" style="display:none;">
			<legend>
				<a onclick="javascript:toggleFieldset(this.parentNode.parentNode)"><?=t('CODE')?></a>
			</legend>
			<div >
			<img id="i_clipboard" onclick="toClipBoard(document.getElementById('area_html').value)">
			<textarea id="area_html"></textarea>
			</div>
		</fieldset>
		
	</form>
	<script type="text/javascript" src="imgmap/imgmaps.js"></script>
  <p class="bottom">
				<span id="buttonOk">
                    <button onClick="select();"><script type="text/javascript">getLabel("POPUP_BUTTON_OK")</script></button>
                    &nbsp;
                    </span>
     <button onclick="window.close();"><script type="text/javascript">getLabel("POPUP_BUTTON_CLOSE")</script></button>
  </p>
</div>
	<script>
		//on remplit le map avec le map courant (passé en argument dans la fonction popupImageMap)
		if(window.dialogArguments["map"]){
			setMapHTML(window.dialogArguments["map"]);	
		}
	</script>
</body>
</html>