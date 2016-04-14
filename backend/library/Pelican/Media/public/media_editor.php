<?php
/**
 * Page de redimensionnement d'une image.
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 15/05/2004
 */
use Itkg\Utils\FormatHelper;

/**
 * Fichier de configuration.
 */
include_once 'config.php';

Pelican_Security::checkSessionValue($_SESSION [APP] ["user"] ["id"], Pelican::$config ["INDEX_PATH"]."/login.php");

/**
 * Librairie de gestion de la mediathèque.
 */
require_once pelican_path('Media');

pelican_import('Index');

Pelican::$frontController = Pelican_Factory::getInstance('Index', false);

$mediaHelper = new FormatHelper();

$fileUrl = Pelican::$config ["MEDIA_HTTP"].$_GET ["path"];
$filePath = Pelican_Media::cleanDirectory(Pelican::$config ["MEDIA_ROOT"].str_replace(Pelican::$config ["MEDIA_HTTP"], "", $fileUrl));
$file = Pelican_Media::cleanDirectory(str_replace(Pelican::$config ["MEDIA_HTTP"], "", $fileUrl));
$opacityColor = "#3366dd";
$format = Pelican_Cache::fetch("Frontend/MediaFormat", $_GET ["format"]);

$pathinfo = pathinfo(rawurldecode($_REQUEST["path"]));
$aPath = explode('.', $pathinfo ["basename"]);
$mediaId = $aPath [count($aPath) - 2];
$connection = Pelican_Db::getInstance();
$sql = "select A.*, C.SITE_ID,  ".Pelican::$config["FW_MEDIA_FIELD_FOLDER_PATH"]." as \"folder\" ";
$sql .= " from ".Pelican::$config["FW_MEDIA_TABLE_NAME"]." A,
                                ".Pelican::$config["FW_MEDIA_FOLDER_TABLE_NAME"]." C
                                where A.".Pelican::$config["FW_MEDIA_FIELD_FOLDER_ID"]."=C.".Pelican::$config["FW_MEDIA_FIELD_FOLDER_ID"]."
                                and ".Pelican::$config["FW_MEDIA_FIELD_ID"]."=".$mediaId;

$media = $connection->queryForm($sql);

