<div id="swf{$media.MEDIA_ID}">

</div>

<script type="text/javascript">
	var so = new SWFObject("{$pelican_config.MEDIA_HTTP}/{$media.MEDIA_PATH}", "object{$media.MEDIA_ID}","264" ,"211", "7", "#ffffff");
	so.addParam("quality","high");
	so.addParam("allowScriptAccess","always");
	so.addParam("bgcolor","#999999");
	so.write("swf{$media.MEDIA_ID}");
</script>