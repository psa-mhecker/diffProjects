<?
?>
<style type="text/css">
.container {
	width: <?=$imageSize[0]?>px;
	height: <?=$imageSize[1]?>px;
	background-image: url(<?=$fileUrl?>);
	background-repeat: no-repeat;
	background-position: 0% 0%;
	text-align: left;
}

.selection {
	position: relative;
	cursor: pointer;
	width: <?=$imageSize[0]?>px;
	height: <?=$imageSize[1]?>px;
	top: 0px;
	left: 0px;
	border-style: dotted;
	border-color: red;
	border-width: 1px;
}

.resizeMe {
	position: absolute;
	
	opacity:0.60; /* firefox, opera, safari, chrome */
    -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(opacity=560)"; /* IE 8 */
    filter:alpha(opacity=60); /* IE 4, 5, 6 and 7 */
    
	background-color: <?=$opacityColor?>;
	overflow: hidden;
	left: 0;
	top: 0;
	width: 0;
	height: 0;
	z-index: 12;
}
</style>