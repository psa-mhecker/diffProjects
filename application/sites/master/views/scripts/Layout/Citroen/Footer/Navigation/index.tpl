	{section name=index loop=$navigation}
          		<div class="bloc">
                	<h5><a href="{$navigation[index].menu.url}">{$navigation[index].menu.lib}</a></h5>
		{if $navigation[index].ssmenu}
                    	<ul>
			{section name=index2 loop=$navigation[index].ssmenu}
                    <li><a {if $navigation[index].ssmenu[index2].target != ""} target="{$navigation[index].ssmenu[index2].target}"{/if} href="{$navigation[index].ssmenu[index2].url}">{$navigation[index].ssmenu[index2].lib}</a></li>
			{/section}
                        </ul>
		{/if}
                </div>
	{/section}
