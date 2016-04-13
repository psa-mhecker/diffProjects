                <div id="SocialFooter">
                	<h5><span>Nous suivre</span></h5>
                	<ul>
	{section name=index loop=$navigation}
                    <li class="{$navigation[index].menu.lib|lower}"><a {if $navigation[index].menu.target != ""} target="{$navigation[index].menu.target}"{/if} href="{$navigation[index].menu.url}"><span>{$navigation[index].menu.lib}</span></a></li>
	{/section}
                    </ul>
                </div>