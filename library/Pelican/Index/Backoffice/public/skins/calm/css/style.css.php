<?php
/**
 * @ignore
 */
header ( "Content-type: text/css" );
include_once ('config.php');
getAppVersion ();

initVar ( $popup );
initVar ( $popup_content );
initVar ( $popup_media );
initVar ( $tmp );
initVar ( $firefox2 );
initVar ( Pelican::$config ['ENV'] ['LOCAL'] ['NAVIGATOR'] ['firefox'] );

$page = $_GET ['page'];

if ($page == "_/Media/popup" || basename ( $page ) == 'popup_media.php') {
	$popup_media = true;
}

if ($page == "_/Popup/content" || basename ( $page ) == 'popup_content.php') {
	$popup_content = true;
}

if ($popup) {
	$window_width = 650;
	$window_height = 350;
} elseif ($popup_content) {
	$window_width = 965;
	$window_height = 420;
} else {
	$window_width = $_SESSION ["screen_width"] - $tmp;
	$window_height = $_SESSION ["screen_height"] - $tmp;
}
$window_margin = 20;
$window_correction = 25;
$main_margin = 10;
$bouton_height = 15;

$firefox = 0;
if (! Pelican::$config ['ENV'] ['LOCAL'] ['NAVIGATOR'] ['ie']) {
	$firefox = 6;
	$firefox2 = 5;
}


$aDiv ['div_header'] ['top'] = 0;
$aDiv ['div_header'] ['left'] = 0;
$aDiv ['div_header'] ['width'] = $window_width;
$aDiv ['div_header'] ['height'] = 50;

$aDiv ['div_main'] ['top'] = $aDiv ['div_header'] ['height'];
$aDiv ['div_main'] ['left'] = 0;
$aDiv ['div_main'] ['width'] = $aDiv ['div_header'] ['width'];
$aDiv ['div_main'] ['height'] = $window_height - 6 * $main_margin - 3 * $firefox2;

$aDiv ['div_onglet'] ['top'] = $aDiv ['div_header'] ['height'];
$aDiv ['div_onglet'] ['left'] = 0;
$aDiv ['div_onglet'] ['width'] = $aDiv ['div_main'] ['width'];
$aDiv ['div_onglet'] ['height'] = 28;

$aDiv ['div_content'] ['top'] = $aDiv ['div_onglet'] ['height'];
$aDiv ['div_content'] ['left'] = 0;
$aDiv ['div_content'] ['width'] = $aDiv ['div_main'] ['width'] - $main_margin / 2;
$aDiv ['div_content'] ['height'] = $aDiv ['div_main'] ['height'] - $aDiv ['div_header'] ['height'] + $main_margin;

$aDiv ['div_footer'] ['top'] = 0;
$aDiv ['div_footer'] ['left'] = 0;
$aDiv ['div_footer'] ['width'] = 0;
$aDiv ['div_footer'] ['height'] = 0;

$aDiv ['frame_left_top'] ['top'] = $main_margin;
$aDiv ['frame_left_top'] ['left'] = $main_margin;
$aDiv ['frame_left_top'] ['width'] = 210;
$aDiv ['frame_left_top'] ['height'] = 18;

$aDiv ['frame_left_middle'] ['top'] = $aDiv ['frame_left_top'] ['top'] + $aDiv ['frame_left_top'] ['height'] + $main_margin;
$aDiv ['frame_left_middle'] ['left'] = 2 + $main_margin; //$aDiv['frame_left_top']['left'];
$aDiv ['frame_left_middle'] ['width'] = $aDiv ['frame_left_top'] ['width'];
$aDiv ['frame_left_middle'] ['height'] = $aDiv ['div_content'] ['height'] - $aDiv ['frame_left_top'] ['height'] - 3 * $main_margin - $bouton_height - 3 * $firefox;

