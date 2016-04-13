                <div id="QuickAccess">
                	<ul>
	{section name=index loop=$navigation}
                    	<li {if $navigation[index].menu.id == 3} class="customer"{/if}><a {if $navigation[index].menu.target != ""} target="{$navigation[index].menu.target}"{/if} href="{$navigation[index].menu.url}">{$navigation[index].menu.lib}</a></li>
	{/section}
                    </ul>
                </div>
