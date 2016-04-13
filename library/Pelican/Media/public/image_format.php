<?php
/**
 * Page de récupération ou de génération d'un format d'image (les tailles étant définies en base de données)
 *
 * @package Pelican
 * @subpackage Pelican_Media
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 15/06/2004
 */

/** Fichier de configuration */
include_once('config.php');
/** Librairie de gestion de la mediathèque */
require_once(pelican_path('Media'));
/** Classe de génération d'image */
require_once(pelican_path('Cache.Media'));

if (!valueExists($_REQUEST, "crop")) {
    $_REQUEST["crop"] = "";
}
if (!valueExists($_REQUEST, "bypass")) {
    $_REQUEST["bypass"] = "";
}

if ($include404) {
    preg_match('#\/(png|jpg|gif|jpeg|bmp)\/([0-9]+)\/([0-9]+)\/(.*)#', rawurldecode($include404), $match);
    if ($match) {
        $extension = $match[1];
        $width = $match[2];
        $height = $match[3];
        $_REQUEST["path"] = rawurlencode(str_replace(Pelican::$config['MEDIA_HTTP'], '', $match[4]));
    } else {
        $file = str_replace(Pelican::$config["MEDIA_ROOT"], "", getUploadRoot(rawurldecode($include404)));
        $pathinfo = pathinfo($file);
        $explode = explode(".", $pathinfo["basename"]);
        if ($explode[count($explode) - 1] == $pathinfo["extension"]) {
            $_REQUEST["path"] = str_replace("." . $explode[count($explode) - 2] . "." . $pathinfo["extension"], "." . $pathinfo["extension"], $include404);
            $_REQUEST["format"] = str_replace("_", "", $explode[count($explode) - 2]);
            $life = DAY;
        }
    }
}
if ($_REQUEST["path"]) {
    $path = rawurldecode($_REQUEST["path"]);
} else {
    if (!$_GET["format"] && !$_GET["path"]) {
        $path = "/xtrans.gif";
    }
}

/** On vérifie l'existence du fichier forcé */
$exists = false;
if ($path) {
    if (isset($_REQUEST["preview"]) && $_REQUEST["preview"] != "1") {
        $pathinfo = pathinfo($path);
        $store = getUploadRoot(Pelican_Media::getFileNameMediaFormat($path, $_REQUEST["format"]));
    }
    /** récupération de l'image */
    if (!$imageGab) {
        $imageGab = Pelican_Cache::fetch("Frontend/MediaFormat", $_REQUEST["format"]);
    }

    $image = new Pelican_Cache_Media($path, $_REQUEST["format"], str_replace('-','',$_REQUEST["crop"]), ($_REQUEST["bypass"] ? true : false), $life, $imageGab["MEDIA_FORMAT_COMPLETE_COLOR"], $width, $height, $extension);
    if ($store) {
        $fp = fopen($store, "wb");
        if (@fwrite($fp, $image->value)) {
            @fclose($fp);
        }
    } else {
        header("Content-Type: " . Pelican::$config["IM_MIME"]);
        echo ($image->value);
    }
} else {
    $imageGab = Pelican_Cache::fetch("Frontend/MediaFormat", $_REQUEST["format"]);
    
    ?>
<script type="text/javascript">
			top.document.getElementById("top").value=0;
			top.document.getElementById("left").value=0;
			top.document.getElementById("width").value=<?=($imageGab["MEDIA_FORMAT_WIDTH"] ? $imageGab["MEDIA_FORMAT_WIDTH"] : "0")?>;
			top.document.getElementById("height").value=<?=($imageGab["MEDIA_FORMAT_HEIGHT"] ? $imageGab["MEDIA_FORMAT_HEIGHT"] : "0")?>;
			top.setPosition(top.document.getElementById("top"));
			top.setPosition(top.document.getElementById("left"));
			top.setPosition(top.document.getElementById("width"));
			top.setPosition(top.document.getElementById("height"));
			top.changeSlider('<?=$imageGab["MEDIA_FORMAT_WIDTH"]?>','<?=$imageGab["MEDIA_FORMAT_HEIGHT"]?>');
			</script>
<?php
}
?>