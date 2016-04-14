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
   {literal}
       <script>
           $(document).ready(function(){
               var $plus  = $( "div#img_resize_plus" );
               var $moins = $("div#img_resize_moins");
               var $menu  = $("div#frame_left_middle");

               $plus.click(function() {
                   var width= $menu.get(0).scrollWidth;
                   $menu.css({'z-index':'1','overflow':'auto','width':width+30});
                   $moins.show();
                   $plus.hide();
               })


               $moins.click(function() {
                   $menu.removeAttr('style').scrollLeft(0);
                   $moins.hide();
                   $plus.show();

               })

               $('#iframeRight').load(function(){
                   var iframe = $('#iframeRight').contents();
                   iframe.click(function(){
                       if(typeof($menu.attr('style')) !== 'undefined'){
                           $menu.removeAttr('style');
                           $moins.hide();
                           $plus.show();
                       }
                   });
               });
           });



       </script>
   {/literal}
</body>
</html>
