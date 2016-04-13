<?php
	header("Content-type: text/css");
	$popup = true;
	if (isset($_GET["media"])) {
		$media = $_GET["media"];
	}
	include_once('config.php');
	pelican_import('Index');
	Pelican::$frontController = Pelican_Factory::getInstance('Index',false);
	Pelican::$frontController->setBackofficeSkin(Pelican::$config["SKIN"], "/library/Pelican/Index/Backoffice/public/skins", Pelican::$config["DOCUMENT_INIT"], "screen,print");
	include(Pelican::$config["DOCUMENT_INIT"].Pelican::$frontController->skinPath."/css/style.css.php");
	include(Pelican::$config["DOCUMENT_INIT"].Pelican::$frontController->skinPath."/css/pelican.css");
?>

.nomargin { margin: 0 0 0 0;}
/*input.text, input.txt, select, textarea { background-color: #FFFFFF; border: 1px solid #8B8B8B; }*/
table.cadre { background-color: #FFFFFF; border: 1px solid; border-color: #000000; }
table.color { border: 1px solid Black; }
tr.e404 { color: #FF0000; }
.a, .s { border-bottom: #8B8B8B solid 1px; border-left: #8B8B8B solid 1px; border-right: #8B8B8B solid 1px; border-top: #8B8B8B solid 1px; filter: progid:DXImageTransform.Microsoft.Gradient(gradientType=0,startColorStr=#f9f9f9,endColorStr=#CACACA); height: 18px; width: 18px; }
.bottom { margin: 10px; text-align: center; }
.but { background-color: #B5B5B5; border-bottom: #787778 1px solid; border-left: #B5B5B5 1px solid; border-right: #787778 1px solid; border-top: #B5B5B5 1px solid; }
.center { text-align: center; }
.gradcontainer { border: 1px inset window; font-size: 1; height: 6; margin-left: 5px; overflow: hidden; position: absolute; width: 140; z-index: 4; }
.line { filter: alpha(style=1); height: 6; overflow: hidden; width: 139; z-index: 0; }
.linecontainer { height: 6; margin-left: 5px; position: absolute; width: 139; z-index: 0; }
.outerslidecontainer { border: 0px; margin-left: 0; width: 150; }
.pointer { cursor: pointer; }
.right { text-align: right; }
.s { font-family: Symbol; }
.sliderhandle { border: 0 outset #FFFFFF; cursor: hand; height: 14; overflow: hidden; width: 11; z-index: 5; }
.sliderhandle img	{ height: 14; width: 11; }
.title { border-bottom: 1px solid #8B8B8B; color: #000000; color: #8B8B8B; font-weight: bold; text-align: left; }
.txt, select { border: 1px inset window; margin: 0px; padding: 0px; }
.txt80 { border: 1px inset window; margin: 0px; padding: 0px; width: 80px; }
#colorbox { border: 1 inset window; height: 50px; margin-left: 2px; width: 25px; }
#colorbox2 { border: 1 inset window; height: 10; margin-left: 2px; vertical-align: middle; width: 10; }
#colorimage { border: 1px inset window; cursor: hand; height: 20; vertical-align: middle; width: 164; }
#scroll-container {
	height: 400px;
	overflow: -moz-scrollbars-vertical;
	overflow-x: hidden;
	overflow-y: auto;
	position: relative;
	width: 400px;
}

#thelist2 {
	border: #CCCCCC 1px solid;
	font-family: Arial, sans-serif;
	font-size: 13px;
	list-style-type: none;
	margin: 4px;
	padding: 4px;
	width: 380px;
}

#thelist2 li {
	background: #FFFFBB;
	background-color: #EEEEEE;
	border: #CCCCCC 1px solid;
	cursor: move;
	cursor: move;
	margin: 2px;
	padding: 2px;
	text-align:left;
}

#thelist2 li.selected {
	background: #FFFFBB;
	background-color: #ffffff;
	border: #CCCCCC 1px solid;
	cursor: move;
	cursor: move;
	margin: 2px;
	padding: 2px;
	text-align:left;
}