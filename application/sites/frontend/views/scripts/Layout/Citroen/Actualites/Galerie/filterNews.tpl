{if $aActualites|@sizeof > 0}
	{include file="{$sIncludeTplPath}/moreNews.tpl"}
{else}
	{'PAS_DE_RESULTATS_FILTRE'|t}
{/if}
