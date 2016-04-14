<?php
/**
 * @ignore
 */
header("Content-type: text/css");
include_once 'config.php';
getAppVersion();

initVar($popup);
initVar($popup_content);
initVar($popup_media);
initVar($tmp);
initVar($firefox2);
initVar(Pelican::$config ['ENV'] ['LOCAL'] ['NAVIGATOR'] ['firefox']);
initVar($_SESSION ["screen_width"]);
initVar($_SESSION ["screen_height"]);
initVar($_SESSION [APP] ["PROFILE_ID"]);
initVar($_SESSION [APP] ['SITE_ID']);
initVar($_SESSION [APP] ["navigation"] ["site"] [$_SESSION [APP] ["PROFILE_ID"]."_".$_SESSION [APP] ['SITE_ID']] ['onglet']);

$page = $_GET ['page'];

if ($page == "_/Media/popup") {
    $popup_media = true;
    $popup = true;
}

if ($page == "_/Popup/content") {
    $popup_content = true;
    $popup = true;
}

if (basename($page) == 'popup_media.php' || basename($page) == 'popup_content.php') {
    $popup_editor = true;
    $popup = true;
}

$bouton_height = 20;
if ($popup_front) {
    $window_width = 650;
    $window_height = 400;
} elseif ($popup_content) {
    $window_width = 1000;
    $window_height = 400;
    $bouton_height = 0;
} elseif ($popup_media) {
    $window_width = 700;
    $window_height = 400;
} elseif ($popup_editor) {
    $window_width = 650;
    $window_height = 310;
} else {
    $window_width = $_SESSION ["screen_width"] - $tmp;
    $window_height = $_SESSION ["screen_height"] - $tmp;
}
if ($popup_front) {
    $window_margin = 0;
    $window_correction = 0;
    $main_margin = 3;
} else {
    $window_margin = 20;
    $window_correction = 10;
    $main_margin = 10;
}

$firefox = 0;
if (! Pelican::$config ['ENV'] ['LOCAL'] ['NAVIGATOR'] ['ie'] || $popup) {
    $firefox = 8;
    $firefox2 = 2;
}

$pelican = (($popup) ? - $main_margin : 31 * count($_SESSION [APP] ["navigation"] ["site"] [$_SESSION [APP] ["PROFILE_ID"]."_".$_SESSION [APP] ['SITE_ID']] ['onglet']));

$aDiv ['div_main'] ['top'] = $window_margin;
$aDiv ['div_main'] ['left'] = 0;
$aDiv ['div_main'] ['width'] = $window_width - $window_correction;
$aDiv ['div_main'] ['height'] = $window_height - $window_correction - 2 * $main_margin - 4 * $firefox;

$aDiv ['div_header'] ['top'] = 0;
$aDiv ['div_header'] ['left'] = 0;
$aDiv ['div_header'] ['width'] = "100%";
$aDiv ['div_header'] ['height'] = 90;

if ($popup) {
    $aDiv ['div_content'] ['top'] = $main_margin;
    $aDiv ['div_content'] ['left'] = 5;
    $aDiv ['div_content'] ['width'] = $window_width - $window_margin - $window_correction;
    $aDiv ['div_content'] ['height'] = $window_height;
} else {
    $aDiv ['div_content'] ['top'] = $main_margin;
    $aDiv ['div_content'] ['left'] = $main_margin;
    $aDiv ['div_content'] ['width'] = $aDiv ['div_main'] ['width'] - 2 * $main_margin;
    $aDiv ['div_content'] ['height'] = $aDiv ['div_main'] ['height'] - $aDiv ['div_header'] ['height'] + $main_margin;
}

$aDiv ['div_footer'] ['top'] = 0;
$aDiv ['div_footer'] ['left'] = 0;
$aDiv ['div_footer'] ['width'] = 0;
$aDiv ['div_footer'] ['height'] = 0;

$aDiv ['frame_left_top'] ['top'] = 2 * $main_margin;
$aDiv ['frame_left_top'] ['left'] = $main_margin;
$aDiv ['frame_left_top'] ['width'] = ($popup_front ? 0 : 215);
$aDiv ['frame_left_top'] ['height'] = ($popup_front ? 0 : 15);

$aDiv ['frame_left_middle'] ['top'] = $aDiv ['frame_left_top'] ['top'] + $aDiv ['frame_left_top'] ['height'] + 15;
$aDiv ['frame_left_middle'] ['left'] = $main_margin; //$aDiv['frame_left_top']['left'];
$aDiv ['frame_left_middle'] ['width'] = $aDiv ['frame_left_top'] ['width'] - $firefox;
$aDiv ['frame_left_middle'] ['height'] = $aDiv ['div_content'] ['height'] - $aDiv ['frame_left_top'] ['height'] - 3 * $main_margin - $bouton_height - $pelican;

