<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
{$header}
</head>
<body id="body_main">
   <script type="text/javascript">getResolution();</script>
   <div id="div_main">
	   <div id="div_content">
			<div id="frame_left_top">{$title}</div>
			<div id="frame_left_middle">{$left_middle}</div>
			<div id="frame_left_bottom">{$left_bottom}</div>

			<div id="frame_right_top">&nbsp;</div>
			<div id="frame_right_middle">{$right_middle}</div>
			<div id="frame_right_bottom">{$right_bottom}</div>
		</div>
	    <div id="div_footer">{$footer}</div>
	</div>
   <div id="div_header">{$top}</div>
   <div id="div_onglet">{$tab}</div>
{$default}
<form action="" method="post" name="fSite" id="fSite">
<input type="hidden" name="SITE_ID" value="" />
</form>
<iframe id="div_popup_iframe" frameborder="0" src="about:blank" style="position: absolute; display: none; top: 100px; left: 200px; width: 600px; height: 400px;"></iframe>
<div id="div_popup" style="position: absolute; top: 100px; left: 200px; width: 600px; height: 400px; background-color: white; border: 1px solid black; display: none;"><!-- Layer à appeler librement dans les gabarits pour afficher des informations en AJAX --></div>
{$footer}
</body>
</html>

{literal}
<script>
  $( "div#img_resize_plus" ).click(function() {
   var divheight =  $("div#frame_left_middle").height();
   $("div#frame_left_middle").css({ height: divheight,'z-index':'1','overflow':'auto','width':'380px'});
   $("div#img_resize_moins").show();
   $("div#img_resize_plus").hide();
   
  })
  
  $( "div#img_resize_moins" ).click(function() {
   $("div#frame_left_middle").removeAttr('style');
   $("div#frame_left_middle" ).scrollLeft(0);
   $("div#img_resize_moins").hide();
   $("div#img_resize_plus").show();
   
  })  
	
  $('#iframeRight').load(function(){
      var iframe = $('#iframeRight').contents();
      iframe.click(function(){
	  if(typeof($("div#frame_left_middle").attr('style')) !== 'undefined'){
           $("div#frame_left_middle").removeAttr('style');
		   $("div#img_resize_moins").hide();
		   $("div#img_resize_plus").show();
		}
      });
   });
  
</script>
{/literal}
