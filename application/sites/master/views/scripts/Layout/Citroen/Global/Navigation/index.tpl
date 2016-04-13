            	<nav>
                	<ul>
	{section name=index loop=$navigation}
                    	<li><a {if $navigation[index].menu.target != ""} target="{$navigation[index].menu.target}"{/if} href="{$navigation[index].menu.url}">{$navigation[index].menu.lib}</a></li>
	{/section}
                    </ul>
                </nav>
