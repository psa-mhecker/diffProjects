{foreach from=$aSearch item=result name=listResults}
	<div class="caption item zoner">
        {if $result.title}<h2 class="parttitle"><a href="{urlParser url=$result.url}" {if $result.mode_ouverture==2}target='_blank'{/if}>{$result.title}</a></h2>{/if}
        {if $result.desc}<p>{$result.desc}</p>{/if}
	</div>
{/foreach}