$aDiv ['frame_left_bottom'] ['top'] = $aDiv ['frame_left_middle'] ['top'] + $aDiv ['frame_left_middle'] ['height'] + $main_margin + $firefox;
$aDiv ['frame_left_bottom'] ['left'] = $main_margin; //$aDiv['frame_left_top']['left'];
$aDiv ['frame_left_bottom'] ['width'] = $aDiv ['frame_left_top'] ['width'];
$aDiv ['frame_left_bottom'] ['height'] = $bouton_height;

$aDiv ['frame_right_top'] ['top'] = $aDiv ['frame_left_top'] ['top'];
$aDiv ['frame_right_top'] ['left'] = $aDiv ['frame_left_top'] ['left'] + $aDiv ['frame_left_top'] ['width'] + $main_margin;
$aDiv ['frame_right_top'] ['width'] = $aDiv ['div_content'] ['width'] - 3 * $main_margin - $aDiv ['frame_left_top'] ['width'];
$aDiv ['frame_right_top'] ['height'] = $aDiv ['frame_left_top'] ['height'];

$aDiv ['frame_right_middle'] ['top'] = $aDiv ['frame_left_middle'] ['top'];
$aDiv ['frame_right_middle'] ['left'] = 2 + $aDiv ['frame_right_top'] ['left'];
$aDiv ['frame_right_middle'] ['width'] = $aDiv ['frame_right_top'] ['width'];
$aDiv ['frame_right_middle'] ['height'] = $aDiv ['frame_left_middle'] ['height'];

$aDiv ['frame_right_bottom'] ['top'] = $aDiv ['frame_left_bottom'] ['top'];
$aDiv ['frame_right_bottom'] ['left'] = $aDiv ['frame_right_top'] ['left'];
$aDiv ['frame_right_bottom'] ['width'] = $aDiv ['frame_right_top'] ['width'];
$aDiv ['frame_right_bottom'] ['height'] = $aDiv ['frame_left_bottom'] ['height'];

$aDiv ['iframeRight'] ['top'] = 0;
$aDiv ['iframeRight'] ['left'] = 0;
$aDiv ['iframeRight'] ['width'] = $aDiv ['frame_right_middle'] ['width'] - 2 + $firefox;
$aDiv ['iframeRight'] ['height'] = $aDiv ['frame_right_middle'] ['height'] - 2 + $firefox;

/***********************************/

$aDiv ['tree'] ['position'] = 'relative';
$aDiv ['tree'] ['top'] = 0;
$aDiv ['tree'] ['left'] = 0;
$aDiv ['tree'] ['width'] = $aDiv ['frame_left_top'] ['width'] - $main_margin;
$aDiv ['tree'] ['height'] = $aDiv ['frame_left_middle'] ['height'] - $main_margin;

$aDiv ['properties'] = $aDiv ['tree'];

$aDiv ['div_fieldset'] = $aDiv ['div_content'];
$aDiv ['div_fieldset'] ['top'] = 0;
$aDiv ['div_fieldset'] ['height'] += $main_margin;

$aDiv ['div_popup_footer'] ['top'] = $aDiv ['div_content'] ['top'] + $aDiv ['div_content'] ['height'] + $main_margin;
$aDiv ['div_popup_footer'] ['left'] = $aDiv ['div_content'] ['left'];
$aDiv ['div_popup_footer'] ['width'] = $aDiv ['div_content'] ['width'];
$aDiv ['div_popup_footer'] ['height'] = $aDiv ['frame_left_bottom'] ['height'];

/***********************************/

echo putDivStyle ( $aDiv );

//include("html.css");
//include("framework.css");
//include("bo.css");


function putDivStyle($aDiv) {
	foreach ( $aDiv as $id => $div ) {
		initVar ( $div ['position'] );
		putDivDimension ( $id, $div ['top'], $div ['left'], $div ['width'], $div ['height'], $div ['position'] );
	}
}

function putDivDimension($id, $top, $left, $width, $height, $position = "") {
	echo "#" . $id . " {position:" . ($position ? $position : "absolute") . ";top:" . $top . "px;left:" . $left . "px;width:" . $width . ($width == "100%" ? "" : "px;") . ";height:" . $height . "px;}\n";
}

?>