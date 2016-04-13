{$doctype}
<!--[if lt IE 9]><html class="ie ie8"><![endif]-->
<!--[if IE 9]><html class="ie ie9"><![endif]-->
<!--[if gt IE 9]><!--><html {if $lang}lang="{$lang}"{/if}><!--<![endif]-->
    <head>
        {$header}
        {literal}<script>(function(){ var html = document.getElementsByTagName('html')[0]; html.className = html.className + ' no-js'; })();</script>{/literal}
    </head>
    <body class="{$page_skin} desktop cookiesAccepted" {if $page_skin eq 'ds'}style="padding-right:0px;"{/if}>
        {$gtmTag}
        {if !isset($smarty.get.popin) }
            {if $showScrollIncite}<div class="arrowBottom sliceNew" ><div class="arrowBottomIn"></div></div>
<style type="text/css">
    .arrowBottomIn{literal}{
        background:{/literal}{$aShowroom.SECOND_COLOR}{literal}!important;
        }{/literal}
</style>
            {/if}
        {/if}

        <div class="{if !isset($smarty.get.popin)}container{/if} {if isset($smarty.get.popin)}container-popin{/if}">
			{if $outilszoneweb neq true && $biFrameDs neq true}
				{include file="$sTemplateOutils"}
			{/if}
            {$body}
			

            <!-- AddThis Scripts -->
            <script type="text/template" id="shareTpl">
                {$shareTpl}
            </script>
            {if !isset($smarty.get.popin)}
                <!-- addthis -->
				{literal}
				<script type="text/javascript">
				window.onload = function()
				{var f=document.getElementsByTagName("script")[0], j=document.createElement("script"); j.src='//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-521f4a58354d5213'; f.parentNode.insertBefore(j,f);}
				;
				</script>
				<!-- addthis -->
				{/literal}
            {/if}
        </div>
        <script type="text/javascript">var LoadingKey = '{'LOADING_CONTENT'|t}'</script>
        {$footer}
        {if $cookieType == "2"}
            {literal}
            	<script type="text/javascript">
			var counter=0;
			var thisInt=setInterval(function(){
			counter++;
			if(counter>100) { clearInterval(thisInt);}
			if(document.getElementById('_evh-ric-c'))
			{
				document.getElementById('_evh-ric-c').src='http://media.citroen.fr/image/28/9/BoutonFermer.216289.3.png';
				clearInterval(thisInt);
			}
			},10);
		</script>
                <script type="text/javascript">
                  (function() {
                                var ev = document.createElement('script'); ev.type = 'text/javascript'; ev.async = true; ev.setAttribute('data-ev-tag-pid', {/literal}{$pid}{literal}); ev.setAttribute('data-ev-tag-ocid', {/literal}{$cid}{literal}); 
                                ev.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'c.betrad.com/pub/tag.js';
                                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ev, s);
                  })();
                </script>
                <script type="text/javascript">
                  (function() {
                                var hn = document.createElement('script'); hn.type = 'text/javascript'; hn.async = true; hn.setAttribute('data-ev-hover-pid', {/literal}{$pid}{literal}); hn.setAttribute('data-ev-hover-ocid', {/literal}{$cid}{literal});
                                hn.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'c.betrad.com/geo/h1.js';
                                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(hn, s);
                  })();
                </script>
            {/literal}
        {/if}
        {if $footerJS}
        <script type="text/javascript">
            {$footerJS}
        </script>
        {/if}
    </body>
</html>