if ($_POST) {
    $uploaded = 0;
    /*
     * Le format a été forcé
     */
    if ($_FILES ["file_name"] ["name"]) {
        /*
         * On a forcé le format par un upload
         */
        $fileName = Pelican_Media::getFileNameMediaFormat($_POST ["path"], $_POST ["format"]);
        if (! copy($_FILES ["file_name"] ["tmp_name"], getUploadRoot($fileName))) {
            /*
             * Erreur lors de la copie du fichier
             */
            echo("<script>alert(\"Problème de transfert de fichier.\");history.go(-1);</script>");
            exit();
        } else {
            chmod(getUploadRoot($fileName), 0777);
            $uploaded = 1;
        }
    } else {
        /*
         * On a forcé le format par un recadrage
         */
        $_REQUEST ["path"] = rawurlencode($_REQUEST ["path"]);
        $_REQUEST ["nocache"] = true;
        require_once Pelican::$config ['LIB_ROOT'].Pelican::$config ['LIB_MEDIA']."/image_format.php";
    }
    if (isset($_REQUEST ["preview"]) && $_REQUEST ["preview"] != "1") {


        Pelican_Db::$values ["MEDIA_ID"] = $mediaId;
        Pelican_Db::$values ["MEDIA_FORMAT_ID"] = $_POST ["format"];
        Pelican_Db::$values ["MEDIA_FORMAT_UPLOAD"] = $uploaded;

        $connection->deletequery("#pref#_media_format_intercept");
        $connection->insertquery("#pref#_media_format_intercept");

        /*
         * Rechargement du opener et fermeture de la popup
         */
         echo('<script>
                if(opener.mediaeditor) {
                  opener.mediaeditor.callback(
                  "'.Pelican::$config ["MEDIA_HTTP"].Pelican_Media::getFileNameMediaFormat($path, $_REQUEST["format"]).'",
                  "'.Pelican::$config ["MEDIA_HTTP"].$path.'"
                  );
                } else {
                  opener.location.href = opener.location.href;
                }
                self.close();
              </script>');
    }
} else {

    /*
     * Récupération des infos de l'image
     */
    $imageSize = @getimagesize($filePath);
    if (! $imageSize) {
        $imageSize [0] = 0;
        $imageSize [1] = 0;
    }


    Pelican::$frontController->setTitle("Pelican");
    pelican_import('Controller.Back');
    include_once Pelican::$config ['APPLICATION_VIEW_HELPERS'].'/Div.php';

    @Pelican_Controller_Back::_setSkin(Pelican::$frontController);
    Pelican::$frontController->setJs(Pelican::$config ['LIB_PATH']."/External/bsJavascript/lib/LibCrossBrowser.js");
    Pelican::$frontController->setJs(Pelican::$config ['LIB_PATH']."/External/bsJavascript/lib/EventHandler.js");
    Pelican::$frontController->setJs(Pelican::$config ['LIB_PATH']."/External/bsJavascript/core/form/Bs_FormUtil.lib.js");
    Pelican::$frontController->setJs(Pelican::$config ['LIB_PATH']."/External/bsJavascript/components/slider/Bs_Slider.class.js");
    Pelican::$frontController->setIncludeHeader(Pelican::$config ['LIB_ROOT'].Pelican::$config ['LIB_MEDIA']."/js/media_editor.js.php", array("imageSize" ));
    Pelican::$frontController->setIncludeHeader(Pelican::$config ['LIB_ROOT'].Pelican::$config ['LIB_MEDIA']."/css/media_editor.css.php", array("imageSize", "opacityColor", "fileUrl" ));
    Pelican::$config['AJAX_ADAPTER'] = false;
    $header = Pelican::$frontController->getHeader();

    $prop = true;
    if (!empty($media)) {
        // check if we are on the same site
        $prop = ($media['SITE_ID'] == $_SESSION[APP]['SITE_ID']);

    } else {
        //if media doesn't provide the  right id disable crop !! ( old imported media from alex !! )
        $prop = false;
    }

    ?>
<html>
<head>
<?=$header?>
</head>
<body id="body_popup" style="margin: 0 0 0 0;" onLoad="Init()" onResize="getPreview()">
	<div class="center">
		<br />
        <?php if (!$prop) : ?>
        <div class="center">
                <br />
                <br />
                <br />
                <p><?php echo t('NO_PROPRIETAIRE') ?></p>
            <p class="bottom">
                <button onclick="window.close();"><?=t('POPUP_BUTTON_CANCEL')?></button>
            </p>
        </div>
        <?php elseif ($imageSize[0] < $format['MEDIA_FORMAT_WIDTH'] || $imageSize[1]  <  $format['MEDIA_FORMAT_HEIGHT']) : ?>
            <div class="center">
                <br />
                <br />
                <br />
                <p><?php echo t('NPD_CANT_CROP_IMAGE') ?></p>
                <p class="bottom">
                    <button onclick="window.close();"><?=t('POPUP_BUTTON_CANCEL')?></button>
                </p>
            </div>
        <?php else : ?>
		<form target="_blank" action="" method="post" enctype="multipart/form-data" name="formulaire" id="formulaire">
			<fieldset>
				<div class="center">
					<legend style="text-align: left"><b>Recadrage</b> <br /><?php echo $mediaHelper->getFormatInformation($format, false); ?>  </legend>
					<br />
						<div id="sliderDiv" style="text-align : left;width: <?=$imageSize[0]?>px;height: 15px;"></div>
						<div id="container" class="container"><div class="resizeMe" id="haut" name="haut"></div><div class="resizeMe" id="gauche" name="gauche"></div><div class="resizeMe" id="droite" name="droite"></div><div class="resizeMe" id="bas" name="bas"></div><div id="selection" class="selection" onmousedown="drags(event)" onmouseup="undrags(event)" ondblclick="submit()"></div></div>
						<br />
				</div>

				<input type="hidden" name="left" value="0px" id="left" onChange="javascript:setPosition(this)"> <input type="hidden" name="top" value="0px" id="top" onChange="javascript:setPosition(this)"> <input type="hidden" name="width" value="<?=$imageSize[0]?>px" id="width" onChange="javascript:setPosition(this)"> <input type="hidden" name="height" value="<?=$imageSize[1]?>px" id="height" onChange="javascript:setPosition(this)"> <input type="hidden" name="crop" value="" id="crop"> <input type="hidden" name="path" value="<?=$_GET["path"]?>" id="path"> <input type="hidden" name="format" value="" id="format"> <input type="hidden" name="preview" value=""> <input type="hidden" name="bypass" value="1">
                <div class="center"><button onclick="submitpreview();" name="show">Visualiser</button></div>
			</fieldset>
		</form>
        <div class="center">
			<p class="bottom">
				<button onclick="submit();" name="<?=t('POPUP_BUTTON_SAVE')?>" id="<?=t('POPUP_BUTTON_SAVE')?>"><?=t('POPUP_BUTTON_OK')?></button>
				&nbsp;
				<button onclick="window.close();"><?=t('POPUP_BUTTON_CANCEL')?></button>
			</p>
		</div>
	</div>
	<iframe id="iframeMediaFormat" width="0" height="0" style="display: none;"></iframe>
        <?php endif ?>
</body>
</html>
<?php

}
?>
