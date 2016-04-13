{if $img != ''}
<center><ul>
{section name=index loop=$img}
<li><a href="{urlParser url=$img[index].url}" target=""><img src="{$img[index].image}" border="0" width="50%"/><br />{$img[index].lib}</a></li>
{/section}
</ul></center>
{else}
{literal}
<script type="text/javascript">
			var flashvars = {};
			flashvars.folderPath = "/library/External/flashxml/cover-flow/";
			flashvars.settingsXML = "settings2.xml";
			var params = {};
			params.scale = "noscale";
			params.salign = "tl";
			params.wmode = "transparent";
			params.allowscriptaccess = "samedomain";
			var attributes = {};
			swfobject.embedSWF("/library/External/flashxml/cover-flow/CoverFlowFX.swf", "CoverFlowFXDiv", "450", "300", "9.0.0", false, flashvars, params, attributes);
		</script>
{/literal}
</head>
<body>
<div id="CoverFlowFXDiv"><a href="http://www.adobe.com/go/getflashplayer">
<img
	src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif"
	alt="Get Adobe Flash player" /> </a></div>
{/if}