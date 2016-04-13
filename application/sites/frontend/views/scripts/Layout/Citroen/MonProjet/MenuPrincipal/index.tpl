<div id="{$aParams.ID_HTML}" class="projectbar full">
	<div class="inner">
		<ul>
			{foreach from=$aMenu item=menu}
			{if $menu.PAGE_ID == $aParams.pid}
			<li class="on"><a href="{urlParser url=$menu.PAGE_CLEAR_URL}"><span>{$menu.PAGE_TITLE_BO}</span></a></li>
			{else}
			<li><a href="{urlParser url=$menu.PAGE_CLEAR_URL}"><span>{$menu.PAGE_TITLE_BO}</span></a></li>
			{/if}
			{/foreach}
		</ul>
	</div>
</div>