{if $aNavigation}
<div id="{$content_zt_id}" class="portal-moveable-content">
	<h3>Liste de liens</h3>
	<ul>
	{section name=index loop=$aNavigation}
		<li><a href="{urlParser url=$aNavigation[index].menu.url}">{$aNavigation[index].menu.lib}</a></li>
	{/section}
	</ul>
</div>
{/if}