<?php

class Backoffice_Media_Helper
{
    public static function rightMiddle()
    {
        
	$return = '
	<iframe style="overflow:hidden" src="/_/Media_Navigation/filter?'.$_SERVER["QUERY_STRING"].'" name="filtre" id="filtre" width="50%" height="30" frameborder="0" scrolling="Auto" margin="0" align="top"></iframe>
	<iframe style="overflow:hidden" src="/_/Media_Navigation/list?media=true&'.str_replace("&lib=".rawurlencode($_GET["lib"]),"",str_replace("&lib=".$_GET["lib"],"",$_SERVER["QUERY_STRING"])).'" width="50%" name="media" id="media" frameborder="0" scrolling="Auto" margin="0" padding="0" align="top"></iframe>
	<iframe style="overflow:hidden" src="/_/Media_Navigation/properties?action=add&view='.$_GET["type"].'&type=file&root='.$_GET["root"].'&initial='.$_GET["root"].'&zone=media" width="50%" height="100%" name="properties" id="properties" frameborder="0" scrolling="Auto" margin="0" padding="0" align="top"></iframe>
	<script type="text/javascript">
		var w = document.getElementById("filtre").parentNode.offsetWidth;
		var h = document.getElementById("filtre").parentNode.offsetHeight;
		document.getElementById("filtre").width = w/2 -3;
		document.getElementById("media").width = w/2 -3;
		document.getElementById("media").height = h - 36;
		document.getElementById("properties").width = w/2 -3;
		document.getElementById("properties").height = h -6;
	</script>
	';
    
	return $return;
    }
}