$aDiv ['frame_left_bottom'] ['top'] = $aDiv ['frame_left_middle'] ['top'] + $aDiv ['frame_left_middle'] ['height'] + 3 * $main_margin + $pelican - 10 + $firefox;
$aDiv ['frame_left_bottom'] ['left'] = $main_margin; //$aDiv['frame_left_top']['left'];
$aDiv ['frame_left_bottom'] ['width'] = $aDiv ['frame_left_top'] ['width'];
$aDiv ['frame_left_bottom'] ['height'] = $bouton_height;

$aDiv ['frame_right_top'] ['top'] = $aDiv ['frame_left_top'] ['top'];
$aDiv ['frame_right_top'] ['left'] = $aDiv ['frame_left_top'] ['left'] + $aDiv ['frame_left_top'] ['width'] + $main_margin;
$aDiv ['frame_right_top'] ['width'] = $aDiv ['div_content'] ['width'] - 3 * $main_margin - $aDiv ['frame_left_top'] ['width'];
$aDiv ['frame_right_top'] ['height'] = $aDiv ['frame_left_top'] ['height'];

$aDiv ['frame_right_middle'] ['top'] = $aDiv ['frame_left_middle'] ['top'];
$aDiv ['frame_right_middle'] ['left'] = $aDiv ['frame_right_top'] ['left'];
$aDiv ['frame_right_middle'] ['width'] = $aDiv ['frame_right_top'] ['width'] - $firefox;
$aDiv ['frame_right_middle'] ['height'] = $aDiv ['frame_left_middle'] ['height'] + $pelican + $main_margin;

$aDiv ['frame_right_bottom'] ['top'] = $aDiv ['frame_left_bottom'] ['top'];
$aDiv ['frame_right_bottom'] ['left'] = $aDiv ['frame_right_top'] ['left'];
$aDiv ['frame_right_bottom'] ['width'] = $aDiv ['frame_right_top'] ['width'];
$aDiv ['frame_right_bottom'] ['height'] = $aDiv ['frame_left_bottom'] ['height'];

$aDiv ['iframeRight'] ['top'] = 0;
$aDiv ['iframeRight'] ['left'] = 0;
$aDiv ['iframeRight'] ['width'] = $aDiv ['frame_right_middle'] ['width'] - 2 + $firefox;
$aDiv ['iframeRight'] ['height'] = $aDiv ['frame_right_middle'] ['height'] - 2 + $firefox;

$aDiv ['div_onglet'] ['top'] = $aDiv ['div_content'] ['top'] + $aDiv ['frame_left_middle'] ['top'] + $aDiv ['frame_left_middle'] ['height'] + 3 * $main_margin - 1 + $firefox;
$aDiv ['div_onglet'] ['left'] = 2 * $main_margin;
$aDiv ['div_onglet'] ['width'] = $aDiv ['frame_left_top'] ['width'] - $firefox2;
$aDiv ['div_onglet'] ['height'] = $pelican;

/***********************************/

$aDiv ['tree'] ['position'] = 'relative';
$aDiv ['tree'] ['top'] = 0;
$aDiv ['tree'] ['left'] = 0;
$aDiv ['tree'] ['width'] = $aDiv ['frame_left_top'] ['width'] - $main_margin;
$aDiv ['tree'] ['height'] = $aDiv ['frame_left_middle'] ['height'] - $main_margin;

$aDiv ['properties'] = $aDiv ['tree'];

$aDiv ['div_fieldset'] = $aDiv ['div_content'];
$aDiv ['div_fieldset'] ['top'] = 0;
$aDiv ['div_fieldset'] ['height'] += 5 * $main_margin;
$aDiv ['div_fieldset'] ['width'] -= $firefox;

$aDiv ['div_popup_footer'] ['top'] = $aDiv ['div_content'] ['top'] + $aDiv ['div_fieldset'] ['height'] + $firefox;
$aDiv ['div_popup_footer'] ['left'] = $aDiv ['div_content'] ['left'];
$aDiv ['div_popup_footer'] ['width'] = $aDiv ['div_content'] ['width'];
$aDiv ['div_popup_footer'] ['height'] = $aDiv ['frame_left_bottom'] ['height'];

/***********************************/

echo putDivStyle($aDiv);

function putDivStyle($aDiv)
{
    foreach ($aDiv as $id => $div) {
        initVar($div ['position']);
        putDivDimension($id, $div ['top'], $div ['left'], $div ['width'], $div ['height'], $div ['position']);
    }
}

function putDivDimension($id, $top, $left, $width, $height, $position = "")
{
    echo "#".$id." {position:".($position ? $position : "absolute").";top:".$top."px;left:".$left."px;width:".$width.($width == "100%" ? "" : "px;").";height:".$height."px;}\n";
}

?>

#divrubrique1, #divmedia1 { height: <?=($aDiv ['frame_left_middle'] ['height'] - 40)?>px; padding: 5px 5px 5px 5px; }

.onglet_centre {border-bottom: 1px solid #002D96; cursor: pointer; height: <?=31 - $firefox?>px; padding-top: 7px; text-align: center; font-weight: bold;}

#PAGE_ID_DIV {outline-offset:-100px;}
