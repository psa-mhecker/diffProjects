{*if $aContenu.CONTENT_DISPLAY_SHARE*} 
<!-- 
<link href="{$mediaPath}/css/bookmark.css" media="screen" rel="stylesheet" type="text/css" />
<link href="{$mediaPath}/css/bookmark_sprite.css" media="screen" rel="stylesheet" type="text/css" />
<ul class="plugin_bookmark">
{foreach from=$aBookmarks key=k item=v}
<li><a href="{$v}" target="_blank"><img src="{$mediaPath}images/pixel.gif" width="16" height="16" class="plugin_bookmark_sprite plugin_bookmark_{$k|lower|replace:'-':''|replace:' ':''|replace:'.':''}" alt="{$k}" title="{$k}" />{if $showLabel}&nbsp;{$k}&nbsp;{/if}</a></li>
{/foreach}
</ul>

var a2a_config = a2a_config || {};
a2a_config.prioritize = ["friendfeed", "amazon_wish_list", "reddit", "slashdot"];
-->
<div class="a2a_kit a2a_default_style">
   {if $like}
    {section name=index loop=$like}
    <a class="a2a_button_{$like[index]}"></a>
	{/section}
    <br />
    <br />    {/if}
    
     {if $share}
    {section name=index loop=$share}
    <a class="a2a_button_{$share[index]}"></a>
	{/section}
    <br />
    <br />    {/if}
   	<a class="a2a_dd" href="http://www.addtoany.com/share_save">
        <img src="http://static.addtoany.com/buttons/share_save_120_16.gif" border="0" alt="Share"/>
    </a>
</div>

<a class="a2a_dd" href="{$aBookmarks.AddToAny}" target="_blank">
<!-- <img src="{$mediaPath}images/addtoany.png" width="16" height="16" border="0" alt="Share/Save/Bookmark"/>
&nbsp;Plus de choix...</a>
<script type="text/javascript">a2a_linkname="%title%";a2a_linkurl="%permalink%";a2a_num_services=22;</script>-->
<script type="text/javascript">
var a2a_config = a2a_config || {};
a2a_config.onclick = 1;
a2a_config.locale = "fr";
{if $prioritize}
a2a_config.prioritize = [{$prioritize}];
{/if}
</script>
<script type="text/javascript" src="http://static.addtoany.com/menu/page.js"></script>
{*/if*}