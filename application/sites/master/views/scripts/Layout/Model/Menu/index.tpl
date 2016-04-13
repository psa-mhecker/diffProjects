<div class="art-nav">
                	<div class="l"></div>
                	<div class="r"></div>
                	<ul class="art-menu">
	{section name=index loop=$navigation}
                		<li><a href="{$navigation[index].menu.url}" target="{$navigation[index].menu.target}" {if $navigation[index].menu.selected}class="active"{/if}><span class="l"></span><span class="r"></span><span class="t">{$navigation[index].menu.lib}</span></a>
		{if $navigation[index].ssmenu}
                			<ul>
			{section name=index2 loop=$navigation[index].ssmenu}
                				<li><a href="{$navigation[index].ssmenu[index2].url}" target="{$navigation[index].ssmenu[index2].target}">{$navigation[index].ssmenu[index2].lib}</a>
				{if $navigation[index].ssmenu[index2].ssmenu}
                					<ul>
					{section name=index3 loop=$navigation[index].ssmenu[index2].ssmenu}
                						<li><a href="{$navigation[index].ssmenu[index2].ssmenu[index3].url}" target="{$navigation[index].ssmenu[index2].ssmenu[index3].target}">$navigation[index].ssmenu[index2].ssmenu[index3].lib}</a></li>
					{/section}</ul>
					{/if}</li>
             {/section}</ul>
		{/if}</li>		
	{/section}</ul>
</div>